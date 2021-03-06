<?php

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'System.php';

// 加载配置
$config = require SF_LIBRARY_PATH.'Config.php';

$appConfig = file_exists($appConfigPath = SF_APP_PATH.'Config.php') ? require $appConfigPath : [];
$config = array_merge($config, $appConfig);

$config['debug'] = ($config['debug']?? SF_DEBUG);

if ($config['debug']) {
    ini_set("display_errors", "On");
    error_reporting(E_ALL);
}

// composer自动加载
require __DIR__ . '/../vendor/autoload.php';

// session
$session = 'Library\\Sessions\\'.ucfirst($config['session']['client']).'Session';
$handler = new $session($config['session']['config']);

session_set_save_handler($handler, true);
session_start();

// register_shutdown_function('session_write_close');

// 实例化应用并运行
$app = new Library\Application(new Library\Https\Request() ,$config);
$app->run();