<?php
require '../db_connection.php';

$file = '../codes/round2/ids2.csv';

// Öffne die CSV-Datei
if (($handle = fopen($file, "r")) !== FALSE) {
    // Zeilen durchlaufen
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $id = $data[0]; // Annahme: Die ID befindet sich in der ersten Spalte
        
        // Überprüfen, ob die ID in der Tabelle 'master' existiert
        $sqlSelect = "SELECT * FROM master WHERE id = ?";
        $stmt = $conn->prepare($sqlSelect);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Wenn die ID gefunden wird, füge den Datensatz in 'mails2' ein
            $row = $result->fetch_assoc();
            $sqlInsert = "INSERT INTO mails2 (id, name, vorname, uniEmail, time_GMT, email, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("isssssi", $row['id'], $row['vorname'], $row['name'], $row['uniEmail'], $row['time_GMT'],$row['email'],$row['price']);
            if ($stmtInsert->execute()) {
                echo "Datensatz für ID {$row['id']} erfolgreich eingefügt.<br>";
            } else {
                echo "Fehler beim Einfügen des Datensatzes für ID {$row['id']}: " . $stmtInsert->error . "<br>";
            }
        } else {
            echo "ID $id nicht in der Tabelle 'master' gefunden.<br>";
        }
        $stmt->close();
    }
    fclose($handle);
} else {
    echo "Die Datei konnte nicht geöffnet werden.";
}

$conn->close();
?>
