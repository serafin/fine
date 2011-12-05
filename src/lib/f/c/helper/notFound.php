<?php

/** @todo */

class f_c_helper_notFound
{

   	public static function helper()
	{
            if(is_file(f_load::$pathApp . 'c/404.php')){
                    $oController = new c_404;
                    $oController->indexAction();
            }
            exit;
	}

}

