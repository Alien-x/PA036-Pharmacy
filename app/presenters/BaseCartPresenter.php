<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI;

/**
 * Base presenter for all application presenters.
 */
class BaseCartPresenter extends BasePresenter {

    private $sessionCart;
    
    /** startup */
    public function startup() {
        parent::startup();
        
        // get cart
        $this->sessionCart = $this->getSession('cartSection');
        
        // counter
        if(!isset($this->sessionCart->counter)) {
            $this->sessionCart->counter = 0;
        }
        // zbozi
        if(!is_array($this->sessionCart->zbozi)) {
            $this->sessionCart->zbozi = array();
        }
        
        // user access
        if(!$this->isUserPredavac()) {
            throw new Nette\Application\ForbiddenRequestException;
        }
    }
    
    protected function addZboziToCart($id_tovar, $nazov, $cena, $doplatok) {
        
        $this->sessionCart->counter += 1;
        $itemID = $this->sessionCart->counter;
        
        $zbozi = array();
        $zbozi['itemID'] = $itemID;
        $zbozi['id_tovar'] = $id_tovar;
        $zbozi['id_recept'] = null;
        $zbozi['nazov'] = $nazov;
        $zbozi['cena'] = $cena;
        $zbozi['doplatok'] = $doplatok;
        $zbozi['uhrada'] = 'P';
        $zbozi['odkaz'] = $this->link('Tovar:show', $id_tovar);
        $zbozi['odmarkovat'] = $this->link('Tovar:odmarkovat', $itemID);
        
        
        
        $this->sessionCart->zbozi[$itemID] = $zbozi;
    }
    
    protected function removeZboziFromCart($itemID) {
        
        unset($this->sessionCart->zbozi[$itemID]);
    }
    
    protected function removeAllZboziFromCart() {
        
        $this->sessionCart->zbozi = array();
    }
    
    protected function assignReceptToZboziInCart($itemID, $id_recept) {
        
        $this->sessionCart->zbozi[$itemID]['id_recept'] = $id_recept;
    }
    
    protected function setUhradaToZboziInCart($itemID, $uhrada) {
        
        $this->sessionCart->zbozi[$itemID]['uhrada'] = $uhrada;
    }
    
    protected function getCartZbozi() {
        
       return $this->sessionCart->zbozi;
    }
    
}
