const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const id = urlParams.get('id');
const tipo = urlParams.get('tipo');


// Usage:
async function getDeviceById(id) {
    let str = '';
    const div = document.createElement('div');
    let url = '';
    if (tipo === 'dispositivo') {
        url = '../helpdesk_API/dispositivo/'+id;
    } else if (tipo === 'aula'){
        url = '../helpdesk_API/aule/'+id;
    }
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch(url,{
        method: 'GET',
            headers: headers,
    })
        .then(response => response.json())
        .then(device => {
            console.log(device);
            //document.getElementById('loading-row').remove();
                const card = document.querySelector('#card');
                const h4 = document.querySelector('h4');
                h4.textContent = tipo+': '+device.nome;
                h4.className = 'card-title';
                const cardF = document.createElement('div');
                cardF.className = 'card-footer';
                cardF.innerHTML = ' <button class=" btn btn-primary mb-2" onclick="addSegnalazione()">Segnala</button>';
                card.appendChild(cardF);
                //document.querySelector('.content').appendChild(card);
            });

}

async function getCategories(){
    fetch('../helpdesk_API/categoria')
        .then(response => response.json())
        .then(categoria => {
            //const sel = document.getElementById('#selCat');
            console.log(categoria);
            categoria.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id;
                option.text = cat.nome;
                console.log(option);
                document.querySelector('#selCat').appendChild(option);
            });
            //document.getElementById('loading-row').remove();

        });
}

getDeviceById(id);
getCategories();

async function addSegnalazione(){
    let url = 'http://localhost/helpdesk_API/segnalazione';
    let data = {};
    if (tipo === 'dispositivo') {
         data = {
            testo_segnalazione: document.querySelector('#tSel').value,
            id_utente: 5,
            id_categoria: document.querySelector('#selCat').value,
            id_aula: null,
            id_dispositivo: id,
        };
    } else {
        data = {
            testo_segnalazione: document.querySelector('#tSel').value,
            id_categoria: document.querySelector('#selCat').value,
            id_aula: id,
            id_dispositivo: null,
        };
    }
    console.log(data);
    const headers = new Headers();
    headers.append('Content-Type', 'application/json');
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(data => {
            let txt = "C'è stato un errore, riprova più tardi";
            if (data.string === "done"){
                txt = 'Segnalazione inviata';
                //alert('Segnalazione inviata');
            }
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    const notification = new Notification('Segnalazione', {
                        body: txt
                    });
                }
            });
            window.location.href = 'index.html';

        })
        .catch((error) => {
            console.error('Error:', error);
        });
}
