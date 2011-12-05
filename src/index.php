<?php

require "lib/f/autoload/includePath.php";

f_autoload_includePath::_()
    ->path(array('.', 'app/m/', 'app/', 'lib/'))
    ->register();


class index extends f_c
{

    public function __construct()
    {
        
        // init main app cointainer
        f::$c = new app_container();

        // init error & exception handler
        $this->error->register();
        
        // do something for diffrent environment (like debugging)
        $this->{$this->env}();

        // session
        //session_start();

        
        echo 5;
        // run controller action by request
        $this->dispacher->run();

        echo 6;
        // render if not renderd before (render attaches output result to response)
        $this->render->renderOnce();

        echo 7;
        // send response to client if not send before
        $this->response->sendOnce();

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


new index();
