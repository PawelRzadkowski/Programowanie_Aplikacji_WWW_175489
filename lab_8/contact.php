<?php
class Contact {

    public function PokazKontakt() {

        $html = '
        <div class="contact-form">
            <h2>Formularz kontaktowy</h2>

            <form method="POST" action="" class="contact-box">
                <label>Imię i nazwisko:</label><br>
                <input type="text" name="imie" required><br><br>

                <label>Adres e-mail:</label><br>
                <input type="email" name="email" required><br><br>

                <label>Treść wiadomości:</label><br>
                <textarea name="tresc" rows="6" required></textarea><br><br>

                <input type="submit" name="wyslij" value="Wyślij">
            </form>
        </div>
        ';

        return $html;
    }

 
    public function WyslijMailKontakt($odbiorca)
{
    if (!isset($_POST['wyslij'])) {
        return false;
    }

    $nadawca = htmlspecialchars($_POST['email']);
    $imie = htmlspecialchars($_POST['imie']);
    $tresc = htmlspecialchars($_POST['tresc']);

    $temat = "Wiadomość ze strony WWW od: $imie";

 
    require_once "smtp_mail.php";

    if (wyslijSMTP($odbiorca, $temat, $tresc)) {
        echo "<p style='color:green; font-weight:bold;'>Wiadomość została wysłana!</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>Błąd wysyłania maila!</p>";
    }
}

    public function PrzypomnijHaslo($email_admina, $haslo)
    {
        $temat = "Przypomnienie hasła – panel administratora";
        $wiadomosc = "Twoje hasło do panelu admina to: ".$haslo;

        $headers = "From: noreply@twojadomena.pl\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($email_admina, $temat, $wiadomosc, $headers)) {
            echo "<p style='color:green;font-weight:bold;'>Hasło wysłane na e-mail administratora!</p>";
        } else {
            echo "<p style='color:red;font-weight:bold;'>Błąd wysyłania hasła.</p>";
        }
    }

}
?>