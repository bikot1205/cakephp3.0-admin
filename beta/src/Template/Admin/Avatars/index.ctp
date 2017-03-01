<?php
$this->Html->addCrumb($modelClass, null);


$tableHeaders[] = array($this->Paginator->sort(__('id'), 'ID') => array('class' => 'id_class text-center2', 'style' => 'width:10%'));
$tableHeaders[] = array('Avatar Image' => array('style' => 'width:50%'));
$tableHeaders[] = array($this->Paginator->sort(__('status')) => array('class' => 'id_class text-center2', 'style' => 'width:20%'));

$tableHeaders[] = array($this->Paginator->sort(__('created'), 'Added Date') => array('class' => 'date-class', 'style' => 'width:10%'));
$tableHeaders[] = array('Actions' => array('class' => 'action-btn-2 text-center', 'style' => 'width:10%'));


$rows = array();
if ($success && $avatars->count() > 0) {
    foreach ($avatars as $key => $listOne) {

        $image = $this->Awesome->image('Avatars/image', $listOne->image, ['tag' => false]);
        $row = array();
        $row[] = $listOne->id;
        
        $row[] = $this->Html->link($this->Awesome->image('Avatars/image', $listOne->image, ['class' => 'img-circle thumb-md clearfix','title' => 'click to view full image']), $image, ['class' => 'image-popup', 'escape' => false]);

        $row[] = $this->Form->checkbox('status', [
            'checked' => $listOne->status,
            'class' => "switchery_with_action",
            'data-size'=>"small",
            'data-model' => $modelClass,
            'data-id' => $listOne->id,
            'data-field' => 'status'
            ]);

        $row[] = $this->Awesome->date($listOne->created);

        $links = $this->Form->postLink(
                '<i class="fa fa-times"></i>', ['action' => 'delete', $listOne->id], ['escape' => false, 'class' => 'btn btn-xs red delete_btn tooltips', 'confirm' => __('Are you sure you want to delete this ? ')]);


        $row[] = array($links, array('class' => 'text-center'));
        $rows[] = $row;
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="row">
                <?php
                echo $this->Form->create($avatar, [
                    'novalidate' => true,
                    'type' => 'file',
                ]);
                ?>
                <div class="col-sm-12 col-md-4 col-lg-4 m-t-20 ">
                    <h4 class="m-b-10 header-title text-right"><b>Upload Avatar </b></h4>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4">
                    <?php
                    echo $this->Form->input('image[]', [
                        'label' => "",
                        'multiple' => 'multiple',
                        'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                    echo $this->Form->error('image');
                    ?>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 m-t-20 ">
                    <?php echo $this->Form->button('Upload Avator', array('class' => 'btn btn-success')); ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="m-t-0 m-b-10 header-title"><b><?php echo $title; ?></b></h4>

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

<?php /*
  <div class="row" style="margin-top: -25px !important">
  <div class="portfolioContainer">
  <?php
  if ($success && $avatars->count() > 0) {
  foreach ($avatars as $key => $listOne) {
  $image = $this->Awesome->image('Avatars/image', $listOne->image, ['tag' => false]);
  ?>
  <div class="col-sm-4 col-lg-2 col-md-2 webdesign illustrator">
  <div class="gal-detail thumb">
  <?php echo $this->Form->postLink(
  '<i class="fa fa-times"></i>', ['action' => 'delete', $listOne->id], ['escape' => false, 'class' => 'btn btn-icon waves-effect waves-light btn-white btn-xs pull-right', 'confirm' => __('Are you sure you want to delete this ? ')]);
  ?>
  <a href="<?php echo $image; ?>" class="image-popup" title="Avatar-<?php echo $listOne->image; ?>">
  <?php echo $this->Awesome->image('Avatars/image', $listOne->image, ['class' => 'thumb-img']); ?>
  </a>

  <h4></h4>

  </div>
  </div>
  <?php
  }
  }
  ?>




  </div>
  </div> <!-- End row -->
 */ ?>
<?php /*
  $this->extend('/Common/Admin/index');
  $this->Html->addCrumb($modelClass, null);


  $tableHeaders[] = array($this->Paginator->sort(__('id'), 'ID') => array('class' => 'id_class text-center2','style' => 'width:10%'));
  $tableHeaders[] = array('Avatar' => array('style' => 'width:70%'));

  $tableHeaders[] = array($this->Paginator->sort(__('created'), 'Added Date') => array('class' => 'date-class','style' => 'width:10%'));
  $tableHeaders[] = array('Actions' => array('class' => 'action-btn-2 text-center','style' => 'width:10%'));

  $this->append('table_head', $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'), array('class' => 'sorting')));

  $tableHeaders = array();


  $this->append('form-start', $this->Form->create($modelClass, array(
  'type' => 'post',
  'class' => 'form-horizontal list_data_form',
  'novalidate' => true,
  )));



  $rows = array();
  if ($success && $avatars->count() > 0) {
  foreach ($avatars as $key => $listOne) {
  $row = array();
  $row[] = $listOne->id;
  $row[] = $this->Awesome->image('Avatars/image', $listOne->image, ['class' => 'img-circle thumb-md clearfix']);

  $row[] = $this->Awesome->date($listOne->created);

  $links = $this->Form->postLink(
  '<i class="fa fa-times"></i>', ['action' => 'delete', $listOne->id], ['escape' => false, 'class' => 'btn btn-xs red delete_btn tooltips', 'confirm' => __('Are you sure you want to delete this ? ')]);


  $row[] = array($links, array('class' => 'text-center'));
  $rows[] = $row;
  }
  }



  if(!empty($rows)){
  $this->append('table_row', $this->Html->tableCells($rows));
  }


  $actionBtns .= $this->Html->link('Add New', ['action' => 'add'], ['class' => 'btn btn-default']);
  $this->assign('actionBtns', $actionBtns);
 */ ?>

