let alert = null;
let piani = [];
let aule = [];
let dispositivi = [];
let categorie = [];
populeteArray();
function populeteArray(){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/piani',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(p => {
            piani.push(p);
        });
    fetch('../helpdesk_API/aule',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(a => {
            aule.push(a);
        });
    fetch('../helpdesk_API/dispositivi',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(d => {
            dispositivi.push(d);
        });
    fetch('../helpdesk_API/categoria',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(c => {
            categorie.push(c);
        });
}
async function showAlert() {
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/alert',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(alert => {




            let i = 0;
            let view = false;
            const table = document.getElementById('alert');
            alert.forEach(al => {
                console.log(al);
                //name related to the id
                let cat = '';
                let disp = '';
                let aulaN = '';
                let pianoN = '';

                console.log('cat:'+cat);
                let row = table.insertRow(i);
                let id = row.insertCell(0);
                let categoria = row.insertCell(1);
                let dispositivo = row.insertCell(2);
                let aula = row.insertCell(3);
                let piano = row.insertCell(4);
                let data = row.insertCell(5);
                let mod = row.insertCell(6);
                let del = row.insertCell(7);
                id.innerHTML = al.id;
                categoria.innerHTML = al.id_categoria;
                dispositivo.innerHTML = al.id_dispositivo;
                aula.innerHTML = al.id_aula;
                piano.innerHTML = al.id_piano;
                data.innerHTML = al.data_creazione;
                mod.innerHTML = "<a class='btn btn-secondary' href='alert.html?id="+al.id+"'>Modifica</a>";
                del.innerHTML = "<button class='btn btn-secondary' onclick='delAlert("+al.id+")'>Elimina</button>";
            });
        });
}

function delAlert(id) {
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/alert/'+id,{
        method: 'DELETE',
        headers: headers,
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.string === 'deleted'){
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        const notification = new Notification('Alert', {
                            body: 'Alert eliminato con successo'
                        });
                    }
                });
            } else {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        const notification = new Notification('Alert', {
                            body: 'Errore durante l\'eliminazione dell\'alert'
                        });
                    }
                });
            }
            location.reload();
        });
}

function addAlert(){
    console.log('addAlert');
    let url = 'http://localhost/helpdesk_API/alert';
    let id_aula = document.querySelector('#Aula').value;
    let id_dispositivo = document.querySelector('#Dispositivo').value;
    let id_categoria = document.querySelector('#Categoria').value;
    let id_piano = document.querySelector('#Piano').value;
    if(id_aula === ''){
        id_aula = null;
    }
    if(id_dispositivo === ''){
        id_dispositivo = null;
    }

    if(id_categoria === ''){
        id_categoria = null;
    }
    if(id_piano === '' && id_categoria == null){
        return;
    } else if(id_piano === ''){
        id_piano = null;
    }
    let data = {
        id_categoria: id_categoria,
        id_dispositivo: id_dispositivo,
        id_aula: id_aula,
        id_piano: id_piano,
    };
    console.log(data);
    const headers = new Headers();
    headers.append('Content-Type', 'application/json');
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify(data)
    })
        .then(response => { response.json()
        .then(data => {
            let txt = "C'è stato un errore, riprova più tardi";
            if (data.string === "done"){
                txt = 'Alert aggiunto';
            }
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    const notification = new Notification('Alert', {
                        body: txt
                    });
                }
            });
            window.location.href = 'auth.php';
        });
        });
}

async function modifyAlert(){
    console.log('modifyAlert');
    let url = 'http://localhost/helpdesk_API/alert';
    let id = alert.id;
    let id_aula = document.querySelector('#AulaM').value;
    let id_dispositivo = document.querySelector('#DispositivoM').value;
    let id_categoria = document.querySelector('#CategoriaM').value;
    let id_piano = document.querySelector('#PianoM').value;
    if(id_aula === ''){
        id_aula = null;
    }
    if(id_dispositivo === ''){
        id_dispositivo = null;
    }

    if(id_categoria === ''){
        id_categoria = null;
    }
    if(id_piano === '' && id_categoria == null){
        return;
    } else if(id_piano === ''){
        id_piano = null;
    }
    let data = {
        id: id,
        id_categoria: id_categoria,
        id_dispositivo: id_dispositivo,
        id_aula: id_aula,
        id_piano: id_piano,
    };
    console.log(data);
    const headers = new Headers();
    headers.append('Content-Type', 'application/json');
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'PUT',
        headers: headers,
        body: JSON.stringify(data)
    })
        .then(response => { response.json()
            .then(data => {
                let txt = "C'è stato un errore, riprova più tardi";
                if (data.string === "done"){
                    txt = 'Alert modificato';
                }
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        const notification = new Notification('Alert', {
                            body: txt
                        });
                    }
                });
                //window.location.href = 'auth.php';
            });
        });

}

function getPiani(){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/piani',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(piani => {
            piani.forEach(p => {
                let option = document.createElement('option');
                option.value = p.id;
                option.innerHTML = p.nome;
                document.querySelector('#Piano').appendChild(option);
            });
        });
}

async function getPianiM(){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/piani',{
        method: 'GET',
        headers: headers,
    })
        .then(async response => response.json())
        .then(piani => {
            piani.forEach(p => {
                let option = document.createElement('option');
                option.value = p.id;
                option.innerHTML = p.nome;
                if (p.id === alert.id_piano){
                    console.log('selected');
                    option.selected = true;
                }
                document.querySelector('#PianoM').appendChild(option);
            });
            getAulaByPianoM();
        });

}

function getAulaByPiano(){
    document.querySelector('#Aula').innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = '';
    document.querySelector('#Aula').append(opt);
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/aule/piano/'+document.querySelector('#Piano').value,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(aule => {
            aule.forEach(a => {
                let option = document.createElement('option');
                option.value = a.id;
                option.innerHTML = a.nome;
                document.querySelector('#Aula').appendChild(option);
            });
        });
}
async function getAulaByPianoM(){
    document.querySelector('#AulaM').innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = '';
    document.querySelector('#AulaM').append(opt);
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    console.log(document.querySelector('#PianoM').value);
    fetch('../helpdesk_API/aule/piano/'+document.querySelector('#PianoM').value,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(aule => {
            console.log(aule);
            aule.forEach(a => {
                let option = document.createElement('option');
                option.value = a.id;
                option.innerHTML = a.nome;
                if (a.id === alert.id_aula){
                    option.selected = true;
                }
                document.querySelector('#AulaM').appendChild(option);
            });
            getDeviceByAulaM();
        });

}

function getDeviceByAula(){
    document.querySelector('#Dispositivo').innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = '';
    document.querySelector('#Dispositivo').append(opt);
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/dispositivi/'+document.querySelector('#Aula').value,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(dispositivi => {
            dispositivi.forEach(d => {
                let option = document.createElement('option');
                option.value = d.id;
                option.innerHTML = d.nome;
                document.querySelector('#Dispositivo').appendChild(option);
            });
        });

}

function getDeviceByAulaM(){
    document.querySelector('#DispositivoM').innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = '';
    document.querySelector('#DispositivoM').append(opt);
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/dispositivi/'+document.querySelector('#AulaM').value,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(dispositivi => {
            dispositivi.forEach(d => {
                let option = document.createElement('option');
                option.value = d.id;
                option.innerHTML = d.nome;
                if(d.id === alert.id_dispositivo){
                    option.selected = true;
                }
                document.querySelector('#DispositivoM').appendChild(option);
            });
        });

}

function getCat(){
    document.querySelector('#Categoria').innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = '';
    document.querySelector('#Categoria').append(opt);
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/categoria/ruolo',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(categorie => {
            categorie.forEach(c => {
                let option = document.createElement('option');
                option.value = c.id;
                option.innerHTML = c.nome;
                document.querySelector('#Categoria').appendChild(option);
            });
        });

}
async function getCatM(){
    document.querySelector('#CategoriaM').innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.innerHTML = '';
    document.querySelector('#CategoriaM').append(opt);
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/categoria/ruolo',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(categorie => {
            categorie.forEach(c => {
                let option = document.createElement('option');
                option.value = c.id;
                option.innerHTML = c.nome;
                if (c.id === alert.id_categoria){
                    option.selected = true;
                }
                document.querySelector('#CategoriaM').appendChild(option);
            });
        });

}

async function showAlertById(id){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/alert/'+id,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(async alertG => {
            console.log(alertG);
            alert = alertG;
            if(alertG.id_piano !== null){
                await getPianiM();
                await getCatM();
            } else if(alertG.id_categoria !== null){
                await getPianiM();
                await getCatM();
            }

        });

}



function selectAlertView(){
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id = urlParams.get('id');
    if (id !== null){
        const div = document.getElementById('alertMOD');
        div.hidden = false;
        showAlertById(id);
    } else {
        const div = document.getElementById('alertADD');
        div.hidden = false;
        getPiani();
        getCat();
    }
}
