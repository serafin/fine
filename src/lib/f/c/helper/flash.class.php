<?php

/**
 * @todo dokonczyc, sprawdzic czy & dziala
 * metody god to zle metody
 */
class f_c_helper_flash
{
    
    protected $_storage;

    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function helper($sText, $sStatus = null)
    {
        $this->add($sText, $aisRedirect, $sStatus);
    }

    public function storage(&$storage = null)
    {
        if (func_num_args() == 0) {
            return $this->_storage;
        }
        $this->_storage = &$storage;
        return $this;
    }

    public function add($sText, $aisRedirect = null, $sStatus = null)
    {
        $_SESSION['_flash'][] = array('text' => $sText, 'status' => $sStatus);
        if ($aisRedirect !== null) {
            f_c_helper::_()->redirect->helper($aisRedirect);
        }
    }

}

f_c_helper_flash::_()
        ->msg('m1', $status, $params)
        ->status('')
        ->param('a', 'b')
        ->uri()
        ->redirect();