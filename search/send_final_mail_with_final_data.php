<?php
require '../db_connection.php';
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Dotenv laden
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


header('Content-Type: application/json; charset=utf-8');

// Überprüfen, ob eine id übergeben wurde
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['vorname']) && !empty($_GET['vorname']) && isset($_GET['nachname']) && !empty($_GET['nachname']) && isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['mail']) && !empty($_GET['mail'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $vorname = $conn->real_escape_string($_GET['vorname']);
    $nachname = $conn->real_escape_string($_GET['nachname']);
    $code = $conn->real_escape_string($_GET['code']);
    $Email = $conn->real_escape_string($_GET['mail']);

    send_mail($vorname,$code, $Email, $mailHost,$mailPassword,$mailPort, $mailUsername);

    // Beispiel: Suche nach E-Mails, die mit der Eingabe übereinstimmen
    #$sql = "SELECT id, vorname, name, uniEmail, paid, sent, code FROM mails WHERE uniEmail LIKE '$query%'";
    $time = date('Y/m/d H:i:s') . '.' . substr(microtime(true), -4);
    $sql = "UPDATE mails SET paid = 1, sent = 1, ts_paid = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);  // Vorbereitetes Statement erstellen
    $stmt->bind_param("si", $time, $id);  // Parameter binden (string, integer)
    $stmt->execute();  // Ausführen der vorbereiteten Abfrage
    $stmt->close();  // Schließen des Statements
} else {
    echo json_encode([]); // Leeres Array, falls kein Suchbegriff vorhanden ist
}

$conn->close();

function send_mail($vorname,$code, $Email,$mailHost,$mailPassword,$mailPort, $mailUsername){
    try {
        $nachricht = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Rechnung</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                    }
                    p {
                        margin: 16px 0;
                    }
                </style>
            </head>
            <body>
                <h3>Hallo " . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') . ",</h3>
                <p>Vielen Dank für deine Überweisung<br>
                Hiermit erhältst du deinen persönlichen Ticket Code<br>
                Dieser lautet: " . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . "</p>

                <p>Du hast bis zum 14.1.2025 Zeit deinen Code einzulösen und dir dein Medi Ticket auf https://medimeisterschaften.com zu kaufen. Falls du deinen Ticket Code bis zum 14. Januar nicht eingelöst hast, wird dieser an andere Person weiter verlost.</p><br>

                <p>Wir freuen uns mit dir auf die Medis2025</p>
                <p>#NurLiebe<br>
                Deine Medi-Hauptorga</p>
            </body>
            </html>
        ";

        // Neues PHPMailer-Objekt für jede E-Mail
        $mail = new PHPMailer(true);

        // SMTP-Konfiguration
        $mail->isSMTP();
        $mail->Host       = $mailHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailUsername;
        $mail->Password   = $mailPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $mailPort;
        $mail->CharSet    = 'UTF-8';

        // Absender & Empfänger
        $mail->setFrom($mailUsername, 'Medimeisterschaften Uni Witten');
        $mail->addReplyTo('streiosc@curiegym.de', 'Oscar');
        $mail->addAddress($Email, $vorname);

         // MDN und DSN Header
        $mail->addCustomHeader('Disposition-Notification-To', $mailUsername); // MDN
        $mail->addCustomHeader('Return-Path', $mailUsername); // DSN

        // Nachricht
        $mail->isHTML(true);
        $mail->Subject = 'DEIN Medicode 2025';
        $mail->Body    = $nachricht;
        $mail->AltBody = 'Dein Code für die Medimeisterschaften 2025';

        $mail->send();
        $mailErfolg[] = "E-Mail erfolgreich an {$Email} mit dem Code: {$code} gesendet.";
        $mail->clearAddresses();
        $data = ['success' => true];
        echo json_encode($data);
        return true;
    } catch (Exception $e) {
        $mailFehler[] = "Fehler beim Senden der E-Mail an {$Email}: " . $mail->ErrorInfo;
        $mail->clearAddresses();
        $data = ['success' => false];
        return false;
    }
}