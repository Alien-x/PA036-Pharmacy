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
    public function renderAddLiek() {
        
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
    
    protected function createComponentInsertLiekForm(){
        $liek = $this->tovar->printForma();
        $form = new Form;
        $form->addText('sn', 'Serial Number: ')->setRequired();
        $form->addText('expire', 'Expiracia: ')->setRequired();
        $form->addSelect('liek', 'Liek: ')->setItems($liek)->setPrompt('Vyberte jednu')->setRequired();
        
        $form->addSubmit('vlozit', 'Vložiť');
        $form->addSubmit('insert', 'Pridaj')->getControlPrototype()->setClass('form-control btn btn-primary');
        
        return $form;
    }
    
    public function insertLiekFormSucceeded($form, $values) {
    
    }
    
    public function actionEdit($postId){
        //to-do, podla tutorialu spravit update, spravit selekt na vsetky potrebne hodnoty, alebo pouzit, uz vytvoreny..
     }

}
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


        $form->addSubmit('insert', 'OK')->getControlPrototype()->setClass('form-control btn btn-primary');
        $form->onSuccess[] = array($this, 'insertFormSucceeded');
        return $form;
    }

    public function insertFormSucceeded($form, $values) {
        //musime z formulara odfiltrovat atributy pre tabulku liek a spravit zvlast insert
        $tovar = $values;
        unset($tovar['je_liek']);
        unset($tovar['id_skupina']);
        unset($tovar['id_ucinna']);
        $id_tovar = $this->getParameter('id_tovar');
        
        if ($id_tovar) {
            $this->tovar->tovarUpdate($tovar, $id_tovar);
            //$this->tovar->insertLiek($id_tovar, $values['id_skupina'], $values['id_ucinna']);
        } else {
            $id_tovar = $this->tovar->insertTovar($tovar);
            if (isset($values['je_liek'])) {
                $this->tovar->insertLiek($id_tovar, $values['id_skupina'], $values['id_ucinna']);
            }
        }

        $this->flashMessage("Tovar " . $id_tovar . " '" . $values['nazov'] . "' bol úspešne spracovaný.", 'success');
        $this->redirect('default');
    }

    public function actionEdit($id_tovar) {
        $tovar = $this->tovar->tovarExists($id_tovar);
        $this->template->values = $tovar;
        if (!$tovar)
            $this->error('Nieje možné nájsť požadovaný tovar');

        if (isset($tovar['id_ucinna']))
            $tovar['je_liek'] = true;

        $this['insertForm']->setDefaults($tovar);
    }

}
