<?php

namespace GAS;

abstract class Sessions {
	
	public function __construct() {
	
		if( headers_sent() !== true && ( isset( $_SESSION ) !== true || is_array( $_SESSION ) !== true ) ) {
			
			session_start();
		
		}
		
	}
	
	public function setSession( $array ) {
		
		foreach( $array as $key => $value ) {
		
			$this->$key = $value;
		
		}
		
		return $this;
	
	}
	
	abstract public function &__get( $key );
	abstract public function __set( $key, $value );
	abstract public function __isset( $key );
	abstract public function setSalt( $salt );
	
}