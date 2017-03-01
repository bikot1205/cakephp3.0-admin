<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php $this->fetch('title') ?>
        New Muslims
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->AssetCompress->css('syle'); ?>
    <script>
        var webroot = '<?php echo $this->request->webroot; ?>';
        var SiteUrl = '<?php echo \Cake\Routing\Router::url('/', true); ?>';
        var chatServer = '<?php echo \Cake\Core\Configure::read('XamppServer.chatServer'); ?>';
        var chatHost = '<?php echo \Cake\Core\Configure::read('XamppServer.host'); ?>';
        var loggedIn = userInfo = <?php echo json_encode($loggedInAs); ?>;
        var activeTab = '<?php echo (isset($activeTab)) ? $activeTab : 'Default';?>';
        var staticMessages = <?php echo json_encode(array('ExitChat' => __('Do you really want to Exit this private chat? You chat details will be saved'),'movingChat' => __('You are moving away. Your private chat will be closed and saved')));?>;
    </script>
</head>
