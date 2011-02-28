<?php

namespace Library\Controllers;

class JSON extends \GAS\Controller\Abstraction {

	public function __construct() {
		
		parent::__construct();
		
		// We could send the JSON headers here or in the __toString(). Whichever makes more sense to you.
	
	}
	
	public function __toString() {
	
		$view	= $this->layout->getView();
		$view->disable();	
		
		$layout	= $this->layout->__toString();
		$view	= $view->enable()->__toString();
		
		return json_encode( array( 'layout' => $layout, 'view' => $view, 'combined' => $this->layout->__toString() ) );
	
	}

}