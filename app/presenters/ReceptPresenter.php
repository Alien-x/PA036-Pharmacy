<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class ReceptPresenter extends BaseCartPresenter {

    /** @var Pharmacy\Recept */
    private $recept;
    
    private $id_recept; // when redirecting to add recept
    private $cartItemID; // to copy info form (hidden)

    /** startup */
    public function startup() {
        parent::startup();
        
        // get model
        $this->recept = $this->getModel('recept');
        
        // user access
        if(!$this->isUserPredavac()) {
            throw new Nette\Application\ForbiddenRequestException;
        }
    }

    public function renderDefault() {
        $template = $this->template;
        
        // template
        $template->recepty = $this->recept->printAll();
    }
    
    public function renderPriradit($cartItemID) {
        
        $this->cartItemID = $cartItemID;
    }
    
    public function renderAdd($id_recept, $cartItemID) {
        $template = $this->template;
        
        $this->id_recept = $id_recept;
        $this->cartItemID = $cartItemID;
        
        // template
        $template->id_recept = $id_recept;
    }
    
     protected function createComponentReceptPriraditForm() 
    {
        $form = new Form;
        
        $form->addHidden('cartItemID', $this->cartItemID);
        $form->addText('id_recept', 'ID receptu:')
                ->setRequired('Zadejte prosím sériové číslo')
                ->addRule(Form::MAX_LENGTH, 'Sériové číslo může mít maximálně %d znaků', 9);
        $form->addSubmit('choose', 'Ok')->getControlPrototype()->setClass('btn btn-primary');
        $form->onSuccess[] = array($this, 'receptPriraditFormSucceeded');
        
        return $form;
    }

    public function receptPriraditFormSucceeded($form, $values) 
    {
        // recept found
        if($this->recept->receptExists($values['id_recept'])) {
            
            // prekrocen pocet 2 tovaru na recept
            if($this->recept->getPocetVydanychTovaru($values['id_recept']) + $this->getCountZboziWithRecept($values['id_recept']) >= 2) {
                
                $this->flashMessage('Recept #'.$values['id_recept'].' nelze přiřadit - byl překročen maximální počet dvou léků na tento recept.' , 'info');
                $this->redirect('Recept:priradit', $values['cartItemID']);
            }
            // ok
            else {
                $this->assignReceptToZboziInCart($values['cartItemID'], $values['id_recept']);
                $this->setUhradaToZboziInCart($values['cartItemID'], $this->recept->getZpusobHrazeni($values['id_recept']));
                $this->redirect('Namarkovane:default');
            }
        }
        // new recept
        else {
            $this->redirect('Recept:add', $values['id_recept'], $values['cartItemID']);
        }
    }
    
    
    protected function createComponentReceptAddForm() 
    {
        $form = new Form;
        
        $form->addHidden('cartItemID', $this->cartItemID);
        $form->addHidden('id_recept', $this->id_recept);
        
        $form->addText('rodne_cislo', 'Rodné číslo:')
                ->setRequired('Zadejte prosím rodné číslo')
                ->addRule(Form::LENGTH, 'Rodné číslo musí mít %d znaků', 10);
        
        $form->addText('dat_vydania', 'Datum vydání \'YYYY-MM-DD\':')
                ->setRequired('Zadejte prosím datum vydání receptu');
        
        $form->addText('pacient_meno', 'Jméno pacienta:')
                ->setRequired('Zadejte prosím jméno pacienta')
                ->addRule(Form::MAX_LENGTH, 'Jméno pacienta může mít maximálně %d znaků', 50);
        
        $form->addText('pacient_priezvisko', 'Příjmení pacienta:')
                ->setRequired('Zadejte prosím příjmení pacienta')
                ->addRule(Form::MAX_LENGTH, 'Příjmení pacienta může mít maximálně %d znaků', 50);
        
        $form->addText('pacient_adresa', 'Adresa pacienta:')
                ->setRequired('Zadejte prosím adresu pacienta')
                ->addRule(Form::MAX_LENGTH, 'Adresa pacienta může mít maximálně %d znaků', 200);
        
        $uhrada = array(
            'I' => 'I - hrazený ze zdravotního pojištění',
            'C' => 'C - částečně hrazený ze zdravotního pojištění',
            'P' => 'P - plně hrazený pacientem'
        );

        $form->addSelect('sposob_uhrady', 'Způsob úhrady:', $uhrada)
            ->setPrompt('Zvolte způsob úhrady');
        
        $form->addTextArea('uzitie', 'Užití:')
            ->addRule(Form::MAX_LENGTH, 'Užití je příliš dlouhé (max %d znaků)', 1000);
        
        $form->addText('vydal', 'Vydal:')
                ->setRequired('Zadejte prosím kdo vydal recept');
        
        
        $form->addSubmit('choose', 'Přidat recept')->getControlPrototype()->setClass('btn btn-success');
        $form->onSuccess[] = array($this, 'receptAddFormSucceeded');
        
        return $form;
    }

    
    public function receptAddFormSucceeded($form, $values) 
    {
        
        $this->recept->insert(array (
            'id_recept' => $values['id_recept'],
            'rodne_cislo' => $values['rodne_cislo'],
            'dat_vydania' => $values['dat_vydania'],
            'pacient_meno' => $values['pacient_meno'],
            'pacient_priezvisko' => $values['pacient_priezvisko'],
            'pacient_adresa' => $values['pacient_priezvisko'],
            'sposob_uhrady' => $values['sposob_uhrady'],
            'uzitie' => $values['uzitie'],
            'vydal' => $values['vydal']
        ));
        
        // change tovar in session
        $this->assignReceptToZboziInCart($values['cartItemID'], $values['id_recept']);
        $this->setUhradaToZboziInCart($values['cartItemID'], $values['sposob_uhrady']);
        
        // redirect
        $this->redirect('Namarkovane:default');
    }
}
