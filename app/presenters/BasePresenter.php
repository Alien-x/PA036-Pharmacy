<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI;

/**
 * Base presenter for all application presenters.
 */
class BasePresenter extends Nette\Application\UI\Presenter {

    protected function getModel($serviceName) {
        return Nette\Environment::getService($serviceName);
    }
}
