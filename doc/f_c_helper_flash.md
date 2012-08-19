

 Służy do przekazywanie wiadomosci uzytkownikowi pomiedzy żądaniami

```php
<?php

$flash = new f_c_helper_flash();
$flash->storage(&$_SESSION['flash']);

$flash