<?php
/*
* Plugin Name: Curriculum Vitae (by osFlake)
* Plugin URI: www.sniezek.eu
* Description: This plugins showing your CV!
* Version: 1.1
* Author: osFlake Arkadiusz Śnieżek
* Author URI: http://www.sniezek.eu/
* Text Domain: cv-osflake
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

define('CV_OSFLAKE_DIR', plugin_dir_path(__FILE__));
define('CV_OSFLAKE_URL', plugin_dir_url(__FILE__));
define('CV_OSFLAKE_PLUGIN_FILE', __FILE__);
define('CV_OSFLAKE_PLUGIN_FILE_LANG', dirname(plugin_basename(__FILE__)) . '/lang/');
define('CV_OSFLAKE_DOMAIN_LANG', 'cv-osflake');

require(CV_OSFLAKE_DIR . 'lib/admin/cv-admin.php');
require(CV_OSFLAKE_DIR . 'lib/menu/cv-menu.php');
require(CV_OSFLAKE_DIR . 'lib/shortcode/cv-shortcode.php');
require(CV_OSFLAKE_DIR . 'lib/widget/cv-widget.php');

define ('CV_OSFLAKE_MOTYW_COLOR', esc_attr(get_option('motyw_color'))); //definiowanie koloru motywu
define ('CV_OSFLAKE_CATEGORY_COLOR', esc_attr(get_option('kategoria_color'))); //definiowanie koloru kategorii


/** Load language texdomain **/
function cv_osflake_load_textdomain(){
	load_plugin_textdomain(CV_OSFLAKE_DOMAIN_LANG, false, CV_OSFLAKE_PLUGIN_FILE_LANG);
}

/** Load custom osflake css **/
function main_css(){
    echo '<link rel="stylesheet" href="'.plugins_url( '/css/cv-osflake.css',__FILE__).'" type="text/css" media="all" />';
	echo '<style>
		.cv-table-row{
			border-left: 2px solid '.CV_OSFLAKE_MOTYW_COLOR.' !important;
			border-bottom: 2px solid '.CV_OSFLAKE_MOTYW_COLOR.' !important;
		}
		.cv-head{	background-color: '.CV_OSFLAKE_MOTYW_COLOR.' !important;}
		.cv-category-name{	background-color: '.CV_OSFLAKE_CATEGORY_COLOR.' !important;}
		.img-cv{	border-color: '.CV_OSFLAKE_MOTYW_COLOR.' !important;}
		</style>';
}

/**  showing on cv panels attributes **/
function show_page_excerpt() {
    add_post_type_support( 'opisy', 'excerpt' );
    add_post_type_support( 'opisy', 'page-attributes' );
}

add_action('after_setup_theme', 'cv_osflake_load_textdomain');
add_action('wp_head', 'main_css');
add_action('init', 'show_page_excerpt');
add_theme_support( 'post-thumbnails' );
add_image_size( 'cv-avatar', 'auto', 90, false );
add_image_size( 'cv-avatar-admin', 'auto', 60, true );
?>