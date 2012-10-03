<?php

class c_index extends f_c_action
{

    public function indexAction()
    {
        $this->render->off();
        $this->response->header('Content-Type', 'text/plain');

        $option = array('a' => 'A', 'b' => 'B');

        $checkbox = new f_form_checkbox();
        $checkbox->option($option);
        $checkbox->form($oForm);
        $checkbox->label('Checkbox');
        echo $checkbox->render();

        echo "\n\n";

        $radio = new f_form_radio();
        $radio->option($option);
        $radio->form($oForm);
        $radio->label('Radio');
        $radio->decor('label')->placement(f_form_decor_default::PLACEMENT_PREPEND);
        echo $radio->render();


    }

}