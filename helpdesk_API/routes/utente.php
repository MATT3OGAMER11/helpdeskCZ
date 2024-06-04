<?php

function getUtenteBySession(){
    global $db;
    $sid = $_SERVER['HTTP_X_SSID'];
    $sql = 'SELECT u.id, u.email, u.nome, u.cognome, u.data_creazione, u.id_ruolo  FROM sessione as s JOIN utente as u on u.id = s.id_utente  AND S.id_sessione = ?';
    $result= $db->exec($sql,array($sid));
    foreach($result as $row){
        $utente = new model\Utente($row['id'],$row['email'],$row['nome'],$row['cognome'],$row['data_creazione'],$row['id_ruolo']);
        return $utente;
    }
    return 'not found';
}

function updateUser(){
    global $db;
    $idUsr = getUtenteBySession();
    if ($idUsr == 'not found'){
        return new model\NotAuthoraized("not found");
    }
    $data = file_get_contents('php://input');
    $datas = json_decode($data);
    $sql = 'UPDATE utente SET nome = ?, cognome = ? WHERE id = ?';
    $db->exec($sql,array($datas->nome,$datas->cognome,$idUsr->id));
    return new model\NotAuthoraized("done");
}

function getUtenteByEmail($email){
    global $db;
    $sid = $_SERVER['HTTP_X_SSID'];
    $sql = 'SELECT * from utente WHERE email = ?';
    $result = $db->exec($sql,array($email));
    $sql = 'INSERT INTO sessione(id_sessione,id_utente) VALUES (?,?)';
    $db->exec($sql,array($sid,$result[0]['id']));
    return $result[0];
}

function getUtenteById($id){
    global $db;
    //$sid = $_SERVER['HTTP_X_SSID'];
    $sql = 'SELECT * from utente WHERE id = ?';
    $result = $db->exec($sql,array($id));
    return $result;
}

function logout(){
    global $db;
    $sid = $_SERVER['HTTP_X_SSID'];
    $sql = 'DELETE FROM sessione WHERE id_sessione = ?';
    $db->exec($sql,array($sid));
    return new model\NotAuthoraized("done");
}

function isAdmin($utente){
    global $db;
    $sql = 'SELECT * FROM ruolo WHERE id = ? and admin = true';
    $result = $db->exec($sql,array($utente->id_ruolo));
    if(count($result)>0){
        return true;
    } else {
        return false;
    }
}

function getUtenti(){
    global $db;
    $sid = $_SERVER['HTTP_X_SSID'];
    if(isAdmin(getUtenteBySession())){
        $sql = 'SELECT * FROM utente';
        $result = $db->exec($sql);
        $list = array();
        foreach ($result as $row){
            $utente = new model\Utente($row['id'],$row['email'],$row['nome'],$row['cognome'],$row['data_creazione'],$row['id_ruolo']);
            array_push($list,$utente);
        }
        return $list;
    } else {
        return new model\NotAuthoraized("not authorized");
    }
}

function updateRole(){
    global $db;
    if(!isAdmin(getUtenteBySession())){
        return new model\NotAuthoraized("not authorized");
    }
    $data = file_get_contents('php://input');
    $datas = json_decode($data);
    $sql = 'UPDATE utente SET id_ruolo = ? WHERE id = ?';
    $db->exec($sql,array($datas->id_ruolo,$datas->id));
    return new model\NotAuthoraized("done");
}

$f3->route('GET /utenti', function($f3){echo json_encode(getUtenti());});
$f3->route('GET /admin', function($f3){echo json_encode(isAdmin(getUtenteBySession()));});
$f3->route('POST /utenti', function ($f3){});
$f3->route('PUT /utenti', function ($f3){echo json_encode(updateUser());});
$f3->route('PUT /utenti/role', function ($f3){echo json_encode(updateRole());});
$f3->route('DELETE /utenti/@id', function ($f3){});
$f3->route('GET /utente', function($f3){ echo json_encode(getUtenteBySession()); });
$f3->route('GET /utente/@email', function($f3){ echo json_encode(getUtenteByEmail($f3->get('PARAMS.email'))); });
$f3->route('GET /utente/@id', function($f3){ echo json_encode(getUtenteById($f3->get('PARAMS.id'))); });
$f3->route('DELETE /utente/logout', function($f3){ echo json_encode(logout()); });
