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
                App::result('success',true);
            }catch(\Exception $e){
                App::result('msg',$e->getMessage());
                App::result('success',true);
            }
        },array('get','post'),true);
    }
}