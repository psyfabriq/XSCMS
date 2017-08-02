<?php

define('_XSCMS_PFQ', 1);
	include_once('./system/libs/debug.lib.php');

	$Debug = new Debug('log', false, true);
	$GLOBALS["Debug"]=$Debug;
	$Debug->AddMessage(__FILE__.': '.__CLASS__.' -> '.__FUNCTION__);

	include ('./system/registry.php');
	include ('./system/rlog.php');
	include ('./system/config.php');
	include ('./system/core.php');

	$CoreObject= XSCore::getInstance();
?>
