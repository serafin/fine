<?php

class c_example_form_addElementBefore extends f_c_action
{
 
    public function indexAction()
	{
        
        $form = new f_form(array(
			'element' => array(
				new f_form_text(array('name' => 'news_title')),
				new f_form_textarea(array('name' => 'news_text')),
			)
		));
        
        $form->addElementBefore('news_title', new f_form_select(array(
            'name' => 'news_status',
            'option' => array('draft' => 'Draft', 'public' => 'Public')
        )));
        
        $this->render->off();
        $this->response->body = $form->render();
    
    }

}
 