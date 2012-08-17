<?php

/**
 * @todo dodac obsluge callbacka przez event
 */
class f_filter_callback
{

    protected $_event;
    protected $_dispacher;
    protected $_subject;
    protected $_callback;

    public static function _()
    {
        return new self;
    }

    public function event($sEventId = null)
    {
        if (func_num_args() == 0) {
            return $this->_event;
        }
        $this->_event = $sEventId;
        return $this;
    }

    public function dispacher($oEventDispacher = null)
    {
        if (func_num_args() == 0) {
            return $this->_dispacher;
        }
        $this->_dispacher = $oEventDispacher;
        return $this;
    }

    public function subject($oEventSubject = null)
    {
        if (func_num_args() == 0) {
            return $this->_subject;
        }
        $this->_subject = $oEventSubject;
        return $this;
    }

    public function callback($kCallback = null)
    {
        if (func_num_args() == 0) {
            return $this->_callback;
        }
        $this->_callback = $kCallback;
        return $this;
    }

    public function filter($mInput)
    {
        // event dispacher
        if ($this->_event !== null) {

            /* @var $dispacher f_event_dispacher */

            $dispacher = $this->_dispacher !== null ? $this->_dispacher : f::$c->event;
            $subject   = $this->_subject !== null ? $this->_subject : $this;
            $event     = new f_event(array('id' => $this->_event, 'subject' => $subject, 'val' => $mInput));

            $dispacher->run($event);

            return $event->val();

        }

        // simple callback
        return call_user_func($this->_callback, $mInput);
    }

}