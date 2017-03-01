<!DOCTYPE html>
<html lang="en" ng-app="myapp">
    <?= $this->element('head');?>
    <body class="login-bg" ng-controller="defaultController">
        <?= $this->fetch('content') ?>
        
        <?= $this->AssetCompress->script('script'); ?>
        <?= $this->Html->script(['satellizer.min','angular-apps']); ?>
        <?php echo $this->fetch('jsSection'); ?>
    </body>
</html>
