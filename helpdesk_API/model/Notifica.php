<?php

namespace model;

class Notifica
{
    public $id;
    public $messaggio_notifica;
    public $notificata;
    public $id_utente;
    public $id_segnalazione;

    public function __construct($id, $messaggio_notifica, $notificata, $id_utente, $id_segnalazione)
    {
        $this->id = $id;
        $this->messaggio_notifica = $messaggio_notifica;
        $this->notificata = $notificata;
        $this->id_utente = $id_utente;
        $this->id_segnalazione = $id_segnalazione;
    }


}