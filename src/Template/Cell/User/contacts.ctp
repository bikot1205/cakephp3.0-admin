<div class="user-list my_contacts" ng-controller="myContactsControllers">
    <div id="l3a" class="optiscroll column all-cotact mid-50">
        <div class="list-heading all-cotact-heading" ng-show="friends.length"><i class="fa fa-user"></i> <?= __('All Contacts'); ?></div>
        <div class="chat-user" ng-repeat="(key, friend) in friends" data-id="{{key}}" ng-click="showOptions(key)" >
            <div class="chat-user-photo" ng-bind-html="trustAsHtml(friend.image)"></div>
            <div class="chat-user-name">
                <span>{{friend.muslimYear}} | </span>{{friend.name}} <frinendImage ng-bind-html="trustAsHtml(friend.country_flag)" ng-show="friend.country.flag"></frinendImage>
            </div>
            <div  ng-class="{'options' : friend.expand==1, 'options hide' : friend.expand==0}" id="option_{{key}}}">
                <ul>
                    <li><img src="<?php echo $this->request->webroot; ?>img/entertainment-sidebar-icon.png" alt="" /> Entertainment</li>
                    <li ng-click="blockFriend(friend.id, key);"><i class="fa fa-ban contact_btn red" aria-hidden="true"></i> <span>Block User</span></li>
                    <li ng-click="unfriend(friend.id, key);"><i class="fa fa-times-circle-o contact_btn black" aria-hidden="true"></i> <span>Unfriend User</span></li>
                </ul>
            </div>
        </div>


        <div class="text-center no-records" ng-show="!friends.length">
            <h1><i class="fa fa-user"></i></h1>
            <h5 class="text-uppercase"><?= __('No contacts in your list.'); ?></h5>
            <?php echo $this->Html->link(__('Search Members'),['controller' => 'users','action' => 'index'],['class' => 'btn btn-default']);?>
        </div>

    </div>
</div>

