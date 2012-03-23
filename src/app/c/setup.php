<?php

class c_setup extends f_c_action
{

    public function checkAction()
    {
     
        /** 
         * @todo sprawdzic prawa zapisu do ./data, ./tmp, ./cache - jezeli instieja
         * 
         */
        
    }
    
    public function modelAction()
    {
        if ($this->env != 'dev') {
            return;
        }
        
        /** @todo */
    }

}