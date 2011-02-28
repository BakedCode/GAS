<?php

namespace Application\Views\Helpers;

class Auth {
	
	public function __construct() {		
	}
	
	public function __get( $key ) {
		
		return \GAS\Bootstrap::$session->$key;
	
	}
	
	public function __isset( $key ) {
		
		return isset( \GAS\Bootstrap::$session->$key );
	
	}
	
}