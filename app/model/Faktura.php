<?php
namespace Pharmacy;
use Nette;

/**
 * 
 */
class Faktura extends Repository {
    
   

public function printAll(){
      return $this->connection->query('
                select f.id_faktura,meno,priezvisko,sum(cena) cena
                from faktura f, faktura_polozka fp ,lekarnik l
		where l.id_lekarnik = f.id_lekarnik and f.id_faktura =fp.id_faktura 
                group by f.id_faktura,meno,priezvisko');
  }
  
  public function printFaktura($id_faktura){
      return $this->connection->query('
                select id_faktura,t.id_tovar,nazov,fp.pocet,t.cena,doplatok 
                from faktura_polozka fp join tovar t on fp.id_tovar = t.id_tovar
                where id_faktura =?',$id_faktura);
  }
  
    public function createFaktura($id_lekarnik){
        
        $this->getTable()->insert(array(
            'cas_vystavenia' => date("Y-m-d H:i:s"),
            'id_lekarnik' => $id_lekarnik
        ));
    }
    
    public function getNextFakturaId(){
       return $this->connection->query('select last_value as last_value from faktura_id_seq')->fetch()->last_value;
    }
    
    
    public function addFakturaPolozka($id_faktura, $id_tovar, $pocet, $platba) {
        
        $res = $this->connection->query('
                select add_to_faktura(?, ?, ?, ?)',
                $id_faktura,
                $id_tovar,
                $pocet, 
                $platba)->fetch();
        
        return $this->pg_array_parse($res[0]);
    }

}