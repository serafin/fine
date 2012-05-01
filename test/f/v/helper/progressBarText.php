<?php

require "../src/lib/f/di/asNew/interface.php";
require "../src/lib/f/v/helper/progressBarText.php";

$progress = new f_v_helper_progressBarText();
$progress->end(300);
$progress->render();

for ($i = 0; $i < 300; $i++) {
    $progress->up();
    usleep(10000);
}