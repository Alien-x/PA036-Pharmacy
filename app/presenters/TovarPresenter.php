<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class TovarPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $tovar;
    private $db;

    public function __construct(Nette\Database\Context $database) {
        $this->db = $database;
    }

    /** startup */
    protected function startup() {
        parent::startup();
        $this->tovar = $this->getModel('tovar');
    }

    public function renderDefault() {
        $post = $this->getHttpRequest()->getPost();

        $this->template->post = $post;
        if (isset($post['indikacna_skupina']) && $post['indikacna_skupina'] != null && $post['indikacna_skupina'] != 0)
            $this->template->tovary = $this->db->query('select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)
                where id_skupina = ?', $post['indikacna_skupina']);
        else if (isset($post['indikacna_skupina']) && $post['indikacna_skupina'] == 0)
            $this->template->tovary = $this->db->query("select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)
                where t.doplnkovy_tovar = true");
        else
            $this->template->tovary = $this->db->query('select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek)');
    }

    public function renderShow($id_tovar) {
        $this->template->tovar = $this->db->query('select  t.id_tovar, t.nazov, t.cena, t.na_predpis, t.doplatok, t.popis, t.doplnkovy_tovar, t.aktivny,
 t.pocet, t.drzitel, i.nazov as skupina_nazov, ol.id_ucinna, ul.popis as ucinna_nazov, t.mnozstvo, t.uzitie, m.nazov as jednotka
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON (ol.id_liek = l.id_liek)
		JOIN mnozstvo_forma m ON (m.id_forma = t.id_forma)
		JOIN indikacna_skupina i ON(i.id_skupina = l.id_skupina)
		JOIN ucinna_latka ul ON(ul.id_ucinna =ol.id_ucinna)
		where  t.id_tovar = ?', $id_tovar)->fetch();
    }

    protected function createComponentSkupinaForm() {

        $skupina = $this->db->table('indikacna_skupina')->fetchPairs('id_skupina', 'nazov');
        array_unshift($skupina, 'Doplnky');

        $form = new Form;
        $form->addSelect('indikacna_skupina', 'Skupina tovaru:')->setItems($skupina)->setPrompt('Vsetko')->getControlPrototype()->setClass('form-control');
        $form->addSubmit('choose', 'Ok')->getControlPrototype()->setClass('btn btn-primary');
        $form->onSuccess[] = array($this, 'skupinaFormSucceeded');
        return $form;
    }

    public function skupinaFormSucceeded($form, $values) {
        
    }

    public function renderNahrada($id_tovar, $id_ucinna) {
        if(!isset($id_ucinna) && isset($id_tovar)){
        $tovar = $this->db->query('select t.nazov, t. id_tovar, t.cena, o.id_ucinna, u.popis from tovar t join liek l on(t.id_tovar = l.id_liek) join obsah_latok o on (o.id_liek = l.id_liek) join ucinna_latka u on (u.id_ucinna = o.id_ucinna) where t.id_tovar=?', $id_tovar)->fetch();
        $this->template->tovar = $tovar;
        $id_ucinna = $tovar['id_ucinna'];
        }
        
        
        $this->template->latka = $this->db->query('select * from ucinna_latka where id_ucinna = ?',$id_ucinna)->fetch();
        $this->template->tovary = $this->db->query('select t.id_tovar,t.nazov, t.cena, t.na_predpis, t.doplatok, t.popis, t.doplnkovy_tovar, t.aktivny, t.pocet, t.drzitel, id_skupina , ol.id_ucinna 
                from tovar t 
                LEFT OUTER JOIN liek l ON(l.id_tovar = t.id_tovar) 
                JOIN obsah_latok ol ON(ol.id_liek = l.id_liek) where ol.id_ucinna = ?', $id_ucinna);
    }

}
