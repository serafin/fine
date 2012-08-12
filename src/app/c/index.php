<?php

class c_index extends f_c_action
{

    public function indexAction()
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

    public function modelAction()
    {

        $this->render->off();
        $this->_c->db = new dbDumpQuery();


        $comment = new m_comment();
        $comment->join('magazine', array('lalala' => 'article_id'), null, 'yogi');
        $comment->selectAll();


        f_debug::dump($comment->param());
    }

}


class dbDumpQuery
{

    public function escape($s)
    {
        return addslashes($s);
    }

    public function query($s)
    {
        f_debug::dump($s, 'query');
    }

    public function row($s)
    {
        f_debug::dump($s, 'row');
    }

    public function rows($s)
    {
        f_debug::dump($s, 'rows');
    }

    public function col($s)
    {
        f_debug::dump($s, 'col');
    }

    public function cols($s)
    {
        f_debug::dump($s, 'cols');
    }

    public function val($s)
    {
        f_debug::dump($s, 'val');
    }

    public function fetchUsingResult($rRresult)
    {
        return null;
    }

}

class m_article extends f_m
{

    public $article_id;
    public $article_type;

    public function relations()
    {
        parent::initRelation();

    }

}

class m_comment extends f_m
{

    public $comment_id;
    public $comment_type;
    public $comment_foreignid;

    public function relations()
    {
        $this->relation('magazine', 'comment_foreignid', 'article_id', "`comment_type` = 'magazine'");

    }

}