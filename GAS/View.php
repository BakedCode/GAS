<?php

namespace GAS;

class View {

	protected $_enabled		= true;
	protected $_viewData	= array();
	protected $_validTypes	= array( '.php', '.html', '.html' );
	protected $_viewDirectory;
	protected $_view;
	protected $_route;
	private $_default;

	public function __construct() {
	}

	public function getViewFile() {
			
		foreach( $this->_validTypes as $type ) {
			
			//echo $this->_viewDirectory . $this->_route->controller . '/' . $this->_route->action . $type . "\n";
			
			if( file_exists( $this->_viewDirectory . $this->_route->controller . '/' . $this->_route->action . $type ) !== false ) {
				
				return $this->_viewDirectory . $this->_route->controller . '/' . $this->_route->action . $type;
				
			}
		
		}
	
		throw new \GAS\View\Exception( 'No view file could be found.' );
	
	}
	
	public function setViewRoute( \stdClass $route ) {
		
		$route->controller	= strtolower( $route->controller );
		$this->_route		= $route;
	
		return $this;
		
	}
	
	public function setView( $action, $controller = null ) {
	
		$route			= $this->_route;
		$route->action	= $action;
		
		if( $controller !== null ) {
			
			$route->controller = $controller;
		
		}
		
		$this->setViewRoute( $route );
		
		return $this;
	
	}
	
	public function getViewRoute() {
		
		return $this->_route;
		
	}
	
	public function getViewDirectory() {
		
		return $this->_viewDirectory;
		
	}
	
	public function setViewDirectory( $directory ) {
	
		if( file_exists( $directory ) !== false ) {
			
			$this->_viewDirectory = $directory;
			
			return $this;
			
		} else {
			
			throw new \GAS\View\Exception( 'Invalid directory given.' );
		
		}
	
	}

	public function enable() {
		
		$this->_enabled = true;
		
		return $this;
		
	}
	
	public function disable() {
		
		$this->_enabled = false;
		
		return $this;
	
	}
	
	public function __set( $key, $value ) {
		
		return $this->_viewData[ $key ] = $value;
	
	}
	
	public function & __get( $key ) {
	
		return $this->_viewData[ $key ];
		
	}
	
	public function __isset( $key ) {
		
		return isset( $this->_viewData[ $key ] );
	
	}

	public function __call( $method, $arguments ) {
		
		$class = '\Application\Views\Helpers\\' . ucwords( $method );

		if( class_exists( $class ) !== false ) {
			
			$instance			= new \ReflectionClass( $class );
			$instance			= $instance->newInstanceArgs( $arguments );
			$instance->route	= $this->route;

			return $instance;
		
		}
	
	}
		
	public function getOutput() {
		
		if( $this->_enabled === true ) {
			
			ob_start();
	
			try {
			
				include $this->getViewFile();
			
			} catch( \GAS\View\Exception $e ) {
				
				return '<!-- no view file -->';	
			
			}
		
	        return ob_get_clean();
		
		} else {
			
			return '';
		
		}
			
	}
		
}