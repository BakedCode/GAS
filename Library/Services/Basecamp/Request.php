<?php

namespace Services\Basecamp;

class Request extends \Services\Basecamp {

	private $_object;
	private $_method;
	private $_get	= array();
	private $_post	= array();
	private $_parent;
	private $_curl;
	private $_url = false;
	
	public function __construct( \Services\Basecamp $parent ) {
		
		$this->_parent	= $parent;
		$this->_curl	= curl_init();
		
	}
	
	public function sendTokenHeader() {
	
		curl_setopt( $this->_curl, CURLOPT_USERPWD, $this->_parent->_consumerKey . ':' . $this->_parent->_consumerSecret );
		
		return $this;
		
	}

	public function sendAuthHeader() {
			
		curl_setopt( $this->_curl, CURLOPT_USERPWD, $this->_parent->_authToken . ':X' );
		
		return $this;
		
	}

	public function request( $object, $method, $format = true ) {
	
		$this->_curl	= curl_init();
		$this->_url		= false;
		$this->_object	= $object;
		$this->_method	= $method;
		$this->_get		= array();
		$this->_post	= array();
		$this->_format	= $format;
		
		curl_setopt( $this->_curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $this->_curl, CURLOPT_FOLLOWLOCATION, 1 );
		
		return $this;
		
	}
	
	public function custom( $url ) {

		$this->_curl	= curl_init();
		$this->_url		= $url;
		$this->_post	= array();
		
		curl_setopt( $this->_curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $this->_curl, CURLOPT_FOLLOWLOCATION, 1 );
		
		return $this;
	
	}
	
	public function get( Array $data = array() ) {
	
		$this->_get = $data;
		
		return $this;
	
	}
	
	public function post( $data ) {
		
		$this->_post += $data;
	
		return $this;
		
	}
	
	public function send( $json = true ) {

		if( $this->_url !== false ) {
		
			$url = $this->_url;
			
		} else {
		
			$url = $this->_parent->getRequestUrl( $this->_object, $this->_method, $this->_get, $this->_format );
		
		}
		
		if( count( $this->_post ) > 0 ) {
		
			curl_setopt( $this->_curl, CURLOPT_POST, 1 );
			curl_setopt( $this->_curl, CURLOPT_POSTFIELDS, $this->_post );
		
		} else {
			
			curl_setopt( $this->_curl, CURLOPT_POST, 0 );
		
		}
		
		curl_setopt( $this->_curl, CURLOPT_URL, $url );
		
		if( $this->_format === true ) {
			
			//$data = array( 'Content-type: application/json', 'Accept: application/json' );
			
			if( $this->_parent->_authToken !== null ) {
				
				$data[] = 'Authorization: Token token="' . $this->_parent->_authToken . '"';
			
			}
			
			curl_setopt( $this->_curl, CURLOPT_HTTPHEADER, $data );
		
		} else {
	
			curl_setopt( $this->_curl, CURLOPT_HTTPHEADER, array( 'Content-type: application/x-www-form-urlencoded' ) );
		
		}
		
		$data	= curl_exec( $this->_curl );
		$error	= curl_errno( $this->_curl );

		if( $error === 0 ) {
			
			if( trim( $data ) != '' ) {
				
				if( $json === true ) {
					
					$json = $this->_decode( $data );
				
					if( property_exists( $json, 'error' ) ) {
						
						throw new \Services\Basecamp\Exception( $json->error );
						
					} else {
						
						return $json;
					
					}
				
				} else {
					
					return $data;
					
				}

			} else {

				throw new \Services\Basecamp\Exception();

			}
			
		} else {

			throw new \Services\Basecamp\Exception( 'cURL encountered an error (code: ' . $error . ')' );

		}	

		return $this;

	}
	
	protected function _decode( $data ) {
	
		switch( $this->_parent->_format ) {
			
			case 'json':
				
				return json_decode( $data );
			
				break;
		
		}
		
	}

}