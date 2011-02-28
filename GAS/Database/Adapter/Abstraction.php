<?php

namespace GAS\Database\Adapter;

abstract class Abstraction extends \GAS\Database\Adapter {
	
	abstract public function __construct();
	
	public function getConnection() {
	
		return static::$_connection;
	
	}

}