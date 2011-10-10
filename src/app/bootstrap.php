<?php

class app_bootstrap extends f_c
{

    public function __construct()
    {
        // init error & exception handler
        $this->error;

        // do something for diffrent environment (like debugging)
        $this->{$this->env}();

        // session
        //$this->session->start();

        // init request
        $this->request;
        
        // modify request (talking links)
        $this->router->run();
        

        // run controller action by request
        $this->dispacher->run();

        // automatic view render if not renderd before + attach result to response
        $this->render->auto();

        // send response to client if not send before
        $this->response->send();

        // don't user exit 
        $this->event->run('main_end');
    }

    public function dev()
    {
        $this->debug->init();
    }

    public function prod()
    {

    }

}
