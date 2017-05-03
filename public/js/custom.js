function initDeleteTarget() {
    $('.swal-dialog-target').append(function () {
        return "\n" +
            "<form action='" + $(this).attr('data-url') + "' method='post' style='display:none'>\n" +
            "   <input type='hidden' name='_method' value='" + ($(this).data('method') ? $(this).data('method') : 'delete') + "'>\n" +
            "   <input type='hidden' name='_token' value='" + Laravel.csrfToken + "'>\n" +
            "</form>\n"
    }).click(function () {
        var deleteForm = $(this).find("form");
        var method = ($(this).data('method') ? $(this).data('method') : 'DELETE');
        var operationOn = ($(this).data('operation-on') ? $(this).data('operation-on') : 'comment');
        var url = $(this).attr('data-url');
        var data = $(this).data('request-data') ? $(this).data('request-data') : '';
        var title = $(this).data('dialog-title') ? $(this).data('dialog-title') : 'Are you sure to delete this item?';
        var message = $(this).data('dialog-msg');
        var type = $(this).data('dialog-type') ? $(this).data('dialog-type') : 'warning';
        var cancel_text = $(this).data('dialog-cancel-text') ? $(this).data('dialog-cancel-text') : 'Never mind';
        var confirm_text = $(this).data('dialog-confirm-text') ? $(this).data('dialog-confirm-text') : 'Yes';
        var enable_html = $(this).data('dialog-enable-html') == '1';
        var enable_ajax = $(this).data('enable-ajax') == '1';

        if (enable_ajax) {
            swal({
                    title: title,
                    text: message,
                    type: type,
                    html: enable_html,
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: cancel_text,
                    confirmButtonText: confirm_text,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: true
                },
                function () {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': Laravel.csrfToken
                        },
                        url: url,
                        type: method,
                        data: $(deleteForm).serialize(),
                        success: function (res) {
                            if (res.code == 200) {
                                /*swal({
                                    title: 'Succeed',
                                    text: res.msg,
                                    type: "success",
                                    timer: 1000,
                                    confirmButtonText: "OK"
                                });*/
                                if(operationOn == "comment"){
                                    $(deleteForm).parents('.comment-wrapper').remove();
                                }else{
                                    $(deleteForm).parents('.content-item-wrapper').remove();
                                }
                                
                                toastr.success(res.msg);
                            } else {
                                /*swal({
                                    title: 'Failed',
                                    text: "There was some error while deleting comment. Please try again.",
                                    type: "error",
                                    timer: 1000,
                                    confirmButtonText: "OK"
                                });*/
                                toastr.warning(res.msg);
                            }
                        },
                        error: function (res) {
                            swal({
                                title: 'Failed',
                                text: "There was some error while sending your request to server. Please try again.",
                                type: "error",
                                timer: 1000,
                                confirmButtonText: "OK"
                            });
                        }
                    })
                });
        } else {
            swal({
                    title: title,
                    text: message,
                    type: type,
                    html: enable_html,
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: cancel_text,
                    confirmButtonText: confirm_text,
                    closeOnConfirm: true
                },
                function () {
                    deleteForm.submit();
                });
        }
    });
}
jQuery(document).ready(function($) {

    $("#notificationDropdown").click(function(){
        if($("#Notifi").hasClass('hidden')){
            $("#Notifi").removeClass('hidden');
            $('.notifications-counter-in-header').html('');

            // sending requets to server for update stream server
            $.ajax({
                url: APP_URL + '/mark-notification-read',
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(data)
                {
                    
                },
                complete: function(){
                },
            });
        }else{
            $("#Notifi").addClass('hidden');
        }
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

        var slick_last_index=$('.add-new-location-in-header').find('.slick-track a:last-child').attr('data-slick-index');
        var slick_last_index_input = $("<input type=\"hiden\" name=\"slick_last_index\" />");
        form.append(slick_last_index_input);

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
                    $('.right-sidebar-locations').append(data.html_result['horizontal']);
                    //$('.add-new-location-in-header').find('.slick-track').append(data.html_result['vertical']);
                    $('.add-new-location-in-header').find('.slick-track .locate-me-popup').after(data.html_result['vertical']);
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







