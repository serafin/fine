<?php

class f_c_helper_de2en
{

    /**
     *
     * @param type $sString
     * @return string
     */
    public static function helper($sString)
    {
        return str_replace(
            array('ä', 'ö', 'ü', 'ß',  'Ä', 'Ö', 'Ü'),
            array('a', 'o', 'u', 'ss', 'A', 'O', 'U'), 
            $sString
        );
    }

}
