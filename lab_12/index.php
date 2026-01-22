<?php
/**
 * index.php – Wersja v1.11 
 */
session_start();
include('cfg.php');
include('showpage.php');
require_once("contact.php");

error_reporting(E_ALL);

$idp = $_GET['idp'] ?? '8'; // Domyślnie strona główna
$contact = new Contact();

//  Pobranie treści
$page_content = PokazPodstrone($idp);

//  Obsługa i podmiana formularza kontaktowego {{}}
if (strpos($page_content, '{{CONTACT_FORM}}') !== false) {
    ob_start();
    $contact->WyslijMailKontakt("p-rzadkowski7@wp.pl");
    $status_maila = ob_get_clean();
    
    $form_html = $contact->PokazKontakt();
    $page_content = str_replace('{{CONTACT_FORM}}', $status_maila . $form_html, $page_content);
}

//  Obsługa koszyka
if (strpos($page_content, '{{CART}}') !== false) {
    require_once 'koszyk.php';
    ob_start();
    if(function_exists('showCart')) showCart();
    $cart_html = ob_get_clean();
    $page_content = str_replace('{{CART}}', $cart_html, $page_content);
}


//  Obsługa sklepu 
if (strpos($page_content, '{{SKLEP}}') !== false) {
    require_once 'sklep.php'; 
    ob_start();
    //gdyby nie mogło znaleść pliku to wyświetla string
    if(function_exists('PokazSklep')) PokazSklep(); 
    $sklep_html = ob_get_clean();
    $page_content = str_replace('{{SKLEP}}', $sklep_html, $page_content);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Największe Budowle Świata</title>
    
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="java/timedate.js"></script>
</head>
<body onload="startclock()">

<div class="container">
    <header class="header">
        <h1>Największe Budowle Świata</h1>
        <div class="time-box">
            <span id="data"></span> | <span id="zegarek"></span>
        </div>
    </header>

    <div class="content-wrapper">
        <nav class="navigation">
            <div class="menu-label">MENU</div>
            <a href="index.php?idp=8">Strona główna</a>
            <a href="index.php?idp=2">Burdż Khalifa</a>
            <a href="index.php?idp=4">Merdeka 118</a>
            <a href="index.php?idp=6">Shanghai Tower</a>
            <a href="index.php?idp=1">Abradż al-Bajt</a>
            <a href="index.php?idp=5">Ping An</a>
            <a href="index.php?idp=3">Filmy</a>
            <a href="index.php?idp=9">Sklep</a>
            <a href="index.php?idp=99">Koszyk</a>
			<a href="index.php?idp=7">KONTAKT</a>
            
            <div class="nav-image">
                <img src="img/Background_naw3.png" alt="nawigacja" style="width:100%; border-radius:8px; margin-top:20px;">
            </div>
        </nav>

        <main class="main">
            <?php echo $page_content; ?>
        </main>
		
		<script>
$(document).ready(function() {
    //  Animacja pasków postępu (Liderzy)
    let numbers = $(".leader .number").map(function() {
        return parseInt($(this).text());
    }).get();

    let max = Math.max(...numbers);

    $(".leader").each(function() {
        let num = parseInt($(this).find(".number").text());
        let perc = (num / max) * 100;
        $(this).find(".progress-bar").animate({ width: perc + "%" }, 1000);
    });

    //  Animacja budynków (Powiększanie)
    $(".building").on("click", function() {
        $(this).animate({ transform: 'scale(1.1)' }, 200).animate({ transform: 'scale(1.0)' }, 200);
        $(this).toggleClass("active-building");
    });
    
    
});
</script>
</script>
    </div>

    <footer class="footer">
        <p>&copy; 2026 Największe Budowle Świata. Wszystkie prawa zastrzeżone.</p>
    </footer>
</div>

</body>
</html>