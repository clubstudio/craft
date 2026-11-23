<?php

/**
 * Yii Web Application Config
 *
 * Merged with config/app.php for web requests only.
 *
 * @link https://craftcms.com/docs/5.x/reference/config/app.html
 */

use craft\helpers\App;

return [
    // Craft's built-in MCP server is enabled in project config so new projects
    // can connect as soon as they're installed, but it's only for local
    // development. Outside the `dev` environment, switch it off for the request
    // so its endpoints 404 like any other missing page.
    'on beforeRequest' => function() {
        if (App::env('CRAFT_ENVIRONMENT') !== 'dev') {
            Craft::$app->get('mcp')->getSettings()->enabled = false;
        }
    },
];
