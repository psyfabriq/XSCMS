<?php
   $options=XSCMS::AUTO_RELOAD | XSCMS::FORCE_INCLUDE;
   $xfenom = XSCMS::factory('templates', 'cache', $options);
  // if($logs!=null){$fenom->display("system/debug-template.tpl", $vars_debug);}
   $xfenom->display("system/auth-template.tpl", $vars);
?>