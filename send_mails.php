<?php

include 'checks.php';
require 'db_connection.php';
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Dotenv laden
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Lade die Umgebungsvariablen
$dbHost = $_ENV['DB_HOST'];
$dbDatabase = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

$mailHost = $_ENV['MAIL_HOST'];
$mailUsername = $_ENV['MAIL_USERNAME'];
$mailPassword = $_ENV['MAIL_PASSWORD'];
$mailPort = $_ENV['MAIL_PORT'];

// Verbindung mit der Datenbank herstellen
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Datenbankverbindung fehlgeschlagen: " . $conn->connect_error);
}

// CSV-Datei einlesen
$csvDatei = 'codes/ids.csv';
if (!file_exists($csvDatei)) {
    die("Die CSV-Datei wurde nicht gefunden.");
}

$ids = [];
if (($handle = fopen($csvDatei, "r")) !== false) {
    while (($daten = fgetcsv($handle, 1000, ",")) !== false) {
        $ids[] = $daten[0]; // Erste Spalte der CSV-Datei als ID
    }
    fclose($handle);
}

if (empty($ids)) {
    die("Keine IDs in der CSV-Datei gefunden.");
}

// IDs in der Datenbank suchen und Mails vorbereiten
$query = "SELECT id, uniEmail, vorname, name, Horbach FROM master WHERE id = ?";
$mailErfolg = [];
$mailFehler = [];

foreach ($ids as $id) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $preis = $user['Horbach'] == 1 ? 25 : 35;
        $email = $user['uniEmail'];
        $vorname = $user['vorname'];
        $name = $user['name'];

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
                    <h3><strong>Herzlichen Glückwunsch " . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') . "</strong>, Du hast einen Medi-Ticket Code gewonnen🥳🥳🥳</h3>
                    <p>Bitte überweise das Geld für das Fan Paket " . htmlspecialchars($preis, ENT_QUOTES, 'UTF-8') . "€<br>innerhalb der nächsten fünf Tage auf das folgende Konto:</p>
                    <p>Maximilian Bockemühl<br>IBAN: DE91 3006 0601 0026 9885 76<br>BIC: DAAEDEDDXXX<br>Verwendungszweck: medis25 " . htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8') . "+" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "<br><br></p>

                    <p>Sende uns bitte als Bestätigung für deine Überweisung einen Zahlungsbeleg an folgende E-Mail Adresse: medimeister@uni-wh.de</p>

                    <p>Sobald das Geld da ist, erhältst du eine weitere Mail mit deinem persönlichen Ticket Code.</p>
                    <p>Sollten wir nach Ablauf der fünf Tage keine Überweisung erhalten haben, fällt dein Code wieder in den Lostopf.<br></p>

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

            // Empfänger
            $mail->setFrom($mailUsername, 'Medimeisterschaften Uni Witten');
            $mail->addReplyTo('streiosc@curiegym.de', 'Oscar');
            $mail->addAddress($email, $vorname);

            // Nachricht
            $mail->isHTML(true);
            $mail->Subject = 'Verlosung Medicodes 2025';
            $mail->Body    = $nachricht;

            $mail->send();
            $mailErfolg[] = "E-Mail erfolgreich an {$email} mit Preis {$preis}€ gesendet.";
            $mail->clearAddresses();
        } catch (Exception $e) {
            $mailFehler[] = "Fehler beim Senden der E-Mail an {$email}: " . $mail->ErrorInfo;
            $mail->clearAddresses();
        }
    } else {
        $mailFehler[] = "ID {$id} nicht in der Datenbank gefunden.";
    }
}

// Ergebnisse ausgeben
foreach ($mailErfolg as $erfolg) {
    echo $erfolg . "\n";
}

foreach ($mailFehler as $fehler) {
    echo $fehler . "\n";
}

#TODO: 
# - Suchmaske für ID progammiere

?>