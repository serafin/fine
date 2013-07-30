<?php

class f_c_helper_wash
{

    public function helper($sString)
    {
        $sString = $this->i18n($sString);
        $sString = $this->normalize($sString);
        
        return $sString;
    }
    
    public function i18n($sString)
    {
        return str_replace(
            array(
                // cyrylica
                'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы' ,'в', 'Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ъ', 'Ф', 'Ы', 'В',
                'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч' ,'с', 'м' ,'и' ,'т' ,'ь', 'б', 'ю', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Э', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю',
                // CZ, SK
                'á', 'ä', 'č', 'ď' ,'é', 'í', 'ĺ', 'ľ', 'ň', 'ô', 'ó', 'ŕ', 'š', 'ť', 'ú', 'ý', 'ž', 'Á', 'Ä', 'Č', 'Ď', 'É', 'Í', 'Ĺ', 'Ľ' ,'Ň' ,'Ô' ,'Ó', 'Ŕ', 'Š', 'Ť', 'Ú', 'Ý', 'Ž',
                // DE
                'ä',  'ö',  'ü',  'ß',  'Ä',  'Ö',  'Ü',
                // PL
                'ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż', 'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż',
            ), 
            array(
                // cyrylica
                'i', 'c', 'u', 'k', 'e', 'n', 'g', 'sz', 'szcz', 'z', 'ch', '', 'f', 'y', 'v', 'I', 'C', 'U', 'K', 'E', 'N', 'G', 'Sz', 'Szcz', 'Z', 'Ch', '', 'F', 'Y', 'V',
                'a', 'p', 'r', 'o', 'l', 'd', 'z', 'z', 'ja', 'c', 's', 'm', 'i', 't', '', 'b', 'ju','A', 'P', 'R', 'O', 'L', 'D', 'Z', 'Z', 'Ja', 'C', 'S', 'M', 'I', 'T', '', 'B', 'Ju',
                // CZ, SK
                'a', 'a', 'c', 'd', 'e', 'i', 'l', 'l', 'n', 'o', 'o', 'r', 's', 't', 'u', 'y', 'z', 'A', 'A', 'C', 'D', 'E', 'I', 'L', 'L', 'N', 'O', 'O', 'R', 'S', 'T', 'U', 'Y', 'Z',
                // DE
                'ae', 'oe', 'ue', 'ss', 'Ae', 'Oe', 'Ue',
                // PL
                'a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', 'A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z',
            ),
            $sString);
    }
    
    public function normalize($sString, $cSpace = '-', $sAllow = '.()')
    {
        // usuwanie wszystkich niedozwolonych znaków
        $sString = preg_replace('![^'.$cSpace.'a-zA-Z0-9'.$sAllow.'\s]+!',      '', $sString);

        // wiele znaków spacji obok siebie zostaje zastąpionych w jednym znakiem spacji
        $sString = preg_replace('!['.$cSpace.'\s]+!'                     , $cSpace, $sString);

        return $sString;
    }
    
}
