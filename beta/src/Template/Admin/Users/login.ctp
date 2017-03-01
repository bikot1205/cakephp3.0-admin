<div class=" card-box">
    <div class="panel-heading"> 
        <h3 class="text-center"> Sign In </h3>
    </div> 
    
    <div class="panel-body">
        <?= $this->Flash->render('auth') ?>
        <?php
        echo $this->Form->create('User', [
            'novalidate' => true,
            'class' => 'form-horizontal m-t-20'
        ]);

        echo $this->Form->input('email');

        echo $this->Form->input('password');
        ?>

        <div class="form-group text-center m-t-40">
            <div class="col-xs-12">
                <?php echo $this->Form->button('Log In', ['class' => 'btn btn-primary btn-block text-uppercase waves-effect waves-light', 'type' => 'submit']); ?>

            </div>
        </div>

        <?php echo $this->end(); ?>
    </div>   
</div>  
