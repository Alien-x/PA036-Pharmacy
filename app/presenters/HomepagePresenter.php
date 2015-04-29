<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    public function __construct(Nette\Database\Context $database) {
        parent::__construct($database);
        
    }

    public function renderDefault() {
        if (!$this->getUser()->isLoggedIn()) {
            //$this->error('Pro vytvoření, nebo editování příspěvku se musíte přihlásit.');
            $this->flashMessage('Musíte sa prihlásiť !');
            //$this->redirect('Homepage:');
        } else {
            $this->template->tovary = $this->database->table('tovar');
            // $this->database->
        }
    }

}
