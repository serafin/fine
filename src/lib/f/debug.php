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
    
    public function log($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_LOG, 'label' => $sLabel, 'data' => $mData));
    }
    
    public function warn($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_WARN, 'label' => $sLabel, 'data' => $mData));
    }
    
    public function error($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_ERROR, 'label' => $sLabel, 'data' => $mData));
    }
    
    public function table($mData, $sLabel = null)
    {
        return $this->logRaw(array('type' => self::LOG_TABLE, 'label' => $sLabel, 'data' => $mData));
    }
    
    public function group($sLabel)
    {
        return $this->logRaw(array('type' => self::LOG_GROUP, 'label' => $sLabel));
    }

    public function groupEnd()
    {
        return $this->logRaw(array('type' => self::LOG_GROUP_END));
    }
    
    public function show($sViewScriptPath = './lib/f/debug/show.view')
    {
        $oView = new f_v();
        $oView->log = $this->_log;
        echo $oView->renderPath($sViewScriptPath);
    }

}