<?php

function getRuoli(){
    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('ruoli','R',$sid)){
        global $db;
        $sql = 'SELECT * FROM ruolo';
        $result = $db->exec($sql);
        $list = array();
        foreach ($result as $row){
            $ruolo = new model\Ruolo($row['id'],$row['nome'],$row['admin']);
            array_push($list,$ruolo);
        }
        return $list;
    } else {
        return new model\NotAuthoraized("not authorized");
    }
}



$f3->route('GET /ruoli', function($f3) { echo json_encode(getRuoli()); });
$f3->route('POST /ruoli', function ($f3){});
$f3->route('PUT /ruoli', function ($f3){});
$f3->route('DELETE /ruoli/@id', function ($f3){});
$f3->route('GET /ruoli/@id', function ($f3){});