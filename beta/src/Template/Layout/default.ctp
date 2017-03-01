<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$slug = $this->request->session()->read('room');
?>
<!DOCTYPE html>
<html ng-app="myapp">
<?= $this->element('head'); ?>
<?php echo  $this->cell('Layout::roomDetails',['slug' => $slug,'loggedIn' => $loggedInAs]); ?>
<body class="inner-bg" ng-controller="defaultController">
  <?= $this->element('header'); ?>
  <div class="overlep-bg"></div>

  <div class="content-section fit-widow" id="window-height">
    <div class="container"  ng-controller="roomController">
      <div class="row">
        <div class="col-md-12">
          <?= $this->Flash->render() ?>
          <?php if($loggedInAs['avatar_status'] == 'Pending'){ ?>
          <div class="alert alert-info">
            <?php 
            $avatarLink = \Cake\Routing\Router::url('/',true).'files/Users/new_avatar/'.$loggedInAs['new_avatar'];
            echo $this->Html->link($this->Awesome->image('Users/new_avatar', $loggedInAs['new_avatar'], ['class' => 'img-circle thumb-sm clearfix','width' => '30']),$avatarLink,['escape' => false,'target'=>'_blank']);
            ?>
            <strong>Hey, <?= $loggedInAs['first_name'].' '.$loggedInAs['last_name'];?></strong> You have recieved a new avatar request. 
            <?php echo $this->Form->postLink('Accept',['controller' => 'users','action' => 'actionAvatar','accept'],['class' => 'btn btn-success btn-sm', 'confirm' => __('Are you sure you want to accept ? ')]);?>

            <?php echo $this->Form->postLink('Decline',['controller' => 'users','action' => 'actionAvatar','decline'],['class' => 'btn btn-danger btn-sm', 'confirm' => __('Are you sure you want to decline ? ')]);?>

          </div>
          <?php }?>
          <?php echo $this->cell('Layout::avatars');

          // 'echo '<script type="text/javascript">';
          //           echo 'var userInfo = ' . json_encode($loggedInAs) . ';';
          //           echo '</script>';'

          ?>

          <div class="box-shadow">
            <?= $this->element('sidebar'); ?>
            <div class="defaultContents" ng-show="Tab.Default">
              <div ng-view></div>
              <?php // $this->fetch('content') ?>
            </div>
            <?php echo  $this->cell('Room::chat'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?= $this->element('copyright'); ?>

  <?= $this->AssetCompress->script('script'); ?>
  <?= $this->Html->script(['satellizer.min', 'angular-apps']); ?>
  <?= $this->Html->script(['fabric.min.js','strophe/strophe','chatroom']);?>
  <?= $this->fetch('jsSection');?>

  <script type="text/javascript">
      // $('body').css('overflow','hidden');
      $(function(){

        $('#notifications').popover(
        {
          html : true,
          content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
          },
          title: function() {
            var title = $(this).attr("data-popover-content");
            return $(title).children(".popover-heading").html();
          }
        }
        ); 

        $("#smiles").popover({
          html : true,
          content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
          },
          title: function() {
            var title = $(this).attr("data-popover-content");
            return $(title).children(".popover-heading").html();
          }
        });



      });

      $('body').on('click', function (e) {
        $('#smiles').each(function () {
                // hide any open popovers when the anywhere else in the body is clicked
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                  $(this).popover('hide');
                }
              });
      });

      try {
        var os3 = new Optiscroll(document.getElementById('os3'), {maxTrackSize: 20, preventParentScroll: true});
      }
      catch(err) { }

      try {
       var l1a = new Optiscroll(document.getElementById('l1a'), {maxTrackSize: 20, preventParentScroll: true});
     }
     catch(err) { }

     try {
      var l1b = new Optiscroll(document.getElementById('l1b'), {maxTrackSize: 20, preventParentScroll: true});
    }
    catch(err) { }

    try {
      var l2a = new Optiscroll(document.getElementById('l2a'), {maxTrackSize: 20, preventParentScroll: true});
    }
    catch(err) { }


    try {
     var l2b = new Optiscroll(document.getElementById('l2b'), {maxTrackSize: 20, preventParentScroll: true});
   }
   catch(err) { }

   try {
     var l3b = new Optiscroll(document.getElementById('l3b'), {maxTrackSize: 20, preventParentScroll: true});
   }
   catch(err) {}

   try {
     var l4a = new Optiscroll(document.getElementById('l4a'), {maxTrackSize: 20, preventParentScroll: true});
   }
   catch(err) { }

   try {
    var l4b = new Optiscroll(document.getElementById('l4b'), {maxTrackSize: 20, preventParentScroll: true});
  }
  catch(err) { }
</script>


</body>
</html>
