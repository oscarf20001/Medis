<?php
require '../db_connection.php';
header('Content-Type: application/json; charset=utf-8');

// Überprüfen, ob ein Suchbegriff gesendet wurde
if (isset($_GET['uniEmail']) && !empty($_GET['uniEmail'])) {
    $query = $conn->real_escape_string($_GET['uniEmail']); // SQL-Injection vermeiden

    // Beispiel: Suche nach E-Mails, die mit der Eingabe übereinstimmen
    $sql = "SELECT id, vorname, name, uniEmail, paid, sent, code FROM mails2 WHERE uniEmail LIKE '$query%'";
    $result = $conn->query($sql);

    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row; // Füge jede Zeile als assoziatives Array hinzu
        }
    }

    // Ergebnisse als JSON zurückgeben
    echo json_encode($data);
} else {
    echo json_encode([]); // Leeres Array, falls kein Suchbegriff vorhanden ist
}

$conn->close();