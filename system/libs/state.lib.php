<?php
defined('_XSCMS_PFQ_STATE') or die('ERROR');

define('_XSCMS_PFQ_DB', 1);
define('_XSCMS_PFQ_ERRTPL', 1);

include ('./system/libs/db.lib.php');
include ('./system/libs/auth.lib.php');


interface State{
	function press(XSContext $context);
}

class StartState implements State
{
	public function press(XSContext $context){
         if($context::init($context)){
            $context->setState($context->getStartedState());
         }
         else{
            $context->setState($context->getStoptState());
         }
         $context->doState();
	}
}

class StartedState implements State
{
    public function press(XSContext $context){
      Session::init();
      require_once 'boot.php';
     }
}

class StoptState implements State
{
	public function press(XSContext $context){
           require_once Registry::get('ResourceError',null,true).'err.tpl';
	}
}

class XSContext extends DBConnection {
	private $state;
	private $startTime;

	private $startState;
	private $startedState;
	private $stopState;

   public function __construct(){
   	$this->setStartTime();
   	$this->startState   =new StartState();
   	$this->startedState =new StartedState();
   	$this->stopState    =new StoptState();
   	$this->state        =$this->startState;
      $this->doState();

   }

   public function setState(State $state){
   		$this->state=$state;
   }
   public function doState(){
   	$this->state->press($this);
   }
   public function getStartIme(){
   	return $this->startTime;
   }
   public function setStartTime(){
   	$this->startTime=microtime(TRUE)*1000;
   }
   public function getStartState(){
   	return $this->startState;
   }
   public function getStartedState(){
   	return $this->startedState;
   }
   public function getStoptState(){
   	return $this->stopState;
   }
}


?>