<?php

namespace model;

class Piano
{
    public $id;
    public $nome;

    /**
     * @param $id
     * @param $nome
     */
    public function __construct($id, $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }


}