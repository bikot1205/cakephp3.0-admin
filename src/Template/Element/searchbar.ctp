<?php
echo $this->Form->create("Users", ['url' => ['pluign' => false, 'controller' => 'users', 'action' => 'index'], 'type' => 'get']);
?>

<div class="search width-70">
    <?php
    echo $this->Form->input('q', [
        'id' => 'searchMembers',
        'templates' => [
            'input' => '<input type="{{type}}" name="{{name}}" class="w-100 search_by_keyword" {{attrs}} />',
            'inputContainer' => '{{content}}',
            'inputContainerError' => '{{content}}',
        ],
        'placeholder' => 'Search',
        'label' => false
    ]);
    ?>
</div>

<?php echo $this->Form->end(); ?>
