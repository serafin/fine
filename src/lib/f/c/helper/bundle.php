<?php

class f_c_helper_bundle
{

    protected $_bundle = array();

    /**
     *
     * @param type $sLibraryName
     * @return f_c_helper_bundle
     */
    public function helper($sLibraryName)
    {
        return $this->init($sLibraryName);
    }

    /**
     *
     * @param type $sLibraryName
     * @return f_c_helper_bundle
     */
    public function init($sLibraryName)
    {
        if (isset($this->_bundle[$sLibraryName])) {
            return;
        }

        $class                        = "bundle_$sLibraryName";
        $this->_bundle[$sLibraryName] = new $class;
        return $this;
    }

}