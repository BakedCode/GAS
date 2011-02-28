<?php

namespace Services;

class Basecamp {
	
	const API_URL				= '://launchpad.37signals.com';
	protected $_useHttps		= true;
	protected $_authToken		= null;
	protected $_consumerKey		= null;
	protected $_consumerSecret	= null;
	protected $_redirectUri		= null;
	protected $_format			= 'json';
	private static $_request;
	
	public function __construct( $consumerKey, $consumerSecret, $redirectUri, $useHttps = true ) {
		
		$this->_consumerKey		= $consumerKey;
		$this->_consumerSecret	= $consumerSecret;
		$this->_redirectUri		= $redirectUri;
		$this->_useHttps		= (bool)$useHttps;
	
	}

	public static function getApiUrl() {
	
		return ( ( $this->_useHttps ) ? 'https' : 'http' ) . self::API_URL;
	
	}
	
	public function getRequestUrl( $object, $method = '', $get = array(), $format = true ) {
	
		$url = ( ( $this->_useHttps ) ? 'https' : 'http' ) . self::API_URL . '/' . $object;
		
		if( $method != '' ) {
		
			$url .= '/' . $method;
		
		}
		
		$i = 0;
		
		foreach( $get as $key => $value ) {
			
			if( $i === 0 ) {
				
				$url .= '?';
							
			} else {
			
				$url .= '&';
			
			}
			
			$url .= urlencode( $key ) . '=' . urlencode( $value );
			
			$i++;
		
		}
		
		if( $format === true ) {
		
			$url .= '.' . $this->_format;
		
		}
		
		if( $this->_authToken !== null ) {
		
			$url .= '?oauth_token=' . $this->_authToken;
		
		}
		
		return $url;
			
	}
	
	public function setAuthToken( $token ) {
	
		$this->_authToken = $token;
	
	}
	
	public function getAuthorizeUrl() {
	
		return $this->getApiUrl() . 'authorization/new?type=web_server&client_id=' . $this->_consumerKey . '&redirect_uri=' . $this->_redirectUri;
	
	}
	
	public function getAuthToken( $code, $setToken = true ) {
		
		$data = array(
			
			'client_id'		=> $this->_consumerKey,
			'client_secret'	=> $this->_consumerSecret,
			'type'			=> 'web_server',
			'code' 			=> $code,
			'redirect_uri'	=> $this->_redirectUri
			
		);
		$data = $this->request( 'authorization', 'token', false )->get( $data )->post( $data )->send();

		if( property_exists( $data, 'error' ) ) {
		
		} else {
			
			if( $setToken === true ) {
			
				$this->setAuthToken( $data->access_token );
				
			}
			
			return $data;
		
		}
	
	}
	
	public function fetch( $url, Array $post = array() ) {
	
		$parts	= explode( '/', $url );
		$object	= $parts[ 0 ];
		$get	= array();
			
		if( isset( $parts[ 1 ] ) !== false ) {
			
			$method = $parts[ 1 ];
		
		}
		
		if( count( $parts ) > 2 ) {
			
			for( $i = 2; $i < ( count( $parts ) - 1 ); $i++ ) {
			
				if( isset( $parts[ $i ] ) ) {
					
					$get[ $parts[ $i ] ] = $parts[ $i + 1 ];
					
					$i++;
				
				}
							
			}
		
		}
		
		return $this->request( $object, $method )->get( $get )->post( $post )->send();
	
	}
	
	public function getRequest() {
	
		if( ( self::$_request instanceof \Services\Basecamp\Request ) !== true ) {
			
			self::$_request = new \Services\Basecamp\Request( $this );
		
		}
		
		return self::$_request;
		
	}

	public function request( $object, $method, $format = true ) {
		
		$this->getRequest()->request( $object, $method, $format );
		
		return $this->getRequest();
	
	}

}