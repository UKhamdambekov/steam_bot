<?php
    date_default_timezone_set('Asia/Tashkent'); //временная зона 
    // определяем кодировку
    header('Content-type: text/html; charset=utf-8');
    /* Общие настройки */
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    session_start();
    /* Подключение файлов системы */
    define('ROOT', dirname(__FILE__));
    require_once(ROOT.'/components/autoload.php');
?>