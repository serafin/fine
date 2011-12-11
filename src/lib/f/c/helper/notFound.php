<?php

/** @todo */

class f_c_helper_notFound extends f_c
{

   	public function helper()
	{
            $this->error(f_error::ERROR_NOT_FOUND);
	}
        
        public function ifNot($bExpression) 
        {
            if ($bExpression) {
                return;
            }
            $this->error(f_error::ERROR_NOT_FOUND);
        }

}

