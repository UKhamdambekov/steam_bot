<?php

class FilesController//контроллер файлов
{
    public function actionIndex()//изображения
    {   
        $files = files::get();
        require_once(ROOT."/views/pages/files.html");
        return true;
    }   
    
    public function actionDelete($id)//удаление файлов
    {   
        $delete = files::delete($id);
        if($delete) header("Location: /images");
        else return false;
    }     
}
?>