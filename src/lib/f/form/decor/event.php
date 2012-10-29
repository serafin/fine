<?php

class f_form_decor_event extends f_form_decor_default
{

    /**
     * @var f_event Obiekt zdarzenia
     */
    protected $_event;

    /**
     * @return f_form_decor_event
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    /**
     * Pobiera/ustawia obiekt zdarzenia
     * 
     * @param f_event $event
     * @return f_form_decor_event|f_event
     */
    public function event(f_event $event = null)
    {
        if (func_num_args() == 0) {
            return $this->_event;
        }
        $this->_event = $event;
        return $this;
    }

    public function render()
    {

        $this->_event->subject($this);
        
        f::$c->event->run($this->_event);
        
        if ($this->_event->cancel()) {
            return $this->_event->val();
        }

        return $this->_render();
        
    }

}