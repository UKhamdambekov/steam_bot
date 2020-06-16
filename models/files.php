<?php
    class files{
        public static function count_image(){
            $db = Db::getConnection();
            
            $result = $db->query("select count(*) from files where type = 1");
            $row = $result->fetch();
            return $row[0];
        } 

        public static function count_video(){
            $db = Db::getConnection();
            
            $result = $db->query("select count(*) from files where type = 2");
            $row = $result->fetch();
            return $row[0];
        }

        public static function winner($id){
            $db = Db::getConnection();
            
            $result = $db->query("select count(*) from winner where id_file = ".$id);
            $row = $result->fetch();
            return $row[0];
        }

        public static function student($id){
            $db = Db::getConnection();
            
            $result = $db->query("select id from files where id_file = ".$id);
            $row = $result->fetch();
            return $row[0];
        }

        public static function file($id){
            $db = Db::getConnection();
            
            $result = $db->query("select file from files where id_file = ".$id);
            $row = $result->fetch();
            return $row[0];
        }

        public static function get(){
            $db = Db::getConnection();
            $files = array();
            
            $result = $db->query("select * from files");
        
            $i = 0;
            while($row = $result->fetch()){		
                $files[$i]['id_file'] = $row['id_file'];	
                $files[$i]['id'] = $row['id'];
                $files[$i]['file'] = $row['file'];
                $files[$i]['date'] = $row['date'];
                $files[$i]['flag'] = $row['flag'];
                $files[$i]['type'] = $row['type'];
                $i++;
            }
            return $files;
        }


        public static function view($flag){
            $db = Db::getConnection();
            $files = array();
            
            $result = $db->query("select * from files where flag = '".$flag."'");
        
            $i = 0;
            while($row = $result->fetch()){			
                $files[$i]['id_file'] = $row['id_file'];	
                $files[$i]['id'] = $row['id'];
                $files[$i]['file'] = $row['file'];
                $files[$i]['date'] = $row['date'];
                $files[$i]['flag'] = $row['flag'];
                $i++;
            }
            return $files;
        }

        public static function delete($id){
            $db = Db::getConnection();
            
            $result = $db->query("select file from files where id_file = ".$id);
            $row = $result->fetch(); $file = substr($row[0], 1);
            if(unlink($file)){
                $result = $db->query("delete from files where id_file = ".$id);
                if($result) return $file;
                else return false;
            } 
            else return false;
        }
    }; 
?>