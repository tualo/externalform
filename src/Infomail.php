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
            $mailText='Folgende neue Rezepte- und Überweisungswünsche sind auf der Webseite eingetragen worden:\n';
            $db  = App::get('session')->getDB();
            $sql="select count(0) anzahl, 'Überweisungen ' Typ from ueberweisungen where mailsend = 0
                    union
                select count(0) anzahl, 'Rezepte ' Typ from rezepte where mailsend = 0";
            $infoData=$db->direct($sql,[]);
            foreach($infoData as $data){
                if($data['anzahl']>0){
                    $ct++;
                    $mailText.= $data['typ'].':  '.$data['anzahl'].PHP_EOL;
                }
            }
            if($ct>0){
                // insert in outgoing mails
                echo $mailText;
                $insSQL="insert into outgoing_mails (
                    id,
                    send_from,
                    send_from_name,
                    send_to,
                    reply_to,
                    reply_to_name,
                    subject,
                    create_date,
                    body
                    )VALUES(
                        uuid(),
                        'no-replay@hausarztpraxis-roedental.de',
                        'Webmaster',
                        'team@hausarztpraxis-roedental.de',
                        'steffen.borchmann@tualo.de',
                        'SBO',
                        'Nachricht von Webseite',
                        now(),
                        '".$mailText."')";
                // echo PHP_EOL.$insSQL.PHP_EOL;
                $db->direct($insSQL,[]);        
                $db->direct('update rezepte set mailsend=1',[]);
                $db->direct('update ueberweisungen set mailsend=1',[]);
                // set mailsend to 1
            }
            echo '<pre>';
            print_r($infoData);
            echo '</pre>';
        }catch(\Exception $e){
            syslog(LOG_WARNING, "InfoMail:  ".$e->getMessage()." - ".__LINE__." ".__FILE__." ");
        }
    }
}