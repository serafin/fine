<?php

class f_c_helper_redirect
{

    protected $_code = 302;
    protected $_exit = true;
    
    public function helper($asUri, $iRedirectCode = null)
    {
            
        if ($iRedirectCode !== null) {
            $this->code($iRedirectCode);
        }
            
        if (is_array($asUri)) {
            $asUri = f::$c->uri->abs($asUri);
        }
            
        f::$c->response
                ->redirect($asUri, $this->_code)
                ->sendHeader();
                
        if ($this->_exit) {
            if (f::$c->event->is('main_end')) {
                f::$c->event->run(new f_event(array('id' => 'main_end', 'subject' => $this)));
            }
            exit;
        }
        
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

}