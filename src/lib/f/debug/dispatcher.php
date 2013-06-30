<?php

class f_debug_dispatcher extends f_c
{

    public static function _()
    {
        return new self;
    }


    public function register()
    {
        $this->_c->event->on(f_c_dispatcher::EVENT_DISPATCHER_PRE, array($this, 'log'));
    }

    public function log($event)
    {

        /* @var $dispatcher f_c_dispatcher */
        
        $dispatcher = $event->subject();

        $log = get_class($dispatcher->object()) 
             . '->' 
             . str_replace('{action}', $dispatcher->action(), $dispatcher->method());
        
        $this->_c->debug->log($log, 'f::$c->dispatcher->run',
                              f_debug::LOG_TYPE_CODE_PHP, f_debug::LOG_STYLE_SYSTEM);

    }

}