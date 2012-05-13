# f_form helpery widoku

Helpery widoku elementow formularza przyjmuja parametry:

- $sName - nazwa elementu, wartosc atrybutu name,
- $mVal - wartosc elementu, zazwyczaj wartosc atrybutu value lub tresc elementu,
- $aAttr - tablica asocjacyjna atrybutow elementu,
- $aOption - talica opcji dla elementow select, radio, multi-checkbox 
- $sSeparator - separator opcji


## f_v_helper_formText

```
<?php
$oText = new f_v_helper_formText();
echo $oText->helper();
?>

<input type="text" name="text" value="" />
```

```
<?php
$oText = new f_v_helper_formText();
echo $oText->helper('foo');
?>
<input type="text" name="foo" value="" />
```

```
<?php
$oText = new f_v_helper_formText();
echo $oText->helper('foo', 'bar');
?>
<input type="text" name="foo" value="bar" />
```

```
<?php
$oText = new f_v_helper_formText();
echo $oText->helper('foo', 'bar', array('class' => 'text', 'style' => 'color:red'));
?>
<input type="text" name="foo" value="bar" class="text" style="color:red" />
```

```
<?php
$oText = new f_v_helper_formText();
echo $oText->helper('foo', '<>');
?>
<input type="text" name="foo" value="&lt;&gt;" />
```


## f_v_helper_formPassword

```
<?php
$oPassword = new f_v_helper_formPassword();
echo $oPassword->helper();
?>
<input type="password" name="password" value="" />
```


## f_v_helper_formTextarea

```
<?php
$oTextarea = new f_v_helper_formTextarea();
echo $oTextarea->helper('foo', 'bar');
?>
<textarea name="foo">bar</textarea>
```


## f_v_helper_formSelect

```
<?php
$oSelect = new f_v_helper_formSelect();
echo $oSelect->helper('foo', null, array(), array('bar' => 'Bar', 'baz' => 'Baz'));
?>
<select name="foo"><option value="bar">Bar</option><option value="baz">Baz</option></select>
```

```
<?php
$oSelect = new f_v_helper_formSelect();
echo $oSelect->helper('foo', 'baz', array(), array('bar' => 'Bar', 'baz' => 'Baz'));
?>
<select name="foo"><option value="bar">Bar</option><option value="baz" selected="selected">Baz</option></select>
```

```
<?php
$oSelect = new f_v_helper_formSelect();
echo $oSelect->helper('foo[]', array('baz'), array(), array('bar' => 'Bar', 'baz' => 'Baz');
?>
<select name="foo[]" multiple="multiple"><option value="bar">Bar</option><option value="baz" selected="selected">Baz</option></select>
```

```
<?php
$oSelect = new f_v_helper_formSelect();
echo $oSelect->helper(
    'foo[]',
    array('baz', 'qux'),
    array(
        'size'     => '3',
        '_option'  => array('class' => 'someClass'),
        '_prepend' => '[',
        '_append'  => ']',
    ),
    array(
        'bar' => 'Bar',
        'baz' => 'Baz',
        'qux' => array(
            'data'     => 'last-input',
            '_text'    => 'Qux',
            '_prepend' => '(',
            '_append'  => ')',
         )
    ),
    "\n"
);
?>
<select name="foo[]" multiple="multiple" size="3"><option value="bar" class="someClass">[Bar]</option>
<option value="baz" selected="selected" class="someClass">[Baz]</option>
<option value="qux" selected="selected" class="someClass" data="last-input">(Qux)</option></select>
```


## f_v_helper_formRadio

```
<?php
$oRadio = new f_v_helper_formRadio();
echo $oRadio->helper('foo', null, array(), array('bar' => 'Bar', 'baz' => 'Baz');
?>
<label><input type="radio" name="foo" value="bar" />Bar</label>
<label><input type="radio" name="foo" value="baz" />Baz</label>
```

```
<?php
$oRadio = new f_v_helper_formRadio();
echo $oRadio->helper(
    'foo',
    'baz',
    array(
        'class'      => 'radio',
        '_label'     => array('class' => 'label'),
        '_prepend'   => '[',
        '_append'    => ']',
        '_separator' => '|',
    ),
    array(
        'bar' => 'Bar',
        'baz' => 'Baz',
        'qux' => array(
            'data'       => 'last-input',
            '_text'      => 'Qux',
            '_prepend'   => '(',
            '_append'    => ')',
            '_separator' => ',',
            '_label'     => array('data' => 'last-label'),
         )
    ),
    "\n"
    );
?>
<label class="label">[<input type="radio" name="foo" value="bar" class="radio" />|Bar]</label>
<label class="label">[<input type="radio" name="foo" value="baz" checked="checked" class="radio" />|Baz]</label>
<label data="last-label" class="label">(<input type="radio" name="foo" value="qux" class="radio" data="last-input" />,Qux)</label>
```


## f_v_helper_formCheckbox


```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper();
?>
<input type="checkbox" name="checkbox" value="" />
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper('foo');
?>
<input type="checkbox" name="foo" value="" />
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper('foo', 1);
?>
<input type="checkbox" name="foo" value="" checked="checked" />
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper('foo', 'on');
?>
<input type="checkbox" name="foo" value="" checked="checked" />
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper('foo', 'bar', array('value' => 'bar'));
?>
<input type="checkbox" name="foo" value="bar" checked="checked" />
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper('foo[]', null, array(), array('bar' => 'Bar', 'baz' => 'Baz'));
?>
<label><input type="checkbox" name="foo[]" value="bar" />Bar</label>
<label><input type="checkbox" name="foo[]" value="baz" />Baz</label>
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper('foo[]', array('baz'), array(), array('bar' => 'Bar', 'baz' => 'Baz'));
?>
<label><input type="checkbox" name="foo[]" value="bar" />Bar</label>
<label><input type="checkbox" name="foo[]" value="baz" checked="checked" />Baz</label>
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper('foo[]', array('baz', 'bar'), array(), array('bar' => 'Bar', 'baz' => 'Baz'));
?>
<label><input type="checkbox" name="foo[]" value="bar" checked="checked" />Bar</label>
<label><input type="checkbox" name="foo[]" value="baz" checked="checked" />Baz</label>
```

```
<?php
$oCheckbox = new f_v_helper_formCheckbox();
echo $oCheckbox->helper(
    'foo[]',
    array('baz'),
    array(
        'class'      => 'checkbox',
        '_label'     => array('class' => 'label'),
        '_prepend'   => '[',
        '_append'    => ']',
        '_separator' => '|',
    ),
    array(
        'bar' => 'Bar',
        'baz' => 'Baz',
        'qux' => array(
            'data'       => 'last-input',
            '_text'      => 'Qux',
            '_prepend'   => '(',
            '_append'    => ')',
            '_separator' => ',',
            '_label'     => array('data' => 'last-label'),
         )
    ),
    "\n"
));
?>
<label class="label">[<input type="checkbox" name="foo[]" value="bar" class="checkbox" />|Bar]</label>
<label class="label">[<input type="checkbox" name="foo[]" value="baz" checked="checked" class="checkbox" />|Baz]</label>
<label data="last-label" class="label">(<input type="checkbox" name="foo[]" value="qux" class="checkbox" data="last-input" />,Qux)</label>
```



## f_v_helper_formSubmit

```
<?php
$oSubmit = new f_v_helper_formSubmit();
echo $oSubmit->helper();
?>
<input type="submit" name="submit" value="submit" />
```

```
<?php
$oSubmit = new f_v_helper_formSubmit();
echo $oSubmit->helper(null, 'Go!');
?>
<input type="submit" name="submit" value="Go!" />
```

```
<?php
$oSubmit = new f_v_helper_formSubmit();
echo $oSubmit->helper(
    'submit',
    null,
    array('class' => 'submit'),
    array(
        'edit'  => array('value' => 'Save', 'style' => 'font-weight:bold'),
        'clone' => 'Clone',
        'reset' => array('value' => 'Reset',
                         'style' => 'text-decoration:line-through',
                         'type'  => 'reset'),
        'del'   => array('value' => 'Delete', 'style' => 'color:red'),
    ),
    "\n"
);
?>
<input type="submit" name="submit[edit]" value="Save" style="font-weight:bold" class="submit" />
<input type="submit" name="submit[clone]" value="Clone" class="submit" />
<input type="reset" name="submit[reset]" value="Reset" style="text-decoration:line-through" class="submit" />
<input type="submit" name="submit[del]" value="Delete" style="color:red" class="submit" />
```


