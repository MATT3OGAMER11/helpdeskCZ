<?php

function getCategoria(){
    global $db;
    $sql = 'SELECT * FROM categoria';
    $result = $db->exec($sql);
    $list = array();
    foreach ($result as $row){
        $categoria = new model\Categoria($row['id'],$row['nome'],$row['id_ruolo']);
        array_push($list,$categoria);
    }
    return $list;
}

function getCategoriaByRuolo(){
    global $db;
    $usr = getUtenteBySession();
    $sql = 'SELECT * FROM categoria WHERE id_ruolo = ?';
    if (isAdmin($usr)){
        $sql = 'SELECT * FROM categoria';
    }

    $result = $db->exec($sql,array($usr->id_ruolo));
    $list = array();
    foreach ($result as $row){
        $categoria = new model\Categoria($row['id'],$row['nome'],$row['id_ruolo']);
        array_push($list,$categoria);
    }
    return $list;
}


$f3->route('GET /categoria', function($f3) { echo json_encode(getCategoria()); });
$f3->route('GET /categoria/ruolo', function($f3) { echo json_encode(getCategoriaByRuolo()); });