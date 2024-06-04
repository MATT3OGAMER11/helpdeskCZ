<?php

function addSegnalazione($data){
    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('segnalazione','C', $sid)){
        global $db;
        $result = $db->exec('SELECT id_utente FROM sessione WHERE id_sessione = ?',array($sid));
        $utente = $result[0]['id_utente'];
        $sql = 'INSERT INTO segnalazione (testo_segnalazione,id_utente,id_categoria,id_aula,id_dispositivo) VALUES (?,?,?,?,?)';
        $db->exec($sql,array($data->testo_segnalazione,$utente,$data->id_categoria,$data->id_aula,$data->id_dispositivo));
        createNotifica($data,$utente);
        controlAlert($data);
        $err = new model\NotAuthoraized("done");
        echo json_encode($err);
     }else{
        $err = new model\NotAuthoraized("not authorized");
        echo json_encode($err);
    }

 }

  function controlAlert($data){
     global $db;
        $u = getUtenteBySession();
     $isDevice = false;
     // check device check i the segnalation is about device or room
      if($data->id_dispositivo != null){
          $isDevice = true;
      }
      if($isDevice == true){

          $idSel = $db->exec('SELECT s.id,a.nome as nome_aula,d.nome as nome_dispositivo FROM segnalazione as s JOIN dispositivo as d ON s.id_dispositivo = d.id JOIN aula as a ON d.id_aula = a.id AND s.testo_segnalazione = ? AND s.id_utente = ? AND s.id_categoria = ? AND s.id_dispositivo = ? ORDER BY s.data_creazione DESC',array($data->testo_segnalazione,$u->id,$data->id_categoria,$data->id_dispositivo));
          // because the segnalation is about device
          $aulaOfDevice = getAulaByDevice($data->id_dispositivo);
          $pianoOfDevice = getPianoByAula($aulaOfDevice);
          $dispositivo = getDeviceByID($data->id_dispositivo);
          $sql = 'SELECT * FROM alert WHERE id_piano = ? AND id_aula = ? AND id_dispositivo = ? OR id_aula IS NULL AND id_piano = ? OR id_dispositivo IS NULL AND id_aula = ?;';
          $result = $db->exec($sql,array($pianoOfDevice,$aulaOfDevice['id'],$data->id_dispositivo,$pianoOfDevice,$aulaOfDevice['id']));
          foreach ($result as $row){
              if($row['id_categoria'] == null || $data->id_categoria == $row['id_categoria']){
                  //CREA NOTIFICA
                  $mess = 'ALERT: Nuova segnalazione, AULA: '.$aulaOfDevice['nome'].' DISPOSITIVO: '.$dispositivo['nome'];
                  creazioneNotifica($mess,$row['id_utente'],$idSel[0]['id']);
              }
          }
          $sql = 'SELECT * FROM alert WHERE id_piano IS NULL AND id_aula IS NULL AND id_dispositivo IS NULL AND id_categoria = ?;';
          $result = $db->exec($sql,array($data->id_categoria));
          foreach ($result as $row){
              //CREA NOTIFICA
              $mess = 'ALERT: Nuova segnalazione, AULA: '.$aulaOfDevice['nome'].' DISPOSITIVO: '.$dispositivo['nome'];
              creazioneNotifica($mess,$row['id_utente'],$idSel[0]['id']);
          }


      } else {
          $idSel = $db->exec('SELECT s.id FROM segnalazione as s JOIN aula as a ON s.id_aula = a.id AND s.testo_segnalazione = ? AND s.id_utente = ? AND s.id_categoria = ? AND s.id_aula = ? ORDER BY s.data_creazione DESC',array($data->testo_segnalazione,$u->id,$data->id_categoria,$data->id_aula));
          // because the segnalation is about room
          $aula = getAulaById($data->id_aula);
          $pianoOfDevice = getPianoByAula($data->id_aula);
          $sql = 'SELECT * FROM alert WHERE id_piano = ? AND id_aula = ? AND id_dispositivo IS NULL OR id_aula IS NULL AND id_piano = ?;';
          $result = $db->exec($sql,array($pianoOfDevice,$data->id_aula,$pianoOfDevice));
          foreach ($result as $row){
              if($row['id_categoria'] == null || $data->id_categoria == $row['id_categoria']){
                  //CREA NOTIFICA
                  $mess = 'ALERT: Nuova segnalazione, AULA: '.$aula->nome;
                  creazioneNotifica($mess,$row['id_utente'],$idSel[0]['id']);
              }
          }
          $sql = 'SELECT * FROM alert WHERE id_piano IS NULL AND id_aula IS NULL AND id_dispositivo IS NULL AND id_categoria = ?;';
          $result = $db->exec($sql,array($data->id_categoria));
          foreach ($result as $row){
              //CREA NOTIFICA
              $mess = 'ALERT: Nuova segnalazione, AULA: '.$aula->nome;
              creazioneNotifica($mess,$row['id_utente'],$idSel[0]['id']);
          }

      }
 }

 function updateSegnalazione($data){

    $sid = $_SERVER['HTTP_X_SSID'];
    if(checkPermission('segnalazione','U',$sid)){
        //check user role
        global $db;
        $role = getUserTypeID($sid);
        $authraized = false;
        $sql = 'SELECT * FROM ruolo WHERE admin = true';
        $result = $db->exec($sql);
        foreach($result as $row){
            if($role == $row['id']){
                $authraized = true;
            }
        }
        if ($authraized == false){
            $sql = 'SELECT * FROM CATEGORIA WHERE id_ruolo = ? AND id = ?';
            $result = $db->exec($sql,array($role,$data->id_categoria));
            if(count($result) == 0){
                $err = new model\NotAuthoraized("not authorized");
                return $err;
            } else {
                $authraized = true;
            }
        }
        $sql = 'UPDATE segnalazione SET testo_segnalazione = ?, testo_soluzione = ?, stato = ?, id_utente = ?, data_creazione = ?, id_categoria = ?, id_aula = ?, id_dispositivo = ?, presa_in_carico_da = ?, data_presa_in_carico = ? WHERE id = ?';
        $db->exec($sql,array($data->testo_segnalazione,$data->testo_soluzione,$data->stato,$data->id_utente,$data->data_creazione,$data->id_categoria,$data->id_aula,$data->id_dispositivo,$data->presa_in_carico_da,$data->data_presa_in_carico,$data->id));
        return new model\NotAuthoraized("done");
    }else{
        $err = new model\NotAuthoraized("not authorized");
        return $err;
    }
 }

function selectTecnico($cat,$id,$tipo){
    global $db;

    if ($tipo == 'A'){
        // check utentexaule
        $sql = 'SELECT u.id as utente FROM categoria as cat JOIN ruolo as r on cat.id_ruolo = r.id JOIN utente as u ON r.id = u.id_ruolo JOIN utente_x_aula AS uxa on u.id = uxa.id_utente AND uxa.id_aula = ? AND cat.id = ?';
        $result = $db->exec($sql,array($id,$cat));
        foreach ($result as $row){
           return $row['utente'];
        }
        $sql = 'SELECT u.id as utente FROM categoria as cat JOIN ruolo as r on cat.id_ruolo = r.id JOIN utente as u ON r.id = u.id_ruolo JOIN aula as a on a.id = ? JOIN utente_x_piano AS uxp on u.id = uxp.id_utente AND uxp.id_piano = a.id_piano AND cat.id = ?;';
        $result = $db->exec($sql,array($id,$cat));
        foreach ($result as $row){
            return $row['utente'];
        }
        // check utentexpiano
    } else {
        // DISPOSITIVO
        // check utentexaula
        $sql = 'SELECT u.id as utente FROM categoria as cat JOIN ruolo as r on cat.id_ruolo = r.id JOIN utente as u ON r.id = u.id_ruolo JOIN dispositivo as d ON d.id = ? JOIN utente_x_aula AS uxa on u.id = uxa.id_utente AND uxa.id_aula = d.id_aula AND cat.id = ?;';
        $result = $db->exec($sql,array($id,$cat));
        foreach ($result as $row){
            return $row['utente'];
        }
        // check utentexpiano
        $sql = 'SELECT u.id as utente FROM categoria as cat JOIN ruolo as r on cat.id_ruolo = r.id JOIN utente as u ON r.id = u.id_ruolo JOIN dispositivo as d ON d.id = ? JOIN aula as a on a.id = d.id_aula JOIN utente_x_piano AS uxp on u.id = uxp.id_utente AND uxp.id_piano = a.id_piano AND cat.id = ?;';
        $result = $db->exec($sql,array($id,$cat));
        foreach ($result as $row){
            return $row['utente'];
        }

    }

    // check piano

 }

 function createNotifica($data, $u){
     global $db;
     if ($data->id_dispositivo == null){
         $result = $db->exec('SELECT s.id,a.nome FROM segnalazione as s JOIN aula as a ON s.id_aula = a.id AND s.testo_segnalazione = ? AND s.id_utente = ? AND s.id_categoria = ? AND s.id_aula = ? ORDER BY s.data_creazione DESC',array($data->testo_segnalazione,$u,$data->id_categoria,$data->id_aula));
         $mess = 'Nuova segnalazione, AULA: '.$result[0]['nome'];
         $id = $data->id_aula;
         $tipo = 'A';
     } else {
         $result = $db->exec('SELECT s.id,a.nome as nome_aula,d.nome as nome_dispositivo FROM segnalazione as s JOIN dispositivo as d ON s.id_dispositivo = d.id JOIN aula as a ON d.id_aula = a.id AND s.testo_segnalazione = ? AND s.id_utente = ? AND s.id_categoria = ? AND s.id_dispositivo = ? ORDER BY s.data_creazione DESC',array($data->testo_segnalazione,$u,$data->id_categoria,$data->id_dispositivo));
         $mess = 'Nuova segnalazione, AULA: '.$result[0]['nome_aula'].' DISPOSITIVO: '.$result[0]['nome_dispositivo'];
         $id = $data->id_dispositivo;
         $tipo = 'D';
     }
     $idu = selectTecnico($data->id_categoria,$id,$tipo);

     $sql = 'INSERT INTO notifica (messaggio_notifica,id_utente,id_segnalazione) VALUES (?,?,?)';
        $db->exec($sql,array($mess,$idu,$result[0]['id']));
 }




 function getSegnalazioneById($id){
     global $db;
     $sid = $_SERVER['HTTP_X_SSID'];
    if (checkPermission('segnalazione','R',$sid)){
        $sql = 'SELECT * FROM segnalazione WHERE id = ?';
        $result = $db->exec($sql,array($id));
        return new model\Segnalazione($result[0]['id'],$result[0]['testo_segnalazione'],$result[0]['testo_soluzione'],$result[0]['stato'],$result[0]['id_utente'],$result[0]['data_creazione'],$result[0]['id_categoria'],$result[0]['id_aula'],$result[0]['id_dispositivo'],$result[0]['presa_in_carico_da'],$result[0]['data_presa_in_carico']);
    }else{
        $usr = getUtenteBySession();
        $sql = 'SELECT * FROM segnalazione WHERE id = ? AND id_utente = ?';
        $result = $db->exec($sql,array($id,$usr->id));
        foreach($result as $row){
            return new model\Segnalazione($row['id'],$row['testo_segnalazione'],$row['testo_soluzione'],$row['stato'],$row['id_utente'],$row['data_creazione'],$row['id_categoria'],$row['id_aula'],$row['id_dispositivo'],$row['presa_in_carico_da'],$row['data_presa_in_carico']);
        }
        return new model\NotAuthoraized("not authorized");
    }
 }

 function getSegnalazioniByUtente(){
     global $db;
     $id = getUtenteBySession();
     $sql = "SELECT * FROM segnalazione WHERE id_utente = ?";
     $result = $db->exec($sql,array($id->id));
     $lista = array();
     foreach($result as $row){
         array_push($lista,new model\Segnalazione($row['id'],$row['testo_segnalazione'],$row['testo_soluzione'],$row['stato'],$row['id_utente'],$row['data_creazione'],$row['id_categoria'],$row['id_aula'],$row['id_dispositivo'],$row['presa_in_carico_da'],$row['data_presa_in_carico']));
     }
     return $lista;
 }

 function getSegnalazioni(){
    global $db;
    if(isAdmin(getUtenteBySession())) {
        $sql = "SELECT * FROM segnalazione";
        $result = $db->exec($sql);
        $lista = array();
        foreach ($result as $row) {
            array_push($lista, new model\Segnalazione($row['id'], $row['testo_segnalazione'], $row['testo_soluzione'], $row['stato'], $row['id_utente'], $row['data_creazione'], $row['id_categoria'], $row['id_aula'], $row['id_dispositivo'], $row['presa_in_carico_da'], $row['data_presa_in_carico']));
        }
        return $lista;
    } else {
        return new model\NotAuthoraized("not authorized");
    }
 }

 function pickSegnalazione($id){
    global $db;
     $sid = $_SERVER['HTTP_X_SSID'];
     if(checkPermission('segnalazione','U',$sid)) {
         $sql = 'UPDATE segnalazione SET stato = "presa in carico" , presa_in_carico_da = ?, data_presa_in_carico = NOW() WHERE id = ?';
         $db->exec($sql,array(getUtenteBySession()->id,$id));
         return new model\NotAuthoraized('done');
     } else {
         return new model\NotAuthoraized('not authorized');
     }
 }

$f3->route('POST /segnalazione', function ($f3){
    $data = file_get_contents('php://input');
    $datas = json_decode($data);
    //echo var_dump($datas).$datas->testo_segnalazione.$datas->id_utente.$datas->id_categoria.' '.$datas->id_aula.' '.$datas->id_dispositivo;
    addSegnalazione($datas);
});

$f3->route('PUT /segnalazione', function ($f3){
    $data = file_get_contents('php://input');
    $datas = json_decode($data);
    //echo var_dump($datas).$datas->testo_segnalazione.$datas->id_utente.$datas->id_categoria.' '.$datas->id_aula.' '.$datas->id_dispositivo;
    updateSegnalazione($datas);
});

$f3->route('GET /segnalazione', function($f3){
    echo json_encode(getSegnalazioni());
});

$f3->route('GET /segnalazione/@id', function($f3){
    echo json_encode(getSegnalazioneById($f3->get('PARAMS.id')));
});

$f3->route('GET /segnalazione/u', function($f3){
    echo json_encode(getSegnalazioniByUtente());
});
$f3->route('GET /segnalazione/pick/@id', function($f3){
    echo json_encode(pickSegnalazione($f3->get('PARAMS.id')));
});