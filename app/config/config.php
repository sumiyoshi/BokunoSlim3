<?php

return array(
    'view' =>
        [
            'debug' => false,
            'template_path' => APP_ROOT . '/src/App/templates',
            'extension' => [
                \Core\Twig\Extension\Form::class,
                \Twig_Extension_Debug::class
            ]
        ],
    'log' => SERVER_ROOT.'/data/log/'
);





