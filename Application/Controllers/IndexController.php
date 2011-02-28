<?php

class IndexController extends \Controllers\UTF8 {
	
	public function indexAction() {
	
		/**
			
			$model = new \Application\Models\Example(); // Oooh, a model! - this is a mongodb model, which is a direct abstraction of the MongoDB PECL extension, which means we can call the same methods.
			
			$data = array(
				
				'name'	=> 'simon',
				'age'	=> 18
			
			);
			
			$model->insert( $data );
			
			$model->findOne( array(
			
				'age'	=> 18
			
			));
			
			$model->update( array( '_id' => $data[ '_id' ] ), array(
				
				'$set'	=> array(
					
					'name'	=> 'simon fletcher'
				
				)
			
			));
		
			$model->remove( array( '_id' => $data[ '_id' ) );
		
		**/
		
		/**
		
			* The base controller which this class extends handles cleaning of $_POST and $_GET.
			* To access the cleaned versions simply access $this->_post & $this->_get.
			
			* For example if this request is made:
			
				http://example/Framework/Web/index.php?hi=%3Cscript%3Ealert('XSS!');%3C/script%3E
				
			* Then $_GET will contain:
				
				array
				  'hi' => string '<script>alert(\'f\');</script>' (length=30)
				  
			* Where as $this->_get will contain:
			
				array
				  'hi' => string '&lt;script&gt;alert(\'f\');&lt;/script&gt;' (length=42)
			
		 **/
		 
		 /**
		  	
		  	* Extra query string parameters can be accessed via $this->route->queryString
		  	* (you could alias this to $this->queryString in your base controller).
		  	
		  	* For example if the following request is made:
		  	
				http://example.com/Framework/Web/index.php/controller/action/query/string?key=value
			
			* Then $this->route->queryString would contain:
			
			    array
			      0 => string 'query' (length=5)
			      1 => string 'string' (length=6)
			      
			* The base controller which this class extends also offers a utility function named $this->findAfter().
			* You can use it like so:
			
				* If the request: <http://19sites.com/Framework/Web/index.php/index/index/update/true> is made, you can use it like so:
				
				if( $this->findAfter( 'update' ) === 'true' ) {
					
					// Do something!
				
				} else {
				
					// If nothing is after /update/ or /update/ isn't in the URL or if the next query string just doesn't
					// equal 'true', then this code gets run.
				
				}
				
				* So basically, it can be used in replace of $_GET sometimes, so that URLs can stay pretty

		  **/
		
	}

}