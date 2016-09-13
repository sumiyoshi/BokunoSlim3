<?php
#region ルーティング
$app->any("/{controller:[a-zA-Z_0-9]*}/[{action:[a-zA-Z_0-9]*}/{id:[0-9]*}]", \Core\Middleware\Dispatch::class)
  ->setName("front")
  ->setArgument('module', "front");
#endregion