<?php

class f_form_decor_event extends f_form_decor_default
{

    protected $_event;

    /**
     * @return f_form_decor_event
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function event($sEventId = null)
    {
        if (func_num_args() == 0) {
            return $this->_event;
        }
        $this->_event = $sEventId;
        return $this;
    }

    protected function render()
    {

        f::$c->event->run(new f_event(array('id'      => $this->_event, 'subject' => $this)));

        return $this->_render();
        
    }

}