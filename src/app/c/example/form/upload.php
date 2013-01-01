<?php

class c_example_form_upload extends f_c_action
{
 
    public function tmpAction()
	{
        
        $form = new f_form(array(
			'element' => array(
				new f_form_hidden(array('name' => 'img', 'decor' => array(
                                            'helper' => new f_form_decor_helper(),
                                            'img' => new f_form_decor_tag(array('tag' => 'img', 'short' => true))
                                        ))),
				new f_form_file(array('name' => 'upload')),
				new f_form_submit(array('val' => 'Upload!')),
			)
		));
        
        $form->val($_POST + $_FILES);
        
        if ($_FILES && f_upload::_()->is()) {
            $form->img->val(f_upload_tmp::_()->create()->path());
        }
        
        $form->img->val() 
            ? $form->img->decor('img')->attr('src', '/' . $form->img->val())
            : $form->img->removeDecor('img');
        
        
        $this->render->off();
        $this->response->body = $form->render();
    
    }

}
 