<?php

use craft\helpers\App;

$host = Craft::$app->getRequest()->getIsConsoleRequest()
    ? App::env('PRIMARY_SITE_URL')
    : Craft::$app->getRequest()->getHostInfo();

return [
    'useDevServer' => App::env('CRAFT_ENVIRONMENT') === 'dev',
    'devServerPublic' => $host . ':5173',
    'serverPublic' => '/dist/',
    'manifestPath' => '@webroot/dist/.vite/manifest.json',
];