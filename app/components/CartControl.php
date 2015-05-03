<?php

namespace App\Components;

use Nette\Application\UI\Control;

/**
 * The Fifteen game control
 */
class CartControl extends Control
{
    /** @var Nette\Http\SessionSection */
    private $sessionSection;
    
    public function __construct(\Nette\Http\SessionSection $sessionSection)
    {
        parent::__construct();

        $this->sessionSection = $sessionSection;
    }

    public function render()
    {
        $template = $this->template;
        $template->zbozi = $this->sessionSection->zbozi;
        
        $template->setFile(__DIR__ . '/CartControl.latte');

        $template->render();
    }


}
