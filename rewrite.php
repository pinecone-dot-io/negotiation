<?php 

namespace negotiation;

/*
*
*	@param array
*	@return array
*/
function query_vars( $qv ){
	return array_merge( $qv, array('response') );
}
add_filter( 'query_vars', __NAMESPACE__.'\query_vars' );

/*
*
*	@param array
*	@return array
*/
function rewrite_rules_array( $rewrite ){
	//dbug( $rewrite );
	
	$new_rules = array();
	
	// add home page match
	$new_rules['index.js'] = 'index.php?pagename=$matches[1]&page=$matches[2]&response=json';
	
	foreach( $rewrite as $match => $rule ){
		if( strpos($match, 'php') !== FALSE ){ 
			//dbug( $rule, $match );
		} else {
			$js_match = str_replace( '/?$', '.js', $match );
			
			//if( $js_match == '(.?.+?)(/[0-9]+)?.js' )
				//ddbug($rule);
				//$js_match = 'index.js';
				
			$new_rules[$js_match] = $rule.'&response=json';
		}
		
		$new_rules[$match] = $rule.'&response=html';
	}
	
	//ddbug( $new_rules );
	
	return $new_rules;
}
add_filter( 'rewrite_rules_array', __NAMESPACE__.'\rewrite_rules_array' );