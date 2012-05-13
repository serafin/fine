# Validatory

Walidatory sa definiowane dla elementow metoda `valid`.
Walidacja nastepuje podczas wywolania metody `isValid`, ktora zwraca `true` lub `false`.
Informace o bledach walidacji mozna pobrac metoda `error`. 

*Metody `valid`, `isValid`, `error` dotycza `f_from` (formularza) i `f_form_*` (elementow)

Metoda `breakOnFail` ustawia przerwanie dalszej walidacji po napotakniu bledu. 
Tzn. jezeli walidator zwroci komunikat o niepoprawnej walidacji to nastepne walidatory w
lancuchu nie zostana uruchomione. Opcja `breakOnFail` jest standardowo ustawiona na `true`.

```
<?php

$oText = new f_form_text();
$oText->valid(array(
    new f_valid_lengthMin(array('min' => 5)),
    new f_valid_email(),
);
$oText->val('Foo');

if ($oText->isValid()) {
    echo 'Yes';
}
else {
    echo 'No';
}

```
`No`
