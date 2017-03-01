<?php

namespace App\Controller\Admin;

use App\Controller\AppController as BaseController;
use Cake\Event\Event;
use Cake\I18n\I18n;

class AppController extends BaseController {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();
        I18n::locale('en_US');
        $this->Auth->__set('sessionKey', 'Auth.Admin');

        $this->Auth->config([
            'loginRedirect' => [
                'controller' => 'dashboard',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
            ],
            'storage' => [
                'className' => 'Session',
                'key' => 'Auth.Admin',
            ],
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email'],
                    'finder' => 'authAdmin'
                ]
            ],
        ]);
        
        $this->loggedIn = $this->_getSession();
    }

    public function beforeRender(Event $event) {
        
        $session = $this->request->session();
        $loggedInAs = $session->read('Auth.Admin');
        $this->set(['title' => $this->modelClass]);
        $this->set(['status' => [1=>'Active','0' => 'Inactive']]);
        $this->set(['loggedInAs' => $loggedInAs]);
        $this->set(['params' => $this->request->params]);
        $this->set(['loggedIn' => $this->loggedIn]);
        parent::beforeRender($event);
        //$this->viewBuilder()->layout('admin');
    }
    
    private function _getSession(){
        return $this->request->session()->read('Auth.Admin');
    }

}
