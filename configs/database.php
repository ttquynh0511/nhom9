<?php
$servername = "127.0.0.1";
$username = 'root';
$password = "";
$dbname = "cafe";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failse !" . mysqli_connect_error());
}
