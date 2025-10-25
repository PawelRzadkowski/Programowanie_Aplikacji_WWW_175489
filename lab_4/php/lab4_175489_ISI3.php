<?php
	$nr_indeksu = '175489';
	$nrGrupy = '3';
	
	echo 'Paweł Rzadkowski '.$nr_indeksu.' grupa '.$nrGrupy.' <br/><br/>';
	
	echo 'Zastosowanie metody include() i require_once <br/>';
	include "test.php";
	echo ''.$zmienna2.' wazy '.$zmienna1.' gram <br/>';
	require_once "funkcje.php"; 
	echo "Funkcje zostały załadowane ale kod się nie powtórzy <br>";
	
	echo " Warunki if, else, elseif, switch <br/>";
	$iter = 5;
	if ($ocena >= 10) {
		echo "bedzie iterowac wiecej niz badz 10 razy <br>";
} 	elseif ($ocena >= 5) {
		echo "Bedzie iterowac wiecej niz badz 5 razy ale mniej niz 10 <br>";
} 	else {
		echo "bedzie iterowac mniej niz 5 razy";
} 

switch ($dzien) {
    case "poniedziałek":
        echo "Dziś jest $dzien - początek tygodnia.<br>";
        break;
    case "wtorek":
        echo "Dziś jest $dzien - drugi dzień tygodnia.<br>";
        break;
    case "środa":
        echo "Dziś jest $dzien - środek tygodnia.<br>";
        break;
    case "piątek":
        echo "Dziś jest $dzien - prawie weekend!<br>";
        break;
    default:
        echo "Dziś jest $dzien.<br>";
}

echo " Pętla while() i for()";

echo "Pętla while()";
$licznik = 1;
echo "Liczenie od 1 do 5 (while): ";
while ($licznik <= 5) {
    echo $licznik . " ";
    $licznik++;
}
echo "<br>";

echo "Pętla for()";
echo "Tabliczka mnożenia przez 3 (for): ";
for ($i = 1; $i <= 5; $i++) {
    echo ($i * 3) . " ";
}
echo "<br>";

echo " Typy zmiennych " ;

echo "<h3>\$_GET</h3>";
echo "Dane z adresu URL (parametry GET):<br>";
if (!empty($_GET)) {
    foreach ($_GET as $klucz => $wartosc) {
        echo "$klucz: $wartosc<br>";
    }
} else {
    echo "Brak parametrów GET. Przykład: ?imie=Jan&wiek=25<br>";
}

echo "<h3>\$_POST</h3>";
echo "Dane z formularza (metoda POST):<br>";
if (!empty($_POST)) {
    foreach ($_POST as $klucz => $wartosc) {
        echo "$klucz: $wartosc<br>";
    }
} else {
    echo "Brak danych POST.<br>";
}

// POST test
echo '<form method="post">
    <input type="text" name="test_imie" placeholder="Wpisz imię">
    <input type="submit" value="Wyślij">
</form>';

echo "<h3>\$_SESSION</h3>";
session_start();

if (!isset($_SESSION['licznik'])) {
    $_SESSION['licznik'] = 1;
    echo "Sesja zainicjowana. Licznik: " . $_SESSION['licznik'] . "<br>";
} else {
    $_SESSION['licznik']++;
    echo "Odwiedzenia strony: " . $_SESSION['licznik'] . " razy<br>";
}

echo "ID sesji: " . session_id() . "<br>";
?>