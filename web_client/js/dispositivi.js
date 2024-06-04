const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const id_aula = urlParams.get('id_aula');

async function getDeviceByIdAula(id) {
    let str = '';
    const div = document.createElement('div');
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/dispositivi/'+id,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(data => {
            //document.getElementById('loading-row').remove();
            data.forEach(async device => {
                const card = document.createElement('div');
                card.className = 'card cardd';
                const cardB = document.createElement('div');
                cardB.className = 'card-body';
                card.innerHTML = ' <h5 class="card-title">'+device.nome+'</h5>\n' +
                    '            <p class="card-text">Tipo:'+device.tipo+'</p>\n';
                card.appendChild(cardB);
                card.innerHTML += ' <a href="segnalazione.html?id='+device.id+'&tipo=dispositivo" class=" btn btn-primary mb-2">Segnala</a>';
                document.querySelector('.content').appendChild(card);
            });
        });

}


getDeviceByIdAula(id_aula);

