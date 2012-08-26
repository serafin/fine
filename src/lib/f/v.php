<?php

class f_v
{

    public $_c;
    public $_defaultPath = 'app/v/script/';

//    public function  __get($sName)
//    {
//        return $this->{$sName} = $this->_c->{$sName};
//    }

    public function  __call($sName, $aArg)
    {
        return call_user_func_array(array($this->_c->{$sName}, 'helper'), $aArg);
    }

    public function viewDir($sDir = null)
    {
        if ($sDir === null) {
            return $this->_defaultPath;
        }
        $this->_defaultPath = $sDir;
        return $this;
    }

    public function render($sFile)
    {
        ob_start();
        include $this->_defaultPath . $sFile . '.php';
        return ob_get_clean();
    }

    public function renderPath($sFile)
    {
        ob_start();
        include $sFile . '.php';
        return ob_get_clean();
    }

}