<?php

class f_c_helper_debugshow extends f_c
{

    public function helper()
    {
        $this->register();
    }

    public function show($event)
    {
        if ($this->env != 'dev' || $this->request->isAjax() || $this->request->isFlash()) {
            return;
        }
        $this->debug->show();
    }

    public function register()
    {
        $this->event->on(f_c_response::EVENT_RESPONSE_POST, array($this, show));
    }

}