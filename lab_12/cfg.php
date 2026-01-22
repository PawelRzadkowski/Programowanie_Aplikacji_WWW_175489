<?php

/**
 * =======================================================
 *  cfg.php – Wersja v1.11
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

$login = "admin@example.con";
$pass  = "1234";


/**
 * -------------------------------------------------------
 * Nawiązanie połączenia z bazą MySQL
 * -------------------------------------------------------
 */
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

function GenerujSklep($conn)
{
    $html = "<h2>Sklep – merch Największe Budowle Świata</h2>";

    $res = mysqli_query($conn, "
        SELECT * FROM products
        WHERE
            status_dostepnosci = 1
            AND ilosc_dostepnych_sztuk > 0
            AND (data_wygasniecia IS NULL OR data_wygasniecia >= CURDATE())
    ");

    if (mysqli_num_rows($res) == 0) {
        return "<p>Brak dostępnych produktów</p>";
    }

    $html .= "<div style='display:flex;flex-wrap:wrap;gap:20px;'>";

    while ($p = mysqli_fetch_assoc($res)) {

        $cena_brutto = $p['cena_netto'] * (1 + $p['podatek_vat'] / 100);

        $html .= "
        <div style='border:1px solid #ccc;padding:15px;width:250px;'>
            <h3>{$p['tytul']}</h3>";

        if ($p['zdjecie'] != "") {
            $html .= "<img src='{$p['zdjecie']}' style='width:100%;height:auto'><br><br>";
        }

        $html .= "
            <p>{$p['opis']}</p>
            <p><b>Cena:</b> ".number_format($cena_brutto, 2)." zł</p>
            <p><b>Dostępne:</b> {$p['ilosc_dostepnych_sztuk']} szt.</p>

            <a href='index.php?idp=99&add={$p['id']}'
               style='display:inline-block;padding:8px 12px;background:#28a745;color:#fff;text-decoration:none;'>
               Dodaj do koszyka
            </a>
        </div>";
    }

    $html .= "</div>";

    return $html;
}


if (!$conn) {
    echo '<b>przerwane połączenie: </b>' . mysqli_connect_error();
}
?>