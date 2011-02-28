<?php

namespace Controllers;

/** 
 * Example base controller implementation
 */
class UTF8 extends \GAS\Controller\Abstraction {
	
	protected $_post;
	protected $_get;
	
	public function __construct() {
		
		parent::__construct();
	
		header( 'Content-type: text/html; charset=utf-8' );
		
		$this->_post	= self::clean( $_POST );
		$this->_get		= self::clean( $_GET );
				
	}
	
	public function __init() {
		
		$this->view->css		= array();
		$this->view->jsAdded	= array(
			
			'before'	=> array(),
			'after'		=> array()
			
		);
	
	}
	
	public function loadCss( $css ) {
		
		$this->view->css[] = $css;
		
	}
	
	public function loadJs( $js, $index = 'before' ) {
		
		$this->view->jsAdded[ $index ][] = $js;
	
	}
	
	public function findAfter( $before ) {
	
		$explode = \SFramework\Router::getUriParts( false );

		foreach( $explode as $key => $value ) {
		
			if( $value == $before ) {
			
				if( isset( $explode[ ( $key + 1 ) ] ) !== false ) {
				
					return htmlspecialchars( $explode[ ( $key + 1 ) ], ENT_QUOTES, 'UTF-8' );
				
				} else {
					
					return false;
				
				}
				
			}
		
		}
		
		return false;
	
	}
	
	public function auth() {
		
		return \GAS\Bootstrap::$session;
	
	}

	public static function clean( $contents, $uri = false ) {
		
		if( is_array( $contents ) !== false ) {
		
			foreach( $contents as $key => $content ) {
				
				unset( $contents[ $key ] );
				
				$key				= str_replace( array( chr( 0 ), '$' ), '', $key );
				$contents[ $key ]	= static::clean( $content );
			
			}
			
			return $contents;
		
		} else {
			
			if( $contents != '' ) {
				
				if( $uri !== false ) {
				
					$contents = urldecode( $contents );
				
				}
				
				$contents	= htmlspecialchars( $contents );
										
			}
						
			return $contents;
		
		}
			
	}
	
}