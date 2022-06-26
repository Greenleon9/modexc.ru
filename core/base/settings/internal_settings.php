<?php
//Проверка доступности файла из строки браузера
defined('VG_ACCESS') or die('Доступ запрещен');

const TEMPLATE = 'templates/default';
const ADMIN_TEMPLATE = 'core/admin/view';

const COOKIE_VERSION = '1.0'; //Для принудительного перезахода пользователей
const CRYPT_KEY = '';
const COOKIE_TIME = 60;
const BLOCK_TIME = 3;

const QTY = 8;
const QTY_LINKS = 3;

const ADMIN_CSS_JS = [
    'styles'=>[],
    'scripts'=>[]
];
const USER_CSS_JS = [
    'styles'=>[],
    'scripts'=>[]
];

use core\base\exceptions\RouteException;
function autoloadMainClasses($class_name)
{
    if (!@include_once $class_name.'.php')
    {
        throw new RouteException('Неверное имя файла для подключения-'.$class_name);
    }
}

spl_autoload_register('autoloadMainClasses');