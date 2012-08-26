<?php

class f_c_response extends f_c
{

    const EVENT_RESPONSE_PRE  = 'response_pre';
    const EVENT_RESPONSE_POST = 'response_post';

    public $body;
    
    protected $_header    = array();
    protected $_headerRaw = array();
    protected $_code      = 200;
    protected $_redirect  = false;
    protected $_sendOnce  = false;

    /**
     *
     * @param array $config
     * @return f_c_response
     */
    public static function _(array $config = array()) 
    {
        return new self();
    }
    
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }
    
    
    public function __toString()
    {
        ob_start();
        $this->send();
        return ob_get_clean();
    }

    public function toString()
    {
        ob_start();
        $this->send();
        return ob_get_clean();
    }

    public function header($sName = null, $sValue = null, $bReplace = false)
    {
        if ($sName === null) {
            return $this->_header;
        }

        $sName  = $this->_headerFormat($sName);

        if ($sValue === null) {
            return $this->_header[$sName];
        }

        if ($bReplace) {
            foreach ($this->_header as $k => $v) {
                if ($sName == $v['name']) {
                    unset($this->_header[$k]);
                }
            }
        }

        $this->_header[] = array(
            'name'    => $sName,
            'value'   => $sValue,
            'replace' => $bReplace
        );

        return $this;
    }

    public function redirect($sUri = null, $iCode = 302)
    {
        if ($sUri === null) {
            return $this->_redirect;
        }
        
        return $this
                ->header('Location', $sUri, true)
                ->code($iCode);
    }

    public function removeHeader($sName = null)
    {
        if ($sName === null) {
            $this->_header = array();
            return $this;
        }

        $sName = $this->_headerFormat($sName);
        foreach ($this->_header as $k => $v) {
            if ($sName == $v['name']) {
                unset($this->_header[$k]);
            }
        }

        return $this;
    }

    public function headerRaw($sHeader = null)
    {
        if ($sHeader === null) {
            return $this->_headerRaw;
        }

        if (strncmp('Location', $sHeader, 8) == 0) {
            $this->_redirect = true;
        }
        $this->_headerRaw[] = (string) $sHeader;
        return $this;
    }

    public function removeHeaderRaw($sHeader = null)
    {
        if ($sHeader === null) {
            $this->_headerRaw = array();
            return $this;
        }

        foreach ($this->_headerRaw as $k => $v) {
            if ($v == $sHeader) {
                unset($this->_headerRaw[$sHeader]);
            }
        }
        
        return $this;
    }

    public function code($iCode = null)
    {
        if ($iCode === null) {
            return $this->_code;
        }

        $this->_redirect = ($iCode >= 300 && $iCode <= 307);
        $this->_code     = $iCode;

        return $this;
    }

    public function body($sContent = null) 
    {
        if (func_num_args() == 0) {
            return $this->body;
        }
        $this->body = $sContent;
        return $this;
    }
    
    public function append($sContent)
    {
        $this->body .= $sContent;
        return $this;
    }

    public function prepend($sContent)
    {
        $this->body = $sContent . $this->body;
        return $this;
    }

    public function sendHeader()
    {
        if (!$this->_header && !$this->_headerRaw && $this->_code == 200) {
            return $this;
        }

        $bCodeSent = false;

        foreach ($this->_headerRaw as $i) {
            if (!$bCodeSent) {
                header($i, true, $this->_code);
                $bCodeSent = true;
            }
            else {
                header($i);
            }
        }

        foreach ($this->_header as $i) {
            if (!$bCodeSent) {
                header($i['name'] . ': ' . $i['value'], $i['replace'], $this->_code);
                $bCodeSent = true;
            }
            else {
                header($i['name'] . ': ' . $i['value'], $i['replace']);
            }
        }

        if (!$bCodeSent) {
            header('HTTP/1.1 ' . $this->_code);
        }

        return $this;
    }

    public function sendBody()
    {
        echo $this->body;
        return $this;
    }
    
    public function sendOnce()
    {
        if ($this->_sendOnce) {
            return;
        }
        $this->send();
    }
    
    public function off()
    {
        $this->_sendOnce = true;
    }
    
    public function send()
    {
        if ($this->event->is(self::EVENT_RESPONSE_PRE)) {
            $this->event->run($event = new f_event(array('id' => self::EVENT_RESPONSE_PRE, 'subject' => $this)));
            if ($event->cancel()) {
                return $this;
            }
        }
        
        $this->_sendOnce = true;
        $this->sendHeader();
        $this->sendBody();
        
        if ($this->event->is(self::EVENT_RESPONSE_POST)) {
            $this->event->run(new f_event(array('id' => self::EVENT_RESPONSE_POST, 'subject' => $this)));
        }
        
        return $this;
    }

    protected function _headerFormat($name)
    {
        return str_replace(' ', '-', ucwords(strtolower(str_replace(
            array('-', '_'),
            ' ',
            $name
       ))));
    }

}
