<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
    }
    /**
     * Returns request string
     * @return string
     */
    private function getURI()
    {
        if(!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    public function run(){
        /* Получить строку запроса */
        $uri = $this->getURI();
        /* Проверить наличиек такого запроса в routes.php */
        foreach($this->routes as $uriPattern => $path){
            //сравниваем #uriPattern и $uri
            if (preg_match("~^$uriPattern$~", $uri)){
                //Получаем внутренний путь из внешного согласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                //Определить какой контроллер и action обрабатывают запрос
                $segments = explode ('/', $internalRoute);  
                $controllerName = ucfirst(array_shift($segments)).'Controller';
                $actionName = 'action'.ucfirst(array_shift($segments));
                $controllerFile = ROOT.'/controllers/'.$controllerName.'.php';
                $parametrs = $segments;
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                //Создать объект, взывать метод (т.е. action)
                $controllerObject = new $controllerName;
                $result = call_user_func_array(array($controllerObject, $actionName), $parametrs);                
                if($result != null){
                    break;
                }
            }
        }
        if(!isset($result)) {
            include_once(ROOT.'/controllers/SiteController.php');
            SiteController::actionError();
        }
    }

}
?>