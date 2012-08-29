<?php

class f_c_helper_redirect extends f_c
{

    protected $_code = 302;
    protected $_exit = true;
    
    public function helper($asUri = '')
    {
        $this->uri($asUri);
    }

    public function code($iCode = null)
    {
        if ($iCode === null) {
            return $this->_code;
        }
        $this->_code = $iCode;
        return $this;
    }

    public function exitScript($bExitScript = null)
    {
        if ($bExitScript === null) {
            return $this->_exit;
        }
        $this->_exit = (boolean)$bExitScript;
        return $this;
    }

    public function raw($sUri)
    {
        $this->response
                ->redirect($sUri, $this->_code)
                ->sendHeader();

        if ($this->_exit) {
            exit;
        }
    }

    public function uri($asUri = '')
    {
        $this->raw($this->_c->uri->assembleAbs($asUri));
    }
}