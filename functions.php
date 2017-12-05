<?php
/* 
Theme Name: Human Concierge
Theme URI: http://www.input.com.br
*/

// adicionar thumbnails, remover versão, remover emojis
add_theme_support( 'post-thumbnails' );
remove_action( 'wp_head', 'wp_generator' );	
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// largura padrão
if ( ! isset( $content_width ) ) {
	$content_width = 1200;
}

function theme_enqueue() {
	wp_enqueue_script( 'bootstrap-script', get_template_directory_uri() . '/library/bootstrap.min.js', array( 'jquery' ), null, true );
	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri() . '/library/bootstrap.css' );
	wp_enqueue_style( 'bootstrap-grid', get_template_directory_uri() . '/library/bootstrap-grid.css' );
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

// menu principal
function register_menu() {
  register_nav_menu('header-menu',__( 'Menu principal' ));
}
add_action( 'init', 'register_menu' );

// classe ativa do menu
function special_nav_class ($classes, $item) {
    if (in_array('current-menu-item', $classes) ){
        $classes[] = 'active ';
    }
    return $classes;
}
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);

// bloqueia WP enum scans
if (!is_admin()) {
        // default URL format
        if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) { 
			status_header(404);
			header('Location: http://www.input.com.vc');
			die();
		}
        add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
        // permalink URL format
        if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
        else return $redirect;
}
function disable_author_archives() {
	if (is_author()) {
		if (!is_admin()) {
			status_header(404);
			header('Location: http://www.input.com.vc');
			die();
		}
	}
}
remove_filter('template_redirect', 'redirect_canonical');

// Login logo
function login_logo() { ?>
    <style type="text/css">
		#login { 
			padding: 2% 0 0 !important;
		}
        #login h1 a, .login h1 a {
            background-image: url(<?php echo content_url(); ?>/themes/human/img/logo-login.png);
			width: 320px;
			background-repeat: no-repeat;
			background-position: center;
			background-size: 220px;
        	padding-bottom: 5px;
			margin-top: 45px;
			margin-bottom: 5px
        }
		#loginform::before {
			content: "Painel administrativo\A do site da Human Concierge";
			white-space: pre;
			display: block;
			position: relative;
			height: 60px;
			font-size: 1.1em;
			line-height: 1.5em;
			font-weight: bold;
			color: #72777c;			
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'login_logo' );

function login_logo_url() {
    return 'http://www.humanconcierge.com.br/';
}
add_filter( 'login_headerurl', 'login_logo_url' );
function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
}
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

// shortcode para full width colorido
function full_shortcode( $atts, $content = null ) {
	extract(shortcode_atts( array('cor' => 'bg-white'), $atts));
	return '	<div class="'. $cor .'">
		<div class="container">
			<div class="row">'.do_shortcode($content).'
			</div>
		</div>
	</div>';
}
add_shortcode( 'full', 'full_shortcode' );

?>