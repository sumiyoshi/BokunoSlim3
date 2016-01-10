<?php

#region ルーティング
$routes = array();
foreach (glob(__DIR__ . '/router/*') as $file) {
    $_file = include($file);
    $routes = array_merge($routes, $_file);
}
#endregion

return array(
    'view' =>
        array(
            'debug' => false,
            'template_path' => APP_ROOT . '/src/App/templates',
            'extension' => [
                '\Core\Twig\Extension\Form',
                '\Twig_Extension_Debug'
            ]
        ),
    'routes' => $routes
);





