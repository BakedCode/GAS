<?php

namespace GAS\Sessions;

class Namespaced extends \GAS\Sessions {

	protected $_prefix;
	private $_salt;
	
	public function __construct( $prefix ) {
		
		parent::__construct();
		
		$this->_prefix = $prefix;
	
	}	
	
	private function _formatPrefix( $string = '' ) {
	
		return $this->_prefix . '\\' . $string;
	
	}
	
	public function &__get( $key ) {
	
		return $_SESSION[ $this->_formatPrefix( $key ) ];
	
	}
	
	public function __set( $key, $value ) {
	
		return $_SESSION[ $this->_formatPrefix( $key ) ] = $value;
	
	}
	
	public function __isset( $key ) {
		
		return isset( $_SESSION[ $this->_formatPrefix( $key ) ] );
		
	}
	
	public function setSalt( $salt ) {
		
		$this->_salt = $salt;
	
	}
	
	public function encrypt( $string ) {
	
		return hash( 'SHA256', $this->_salt . $string );
	
	}
	
	public function __unset( $key ) {
	
		unset( $_SESSION[ $this->_formatPrefix( $key ) ] );
			
		return $this;
		
	}
	
	public function clear() {
		
		foreach( $_SESSION as $key => $value ) {
			
			if( preg_match( '/^' . $this->_formatPrefix() . '\(.+)$/', $key ) === 1 ) {
				
				unset( $_SESSION[ $key ] );
			
			}
		
		}
	
	}
	
}