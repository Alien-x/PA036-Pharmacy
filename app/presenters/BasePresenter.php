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
    
    /*
    protected function createComponentLoginForm()
    {
        $form = new UI\Form;

        $form->addText('login', 'Login:')
            ->setRequired();
        
        $form->addPassword('password', 'Heslo:')
            ->setRequired();

        $form->addSubmit('send', 'Přihlásit se');

        $form->onSuccess[] = array($this, 'loginFormSend');
        
        return $form;
    }
    
    public function commentFormSucceeded($form, $values)
    {
        $postId = $this->getParameter('postId');

        $this->database->table('comments')->insert(array(
            'post_id' => $postId,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ));

        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }
    */
}
