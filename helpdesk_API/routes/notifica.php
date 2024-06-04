<?php

function getNotifiche(){
    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('notifica','R',$sid)){
        $id = getUtenteBySession();
        global $db;
        $sql = 'SELECT * FROM notifica WHERE id_utente = ? ORDER BY id desc ';
        $result = $db->exec($sql,array($id->id));
        $list = array();
        foreach ($result as $row){
            $notifica = new model\Notifica($row['id'],$row['messaggio_notifica'],$row['notificata'],$row['id_utente'],$row['id_segnalazione']);
            array_push($list,$notifica);
        }
        return $list;
    } else {
        return new model\NotAuthoraized("not authorized");
    }
}

function creazioneNotifica($mess,$idu,$idS){
    global $db;
    $sql = 'INSERT INTO notifica (messaggio_notifica,id_utente,id_segnalazione) VALUES (?,?,?)';
    $db->exec($sql,array($mess,$idu,$idS));
}

function notificaLetta($idNot){
    $id = getUtenteBySession();
    global $db;
    $sql = 'UPDATE notifica SET notificata = true WHERE id_utente = ? AND id = ?';
    $db->exec($sql,array($id->id,$idNot));
    return new model\NotAuthoraized("done");
}
$f3->route('GET /notifica', function($f3) {echo json_encode(getNotifiche());});
$f3->route('POST /notifica', function ($f3){});
$f3->route('PUT /notifica/@id', function ($f3){echo json_encode(notificaLetta($f3->get('PARAMS.id')));});
$f3->route('GET /notifica/@id', function ($f3){});
$f3->route('DELETE /piani/@id', function ($f3){});