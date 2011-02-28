<?php

namespace GAS;

class Autoloader {

	protected $_directories = array();

	public function addDirectory( $directory ) {
		
		if( file_exists( $directory ) !== false ) {

			$this->_directories[] = $directory;
		
		} else {
			
			throw new \GAS\Autoloader\Exception( 'Invalid directory passed (' . $directory . ').' );
		
		}
			
	}
	
	public function removeDirectory( $directory ) {
	
		foreach( $this->_directories as $key => $value ) {
			
			if( $directory == $value ) {
			
				unset( $this->_directories[ $key ] );
			
			}
		
		}
	
	}
	
	public function attach() {
		
		spl_autoload_register( array( $this, 'autoload' ) );
		
	}
	
	public function autoload( $str ) {
	
		$file	 = str_replace( '\\', '/', $str );
		$file	.= '.php';
		
		foreach( $this->_directories as $directory ) {
			
			$path = $directory . $file;
			
			if( file_exists( $path ) !== false ) {
				
				include_once $path;
			
			}
		
		}
	
	}

}