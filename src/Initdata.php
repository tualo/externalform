<?php

namespace Tualo\Office\ExternalForm;
use Tualo\Office\ContentManagementSystem\CMSMiddleware;
use Tualo\Office\Basic\TualoApplication as App;

class Initdata extends CMSMiddleWare{
    public static function run(&$request,&$result){
        @session_start();
        try{
            if ($_SESSION['wa_session']['login']['loggedIn']==1){ // eventuell Rollen/Gruppen prüfen
                $db  = App::get('session')->getDB();
                $rezepte = $db->direct('select * from rezepte',[]);
                $result['rezepte']=$rezepte;

            }
        }catch(\Exception $e){
            
        }
        session_commit();
        
    }
}