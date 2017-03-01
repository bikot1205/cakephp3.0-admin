var myApp = angular.module('myapp', ['satellizer', 'toastr','ngRoute'])
.config(function ($authProvider, $routeProvider) {

        // Facebook
        $authProvider.facebook({
            clientId: '265287920537904',
            name: 'facebook',
            url: SiteUrl + 'auth/facebook',
            authorizationEndpoint: 'https://www.facebook.com/v2.5/dialog/oauth',
            redirectUri: SiteUrl + 'signin/',
            requiredUrlParams: ['display', 'scope'],
            scope: ['email'],
            scopeDelimiter: ',',
            display: 'popup',
            oauthType: '2.0',
            popupOptions: {width: 580, height: 400}
        });
        // Google
        $authProvider.google({
            clientId: '192560988069-1k72tm7tmss6o16vhv7o5l34nph4lapm.apps.googleusercontent.com',
            url: SiteUrl + 'auth/google',
            authorizationEndpoint: 'https://accounts.google.com/o/oauth2/auth',
            redirectUri: SiteUrl + 'signin/',
            requiredUrlParams: ['scope'],
            optionalUrlParams: ['display'],
            scope: ['profile', 'email'],
            scopePrefix: 'openid',
            scopeDelimiter: ' ',
            display: 'popup',
            oauthType: '2.0',
            popupOptions: {width: 452, height: 633}
        });
        // Twitter
        $authProvider.twitter({
            clientId: 'ytL2KsoLcygTKwRv2qEQkGlnz',
            consumer_secret: 'ytL2KsoLcygTKwRv2qEQkGlnz',
            url: SiteUrl+'auth/twitter',
            authorizationEndpoint: 'https://api.twitter.com/oauth/authorize',
            redirectUri: SiteUrl + 'signin/',
            oauthType: '1.0',
            force_login: true,
            popupOptions: {width: 495, height: 645}
        });
    });

// Define App routes
myApp.config(function($routeProvider) {
    $routeProvider
    // .when("/", {
    //     templateUrl : SiteUrl+"term-and-conditions"
    // })
    .when("/edit-profile", {
        templateUrl : SiteUrl+"edit-profile"
    })
    .when("/search-memebers", {
        templateUrl : SiteUrl+"users"
    })
    .when("/term-and-conditions", {
        templateUrl : SiteUrl+"term-and-conditions"
    })
    .when("/feedback-and-suggestions", {
        templateUrl : SiteUrl+"feedback-and-suggestions"
    })
    .when("/about-us", {
        templateUrl : SiteUrl+"about-us"
    })
    .when("/contact-us", {
        templateUrl : SiteUrl+"contact-us"
    });
});

myApp.service('blockUILoading', function () {
    return {
        start: function (selector) {
            $(selector).block({
            message: '<h1><img src="' + SiteUrl + 'img/default.gif" width="70px" /></h1>', //loading.gif
            centerY: false,
            centerX: false,
            css: {
                border: 'none',
                'padding': '15px',
                'backgroundColor': 'none',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                'opacity': 1,
                position: 'fixed',
                margin: 'auto'
            }})
        },
        stop: function (selector) {
            $(selector).unblock();
        }

    };
});
myApp.controller('defaultController', function ($scope, $attrs, $http, $sce, $filter, $auth, toastr,blockUILoading, $location, $timeout) {

    $scope.$on('$viewContentLoaded', function () {
        var viewPortHeight = $(window).height() - 285;
        $('#os3').height(viewPortHeight);
        $('.detail-area').height(viewPortHeight + 71);
        var os3 = new Optiscroll(document.getElementById('os3'), {maxTrackSize: 20, preventParentScroll: true});


        // Browser supports HTML5 multiple file?
        var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined',
        isIE = /msie/i.test(navigator.userAgent);

       

        // Old browser fallback
        if (!multipleSupport) {
            $(document).on('change', 'input.customfile', function () {

                var $this = $(this),
                        // Create a unique ID so we
                        // can attach the label to the input
                        uniqId = 'customfile_' + (new Date()).getTime(),
                        $wrap = $this.parent(),
                        // Filter empty input
                        $inputs = $wrap.siblings().find('.file-upload-input')
                        .filter(function () {
                            return !this.value
                        }),
                        $file = $('<input type="file" id="' + uniqId + '" name="' + $this.attr('name') + '"/>');

                // 1ms timeout so it runs after all other events
                // that modify the value have triggered
                setTimeout(function () {
                    // Add a new input
                    if ($this.val()) {
                        // Check for empty fields to prevent
                        // creating new inputs when changing files
                        if (!$inputs.length) {
                            $wrap.after($file);
                            $file.customFile();
                        }
                        // Remove and reorganize inputs
                    } else {
                        $inputs.parent().remove();
                        // Move the input so it's always last on the list
                        $wrap.appendTo($wrap.parent());
                        $wrap.find('input').focus();
                    }
                }, 1);

            });
        }


        $('input[type=file].files').customFile();


        

    });

    $scope.trustAsHtml = function (html) {
        return $sce.trustAsHtml(html);
    };
    $scope.showLoading = function () {
        $.blockUI({
        message: '<h1><img src="' + SiteUrl + 'img/default.gif" /></h1>', //loading.gif
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#fff',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: 1,
            color: '#fff',
            width: '13%',
            left: '45%',
        }});
    }
    $scope.stopLoading = function () {
        $.unblockUI();
    }

    $scope.authenticate = function (provider) {
        //        $auth.authenticate(provider);
        $scope.showLoading();
        $auth.authenticate(provider)
        .then(function (response) {
            $scope.stopLoading();
            if (response.data.status) {
                window.location.href = response.data.redirectTo;
            } else {
                            //alert(response.data.message);
                        }
                    })
        .catch(function (response) {
            $scope.stopLoading();
        });
    };
    
    $(document).on('submit','#searchMembers',function(event){
        event.preventDefault();
        $scope.$apply(function($location){
            $scope.searchUsers();
        });
    });

    $(document).on('submit','#filters_form',function(event){
        event.preventDefault();
        $scope.$apply(function($location){
            $scope.searchUsers();
        });
    });

    $scope.searchUsers = function(){
        $location.path('/search-memebers');
    }


});
myApp.controller('ExampleController', function ($scope, $attrs, $http, $sce, $filter, $auth, toastr) {
    $scope.showLoading = function () {
        $.blockUI({
        message: '<h1><img src="' + SiteUrl + 'img/default.gif" /></h1>', //loading.gif
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#fff',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: 1,
            color: '#fff',
            width: '13%',
            left: '45%',
        }});
    }
    $scope.stopLoading = function () {
        $.unblockUI();
    }

    $('#filters_form').submit(function () {
        $scope.showLoading();
    });
});
myApp.controller('profileController', function ($scope, $attrs, $http, $sce, $filter) {

    $scope.avatars = avatars;
    $scope.Users = userInfo;
    $scope.selectedAvatar = null;
    
    $scope.makeSelectedAvatar = function (AvatarID) {
    //$scope.selectedAvatar = AvatarID;
    $scope.Users.avatar_id = AvatarID;
}

$scope.profileForm = function () {
    $scope.showLoading();
}

$scope.cancelSubmit = function($event){
    alert('Hello');
    $event.preventDefaults();
    window.location.href = '/signin';
}

});


myApp.controller('myContactsControllers', function ($scope, $attrs, $http, $sce, $filter, blockUILoading) {
    $scope.friends = [];
    $scope.process = false;
    $scope.loadContacts = function (AvatarID) {
        blockUILoading.start('#l3a');
        $http({
            method: 'POST',
            url: SiteUrl + 'friends/index',
        // data: $.param($scope.formData),
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        blockUILoading.stop('#l3a');
        $scope.friends = data.contacts;
        $('#l3a').optiscroll({maxTrackSize: 20, preventParentScroll: true});

    });
}

$scope.blockFriend = function (FriendId) {
    if (!confirm('Are you sure want to block friend ?')) {
        return false;
    }
    $scope.process = true;
    $scope.formData = {'friend': FriendId};
    $http({
        method: 'POST',
        url: SiteUrl + 'friends/block?friend=' + FriendId,
        data: $.param($scope.formData),
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        $scope.process = false;
        if (data.status) {
            $scope.loadContacts();
        }

    });
}


$scope.unfriend = function (FriendId) {
    if (!confirm('Are you sure want to unfriend ?')) {
        return false;
    }
    $scope.process = true;
    $scope.formData = {'friend': FriendId};
    $http({
        method: 'POST',
        url: SiteUrl + 'friends/unfriend?friend=' + FriendId,
        data: $.param($scope.formData),
        headers: {'Content-Type': 'application/json'}  // set the headers so angular passing info as form data (not request payload)
    }).success(function (data) {
        $scope.process = false;
        if (data.status) {
            $scope.loadContacts();
        }

    });
}

$scope.showOptions = function (key) {
    // Hide other opened options
    if ($scope.process == true) {
        return true;
    }
    $($scope.friends).each(function (index, element) {
        if (index == key) {
            if ($scope.friends[key]['expand'] == 0) {
                $scope.friends[key]['expand'] = 1
            } else {
                $scope.friends[key]['expand'] = 0;
            }
        } else {
            $scope.friends[index]['expand'] = 0;
        }
    });


}
$scope.loadContacts();
//    $(document).on("click", '.my_contacts .chat-user', function () {
//        $('.options').hide();
//        $(this).children('.options').toggle();
//        $('#l3a').optiscroll({maxTrackSize: 20, preventParentScroll: true});
//    })
});



myApp.controller('roomsController', function ($scope, $attrs, $http, $sce, $filter, blockUILoading) {

    var canvas = this.__canvas = new fabric.Canvas('canvas_mage', {
        hoverCursor: 'pointer',
        selection: false
    });
    var radius = 40;
    canvas.setBackgroundImage(SiteUrl+'img/banner-img.jpg', canvas.renderAll.bind(canvas), {
        // backgroundImageOpacity: 0.5,
        // backgroundImageStretch: false
    });        


    canvas.on({
        'object:moving': function (e) {
            e.target.opacity = 0.5;
        },
        'object:modified': function (e) {
            JSON.stringify(canvas);
            console.log(JSON.stringify(canvas));
            var id = canvas.getActiveObject().get('id');
            console.log(id);
            e.target.opacity = 1;
        },
        'mouse:over': function (e) {
            if(e.target && e.target.text == undefined){
                showImageTools(e.target);
            }
        },
        'mouse:out': function (e) {
            if(e.target && e.target.text == undefined){
                //$("#myChatrommTTT").popover('hide'); 
            }
            $("#myChatrommTTT").popover('destroy'); 
        },
    });


    if (roomData) {
        $.each(roomData.objects, function (index, value) {
            if(roomMembers[index]){
                var userInfo = roomMembers[index];
                fabric.Image.fromURL(userInfo.image, function (img) {
                    img.set({
                        left: value.left,
                        top: value.top,
                        id: userInfo.id,
                        
                    });
                    img.id = userInfo.id;
                    img.perPixelTargetFind = true;
                    img.targetFindTolerance = 4;
                    img.hasControls = img.hasBorders = false;
                    img.lockMovementX = true;
                    img.lockMovementY = true;
                    img.width = 30;
                    img.height = 30;
                    // img.stroke = 'white';
                    // img.strokeWidth = 100;
                    // img.clipTo = function (ctx) {
                    //     ctx.arc(0, 0, radius, 0, Math.PI * 2, false);
                    // };
                    canvas.add(img);
                });

                var textHeight = value.top;
                var text = canvas.add(new fabric.Text(userInfo.first_name, { 
                    left: value.left, //Take the block's position
                    top: textHeight+35, 
                    fontSize:'11',
                    lockMovementX : true,
                    lockMovementY : true,
                    editable:false,
                    hasControls:false,
                    hasRotatingPoint:false,
                    transparentCorners:false,
                    id : userInfo.id,
                    //fill: 'white'
                }));
            }

        });

    }

    function showImageTools (e) {
        index = e.id;
        var userInfo = userDetails[index];
        console.log(userInfo);

        var top = e.top - 95;
        var left = e.left - 55;

        var content = '<div class="detail-tooltip">';
        content += '<ul>';
        content += '<li class="filter-user-name"><img src="'+userInfo.image+'" alt=""> '+userInfo.first_name+'</li>';
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


    function windowResize(){
       // $("#canvas_mage").outerHeight($(window).height()-$("#canvas_mage").offset().top- Math.abs($("#canvas_mage").outerHeight(true) - $("#canvas_mage").outerHeight()));
   }

    //windowResize();    
    $(window).resize(function(){
        windowResize();                      
    });

});



