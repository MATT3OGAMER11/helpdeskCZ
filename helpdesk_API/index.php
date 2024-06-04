<?php

session_start();
use model\Dispositivo;
$f3 = require('fatfree-core-master/base.php');
$f3 = Base::instance();
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "helpdesk";


$options = array(
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // generic attribute
    \PDO::ATTR_PERSISTENT => TRUE,  // we want to use persistent connections
    \PDO::MYSQL_ATTR_COMPRESS => TRUE, // MySQL-specific attribute
);
$db= new \DB\SQL('mysql:host='.$servername.';port=3306;dbname='.$dbName,$username,$password, $options);



require_once 'authorization.php';



// UTENTI

require_once 'routes/utente.php';


// DISPOSITIVI

require_once 'routes/dispositivo.php';

// AULE

require_once 'routes/aula.php';

// PIANI

require_once 'routes/piano.php';

// NOTIFICA

require_once 'routes/notifica.php';

// CATEGORIA

require_once 'routes/categoria.php';

// SEGNALAZIONE

require_once 'routes/segnalazione.php';

// RUOLI

require_once 'routes/ruolo.php';

// CHAT

require_once 'routes/chat.php';

// ALERT

require_once 'routes/alert.php';
$f3->run();
?>