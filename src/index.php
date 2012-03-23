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
        f::$c = new container();

        // init error & exception handler
        $this->error->register();
        
        // do something for diffrent environment (like debugging)
        $this->{$this->env}();

        // session
        //session_start();
        
        // pretty uris
        $this->uri->resolveRequestUri();
        
        // run controller action by request
        $this->dispacher->run();

        // render if not renderd before (render attaches output result to response)
        //$this->render->renderOnce();

        // send response to client if not send before
        //$this->response->sendOnce();
        
    }

    public function dev()
    {

    }

    public function prod()
    {

    }

}

new index();