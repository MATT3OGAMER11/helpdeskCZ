<?php

function getUsertype($sid){
    global $db;
    $sql = 'SELECT u.id,u.id_ruolo,r.nome FROM sessione AS s JOIN utente as u on u.id = s.id_utente JOIN ruolo  as r on r.id = u.id_ruolo AND s.id_sessione = ?';
    $result = $db->exec($sql, array($sid));
    foreach ($result as $row){
        return $row['nome'];
    }
}

function getUsertypeID($sid){
    global $db;
    $sql = 'SELECT u.id,u.id_ruolo,r.nome FROM sessione AS s JOIN utente as u on u.id = s.id_utente JOIN ruolo  as r on r.id = u.id_ruolo AND s.id_sessione = ?';
    $result = $db->exec($sql, array($sid));
    foreach ($result as $row){
        return $row['id_ruolo'];
    }
}

function checkPermission($permCategory, $permType, $sid): bool
{
    $permissions = json_decode(file_get_contents('confFile/permission.json'),true);
    $userType = getUserType($sid);
    foreach($permissions as $permis){
        if($permis['endpoint']==$permCategory){
            if(str_contains($permis[$userType],$permType)){
                return true;
            }
        }
    }
    return false;
}
    