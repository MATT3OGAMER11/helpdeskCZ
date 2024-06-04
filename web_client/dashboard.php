<?php
session_start();
echo '<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk Zuccante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-secondary">
    <div class="container-fluid">
        <a href="index.html" class="navbar-brand">Helpdesk</a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="mappa.html">Mappa</a>
                <a class="nav-item nav-link" href="chat.html"><i class="bi bi-chat-square-text"></i></a>

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"  aria-expanded="false">
                        <i class="bi bi-bell-fill"></i>
                    </button>
                    <ul class="dropdown-menu" id="notifiche" aria-labelledby="dropdownMenuButton1">

                    </ul>
                </div>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="nav-item nav-link" href="auth.php"> <i class="bi bi-person-fill"></i></a>
            </div>

        </div>
    </div>
</nav>
<br>
<div class="row">
    <div class="card col-md-5 center" >
    <div class="card-header">
                <h4>Info Account</h4>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4" id="name">
                    
                </div>
                <div class="col-lg-1">
                   
                </div>
                <div class="col-lg-4" id="surname">
                    
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-4" id="email">
                   
                </div>
                <div class="col-lg-1">
                   
                </div>
                <div class="col-lg-4" id="ruolo">
                </div>
            </div>  
            <br>
            <div class="row">
                <div class="col-lg-4" id="password">
                    
                </div>
            </div>  
            <div class="row">
                <div class="col-lg-4" id="dataD">
                    
                </div>
            </div>
            
            
</div>
        <div class="card-footer">
            <button class="btn btn-primary" onclick="updateAcc()">Salva</button>
            <button class="btn btn-danger" onclick="logout()">Logout</button>
    </div>
</div>
</div>
<div class="space-between"></div>
<div class="row" hidden="hidden" id="rowSel">
    <div class="card center">
        <div class="card-header">
                <h4>Segnalazioni effettuate</h4>
        </div>
            <table class="table" >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoria</th>
                        <th>Luogo</th>
                        <th>Data creazione</th>
                        <th>Stato</th>
                        <th>Presa in carico da</th>
                        <th>Vai alla segnalazione</th>
                    </tr>
                </thead>
                <tbody id="segnal">
          
                </tbody>
                </table>
        </div>
</div>
<div class="space-between"></div>
<div class="row"  id="rowAlert" hidden="hidden">
    <div class="card center">
        <div class="card-header">
            <div class="row">
                <div class="col-md-2">
                    <h4>Alert creati</h4>
                </div>
                <div class="col-md-5">
                    <a href="alert.html" class="btn btn-primary">Crea un alert</a>
                </div>  
            </div>
            </div>
            <table class="table" >
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoria</th>
                        <th>Dispositivo</th>
                        <th>Aula</th>
                        <th>Piano</th>
                        <th>Data creazione</th>
                        <th>Modifica alert</th>
                        <th>Elimina alert</th>
                    </tr>
                </thead>
                <tbody id="alert">
          
                </tbody>
                </table>
        </div>
</div>
<br>
</body>
<footer class="text-center bg-secondary footer">
    <p class="text-center p-3">© 2024 Matteo Piazzon. Tutti i diritti riservati.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src ="script.js"></script>
<script src ="js/notification.js"></script>
<script src ="js/user.js"></script>
<script src ="js/alert.js"></script>
<script>
 showAlert();
</script>
</html>
';