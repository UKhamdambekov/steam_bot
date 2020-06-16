<?php
    return array(    
        'settings/([0-9]+)' => 'site/edit/$1',//actionEdit в SiteController
        'settings' => 'site/settings',//actionSettings в SiteController

        'winner/([0-9]+)/([a-z]+)' => 'students/addwinner/$1/$2',  //actionAddWinner в StudentsController
        'winner' => 'students/winner',  //actionWinner в StudentsController

        'files/delete/([0-9]+)' => 'files/delete/$1', //actionDelete в FilesController
        'files' => 'files/index',  //actionIndex в FilesController 
        
        'classes/delete/([0-9]+)' => 'classes/delete/$1', //actionDelete в ClassesController 
        'classes/active/([0-9]+)' => 'classes/active/$1',  //actionActive в ClassesController 
        'classes/block/([0-9]+)' => 'classes/block/$1',  //actionBlock в ClassesController 
        'classes' => 'classes/index', //actionIndex в ClassesController
        
        'students/delete/([0-9]+)' => 'students/delete/$1', //actionDelete в StudentsController 
        'students/active/([0-9]+)' => 'students/active/$1',  //actionActive в StudentsController 
        'students/block/([0-9]+)' => 'students/block/$1',  //actionBlock в StudentsController 
        'students' => 'students/index',  //actionIndex в StudentsController 
        
        'users/delete/([0-9]+)' => 'user/delete/$1', //actionDelete в UserController
        'users/change/([0-9]+)' => 'user/change/$1', //actionChange в UserController
        'users/reset/([0-9]+)' => 'user/reset/$1', //actionReset в UserController
        'users/([0-9]+)' => 'user/$1', //actionView в UserController
        'users/add' => 'user/add', //actionAdd в UserController
        'users' => 'user/index', //actionIndex в UserController
        
        'profile' => 'site/profile', //actionProfile в SiteController
        'logout' => 'site/logout', //actionLogout в SiteController
        '404' => 'site/error', //actionError в SiteController
        '' => 'site/index', //actionIndex в SiteController
    );
?>