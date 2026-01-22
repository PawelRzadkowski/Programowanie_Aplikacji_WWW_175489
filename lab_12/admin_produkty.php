<?php
session_start();
require_once "cfg.php";

/* ZABEZPIECZENIE CMS */
if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header("Location: admin.php");
    exit();
}

echo "<h2>Panel zarządzania produktami</h2>";
echo '<a href="admin.php">← Powrót do panelu</a><br><br>';

/* ==================================================
   Funkcja: WARUNKI DOSTĘPNOŚCI PRODUKTU
   ================================================== */
function CzyProduktDostepny($p)
{
    if ($p['status_dostepnosci'] == 0) return false;
    if ($p['ilosc_dostepnych_sztuk'] <= 0) return false;
    if ($p['data_wygasniecia'] !== null && strtotime($p['data_wygasniecia']) < time()) {
        return false;
    }
    return true;
}

/* ==================================================
   Funkcja: DODAJ PRODUKT
   ================================================== */
function DodajProdukt($conn)
{
    if (isset($_POST['dodaj'])) {

        $tytul  = mysqli_real_escape_string($conn, $_POST['tytul']);
        $opis   = mysqli_real_escape_string($conn, $_POST['opis']);
        $cena   = floatval($_POST['cena_netto']);
        $vat    = intval($_POST['podatek_vat']);
        $ilosc  = intval($_POST['ilosc']);
        $status = intval($_POST['status']);
        $kat    = mysqli_real_escape_string($conn, $_POST['kategoria']);
        $gab    = mysqli_real_escape_string($conn, $_POST['gabaryt']);
        $img    = mysqli_real_escape_string($conn, $_POST['zdjecie']);
        $exp    = $_POST['data_wygasniecia'] !== "" ? "'".$_POST['data_wygasniecia']."'" : "NULL";

        mysqli_query($conn, "
            INSERT INTO products
            (tytul, opis, data_utworzenia, data_wygasniecia, cena_netto, podatek_vat,
             ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie)
            VALUES
            ('$tytul','$opis',NOW(),$exp,$cena,$vat,$ilosc,$status,'$kat','$gab','$img')
        ");

        echo "<p style='color:green;font-weight:bold;'>Produkt dodany</p>";
    }

    echo '
    <h3>Dodaj produkt</h3>
    <form method="post">
        Tytuł: <input type="text" name="tytul" required><br><br>
        Opis:<br><textarea name="opis"></textarea><br><br>
        Cena netto: <input type="text" name="cena_netto" required><br>
        VAT: <input type="number" name="podatek_vat" value="23"><br>
        Ilość: <input type="number" name="ilosc"><br>
        Status:
        <select name="status">
            <option value="1">Dostępny</option>
            <option value="0">Niedostępny</option>
        </select><br>
        Data wygaśnięcia: <input type="date" name="data_wygasniecia"><br>
        Kategoria: <input type="text" name="kategoria"><br>
        Gabaryt: <input type="text" name="gabaryt"><br>
        Zdjęcie (link): <input type="text" name="zdjecie"><br><br>
        <input type="submit" name="dodaj" value="Dodaj produkt">
    </form>
    <hr>
    ';
}

/* ==================================================
   Funkcja: EDYTUJ PRODUKT
   ================================================== */
function EdytujProdukt($conn)
{
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id LIMIT 1");
    $p = mysqli_fetch_assoc($res);

    if (isset($_POST['zapisz'])) {

        $tytul  = mysqli_real_escape_string($conn, $_POST['tytul']);
        $opis   = mysqli_real_escape_string($conn, $_POST['opis']);
        $cena   = floatval($_POST['cena_netto']);
        $vat    = intval($_POST['podatek_vat']);
        $ilosc  = intval($_POST['ilosc']);
        $status = intval($_POST['status']);
        $kat    = mysqli_real_escape_string($conn, $_POST['kategoria']);
        $gab    = mysqli_real_escape_string($conn, $_POST['gabaryt']);
        $img    = mysqli_real_escape_string($conn, $_POST['zdjecie']);
        $exp    = $_POST['data_wygasniecia'] !== "" ? "'".$_POST['data_wygasniecia']."'" : "NULL";

        mysqli_query($conn, "
            UPDATE products SET
                tytul='$tytul',
                opis='$opis',
                data_modyfikacji=NOW(),
                data_wygasniecia=$exp,
                cena_netto=$cena,
                podatek_vat=$vat,
                ilosc_dostepnych_sztuk=$ilosc,
                status_dostepnosci=$status,
                kategoria='$kat',
                gabaryt_produktu='$gab',
                zdjecie='$img'
            WHERE id=$id
        ");

        echo "<p style='color:green;font-weight:bold;'>Zapisano zmiany</p>";
    }

    echo '
    <h3>Edytuj produkt</h3>
    <form method="post">
        Tytuł: <input type="text" name="tytul" value="'.$p['tytul'].'"><br><br>
        Opis:<br><textarea name="opis">'.$p['opis'].'</textarea><br><br>
        Cena netto: <input type="text" name="cena_netto" value="'.$p['cena_netto'].'"><br>
        VAT: <input type="number" name="podatek_vat" value="'.$p['podatek_vat'].'"><br>
        Ilość: <input type="number" name="ilosc" value="'.$p['ilosc_dostepnych_sztuk'].'"><br>
        Status:
        <select name="status">
            <option value="1" '.($p['status_dostepnosci'] ? 'selected' : '').'>Dostępny</option>
            <option value="0" '.(!$p['status_dostepnosci'] ? 'selected' : '').'>Niedostępny</option>
        </select><br>
        Data wygaśnięcia: <input type="date" name="data_wygasniecia" value="'.$p['data_wygasniecia'].'"><br>
        Kategoria: <input type="text" name="kategoria" value="'.$p['kategoria'].'"><br>
        Gabaryt: <input type="text" name="gabaryt" value="'.$p['gabaryt_produktu'].'"><br>
        Zdjęcie: <input type="text" name="zdjecie" value="'.$p['zdjecie'].'"><br><br>
        <input type="submit" name="zapisz" value="Zapisz zmiany">
    </form>
    ';
}

/* ==================================================
   Funkcja: USUŃ PRODUKT
   ================================================== */
function UsunProdukt($conn)
{
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id=$id LIMIT 1");
    header("Location: admin_produkty.php");
    exit();
}

/* ==================================================
   Funkcja: POKAŻ PRODUKTY
   ================================================== */
function PokazProdukty($conn)
{
    $res = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

    echo "<h3>Lista produktów</h3>";
    echo "<a href='?add=1'>+ Dodaj produkt</a><br><br>";

    echo "<table border='1' cellpadding='5'>
        <tr>
            <th>ID</th>
            <th>Tytuł</th>
            <th>Cena netto</th>
            <th>Ilość</th>
            <th>Dostępność</th>
            <th>Akcje</th>
        </tr>";

    while ($p = mysqli_fetch_assoc($res)) {
        $dostepny = CzyProduktDostepny($p) ? "TAK" : "NIE";

        echo "<tr>
            <td>{$p['id']}</td>
            <td>{$p['tytul']}</td>
            <td>{$p['cena_netto']} zł</td>
            <td>{$p['ilosc_dostepnych_sztuk']}</td>
            <td>$dostepny</td>
            <td>
                <a href='?edit={$p['id']}'>Edytuj</a> |
                <a href='?delete={$p['id']}' onclick=\"return confirm('Usunąć produkt?')\">Usuń</a>
            </td>
        </tr>";
    }

    echo "</table>";
}

/* ==================================================
   ROUTING
   ================================================== */
if (isset($_GET['add'])) {
    DodajProdukt($conn);
    exit();
}

if (isset($_GET['edit'])) {
    EdytujProdukt($conn);
    exit();
}

if (isset($_GET['delete'])) {
    UsunProdukt($conn);
    exit();
}

PokazProdukty($conn);
?>
