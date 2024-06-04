<?php

namespace model;

class ChatxMessaggio
{
    public $nomechat;
    public $id_ruolo;
    public $id_messaggio;
    public $messaggio;
    public $data;
    public $nome;
    public $cognome;
    public $usr;

    /**
     * @param $nomechat
     * @param $id_ruolo
     * @param $id_messaggio
     * @param $messaggio
     * @param $data
     * @param $nome
     * @param $cognome
     */
    public function __construct($nomechat, $id_ruolo, $id_messaggio, $messaggio, $data, $nome, $cognome,$usr)
    {
        $this->nomechat = $nomechat;
        $this->id_ruolo = $id_ruolo;
        $this->id_messaggio = $id_messaggio;
        $this->messaggio = $messaggio;
        $this->data = $data;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->usr = $usr;
    }


}