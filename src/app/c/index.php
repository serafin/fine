<?php

class c_index extends f_c_action
{

    public function indexAction()
    {

//        $article = new m_article();
//        $article->article_type = 'mag';
//        $article->save();
//        $article->selectInserted();
//
//        $article->insertAll(array_fill(0, 2000, $article->val()));
//

        $this->debugshow();
        
        $article = new m_article();
        $article->param('article_type', 'mag');
        $article->paramPaging();
        $article->selectAll();
        m_article::_()->delete(array($article->key() => array(1,2,3,4,5,6,7,8,9)));
        echo $this->v->paging($article->paging());


//        $this->debug->limit(20);

        $this->debug->log('Cicik');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
        $this->debug->log('Cicik2');
    }

    public function index2Action()
    {
        f_debug::dump(range(0, 'f'));
        //filemtime('./asdasdad');
        /**
         * Begin
         */
//        $this->v->head->css('/lib/bootstrap.css');
//        echo "<!DOCTYPE html><html><head>{$this->v->head->render()}</head><body><div class=\"container\">\n\n";


        /**
         * Basic
         */
        $basic = new f_form();
        $basic->addClass('well');
        $basic->decor(new f_form_decor_default(array(
            'decoration'  => '<p>',
            'decoration2' => '</p>',
            'placement'   => f_form_decor_default::PLACEMENT_EMBRACE,
        )));
        $basic->element(new f_form_text(array('name' => 'text')));

        $basic->text->label('Label name');
        $basic->text->decor('label')->placement(f_form_decor_label::PLACEMENT_PREPEND);

        $basic->text->attr('placeholder', 'Type something...');

        $basic->text->desc('Example block-level help text here.');
        $basic->text->decor('desc')
                ->tag('span')
                ->attr(array('class' => 'help-block'))
                ;
        $basic->text->removeDecor('tag');

        $basic->element(new f_form_checkbox(array('name' => 'checkbox')));
        $basic->checkbox->option(array('a'=>A, b => B));
        $basic->checkbox->label('Check me out');
        $basic->checkbox->decor('label')->placement(f_form_decor_default::PLACEMENT_EMBRACE);
        $basic->checkbox->decor('label')->gravity(f_form_decor_label::GRAVITY_RIGHT);
        $basic->checkbox->removeDecor('tag');

        $basic->submit = new f_form_submit(array('val' => 'Submit', 'addClass' => 'btn'));

        $basic->val($_POST);
        $basic->text->valid(new f_valid_lengthMin(array('min' => 3)));
        $basic->text->valid(new f_valid_digit());
        $basic->text->ignoreError(true);
//        f_debug::dump($_POST, '$_POST');
//        f_debug::dump($basic->isValid(), 'basic->isValid()');
//        f_debug::dump($basic->val(), 'f_form::val()');

       // echo $basic->render();


        /**
         * End
         */
        //echo '</div></body></html>';

        //$this->debug->show();
    }

    public function flashAction()
    {
        $this->flash('My message', f_c_helper_flash::STATUS_INFO);
    }
    public function flash2Action()
    {
        f_debug::dump($this->flash->get());

    }

    public function imageAction()
    {
        //f_debug::dump($i->error());
    }

    public function cacheAction()
    {
        $this->render->off();

        $cache = new f_cache(array('backend' => new f_cache_backend_file()));
        $cache->prefix('app_');


        if(!$cache->start('render')) {

            //echo 'Gdzie te czasy kiedy Ala miala kota';

            $cache->stop();
        }



    }

    public function formAction()
    {
        $this->render->off();

        f_debug::dump($_POST, '$_POST');

        $form = new f_form();

        foreach (array('a', 'b') as $type) {

            foreach (array('from', 'to') as $k => $person) {

                $form->element(new f_form_text(array('name' => "{$type}[{$k}][user_name]")));
                $form->element(new f_form_text(array('name' => "{$type}[{$k}][user_email]")));

            }

        }


        $form->element(new f_form_submit());

        $form->val($_POST);
        
        f_debug::dump($form->val(), '$form->val()');

        echo $form->render();



    }

    public function hardrelAction()
    {
        // Pobieranie listy galerii dla artykulu

        $oGallery = new m_gallery();
        $oGallery->join('article_mag');
        $oGallery->param('resource_baseId', 1234);
        $oGallery->param('resource_baseType', 'article_mag');
        $oGallery->selectAll();

        // v2
        $oResource = new m_resource();
        $oResource->paramGalleryForArticleMag(1234);
        $oResource->selectAll();


        // Pobieranie wszystkich obrazkow

        $oGalleryPic = new m_galleryPic();
        $oGalleryPic->join('gallery');
        $oGalleryPic->join('article_mag', null, 'gallery');
        $oGalleryPic->param('resource_baseId', 1234);
        $oGalleryPic->param('resource_baseType', 'article_mag');
        $oGalleryPic->selectAll();

        // v2
        $oResource = new m_resource();
        $oResource->paramGalleryPicForArticleMag(1234);
        $oResource->selectAll();

        $oArticle = new m_article();
        foreach ($oArticle as $v) {
            
        }
         
        $oArticle->paramPaging();
        $oArticle->selectAll();

        $this->debug->log('c', '3', f_debug::LOG_TYPE_VAL);
        $this->debug->log(array(array('a' => 1, 'b' => 2), array('a' => 11, 'b' => 22)), 'd', f_debug::LOG_TYPE_TABLE);
        $this->debug->log(array('a' => 1, 'b' => 2, 'c' => 11, 'd' => 22), 'd', f_debug::LOG_TYPE_LIST);


        $this->debug->show();
        $this->render->off();

    }

    public function geshiAction()
    {
        $this->render->off();
        $this->bundle('geshi');


        $geshi = new GeSHi();
        $geshi->set_language('php');
        $geshi->enable_keyword_links(false);
        $geshi->set_header_type(GESHI_HEADER_NONE);
        $geshi->set_source(file_get_contents('index.php'));
        echo $geshi->parse_code();

        $this->db->query('SELECT * FROM dupa');

    }
}

