<?php

/** @todo jeden helper dla kontrollera i widoku */

class f_c_helper_flash
{

    public static function helper($sText, $aisRedirect = null, $sStatus = null)
    {
        $_SESSION['_flash'][] = array('text' => $sText, 'status' => $sStatus);
        if ($aisRedirect !== null) {
            f_c_helper::_()->redirect->helper($aisRedirect);
        }
    }

}
