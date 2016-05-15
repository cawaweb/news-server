<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Collection\Manager as CollectionManager;
use NewsServer\Frontend\Plugins\SecurityPlugin;
use NewsServer\Frontend\Plugins\NotFoundPlugin;

/**
 * The FactoryDefault Dependency Injector automatically registers the right services to provide a full stack framework
 */
$di = new FactoryDefault();

/**
 * Registering a router
 */
$di->setShared('router', function () {
    $router = new Router();
    $router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
    $router->setDefaultModule('web');
    $router->setDefaultNamespace('NewsServer\Frontend\Controllers');

    return $router;
});

/**
 * The URL component is used to generate all kinds of URLs in the application
 */
$di->setShared('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('mongo', function () {
    try {
        $mongo = new MongoClient(getenv('DBCONNECTION'));
        return $mongo->selectDB(getenv('DBNAME'));
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
});

$di->setShared('collectionManager', function () {
    return new CollectionManager();
});

/**
* Set the default namespace for dispatcher
*/
$di->setShared('dispatcher', function () use ($di) {
    $dispatcher = new Phalcon\Mvc\Dispatcher();
    $dispatcher->setDefaultNamespace('NewsServer\Frontend\Controllers');

    // Create an events manager
    $eventsManager = new EventsManager();

    // Listen for events produced in the dispatcher using the Security plugin
    $eventsManager->attach('dispatch:beforeExecuteRoute', new SecurityPlugin);

    // Handle exceptions and not-found exceptions using NotFoundPlugin
    $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

    // Assign the events manager to the dispatcher
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});
