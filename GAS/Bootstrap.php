<?php

namespace GAS;

$appPath = APP_PATH;

class Bootstrap {
	
	public static $session;
	public static $router;
	public static $layout;
	public static $view;
	public static $front;
	public static $options = array();
	public static $registry;
	
	protected static $_instance;
	protected static $_autoloader;
	protected static $_databases;
	
	public function __construct( $options ) {
		
		$this->loadDependencies();
		self::setOptions( array(
	
			'sframework'	=> array(
				
				'sessions'	=> array(
				
					'prefix'	=> 'gas',
					'salt'		=> 'g44s5$*'
				
				),
				'paths'		=> array(
				
					'layouts'		=> APP_PATH . 'Application/Views/Layouts/',
					'views'			=> APP_PATH . 'Application/Views/Scripts/',
					'models'		=> APP_PATH . 'Application/Models/',
					'controllers'	=> APP_PATH . 'Application/Controllers/',
					'libraries'		=> APP_PATH . 'Library/'
				
				)
			
			),
			'application'	=> array(
				
				'defaultLayout'		=> 'common',
				'defaultController'	=> 'Index',
				'defaultAction'		=> 'Index',
				'errorController'	=> 'Error',
				'ajaxActionSuffix'	=> 'Ajax'
			
			),
			'site'			=> array(),
			'database'		=> array()
			
		));
		self::setOptions( $options );
		$this->initAutoload();
		$this->initRegistry();
		$this->initDatabase();
		$this->initSessions();
		
		if( static::enabled( 'application.mvc' ) ) {
			
			// MVC Enabled
			
			$controller = $this->dispatch();
		
			$this->_handleResponse( $controller );
			
		}
	
	}
	
	public static function init( $options ) {	
		
		if( ( static::$_instance instanceof self ) !== true ) {
			
			static::$_instance = new self( $options );
		
		}
	
		return static::$_instance;
		
	}

	public static function setOptions( Array $options ) {
	
		static::$options = \GAS\Utility::arrayMergeRecursiveDistinct( static::$options, $options );
		
	}
	
	public static function getOption( $option ) {
		
		$parts	= explode( '.', $option );
		$array	= static::$options;

		foreach( $parts as $part ) {
			
			$part	= str_replace( '\.', '.', $part );
			
			if( array_key_exists( $part, $array ) !== false ) {
			
				$array	= $array[ $part ];
			
			} else {
				
				return null;
			
			}
			
		}
		
		return $array;
	
	}
	
	public static function enabled( $option ) {
		
		return ( static::getOption( $option ) === true );
	
	}
	
	public function loadDependencies() {
	
		require 'Exception.php';
		require 'Autoloader.php';
		require 'Autoloader/Exception.php';
		require 'Events.php';
		require 'Events/ReflectorAccess.php';
		require 'Utility.php';
	
	}
	
	public function initAutoload() {
		
		$autoloader	= static::getAutoloader();
		$autoloader->addDirectory( APP_PATH );
		$autoloader->addDirectory( static::getOption( 'sframework.paths.controllers' ) );
		$autoloader->addDirectory( static::getOption( 'sframework.paths.models' ) );
		$autoloader->addDirectory( static::getOption( 'sframework.paths.libraries' ) );
		
		$autoloader->attach();
		
		return $this;
	
	}
	
	public static function getAutoloader() {
		
		if( ( static::$_autoloader instanceof \GAS\Autoloader ) !== true ) {
		
			static::$_autoloader = new \GAS\Autoloader();
		
		}
		
		return static::$_autoloader;
	
	}
	
	public function initRegistry() {
		
		static::$registry = \GAS\Registry::getInstance();
	
	}
	
	public function initDatabase() {
		
		if( array_key_exists( 'database', static::$options ) !== false ) {
			
			$databases = static::$options[ 'database' ];
			
			if( count( $databases ) > 0 ) {
				
				foreach( $databases as $adapter => $connections ) {
					
					foreach( $connections as $options ) {
					
						if( isset( static::$_databases[ $adapter ][ $options[ 'server' ] ] ) !== true ) {
							
							$className = '\GAS\Database\\' . ucwords( strtolower( $adapter ) );
							
							if( class_exists( $className ) !== false ) {
							
								static::$_databases[ $adapter ][ $options[ 'server' ] ] = new $className( $options );
							
							} else {
								
								throw new \GAS\Database\Exception( 'Invalid database adapter specified (\'' . $adapter . '\')' );
							
							}
						
						}
						
					}
					
				}
			
			}
		
		}
	
	}
	
	public function initSessions() {
	
		static::$session = new \GAS\Sessions\Namespaced( static::getOption( 'sframework.sessions.prefix' ) );
		static::$session->setSalt( static::getOption( 'sframework.sessions.salt' ) );
	
	}
	
	public static function getRouter() {
		
		if( ( static::$router instanceof \GAS\Router ) !== true ) {
		
			static::$router = new \GAS\Router();
			
		}
				
		if( $routes = static::getOption( 'application.routes' ) ) {
			
			foreach( $routes as $route ) {
				
				$pattern		= '';
				$controller		= '';
				$queryString	= array();
				$action			= null;
				
				if( isset( $route[ 0 ] ) ) {
				
					$pattern = $route[ 0 ];
				
				}
				
				if( isset( $route[ 1 ] ) ) {
				
					$controller = $route[ 1 ];
				
				}
				
				if( isset( $route[ 2 ] ) ) {
				
					$action = $route[ 2 ];
				
				}
				
				if( isset( $route[ 3 ] ) ) {
				
					$queryString = $route[ 3 ];
				
				}
				
				static::$router->addRoute( $pattern, $controller, $action, $queryString );
			
			}
		
		}
		
		return static::$router;
	
	}
	
	public static function getLayout() {
	
		if( ( static::$layout instanceof \GAS\Layout ) !== true ) {
			
			static::$layout = new \GAS\Layout();
			static::$layout->setLayoutDirectory( static::getOption( 'sframework.paths.layouts' ) );
			static::$layout->setLayoutName( static::getOption( 'application.defaultLayout' ) );
			
		}
		
		return static::$layout;
	
	}
	
	public static function getView() {
	
		if( ( static::$view instanceof \GAS\View ) !== true ) {
			
			static::$view = new \GAS\View();
			static::$view->setViewDirectory( static::getOption( 'sframework.paths.views' ) );
		
		}	
	
		return static::$view;
		
	}
	
	
	public function dispatch( $controller = null, $action = null, $queryString = null, $closure = true ) {
		
		if( $closure && $func = self::getOption( 'methods.dispatch' ) ) {
			
			$func( $this );
		
		}
	
		static::$front = \GAS\Controller\Front::getFront();

		static::$front->setDefaults( static::getOption( 'application.defaultController' ), static::getOption( 'application.defaultAction' ) );
		static::$front->setAjaxSuffix( static::getOption( 'application.ajaxActionSuffix' ) );
		static::$front->attachRouter( static::getRouter() );
		
		$layout	= static::getLayout();
		$view	= static::getView();
		
		$layout->attachView( $view );
		
		static::$front->attachLayout( $layout );
		
		try {
		
			return static::$front->dispatch( $controller, $action, $queryString );
		
		} catch( \GAS\Controller\Front\Exception $e ) {
			
			return static::$front->dispatch( static::getOption( 'application.errorController' ), static::getOption( 'application.defaultAction' ) );
			
		}
		
	}
	
	private function _handleResponse( \GAS\Controller\Abstraction $controller ) {
		
		try {
		
			echo $controller->getOutput();
		
		}  catch( \GAS\Layout\Exception $e ) {

			echo static::$front->dispatch( static::getOption( 'application.errorController' ), static::getOption( 'application.defaultAction' ) )->getOutput();
		
		} catch( \GAS\View\Exception $e ) {
			
			echo static::$front->dispatch( static::getOption( 'application.errorController' ), static::getOption( 'application.defaultAction' ) )->getOutput();
		
		}
		
	}
	
}