<?php

namespace Application\Views\Helpers;

class UrlConstruct {

	protected $_parameters;
	
	public function __construct( $parameters ) {
	
		$this->_parameters = $parameters;
	
	}

	public function __toString() {
	
		$explode	= \GAS\Router::getUriParts();
		$done		= array();
		
		foreach( $explode as $int => $part ) {
			
			if( array_key_exists( $part, $this->_parameters ) !== false ) {
				
				$explode[ ( $int + 1 ) ]	= $this->_parameters[ $part ];				
				$done[ $part ] 				= true;

			}
		
		}
		
		foreach( $this->_parameters as $part => $value ) {
		
			if( array_key_exists( $part, $done ) !== true ) {
			
				$explode[] = $part;
				$explode[] = $value;
			
			}
		
		}
		
		$url	 = SITE_ROOT;
		$url	.= str_replace( '//', '/', @implode( '/', $explode ) );
		
		
		return $url;
		
	}

}