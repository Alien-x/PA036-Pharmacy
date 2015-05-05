<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class NamarkovanePresenter extends BaseCartPresenter {

  
    public function renderDefault() {
        $template = $this->template;
        
        // template
        $template->zbozi = $this->getCartZbozi();

    }
    
    public function renderOdmarkovat($itemID) {
        
        $this->removeZboziFromCart($itemID);
        
        $this->redirect('Namarkovane:default');
    }
    


}
