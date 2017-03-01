<div class="signup-page">
    <div class="container">
        <div class="row">
            <!-- user-login -->			
            <div class="col-sm-6 col-sm-offset-3">
                <div class="ragister-account account-login">		
                    <h1 class="section-title title">Reset Password</h1>
                   
                    <?= $this->Flash->render() ?>
                    <?php
                    echo $this->Form->create('User', [
                        'novalidate' => true,
                        'id' => 'registation-form'
                    ]);

                    echo $this->Form->input('email',['label' => 'Enter your registered email address']);

                    ?>
                    	
                    <div class="submit-button text-center">
                        <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    <div class="new-user text-center">
                        <p><?= $this->Html->link('Login',['plugin' => false,'controller' => 'users','action' => 'login']);?> </p>
                    </div>

                </div>
            </div><!-- user-login -->			
        </div><!-- row -->	
    </div><!-- container -->
</div><!-- signup-page -->
