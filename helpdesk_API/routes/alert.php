<?php


function getAlert(){
    $sid = $_SERVER['HTTP_X_SSID'];
    $id = getUtenteBySession();
    if(checkPermission('alert','R',$sid)){
        global $db;
        $sql = 'SELECT * FROM alert WHERE id_utente = ?';
        $result = $db->exec($sql,array($id->id));
        $list = array();
        foreach ($result as $row){
            $al = new model\Alert($row['id'],$row['id_utente'],$row['id_categoria'],$row['id_dispositivo'],$row['id_aula'],$row['id_piano'],$row['data_creazione']);
            array_push($list,$al);
        }
        return $list;
    } else {
        return new model\NotAuthoraized("not authorized");
    }
}

function deleteAlert($id){
    $sid = $_SERVER['HTTP_X_SSID'];
    $idu = getUtenteBySession();
    if(checkPermission('alert','D',$sid)){
        global $db;
        $sql = 'DELETE FROM alert WHERE id_utente = ? and id = ?';
        $db->exec($sql,array($idu->id,$id));
        return new model\NotAuthoraized("deleted");
    } else {
        return new model\NotAuthoraized("not authorized");
    }
}

function addAlert($alert){
    $sid = $_SERVER['HTTP_X_SSID'];
    $idu = getUtenteBySession();
    if(checkPermission('alert','C',$sid)){
        global $db;
        $sql = 'INSERT INTO  alert (id_utente,id_categoria,id_dispositivo,id_aula,id_piano) VALUES (?,?,?,?,?)';
        $db->exec($sql,array($idu->id,$alert->id_categoria,$alert->id_dispositivo,$alert->id_aula,$alert->id_piano));
        return new model\NotAuthoraized("done");
    } else {
        return new model\NotAuthoraized("not authorized");
    }
}

function updateAlert(){
    $sid = $_SERVER['HTTP_X_SSID'];
    $idu = getUtenteBySession();
    if(checkPermission('alert','U',$sid)){
        global $db;
        $data = file_get_contents('php://input');
        $datas = json_decode($data);
        $sql = 'UPDATE alert SET id_categoria = ?, id_dispositivo = ?, id_aula = ?, id_piano = ? WHERE id = ?';
        $db->exec($sql,array($datas->id_categoria,$datas->id_dispositivo,$datas->id_aula,$datas->id_piano,$datas->id));
        return new model\NotAuthoraized("done");
    } else {
        return new model\NotAuthoraized("not authorized");
    }
}

function getAlertById($id){
    $sid = $_SERVER['HTTP_X_SSID'];
    $idu = getUtenteBySession();
    if(checkPermission('alert','R',$sid)){
        global $db;
        $sql = 'SELECT * FROM alert WHERE id = ? AND id_utente = ?';
        $result = $db->exec($sql,array($id,$idu->id));
        $al = new model\Alert($result[0]['id'],$result[0]['id_utente'],$result[0]['id_categoria'],$result[0]['id_dispositivo'],$result[0]['id_aula'],$result[0]['id_piano'],$result[0]['data_creazione']);
        return $al;
    } else {
        return new model\NotAuthoraized("not authorized");
    }

}

$f3->route('GET /alert', function($f3) { echo json_encode(getAlert()); });
$f3->route('GET /alert/@id', function($f3) { echo json_encode(getAlertById($f3->get('PARAMS.id'))); });
$f3->route('POST /alert', function ($f3){
    $data = file_get_contents('php://input');
    $alert = json_decode($data);
    echo json_encode(addAlert($alert));
});
$f3->route('PUT /alert', function ($f3){echo json_encode(updateAlert());});
$f3->route('DELETE /alert/@id', function ($f3){echo json_encode(deleteAlert($f3->get('PARAMS.id')));});
//$f3->route('GET /alert/@id', function ($f3){ echo json_encode(getAlertById($f3->get('PARAMS.id')));});
