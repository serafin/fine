<?php

return array(
    'css' => array(
        'style'  => array(
            'v'  => 1,
            'replace_regexp'  => array(),
            'sprite' => 'main'
        ),
        
    ),
    'sprite' => array(
        'main' => array(
            'v'  => 1,
            'adapter' => array(
                'f_sprite_adapter_col', 
                'col' => array(
                    array('small', 100, 5, 5),
                    array('medium', 200, 5, 5),
                    array('large', 300, 5, 5),
                ),
                'match' => array(
                    array('small', 'icon*'),
                    array('medium', 'button*'),
                    array('large', 'logo*'),
                )
            )
            
        ),
    ),
);