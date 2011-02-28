<?php

namespace Application\Views\Helpers;

class Javascript {

	protected static $_registry;
	private $_css	= array();
	
	public function __construct() {
	
		if( self::$_registry === null ) {
			
			self::$_registry = \GAS\Registry::getInstance();
		
		}
				
		$this->_buildLinks();
	
	}

	protected function _buildLinks() {
				
		$js				= array();
		$front			= \GAS\Controller\Front::getFront();
		$controller 	= strtolower( $front->layout->route[1]->controller );
		$action			= strtolower( $front->layout->route[1]->action );
		$jsDir			= BASEDIR . PUBLIC_DIR . '_js/';
		$jsPath			= PUBLIC_ROOT . '_js/';
		$controllerJs	= $front->view->jsAdded[ 'before' ];
		$controllerJs	= array_unique( $controllerJs );
		
		if( isset( self::$_registry->jsPrefix ) ) {
			
			$JS_PREFIX = self::$_registry->jsPrefix;
		
		} else {
			
			$JS_PREFIX = '';
		
		}
		
		$files		= array_merge( array( 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js' ), $controllerJs );
		$files[]	= $JS_PREFIX . $controller . '.js';
		$files[]	= $JS_PREFIX . $controller . '/' . $action . '.js';
		$files[]	= $JS_PREFIX . $controller . '/' . $controller . '.js';
		$files		= array_unique( $files );
		
		foreach( $files as $key => $value ) {
			
			if( preg_match( '/http(s?):\/\//', $value ) ) {
			
				$js[] = '<script type="text/javascript" src="' . $value . '"></script>';

			} else {
				
				$file = $jsDir . $value;
	
				if( file_exists( $file ) !== false ) {
				
					$js[] = '<script type="text/javascript" src="' . $jsPath . $value . '?' . filemtime( $file ) . '"></script>';
				
				}
			
			}
			
		}
		
		foreach( $front->view->jsAdded[ 'after' ] as $key => $value ) {
			
			if( preg_match( '/http(s?)\:\/\//', $value ) ) {
				
				$js[] = '<script type="text/javascript" src="' . $value . '"></script>';

			} else {
	
				$file = $jsDir . $value;
	
				if( file_exists( $file ) !== false ) {
				
					$js[] = '<script type="text/javascript" src="' . $jsPath . $value . '?' . filemtime( $file ) . '"></script>';
				
				}
				
			}
	
		}
		
		$this->_js = $js;	
	
	}

	public function __toString() {
		
		return implode( "\n		", $this->_js ) . "\n		";
	
	}

}