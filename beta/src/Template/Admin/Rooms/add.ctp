<?php
$this->Html->addCrumb('Rooms', ['controller' => 'rooms', 'action' => 'index']);
$this->Html->addCrumb('Add Room', null);

?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-9">

                    <div class="p-20">
                        <?php
                        echo $this->Form->create($room, [
                            'novalidate' => true,
                            'type' => 'file',
                            'class' => 'form-horizontal'
                            ]);
                            ?>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Room Name <span>*</span></label>
                                <?php
                                echo $this->Form->input('name', [
                                    'label' => false,
                                    'type' => 'text',
                                    'autocomplete' => 'off',
                                    'templates' => [
                                    'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                    ],
                                    ]);
                                    ?>

                                </div>

                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Room Moderator <span>*</span></label>
                                    <?php
                                    $error = $this->Form->error('moderator_id');
                                    echo $this->Form->input('moderator', [
                                        'label' => false,
                                        'type' => 'text',
                                        'id' => 'moderator',
                                        'templates' => [
                                        'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}'.$error.'</div>',
                                        'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}'.$error.'</div>',
                                        ],
                                        'class' => 'form-control'
                                        ]
                                        );

                                    echo $this->Form->input('moderator_id', [
                                        'type' => 'hidden',
                                        'id' => 'moderator_id',
                                        ]
                                        );
                                        ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label">Occupancy level <span>*</span></label>
                                        <?php
                                        echo $this->Form->input('occupancy', [
                                            'label' => false,
                                            'options' => $room->occupancyLevels,
                                            'templates' => [
                                            'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}</div>',
                                            'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                            ],
                                            'empty' => 'Occupancy level',
                                            'class' => 'form-control select2'
                                            ]
                                            );
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Room Type <span>*</span></label>
                                            <?php
                                            echo $this->Form->input('type', [
                                                'label' => false,
                                                'options' => $room->types,
                                                'empty' => 'Room Type',
                                                'templates' => [
                                                'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}</div>',
                                                'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                                ],
                                                'class' => 'form-control select2']);
                                                ?>

                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">Room Icon</label>
                                                <?php
                                                $image = '';
                                                '<div class="thumbnail" style="width:200px;">' . $this->Awesome->image('Rooms/image', $room['icon'], ['class' => 'img-responsive clearfix']) . '

                                            </div>';
                                            echo $this->Form->input('icon', [
                                                'templates' => [
                                                'inputContainer' => '<div class="col-lg-5 mt10 {{type}}{{required}}">{{content}}</div>',
                                                'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                                'file' => $image . '<input type="file" name="{{name}}"{{attrs}}>'],
                                                'label' => false,
                                                'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                                                ?>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">Room Image</label>
                                                <?php
                                                $image = '';
                                                '<div class="thumbnail" style="width:200px;">' . $this->Awesome->image('Rooms/image', $room['image'], ['class' => 'img-responsive clearfix']) . '

                                            </div>';
                                            echo $this->Form->input('image', [
                                                'templates' => [
                                                'inputContainer' => '<div class="col-lg-5 mt10 {{type}}{{required}}">{{content}}</div>',
                                                'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                                'file' => $image . '<input type="file" name="{{name}}"{{attrs}}>'],
                                                'label' => false,
                                                'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                                                ?>
                                            </div>
                                            <?php 
                                            echo '<div class="form-group">
                                            <label class="control-label col-sm-8 col-md-4">File Sharing</label>
                                            <div class="switchery-demo col-sm-8 col-md-4">
                                                ' . $this->Form->checkbox('file_share', [
                                                    'data-plugin' => "switchery",
                                                    'data-color' => "#5d9cec",
                //'data-size'=>"medium"
                                                    ]) .
                                                '</div>
                                            </div>';

                                            ?>

                                            <?php 
                                            echo '<div class="form-group">
                                            <label class="control-label col-sm-8 col-md-4">Status</label>
                                            <div class="switchery-demo col-sm-8 col-md-4">
                                                ' . $this->Form->checkbox('status', [
                                                    'data-plugin' => "switchery",
                                                    'data-color' => "#5d9cec",
                //'data-size'=>"medium"
                                                    ]) .
                                                '</div>
                                            </div>';

                                            ?>
                                            
                                            <div class="form-group m-b-0">
                                                <div class="col-sm-offset-4 col-sm-9 m-t-15">
                                                    <?php
                                                    $submitBtn = $this->Form->button('Add Room', array('class' => 'btn btn-info'));
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
