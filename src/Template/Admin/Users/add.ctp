<?php $this->Html->addCrumb('Users', ['controller' => 'users','action' => 'index']);
$this->Html->addCrumb('Add User', null);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="m-t-0 header-title"><b>Profile Details</b></h4>

            <div class="row">
                <div class="col-lg-9">

                    <div class="p-20">
                        <?php
                        unset($user['password']);
                        echo $this->Form->create($user, [
                            'novalidate' => true,
                            'type' => 'file',
                            'class' => 'form-horizontal'
                        ]);
                        ?>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">First Name <span>*</span></label>
                            <?php
                            echo $this->Form->input('first_name', [
                                'label' => false,
                                'type' => 'text',
                                'autocomplete' => 'off',
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-3 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-3 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                            ]);
                            ?>
                            <label class="col-lg-2 control-label">Last Name </label>
                            <?php
                            echo $this->Form->input('last_name', [
                                'label' => false,
                                'type' => 'text',
                                'autocomplete' => 'off',
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-3 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-3 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                            ]);
                            ?>

                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">Email Address <span>*</span></label>
                            <?php
                            echo $this->Form->input('email', [
                                'label' => false,
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ]
                            ]);
                            ?>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">Password </label>
                            <?php
                            echo $this->Form->input('password', [
                                'label' => false,
                                'autocomplete' => 'off',
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-3 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-3 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                            ]);
                            ?>
                            <label class="col-lg-2 control-label">Verify Password </label>
                            <?php
                            echo $this->Form->input('confirm_password', [
                                'label' => false,
                                'type' => 'password',
                                'autocomplete' => 'off',
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-3 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-3 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                            ]);
                            ?>

                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">Nick Name <span>*</span></label>
                            <?php
                            echo $this->Form->input('nick_name', [
                                'label' => false,
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ]
                            ]);
                            ?>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Select Country <span>*</span></label>
                            <?php
                            echo $this->Form->input('country_id', [
                                'label' => false,
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                                'empty' => 'Select Country',
                                'class' => 'form-control select2'
                                    ]
                            );
                            ?>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-4 control-label">Age <span>*</span></label>
                            <?php
                            echo $this->Form->input('age', [
                                'label' => false,
                                'options' => $user->ageList,
                                'empty' => 'Select',
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-3 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-3 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                                'class' => 'form-control select2']);
                            ?>
                            <label class="col-lg-2 control-label">Gender </label>
                            <div class="col-lg-3 gender">
                                <?php
                                echo $this->Form->select('gender', $user->genders, ['empty' => 'Select', 'class' => 'select2']);
                                echo $this->Form->error('gender');
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Muslim Since <span>*</span></label>
                            <?php
                            echo $this->Form->input('muslim_since', [
                                'label' => false,
                                'type' => 'date',
                                'maxYear' => date('Y'),
                                'minYear' => date('Y') - 100,
                                'class' => 'form-control select2',
                                'value' => (!empty($user['muslim_since'])) ? $user['muslim_since'] . '-01-01' : '',
                                'empty' => 'Select',
                                'templates' => [
                                    'dateWidget' => '{{year}}',
                                    'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                                    ]
                            );
                            ?>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Languages <span>*</span></label>
                            <?php
                            echo $this->Form->input('languages._ids', [
                                'label' => false,
                                'class' => 'select2 select2-multiple languages',
                                'empty' => 'Select',
                                'data-placeholder' => 'Select Lanaguages',
                                'multiple' => 'multiple',
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                                    ]
                            );
                            ?>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-4 control-label">Profile Image</label>
                            <?php
                            $image = '<div class="thumbnail" style="width:200px;">' . $this->Awesome->image('Users/image', $user['image'], ['class' => 'img-responsive clearfix']) . '
                                                                                                                
                                                                                                        </div>';
                            echo $this->Form->input('image', [
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-5 mt10 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                    'file' => $image . '<input type="file" name="{{name}}"{{attrs}}>'],
                                'label' => false,
                                'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                            ?>
                        </div>



                         <?php 
                        echo '<div class="form-group">
                        <label class="control-label col-sm-8 col-md-4">Status</label>
                        <div class="switchery-demo col-sm-8 col-md-4">
                            ' . $this->Form->checkbox('status', [
                                'data-plugin' => "switchery",
                                'data-color' => "#5d9cec",
                                
                                ]) .
                            '</div>
                        </div>';

                        ?>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-4 col-sm-9 m-t-15">
                                <?php
                                $submitBtn = $this->Form->button('Add User', array('class' => 'btn btn-success'));
                                $caneclBtn = $this->Html->link('Cancel', array('action' => 'index'), array('class' => 'btn btn-default m-l-5'));
                                echo $submitBtn;
                                echo $caneclBtn;
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>