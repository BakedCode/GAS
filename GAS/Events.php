<?php

namespace GAS;

class Events {

	private $_handlers = array();
	protected $_this;
	
	public function __construct( $enableThis = false ) {
		
		if( $enableThis !== false ) {
		
			$this->_this = $this->getThis( $enableThis );
		
		} else {
			
			$this->_this = false;
			
		}
		
	}

	public function __set( $key, $value ) {
	
		if( array_key_exists( $key, $this->_handlers ) !== false ) {
		
			$this->_handlers[ $key ] = array();
			
		}
		
		$this->_handlers[ $key ][] = $value;
	
	}
	
	public function __call( $function, $arguments ) {
		
		if( array_key_exists( $function, $this->_handlers ) !== false ) {
			
			foreach( $this->_handlers[ $function ] as $closure ) {
				
				if( $this->_this !== false ) {
					
					$closure( $this->_this );
				
				} else {
					
					$closure();
					
				}
				
			}
		
		}
	
	}
	
	public function getThis( $self ) {
	
		return new \GAS\Events\ReflectorAccess( $self );
	
	}

}