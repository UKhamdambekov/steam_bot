<?php

class ClassesController//контроллер уроков
{
    public function actionIndex()//главная страница
    {
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            if(isset($_POST['submit'])){
                $data = [
                    'command' => "/".$_POST['command'],
                    'ru'  => $_POST['ru'],
                    'en'  => $_POST['en'],
                    'uz' => $_POST['uz']
                ];
                $add = classes::add($data);
                if($add) header("Location: /classes");
                else return false;
            }
            $classes = classes::get();
            require_once(ROOT."/views/pages/classes.html");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }

    public function actionBlock($id)//блокировка
    {   
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $block = classes::block($id);
            if($block) {//если деактивирован активирован
                $students = student::view();         
                foreach($students as $student) {
                    $classes = classes::lng($id, $student['lang']); // описание
                    $lng = telegram::getlng($student['lang'], "classdeactive"); //шаблон сообщения
                    $text = str_replace("+CLASSES+", "'".$classes."'", $lng);  //замена сообщения
                    if($student['status']) telegram::sendMessage($student['id'], $text);                
                }
                header("Location: /classes");   

            }
            else return false;
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }

    public function actionActive($id)//активация
    {   
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $active = classes::active($id);
            if($active) {//если урок активирован
                $students = student::view();         
                foreach($students as $student) {
                    $classes = classes::lng($id, $student['lang']); // описание
                    $command = classes::command($id); //команда
                    $lng = telegram::getlng($student['lang'], "classactive"); //шаблон сообщения
                    $text = str_replace("+CLASSES+", "'".$classes."'", $lng);  //замена сообщения
                    $text = str_replace("+COMMAND+", $command, $text); // замена сообщения
                    if($student['status']) telegram::sendMessage($student['id'], $text);                
                }
                header("Location: /classes");        
            }
            else return false;
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }


    public function actionDelete($id)//удаления
    {
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $students = student::view();         
            foreach($students as $student) {
                $classes = classes::lng($id, $student['lang']); // описание
                $lng = telegram::getlng($student['lang'], "classdeactive"); //шаблон сообщения
                $text = str_replace("+CLASSES+", "'".$classes."'", $lng);  //замена сообщения
                if($student['status']) telegram::sendMessage($student['id'], $text);                
            }
            $del = classes::delete($id);            
            if($del) header("Location: /classes");
            else return false;
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }
}
?>