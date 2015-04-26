<?php

namespace App\Presenters;

use Nette,
    App\Forms\SignFormFactory;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{
	/** @var SignFormFactory @inject */
	public $factory;
        
        
        public function __construct(Nette\Database\Context $database)
        {
            parent::__construct($database);
        }
        
	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
            $form = new Form;
            $form->addText('username', 'Uživatelské jméno:')
                ->setRequired('Prosím vyplňte své uživatelské jméno.');

            $form->addPassword('password', 'Heslo:')
                ->setRequired('Prosím vyplňte své heslo.');

            $form->addSubmit('send', 'Přihlásit');

            $form->onSuccess[] = array($this, 'signInFormSucceeded');
            return $form;
	}

        public function signInFormSucceeded($form)
        {
            $values = $form->values;

            try {
                $this->getUser()->login($values->username, $values->password);
                $this->redirect('Homepage:');

            } catch (Nette\Security\AuthenticationException $e) {
                $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
            }
        }
        

	public function actionOut()
        {
            $this->getUser()->logout();
            $this->flashMessage('Odhlášení bylo úspěšné.');
            $this->redirect('Homepage:');
        }

}
