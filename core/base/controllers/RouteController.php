<?php


namespace core\base\controllers;


use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController
{
  static private $_instance;

  protected $routes;
  protected $controller;
  protected $inputMethod;
  protected $outputMethod;
  protected $parameters;

  private function __clone()
  {
  }

  static public function getInstance()
  {
      if (self::$_instance instanceof self)
      {
          return self::$_instance;
      }
      return self::$_instance= new self;
  }
// Разбираем путь адресной строки
  private function __construct()
  {
      $address_str = $_SERVER['REQUEST_URI'];

      if (strrpos($address_str, '/') === strlen($address_str)-1 &&
          strrpos($address_str, '/') !==0){
          $this->redirect(rtrim($address_str, '/'), 301);
      }
      $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));
      if ($path===PATH){
          $this->routes = settings::get('routes');

          if (!$this->routes) throw new RouteException('Сайт находится на техническом 
          обслуживании');

          if (strpos($address_str, $this->routes['admin']['alias'] === strlen(PATH))){

              $url = explode('/', substr($address_str, strlen(PATH . $this->routes['admin']['alias'])+1));
              if ($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'][$url[0]])){
// Проверяем наличие папки с названием плагина, указанного в адресной строке
                  $plugin = array_shift($url);
                  $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');
                // Проверяем существует ли файл плагина и изменяем формат пути
                  if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . 'php')){
                      $pluginSettings = str_replace('/', '\\', $pluginSettings);
                      $this->routes = $pluginSettings::get('routes');
                  }

              }else{
                  $this->controller = $this->routes['admin']['path'];
                  $hrUrl = $this->routes['admin']['hrUrl'];
                  $route = 'admin';
              }

          }else{
              $url = explode('/', substr($address_str, strlen(PATH)));
              $hrUrl= $this->routes['user']['hrUrl'];

              $this->controller = $this->routes['user']['path'];
              $route = 'user';
          }
          $this->createRoute($route, $url);


          exit();
      }else {
          try{
              throw  new \Exception('Некоректная директория сайта');
          }
          catch (\Exception $e){
              exit($e->getMessage());
          }
      }
  }
  // Установка котроллеров и методов согласно адресу запроса
  private function createRoute($var, $arr){
      $route = [];
      if (!empty($arr[0])){
          if ($this->routes[$var]['routes'][$arr[0]]){
              $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);
              $this->controller .= ucfirst($route.'Controller');
          }else {
              $this->controller .= ucfirst($arr[0].'Controller');
          }
      }else{
          $this->controller .= $this->routes['default']['controller'];
      }
      $this->inputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
      $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];

  }

}