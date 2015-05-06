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
                LEFT JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)
                where id_skupina = ?  and t.aktivny = true', $indikacna_skupina);
        } elseif ($isset && $indikacna_skupina == 0) {
            return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                LEFT JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)
                where t.doplnkovy_tovar = true and t.aktivny = true');
        } else {
            return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                LEFT JOIN obsah_latok ol ON(ol.id_liek = l.id_liek) and t.aktivny = true');
        }
    }

    public function printByID($id_tovar) {

        return $this->connection->query('
                select t.id_tovar, t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    i.nazov as skupina_nazov, ol.id_ucinna, ul.popis as ucinna_nazov, 
                    t.mnozstvo, t.uzitie, m.nazov as jednotka
                from tovar t 
                LEFT JOIN liek l ON(l.id_tovar = t.id_tovar) 
                LEFT JOIN obsah_latok ol ON (ol.id_liek = l.id_liek)
		LEFT JOIN mnozstvo_forma m ON (m.id_forma = t.id_forma)
		LEFT JOIN indikacna_skupina i ON(i.id_skupina = l.id_skupina)
		LEFT JOIN ucinna_latka ul ON(ul.id_ucinna =ol.id_ucinna)
		where t.id_tovar = ? and t.aktivny = true', $id_tovar)
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
                where t.id_tovar=? and t.aktivny = true', $id_tovar)
                        ->fetch();
    }

    public function printZleKombinacie($id_ucinna) {
        return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                LEFT JOIN obsah_latok ol ON(ol.id_liek = l.id_liek) 
                where ol.id_ucinna in (
			select id_ucinna_2 as id_ucinna from zla_kombinacia where id_ucinna_1 = ?
                        ) and t.aktivny = true', $id_ucinna);
    }

    public function printUcinnaLatka($id_ucinna) {
        return $this->connection->query('
                select * from ucinna_latka 
                where id_ucinna = ?', $id_ucinna)->fetch();
    }

    //mal by byt zvlast servis ucinna
    public function printUcinna() {
        return $this->connection->query('
                select id_ucinna, (id_ucinna|| ? || popis)as popis from ucinna_latka', ' - ')->fetchPairs('id_ucinna', 'popis');
    }

    //mal by byt zvlast servis mnozstvo forma
    public function printForma() {
        return $this->getTable('mnozstvo_forma')
                        ->fetchPairs('id_forma', 'nazov');
    }
    
    
    public function printIdLiek(){
         return $this->connection->query('select l.id_tovar, t.nazov from tovar t right join liek l on(t.id_tovar=l.id_tovar) where t.aktivny = true')->fetchPairs('id_tovar','nazov');
    }
    
    public function inserSamotnytLiek($sn,$time,$FK){
        $this->connection->query('insert into samotny_liek values(?,TIMESTAMP ?,CURRENT_TIMESTAMP,?)',$sn,$time,$FK);
    }

    public function printNahrady($id_ucinna) {
        return $this->connection->query('
                select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, 
                    t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, 
                    id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek) 
                where ol.id_ucinna = ? and t.aktivny = true', $id_ucinna);
    }

    public function printIndikacneSkupiny() {
        return $this->getTable('indikacna_skupina')
                        ->fetchPairs('id_skupina', 'nazov');
    }

    public function insertTovar($data) {
        return $this->connection->table('tovar')->uinsert($data);
    }

    public function insertLiek($id_tovar, $id_skupina, $id_ucinna) {
        $this->connection->query('select insert_liek(?,?,?)', $id_tovar, $id_skupina, $id_ucinna);
    }

    public function printBestsellers($od = null, $doo = null) {
        if(is_null($od) || is_null($doo)) {
            return $this->connection->query("
            select t.nazov, t.id_tovar, t.cena, sum(fp.pocet) as pocetBest
            from tovar t, faktura_polozka fp, faktura f
            where fp.id_tovar = t.id_tovar and fp.id_faktura = f.id_faktura
            group by t.nazov, t.id_tovar, t.cena
            ORDER BY pocetBest DESC");  
        }
        else {
            return $this->connection->query("
            select t.nazov, t.id_tovar, t.cena, sum(fp.pocet) as pocetBest
            from tovar t, faktura_polozka fp, faktura f
            where fp.id_tovar = t.id_tovar and fp.id_faktura = f.id_faktura
            and cas_vystavenia >= to_date(?, 'DD.MM.YYYY')
            and cas_vystavenia <= to_date(?, 'DD.MM.YYYY')
            group by t.nazov, t.id_tovar, t.cena
            ORDER BY pocetBest DESC" , $od, $doo);  
        }
    }

    public function tovarExists($id) {
        return $this->connection->query('
             select t.doplnkovy_tovar, t.id_tovar, t.nazov, t.cena, t.na_predpis,
            t.doplatok, t.popis , t.aktivny, t.pocet, t.drzitel, t.mnozstvo,
            l.id_skupina, o.id_ucinna, t.min_pocet, t.id_forma, t.uzitie
            from tovar t LEFT JOIN liek l ON(l.id_tovar = t.id_tovar) 
            LEFT JOIN obsah_latok o on (l.id_liek = o.id_liek)
             where t.id_tovar = ?', $id)->fetch();
    }

    public function tovarUpdate($data, $id){
        return $this->connection->table('tovar')->where('id_tovar',$id)->update($data);
    }
    
    public function deleteTovar($id){
        return $this->connection->query('update tovar set aktivny = ? where id_tovar = ?', false, $id);
    }
}
