<?php

class f_c_helper_noAccess 
{
    
    public function helper()
    {
        throw new f_c_exception_noAccess();
    }
    
}