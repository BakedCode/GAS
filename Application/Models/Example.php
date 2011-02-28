<?php

namespace Application\Models;

/**
 * Because our classes name is Example, all documents will be
 * inserted in to a collection named 'example'!
 */
class Example extends \GAS\Database\Mongodb\Model {
	
	/**
	 protected $_name = 'not_example'; // We can overwrite the model's default name by assigning it it's own $_name property
	 */
	
	public function get( $id ) {
	
		return $this->findOne( array( '_id' => new \MongoId( $id ) ) );
		
	}
		
}