<?php

require "lib/f/autoload/includePath.php";

f_autoload_includePath::_()
    ->path(array('.', 'app/', 'lib/'))
    ->register();


class index extends f_c
{

    public function __construct()
    {
        
        // init main app container
        f::$c = new container();

        // init error & exception handler
        $this->error->register();
        
        // session
        session_start();

        // do something for diffrent environment (like debugging)
        $this->{$this->env}();

        // pretty uris
        $_GET = $this->uri->resolve($_SERVER['REQUEST_URI']);

        // run controller action by request
        $this->dispacher->run();

        // render if not renderd before (render attaches output result to response)
        $this->render->renderOnce();

        // send response to client if not send before
        $this->response->sendOnce();
        
    }

    public function dev()
    {
        $this->debug;
    }

    public function prod()
    {

    }

}

new index();