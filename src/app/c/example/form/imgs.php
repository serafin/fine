<?php

class c_example_form_imgs
{
 
    public function addAction()
	{
        
        // 1. Model
        
        
        // 2. Form
        
        $form = $this->_form();
        
        if ($_POST) {
            
            $this->_formAttachImgs($form, (array)$_POST['img']); // przepisanie img z POST do formularza
            
            $form->val($_POST + $_FILES);
            
			if ($_FILES && !f_upload::_()->image()->error()) { // dodanie nowego img z POST i FILES
                
                $this->_formAttachImg($form, max(array_keys((array)$_POST['img'])) + 1, array(
                    'img_title' => $_POST['img_title'],
                    'path'      => f_upload_tmp::_()->create()->option('80x80'),
                ));
			}
        }
        
        // 3. Cancel
        
        if (isset($_POST['cancel'])) {
            $this->flash('Canceled.')->redirect(array('example_form_imgs', 'list'));
        }
		
        
        // 4. Save
        
		if (isset($_POST['save']) && $form->isValid()) {
            
            $news = new m_news();
            $news->val($form->val());
            $news->save()->selectInserted();
            
            $val = $form->val();
            
            foreach ($val['img'] as $img) { // dodanie rekordow img i plikow img
                
                if ($img['del'] == 'yes') {
                    continue;
                }
                
                $img = new m_img();
                $img->img_id_news = $news->id();
                $img->img_token   = $this->token();
                $img->img_title   = $img['img_title'];
                $img->save()->selectInserted();
                
                f_upload_tmp::_()->path($img['path'])->save("data/img/{$img->id()}_{$img->img_token}.jpg");
            }
            
            $this->flash('Done.')->redirect(array('example_form_imgs', 'list'));
            
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
        
        if ($_POST) { // dane z $_POST do f_form
            
            $this->_formAttachImgs($form, (array)$_POST['img']); // przepisanie img z POST do formularza
            
            $form->val($_POST + $_FILES);
            
			if ($_FILES && !f_upload::_()->image()->error()) { // dodanie nowego img z $_POST i $_FILES
                
                $this->_formAttachImg($form, max(array_keys((array)$_POST['img'])) + 1, array(
                    'img_title' => $_POST['img_title'],
                    'path'      => f_upload_tmp::_()->create()->option('80x80'),
                ));
			}
        }
        else { // dane z f_m do f_form
            
            $form->val($news->val());
            
            $this->_formAttachImgs($form, $news->img->fetchAll());
            
        }
        
        $form->img->val() 
            ? $form->img->decor('img')->attr('src', '/' . $form->img->val())
            : $form->img->removeDecor('img');


        // 3. Cancel
        
        if (isset($_POST['cancel'])) {
            $this->redirect(array('example_form_imgs', 'list'));
        }
        
		
        // 4. Save
        
		if (isset($_POST['save']) && $form->isValid()) {
            
            $news->val($_POST);
            $news->save();

            $val = $form->val();
            
            foreach ((array)$val['img'] as $k => $img) {
                
                if ($img['del'] == 'yes') { // usuniecie obrazka
                    
                    if (!isset($img['img_id'])) { // to tylko plik tmp, wiec nic nie robimy
                        continue;
                    }
                    
                    $oImg = new m_img();
                    $oImg->select($img['img_id']);
                    if (!$oImg->id()) {
                        continue;
                    }
                    $oImg->delete();
                    
                    /** @todo usunac plik i jego rozmiary $this->unlinkDataImg(); */
                    
                    continue;
                }
                
                if (isset($img['img_id'])) { // istniejacy obrazek
                    
                    /** @todo zapisujemy jego nowe title */
                    
                    continue;
                }
                
                // dodanie nowego obrazka
                
                $oImg = new m_img();
                $oImg->img_id_news = $news->id();
                $oImg->img_token   = $this->token();
                $oImg->img_title   = $img['img_title'];
                $oImg->save()->selectInserted();
                
                f_upload_tmp::_()->path($img['path'])->save("data/img/{$oImg->id()}_{$oImg->img_token}.jpg");
                
            }
            
		
            $this->flash('Done.')->redirect(array('example_form_imgs', 'list'));
		}
	
	}
    
    protected function _form()
    {
        return new f_form(array(
			'element' => array(
				new f_form_text(array('name' => 'news_title')),
				new f_form_file(array('name' => 'imgupload')),
				new f_form_text(array('name' => 'imgtitle')),
				new f_form_button(array('name' => 'imgadd', 'val' => '+', 'attr' => array('type' => 'submit'))),
			)
		));
    
    }
    
    protected function _formAttachImgs(f_form $form, $imgs)
    {
        foreach ($imgs as $index => $img) {
            $this->_formAttachImg($form, $index, $img);
        }
    }
    
    protected function _formAttachImg(f_form $form, $index, $img)
    {
        if (isset($img['img_id'], $img['img_token'])) {
            $img['path'] = "data/img/{$img['img_id']}_{$img['img_token']}_80x80.jpg";
        }
        
        $form->addElementBefore('imgupload', array(
            new f_form_hidden(  array('name' => "img[$index][path]",      'val' => $img['path'])),
            new f_form_hidden(  array('name' => "img[$index][img_id]",    'val' => $img['img_id'])),
            new f_form_text(    array('name' => "img[$index][img_title]", 'val' => $img['img_title'])),
            new f_form_checkbox(array('name' => "img[$index][del]",       'val' => $img['del'], 
                                'attr' => array('value' => 'yes'))),
        ));
        
        /** @todo udekorowac odpowiednio te elmenty, jeden obrazek w jednej lini
         * moze dodac sobie do formularz pomocnicze elementy z ignoreValue o nazwach img_list_begin 
         * ktory jako decorator bedzie mial <table> a img_list_end jako decorator </table>
         * wtedy $form->>addElementBefore('img_list_end', ...
         * 
         * img_list_end mozna dac ignoreRender, bedzie tylko odniesieniem dla dodawania nowych obrazkow,
         * imgupload, imgtitle, imgadd beda tez w tej tabeli z lista obrazkow
         * 
         * albo tylko elementowi przed lista dac append <table>
         * dalej $form->addElementBefore('imgupload',
         * i element imgadd zamyka lista </table>
         * 
         */
    }
    
    
}
 