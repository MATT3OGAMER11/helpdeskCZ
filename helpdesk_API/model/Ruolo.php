<?php

namespace model;

class Ruolo
{
    public $id;

    public $nome;

    public $admin;

    /**
     * @param $id
     * @param $nome
     * @param $admin
     */
    public function __construct($id, $nome, $admin)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->admin = $admin;
    }


}