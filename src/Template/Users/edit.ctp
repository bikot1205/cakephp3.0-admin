<div class="chat-section">
    <div class="top-header">
        <?= $this->element('search_users'); ?>
    </div>
    <div class="detail-area ">
        <div class="p-20">
            <div class="title" id="hide-filter">
                <?=  __('User Profile');?>
            </div>
            <?= $this->element('filters'); ?>

            <?php
            echo '<script type="text/javascript">';
            echo 'var userInfo = ' . json_encode($user) . ';';
            echo '</script>';
            $this->assign('title', __('User Profile'));
            $this->assign('page_title', __('User Profile'));

            ?>

            <div class="login-area content-area" ng-controller="profileController">
                <div id="os3" class="optiscroll column-container mid-50">
                    <div class="add-user-section">


                        <?php
                        $this->Form->templates([
                            'nestingLabel' => '<li><label{{attrs}}>{{text}}</label>{{input}}<div class="check"></div></li>',
                            ]);

                        echo $this->Form->create($user, [
                            'novalidate' => true,
                            'type' => 'file',
                            'ng-submit' => 'profileForm();'
                            ]);
                            ?>
                            <div class="form-area">
                                <div class="edit-user-photo">
                                    <?php echo $this->Awesome->userImage($user); ?>
                                </div>

                                <?php
                                
                                echo $this->Form->input('image', [
                                    'type' => 'file',
                                    'id' => 'file',
                                    'label' => false,
                                    'class' => 'files',
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
                                            <?= __('Choose the Avatar'); ?>
                                        </label>
                                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"><?= __('Browse');?></button>
                                    </div>

                                    <div class="input-field-first row">
                                        <?php
                                        echo $this->Form->input('first_name', [
                                            'label' => false,
                                            'class' => false,
                                            'placeholder' => __('First Name'),
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
                                                'placeholder' => __('Last Name'),
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
                                                'placeholder' => __('Email Address'),
                                                'templates' => [
                                                'input' => '<input type="{{type}}" name="{{name}}" {{attrs}}/>',
                                                'inputContainer' => '<div class="input-field {{type}}{{required}}">{{content}}</div>',
                                                'inputContainerError' => '<div class="input-field input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                                ],
                                                ]);
                                                ?>

                                                <?php
                                                echo $this->Form->input('nick_name', [
                                                    'type' => 'text',
                                                    'label' => false,
                                                    'placeholder' => __('Nick Name'),
                                                    'templates' => [
                                                    'input' => '<input type="{{type}}" name="{{name}}"  {{attrs}}/>',
                                                    'inputContainer' => '<div class="input-field {{type}}{{required}}">{{content}}</div>',
                                                    'inputContainerError' => '<div class="input-field input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                                    ],
                                                    ]);

                                                echo $this->Form->input('country_id', [
                                                    'label' => false,
                                                    'empty' => __('Select Country'),
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
                                                                'empty' => __('Age'),
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
                                                                        ['value' => 'M', 'text' => $this->Html->image('male-icon-black.png') . __('Male'), 'id' => 'f-option'],
                                                                        ['value' => 'F', 'text' => $this->Html->image('female-icon-black.png') . __('Female'), 'id' => 's-option'],
                                                                        ], ['templates' => [ 'nestingLabel' => '<label{{attrs}}>{{text}}</label>{{input}}'], 'escape' => false]
                                                                        );
                                                                        ?>
                                                                    </ul>
                                                                    <?php echo $this->Form->error('gender'); ?>

                                                                </div>
                                                            </div>

                                                            <div class="input-field">
                                                                <?php
                                                                echo $this->Form->input('muslim_since', [
                                                                    'label' => false,
                                                                    'type' => 'date',
                                                                    'maxYear' => date('Y'),
                                                                    'minYear' => date('Y') - 100,
                                                                    'empty' => __('Muslim Since'),
                                                                    'templates' => [
                                                                    'select' => '<select name="{{name}}">{{content}}</select>',
                                                                    'dateWidget' => '{{year}}',
                                                                    'inputContainer' => '<div class="{{type}}{{required}}">{{content}}</div>',
                                                                    'inputContainerError' => '<div class="input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                                                    ],
                                                                    ]);
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="add-user-btn">
                                                                <input type="submit" value="<?= __('Submit');?>" class="blue-btn">
                                                            </div>
                                                            <?php $this->Form->end(); ?>
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
