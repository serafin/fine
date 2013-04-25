<?php

class f_v_helper_box
{

    public static function helper($sController, $sName,  $aParams = array())
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        
	$sClass = "c_$sController";
	$oController = new $sClass;
	return call_user_func_array(array($oController, $sName . 'Box'), $args);
    }

}