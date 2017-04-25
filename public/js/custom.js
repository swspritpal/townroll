jQuery(document).ready(function($) {


    $('.scrollerDivSlick').slick({
      dots: false,
      infinite: false,
      speed: 300,
      arrows: false,
      slidesToShow: 4,
      slidesToScroll: 4,

      variableWidth: true,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
           /* infinite: true,
            dots: false*/
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });

    $("#notificationDropdown").click(function(){
        $("#Notifi").toggle();
      

    });

    if(('.convert_space_into_underscore').length){

        $(".convert_space_into_underscore").keyup(function () {
            var textValue = $(this).val();
            textValue =textValue.replace(/ /g,"_");
            $(this).val(textValue);
        });
    }

    function setCookie(key, value) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
        //document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        document.cookie = key + '=' + value ;
    }

    /*function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }*/
    function getCookie(name) {
      var value = "; " + document.cookie;
      var parts = value.split("; " + name + "=");
      if (parts.length == 2) return parts.pop().split(";").shift();
    }

    function delete_cookie( name ) {
      document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

    function success_callback(p)
    {
        setCookie("latitude",p.coords.latitude);
        setCookie("longitude",p.coords.longitude);
        
        // if cookie set the call to ajax
            $('#locateMe').modal('show');
            $('.new-places-output').html('');
            

            $.ajax({
                url: APP_URL + '/near-by-places',
                type: 'get',
                dataType: 'json',
                cache: false,
                beforeSend: function() {
                    $('.new-places-output').html('<img class="center-block loader-image" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
                },
                success: function(data)
                {
                    if(data.status == 200){
                        $('.new-places-output').append(data.html_result);
                    }
                },
                complete: function(){
                    $('.new-places-output').find('.loader-image').remove();
                },
            });
    }

    
    function error_callback(p)
    {
        if(p.PERMISSION_DENIED){
            $('#locateMe').modal('hide');
            swal({
              title: "Error",
              text: "Geolocation is not enabled. Please enable to use this feature.",
              type: "error",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Ok guide me !",
              cancelButtonText: "cancel",
              closeOnConfirm: false,
              closeOnCancel: true
            },
            function(isConfirm){
              if (isConfirm) {
                swal("Info", "You need to set location value allow.", "success");
              } else {
                //swal("Cancelled", "Your imaginary file is safe :)", "error");
              }
            });
        }else{
            swal({
              title: "Error",
              text: p.message,
              type: "error",
              showCancelButton: true,
              confirmButtonText: "Ok",
              closeOnConfirm: true,
            });
        }        
    }


    $(document).on('click','.locate-me-popup',function(){
        delete_cookie("latitude");
        delete_cookie("longitude");

        if(geo_position_js.init()){
            geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
        }
        else{
            swal({
              title: "Error",
              text: 'Geolocation is not supported by this browser. Please update with new one.',
              type: "error",
              showCancelButton: true,
              confirmButtonText: "Ok",
              closeOnConfirm: true,
            });
        }        
    });

    $(document).on('click','.join-new-place',function(e){
        e.preventDefault(e);

        var form=$(this).parents('.add-new-place-form');

        $.ajax({
            url: APP_URL + '/add-new-place',
            type: 'post',
            data:form.serialize(),
            dataType: 'json',
            cache: false,
            beforeSend: function() {

            },
            success: function(data)
            {
                if(data.status == 200){
                    $('#locateMe').modal('hide');
                    $('.right-sidebar-locations').append(data.html_result);
                }else{
                    toastr.warning(data.message);
                }
            },
            complete: function(){
                
            },
        });
    });

    /*$(document).on('click','.filter-posts',function(e){
        e.preventDefault(e);

        var currentElement=$(this);
        var filter_name=$(currentElement).data('filter-by');
        var filterId=$(this).data('filter-id');

        $.ajax({
            url: APP_URL+'?'+filter_name+'='+filterId,
            type: 'get',
            //data:{filter_name:filter_name,'filter_id':filterId},
            //dataType: 'json',
            beforeSend: function() {
                $('.append-new-post-content').html('<img class="center-block loader-image" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
            },
            success: function(data)
            {
                $('.append-new-post-content').append(data);
            },
            complete: function(){
                $('.append-new-post-content').find('.loader-image').remove();
            },
        });
    });*/

    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });






});



/*
function prompt(window, pref, message, callback) {
    let branch = Components.classes["@mozilla.org/preferences-service;1"]
                           .getService(Components.interfaces.nsIPrefBranch);

    if (branch.getPrefType(pref) === branch.PREF_STRING) {
        switch (branch.getCharPref(pref)) {
        case "always":
            return callback(true);
        case "never":
            return callback(false);
        }
    }

    let done = false;

    function remember(value, result) {
        return function() {
            done = true;
            branch.setCharPref(pref, value);
            callback(result);
        }
    }

    let self = window.PopupNotifications.show(
        window.gBrowser.selectedBrowser,
        "geolocation",
        message,
        "geo-notification-icon",
        {
            label: "Share Location",
            accessKey: "S",
            callback: function(notification) {
                done = true;
                callback(true);
            }
        }, [
            {
                label: "Always Share",
                accessKey: "A",
                callback: remember("always", true)
            },
            {
                label: "Never Share",
                accessKey: "N",
                callback: remember("never", false)
            }
        ], {
            eventCallback: function(event) {
                if (event === "dismissed") {
                    if (!done) callback(false);
                    done = true;
                    window.PopupNotifications.remove(self);
                }
            },
            persistWhileVisible: true
        });
}

prompt(window,
       "extensions.foo-addon.allowGeolocation",
       "Foo Add-on wants to know your location.",
       function callback(allowed) { alert(allowed); });*/







