<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

use craft\config\GeneralConfig;
use craft\helpers\App;

return GeneralConfig::create()
    ->defaultWeekStartDay(1)
    ->omitScriptNameInUrls()
    ->preloadSingles()
    ->preventUserEnumeration()
    ->enableGql(false)
    ->errorTemplatePrefix('_errors/')
    ->aliases([
        '@webroot' => dirname(__DIR__) . '/web',
        '@svg' => dirname(__DIR__) . '/src/svg',
    ])
;
