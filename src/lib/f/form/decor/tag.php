<?php

class f_form_decor_tag extends f_form_decor_abstract
{

    protected $_placement    = self::PLACEMENT_EMBRACE;
    protected $_name         = 'div';
    protected $_attr         = array();
    protected $_short        = false;
    protected $_prepend      = '';
    protected $_append       = '';
    protected $_innerPrepend = '';
    protected $_innerAppend  = '';


    public function name($sName = null)
    {
        if (func_num_args() == 0) {
            return $this->_name;
        }
        $this->_name = $sName;
        return $this;
    }

    public function attr($aAttr = null)
    {
        if (func_get_args() == 0) {
            return $this->_attr;
        }
        $this->_attr = $aAttr;
        return $this;
    }

    public function short($bShort = null)
    {
        if (func_num_args() == 0) {
            return $this->_short;
        }
        $this->_short = $bShort;
        return $this;
    }

    public function prepend($sPrepend = null)
    {
        if (func_num_args() == 0) {
            return $this->_prepend;
        }
        $this->_prepend = $sPrepend;
        return $this;
    }

    public function append($sAppend = null)
    {
        if (func_num_args() == 0) {
            return $this->_append;
        }
        $this->_append = $sAppend;
        return $this;
    }

    public function innerPrepend($sInnerPrepend = null)
    {
        if (func_num_args() == 0) {
            return $this->_innerPrepend;
        }
        $this->_innerPrepend = $sInnerPrepend;
        return $this;
    }

    public function innerAppend($sAppend = null)
    {
        if (func_num_args() == 0) {
            return $this->_innerAppend;
        }
        $this->_innerAppend = $sAppend;
        return $this;
    }

    public function render()
    {
        $this->_prepateTag();
        return $this->_render();
    }

    protected function _prepateTag()
    {
        $attr = "";
        if ($this->_attr) {
            foreach ((array)$this->_attr as $k => $v) {
                $attr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"';
            }
        }

        if ($this->_short === true) {
            $this->_decoration  = "$this->_prepend<{$this->_name}{$attr} />$this->_append";
            $this->_decoration2 = "";
        }
        else {
            $this->_decoration  = "$this->_prepend<{$this->_name}{$attr}>$this->_innerPrepend";
            $this->_decoration2 = "$this->_innerAppend</{$this->_name}>$this->_append";
        }

    }

}