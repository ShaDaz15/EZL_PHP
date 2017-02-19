<?php

if(isset($dbc)){
    exit;
} else {
    //Temp code to quickly begin calling databases
    //0-db, 1-
    $info = array('localhost', 'user', 'pass', 'ezl_db');
    $dbc = mysqli_connect($info[0], $info[1], $info[2], $info[3]) or die("Failed database connection!");
}