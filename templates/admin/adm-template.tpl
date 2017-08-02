
    <div ng-controller="AdminAppControler" class=" wrapper ng-cloak"   >
      <header class="main-header">
        <a  class="logo">
          <span class="logo-mini"><b>X</b>S</span>
          <span class="logo-lg"><b>XS</b>CMS</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          {include '../templates/admin/blocks/adm-block-topbar.tpl'} 
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              
              <li class="dropdown user user-menu">
                  <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i></a>
                     <ul class="dropdown-menu">
                  <li class="user-header"></li>
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#/profile" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="../logout" class="btn btn-default btn-flat">Logout</a>
                    </div>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <aside class="main-sidebar">
        {include '../templates/admin/blocks/adm-block-leftsidebar.tpl'}   
      </aside>
      <div class="content-wrapper" pfq-scroll>
        <section class="content" >
          <div class="row">
             <div growl></div>
             {include '../templates/admin/blocks/adm-block-bodyprefix.tpl'}    
             <div ng-if="!isRouteLoading" ng-view class="view-slide-in" ></div>
             {include '../templates/admin/blocks/adm-block-bodypostfix.tpl'}
          </div>
          </section>
      </div>

      <footer class="main-footer">
        {include '../templates/admin/blocks/adm-block-footerbar.tpl'}  
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0 a
        </div>
        <strong>Develop &copy; 2015 <a href="http://xscms.ru">PsyFabriQ </a>.</strong> DEV
      </footer>
       {include '../templates/admin/blocks/adm-block-rightsidebar.tpl'}
    </div>