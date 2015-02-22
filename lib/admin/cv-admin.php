<?php

/*************************/
/** ADMIN SETTINGS PAGE **/
/*************************/

function cv_menu_settings() {
	add_submenu_page( 'edit.php?post_type=opisy', __('CV Plugin Settings',CV_OSFLAKE_DOMAIN_LANG), __('CV - settings',CV_OSFLAKE_DOMAIN_LANG), 'manage_options', 'cv-settings-page', 'cv_settings_page' );
}
/****************************/
/******  CV - SETTINGS ******/
/****************************/

function cv_settings_page() { ?>

<div class="wrap">
<script language='JavaScript'>
jQuery(document).ready(function() {

	jQuery('.input_color').wpColorPicker();

	jQuery('#upload_image_button').click(function() {
	formfield = jQuery('#upload_image').attr('name');
	tb_show('', 'media-upload.php?type=image&TB_iframe=true');
return false;
});

window.send_to_editor = function(html) {
	imgurl = jQuery('img',html).attr('src');
	jQuery('#upload_image').val(imgurl);
	tb_remove();}
});
</script>

<h2>Curriculum Vitae (by osFlake)</h2>
<h3><?php _e('cv settings page',CV_OSFLAKE_DOMAIN_LANG);?></h3>

<form method="post" action="options.php">
<?php 
    
settings_fields( 'cv-settings-group' );
do_settings_sections( 'cv-settings-group' ); 
?>
	
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e('Name and surname',CV_OSFLAKE_DOMAIN_LANG);?></th>
        <td><input type="text" name="imie_nazwisko" value="<?php echo esc_attr( get_option('imie_nazwisko') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php _e('Your motto',CV_OSFLAKE_DOMAIN_LANG);?></th>
        <td><textarea name="motto" ><?php echo esc_attr( get_option('motto') ); ?></textarea></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php _e('Photo',CV_OSFLAKE_DOMAIN_LANG);?></th>
	<td><label for="upload_image">
		<input id="upload_image" type="text" size="36" name="upload_image" value="<?php echo get_option('upload_image'); ?>" />
		<input id="upload_image_button" type="button" value="<?php _e('Choose photo',CV_OSFLAKE_DOMAIN_LANG);?>" />
		<br /><?php _e('Upload your photo or select from media section',CV_OSFLAKE_DOMAIN_LANG);?>.

	<?php if(get_option('upload_image')){?>
		<p><img src="<?php echo get_option('upload_image'); ?>" style="max-width:300px;" /></p>
	<?php }else{echo "<h2 style='color:red;'>". _e('PHOTO IS EMPTY! Please upload it!',CV_OSFLAKE_DOMAIN_LANG)."</h2>";} ?>
	</label>
	</td>
    </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Theme color',CV_OSFLAKE_DOMAIN_LANG);?></th>
        <td><input type="text"  name="motyw_color" value="<?php echo CV_OSFLAKE_MOTYW_COLOR;?>" class="input_color"/></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Category bg color',CV_OSFLAKE_DOMAIN_LANG);?></th>
        <td><input type="text"  name="kategoria_color" value="<?php echo CV_OSFLAKE_CATEGORY_COLOR;?>" class="input_color"/></td>
        </tr>
    </table>
 
 
    <?php submit_button(); ?>
    
</form>
</div>
<?php } //koniec strony cv-settings-page

add_action('admin_menu', 'cv_menu_settings');


/*****************************/
/****** REJESTRACJA PÓL ******/
/*****************************/

function register_wlasnepola() {
	register_setting( 'cv-settings-group', 'imie_nazwisko' );
	register_setting( 'cv-settings-group', 'motto' );
	register_setting( 'cv-settings-group', 'upload_image' );
	register_setting( 'cv-settings-group', 'motyw_color' );
	register_setting( 'cv-settings-group', 'kategoria_color' );
}
add_action( 'admin_init', 'register_wlasnepola' );

/*****************************/
/*** WEWNETRZNE SKRYPTY WP ***/
/*****************************/

function wp_gear_manager_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
	wp_enqueue_script('wp-color-picker');
}

function wp_gear_manager_admin_styles() {
	wp_enqueue_style('thickbox');
	wp_enqueue_style('wp-color-picker');
}

function wp_jquery_page_scripts(){
	wp_enqueue_script('jquery');
}

add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');



/*************************/
/*** ADMIN ABOUT PAGE ***/
/*************************/

function cv_menu_about() {
	add_submenu_page( 'edit.php?post_type=opisy', __('About',CV_OSFLAKE_DOMAIN_LANG), __('About',CV_OSFLAKE_DOMAIN_LANG), 'manage_options', 'cv-about-page', 'cv_about_page' );

}

function cv_about_page() {
	echo "<div class='wrap'>";
	echo "<h2>".__('About',CV_OSFLAKE_DOMAIN_LANG)."</h2>";
	echo "<div class='opis-left'>";
	echo "<img src='".CV_OSFLAKE_URL."img/autor.jpg' alt='osFlake Arkadiusz Śnieżek' align='left'/>";
		echo "some text";
	echo '</div>';
	echo "</div>";
}
add_action('admin_menu', 'cv_menu_about');


//powiadomienie dla admina
function show_admin_panel_message() {
    $key = esc_attr(get_option('imie_nazwisko'));
    $keyt = esc_attr(get_option('upload_image'));
    $link_edit = 'edit.php?post_type=opisy&page=cv-settings-page';

    if(empty($key)) {
        echo '<div id="message" class="error"><p>';
	printf(__('CV plugin required your <b>name and surname </b> <a href=\"%s\">Update this field</a>', CV_OSFLAKE_DOMAIN_LANG), esc_url($link_edit));
	echo '</p></div>';
    }
    if(empty($keyt)) {
	echo '<div id="message" class="error"><p>';
	printf(__('CV plugin required your <b>photo </b> <a href=\"%s\">Update this field</a>', CV_OSFLAKE_DOMAIN_LANG), esc_url($link_edit));
        echo '</p></div>';
    }
}

add_action('admin_notices', 'show_admin_panel_message');
?>