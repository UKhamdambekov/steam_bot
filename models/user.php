<?php 
    class user //класс пользователя
    { 
        public static function checkName($login){//проверка логина
            $db = Db::getConnection();

            $sql = 'select count(*) from user where login = :login';
            $result = $db->prepare($sql);
            $result->bindParam(':login', $login, PDO::PARAM_STR);
            $result->execute();

            if($result->fetchColumn()) return true;
            else return false;
        }

        public static function checkPassword($login, $password) {//проверка пароля
            $db = Db::getConnection();
            $password = md5(sha1($password));

            $sql = 'select count(*) from user where login = :login and password = :password';
            $result = $db->prepare($sql);
            $result->bindParam(':login', $login, PDO::PARAM_STR);
            $result->bindParam(':password', $password, PDO::PARAM_STR);
            $result->execute();
            if($result->fetchColumn()) return true;
            else return false;
        }

        public static function auth($login){//авторизация
            $db = Db::getConnection();
            $users = array();        
            $result = $db->query("select * from user where login = '".$login."'");    
            $i = 0;
            while($row = $result->fetch()){
                $_SESSION['id'] = $row['id_user'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['login'] = $row['login'];
                $_SESSION['role'] = $row['role'];
                $i++;
            }
            $_SESSION['time'] = date("H:i:s");
            return true;
        }

        public static function out(){//выход с системы
            unset($_SESSION['id']);
            unset($_SESSION['role']);
            unset($_SESSION['time']);
        }

        public static function users(){//список
            $db = Db::getConnection();
            $users = array();
            
            $result = $db->query("select * from user");
        
            $i = 0;
            while($row = $result->fetch()){
                $users[$i]['id'] = $row['id_user'];
                $users[$i]['name'] = $row['name'];
                $users[$i]['login'] = $row['login'];
                $users[$i]['role'] = $row['role'];
                $users[$i]['active'] = $row['active'];
                $i++;
            }
            return $users;
        }

        public static function view($id){//вывод инфы
            $db = Db::getConnection();
            $users = array();
            
            $result = $db->query("select * from user where id = ".$id);
        
            $i = 0;
            while($row = $result->fetch()){
                $users[$i]['id'] = $row['id_user'];
                $users[$i]['name'] = $row['name'];
                $users[$i]['login'] = $row['login'];
                $users[$i]['role'] = $row['role'];
                $users[$i]['active'] = $row['active'];
                $i++;
            }
            return $users;
        }

        public static function add($data){//добавление
            $db = Db::getConnection();  
            $password = md5(sha1($data['password']));         
            $sql = 'insert into user(name, login, password, role, active) 
                values(:name, :login, :password, :role, :active)';
            $result = $db->prepare($sql);
            $result->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $result->bindParam(':login', $data['login'], PDO::PARAM_STR);
            $result->bindParam(':password', $password, PDO::PARAM_STR);
            $result->bindParam(':role', $data['role'], PDO::PARAM_STR);
            $result->bindParam(':active', $data['active'], PDO::PARAM_INT);
            $result->execute();
            if($result) return true;
            else return false;
        }

        public static function reset($id){//сбросить пароль
            $db = Db::getConnection();
            $pass = "1111";  
            $password = md5(sha1($pass));
            $result = $db->query("update user set password = '".$password."' where id_user = ".$id);
            if($result) return true;
            else return false;
        }

        public static function change($id, $type, $data){//изменение
            $db = Db::getConnection();  
            if($type == "pass"){ 
                $password = md5(sha1($data));
                $sql = "update user set password = '".$password."' where id_user = ".$id;
            }
            if($type == "name") $sql = "update user set name = '".$data."' where id_user = ".$id;
            $result = $db->query($sql);
            if($result) return true;
            else return false;
        }

        public static function del($id){//удаление
            $db = Db::getConnection();  
            $result = $db->query("delete from user where id_user = ".$id);
            if($result) return true;
            else return false;
        }
    }
?>