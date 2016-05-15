<?php

/**
 * Register application modules
 */
$application->registerModules([
    'web' => [
        'className'   => 'NewsServer\Frontend\Module',
        'path'        => __DIR__ . '/../apps/frontend/Module.php'
    ]
]);
