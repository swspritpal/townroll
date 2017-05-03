jQuery(document).ready(function($) {

    // clean post content beofore showing modal
    $(document).on('click',"[data-target='#add_post']", function(){
        $('#post_content').val('');
        $('.delete-post-image-icon').trigger('click');
    });

    $('.post-upload-image-link').click(function(){
        $('.post_image_file_input').trigger('click');
    });

    $('#post_category_drop_down').select2();


    $('.post_image_file_input').change(function(event)
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
                        //$('.wrapper_'+id).removeClass('hidden');
                        $('.post-upload-image-preview').attr('src', '');
                        $('.post-upload-image-preview').attr('src', e.target.result);

                        $('#is_post_have_image').val('yes');

                        $('.show-on-upload').removeClass('hidden');
                        $('.post-content-error').hide();

                       /* // You selected section
                        if(id == "largeImage"){
                            $('.choose_main_image').removeClass('hidden');
                            $('.choose_main_image').find('.main_image_show .choose-value').attr('src',e.target.result);
                            $('.large-image').addClass('addImage').attr('id','add_main_image');
                        }
                        $('.custom-images-error-icon').removeClass('hidden').removeClass('fa-times').addClass('fa-check');*/
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

    function updateCoords(c)
    {
        $('#image_x').val(c.x);
        $('#image_y').val(c.y);
        $('#image_w').val(c.w);
        $('#image_h').val(c.h);
    };


    $('.crop-tool-icon').click(function(){

        var jcrop_api,
            boundx,
            boundy;

        $('#unhook').show();
        $('#upload_crop_image').show();

        $(this).hide();

        // In this example, since Jcrop may be attached or detached
        // at the whim of the user, I've wrapped the call into a function
        initJcrop();
        
        // The function is pretty simple
        function initJcrop()
        {
          // Hide any interface elements that require Jcrop
          // (This is for the local user interface portion.)

          //$('.requiresjcrop').hide();

          // Invoke Jcrop in typical fashion
          $('.jcrop-init-target').Jcrop({
            aspectRatio: 4/3,
            minSize: [ 100, 100 ],
            onChange: updateCoords,
            onSelect: updateCoords,
            onRelease: releaseCheck
          },function(){

            jcrop_api = this;
            //jcrop_api.animateTo([100,100,400,300]);
            jcrop_api.animateTo([217,122,382,284]);

            // Setup and dipslay the interface for "enabled"
            //$('#can_click,#can_move,#can_size').attr('checked','checked');
            //$('#ar_lock,#size_lock,#bg_swap').attr('checked',false);

            //$('.requiresjcrop').show();

             // Use the API to get the real image size
              var bounds = this.getBounds();
              boundx = bounds[0];
              boundy = bounds[1];

          });

        };


        // Use the API to find cropping dimensions
        // Then generate a random selection
        // This function is used by setSelect and animateTo buttons
        // Mainly for demonstration purposes
        function getRandom() {
          var dim = jcrop_api.getBounds();
          return [
            Math.round(Math.random() * dim[0]),
            Math.round(Math.random() * dim[1]),
            Math.round(Math.random() * dim[0]),
            Math.round(Math.random() * dim[1])
          ];
        };

        // This function is bound to the onRelease handler...
        // In certain circumstances (such as if you set minSize
        // and aspectRatio together), you can inadvertently lose
        // the selection. This callback re-enables creating selections
        // in such a case. Although the need to do this is based on a
        // buggy behavior, it's recommended that you in some way trap
        // the onRelease callback if you use allowSelect: false
        function releaseCheck()
        {
            jcrop_api.setOptions({allowSelect: true});
            //jcrop_api.setOptions({aspectRatio: 4/3});
            //jcrop_api.setOptions({minSize: [ 100, 100 ]});
            //jcrop_api.focus();
            
          //$('#can_click').attr('checked',false);

        };

        $('#unhook').click(function(e) {
          // Destroy Jcrop widget, restore original state
          jcrop_api.destroy();
          // Update the interface to reflect un-attached state
          $('#unhook,#enable,.requiresjcrop,#upload_crop_image').hide();

          $('#rehook').show();
          $('.crop-tool-icon').show();
          return false;
        });

        $('#upload_crop_image').click(function(){   

            $('#is_crop_post_image').val('yes');

            var xsize,ysize='';
             // Destroy Jcrop widget, restore original state
              jcrop_api.destroy();
              $('#rehook').show();
              $('.crop-tool-icon').show();

            $pimg = $('.post-upload-image-preview');
            $pcnt = $('.post-upload-image-wrapper');

            xsize = $pcnt.width(),
            ysize = $pcnt.height();

            //$('.post-upload-image-preview').css('max-width','none');

            var c=new Object();
            c.x=$('#image_x').val();
            c.y=$('#image_y').val();
            c.w=$('#image_w').val();
            c.h=$('#image_h').val();


            if (parseInt(c.w) > 0)
            {
                var rx = xsize / c.w;
                var ry = ysize / c.h;

                console.log(boundx);
                
                $pimg.css({
                  width: Math.round(rx * boundx) + 'px',
                  height: Math.round(ry * boundy) + 'px',
                  //marginLeft: '-' + Math.round(rx * c.x) + 'px',
                  marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }

        });


        // Attach interface buttons
        // This may appear to be a lot of code but it's simple stuff
        $('#setSelect').click(function(e) {
          // Sets a random selection
          jcrop_api.setSelect(getRandom());
        });
        $('#animateTo').click(function(e) {
          // Animates to a random selection
          jcrop_api.animateTo(getRandom());
        });
        $('#release').click(function(e) {
          // Release method clears the selection
          jcrop_api.release();
        });
        $('#disable').click(function(e) {
          // Disable Jcrop instance
          jcrop_api.disable();
          // Update the interface to reflect disabled state
          $('#enable').show();
          $('.requiresjcrop').hide();
        });
        $('#enable').click(function(e) {
          // Re-enable Jcrop instance
          jcrop_api.enable();
          // Update the interface to reflect enabled state
          $('#enable').hide();
          $('.requiresjcrop').show();
        });
        $('#rehook').click(function(e) {
          // This button is visible when Jcrop has been destroyed
          // It performs the re-attachment and updates the UI
          $('#rehook,#enable').hide();
          initJcrop();
          $('#unhook,.requiresjcrop').show();
          return false;
        });

        // Hook up the three image-swapping buttons
        $('#img1').click(function(e) {
          $(this).addClass('active').closest('.btn-group')
            .find('button.active').not(this).removeClass('active');

          jcrop_api.setImage('demo_files/sago.jpg');
          jcrop_api.setOptions({ bgOpacity: .6 });
          return false;
        });
        $('#img2').click(function(e) {
          $(this).addClass('active').closest('.btn-group')
            .find('button.active').not(this).removeClass('active');

          jcrop_api.setImage('demo_files/pool.jpg');
          jcrop_api.setOptions({ bgOpacity: .6 });
          return false;
        });
        $('#img3').click(function(e) {
          $(this).addClass('active').closest('.btn-group')
            .find('button.active').not(this).removeClass('active');

          jcrop_api.setImage('demo_files/sago.jpg',function(){
            this.setOptions({
              bgOpacity: 1,
              outerImage: 'demo_files/sagomod.jpg'
            });
            this.animateTo(getRandom());
          });
          return false;
        });

        // The checkboxes simply set options based on it's checked value
        // Options are changed by passing a new options object

        // Also, to prevent strange behavior, they are initially checked
        // This matches the default initial state of Jcrop

        $('#can_click').change(function(e) {
          jcrop_api.setOptions({ allowSelect: !!this.checked });
          jcrop_api.focus();
        });
        $('#can_move').change(function(e) {
          jcrop_api.setOptions({ allowMove: !!this.checked });
          jcrop_api.focus();
        });
        $('#can_size').change(function(e) {
          jcrop_api.setOptions({ allowResize: !!this.checked });
          jcrop_api.focus();
        });
        $('#ar_lock').change(function(e) {
          jcrop_api.setOptions(this.checked?
            { aspectRatio: 4/3 }: { aspectRatio: 0 });
          jcrop_api.focus();
        });
        $('#size_lock').change(function(e) {
          jcrop_api.setOptions(this.checked? {
            minSize: [ 80, 80 ],
            maxSize: [ 350, 350 ]
          }: {
            minSize: [ 0, 0 ],
            maxSize: [ 0, 0 ]
          });
          jcrop_api.focus();
        });


    });

    $('.delete-post-image-icon').click(function(){
        $('#is_post_have_image,#is_crop_post_image').val('no');

        $('.post-upload-image-preview').attr('src','').css('max-width','100%');
        if(!$('.show-on-upload').hasClass('hidden')){
            $('.show-on-upload').addClass('hidden');
        }
        
    });
    $('#post_content').keyup(function(){
        var postContent=$('#post_content').val();
        if (postContent.length <= 0){
            $('.post-content-error').show().html('Post can\'t be null.' );
        }else{
            $('.post-content-error').hide();
        }
    });

    $('#post_category_drop_down').change(function(){
        var postCategories=$('#post_category_drop_down').val();
        if (postCategories.length <= 0){
            $('.post-categories-error').show().html('choose who can see your post');
        }else{
            $('.post-categories-error').hide();
        }
    });


    // Progressbar for post insertion
      var $progressbar = $(".home-post-progressbar");
      var updateProgressBar = function(evt) {
          if(evt.lengthComputable) {
              var percent = (evt.loaded*100)/evt.total;
              $(function(){
                  $progressbar.css('width', percent.toFixed(1) + '%');
              }); 
          }
      }

    $('#save_new_post').click(function(e){
        e.preventDefault();
        //inilize
        var postMustHaveContent=true;
        var form_data = new FormData();
        var isPostHaveImage=$('#is_post_have_image').val();
        var isPostImageCrop=$('#is_crop_post_image').val();
        var postContent=$('#post_content').val();
        var postCategories=$('#post_category_drop_down').val();
        //validation part
        if (postContent.length <= 0 && isPostHaveImage == "no"){
            postMustHaveContent=false;
            $('.post-content-error').show().html('Post can\'t be null.' );
        }else{
            postMustHaveContent=true;
            $('.post-content-error').hide();
        }

        if(postCategories.length <= 0){
            $('.post-categories-error').show().html('choose who can see your post');
        }else{
            $('.post-categories-error').hide();
        }

        if (postMustHaveContent == false || postCategories.length <= 0){
            return false;
        }else{
            $('.post-content-error,.post-content-error').hide();
        }
        //form submission prepared
        
        if(isPostHaveImage == "yes"){
            var file_data = $('.post-upload-image-preview').attr('src');
            form_data.append("file", file_data);
        }
        if(isPostImageCrop == "yes"){
            form_data.append("image_x", $('#image_x').val());
            form_data.append("image_y", $('#image_y').val());
            form_data.append("image_w", $('#image_w').val());
            form_data.append("image_h", $('#image_h').val());
        }

        
        form_data.append("post_content", postContent);
        form_data.append("post_categories", postCategories);


        //$progressbar.show();
        $.ajax({
            xhr: function() {
                  var req = new XMLHttpRequest();
                  req.upload.addEventListener("progress", updateProgressBar, false);
                  req.addEventListener("progress", updateProgressBar, false);
                  return req;
              },
            url: APP_URL + '/post/store',
            type: 'post',
            dataType: 'json',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                //$("body").addClass("loading").append('<img src="'+baseurl+'/images/ajax-loader.gif" id="loader_image">');
                $('#save_new_post').prop('disabled',true);
            },
            success: function(data)
            {
                if(data.status == "success"){
                    $('#add_post').modal('hide');
                    toastr.success(data.message);
                    $('.append-new-post-content').prepend(data.html_result);

                    // remove no post method 
                    if($('.no-post').length != 0){
                      $('.no-post').remove();
                    }
                    //$progressbar.css('width', '100%');
                }else{
                    toastr.warning(data.message);                            
                }
            },
            complete: function(){
                /*$("body").removeClass("loading");
                $('#loader_image').remove();*/
                $('#save_new_post').prop('disabled',false);
            },
        });
    //}).uploadProgress(updateProgressBar).upload(updateProgressBar);
    });

    
});




