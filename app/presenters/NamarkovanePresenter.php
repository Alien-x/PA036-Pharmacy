<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class NamarkovanePresenter extends BaseCartPresenter {

    /** @var Pharmacy\Faktura */
    private $faktura;
    
    /** startup */
    public function startup() {
        parent::startup();
        
        // get model
        $this->faktura = $this->getModel('faktura');
    }
    
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
    
    public function renderVytvoritFakturu() {
        
        // remove faktura_polozky from session
        $this->removeFakturaPolozky();
        
        // insert faktura to DB
        $id_faktura = $this->faktura->createFaktura($this->getUser()->getId());
        
        foreach ($this->getCartZbozi() as $zbozi) {
            
            // save to db
            $res = $this->faktura->addFakturaPolozka($id_faktura, $zbozi['id_tovar'], 1, $zbozi['uhrada']);
            // res = sn, nazov, expiracia, id_tovar, cena, sposob_uhrady, pocet
            //       0   1      2          3         4     5              6            
            
            //print_r($res);
            //echo (count($res).'<br>');
           
            $i_sn = 0;
            $i_nazov = 1;
            $i_expiracia = 2;
            $i_id_tovar = 3;
            $i_cena = 4;
            $i_sposob_uhrady = 5;
            $i_pocet = 6;
            
            // save to session
            $this->addFakturaPolozka($res[$i_id_tovar], $res[$i_nazov], $res[$i_sn], 
                $res[$i_expiracia], $res[$i_cena], $res[$i_sposob_uhrady], $res[$i_pocet]);
        }
        
        
        
        $this->template->fakturaID = $id_faktura;
        $this->template->cenaCelkem = $this->getFakturaCenaCelkem();
        $this->template->fakturaPolozky = $this->getFakturaPolozky();
    }
    
    public function renderOdmarkovatVse() {
        
        // remove zbozi from cart
        $this->removeAllZboziFromCart();
        
        $this->redirect('Tovar:default');
    }


}
