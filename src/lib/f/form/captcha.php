<?php

class f_form_captcha extends f_form_element
{

    protected $_type       = 'captcha';
    protected $_viewHelper = 'formCaptcha';
    protected $_attr       = array('class' => 'captcha');
    protected $_ignore     = true;

}