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
		
		*/
		
		/**
			The base controller which this class extends handles cleaning of $_POST and $_GET.
			To access the cleaned versions simply access $this->_post & $this->_get.
			
			For example if this request is made:
			
				http://example/Framework/Web/index.php?hi=%3Cscript%3Ealert('XSS!');%3C/script%3E
				
			Then $_GET will contain:
				
				array
				  'hi' => string '<script>alert(\'f\');</script>' (length=30)
				  
			Where as $this->_get will contain:
			
				array
				  'hi' => string '&lt;script&gt;alert(\'f\');&lt;/script&gt;' (length=42)
			
		 */
		
	}

}