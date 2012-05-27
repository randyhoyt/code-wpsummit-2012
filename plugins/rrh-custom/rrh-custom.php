<?php
/*
Plugin Name: Custom Functionality Plugin
Description: Adds custom functionality specific to this site.
Version: 1.0
Author: Randy Hoyt
Author URI: http://randyhoyt.com/
*/

add_action( 'init', 'rrh_create_post_type' );
function rrh_create_post_type() {

      register_post_type( 'rrh_assignment',
            array(
                  'labels' => array(
                        'name' => __('Assignments'),
                        'singular_name' => __('Assignment')
                  ),
                  'public' => true,
				  'supports' => array('title'),
                  'has_archive' => 'assignments'
            )
      );

}

add_filter('widget_text', 'do_shortcode');
add_shortcode('upcoming_assignments','rrh_render_upcoming_assignments');
function rrh_render_upcoming_assignments() {
	
	$args = array(
			'post_type' => 'rrh_assignment',
			'meta_query' => array(
				array(
					'key' => 'date_due',
					'value' => time()-86400-19552000,
					'compare' => '>='
				)
			)
		);
	query_posts($args);
	
	$output = "";	
	if (have_posts()) {
		$output .= '<ul class="upcoming">';
		while ( have_posts() ) { the_post();
			$output .= '<li><strong>';
			$output .= date('M d', get_post_meta(get_the_ID(),"date_due",true));
			$output .= '</strong>: ';
			$output .= '<a href="/assignments/">';
			$output .= get_the_title();
			$output .= '</a>';
			$output .= '</li>';
		}
		$output .= '</ul>';
	}
	wp_reset_query();

	return $output;
	
}

add_action('init','rrh_init_cmb_meta_boxes',10000);
function rrh_init_cmb_meta_boxes() {
	if (!class_exists('cmb_Meta_Box')) { require_once('metaboxes/init.php'); }
}

add_filter('cmb_meta_boxes', 'rrh_assignment_meta_boxes');
function rrh_assignment_meta_boxes($meta_boxes) {
	$meta_boxes[] = array(
		'id' => 'rrh_assignment_data',
		'title' => 'Assignment Information',
		'pages' => array('rrh_assignment'), // post type
		'context' => 'normal',
		'priority' => 'low',
		'show_names' => true, // Show 'text_date_timestamp' field names left of input
		'fields' => array(
			array(
		       'name' => 'Date Due',
		       'id' => 'date_due',
		       'type' => 'text_date_timestamp'
			),
			array(
				'name' => 'Percentage',
				'id' => 'percentage',
				'type' => 'select',
				'options' => array(
					array('value' => '', 'name' => "&mdash; Select &mdash;"),		
					array('value' => '4', 'name' => "4"),  
					array('value' => '8', 'name' => "8"),  
					array('value' => '16', 'name' => "16"),  
					array('value' => '24', 'name' => "24")
				)  
			),	
		),
	);
	return $meta_boxes;

}	































add_filter("manage_edit-rrh_assignment_columns", "rrh_assignment_columns");
add_action("manage_posts_custom_column", "rrh_assignment_values");
add_filter('posts_join', 'rrh_assignment_join' );
add_filter('posts_orderby', 'rrh_assignment_order' );
add_action('admin_head',"rrh_assignment_admin_styles");
function rrh_assignment_columns($columns) //this function display the columns headings
{
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Assignment",
		"percentage" => "Percentage",
		"date_due" => "Date Due"
	);
	return $columns;
}
 
function rrh_assignment_values($column)
{
	global $post;
	if ("title" == $column) echo $post->post_title;
	elseif ("percentage" == $column) echo get_post_meta($post->ID,"percentage",true); 
	elseif ("date_due" == $column) echo get_post_meta($post->ID,"date_due",true);
	elseif ("ID" == $column) echo $post->post_title;	
}
function rrh_assignment_join($wp_join)
{
	//if(is_archive('rrh_assignment') || (is_admin() && $_GET['post_type'] == 'rrh_assignment')) {
		global $wpdb;
		$wp_join .= " LEFT JOIN (
				SELECT post_id, meta_value as date_due
				FROM $wpdb->postmeta
				WHERE meta_key =  'date_due' ) AS DD
				ON $wpdb->posts.ID = DD.post_id ";
	//}
	return ($wp_join);
}
function rrh_assignment_order( $orderby )
{

	//if(is_archive('rrh_assignment') || (is_admin() && $_GET['post_type'] == 'rrh_assignment')) {
			$orderby = " DD.date_due ASC ";
	//}
 	return $orderby;
}
function rrh_assignment_admin_styles() {
	echo '<style>
		      .column-percentage {text-align:center!important;}
		  </style>';	
}