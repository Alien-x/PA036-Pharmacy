<?php
namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;


class TovaryPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct($database);
    }

    public function renderDefault()
    {  
        $this->template->tovary = $this->database->table('tovar');
    }
}