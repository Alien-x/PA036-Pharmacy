<?php

namespace App\Components;

use Nette\Application\UI\Control;

/**
 * The Fifteen game control
 */
class CartControl extends Control
{
    private $zbozi;
    
    public function __construct($zbozi)
    {
        parent::__construct();

        $this->zbozi = $zbozi;
    }
    
    public function render()
    {
        $template = $this->template;
        
        // odkazy
        $template->odkazNamarkovane = $this->getPresenter()->link('Namarkovane:default');
        
        // template
        $template->zbozi = $this->zbozi;
        $template->setFile(__DIR__ . '/CartControl.latte');

        $template->render();
    }


}
