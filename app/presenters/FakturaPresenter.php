<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI\Form;


class FakturaPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $faktura;
    
    /** startup */
    protected function startup() {
        parent::startup();
        // get model
        $this->faktura = $this->getModel('faktura');
    }
  
    public function renderDefault(){
    
        $this->template->faktury = $this->faktura->printAll();
    }
    
    public function renderShow($id_faktura){
        $this->template->faktury = $this->faktura->printFaktura($id_faktura);
        $this->template->id = $id_faktura;
    }
    


}
