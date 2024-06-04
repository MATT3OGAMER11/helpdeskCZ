<?php
// Initialize the session
session_start();
if (isset($_COOKIE["session"])){
    $cookie = $_COOKIE["session"];
    echo "SESSION FOUND     ".$cookie."\n";
}else{
    setcookie("session", session_id(), time() +86400 * 200);
}

require_once 'db.php';
global $logged;
$logged = false;
$sql1 = 'SELECT id_utente FROM sessione where id_sessione = ?';
$sth1 = $conn->prepare($sql1, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
$sth1->execute([$_COOKIE['session']]);
$res = $sth1->fetchAll();
foreach($res as $row){
    $logged = true;
}
if ($logged){
    header('Location: dashboard.php');
    exit;
}
// TODO: if you want the authentication to work you need to create a google app insert the necessary value to this 3 var
$google_oauth_client_id = "";
$google_oauth_client_secret = "";
$google_oauth_redirect_uri = "";

// If the captured code param exists and is valid
if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Execute cURL request to retrieve the access token
    $params = [
        'code' => $_GET['code'],
        'client_id' => $google_oauth_client_id,
        'client_secret' => $google_oauth_client_secret,
        'redirect_uri' => $google_oauth_redirect_uri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response, true);
    // Make sure access token is valid
    if (isset($response['access_token']) && !empty($response['access_token'])) {
        // Execute cURL request to retrieve the user info associated with the Google account
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);

        $profile = json_decode($response, true);
        // Make sure the profile data exists
        if (isset($profile['email'])) {
            // Authenticate the user
            session_regenerate_id();
            $_SESSION['google_loggedin'] = TRUE;
            $_SESSION['google_id'] = $profile['sub'];
            $_SESSION['google_email'] = $profile['email'];
            $_SESSION['google_name'] = isset($profile['given_name']) ? $profile['given_name'] : '';
            $_SESSION['google_surname'] = isset($profile['family_name']) ? $profile['family_name'] : '';
            $_SESSION['google_picture'] = isset($profile['picture']) ? $profile['picture'] : '';
            $exist = false;
            $sql1 = 'SELECT token FROM utente where token = ?';
            $sth1 = $conn->prepare($sql1, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $sth1->execute([$_SESSION['google_id']]);
            $res = $sth1->fetchAll();
            foreach($res as $row){
                $exist = true;
                }
        if (!$exist) {
            $sql1 = 'INSERT INTO utente (email,nome,cognome,token,id_ruolo) VALUES (?,?,?,?,?)';
            $sth1 = $conn->prepare($sql1, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $sth1->execute([$_SESSION['google_email'], $_SESSION['google_name'], $_SESSION['google_surname'], $_SESSION['google_id'], 1]);
            $res = $sth1->fetchAll();
        }
            $sql1 = 'SELECT id FROM utente where token = ?';
            $sth1 = $conn->prepare($sql1, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $sth1->execute([$_SESSION['google_id']]);
            $res = $sth1->fetchAll();
            $id = null;
            foreach($res as $row){
                $id = $row['id'];
            }
            $sql1 = 'INSERT INTO sessione (id_sessione,id_utente) VALUES (?,?)';
            $sth1 = $conn->prepare($sql1, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
            $sth1->execute([$_COOKIE['session'],$id]);
            $res = $sth1->fetchAll();
            // Redirect to profile page
            header('Location: dashboard.php');
            exit;
        } else {
            exit('Could not retrieve profile information! Please try again later!');
        }
    } else {
        exit('Invalid access token! Please try again later!');
    }
} else {
    // No response code, redirect to Google Authentication page with params
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}
?>