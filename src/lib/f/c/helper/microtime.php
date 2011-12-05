<?php

class f_c_helper_microtime
{

    public static function helper()
    {
        $aTime = explode(" ", microtime());
        return ((float) $aTime[0] + (float) $aTime[1]);
    }

}
