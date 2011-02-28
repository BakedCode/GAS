<?php

namespace GAS\Controller;

abstract class Abstraction {

	public $layout;
	public $view;
	
	public function __construct() {
	}
	
	public function attachLayout( \GAS\Layout &$layout ) {
		
		$this->layout	= &$layout;
		$this->view		= &$this->layout->getView();
		
	}
	
	public function getOutput() {
	
		return $this->layout->getOutput();
	
	}
	
}