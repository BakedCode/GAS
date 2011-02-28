<?php

namespace GAS\Database\Mongodb;

class Model {

	protected $_mongo;
	protected $_collection;
	protected $_name;
	
	public function __construct( $connection = null ) {
		
		$name			= get_called_class();
		$_collection	= strtolower( $name );
		$_collection	= explode( 'application\models\\', $_collection );
		$_collection	= $_collection[ 1 ];
		
		if( strstr( $_collection, '\\' ) ) {
			
			$parts			= explode( '\\', $_collection );
			$potentialNs	= $parts[ 0 ];
			$collection		= $parts;
			
			unset( $collection[ 0 ] );
			
			$collection = implode( '\\', $collection );
			
			try {
				
				$this->_mongo = \GAS\Database\Mongodb::getConnection( $connection, $potentialNs );
				
				if( $this->_name === null ) {
				
					$collection		= str_replace( '\\', '_', $collection );
					$this->_name	= $collection;
				
				}

			} catch( Exception $e ) {
				
				$this->_mongo = \GAS\Database\Mongodb::getConnection( $connection );
				
				if( $this->_name === null ) {
				
					$collection		= implode( '_', $parts );
					$this->_name	= $collection;
				
				}
				
			}
			
		} else {
			
			$collection = $_collection;

			if( $this->_name === null ) {
			
				$collection		= str_replace( '\\', '_', $collection );
				$this->_name	= $collection;
			
			}
					
			$this->_mongo = \GAS\Database\Mongodb::getConnection( $connection, $_collection );
		
		}

		if( ( $this->_mongo instanceof Mongo ) === true ) {
			
			throw new \GAS\Mongodb\Model\Exception( 'You must connect to a Mongo DB database before using a model.' );
		
		}
		
		$this->changeCollection( $this->_name );
					
	}
	
	public function changeCollection( $name ) {
	
		$this->_name		= $name;
		$this->_collection	= $this->_mongo->selectCollection( $this->_name );
			
	}
	
	public function changeConnection( $connection ) {

		$this->_mongo = \GAS\Database\Mongodb::getConnection( $connection );
	
	}
	
	public function __call( $method, $arguments ) {
		
		return call_user_func_array( array( $this->_collection, $method ), $arguments );
	
	}
	
	public function __get( $key ) {
	
		return $this->_collection->$key;
	
	}
	
	public function __set( $key, $value ) {
	
		return ( $this->_collection->$key = $value );
	
	}

	
}