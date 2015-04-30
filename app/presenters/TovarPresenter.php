<?php
namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;


class TovarPresenter extends BasePresenter
{
    /** @var Pharmacy\Tovar */
    private $tovar;

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct($database);
    }
    
    /** startup */
    protected function startup() {
        parent::startup();
        $this->tovar = Nette\Environment::getService('tovar');
    }
    

    public function renderShow($id_tovar)
    {  
        $this->template->tovar = $this->tovar->printByID($id_tovar);
    }
}