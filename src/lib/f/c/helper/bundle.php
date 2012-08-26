<?php

class f_c_helper_bundle
{

    protected $_bundle = array();

    public function helper($sLibraryName)
    {
        $this->init($sLibraryName);
    }

    public function init($sLibraryName)
    {

        if (isset($this->_bundle[$sLibraryName])) {
            return;
        }

        $class                        = "bundle_$sLibraryName";
        $this->_bundle[$sLibraryName] = new $class;
        
    }

}