<?php

class c_index extends f_c_action 
{
    
    public function indexAction()
    {
        $form = new f_form();


        echo $form->render();
        
    }

}
