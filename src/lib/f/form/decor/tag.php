<?php

class f_form_decor_tag extends f_form_decor_abstract
{


    protected $_placement = self::EMBRACE;
    protected $_name      = 'div';
    protected $_attr;
    protected $_short;


    public function name($sName = null)
    {
        if ($sName === null) {
            return $this->_name;
        }
        $this->_name = $sName;
        return $this;
    }

    public function attr($asAttr = null)
    {
        if ($asAttr === null) {
            return $this->_attr;
        }
        $this->_attr = $asAttr;
        return $this;
    }

    public function short($bShort = null)
    {
        if ($bShort === null) {
            return $this->_short;
        }
        $this->_short = $bShort;
        return $this;
    }

    public function render()
    {
        $attr = "";
        if (is_string($this->_attr)) {
            $attr = " " . $this->_attr;
        }
        else if (is_array($this->_attr)) {
            foreach ((array)$this->_attr as $k => $v) {
                $attr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"';
            }
        }

        if ($this->_short === true) {
            $this->decoration = "<{$this->_name}{$attr} />";
        }
        else {
            $this->decoration  = "<{$this->_name}{$attr}>";
            $this->decoration2 = "</{$this->_name}>";
        }

        $this->_render();
    }

}