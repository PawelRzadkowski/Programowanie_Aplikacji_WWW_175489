<?php
session_start();
require_once "cfg.php";

/* ZABEZPIECZENIE CMS */
if (!isset($_SESSION['zalogowany'])) {
    header("Location: admin.php");
    exit();
}

echo "<h2>Panel zarządzania produktami</h2>";
echo '<a href="admin.php">← Powrót do panelu</a><br><br>';

/* ==================================================
   FUNKCJA: WARUNKI DOSTĘPNOŚCI PRODUKTU
   ================================================== */
function CzyProduktDostepny($p)
{
    if ($p['availability_status'] == 0) return false;
    if ($p['stock'] <= 0) return false;
    if ($p['expires_at'] !== null && strtotime($p['expires_at']) < time()) {
        return false;
    }
    return true;
}

/* ==================================================
   FUNKCJA: DODAJ PRODUKT
   ================================================== */
function DodajProdukt($conn)
{
    if (isset($_POST['dodaj'])) {

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $desc  = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price_net']);
        $vat   = intval($_POST['vat']);
        $stock = intval($_POST['stock']);
        $status = intval($_POST['availability_status']);
        $cat   = intval($_POST['category_id']);
        $size  = mysqli_real_escape_string($conn, $_POST['size']);
        $img   = mysqli_real_escape_string($conn, $_POST['image']);
        $exp   = $_POST['expires_at'] !== "" ? "'".$_POST['expires_at']."'" : "NULL";

        mysqli_query($conn, "
            INSERT INTO products
            (title, description, created_at, expires_at, price_net, vat, stock, availability_status, category_id, size, image)
            VALUES
            ('$title','$desc',NOW(),$exp,$price,$vat,$stock,$status,$cat,'$size','$img')
        ");

        echo "<p style='color:green;font-weight:bold;'>Produkt dodany</p>";
    }

    echo '
    <h3>Dodaj produkt</h3>
    <form method="post">
        Nazwa: <input type="text" name="title" required><br><br>
        Opis:<br><textarea name="description"></textarea><br><br>
        Cena netto: <input type="text" name="price_net" required><br>
        VAT: <input type="number" name="vat" value="23"><br>
        Stan magazynu: <input type="number" name="stock"><br>
        Status:
        <select name="availability_status">
            <option value="1">Dostępny</option>
            <option value="0">Niedostępny</option>
        </select><br>
        Data wygaśnięcia: <input type="date" name="expires_at"><br>
        Kategoria ID: <input type="number" name="category_id"><br>
        Gabaryt: <input type="text" name="size"><br>
        Zdjęcie (link): <input type="text" name="image"><br><br>
        <input type="submit" name="dodaj" value="Dodaj produkt">
    </form>
    <hr>
    ';
}

/* ==================================================
   FUNKCJA: EDYTUJ PRODUKT
   ================================================== */
function EdytujProdukt($conn)
{
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id LIMIT 1");
    $p = mysqli_fetch_assoc($res);

    if (isset($_POST['zapisz'])) {

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $desc  = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price_net']);
        $vat   = intval($_POST['vat']);
        $stock = intval($_POST['stock']);
        $status = intval($_POST['availability_status']);
        $cat   = intval($_POST['category_id']);
        $size  = mysqli_real_escape_string($conn, $_POST['size']);
        $img   = mysqli_real_escape_string($conn, $_POST['image']);
        $exp   = $_POST['expires_at'] !== "" ? "'".$_POST['expires_at']."'" : "NULL";

        mysqli_query($conn, "
            UPDATE products SET
                title='$title',
                description='$desc',
                updated_at=NOW(),
                expires_at=$exp,
                price_net=$price,
                vat=$vat,
                stock=$stock,
                availability_status=$status,
                category_id=$cat,
                size='$size',
                image='$img'
            WHERE id=$id
        ");

        echo "<p style='color:green;font-weight:bold;'>Zapisano zmiany</p>";
    }

    echo '
    <h3>Edytuj produkt</h3>
    <form method="post">
        Nazwa: <input type="text" name="title" value="'.$p['title'].'"><br><br>
        Opis:<br><textarea name="description">'.$p['description'].'</textarea><br><br>
        Cena netto: <input type="text" name="price_net" value="'.$p['price_net'].'"><br>
        VAT: <input type="number" name="vat" value="'.$p['vat'].'"><br>
        Stan: <input type="number" name="stock" value="'.$p['stock'].'"><br>
        Status:
        <select name="availability_status">
            <option value="1" '.($p['availability_status']?'selected':'').'>Dostępny</option>
            <option value="0" '.(!$p['availability_status']?'selected':'').'>Niedostępny</option>
        </select><br>
        Data wygaśnięcia: <input type="date" name="expires_at" value="'.$p['expires_at'].'"><br>
        Kategoria ID: <input type="number" name="category_id" value="'.$p['category_id'].'"><br>
        Gabaryt: <input type="text" name="size" value="'.$p['size'].'"><br>
        Zdjęcie: <input type="text" name="image" value="'.$p['image'].'"><br><br>
        <input type="submit" name="zapisz" value="Zapisz zmiany">
    </form>
    ';
}

/* ==================================================
   FUNKCJA: USUŃ PRODUKT
   ================================================== */
function UsunProdukt($conn)
{
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id=$id LIMIT 1");
    header("Location: admin_produkty.php");
}

/* ==================================================
   FUNKCJA: POKAŻ PRODUKTY
   ================================================== */
function PokazProdukty($conn)
{
    $res = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

    echo "<h3>Lista produktów</h3>";
    echo "<a href='?add=1'>+ Dodaj produkt</a><br><br>";

    echo "<table border='1' cellpadding='5'>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Cena netto</th>
            <th>Stan</th>
            <th>Dostępność</th>
            <th>Akcje</th>
        </tr>";

    while ($p = mysqli_fetch_assoc($res)) {
        $dostepny = CzyProduktDostepny($p) ? "TAK" : "NIE";

        echo "<tr>
            <td>{$p['id']}</td>
            <td>{$p['title']}</td>
            <td>{$p['price_net']} zł</td>
            <td>{$p['stock']}</td>
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