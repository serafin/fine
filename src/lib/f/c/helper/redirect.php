<?php

class f_c_helper_redirect extends f_c
{

    protected $_code = 302;
    protected $_exit = true;
    
    public function helper($asUri)
    {
        $this->redirect($asUri);
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

    public function redirect($asUri)
    {
        $this->response
                ->redirect($asUri, $this->_code)
                ->sendHeader();

        if ($this->_exit) {
            exit;
        }
    }

    public function uri($asUri)
    {
        $this->redirect($this->_c->uri($asUri));
    }
}