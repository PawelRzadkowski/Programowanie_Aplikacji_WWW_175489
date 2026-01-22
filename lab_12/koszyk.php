<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "cfg.php";

/* =========================
   Dodawanie do koszyka
   ========================= */
function addToCart($product_id, $qty = 1)
{
    global $conn;
    $product_id = intval($product_id);

    $res = mysqli_query($conn, "
        SELECT * FROM products WHERE id = $product_id LIMIT 1
    ");

    if (mysqli_num_rows($res) == 0) return;

    $p = mysqli_fetch_assoc($res);

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'tytul'       => $p['tytul'],
            'cena_netto'  => $p['cena_netto'],
            'podatek_vat' => $p['podatek_vat'],
            'ilosc'       => $qty
        ];
    } else {
        $_SESSION['cart'][$product_id]['ilosc'] += $qty;
    }
}

/* =========================
   Usuwanie z koszyka
   ========================= */
function removeFromCart($product_id)
{
    unset($_SESSION['cart'][$product_id]);
}

/* =========================
   Aktualizacja ilości
   ========================= */
function updateQty($product_id, $qty)
{
    if ($qty <= 0) {
        removeFromCart($product_id);
    } else {
        $_SESSION['cart'][$product_id]['ilosc'] = $qty;
    }
}

/* =========================
   Wyświetlanie koszyka
   ========================= */
function showCart()
{
    if (empty($_SESSION['cart'])) {
        echo "<p>Koszyk jest pusty</p>";
        return;
    }

    $total = 0;

    echo "<h2>Koszyk</h2>";
    echo "<table border='1' cellpadding='6'>
        <tr>
            <th>Produkt</th>
            <th>Cena brutto</th>
            <th>Ilość</th>
            <th>Razem</th>
            <th>Akcja</th>
        </tr>";

    foreach ($_SESSION['cart'] as $id => $p) {
        $cena_brutto = $p['cena_netto'] * (1 + $p['podatek_vat'] / 100);
        $suma = $cena_brutto * $p['ilosc'];
        $total += $suma;

        echo "<tr>
            <td>{$p['tytul']}</td>
            <td>".number_format($cena_brutto, 2)." zł</td>
            <td>
                <form method='post'>
                    <input type='hidden' name='id' value='$id'>
                    <input type='number' name='ilosc' value='{$p['ilosc']}' min='1'>
                    <input type='submit' name='update' value='OK'>
                </form>
            </td>
            <td>".number_format($suma, 2)." zł</td>
            <td>
                <a href='?remove=$id'>Usuń</a>
            </td>
        </tr>";
    }

    echo "<tr>
        <td colspan='3'><b>Suma</b></td>
        <td colspan='2'><b>".number_format($total, 2)." zł</b></td>
    </tr>";

    echo "</table>";
}

/* =========================
   Obsługa akcji
   ========================= */
if (isset($_GET['add'])) {
    addToCart($_GET['add']);
}

if (isset($_GET['remove'])) {
    removeFromCart($_GET['remove']);
}

if (isset($_POST['update'])) {
    updateQty($_POST['id'], $_POST['ilosc']);
}