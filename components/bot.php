<?php
// определяем кодировку
header('Content-type: text/html; charset=utf-8');

// Создаем объект бота
$bot = new Bot();
// Обрабатываем пришедшие данные
$bot->init('php://input');

/**
 * Class Bot 
 * https://api.telegram.org/bot951383028:AAEEGAze-z4JWB6mxVT2v6rkMpAC5o7GGaA/setWebhook?url=https://bot.loupe.uz/bot
 */
class Bot
{
    // адрес для запросов к API Telegram
    private $apiUrl = "https://api.telegram.org/bot";

    public function init($data_php)
    {
        $db = Db::getConnection();
        // создаем массив из пришедших данных от API Telegram
        $data = $this->getData($data_php);
        // id чата отправителя
        $chat_id = $data['message']['chat']['id'];
        if(!$this->checkchatid($chat_id)) $this->putchatid($chat_id);
        else if(!$this->checkstatus($chat_id)) $this->block($chat_id);
        else {
            $putset = $this->getputset($chat_id);
            if($putset == "register") $this->register($chat_id, $data);//если в стадии регистрации  
            else if(array_key_exists('message', $data)) { // проверяем если пришло сообщение 
                $student = $this->getstudent($chat_id);
                $classes = $this->classes(); 
                $putset = $this->getputset($chat_id);
                if(in_array($data['message']['text'], $classes)) $this->putclass($chat_id, $data);
                else if($data['message']['text'] == "/cancel") $this->cancel($chat_id);
                else if(array_key_exists('photo', $data['message'])){ //если урок не выбран                    
                    $putset = $this->getputset($chat_id);
                    $errclass = $this->getlng($student['lang'], "errclass");
                    $classes = $this->getclasses($student['lang']); 
                    if($putset != NULL) $this->getPhoto($data['message'], $chat_id);              
                    else $this->sendMessage($chat_id, $errclass.$classes); //если урок не выбран        
                }
                else if(array_key_exists('document', $data['message'])){
                    $putset = $this->getputset($chat_id);
                    $errclass = $this->getlng($student['lang'], "errclass");
                    $classes = $this->getclasses($student['lang']); 
                    if($putset != NULL) $this->getVideo($data['message'], $chat_id);     
                    else $this->sendMessage($chat_id, $errclass.$classes); //если урок не выбран 
                }    
                else if($putset != NULL) {  // если выбран урок
                    $setclasses = $this->getlng($student['lang'], "setclasses"); 
                    $setclasses = str_replace("+TEACH+", $putset, $setclasses);             
                    $this->sendMessage($chat_id, $setclasses);
                }
                else $this->command($chat_id, $data); //выполнение комманд
            }
        }
    }

    // функция отправки текстового сообщения
    private function sendMessage($chat_id, $text)
    {
        $this->requestToTelegram([
            'chat_id' => $chat_id,
            'text' => $text
        ], "sendMessage");
    }

    /**
     * Парсим что приходит преобразуем в массив
     * @param $data
     * @return mixed
     */
    private function getData($data)
    {
        return json_decode(file_get_contents($data), TRUE);
    }

    /** Отправляем запрос в Телеграмм
     * @param $data
     * @param string $type
     * @return mixed
     */
    private function requestToTelegram($data, $type)
    {
        $result = null;
        if (is_array($data)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->bottoken() . '/' . $type);
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return $result;
    }

    private function checkchatid($chat_id){ //проверка существования пользователя
        $db = Db::getConnection();
        $result = $db->query("select count(*) from student where id = ".$chat_id);
        $row = $result->fetch(); 
        if($row[0] == 0) return false;        
        else return true;
    }

    private function bottoken(){ //получаем токен бота
        $db = Db::getConnection();
        $result = $db->query("select bottoken from setting");
        $row = $result->fetch(); 
        return $row[0];
    }

    private function command($chat_id, $data){ //выполнение комманд
        $db = Db::getConnection();
        $student = $this->getstudent($chat_id);
        $command = $data['message']['text'];
        switch($command){
            case('/start'):
                $welcome = $this->getlng($student['lang'], "welcome");
                $result = $db->query("select name from student where id = ".$chat_id);
                $row = $result->fetch(); $name = $row[0];
                $welcome = str_replace("+NAME+", $name, $welcome);                
                $this->sendMessage($chat_id, $welcome);
            break;
            case("/help"): 
                $classes = $this->getclasses($student['lang']); 
                $help = $this->getlng($student['lang'], "help");
                $this->sendMessage($chat_id, $help.$classes);
            break;
            case("/stop"):
                $stop = $this->getlng($student['lang'], "stop");
                $this->sendMessage($chat_id, $stop);
            break;
            case("/ru"):
                $result = $db->query("update student set lang = 'ru' where id = ".$chat_id);
                $this->sendMessage($chat_id, "Выбран русский язык!");
                $help = $this->getlng("ru", "help");
                $this->sendMessage($chat_id, $help);
            break;
            case("/en"):
                $result = $db->query("update student set lang = 'en' where id = ".$chat_id);
                $this->sendMessage($chat_id, "Choose english language!");
                $help = $this->getlng("en", "help");
                $this->sendMessage($chat_id, $help);
            break;
            case("/uz"):
                $result = $db->query("update student set lang = 'uz' where id = ".$chat_id);
                $this->sendMessage($chat_id, "O'zbek tili tanlandi!");
                $help = $this->getlng("uz", "help");
                $this->sendMessage($chat_id, $help);
            break;
            default: 
                $batcommand = $this->getlng($student['lang'], "batcommand");
                $this->sendMessage($chat_id, $batcommand);
            break;
        } 
    }

    private function checkstatus($chat_id){
        $db = Db::getConnection();
        $result = $db->query("select status from student where id = ".$chat_id);
        $row = $result->fetch(); 
        return $row[0];
    }

    private function block($chat_id){
        $student = $this->getstudent($chat_id);
        $block = $this->getlng($student['lang'], "block");
        $this->sendMessage($chat_id, $block);
    }

    private function putchatid($chat_id){ //добавление нового пользователя в БД
        $db = Db::getConnection();
        $db->query("insert into student(id) values(".$chat_id.");");
        $db->query("insert into putset(id, flag) values(".$chat_id.", 'register');");
        $this->sendMessage($chat_id, "Salom, botdan foydalanish uchun ro'yxatdan o'ting!"
        ."\nЗдравствуйте! Для начало зарегистрируйтесь!"
        ."\nHi! To start the bot, please register!");  
        $this->sendMessage($chat_id, "\n/ru - Русский \n/uz - O'zbekcha \n/en - English");            
    }

    private function getputset($chat_id){ //получаем состояние пользователя
        $db = Db::getConnection();
        $result = $db->query("select flag from putset where id = ".$chat_id);
        $row = $result->fetch(); 
        return $row[0];
    }

    private function getstudent($chat_id){//получаем данные пользователя
        $db = Db::getConnection();
        $result = $db->query("select * from student where id = ".$chat_id);
        $row = $result->fetch(); 
        $data = [
            "id" => $row[0],
            "name" => $row[1], 
            "district" => $row[2],
            "school" => $row[3],
            "class" => $row[4],
            "lang" => $row[5]
        ];
        return $data;
    }

    private function register($chat_id, $data){//регистрация 
        $db = Db::getConnection();  
        $student = $this->getstudent($chat_id);
        if($student['lang'] == NULL) {
            $command = $data['message']['text'];
                switch($command){
                    case("/ru"):
                        $result = $db->query("update student set lang = 'ru' where id = ".$chat_id);
                        $this->sendMessage($chat_id, "Выбран русский язык!");
                        $setname = $this->getlng("ru", "setname");
                        $this->sendMessage($chat_id, $setname);
                    break;
                    case("/en"):
                        $result = $db->query("update student set lang = 'en' where id = ".$chat_id);
                        $this->sendMessage($chat_id, "Selected english language!");
                        $setname = $this->getlng("en", "setname");
                        $this->sendMessage($chat_id, $setname);
                    break;
                    case("/uz"):
                        $result = $db->query("update student set lang = 'uz' where id = ".$chat_id);
                        $this->sendMessage($chat_id, "O'zbek tili tanlandi!");
                        $setname = $this->getlng("uz", "setname");
                        $this->sendMessage($chat_id, $setname);
                    break;
                    default:
                        $this->sendMessage($chat_id, "\n/ru - Русский \n/uz - O'zbekcha \n/en - English");
                    break;
                }  
        }
        else if($student['name'] == NULL) { 
            $setdistrict = $this->getlng($student['lang'], "setdistrict");
            $setname = $this->getlng($student['lang'], "setname");
            $success = $this->getlng($student['lang'], "success");
            $error = $this->getlng($student['lang'], "error");
            $text = $data['message']['text'];
            if(!stristr($text, '/')){
                $result = $db->query("update student set name = '".$text."' where id = ".$chat_id);
                if($result) {
                    $this->sendMessage($chat_id, $success);
                    $this->sendMessage($chat_id, $setdistrict);
                }
                else $this->sendMessage($chat_id, $error);
            }
            else $this->sendMessage($chat_id, $setname);
        }
        else if($student['district'] == NULL) { 
            $setschool = $this->getlng($student['lang'], "setschool");
            $setdistrict = $this->getlng($student['lang'], "setdistrict");
            $success = $this->getlng($student['lang'], "success");
            $error = $this->getlng($student['lang'], "error");
            $text = $data['message']['text'];
            if(!stristr($text, '/')){
                $result = $db->query("update student set district = '".$text."' where id = ".$chat_id);
                if($result) {
                    $this->sendMessage($chat_id, $success);
                    $this->sendMessage($chat_id, $setschool);
                }
                else $this->sendMessage($chat_id, $error);
            }
            else $this->sendMessage($chat_id, $setdistrict);
        }
        else if($student['school'] == NULL)  { 
            $setclass = $this->getlng($student['lang'], "setclass");
            $setschool = $this->getlng($student['lang'], "setschool");
            $success = $this->getlng($student['lang'], "success");
            $error = $this->getlng($student['lang'], "error");
            $text = $data['message']['text'];
            if(!stristr($text, '/')){
                $result = $db->query("update student set school = '".$text."' where id = ".$chat_id);
                if($result) {
                    $this->sendMessage($chat_id, $success);
                    $this->sendMessage($chat_id, $setclass);
                }
                else $this->sendMessage($chat_id, $error);
            }
            else $this->sendMessage($chat_id, $setschool);
        }
        else if($student['class'] == NULL) { 
            $welcome = $this->getlng($student['lang'], "welcome");
            $result = $db->query("select name from student where id = ".$chat_id);
            $row = $result->fetch(); $name = $row[0];
            $welcome = str_replace("+NAME+", $name, $welcome);   
            $setclass = $this->getlng($student['lang'], "setclass");
            $success = $this->getlng($student['lang'], "success");
            $error = $this->getlng($student['lang'], "error");                
            $text = $data['message']['text'];
            if(!stristr($text, '/')){
                $result = $db->query("update student set class = '".$text."', status = 1 where id = ".$chat_id);
                if($result) {
                    $result = $db->query("update putset set flag = NULL where id = ".$chat_id);
                    $this->sendMessage($chat_id, $success);
                    $this->sendMessage($chat_id, $welcome);
                }
                else $this->sendMessage($chat_id, $error);
            }
            else $this->sendMessage($chat_id, $setclass);
        }
    }

    private function cancel($chat_id){ //команда отмены
        $db = Db::getConnection();      
        $student = $this->getstudent($chat_id);
        $result = $db->query("update putset set flag = NULL where id = ".$chat_id);
        $classes = $this->getclasses($student['lang']); 
        $help = $this->getlng($student['lang'], "help");
        $this->sendMessage($chat_id, $help.$classes);
    }

    private function putclass($chat_id, $data){ //выбор класса
        $db = Db::getConnection();      
        $student = $this->getstudent($chat_id);
        $setclasses = $this->getlng($student['lang'], "setclasses"); 
        $setclasses = str_replace("+TEACH+", $data['message']['text'], $setclasses);
        $set = str_replace("/", "", $data['message']['text']);
        $result = $db->query("update putset set flag = '".$set."' where id = ".$chat_id);               
        $this->sendMessage($chat_id, $setclasses);
    }

    private function getlng($lang, $flag){ //получаем язык
        $db = Db::getConnection();
        $result = $db->query("select ".$lang." from lng where flag = '".$flag."'");
        $row = $result->fetch();
        return $row[0]; 
    }

    private function classes(){ //получаем уроки
        $db = Db::getConnection();
        $classes = array();
        $result = $db->query("select command from classes where active = 1");
        $i = 0;
        while($row = $result->fetch()){
            $classes[$i] = $row[0];
            $i++;
        }
        return $classes;
    }

    private function getclasses($lang){ //получаем описание уроков с описанием на выбранном языке
        $db = Db::getConnection();
        $classes = array();
        $result = $db->query("select command, ".$lang." from classes where active = 1");
        $i = 0;
        while($row = $result->fetch()){
            $classes[$i]['command'] = $row[0];
            $classes[$i]['lang'] = $row[1];
            $i++;
        }
        $text = "\n";
        foreach($classes as $class){
            $text .= $class['command']." - ".$class['lang']."\n";
        }
        return $text;
    }
    
    private function getPhoto($data, $chat_id){//получение фотографии
        $db = db::getConnection();         
               
        $student = $this->getstudent($chat_id);
        $success = $this->getlng($student['lang'], "success");
        $error = $this->getlng($student['lang'], "error");   
        $errdb = $this->getlng($student['lang'], "errdb");
        $flag = $this->getputset($chat_id);   
        // если пришла картинка то сохраняем ее у себя
        $data = $data['photo'];
        // берем последнюю картинку в массиве
        $file_id = $data[count($data) - 1]['file_id'];
        // получаем объект File
        $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);
        // возвращаем file_path
        $file_path =  $array['result']['file_path'];
    
        // ссылка на файл в телеграме
        $file_from_tgrm = "https://api.telegram.org/file/bot".$this->bottoken()."/".$file_path;
        // достаем расширение файла
        $ext =  end(explode(".", $file_path));
        // назначаем свое имя здесь время_в_секундах.расширение_файла
        $name_our_new_file = time().".".$ext;
        $file = "/upload/images/".$name_our_new_file;
        $sql = "insert into files(id, file, type, date, flag) values(:id, :file, 1, now(), :flag)";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $chat_id, PDO::PARAM_INT);
        $result->bindParam(':file', $file, PDO::PARAM_STR);
        $result->bindParam(':flag', $flag, PDO::PARAM_STR);
        $result->execute();
        if($result) { 
            if(copy($file_from_tgrm, "upload/images/".$name_our_new_file)){                        
                $result = $db->query("update putset set flag = NULL where id =".$chat_id);
                $this->sendMessage($chat_id, $success);                             
                $classes = $this->getclasses($student['lang']); 
                $help = $this->getlng($student['lang'], "help");
                $this->sendMessage($chat_id, $help.$classes);
            }
            else $this->sendMessage($chat_id, $error);                
        }
        else $this->sendMessage($chat_id, $errdb);  
    }

    private function getVideo($data, $chat_id){//получение видео
        $db = db::getConnection();
               
        $student = $this->getstudent($chat_id);
        $success = $this->getlng($student['lang'], "success");
        $error = $this->getlng($student['lang'], "error");  
        $errdb = $this->getlng($student['lang'], "errdb");    
        $errformat = $this->getlng($student['lang'], "errformat");    
        $flag = $this->getputset($chat_id);
        $type = $data['document']['mime_type'];
        if($type == 'video/mp4' || $type == 'video/quicktime' || $type == 'video/3gpp' || $type == 'video/x-msvideo' || $type == 'video/x-ms-wmv'){
            $file_id = $data['document']['file_id'];
            // получаем file_path
            $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);
            // возвращаем file_path
            $file_path = $array['result']['file_path'];
            // ссылка на файл в телеграме
            $file_from_tgrm = "https://api.telegram.org/file/bot".$this->bottoken()."/".$file_path;
            // достаем расширение файла
            $ext =  end(explode(".", $file_path));
            // назначаем свое имя здесь время_в_секундах.расширение_файла
            $name_our_new_file = time().".".$ext;
            $file = "/upload/video/".$name_our_new_file;
            $sql = "insert into files(id, file, type, date, flag) values(:id, :file, 2, now(), :flag)";
            $result = $db->prepare($sql);
            $result->bindParam(':id', $chat_id, PDO::PARAM_INT);
            $result->bindParam(':file', $file, PDO::PARAM_STR);
            $result->bindParam(':flag', $flag, PDO::PARAM_STR);
            $result->execute();
            if($result) { 
                if(copy($file_from_tgrm, "upload/video/".$name_our_new_file)) {
                    $result = $db->query("update putset set flag = NULL where id =".$chat_id);
                    $this->sendMessage($chat_id, $success);                     
                    $classes = $this->getclasses($student['lang']); 
                    $help = $this->getlng($student['lang'], "help");
                    $this->sendMessage($chat_id, $help.$classes);
                }
                else $this->sendMessage($chat_id, $error);  
            }
            else $this->sendMessage($chat_id, $errdb);         
        }
        else $this->sendMessage($chat_id, $errformat); 
    }
}