<?php
namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;


class TovarPresenter extends BasePresenter
{
    /** @var Pharmacy\Tovar */
    private $tovar;
    
    /** startup */
    protected function startup() {
        parent::startup();
        $this->tovar = $this->getModel('tovar');
    }

    public function renderDefault()
    {  
        $this->template->tovary = $this->tovar->printAll();
    }
    
    public function renderShow($id_tovar)
    {
        $this->template->tovar = $this->tovar->printByID($id_tovar);
    }
}