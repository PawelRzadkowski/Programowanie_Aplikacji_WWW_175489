<?php

/**
 * =======================================================
 *  cfg.php – Wersja v1.8
 *  Plik konfiguracyjny aplikacji
 *  - konfiguracja bazy danych
 *  - dane administratora (login i hasło)
 *  - inicjalizacja połączenia mysqli
 * =======================================================
 */
//session_start(); 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$login = "admin@example.con=m";
$pass  = "1234";


/**
 * -------------------------------------------------------
 * Nawiązanie połączenia z bazą MySQL
 * -------------------------------------------------------
 */
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

if (!$conn) {
    echo '<b>przerwane połączenie: </b>' . mysqli_connect_error();
}
?>