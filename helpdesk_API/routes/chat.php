<?php

function getChat(){
    $sid = $_SERVER['HTTP_X_SSID'];
    $usr = getUtenteBySession();
        global $db;
        $sql = 'SELECT c.id,c.nome,c.id_ruolo FROM utente as u JOIN chat as c ON u.id_ruolo = c.id_ruolo AND u.id = ?;';
        $result = $db->exec($sql,array($usr->id));
        $list = array();
        foreach ($result as $row){
            $chat = new model\Chat($row['id'],$row['nome'],$row['id_ruolo']);
            array_push($list,$chat);
        }
        return $list;
}

function getMessageById($id){
    $sid = $_SERVER['HTTP_X_SSID'];
    $usr = getUtenteBySession();
    global $db;
    if ($id === 1){
        $sql = "SELECT * FROM chat_tecnici";
        $result = $db->exec($sql);
    } else if ($id === 2){
        $sql = "SELECT * FROM chat_ata";
        $result = $db->exec($sql);
    } else {
        $sql = "SELECT c.nome as nomechat,c.id_ruolo,m.id AS id_messaggio,m.messaggio as messaggio,m.data as data, u.nome, u.cognome,u.id as id_utente FROM chat AS c JOIN messaggio as m ON m.id_chat = c.id JOIN utente as u ON u.id = m.id_utente AND c.id = ?;";
        $result = $db->exec($sql,array($id));
    }
    $list = array();
    foreach($result as $row){
        if ($usr->id == $row['id_utente']){
            $usrb = true;
        } else {
            $usrb = false;
        }
        $chat = new model\ChatxMessaggio($row['nomechat'],$row['id_ruolo'],$row['id_messaggio'],$row['messaggio'],$row['data'],$row['nome'],$row['cognome'],$usrb);
        array_push($list,$chat);

    }
    return $list;
}
function addMessagge($id_chat){
    $data = file_get_contents('php://input');
    $datas = json_decode($data);
    $usr = getUtenteBySession();
    if ($usr == 'not found'){
        return new model\NotAuthoraized("not found");
    }
    // check if user have access to the chat


    global $db;
    $sql = 'SELECT * FROM chat AS c  JOIN utente as u ON u.id_ruolo = c.id_ruolo  AND c.id = ? AND u.id = ?;';
    $result = $db->exec($sql,array($id_chat,$usr->id));
    if (empty($result)){
        return new model\NotAuthoraized("not authorized");
    }else {
        $sql = 'INSERT INTO messaggio (id_chat,id_utente,messaggio) VALUES (?,?,?)';
        $db->exec($sql,array($id_chat,$usr->id,$datas->messaggio));
        return new model\NotAuthoraized("done");
    }

}

function getChats(){
    if(isAdmin(getUtenteBySession())){
        global $db;
        $sql = 'SELECT * FROM chat';
        $result = $db->exec($sql);
        $list = array();
        foreach ($result as $row){
            $chat = new model\Chat($row['id'],$row['nome'],$row['id_ruolo']);
            array_push($list,$chat);
        }
        return $list;
    } else {
        return new model\NotAuthoraized("not authorized");

    }
}

$f3->route('GET /chat', function($f3){ echo json_encode(getChat()); });
$f3->route('GET /chats', function($f3){ echo json_encode(getChats()); });
$f3->route('GET /chat/@id', function($f3){ echo json_encode(getMessageById($f3->get('PARAMS.id'))); });
$f3->route('POST /chat', function ($f3){});
$f3->route('PUT /chat', function ($f3){});
$f3->route('DELETE /chat/@id', function ($f3){});



$f3->route('POST /messaggio/@id_chat', function ($f3){echo json_encode(addMessagge($f3->get('PARAMS.id_chat')));});