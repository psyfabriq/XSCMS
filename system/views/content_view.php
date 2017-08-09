<?php
   $options=XSCMS::AUTO_RELOAD | XSCMS::FORCE_INCLUDE;
   $xfenom = XSCMS::factory('templates', 'cache', $options);
//   if($logs!=null){$fenom->display("admin/debug-template.tpl", $vars_debug);}
   $xfenom->display("site/site-template.tpl", $vars);
?>
