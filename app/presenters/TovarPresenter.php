<?php
namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form;


class TovarPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct($database);
    }

    public function renderShow($id_tovar)
    {  
        $tovar = $this->database->table('tovar')->get($id_tovar);
        if (!$tovar) {
            $this->error('Stránka nebyla nalezena');
        }
        
        $this->template->tovar = $tovar;
    }
}