jQuery(document).ready(function($) {

    initDeleteTarget();   

     // like or unlike count
    $(document).on('click','.post-like-click',function (e) {

        var postId=$(this).parent('a').attr('data-post-id');
        var current_element=$(this);

        var counterElement=$(current_element).parents('.post-counter-bar').find('.post-like-counter');
        var counterElementVal=$(counterElement).html();

        $(document).find('[data-post-id="'+postId+'"]').attr('data-auto-refresh','true');

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

    // slap action on Image then trigger html click
    $(document).on('dblclick','.post-slap-click-trigger',function (e) {        
        var current_element=$(this);
        $(current_element).parents('.post-container').find('.post-counter-bar .post-slap-click').trigger('dblclick');
    });

     // slap  action on counter 
    $(document).on('dblclick','.post-slap-click',function (e) {
        var postId=$(this).parent('a').attr('data-post-id');
        var current_element=$(this);

        var counterElement=$(current_element).parents('.post-counter-bar').find('.post-slap-counter');
        var counterElementVal=$(counterElement).html();

        $(document).find('[data-post-id="'+postId+'"]').attr('data-auto-refresh','true');

        if($(current_element).hasClass('slapped-class'))
        {
            var newCount=parseFloat(counterElementVal) - 1;
            newCount=(newCount < 0) ? 0 : newCount;
            $(counterElement).html(newCount);

            $(current_element).removeClass('slapped-class');

            $.ajax({
                url: APP_URL + '/post/slap/'+postId,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(data)
                {
                    if($(current_element).hasClass('slapped-class')){
                        $(current_element).removeClass('slapped-class');
                    }
                    
                },
                complete: function(){
                },
            });


        }else{

            var newCount= 1 + parseFloat(counterElementVal);
            $(counterElement).html(newCount);

            $(current_element).addClass('slapped-class');

            $.ajax({
                url: APP_URL + '/post/slap/'+postId,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(data)
                {
                    $(current_element).addClass('slapped-class');
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

    $(document).on('click','.post-slapped-users',function(){

        $('#post_slapped_users').modal('show');
        var postId=$(this).attr('data-post-id');
        $('.load-post-slapped-user-list').html('');

        $.ajax({
            url: APP_URL + '/post-slapped-users/'+postId,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                //$("body").addClass("loading").append('<img src="'+baseurl+'/images/ajax-loader.gif" id="loader_image">');
            },
            success: function(data)
            {
                if(data.status == 200){
                    $('.load-post-slapped-user-list').append(data.html_result);
                }
            },
            complete: function(){
                /*$("body").removeClass("loading");
                $('#loader_image').remove();*/
            },
        });
    });
    
});




