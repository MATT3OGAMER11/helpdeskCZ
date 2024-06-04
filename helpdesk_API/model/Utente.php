<?php

namespace model;

class Utente
{
    public $id;
    public $email;
    public $nome;
    public $cognome;
    public $data_creazione;
    public $id_ruolo;

    public function __construct($id, $email, $nome,$cognome,$data_creazione,$id_ruolo)
    {
        $this->id = $id;
        $this->email = $email;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->data_creazione = $data_creazione;
        $this->id_ruolo = $id_ruolo;
    }


}