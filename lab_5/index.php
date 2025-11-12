<?php
error_reporting(E_ALL);

$idp = $_GET['idp'] ?? 'logowanie'; 
$sciezka = "html/$idp.html";

if (file_exists($sciezka)) {
    $strona = $sciezka;
} else {
    $strona = "html/logowanie.html";
}

$nr_indeksu = ‘175489’;
 $nrGrupy = ‘ISI 3’;
 echo ‘Autor: Paweł Rzadkowski ‘.$nr_indeksu.’ grupa ‘.$nrGrupy.’ <br /><br />’;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="description" content="Projekt 1">
<meta name="keywords" content="HTML5, CSS3, JavaScript">
<meta name="author" content="Pawel Rzadkowski">
<link rel="stylesheet" href="css/style.css">
<script src="java/timedate.js" type="text/javascript"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
<title>Największe budynki świata</title>
</head>

<body>
<div class="container">

  <div class="header">
    <img src="img/webp.webp" alt="szyld_obraz">
    <img src="img/Header.png" class="nieformat" alt="logo">&nbsp;&nbsp;
    <img src="img/Header4.png" alt="szyld_obraz2">
  </div>

  <div class="navigation">
    <table>
      <tr><td><a href="index.php?idp=glowna">Strona główna</a><hr></td></tr>
      <tr><td><a href="index.php?idp=BK">Burdż Khalifa</a><hr></td></tr>
      <tr><td><a href="index.php?idp=M118">Merdeka 118</a><hr></td></tr>
	  <tr><td><a href="index.php?idp=SHT">Shanghai Tower</a><hr></td></tr>
	  <tr><td><a href="index.php?idp=Abradz">Abradż al-Bajt</a><hr></td></tr>
      <tr><td><a href="index.php?idp=Pingan">Ping An</a><hr></td></tr>
      <tr><td><a href="index.php?idp=filmy">Filmy</a><hr></td></tr>
    </table>
    <br><br>
    <img src="img/Background_naw3.png" alt="nawigacja_obraz">
  </div>

  <div class="main">
    <?php include($strona); ?>
  </div>

  <div class="footer">
    <p><u><b>Kontakt</b></u></p>
    <img src="img/Foot_P.png" alt="telefon">&nbsp;&nbsp;<span>+48 123 456 789</span>
  </div>

</div>

<?php
  $nr_indeksu = '175489';
  $nrGrupy = '3';
  echo 'Autor:  '.$nr_indeksu.' grupa '.$nrGrupy.'<br /><br />';
?>
</body>
</html>