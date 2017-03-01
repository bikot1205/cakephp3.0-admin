<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
$this->layout = 'front';
?>
<meta http-equiv="refresh" content="1;url=<?php echo \Cake\Routing\Router::url(['controller' => 'users','action' => 'login'],true);?>" />

<div class="splash">
    <div class="splash-section">
        <div class="splash-logo">
            <img src="<?php echo $this->request->webrot; ?>img/splash-logo.png" alt="" />
        </div>
        
        <?php if (Cake\Core\Configure::read('Site.Registration.SocialLogin')) { ?> 
            <div class="splash-social">
                <ul>
                    <li><a href="javascript:void(0)" ng-click="authenticate('facebook')"  class="fb-btn-splash"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="javascript:void(0)" ng-click="authenticate('twitter')" class="tweet-btn-splash"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="javascript:void(0)" ng-click="authenticate('google')" class="gplus-btn-splash"><i class="fa fa-google-plus"></i></a></li>
                </ul>
            </div>
            <?php } else { ?>
            
            <?php } ?>
        </div>
    </div>
    