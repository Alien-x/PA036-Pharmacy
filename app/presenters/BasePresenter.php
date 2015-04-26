<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI;


/**
 * Base presenter for all application presenters.
 */
class BasePresenter extends Nette\Application\UI\Presenter {

    /** @var Nette\Database\Context */
    protected $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
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
