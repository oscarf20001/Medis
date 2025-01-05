<?php
// JSON-Daten aus der Anfrage empfangen
$data = json_decode(file_get_contents('php://input'), true);

// Sicherstellen, dass alle notwendigen Daten vorhanden sind
if (isset($data['success']) && $data['success'] === true) {
    $logEntry = sprintf(
        "[%s] Erfolgreich E-Mail gesendet an %s %s (ID: %d, Code: %s, E-Mail: %s)\n",
        date('Y-m-d H:i:s'),
        $data['vorname'],
        $data['nachname'],
        $data['id'],
        $data['code'],
        $data['mail']
    );

    // Log-Datei anfügen (oder erstellen, falls sie nicht existiert)
    file_put_contents('email_log.txt', $logEntry, FILE_APPEND);
}
?>