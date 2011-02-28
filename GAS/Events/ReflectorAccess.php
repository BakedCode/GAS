<?php

namespace GAS\Events;

class ReflectorAccess {
	
	private $_self;
	private $_reflection;
	
	public function __construct( $self ) {
	
		$this->_self		= $self;
		$this->_reflection	= new \ReflectionObject( $this->_self );
		
	}
	
	public function __get( $key ) { 
		
		$property = $this->_relfection->getProperty( $name );
		$property->setAccessible( true );
		
		return $property->getValue( $this->_self );
	
	}
	
	public function __set( $key, $value ) {
	
		if( $this->_reflection->hasProperty( $key ) !== false ) {
			
			$property = $this->_reflection->getProperty( $key );
			$property->setAccessible( true );
			
			return $property->setValue( $this->_self, $value );
		
		} else {
			
			return $this->_self->$key = $value;
			
		}
			
	}
	
	public function __isset( $key ) {
		
		return isset( $this->$key );
	
	}
	
	public function __call( $method, $arguments ) {
	
		$method	= $this->_reflection->getMethod( $method );
		$method->setAccessible( true );
		
		return $method->invokeArgs( $this->_self, $arguments );
	
	}

}