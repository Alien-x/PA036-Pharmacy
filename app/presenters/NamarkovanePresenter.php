<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class NamarkovanePresenter extends BaseCartPresenter {

  
    public function renderDefault() {
        $template = $this->template;
        
        // template
        $template->zbozi = $this->getCartZbozi();
        
        // user access
        if(!$this->isUserPredavac()) {
            throw new Nette\Application\ForbiddenRequestException;
        }
    }
    
    public function renderOdmarkovat($itemID) {
        
        $this->removeZboziFromCart($itemID);
        
        $this->redirect('Namarkovane:default');
    }
    
    public function renderVyprazdnit() {
        
        $this->removeAllZboziFromCart();
        
        $this->redirect('Namarkovane:default');
    }
    


}
