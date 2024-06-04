<?php

function getAule() {
    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('aule','R',$sid)){
        global $db;
        $sql = 'SELECT * FROM aula';
        $result = $db->exec($sql);
        $list = array();
        foreach ($result as $row){
            $aula = new model\aula($row['id'],$row['numero'],$row['nome'],$row['tipo'],$row['mostra_sulla_mappa'],$row['id_piano']);
            array_push($list,$aula);
        }
        return $list;
    } else {
        return new model\NotAuthoraized("not authorized");
    }

}

function getAulaById($id) {
    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('aule','R',$sid)){
        global $db;
        $sql = 'SELECT * FROM aula where id = ?';
        $result = $db->exec($sql,array($id));
        foreach ($result as $row){
            $aula = new model\aula($row['id'],$row['numero'],$row['nome'],$row['tipo'],$row['mostra_sulla_mappa'],$row['id_piano']);
            return $aula;
        }
    } else {
        return array(new model\NotAuthoraized("not authorized"));
    }

}

function getAulaByDevice($id){
        global $db;
        $sql = 'SELECT * FROM aula WHERE (SELECT id_aula FROM dispositivo WHERE id = ?) = id';
        $result = $db->exec($sql,array($id));
        return $result[0];
}

function getAuleByPiano($id){
    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('aule','R',$sid)){
        global $db;
        $sql = 'SELECT * FROM aula where id_piano = ?';
        $result = $db->exec($sql,array($id));
        $list = array();
        foreach ($result as $row){
            $aula = new model\aula($row['id'],$row['numero'],$row['nome'],$row['tipo'],$row['mostra_sulla_mappa'],$row['id_piano']);
            array_push($list,$aula);
        }
    } else {
        return array(new model\NotAuthoraized("not authorized"));
    }
    return $list;
}

$f3->route('GET /aule', function($f3) { echo json_encode(getAule()); });
$f3->route('GET /aule/piano/@id', function($f3) { echo json_encode(getAuleByPiano($f3->get('PARAMS.id'))); });
$f3->route('POST /aule', function ($f3){});
$f3->route('PUT /aule', function ($f3){});
$f3->route('DELETE /aule/@id', function ($f3){});
$f3->route('GET /aule/@id', function ($f3){ echo json_encode(getAulaById($f3->get('PARAMS.id')));});