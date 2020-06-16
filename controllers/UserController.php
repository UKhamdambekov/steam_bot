<?php

class UserController//контроллер пользователя
{

    public function actionIndex()//главная страница
    {   
        if($_SESSION['role'] == 'admin'){
            if(isset($_POST['submit'])){
                $check = user::checkName($_POST['login']);
                if($check) return false;
                else{
                    if(isset($_POST['active'])) $active = 1;
                    else $active = 0;  
                    $data = [
                        'name' => $_POST['name'],
                        'login'  => $_POST['login'],
                        'password'  => $_POST['password'],
                        'role' => $_POST['role'],
                        'active' => $active
                    ];
                    $add = user::add($data);
                    if($add) header("Location: /users");
                    else return false;
                }
            }
            $users = user::users();
            require_once(ROOT."/views/pages/users.html");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }    

    public function actionView($id)//просмотр
    {   
        if($_SESSION['role'] == 'admin'){
            $users = user::view($id);
            require_once(ROOT."/views/users/view.html");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }    

    public function actionReset($id)//сбросить пароль
    {   
        if($_SESSION['role'] == 'admin'){
            $reset = user::reset($id);
            if($reset) header("Location: /users");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }  

    public function actionChange($id)//Изменить
    {   
        if($_SESSION['role'] == 'admin'){
            $change = user::change($id, $_POST['type'], $_POST['data']);
            if($change) header("Location: /logout");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }    

    public function actionDelete($id)//удаление
    {   
        if($_SESSION['role'] == 'admin'){
            $delete = user::del($id);
            if($delete) header("Location: /users");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }     
}
?>