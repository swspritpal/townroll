<div class="col-md-2 static hidden-xs hidden-sm">
    <div class="suggestions" id="sticky-sidebar" style="">
      <div class="create-post ss-container" >

        <div class="row" >
          <!-- <div class="col-md-12 col-sm-12 ">     
            <div class="form-group">  
              <textarea  cols="100" rows="1" class="form-control" placeholder="Write here anything..." data-toggle="modal" data-target="#myModal"></textarea>
                <div class="modal fade" id="myModal" role="dialog">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title"><i class="ion-compose myIcons" ></i> Create a post  |  <i class="ion-image myIcons"></i> Photo/Album</a></h5>
                      </div>
                      <div class="modal-body">
                        <textarea name="texts" id="exampleTextarea" cols="60" rows="1" class="form-control" placeholder="Write here anything..." ></textarea>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default pull right" data-dismiss="modal">Post</button>
                      </div>
                    </div>      
                  </div>
                </div>
            </div>
          </div> -->

          <!-- <div class="col-md-5 col-sm-5">
            <div class="tools">
              <ul class="publishing-tools list-inline">
                <li><a href="#"><i class="ion-compose"></i></a></li>
                <li><a href="#"><i class="ion-images"></i></a></li>
                <li><a href="#"><i class="ion-ios-videocam"></i></a></li>
                <li><a href="#"><i class="ion-map"></i></a></li>
              </ul>
              <button class="btn btn-primary pull-right">Publish</button>
            </div>
          </div> -->

        <!-- </div>  
        <h5 class="modal-title"> <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal">  <i class="ion-compose myIcons" ></i> Create a post </a> &nbsp; &nbsp; &nbsp;  <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal"> <i class="ion-image myIcons"></i> Photo / album </a> </a></h5> -->
        
      </div>
                      
        <div class="rightLocations right-sidebar-locations" >
              <a href="javascript:void(0);"  class='locate-me-popup' > 
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 paddingUnset seperatorGroup locateMeButton">
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  paddingUnset">
                    <img src="{{asset('img/icons/locateMeNew.png') }}" class="imgCircle" />
                  </div>
                  <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
                    <div class="groupTitle single locateMeButton">Locate me </div>
                  </div>
                </div>
              </a>
              
              @unless(empty($categories))
                @foreach($categories as $user_category)
                  @include('frontend.includes.categories.horizontal',compact('user_category'))
                @endforeach
              @endunless
                                
              <!--  <div class="viewAllGroups hidden-xs hidden-sm"><span class="pull-right"><a href="javascript:void(0);" class="viewAllGroupLink">View all</a> </span> </div> -->
        </div>
        <div style="clear:both"> </div>

    </div> <!-- Create post Ends -->
</div>
<style>
.place-active .groupTitle {
    color: #2da5da;
}
</style>