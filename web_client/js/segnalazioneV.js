async function showSegnalazione(id) {
    let url = 'http://localhost/helpdesk_API/segnalazione/' + id;
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(segnalazione => {
            console.log(segnalazione);
            document.getElementById('title').innerHTML += segnalazione.id;
            // testo segnalazione
            const tsel = document.getElementById('tSel')
            tsel.readOnly = true;
            tsel.value = segnalazione.testo_segnalazione;
            // testo soluzione
            const tsol = document.getElementById('tSol')
            tsol.value = segnalazione.testo_soluzione;
            // stato
            const stat = document.getElementById('stat');
            const op1 = document.createElement('option');
            op1.text = 'In attesa';
            op1.value = 'in attesa';
            if(segnalazione.stato === 'in attesa'){
                op1.selected = true;
            }
            const op2 = document.createElement('option');
            op2.text = 'Presa in carico';
            op2.value = 'presa in carico';
            if(segnalazione.stato === 'presa in carico'){
                op2.selected = true;
            }
            const op3 = document.createElement('option');
            op3.text = 'Risolta';
            op3.value = 'risolta';
            if(segnalazione.stato === 'risolta'){
                op3.selected = true;
            }
            stat.appendChild(op1);
            stat.appendChild(op2);
            stat.appendChild(op3);
            // fatta da
            const nm = document.getElementById('nm');
            nm.value = segnalazione.id_utente;
            nm.readOnly = true;
            //relativa a
            let luogo = segnalazione.id_aula;
            if(segnalazione.id_dispositivo !== null){
                luogo = segnalazione.id_dispositivo;
            }
            document.getElementById('relA').value = luogo;
            document.getElementById('relA').readOnly = true;
            //Presa in carico
            // il
            document.getElementById('datapc').value = segnalazione.data_presa_in_carico;
            document.getElementById('datapc').readOnly = true;
            // da
            document.getElementById('carico').value = segnalazione.presa_in_carico_da;
            document.getElementById('carico').readOnly = true;
            //categoria
            placeCat(segnalazione.id_categoria);
            //effettuata da
            const ef = document.getElementById('data');
            ef.value = segnalazione.data_creazione;
            ef.readOnly = true;

            //bottone tecnici
            const btnP = document.createElement('button');
            btnP.onclick = function() {
                pickSegnalazione(segnalazione);
            };
            btnP.textContent = 'Prendi in carico';
            btnP.className = 'btn btn-primary';
            document.getElementById('buttonPick').appendChild(btnP)
            const btnU = document.createElement('button');
            btnU.onclick = function() {
                updateSegnalazione(segnalazione);
            };
            btnU.textContent = 'Salva';
            btnU.className = 'btn btn-success';
            document.getElementById('buttonUpdate').appendChild(btnU);

        });
}

async function placeCat(catid) {
    let url = 'http://localhost/helpdesk_API/categoria';
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(categorie => {
            console.log(categorie);
            const sel = document.getElementById('cat');
            sel.disable = true;
            categorie.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.id;
                option.text = categoria.nome;
                if (catid===categoria.id){
                    option.selected = true;
                }
                document.querySelector('#cat').appendChild(option);
            });
        });
}





function updateSegnalazione(sel){
    sel.testo_soluzione = document.getElementById('tSol').value;
    sel.stato = document.getElementById('stat').value;

    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('http://localhost/helpdesk_API/segnalazione', {
        method: 'PUT',
        headers: headers,
        body: JSON.stringify(sel),
    })
        .then(response => {
            if (response.ok) {
                alert('Segnalazione aggiornata');
                //location.reload();
            } else {
                alert('Errore');
            }
        });
}

function pickSegnalazione(sel){
    let url = 'http://localhost/helpdesk_API/segnalazione/pick/' + sel.id;
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json(

        ));
    location.reload();
}

window.onload = function() {
    
}
const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id = urlParams.get('id');
    if (id != null) {
        showSegnalazione(id);
    }

