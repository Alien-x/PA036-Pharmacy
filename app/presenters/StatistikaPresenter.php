<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form;


class StatistikaPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $lekarnik;
    
    /** startup */
    protected function startup() {
        parent::startup();
        // get model
        $this->lekarnik = $this->getModel('lekarnik');
    }
  
    public function renderDefault(){
    
        $this->template->lekarnikci = $this->lekarnik->printLekarniciFaktury();
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
        $this->redirect('Statistika:edit', $values->od, $values->doo);
    }

    public function renderEdit($od,$doo){
        
        $this->template->lekarnikci = $this->lekarnik->printLekarniciFaktury($od, $doo);
    }


}
