<?php
class Controller_Pages extends Controller
{
	public function __construct(){
        $this->loadModel();
      }
	function action_index(){}

	function action_do()
	{
		parent::action_do();
	}
}