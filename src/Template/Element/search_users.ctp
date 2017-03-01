<!--<div class="filter " id="filter-btn"><i class="fa fa-th-large"></i></div>
<div class="search width-70"><input type="text" placeholder="Search" class="w-100" ></div>-->
<?php
echo $this->Form->create("Users", ['url' => ['pluign' => false, 'controller' => 'users', 'action' => 'index'], 'type' => 'get','id' => 'searchMembers']);
?>
<div class="filter" id="filter-btn">
    <?php echo $this->Html->image('filter-icon.png'); ?>
</div>
<div class="search width-70">
    <?php
    echo $this->Form->input('q', [
        'templates' => [
            'input' => '<input type="{{type}}" name="{{name}}" class="w-100 search_by_keyword" {{attrs}} />',
            'inputContainer' => '{{content}}',
            'inputContainerError' => '{{content}}',
        ],
        'placeholder' => 'Search',
        'label' => false
    ]);
    echo $this->Form->hidden('country_id');
    echo $this->Form->hidden('gender');
    echo $this->Form->hidden('age');
    echo $this->Form->hidden('language');
    ?>
</div>


<?php echo $this->Form->end(); ?>

<?php echo $this->start('jsSection'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js"></script>



<script>
    $('.selectpicker').selectpicker({
        style: 'btn-info',
        size: 4
    }).on('changed.bs.select', function () {
        $('#filters_form').submit();
    });
    $('.search_by_keyword').blur(function(){
        var keyword = $(this).val();
        $('#filters_form_q').val(keyword);
        console.log(keyword.length);
        if(keyword.length > 3){
            $('#filters_form').submit();
        }
        
    })
</script>
<?php echo $this->end(); ?>
