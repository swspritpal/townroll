
jQuery(document).ready(function($) {


     $.validator.addMethod("valueNotEquals", function(value, element, arg){
        return arg != value;
     }, "Please select a item!.");

    $.validator.addMethod("noSpace", function(value, element) {
      return value.indexOf(" ") < 0 && value != ""; 
    }, "No space please and don't leave it empty");

    // Signup form
    if(($('#need_signup').length) && ($('#need_signup').val() == "true") ){
        
        
        var country,city,state='';

        $('#signup_form_modal').modal('show');

        $.get("//ipinfo.io", function (response) {            
            country=response.country;
            city=response.city;
            state=response.region;

            //alert(state);

            /*$("#ip").html("IP: " + response.ip);
            $("#address").html("Location: " + response.city + ", " + response.region);
            $("#details").html(JSON.stringify(response, null, 4));*/

            if(country != ''){
                if(state != ''){
                    $('#signup_default_state').val(state);
                }
                if(city != ''){
                    $('#signup_default_city').val(city);
                }

                $('#signup_form_country').val(country).trigger('change');
            }

        }, "jsonp");



        $('#signup_form_country').on('change',function (e) {
            var selectedCountry=$(this).val();
            var defaultState=$('#signup_default_state').val();


            $("#signup_form_state option:gt(0)").remove(); 
            $("#signup_form_city option:gt(0)").remove(); 

            $.get(APP_URL+ '/states/'+selectedCountry, function (data) {
                if(data.status == 'success'){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        $('#signup_form_state').append(option);
                        // auto selected values
                        if(defaultState != '' && val == defaultState){
                            option.attr('selected', 'selected');
                            $('#signup_form_state').trigger('change');
                        }

                    });
                    $("#signup_form_state").prop("disabled",false);

                }
                else{
                    console.log(data.message);
                }

            },'json');
        });

        $('#signup_form_state').on('change',function (e) {

            var selectedState=$(this).val();
            var defaultCity=$('#signup_default_city').val();

            $("#signup_form_city option:gt(0)").remove(); 

            $.get(APP_URL+ '/cities/'+selectedState, function (data) {
                if(data.status == 'success'){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);

                         // auto selected values
                        if(defaultCity != '' && val == defaultCity){
                            option.attr('selected', 'selected');
                        }

                        $('#signup_form_city').append(option);
                    });
                    $("#signup_form_city").prop("disabled",false);
                }
                else{
                    console.log(data.message);
                }

            },'json');
        });

        $('#save_signup').click(function(){

            $("#signup_form").validate({
                rules: {                  
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength:50,
                        noSpace:true,
                        remote: {
                            type: 'POST',
                            url: APP_URL + '/unique-username'
                        }
                    },
                    state: {
                        valueNotEquals: "0",
                    },
                    city: {
                        valueNotEquals: "0",
                    },
                },
                messages: {
                    username: {
                        required: "Please enter your unique identity",
                        minlength: "Username is too short. Please enter loonger one.",
                        noSpace: "Space are not allowed in username",

                    },
                    state: {
                        valueNotEquals: "Select your state",
                    },
                    city: {
                        valueNotEquals: "select your city",
                    },
                }
            });


           if($("#signup_form").valid()){           

                $.ajax({
                    url: APP_URL + '/signup',
                    type: 'post',
                    dataType: 'json',
                    data: $('#signup_form').serialize(),
                    beforeSend: function() {
                        //$("body").addClass("loading").append('<img src="'+baseurl+'/images/ajax-loader.gif" id="loader_image">');
                        $('#save_signup').prop('disabled',true);
                    },
                    success: function(data)
                    {
                        if(data.status == "success"){
                            $('#signup_form_modal').modal('hide');
                            toastr.success(data.message);
                        }else{
                            toastr.warning(data.message);                            
                        }
                    },
                    complete: function(){
                        /*$("body").removeClass("loading");
                        $('#loader_image').remove();*/
                        $('#save_signup').prop('disabled',false);
                    },
                });
            }
        });
    }
});




