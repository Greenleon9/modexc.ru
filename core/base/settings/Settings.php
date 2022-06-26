<?php


namespace core\base\settings;


class Settings //Класс шаблон одиночка
{
    private static object $_instance;

    private $routes = [
        'admin'=> [
            'alias'=> 'admin',
            'path'=> 'core/admin/controller/',
            'hrUrl'=> false, //человеко-понятные ссылки
            'routes'=> [
            ]
        ],
        'settings'=> [
            'path'=> 'core/base/settings/'
        ],
        'plugins'=> [
            'path'=> 'core/plugins/',
            'hrUrl'=> false, //человеко-понятные ссылки
            'dir'=> false
        ],
        'user'=>[
            'path'=> 'core/user/controller/',
            'hrUrl'=> true,
            'routes'=>[

            ]
        ],
        'default'=>[
            'controller'=> 'IndexController',
            'inputMethod'=> 'inputData',
            'outputMethod'=> 'outputData'
        ]
    ];
    private array $templateArr = [
        'text'=> ['name', 'phone' , 'address'],
        'textarea'=> ['content', 'keywords']
    ];
    private function __construct()
    {
    }
    private function __clone()
    {
    }

    static public function get($property)
    {
       return self::instance()->$property;
    }

    static public function instance()
    {
        if (self::$_instance instanceof self)
        {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
    //склеивание всех свойств с заменой совпадающих значений
    public function clueProperties($class): array
    {
        $baseProperties = [];

        foreach ($this as $name=> $item){
            $property = $class::get($name);

            if (is_array($property) && is_array($item)){
                $baseProperties[$name]= $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }
            if (!$property) $baseProperties[$name] = $this->$name;
        }
       return $baseProperties;
    }
// метод для добавления и перезаписи свойств
    public function arrayMergeRecursive()
    {
        $arrays = func_get_args();
        $base = array_shift($arrays); // извлекаем первый элемент массива

        // проходим по остальным элементам массива
        foreach ($arrays as $array){
            foreach ($array as $key=>$value){
                if (is_array($value) && is_array($base[$key])){
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                } else {
                    if (is_int($key)){
                        if (!in_array($value, $base)) $base[]=$value;
                        continue;
                    }
                    $base[$key] =$value;
                }
            }
        }
        return $base;
    }

}