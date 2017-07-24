<?php
/* 
Theme Name: Input
Theme URI: http://www.input.com.br
*/

// adicionar thumbnails, remover versão, remover emojis
add_theme_support( 'post-thumbnails' );
remove_action( 'wp_head', 'wp_generator' );	
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// largura padrão
if ( ! isset( $content_width ) ) {
	$content_width = 1200;
}

function theme_enqueue() {
	wp_enqueue_script( 'bootstrap-script', get_template_directory_uri() . '/library/bootstrap.min.js', array( 'jquery' ), null, true );
	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri() . '/library/bootstrap.min.css' );
	wp_enqueue_style( 'main-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue', 1 );

// slug na classe do body
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset($post) && !is_post_type_archive() )  {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// adiciona excerpt nas páginas
function wpcodex_add_excerpt_support_for_pages() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'wpcodex_add_excerpt_support_for_pages' );

// shortcode para full width colorido
function full_shortcode( $atts, $content = null ) {
	extract(shortcode_atts( array('cor' => 'bg-white'), $atts));
	return '	<div class="'. $cor .'">
		<div class="container">
			<div class="row">' . do_shortcode($content) . '
			</div>
		</div>
	</div>';
}
add_shortcode( 'full', 'full_shortcode' );

// shortcode para col-md-6
function col6_shortcode( $atts, $content = null ) {
	return '	<div class="col-md-6">
				' . do_shortcode($content) . '
			</div>';
}
add_shortcode( 'metade', 'col6_shortcode' );

?>