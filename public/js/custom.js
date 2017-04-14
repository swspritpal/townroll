jQuery(document).ready(function($) {

    if(('.convert_space_into_underscore').length){

        $(".convert_space_into_underscore").keyup(function () {
            var textValue = $(this).val();
            textValue =textValue.replace(/ /g,"_");
            $(this).val(textValue);
        });
    }

    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(setNewValueToLocationCookie);
    }else{
        alert("Geolocation is not supported by this browser. Please update with new one.");
    }

    function setNewValueToLocationCookie(position)
    {
      setCookie("latitude",position.coords.latitude);
      setCookie("longitude",position.coords.longitude);
    }


    function setCookie(key, value) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
        //document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        document.cookie = key + '=' + value ;
    }

    function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }

    function positionError(error) {
        if(error.PERMISSION_DENIED){
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
                swal("Deleted!", "Your imaginary file has been deleted.", "success");
              } else {
                //swal("Cancelled", "Your imaginary file is safe :)", "error");
              }
            });

            return false;
        }
    }


    $(document).on('click','.locate-me-popup',function(){

        // re-inilize the location params
        navigator.geolocation.getCurrentPosition(setNewValueToLocationCookie,positionError);

        $('.new-places-output').html('');

        

        $.ajax({
            url: APP_URL + '/near-by-places',
            type: 'get',
            dataType: 'json',
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
    });

    $(document).on('click','.join-new-place',function(e){
        e.preventDefault(e);

        var form=$(this).parents('.add-new-place-form');

        $.ajax({
            url: APP_URL + '/add-new-place',
            type: 'post',
            data:form.serialize(),
            dataType: 'json',
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






