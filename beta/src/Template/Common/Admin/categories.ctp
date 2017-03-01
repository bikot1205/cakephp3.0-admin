
<script>
    var items = <?php echo $this->fetch('table_row');
?>
</script>
<style>
    .form-inline .form-control {
        width: 100% !important;
        margin-bottom: 10px;
    }
    table tr td {
        cursor: move;

    }
    /*.tablesaw-swipe{border: 1px solid #ebeff2}*/
</style>
<?php if ($searhForm = $this->fetch('search')) { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="m-t-0 m-b-10 header-title"><b>Search</b></h4>
                <div class="row">
                    <?php echo $searhForm; ?>
                    <div class="form-group col-md-3">
                        <?php
                        if ($btn = trim($this->fetch('btn'))):
                            echo $btn;
                        endif;
                        ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row hide" ng-controller="sortableController" ng-cloak data-id="categories" id="categories">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="m-t-0 m-b-10 header-title"><b><?php echo $title; ?></b></h4>
            <?php
            echo $this->Form->create($modelClass, array(
                'novalidate' => true,
                'class' => 'form-inline bulk_form',
                'url' => $this->Url->build(['controller' => 'blogs', "action" => "publishAll"], ['base' => false])
            ));
            ?>
            <div class="form-inline m-b-10">
                <div class="row">
                    <div class="col-sm-8 text-xs-center">
                        <?php echo $this->fetch('bulk_action'); ?>


                    </div>
                    <div class="col-sm-4 text-xs-center text-right">
                        <div class="btn-group m-r-10">
                            <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Records per Page <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:void(0)"><a href="<?php echo $this->Paginator->generateUrl(['limit' => 20]); ?>">20 Records</a></a></li>
                                <li><a href="javascript:void(0)"><a href="<?php echo $this->Paginator->generateUrl(['limit' => 50]); ?>">50 Records</a></a></li>
                                <li><a href="javascript:void(0)"><a href="<?php echo $this->Paginator->generateUrl(['limit' => 100]); ?>">100 Records</a></a></li>
                            </ul>
                        </div>
                        <div class="button-list pull-right pull-right">
                            <?php
                            echo $this->fetch('actionBtns');
                            ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table class="tablesaw table tablesaw-swipe table-bordered1">
                    <thead>
                        <?php echo $this->fetch('table_head'); ?>
                    </thead>

                    <tbody ui-sortable="sortableOptions" ng-model="items">
                        <?php //echo $tableRows = $this->fetch('table_row');  ?>
                        <tr ng-repeat="(key, item) in items">
                            <td class="text-center">{{item.id}}</td>
                            <td>{{item.title}}</td>
                            <?php if (empty($parent_id)) { ?>
                                <td ng-bind-html="trustAsHtml(item.child_count)" class="text-left"></td>
                            <?php } ?>
                            <td ng-bind-html="trustAsHtml(item.status)"></td>
                            <td ng-bind-html="trustAsHtml(item.created)"></td>
                            <td ng-bind-html="trustAsHtml(item.links)">{{item.links}}</td>
                        </tr>
                    </tbody>
                </table>

                

            </div>
            <?php echo $this->Form->end(); ?>
            <div class="row">

                <div class="col-sm-6 pagination">
                    <?php
                    // Change a template
                    $config = [
                        'nextActive' => '<li class="next"><a rel="next" href="{{url}}">{{text}}</a></li>',
                        'nextDisabled' => '<li class="next disabled"><span>{{text}}</span></li>',
                        'prevActive' => '<li class="prev"><a rel="prev" href="{{url}}">{{text}}</a></li>',
                        'prevDisabled' => '<li class="prev disabled"><span>{{text}}</span></li>',
                        'counterRange' => '{{start}} - {{end}} of {{count}}',
                        'counterPages' => '{{page}} of {{pages}}',
                        'first' => '<li class="first"><a href="{{url}}">{{text}}</a></li>',
                        'last' => '<li class="last"><a href="{{url}}">{{text}}</a></li>',
                        'number' => '<li><a href="{{url}}">{{text}}</a></li>',
                        'current' => '<li class="active"><span>{{text}}</span></li>',
                        'ellipsis' => '<li class="ellipsis"><a href="#">...</a></li>',
                        'sort' => '<a href="{{url}}">{{text}}</a>',
                        'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
                        'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
                        'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
                        'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
                    ];
                    $this->Paginator->templates($config);

                    echo $this->Paginator->counter(
                            'Page {{page}} of {{pages}}, showing {{current}} records out of
     {{count}} total'
                    );
                    //, starting on record {{start}}, ending on {{end}}
                    ?>
                </div>
                <div class="col-sm-6 text-right">
                    <ul class="pagination">
                        <?= $this->Paginator->prev('<i class="fa fa-fw fa-chevron-left"></i>', ['escape' => false]) ?>
                        <?= $this->Paginator->numbers(['first' => 2, 'last' => 2, 'before' => false, 'after' => false, 'modulus' => 6]) ?>
                        <?= $this->Paginator->next('<i class="fa fa-fw fa-chevron-right"></i>', ['escape' => false]) ?>
                    </ul>
                </div>
            </div>


        </div>
    </div> <!-- end col -->

</div>

