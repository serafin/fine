<?php

class c_example_f_form_decor_event extends f_c_action
{

    public function indexAction()
    {
        // form
        $form = new f_form(array(
            'element' => array(
                new f_form_radio(array('name' => 'radio', 'option' => array('a' => 'A', 'b' => 'B', 'c' => array('_text' => 'C')))),
                new f_form_text(array('name' => 'other', 'ignoreRender' => true, 'decor' => array('f_form_decor_helper'))),
            )
        ));
        
        // set f_form_decor_event with event, with id  'attach_other'
        // when f_form_decor_event will be renderd it will, fire 'attach_other' event id
        $form->radio
            ->removeDecor()
            ->decor(array(
                array('f_form_decor_event', 'event' => f_event::_()->id('attach_other')->other($form->other)->radio($form->radio)), 
                'f_form_decor_helper'
            ))
        ;
        
        // subscribe for event id 'attach_other'
        $this->event->on('attach_other', array($this, 'attachOther'));
        
        
        // some test data
        $form->radio->separator('<br />');
        $form->val(array('radio' => 'c', 'other' => 'and my mind moves away'));
        
        // set response
        $this->response->body = $form->render();
        $this->render->off();
    }
    
    public function attachOther(f_event $event)
    {
        $option            = $event->radio()->option('c');
        $option['_append'] = $event->other()->render();
        $event->radio()->option('c', $option);
    }

}