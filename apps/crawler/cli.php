<?php

require __DIR__ . '/../../vendor/autoload.php';

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Events\Manager as EventManager;
use Phalcon\Mvc\Collection\Manager as CollectionManager;
use Dotenv\Dotenv;
use NewsServer\Common\Utils\NewsHandler;
use NewsCrawler\Utils\TaskHandler;

define('VERSION', '1.0.0');

// Using the CLI factory default services container
$di = new CliDI();

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__)));

/**
 * Tell it to register the tasks directory
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    [
        APPLICATION_PATH . '/tasks'
    ]
);

$loader->registerNamespaces(
    [
        'NewsServer\Common\Collections'   => __DIR__ . '/../common/collections/',
        'NewsServer\Common\Utils'         => __DIR__ . '/../common/utils/',
        'NewsCrawler\Utils'               => __DIR__ . '/utils'
    ]
);

$loader->register();

// Load Environment Config
$dotenv = new Dotenv(__DIR__ . '/../../config/');
$dotenv->load();
// Connecting to a domain socket, falling back to localhost connection
$di->set('mongo', function () {
    try {
        $mongo = new MongoClient(getenv('DBCONNECTION'));
        return $mongo->selectDB(getenv('DBNAME'));
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}, true);
$di->setShared('news', function () {
    $news = new NewsHandler();
    return $news;
});
$di->setShared('task', function () {
    $taskHandler = new TaskHandler();
    return $taskHandler;
});
$di->setShared('collectionManager', function () {
    return new CollectionManager();
});

// Create event manager
$em = new EventManager();

$em->attach("console:afterHandleTask", function ($event, $console) use ($di) {
    $di->get('task')->stop();
});

// Create a console application
$console = new ConsoleApp();
$console->setDI($di);
$console->setEventsManager($em);

/**
 * Process the console arguments
 */
$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

// Define global constants for the current task and action
define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    exit(255);
}
