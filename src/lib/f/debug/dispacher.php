<?php

class f_debug_dispacher extends f_c
{

    public static function _()
    {
        return new self;
    }


    public function register()
    {
        $this->_c->event->on(f_c_dispacher::EVENT_DISPACHER_PRE, array($this, 'log'));
    }

    public function log($event)
    {

        /* @var $dispacher f_c_dispacher */
        
        $dispacher = $event->subject();

        $log = get_class($dispacher->object()) 
             . '->' 
             . str_replace('{action}', $dispacher->action(), $dispacher->method());
        
        $this->_c->debug->log($log, 'f::$c->dispacher->run',
                              f_debug::LOG_TYPE_CODE_PHP, f_debug::LOG_STYLE_SYSTEM);

    }

}