<?php

return [
    'view' =>
        [
            'debug' => false,
            'template_path' => APP_ROOT . '/src/templates',
            'extension' => [
                \Core\Twig\Extension\Form::class,
                \Twig_Extension_Debug::class
            ]
        ],
    'log' => SERVER_ROOT . '/data/log/',
    'database' =>
        [
            'host' => 'localhost',
            'db' => 'test',
            'username' => 'root',
            'password' => ''
        ]
];
