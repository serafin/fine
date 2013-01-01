<?php

class c_example_form_img
{
 
    public function addAction()
	{
        
        // 1. Model
        
        
        // 2. Form
        
        $form = $this->_form();
        
        if ($_POST) {
            $form->val($_POST + $_FILES);
			if ($_FILES && !f_upload::_()->image()->error()) {
				$form->img->val(f_upload_tmp::_()->create()->option('80x80'));
			}
        }
        
        $form->img->val() 
            ? $form->img->decor('img')->attr('src', '/' . $form->img->val())
            : $form->img->removeDecor('img');


        // 3. Cancel
        
        if (isset($_POST['cancel'])) {
            $this->flash('Canceled.')->redirect(array('example_form_img', 'list'));
        }
		
        
        // 4. Save
        
		if ($_POST && $form->isValid()) {
            
            $news = new m_news();
            $news->val($_POST);
            $news->save();

            if ($form->img->val()) {
                $news
                    ->selectInserted()
                    ->fieldAndVal(array('news_img' => $this->token()))
                    ->save();
                
                f_upload_tmp::_()
                    ->path($form->img->val())
                    ->save("data/news/{$news->id()}_{$news->news_img}.jpg");
            }
		
            $this->flash('Done.')->redirect(array('example', 'list'));
            
		}
        
        
        // 5. View
        
        $this->v->form = $form;
	
	}
    
	public function editAction()
	{
        // 1. Model
        
        $news = new m_news();
        $news->select($_GET['id']);
        $this->notFound->ifNot($news->id());

        
        // 2. Form
        
        $form = $this->_form();
        
        $form->img->decor('img')->attr('src', "/data/news/{$news->id()}_{$news->news_img}_80x80.jpg");
        
        if ($_POST) {
            
            $form->val($_POST);
            
			if ($_FILES && !f_upload::_()->image()->error()) {
				$form->img->val(f_upload_tmp::_()->create()->option('80x80'));
			}
            
        }
        else {
            
            $form->val($news->val());
            
        }
        
        $form->img->val() 
            ? $form->img->decor('img')->attr('src', '/' . $form->img->val())
            : $form->img->removeDecor('img');


        // 3. Cancel
        
        if (isset($_POST['cancel'])) {
            $this->redirect(array('example_form_img', 'list'));
        }
        
		
        // 4. Save
        
		if ($_POST && $form->isValid()) {
            
            $news->val($_POST);
            $news->save();

            if ($form->img->val()) {
                
                $this->unlinkDataImg("data/news/{$news->id()}_{$news->news_img}.jpg");
                
                $news
                    ->fieldAndVal(array('news_img' => $this->token()))
                    ->save();
                
                f_upload_tmp::_()
                    ->path($form->img->val())
                    ->save("data/news/{$news->id()}_{$news->news_img}.jpg");
            }
		
            $this->flash('Done.')->redirect(array('example_form_img', 'list'));
		}
	
	}
    
    protected function _form()
    {
        return new f_form(array(
			'element' => array(
				new f_form_text(array('name' => 'news_title')),
				new f_form_file(array('name' => 'imgupload')),
				new f_form_hidden(array('name' => 'img')),
			)
		));
    
    }

}
 