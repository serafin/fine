# Filtry

Filtry sa definiowane dla elementow metoda `filter` (metoda elementu lub formularza).
Podczas ustawiania wartosci elementu metoda `val` (lub wartosci forumarza), 
warosc jest filtorowana zdefiniowanymi wczesniej filtrami. Kolejnosc filtrow ma znaczenie. 

```
<?php

$oText = new f_form_text();
$oText->filter(array(
    new f_filter_trim(),
    new f_filter_cut(array('length' => 5, 'end' => '...')),
);
$oText->val(' Hello World! ');
echo $oText->val();
```
`Hello...`

