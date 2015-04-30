<?php
namespace Pharmacy;
use Nette;

/**
 * Table Tovar
 */
class Tovar extends Repository {

    
    public function printAll() {

        return $this->findAll();
    }

    public function printByID($id) {
        
        return $this->findByID($id);
    }


}