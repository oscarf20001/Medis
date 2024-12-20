// VALUE VOM SWITCH 'HOSE ODER ROCK' GÖNNEN => JE NACH DEM HANDELN
const auswahl = document.getElementById('selectClothes');
// NUR OPERIEREN, WENN CHANGE WARGENOMMEN WIRD
auswahl.addEventListener('change', () => changeCloth(auswahl))

function changeCloth(auswahl){
    //WERT AUS DEM SELECT HOLEN
    const cloth = auswahl.value;

    //DEFINIEREN DER ABZUÄNDERNDEN ELEMENTE
    const labelSizeChooser = document.getElementById('labelClothSize');

    //REAGIEREN AUF CASES / ABÄNDERN DER DISPLAYTEN INHALTE
    switch (cloth){
        case 'Hose':
            labelSizeChooser.innerText = 'Fanpaket Größe Hose';
            break;
        case 'Rock':
            labelSizeChooser.innerText = 'Fanpaket Größe Rock';
            break;
        default: 
            labelSizeChooser.innerText = 'Wähle Hose oder Rock aus';
            break;
    }

    return cloth;
}