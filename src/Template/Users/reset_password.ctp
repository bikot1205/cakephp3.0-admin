<div class="signup-page">
    <div class="container">
        <div class="row">
            <!-- user-login -->			
            <div class="col-sm-6 col-sm-offset-3">
                <div class="ragister-account account-login">		
                    <h1 class="section-title title">Enter New Password</h1>
                    <?= $this->Flash->render() ?>
                    <?php
                    echo $this->Form->create($user, [
                        'novalidate' => true,
                        'id' => 'registation-form'
                    ]);

                    echo $this->Form->input('password');
                    echo $this->Form->input('confirm_password');

                    ?>
                    <div class="submit-button text-center">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                    <?php echo $this->Form->end(); ?>

                </div>
            </div><!-- user-login -->			
        </div><!-- row -->	
    </div><!-- container -->
</div><!-- signup-page -->

