<?php

class c_test_error extends f_c_action
{
    public function indexAction()
    {
        $this->render->off();
        
        $tmp = array();
        echo $tmp[1];
        set_exception_handler(array($this, 'handleException'));

    }
    
    public function handleException($exception)
    {
        try {
            $this->exception = $exception;
            $this->msg       = $exception->getMessage();
            $this->code      = $exception->getCode();
            $this->file      = $exception->getFile();
            $this->line      = $exception->getLine();
            $this->trace     = $exception->getTrace();

            //f_debug::dump($this->exception);
            //f_debug::dump($this->exception instanceof ErrorException);
            //f_debug::dump(!($this->exception instanceof RuntimeException));
        }
        catch (Exception $e) {
            echo $e;
        }
    }
    
}