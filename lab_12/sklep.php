<?php
// sklep.php
require_once "cfg.php";

function PokazSklep() {
    global $conn; 

    echo "<h2>Sklep Merch , Największe Budowle Świata</h2>";
//pomijanie tych których nie ma
    $res = mysqli_query($conn, "
        SELECT * FROM products
        WHERE
            status_dostepnosci = 1
            AND ilosc_dostepnych_sztuk > 0
            AND (data_wygasniecia IS NULL OR data_wygasniecia >= CURDATE())
    ");

    if (mysqli_num_rows($res) == 0) {
        echo "<p>Brak dostępnych produktów</p>";
        return;
    }

    echo "<div class='sklep-container' style='display:flex;flex-wrap:wrap;gap:20px;justify-content:center;'>";

    while ($p = mysqli_fetch_assoc($res)) {
        $cena_brutto = $p['cena_netto'] * (1 + $p['podatek_vat'] / 100);

        echo "
        <div class='produkt-karta' style='border:1px solid #ccc;padding:15px;width:250px;background:#fff;border-radius:8px;'>
            <h3>{$p['tytul']}</h3>
        ";

        if ($p['zdjecie'] != "") {
            echo "<img src='{$p['zdjecie']}' style='width:100%;height:150px;object-fit:contain;'><br><br>";
        }

// wyświetlanie zdjęć
        echo "
            <p style='height:60px;overflow:hidden;'>{$p['opis']}</p>
            <p><b>Cena:</b> ".number_format($cena_brutto, 2)." zł</p>
            <p><b>Dostępne:</b> {$p['ilosc_dostepnych_sztuk']} szt.</p>

            <a href='index.php?idp=99&add={$p['id']}'
               style='display:inline-block;padding:8px 12px;background:#28a745;color:#fff;text-decoration:none;border-radius:4px;'>
               Dodaj do koszyka
            </a>
        </div>
        ";
    }

    echo "</div>";
}
?>