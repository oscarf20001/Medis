<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
error_reporting(0);
ini_set('display_errors', 0);


include 'db_connection.php';  // Verbindung zur Datenbank

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    // Eingabewerte absichern
    $user = $conn->real_escape_string($_POST['username']);
    $pass = $conn->real_escape_string($_POST['password']);

    // SQL-Abfrage zur Suche des Benutzers
    $sql = "SELECT COUNT(*) AS count FROM user WHERE username = '$user' AND password = '$pass'";
    $result = $conn->query($sql);

    // Überprüfen, ob ein Datensatz gefunden wurde
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['count'] == 1) {
            // Login erfolgreich
            setcookie('user_session', base64_encode($user), time() + 1800, "/");
            echo json_encode(["success" => true]);
            #return true;
        } else {
            // Login fehlgeschlagen
            echo json_encode(["success" => false]);
            #return false;
        }
    } else {
        echo json_encode(["success" => false]);
        #return false;
    }

    // Verbindung schließen
    $conn->close();
    exit();  // Den PHP-Prozess nach der Antwort beenden
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <script>
        // Wenn die Seite geladen wird, öffne Prompts für Benutzername und Passwort
        function showPrompts(){
            let username = prompt("Bitte gib deinen Benutzernamen ein:");
            let password = prompt("Bitte gib dein Passwort ein:");

            // Überprüfen, ob die Eingaben nicht leer sind
            if (username && password) {
                // FormData für die AJAX-Anfrage vorbereiten
                let formData = new FormData();
                formData.append("username", username);
                formData.append("password", password);

                // Sende die Daten per POST an das PHP-Skript
                fetch("../login.php", { method: "POST", body: formData })
                .then(response => response.text())  // Als Text lesen, um Fehler zu erkennen
                .then(data => {
                    console.log(data);  // Zeigt die tatsächliche Antwort an
                    return JSON.parse(data);  // Dann parsen
                })
                .then(json => {
                    if (json.success) {
                        alert("Login erfolgreich!");
                    } else {
                        alert("Login fehlgeschlagen. Benutzername oder Passwort sind falsch. Bitte erneut versuchen!");
                        window.location = 'search.php';
                    }
                })
                .catch(error => console.error("Fehler beim Parsen:", error));
            } else {
                alert("Benutzername und Passwort dürfen nicht leer sein.");
            }
        }

        function setCookie(name, value, minutes){
            const date = new Date();
            date.setTime(date.getTime() + (minutes*60*1000));
            const expires = "expires" + date.toUTString();
            document.cookie = `${name}=${value}; ${expires}; path=/`;
        }

        function getCookie(name){
            const cookies = document.cookie.split(';');
            for(let i = 0; i<cookies.length;i++){
                const cookie = cookies[i].trim();
                if(cookie.startsWith(name+='=')){
                    return cookie.substring(name.length+1);
                }
            }
            return null;
        }

        if (!getCookie("user_session")) {
            showPrompts();
        }
    </script>
</body>
</html>