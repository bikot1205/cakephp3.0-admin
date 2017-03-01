<style>
    #show-filter .dropdown-menu {
        width: auto !important;
        /*overflow: auto;*/
        /*min-width: 200px;*/
        top:35px;

    }
    .bootstrap-select .dropdown-toggle:focus{
        outline: none !important;
        outline: 0px auto -webkit-focus-ring-color !important;
        outline-offset: -2px !important;
        color: #000 !important;
        background-color: #c0b1cf !important;
        border-color: #bab5d1 !important;;
        border: 0px !important;
    }

    bootstrap-select .dropdown-toggle:focus {
        outline: none !important;
        outline: 0px !important;
        outline-offset: -2px;
    }

    #show-filter .dropdown-menu label{
        white-space: nowrap;
    }
    .bootstrap-select.btn-group .dropdown-toggle .filter-option{
        font-size: 16px;
    }
    .bootstrap-select>.dropdown-toggle{
        color: #000 !important;
        background: none;
        border: 0px;
        width: auto !important;
    }

    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
        width: auto !important;
    }

    #show-filter .btn-group, .btn-group-vertical {
        margin: -4px 0;
    }
    
    <?php if(!empty($this->request->query['country_id']) 
            || !empty($this->request->query['gender']) 
            || !empty($this->request->query['age'])
            || !empty($this->request->query['language'])) {
        echo '#show-filter{display:block}';
        echo '#hide-filter{display:none}';
    } ?>
</style>

<div class="title show-filter" id="show-filter">

    <?php
    echo $this->Form->create("Users", ['url' => ['pluign' => false,
            'controller' => 'users', 'action' => 'index'],
        'type' => 'get', 'id' => 'filters_form',
        'ng-submit' => 'searchUserForm()',
        'ng-controller' => "ExampleController",
    ]);
    ?>

    <?php
    echo $this->Form->hidden('q',['id' => 'filters_form_q']);
    ?>
    <div class="btn-group">
        <?php
        echo $this->Form->input('country_id', [
            'type' => 'select',
            'label' => false,
//            'data-live-search' => true,
            'data-size' => 'auto',
            'templates' => [
                'inputContainer' => '{{content}}',
                'select' => '<select name="{{name}}" class="selectpicker show-menu-arrow show-tick" {{attrs}}>{{content}}</select>',
            ],
            'empty' => false]);
        ?>
    </div>
    <div class="btn-group">
        <?php
        echo $this->Form->input('gender', [
            'label' => false,
            'options' => [['value'=>'','text' => 'Both','title' => __('By Gender')],['value'=>'M','text' => 'Male','title' => 'By Gender'], ['value'=>'F','text' => 'Female','title' => 'By Gender']],
            'templates' => [
                'inputContainer' => '{{content}}',
                'select' => '<select name="{{name}}" class="selectpicker show-menu-arrow show-tick">{{content}}</select>',
            ],
            'empty' => false]);
        ?>
    </div>

    <div class="btn-group">
        <?php
        echo $this->Form->input('age', [
            'label' => false,
            'empty' => false,
            'data-size' => 'auto',
            'show-tick' => 'show-tick',
            'options' => $user->ageRange,
            'templates' => [
                'inputContainer' => '{{content}}',
                'select' => '<select name="{{name}}" class="selectpicker show-menu-arrow show-tick" {{attrs}}>{{content}}</select>',
            ],
        ]);
        ?>



    </div>

    <div class="btn-group">
        <?php
        echo $this->Form->input('language', [
            'label' => false,
            'empty' => false,
            'data-size' => 'auto',
            'show-tick' => 'show-tick',
            'templates' => [
                'inputContainer' => '{{content}}',
                'select' => '<select name="{{name}}" class="selectpicker show-menu-arrow show-tick" {{attrs}}>{{content}}</select>',
            ],
        ]);
        ?>

    </div>
    <?php echo $this->Form->end(); ?>
</div>
