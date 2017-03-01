<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <!--<h4 class="m-t-0 m-b-30 header-title"><b><?php //echo $title;?></b></h4>-->
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    echo $this->fetch('form');
                    
                    if (!$submitBtn = trim($this->fetch('btn-submit'))):
                        $submitBtn = $this->Form->button('Submit', array('class' => 'btn btn-primary'));
                    endif;

                    if (!$caneclBtn = trim($this->fetch('btn-cancel'))):
                        $caneclBtn = $this->Html->link('Cancel', array('action' => 'index'), array('class' => 'btn btn-default m-l-5'));
                    endif;
                    ?>
                    <div class="form-group m-b-0">
                        <div class="col-sm-offset-4 col-sm-9 m-t-15">
                            <?php 
                            echo $submitBtn;
                            echo $caneclBtn;
                            ?>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
