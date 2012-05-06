<?php

class f_form_decor_default
{

    const PLACEMENT_PREPEND = 'PLACEMENT_PREPEND';
    const PLACEMENT_APPEND  = 'PLACEMENT_APPEND';
    const PLACEMENT_EMBRACE = 'PLACEMENT_EMBRACE';

    /**
     * @var string
     */
    public $buffer;

    /**
     * @var f_form|f_form_element
     */
    public $object;

    protected $_decoration;
    protected $_decoration2;
    protected $_placement = self::PLACEMENT_APPEND;

    /**
     * @return f_form_decor_default
     */
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

    public function buffer($sBuffer = null)
    {
        if (func_num_args() === 0) {
            return $this->buffer;
        }
        $this->buffer = $sBuffer;
        return $this;
    }

    public function object($oObject = null)
    {
        if ($oObject === null) {
            return $this->object;
        }
        $this->object = $oObject;
        return $this;
    }

    public function decoration($sDecoration = null)
    {
        if ($sDecoration === null) {
            return $this->_decoration;
        }
        $this->_decoration = $sDecoration;
        return $this;
    }

    public function decoration2($sDecoration = null)
    {
        if ($sDecoration === null) {
            return $this->_decoration2;
        }
        $this->_decoration2 = $sDecoration;
        return $this;
    }

    public function placement($tPlacement = null)
    {
        if ($tPlacement === null) {
            return $this->_placement;
        }
        $this->_placement = $tPlacement;
        return $this;
    }

    public function render()
    {
        return $this->_render();
    }

    protected function _render()
    {
        switch ($this->_placement) {
            case self::PLACEMENT_PREPEND:
                return $this->_decoration . $this->_decoration2 . $this->buffer;

            case self::PLACEMENT_APPEND:
                return $this->buffer . $this->_decoration . $this->_decoration2;

            case self::PLACEMENT_EMBRACE:
                return $this->_decoration . $this->buffer. $this->_decoration2;

            default:
                throw new f_form_decor_exception_domain('Wrong value for placement property');
        }
    }

}
