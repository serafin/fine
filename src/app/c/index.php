<?php

class c_index extends f_c_action 
{
    
    public function indexAction()
    {
        
        throw new f_exception(array('msg' => 'Test exception', 'type' => f_exception::LOGIC));
        
        $this->response
                ->body('Hello World!')
                ->send();
        
    }
    
}
