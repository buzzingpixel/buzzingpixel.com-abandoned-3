<?php

declare(strict_types=1);

return [

    'siteName' => 'BuzzingPixel.com',
    'socialMedia' => ['twitterHandle' => 'buzzingpixel'],

    'nav' => [
        'persistentItems' => [
            [
                'svgIconTemplate' => 'Svg/CartIcon.svg',
                'href' => '/cart',
                'content' => 'Cart',
                'hasBadgeMarkup' => true,
                'badgeClass' => 'JS-CartCount',
            ],
            [
                'svgIconTemplate' => 'Svg/AccountIcon.svg',
                'href' => '/account',
                'content' => 'Account',
            ],
        ],
        'items' => [
            // [
            //     'href' => '/news',
            //     'content' => 'News',
            // ],
            [
                'href' => '/software/ansel-craft',
                'content' => 'Ansel for Craft',
            ],
            [
                'href' => '/software/ansel-ee',
                'content' => 'Ansel for EE',
            ],
            [
                'href' => '/software/executive-ee',
                'content' => 'Executive for EE',
            ],
            [
                'href' => '/software/treasury',
                'content' => 'Treasury',
            ],
            [
                'href' => '/software/ansel-treasury-ee',
                'content' => 'Ansel + Treasury Bundle',
            ],
            [
                'href' => '/software/construct',
                'content' => 'Construct',
            ],
            [
                'href' => '/software/category-construct',
                'content' => 'Category Construct',
            ],
            [
                'href' => '/software/collective',
                'content' => 'Collective',
            ],
            [
                'href' => '/software/marksmin',
                'content' => 'Marksmin',
            ],
            [
                'href' => '/software/field-limits',
                'content' => 'Field Limits',
            ],
            [
                'href' => '/software/typographee',
                'content' => 'Typography',
            ],
        ],
    ],

    /**
     * Cache breaking schemes are DEV ONLY. Deployment process will change the actual filenames of the files and update
     * this array to link directly to those files. We will not use query string and uniqid() for versioning. For
     * instance, style.min.css will become something like style.min.1553365271.css.
     */
    'headStyleSheets' => [
        'https://fonts.googleapis.com/css?family=Arvo:400,400i,700,700i|Noto+Sans+SC:100,300,400,500,700,900',
        '/assets/css/style.min.css?v=' . uniqid(),
    ],
    'endBodyJsFiles' => [
        // Deployment process will replace this with https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.min.js
        'vue' => 'https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js',
        'main' => [
            'src' => '/assets/js/main.js?v=' . uniqid(),
            'type' => 'module',
        ],
    ],

];
