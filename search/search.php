<?php
include '../checks.php';
require __DIR__ . '/../vendor/autoload.php';
require '../login.php';
require '../db_connection.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Dotenv laden
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bezahlung</title>
    <link rel="stylesheet" href="../css/search.css">
</head>
<body>
    <div class="search_for_identification_mail">
        <div class="input-field">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" autocomplete="off">
        </div>
        <div class="suggestions" id="suggestions"></div>
    </div>

    <div id="data-container" class="data-display"></div>

    <input type="button" value="Mail an diese Person senden" id="final_send">

    <script>
        const emailInput = document.getElementById('email');
        const suggestionsDiv = document.getElementById('suggestions');
        const sentMailBtn = document.getElementById('final_send');
        sentMailBtn.style.display = 'none';

        let id_Mail = 0;
        let mail_Mail = null;
        let code_Mail = null;
        let vorname_Mail = null;
        let nachname_Mail = null;

        emailInput.addEventListener('input', () => {
            const query = emailInput.value;

            // Nur Suche auslösen, wenn Eingabe nicht leer ist
            if (query.trim() !== '') {
                fetch(`search_suggestions.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Vorschläge anzeigen
                        suggestionsDiv.innerHTML = ''; // Alte Vorschläge löschen
                        data.forEach(email => {
                            const suggestion = document.createElement('div');
                            suggestion.textContent = email;
                            suggestion.addEventListener('click', () => {
                                emailInput.value = email; // Ausgewählten Vorschlag ins Eingabefeld einfügen
                                suggestionsDiv.innerHTML = ''; // Vorschläge ausblenden

                                //GET DATA FOR SELECTED EMAIL
                                fetch(`getDataSearchQuery.php?uniEmail=${encodeURIComponent(email)}`)
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    sentMailBtn.style.display = 'block';
                                    if(data.length > 0){

                                        //Get Container for data and make this container empty (maybe some previous querys happend)
                                        const container = document.getElementById('data-container');
                                        container.innerHTML = '';

                                        //Make data visible
                                        data.forEach(item => {
                                            const row = document.createElement('div');
                                            row.classList.add('data-row');
                                            row.innerHTML = `
                                                <p><strong>ID:</strong> ${item.id}</p>
                                                <p><strong>Vorname:</strong> ${item.vorname}</p>
                                                <p><strong>Name:</strong> ${item.name}</p>
                                                <p><strong>E-Mail:</strong> ${item.uniEmail}</p>
                                                <p><strong>Bezahlt:</strong> ${item.paid === 0 ? 'Ja' : 'Nein'}</p>
                                                <p><strong>Gesendet:</strong> ${item.sent === 0 ? 'Ja' : 'Nein'}</p>
                                                <p><strong>Code:</strong> ${item.code}</p>
                                            `;
                                            container.appendChild(row);

                                            id_Mail = item.id;
                                            mail_Mail = item.uniEmail;
                                            code_Mail = item.code;
                                            vorname_Mail = item.vorname;
                                            nachname_Mail = item.name;
                                        });
                                        return data;
                                    }else{
                                        alert('Keine Daten gefunden')
                                    }
                                })
                                .catch(error => {
                                    console.error('Fehler:', error);
                                    alert('Daten konnten nicht abgerufen werden!')
                                });
                            });
                            suggestionsDiv.appendChild(suggestion);
                        });
                    })
                    .catch(error => console.error('Fehler:', error));
            } else {
                suggestionsDiv.innerHTML = ''; // Vorschläge löschen, wenn Eingabe leer ist
            }
        });

        //SENDEN DER FINALEN MAIL
        sentMailBtn.addEventListener('click', () => {
            if (id_Mail) {
                sentMailBtn.setAttribute('disabled','true');
                sentMailBtn.value = 'Senden... - warte auf Rückmeldung vom Server';
                // Sende die Mail mit der ausgewählten ID
                fetch(`send_final_mail_with_final_data.php?id=${encodeURIComponent(id_Mail)}&vorname=${encodeURIComponent(vorname_Mail)}&nachname=${encodeURIComponent(nachname_Mail)}&code=${encodeURIComponent(code_Mail)}&mail=${encodeURIComponent(mail_Mail)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Mail erfolgreich gesendet!');
                        clear();
                        fetch('log_email_sent.php', {
                            method: 'POST',
                            body: JSON.stringify({ success: true, id: id_Mail, vorname: vorname_Mail, nachname: nachname_Mail, code: code_Mail, mail: mail_Mail }),
                            headers: { 'Content-Type': 'application/json' }
                        });
                    }else if(!data.success){
                        alert('Fehler beim senden der Mail. Bitte an Oscar wenden. ID: ' + id_Mail);
                    }else{
                        alert('Ein unerwarteter Fehler ist aufgetreten. Bitte an Oscar wenden. ID: ' + id_Mail);
                    }
                })
                .catch(error => {
                    console.error('Fehler:', error);
                    alert('Ein unerwarteter Fehler ist aufgetreten. Bitte an Oscar wenden. ID: ' + id_Mail);
                });
            } else {
                alert('Keine ID ausgewählt!');
            }
        });

        function clear(){
            const container = document.getElementById('data-container');
            container.innerHTML = '';
            suggestionsDiv.innerHTML = ''; // Alte Vorschläge löschen
            emailInput.value = '';
            sentMailBtn.style.display = 'none';
            sentMailBtn.removeAttribute('disabled');
            sentMailBtn.value = 'Mail an diese Person senden';
        }
    </script>
</body>
</html>