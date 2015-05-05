<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form,
    Nette\Forms\Validator;

class SkladPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $tovar;

    /** startup */
    protected function startup() {
        parent::startup();
        $this->tovar = $this->getModel('tovar');
    }

    public function renderDefault() {
        $this->template->tovary = $this->tovar->printAll(null, null);
    }

    public function renderAdd() {
        
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
        $form->addText('cena', 'Cena: ')->addRule(Form::FLOAT);
        $form->addCheckbox('na_predpis', 'Na predpis: ');
        $form->addText('doplatok', 'Doplatok: ')->addConditionOn($form['na_predpis'], Form::EQUAL, TRUE)->setRequired('Vyberte jednu zo skupín !');
        $form->addText('popis', 'Popis: ')->setRequired();
        $form->addText('min_pocet', 'Minimálny počet: ')->setRequired();
        $form->addCheckbox('doplnkovy_tovar', 'Doplnkový tovar: ');
        $form->addCheckbox('aktivny', 'Aktívny: ');
        $form->addSelect('id_forma', 'Forma tovaru: ')->setItems($mnozstvo_forma)->setPrompt('Vyberte jednu')->setRequired();
        $form->addText('mnozstvo', 'Množstvo: ')->addRule(Form::FLOAT);
        $form->addText('drzitel', 'Držitel: ')->setRequired();
        $form->addText('uzitie', 'Užitie: ')->setRequired();
        $form->addText('pocet', 'Počet: ')->addRule(Form::INTEGER);
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
        //musime z formulara odfiltrovat atributy pre tabulku liek a spravit zvlast insert
        $liek = array();
        array_push($liek, $values['id_skupina'], $values['id_ucinna']);

        unset($values['je_liek']);
        unset($values['id_skupina']);
        unset($values['id_ucinna']);

        $this->tovar->insertTovar($values);
        $this->tovar->insertLiek($liek);


        $this->flashMessage("Tovar <b>".$values['nazov']."</b> bol úspešne vložený.", 'success');
        $this->redirect('default');
    }

}
