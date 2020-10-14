<?php

use GreenCheap\Discord\Events\BlogListener;

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
            ]
        ]
    ],

    'settings' => 'discord-settings',

    'events' => [
        'boot' => function ($event, $app) {
            $app->subscribe(
                new BlogListener($this)
            );
        },

        'view.scripts' => function ($event, $scripts) use ($app) {
            $scripts->register('discord-settings', 'discord:app/bundle/discord-settings.js', ['~extensions', 'input-tree']);
        },
    ]
];
