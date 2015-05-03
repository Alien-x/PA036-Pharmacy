<?php
namespace Pharmacy;
use Nette;

/**
 * Table Tovar
 */
class Tovar extends Repository {
    
    public function printAll($isset = false, $indikacna_skupina = null) {

        if ($isset && $indikacna_skupina != null && $indikacna_skupina != 0) {
            return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)
                where id_skupina = ?', $indikacna_skupina);
        }
        elseif ($isset && $indikacna_skupina == 0) {
            return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)
                where t.doplnkovy_tovar = true');
        }
        else {
            return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)');
        }
    }

    public function printByID($id_tovar) {
        
        return $this->connection->query('
                select t.id_tovar, t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    i.nazov as skupina_nazov, ol.id_ucinna, ul.popis as ucinna_nazov, 
                    t.mnozstvo, t.uzitie, m.nazov as jednotka
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON (ol.id_liek = l.id_liek)
		JOIN mnozstvo_forma m ON (m.id_forma = t.id_forma)
		JOIN indikacna_skupina i ON(i.id_skupina = l.id_skupina)
		JOIN ucinna_latka ul ON(ul.id_ucinna =ol.id_ucinna)
		where t.id_tovar = ?', $id_tovar)
                ->fetch();
    }
    
    public function printNahrada($id_tovar) {
        return $this->connection->query('
                select t.nazov, t. id_tovar, t.cena, 
                    o.id_ucinna, u.popis
                from tovar t 
                join liek l on(t.id_tovar = l.id_liek) 
                join obsah_latok o on (o.id_liek = l.id_liek) 
                join ucinna_latka u on (u.id_ucinna = o.id_ucinna) 
                where t.id_tovar=?', $id_tovar)
                ->fetch();
    }
    
    public function printUcinnaLatka($id_ucinna) {
        return $this->connection->query('
                select * from ucinna_latka 
                where id_ucinna = ?',
                $id_ucinna)->fetch();
    }
    
    public function printNahrady($id_ucinna) {
        return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek) 
                where ol.id_ucinna = ?', $id_ucinna);
    }
    
    public function printIndikacneSkupiny() {
        return $this->getTable('indikacna_skupina')
                ->fetchPairs('id_skupina', 'nazov');
    }
    


}