<?php

class f_c_helper_debugshow extends f_c
{

    /**
     *
     * @return f_c_helper_debugshow
     */
    public function helper()
    {
        $this->register();
        return $this;
    }

    public function show($event)
    {
        if ($this->env != 'dev' || $this->request->isAjax() || $this->request->isFlash()) {
            return;
        }
        $this->debug->show();
    }

    /**
     *
     * @return f_c_helper_debugshow
     */
    public function register()
    {
        $this->event->on(f_c_response::EVENT_RESPONSE_POST, array($this, show));
        return $this;
    }

}