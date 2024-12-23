// DIESE DATEI WIRD NUR AUSGEFÜHRT, WENN IN INDEX.PHP DIE IF ABFRAGE FÜR DEN STATUS UNGLEICH 1 IST

// CREATE BANNER
const banner = document.createElement("div");
banner.setAttribute('class', 'banner');
const bannerContent = document.createTextNode('Die Anmelung ist aktuell geschlossen!');
banner.appendChild(bannerContent);

const firstDiv = document.getElementById('first');
document.body.insertBefore(banner, firstDiv);

// ### DEFINE VARIABLES
let ids = ['name','preName','email','studiengang','semester','matrikelNumber','countMedis','selectClothes','clothSize','shirtSize','shoeSize','submit']

// Horbach
let checkHorbach = document.getElementById('Horbach');
checkHorbach.addEventListener('change', () => {
    // Firstly check, if the variables are needed. When not, return, because if we go further, there would be an error not finding the id, because its not existing
    if(checkHorbach.checked === true){
        ids.push('inputPrivatEmailHorbach','inputPrivatTelNumberHorbach');
        disable();
    }
});

disable();

function disable(){
    for(let i = 0;i < ids.length;i++){
        let element = document.getElementById(ids[i]);
        element.setAttribute('disabled','');
        element.classList.add('disabled');
    }
}