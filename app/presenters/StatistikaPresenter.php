<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form;


class StatistikaPresenter extends BasePresenter {

    private $database;
    private $values;
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
  
public function renderDefault(){
    
    $this->template->lekarnikci = $this->database->query("select meno,priezvisko,titul, count(*) as pocet from lekarnik l,faktura f,faktura_polozka fp
    where l.id_lekarnik = f.id_lekarnik and f.id_faktura= fp.id_faktura
    group by meno,priezvisko,titul
    order by pocet;");
    
    }
    
    protected function createComponentStatForm()
{
    $form = new Form;
    $form->addText('od', 'Od dátumu:')
        ->setRequired();
    $form->addText('doo', 'Po dátum:')
        ->setRequired();
   

    $form->addSubmit('send', 'Uložit a publikovat');
    
    $form->onSuccess[] = array($this, 'volajEdit');

    return $form;
}

public function volajEdit($form){
    $values = $form->getValues();
    $this->redirect('Statistika:edit',  $values->od,$values->doo);
}

public function renderEdit($od,$doo){
    
    
    $this->template->lekarnikci = $this->database->query("select meno,priezvisko,titul, count(*) as pocet from lekarnik l,faktura f,faktura_polozka fp
    where l.id_lekarnik = f.id_lekarnik and f.id_faktura= fp.id_faktura and  cas_vystavenia > to_date(?, 'DD.MM.YYYY') and cas_vystavenia < to_date(?, 'DD.MM.YYYY')
group by meno,priezvisko,titul;",$od,$doo);
    
}


}
