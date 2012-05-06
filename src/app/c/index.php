<?php

class c_index extends f_c_action
{

    public function indexAction()
    {
        /**
         * Begin
         */
        $this->v->head->css('/lib/bootstrap.css');
        echo "<!DOCTYPE html><html><head>{$this->v->head->render()}</head><body><div class=\"container\">\n\n";


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
        $basic->text->ignoreRender(true);

        $basic->element(new f_form_checkbox(array('name' => 'checkbox')));
        $basic->checkbox->label('Check me out');
        $basic->checkbox->decor('label')->placement(f_form_decor_default::PLACEMENT_EMBRACE);
        $basic->checkbox->decor('label')->gravity(f_form_decor_label::GRAVITY_RIGHT);
        $basic->checkbox->removeDecor('tag');

        $basic->submit = new f_form_submit(array('val' => 'Submit', 'addClass' => 'btn'));

        echo $basic->render();


        /**
         * End
         */
        echo '</div></body></html>';
    }

}
