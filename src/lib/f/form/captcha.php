<?php

class f_form_captcha extends f_form_element
{

    protected $_type      = 'captcha';
    protected $_helper    = 'formTextNoVal';
    protected $_attr      = array('class' => 'form-captcha');
    
    protected $_decorForm = array(
        'img'    => array('f_form_decor_tag', 'tag' => 'img', 'short' => true, 'attr' => array('src' => '/captcha/captcha')),
        'helper' => 'f_form_decor_helper',
        'label'  => 'f_form_decor_label',
        'error'  => 'f_form_decor_error',
        'desc'   => 'f_form_decor_desc',
        'tag'    => array('f_form_decor_tag', 'attr' => array('class' => 'form-element')),
    );
    
    public function __construct(array $config = array())
    {
        parent::__construct($config);
        
        $sName = $this->name() ? $this->name() : '_captcha';
        
        $this->required(true)
             ->filter(new f_filter_strtolower())
			 ->valid(
                new f_valid_equal(
                    array('equal' => strtolower($_SESSION[$sName]))
                )
			 );
             
        $this->_decorForm['img'] = array('f_form_decor_tag', 'tag' => 'img', 'short' => true, 'attr' => array('src' => '/captcha/'.$sName));
    }
    
    public function captchaUri($sUri)
    {     
        $this->decor('img')->attr(array('src' => $sUri));
    }
    
}