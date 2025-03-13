<?php
require '../db_connection.php'; // Stelle sicher, dass die Datenbankverbindung korrekt ist.

header('Content-Type: application/json');

// Überprüfen, ob ein Suchbegriff gesendet wurde
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']); // SQL-Injection vermeiden

    // Beispiel: Suche nach E-Mails, die mit der Eingabe übereinstimmen
    $sql = "SELECT uniEmail FROM mails2 WHERE uniEmail LIKE '$query%' LIMIT 10";
    $result = $conn->query($sql);

    $suggestions = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row['uniEmail'];
        }
    }

    // Ergebnisse als JSON zurückgeben
    echo json_encode($suggestions);
} else {
    echo json_encode([]); // Leeres Array, falls kein Suchbegriff vorhanden ist
}

$conn->close();