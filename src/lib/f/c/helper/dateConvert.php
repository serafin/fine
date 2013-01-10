<?php

class f_c_helper_dateConvert
{
    
    /**
     * Konwertuje date pomiedzy dwa formatami
     * 
     * @param string $sInputFormat Format wejsciowy daty
     * @param string $sOutputFormat Format wyjsciowy daty
     * @param string $sInputDate Data
     * @return string Data $sInputDate w formacie $sOutputFormat
     */
    public function helper($sInputFormat, $sOutputFormat, $sInputDate)
    {
        $delimiter = '#';
        
        $formats   = array(
            'Y' => '[0-9]{4}',
            'm' => '[0-9]{2}',
            'd' => '[0-9]{2}',
            'H' => '[0-9]{2}',
            'i' => '[0-9]{2}',
            's' => '[0-9]{2}',
        );
        
        $datePattern = "";
        
        for ($i = 0, $end = strlen($sInputFormat); $i < $end; $i++) {
            
            $char = $sInputFormat[$i];
            
            $datePattern .= isset($formats[$char])
                          ? "(?P<" . preg_quote($char, $delimiter). ">" . $formats[$char] . ")"
                          : preg_quote($char, $delimiter) . '?';

        }
        
        $matches = array();
        preg_match($delimiter . $datePattern . $delimiter, $sInputDate, $matches);
        
        return date($sOutputFormat, mktime((int)$matches['H'], (int)$matches['i'], (int)$matches['s'], 
                                           (int)$matches['m'], (int)$matches['d'], (int)$matches['Y']));
    }
    
}