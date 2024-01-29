<?php

$db_name  = 'hotel_db';
$host = 'localhost';
$user = 'root';
$passaword =  '';

$conn = new PDO("mysql:host=$host;dbname=$db_name",$user,$passaword);


function  create_unique_id() {
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $rand = array();
    $lenghtb = strlen($str) - 1;
    for ($i=0; $i < 20; $i++) { 
        $n = mt_rand(0,$lenghtb);
        $rand[] = $str[$n];

    }
    return implode($rand);
}
