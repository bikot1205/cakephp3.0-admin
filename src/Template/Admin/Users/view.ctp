<?php $this->Html->addCrumb('Users', ['controller' => 'users','action' => 'index']);
$this->Html->addCrumb('Profile', null);
$this->Html->addCrumb($user->name, null);
?>
<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="profile-detail card-box">
            <div>
                <?php echo $this->Awesome->userImage($user); ?>
                <h4 class="text-uppercase font-600"><?php echo $user->name; ?></h4>
                <?php echo $user->email; ?>
                <hr>

                <div class="text-left">
                    <p class="text-muted font-13"><strong>Nick Name :</strong> <span class="m-l-15"><?php echo $user->nick_name; ?></span></p>

                    <p class="text-muted font-13"><strong>Age :</strong> <span class="m-l-15"><?php echo ($user->age > 1) ? $user->age.'yrs' : $user->age.'yr'; ?></span></p>
                    <p class="text-muted font-13"><strong>Gender :</strong> <span class="m-l-15"><?php echo $user->genderTxt; ?></span></p>

                    <p class="text-muted font-13"><strong>Country :</strong> <span class="m-l-15"><?php echo $user->country->name; ?></span></p>
                    <p class="text-muted font-13"><strong>Muslim Since :</strong> <span class="m-l-15"><?php echo $user->muslimYear; ?></span></p>
                    <p class="text-muted font-13"><strong>Join Date :</strong> <span class="m-l-15"><?php echo $this->Awesome->date($user->created); ?></span></p>

                    <p class="text-muted font-13"><strong>Status :</strong>
                        <?php echo $this->Form->checkbox('status', [
                            'checked' => $user->status,
                            'class' => "switchery_with_action",
                            'data-size'=>"small",
                            'data-model' => $modelClass,
                            'data-id' => $user->id,
                            'data-field' => 'status'
                            ]);
                            ?>
                        </p>

                    </div>

                    <div class="button-list m-t-20">
                        <?php if ($user->facebook_id) { ?>
                        <button type="button" class="btn btn-facebook waves-effect waves-light">
                            <i class="fa fa-facebook"></i>
                        </button>
                        <?php } ?>
                        <?php if ($user->twitter_id) { ?>
                        <button type="button" class="btn btn-twitter waves-effect waves-light">
                            <i class="fa fa-twitter"></i>
                        </button>
                        <?php } ?>
                        <?php if ($user->google_id) { ?>
                        <button type="button" class="btn btn-googleplus waves-effect waves-light">
                            <i class="fa fa-google-plus"></i>
                        </button>
                        <?php } ?>

                    </div>
                </div>

            </div>

            <div class="card-box" style="min-height: 215px;">
                <?php echo $cell = $this->cell('User::friends', ['user' => $user->id]); ?>

            </div>
    </div>


    <div class="col-lg-9 col-md-9">
        <ul class="nav nav-tabs tabs">
            <li class="active tab"> 
                <a href="#settings" data-toggle="tab" aria-expanded="false"> 
                    <span class="visible-xs"><i class="fa fa-cog"></i></span> 
                    <span class="hidden-xs">Settings</span> 
                </a> 
            </li> 
           
            <li class="tab">
                <a href="#JoinedRooms" data-toggle="tab" aria-expanded="false"> 
                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                    <span class="hidden-xs">Joined Rooms</span> 
                </a> 
            </li> 
            <li class="tab">
                <a href="#friends" data-toggle="tab" aria-expanded="false"> 
                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                    <span class="hidden-xs">Chat History</span> 
                </a> 
            </li>
            
        </ul> 

        <!-- Tab contents start from here -->
        <div class="tab-content"> 
            <div class="tab-pane active" id="settings"> 
                <?php
                echo $this->Form->create($user, [
                    'novalidate' => true,
                    'type' => 'file',
                    'class' => 'form-horizontal card-box'
                    ]);
                ?>
                    <h4>Profile Details</h4>
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
                            'autocomplete' => 'off',
                            'templates' => [
                                'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                                'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                ],
                            ]
                        );
                        ?>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Upload New Avatar</label>
                        <?php
                        $image = '<div class="thumbnail" style="width:200px;">' . $this->Awesome->image('Users/image', $user['image'], ['class' => 'img-responsive clearfix']) . '

                        </div>';
                        echo $this->Form->input('image', [
                            'templates' => [
                            'inputContainer' => '<div class="col-lg-5 mt10 {{type}}{{required}}">{{content}}</div>',
                            'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                            'file' => '<input type="file" name="{{name}}"{{attrs}}>'],
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
                            echo $this->Form->button('Update Profile', array('class' => 'btn btn-pink'));

                            echo $this->Html->link('Delete', ['action' => 'delete', $user->id], ['escape' => false, 'class' => 'btn btn-danger m-l-5', 'confirm' => __('Are you sure you want to delete this user ? ')]);

                            if ($user->status) {
                            echo $this->Html->link('Block', ['action' => 'changeStatus', $user->id, $user->status], ['escape' => false, 'class' => 'btn btn-info m-l-5', 'confirm' => __('Are you sure you want to block this user ? ')]);
                            } else {
                            echo $this->Html->link('Unblock', ['action' => 'changeStatus', $user->id, $user->status], ['escape' => false, 'class' => 'btn btn-info m-l-5', 'confirm' => __('Are you sure you want to block this user ? ')]);
                            }
                            ?>
                        </div>
                    </div>
                <?php echo $this->Form->end();?>

                <hr>

                <?php
                unset($user->password);
                echo $this->Form->create($user, [
                    'novalidate' => true,
                    'type' => 'file',
                    'class' => 'form-horizontal card-box'
                    ]);
                ?>
                <h4>Change Password</h4>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Password </label>
                    <?php
                    echo $this->Form->input('password', [
                        'label' => false,
                        'autocomplete' => 'off',
                        'templates' => [
                        'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                        'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                        ],
                    ]);
                    ?>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">Verify Password </label>
                    <?php
                    echo $this->Form->input('confirm_password', [
                        'label' => false,
                        'type' => 'password',
                        'autocomplete' => 'off',
                        'templates' => [
                        'inputContainer' => '<div class="col-lg-8 {{type}}{{required}}">{{content}}</div>',
                        'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                        ],
                    ]);
                    ?>
                </div>

                <div class="form-group m-b-0">
                    <div class="col-sm-offset-4 col-sm-9 m-t-15">
                        <?php
                        $submitBtn = $this->Form->button('Change Password', array('class' => 'btn btn-pink','name' => 'changePassword'));
                        echo $submitBtn;
                        ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>

            <div class="tab-pane" id="JoinedRooms">
                <table class="tablesaw table tablesaw-swipe table-bordered1">
                        <thead>
                            <?php 
                                $tableHeaders[] = array($this->Paginator->sort(__('id'), 'Room ID') => array('class' => 'id_class text-center','style2' => 'width:10%'));
                                $tableHeaders[] = array('Icon' => array('style2' => 'width:10%'));
                                $tableHeaders[] = array($this->Paginator->sort(__('name'), 'Room Name') => array('style2' => 'width:20%'));

                                $tableHeaders[] = array($this->Paginator->sort(__('type'), 'Room Type') => array('style2' => 'width:10%'));
                               
                                
                                $tableHeaders[] = array($this->Paginator->sort(__('occupancy')) => array('style2' => 'width:15%'));
                               

                                echo $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'));
                            ?>
                        </thead>

                        <tbody>
                            <?php 
                            $rows = array();
                            if ($joinedRooms->count() > 0) {
                                foreach ($joinedRooms->toArray() as $key => $listOne) {
                                    $listOne = $listOne->room;
                                    $row = array();
                                    $row[] = array($listOne['id'], array('class' => 'text-center'));
                                    $row[] = $this->Awesome->image('Rooms/icon', $listOne->icon, ['class' => 'img-circle thumb-md clearfix','title' => 'click to view full image']);
                                    $row[] = $this->Html->link($listOne->name,['controller' => 'rooms','action' => 'view','slug' => $listOne->slug]);

                                    $row[] = $listOne->types[$listOne->type];
                                    
                                    $row[] = $listOne->occupancy;

                                    $rows[] = $row;
                                }

                                echo $this->Html->tableCells($rows);
                            }
                            ?>
                        </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


