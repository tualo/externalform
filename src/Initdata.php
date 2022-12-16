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
                if(isset($_REQUEST['rez'])){
                    $db->direct('update rezepte set status=1, processed_datetime=now(),login={login} where id={id}',['id'=>$_REQUEST['rez'],'login'=>$_SESSION['wa_session']['login']['user']]);
                }
                $rezepte = $db->direct("select 
                id,
                name,
                date_format(geburtsdatum,'%d.%m.%Y') geburtsdatum,
                strasse,
                plz,
                Ort,
                telefon,
                email,
                rezeptwunsch,
                login,
                date_format(datetime,'%d.%m.%Y %H:%i') datetime,
                date_format(processed_datetime,'%d.%m.%Y %H:%i') processed_datetime,
                status
            from rezepte order by status, datetime",[]);
                $result['rezepte']=$rezepte;
            }
        }catch(\Exception $e){
            
        }
        session_commit();
        
    }
}