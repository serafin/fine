<?php

class c_example extends f_c_action
{
    
    public function connectionCloseWithHelperAction()
    {
        return;
        
        $this->render
            ->view('example/connectionCloseWithHelperAction')
            ->render();
        
        $this->connectionClose();
        
        // do some stuff ...
    }
    
    public function connectionCloseRawAction()
    {
        return;
        
        $this->render
            ->view('example/connectionCloseRaw')
            ->render();
        
        $this->response
            ->header('Content-Length', mb_strlen($this->response->body))
            ->header('Connection', 'close')
            ->send();
        
        // do some stuff ...
        
    }
    
    
    
}