<?php

class f_c_helper_token
{

    public static function helper()
    {
        return  md5(uniqid(rand(), true));
    }

}

