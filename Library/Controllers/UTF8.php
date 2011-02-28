<?php

namespace Controllers;

/** 
 * Example base controller implementation
 */
class UTF8 extends \GAS\Controller\Abstraction {

	public function __construct() {
		
		parent::__construct();
	
		header( 'Content-type: text/html; charset=utf-8' );
					
	}
	
	public function __init() {
		
		$this->view->css		= array();
		$this->view->jsAdded	= array(
			
			'before'	=> array(),
			'after'		=> array()
			
		);
	
	}
	
	public function loadCss( $css ) {
		
		$this->view->css[] = $css;
		
	}
	
	public function loadJs( $js, $index = 'before' ) {
		
		$this->view->jsAdded[ $index ][] = $js;
	
	}
	
	public function auth() {
		
		return \GAS\Bootstrap::$session;
	
	}
	
}