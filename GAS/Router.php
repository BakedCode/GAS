<?php

namespace GAS;

class Router {

	protected $_routes = array();
	
	public function __construct() {
	}
	
	private function _format( $string ) {
		
		$string = str_replace( '-', ' ', $string );
		$string = str_replace( '_', ' ', $string );
		$string	= strtolower( $string );
		$string	= ucwords( $string );
		$string	= str_replace( ' ', '', $string );
		
		return $string;
			
	}
	
	public static function getUriParts( $incDots = true ) {
		
		$uri	= $_SERVER[ 'REQUEST_URI' ];
		$uri	= explode( '?', $uri );
		$uri	= $uri[ 0 ];
		
		if( Bootstrap::getOption( 'application.rewrited' ) === false ) {
		
			$split = explode( 'index.php', $uri );

			unset( $split[ 0 ] );
			
			$uri	= implode( 'index.php', $split );
			$uri	= explode( '/', $uri );
			
			unset( $uri[ 0 ] );
			
			$uri	= array_values( $uri );
			
		} else if( defined( 'BASEDIR' ) === true ) {
			
			$split	= explode( BASEDIR, $uri );
			
			unset( $split[ 0 ] );
			
			$uri	= implode( BASEDIR, $split );
			$uri	= explode( '/', $uri );

		} else {
			
			$uri	= explode( '/', $uri );
			
			unset( $uri[ 0 ] );
			
			$uri	= array_values( $uri );
		
		}
		
		if( $incDots === true ) {
			
			foreach( $uri as $key => $value ) {
				
				$new = explode( '.', $value );
				
				if( count( $new ) > 1 ) {
					
					unset( $uri[ $key ] );
					
					foreach( $new as $k => $v ) {
					
						self::array_insert( $uri, $v, ( $key + $k ) );
					
					}
					
				}
			
			}
		
		}
				
		return $uri;
	
	}
	
	public function addRoute( $pattern, $controller, $action = null, $queryString = array() ) {
	
		$this->_routes[] = array( $pattern, $controller, $action, $queryString );
		
	}
	
	public function getRoute() {
		
		$route	= $this->parseRoute();
		$route	= $this->checkRoutes( $route );

		return $route;
	
	}
	
	public function parseRoute() {
		
		$route					= new \stdClass();
		$route->queryString		= array();
		$uri					= self::getUriParts();
		
		if( isset( $uri[ 0 ] ) !== false ) {
			
			// We have a controller ladies and gentleman!
			
			$controller	= $uri[ 0 ];
			$controller	= trim( $controller );
			
			// Reformat URI if neccesary
			
			if( $controller != '' ) {
				
				$route->controller	= $this->_format( $controller );
				
			} else {
			
				$route->controller = Bootstrap::getOption( 'application.defaultController' );
			
			}
			
			if( isset( $uri[ 1 ] ) !== false ) {
			
				$action	= $uri[ 1 ];
				$action	= trim( $action );
				
				if( $action != '' ) {
					
					$route->action	= $this->_format( $action );
					
				} else {
					
					$route->action = Bootstrap::getOption( 'application.defaultAction' );
				
				}				
				
				unset( $uri[ 0 ] );
				unset( $uri[ 1 ] );
				
				$route->queryString = array_values( $uri );
			
			} else {
				
				$route->action	= Bootstrap::getOption( 'application.defaultAction' );
			
			}
		
		} else {
		
			$route->controller	= Bootstrap::getOption( 'application.defaultController' );
			$route->action		= Bootstrap::getOption( 'application.defaultAction' );
			
		}
		
		return $route;
		
	}
	
	public static function array_insert(&$array, $insert, $position = -1) {
     $position = ($position == -1) ? (count($array)) : $position ;
     if($position != (count($array))) {
          $ta = $array;
          for($i = $position; $i < (count($array)); $i++) {
               if(!isset($array[$i])) {
                    die(print_r($array, 1)."\r\nInvalid array: All keys must be numerical and in sequence.");
               }
               $tmp[$i+1] = $array[$i];
               unset($ta[$i]);
          }
          $ta[$position] = $insert;
          $array = $ta + $tmp;
          //print_r($array);
     } else {
          $array[$position] = $insert;
     }

     ksort($array);
     return true;
	}

	public function checkRoutes( \stdClass $route ) {
		
		if( count( $this->_routes ) > 0 ) {
		
			$uri	= self::getUriParts();
				
			foreach( $this->_routes as $item ) {
				
				list( $pattern, $_controller, $_action, $_queryString ) = $item;
				
				if( $_action == '' ) {
				
					$_action = Bootstrap::getOption( 'application.defaultAction' );
			
				}
				
				$parts			= explode( '/', $pattern );
				$valid			= true;
				
				foreach( $parts as $key => $value ) {
					
					$new = explode( '.', $value );
					
					if( count( $new ) > 1 ) {
						
						unset( $parts[ $key ] );
						
						foreach( $new as $k => $v ) {
						
							self::array_insert( $parts, $v, ( $key + $k ) );
						
						}
						
					}
				
				}
				
				foreach( $parts as $key => $value ) {
					
					if( $value === '[controller]' ) {
						
						if( isset( $uri[ $key ] ) !== false && $uri[ $key ] != '' ) {
						
							$_controller = $this->_format( $uri[ $key ] );
						
						}
					
					} else if( $value === '[action]' ) {
						
						if( isset( $uri[ $key ] ) !== false && $uri[ $key ] != '' ) {
						
							$_action = $this->_format( $uri[ $key ] );
						
						}
					
					} else if( substr( $value, 0, 1 ) === ':' && isset( $uri[ $key ] ) !== false ) {
					
						$_queryString[ substr( $value, 1 ) ] = $uri[ $key ];
				
					} else if( isset( $uri[ $key ] ) !== true || $uri[ $key ] != $value ) {
						
						if( @preg_match( $value, $uri[ $key ] ) !== 1 ) {
							
							$valid = false;
							
						}
					
					}
					
				}
				
				$key++;
			
				for( $key; $key < count( $uri ); $key++ ) {
					
					$_queryString[] = $uri[ $key ];
				
				}
				
				if( $valid === true && ( $key >= count( $uri ) || $uri[ $key ] == '' ) ) {

					$route = (object)array(
						
						'controller'	=> $_controller,
						'action'		=> $_action,
						'queryString'	=> $_queryString
					
					);
				
				}
			
			}
			
		}
		
		return $route;
	
	}

}