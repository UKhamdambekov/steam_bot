<?php
    spl_autoload_register(function($class_name) {
        $array_paths = array(
               '/models/',
               '/components/'
           );
   
           foreach ($array_paths as $path) {
               $path = ROOT. $path . $class_name . '.php';
               if(is_file($path)) {
                   include_once $path;
               }
           }
   });

    /* Вызов роутера */
    $router = new Router();
    
    if(stristr($_SERVER['REQUEST_URI'], 'bot') == TRUE){ 
        //подключаем бота
        include_once(ROOT.'/components/bot.php');
     }
    else {//открываем страницы сайта
        if(isset($_SESSION['id'])) $router->run();//если авторизован выполняем маршруты        
        else {
            include_once(ROOT.'/controllers/SiteController.php'); //если нет  выводим  страницу авторизации
            SiteController::auth();
        }

    }

?>