<?php

class StudentsController//контроллер ученика
{
    public function actionIndex()//просмотр
    {         
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $students = student::view();
            if(isset($_POST['send'])){            
                foreach($students as $student) if($student['status']) telegram::sendMessage($student['id'], $_POST['text']);
                header("Location: /students");
            }
            require_once(ROOT."/views/pages/students.html");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }   

    public function actionBlock($id)//блокировка
    {   
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $block = student::block($id);
            if($block) header("Location: /students");
            else return false;
        }
        else require_once(ROOT."/views/site/404.html");
    }

    public function actionActive($id)//активация
    {   
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $active = student::active($id);
            if($active) header("Location: /students");
            else return false;
        }
        else require_once(ROOT."/views/site/404.html");
    }

    public function actionDelete($id)//удаления
    {   
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $del = student::delete($id);
            if($del) header("Location: /students");
            else return false;
        }
        else require_once(ROOT."/views/site/404.html");
    }  
    
    public function actionWinner()//победители
    {   
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $students = student::winner();
            require_once(ROOT."/views/pages/winner.html");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }  

    public function actionAddWinner($id, $flag)//победители
    {   
        if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator'){
            $student = files::student($id);
            $lang = student::lang($student);
            $classes = classes::lang($flag, $lang);
            $lng = telegram::getlng($lang, "congurlation");
            $text = str_replace("+CLASSES+", "'".$classes."'", $lng);  
            $add = student::addwinner($id, $flag);
            if($add){
                telegram::sendMessage($student, $text);
                header("Location: /files");
            }
            else return false;
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    } 
}
?>