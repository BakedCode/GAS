<?php

namespace GAS\Database\Mongodb;

class Connection {
	
	protected $_mongo;
	
	public function __construct() {
	
		$arguments			= func_get_args();
		$mongo				= new \ReflectionClass( 'Mongo' );
		$this->_mongo		= $mongo->newInstanceArgs( $arguments );

	}
	
	public function selectDB( $name ) {
		
		$this->_mongo = $this->_mongo->selectDB( $name );
		
	}
	
	public function getConnection() {
		
		return $this->_mongo;
	
	}
	
	public function __call( $method, $arguments ) {

		return call_user_func_array( array( $this->_mongo, $method ), $arguments );
	
	}
	
	public function __get( $key ) {
	
		return $this->_mongo->$key;
	
	}
	
	public function __set( $key, $value ) {
	
		return ( $this->_mongo->$key = $value );
	
	}

}