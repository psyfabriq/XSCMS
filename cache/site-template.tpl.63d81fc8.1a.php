<?php 
/** XSCMS template 'site/site-template.tpl' compiled at 2016-07-11 16:32:48 */
return new XSCMS\Render($fenom, function ($var, $tpl) {
?>
<div id="wrapper" class="container">
    
       

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
             <br>
             <div class="col-xs-12 col-md-12 col-lg-12"><img src="../images_public/system/svdmitry.png" class="img-responsive" alt="Cinque Terre"></div>
             <div class="col-xs-12 col-md-12 col-lg-12"><?php
/* site/site-template.tpl:10: {include '../templates/site/blocks/site-block-navbar.tpl'} */
 $tbec1189a_1 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.topbar_home"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $tbec1189a_1; unset($tbec1189a_1); ?></div>
            
           
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        
        
        <div id="page-content-wrapper">
            <div class="wide"></div>
            <div class="container-fluid">
                
                <div class="row">
                    <?php
/* site/site-template.tpl:24: {include '../templates/site/blocks/site-block-bodyprefix.tpl'} */
 $tbec1189a_2 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.bodyprefix_home"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $tbec1189a_2; unset($tbec1189a_2); ?>
                      <div  ng-view class="view-slide-in" ></div>
                    <?php
/* site/site-template.tpl:26: {include '../templates/site/blocks/site-block-bodypostfix.tpl'} */
 $tbec1189a_3 = $var; ?><div ng-controller="BlockController as ctrl">
    <div ng-repeat="blocks in blocksList.bodypostfix_home"> <block-constructor block="blocks"></block-constructor> </div>
</div> <?php $var = $tbec1189a_3; unset($tbec1189a_3); ?>
                  
                </div>
            </div>
        </div>
        
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper --><?php
}, array(
	'options' => 192,
	'provider' => false,
	'name' => 'site/site-template.tpl',
	'base_name' => 'site/site-template.tpl',
	'time' => 1468243965,
	'depends' => array (
  0 => 
  array (
    '../templates/site/blocks/site-block-navbar.tpl' => 1468243965,
    '../templates/site/blocks/site-block-bodyprefix.tpl' => 1468243965,
    '../templates/site/blocks/site-block-bodypostfix.tpl' => 1468243965,
    'site/site-template.tpl' => 1468243965,
  ),
),
	'macros' => array(),

        ));
