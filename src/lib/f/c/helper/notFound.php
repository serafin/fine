<?php

class f_c_helper_notFound extends f_c
{

    public function helper()
    {
        throw new f_c_exception_notFound();
    }

    public function ifNot($bExpression)
    {
        if ($bExpression) {
            return;
        }
        throw new f_c_exception_notFound();
    }

}