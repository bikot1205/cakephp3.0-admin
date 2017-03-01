<?php
$this->Html->addCrumb('Rooms', ['controller' => 'rooms', 'action' => 'index']);
$this->Html->addCrumb('Details', null);
$this->Html->addCrumb($room->name, null);
?>
<div class="row">
    <div class="col-md-3 col-lg-3">
        <div class="profile-detail card-box">
            <div>
                <?php echo $this->Awesome->image('Rooms/icon', $room->icon, ['class' => 'img-circle thumb-md clearfix', 'style' => 'width: 40px;height: 40px;']); ?>
                <h4 class="text-uppercase font-600"><?php echo $room->name; ?></h4>
                <ul class="list-inline status-list m-t-20">
                    <li>
                        <h3 class="text-primary m-b-5"><?= $room->occupancy; ?></h3>
                        <p class="text-muted">Occupancy</p>
                    </li>

                    <!-- <li>
                        <h3 class="text-success m-b-5">0</h3>
                        <p class="text-muted">Users</p>
                    </li> -->
                </ul>
                <?php
                echo $this->Form->postLink(
            'Delete', ['action' => 'delete', $room->id], ['escape' => false, 'class' => 'btn btn-danger', 'confirm' => __('Are you sure you want to delete this ? ')]);
                
                 $this->Form->postLink('Delete', array('action' => 'index'), array('class' => 'btn btn-danger'));
                ?>
                
                <hr>

                <div class="text-left">
                    <p class="text-muted font-13"><strong>Type :</strong> <span class="m-l-15"><?php echo ($room->type == 1 ) ? 'Public' : 'Private'; ?></span></p>

                    <p class="text-muted font-13"><strong>File Sharing :</strong> <span class="m-l-15"><?php echo ($room->file_share == 1 ) ? 'Yes' : 'No'; ?></span></p>
                    <p class="text-muted font-13"><strong>Moderator :</strong> <span class="m-l-15"><?php echo $this->Html->link($room->moderator->name, ['controller' => 'users', 'action' => 'view', 'slug' => $room->moderator->slug]); ?></span></p>

                </div>

            </div>

        </div>

    </div>


    <div class="col-lg-9 col-md-9">

        <ul class="nav nav-tabs tabs">
            <li class="active tab"> 
                <a href="#settings" data-toggle="tab" aria-expanded="false"> 
                    <span class="visible-xs"><i class="fa fa-cog"></i></span> 
                    <span class="hidden-xs">Settings</span> 
                </a> 
            </li> 
            <li class="tab">
                <a href="#design" data-toggle="tab" aria-expanded="false"> 
                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                    <span class="hidden-xs">Room Design</span> 
                </a> 
            </li>

            <li class="tab">
                <a href="#users" data-toggle="tab" aria-expanded="false"> 
                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                    <span class="hidden-xs">Users</span> 
                </a> 
            </li>
            <li class="tab">
                <a href="#chatHistory" data-toggle="tab" aria-expanded="false"> 
                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                    <span class="hidden-xs">Chat History</span> 
                </a> 
            </li> 
        </ul> 

        <div class="tab-content"> 


            <div class="tab-pane active" id="settings"> 
                <?php
                echo $this->Form->create($room, [
                    'novalidate' => true,
                    'type' => 'file',
                    'class' => 'form-horizontal card-box'
                ]);
                ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Room Name <span>*</span></label>
                    <?php
                    echo $this->Form->input('name', [
                        'label' => false,
                        'type' => 'text',
                        'autocomplete' => 'off',
                        'templates' => [
                            'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}</div>',
                            'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                        ],
                    ]);
                    ?>

                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">Room Moderator <span>*</span></label>
                    <?php
                    echo $this->Form->input('moderator', [
                        'label' => false,
                        'type' => 'text',
                        'id' => 'moderator',
                        'value' => $room->moderator->name . '-' . $room->moderator->id,
                        'templates' => [
                            'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}</div>',
                            'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                        ],
                        'class' => 'form-control'
                            ]
                    );

                    echo $this->Form->input('moderator_id', [
                        'type' => 'hidden',
                        'value' => $room->moderator->id,
                        'id' => 'moderator_id',
                            ]
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">Occupancy level <span>*</span></label>
                    <?php
                    echo $this->Form->input('occupancy', [
                        'label' => false,
                        'options' => $room->occupancyLevels,
                        'templates' => [
                            'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}</div>',
                            'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                        ],
                        'empty' => 'Occupancy level',
                        'class' => 'form-control select2'
                            ]
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">Room Type <span>*</span></label>
                    <?php
                    echo $this->Form->input('type', [
                        'label' => false,
                        'options' => $room->types,
                        'empty' => 'Room Type',
                        'templates' => [
                            'inputContainer' => '<div class="col-lg-5 {{type}}{{required}}">{{content}}</div>',
                            'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                        ],
                        'class' => 'form-control select2']);
                    ?>

                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">Room Icon</label>
                    <?php
                    $image = '';
                    '<div class="thumbnail" style="width:200px;">' . $this->Awesome->image('Rooms/image', $room['image'], ['class' => 'img-responsive clearfix']) . '

              </div>';
                    echo $this->Form->input('icon', [
                        'templates' => [
                            'inputContainer' => '<div class="col-lg-5 mt10 {{type}}{{required}}">{{content}}</div>',
                            'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                            'file' => $image . '<input type="file" name="{{name}}"{{attrs}}>'],
                        'label' => false,
                        'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                    ?>
                </div>

                <?php 
                echo '<div class="form-group">
                <label class="control-label col-sm-8 col-md-4">File Sharing</label>
                <div class="switchery-demo col-sm-8 col-md-4">
                    ' . $this->Form->checkbox('file_share', [
                        'data-plugin' => "switchery",
                        'data-color' => "#5d9cec",
                        'data-field' => 'file_share'
                        ]) .
                    '</div>
                </div>';

                ?>

                <?php 
                echo '<div class="form-group">
                <label class="control-label col-sm-8 col-md-4">Status</label>
                <div class="switchery-demo col-sm-8 col-md-4">
                    ' . $this->Form->checkbox('status', [
                        'data-plugin' => "switchery",
                        'data-color' => "#5d9cec",
                        'data-field' => 'status'
                        ]) .
                    '</div>
                </div>';

                ?>

                <div class="form-group m-b-0">
                    <div class="col-sm-offset-3 col-sm-9 m-t-15">
                        <?php
                        $submitBtn = $this->Form->button('Update Room', array('class' => 'btn btn-info'));
                        $caneclBtn = $this->Html->link('Cancel', array('action' => 'index'), array('class' => 'btn btn-default m-l-5'));
                        echo $submitBtn;
                        echo $caneclBtn;
                        ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div> 

            <div class="tab-pane active" id="design">
                <?php
                echo $this->Form->create($room, [
                    'url' => ['action' => 'updateDesign'],
                    'novalidate' => true,
                    'type' => 'file',
                    'id' => 'updateRoomDesign',
                    'class' => 'form-horizontal card-box'
                ]);

                echo $this->Form->hidden('user_meta', ['id' => 'user_meta']);
                ?>


                <div class="form-group">
                    <label class="col-lg-3 control-label">Upload Room Design</label>
                    <?php
                    
                    echo $this->Form->input('image', [
                        'templates' => [
                            'inputContainer' => '<div class="col-lg-5 mt10 {{type}}{{required}}">{{content}}</div>',
                            'inputContainerError' => '<div class="col-lg-5 input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
                            'file' => '<input type="file" name="{{name}}"{{attrs}}>'],
                        'label' => false,
                        'id' => 'file',
                        'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
                    ?>
                    <span class="loading"><h5 class="text-success">Loading...</h5></span>
                </div>

                <div class="">
                    <canvas id="canvas_mage" width="790" height="300" style="border: 1px solid #ccc;"></canvas>
                </div>

                <div class="form-group m-b-0">
                    <div class="col-sm-offset-4 col-sm-9 m-t-15">
                        <?php
                        $submitBtn = $this->Form->button('Update', array('class' => 'btn btn-info'));
                        $caneclBtn = $this->Html->link('Cancel', array('action' => 'index'), array('class' => 'btn btn-default m-l-5'));
                        echo $submitBtn;
                        //echo $caneclBtn;
                        ?>
                    </div>
                </div>

            </div>

            <div class="tab-pane" id="users">
                <table class="tablesaw table tablesaw-swipe table-bordered1">
                        <thead>
                            <?php 
                                $tableHeaders[] = array($this->Paginator->sort(__('id'), 'ID') => array('class' => 'id_class text-center','style2' => 'width:5%'));
                                $tableHeaders[] = array('Avatar' => array('style2' => 'width:5%'));
                                $tableHeaders[] = array($this->Paginator->sort(__('first_name'), 'Name') => array('style2' => 'width:25%'));
                                //$tableHeaders[] = array($this->Paginator->sort(__('email')) => array('style2' => 'width:30%'));
                                $tableHeaders[] = array($this->Paginator->sort(__('age'), 'Age & Gender') => array('style2' => 'width:15%'));
                                $tableHeaders[] = array($this->Paginator->sort(__('countries.name'),'Country') => array('style2' => 'width:15%'));
                                $tableHeaders[] = array($this->Paginator->sort(__('muslim_since')) => array('style2' => 'width:15%'));

                                $tableHeaders[] = array('Type' => array('style2' => 'width:10%'));
                                $tableHeaders[] = array($this->Paginator->sort(__('status')) => array('style2' => 'width:10%'));


                                echo $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'));
                            ?>
                        </thead>

                        <tbody>
                            <?php 
                            $rows = array();
                            if ($members->count() > 0) {
                                foreach ($members->toArray() as $key => $listOne) {
                                    $listOne = $listOne->user;
                                    $row = array();
                                    $row[] = array($listOne['id'], array('class' => 'text-center'));
                                    $row[] = $this->Awesome->userImage($listOne);
                                    $row[] = $this->Html->link($listOne->name,['controller' => 'users','action' => 'view','slug' => $listOne->slug]);

                                    //$row[] = $listOne->email;
                                    $row[] = $listOne->age . ' | ' . $listOne->gender;

                                    $row[] = $listOne->country->name;
                                    $row[] = $listOne->muslim_since;
                                    $row[] = $this->cell('User::subscription', ['user' => $listOne->id]);
                                    $row[] = $this->Form->checkbox('status', [
                                        'checked' => $listOne->status,
                                        'class' => "switchery_with_action",
                                        'data-size'=>"small",
                                        'data-model' => 'Users',
                                        'data-id' => $listOne->id,
                                        'data-field' => 'status'
                                        ]);
        

                                    $rows[] = $row;
                                }

                                echo $this->Html->tableCells($rows);
                            }
                            ?>
                        </tbody>
                </table>
            </div>

        </div>
    </div>

</div>


<?php
echo $this->start('jsSection');
echo $this->Html->script(['fabric.min.js']);
?>

<script>
    (function () {
        $('.loading').hide();
        var canvas = this.__canvas = new fabric.Canvas('canvas_mage', {
            hoverCursor: 'pointer',
            selection: false
        });
        var json = <?php echo (!empty($room->user_meta)) ? $room->user_meta : '{}'; ?>;

        <?php if(!empty($room->image)){ ?>
            canvas.setBackgroundImage(SiteUrl+'files/Rooms/image/<?php echo $room->image;?>', canvas.renderAll.bind(canvas));       
        <?php } else{ ?>
            canvas.setBackgroundImage(SiteUrl+'img/banner-img.jpg', canvas.renderAll.bind(canvas));       
        <?php } ?>
       

        canvas.on({
            'object:moving': function (e) {
                e.target.opacity = 0.5;
            },
            'object:modified': function (e) {
                e.target.opacity = 1;
            },

        });

        var occupancy = <?php echo $room->occupancy; ?>;

        if (json.objects) {
            var length = json.objects.length;
            console.log(occupancy);
            console.log(length);
            $.each(json.objects, function (index, value) {
                if (index >= occupancy) {
                    return false;
                }
                fabric.Image.fromURL(webroot + 'img/user-map.png', function (img) {
                    img.set({
                        left: value.left,
                        top: value.top,
                        // angle: fabric.util.getRandomInt(0, 90),
                        id: index
                    });
                    img.id = index;
                    img.perPixelTargetFind = true;
                    img.targetFindTolerance = 4;
                    img.hasControls = img.hasBorders = false;

                    canvas.add(img);
                });

            });

            if (length < occupancy) {
                for (var i = 0, len = occupancy - length; i < len; i++) {
                    fabric.Image.fromURL(webroot + 'img/user-map.png', function (img) {
                        img.set({
                            left: fabric.util.getRandomInt(0, 760),
                            top: fabric.util.getRandomInt(0, 275),
                            // angle: fabric.util.getRandomInt(0, 90),
                            id:  i
                        });
                        img.id =  i
                        img.perPixelTargetFind = true;
                        img.targetFindTolerance = 4;
                        img.hasControls = img.hasBorders = false;
                        //img.id = i;
                        //img.scale(fabric.util.getRandomInt(50, 100) / 100);

                        canvas.add(img);
                    });
                }
            }

        } else {
            for (var i = 0, len = occupancy; i < len; i++) {
                fabric.Image.fromURL(webroot + 'img/user-map.png', function (img) {
                    img.set({
                        left: fabric.util.getRandomInt(0, 760),
                        top: fabric.util.getRandomInt(0, 275),
                        // angle: fabric.util.getRandomInt(0, 90),
                        id: "Prakash" + i
                    });
                    img.id = "Prakash1" + i
                    img.perPixelTargetFind = true;
                    img.targetFindTolerance = 4;
                    img.hasControls = img.hasBorders = false;
                    //img.id = i;
                    //img.scale(fabric.util.getRandomInt(50, 100) / 100);

                    canvas.add(img);
                });
            }
        }

        $('#updateRoomDesign').submit(function (e) {
            $('.loading').show();
            e.preventDefault();
            $('#user_meta').val(JSON.stringify(canvas));
             var data = $(this).serialize();
            var form = $(this).attr('action');
            $.ajax({
                type: 'post',
                url: form,
                data: data,
                dataType: 'json',
                success: function (data) {
                    $('.loading').hide();
                    alert(data.message);
                }
            });
        });

        $('#file').change(function(){
                $('.loading').show();
                var form = $('#updateRoomDesign').attr('action');
                $.ajax({
                    enctype: 'multipart/form-data',
                    url: form, // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    data: new FormData($('#updateRoomDesign')[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache: false,             // To unable request pages to be cached
                    dataType:'json',
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: function(data)   // A function to be called if request succeeds
                    {
                        console.log(data.room);
                        $('.loading').hide();
                        canvas.setBackgroundImage(SiteUrl+'files/Rooms/image/'+data.room.image, canvas.renderAll.bind(canvas));  
                        
                    }
            });
        });

    })();
</script>
<?php echo $this->end(); ?>

