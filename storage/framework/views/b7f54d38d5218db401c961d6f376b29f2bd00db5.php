<header id="header">
      <nav class="navbar navbar-default navbar-fixed-top menu">
        <div class="container">

          <!-- Brand and toggle get grouped for better mobile display -->
         

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right main-menu">
              
              <li class="dropdown">
                <a href="javascript:void(0);"> <img src="<?php echo e($logged_in_user->picture); ?>" alt="user" class="menu-profile-photo"> <span>
                </span></a>
                 
              </li>
              <li class="dropdown">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#add_post"> <i class="fa fa-plus-square-o fa-2x" aria-hidden="true"></i> </span></a>
                 
              </li>
              <li class="dropdown">
                <a href="javascript:void(0);" class='locate-me-popup' > <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i> </a>
                 
              </li>

              <li class="dropdown">
                <a href="<?php echo e(route('frontend.index')); ?>"> <i class="fa fa-home fa-2x" aria-hidden="true"></i> </a>                
              </li>
              
            </ul>

            <div class="pull-right navLogout">
              <?php echo e(link_to_route('frontend.auth.logout', 'Logout',null,['class'=>'fa fa-sign-out fa-fw'])); ?>

            </div>

          </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
      </nav>
    </header>