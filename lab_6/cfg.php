<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

if (!$conn) {
    echo '<b>przerwane połączenie: </b>' . mysqli_connect_error();
}
?>