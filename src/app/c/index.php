<?php

class c_index extends f_c_action 
{
    
    public function indexAction()
    {
        
        throw new f_exception();
        
        $this->response
                ->body('Hello World!')
                ->send();
        
    }
    
}
