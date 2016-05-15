<?php

return new \Phalcon\Config([
    'application' => [
        'controllersDir'   => __DIR__ . '/../controllers/',
        'collectionsDir'   => __DIR__ . '/../collections/',
        'migrationsDir'    => __DIR__ . '/../migrations/',
        'viewsDir'         => __DIR__ . '/../views/',
        'cacheDir'         => __DIR__ . '/../cache/',
        'baseUri'          => '/'
    ]
]);
