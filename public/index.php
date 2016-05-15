<?php

use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('APP_PATH', realpath('..'));

try {

    /**
     * Load vendor autoloaders
     */
    require __DIR__ . '/../vendor/autoload.php';

    /**
     * Read the configuration
     */
    $config = include APP_PATH . '/apps/frontend/config/config.php';

    /**
     * Include enfironment params
     */
    require __DIR__ . '/../config/environment.php';

    /**
     * Include services
     */
    require __DIR__ . '/../config/services.php';

    /**
     * Handle the request
     */
    $application = new Application($di);

    /**
     * Include modules
     */
    require __DIR__ . '/../config/modules.php';

    /**
     * Include routes
     */
    require __DIR__ . '/../config/routes.php';

    echo $application->handle()->getContent();
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
