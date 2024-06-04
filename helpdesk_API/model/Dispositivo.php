<?php

namespace model;

class Dispositivo
{
    public $id;
    public $nome;
    public $tipo;
    public $id_aula;

    public function __construct($id,$nome,$tipo,$id_aula)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->id_aula = $id_aula;
    }

    public function stamp(){
        echo $this->id.' '.$this->nome.' '.$this->tipo.' '.$this->id_aula;
    }

}