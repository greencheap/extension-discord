<?php

use GreenCheap\Discord\Events\BlogListener;
use GreenCheap\Discord\Events\DocsListener;
use GreenCheap\Discord\Events\MarketplaceListener;

return [
    'name' => 'discord',

    'autoload' => [
        'GreenCheap\\Discord\\' => 'src'
    ],

    'config' => [
        'webhook_uri' => '',
        'pkgs' => [
            'blogEvent' => [
                'active' => false,
                'title' => 'Blog Event'
            ],
            'docsEvent' => [
                'active' => false,
                'title' => 'Docs Event'
            ],
            'brainEvent' => [
                'active' => false,
                'title' => 'Brain Event'
            ]
        ]
    ],

    'settings' => 'discord-settings',

    'events' => [
        'boot' => function ($event, $app) {
            $app->subscribe(
                new BlogListener($this),
                new DocsListener($this),
                new MarketplaceListener($this)
            );
        },

        'view.scripts' => function ($event, $scripts) use ($app) {
            $scripts->register('discord-settings', 'discord:app/bundle/discord-settings.js', ['~extensions', 'input-tree']);
        },
    ]
];
