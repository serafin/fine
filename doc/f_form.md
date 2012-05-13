# f_form

Obsluga formularzy: tworzenie, renderowanie, filtracja, walidacja danych.


```
<?php

$oSearch = new f_form_text(array(
    'name' => 'query',
    'val'  => 'search...',
));

echo $oSearch;
```

```
<?php

$oForm = new f_form(array(
    'attr'    => array('id' => 'box-formlogin'),
    'action'  => '/login/'
    'element' => array(
        new f_form_text(array(
            'name'     => 'user_email',
            'label'    => 'E-mail',
            'required' => true,
            'valid'    => new f_valid_lengthMin(array('min' => 3)),
        )),
        new f_form_text(array(
            'name'     => 'user_pass',
            'label'    => 'Password',
            'required' => true,
        )),
        new f_form_submit(array(
            'name'  => 'submit',
            'value' => 'Submit',
        )),
    )
));

$oForm->val(array(
    'user_email' => 'john.doe@example.com',
    'user_pass'  => 'qwerty',
));

if ($oForm->isValid()) {
    // do some stuff...
}

echo $oForm->render();

```






