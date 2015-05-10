<?php
namespace Pharmacy;
use Nette;

/**
 * 
 */
class Lekarnik extends Repository {
    
    public function printLekarniciFaktury($od = null, $doo = null) {

        if(is_null($od) || is_null($doo)) {
        
            return $this->connection->query('
                select meno,priezvisko,titul, count(*) as pocet 
                from lekarnik l, faktura f, faktura_polozka fp
                where l.id_lekarnik = f.id_lekarnik and f.id_faktura= fp.id_faktura
                group by meno,priezvisko,titul
                order by pocet');
        }
        else {
            
            return $this->connection->query(" 
                select meno, priezvisko, titul, count(*) as pocet 
                from lekarnik l, faktura f, faktura_polozka fp
                where l.id_lekarnik = f.id_lekarnik and f.id_faktura= fp.id_faktura 
                    and cas_vystavenia > to_date(?, 'DD.MM.YYYY') 
                    and cas_vystavenia < to_date(?, 'DD.MM.YYYY')
                group by meno, priezvisko, titul", $od, $doo);
        }
    }


}