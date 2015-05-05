<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class NamarkovanePresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $sessionCart;

    /** startup */
    protected function startup() {
        parent::startup();
        
        // get cart
        $this->sessionCart = $this->getSession('cartSection');
        if(!is_array($this->sessionCart->zbozi)) {
            $this->sessionCart->zbozi = array();
        }
    }

    public function renderDefault() {
        $template = $this->template;
        
        // template
        $template->zbozi = $this->sessionCart->zbozi;

    }


}
