<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Erstelle ein Dotenv-Objekt und lade die .env-Datei
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Greife auf die Umgebungsvariablen zu
$dbHost = $_ENV['DB_HOST'];
$dbDatabase = $_ENV['DB_NAME'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];

// Beispiel: Erstellen einer MySQL-Verbindung mit den Umgebungsvariablen
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);

// Verbindung auf UTF-8 setzen
$conn->set_charset("utf8");

// Überprüfen der Verbindung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medimeisterschaften 2024 | Anmeldung</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HEAD -->
    <div class="upper">
        <h1>Medimeisterschaften 2024</h1>
    </div> 

    <!-- MAIN -->
    <div class="main">
        <form action="index.php" method="POST">

        <!-- GENERELL INFORMATION ABOUT THE PERSON WHOS BOOKING -->
            <div class="generell formBlock">
                <h2>Zuerst, ein bisschen etwas über <span style="color: #52c393">dich!</span></h2>

                <div class="inputsGenerell">
                    <div class="input-field name">
                        <input type="text" id="name" name="nachname" required>
                        <label for="nachname">Dein Nachname:<sup>*</sup></label>
                    </div>
                    <div class="input-field prename">
                        <input type="text" id="preName" name="vorname" required>
                        <label for="vorname">Dein Vorname:<sup>*</sup></label>
                    </div>
                    <div class="input-field email">
                        <input type="email" id="email" name="email" required>
                        <label for="email">Deine Email:<sup>*</sup></label>
                    </div>
                </div>
            </div>


        <div class="downerForm">
            <!-- INFORMATIONS ABOUT THE "STUDIENGANG" AND THE SEMESTER -->
            <div class="university formBlock">
                <h2>Was <span style="color: #52c393">studierst</span> du so?</h2>

                <div class="input-field studiengang">
                    <select name="studiengang" id="studiengang" required>
                        <option value="none" disabled>--> Bitte auswählen <--</option>
                        <option value="Humanmedizin">Humanmedizin</option>
                        <option value="Zahnmedizin">Zahnmedizin</option>
                    </select>
                    <label for="studiengang">Dein Studiengang:<sup>*</sup></label>
                </div>

                <div class="input-field">
                    <input type="number" id="semester" name="semester" required>
                    <label for="semester">Dein Semester:<sup>*</sup></label>
                </div>

                <div class="input-field">
                    <input type="number" id="matrikelNumber" name="matrikelNumber" required>
                    <label for="matrikelNumber">Was ist deine Matrikel Nummer?<sup>*</sup></label>
                </div>
            </div>

        <!-- INFORMATIONS ABOUT THE MEDIS ITSELF AND ABOUT SIZES -->
            <div class="specificMedis formBlock">
            <h2>Nun etwas zu den <span style="color: #52c393">Medis!</span></h2>

                <div class="input-field">
                    <input type="number" id="countMedis" name="countMedis" required>
                    <label for="countMedis">Wie oft warst du schon bei den Medis?<sup>*</sup></label>
                </div>

                <div class="input-field">
                    <select name="trousesSize" id="trousesSize" required>
                        <option value="none">--> Bitte auswählen <--</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                    <label for="trousesSize">Fanpaket Größe Hose</label>
                </div>

                <div class="input-field">
                    <select name="shirtSize" id="shirtSize" required>
                        <option value="none">--> Bitte auswählen <--</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                    <label for="shirtSize">Fanpaket Größe T-shirt</label>
                </div>
            </div>
        </div>
            
        <button type="submit" id="submit">Anmelden!</button>
        </form>
        <p style="font-weight: thin;margin: 10px 0 50px 20px;font-size:12px;color: grey;"><sup>*</sup> Pflichtfelder</p>
    </div>

    <!-- DOWNER -->
    <footer class="downer">
        <p style="text-align:center;">For support contact: streiosc@curiegym.de | Copyright <span id="year"></span> | Oscar Streich</p>
    </footer>

    <div class="alerts" id="alerts">
        <div class="alert alert-success" id="successAlert">

            <!-- SUCCES SVG -->
            <svg id="check" width="100%" height="100%" viewBox="0 0 1867 2134" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                <g transform="matrix(4.16667,0,0,4.16667,0,0)">
                    <path d="M438.6,105.4C451.1,117.9 451.1,138.2 438.6,150.7L182.6,406.7C170.1,419.2 149.8,419.2 137.3,406.7L9.3,278.7C-3.2,266.2 -3.2,245.9 9.3,233.4C21.8,220.9 42.1,220.9 54.6,233.4L160,338.7L393.4,105.4C405.9,92.9 426.2,92.9 438.7,105.4L438.6,105.4Z" style="fill:white;fill-rule:nonzero;"/>
                </g>
            </svg>

            <!-- ERROR SVG -->
            <svg id="xmark" width="100%" height="100%" viewBox="0 0 1600 2134" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                <g transform="matrix(4.16667,0,0,4.16667,0,0)">
                    <path d="M342.6,150.6C355.1,138.1 355.1,117.8 342.6,105.3C330.1,92.8 309.8,92.8 297.3,105.3L192,210.7L86.6,105.4C74.1,92.9 53.8,92.9 41.3,105.4C28.8,117.9 28.8,138.2 41.3,150.7L146.7,256L41.4,361.4C28.9,373.9 28.9,394.2 41.4,406.7C53.9,419.2 74.2,419.2 86.7,406.7L192,301.3L297.4,406.6C309.9,419.1 330.2,419.1 342.7,406.6C355.2,394.1 355.2,373.8 342.7,361.3L237.3,256L342.6,150.6Z" style="fill:white;fill-rule:nonzero;"/>
                </g>
            </svg>

            <!-- INFO TEXT SUCCESS/ERROR -->
            <h3 id="infoLine">Du wurdest erfolgreich eingetragen!</h3>
        </div>
    </div>

    <script>
        const year = new Date().getFullYear();

        document.getElementById("year").innerHTML = year;
    </script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['nachname']);
    $preName = $conn->real_escape_string($_POST['vorname']);
    $email = $conn->real_escape_string($_POST['email']);
    $studienGang = $conn->real_escape_string($_POST['studiengang']);

    $semester = intval($_POST['semester']);
    $countMedis = intval($_POST['countMedis']);
    $matrikelNumber = intval($_POST['matrikelNumber']);

    $trousesSize = $conn->real_escape_string($_POST['trousesSize']);  // String, daher "s" verwenden
    $shirtSize = $conn->real_escape_string($_POST['shirtSize']);      // String, daher "s" verwenden

    $time = getCurrentTimestamp();  // Unix-Timestamp als Integer

    if(!empty($name) && !empty($preName) && !empty($email)){
        // 10 Spalten in der SQL-Abfrage, daher brauchen wir 10 Werte in bind_param
        $stmt = $conn->prepare("INSERT INTO master (`name`, `vorname`, `email`, `studiengang`, `semesterCnt.`, `medisCnt.`, `matrikelNr.`, `trousers`, `shirt`, `time_GMT`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Korrekte Typen: "ssssiiissi" (4 Strings, 2 Integers, 1 String, 2 Integers)
        $stmt->bind_param("ssssiiisss", $name, $preName, $email, $studienGang, $semester, $countMedis, $matrikelNumber, $trousesSize, $shirtSize, $time);

        try {
            // Dein Code zum Ausführen des Statements
            $stmt->execute();

            echo '
            <script>  
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("alerts").classList.add("success");
                    document.getElementById("alerts").classList.remove("fail");
                    document.getElementById("check").style.display = "block";
                    document.getElementById("xmark").style.display = "none";

                    setTimeout(() => {
                        document.getElementById("alerts").style.transform = "translateX(100%)";
                        console.log("Yallah");
                    }, 5000);
                });
            </script>
            ';
        } catch (mysqli_sql_exception $e) {
            // Überprüfe, ob es sich um einen Duplicate Entry Fehler handelt
            if ($e->getCode() === 1062) {
                // Handle den Duplicate Entry Fehler
                echo '

                <script>  
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById("alerts").classList.remove("success");
                        document.getElementById("alerts").classList.add("fail");
                        document.getElementById("check").style.display = "none";
                        document.getElementById("xmark").style.display = "block";
                        document.getElementById("infoLine").innerText = "Fehler: Diese E-Mail-Adresse existiert bereits.";

                        setTimeout(() => {
                            document.getElementById("alerts").style.transform = "translateX(100%)";
                            console.log("Yallah");
                        }, 5000);
                    });

                </script>
                
                ';
            } else {
                // Handle andere Fehler
                echo "Es ist ein Fehler aufgetreten: " . $e->getMessage();
            }
        }
        

        $stmt->close();
        $conn->close();
    }
}

function getCurrentTimestamp() {
    // microtime gibt die Zeit im Format "Sekunden Mikrosekunden" zurück
    $microtime = microtime(true);

    // Teile die Zeit in Sekunden und Millisekunden auf
    $milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);

    // Formatiere das Datum mit der aktuellen Zeit (ohne Millisekunden)
    $date = date("Y/m/d H:i:s", floor($microtime));

    // Füge die Millisekunden hinzu
    return $date . ":$milliseconds";
}

?>