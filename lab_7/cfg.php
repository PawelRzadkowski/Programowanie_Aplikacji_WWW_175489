<?php
//session_start(); 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$login = "admin@example.con=m";
$pass  = "1234";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

if (!$conn) {
    echo '<b>przerwane połączenie: </b>' . mysqli_connect_error();
}
?>