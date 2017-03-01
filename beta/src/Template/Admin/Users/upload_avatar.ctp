<?php 
$this->Html->addCrumb('Users', ['controller' => 'users','action' => 'index']);
$this->Html->addCrumb('Avatars', ['controller' => 'users','action' => 'avatars']);
$this->Html->addCrumb($user->name, null);

$this->Html->addCrumb('Upload New Avatar', null);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-9">

                    <div class="p-20">
                        <?php
                        echo $this->Form->create($user, [
                            'novalidate' => true,
                            'type' => 'file',
                            'class' => 'form-horizontal'
                        ]);

                        echo $this->Form->hidden('referer',['value' => $referer]);
                        
                        ?>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">New Avatar Image</label>
                            <?php
                            echo $this->Form->input('new_avatar', [
                                'templates' => [
                                    'inputContainer' => '<div class="col-lg-5 mt10 {{type}}{{required}}">{{content}}</div>',
                                    'inputContainerError' => '<div class="col-lg-8 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                                    'file' =>'<input type="file" name="{{name}}"{{attrs}}>'],
                                'label' => false,
                                'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                            ?>
                        </div>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-4 col-sm-9 m-t-15">
                                <?php
                                $submitBtn = $this->Form->button('Save Avatar', array('class' => 'btn btn-success'));
                                $caneclBtn = $this->Html->link('Cancel', array('action' => 'avatars'), array('class' => 'btn btn-default m-l-5'));
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