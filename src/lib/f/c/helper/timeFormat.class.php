<?php

class f_c_helper_datetime2unixtime
{

    public static function helper($sDateTime)
    {
        return mktime(
            substr($sDateTime, 11, 2),
            substr($sDateTime, 14, 2),
            substr($sDateTime, 17, 2),
            substr($sDateTime,  5, 2),
            substr($sDateTime,  8, 2),
            substr($sDateTime,  0, 4)
        );
    }

}
