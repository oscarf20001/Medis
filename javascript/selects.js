const form = document.getElementById('submit');
form.addEventListener('click', () => validateForm());

function validateForm() {
    // Feld-Referenzen
    var studiengang = document.getElementById("studiengang");
    var trousesOrRock = document.getElementById("selectClothes");
    var clothSize = document.getElementById("clothSize");
    var shirtSize = document.getElementById("shirtSize");
    var shoeSize = document.getElementById("shoeSize");

    // Prüfen ob die Auswahl für Studiengang getroffen wurde
    if (studiengang.value === "none") {
        alert("Bitte wählen Sie Ihren Studiengang aus.");
        return false; // Formular nicht absenden
    }

    // Prüfen ob Hose oder Rock ausgewählt wurde
    if (trousesOrRock.value === "none") {
        alert("Bitte wählen Sie zwischen Hose oder Rock aus.");
        return false;
    }

    // Prüfen ob Kleidergröße ausgewählt wurde (nur, wenn Hose oder Rock ausgewählt wurde)
    if (trousesOrRock.value !== "none" && clothSize.value === "none") {
        alert("Bitte wählen Sie eine Größe für Ihre Hose oder Rock aus.");
        return false;
    }

    // Prüfen ob T-Shirt-Größe ausgewählt wurde
    if (shirtSize.value === "none") {
        alert("Bitte wählen Sie eine T-Shirt Größe aus.");
        return false;
    }

    // Prüfen ob Schuhgröße ausgewählt wurde
    if (shoeSize.value === "none") {
        alert("Bitte wählen Sie eine Schuhgröße aus.");
        return false;
    }

    return true; // Alle Felder sind gültig, Formular wird gesendet
}