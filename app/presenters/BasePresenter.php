<?php

namespace App\Presenters;

use Nette,
    App\Model,
    Nette\Application\UI;

/**
 * Base presenter for all application presenters.
 */
class BasePresenter extends Nette\Application\UI\Presenter {
    
    private $role_spravca = false;
    private $role_predavac = false;
    private $role_magister = false;
    
    public function isUserSpravca() {
        return $this->role_spravca;
    }
    
    public function isUserPredavac() {
        return $this->role_predavac;
    }
    
    public function isUserMagister() {
        return $this->role_magister;
    }
    
    public function startup() {
        parent::startup();
        
        // role
        switch($this->getUser()->getRoles()[0]) {

            // spravca - ma pravo upravovat polozky v sklade tovaru
            case 1: {
                $this->role_spravca = true;
            } break;

            // predavac - moze predavat produkty, ktore niesu na predpis
            case 2: {
                $this->role_predavac = true;
            } break;   

            // magister - moze predavat produkty, ktore su aj na predpis
            case 3: {
                $this->role_predavac = true;
                $this->role_magister = true;
            } break;

            // magister_admin - moze predavat produkty, ktore su na predpis, rovnako moze zasahovat do skladu
            case 4: {
                $this->role_spravca = true;
                $this->role_predavac = true;
                $this->role_magister = true;
            } break;
        }
    }
    
    protected function getModel($serviceName) {
        return Nette\Environment::getService($serviceName);
    }
    
    public function beforeRender() {
        
        $this->template->role_spravca = $this->role_spravca;
        $this->template->role_predavac = $this->role_predavac;
        $this->template->role_magister = $this->role_magister;
    }
}
