<?php

class f_c_helper_responseAjax
{

    public function helper()
    {
        $this->register();
    }

    public function register()
    {
        f::$c->render->off();
        
        f::$c->event->on(f_c_response::EVENT_RESPONSE_PRE, array($this, 'responsePre'));
    }

    public function responsePre(f_event $event)
    {
        /* @var $response f_c_response */
        
        $response = $event->subject();

        $response->header('Content-Type', 'application/json');
        $response->body = json_encode($response->body);
    }

}