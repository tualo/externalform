<?php
/*
* Formulardaten entgegennehmen
*/
namespace Tualo\Office\ExternalForm;
use Tualo\Office\ContentManagementSystem\CMSMiddleware;
use Tualo\Office\Basic\TualoApplication as App;

class Insertdata extends CMSMiddleWare{
    public static function run(&$request,&$result){
        @session_start();
        try{
            $db  = App::get('session')->getDB();
            if ( isset($_REQUEST['typ']) ){ // klären DB Zugang
                if ( $_REQUEST['typ'] == 'rezept'){
                    // Formular Rezept
                    // Überprüfung eventuell über $_SERVER['REMOTE_ADDR']

                    $db->direct("insert into rezepte (name, geburtsdatum, strasse, plz, Ort, telefon, email, rezeptwunsch) 
                        VALUES ( {name}, 
                                 {geburtsdatum},
                                 {strasse},
                                 {plz},
                                 {ort},
                                 {telefon},
                                 {email},
                                 {rezeptwunsch}
                                )",[
                                    'name'=>$_REQUEST['name'],
                                    'geburtsdatum'=>$_REQUEST['geburtsdatum'],
                                    'strasse' => $_REQUEST['strasse'],
                                    'plz'=>$_REQUEST['plz'],
                                    'ort'=>$_REQUEST['ort'],
                                    'telefon'=>$_REQUEST['telefon'],
                                    'email'=>$_REQUEST['email'],
                                    'rezeptwunsch'=>$_REQUEST['rezeptwunsch']

                                ]);
                                header('Location:https://hausarztpraxis-roedental.de/vielen-dank-rezept/');
                                exit();
                } 
            if ( $_REQUEST['typ'] == 'ueberweisung'){
                    // Formular Überweisung
                    // Überprüfung
                    $db->direct("insert into ueberweisungen (name, geburtsdatum, strasse, plz, Ort, telefon, email, ueberweisungswunsch) 
                        VALUES ( {name}, 
                                 {geburtsdatum},
                                 {strasse},
                                 {plz},
                                 {ort},
                                 {telefon},
                                 {email},
                                 {ueberweisungswunsch}
                                )",[
                                    'name'=>$_REQUEST['name'],
                                    'geburtsdatum'=>$_REQUEST['geburtsdatum'],
                                    'strasse' => $_REQUEST['strasse'],
                                    'plz'=>$_REQUEST['plz'],
                                    'ort'=>$_REQUEST['ort'],
                                    'telefon'=>$_REQUEST['telefon'],
                                    'email'=>$_REQUEST['email'],
                                    'ueberweisungswunsch'=>$_REQUEST['ueberweisungswunsch']

                                ]);
                                header('Location:https://hausarztpraxis-roedental.de/vielen-dank-ueberweisung/');
                                exit();                   
                } 

            }
        }catch(\Exception $e){
            syslog(LOG_WARNING, "RezepteFormular ".$e->getMessage()." - ".__LINE__." ".__FILE__." ");
        }
    }
}