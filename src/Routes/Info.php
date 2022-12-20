<?php
namespace Tualo\Office\ExternalForm\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;



class Route implements IRoute{
    public static function register(){

        BasicRoute::add('/xyz/info',function($matches){
            App::contenttype('application/json');
            try{
                $ct=0;
                
                $mailText='Folgende neue Rezept- und Überweisungswünsche sind auf der Webseite eingetragen worden:\n';
                $db  = App::get('session')->getDB();
                $sql="select count(0) anzahl, 'Überweisungen ' Typ from ueberweisungen where mailsend = 0  and status=0
                        union
                    select count(0) anzahl, 'Rezepte ' Typ from rezepte where mailsend = 0  and status=0";
                $infoData=$db->direct($sql,[]);
                foreach($infoData as $data){
                    if($data['anzahl']>0){
                        $ct++;
                        $mailText.= $data['typ'].':  '.$data['anzahl'].PHP_EOL;
                    }
                }
                if($ct>0){
                    // insert in outgoing mails
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
                            'no-reply@tualo.de',
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

                }
                App::result('success',true);
            }catch(\Exception $e){
                App::result('msg',$e->getMessage());
                App::result('success',true);
            }
        },array('get','post'),true);
    }
}