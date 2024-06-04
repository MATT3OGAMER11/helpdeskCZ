function logout() {

    var res = document.cookie;

    var multiple = res.split(";");

    for(var i = 0; i < multiple.length; i++) {

        var key = multiple[i].split("=");

        document.cookie = key[0]+" =; expires = Thu, 01 Jan 1970 00:00:00 UTC";

    }

    window.location.href = 'index.html';

}


async function getUSerBySession() {
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/utente',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(user => {
            //document.getElementById('loading-row').remove();
            //let count = 0;
            /*const row = document.createElement('div');
            row.className = 'row';*/
                //const h4 = document.createElement('h4');
                const inputM = document.createElement('input');
                const labelM = document.createElement('label');
                labelM.textContent = 'Nome';
                labelM.htmlFor = 'nome';
                inputM.type = 'textfield';
                inputM.value = user.nome;
                inputM.id = 'nome';
                //h4.textContent = 'Nome: '+user.nome+' Cognome: '+user.cognome+' Email: '+user.email;
                //document.querySelector('#cardTitle').appendChild(h4);
                document.querySelector('#name').appendChild(labelM);
                document.querySelector('#name').appendChild(inputM);

                // cognome

            const inputC = document.createElement('input');
            const labelC = document.createElement('label');
            labelC.textContent = 'Cognome';
            labelC.htmlFor = 'cognome';
            inputC.type = 'textfield';
            inputC.value = user.cognome;
            inputC.id = 'cognome';
            document.querySelector('#surname').appendChild(labelC);
            document.querySelector('#surname').appendChild(inputC);

            // email

            const inputE = document.createElement('input');
            const labelE = document.createElement('label');
            labelE.textContent = 'Email';
            labelE.htmlFor = 'emailT';
            inputE.type = 'textfield';
            inputE.value = user.email;
            inputE.id = 'emailT';
            inputE.readOnly = true;
            document.querySelector('#email').appendChild(labelE);
            document.querySelector('#email').appendChild(inputE);

            // ruolo

            const inputR = document.createElement('input');
            const labelR = document.createElement('label');
            labelR.textContent = 'Ruolo';
            labelR.htmlFor = 'ruolo';
            inputR.type = 'textfield';
            inputR.value = 'base';
            for (let i = 0; i < roles.length; i++) {
                if (roles[i].id == user.id_ruolo) {
                    inputR.value = roles[i].nome;
                    if (roles[i].nome === 'tecnico' || roles[i].nome === 'amministratore' || roles[i].nome === 'personale ATA'){
                        const alertROW = document.getElementById('rowAlert');
                        alertROW.hidden = false;
                    }
                }
            }
            inputR.id = 'ruolo';
            inputR.readOnly = true;
            document.querySelector('#ruolo').appendChild(labelR);
            document.querySelector('#ruolo').appendChild(inputR);

            // data creazione
            const inputD = document.createElement('input');
            const labelD = document.createElement('label');
            labelD.textContent = 'Data creazione';
            labelD.htmlFor = 'data';
            inputD.type = 'datetime-local';

            inputD.value = user.data_creazione;
            inputD.id = 'data';
            inputD.readOnly = true;
            document.querySelector('#dataD').appendChild(labelD);
            document.querySelector('#dataD').appendChild(inputD);
            });
    showOwnTickets();

}
let roles = []

async function getRole() {
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/ruoli',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(user => {
            user.forEach(u => {
                let r = {
                    id: u.id,
                    nome: u.nome
                }
                roles.push(r);
            });
        });

}
getRole();
getUSerBySession();

function updateAcc(){
    let url = 'http://localhost/helpdesk_API/utenti';
    let data = {
        nome: document.getElementById('nome').value,
        cognome: document.getElementById('cognome').value,
    };
    const headers = new Headers();
    headers.append('Content-Type', 'application/json');
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'PUT',
        headers: headers,
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(data => {
            let txt = "C'è stato un errore, riprova più tardi";
            if (data.string === "done"){
                txt = 'Profilo aggiornato';
                //alert('Segnalazione inviata');
            }
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    const notification = new Notification('Profilo', {
                        body: txt
                    });
                }
            });


        })
        .catch((error) => {
            console.error('Error:', error);
        });
    location.reload();
}

function showOwnTickets(){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/segnalazione/u',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(segnalazioni => {
            let i = 0;
            let view = false;
            const table = document.getElementById('segnal');
            segnalazioni.forEach(segn => {
                view = true;
                let luogo = '';
                if (segn.id_aula != null) {
                    luogo = 'Aula '+segn.id_aula;
                } else {
                    luogo = 'Dispositivo '+segn.id_dispositivo;
                }

                let row = table.insertRow(i);
                let id = row.insertCell(0);
                let categoria = row.insertCell(1);
                let location = row.insertCell(2);
                let datacreazione = row.insertCell(3);
                let stato = row.insertCell(4);
                let tecnico = row.insertCell(5);
                let button = row.insertCell(6);
                id.innerHTML = segn.id;
                categoria.innerHTML = segn.id_categoria;
                location.innerHTML = luogo;
                datacreazione.innerHTML = segn.data_creazione;
                stato.innerHTML = segn.stato;
                tecnico.innerHTML = segn.presa_in_carico_da;
                button.innerHTML = '<a class="btn btn-secondary" href="segnalazioneView.html?id='+segn.id+'">Dettagli</a>';
                i++;
            })
            if (view){
                const div = document.getElementById('rowSel');
                div.hidden = false;
            }
        });
}