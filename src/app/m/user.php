<?php

class m_user extends f_m
{

    public $user_id;
    public $user_id_img;
    public $user_name;
    public $user_email;
    public $user_pass;

    public static function _()
    {
        return new self;
    }

}
