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
                        VALUES 
                            ('".$_REQUEST['name']."','".$_REQUEST['geburtsdatum']."','".$_REQUEST['strasse']."','".$_REQUEST['plz']."','".$_REQUEST['ort']."','".$_REQUEST['telefon']."','".$_REQUEST['email']."','".$_REQUEST['rezeptwunsch']."','" ,[]);

                } 
            if ( $_REQUEST['typ'] == 'ueberweisungen'){
                    // Formular Überweisung
                    // Überprüfung
                    $db->direct("insert into ueberweisungen (name, geburtsdatum, strasse, plz, Ort, telefon, email, ueberweisungswunsch) 
                        VALUES 
                            ('".$_REQUEST['name']."','".$_REQUEST['geburtsdatum']."','".$_REQUEST['strasse']."','".$_REQUEST['plz']."','".$_REQUEST['ort']."','".$_REQUEST['telefon']."','".$_REQUEST['email']."','".$_REQUEST['ueberweisungswunsch']."','" ,[]);                    
                } 

            }
        }catch(\Exception $e){
        }
    }
}