<?php
session_start();
require_once "cfg.php";

/* ZABEZPIECZENIE – tylko zalogowany admin */
if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header("Location: admin.php");
    exit();
}

echo "<h2>Zarządzanie kategoriami</h2>";
echo '<a href="admin.php">← Powrót do panelu</a><br><br>';

/* ===============================
   DODAWANIE KATEGORII
   =============================== */
if (isset($_POST['dodaj'])) {
    $nazwa = mysqli_real_escape_string($conn, $_POST['nazwa']);
    $matka = intval($_POST['matka']);

    if ($nazwa != "") {
        mysqli_query(
            $conn,
            "INSERT INTO kategorie (nazwa, matka) VALUES ('$nazwa', $matka)"
        );
        echo "<p style='color:green'>Dodano kategorię</p>";
    }
}

/* ===============================
   USUWANIE KATEGORII
   =============================== */
if (isset($_GET['usun'])) {
    $id = intval($_GET['usun']);

    // usuń dzieci
    mysqli_query($conn, "DELETE FROM kategorie WHERE matka = $id");
    // usuń kategorię
    mysqli_query($conn, "DELETE FROM kategorie WHERE id = $id LIMIT 1");

    echo "<p style='color:red'>Usunięto kategorię</p>";
}

/* ===============================
   FORMULARZ DODAWANIA
   =============================== */
echo '
<h3>Dodaj kategorię</h3>
<form method="post">
    Nazwa: <input type="text" name="nazwa">
    Matka:
    <select name="matka">
        <option value="0">Kategoria główna</option>';

$matki = mysqli_query($conn, "SELECT * FROM kategorie WHERE matka = 0 LIMIT 50");
while ($m = mysqli_fetch_assoc($matki)) {
    echo '<option value="'.$m['id'].'">'.$m['nazwa'].'</option>';
}

echo '
    </select>
    <input type="submit" name="dodaj" value="Dodaj">
</form>
<hr>
';

/* ===============================
   WYŚWIETLANIE DRZEWA KATEGORII
   (PĘTLE ZAGNIEŻDŻONE – TIP 2)
   =============================== */

echo "<h3>Lista kategorii</h3>";

$matki = mysqli_query($conn, "SELECT * FROM kategorie WHERE matka = 0 LIMIT 50");

while ($m = mysqli_fetch_assoc($matki)) {

    echo "<b>".$m['nazwa']."</b> ";
    echo '<a href="?usun='.$m['id'].'" onclick="return confirm(\'Usunąć?\')">[usuń]</a><br>';

    $dzieci = mysqli_query(
        $conn,
        "SELECT * FROM kategorie WHERE matka = ".$m['id']." LIMIT 50"
    );

    while ($d = mysqli_fetch_assoc($dzieci)) {
        echo "&nbsp;&nbsp;— ".$d['nazwa']." ";
        echo '<a href="?usun='.$d['id'].'">[usuń]</a><br>';
    }
}
?>