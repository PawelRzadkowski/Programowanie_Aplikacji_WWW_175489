<?php

/**
 * =======================================================
 *  showpage.php – Wersja v1.11
 *  Funkcja: PokazPodstrone()
 *  Opis: Pobiera treść podstrony z bazy danych.
 * =======================================================
 */


/**
 * -------------------------------------------------------
 * FUNKCJA: PokazPodstrone($id)
 *
 * Parametry:
 *  - $id → ID podstrony przesyłane np. przez $_GET['idp']
 *
 * Zabezpieczenia:
 *  - intval() → zapobiega SQL Injection na parametrze ID
 *  - LIMIT 1 → bezpieczeństwo i optymalizacja
 *
 *  - Funkcja korzysta z połączenia $conn z pliku cfg.php
 * -------------------------------------------------------
 */

function PokazPodstrone($id)
{
    
    global $conn;

    // Zabezpieczenie ID
    $id_clear = intval($id);
    $query = "SELECT * FROM page_list WHERE id = '$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
	
	// Sprawdzenie, czy podstrona istnieje
    if (!$result || mysqli_num_rows($result) == 0) {
        return "[nie_znaleziono_strony]";
    }
    $row = mysqli_fetch_assoc($result);

	// Zwrócenie treści podstrony
    return $row['page_content'];
}
?>