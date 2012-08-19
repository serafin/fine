<?php

class f_debug
{
    
    const LOG_LOG       = 'LOG_LOG';
    const LOG_WARN      = 'LOG_WARN';
    const LOG_ERROR     = 'LOG_ERROR';
    const LOG_TABLE     = 'LOG_TABLE';
    const LOG_GROUP     = 'LOG_GROUP';
    const LOG_GROUP_END = 'LOG_GROUP_END';
    
    protected $_log = array();
    protected $_phpPV = array('_COOKIE' => '', '_ENV' => '', '_FILES' => '', '_POST' => '',
                              '_GET' => '', '_REQUEST' => '', '_SERVER' => '', '_SESSION' => '');
    protected $_offset;
    protected $_timer;

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
                $row = '<span class="f_debug-number">'.sprintf($format, $line).'</span> '.$row;
                if ($line === $iLine) {
                    $row = '<span class="f_debug-line f_debug-highlight">'.$row.'</span>';
                }
                else {
                    $row = '<span class="f_debug-line">'.$row.'</span>';
                }
                $source .= $row;
            }
        }
        fclose($sFile);
        return '<pre class="box-f_debug">'.$source.'</pre>';
    }
    
    public static function dump($mVar, $sLabel = null, $bEcho = true)
    {
        $label = ($sLabel === null) ? '' : '<span style="color:#666; font-family:monospace;">'.trim($sLabel) . '</span> ';
        
        $sOutput = '<pre style="background:black;margin:5px 0px 0 0px;color:#0f0;padding:10px;text-align:left;border-radius:5px;">' 
                  . $label 
                  . htmlspecialchars(self::varDumpPretty($mVar), ENT_QUOTES)
                  . '</pre>';
        
        if ($bEcho) {
            echo($sOutput);
            return;
        }
        
        return $sOutput;
    }

    public static function dumpFunctionArgs($aArgs, $iArgMaxLenght = 30, $iArgsLimit = 3)
    {

        if (! $aArgs) {
            return '';
        }

        

        $return = array();
        
        for ($i = 0, $end = count($aArgs); $i < $end && $i < $iArgsLimit; $i++) {
            
            $arg = $aArgs[$i];
            
            if (is_bool($arg)) {
                $return[] = $arg ? 'true' : 'false';
            }
            else if (is_float($arg) || is_int($arg)) {
                $return[] = $arg;
            }
            else if (is_string($arg)) {
                if (strlen($arg) > $iArgMaxLenght) {
                    $arg = substr($arg, 0, $iArgMaxLenght) . '...';
                }
                $return[] = "'" . $arg . "'";
            }
            else {
                $arg = str_replace("\n", '', self::varDumpPretty($arg));
                if (strlen($arg) > $iArgMaxLenght) {
                    $arg = substr($arg, 0, $iArgMaxLenght) . '...';
                }
                $return[] =  $arg;
            }
            
        }

        $return = implode(', ', $return);
     
        return $return;
    }



    public static function varDumpPretty($mVar)
    {
        ob_start();
        var_dump($mVar);
        return preg_replace("/\]\=\>\n(\s+)/m", "] => ", ob_get_clean());
    }
    
    public function __construct(array $config = array())
    {
        $this->_offset = new f_timer();
        $this->_offset->start();
        
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
        
    }
    
    public function timer()
    {
        $this->_timer = new f_timer();
        $this->_timer->start();
    }

    /* log */

    public function logRaw($aLog = null)
    {
        if ($aLog === null) {
            return $this->_log;
        }
        
        if ($this->_timer) {
            $aLog['time'] = $this->_timer->stop()->get();
            $this->_timer = null;
        }
        
        $aLog['offset'] = $this->_offset->get();
            
        $this->_log[] = $aLog;
        return $this;
    }

    public function phpPredefinedVariables()
    {
        $this->group('PHP Predefined Variables');
        foreach ($this->_phpPV as $k => $v) {
            $this->log($GLOBALS[$k], '$' . $k);
            $this->_phpPV[$k] = md5(self::varDumpPretty($GLOBALS[$k]));
        }
        $this->groupEnd();
    }

    public function phpPredefinedVariablesChange()
    {
        reset($this->_phpPV);
        if (strlen(current($this->_phpPV)) == 0) {
            return;
        }

        $diff = array();

        foreach ($this->_phpPV as $k => $v) {
            if ($v == md5(self::varDumpPretty($GLOBALS[$k]))) {
                continue;
            }
            $diff[] = $k;
        }

        if (!$diff) {
            return;
        }

        $this->group('PHP Predefined Variables - Change');
        foreach ($diff as $i) {
            $this->log($GLOBALS[$i], '$' . $i);
        }
        $this->groupEnd();
    }


    
    public function log($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_LOG, 'label' => $sLabel, 'data' => $mData));
    }
    
    public function warn($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_WARN, 'label' => $sLabel, 'data' => $mData, 'style' => self::LOG_WARN));
    }
    
    public function error($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_ERROR, 'label' => $sLabel, 'data' => $mData, 'style' => self::LOG_ERROR));
    }
    
    public function table($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_TABLE, 'label' => $sLabel, 'data' => $mData));
    }
    
    public function group($sLabel, $mPreview = null)
    {
        return $this->logRaw(array('type' => self::LOG_GROUP, 'label' => $sLabel, 'data' => $mPreview));
    }

    public function groupEnd()
    {
        return $this->logRaw(array('type' => self::LOG_GROUP_END));
    }
    
    public function show($sViewScriptPath = './lib/f/debug/show.view')
    {
        $this->phpPredefinedVariablesChange();
        
        $oView = new f_v();
        $oView->log = $this->_log;
        echo $oView->renderPath($sViewScriptPath);
    }

}