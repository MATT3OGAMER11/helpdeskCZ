<?php
function getDevice() {
    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('dispositivi','R',$sid)){
        global $db;
    $sql = 'SELECT * FROM dispositivo';
    $result = $db->exec($sql);
    $list = array();
    foreach ($result as $row){
        $device = new model\Dispositivo($row['id'],$row['nome'],$row['tipo'],$row['id_aula']);
        array_push($list,$device);
    }
    return $list;
    } else {
        return new model\NotAuthoraized("not authorized");
    }
    
}

function getDeviceByIdAula($id){
    global $db;
    $sql = 'SELECT * FROM dispositivo WHERE id_aula = ?';
    $result= $db->exec($sql,array($id));
    $list = array();
    foreach ($result as $row){
        $device = new model\Dispositivo($row['id'],$row['nome'],$row['tipo'],$row['id_aula']);
        array_push($list,$device);
    }
    return $list;
}

function getDeviceByID($id){
    global $db;
    $sql = 'SELECT d.id,d.nome,d.tipo,a.nome as id_aula FROM dispositivo as d JOIN aula as a on a.id = d.id_aula and d.id = ? ';
    $result= $db->exec($sql,array($id));
    foreach ($result as $row){
        $device = new model\Dispositivo($row['id'],$row['nome'],$row['tipo'],$row['id_aula']);
        return $device;
    }
}

function addDevice($dispositivo){
    if (checkPermission('dispositivi','C')){
        global $db;
        $sql = 'INSERT INTO dispositivo (id,nome,tipo,id_aula) VALUES (?,?,?,?)';
        $db->exec($sql,array($dispositivo->id,$dispositivo->nome,$dispositivo->tipo,$dispositivo->id_aula));
    } else{
        return "na";
    }
}

$f3->route('GET /dispositivi', function($f3) { echo json_encode(getDevice()); });
$f3->route('POST /dispositivi', function ($f3){ 
    //$nome= $f3->get($_POST);
    $data = file_get_contents('php://input');
    $datas = json_decode($data);
    if (is_array($datas)){
        echo 'array';
        foreach ($datas as $row){
            $nome = $row->nome;
            $tipo = $row->tipo;
            $aula = $row->id_aula;
            $dispositivo = new model\Dispositivo(null,$nome, $tipo, $aula);
            if (addDevice($dispositivo) == 'na'){
                echo json_encode('utente non autorizzato');
            }
        }
    } else {
        $nome = $datas->nome;
        $tipo = $datas->tipo;
        $aula = $datas->id_aula;
        $dispositivo = new model\Dispositivo(null,$nome, $tipo, $aula);
        if (addDevice($dispositivo) == 'na'){
            echo json_encode('utente non autorizzato');
        }
    }

});
$f3->route('PUT /dispositivi', function ($f3){});
$f3->route('DELETE /dispositivi/@id', function ($f3){});
$f3->route('GET /dispositivi/@id_aula', function($f3) { echo json_encode(getDeviceByIdAula($f3->get('PARAMS.id_aula')));  });
$f3->route('GET /dispositivo/@id', function($f3) { echo json_encode(getDeviceById($f3->get('PARAMS.id')));  });