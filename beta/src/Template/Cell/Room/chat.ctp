<?php use Cake\I18n\Time;?>
<style type="text/css">
    .detail-tooltip{
        font-size: 11px;
    }
    .chat-section-area img{
        width: 30px !important;
    }
    .column-container{height: 100%}
</style>

<div class="chatBox" ng-show="Tab.Chatbox">
    <div class="close-menu" id="close-menu"><i class="fa fa-times" aria-hidden="true"></i></div>
    <div class="overlep-bg-b"></div>
    <div class="chat-section">
        <div class="top-header">
            <div class="filter " id="filter-btn-room"><i class="fa fa-th-large active"></i></div>
            <a href="" style="  color: #fff;
            position: absolute;
            right: 24px;
            top: 14px;" data-toggle="modal" data-target="#inviteUsers"> <i class="fa fa-ellipsis-v" aria-hidden="true" style="font-size: 32px"></i></a>
            <?php echo $this->element('searchbar');?>
        </div>

        <div class="detail-area ">
            <?php echo $cell = $this->cell('Layout::rooms'); ?>
            <div class="p-20">
                <div id="dynamicImg" class="hide"></div>
                <div id="dynamicImgDetail" class="hide"></div>
                <div class="position-banner-sec">
                    <div class="banner-up"><img src="<?php echo $this->request->webroot; ?>img/banner-up.png" alt="" /></div>
                    <div class="banner-down"><img src="<?php echo $this->request->webroot; ?>img/banner-down.png" alt="" /></div>


                    <div class="banner chat-section-area">
                        <a href="#" id="myChatrommTTT"></a>
                        <canvas id="canvas_mage" width="840" height="275" style="border: 0px solid #ccc;width:100%; height:100%"></canvas>
                    </div>
                </div>


                <div class="content-area chat-window">
                    <div id="chat" class="optiscroll column-container mid-50">

                    </div>
                </div>
                <div class="message-write-box">
                    <div class="send-message-bar">
                        <div class="send-input">
                            <input type="text" placeholder="<?php echo __('Type a message here');?>" id="input">

                            <i class="fa fa-photo upload_icon" aria-hidden="true" ng-show='roomDetails.file_share'></i>
                        </div>

                        <div class="smily-box">

                            <?php //echo $this->Html->image('chat-upload.png',['class' => 'upload_icon']);?>
                            <img src="<?php echo $this->request->webroot; ?>img/smily-icon.png" alt="" /> 
                            <a data-placement="top" data-popover-content="#a1" data-toggle="popover" data-trigger="click" href="javascript:void(0)" tabindex="0" id="smiles">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>


                        <div class="send-btn">
                            <input type="submit" value="Send" class="blue-btn" id="send_message">

                        </div>
                    </div>

                    <?php echo $this->Form->create('Attachment',['type' => 'file','id' => 'fileUploadForm']);

                    echo $this->Form->file('file',['id' => 'files','class' => 'hide']);

                    echo $this->Form->end();
                    ?>
                    <div class="name-bar" ng-if="privateUser.status == 'accepted'">
                        <a href="javascript:void(0)" ng-click="removePrivateChat();">{{privateUser.name}}</a>
                    </div>
                </div>


                <!-- Content for Popover #1 -->
                <div class="hidden" id="a1">
                    <div class="popover-heading">
                        Smiles
                    </div>

                    <div class="popover-body">
                        <div class="img-section smiles_list" >
                            <ul class="list-inline">
                                <li ng-repeat="smile in smiles" action-button-directive>
                                    <img ng-src="{{smile.image}}" src="<?php echo $this->request->webroot; ?>img/default.gif" width="40px;" alt="{{smile.name}}" title="{{smile.name}}" class="smile_image_item" data-code="{{smile.code}}" ng-click="checkLick();">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="start_private_chat"></div>
        <div class="cancelled_private_chat"></div>
    </div>
</div>

<!--- Recent Chat -->
<div class="recentChat" ng-show="Tab.RecentChat">
    <div class="close-menu" id="close-menu"><i class="fa fa-times" aria-hidden="true"></i></div>
    <div class="overlep-bg-b"></div>
    <div class="chat-section">
        <div class="top-header">
            <?php echo $this->element('searchbar');?>
        </div>
        <div class="detail-area">
            <?php echo $cell = $this->cell('Layout::rooms',['display' => true]); ?>
            <div class="p-20">
                <div class="title">
                    <?= __('Recent Chats');?>
                </div>
                <div class="content-area my-chat">
                    <div id="recentChatList" class="optiscroll column-container mid-50">

                        <div class="new-user-list" ng-repeat="(key, chat) in recentChats">
                            <div class="new-user-photo" ng-bind-html="trustAsHtml(chat.user.image)">
                            </div>
                            <div class="add-user-option-sec">
                                <ul>
                                    <li><i class="fa fa-clock-o"></i> {{chat.created_at}}</li>
                                    <li class="blue-text">
                                        <span>{{chat.room.name}}</span> | <img src="<?php echo $this->request->webroot;?>img/user-add-icon.png" alt="" /> 
                                    </li>
                                </ul>
                            </div>
                            <div class="new-user-name">
                                <div class="user-name">
                                    {{chat.user.first_name}} {{chat.user.last_name}}
                                </div><br>
                                <div class="since">{{chat.txt}}</div>
                            </div>
                        </div>
                        
                        <div class="text-center no-records" ng-if="!recentChats">
                            <h1><i class="fa fa-wechat"></i></h1>
                            <h5 class="text-uppercase"><?= __('No recent chat.'); ?></h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="myChat" ng-show="Tab.MyChat">

    <div class="close-menu" id="close-menu">
        <i class="fa fa-times" aria-hidden="true"></i>
    </div>

    <div class="overlep-bg-b"></div>
    <div class="chat-section">
        <div class="top-header">
            <?php echo $this->element('searchbar');?>
        </div>

        <div class="detail-area">
            <div class="p-20">
                <div class="title">
                    <?= __('My Chats');?>
                </div>
                <div class="content-area">
                    <div id="myChatList" class="optiscroll column-container mid-50">
                        <div class="new-user-list" ng-repeat="(key, chat) in myChats">
                            <div class="new-user-photo" ng-bind-html="trustAsHtml(chat.user.image)">
                            </div>
                            <div class="add-user-option-sec">
                                <ul>
                                    <li><i class="fa fa-clock-o"></i> {{chat.created_at}}</li>
                                    <li class="blue-text">
                                        <span>{{chat.room.name}}</span> | <img src="<?php echo $this->request->webroot;?>img/user-add-icon.png" alt="" /> 
                                    </li>
                                </ul>
                            </div>
                            <div class="new-user-name">
                                <div class="user-name">
                                    {{chat.user.first_name}} {{chat.user.last_name}}
                                </div><br>
                                <div class="since">{{chat.txt}}</div>
                            </div>
                        </div>

                        <div class="text-center no-records" ng-if="!recentChats">
                            <h1><i class="fa fa-wechat"></i></h1>
                            <h5 class="text-uppercase"><?= __('No recent chat.'); ?></h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="freends" ng-show="Tab.Friends">
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
                <?php 
                $this->assign('page_title', '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default dropdown-toggle text-uppercase">
                    <i class="fa fa-user-plus"></i>  '.__('Friend Requests').' <span class="caret"></span>
                </button>
                <div class="dropdown-menu">
                    <div id="os4" class="optiscroll column-container mid-50">
                        <ul>
                            <li>'.$this->Html->link('<i class="fa fa-user"></i> '.__('Suggested Friends'),['controller' => 'users','action' => 'suggested'],['escape' => false]).'</li>
                        </ul>
                    </div>
                </div></div>');
                ?>
                <div class="content-area">
                    <div id="friendsList" class="optiscroll  mid-50">

                        <div class="new-user-list" ng-repeat="(key, friend) in Friends">
                            <div class="new-user-photo" ng-bind-html="trustAsHtml(friend.image)">

                            </div>
                            <div class="add-user-option">
                                <ul ng-bind-html="trustAsHtml(friend.action)">
                                </ul>

                            </div>
                            <div class="new-user-name">
                                <div class="user-name">{{friend.name}} </div>
                                <div class="country-flag" ng-bind-html="trustAsHtml(friend.country.flag)">

                                </div><br>
                                <div class="since">Muslim Since : <span>{{friend.muslimYear}}</span></div>
                            </div>
                        </div>
                        
                        
                        <div class="text-center no-records" ng-if="!Friends.length">
                            <h1><i class="fa fa-bell-o"></i></h1>
                            <h5 class="text-uppercase"><?= __('No friend request');?></h5>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #send_invitation_addTag > input {
    width: 100% !important;
}
</style>
<!-- Modal -->
<div class="modal fade" id="inviteUsers" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo __('Send invitation');?></h4>
      </div>
      <div class="modal-body">
          <p>Enter email address for send invitation</p>
          
          <input id='send_invitation' type='text' class='tags form-control' placeholder="Enter Email address"></p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" ng-disabled="!invitedEmails" ng-click="sendInvitation()">Send</button>
      </div>
  </div>

</div>
</div>

