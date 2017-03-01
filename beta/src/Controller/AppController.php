<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\I18n\I18n;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    use \Crud\Controller\ControllerTrait;
    
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
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
        $this->loadComponent('Auth', [
            'loginAction' => [
            'controller' => 'Users',
            'action' => 'login',
            ],
            //'authError' => false,
            'flash' => [
            'key' => 'auth',
            'params' => [
            'class' => 'alert alert-success alert-dismissible'
            ]
            ],
            'authError' => 'Did you really think you are allowed to see that?',
            'authenticate' => [
            'Form' => [
            'fields' => ['username' => 'email'],
            'finder' => 'authUser'
            ]
            ],
            'storage' => 'Session'
            ]);
        $this->loadComponent('Crud.Crud', [
            'actions' => [
            'index' => [
            'className' => 'Crud.Index',
            ],
            'add' => [
            'className' => 'Crud.Add',
            'messages' => [
            'success' => [
            'text' => '{name} has been created successfully.',
            'params' => ['class' => 'alert alert-success alert-dismissible']
            ],
            'error' => [
            'text' => 'Could not create {name}',
            'params' => ['class' => 'alert alert-danger alert-dismissible']
            ]
            ],
            ],
            'edit' => [
            'className' => 'Crud.Edit',
            'messages' => [
            'success' => [
            'text' => '{name} has been updated successfully.',
            'params' => ['class' => 'alert alert-success alert-dismissible']
            ],
            'error' => [
            'text' => 'Could not updated {name}',
            'params' => ['class' => 'alert alert-danger alert-dismissible']
            ]
            ],
            ],
            'delete' => [
            'className' => 'Crud.Delete',
            'messages' => [
            'success' => [
            'text' => '{name} has been deleted successfully.',
            'params' => ['class' => 'alert alert-success alert-dismissible']
            ],
            'error' => [
            'text' => 'Could not deleted {name}',
            'params' => ['class' => 'alert alert-danger alert-dismissible']
            ]
            ],
            ],
            'view' => [
            'className' => 'Crud.View',
            'messages' => [
            'success' => [
            'text' => '{name} has been deleted successfully.',
            'params' => ['class' => 'alert alert-success alert-dismissible']
            ],
            'error' => [
            'text' => 'Could not deleted {name}',
            'params' => ['class' => 'alert alert-danger alert-dismissible']
            ]
            ],
            ],
            ]
            ]);

        $session = $this->request->session();

        $loggedInAs = $session->read('Auth.User');

        // For completing social users profile after social login
        if(!empty($loggedInAs)  && !(strtolower($this->request->params['controller']) == 'users' && strtolower($this->request->params['action']) == 'add' ) && $loggedInAs['role_id'] == 0){

            $this->Flash->set(__('Please complete your profile.'), [
                'params' => ['class' => 'alert alert-success alert-dismissible']
                ]);

            return $this->redirect(['controller' => 'users','action' => 'add']);
        }
        $this->loggedIn = $loggedInAs;

        $this->_setLanguage();
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event) {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
            ) {
            $this->set('_serialize', true);
    }
    $this->set('modelClass', $this->modelClass);
    $this->set(['title' => $this->modelClass]);

    $session = $this->request->session();

    $loggedInAs = $session->read('Auth.User');
    //pr($loggedInAs);die;
    $this->set(['loggedInAs' => $loggedInAs]);
    $this->set(['params' => $this->request->params]);
    //$this->set(['activeTab' => 'Default']);
}

public function send_mail($to, $template, $replace = NULL, $from = NULL) {
    $replace[] = '<img src="' . Configure::read('Site.Logo') . '" alt="' . Configure::read('Site.name') . '">';
    $replace[] = Configure::read('Site.copyright');
    $replace[] = Configure::read('Site.Website');

    if ($template) {
            // get email template
        $this->loadModel('EmailTemplates');
        $emailtemplate = $this->EmailTemplates->find('all', array('fields' => array('EmailTemplates.subject', 'EmailTemplates.from', 'EmailTemplates.message', 'EmailTemplates.fields'), 'conditions' => array('EmailTemplates.slug' => $template)))->first()->toArray();
        if ($emailtemplate) {
            if (!$from) {
                $from = $emailtemplate['from'];
            }
            $find = explode(',', $emailtemplate['fields']);
            $sub = str_replace($find, $replace, $emailtemplate['subject']);

            $template = str_replace($find, $replace, $emailtemplate['message']);

            $email = new Email('gmail');//mailgun
            $email->from(array($from => Configure::read('Site.name')));
            $email->to($to);
            $email->emailFormat('html');
            $email->subject(($sub) ? $sub : $emailtemplate['subject']);
            if ($email->send($template)) {
                return true;
            }
        }
    }

    return false;
}

function _setLanguage() {
    $session = $this->request->session();

    if ($this->Cookie->read('lang') && !$session->check('Config.language')) {
        $session->write('Config.language', $this->Cookie->read('lang'));
    }
    else if ($this->request->query('language') && ($this->request->query('language')
     !=  $session->read('Config.language'))) {

        $session->write('Config.language', $this->request->query('language'));
    $this->Cookie->write('lang', $this->params['language'], false, '20 days');
}
else if (!$this->Cookie->read('lang') && !$session->check('Config.language')) {
    $session->write('Config.language', Configure::read('Config.language'));
    $this->Cookie->write('lang', Configure::read('Config.language'), false, '20 days');
}

if($this->request->session()->check('Config.language')){
    I18n::locale($this->request->session()->read('Config.language'));
}
if($this->request->query('language')){
    $this->redirect($this->referer());
}



}

}
