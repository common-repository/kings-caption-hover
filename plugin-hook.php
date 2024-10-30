<?php
/*
Plugin Name: Kings Caption Hover
Plugin URI: http://wordpress.org/plugins/kings-caption-hover
Description: This will add gallery or portfolio with awesome caption hover effects.
Author: Saif Bin-Alam
Version: 2.0
Author URI: http://kingsitservice.com/saif
*/


function kings_caption_hover_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'kings_caption_hover_latest_jquery');



function kings_caption_hover_plugin_main_files() {
    wp_enqueue_script( 'kings-caption-hover-js1', plugins_url( '/js/modernizr.custom.js', __FILE__ ), array('jquery'), 1.0, false);
    wp_enqueue_script( 'kings-caption-hover-js2', plugins_url( '/js/toucheffects.js', __FILE__ ), array('jquery'), 1.0, true);
    wp_enqueue_style( 'kings-caption-hover-css1', plugins_url( '/css/component.css', __FILE__ ));
}

add_action('init','kings_caption_hover_plugin_main_files');

add_theme_support( 'post-thumbnails', array( 'post', 'captionhover' ) );
add_image_size( 'caption-image', 400, 300, true );


add_action( 'init', 'caption_hover_custom_post' );
function caption_hover_custom_post() {

	register_post_type( 'captionhover',
		array(
			'labels' => array(
				'name' => __( 'Hover Captions' ),
				'singular_name' => __( 'Hover Caption' ),
				'add_new_item' => __( 'Add New Hover Caption' )
			),
			'public' => true,
                            'menu_position' => 14,
			'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'caption-hover-item'),
		)
	);
		

}





function caption_hover_taxonomy() {
	register_taxonomy(
		'caption_hover_cat',  
		'captionhover',                  
		array(
			'hierarchical'          => true,
			'label'                         => 'Caption Hover Category',  
			'query_var'             => true,
			'show_admin_column'			=> true,
			'rewrite'                       => array(
				'slug'                  => 'cap-ho-cat', 
				'with_front'    => true 
				)
			)
	);
}
add_action( 'init', 'caption_hover_taxonomy');   







function kings_caption_hover_shortcode($atts){
	extract( shortcode_atts( array(
		'show' => '-1',
		'category' => '',
		'id' => ''
	), $atts, 'projects' ) );
	
    $q = new WP_Query(
        array('posts_per_page' => $show, 'post_type' => 'captionhover', 'caption_hover_cat' => $category)
        );		
		
		
	$list = '<ul class="kings_grid cs-style-1">';

	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$author = get_post_meta(get_the_ID(), 'author', true);
		$list .= '
			<li>
				<figure>
					<img src="'.$url.'" alt="img01">
					<figcaption>
						<h3>'.get_the_title().'</h3>
						<span>'.$author.'</span>
						<a href="'.get_the_permalink().'">Take a look</a>
					</figcaption>
				</figure>
			</li>
		
		';        
	endwhile;
	$list.= '</ul>';
	wp_reset_query();
	return $list;
}
add_shortcode('caption-hover', 'kings_caption_hover_shortcode');	












?>