<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form, Nette\Forms\Validator;

class SkladPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $tovar;

    /** startup */
    protected function startup() {
        parent::startup();
        $this->tovar = $this->getModel('tovar');
    }

    public function renderDefault() {
        
    }

    protected function createComponentInsertForm() {

        $skupina = $this->tovar->printIndikacneSkupiny();
        $ucinne_latky = $this->tovar->printUcinna();
        $mnozstvo_forma = $this->tovar->printForma();
        //array_unshift($skupina, 'Doplnky');

        $form = new Form;
        //obecny formular
        //
        $form->addText('nazov', 'Názov: ')->setRequired();
        $form->addText('cena', 'Cena: ')->addRule(Form::FLOAT, "§§§§");
        $form->addCheckbox('na_predpis', 'Na predpis: ');
        $form->addText('doplatok', 'Doplatok: ')->addConditionOn($form['na_predpis'], Form::EQUAL, TRUE)->setRequired('Vyberte jednu zo skupín !');
        $form->addText('popis', 'Popis: ')->setRequired();
        $form->addText('min_pocet', 'Minimálny počet: ')->setRequired();
        $form->addCheckbox('doplnkovy_tovar', 'Doplnkový tovar: ');
        $form->addCheckbox('aktivny', 'Aktívny: ');
        $form->addSelect('id_forma', 'Forma tovaru: ')->setItems($mnozstvo_forma)->setPrompt('Vyberte jednu')->setRequired();
        $form->addText('mnozstvo', 'Množstvo: ')->setRequired();
        $form->addText('drzitel', 'Držitel: ')->setRequired();
        $form->addText('uzitie', 'Užitie: ')->setRequired();
        $form->addText('pocet', 'Počet: ')->setRequired();
        //formular pre liek
        $form->addCheckbox('je_liek', 'Je liek: ');
        $form->addSelect('id_skupina', 'Skupina tovaru:')->setItems($skupina)->setPrompt('Vyberte jednu')
                ->addConditionOn($form['je_liek'], Form::EQUAL, TRUE)->setRequired('Vyberte jednu zo skupín !');
        $form->addSelect('id_ucinna', 'Ucinna latka lieku: ')->setItems($ucinne_latky)->setPrompt('Vyberte jednu')
                ->addConditionOn($form['je_liek'], Form::EQUAL, TRUE)->setRequired('Vyberte jednu zo skupín !');


        $form->addSubmit('insert', 'Pridaj')->getControlPrototype()->setClass('form-control btn btn-primary');
        $form->onSuccess[] = array($this, 'insertFormSucceeded');
        return $form;
    }

    public function insertFormSucceeded($form, $values) {
        $this->template->values = $form->values;
        
    }

}
