<?php
$container = $app->getContainer();

//シャットダウンハンドラー
register_shutdown_function(['\Core\Service\ErrorHandler', 'shutdownHandler']);

#region ルーティング
$app->any("/{controller:[a-zA-Z_0-9]*}/[{action:[a-zA-Z_0-9]*}/{id:[0-9]*}]", \App\Front\FrontMiddleware::class)->setName("front");
#endregion

$config = $container->get('config');

#region ログの初期値セット
if (isset($config['log']) && !empty($config['log'])) {
    \Core\Service\Logger::$log_path = $config['log'];
}
#endregion