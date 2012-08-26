<?php

class f_v_helper_run
{

    public static function helper($sController, $sAction,  $aParams = null)
    {
		$sClass = "c_$sController";
		$oController = new $sClass;
		return call_user_func_array(array($oController, $sAction), $aParams);
    }

}

