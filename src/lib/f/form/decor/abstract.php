<?php

abstract class f_form_decor_abstract
{

    const PREPEND = 'PREPEND';
    const APPEND  = 'APPEND';
    const EMBRACE = 'EMBRACE';

    public $content;
    public $element;
    public $decoration;
    public $decoration2;
    

    protected $_placement = self::APPEND;
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
            case self::PREPEND:
                return $this->decoration . $this->decoration2 . $this->content;

            case self::APPEND:
                return $this->content . $this->decoration . $this->decoration2;

            case self::EMBRACE:
                return $this->decoration . $this->content. $this->decoration2;

            default:
                throw new f_form_decor_exception(array(
                    'type' => f_form_decor_exception::UNEXPECTED_VALUE,
                    'msg'  => 'Wrong value for placement property',
                ));
        }

    }

}
