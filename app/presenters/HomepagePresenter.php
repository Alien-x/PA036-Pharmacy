<?php

namespace App\Presenters;

use Nette,
    App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
        public function __construct(Nette\Database\Context $database)
        {
            parent::__construct($database);
        }
        
	public function renderDefault()
	{
            $this->template->tovary = $this->database->table('tovar')
                ->limit('10');
	}

}
