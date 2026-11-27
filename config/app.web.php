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
            return;
        }

        // In dev, when the site is opened at a host other than PRIMARY_SITE_URL's (shared with
        // `ddev share`, for example), use that host for the site's URLs so links and assets work there.
        $hostInfo = Craft::$app->getRequest()->getHostInfo();
        if ($hostInfo && $hostInfo !== rtrim(App::env('PRIMARY_SITE_URL') ?? '', '/')) {
            $_SERVER['PRIMARY_SITE_URL'] = $hostInfo;
            Craft::setAlias('@web', $hostInfo);

            // The asset manager has already resolved its base URL from the old `@web`, so the
            // control panel's resources would still load from PRIMARY_SITE_URL.
            Craft::$app->getAssetManager()->baseUrl = Craft::getAlias(Craft::$app->getConfig()->getGeneral()->resourceBaseUrl);
        }
    },
];
