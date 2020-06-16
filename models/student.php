<?php
    class student{

        public static function name($id){
            $db = Db::getConnection();
            
            $result = $db->query("select name from student where id = ".$id);
            $row = $result->fetch();
            return $row[0];
        } 

        public static function classes($id){
            $db = Db::getConnection();
            
            $result = $db->query("select class from student where id = ".$id);
            $row = $result->fetch();
            return $row[0];
        } 

        public static function count(){
            $db = Db::getConnection();
            
            $result = $db->query("select count(*) from student");
            $row = $result->fetch();
            return $row[0];
        } 

        public static function count_winner(){
            $db = Db::getConnection();
            
            $result = $db->query("select count(*) from winner");
            $row = $result->fetch();
            return $row[0];
        } 

        public static function view(){
            $db = Db::getConnection();
            $students = array();
            
            $result = $db->query("select * from student");
        
            $i = 0;	            
            while($row = $result->fetch()){			
                $students[$i]['id'] = $row['id'];
                $students[$i]['name'] = $row['name'];
                $students[$i]['district'] = $row['district'];
                $students[$i]['school'] = $row['school'];
                $students[$i]['class'] = $row['class'];
                $students[$i]['lang'] = $row['lang'];
                $students[$i]['status'] = $row['status'];
                $i++;
            }
            return $students;
        }
        
        public static function block($id){
            $db = Db::getConnection();
            
            $result = $db->query("update student set status = 0 where id = ".$id);
            if($result) return true;
            else return false;
        } 
        
        public static function active($id){
            $db = Db::getConnection();
            
            $result = $db->query("update  student set status = 1 where id = ".$id);
            if($result) return true;
            else return false;
        } 
        
        public static function delete($id){
            $db = Db::getConnection();
            
            $result = $db->query("delete from student where id = ".$id);
            if($result) return true;
            else return false;
        } 

        public static function winner(){
            $db = Db::getConnection();
            $students = array();
            
            $result = $db->query("select * from winner");
        
            $i = 0;
            while($row = $result->fetch()){			
                $students[$i]['id_winner'] = $row['id_winner'];
                $students[$i]['id'] = $row['id'];
                $students[$i]['name'] = $row['name'];
                $students[$i]['flag'] = $row['flag'];
                $students[$i]['about'] = $row['about'];
                $i++;
            }
            return $students;
        }      
        
        public static function addwinner($id, $flag){
            $db = Db::getConnection();
            $student = files::student($id);
            $name = student::name($student);
            $file = files::file($id);
            $about = "Победил по предмету ".$flag." за файл <br>
                <a href='".$file."' target='_blank'>
                    <span class='label label-info'>Посмотреть</span>
                </a>";        
            $result = $db->prepare("insert into winner (id, name, flag, about, id_file) 
                values(:id, :name, :flag, :about, :file)");
            $result->bindParam(':id', $student, PDO::PARAM_INT);
            $result->bindParam(':name', $name, PDO::PARAM_STR);
            $result->bindParam(':flag', $flag, PDO::PARAM_STR);
            $result->bindParam(':about', $about, PDO::PARAM_STR);
            $result->bindParam(':file', $id, PDO::PARAM_INT);
            $result->execute();
            if($result) return true;
            else return false;
        }   

        public static function lang($id){
            $db = Db::getConnection();
            $result = $db->query("select lang from student where id = ".$id);
            $row = $result->fetch();
            return $row[0];
        }
    }
?>