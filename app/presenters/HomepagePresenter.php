<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    public function renderDefault() {
        if (!$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Musíte sa prihlásiť !');
            $this->redirect('Sign:in');
        }
    }

}
