<?php
/*
* Plugin Name: Curriculum Vitae (by osFlake)
* Plugin URI: www.sniezek.eu
* Description: This plugins showing your CV!
* Version: 1.0.1
* Author: osFlake Arkadiusz Śnieżek
* Author URI: http://www.sniezek.eu/
* Text Domain: cv-osflake
* License: GPL2
*/

function cv_osflake_load_textdomain(){
	load_plugin_textdomain('cv-osflake', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}
add_action('after_setup_theme', 'cv_osflake_load_textdomain');

add_action('wp_head', 'main_css');
function main_css(){
    echo '<link rel="stylesheet" href="'.plugins_url( '/css/cv-osflake.css',__FILE__).'" type="text/css" media="all" />';
}


add_theme_support( 'post-thumbnails' );
add_image_size( 'cv-avatar', 'auto', 90, false );
add_image_size( 'cv-avatar-admin', 'auto', 60, true );

add_action('init', 'show_page_excerpt');
function show_page_excerpt() {
    add_post_type_support( 'opisy', 'excerpt' );
    add_post_type_support( 'opisy', 'page-attributes' );
}

function cv() {
	$labels = array(
		'name'               => _x( 'CV', 'post type general name' ),
		'singular_name'      => _x( 'cv', 'post type singular name' ),
		'add_new'            => __( 'Dodaj nowe','cv-osflake'),
		'add_new_item'       => __( 'Dodaj nowe','cv-osflake' ),
		'edit_item'          => __( 'Edytuj cv','cv-osflake' ),
		'new_item'           => __( 'Nowe cv','cv-osflake' ),
		'all_items'          => __( 'Wszystkie cv','cv-osflake' ),
		'view_item'          => __( 'Zobacz cv','cv-osflake'),
		'search_items'       => __( 'Szukaj cv','cv-osflake'),
		'not_found'          => __( 'Nie znaleziono pozycji','cv-osflake' ),
		'not_found_in_trash' => __( 'Nie znaleziono pozycji w koszu','cv-osflake' ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('CV','cv-osflake'));
	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'show_ui' => true,
		'publicly_queryable' => true,
		'rewrite' => array( 'slug' => 'opisy' ),
                'has_archive'   => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail')
	);
	register_post_type( 'opisy', $args );	

}

function taxonomia_cv_category() {
	register_taxonomy( 'cv-category',array (
	  0 => 'opisy',
	),
	array( 'hierarchical' => true,
		'label' => __('CV - kategoria','cv-osflake'),
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true,
		'labels' => array (
	  'search_items' => __('CV - kategoria','cv-osflake'),
	  'popular_items' => __('Najpopularniejsze','cv-osflake'),
	  'all_items' => __('Wszystkie','cv-osflake'),
	  'parent_item' => __('Nadrzędny','cv-osflake'),
	  'parent_item_colon' => __('Nadrzędna Kategoria','cv-osflake'),
	  'edit_item' => __('Edytuj','cv-osflake'),
	  'update_item' => __('Aktualizuj','cv-osflake'),
	  'add_new_item' => __('Dodaj nową','cv-osflake'),
	  'new_item_name' => __('Nowa kategoria','cv-osflake'),
	  'separate_items_with_commas' => __('Oddziel przecinkami','cv-osflake'),
	  'add_or_remove_items' => __('Dodaj lub usuń','cv-osflake'),
	  'choose_from_most_used' => __('Wybierz z najczęściej używanych','cv-osflake'),
	)
) ); 
}

function show_cv_opisy(){

$pokaz = __('pokaż...','cv-osflake');
$ukryj = __('ukryj...','cv-osflake');
?>
<script language='JavaScript'>
jQuery(function(){
        var status = 0;
	var ukryj = "<?=$pokaz;?>";
	var pokaz = "<?=$ukryj;?>";
        jQuery('.show-info').click(function(){
            jQuery('#div-'+ jQuery(this).attr('target')).toggle("slow");
            if(status == 0)
            {
                jQuery(this).val(pokaz);
                status = 1;
            }
            else
            {
                jQuery(this).val(ukryj);
                status = 0;
            }
            
        });
});
</script>
<?php
echo "<div class='opis-left cv-head'>";
if (esc_attr(get_option('upload_image'))) :
	echo "<img src='".esc_attr(get_option('upload_image'))."' alt='".$dane[0]."' class='img-cv'/>";
endif;
echo "<h1 class='cv-category-name fonty-up'>".esc_attr(get_option('imie_nazwisko'))."</h1>";
if (esc_attr(get_option('motto'))) :
	echo "<p class='odstep'><i>".esc_attr(get_option('motto'))."</i></p>";
endif;

echo "</div><div class='clear'></div>";


$taxonomy = get_terms('cv-category',array(
	'fields'            => 'all',
	'hide_empty' 	    => 1,
	'orderby'	    => 'id'
));

$nr_pozycji = 1;
foreach ($taxonomy as $cat){
echo "<div class='cv-table'>";
echo "<h1 class='cv-category-name fonty-up'>".$cat->name."</h1>";

	query_posts( array(
		'posts_per_page' => 100,
		'tax_query' => array(
			array(
				'taxonomy' => 'cv-category',
				'field' => 'slug',
				'terms' => $cat->slug
			)
		),
		'orderby'=>'menu_order',
		'order' => 'DESC',
		'post_type' => 'opisy'
	));

while ( have_posts() ) : the_post();
	echo "<div class='cv-table-row'>";
	if ( has_post_thumbnail() ) { 
		echo "<div class='opis-left'><div class='img-position'>";the_post_thumbnail('cv-avatar');echo "</div>";
	}
//	if ( current_user_can('administrator')){ echo edit_post_link(_e('edytuj','cv-osflake'));}
		the_title( '<h2 class="cv-title fonty-lo">', '</h2>' ); 
		the_excerpt();
		if ( has_post_thumbnail() ) {echo "</div><div class='clear'></div>";}	

/* wyświetlanie zawartości */
	if (get_the_content()){
?>

		<input class="show-info" type="button" target="<?php echo $nr_pozycji;?>" value="<?php echo $pokaz;?>" />
<?php		
		echo'	<div id="div-'.$nr_pozycji.'" style="display: none; ">
			<div class="block">'.get_the_content().'</p>
			</div>
		</div><div class="clear"></div>';
	$nr_pozycji++;
	} //end zawartosci

	echo "</div>"; //end div cv-table-row
endwhile; 

echo "</div>";

wp_reset_query();
} //end foreach

}


class osFlake_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'osflake_widget', // Base ID
			__('CV', 'cv-osflake'), // Name
			array( 'description' => __( 'A osFlake Widget to show Your CV', 'cv-osflake' ), ) // Args
		);
	}

/*** Front-end display of widget.
* @see WP_Widget::widget()
* @param array $args     Widget arguments.
* @param array $instance Saved values from database.
*/
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ){
			echo $args['before_title'] . $title . $args['after_title'];
//			echo $args['before_title'] . $args['after_title'];
			echo do_shortcode('[show-cv]');
			wp_reset_query();
		}
		echo $args['after_widget'];
	}
	

	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {	$title = $instance[ 'title' ];	}
		else {	$title = __( 'New title', 'cv-osflake' );}?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
<?php 	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}


/** register osFlake_Widget widget **/
function register_osflake_widget() {
    register_widget( 'osFlake_Widget' );
}


/************************/
/** ADMIN SECTION PAGE **/
/************************/

add_action('admin_menu', 'cv_create_menu');
function cv_create_menu() {
	add_submenu_page( 'edit.php?post_type=opisy', __('Ustawienia dla pluginu CV','cv-osflake'), __('CV - ustawienia','cv-osflake'), 'manage_options', 'cv-settings-page', 'cv_settings_page' );

/**8888 REJESTRACJA PÓL ******/
add_action( 'admin_init', 'register_wlasnepola' );}

function register_wlasnepola() {
	register_setting( 'cv-settings-group', 'imie_nazwisko' );
	register_setting( 'cv-settings-group', 'motto' );
	register_setting( 'cv-settings-group', 'upload_image' );
}
/** KONIEC REJESTRACJI PÓL **/

function wp_gear_manager_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
}
function wp_gear_manager_admin_styles() {
	wp_enqueue_style('thickbox');
}
function wp_jquery_page_scripts(){
	wp_enqueue_script('jquery');
}
//add_action( 'wp_enqueue_scripts', 'wp_jquery_page_scripts' );
add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');

function cv_settings_page() {
?>
<div class="wrap">
<script language='JavaScript'>
jQuery(document).ready(function() {
jQuery('#upload_image_button').click(function() {
formfield = jQuery('#upload_image').attr('name');
tb_show('', 'media-upload.php?type=image&TB_iframe=true');
return false;});

window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
jQuery('#upload_image').val(imgurl);
tb_remove();}
});
</script>

<h2>Curriculum Vitae (by osFlake)</h2>
<h3><?php _e('strona ustawień wtyczki','cv-osflake');?></h3>

<form method="post" action="options.php">
    <?php settings_fields( 'cv-settings-group' ); ?>
    <?php do_settings_sections( 'cv-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e('Imię i nazwisko','cv-osflake');?></th>
        <td><input type="text" name="imie_nazwisko" value="<?php echo esc_attr( get_option('imie_nazwisko') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php _e('Twoje motto','cv-osflake');?></th>
        <td><textarea name="motto" ><?php echo esc_attr( get_option('motto') ); ?></textarea></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php _e('Zdjęcie profilowe CV','cv-osflake');?></th>
	<td><label for="upload_image">
		<input id="upload_image" type="text" size="36" name="upload_image" value="<?php echo get_option('upload_image'); ?>" />
		<input id="upload_image_button" type="button" value="<?php _e('Wybierz zdjęcie','cv-osflake');?>" />
		<br /><?php _e('Załaduj zdjęcie lub wybierz z bazy plików','cv-osflake');?>.
	<?php if(get_option('upload_image')){?>
	<p><img src="<?php echo get_option('upload_image'); ?>" style="max-width:300px;" /></p>
	<?php }else{echo "<h2 style='color:red;'><?php _e('BRAK ZDJĘCIA! Załaduj je powyżej!','cv-osflake');?></h2>";} ?>
	</label>
	</td>
        </tr>
    </table>
    <?php submit_button(); ?>


</form>
</div>
<?php }

//powiadomienie dla admina
function show_admin_panel_message() {
    $key = esc_attr(get_option('imie_nazwisko'));
    $keyt = esc_attr(get_option('upload_image'));
    $link_edit = 'edit.php?post_type=opisy&page=cv-settings-page';

    if(empty($key)) {
        echo '<div id="message" class="error"><p>';
	printf(__('Plugin CV wymaga dodania <b>imienia i nazwiska.</b>  <a href="%s">Zaktualizuj</a>', 'cv-osflake'), esc_url($link_edit));
	echo '</p></div>';
    }
    if(empty($keyt)) {
	echo '<div id="message" class="error"><p>';
	printf(__('Plugin CV wymaga dodania zdjęcia! <a href="%s">Zaktualizuj</a>', 'cv-osflake'), esc_url($link_edit));
        echo '</p></div>';
    }
}

add_action('admin_notices', 'show_admin_panel_message');

/******************************/
/** KONIEC SEKCJI USTAWIENIA **/
/******************************/

function dodanie_kolumny_w_cv_admin( $column ) {
    $column['dod_opis'] = 'Opis';
    $column['obrazek'] = 'Obrazek';
    return $column;
}
add_filter( 'manage_opisy_posts_columns', 'dodanie_kolumny_w_cv_admin' );

function dodanie_wierszy_w_cv_admin( $column_name, $post_id ) {

    $custom_fields = get_post_custom( $post_id );

    switch ($column_name) {
        case 'dod_opis' :
		echo the_excerpt();
            break;
        case 'obrazek' :
		if ( has_post_thumbnail() ){ the_post_thumbnail('cv-avatar-admin');}
            break;
        default:
    }
}

add_action( 'manage_opisy_posts_custom_column', 'dodanie_wierszy_w_cv_admin', 10, 1 );

add_shortcode('show-cv','show_cv_opisy');
add_action( 'widgets_init', 'register_osflake_widget' );
add_action( 'init', 'cv' );
add_action( 'init', 'taxonomia_cv_category');


?>