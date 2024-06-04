function showTickets(){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/segnalazione',{
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
                stato.innerHTML = '<select id="stato'+segn.id+'" name="stato"><select/>';
                tecnico.innerHTML = segn.presa_in_carico_da;
                button.innerHTML = '<a class="btn btn-secondary" href="segnalazioneView.html?id='+segn.id+'">Dettagli</a>';
                let opt1 = document.createElement('option');
                opt1.value = 'in attesa';
                opt1.text = 'In attesa';
                let opt2 = document.createElement('option');
                opt2.value = 'presa in carico';
                opt2.text = 'Presa in carico';
                let opt3 = document.createElement('option');
                opt3.value = 'risolta';
                opt3.text = 'Risolta';
                if('in attesa' == segn.stato){
                    opt1.selected = true;
                } else if('presa in carico' == segn.stato){
                    opt2.selected = true;
                }else {
                    opt3.selected = true;
                }
                document.getElementById('stato'+segn.id).appendChild(opt1);
                document.getElementById('stato'+segn.id).appendChild(opt2);
                document.getElementById('stato'+segn.id).appendChild(opt3);
                document.getElementById('stato'+segn.id).addEventListener('change', function(){
                    updateSelStatus(segn);
                });
                i++;
            })
            if (view){
                const div = document.getElementById('rowSel');
                div.hidden = false;
            }
        });
}

function updateSelStatus(segn){
    console.log('pressed');
    segn.stato = document.getElementById('stato'+segn.id).value;
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/segnalazione',{
        method: 'PUT',
        headers: headers,
        body: JSON.stringify(segn)
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            location.reload();
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    location.reload();
}

function isAdminControl(){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/admin',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(admin => {
            if (!admin){
                window.location.href = 'index.html';
            }
        });
}

function showAcc(ruoli){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/utenti',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(utenti => {
            let i = 0;
            let view = false;
            const table = document.getElementById('account');
            utenti.forEach(usr => {
                /*view = true;
                let luogo = '';
                if (usr.id_aula != null) {
                    luogo = 'Aula '+usr.id_aula;
                } else {
                    luogo = 'Dispositivo '+usr.id_dispositivo;
                }*/
                let row = table.insertRow(i);
                let id = row.insertCell(0);
                let email = row.insertCell(1);
                let nome = row.insertCell(2);
                let cognome = row.insertCell(3);
                let ruolo = row.insertCell(4);
                id.innerHTML = usr.id;
                email.innerHTML = usr.email;
                nome.innerHTML = usr.nome;
                cognome.innerHTML = usr.cognome;
                ruolo.innerHTML = '<select id="ruoloAcc'+usr.id+'" name="ruoloACC"><select/>';
                ruoli.forEach(r => {
                    let opt = document.createElement('option');
                    opt.value = r.id;
                    opt.text = r.nome;
                    if(r.id === usr.id_ruolo){
                        opt.selected = true;
                    }
                    document.getElementById('ruoloAcc'+usr.id).appendChild(opt);
                });
                document.getElementById('ruoloAcc'+usr.id).addEventListener('change', function(){
                    updateAccRole(usr);
                });

                i++;
            })
            if (view){
                const div = document.getElementById('rowSel');
                div.hidden = false;
            }
            showRuolis(ruoli);
        });
}

function updateAccRole(usr){
    console.log('pressed');
    usr.id_ruolo =document.getElementById('ruoloAcc'+usr.id).value;
    usr.id_ruolo = parseInt(usr.id_ruolo);
    console.log(usr);
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/utenti/role',{
        method: 'PUT',
        headers: headers,
        body: JSON.stringify(usr)
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    //location.reload();
}

function getRole() {
    let roles = [];
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
    return roles;
}

function showChats(ruoli){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/chats',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(chats => {
            let i = 0;
            let view = false;
            const table = document.getElementById('chat');
            chats.forEach(chat => {
                /*view = true;
                let luogo = '';
                if (chat.id_aula != null) {
                    luogo = 'Aula '+chat.id_aula;
                } else {
                    luogo = 'Dispositivo '+chat.id_dispositivo;
                }*/
                let ruolos = null;
                ruoli.forEach(r => {
                    if(r.id === chat.id_ruolo){
                        ruolos = r.nome;
                    }
                });
                let row = table.insertRow(i);
                let id = row.insertCell(0);
                let nome = row.insertCell(1);
                let ruolo = row.insertCell(2);
                id.innerHTML = chat.id;
                nome.innerHTML = chat.nome;
                ruolo.innerHTML = ruolos;

                i++;
            })
            if (view){
                const div = document.getElementById('rowSel');
                div.hidden = false;
            }
        });

}

function showRuolis(ruoli){
    const table = document.getElementById('ruoli');
    let i = 0;
    ruoli.forEach(r => {
        let row = table.insertRow(i);
        let id = row.insertCell(0);
        let nome = row.insertCell(1);
        let admin = row.insertCell(2);
        id.innerHTML = r.id;
        nome.innerHTML = r.nome;
        admin.innerHTML = r.admin;
        i++
    });
}


window.onload = function() {
    

}
showTickets();
let ruolo = getRole();
showAcc(ruolo);
showChats(ruolo);

isAdminControl();