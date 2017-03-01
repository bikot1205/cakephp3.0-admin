<div class="group-chat-option-bar" <?php if($display == false) { ?>id="filter-group" <?php } ?>>
    <?php if($display == true) { ?>
    <div class="all-category">
        <div class="category-icon">
            <a href="" class="active"><span class="all active"></span>All</a>
        </div>
    </div>
    <?php  } ?>
    <div class="sub-category <?php if($display == false) { echo 'sub-category-full';} ?>">
        <div id="" class="owl-carousel owl-demo">
            <?php foreach($rooms as $room) { ?>
                <div class="category-icon">
                    <a  href="javascript:void(0)" ng-click="joinRoom('<?php echo $room->slug;?>')" role="button" class="btn" data-toggle="modal">
                    <!-- <div style="width: 40px; height: 40px; overflow: hidden;">
                        <?php //echo $this->Awesome->image('Rooms/icon', $room->icon, ['width' => '40']);?>
                    </div> -->
                    <span class="<?php echo $room->slug;?> <?php echo ($this->request->session()->read('room') == $room->slug) ? 'active' : '';?>"></span>
                    <?php echo $room->name;?>
                        
                    </a>
                </div>
            <?php } ?>
            
        </div>
    </div>
</div>