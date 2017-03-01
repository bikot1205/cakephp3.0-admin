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

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

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
        $this->Auth->allow(['display', 'view', 'feedback']);
    }

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function display() {
        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if($page == 'home' && $this->loggedIn['id']){
           return $this->redirect(['controller' => 'users','action' => 'index']);
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }

        $this->viewBuilder()->layout('front');
    }

    public function view($slug) {
        
        $this->viewBuilder()->layout(false);
        $page = $this->Pages->find('slug', ['slug' => $slug]);
        if (empty($page)) {
            throw new NotFoundException('404 error.');
        }
        
        $this->set(compact('page'));
    }

    public function feedback() {
        $this->viewBuilder()->layout(false);
        $this->loadModel('Feedbacks');
        $feedback = $this->Feedbacks->newEntity();
        if ($this->request->is(['Post', 'Put'])) {
            $userEntity = $this->Feedbacks->patchEntity($feedback, $this->request->data);
            $userEntity->user_id = $this->loggedIn['id'];
            if ($result = $this->Feedbacks->save($userEntity)) {

                $this->Flash->set(__('Your feedback and suggestions has been posted successfully.'), [
                    'params' => ['class' => 'alert alert-success alert-dismissible']
                ]);

                return $this->redirect(['action' => 'feedback']);
            }
        }

        $this->set('feedback', $feedback);
    }

   

}
