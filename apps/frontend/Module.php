<?php

namespace NewsServer\Frontend;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Security;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use NewsServer\Common\Utils\NewsHandler;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'NewsServer\Frontend\Controllers'   => __DIR__ . '/controllers',
            'NewsServer\Frontend\Collections'   => __DIR__ . '/collections',
            'NewsServer\Frontend\Forms'         => __DIR__ . '/forms',
            'NewsServer\Frontend\Plugins'       => __DIR__ . '/plugins',
            'NewsServer\Common\Utils'           => __DIR__ . '/../common/utils',
            'NewsServer\Common\Collections'     => __DIR__ . '/../common/collections'
        ]);

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Read configuration
         */
        $config = include APP_PATH . "/apps/frontend/config/config.php";

        /**
         * Setting up the view component
         */
        $di->set('view', function () use ($config) {

            $view = new View();

            $view->setViewsDir($config->application->viewsDir);

            $view->registerEngines([
                '.volt' => function ($view, $di) use ($config) {

                    $volt = new Volt($view, $di);
                    $volt->setOptions(
                        [
                            'compiledPath'      => $config->application->cacheDir,
                            'compiledExtension' => ".compiled",
                            'compiledSeparator' => "_",
                            'stat'              => true,
                            'compileAlways'     => true
                        ]
                    );

                    $compiler = $volt->getCompiler();
                    $compiler->addFilter('hash', 'sha1');

                    return $volt;
                },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ]);

            return $view;
        });

        /**
         * Starts the session the first time some component requests the session service
         */
        $di->set('session', function () {
            $session = new SessionAdapter();
            $session->start();

            return $session;
        });

        /**
         * Register the session flash service with the Twitter Bootstrap classes
         */
        $di->set('flash', function () {
            return new Flash([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        });

        $di->set('security', function () {
            $security = new Security();

            // Set the password hashing factor to 12 rounds
            $security->setWorkFactor(12);

            return $security;
        }, true);

        $di->set('news', function () {
            $news = new NewsHandler();
            return $news;
        });
    }
}
