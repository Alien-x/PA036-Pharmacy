<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form;


class StatistikaPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $lekarnik;
    private $tovar;
    /** startup */
    public function startup() {
        parent::startup();
        
        // get model
        $this->lekarnik = $this->getModel('lekarnik');
         $this->tovar = $this->getModel('tovar');
         
        // user access
        if(!$this->isUserSpravca()) {
            throw new Nette\Application\ForbiddenRequestException;
        }
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


        $form->addSubmit('send', 'Zobrazit');

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
    
    
    protected function createComponentStatFormBestsellers()
    {
        $form = new Form;
        $form->addText('od', 'Od dátumu:')
            ->setRequired();
        $form->addText('doo', 'Po dátum:')
            ->setRequired();


        $form->addSubmit('send', 'Zobrazit');

        $form->onSuccess[] = array($this, 'volajEditBestsellers');

        return $form;
    }
    
    public function volajEditBestsellers($form){
        
        $values = $form->getValues();
        $this->redirect('Statistika:editBestsellers', $values->od, $values->doo);
    }
    
    public function renderEditBestsellers($od,$doo){
        
        $this->template->tovary = $this->tovar->printBestsellers($od.$doo);
    }


}
