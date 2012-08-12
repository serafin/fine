<?php

class m_post extends f_m
{

    public $post_id;
    public $post_id_post;
    public $post_id_user;
    public $post_id_user_edit;
    public $post_id_img;
    public $post_insert;
    public $post_title;

    public static function _()
    {
        return new self;
    }

}