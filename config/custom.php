<?php

return [
    'transforms' => [
        'default' => [
            'width' => 800,
            'srcset' => [400, 800, 1200, 1600, 2400],
            'sizes' => '100vw',
        ],
        'avatar' => [
            'width' => 128,
            'srcset' => [64, 128, 192],
            'sizes' => '64px',
        ],
        'section:none' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400, 2800],
            'sizes' => '100vw',
        ],
        'section:none:columns:2' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1280px) 742px, (min-width: 1024px) 384px, 100vw',
        ],
        'section:none:text-and-media' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1280px) 742px, (min-width: 1024px) 384px, 100vw',
        ],
        'section:contain:text-and-media' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1280px) 584px, (min-width: 1024px) 296px, 100vw',
        ],
        'section:contain' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400, 2800],
            'sizes' => '(min-width: 1280px) 1216px, (min-width: 1024px) 960px, 100vw',
        ],
        'section:contain:columns:2' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1280px) 584px, (min-width: 1024px) 584px, 100vw',
        ],
        'section:contain:columns:3' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1024px) 373px, 100vw',
        ],
        'section:none:steps' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1024px) 33vw, 100vw',
        ],
        'section:contain:steps' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1280px) 389px, (min-width: 1024px) 304px, 100vw',
        ],
        'hero' => [
            'width' => 640,
            'srcset' => [640, 960, 1280, 1920, 2560],
            'sizes' => '(min-width: 1024px) 50vw, 100vw',
        ],
        'image-inline' => [
            'width' => 96,
            'srcset' => [96, 192],
            'sizes' => '96px',
        ],
        'logo' => [
            'width' => 160,
            'srcset' => [160, 320, 480],
            'sizes' => '160px',
        ],
    ],
];
