<?php
define('VG_ACCESS', true); //Константа безопасности, для запрета доступа к другим php файлам из браузера
header('Content-Type:text/html;charset=utf-8');
session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';

use core\base\exceptions\RouteException;
use core\base\controller\RouteController;

try {
    RouteController::getInstance()->route();
}
catch (RouteException $e)
{
    exit($e->getMessage());
}
