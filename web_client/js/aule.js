
/*async function getDeviceByIdAula(id) {
    let str = '';
    const div = document.createElement('div');
    fetch('../helpdesk_API/dispositivi/'+id)
        .then(response => response.json())
        .then(data => {
            //document.getElementById('loading-row').remove();
            data.forEach(async device => {
                console.log(device.nome);
                str += device.nome;
                //console.log(device);
                /!* const h = document.createElement('h1');
                 h.textContent = JSON.stringify(device);//'id:'+device.id+' nome:'+device.nome+' tipo:'+device.tipo+' id_aula: '+device.id_aula;
                 //div.appendChild(h);
                 document.querySelector('div').appendChild(h);*!/
            });
        });

}*/


async function getPiani() {
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/piani',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(data => {
            //document.getElementById('loading-row').remove();
            //let count = 0;
            /*const row = document.createElement('div');
            row.className = 'row';*/
            data.forEach(async piano => {
                let myObject = { nome: piano.nome, id: piano.id };
                piani.push(myObject);
            });
        });
}
async function getAule() {
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/aule',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(data => {
            //document.getElementById('loading-row').remove();
            //let count = 0;
            /*const row = document.createElement('div');
            row.className = 'row';*/
            data.forEach(async aula => {
                /*if (count === 4){
                    console.log('alfa');
                    row.innerHTML = '';
                }*/
                //console.log(aula);
                const card = document.createElement('div');
                card.className = 'card cardd';
                const cardB = document.createElement('div');
                cardB.className = 'card-body';
                let piano = '';
                for (let i = 0; i < piani.length; i++) {
                    if (piani[i].id === aula.id_piano){
                        piano = piani[i].nome;
                    }
                }
                let aulaObj = { numero: aula.numero, nome: aula.nome, id: aula.id, piano: piano, tipo: aula.tipo };
                aule.push(aulaObj);
                if (aula.numero != null && aula.nome != null){
                    card.innerHTML = ' <h5 class="card-title">'+aula.nome+'</h5>\n' +
                        '            <p class="card-text">Numero:'+aula.numero+'</p>\n' +
                        '            <p class="card-text">Piano: '+piano+'</p>\n' +
                        '            <p class="card-text">Tipo: '+aula.tipo+'</p>'
                        //'Numero:'+aula.numero+' Nome:'+aula.nome;//'id:'+device.id+' nome:'+device.nome+' tipo:'+device.tipo+' id_aula: '+device.id_aula;
                }else if(aula.numero != null){
                    card.innerHTML = ' <h5 class="card-title"></h5>\n' +
                        '            <p class="card-text">Numero:'+aula.numero+'</p>\n' +
                        '            <p class="card-text">Piano: '+piano+'</p>\n' +
                        '            <p class="card-text">Tipo: '+aula.tipo+'</p>'
                }else if(aula.nome != null){
                    card.innerHTML = ' <h5 class="card-title">'+aula.nome+'</h5>\n' +
                        '            <p class="card-text">Numero:</p>\n' +
                        '            <p class="card-text">Piano: '+piano+'</p>\n' +
                        '            <p class="card-text">Tipo: '+aula.tipo+'</p>'
                }

                /*await fetch('../helpdesk_API/dispositivi/'+aula.id)
                    .then(response => response.json())
                    .then(data => {
                        //document.getElementById('loading-row').remove();
                        data.forEach( device => {
                            const h4 = document.createElement('h4');
                            h4.textContent = device.nome;
                            //console.log(device);
                            /!* const cardB = document.createElement('h1');
                             cardB.textContent = JSON.stringify(device);//'id:'+device.id+' nome:'+device.nome+' tipo:'+device.tipo+' id_aula: '+device.id_aula;
                             //div.appendChild(cardB);
                             document.querySelector('div').appendChild(cardB);*!/
                            div.appendChild(h4);
                            div.hidden = true;
                        });
                    });*/
                /*console.log('a');


                let devices = await getDeviceByIdAula(aula.id);
                div.textContent = devices;
                document.querySelector('.content').appendChild(div);*/
                card.appendChild(cardB);
                card.innerHTML += ' <a href="segnalazione.html?id='+aula.id+'&tipo=aula" class=" btn btn-primary mb-2">Segnala</a>\n' +
                    '        <a href="aula.html?id_aula='+aula.id+'" class=" btn btn-primary mb-2">Dispositivi</a>'
                //console.log(card);
                document.querySelector('.content').appendChild(card);
                /*console.log(count);
                if (count === 4){
                    console.log(row);
                    document.querySelector('.content').appendChild(row);
                    count = -1;
                }
                count++;*/
                    });
            insertCardAula(aule);

                //div.appendChild(h);
            });
}

function insertCardAula(auleParam){
    document.querySelector('.content').innerHTML = '';
    auleParam.forEach(aula =>{
        const card = document.createElement('div');
        card.className = 'card cardd';
        const cardB = document.createElement('div');
        cardB.className = 'card-body';
        if (aula.numero != null && aula.nome != null){
            card.innerHTML = ' <h5 class="card-title">'+aula.nome+'</h5>\n' +
                '            <p class="card-text">Numero:'+aula.numero+'</p>\n' +
                '            <p class="card-text">Piano: '+aula.piano+'</p>\n' +
                '            <p class="card-text">Tipo: '+aula.tipo+'</p>'
            //'Numero:'+aula.numero+' Nome:'+aula.nome;//'id:'+device.id+' nome:'+device.nome+' tipo:'+device.tipo+' id_aula: '+device.id_aula;
        }else if(aula.numero != null){
            card.innerHTML = ' <h5 class="card-title"></h5>\n' +
                '            <p class="card-text">Numero:'+aula.numero+'</p>\n' +
                '            <p class="card-text">Piano: '+aula.piano+'</p>\n' +
                '            <p class="card-text">Tipo: '+aula.tipo+'</p>'
        }else if(aula.nome != null){
            card.innerHTML = ' <h5 class="card-title">'+aula.nome+'</h5>\n' +
                '            <p class="card-text">Numero:</p>\n' +
                '            <p class="card-text">Piano: '+aula.piano+'</p>\n' +
                '            <p class="card-text">Tipo: '+aula.tipo+'</p>'
        }
        card.appendChild(cardB);
        card.innerHTML += ' <a href="segnalazione.html?id='+aula.id+'&tipo=aula" class=" btn btn-primary mb-2">Segnala</a>\n' +
            '        <a href="aula.html?id_aula='+aula.id+'" class=" btn btn-primary mb-2">Dispositivi</a>'
        //console.log(card);
        document.querySelector('.content').appendChild(card);

    });
}
let piani = [];
let aule = [];
getPiani();
getAule();
//getDeviceByIdAula(1);
/*
let url = 'http://localhost/helpdesk_API/dispositivi';

let controller = new

async function createDevice() {
    let data = {
        nome: 'Device Name',
        tipo: 'Device Type',
        id_aula: '1'
    };

fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
})
    .then(response => response.json())
    .then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });

}*/
