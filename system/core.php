<?php
defined('_XSCMS_PFQ') or die('ERROR');
define('_XSCMS_PFQ_STATE', 1);
include ('system/libs/state.lib.php');
class XSCore
{
    protected static $instance=null;
	protected static $state   =null;

	public static function getInstance(){
		if(!self::$instance instanceof self){
			self::$instance=new XSCore; 

		}
		return self::$instance;
	}

	private function __construct(){
		self::$state=new XSContext();
		Registry::set('StartTime', self::$state->getStartIme());
	}
	protected function __clone(){}
	protected function __wakeup() {}


}
?>