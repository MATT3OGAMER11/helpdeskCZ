async function getNotificationById(id) {
    let url = 'http://localhost/helpdesk_API/notifica';
    const headers = new Headers();
    document.querySelector('#notifiche').innerHTML = '<ul class="dropdown-menu" id="notifiche" aria-labelledby="dropdownMenuButton1"></ul>';
    headers.append('x-ssid', readCookie('session'));
    fetch(url,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(notification => {
            notification.forEach(not =>{
                let option = document.createElement('li');
                /*let input = document.createElement('input');
                input.value = not.id;
                input.readOnly = true;
                input.hidden = true;*/
                let btn = document.createElement('button');
                if (!not.notificata){
                    btn.innerHTML = '<button class="btn btn-light" onclick="notificaLetta('+not.id+')"><b>'+not.messaggio_notifica+'</b></button>';

                } else {
                    btn.innerHTML = '<button class="btn btn-light" onclick="notificaLetta('+not.id+')">'+not.messaggio_notifica+'</button>';

                }
                option.appendChild(btn);
                if (not.id_segnalazione != null){
                    let a = document.createElement('a');
                    a.href = 'segnalazioneView.html?id='+not.id_segnalazione;
                    a.className = 'btn btn-primary';
                    a.textContent = 'Vai alla segnalazione';
                    option.appendChild(a);
                }

                document.querySelector('#notifiche').appendChild(option);
            })
        });
}
getNotificationById();

function notificaLetta(notifica){
    console.log(notifica);
    let url = 'http://localhost/helpdesk_API/notifica/'+notifica;
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    let data = {
        notificata: true
    }
    fetch(url,{
        method: 'PUT',
        headers: headers,
    })
        .then(response => response.json())
        .then(notification => {
            if (notification){
                getNotificationById();
            }
        });
}

function isAdmin(){
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch('../helpdesk_API/admin',{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(admin => {
            if (admin){
               // document.getElementById('admin').removeAttribute('hidden');
            }
        });
}

isAdmin();