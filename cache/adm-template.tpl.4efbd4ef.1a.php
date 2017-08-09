<?php 
/** XSCMS template 'admin/adm-template.tpl' compiled at 2016-07-04 11:33:58 */
return new XSCMS\Render($fenom, function ($var, $tpl) {
?>
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
          <?php
/* admin/adm-template.tpl:12: {include '../templates/admin/blocks/adm-block-topbar.tpl'} */
 $te7fc0fd1_1 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.top_bar"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $te7fc0fd1_1; unset($te7fc0fd1_1); ?> 
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
        <?php
/* admin/adm-template.tpl:38: {include '../templates/admin/blocks/adm-block-leftsidebar.tpl'} */
 $te7fc0fd1_2 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.left_sidebar"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $te7fc0fd1_2; unset($te7fc0fd1_2); ?>   
      </aside>
      <div class="content-wrapper" pfq-scroll>
        <section class="content" >
          <div class="row">
             <div growl></div>
             <?php
/* admin/adm-template.tpl:44: {include '../templates/admin/blocks/adm-block-bodyprefix.tpl'} */
 $te7fc0fd1_3 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.body_prefix"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $te7fc0fd1_3; unset($te7fc0fd1_3); ?>    
             <div ng-if="!isRouteLoading" ng-view class="view-slide-in" ></div>
             <?php
/* admin/adm-template.tpl:46: {include '../templates/admin/blocks/adm-block-bodypostfix.tpl'} */
 $te7fc0fd1_4 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.body_postfix"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $te7fc0fd1_4; unset($te7fc0fd1_4); ?>
          </div>
          </section>
      </div>

      <footer class="main-footer">
        <?php
/* admin/adm-template.tpl:52: {include '../templates/admin/blocks/adm-block-footerbar.tpl'} */
 $te7fc0fd1_5 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.footer_bar"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $te7fc0fd1_5; unset($te7fc0fd1_5); ?>  
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0 a
        </div>
        <strong>Develop &copy; 2015 <a href="http://xscms.ru">PsyFabriQ </a>.</strong> DEV
      </footer>
       <?php
/* admin/adm-template.tpl:58: {include '../templates/admin/blocks/adm-block-rightsidebar.tpl'} */
 $te7fc0fd1_6 = $var; ?><aside class="control-sidebar control-sidebar-light">
   <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab" showtab=""><i class="fa fa-home"></i></a></li>
      <li ><a href="#control-sidebar-theme-xscmsstyle-options-tab" data-toggle="tab" showtab=""><i class="fa fa-wrench"></i></a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane active" id="control-sidebar-home-tab">
         <div ng-controller="BlockController as ctrl">
            <div ng-repeat="blocks in blocksList.right_sidebar_home"> <block-constructor block="blocks"></block-constructor> </div>
        </div> 
      </div>
      <div id="control-sidebar-theme-xscmsstyle-options-tab" class="tab-pane">
         <div>
          
           <div ng-controller="BlockController as ctrl">
                <h4 class="control-sidebar-heading">Skins</h4>
            <ul class="list-unstyled clearfix">
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-blue" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin">Blue</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-black" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin">Black</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-purple" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin">Purple</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-green" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin">Green</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-red" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin">Red</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-yellow" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin">Yellow</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-blue-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin" style="font-size: 12px">Blue Light</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-black-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin" style="font-size: 12px">Black Light</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-purple-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin" style="font-size: 12px">Purple Light</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin" style="font-size: 12px">Green Light</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-red-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin" style="font-size: 12px">Red Light</p>
               </li>
               <li style="float:left; width: 33.33333%; padding: 5px;">
                  <a href="javascript:void(0);" data-skin="skin-yellow-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">
                     <div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div>
                     <div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div>
                  </a>
                  <p class="text-center no-margin" style="font-size: 12px;">Yellow Light</p>
               </li>
            </ul>
                <div ng-repeat="blocks in blocksList.right_sidebar_settings"> <block-constructor block="blocks"></block-constructor> </div>
           </div> 
            
         </div>
      </div>
   </div>
</aside>
<div class='control-sidebar-bg'></div><?php $var = $te7fc0fd1_6; unset($te7fc0fd1_6); ?>
    </div><?php
}, array(
	'options' => 192,
	'provider' => false,
	'name' => 'admin/adm-template.tpl',
	'base_name' => 'admin/adm-template.tpl',
	'time' => 1467619516,
	'depends' => array (
  0 => 
  array (
    '../templates/admin/blocks/adm-block-topbar.tpl' => 1467619517,
    '../templates/admin/blocks/adm-block-leftsidebar.tpl' => 1467619516,
    '../templates/admin/blocks/adm-block-bodyprefix.tpl' => 1467619516,
    '../templates/admin/blocks/adm-block-bodypostfix.tpl' => 1467619516,
    '../templates/admin/blocks/adm-block-footerbar.tpl' => 1467619516,
    '../templates/admin/blocks/adm-block-rightsidebar.tpl' => 1467619517,
    'admin/adm-template.tpl' => 1467619516,
  ),
),
	'macros' => array(),

        ));
