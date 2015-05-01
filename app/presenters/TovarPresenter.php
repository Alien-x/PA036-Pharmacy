<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;

class TovarPresenter extends BasePresenter {

    /** @var Pharmacy\Tovar */
    private $tovar;
    private $db;


    public function __construct(Nette\Database\Context $database) {
        $this->db = $database;


    }

    /** startup */
    protected function startup() {
        parent::startup();
        $this->tovar = $this->getModel('tovar');
    }

    public function renderDefault() {
        $this->template->tovary = $this->tovar->printAll();
    }

    public function renderShow($id_tovar) {
        $this->template->tovar = $this->tovar->printByID($id_tovar);
    }

    protected function createComponentSkupinaForm() {
        $skupina = $this->db->table('indikacna_skupina')->fetchPairs('id_skupina', 'nazov');
        $skupina[] = ('Doplnky');
        $form = new Form;
        $form->addSelect('indikacna_skupina', 'Skupina tovaru:')->setItems($skupina)->setPrompt('Vsetko');
        $form->addSubmit('choose', 'Ok')->getControlPrototype()->setClass('btn btn-primary');
        $form->onSuccess[] = array($this, 'skupinaFormSucceeded');
        return $form;
    }

    public function skupinaFormSucceeded($form, $values) {
       $post = $this->getHttpRequest()->getPost();
       $this->template->post = $post;
       //if(isset($post['indikacna_skupina']))
          // $this->template->tovary = $this->tovar->
    }

}
