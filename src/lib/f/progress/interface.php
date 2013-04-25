<?php

interface f_progress_interface
{

    public function all($iAllTask = null);

    public function set($iDoneTasks);

    public function get();
    
    public function up($iNumber = 1);
    
    public function done();

    public function progress($iPrecision = 2);

}