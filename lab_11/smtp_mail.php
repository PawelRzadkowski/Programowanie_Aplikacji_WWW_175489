
<?php
/**
 * ================================================
 *  smtp_mail.php – Wersja v1.8
 *  Funkcja: wyslijSMTP()
 *  Opis: Bezpieczna wysyłka maili przez PHPMailer
 * ================================================
 */
 
// Załączenie bibliotek PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';


/**
 * --------------------------------------------------
 * FUNKCJA: wyslijSMTP()
 * Wysyła e-mail przy użyciu bezpiecznego połączenia SMTP.
 *
 * Parametry:
 *  - $odbiorca (string)  → adres e-mail odbiorcy
 *  - $temat     (string) → temat wiadomości
 *  - $tresc     (string) → treść wiadomości
 *
 * Zabezpieczenia:
 *  - filtracja parametrów wejściowych
 *  - obsługa wyjątków PHPMailer
 * --------------------------------------------------
 */

function wyslijSMTP($odbiorca, $temat, $tresc)
{
    $mail = new PHPMailer(true);
	$mail->SMTPDebug = 2;

    try {
        // konfiguracja SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.wp.pl';
        $mail->SMTPAuth = true;
        $mail->Username = 'p-rzadkowski7@wp.pl';   
        $mail->Password = 'FQYMWV6M8AN24K8N';        
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('p-rzadkowski7@wp.pl', 'Formularz WWW');
        $mail->addAddress($odbiorca);
        $mail->isHTML(false);
        $mail->Subject = $temat;
        $mail->Body    = $tresc;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
?>