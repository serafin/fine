<?php

class c_index extends f_c_action
{

    public function indexAction()
    {
        $form = new f_form(array(
            'action'  => 'index/index',
            'element' => array(
                new f_form_checkbox(array('name' => 'checkbox')),
                new f_form_checkbox(array('name' => 'checkbox2', 'option' => array('a' => 'A', 'b' => 'B'))),
                new f_form_file(array('name' => 'file')),
                new f_form_password(array('name' => 'password')),
                new f_form_radio(array('name' => 'radio', 'option' => array('a' => 'A', 'b' => 'B'))),
                new f_form_select(array('name' => 'select', 'option' => array('a' => 'A', 'b' => 'B'))),
                new f_form_submit(array('name' => 'submit')),
                new f_form_text(array('name' => 'text')),
                new f_form_textarea(array('name' => 'textarea')),
            ),

        ));

        echo $form->render();

        $this->render->off();
        $this->response->off();
    }

}