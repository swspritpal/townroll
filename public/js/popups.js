jQuery(document).ready(function($) {

    $(document).on('click','[data-action="user-profile"]',function(){
        var user_id=$(this).attr('data-user-id');
   
        $('#user_profile_popup').modal('show');
        $('.add-profile-data').html('');
        
        $.ajax({
            url: APP_URL + '/profile-popup?id='+user_id,
            type: 'get',
            dataType: 'html',
            beforeSend: function() {
                $('.add-profile-data').html('<img class="center-block loader-image img-responsive" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
            },
            success: function(data)
            {
                $('.add-profile-data').append(data);
            },
            complete: function(){
                $('.add-profile-data').find('.loader-image').remove();
            },
        });
    });


    $(document).on('click','[data-action="post-single-popup"]',function(){
        var post_id=$(this).attr('data-post');

        $('#single_post_popup').modal('show');
        $('.add-single-post-data').html('');
        $(document).find('[data-post-id="'+post_id+'"]').attr('data-auto-refresh','false');
        
        $.ajax({
            url: APP_URL + '/post-single-popup/'+post_id,
            type: 'get',
            dataType: 'html',
            beforeSend: function() {
                $('.add-single-post-data').html('<img class="center-block loader-image img-responsive" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
            },
            success: function(data)
            {
                $('.add-single-post-data').append(data);
                initDeleteTarget();
                init_comment_read_more();
            },
            complete: function(){
                $('.add-single-post-data').find('.loader-image').remove();
            },
        });
    }); 



    // When Single post popup is about to close then check IS there need to refresh the post 
    $(document).on('hidden.bs.modal','#single_post_popup,#boost_post_popup', function (e) {
        // Need to get updated data from server for post
        if($('.append-new-post-content').find('[data-auto-refresh="true"]').length != 0){

            var current_obj=$('.append-new-post-content').find('[data-auto-refresh="true"]');
            var post_id=$(current_obj).data('post-id');
            $(current_obj).html('');
                        
            $.ajax({
                url: APP_URL + '/post-single-popup/'+post_id+'/true',
                type: 'get',
                dataType: 'html',
                beforeSend: function() {
                    $(current_obj).html('<img class="center-block loader-image img-responsive" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
                },
                success: function(data)
                {
                    $(current_obj).append(data);
                    initDeleteTarget();
                    init_comment_read_more();
                },
                complete: function(){
                    $(current_obj).find('.loader-image').remove();
                },
            });
        }
    })

    // Boost post concept 
    $(document).on('click','.boost-this-post',function(){
        $('#boost_post_popup').modal('show');
        var postId=$(this).attr('data-post-id');
        $('.load-user-groups, .empty_boost_form_error').html('');
        $('#boost_unchecked_categories').val('');

        $('#boost_post_id').val(postId);
        $.ajax({
            url: APP_URL + '/boost/create/'+postId,
            type: 'get',
            dataType: 'html',
            beforeSend: function() {
                $(".load-user-groups").append('<img class="center-block loader-image img-responsive" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
            },
            success: function(data)
            {
                $('.load-user-groups').append(data);
            },
            complete: function(){
                $(".load-user-groups").find('.loader-image').remove();
            }
        });
    });

    $(document).on('click','.load-more-boost-posts',function(){
        var next_page_url=$(this).data('next-page-url');
        $('.boost-paginator').html('');

        $.ajax({
            url: next_page_url,
            type: 'get',
            dataType: 'html',
            beforeSend: function() {
                $(".boost-paginator").append('<img class="center-block loader-image img-responsive" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
            },
            success: function(data)
            {
                $('.load-user-groups').append(data);
            },
            complete: function(){
                $(".boost-paginator").find('.loader-image').remove();
            }
        });
    });

    $(document).on('click','.boost_search_button',function(){
        var search_key=$('#boost_category_search').val();
        var postId=$(this).attr('data-post-id');
        $('.load-user-groups').html('');

        $.ajax({
            url: APP_URL + '/boost/create/'+postId+'/'+search_key,
            type: 'get',
            dataType: 'html',
            beforeSend: function() {
                $(".load-user-groups").append('<img class="center-block loader-image img-responsive" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
            },
            success: function(data)
            {
                $('.load-user-groups').append(data);
            },
            complete: function(){
                $(".load-user-groups").find('.loader-image').remove();
            }
        });
    });

    $(document).on('click','.boost_category_checkbox',function() {
        
        if(!$(this).is(':checked')){
            var category_value=$(this).val();

            if (confirm('Are you sure you want to unboost this post from this group ?')) {
                $('#boost_unchecked_categories').val(function(i,val) { 
                    return val + (!val ? '' : ', ') + category_value;
                });    
            } else {
                $(this).prop('checked', true);
            }            
        }
        
    });

    $(document).on('click','.boost_submit',function(){
        var current_obj=$(this);
        var form_data=$('#boost_post_category_form').serialize();

        //console.log(form_data);

        $.ajax({
            url: APP_URL + '/boost/store/',
            type: 'POST',
            data:form_data,
            dataType: 'json',
            beforeSend: function() {
                $(current_obj).html('Sending...');
            },
            success: function(data)
            {
                if(data.status == 'error_validate'){
                    $('.empty_boost_form_error').html(data.message);
                }else if(data.status == 'error'){
                    $('#boost_post_popup').modal('hide');
                    toastr.warning(data.message);
                    
                }else if(data.status == 'success'){
                    $('#boost_post_popup').modal('hide');

                    $(document).find('[data-post-id="'+data.post_id+'"]').attr('data-auto-refresh','true');
                    toastr.success(data.message);
                }
            },
            complete: function(){
                $(current_obj).html('Boost Post Now');
            }
        });
    });

});

