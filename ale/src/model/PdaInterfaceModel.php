<?php

/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 07/07/16
 * Time: 10:22
 */
interface PdaInterfaceModel
{
    /**
     * Salvataggio dell'oggetto
     *
     * @param bool $forzaInsert se true salva l'oggetto settando anche la chiave primaria
     *
     * @return int
     */
    public function saveOrUpdate($forzaInsert);

    /**
     * Salvataggio dell'oggetto da un array posizionale
     *
     * @param bool $forzaInsert se true salva l'oggetto settando anche la chiave primaria
     *
     * @return int
     */
    public function saveOrUpdatePosizionale($arrayPosizionale);




    

    /** @return PDO */
    public function getPdo();

    /** @param PDO $pdo */
    public function setPdo($pdo);

}