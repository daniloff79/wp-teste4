<?php
/* 
Theme Name: Vinhedo
Theme URI: http://www.input.com.br
Version: 2017.1.07
*/

add_action( 'wp_enqueue_scripts', 'vinhedo_go' );
function vinhedo_go() {
	wp_enqueue_style( 'vinhedo-style', get_stylesheet_uri() );
	if( is_front_page() ) {
		wp_enqueue_style( 'vinhedo-home-style', get_template_directory_uri() .'/library/lightslider.css', false );
	}
}

require_once( 'library/core.php' );

function vinhedo_gogo() {
  // launching operation cleanup
  add_action( 'init', 'vinhedo_head_cleanup' );
  // remove WP version
  add_filter( 'the_generator', 'vinhedo_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'remove_wp_widget_recent_comments_style', 1 );
}

add_action( 'after_setup_theme', 'vinhedo_gogo' );

function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );

add_editor_style( 'library/style-editor.css' );

if ( has_tag('galeria') || is_page(33) || is_page('presidentes') || is_page('mais-de-4-mandatos') || is_page('vereadoras') ) {
	wp_enqueue_script('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js', array('jquery'), '1.11.4,');
	wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
	wp_enqueue_script('jquery-ui-core');
}; 
	
// Add thumbnail, automatic feed links and title tag support
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );

// Add content width (desktop default) and 3-posts (recent-featured) image size
if ( ! isset( $content_width ) ) {
	$content_width = 1300;
}

add_image_size( 'recent-featured-img', 380, 285, true );

@ini_set( 'upload_max_size' , '128M' );
@ini_set( 'post_max_size', '128M');
@ini_set( 'max_execution_time', '300' );

// Remove emojis
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// Add comments template, remove url field and move textarea, change comments list
function comments( $comment_template ) {
	global $post;
     if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
        return;
     }
}
add_filter( "comments_template", "comments" ); 

function remove_comment_fields($fields) {
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','remove_comment_fields');

function wpb_move_comment_field_to_bottom( $fields ) {
$comment_field = $fields['comment'];
unset( $fields['comment'] );
$fields['comment'] = $comment_field;
return $fields;
}
add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );

function bla( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <article class="comment-body">
		<?php if ( $comment->comment_approved == '1' ) { ?>
			<div class="comment-meta">
				<?php if ( $comment->user_id != '0' ) { ?>				
					<span class="comment-author strong">Comunicação Institucional da Câmara Municipal de Vinhedo</span>
				<?php } else { ?>
					<span class="comment-author strong"><?php comment_author(); ?></span>
				<?php } ?>
				<span class="comment-metadata grayc hidden-xs"><?php comment_date('j \d\e F \d\e Y'); ?>, <?php comment_time(); ?></span>
			</div>
            <div class="comment-content gray9">
				<?php comment_text(); ?>
			</div>
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Responder', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>
		<?php } else { ?>
			<div class="grayc">Comentário aguardando aprovação</div>
		<?php } ?>
        </article>
    </li>
    <?php
}

// Register sidebar (and give editor access)
function theme_register_sidebar() {
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'id' => 'footer-map',
			'name' => 'rodap&eacute;',
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '<div class="clearfix"></div></div>',
		    'before_title' => '<h5 class="text-center">',
		    'after_title' => '</h5>',
		 ));
	}
}
add_action('widgets_init', 'theme_register_sidebar');

$role = get_role('editor');
$role->add_cap('edit_theme_options');

// Login logo
function login_logo() { ?>
    <style type="text/css">
		#login { 
			padding: 2% 0 0 !important;
		}
        #login h1 a, .login h1 a {
            background-image: url(<?php echo content_url(); ?>/uploads/2017/03/brasao.png);
			height: 65px;
			width: 320px;
			background-size: 80px 90px;
			background-repeat: no-repeat;
			background-position: center;
        	padding-bottom: 30px;
        }
		#loginform::before {
			content: "Painel administrativo\A do site da Câmara de Vinhedo";
			white-space: pre;
			display: block;
			position: relative;
			height: 60px;
			font-size: 1.1em;
			line-height: 1.5em;
			font-weight: bold;
			color: #72777c;			
		}
		#loginform::after {
			content: "Criado pela Input Tecnologia";
			display: block;
			position: relative;
			clear: both;
			color: #72777c;		
			margin-top: 60px;
			height: 40px;
			margin-bottom: -30px;		
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'login_logo' );

function login_logo_url() {
    return 'http://camaravinhedo.sp.gov.br/';
}
add_filter( 'login_headerurl', 'login_logo_url' );

function login_logo_url_title() {
    return 'Site da Câmara de Vinhedo, criado pela Input';
}
add_filter( 'login_headertitle', 'login_logo_url_title' );

add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
}

// custom maintenance page
function wp_maintenance_mode(){
    if(!current_user_can('edit_themes') || !is_user_logged_in()){
	    if( file_exists( ABSPATH . '.maintenance' ) ) {
            wp_die('
			<img src="http://camaravinhedo.sp.gov.br//wp-content/themes/vinhedo/images/logo-camara.png" srcset="http://camaravinhedo.sp.gov.br//wp-content/themes/vinhedo/images/logo-camara-hires.png 2x, http://camaravinhedo.sp.gov.br//wp-content/themes/vinhedo/images/logo-camara-hires.png 3x" alt="Câmara Municipal de Vinhedo" style="max-width: 98%">
			<br />
			<div style="display:block;font-family: \'Myriad Pro\',Myriad,Calibri,sans-serif; font-size: 14px;line-height: 1.42em;margin-top:20px">
			<h1 style="color:#a2112a;font-weight: 700;border:none">Site em manutenção ou atualização</h1>
			<br />
			<p>Volte mais tarde.</p>
			<p style="font-size:0.85em;color:#666;margin-top:40px">© <span style="font-weight: 700;">2017 - Câmara Municipal de Vinhedo</span>
			<br />Av. Dois de Abril, 78 - Centro - Vinhedo/SP - CEP 13280-000
			<br />(19) 3826-7700
			<br />imprensa@camaravinhedo.sp.gov.br
			</p>
			</div>
			');
        }
    }
}
add_action('get_header', 'wp_maintenance_mode');


// counter
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
	$count = $count + get_post_meta('33', $countKey, true);
	$count = $count + get_post_meta('2', $countKey, true);
	$count = $count + get_post_meta('12', $countKey, true);
	$count = $count + get_post_meta('19', $countKey, true);
	$count = $count + get_post_meta('527', $countKey, true);	
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
        $count = 1;
    }$count = $count + get_post_meta('14', $countKey, true);
    return $count;
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
    }
    else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
	$count = $count + get_post_meta('33', $countKey, true);
	$count = $count + get_post_meta('2', $countKey, true);
	$count = $count + get_post_meta('12', $countKey, true);
	$count = $count + get_post_meta('19', $countKey, true);
	$count = $count + get_post_meta('527', $countKey, true);
	$count = $count + get_post_meta('14', $countKey, true);
}


// Bootstrap Top Menu setup

add_action( 'after_setup_theme', 'bootstrap_setup' );

if ( ! function_exists( 'bootstrap_setup' ) ) {
	include_once( 'library/menu.php' );
}

// theme options
global $submenu;
unset($submenu['themes.php'][6]); 
function as_remove_menus() {
       remove_menu_page('customize.php');
       global $submenu;
       unset($submenu['themes.php'][6]);
}
add_action('admin_menu', 'as_remove_menus');

add_action('admin_menu', 'header_links');
function header_links() {
    $page_title = 'Links sociais';
    $menu_title = 'Links sociais';
    $capability = 'edit_posts';
    $menu_slug = 'header_links';
    $function = 'my_header_links';

    add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function );
}
function my_header_links() {
	include_once( 'library/header-links.php' );
}

// TinyMCE buttons
function cw_mce_buttons_1( $buttons ) {
    $buttons = array( 'fontsizeselect','forecolor','bold','italic','bullist','numlist','alignleft','alignright','aligncenter','alignjustify','link','unlink','wp_adv' );
    return $buttons;
}
add_filter( 'mce_buttons', 'cw_mce_buttons_1' );
function cw_mce_buttons_2( $buttons ) {
    $buttons = array('styleselect','hr','pastetext','removeformat','charmap','indent','outdent','undo','redo','wp_help');
    return $buttons;
}
add_filter( 'mce_buttons_2', 'cw_mce_buttons_2' );

// social share
function social($content) {
	$shareURL = urlencode(get_permalink());

	$shareTitle = str_replace( ' ', '%20', get_the_title());
		 
	$twitterURL = 'https://twitter.com/intent/tweet?text='.$shareTitle.'&amp;url='.$shareURL;
	$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$shareURL;
		 
	$variable = '<div class="share-social">';
	$variable .= '<a class="share-link share-facebook" href="'.$facebookURL.'" target="_blank">Compartilhar</a>';
	$variable .= '<a class="share-link share-twitter" href="'. $twitterURL .'" target="_blank">Tweetar</a>';
	$variable .= '</div>';
	return $variable;
};
add_shortcode( 'social', 'social' );


// custom type events
add_action( 'init', 'create_event_postype' );

function create_event_postype() {
$labels = array(
    'name' => _x('Eventos', 'evento'),
    'singular_name' => _x('Evento', 'evento'),
    'add_new' => _x('Adicionar novo', 'evento'),
    'add_new_item' => __('Adicionar novo evento'),
    'edit_item' => __('Editar evento'),
    'new_item' => __('Novo evento'),
    'view_item' => __('Ver evento'),
    'search_items' => __('Buscar eventos'),
    'not_found' =>  __('Nenhum evento encontrado'),
    'not_found_in_trash' => __('Nenhum evento no lixo'),
    'parent_item_colon' => ''
);

$args = array(
    'label' => __('Eventos'),
    'labels' => $labels,
    'public' => true,
    'capability_type' => 'post',
    'menu_icon' => 'dashicons-calendar-alt',
	'has_archive' => true,
    'hierarchical' => false,
    'rewrite' => array( "slug" => "events" ),
    'supports'=> array('title', 'thumbnail', 'editor', 'revisions') ,
    'show_in_nav_menus' => true
);
register_post_type( 'tf_events', $args);
}

add_filter ("manage_edit-tf_events_columns", "tf_events_edit_columns");
add_action ("manage_posts_custom_column", "tf_events_custom_columns");

function tf_events_edit_columns($columns) {
$columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Evento",
	"tf_col_ev_date" => "Datas",
    "tf_col_ev_desc" => "Descritivo",
    );
return $columns;
}

function tf_events_custom_columns($column) {
global $post;
$custom = get_post_custom();
switch ($column) {
	case "tf_col_ev_date": 
		$startd = $custom["wpcf-data-de-inicio"][0];
		$startdate = date("H:i d/m/Y", $startd); 
		echo $startdate ;
	break;
	case "tf_col_ev_desc";
		the_excerpt();
	break;
	}
}

add_action('admin_head', 'my_column_width'); // CSS in WP panel
function my_column_width() {
    echo '<style type="text/css">';
    echo '.column-tf_col_ev_date { width:20% !important; } @media screen and (max-width: 783px) and (min-width: 481px) { .column-tf_col_ev_date { width:15% !important; } } @media screen and (max-width: 782px){ .column-tf_col_ev_date { display: none !important; } }.media-types-required-info { display: none}';
	echo '.max-upload-size:after { 	content: "\A Imagens no carrossel principal da Home devem ter 1200 por 360 pixels.\A As imagens de posts devem ter 780 x 583 pixels.\A E as imagens do \"menu carrossel\" devem ter 50 por 50 pixels."; white-space: pre; } ';
    echo '#tf_events_meta input { line-height: 1.45; color: #555; background-color: #fff; background-image: none;
	border: 1px solid #ccc; }
	.ui-widget.ui-widget-content { border: 1px solid #c5c5c5; background: #fff; color: #333;  }
	.wpsm-collaps-C-review-notice, #mceu_94, #mceu_95 { display: none !important; }
	.dpro-admin-notice { width: 95% !important }';
    echo '</style>';
}

function is_post_type( $tipo ){
 global $wp_query;
 if($tipo == get_post_type($wp_query->post->ID)) return true;
 return false;
}

// (ending custom type events)

/* no more extra p´s, br only in li
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );
preg_match_all('#\[li\](.*?)\[/li]#is', $text, $stack);
foreach($stack[1] as $t) {
  $text = str_replace($t, nl2br($t), $text); 
}
//add_filter( 'the_excerpt', 'nl2br' );*/

// remove home from search result
function jp_search_filter( $query ) {
	if ( ! $query->is_admin && $query->is_search && $query->is_main_query() ) {
		$query->set( 'post__not_in', array( 33 ) );
	}
}
add_action( 'pre_get_posts', 'jp_search_filter' );

// custom 'read more'
function modify_read_more_link() {
    return '<a class="red" href="' . get_permalink() . '">leia mais</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );
function new_excerpt_more($more) {
    global $post;
	return ' <a class="more red" href="' . get_permalink($post->ID) . '">leia mais</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

// slug at body class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset($post) && !is_post_type_archive() )  {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// bye plugins everywhere
add_action( 'wp_print_scripts', 'economic', 200 );
function economic() {
	if ( !is_post_type_archive() ) {
		wp_deregister_script( 'wp-fullcalendar' );
	}
	if ( is_front_page() ) {
		wp_deregister_script( 'wpbgallery' );
		wp_deregister_style( 'wpbgallery-gallery-css' );
	}
	if ( is_post_type_archive() || is_archive() || ( $GLOBALS['pagenow'] === 'wp-login.php' ) ) {
		wp_deregister_script( 'wpbgallery' );
		wp_deregister_script( 'wpbgallery-gallery-js' );
		wp_deregister_script( 'formidable' ); 
		wp_deregister_style( 'formidable-css' );
		wp_deregister_style( 'frm_fonts-css' );
		wp_deregister_style( 'wpbgallery-blueimp-css' );
		wp_deregister_style( 'wpbgallery-gallery-css' );
	}
	if ( is_page(array( 2, 1309, 12, 14, 1608, 19, 22 )) ) {  // a camara, municipio, r humanos, ativ legislativas, transp, acervo, localizac, contato
		wp_deregister_script( 'wpbgallery' );
		wp_deregister_script( 'wpbgallery-gallery-js' );
		wp_deregister_style( 'wpbgallery-blueimp-css' );
		wp_deregister_style( 'wpbgallery-blueimp-css' );
		wp_deregister_style( 'wpbgallery-gallery-css' );
	}
}

// shortcodes
function carousel_one($atts){
	$q = new WP_Query(
	array( 'category_name' => 'destaque', 'orderby' => 'date', 'posts_per_page' => '3' )
	 );
	$list = '<div class="carousel-inner" role="listbox">';

	if($q->have_posts()) {
		$i=0;
		while($q->have_posts()) {		
		$q->the_post();
		
		if ( !wp_is_mobile() ) {
			$big = get_post_meta( get_the_ID(), 'wpcf-carousel-image', true);
		} else {
			$big = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'medium' );
		}		
		if (empty($big)) { $big = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); }
		if($i==0){
			$list .= '<div class="item active"> 
			<img src="'. $big .'" alt="'. get_the_title() .'">
			<div class="carousel-caption">
			<a href="' . get_permalink() . '">'. get_the_title() .'</a>
			</div> 
			</div>'; 
		} else {
			$list .= '<div class="item">
			<img src="'. $big .'" alt="'. get_the_title() .'">
			<div class="carousel-caption">
			<a href="' . get_permalink() . '">'. get_the_title() .'</a>
			</div>
			</div>';
		}
		$i++;
		}
	}	
	$list .= '<a class="left carousel-control" href="#carousel-1" role="button" data-slide="prev">
					<span class="icon-prev" aria-hidden="true"></span>
					<span class="sr-only">anterior</span>
				</a>
				<a class="right carousel-control" href="#carousel-1" role="button" data-slide="next">
					<span class="icon-next" aria-hidden="true"></span>
					<span class="sr-only">próxima</span>
				</a>
			</div>';
	
	rewind_posts();
	$i=0;
	$list .= '<ol class="carousel-indicators">';
	while($q->have_posts()) {
		$q->the_post();
		if($i==0){ $list .= '<li data-target="#carousel-1" data-slide-to="0" class="active"></li>
			';
		} else { $list .= '<li data-target="#carousel-1" data-slide-to="'. $i .'"></li>
			';
		}		
	$i++;
	}
	wp_reset_query();
	$list .= '</ol>';
	return $list;
}
add_shortcode('carousel-1', 'carousel_one');

function recent_featured($atts){ //3-posts
	$q = new WP_Query(
	array( 'category_name' => 'destaque', 'orderby' => 'date', 'posts_per_page' => '3', 'offset' => '3' )
	 );
	$list = ' <div class="clearfix"></div>
			<div id="featured-container">';
	$vazio = 0;
	while($q->have_posts()) : $q->the_post();
	$novotamanho = wp_get_attachment_url(  get_post_thumbnail_id(get_the_ID(),'recent-featured-img'));
	$tamanhomed = wp_get_attachment_url(  get_post_thumbnail_id(get_the_ID(),'medium'));

	$semextensao = substr($tamanhomed, 0, strrpos( $tamanhomed, '.') );
	$extensao = substr($tamanhomed, strripos($tamanhomed, '.') + 1);
	$completo = $semextensao .'-380x285.'. $extensao;
	
	$vazio = 1;
	if(is_file($completo)) {
		$list .= '<div class="col-sd-6 col-md-4 featured"><a href="'. get_permalink() .'" class="featured-link-img"><img src="'. $completo .'" class="size380"></a><a href="' . get_permalink() . '" class="featured-link-txt"><h3>' . get_the_title() . '</h3></a></div>';
	} elseif(file_exists($novotamanho)) { 
			$list .= '<div class="col-sd-6 col-md-4 featured"><a href="'. get_permalink() .'" class="featured-link-img"><img src="'. $novotamanho .'" class="recent-featured-img"></a><a href="' . get_permalink() . '" class="featured-link-txt"><h3>' . get_the_title() . '</h3></a></div>';
	} else { 
			$list .= '<div class="col-sd-6 col-md-4 featured"><a href="'. get_permalink() .'" class="featured-link-img"><img src="'. $tamanhomed .'" class="medium"></a><a href="' . get_permalink() . '" class="featured-link-txt"><h3>' . get_the_title() . '</h3></a></div>';
	} 
	
	endwhile;
	if( $vazio == 0 ) {
		$list .= '<div class="col-sd-6 col-md-4 featured gray9">Não há mais notícias</div>';
	}
	$list .= '</div>';
	wp_reset_query();
	return $list;
}
add_shortcode('3-posts', 'recent_featured');

function carousel_two($atts){
    extract(shortcode_atts(array(
      'n' => '6',
    ), $atts));
	$q = new WP_Query(
	array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'tag' => 'menu', 'posts_per_page' => $n, 'order' => 'ASC')
	 );
	$list = '<ul id="carousel-2-inner" class="carousel-inner content-slider" role="listbox">';
	$vazio = 0;
	while($q->have_posts()) {
		$q->the_post();
		$vazio = 1;
		$list .= '<li class="item">
					<img src="'. wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ) .'">
					<a href="' . get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true ) . '" class="red" id="carousel-2-item-'. get_the_ID() .'"><h4>' . get_the_excerpt() . '</h4></a>
				  </li>
				  ';	
	}
	$list .= '</ul>';
	if( $vazio == 0 ) {
		$list .= '<li class="item gray9">Não há itens no menu</li>';
	}
	return $list;
}
add_shortcode('carousel-2', 'carousel_two');

function recent_posts_shortcode($atts){
    extract(shortcode_atts(array(
      'n' => '5',
    ), $atts));
	$x = new WP_Query( 
	array ( 'category_name' => 'destaque', 'orderby' => 'date', 'posts_per_page' => '6' )
	);
	$z = 0;
	$vazio = 0;
	while ($x->have_posts()): $x->the_post();
		$exclude_post_id[$z] = get_the_ID();
		$z = $z + 1;
	endwhile;
	wp_reset_query();

	$y = new WP_Query(
	array( 'orderby' => 'date', 'posts_per_page' => $n, 'cat' => '-62,-63,-64', 'post__not_in' => $exclude_post_id )
	 );
	$list = '<img class="icon" src="'. get_home_url() .'/wp-content/themes/vinhedo/images/ico-last-news.png" /><h1>Últimas Notícias</h1> 
		<a href="'. get_home_url() .'/noticias/" class="btn-group"><button class="btn btn-default">ver todas as notícias</button></a>';
	while($y->have_posts()) : $y->the_post();
	$vazio = 1;
	$list .= '<article><section class="entry-content">';	
	if( is_home() || is_front_page() ) {
		$novotamanho = wp_get_attachment_url(  get_post_thumbnail_id(get_the_ID(),'recent-featured-img'));
		$semextensao = substr($novotamanho, 0, strrpos( $novotamanho, '.') );
		$extensao = substr($novotamanho, strripos($novotamanho, '.') + 1);
		$completo = $semextensao .'-380x285.'. $extensao;
		
		/* $file_content = utf8_encode(file_get_contents($completo));
		$hash = hash('sha512', $file_content, false);
		$bits = pack("H*", $hash);
		$file_hash = base64_encode($bits);
		$filesize = strlen($file_content); */
		
		$filesize = strlen($completo);
		
		if(is_file($completo)) {
			$list .= '<a href="' . get_permalink() . '" class="featured-link-img"><img src="'. $completo . '" class="post-thumnail size380"></a>';
		} elseif($filesize >= 10) { 
			$list .= '<a href="' . get_permalink() . '" class="featured-link-img"><img src="'. $completo .'" class="post-thumnail phpwin"></a>';
		} else { 
			$list .= '<a href="' . get_permalink() . '" class="featured-link-img"><img src="'. $novotamanho .'" class="post-thumnail novotamanho"></a>';
		} 
	}
	$list .= '<p>' . get_the_title() . '<a href="' . get_permalink() . '" class="red">leia mais</a></p>
			</section></article>';
	endwhile;
	if( $vazio == 0 ) {
			$list = '<img class="icon" src="'. get_home_url() .'/wp-content/themes/vinhedo/images/ico-last-news.png" /><h1>Últimas Notícias</h1>
			<article><p class="gray9" style="margin: 20px 0">Não há mais notícias</p></article>';
	} 
	wp_reset_query();
	return $list;
}
add_shortcode('recent-posts', 'recent_posts_shortcode');

function recent_events_shortcode($atts){
    extract(shortcode_atts(array(
      'n' => '3',
    ), $atts));
	$vazio = 0;
	$today = current_time( 'timestamp' );
	if( is_front_page() ) {
	$q = new WP_Query(
		array( 'post_type' => 'tf_events', 'meta_key' => 'wpcf-data-de-inicio', 'orderby' =>' meta_value_num', 'posts_per_page' => $n, 'order' => 'ASC', 'tag__not_in' => '48', 'meta_query'  => array(
                array (
				'meta_key' => 'wpcf-data-de-inicio',
                'value' => $today,
                'compare' => '>=',
				'type' => 'NUMERIC'
				)
            ),)
		 );
	} else {
	$q = new WP_Query(
		array( 'post_type' => 'tf_events', 'meta_key' => 'wpcf-data-de-inicio', 'orderby' =>' meta_value_num', 'posts_per_page' => $n, 'order' => 'ASC')
		 ); 
	}
	$list = '<img class="icon" src="'. get_home_url() .'/wp-content/themes/vinhedo/images/ico-agenda.png" /><h4>Agenda do Plenário</h4>';
	
	while($q->have_posts()) : $q->the_post();

	$vazio = 1;
	$list .= '<article><section class="entry-content">
	<section class="entry-meta">' . ucfirst(date_i18n("l, d/m/Y, H:i", get_post_meta(get_the_ID(), 'wpcf-data-de-inicio', true ))) . '</section>
	<section class="entry-content">' . get_the_title() . '</section>
	</section></article>';
	endwhile;
	$list .= '<a href="'. get_home_url() .'/events/" class="btn-group"><button class="btn btn-default">ver agenda completa</button></a>';
	if( $vazio == 0 ) {
			$list .= '<article><p class="gray9" style="margin: 20px 0">Não há eventos</p></article>';
	} 
	wp_reset_query();
	return $list;
}
add_shortcode('recent-events', 'recent_events_shortcode');

function recent_pautas($atts){
    extract(shortcode_atts(array(
      'n' => '3',
    ), $atts));
	$q = new WP_Query(
	array( 'category_name' => 'pautas', 'orderby' => 'date', 'posts_per_page' => $n)
	 );
	if(is_page(12)) {
		$list = '<img class="icon" src="'. get_home_url() .'/wp-content/uploads/2017/03/ico-tramitacao.png" /><h4>Pautas das Sessões</h4>
				<a href="'. get_home_url() .'/atividades-legislativas/pautas/" class="btn-group"><button class="btn btn-default">ver todas as pautas</button></a>';
	} else { $list=''; }
	while($q->have_posts()) : $q->the_post();
	$anexo = get_post_meta( get_the_ID(), 'wpcf-pdf-anexo',true); 
	$filetype = wp_check_filetype($anexo);
	if($filetype['ext'] == pdf) {
		$arqanexo = substr($anexo, strrpos($anexo, '/') + 1);
	} 
	$list .= '<article class="panel panel-default">
	<div class="panel-heading">
		<a href="' . get_permalink() . '"><h3>'. get_the_title() .'</h3></a>
		<p class="meta-data gray6">Data de publicação: <span>'.get_the_date('d/m/Y') .'</span></p>
	</div>
	<div class="panel-body">';
	if($filetype['ext'] == pdf) {
		$list .= '<a href="'. $anexo .'" class="pdf" target="_blank">'. $arqanexo .'</a>';
	} else {
		$list .= '<a href="'. $anexo .'" class="link">'. $anexo .'</a>';
	}
	$list .= '</div>
		</article>';
	endwhile;
	wp_reset_query();
	return $list;
}
add_shortcode('pautas', 'recent_pautas');

function recent_projetos($atts){
    extract(shortcode_atts(array(
      'n' => '3',
    ), $atts));
	$q = new WP_Query(
	array( 'category_name' => 'projetos', 'orderby' => 'date', 'posts_per_page' => $n)
	 );
	if(is_page(12)) {
		$list = '<img class="icon" src="'. get_home_url() .'/wp-content/uploads/2017/03/ico-tramitacao.png" /><h4>Projetos em Tramitação</h4>
				<a href="'. get_home_url() .'/atividades-legislativas/projetos/" class="btn-group"><button class="btn btn-default">ver todos os projetos</button></a>';
	} else { $list=''; }
	while($q->have_posts()) : $q->the_post();
	$anexo = get_post_meta( get_the_ID(),'wpcf-pdf-anexo', true );
	$filetype = wp_check_filetype($anexo);
	if($filetype['ext'] == pdf) {
		$arqanexo = substr($anexo, strrpos($anexo, '/') + 1);
	} 
	$arqanexo = substr($anexo, strrpos($anexo, '/') + 1);
	$list .= '<article class="panel panel-default">
	<div class="panel-heading">
		<a href="' . get_permalink() . '"><h3>'. get_the_title() .'</h3></a>
		<p class="meta-data gray6">Data de publicação: <span>'.get_the_date('d/m/Y') .'</span></p>
	</div>
	<div class="panel-body">';
	if($filetype['ext'] == pdf) {
		$list .= '<a href="'. $anexo .'" class="pdf">'. $arqanexo .'</a>';
	} else {
		$list .= '<a href="'. $anexo .'" class="link">'. $anexo .'</a>';		
	}
	$list .= '</div>
		</article>';
	endwhile;
	wp_reset_query();
	return $list;
}
add_shortcode('projetos', 'recent_projetos');

function anexo($atts){
    extract(shortcode_atts(array(
      'titulo' => 'Título',
	  'data' => '',
	  'nome' => 'o anexo',
    ), $atts));
	
	$arqanexo = substr($nome, strrpos($nome, '/') + 1);
	$extensao = substr($nome, strripos($nome, '.') + 1);
	
	$arquivo = '<div class="panel panel-default">
			  <div class="panel-heading">
				<h3>'. $titulo .'</h3>';
	if (empty($data)) { } else { $arquivo .= '<p class="meta-data gray6">Data de publicação: <span>'. $data .'</span></p>'; }
	$arquivo .= '</div>
			  <div class="panel-body">';
	if($extensao == pdf) {			  
		$arquivo .= '<a href="' .$nome .'" class="pdf">'. $arqanexo .'</a>';
	} elseif($extensao == mp3) {			  
		$arquivo .= '<a href="' .$nome .'" class="play">'. $arqanexo .'</a>';
	} elseif(($extensao == png) || ($extensao == jpg) || ($extensao == gif)) {
		$arquivo .= '<a href="' .$nome .'" class="img">'. $arqanexo .'</a>';	
	} else {
		$arquivo .= '<a href="' .$nome .'" class="link">'. $arqanexo .'</a>';	
	}
	$arquivo .= '</div>
			  </div>';
	return $arquivo;
}
add_shortcode('anexo', 'anexo');

?>