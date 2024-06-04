<?php

namespace model;

class Chat
{
    public $id;
    public $nome;
    public $id_ruolo;

    public function __construct($id, $nome, $id_ruolo){
        $this->id = $id;
        $this->nome = $nome;
        $this->id_ruolo = $id_ruolo;
    }
}