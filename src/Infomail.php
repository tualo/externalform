<?php
/*
* Formulardaten entgegennehmen
*/
namespace Tualo\Office\ExternalForm;
use Tualo\Office\ContentManagementSystem\CMSMiddleware;
use Tualo\Office\Basic\TualoApplication as App;

class Infomail extends CMSMiddleWare{
    public static function run(&$request,&$result){
        @session_start();
        try{
            $db  = App::get('session')->getDB();
            $sql="select count(0) anzahl, 'Überweisungen ' Typ from ueberweisungen where mailsend = 0
                    union
                select count(0) anzahl, 'Rezepte ' Typ from rezepte where mailsend = 0";
            $infoData=$db->direct($sql,[]);
            print_r($infoData);
        }catch(\Exception $e){
            syslog(LOG_WARNING, "InfoMail:  ".$e->getMessage()." - ".__LINE__." ".__FILE__." ");
        }
    }
}