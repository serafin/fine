<?php

class f_c_helper_notFound extends f_c
{

    /**
     * 404 Not Found
     * @throws f_c_exception_notFound
     */
    public function helper()
    {
        throw new f_c_exception_notFound();
    }

    /**
     * Checks expression, if false the 404 Not Found
     *
     * @param type $bExpression
     * @return type
     * @throws f_c_exception_notFound
     */
    public function ifNot($bExpression)
    {
        if ($bExpression) {
            return;
        }
        throw new f_c_exception_notFound();
    }

}