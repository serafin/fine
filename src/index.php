<?php

require "lib/f/autoload/includePath.php";

f_autoload_includePath::_()
    ->path(array('.', 'lib/', 'app/'))
    ->register();

f::$c = new app_c();

