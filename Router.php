<?php

class Router {

    private $routes = [], //route from config and [method, file]
            $params = [], //method and file
            $urlParams = [], //passed parameters in url
            $urlConfig,
            $rootDirectory;


    public function __construct($urlConfig) {
        $this->rootDirectory = $_SERVER['DOCUMENT_ROOT'];
        $this->urlConfig = $urlConfig;
        foreach ($this->urlConfig as $key => $val) {
            $this->add($key, $val);
        }
    }

    public function add($route, $params) {
        $route = '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $route) . '/?$#';
        $this->routes[$route] = $params;
    }

    public function match() {
        $url = trim($_SERVER['REQUEST_URI'], '/'); //отрезаем '/' с обоих сторон
        foreach ($this->routes as $route => $params) { //перебираем массив с шаблоном маршрута и настройками для маршрута
            if (preg_match($route, $url, $matches)) { //проверяем соответствует ли введенный url шаблону маршрута из config
                $this->urlParams = $this->clearParams($matches); //очищаем массив от числовых ключей
                foreach ($params as $key => $val) {
                    $this->params[$key] = $val; //сохраняем method и file в виде массива в переменную $this->params
                }
                return true; //если маршрут найден, то возвращаем true
            }
        }
        return false; //иначе false
    }

    private function clearParams($params) {
        $result = [];
        foreach ($params as $key => $param) {
            if (!is_int($key)) {
                $result[$key] = $param;
            }
        }
        return $result;
    }

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

    public function run() {
        if ($this->match()) { //если введенный путь соответствует шаблону из config
            $formedUrl = $this->formedAddress($this->params, $this->urlParams);
            if (file_exists($this->rootDirectory . $formedUrl)) { //если файл существует
                include $this->rootDirectory . $formedUrl; exit;
            } else {
                include $this->rootDirectory . $this->urlConfig['pageError']['file']; exit;
            }
        } else {
            include $this->rootDirectory . $this->urlConfig['pageError']['file']; exit;
        }
    }



}