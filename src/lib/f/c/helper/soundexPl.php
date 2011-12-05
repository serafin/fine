<?php

class f_c_helper_soundexPl
{

    public static function helper($sString)
    {
        return substr(
            f_c_helper_pl2en::helper(
                strtolower($sString[0]))
                . str_replace(
                    array('a', 'ą', 'e', 'ę', 'i', 'o', 'ó', 'u', 'y', 'ch', 'h', 'b', 'p', 'c', 'ć', 'k', 'q', 'd', 't', 'l', 'ł', 'm', 'n', 'ń', 'r', 'g', 'j', 'x', 'z', 'ź', 'ż', 's', 'ś', 'f', 'v', 'w'),
                    array('', '', '', '', '', '', '', '', '', 'a', 'a', '1', '1', '2', '2', '2', '2', '3', '3', '4', '4', '5', '5', '5', '6', '7', '7', '8', '8', '8', '8', '8', '8', '9', '9', '9'), strtolower(substr($sString, 1, 5))
                )
                . '00000'
            , 0
            , 5
        );
    }

}
