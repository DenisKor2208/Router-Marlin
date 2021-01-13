<?php

class Router {

    private $routes = [],
            $params = [],
            $urlParams = [],
            $urlConfig,
            $rootDirectory;


    public function __construct($urlConfig) {
        $this->rootDirectory = $_SERVER['DOCUMENT_ROOT'];
        $this->urlConfig = $urlConfig;
        foreach ($this->urlConfig as $key => $val) {
            $this->add($key, $val);
        }
    }

    /**
    Parameters:
    $route - string
    $params - array

    Description: преобразование в шаблон регулярного выражения, маршрута и добавление его с параметрами в свойство $routes

    Return value: null
     **/
    public function add($route, $params) {
        $route = '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $route) . '/?$#';
        $this->routes[$route] = $params;
    }

    /**
    Parameters:

    Description: поиск введенного в адресную строку браузера маршрута в уже
               * существующих маршрутах, которые были переданны массивом в конструктор этого класса.
               * Сохранение найденного маршрута и параметров в соответствующие свойства класса.

    Return value: boolean
     **/
    public function match() {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->urlParams = $this->clearParams($matches);
                foreach ($params as $key => $val) {
                    $this->params[$key] = $val;
                }
                return true;
            }
        }
        return false;
    }

    /**
    Parameters:
    $params - array

    Description: Очистка переданного массива от числовых ключей

    Return value: array
     **/
    private function clearParams($params) {
        $result = [];
        foreach ($params as $key => $param) {
            if (!is_int($key)) {
                $result[$key] = $param;
            }
        }
        return $result;
    }

    /**
    Parameters:
    $urlConfig - array
    $urlParams - array

    Description: Отправка параметров, переданных в адресной строке, методом GET либо POST
               * на нужную страницу и возврат, указанного в параметрах маршрута этого имени файла
               * для открытия и приема в нем переданных параметров из массива GET либо POST

    Return value: string
     **/
    private function formedAddress($urlConfig, $urlParams) {

        switch ($urlConfig['method']) {
            case 'GET':
                if (empty($urlParams)) {
                    return $urlConfig['file'];
                }
                foreach($urlParams as $key => $val){
                    $_GET[$key] = $val;
                }
                return $urlConfig['file'];
            case 'POST':
                if (empty($urlParams)) {
                    return $urlConfig['file'];
                }
                foreach($urlParams as $key => $val){
                    $_POST[$key] = $val;
                }
                return $urlConfig['file'];
            default:
                return false;
        }

    }

    /**
    Parameters:

    Description: Запуск маршрутизатора

    Return value: null
     **/
    public function run() {
        if ($this->match()) {
            $formedUrl = $this->formedAddress($this->params, $this->urlParams);
            if (file_exists($this->rootDirectory . $formedUrl)) {
                include $this->rootDirectory . $formedUrl; exit;
            } else {
                include $this->rootDirectory . $this->urlConfig['pageError']['file']; exit;
            }
        } else {
            include $this->rootDirectory . $this->urlConfig['pageError']['file']; exit;
        }
    }

}