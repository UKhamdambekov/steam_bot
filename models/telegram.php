<?php
    class telegram{
        
        // функция отправки текстового сообщения
        public static function sendMessage($chat_id, $text)
        {
            telegram::requestToTelegram([
                'chat_id' => $chat_id,
                'text' => $text
            ], "sendMessage");
        }
        
        public static function requestToTelegram($data, $type)
        {       
                $db = Db::getConnection();
                $result = $db->query("select bottoken from setting");
                $row = $result->fetch();
                $apiUrl = "https://api.telegram.org/bot";    
                $bottoken = $row[0];
            $result = null;
            if (is_array($data)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl . $bottoken . '/' . $type);
                curl_setopt($ch, CURLOPT_POST, count($data));
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                $result = curl_exec($ch);
                curl_close($ch);
            }
            return $result;
        }
        
        public static function getlng($lang, $flag){ //получаем язык
            $db = Db::getConnection();
            $result = $db->query("select ".$lang." from lng where flag = '".$flag."'");
            $row = $result->fetch();
            return $row[0]; 
        }

    }
?>