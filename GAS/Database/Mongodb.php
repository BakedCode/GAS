<?php

namespace GAS\Database;

class Mongodb extends Adapter\Abstraction {
	
	protected static $_connections	= array();
	protected static $_namespaces	= array();
	
	public function __construct( $config ) {
		
		$server		= $config[ 'server' ];
		$options	= $config[ 'options' ];
		$mongo		= new \Mongo( $server, $options );
		$parts		= explode( '/', $server );
		$db			= array_pop( $parts );
		
		self::$_connections[ $server ]								= $mongo->selectDb( $db );
		self::$_namespaces[ strtolower( $config[ 'namespace' ] ) ]	= $server;

	}

	public function getConnection( $server = null, $namespace = null ) {
		
		if( $server === null || ( $server !== null && array_key_exists( $server, self::$_connections ) !== true ) ) {
			
			if( isset( $this->namespace ) && $this->namespace != '' ) {
								
				$server = self::$_namespaces[ $this->namespace ];
			
			} else if( $namespace === null ) {
			
				$server = array_keys( self::$_connections );
				$server	= $server[ 0 ];
			
			} else {
				
				if( isset( self::$_namespaces[ $namespace ] ) !== false ) {
				
					$server = self::$_namespaces[ $namespace ];
				
				} else {
				
					throw new Mongodb\Exception( 'Invalid namespace used', 1 );
				
				}
				
			}
							
		}
		
		return self::$_connections[ $server ];
		
	}
	
}