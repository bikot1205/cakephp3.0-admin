<div class="user-list my_contacts" ng-cloak ng-show="connected">

        <div class="list-heading m-t-10"><i class="fa fa-user"></i> <?= __('Active Users'); ?></div>
        <div class="chat-user" ng-repeat="(key, friend) in groupMembers | orderBy:friend.sr" data-id="{{friend.id}}" ng-click="showOptions(key)" >
            <div class="chat-user-photo" ng-bind-html="trustAsHtml(friend.image)"></div>
            <div class="chat-user-name">
                <span ng-click="removeUser(key)">{{friend.sr}} | </span>{{friend.name}} <frinendImage ng-bind-html="trustAsHtml(friend.country_flag)" ng-show="friend.country.flag"></frinendImage>
            </div>
            <div  ng-class="{'options' : friend.expand==1, 'options hide' : friend.expand==0}" id="option_{{friend.id}}}">
                <ul>
                    <li><img src="<?php echo $this->request->webroot; ?>img/entertainment-sidebar-icon.png" alt="" /> Entertainment</li>
                    <li ng-click="blockFriend(friend.id, friend.slug);"><i class="fa fa-ban contact_btn red" aria-hidden="true"></i> <span>Block User</span></li>
                    <li ng-click="unfriend(friend.id, friend.slug);"><i class="fa fa-times-circle-o contact_btn black" aria-hidden="true"></i> <span>Unfriend User</span></li>
                </ul>
            </div>
        </div>
</div>


<div class="user-list my_contacts">
        <div class="list-heading m-t-10"><i class="fa fa-user"></i> <?= __('All Users'); ?></div>
        <div class="chat-user" ng-repeat="(key, friend) in membersList" data-id="{{friend.id}}" ng-click="showOptions(key)" >
            <div class="chat-user-photo" ng-bind-html="trustAsHtml(friend.image)"></div>
            <div class="chat-user-name">
                <span>{{friend.sr}} | </span>{{friend.name}} <frinendImage ng-bind-html="trustAsHtml(friend.country_flag)" ng-show="friend.country.flag"></frinendImage>
            </div>
            <div  ng-class="{'options' : friend.expand==1, 'options hide' : friend.expand==0}" id="option_{{friend.id}}}">
                <ul>
                    <li><img src="<?php echo $this->request->webroot; ?>img/entertainment-sidebar-icon.png" alt="" /> Entertainment</li>
                    <li ng-click="blockFriend(friend.id, friend.slug);"><i class="fa fa-ban contact_btn red" aria-hidden="true"></i> <span>Block User</span></li>
                    <li ng-click="unfriend(friend.id, friend.slug);"><i class="fa fa-times-circle-o contact_btn black" aria-hidden="true"></i> <span>Unfriend User</span></li>
                </ul>
            </div>
        </div>


        <!-- <div class="text-center no-records" ng-show="friends">
            <h1><i class="fa fa-user"></i></h1>
            <h5 class="text-uppercase"><?= __('No contacts in your list.'); ?></h5>
            <?php echo $this->Html->link(__('Search Members'),['controller' => 'users','action' => 'index'],['class' => 'btn btn-default']);?>
        </div> -->

</div>

