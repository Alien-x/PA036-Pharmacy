<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class TovarPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $tovar;
    
    private $sessionCart;

    /** startup */
    protected function startup() {
        parent::startup();
        // get model
        $this->tovar = $this->getModel('tovar');
        
        // get cart
        $this->sessionCart = $this->getSession('cartSection');
        if(!is_array($this->sessionCart->zbozi)) {
            $this->sessionCart->zbozi = array();
        }
    }

    public function renderDefault() {
        $post = $this->getHttpRequest()->getPost();

        $this->template->post = $post;
            
        $this->template->tovary = $this->tovar->printAll(
                isset($post['indikacna_skupina']),
                (isset($post['indikacna_skupina']) ? $post['indikacna_skupina'] : false)
            );
    }

    public function renderShow($id_tovar) {
        $this->template->tovar = $this->tovar->printByID($id_tovar);
    }

    public function renderNahrada($id_tovar, $id_ucinna) {
        if(!isset($id_ucinna) && isset($id_tovar)){
            $tovar = $this->tovar->printNahrada($id_tovar);
            $this->template->tovar = $tovar;
            $id_ucinna = $tovar['id_ucinna'];
        }
        
        
        $this->template->latka = $this->tovar->printUcinnaLatka($id_ucinna);
        $this->template->tovary = $this->tovar->printNahrady($id_ucinna);
    }
    
    public function renderNamarkovat($id_tovar) {
        
        $tovar = $this->tovar->printByID($id_tovar);
        
        $zbozi = array();
        $zbozi['id_tovar'] = $tovar->id_tovar;
        $zbozi['nazov'] = $tovar->nazov;
        $zbozi['cena'] = $tovar->cena;
        $zbozi['doplatok'] = $tovar->doplatok;
        $zbozi['odkaz'] = $this->link('Tovar:show', $id_tovar);
        $zbozi['odmarkovat'] = $this->link('Tovar:odmarkovat', $id_tovar);
        
        $this->sessionCart->zbozi[] = $zbozi;
        
        $this->redirect('Tovar:default');
    }
    
    public function renderOdmarkovat($id_tovar) {
        
        $zbozi = null;
        
        foreach($this->sessionCart->zbozi as $item) {
            if($item['id_tovar'] == $id_tovar) {
                $zbozi = $item;
            }
        }
        
        if(($key = array_search($zbozi, $this->sessionCart->zbozi)) !== false) {
            unset($this->sessionCart->zbozi[$key]);
        }
        
        $this->redirect('Tovar:default');
    }
    
    public function renderNevhodne ($id_tovar, $id_ucinna) {
        if(!isset($id_ucinna) && isset($id_tovar)){
            $tovar = $this->tovar->printNahrada($id_tovar);
            $this->template->tovar = $tovar;
            $id_ucinna = $tovar['id_ucinna'];
        }
        
        $this->template->latka = $this->tovar->printUcinnaLatka($id_ucinna);
        $this->template->tovary = $this->tovar->printZleKombinacie($id_ucinna);
    }
    
    /*
     * Components
     */
    
    protected function createComponentSkupinaForm() 
    {

        $skupina = $this->tovar->printIndikacneSkupiny();
        array_unshift($skupina, 'Doplnky');

        $form = new Form;
        $form->addSelect('indikacna_skupina', 'Skupina tovaru:')->setItems($skupina)->setPrompt('Vsetko')->getControlPrototype()->setClass('form-control');
        $form->addSubmit('choose', 'Ok')->getControlPrototype()->setClass('btn btn-primary');
        $form->onSuccess[] = array($this, 'skupinaFormSucceeded');
        return $form;
    }

    public function skupinaFormSucceeded($form, $values) 
    {
        
    }

    protected function createComponentCartControl()
    {
        $cart = new \App\Components\CartControl($this->sessionCart);
        $cart->redrawControl();
        return $cart;
    }

}
