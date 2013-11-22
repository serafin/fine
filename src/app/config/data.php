<?php

return array(
    'model' => array(
        'imgsize' => array(
            't200x150',                    // wartosc `[rt][0-9]{1,4}x[0-9]{1,4}` np. r640x480 (tylko jpg)
            'big' => array(                // lub klucz z dokladnymi parametrami
                'w'        => '200',         // szerokosc, wymagane
                'h'        => '150',         // wysokosc, wymagane
                'ext'      => 'jpg png gif', // opcjonalnie, standardowo jpg
                'type'     => 'resize',      // lub thumb, opcjonalnie, standardowo resize
                'extend'   => true,          // lub false, default true
                'callback' => array(/* dane w formacie callable */), // np. array(new stdClass(), 'nazwa_metody')
              )
        )
    )
);