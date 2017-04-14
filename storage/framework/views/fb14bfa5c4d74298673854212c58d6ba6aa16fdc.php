<div class="createPostWrapper">
      <div class="createPost">
        <div class="col-md-12 col-sm-12 paddingUnset">

          <h5 class="modal-title"> 
            <a href="javascript:void(0);" data-toggle="modal" data-target="#add_post">  
              <i class="ion-compose myIcons" ></i> Create a post
            </a>
               &nbsp; &nbsp; |  &nbsp; &nbsp;
            <a href="javascript:void(0);" data-toggle="modal" data-target="#add_post" class="post-upload-image-link"> 
              <i class="ion-image myIcons"></i> Photo/Album 
            </a>
          </h5>

        </div>
      </div>
    <div class="create-post"> 
        <div class="row">
            <div class="col-md-12 col-sm-12 ">  
          <div class="form-group">
            <img src="<?php echo e($logged_in_user->picture); ?>" alt="" class="profile-photo-md">
            <textarea  cols="80" rows="2" class="form-control" placeholder="Write here anything..." data-toggle="modal" data-target="#add_post" readonly="true"></textarea>
          </div>
        </div>
        </div>
    </div>
</div><!-- Post Create Box End-->