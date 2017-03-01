<div class="login-section">
    <div class="logo-canter">
        <img src="<?= $this->request->webroot; ?>img/splash-logo.png" alt="" />
    </div>
    <div class="bottom-shadow">
        <img src="<?= $this->request->webroot; ?>img/logo-bottom-shadow.png" alt="" />
    </div>
    <?php if (Cake\Core\Configure::read('Site.Registration.SocialLogin')) { ?> 
        <div class="social-btns">
            <ul>
                <li><a href="javascript:void(0)" ng-click="authenticate('facebook')"  class="fb-btn" ><i class="fa fa-facebook"></i> <span>facebook</span></a></li>
                <li><a href="javascript:void(0)" ng-click="authenticate('twitter')" class="tweet-btn"><i class="fa fa-twitter"></i><span> Twitter</span></a></li>
                <li><a href="javascript:void(0)" ng-click="authenticate('google')" class="gplus-btn"><i class="fa fa-google-plus"></i> <span>Google+</span></a></li>
            </ul>
        </div>
    <?php } ?>
    <div class="login-area">
        <?php if (Cake\Core\Configure::read('Site.Registration.SocialLogin')) { ?> 
            <p><?php echo __('Or Login With');?></p>
        <?php } ?>
        <?= $this->Flash->render('auth') ?>
        <?= $this->Flash->render() ?>
        <?php
        echo $this->Form->create('User', [
            'novalidate' => true,
        ]);

        echo $this->Form->input('email', [
            'type' => 'text',
            'class' => 'text',
            'label' => false,
            'placeholder' => 'Email Address',
            'templates' => [
                'input' => '<input type="{{type}}" name="{{name}}" {{attrs}}/>',
                'inputContainer' => '<div class="input-field {{type}}{{required}}">{{content}}</div>',
                'inputContainerError' => '<div class="input-field input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
            ],
        ]);

        echo $this->Form->input('password', [
            'label' => false,
            'placeholder' => 'Password',
            'templates' => [
                'input' => '<input type="{{type}}" name="{{name}}" {{attrs}}/>',
                'inputContainer' => '<div class="input-field {{type}}{{required}}">{{content}}</div>',
                'inputContainerError' => '<div class="input-field input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
            ],
        ]);
        ?>

        <p class="m-t-0-b-15"><?php echo __('By Creating an account, you agree to our');?> <?php echo $this->Html->link('Terms & Conditions',['controller' => 'pages','action' => 'view', 'term-and-conditions'],['target'=> '_blank']);?></p>
        <div class="input-field">
            <div class="action-btn ">
                <input type="submit" value="login" class="blue-btn">
            </div>
            <div class="action-btn">
                <?php echo $this->Html->link('Register',['controller' => 'users','action' => 'add'],['class' => 'gray-btn']);?>
                
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>

<?= $this->element('copyright'); ?>
