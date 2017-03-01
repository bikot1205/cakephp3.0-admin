<?php
$this->assign('title', __('Feedback & Suggestions'));
$this->assign('page_title', __('Feedback & Suggestions'));
?>
<div class="chat-section">
    <div class="top-header">
        <?= $this->element('search_users'); ?>
    </div>
    <div class="detail-area ">
        <div class="p-20">
            <div class="title" id="hide-filter">
                <?= $this->fetch('page_title');?>
            </div>
            <?= $this->element('filters'); ?>
            <div class="content-area">
                <div id="os3" class="optiscroll column-container mid-50">
                    <div class="add-user-section">
                        <?php
                        echo $this->Form->create($feedback, [
                            'novalidate' => true,
                            'type' => 'file',
                            'ng-submit' => 'profileForm();'
                            ]);
                            ?>
                            <div class="Feedback-area">
                                <?php 
                                echo $this->Form->input('feedback',['label' => false,
                                    'templates' => [
                                    'textarea' => '<textarea name="{{name}}" placeholder="'.__('Enter You Text').'">{{value}}</textarea>',
                                    'inputContainer' => '{{content}}',
                                    'inputContainerError' => '{{content}}{{error}}',
                                    ],
                                    ]);
                                    ?>
                                </div>
                                <div class="add-user-btn">
                                    <input type="submit" value="<?= __('Submit');?>" class="blue-btn">
                                </div>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .help-block{
                width: 100%;
                text-align: center;
            }
        </style>