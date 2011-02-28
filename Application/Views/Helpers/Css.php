<?php

namespace Application\Views\Helpers;

class Css {

	protected static $_registry;
	private $_css	= array();
	
	public function __construct() {
	
		if( self::$_registry === null ) {
			
			self::$_registry = \GAS\Registry::getInstance();
		
		}
				
		$this->_buildLinks();
	
	}

	protected function _buildLinks() {
				
		$css			= array();
		$front			= \GAS\Controller\Front::getFront();
		$controller 	= strtolower( $front->layout->route[1]->controller );
		$action			= strtolower( $front->layout->route[1]->action );
		$cssPath		= BASEDIR . PUBLIC_DIR . '_css/';
		$cssDir			= PUBLIC_PATH . '_css/';
		$controllerCss	= $front->view->css;
		$controllerCss	= array_unique( $controllerCss );
		
		if( defined( 'CSS_PREFIX' ) ) {
			
			$CSS_PREFIX = CSS_PREFIX;
		
		} else if( isset( self::$_registry->cssPrefix ) ) {
			
			$CSS_PREFIX = self::$_registry->cssPrefix;
		
		} else {
			
			$CSS_PREFIX = '';
		
		}
		
		if( $CSS_PREFIX != '' ) {
		
			$p = 'app/';
		
		} else {
			
			$p = '';
		
		}
		
		$files		= array_merge( $controllerCss, array( $p . 'global.css' ) );
		$files[]	= $CSS_PREFIX . $controller . '.css';
		$files[]	= $CSS_PREFIX . $controller . '/' . $action . '.css';
		$files[]	= $CSS_PREFIX . $controller . '/' . $controller . '.css';
		$files		= array_unique( $files );
		
		foreach( $files as $key => $value ) {
			
			if( preg_match( '/http(s?):\/\//', $value ) ) {

				$css[] = '<link href="' . $value . '" rev="stylesheet" rel="stylesheet" />';

			} else {
			
				$file = $cssDir . $value;
				
				if( file_exists( $file ) !== false ) {
				
					$css[] = '<link href="' . $cssPath . $value . '?' . filemtime( $file ) . '" rev="stylesheet" rel="stylesheet" />';
				
				}
				
			}

		}
		
		$this->_css = $css;	
	
	}

	public function __toString() {
		
		return implode( "\n		", $this->_css ) . "\n		";
	
	}

}