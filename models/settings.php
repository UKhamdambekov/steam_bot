<?php
    class settings{
        public static function view(){
            
            $db = Db::getConnection();     
            $result = $db->query("select * from setting");
            $row = $result->fetch();
            $setting = [
                'token' => $row[1],
                'url' => $row[2]
            ];
            return $setting;
        }

        public static function add($token, $url){
            $db = Db::getConnection();     
            $result = $db->query("select count(*) from setting");
            $row = $result->fetch();
            if($row[0] == 0) $db->query("insert into setting(id) values(1)");  
            $sql = 'update setting set bottoken = :token, url = :url where id = 1';
            $result = $db->prepare($sql);
            $result->bindParam(':token', $token, PDO::PARAM_STR);
            $result->bindParam(':url', $url, PDO::PARAM_STR);
            $result->execute();
            if($result) return true;
            else return false;
        }        

        public static function lng(){//вывод шаблонов
            $db = Db::getConnection();
            $texts = array();
            
            $result = $db->query("select * from lng");
        
            $i = 0;
            while($row = $result->fetch()){
                $texts[$i]['id'] = $row['id'];
                $texts[$i]['ru'] = $row['ru'];
                $texts[$i]['uz'] = $row['uz'];
                $texts[$i]['en'] = $row['en'];
                $texts[$i]['flag'] = $row['flag'];
                $i++;
            }
            return $texts;
        }
        
        public static function text($id){//вывод шаблонов
            $db = Db::getConnection();            
            $result = $db->query("select * from lng where id = ".$id);
            $row = $result->fetch();
            $texts = [
                'id' => $row['id'],
                'ru' => $row['ru'],
                'uz' => $row['uz'],
                'en' => $row['en'],
                'flag' => $row['flag']
            ];  
            return $texts;
        }

        public static function edit($data){
            $db = Db::getConnection();     
            $sql = "update lng set ru = :ru, uz = :uz, en = :en where id = :id";
            $result = $db->prepare($sql);
            $result->bindParam(':id', $data['id'], PDO::PARAM_STR);
            $result->bindParam(':ru', $data['ru'], PDO::PARAM_STR);
            $result->bindParam(':uz', $data['uz'], PDO::PARAM_STR);
            $result->bindParam(':en', $data['en'], PDO::PARAM_STR);
            $result->execute();
            if($result) return true;
            else return false;
        }    
    }
?>