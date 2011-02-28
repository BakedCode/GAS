<?php

namespace GAS\Cache;

class Memcache extends Abstraction {

	public function connect( Array $params = array() ) {
		
		if( isset( $params[ 'host' ] ) === false ) {
			
			$params[ 'host' ] = '127.0.0.1';
		
		}
		
		if( isset( $params[ 'port' ] ) === false ) {
			
			$params[ 'port' ] = 11211;
		
		}
		
		if( isset( $params[ 'timeout' ] ) === false ) {
			
			$params[ 'timeout' ] = 0;
		
		}
		
		$this->_connection = new \Memcache();
		$this->_connection->connect( $params[ 'host' ], $params[ 'port' ], $params[ 'timeout' ] );
		
	}
	
	public function set() {
		
		$args = func_get_args();
		
		return call_user_func_array( array( $this->_connection, 'set' ), $args );
	
	}
	
	public function get( $key ) {
	
		return $this->_connection->get( $key );
		
	}
	
	public function __set( $key, $value ) {
	
		return $this->set( $key, $value );
		
	}
	
	public function __get( $key ) {
		
		return $this->get( $key );
	
	}
	
	public function __isset( $key ) {
		
		if( $item = $this->get( $key ) ) {
			
			return true;
		
		} else {
			
			return false;
		
		}
	
	}

}