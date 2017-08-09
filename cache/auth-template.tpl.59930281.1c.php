<?php 
/** XSCMS template 'system/auth-template.tpl' compiled at 2016-07-04 11:33:47 */
return new XSCMS\Render($fenom, function ($var, $tpl) {
?><div class="container">
       <div ng-controller="MessageController" class="ng-cloak">
        <div ng-if="isshowmessage">
            <div class="[[typemessage.class]]">
                <h4>[[headermessage]]</h4>
                    [[message]]
                </div>
            </div>
        </div>
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" ng-controller="AuthController">
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title"><b>XS</b>CMS</div>
                    </div>

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                        <form id="loginform" class="form-horizontal" role="form" ng-submit="auth()">

                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="text" class="form-control" ng-model="user.login" value="" placeholder="username">
                                    </div>

                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" ng-model="user.pass" placeholder="password">
                                    </div>

                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->

                                    <div class="col-sm-12 controls">
                                      <input  class="btn btn-success"  type="submit" value="Login">
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
        </div>

    </div>
<?php
}, array(
	'options' => 192,
	'provider' => false,
	'name' => 'system/auth-template.tpl',
	'base_name' => 'system/auth-template.tpl',
	'time' => 1467619520,
	'depends' => array (
  0 => 
  array (
    'system/auth-template.tpl' => 1467619520,
  ),
),
	'macros' => array(),

        ));
