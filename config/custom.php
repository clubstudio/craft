<?php

/**
 * Image transforms, by transform key. See Image Transform System in the README.
 *
 * Each `sizes` describes how wide the image is shown at each breakpoint, so it's written for the layout the CSS
 * draws. If any of these change, update the sizes that use them:
 *
 * - The gutter on each side (--gutter in src/css/site.css): 1.5rem, then 2rem from 768px, 4rem from 1024px,
 *   5rem from 1280px and 8rem from 1536px. calc(100vw - 16rem) at 1536px is the width inside both gutters.
 * - Medium and Narrow content widths (--container-medium and --container-narrow): 1024px and 720px.
 * - The gap between columns (gap-6 in _components/layout/columns and _builder/blocks/faqs): 1.5rem.
 * - The gap in Content & Media and the Hero (lg:gap-x-16 in _components/layout/split): 4rem.
 *
 * Columns keys name the Column Layout (11, 12, 21, 13, 31 or 111), and are sized for the widest column.
 */

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
        'section:full-bleed:columns:11' => [
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
        'section:full-width:columns:11' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 17.5rem) / 2), (min-width: 1280px) calc((100vw - 11.5rem) / 2), (min-width: 1024px) calc((100vw - 9.5rem) / 2), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width:columns:111' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 19rem) / 3), (min-width: 1280px) calc((100vw - 13rem) / 3), (min-width: 1024px) calc((100vw - 11rem) / 3), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width:columns:12' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 17.5rem) * 2 / 3), (min-width: 1280px) calc((100vw - 11.5rem) * 2 / 3), (min-width: 1024px) calc((100vw - 9.5rem) * 2 / 3), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width:columns:21' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 17.5rem) * 2 / 3), (min-width: 1280px) calc((100vw - 11.5rem) * 2 / 3), (min-width: 1024px) calc((100vw - 9.5rem) * 2 / 3), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width:columns:13' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 17.5rem) * 3 / 4), (min-width: 1280px) calc((100vw - 11.5rem) * 3 / 4), (min-width: 1024px) calc((100vw - 9.5rem) * 3 / 4), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
        ],
        'section:full-width:columns:31' => [
            'width' => 320,
            'srcset' => [320, 768, 1024, 1400],
            'sizes' => '(min-width: 1536px) calc((100vw - 17.5rem) * 3 / 4), (min-width: 1280px) calc((100vw - 11.5rem) * 3 / 4), (min-width: 1024px) calc((100vw - 9.5rem) * 3 / 4), (min-width: 768px) calc(100vw - 4rem), calc(100vw - 3rem)',
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
