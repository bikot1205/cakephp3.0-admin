<style>
    .form-inline .form-control {
        width: 100% !important;
        margin-bottom: 10px;
    }
    /*.tablesaw-swipe{border: 1px solid #ebeff2}*/
</style>

<?php
$this->Html->addCrumb($modelClass, null);



$tableHeaders[] = array('Smile Image' => array('style2' => 'width:50%'));

$tableHeaders[] = array($this->Paginator->sort(__('name'), 'Nmae') => array('class' => 'id_class text-center2', 'style2' => 'width:10%'));

$tableHeaders[] = array($this->Paginator->sort(__('code'), 'Code') => array('class' => 'id_class text-center2', 'style2' => 'width:10%'));
$tableHeaders[] = array($this->Paginator->sort(__('status')) => array('class' => 'date-class', 'style2' => 'width:20%'));

$tableHeaders[] = array($this->Paginator->sort(__('created'), 'Added Date') => array('class' => 'date-class', 'style2' => 'width:10%'));
$tableHeaders[] = array('Actions' => array('class' => 'action-btn-2 text-center', 'style2' => 'width:10%'));


$rows = array();
if ($success && $smiles->count() > 0) {
    foreach ($smiles as $key => $listOne) {

        $row = array();
        
        $row[] = $this->Awesome->image('Smiles/image', $listOne->image, ['class' => 'img-circle thumb-md clearfix']);
        
        $row[] = $listOne->name;
        $row[] = $listOne->code;
        $row[] = $this->Form->checkbox('status', [
            'checked' => $listOne->status,
            'class' => "switchery_with_action",
            'data-size'=>"small",
            'data-model' => $modelClass,
            'data-id' => $listOne->id,
            'data-field' => 'status'
            ]);
        $row[] = $this->Awesome->date($listOne->created);

         $links = $this->Html->link(__('<i class="fa fa-edit"></i>'), array('action' => 'edit', $listOne->id), array('class' => 'btn btn-xs green tooltips', 'data-placement' => "top", 'data-original-title' => "Delete", 'title' => 'Edit', 'escape' => false));

        $links .= $this->Form->postLink(
            '<i class="fa fa-times"></i>', ['action' => 'delete', $listOne->id], ['escape' => false, 'class' => 'btn btn-xs red delete_btn tooltips', 'confirm' => __('Are you sure you want to delete this ? ')]);


        $row[] = array($links, array('class' => 'text-center'));
        $rows[] = $row;
    }
}
?>

<!-- <div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="row">
                <?php
                echo $this->Form->create($smiles, [
                    'novalidate' => true,
                    'type' => 'file',
                    ]);
                    ?>
                    <div class="col-sm-12 col-md-4 col-lg-4 m-t-20 ">
                        <h4 class="m-b-10 header-title text-right"><b>Upload Smile </b></h4>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <?php
                        echo $this->Form->input('image[]', [
                            'label' => "",
                            'multiple' => 'multiple',
                            'class' => 'filestyle2', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                        echo $this->Form->error('image');
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4 m-t-20 ">
                        <?php echo $this->Form->button('Upload', array('class' => 'btn btn-success')); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div> -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row m-b-10">
                    <div class="col-sm-6 text-xs-center">
                        <h4 class="m-t-0 m-b-10 header-title"><b><?php echo $title; ?></b></h4>
                    </div>
                    <div class="col-sm-6 text-xs-center text-right">
                        <div class="button-list pull-right pull-right">
                            <?php echo $this->Html->link('Add New', ['action' => 'add'], ['class' => 'btn btn-default']);?>

                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="tablesaw table tablesaw-swipe table-bordered1">
                        <thead>
                            <?php echo $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'), array('class' => 'sorting')); ?>
                        </thead>

                        <tbody>
                            <?php echo $this->Html->tableCells($rows); ?>
                        </tbody>
                    </table>
                    <?php if ($rows == null) { ?>
                    <div class="btn btn-block btn--md btn-white waves-effect waves-light">
                        <strong>Oops!</strong> No records found.
                    </div>
                    <?php } ?>
                </div>
                <?php echo $this->Form->end(); ?>

                <?= $this->element('pagination')?>

            </div>
        </div> <!-- end col -->

    </div>




