<?php

class c_error extends f_c
{

    public function error()
    {
        
        switch (get_class($this->error->exception)) {

            case f_c_exception_notFound:
                $this->notFoundAction();
                break;

            default:
                $this->internalError();
                break;
        }
        
    }
    
    public function notFoundAction()
    {
        $this->error->render(false);
        
        $this->render->off();
        
        $this->response
            ->code(404)
            ->body("404 Not Found")
            ->send();
    }

    public function internalError()
    {
        $this->render->off();
        
        if ($this->env == 'dev') {
            return;
        }
        $this->response
            ->code(500)
            ->body("500 Internal Server Error")
            ->send();
    }

}