<?php

namespace Application\Views\Helpers;

class Config {
	
	private $_config;
	
	public function __construct( $string ) {
		
		$this->_config = \GAS\Bootstrap::getOption( $string );

	}
	
	public function __toString() {
		
		return (string)$this->_config;
	
	}

}