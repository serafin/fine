<?php

class f_debug
{

    public static function source($sFile, $iLine, $iPaddingLines = 5)
    {
	if (!is_readable($sFile)) {
            return false;
        }
        $sFile  = fopen($sFile, 'r');
        $line   = 0;
        $begin  = $iLine - $iPaddingLines;
        $end    = $iLine + $iPaddingLines;
        $format = '% '.strlen($end).'d';
        $source = '';
        while (($row = fgets($sFile)) !== false) {
            if (++$line > $end) {
                break;
            }
            if ($line >= $begin) {
                $row = htmlspecialchars($row, ENT_NOQUOTES, 'utf-8');
                $row = '<span class="number">'.sprintf($format, $line).'</span> '.$row;
                if ($line === $iLine) {
                    $row = '<span class="line highlight">'.$row.'</span>';
                }
                else {
                    $row = '<span class="line">'.$row.'</span>';
                }
                $source .= $row;
            }
        }
        fclose($sFile);
        return '<pre class="debug_source"><code>'.$source.'</code></pre>';
    }
    
    public static function dump($mVar, $sLabel = null, $bEcho = true)
    {
        $label = ($sLabel === null) ? '' : '<strong style="color:red">'.trim($sLabel) . '</strong> ';
        
        $sOutput = '<pre style="background:black;color:#0f0;padding:5px;text-align:left;">' 
                  . $label 
                  . htmlspecialchars(self::varDumpPretty($mVar), ENT_QUOTES)
                  . '</pre>';
        
        if ($bEcho) {
            echo($sOutput);
            return;
        }
        
        return $sOutput;
    }

    public static function varDumpPretty($mVar)
    {
        ob_start();
        var_dump($mVar);
        return preg_replace("/\]\=\>\n(\s+)/m", "] => ", ob_get_clean());
    }
	

    

}
