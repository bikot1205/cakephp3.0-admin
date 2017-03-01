<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-3 col-xs-12">
                <div class="show-list-btn" id="list-btn">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="logo">
                    <?php echo $this->Html->link($this->Html->image('logo.png'),['plugin' => false,'controller' => 'pages','action' => 'display','home'],['escape' => false]);?>
                </div>
            </div>
            <div class="col-md-8 col-sm-9 col-xs-12">

                <div class="more" id="fb-btn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></div>
                <div class="h-right" id="fb-form">
                    <div class="social">
                        <ul>
                            <?php if (!$loggedInAs['facebook_id']) { ?>
                            <li><a href="javascript:void(0)" ng-click="authenticate('facebook')" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <?php } ?>

                            <?php if (!$loggedInAs['twitter_id']) { ?>
                            <li><a href="javascript:void(0)" ng-click="authenticate('twitter')" class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <?php } ?>

                            <?php if (!$loggedInAs['google_id']) { ?>
                            <li><a href="javascript:void(0)" ng-click="authenticate('google')" class="gplus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="userinfo">
                        <ul>
                            <li><?php 
                                echo $this->Html->link($this->Awesome->userImage($loggedInAs),'#edit-profile',['escape' => false]); ?></li>
                                <li><?php echo $this->Html->link($loggedInAs['first_name'] . ' ' . $loggedInAs['last_name'],['plugin' => false,'controller' => 'users','action' => 'edit']); ?></li>
                            </ul>
                        </div>
                        <div class="setting-btns">
                            <ul>
                                <li><button class="btn btn-default dropdown-toggle" data-popover-content="#notificationsList" data-toggle="popover" data-trigger="click" data-placement="bottom" id="notifications" style="width: 50px;">
                                    <span class="fa fa-bell-o" style="color: #177fa7;
                                    font-size: 20px;"></span>
                                </button>

                                <!-- Content for Popover #1 -->
                                <div class="hidden" id="notificationsList">
                                    <div class="popover-heading">
                                        <?php echo __('Notifications');?>
                                    </div>

                                    <div class="popover-body">
                                        <div class="img-section smiles_list" >
                                            <ul class="list-inline">
                                                <li>
                                                    <?php echo ('No notifications yet!!!');?>
                                                    <hr style="margin: 10px 0px">
                                                </li>
                                                
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>


                            </li>
                            <li>
                                <div class="btn-group">


                                    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                        <span class="setting-icon"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?php echo $this->Html->link(__('Terms and Conditions'), '#term-and-conditions'); ?>
                                        </li>
                                        <li>
                                            <?php echo $this->Html->link(__('Feedback and Suggestions'), '#feedback-and-suggestions'); ?>
                                        </li>
                                        <li>
                                            <?php echo $this->Html->link(__('About Us'), '#about-us'); ?>
                                        </li>

                                        
                                        <li>
                                            <?php echo $this->Html->link(__('Contact Us'), '#contact-us'); ?>
                                        </li>

                                        
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <?php echo $this->Html->link('<span class="logout-icon"></span>', ['plugin' => false, 'controller' => 'users', 'action' => 'logout'], ['escape' => false, 'class' => 'btn btn-default dropdown-toggle']); ?>
                            </li>
                            <li>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle language-change-btn" type="button" data-toggle="dropdown"><?= __('Language');?>
                                      <span class="caret"></span></button>
                                      <ul class="dropdown-menu">
                                        <li>
                                            <?php echo $this->Html->link('English', ['language' => 'en_US'], ['escape' => false, 'class' => '']); ?>
                                        </li>
                                        <li>
                                            <?php echo $this->Html->link('Spanish', ['language' => 'es_AR'], ['escape' => false, 'class' => '']); ?>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
