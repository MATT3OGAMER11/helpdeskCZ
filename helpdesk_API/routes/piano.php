<?php
function getPiani(){
    global $db;
    $sql = 'SELECT * FROM piano ORDER BY id';
    $result = $db->exec($sql);
    $list = array();
    foreach ($result as $row){
        $aula = new model\piano($row['id'],$row['nome']);
        array_push($list,$aula);
    }
    return $list;
}

function getPianoByAula($aulaOfDevice){
    global $db;
    $sql = 'SELECT * FROM piano WHERE id = (SELECT id_piano FROM aula WHERE id = ?)';
    $result = $db->exec($sql, array($aulaOfDevice));
    return $result[0]['id'];
}

$f3->route('GET /piani', function($f3) {
    echo json_encode(getPiani());
});
$f3->route('POST /piani', function ($f3){});
$f3->route('PUT /piani', function ($f3){});
$f3->route('DELETE /piani/@id', function ($f3){});