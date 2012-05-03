<?php

require "./../src/lib/f/sis/exception.php";
require "./../src/lib/f/sis/exception/notWritable.php";
require "./../src/lib/f/sis/exception/running.php";
require "./../src/lib/f/sis.php";


$sis = new f_sis();
$sis->id('./test.sis');

if ($sis->begin()) {
    exec('echo "' . date('Hi ') .'" >> test.txt');
    sleep(30);
}
