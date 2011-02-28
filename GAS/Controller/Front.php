<?php

namespace GAS\Controller;

class Front {

	public $layout;
	public $view;
	public $controller;
	protected static $_instance;
	private $_router;
	private $_defaults;
	private $_ajaxSuffix;
	
	public function __construct() {
		
		$this->attachRouter( new \GAS\Router );
	
	}
	
	public function getFront() {
		
		if( ( self::$_instance instanceof self ) !== true ) {
			
			self::$_instance = new self();
		
		}
		
		return self::$_instance;
	
	}
	
	public function attachRouter( \GAS\Router $router ) {
	
		$this->_router = $router;
	
	}
	
	public function getRouter() {
		
		return $this->_router;
	
	}
	
	public function attachLayout( \GAS\Layout $layout ) {
		
		$this->layout = $layout;
	
	}
	
	public function setDefaults( $controller, $action ) {
		
		$defaults				= new \stdClass();
		$defaults->controller	= $controller;
		$defaults->action		= $action;
		
		$this->_defaults		= array(
		
			'raw'	=> clone $defaults,
			'route'	=> $this->_formatRoute( $defaults )
	
		);
		
	}
	
	public function setAjaxSuffix( $suffix ) {
	
		$this->_ajaxSuffix = $suffix;
	
	}
	
	public static function controllerExists( $controllerName ) {
	
		if( class_exists( $controllerName ) !== false ) {
		
			return true;
		
		} else {
		
			return false;
		
		}
	
	}
	
	public function dispatch( $controller = null, $action = null, $queryString = null ) {

		$route	= $this->_router->getRoute();

		if( $controller !== null ) {
			
			$route->controller = $controller;
		
		}
		
		if( $action !== null ) {
			
			$route->action = $action;
		
		}
		
		if( $queryString !== null ) {
			
			$route->queryString = $queryString;
		
		}
		
		$viewRoute	= clone $route;
		$route		= $this->_formatRoute( $route );

		if( self::controllerExists( $route->controller ) !== true ) {
			
			throw new \GAS\Controller\Front\Exception( 'Invalid controller given', 404 );
		
		}
		
		\GAS\Registry::getInstance()->controller = strtolower( $viewRoute->controller );
		
		$controller = new $route->controller();

		if( method_exists( $controller, $route->action ) !== true ) {
			
			$route->action		= $this->_defaults[ 'route' ]->action;
			$viewRoute->action	= $this->_defaults[ 'raw' ]->action;
								
		}
		
		\GAS\Registry::getInstance()->action	 = strtolower( $viewRoute->action );
		
		$controller->route	= $route;
		$controller->attachLayout( $this->layout );

		$this->controller		= $controller;
		$this->layout->route	= array( $route, $viewRoute );
		$this->view				= $this->layout->getView();
		$this->layout->getView()->setViewRoute( $viewRoute );

		if( method_exists( $controller, '__init' ) !== false ) {
			
			$controller->__init();
		
		}
		
		if( method_exists( $controller, 'init' ) !== false ) {
			
			$controller->init();
		
		}
		
		if( \GAS\Http::isAjax() === true && is_string( $this->_ajaxSuffix ) && method_exists( $controller, $route->action . $this->_ajaxSuffix ) !== false ) {
		
			$route->action = $route->action . $this->_ajaxSuffix;

		}
		
		if( method_exists( $controller, $route->action ) !== false ) {
	
			$controller->{$route->action}();
		
		}
			
		if( method_exists( $controller, 'final' ) !== false ) {
			
			$controller->final();
		
		}
		
		return $controller;
		
	}
	
	private function _formatRoute( \stdClass $route ) {
	
		$route->controller	= $route->controller . 'Controller';
		$route->action		= $route->action . 'Action';
	
		return $route;
		
	}

}