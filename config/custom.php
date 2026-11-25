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
        'section:full-bleed' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400, 2800],
            'sizes' => '100vw',
        ],
        'section:full-bleed:columns:2' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1024px) calc((100vw - 1.5rem) / 2), 100vw',
        ],
        'section:full-bleed:content-and-media' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1024px) calc((100vw - 4rem) / 2), 100vw',
        ],
        'section:full-width:content-and-media' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 20rem) / 2), (min-width: 1280px) calc((100vw - 14rem) / 2), (min-width: 1024px) calc((100vw - 12rem) / 2), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400, 2800],
            'sizes' => '(min-width: 1536px) calc(100vw - 16rem), (min-width: 1280px) calc(100vw - 10rem), (min-width: 1024px) calc(100vw - 8rem), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width:columns:2' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 17.5rem) / 2), (min-width: 1280px) calc((100vw - 11.5rem) / 2), (min-width: 1024px) calc((100vw - 9.5rem) / 2), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width:columns:3' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 19rem) / 3), (min-width: 1280px) calc((100vw - 13rem) / 3), (min-width: 1024px) calc((100vw - 11rem) / 3), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-bleed:faqs' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1024px) calc((100vw - 1.5rem) / 3), 100vw',
        ],
        'section:full-width:faqs' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 17.5rem) / 3), (min-width: 1280px) calc((100vw - 11.5rem) / 3), (min-width: 1024px) calc((100vw - 9.5rem) / 3), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:medium' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400, 2048],
            'sizes' => '(min-width: 1152px) 1024px, (min-width: 1024px) calc(100vw - 8rem), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:narrow' => [
            'width' => 320,
            'srcset' => [320, 720, 1024, 1440],
            'sizes' => '(min-width: 784px) 720px, (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'hero' => [
            'width' => 640,
            'srcset' => [640, 960, 1280, 1920, 2560],
            'sizes' => '(min-width: 1536px) calc((100vw - 20rem) / 2), (min-width: 1280px) calc((100vw - 14rem) / 2), (min-width: 1024px) calc((100vw - 12rem) / 2), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
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
