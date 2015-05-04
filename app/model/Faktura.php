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
  


}