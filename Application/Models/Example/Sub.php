<?php

namespace Application\Models\Example;

/**
 * Because this model is under the extra namespace 'Example',
 * all documents will be inserted under the collection 'example_sub'
 * --
 * UNLESS our database connection was set to use the namespace 'Example'
 * in which case, documents would simply be inserted in to the connection 'sub'
 */
class Sub extends \GAS\Database\Mongodb\Model {
	
	/**
	 protected $_name = 'not_example'; // Again, we can overwrite the model's default name by assigning it it's own $_name property
	 */
	
	public function get( $id ) {
	
		return $this->findOne( array( '_id' => new \MongoId( $id ) ) );
		
	}
		
}