function initDeleteTarget() {
    /*$('.swal-dialog-target').append(function () {
        return "\n" +
            "<form action='" + $(this).attr('data-url') + "' method='post' style='display:none'>\n" +
            "   <input type='hidden' name='_method' value='" + ($(this).data('method') ? $(this).data('method') : 'delete') + "'>\n" +
            "   <input type='hidden' name='_token' value='" + Laravel.csrfToken + "'>\n" +
            "</form>\n"
    })*/

    $(document).on('click','.swal-dialog-target',function () {
        var comment_post_id='';

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

        if(operationOn == "comment"){
            comment_post_id = $(this).data('post-id');
        }
        

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
                               
                                if(operationOn == "comment"){
                                    // Remove 1 from total comment counter
                                    var comment_counter_container=$(deleteForm).parents('.comment-main-wrappper').find('.home-comment-counter');
                                    var total_comments_count=$(comment_counter_container).html();
                                    var new_value_comment_count=parseInt(total_comments_count) - parseInt(1);
                                    if(parseInt(new_value_comment_count) > 0){
                                        $(comment_counter_container).html(new_value_comment_count);
                                    }else{
                                        $(comment_counter_container).html('0');
                                    }                                   

                                    $(deleteForm).parents('.comment-wrapper').remove();

                                    // auto refresh TRUE
                                    $(document).find('[data-post-id="'+comment_post_id+'"]').attr('data-auto-refresh','true');
                                }else{
                                    $(deleteForm).parents('.content-item-wrapper').remove();
                                }
                                
                                toastr.success(res.msg);
                            } else {
                               
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



    $(document).on('keyup','.suggest-user-list',function(e) {
        /*if(e.which == 64) {
        }*/
        var curenct_obj=$(this);

        var enter_char =$(curenct_obj).val();

        // get one charactor from all values
        /*if(enter_char.indexOf(' ') > -1){
            enter_char= enter_char.substr(enter_char.indexOf('@'), enter_char.indexOf(' '));
        }else{
            enter_char= enter_char.substr(enter_char.indexOf('@'));
        }*/

        if(enter_char.indexOf(' ') > -1){
            enter_char= enter_char.substr(enter_char.lastIndexOf('@'), enter_char.lastIndexOf(' '));
        }else{
            enter_char= enter_char.substr(enter_char.lastIndexOf('@'));
        }
        

        //if(enter_char.length > 1 && enter_char.indexOf('@') > -1){

        if(enter_char.length > 1 && enter_char.lastIndexOf('@') > -1){

            //$(curenct_obj).parent().find('.suggest-user-output').remove();

            $.ajax({
                url: APP_URL + '/suggest-user-list?search='+enter_char,
                type: 'get',
                //dataType: 'json',
                beforeSend: function() {
                },
                success: function(data)
                {
                    $(curenct_obj).parent().find('.suggest-user-output').each(function( index ) {
                        $(this).remove();
                    });

                    $(curenct_obj).parent().append(data);

                },
                complete: function(){
                },
            });
        }else{
            $(curenct_obj).parent().find('.suggest-user-output').remove();
        }
        
    });

    $(document).on('click','.suggest-user-click',function(e) {
        var curenct_obj=$(this);
        var input_field_obj=$(curenct_obj).parent().prev('.suggest-user-list');

        var selected_user=$(curenct_obj).find('.suggest-selected-username').html();

        var input_text=$(input_field_obj).val();

         // get one charactor from all values
        //var replace_string= input_text.substr(input_text.indexOf('@')+1, input_text.indexOf(' '));

        if(input_text.indexOf(' ') > -1){
            var replace_string= input_text.substr(input_text.lastIndexOf('@')+1, input_text.lastIndexOf(' '));
        }else{
            var replace_string= input_text.substr(input_text.lastIndexOf('@')+1);
        }        

        //var auto_complete_full_name = input_text.replace('/'+replace_string+'/g',selected_user);

        var auto_complete_full_name = input_text.replace(replace_string,selected_user);

        auto_complete_full_name=auto_complete_full_name+" ";

        $(input_field_obj).val(auto_complete_full_name);
        $(curenct_obj).parents('.suggest-user-output').remove();
            
    });

    

    // Hide the notification menu when click on Document
    $(document).on("click", function(event){
        var $trigger = $("#notificationDropdown");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            if(!$("#Notifi").hasClass("hidden")){
                $("#Notifi").addClass("hidden");
            }
        }            
    });
    // open notification dropdwon
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

    $('.edit-profile-icon').click(function() {
        $('#user_profile_image_input').trigger('click');
    });

    $('#user_profile_image_input').change(function(event)
    {
        event.preventDefault();

        var imgPath = $(this)[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var file_size = $(this)[0].files[0].size;

        if (extn == "tif" || extn == "tiff" || extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "GIF" || extn == "PNG" || extn == "JPG" || extn == "JPEG" || extn == "TIF" || extn == "TIFF" || file_size <= 2097152) {
            if (typeof (FileReader) != "undefined") {

               if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.user-profile-image').attr('src', '').attr('src', e.target.result);
                        //$('.profile-image-click-target').trigger('click');

                        var image_src=$('.user-profile-image').attr('src');
                        var old_image_src=$('.old_image_src').val();

                        $.ajax({
                            url: APP_URL+'/save-profile-image',
                            type: 'POST',
                            data:{image_src:image_src,old_image_src:old_image_src},
                            dataType: 'json',
                            beforeSend: function() {
                            },
                            success: function(data)
                            {
                                if(data.status =="success"){
                                    toastr.success(data.msg);
                                } else {
                                    $('.user-profile-image').attr('src', '').attr('src',old_image_src);                                 
                                    toastr.warning(data.msg);
                                }
                            },
                            complete: function(){
                            },
                        });

                    }
                    reader.readAsDataURL(this.files[0]);
                }                

            } else {
                swal({
                  title: "Sorry!!!",
                  text: 'This browser does not support FileReader.Please upgrade your browser.',
                  type: "warning",
                  showCancelButton: true,  
                  //confirmButtonColor: "ff0000",
                  confirmButtonText: "Ok",
                  closeOnConfirm: true,
                });
            }
        } else {
            swal({
              title: "Sorry!!!",
              text: 'Your photo couldn\'t be uploaded. Photo should be less than 2 MB and saved as JPG, PNG, GIF or TIFF files.',
              type: "warning",
              showCancelButton: true,  
              //confirmButtonColor: "ff0000",
              confirmButtonText: "Ok",
              closeOnConfirm: true,
            });
        }
    });   

});






