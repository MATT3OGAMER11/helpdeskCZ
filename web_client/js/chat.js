function getChat() {
    let chat = document.getElementById("div");
    let url = 'http://localhost/helpdesk_API/chat';
    const headers = new Headers();
    document.querySelector('#notifiche').innerHTML = '<ul class="dropdown-menu" id="notifiche" aria-labelledby="dropdownMenuButton1"></ul>';
    headers.append('x-ssid', readCookie('session'));
    fetch(url,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(chats => {
            chats.forEach(chat => {
                console.log(chat);
                const card = document.createElement('div');
                card.className = 'card cardd';
                const cardB = document.createElement('div');
                cardB.className = 'card-body';
                card.innerHTML = ' <h5 class="card-title">'+chat.nome+'</h5>\n';
                card.appendChild(cardB);
                card.innerHTML += ' <a href="chat.html?id='+chat.id+'" class=" btn btn-primary mb-2">Visualizza</a>';
                document.querySelector('.content').appendChild(card);
            })
            const footer = document.querySelector('footer');
            footer.className = 'text-center bg-secondary footerB';
        })

}

function sendMessage() {
    let url = 'http://localhost/helpdesk_API/messaggio/'+id;
    let data = {
        messaggio: document.getElementById('inputMess').value
    };
    const headers = new Headers();
    headers.append('Content-Type', 'application/json');
    headers.append('x-ssid', readCookie('session'));
    fetch(url, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(message => {
            if (message.string === 'done'){
                window.location.reload();
            } else{
                alert('Errore');
            }
        });
}


function getMessageById(id) {
    let url = 'http://localhost/helpdesk_API/chat/'+id;
    const headers = new Headers();
    headers.append('x-ssid', readCookie('session'));
    fetch(url,{
        method: 'GET',
        headers: headers,
    })
        .then(response => response.json())
        .then(messages => {
            messages.forEach(message => {
                console.log(message);
                const div = document.createElement('div');
                div.className = 'row';
                /*const card = document.createElement('div');
                card.className = 'card';
                const cardTitle = document.createElement('div');
                cardTitle.className = 'card-title';
                cardTitle.textContent = message.nome+' '+message.cognome;
                const cardBody = document.createElement('div');
                cardBody.className = 'card-body';
                cardBody.textContent = message.messaggio;*/
                const div2 = document.createElement('div');
                const h = document.createElement('h5');
                h.textContent = message.nome + ' ' + message.cognome;
                div2.appendChild(h);
                const p = document.createElement('p');
                p.textContent = message.messaggio;
                div2.appendChild(p);
                if (message.usr) {
                    div2.className += 'messOwner';
                } else {
                    div2.className += 'messExt';
                }
                /*card.appendChild(cardTitle);
                card.appendChild(cardBody);
                div.appendChild(card);*/

                const date = document.createElement('p');
                date.textContent = message.data;
                date.className = 'messDate';
                div2.appendChild(date);
                div.appendChild(div2);
                document.querySelector('.content').appendChild(div);
            })
            const sent = document.querySelector('.sentMess');
            sent.hidden = false;
            const footer = document.querySelector('footer');
            footer.className = 'text-center bg-secondary footer';

        })
}

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const id = urlParams.get('id');
if(id!=null){
    getMessageById(id);
} else {
    getChat();
}
