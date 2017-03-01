myApp.config(function(toastrConfig) {
  angular.extend(toastrConfig, {
    autoDismiss: false,
    containerId: 'toast-container',
    maxOpened: 0,    
    newestOnTop: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    preventOpenDuplicates: false,
    target: 'body'
});
});
myApp.controller('roomController', function ($scope, $attrs, $http, $sce, $filter, $timeout, blockUILoading, $interval, toastr) {
    blockUILoading.start('.box-shadow');
    $scope.trustAsHtml = function (html) {
        return $sce.trustAsHtml(html);
    };

    $scope.FileAllowdTypes = ['png','gif','jpg','jpeg'];
    $scope.roomDetails = roomDetails;
    $scope.accupants = accupants;

    $scope.movingUsers = {};
    //Chatbox, RecentChat, MyChat
    $scope.activeTab = {'Default' : true};

    $scope.$on('$routeChangeSuccess', function () {
      // run some code to do your animations
      $scope.Tab = {'Default' : true};
    });
    
    $scope.activeContents = function(ActiceTab){

        if(ActiceTab == 'RecentChat'){
            $scope.RecentChats();
            $scope.Tab = {'RecentChat' : true};
        }

        else if(ActiceTab == 'Chatbox'){
            var viewPortHeight = window.innerHeight - 265;
            var viewPortHeight2 = $(window).height() - 550;
            $('#os3').height(viewPortHeight2);
            viewPortHeight = viewPortHeight-10;
            
            $('.detail-area').height(viewPortHeight + 61);
            $('#canvas_mage').width(840);
            $scope.Tab = {'Chatbox' : true};
        }
        else if(ActiceTab == 'MyChat'){
            $scope.Tab = {'MyChat' : true};
            $scope.MyChats();
        }
        else if(ActiceTab == 'Friends'){
            $scope.getFriends();
            $scope.Tab = {'Friends' : true};
        }
        else{
            $scope.Tab = {'Default' : true};
        }

        
    }

    $scope.RecentChats = function(){
        // blockUILoading.start('.box-shadow');
        $http({
            method: 'POST',
            url: SiteUrl + 'rooms/recent-chat',
        // data: {'slug':slug},
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        var viewPortHeight = window.innerHeight - 275;

        $('#recentChatList').height(viewPortHeight);
        blockUILoading.stop('.box-shadow');
        $scope.recentChats = data.recentChats;

    });

}
//edujustChatBox(275, 71, 156);
$scope.MyChats = function(){
        // blockUILoading.start('.box-shadow');
        $http({
            method: 'POST',
            url: SiteUrl + 'rooms/my-chat',
        // data: {'slug':slug},
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        var viewPortHeight = window.innerHeight - 275;
        $('#myChatList').height(viewPortHeight);
        $scope.myChats = data.recentChats;
            // blockUILoading.stop('.box-shadow');
            
        });

}


$scope.getFriends = function(){
    $http({
        method: 'POST',
        url: SiteUrl + 'friends/request',
        // data: {'slug':slug},
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        var viewPortHeight = window.innerHeight - 250;
        $('#friendsList').height(viewPortHeight);
        $scope.Friends = data.friends;

    });

}


$scope.sendInvitation = function(){
        // blockUILoading.start('.box-shadow');
        $http({
            method: 'POST',
            url: SiteUrl + 'rooms/send-invitation',
            data: {'emails':$scope.invitedEmails},
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        toastr.success(data.message);
        $('#inviteUsers').modal('hide');
        $('#send_invitation').val('');
        $scope.invitedEmails = '';

    });

}

$scope.activeContents(activeTab);

$http({
    method: 'POST',
    url: SiteUrl + 'rooms/room-data/'+$scope.roomDetails.slug,
        // data: {'slug':slug},
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        $scope.membersList = data.membersList;
        $scope.smiles = smiles = data.smiles;
        $scope.smilesCode =  smilesCode = data.smilesCode;
    });

    $scope.connected = false;

    $scope.groupMembers = {};

    $scope.chatFile = '';

    // Private Chat User
    $scope.privateUser = [];

    
    var viewPortHeight2 = $(window).height() - 602;
    $('#chat').height(viewPortHeight2);

    //$('.message-write-box').css({'position':'fixed','bottom':'60px','width':$('.content-section .chat-section .detail-area').width() - 16});

    $(".banner-up").click(function (e) {
        $("#filter-group").hide();

        $(".banner-down").slideToggle();
        $(".banner").slideToggle(autoHeightChatBox());
        $(".banner-up").slideToggle();

    });
    $(".banner-down").click(function (e) {
        $("#filter-group").hide();

        $(".banner-up").slideToggle();
        $(".banner").slideToggle(autoHeightChatBox());
        $(".banner-down").slideToggle();
        
    });
    $("#filter-btn").click(function (e) {
        //$(".banner").show();
        $("#filter-group").slideToggle();
        
    });

    $(document).on("click", ".smile_image_item", function() {

        var text = $('#input').val($('#input').val()+$(this).data('code'));
        $('#send_message').click();
        
    });

    $('.upload_icon').click(function(){$('#files').click();});

    function onChange(options) {
        $("#myChatrommTTT").popover('destroy'); 
        options.target.setCoords();
        canvas.forEachObject(function(obj) {
             var user = obj.id;
          if (obj === options.target) return;
          if(options.target.intersectsWithObject(obj) && !$scope.groupMembers[user]['busy']){
            options.target.lockMovementX = true;
            options.target.lockMovementY = true;
            options.target.top = obj.top;
            options.target.left = obj.left+50;
            options.target.opacity = 0.5;
            
            //Start Private Chat
            // Send Private Chat notification to reciever.
           

            // Cancel Previous Chat
            if($scope.privateUser.status == 'accepted'){
                $('.cancelled_private_chat').click(); 

            }
            $scope.privateUser = {};
            $scope.privateUser = $scope.groupMembers[user];


            $scope.privateUser.status = 'pending';

            $('.start_private_chat').click();

            $timeout(function(){
                if($scope.privateUser.status != 'accepted'){
                    $('.cancelled_private_chat').click(); 
                    $scope.privateUser = {};
                    $scope.moveUsers();    
                }
            }, 10000);

        }
        obj.setOpacity(options.target.intersectsWithObject(obj) ? 0.5 : 1);
    });
    }



    //$('#chat').optiscroll({maxTrackSize: 20});
    //$('#chat').optiscroll();

    //var chat = new Optiscroll(document.getElementById('chat'), {maxTrackSize: 20, preventParentScroll: true});

    function autoHeightChatBox(){
        var viewPortHeight2 = $(window).height() - 353;
        if ($(".banner").css('display') == 'none') {
            var viewPortHeight2 = $(window).height() - 602;
        }
        $('#chat').animate({height:viewPortHeight2});
    }


    

    $scope.RoomBackgroundImage = SiteUrl+'img/banner-img.jpg';
    if(roomDetails.image){
        $scope.RoomBackgroundImage = SiteUrl+'files/Rooms/image/'+roomDetails.image;
    }
    var canvas = this.__canvas = new fabric.Canvas('canvas_mage', {
        hoverCursor: 'pointer',
        selection: false,
        allowTouchScrolling : true,
    });
    var radius = 20;
    canvas.setBackgroundImage($scope.RoomBackgroundImage, canvas.renderAll.bind(canvas));        

    
    canvas.on({
        'object:moving': onChange,
        //'object:scaling': onChange,
       // 'object:rotating': onChange,

        // 'object:moving': function (e) {
        //     console.log(e.target);
        //     e.target.opacity = 0.5;
        // },
        'object:modified': function (e) {
            //JSON.stringify(canvas);
            //console.log('modified');
            // var id = canvas.getActiveObject().get('id');
            // e.target.opacity = 1;
        },
        'object:selected' : function(e){
            if(e.target && e.target.text == undefined){
                showImageTools(e.target);
            }
        },
        'mouse:up': function (options) {
            canvas.forEachObject(function(obj) {
                if(options.target && !options.target.intersectsWithObject(obj)){
                   $scope.moveUsers();
               }
           });
        },
        'mouse:down': function (e) {

            $scope.movingUsers[e.target.id] = {
                'left' : e.target.left,
                'top' : e.target.top

            };

        },

        // 'mouse:over': function (e) {
        //     if(e.target && e.target.text == undefined){
        //         showImageTools(e.target);
        //     }
        // },
        'mouse:out': function (e) {
            if(e.target && e.target.text == undefined){
                //$("#myChatrommTTT").popover('hide'); 
            }
            //$("#myChatrommTTT").popover('destroy'); 
        },
    });

    $scope.getAvailablePossitionOnCanvas = function(){
        //console.log($scope.AvailableSpaceOnCanvas.length);

        var position = $scope.AvailableSpaceOnCanvas[Math.floor(Math.random()*$scope.AvailableSpaceOnCanvas.length)];

        // var position = 0;
        // $.each($scope.AvailableSpaceOnCanvas, function (index, value) {
        //     position = value;
        //     return;
        // });

        $scope.AvailableSpaceOnCanvas.splice(position,1);
        return position;
    }

    $scope.moveUsers = function() {
        $("#myChatrommTTT").popover('destroy'); 
        if($scope.groupMembers.length <= 0 ){
            return false;
        }
        var object = $scope.groupMembers;

        var newObject = {};

        $scope.getAllAvailableSpace();

        canvas.clear();
        canvas.setBackgroundImage($scope.RoomBackgroundImage, canvas.renderAll.bind(canvas));
        angular.forEach(object, function(value, key) {
            newObject[key] = value;
            $scope.updateUserPresenceOnCanvas(value);
        });

        $scope.groupMembers = newObject;
        
    }

    $interval(function() {
        if($scope.activeTab == 'Chatbox'){
            $scope.moveUsers();    
        }
    }, 60000);  


    $scope.getAllAvailableSpace = function(){
        $scope.AvailableSpaceOnCanvas = [];
        if (roomData) {
            $.each(roomData.objects, function (index, value) {
                $scope.AvailableSpaceOnCanvas.push(index) ;
            });
        }
    }

    $scope.getAllAvailableSpace();
    

    $scope.formatSrNumber = function(number) {
        if(number == 0 ){
            number++;
        }
        var char = number.toString().length;

        var formatSrNumber = number;
        switch(char) {
            case 1:
            formatSrNumber = "00"+formatSrNumber;
            break;
            case 2:
            formatSrNumber = "0"+formatSrNumber;
            break;
            default:
            formatSrNumber = formatSrNumber;
        }

        return formatSrNumber;
    }

    $scope.updateUserPresenceOnCanvas = function(userInfo){
        var lockMove = true;
        if(loggedIn.id == userInfo.id ){
            lockMove = false;
        }
        var index = $scope.getAvailablePossitionOnCanvas();
        var value = roomData.objects[index];
       //console.log(value);
       var leftM = value.left;
       var topM = value.top;
       var opacity = (userInfo.busy) ? 0.5 : 1;
       
       if($scope.privateUser.status == 'accepted' && userInfo.id == $scope.privateUser.id){
           leftM = 780;
           topM = 225;

       }
       if($scope.privateUser.status == 'accepted' && userInfo.id == loggedIn.id){
        leftM = 720;
        topM = 225;

   }


   fabric.Image.fromURL(userInfo.imageUrl, function (img) {
            // img.set({
            //     left: value.left,
            //     top: value.top,
            //     id: userInfo.slug,

            // });
            img.id = userInfo.slug;
            img.perPixelTargetFind = true;
            img.targetFindTolerance = 4;
            img.hasControls = img.hasBorders = false;
            img.lockMovementX = lockMove;
            img.lockMovementY = lockMove;
            img.width = 50;
            img.height = 50;

            var text = new fabric.Text(userInfo.first_name, {
              fontFamily: '"Roboto",sans-serif',
              fontSize: 11,
              fill:'#fff',
          });
            var top = (img.getBoundingRectHeight() / 2) - (text.width / 2) + 45;
            text.set("top", top);
            text.set("left", (img.getBoundingRectWidth() / 2) - (text.height / 2) - 10);

            var group = new fabric.Group([img, text], {
                left: leftM,
                top: topM - 20,
                opacity : opacity,
                lockMovementX: lockMove,
                lockMovementY: lockMove,
                hasControls: false,
                hasBorders: false,
                id : userInfo.slug,
                perPixelTargetFind: true,
                targetFindTolerance: 4,
            });


            canvas.add(group);
        });

        // var textHeight = value.top;
        // var text = canvas.add(new fabric.Text(userInfo.first_name, { 
        //     left: value.left+5, //Take the block's position
        //     top: textHeight+42, 
        //     fontSize:'11',
        //     fill:'#fff',
        //     fontFamily:'"Roboto",sans-serif',
        //     lockMovementX : true,
        //     lockMovementY : true,
        //     editable:false,
        //     hasControls:false,
        //     hasRotatingPoint:false,
        //     transparentCorners:false,
        //     id : userInfo.slug,
        // }));

    }

    function showImageTools (e) {
        index = e.id;
        var userInfo = $scope.membersList[index];
        // console.log(userInfo);

        var top = e.top - 95;
        var left = e.left - 45;

        var content = '<div class="detail-tooltip">';
        content += '<ul>';
        content += '<li class="filter-user-name"><img src="'+userInfo.imageUrl+'" alt="" width="50"> '+userInfo.name+'</li>';
        content += '<li>Muslim Since: <span>'+userInfo.muslim_since+'</span></li>';
        content += '<li>Gender: <span>'+userInfo.gender+'</span></li>';
        content += '<li>Age: <span>'+userInfo.age+'</span></li>';
        content += '</ul>';
        content += '</div>'

        $("#myChatrommTTT").popover({
            content: content,
            html: true,
            placement: 'top',
        }).popover('show'); 
        
        $(".popover").css({top: top, left: left});
    }

    $scope.removePrivateChat = function(){
        if(confirm(staticMessages.ExitChat)){
            $('.cancelled_private_chat').click(); 
            $scope.privateUser = {};
            $scope.moveUsers(); 
        }
    }


    function windowResize(){
       // $("#canvas_mage").outerHeight($(window).height()-$("#canvas_mage").offset().top- Math.abs($("#canvas_mage").outerHeight(true) - $("#canvas_mage").outerHeight()));
   }

    //windowResize();    
    $(window).resize(function(){
        windowResize();                      
    });

    

    // Get User details

    $scope.getUserDetail = function(jid){
        var data = $scope.membersList[jid];
        if(data == undefined){
            $http({
                method: 'POST',
                url: SiteUrl + 'users/getdetail',
                data: {'slug':jid},
                headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
            }).success(function (data) {
                var count = Object.keys($scope.groupMembers).length;
                $scope.groupMembers[jid] = data;
                $scope.groupMembers[jid]['sr'] = $scope.formatSrNumber(count);
                $scope.updateUserPresenceOnCanvas(data);
            });
        }
        else{
            $scope.updateUserPresenceOnCanvas(data);
            $timeout(function(){
                $scope.groupMembers[jid] = data;
            },100)
            
        }

        $scope.connected = true;
        
        
    }

    $scope.removeUser = function(nick) {
        var object = $scope.groupMembers;

        var newObject = {};

        $scope.getAllAvailableSpace();

        canvas.clear();
        canvas.setBackgroundImage($scope.RoomBackgroundImage, canvas.renderAll.bind(canvas));
        angular.forEach(object, function(value, key) {
          if(nick != key){
            newObject[key] = value;
            $scope.updateUserPresenceOnCanvas(value);
        }
    });

        $scope.groupMembers = newObject;
        
    }

    $scope.updateUserStatus = function(nick, status) {
        var user  = $scope.membersList[nick];
        user.busy = status;
        $scope.membersList[nick] = user;
    }

    $scope.getUserAttributeFromJID = function(jid,attr){
        if($scope.membersList[jid] != undefined){
            return $scope.membersList[jid][attr];    
        }
        return jid;
    }



    var Groupie = {
        connection: null,
        room: null,
        nickname: null,

        NS_MUC: "http://jabber.org/protocol/muc",

        joined: null,
        participants: null,

        on_presence: function (presence) {
            console.log(presence);
            var from = $(presence).attr('from');
            var room = Strophe.getBareJidFromJid(from);

        // make sure this presence is for the right room
        if (room === Groupie.room) {
            var nick = Strophe.getResourceFromJid(from);
            
            if ($(presence).attr('type') === 'error' &&
                !Groupie.joined) {
                // error joining room; reset app
            Groupie.connection.disconnect();

        } else if ($(presence).children('data').attr('type') === 'UserBusy') {

            $scope.updateUserStatus(nick,true);
        
        } else if ($(presence).children('data').attr('type') === 'UserFree') {

            $scope.updateUserStatus(nick,false);
        
        } else if (!Groupie.participants[nick] &&
            $(presence).attr('type') !== 'unavailable') {
                // add to participant list
                var user_jid = $(presence).find('item').attr('jid');


                Groupie.participants[nick] = user_jid || true;

                $scope.getUserDetail(nick);

                if (Groupie.joined) {
                    $(document).trigger('user_joined', nick);
                }
            } else if (Groupie.participants[nick] &&
             $(presence).attr('type') === 'unavailable') {
                // remove from participants list

                $scope.$apply(function(){
                    $scope.removeUser(nick);
                });
                
                $(document).trigger('user_left', nick);
            }

            if ($(presence).attr('type') !== 'error' && 
                !Groupie.joined) {
                // check for status 110 to see if it's our own presence
            if ($(presence).find("status[code='110']").length > 0) {
                    // check if server changed our nick
                    if ($(presence).find("status[code='210']").length > 0) {
                        Groupie.nickname = Strophe.getResourceFromJid(from);
                    }

                    // room join complete
                    $(document).trigger("room_joined");
                    
                }
            }
        }

        return true;
    },

    on_public_message: function (message) {

        var from = $(message).attr('from');
        var room = Strophe.getBareJidFromJid(from);
        var nick = Strophe.getResourceFromJid(from);

        var name = $scope.getUserAttributeFromJID(nick,'name');

        var image = $scope.getUserAttributeFromJID(nick,'imageUrl');
        
        // check user busy notification.
        

        // make sure message is from the right place
        if (room === Groupie.room) {
            // is message from a user or the room itself?
            var notice = !nick;

            // messages from ourself will be styled differently
            var nick_class = "message-bar";
            if (nick === Groupie.nickname) {
                nick_class += "-out";
            }
            
            var body = $(message).children('body').text();

            var delayed = $(message).children("delay").length > 0  ||
            $(message).children("x[xmlns='jabber:x:delay']").length > 0;

            // look for room topic change
            var subject = $(message).children('subject').text();
            if (subject) {
                $('#room-topic').text(subject);
            }

            if( $(message).children("data").attr('type') != 'UserBusy' ){
                if (!notice) {
                    var delay_css = delayed ? " delayed" : "";

                    var action = body.match(/\/me (.*)$/);
                    if (!action) {
                        Groupie.clean_message(body, nick_class, name, image);
                    } else {
                        Groupie.add_message(
                            "<div class='message action " + delay_css + "'>" +
                            "* " + name + " " + action[1] + "</div>");
                    }
                } else {
                    Groupie.add_message("<div class='notice'>*** " + body +
                        "</div>");
                }
            } else{
                //alert(from);
            }
            
        }

        return true;
    },

    clean_message: function(body, nick_class, name, image){
        $.each($scope.smiles, function(key, value){
            var value = key;
            var match = new RegExp(value, "ig");     
            var replacement = '<img src="'+$scope.smiles[key]['image']+'" width="40"/>';;
            body = body.replace(match, replacement);

        });


        exptenstion = body.split('.').pop();

        if( exptenstion && $.inArray( exptenstion, $scope.FileAllowdTypes ) != -1){
            $.ajax({
                url:SiteUrl+'files/Attachments/file/'+body,
                type:'HEAD',
                error: function(){
                            //file not exists
                        },
                        success: function(){
                            body = '<a href="'+SiteUrl+'rooms/download/'+body+'" class="donwloadTrasferredFile">'+body+' <i class="fa fa-paperclip" aria-hidden="true"></i></a>';
                            Groupie.add_message(
                                "<div class='"+nick_class+"'>"+
                                "<div class='messanger-photo'><img src='"+image+"'  /></div>" +
                                "<div class='message-disply-box'><div class='message'>" + body+"</div>"+
                                "<div class='who'>"+name + "</div>"+
                                "</div>");
                        }
                    });
        }
        else{
         Groupie.add_message(
            "<div class='"+nick_class+"'>"+
            "<div class='messanger-photo'><img src='"+image+"'  /></div>" +
            "<div class='message-disply-box'><div class='message'>" + body+"</div>"+
            "<div class='who'>"+name + "</div>"+
            "</div>");
     }
 },

 add_message: function (msg) {
        // detect if we are scrolled all the way down
        var chat = $('#chat').get(0);
        var at_bottom = chat.scrollTop >= chat.scrollHeight - 
        chat.clientHeight;
        
        $('#chat').append(msg);

        // if we were at the bottom, keep us at the bottom
        if (at_bottom) {
            chat.scrollTop = chat.scrollHeight;
        }


    },

    on_private_message: function (message) {
        var to = $(message).attr('to');
        var from = $(message).attr('from');
        var room = Strophe.getBareJidFromJid(from);
        var nick = Strophe.getResourceFromJid(from);
        var toUser = Strophe.getResourceFromJid(to);


        var nick_class = "message-bar";
        if (nick === Groupie.nickname) {
            nick_class += "-out";
        }

        var fromUser = from.split('/');
        var toUser = to.split('/');

        // make sure this message is from the correct room
        if (room === Groupie.room) {
            var body = $(message).children('body').text();
            var fromUserData = $scope.groupMembers[fromUser[1]];

            //$scope.privateUser.status
            if(body == 'PrivateChatRequest'){
                var user  =  fromUserData['slug'];
                
                $scope.$apply(function(){
                    $scope.privateUser = fromUserData;
                    $scope.privateUser.status = 'pending';
                });

                var data = fromUserData['name']+ ' send a private chat request.';
                $.blockUI({ message: '<div class="alert-danger alert-dismissible fade in" role="alert"> <div class="chat-user-photo"><img src="'+$scope.groupMembers[fromUser[1]]['imageUrl']+'" alt="'+$scope.groupMembers[fromUser[1]]['name']+'" width="90"> <p>'+data+'</p> <p>  <button type="button" class="btn btn-default accept_private_request" data-user="'+user+'">Accept</button> <button type="button" class="btn btn-danger decline_private_request" data-user="'+user+'">Decline</button> </p> </div>', 
                    css: { width: '40%','border':'2px solid #f2dede','border-radius':'5px','top':'30%','background' : '#f2dede','z-index' : '9999' } }); 
            }  
            else if(body == 'PrivateChatRequestAccepted'){
                $scope.$apply(function(){
                    $scope.privateUser = fromUserData;
                    $scope.privateUser.status = 'accepted';
                });
                $scope.moveUsers(); 

                $('#chat').height($('#chat').height() - 20);
                toastr.success('Private chat request has been accepted.');

                body = 'PrivateChatRequestConfirmed';
                var m = $msg({to: roomDetails.slug+"@conference."+chatHost+"/"+fromUserData['slug'], type: 'chat'}).c("body").t(body);     
                // custom data
                m.up().c("data", {xmlns: 'custom-data', 'type': 'PrivateChatRequestConfirmed'});
                Groupie.connection.send(m);


                Groupie.connection.send($pres({
                        to: Groupie.room
                    }).c('data', {'xmlns': 'custom-data', 'type': 'UserBusy'}));


            }
            else if(body == 'PrivateChatRequestConfirmed'){
                $('#chat').height($('#chat').height() - 20);
                
                $scope.$apply(function(){
                    $scope.privateUser = fromUserData;
                    $scope.privateUser.status = 'accepted';
                    $scope.moveUsers(); 
                });

                Groupie.connection.send($pres({
                        to: Groupie.room
                    }).c('data', {'xmlns': 'custom-data', 'type': 'UserBusy'}));

            }
            else if(body == 'PrivateChatRequestDeclined'){
                toastr.error('Private chat request has been declined.');
                $scope.$apply(function(){
                    $scope.privateUser.status = 'declined';
                    $scope.moveUsers();    
                });
            } 
            else if(body == 'PrivateChatCancelled'){
                // alert('PrivateChatCancelled')
                $scope.$apply(function(){
                    $scope.privateUser = {};
                    $.unblockUI();
                    $scope.moveUsers(); 
                });

                Groupie.connection.send($pres({
                        to: Groupie.room
                    }).c('data', {'xmlns': 'custom-data', 'type': 'UserFree'}));

            }
            else {

                // Groupie.add_message("<div class='message private'>" +
                //     "@@ &lt;<span class='nick'>" +
                //     nick + "</span>&gt; <span class='body'>" +
                //     body + "</span> @@</div>");

                var name = $scope.getUserAttributeFromJID(fromUserData['slug'],'name');
                var image = $scope.getUserAttributeFromJID(fromUserData['slug'],'imageUrl');

                Groupie.clean_message(body, nick_class, name, image);

                // Groupie.add_message(
                //     "<div class='"+nick_class+"'>"+
                //     "<div class='messanger-photo'><img src='"+image+"'  /></div>" +
                //     "<div class='message-disply-box'><div class='message'>" + body+"</div>"+
                //     "<div class='who'>"+name + "</div>"+
                //     "</div>");

            }
            
        }

        return true;
    },

    
};

$(document).ready(function () {

    // Login to room

    // alert(accupants +" - "+roomDetails.occupancy );
    // if(accupants <= roomDetails.occupancy ) {


    // }
    

    Groupie.room = roomDetails.slug+'@conference.'+chatHost;
    Groupie.nickname = loggedIn.slug;

    $(document).trigger('connect', {
        jid: loggedIn.slug+"@"+chatHost,
        password: loggedIn.id
    });
    

    $('#leave').click(function () {
        $('#leave').attr('disabled', 'disabled');
        Groupie.connection.send(
            $pres({to: Groupie.room + "/" + Groupie.nickname,
             type: "unavailable"}));
        Groupie.connection.disconnect();
    });

    $('#input').keypress(function (ev) {
        if (ev.which === 13) {
            ev.preventDefault();
            send_message();
            
        }
    });

    $('#send_message').click(function (ev) {
        ev.preventDefault();
        send_message();
    });

    $(document).on('click','.start_private_chat',function(){
        var user = $scope.privateUser.slug;

        send_private_message('PrivateChatRequest', user);
        toastr.success('Private chat request has been sent to '+$scope.privateUser.name);

    });
    $(document).on('click','.cancelled_private_chat',function(){
        var user = $scope.privateUser.slug;

        send_private_message('PrivateChatCancelled', user);
        
    });

    $(document).on('click','.accept_private_request',function(){
        var user = $(this).data('user');

        // $scope.$apply(function(){
        //     $scope.privateUser.status = 'accepted';
        // });
        send_private_message('PrivateChatRequestAccepted', user);
        $.unblockUI();
    });

    $(document).on('click','.decline_private_request',function(){
        var user = $(this).data('user');

        // $scope.$apply(function(){
        //     $scope.privateUser = {};
        // });
        send_private_message('PrivateChatRequestDeclined', user);
        $.unblockUI();
    });

    $('#files').change(function(){
     $("#fileUploadForm").submit();
 });

    $('#fileUploadForm').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: SiteUrl+'rooms/generateImageToken',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:'json',
            success:function(data){
                if(data.token){
                    $scope.chatFile = data.token;
                    send_message();
                }
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
    }));


    var send_message = function () {

        if($scope.chatFile){
            var body = $scope.chatFile;
            $scope.chatFile = '';
        } else{
            var body = $('#input').val();    
        }

        var match = body.match(/^\/(.*?)(?: (.*))?$/);
        
        var args = null;
        if (match) {
            if (match[1] === "msg") {
                args = match[2].match(/^(.*?) (.*)$/);
                if (Groupie.participants[args[1]]) {
                    Groupie.connection.send(
                        $msg({
                            to: Groupie.room + "/" + args[1],
                            type: "file"}).c('body').t(body));


                    Groupie.add_message(
                        "<div class='message-bar'>"+
                        "<div class='messanger-photo'><img src='"+SiteUrl+"img/chat-user.jpg'  /></div>" +
                        "<div class='message-disply-box'><div class='message'>" + args[2]+"</div>"+
                        "<div class='who'>"+Groupie.nickname + "</div>"+
                        "</div>");
                } else {
                    Groupie.add_message(
                        "<div class='notice error'>" +
                        "Error: User not in room." +
                        "</div>");
                }
            } else if (match[1] === "me" || match[1] === "action") {
                Groupie.connection.send(
                    $msg({
                        to: Groupie.room,
                        type: "groupchat"}).c('body')
                    .t('/me ' + match[2]));
            } else if (match[1] === "topic") {
                Groupie.connection.send(
                    $msg({to: Groupie.room,
                      type: "groupchat"}).c('subject')
                    .text(match[2]));
            } else if (match[1] === "kick") {
                Groupie.connection.sendIQ(
                    $iq({to: Groupie.room,
                       type: "set"})
                    .c('query', {xmlns: Groupie.NS_MUC + "#admin"})
                    .c('item', {nick: match[2],
                        role: "none"}));
            } else if (match[1] === "ban") {
                Groupie.connection.sendIQ(
                    $iq({to: Groupie.room,
                       type: "set"})
                    .c('query', {xmlns: Groupie.NS_MUC + "#admin"})
                    .c('item', {jid: Groupie.participants[match[2]],
                        affiliation: "outcast"}));
            } else if (match[1] === "op") {
                Groupie.connection.sendIQ(
                    $iq({to: Groupie.room,
                       type: "set"})
                    .c('query', {xmlns: Groupie.NS_MUC + "#admin"})
                    .c('item', {jid: Groupie.participants[match[2]],
                        affiliation: "admin"}));
            } else if (match[1] === "deop") {
                Groupie.connection.sendIQ(
                    $iq({to: Groupie.room,
                       type: "set"})
                    .c('query', {xmlns: Groupie.NS_MUC + "#admin"})
                    .c('item', {jid: Groupie.participants[match[2]],
                        affiliation: "none"}));
            } else {
                Groupie.add_message(
                    "<div class='notice error'>" +
                    "Error: Command not recognized." +
                    "</div>");
            }
        } else if($scope.privateUser.status == 'accepted'){

            Groupie.clean_message(body, 'message-bar-out', $scope.groupMembers[Groupie.nickname]['name'], $scope.groupMembers[Groupie.nickname]['imageUrl']);


            var receiver = $scope.privateUser.slug;
            var m = $msg({to: roomDetails.slug+"@conference."+chatHost+"/"+receiver, type: 'chat'}).c("body").t(body);     
            // custom data
            m.up().c("data", {xmlns: 'custom-data', 'type': 'PrivateChat'});
            Groupie.connection.send(m);

        }
        else {
            Groupie.connection.send(
                $msg({
                    to: Groupie.room,
                    type: "groupchat"}).c('body').t(body));
        }

        $('#input').val('');

        return true;
    }

    var send_private_message = function (body, receiver) {
        // var body = $('#input').val();
        //var body = 'PrivateChatrequest';
        //var receiver = $scope.privateUser['slug'];

        var m = $msg({to: roomDetails.slug+"@conference."+chatHost+"/"+receiver, type: 'chat'}).c("body").t(body);     
        // custom data
        m.up().c("data", {xmlns: 'custom-data', 'type': body});
        Groupie.connection.send(m);

        $('#input').val('');

        return true;
    }
});

$(document).bind('connect', function (ev, data) {

    Groupie.connection = new Strophe.Connection(chatServer);

    Groupie.connection.connect(
        data.jid, data.password,
        function (status) {
            blockUILoading.stop('.box-shadow');
            // console.log(status, Strophe.Status.CONNECTED);
            if (status === Strophe.Status.CONNECTED) {
                $(document).trigger('connected');
                blockUILoading.stop('.box-shadow');
                $('#canvas_mage').css('width',$('.chat-section-area').width());
                $('#canvas_mage').css('height','auto');
                $('.canvas-container').css('height',$('#canvas_mage').height());

                $('#canvas_mage').css('height','275px');
                $('.canvas-container').css('height','275px');

                //autoHeightChatBox();
                //alert($('#canvas_mage').height());
            } else if (status === Strophe.Status.DISCONNECTED) {
                $(document).trigger('disconnected');
            }
        });
});

$(document).bind('connected', function () {
    Groupie.joined = false;
    Groupie.participants = {};

    // Groupie.connection.rawInput = console.log;
    // Groupie.connection.rawOutput = console.log;

    Groupie.connection.send($pres().c('priority').t('-1'));
    
    Groupie.connection.addHandler(Groupie.on_presence,
      null, "presence");
    Groupie.connection.addHandler(Groupie.on_public_message,
      null, "message", "groupchat");
    Groupie.connection.addHandler(Groupie.on_private_message,
      null, "message", "chat");

    Groupie.connection.send(
        $pres({
            to: Groupie.room + "/" + Groupie.nickname
        }).c('x', {xmlns: Groupie.NS_MUC}));
});

$(document).bind('disconnected', function () {
    Groupie.connection = null;
    $('#room-name').empty();
    $('#room-topic').empty();
    $('#participant-list').empty();
    $('#chat').empty();
    //$('#login_dialog').dialog('open');
});

$(document).bind('room_joined', function () {
    Groupie.joined = true;

    $('#leave').removeAttr('disabled');
    $('#room-name').text(Groupie.room);

    Groupie.add_message("<div class='notice-chat'><i class='fa fa-user'></i> You have joined the group.</div>")
});

$(document).bind('user_joined', function (ev, nick) {

    var name = $scope.getUserAttributeFromJID(nick,'name');
    Groupie.add_message("<div class='notice-chat'><i class='fa fa-user'></i> " +name +
       " has joined the group.</div>");
});

$(document).bind('user_left', function (ev, nick) {
    var name = $scope.getUserAttributeFromJID(nick,'name');
    Groupie.add_message("<div class='notice-chat'><i class='fa fa-user'></i> " + name +
        " has left the group.</div>");
});


        var regex4 = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;// Email address
        $('#send_invitation').tagsInput({
          width: 'auto',
          pattern: regex4,
          defaultText : 'Enter email address',
          interactive : true,
          onAddTag: function () {
            var emails = $(this).val();
            $scope.$apply(function () {
              $scope.invitedEmails = emails;
          });

        },
        onRemoveTag: function () {
            var emails = $(this).val();
            $scope.$apply(function () {
              $scope.invitedEmails = emails;
          });

        }
    });


// window.addEventListener("beforeunload", function (e) {
//   // var confirmationMessage = "Are you sure? Your chat session will be lost!";
//   // e.returnValue = confirmationMessage;     // Gecko and Trident
//   return 'Are you sure? Your changes will be lost!';              // Gecko and WebKit
// });

$(document).on('click','.donwloadTrasferredFile',function(){

    $('#reload_passed').val('download');
});



});

$(window).load(function () {

    window.onbeforeunload = function () {
        var msg = 'Are you sure? Your chat session will be lost!';
        var isDirty = true;

        if($('#reload_passed').val()) {
            isDirty = false;
            $('#reload_passed').val('');
        }

        if (isDirty == true) {
            return msg;
        }
    };
});
