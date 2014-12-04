<?php 

namespace negotiation;

/*
*	default filter for post data before json return
*	@param object
*	@param WP_Post
*	@param WP_Query
*	@return object
*/
function filter_post( $post, \WP_Post $wp_post, \WP_Query $wp_query ){
	$taxonomies = get_taxonomies( array(
		'public' => true
	) );
	
	$terms = (object) array();
	$object_terms = wp_get_object_terms( $wp_post->ID, $taxonomies );
	foreach( $object_terms as $object_term ){
		$taxonomy = $object_term->taxonomy;
		
		if( !isset($terms->$taxonomy) )
			$terms->$taxonomy = array();
		
		$term = (object) array(
			'id' => $object_term->term_id,
			'name' => $object_term->name,
			'parent' => $object_term->parent,
			'slug' => $object_term->slug
		);
		
		array_push( $terms->$taxonomy, $term );
	}
	
	// needed :(
	$GLOBALS['post'] = $wp_post;
	
	$post = (object) array(
		'id' => (int) $wp_post->ID,
		'post_author' => (int) $wp_post->post_author,
		'post_content' => apply_filters( 'the_content', $wp_post->post_content ),
		'post_parent' => (int) $wp_post->post_parent,
		'post_title' => apply_filters( 'the_title', $wp_post->post_title ),
		'post_type' => $wp_post->post_type,
		
		'post_meta' => get_post_meta( $wp_post->ID ),
		'taxonomies' => $terms
	);
	
	return $post;
}
add_filter( 'negotiation\filter_post', __NAMESPACE__.'\filter_post', 10, 3 );