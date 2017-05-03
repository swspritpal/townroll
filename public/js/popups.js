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

                $('.comment-pagination ul.pagination').hide();
                $('.comment-infinite-scroll').jscroll({
                    autoTrigger: true,
                    loadingHtml: '<img class="center-block img-responsive" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />',
                    padding: 0,
                    nextSelector: '.comment-pagination .pagination li.active + li a',
                    contentSelector: 'div.comment-infinite-scroll',
                    callback: function() {
                        $('ul.pagination').remove();
                    }
                });
                initDeleteTarget();
            },
            complete: function(){
                $('.add-single-post-data').find('.loader-image').remove();
            },
        });
    });   

});

