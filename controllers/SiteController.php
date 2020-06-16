<?php

class SiteController//контроллер сайта
{
    public static function auth()//страница авторизаций
    {
        $alert = "";
        if(isset($_POST['submit'])){             
            $login = $_POST['login'];
            $password = $_POST['password'];
            if(user::checkName($login)){
                if(user::checkPassword($login, $password)){
                    if(user::auth($login)) header("Location: /");
                    else $alert = "Ошибка авторизаций";
                }
                else $alert = "Неверный пароль";
            }
            else $alert = "Такого пользователя не существует";
        } 

        require_once(ROOT."/views/site/auth.html");
        return true;
    }

    public function actionLogout(){//выход
        user::out();
        header("Location: /");
        return true;
    }

    public function actionIndex()//главная страница
    {   
        $student = student::count();
        $image = files::count_image();
        $video = files::count_video();
        $winner = student::count_winner();
        require_once(ROOT."/views/site/index.html");
        return true;
    }    

    public static function actionError()//страница ошибки
    {   
        require_once(ROOT."/views/site/404.html");
        return true;
    }

    public static function actionProfile()//профиль
    {   
        require_once(ROOT."/views/site/profile.html");
        return true;
    }

    public static function actionSettings(){        
        if($_SESSION['role'] == 'admin'){
            if(isset($_POST['submit'])){ 
                $token = $_POST['bottoken'];
                $url = $_POST['url'];
                $add = settings::add($token, $url);
                if($add) header("Location: /settings");
                else return false;
            }
            $setting = settings::view();
            $texts = settings::lng();
            require_once(ROOT."/views/site/settings.html");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }

    public static function actionEdit($id){  
        if($_SESSION['role'] == 'admin'){    
            if(isset($_POST['submit'])){ 
                $data = [
                    'id' => $id,
                    'ru' => $_POST['ru'],
                    'uz' => $_POST['uz'],
                    'en' => $_POST['en']
                ];
                $add = settings::edit($data);
                if($add) header("Location: /settings");
                else return false;
            }
            $text = settings::text($id);
            require_once(ROOT."/views/site/edit.html");
        }
        else require_once(ROOT."/views/site/404.html");
        return true;
    }
}
?>