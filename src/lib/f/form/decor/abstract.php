<?php

abstract class f_form_decor_abstract
{

    protected $_element;
    protected $_option = array('place' => 'append', 'separator' => '');

    public function __construct($aConfig = array())
    {
        foreach ($aConfig as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function element($oElement)
    {
        $this->_element = $oElement;
    }

    public function option($asKey, $mVal = null)
    {
        if ($mVal === null) {
            return $this->_option[$sKey];
        }
        $this->_option[$sKey] = $mVal;
        return $this;
    }

    public function clearOption($sKey)
    {
        unset($this->_option[$sKey]);
    }

    protected function _render($sRender, $sDecor)
    {
        return
            $this->_option['place'] == 'prepend'
                ? $sDecor  . $this->_option['separator'] . $sRender
                : $sRender . $this->_option['separator'] . $sDecor;
    }

}