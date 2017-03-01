<?php
echo '<script type="text/javascript">';
echo 'var userInfo = ' . json_encode($this->request->data) . ';';
echo '</script>';
?>
<div class="login-section" ng-controller="profileController">
    <div class="logo-canter">
        <img src="<?= $this->request->webroot; ?>img/splash-logo.png" alt="" />
    </div>
    <div class="bottom-shadow regiser">
        <img src="<?= $this->request->webroot; ?>img/logo-bottom-shadow.png" alt="" />
    </div>
    <?php if (Cake\Core\Configure::read('Site.Registration.SocialLogin')) { ?> ?>
    <div class="social-btns">
        <ul>
            <li><a href="javascript:void(0)" ng-click="authenticate('facebook')"  class="fb-btn"><i class="fa fa-facebook"></i> <span>Facebook</span></a></li>
            <li><a href="javascript:void(0)" ng-click="authenticate('twitter')" class="tweet-btn"><i class="fa fa-twitter"></i><span> Twitter</span></a></li>
            <li><a href="javascript:void(0)" ng-click="authenticate('google')" class="gplus-btn"><i class="fa fa-google-plus"></i> <span>Google+</span></a></li>
        </ul>
    </div>
    <?php } ?>
    <div class="login-area">
        <p>Or Sign up With</p>
        <?= $this->Flash->render() ?>
        <?php
        $this->Form->templates([
            'nestingLabel' => '<li><label{{attrs}}>{{text}}</label>{{input}}<div class="check"></div></li>',
        ]);

        echo $this->Form->create($user, [
            'novalidate' => true,
            'type' => 'file',
            'ng-submit' => 'profileForm();'
        ]);

        echo $this->Form->input('image', [
            'type' => 'file',
            'id' => 'file',
            'class' => 'files',
            'label' => false,
            'templates' => [
                'input' => '<div class="custom-file-upload"><input type="{{type}}" name="{{name}}" class="hide files"/></div>',
                'inputContainer' => '<div class="input-field-file {{type}}{{required}}">{{content}}</div>',
                'inputContainerError' => '<div class="input-field-file {{type}}{{required}} has-error">{{content}}{{error}}</div>',
            ],
        ]);

        echo $this->Form->input('avatar_id', [
            'type' => 'text',
            'id' => 'avatar_id',
            'ng-model' => 'Users.avatar_id',
            'class' => 'hide',
            'label' => false
        ]);
        ?>


        <div class="input-field-popup">
            <label for="choose-avatar" >
                <div class="selectedAvatar" ng-bind-html="trustAsHtml(avatars[Users.avatar_id]['avatar'])"></div>
                Choose the Avatar
            </label>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Browse</button>
        </div>

        <div class="input-field-first row">
            <?php
            echo $this->Form->input('first_name', [
                'label' => false,
                'class' => false,
                'placeholder' => 'First Name',
                'templates' => [
                    'input' => '<input type="{{type}}" name="{{name}}"  {{attrs}}/>',
                    'inputContainer' => '<div class="col-md-6 {{type}}{{required}}">{{content}}</div>',
                    'inputContainerError' => '<div class="col-md-6  {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                ],
            ]);
            ?>
            <?php
            echo $this->Form->input('last_name', [
                'label' => false,
                'placeholder' => 'Last Name',
                'templates' => [
                    'input' => '<input type="{{type}}" name="{{name}}"   {{attrs}}/>',
                    'inputContainer' => '<div class="col-md-6 {{type}}{{required}}">{{content}}</div>',
                    'inputContainerError' => '<div class="col-md-6  {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                ],
            ]);
            ?>
        </div>
        <?php
        echo $this->Form->input('email', [
            'type' => 'text',
            'label' => false,
            'placeholder' => 'Email Address',
            'templates' => [
                'input' => '<input type="{{type}}" name="{{name}}" {{attrs}}/>',
                'inputContainer' => '<div class="input-field {{type}}{{required}}">{{content}}</div>',
                'inputContainerError' => '<div class="input-field input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
            ],
        ]);
        ?>
        <div class="input-field-first row">
            <?php
            echo $this->Form->input('password', [
                'label' => false,
                'class' => false,
                'placeholder' => 'Password',
                'templates' => [
                    'input' => '<input type="{{type}}" name="{{name}}"  {{attrs}}/>',
                    'inputContainer' => '<div class="col-md-6 {{type}}{{required}}">{{content}}</div>',
                    'inputContainerError' => '<div class="col-md-6  {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                ],
            ]);
            ?>
            <?php
            echo $this->Form->input('confirm_password', [
                'label' => false,
                'type' => 'password',
                'placeholder' => 'Confirm Password',
                'templates' => [
                    'input' => '<input type="{{type}}" name="{{name}}"   {{attrs}}/>',
                    'inputContainer' => '<div class="col-md-6 {{type}}{{required}}">{{content}}</div>',
                    'inputContainerError' => '<div class="col-md-6  {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                ],
            ]);
            ?>
        </div>
        <?php
        echo $this->Form->input('nick_name', [
            'type' => 'text',
            'label' => false,
            'placeholder' => 'Nick Name',
            'templates' => [
                'input' => '<input type="{{type}}" name="{{name}}"  {{attrs}}/>',
                'inputContainer' => '<div class="input-field {{type}}{{required}}">{{content}}</div>',
                'inputContainerError' => '<div class="input-field input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
            ],
        ]);

        echo $this->Form->input('country_id', [
            'label' => false,
            'empty' => 'Select Country',
            'templates' => [
                'select' => '<select name="{{name}}">{{content}}</select>',
                'inputContainer' => '<div class="input-field {{type}}{{required}}">{{content}}</div>',
                'inputContainerError' => '<div class="input-field input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
            ],
        ]);
        ?>

        <div class="input-field">

            <div class="select-input">
                <?php
                echo $this->Form->input('age', [
                    'label' => false,
                    'empty' => 'Age',
                    'options' => $user->ageList,
                    'templates' => [
                        'select' => '<select name="{{name}}">{{content}}</select>',
                    ],
                ]);
                ?>
            </div>
            <div class="radio-input">
                <ul class="clearfix">
                    <?php
                    echo $this->Form->radio(
                            'gender', [
                        ['value' => 'M', 'text' => $this->Html->image('male-icon.png') . ' Male', 'id' => 'f-option'],
                        ['value' => 'F', 'text' => $this->Html->image('female-icon.png') . ' Female', 'id' => 's-option'],
                            ], ['templates' => [ 'nestingLabel' => '<label{{attrs}}>{{text}}</label>{{input}}'], 'escape' => false]
                    );
                    ?>
                </ul>
                <?php echo $this->Form->error('gender'); ?>
                <?php /*
                  <ul>
                  <li>
                  <label for="f-option"><img src="<?= $this->request->webroot; ?>img/male-icon.png" alt="" /> Male</label>
                  <?php
                  echo $this->Form->radio(
                  'gender', [['value' => 'M', 'text' => '']], [
                  'id' => 'f-option',
                  'empty' => false,
                  'templates' => ['radioWrapper' => '{{}}']
                  ]
                  );
                  ?>
                  <div class="check"></div>
                  </li>

                  <li>
                  <label for="s-option"><img src="<?= $this->request->webroot; ?>img/female-icon.png" alt="" /> Female</label>
                  <?php
                  echo $this->Form->radio(
                  'gender', [
                  ['value' => 'F', 'text' => ''],
                  ], [ 'id' => 's-option', 'empty' => false, 'templates' => ['radioWrapper' => '{{div}}']]
                  );
                  ?>

                  <div class="check"><div class="inside"></div></div>
                  </li>

                  </ul>
                 * 
                 */ ?>
            </div>
        </div>

        <div class="input-field">
            <?php
            echo $this->Form->input('muslim_since', [
                'label' => false,
                'type' => 'date',
                'maxYear' => date('Y'),
                'minYear' => date('Y') - 100,
                'empty' => 'Muslim Since',
                'templates' => [
                    'select' => '<select name="{{name}}">{{content}}</select>',
                    'dateWidget' => '{{year}}',
                    'inputContainer' => '<div class="{{type}}{{required}}">{{content}}</div>',
                    'inputContainerError' => '<div class="input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                ],
            ]);
            ?>
        </div>

        <p class="m-t-0-b-15"><?php echo __('By Creating an account, you agree to our');?> 
        <?php echo __('By Creating an account, you agree to our');?> <?php echo $this->Html->link('Terms & Conditions',['controller' => 'pages','action' => 'view', 'term-and-conditions'],['target'=> '_blank']);?>
        </p>
        <div class="input-field">
            <div class="action-btn ">
                <input type="submit" value="<?= __('Enter');?>" class="blue-btn">
            </div>
            <div class="action-btn">
            <?php echo $this->Html->link(__('Cancel'),['controller' => 'users','action' => 'login'],['class' => 'gray-btn']);?>
                
            </div>
        </div>
    </div>
    <div class="modal fade avatar-popup" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <?= __('Choose the Avatar');?>
            </div>
            <div class="modal-body">
                <div class="img-section" id="avatars_list" style="max-height: 470px; overflow-x: hidden;overflow-y: auto">
                    <ul>
                        <li ng-repeat="avatar in avatars">
                            <div class="choose-img">
                                <div ng-bind-html="trustAsHtml(avatar.avatar)" ng-click="makeSelectedAvatar(avatar.id)"></div>
                                <div class="active" ng-if="avatar.id == Users.avatar_id">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default blue-btn popup-btn" data-dismiss="modal">Done</button>
            </div>
        </div>

    </div>
</div>
    <?php echo $cell = $this->cell('Layout::avatars'); ?>

</div>

<?= $this->element('copyright'); ?>


