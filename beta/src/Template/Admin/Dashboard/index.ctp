<!-- Page-Title -->
<?php $this->Html->addCrumb('Dashboard', null);?>

<!-- Start row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-dark header-title">Total Users</h4>
            <hr>
            <div class="widget-chart text-center" id="totalUsers" style="height: 300px;"></div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0">Top Rooms</h4>
            <div id="topRooms" style="height: 340px;"></div>
        </div>
    </div>

</div>
<!-- end row -->


<!-- Start row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0">Top 10 Countries</h4>
            <div id="topCountries" style="height: 303px;"></div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0">Top 10 Languages</h4>
            <div id="topLanguages" style="height: 303px;"></div>
        </div>
    </div>

</div>
<!-- end row -->


<!-- Start row -->
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0">Users by age</h4>
            <div id="ageGroup" style="height: 303px;"></div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0">Users by gender</h4>
            <div id="genderGroup" style="height: 303px;"></div>
        </div>
    </div>

</div>
<!-- end row -->

<?php
$this->start('jsSection');
?>
<style type="text/css">
    #totalAppoinments {
    width   : 100%;
    height  : 500px;
}                                   
</style>

<script src="<?php echo $this->request->webroot;?>assets/plugins/amcharts/amcharts/amcharts.js"></script>
<script src="<?php echo $this->request->webroot;?>assets/plugins/amcharts/amcharts/serial.js"></script>
<script src="<?php echo $this->request->webroot;?>assets/plugins/amcharts/amcharts/themes/light.js"></script>
 <script type="text/javascript" src="<?php echo $this->request->webroot;?>assets/plugins/amcharts/amcharts/plugins/export/export.js"></script>

 <script src="<?php echo $this->request->webroot;?>assets/plugins/amcharts/amcharts/plugins/dataloader/dataloader.min.js"></script>

<script src="<?php echo $this->request->webroot;?>assets/js/charts.js"></script>

<link  type="text/css" href="<?php echo $this->request->webroot;?>assets/plugins/amcharts/amcharts/plugins/export/export.css" rel="stylesheet">
<script type="text/javascript">
    DashboardCharts.init();
    
</script>

<?php echo $this->end();?>
 
