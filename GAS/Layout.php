<?php

namespace GAS;

class Layout {
	
	protected $_layoutData	= array();
	protected $_enabled		= true;
	protected $_validTypes	= array( '.php', '.html', '.html' );
	protected $_layoutDirectory;
	protected $_layoutName;
	protected $_view;
	
	public function __construct() {
	}
	
	public function attachView( \GAS\View $view ) {
		
		$this->_view = $view;
		
		return $this;
	
	}
	
	public function getView() {
		
		return $this->_view;
	
	}
	
	public function getLayoutFile() {
		
		if( $this->_layoutName !== null ) {
			
			if( $this->_layoutDirectory !== null ) {
				
				foreach( $this->_validTypes as $type ) {
					
					if( file_exists( $this->_layoutDirectory . $this->_layoutName . $type ) !== false ) {
						
						return $this->_layoutDirectory . $this->_layoutName . $type;
						
					}
				
				}
			
				throw new \GAS\Layout\Exception( 'No layout file could be found.' );
			
			} else {
				
				throw new \GAS\Layout\Exception( 'A valid layout directory must be given.' );
			
			}
					
		} else {
			
			throw new \GAS\Layout\Exception( 'A valid layout name must be given.' );
		
		}
			
	}

	public function getLayoutName( $name ) {
		
		return $this->_layoutName;
		
	}
	
	public function setLayoutName( $name ) {
		
		$this->_layoutName = $name;
		
		return $this;
		
	}

	
	public function setLayout( $name ) {
		
		$this->_layoutName = $name;
		
		return $this;
		
	}
	
	public function getLayoutDirectory() {
		
		return $this->_layoutDirectory;
		
	}
	
	public function setLayoutDirectory( $directory ) {
	
		if( file_exists( $directory ) !== false ) {
			
			$this->_layoutDirectory = $directory;
			
			return $this;
			
		} else {
			
			throw new \GAS\Layout\Exception( 'Invalid directory given.' );
		
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
		
		return $this->_layoutData[ $key ] = $value;
	
	}
	
	public function & __get( $key ) {
	
		return $this->_layoutData[ $key ];
		
	}
	
	public function __isset( $key ) {
		
		return isset( $this->_layoutData[ $key ] );
	
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
	
	private function content() {
		
		return $this->_view->getOutput();
	
	}
	
	public function getOutput() {
		
		if( $this->_enabled === true ) {
			
			ob_start();
	
			try {
			
				include $this->getLayoutFile();
			
			} catch( \GAS\Layout\Exception $e ) {
			
				return '<!-- no layout could be found -->';
				
			}
			
	        return ob_get_clean();
		
		} else {
			
			return $this->content();
		
		}
			
	}
	
}