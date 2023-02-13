<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'loginsystem';

$conn = mysqli_connect($servername,$username,$password,$database);

if(!$conn){
    die("Error".mysqli_connect_error());
}



?>