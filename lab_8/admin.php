<?php
function FormularzLogowania(){
    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Panel CMS:</h1>
      <div class="logowanie">
       <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
        <table class="logowanie">
         <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
         <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
         <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
        </table>
       </form>
      </div>
     </div>
     ';
     
     return $wynik; 
}

session_start();
require_once "cfg.php";   


if (isset($_POST['x1_submit'])) {

    if ($_POST['login_email'] == $login && $_POST['login_pass'] == $pass) {
        $_SESSION['zalogowany'] = true;
    } else {
        echo FormularzLogowania("Niepoprawny email lub hasło");
        exit();
    }
}

// Sprawdzenie czy uzytkownik jest zalogowany
if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    echo FormularzLogowania();
    exit();
}

if (isset($_POST['wyloguj'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}


echo "<h2>Witaj w panelu administratora</h2>";

echo '<form method="post"><input type="submit" name="wyloguj" value="Wyloguj"></form>';

// ====================
// Funkcje CMS
// ====================

function ListaPodstron($conn)
{
    $sql = "SELECT id, page_title FROM page_list";
    $result = mysqli_query($conn, $sql);

    echo '<h2>Lista podstron</h2>';

    echo '<table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Tytuł podstrony</th>
                <th>Akcje</th>
            </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['page_title'].'</td>
                <td>
                    <a href="admin.php?edit='.$row['id'].'">Edytuj</a> |
                    <a href="admin.php?delete='.$row['id'].'" onclick="return confirm(\'Na pewno usunąć?\')">Usuń</a>
                </td>
              </tr>';
    }

    echo '</table>';
}

function EdytujPodstrone($conn)
{
    if (!isset($_GET['edit'])) {
        echo "<p>Nie wybrano podstrony do edycji.</p>";
        return;
    }

    $id = intval($_GET['edit']);

    // Pobranie danych podstrony
    $sql = "SELECT * FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "<p>Nie znaleziono podstrony.</p>";
        return;
    }

    $row = mysqli_fetch_assoc($result);


    if (isset($_POST['zapisz'])) {

        $title   = mysqli_real_escape_string($conn, $_POST['page_title']);
        $content = mysqli_real_escape_string($conn, $_POST['page_content']);
        $status  = isset($_POST['page_active']) ? 1 : 0;

        $update = "
            UPDATE page_list 
            SET 
                page_title = '$title',
                page_content = '$content',
                status = $status
            WHERE id = $id
            LIMIT 1
        ";

        if (mysqli_query($conn, $update)) {
            echo "<p style='color:green; font-weight:bold;'>Zapisano zmiany!</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>Błąd podczas zapisu!</p>";
        }
    }

    echo '
    <h2>Edytuj podstronę</h2>
    <form method="post">

        <label>Tytuł podstrony:</label><br>
        <input type="text" name="page_title" value="'.htmlspecialchars($row['page_title']).'" style="width:400px;">
        <br><br>

        <label>Treść strony:</label><br>
        <textarea name="page_content" rows="10" cols="70">'.htmlspecialchars($row['page_content']).'</textarea>
        <br><br>

        <label>
            <input type="checkbox" name="page_active" '.($row['status'] == 1 ? 'checked' : '').'>
            Strona aktywna
        </label>
        <br><br>

        <input type="submit" name="zapisz" value="Zapisz zmiany">

    </form>
    ';
}

function DodajNowaPodstrone($conn)
{
    if (isset($_POST['dodaj'])) {

        $title   = mysqli_real_escape_string($conn, $_POST['page_title']);
        $content = mysqli_real_escape_string($conn, $_POST['page_content']);
        $status  = isset($_POST['page_active']) ? 1 : 0;

        if ($title == "" || $content == "") {
            echo '<p style="color:red; font-weight:bold;">Uzupełnij wszystkie pola!</p>';
        } else {

            $sql = "
                INSERT INTO page_list (page_title, page_content, status)
                VALUES ('$title', '$content', $status)
            ";

            if (mysqli_query($conn, $sql)) {
                echo '<p style="color:green; font-weight:bold;">Dodano nową podstronę!</p>';
            } else {
                echo '<p style="color:red; font-weight:bold;">Błąd przy dodawaniu podstrony!</p>';
            }
        }
    }

    echo '
    <h2>Dodaj nową podstronę</h2>

    <form method="post">

        <label>Tytuł strony:</label><br>
        <input type="text" name="page_title" style="width:400px;"><br><br>

        <label>Treść strony:</label><br>
        <textarea name="page_content" rows="10" cols="70"></textarea><br><br>

        <label>
            <input type="checkbox" name="page_active">
            Strona aktywna
        </label><br><br>

        <input type="submit" name="dodaj" value="Dodaj podstronę">

    </form>
    ';
}

function UsunPodstrone($conn)
{
    if (!isset($_GET['delete'])) {
        echo "<p>Nie podano ID podstrony do usunięcia.</p>";
        return;
    }

    $id = intval($_GET['delete']);

    // sprawdź, czy podstrona istnieje
    $check = mysqli_query($conn, "SELECT id FROM page_list WHERE id = $id LIMIT 1");

    if (mysqli_num_rows($check) == 0) {
        echo "<p style='color:red; font-weight:bold;'>Podstrona o ID $id nie istnieje.</p>";
        return;
    }

    // usuwanie
    $sql = "DELETE FROM page_list WHERE id = $id LIMIT 1";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green; font-weight:bold;'>Podstrona została usunięta!</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>Błąd podczas usuwania podstrony.</p>";
    }

    echo '<p><a href="admin.php">Powrót do listy</a></p>';
}


if (isset($_GET['add'])) {
    DodajNowaPodstrone($conn);
    exit();
}

if (isset($_GET['edit'])) {
    EdytujPodstrone($conn);
    exit();
}

if (isset($_GET['delete'])) {
    UsunPodstrone($conn);
    exit();
}

// Domyślnie lista podstron
ListaPodstron($conn);
?>