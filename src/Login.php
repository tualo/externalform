<?php 
    namespace Tualo\Office\ExternalForm;
    use Tualo\Office\ContentManagementSystem\CMSMiddleware;
    use Tualo\Office\Basic\TualoApplication as App;

    class Login extends CMSMiddleware {
        public static function run(&$request,&$result){
            @session_start();
            // check dynamic fieldnames
            if( 
                isset($_REQUEST[$_SESSION['wa_session']['login']['usrOldID']]) 
                    && isset($_REQUEST[$_SESSION['wa_session']['login']['pwOldID']])
            ){  

                $sessionDB  = App::get('session')->db;
                $loginResult = $sessionDB->singleValue(
                    'select test_login({username},{password}) res',
                    [
                        'username'=>$_REQUEST[$_SESSION['wa_session']['login']['usrOldID']],
                        'password'=>$_REQUEST[$_SESSION['wa_session']['login']['pwOldID']]
                    ],
                    'res'
                );
                $username=$_REQUEST[$_SESSION['wa_session']['login']['usrOldID']];
                if ($loginResult==1){
                    $_SESSION['wa_session']['login']['loggedIn']=TRUE;
/*                    $_SESSION['wa_session']['login']['user']='Karl Knall';
                    $_SESSION['wa_session']['login']['role']='OberAdmin'; */
                    $login = $sessionDB->singleRow('select * from loginnamen where login = {login}',['login'=>$username]);
                    $groups = $sessionDB->singleValue('select JSON_ARRAYAGG(`group`) a from macc_users_groups where id={login}',$login,'a');
                    $_SESSION['wa_session']['login']['user']=$login['login'];
                    $_SESSION['wa_session']['login']['vorname']=$login['vorname'];
                    $_SESSION['wa_session']['login']['nachname']=$login['nachname'];
                    $_SESSION['wa_session']['login']['role']=json_decode($groups,true);
                    $_SESSION['wa_session']['login']['groups']=implode(' - ',json_decode($groups,true));
                    $result['message']=$result['message'].'<br> Eingaben OK -'; 
                    header('Location: ../re');
                    exit();                    
                } else {
                    $_SESSION['wa_session']['login']['loginFormError']++;
                    $result['message']=$result['message'].'<br> Eingaben NICHT OK -';
                }
                $result['message']=$result['message'].'<br> Formularfelder vorhanden -> passt -';

            } else {
                $result['message']=$result['message'].' -> Formularfelder NICHT vorhanden -> passt NICHT -';
                $_SESSION['wa_session']['login']['loginFormError']++;
                
            }
            if($_SESSION['wa_session']['login']['loginFormError'] > 3){
                header("HTTP/1.1 404 Not Found");
                exit();
            }
         }
    } 

