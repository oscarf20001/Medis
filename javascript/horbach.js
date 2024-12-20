// HORBACH CHECKBOX GÖNNEN
let checkbox = document.getElementById('Horbach');
// STATE FÜR ADDED OR NOT ADDED
let added = false;
// AUF KLICK HÖLEN, WENN HORBACH GEKLICKT WIRD
checkbox.addEventListener('click', () => addInputFieldsHorbach(checkbox, added));

// FUNKTION FÜGT ZWEI INPUT FELDER IM HTML HINZU, DIE AUSGEFÜLLT WERDEN MÜSSEN, WENN MAN 10€ SPAREN MÖCHTE
function addInputFieldsHorbach(checkbox, state){
    if(!state){
        // ELEMENTE WERDEN HINZUGEFÜGT => STATE = TRUE
        state = true;

        // DEFINITION DER NEUEN ELEMENTE
        const outerDiv = document.createElement('div');
        outerDiv.setAttribute('id', 'outerDivNewInputs');
        outerDiv.setAttribute('class', 'containerNewInputs');

            const horbachEmailDiv = document.createElement('div');
            horbachEmailDiv.setAttribute('id', 'horbachEmailDiv');
            horbachEmailDiv.setAttribute('class', 'input-field');

                const horbachInputPrivatEmail = document.createElement('input');
                horbachInputPrivatEmail.setAttribute('type', 'email')
                horbachInputPrivatEmail.setAttribute('id', 'inputPrivatEmailHorbach');
                horbachInputPrivatEmail.setAttribute('name', 'inputPrivatEmailHorbach');
                horbachInputPrivatEmail.setAttribute('required', '');

                const horbachLabelPrivatEmail = document.createElement('label');
                horbachLabelPrivatEmail.setAttribute('for', 'inputPrivatEmailHorbach');
                horbachLabelPrivatEmail.textContent = 'Deine private E-Mail *';

            horbachEmailDiv.appendChild(horbachInputPrivatEmail);
            horbachEmailDiv.appendChild(horbachLabelPrivatEmail);

            const horbachTelDiv = document.createElement('div');
            horbachTelDiv.setAttribute('id', 'horbachTelDiv');
            horbachTelDiv.setAttribute('class', 'input-field');

                const horbachInputPrivatTelNumber = document.createElement('input');
                horbachInputPrivatTelNumber.setAttribute('type', 'tel');
                horbachInputPrivatTelNumber.setAttribute('id', 'inputPrivatTelNumberHorbach');
                horbachInputPrivatTelNumber.setAttribute('name', 'inputPrivatTelNumberHorbach');
                horbachInputPrivatTelNumber.setAttribute('required', '');

                const horbachLabelPrivatTelNumber = document.createElement('label');
                horbachLabelPrivatTelNumber.setAttribute('for', 'inputPrivatTelNumberHorbach');
                horbachLabelPrivatTelNumber.textContent = 'Deine private Telefonnummer *';

            horbachTelDiv.appendChild(horbachInputPrivatTelNumber);
            horbachTelDiv.appendChild(horbachLabelPrivatTelNumber);

            // HINZUFÜGEN DER NEUEN ELEMTEN UNTER CHECKBOX
        outerDiv.appendChild(horbachEmailDiv);
        outerDiv.appendChild(horbachTelDiv);
        document.getElementById('outerCheckbox').parentNode.appendChild(outerDiv);

        return added = state;
    }

    // LÖSCHEN DER ELEMENTE
    let div = document.getElementById('outerDivNewInputs');
    div.remove();

    // SETZEN DES STATES WIEDER AUF FALSE
    state = false;
    return added = state;
}