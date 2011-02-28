<?php

/**
 * Start GAS namespace
 */
namespace GAS;

ini_set( 'display_errors', 1 );
error_reporting( E_ALL ^ E_NOTICE );
date_default_timezone_set( 'Europe/London' );

/**
 * Sets the path to the root folder of the frameowrk
 */
  
define( 'APP_PATH', dirname( __DIR__ ) . '/' ); // The path to our root framework directory
define( 'BASEDIR', '/Framework/Web/' ); // The base directory of your code (usually either /Framework/Web/ or / if you're using mod_rewrite, etc)
define( 'PUBLIC_DIR', '_public/' ); // The folder which stores public facing static content (e.g. css, js, etc)
define( 'PUBLIC_PATH',  dirname( APP_PATH ) . BASEDIR . PUBLIC_DIR ); // The absolute path to the public directory (used for a few example helpers).

/**
 * Require the bootstrap file
 */
require_once APP_PATH . 'GAS/Bootstrap.php'; // Load the bootstrap file

$config = array(
	
	'sframework'	=> array(
		
		'sessions'	=> array(
			
			'prefix'	=> 'gasexample', // App specific session prefix
			'salt'		=> 'th1sI5aS4L1t.' // A salt to pass to the session helper
		
		)
	
	),
	'application'	=> array(
		
		'mvc'				=> true, // If we want to setup MVC (default: true )
		'defaultController'	=> 'Index', // The default controller to load (default: Index)
		'defaultAction'		=> 'Index', // The default action to load (default: Index)
		'defaultLayout'		=> 'common', // The default layout to load (default: common)
		'ajaxActionSuffix'	=> 'Ajax', // If we want requests sent via AJAX to be sent to a different action, we would place a string here (e.g. if a regular request is made to /index/awesome the action called would be 'awesomeAction', where as if we set 'ajaxActionSuffix' to 'Ajax' and the request is sent via AJAX, the action called would be 'awesomeActionAjax' so that we can handle it sperately. 
		'rewrited'			=> false, // If the URL is being rewrited, this will be removed in future versions of GAS if possible.
		'routes'			=> array(
			
			 // Any custom routes
			 
			// array( ':id/[action]', 'Awesome' ),
		
		)
	
	),
	'site'	=> array(
	
		'title'	=> 'GAS Framework Example',
		'name'	=> 'GAS Framework Example'
	
	),
	'database'	=> array(
		
		/**
			
			* All database connections go here, we only have a driver for MongoDB currently
			* but more will be added soon.
		
		'mongodb'	=> array(
			
			array(
				
				'server'	=> 'mongodb://username:password@hostname/db', // Your regular connection address goes here (see first argument here: http://uk.php.net/manual/en/mongo.construct.php )
				
				'options'	=> array(), // Any extra options to be passed to the Mongo driver go here (see the second argument here: http://uk.php.net/manual/en/mongo.construct.php )
				
				'namespace'	=> null // This is used to tell the database driver to use THIS connection that we're defining for models under a specific namespace. For example if we had models under the directory /Application/Models/Awesome/ (which mean's their namespace would be Application\Models\Awesome) and we wanted all models under this namespace/directory to be inserted in to this database that we're definining, we would set the namespace index to 'Awesome'. If we didn't, all collections would be prefixed with 'awesome_' as expected.
							
			)
						
		)
		*/
	
	)

);

/**
 * Bootstrap the framework
 */
\GAS\Bootstrap::init( $config );