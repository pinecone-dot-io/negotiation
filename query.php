<?php 

namespace negotiation;

/*
*
*	applies filter `negotiation\filter_post`
*	applies filter `negotiation\json_response`
*	@param array
*	@param WP_Query
*	@return array | json response
*/
function posts_results( $posts, \WP_Query $wp_query ){
	if( !$wp_query->is_main_query() )
		return $posts;
		
	$do_json = FALSE;
	
	// content negotiation via accept header
	$accept = explode( ',', $_SERVER['HTTP_ACCEPT'] );
	$accept = array_map( function($header){
		return trim( strtolower($header) );
	}, $accept );
	
	$do_json = in_array( 'application/json', $accept );
	
	// content negotiation via .js extension in url or manually set in wp_query
	if( !empty($wp_query->query_vars['response']) && ($wp_query->query_vars['response'] == 'json') )
		$do_json = TRUE;
	
	//
	if( !$do_json )
		return $posts;
	
	//
	$json = array( 
		'posts' => array_map( function($post) use($wp_query){
			setup_postdata( $post );
			return apply_filters( 'negotiation\filter_post', (object) array(), $post, $wp_query );
		}, $posts ),
		'success' => !!count( $posts )
	);
	
	$json = apply_filters( 'negotiation\json_response', $json, $posts, $wp_query );
	
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	die();
}
add_filter( 'posts_results', __NAMESPACE__.'\posts_results', 10, 2 );

/*
*	just for debuging, will probably be removed
*	@param WP_Query
*	@return WP_Query
*/
function pre_get_posts( $wp_query ){
	//ddbug( $wp_query->query_vars );
	return $wp_query;
}
add_filter( 'pre_get_posts', __NAMESPACE__.'\pre_get_posts', 10, 2 );