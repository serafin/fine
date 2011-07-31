<?php

require "lib/f/autoload/includePath.php";

f_autoload_includePath::_()
    ->path(array('.', 'app/m/', 'app/', 'lib/'))
    ->register();

f::$c = new app_c();

new app_main();



