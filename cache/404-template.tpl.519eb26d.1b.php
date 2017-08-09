<?php 
/** XSCMS template 'system/404-template.tpl' compiled at 2016-07-04 11:33:13 */
return new XSCMS\Render($fenom, function ($var, $tpl) {
?><div class="row">
<div class="container">
        <div class="col-lg-8 col-lg-offset-2 text-center">
	  <div class="logo">
	    <h1>OPPS, Error 404 !</h1>
          </div>

            <div class="clearfix"></div>
            <br /><br />
          <p class="text-muted">Theere is some error here, Please try later. </p>
          <div class="clearfix"></div>
             <br /><br />
                <div class="col-lg-6 col-lg-offset-3">

                </div>
            <div class="clearfix"></div>
            <br />
                <div class="col-lg-6  col-lg-offset-3">
      		  <div class="btn-group btn-group-justified">
      		      <a href="/" class="btn btn-primary">Return to main page</a>
                <a href="" class="btn btn-success">Return to preview page</a>
      		  </div>

                </div>

        </div>
</div>
</div>
<?php
}, array(
	'options' => 192,
	'provider' => false,
	'name' => 'system/404-template.tpl',
	'base_name' => 'system/404-template.tpl',
	'time' => 1467619520,
	'depends' => array (
  0 => 
  array (
    'system/404-template.tpl' => 1467619520,
  ),
),
	'macros' => array(),

        ));
