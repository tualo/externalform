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
                if(isset($_REQUEST['ueb'])){
                    $db->direct('update ueberweisungen set status=1, processed_datetime=now(),login={login} where id={id}',['id'=>$_REQUEST['rez'],'login'=>$_SESSION['wa_session']['login']['user']]);
                }
                $db->direct('update rezepte set status=2 where status=1 and datetime < now() + interval -10 DAY ',[]);
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
            from rezepte 
                where status < 2
                order by status, datetime",[]);
                $result['rezepte']=$rezepte;
                $db->direct('update ueberweisungen set status=2 where status=1 and datetime < now() + interval -10 DAY ',[]);
                $ueberweisungen = $db->direct("select 
                id,
                name,
                date_format(geburtsdatum,'%d.%m.%Y') geburtsdatum,
                strasse,
                plz,
                Ort,
                telefon,
                email,
                ueberweisungswunsch,
                login,
                date_format(datetime,'%d.%m.%Y %H:%i') datetime,
                date_format(processed_datetime,'%d.%m.%Y %H:%i') processed_datetime,
                status
            from ueberweisungen 
                where status < 2
                order by status, datetime",[]);
                $result['ueberweisungen']=$ueberweisungen;                
            }
        }catch(\Exception $e){
            
        }
        session_commit();
        
    }
}