<?php
/*
Plugin Name:	Negotiation
Plugin URI:		
Description:	Content Negotiation for json responses
Version:		0.1
Author:			
Author URI:		
*/

register_activation_hook( __FILE__, create_function("", '$ver = "5.3"; if( version_compare(phpversion(), $ver, "<") ) die( "This plugin requires PHP version $ver or greater be installed." );') );

require __DIR__.'/index.php';