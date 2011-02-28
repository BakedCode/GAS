<?php

namespace GAS\Cache;

abstract class Abstraction {

	protected $_connection;
	
	public function __construct( Array $params = array() ) {
		
		$this->connect( (array)@$params[ 'connection' ] );
	
	}
	
	abstract public function connect( Array $params = array() );
	
	abstract public function set();
	abstract public function get( $key );
	
	abstract public function __set( $key, $value );
	abstract public function __get( $key );
	abstract public function __isset( $key );
	
}