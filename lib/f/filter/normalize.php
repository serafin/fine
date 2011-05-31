<?php

class f_filter_normalize extends f_filter_abstract
{

    protected $_space = '-';
    protected $_allow = '.()';

    public static function _()
    {
        return new self;
    }

    public function space($cSpace = null)
    {
        if ($cSpace === null) {
            return $this->_space;
        }
        $this->_space = $cSpace;
        return $this;
    }

    public function allow($acsAllow = null)
    {
        if ($acsAllow === null) {
            return $this->_allow;
        }

        if (is_array($acsAllow)) {
            $acsAllow = implode("", $acsAllow);
        }
        $this->_space = $acsAllow;

        return $this;
    }

    public function filter($sText)
    {
        return preg_replace( // wiele znakow spacji obok siebie zostaje zastąpionych jednym znakiem spacji
            '#[' . preg_quote($this->_space, '#') . '\s]+#',
            $this->_space,
            preg_replace( // usuwanie wszystkich niedozwolonych znakow
                '#[^' . preg_quote($this->_space, '#') . 'a-zA-Z0-9' . preg_quote($this->_allow, '#') . '\s]+#',
                '',
                $sText
            )
        );
    }
    
}