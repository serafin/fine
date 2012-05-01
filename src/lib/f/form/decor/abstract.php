<?php

abstract class f_form_decor_abstract
{

    const PLACEMENT_PREPEND = 'PLACEMENT_PREPEND';
    const PLACEMENT_APPEND  = 'PLACEMENT_APPEND';
    const PLACEMENT_EMBRACE = 'PLACEMENT_EMBRACE';

    public $buffor;
    public $object;

    protected $_decoration;
    protected $_decoration2;
    protected $_placement = self::PLACEMENT_APPEND;
    protected $_event;

    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function placement($tPlacement = null)
    {
        if ($tPlacement === null) {
            return $this->_placement;
        }
        $this->_placement = $tPlacement;
        return $this;
    }

    public function event($sEventId = null)
    {
        if ($sEventId === null) {
            return $this->_event;
        }
        $this->_event = $sEventId;
        return $this;
    }

    protected function _render()
    {

        if ($this->_event !== null) {
            f::$c->event->run($event = new f_event(array('id' => $this->_event, 'subject' => $this)));
            return $event->val;
        }        

        switch ($this->_placement) {
            case self::PLACEMENT_PREPEND:
                return $this->_decoration . $this->_decoration2 . $this->buffor;

            case self::PLACEMENT_APPEND:
                return $this->buffor . $this->_decoration . $this->_decoration2;

            case self::PLACEMENT_EMBRACE:
                return $this->_decoration . $this->buffor. $this->_decoration2;

            default:
                throw new f_form_decor_exception_domain('Wrong value for placement property');
        }

    }

}
