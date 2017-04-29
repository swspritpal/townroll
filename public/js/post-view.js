jQuery(document).ready(function($) {

    initDeleteTarget();

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

     // like or unlike count
    $(document).on('click','.post-like-click',function (e) {

        var postId=$(this).parent('a').attr('data-post-id');
        var current_element=$(this);

        var counterElement=$(current_element).parents('.post-counter-bar').find('.post-like-counter');
        var counterElementVal=$(counterElement).html();

        if($(this).hasClass('fa-heart'))
        {
            var newCount=parseFloat(counterElementVal) - 1;
            newCount=(newCount < 0) ? 0 : newCount;
            $(counterElement).html(newCount);

            $(current_element).removeClass('fa-heart').addClass('fa-heart-o');

            $.ajax({
                url: APP_URL + '/post/like/'+postId,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(data)
                {
                    if($(current_element).hasClass('fa-heart-o')){
                        $(current_element).removeClass('fa-heart-o').addClass('fa-heart');
                    }
                    
                },
                complete: function(){
                },
            });


        }else if($(this).hasClass('fa-heart-o')){

            var newCount= 1 + parseFloat(counterElementVal);
            $(counterElement).html(newCount);

            $(current_element).removeClass('fa-heart-o').addClass('fa-heart');

            $.ajax({
                url: APP_URL + '/post/like/'+postId,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(data)
                {
                    if($(current_element).hasClass('fa-heart-o')){
                        $(current_element).removeClass('fa-heart-o').addClass('fa-heart');
                    }
                },
                complete: function(){
                },
            });

           
            
        }
    });


    $(document).on('click','.post-view-by-users',function(){

        $('#post_view_users').modal('show');
        var postId=$(this).attr('data-post-id');
        $('.load-viewed-user-list').html('');

        $.ajax({
            url: APP_URL + '/post-viewed-users/'+postId,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                //$("body").addClass("loading").append('<img src="'+baseurl+'/images/ajax-loader.gif" id="loader_image">');
            },
            success: function(data)
            {
                if(data.status == 200){
                    $('.load-viewed-user-list').append(data.html_result);
                }
            },
            complete: function(){
                /*$("body").removeClass("loading");
                $('#loader_image').remove();*/
            },
        });
    });

    $(document).on('click','.post-liked-users',function(){

        $('#post_liked_users').modal('show');
        var postId=$(this).attr('data-post-id');
        $('.load-post-liked-user-list').html('');

        $.ajax({
            url: APP_URL + '/post-liked-users/'+postId,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                //$("body").addClass("loading").append('<img src="'+baseurl+'/images/ajax-loader.gif" id="loader_image">');
            },
            success: function(data)
            {
                if(data.status == 200){
                    $('.load-post-liked-user-list').append(data.html_result);
                }
            },
            complete: function(){
                /*$("body").removeClass("loading");
                $('#loader_image').remove();*/
            },
        });
    });

    
});




