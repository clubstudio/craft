<?php

return [
    'transforms' => [
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
    ]
];
