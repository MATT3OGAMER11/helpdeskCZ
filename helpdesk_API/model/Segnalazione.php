<?php

namespace model;

class Segnalazione
{
    public $id;
    public $testo_segnalazione;
    public $testo_soluzione;
    public $stato;
    public $id_utente;
    public $data_creazione;
    public $id_categoria;
    public $id_aula;
    public $id_dispositivo;
    public $presa_in_carico_da;
    public $data_presa_in_carico;

    /**
     * @param $id
     * @param $testo_segnalazione
     * @param $testo_soluzione
     * @param $stato
     * @param $id_utente
     * @param $data_creazione
     * @param $id_categoria
     * @param $id_aula
     * @param $id_dispositivo
     * @param $presa_in_carico_da
     * @param $data_presa_in_carico
     */
    public function __construct($id, $testo_segnalazione, $testo_soluzione, $stato, $id_utente, $data_creazione, $id_categoria, $id_aula, $id_dispositivo, $presa_in_carico_da, $data_presa_in_carico)
    {
        $this->id = $id;
        $this->testo_segnalazione = $testo_segnalazione;
        $this->testo_soluzione = $testo_soluzione;
        $this->stato = $stato;
        $this->id_utente = $id_utente;
        $this->data_creazione = $data_creazione;
        $this->id_categoria = $id_categoria;
        $this->id_aula = $id_aula;
        $this->id_dispositivo = $id_dispositivo;
        $this->presa_in_carico_da = $presa_in_carico_da;
        $this->data_presa_in_carico = $data_presa_in_carico;
    }


}