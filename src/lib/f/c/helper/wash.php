<?php

class f_c_helper_wash
{

    public static function helper($sString)
    {
        $sString = self::pl2en($sString);
        $sString = self::de2en($sString);
        $sString = self::normalize($sString);
        return $sString;
    }

    public static function pl2en($sString)
    {
        return str_replace(
            array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż', 'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż'),
            array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', 'A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z'), 
            $sString
        );
    }
    
    public static function de2en($sString)
    {
        return str_replace(
            array('ä',  'ö',  'ü',  'ß',  'Ä',  'Ö',  'Ü'),
            array('ae', 'oe', 'ue', 'ss', 'Ae', 'Oe', 'Ue'), 
            $sString
        );
    }
    
    public static function normalize($sString, $cSpace = '-', $sAllow = '.()')
    {
        
        // usuwanie wszystkich niedozwolonych znaków
        $sString = preg_replace('![^'.$cSpace.'a-zA-Z0-9'.$sAllow.'\s]+!',      '', $sString);

        // wiele znaków spacji obok siebie zostaje zastąpionych w jednym znakiem spacji
        $sString = preg_replace('!['.$cSpace.'\s]+!'                     , $cSpace, $sString);

        return $sString;
    }
    
}
