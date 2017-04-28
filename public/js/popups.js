jQuery(document).ready(function($) {

    $(document).on('click','[data-action="user-profile"]',function(){
        var user_id=$(this).attr('data-user-id');
   
        $('#user_profile_popup').modal('show');
        $('.new-places-output').html('');
        
        $.ajax({
            url: APP_URL + '/profile-popup?id='+user_id,
            type: 'get',
            dataType: 'html',
            beforeSend: function() {
                $('.add-profile-data').html('<img class="center-block loader-image" src="'+APP_URL+'/img/loader.gif" alt="Loading..." />');
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
   

});

