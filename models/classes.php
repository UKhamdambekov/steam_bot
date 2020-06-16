<?php
    class classes{
        public static function get(){//получение всех уроков
            $db = Db::getConnection();
            $classes = array();
            
            $result = $db->query("select * from classes");

            $i = 0;
            while($row = $result->fetch()){			
                $classes[$i]['id'] = $row['id'];
                $classes[$i]['command'] = $row['command'];
                $classes[$i]['ru'] = $row['ru'];
                $classes[$i]['uz'] = $row['uz'];
                $classes[$i]['en'] = $row['en'];
                $classes[$i]['active'] = $row['active'];
                $i++;
            }
            return $classes;
        }

        public static function add($data){//добавление
            $db = Db::getConnection();
            $sql = 'insert into classes(command, ru, uz, en) 
                values(:command, :ru, :uz, :en)';
            $result = $db->prepare($sql);
            $result->bindParam(':command', $data['command'], PDO::PARAM_STR);
            $result->bindParam(':ru', $data['ru'], PDO::PARAM_STR);
            $result->bindParam(':uz', $data['uz'], PDO::PARAM_STR);
            $result->bindParam(':en', $data['en'], PDO::PARAM_STR);
            $result->execute();
            if($result) return true;
            else return false;
        }
        
        public static function block($id){
            $db = Db::getConnection();
            
            $result = $db->query("update classes set active = 0 where id = ".$id);
            if($result) return true;
            else return false;
        } 
        
        public static function active($id){
            $db = Db::getConnection();
            
            $result = $db->query("update classes set active = 1 where id = ".$id);
            if($result) return true;
            else return false;
        } 

        public static function delete($id){
            $db = Db::getConnection();
            
            $result = $db->query("delete from classes where id = ".$id);
            if($result) return true;
            else return false;
        } 

        public static function lang($flag, $lang){
            $db = Db::getConnection();
            $flag = "/".$flag;
            $result = $db->query("select ".$lang." from classes where command = '".$flag."'");
            $row = $result->fetch();
            return $row[0];
        } 

        public static function command($id){
            $db = Db::getConnection();
            $result = $db->query("select command from classes where id = ".$id);
            $row = $result->fetch();
            return $row[0];
        } 

        public static function lng($id, $lang){
            $db = Db::getConnection();
            $result = $db->query("select ".$lang." from classes where id = ".$id);
            $row = $result->fetch();
            return $row[0];
        } 
    }
?>