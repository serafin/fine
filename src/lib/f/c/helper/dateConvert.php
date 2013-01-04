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
                    
//                        ? "(?(" . $formats[$char] . ")" . "(?P<" . preg_quote($char, $delimiter). ">" . $formats[$char] . ")". ")"
//                        ? "(?(" . $formats[$char] . ")" . "?P<" . preg_quote($char, $delimiter). ">" . $formats[$char] . "". ")"
//                          ?  "(?P<" . preg_quote($char, $delimiter). ">" . "(?(" . $formats[$char] . ")". $formats[$char] .")" .")"
//                          ? "(?P?<" . preg_quote($char, $delimiter). ">(".$formats[$char].")".$formats[$char].")"
                            
                    
                          : preg_quote($char, $delimiter) . '?';

        }
        
        $matches = array();
        preg_match($delimiter . $datePattern . $delimiter, $sInputDate, $matches);
        
        return date($sOutputFormat, mktime((int)$matches['H'], (int)$matches['i'], (int)$matches['s'], 
                                           (int)$matches['m'], (int)$matches['d'], (int)$matches['Y']));
    }
    
    /**
     * Konwertuje date pomiedzy dwa formatami
     * 
     * @param string $sInputFormat Format wejsciowy daty
     * @param string $sOutputFormat Format wyjsciowy daty
     * @param string $sInputDate Data
     * @return string Data $sInputDate w formacie $sOutputFormat
     */
    public function helper2($sInputFormat, $sOutputFormat, $sInputDate)
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
        
        
        $out = array();
        
        foreach ($formats as $formatKey => $formatPattern) {
            
            $datePattern = "";

            for ($i = 0, $end = strlen($sInputFormat); $i < $end; $i++) {

                $char = $sInputFormat[$i];

                $datePattern .= $char == $formatKey
                              ? "(?P<" . preg_quote($formatKey, $delimiter). ">" . $formatPattern . ")"
                              : preg_quote($char, $delimiter) . '?';

            }

            $matches = array();
            preg_match($delimiter . $datePattern . $delimiter, $sInputDate, $matches);
            
//            f_debug::dump($datePattern);
            
            $out[$formatKey] = $matches[$formatKey];
            
        }
        
        
        
        return date($sOutputFormat, mktime((int)$out['H'], (int)$out['i'], (int)$out['s'], 
                                           (int)$out['m'], (int)$out['d'], (int)$out['Y']));
    }
    
}