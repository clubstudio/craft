<?php

/**
 * Site URL Rules
 *
 * You can define custom site URL rules here, which Craft will check in addition
 * to routes defined in Settings → Routes.
 *
 * Read about Craft’s routing behavior (and this file’s structure), here:
 * @link https://craftcms.com/docs/5.x/system/routing.html
 */

use craft\helpers\App;

return App::devMode() ? [
    // The style guide renders each component with sample values. It's only routed in dev mode.
    'styleguide' => ['template' => '_styleguide/index'],
] : [];
