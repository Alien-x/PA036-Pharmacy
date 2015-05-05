<?php
namespace Pharmacy;
use Nette;

/**
 * 
 */
class Recept extends Repository {
    
   
    public function printAll(){
          return $this->getTable();
    }

  
    public function receptExists($id){

        $res = $this->connection->query('
            select COUNT(*) as count 
            from recept 
            where id_recept = ?', $id)->fetch();

        if($res->count > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public function getZpusobHrazeni($id) {
        
        return $this->connection->query('
                    select sposob_uhrady 
                    from recept 
                    where id_recept = ?', $id)->fetch()->sposob_uhrady;
    }
  
    public function insert(array $recept){

        $this->connection->query(
            'INSERT INTO recept', $recept);
    }
  


}