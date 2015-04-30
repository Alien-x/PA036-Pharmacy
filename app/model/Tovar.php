<?php
namespace Pharmacy;
use Nette;

/**
 * Table Tovar
 */
class Tovar extends Repository {

    /*
    public function printAll() {

        return $this->findAll();
    }*/

    public function printByID($id) {
        /*
        $tovar = $this->database->table('tovar')->get($id_tovar);
        if (!$tovar) {
            $this->error('Stránka nebyla nalezena');
        }
        */
        return $this->connection->table('tovar')->get($id);
        //return $this->findBy(array('id_tovar' => $id)); //->order('Balance DESC');
    }


}