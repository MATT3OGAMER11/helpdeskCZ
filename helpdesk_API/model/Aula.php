<?php

namespace model;

class Aula
{
    public $id;
    public $numero;
    public $nome;
    public $tipo;
    public $mostra_sulla_mappa;
    public $id_piano;

    /**
     * @param $id
     * @param $numero
     * @param $nome
     * @param $tipo
     * @param $mostra_sulla_mappa
     * @param $id_piano
     */
    public function __construct($id, $numero, $nome, $tipo, $mostra_sulla_mappa, $id_piano)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->mostra_sulla_mappa = $mostra_sulla_mappa;
        $this->id_piano = $id_piano;
    }



}