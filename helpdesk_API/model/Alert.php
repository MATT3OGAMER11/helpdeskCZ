<?php

namespace model;

class Alert{

    public $id;
    public $id_utente;

    public $id_categoria;

    public $id_dispositivo;

    public $id_aula;

    public $id_piano;

    public $data_creazione;

    /**
     * @param $id_utente
     * @param $id_categoria
     * @param $id_dispositivo
     * @param $id_aula
     * @param $id_piano
     * @param $data_creazione
     */
    public function __construct($id,$id_utente, $id_categoria, $id_dispositivo, $id_aula, $id_piano, $data_creazione)
    {
        $this->id = $id;
        $this->id_utente = $id_utente;
        $this->id_categoria = $id_categoria;
        $this->id_dispositivo = $id_dispositivo;
        $this->id_aula = $id_aula;
        $this->id_piano = $id_piano;
        $this->data_creazione = $data_creazione;
    }


}