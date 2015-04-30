<?php

namespace App\Presenters;

use Nette,
    App\Forms\SignFormFactory,
    Nette\Application\UI\Form;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter {

    /** @var SignFormFactory @inject */
    public $factory;
    public $form;
    
    public function __construct(Nette\Database\Context $database) {
        parent::__construct($database);
        
    }

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSignInForm() {
        $form = new Form();
        $form->addText('username', 'Uživatelské jméno:')
                ->setRequired('Prosím vyplňte své uživatelské jméno.')->getControlPrototype()->setClass('form-control');

        $form->addPassword('password', 'Heslo:')
                ->setRequired('Prosím vyplňte své heslo.')->getControlPrototype()->setClass('form-control');


        $form->addSubmit('send', 'Přihlásit')->getControlPrototype()->setClass('btn btn-primary');
  
        $this->form = $form;
        $this->form->onSuccess[] = array($this, 'signInFormSucceeded');
        return $this->form;
    }

    public function signInFormSucceeded($form) {
        $values = $form->values;

        try {
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }

    public function actionOut() {
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Homepage:');
    }

}
