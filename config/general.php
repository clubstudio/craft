<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 */

use craft\helpers\App;

return [
    // Global settings
    '*' => [
        // Allow updates?
        'allowUpdates' => false,

        // Default Week Start Day (0 = Sunday, 1 = Monday...)
        'defaultWeekStartDay' => 0,

        // Enable CSRF Protection (recommended, will be enabled by default in Craft 3)
        'enableCsrfProtection' => true,

        // Whether "index.php" should be visible in URLs
        'omitScriptNameInUrls' => true,

        // Error templates path
        'errorTemplatePrefix' => "_errors/",

        // Control panel trigger word
        'cpTrigger' => 'cms',

        // The secure key Craft will use for hashing and encrypting data
        'securityKey' => App::env('SECURITY_KEY'),

        // Base site URL
        'siteUrl' => App::env('SITE_URL'),

        // Default timezone
        'timezone' => 'Europe/London',

        // Whether to save the project config out to config/project.yaml
        // (see https://docs.craftcms.com/v3/project-config.html)
        'useProjectConfigFile' => false,
        
        // Whether an `X-Powered-By: Craft CMS` header should be sent, helping
        // services like BuiltWith identify that the site is running on Craft.
        'sendPoweredByHeader' => false,
    ],

    // Dev environment settings
    'dev' => [
        // Allow updates?
        'allowUpdates' => true,

        // Dev Mode (see https://craftcms.com/guides/what-dev-mode-does)
        'devMode' => true,

        // Prevent crawlers from indexing pages and following links
        'disallowRobots' => true,        
    ],

    // Staging environment settings
    'staging' => [
        // Set this to `false` to prevent administrative changes from being made on staging
        'allowAdminChanges' => false,

        // Prevent crawlers from indexing pages and following links
        'disallowRobots' => true,        
    ],

    // Production environment settings
    'production' => [
        // Set this to `false` to prevent administrative changes from being made on production
        'allowAdminChanges' => false,
    ],
];
