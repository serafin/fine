<?php

class c_index extends f_c_action 
{
    
    public function indexAction()
    {
        $oSetup = new c_setup();
        $oSetup->checkAction();
    }
    
}
