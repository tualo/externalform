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
            $ct=0;
            $mailText='Folgende neue Rezepte- und Überweisungswünsche sind auf der Weibseite eingetragen worden:\n';
            $db  = App::get('session')->getDB();
            $sql="select count(0) anzahl, 'Überweisungen ' Typ from ueberweisungen where mailsend = 0
                    union
                select count(0) anzahl, 'Rezepte ' Typ from rezepte where mailsend = 0";
            $infoData=$db->direct($sql,[]);
            foreach($infoData as $data){
                if($data['anzahl']>0){
                    $ct++;
                    $mailText.= $data['typ'].';  '.$data['anzahl'].'\n';
                }
            }
            if($ct>0){
                // insert in outgoing mails
                echo $mailText;
            }
            echo '<pre>';
            print_r($infoData);
            echo '</pre>';
        }catch(\Exception $e){
            syslog(LOG_WARNING, "InfoMail:  ".$e->getMessage()." - ".__LINE__." ".__FILE__." ");
        }
    }
}