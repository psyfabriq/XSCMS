<html xmlns="http://www.w3.org/1999/xhtml" <?php Registry::gete('html_parametrs') ?> ng-style="htmlStyle"  >
	<head>
		<?php
		 foreach (Registry::get('nodeMETA') as $value) {
			    echo $value;
		 }
		 foreach (Registry::get('nodeCSS') as $value) {
			     echo $value;
		 }
		?>
	</head>
	<body   <?php Registry::gete('body_parametrs') ?>  ng-style="bodyStyle">

		<?php
		//$Debug->End(true);
		include ($content_view);
		?>
	 <?php
	 foreach (Registry::get('nodeJS') as $value) {
			     echo $value;
	 }
	// echo Registry::get('xsrf');
	?>
	</body>
</html>
 