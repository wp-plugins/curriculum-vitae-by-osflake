<?php
function cv() {
	$labels = array(
		'name'               => _x( 'Curriculum Vitae', 'post type general name' ),
		'singular_name'      => _x( 'cv', 'post type singular name' ),
		'add_new'            => __( 'Add new',CV_OSFLAKE_DOMAIN_LANG),
		'add_new_item'       => __( 'Add new',CV_OSFLAKE_DOMAIN_LANG),
		'edit_item'          => __( 'Edit CV',CV_OSFLAKE_DOMAIN_LANG ),
		'new_item'           => __( 'New item CV',CV_OSFLAKE_DOMAIN_LANG ),
		'all_items'          => __( 'All items',CV_OSFLAKE_DOMAIN_LANG ),
		'view_item'          => __( 'View CV',CV_OSFLAKE_DOMAIN_LANG),
		'search_items'       => __( 'Szukaj cv',CV_OSFLAKE_DOMAIN_LANG),
		'not_found'          => __( 'Not found!',CV_OSFLAKE_DOMAIN_LANG ),
		'not_found_in_trash' => __( 'Not found in trash',CV_OSFLAKE_DOMAIN_LANG ), 
		'parent_item_colon'  => '',
		'menu_name'          => __('Curriculum vitae',CV_OSFLAKE_DOMAIN_LANG));
	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'show_ui' => true,
		'publicly_queryable' => true,
		'rewrite' => array( 'slug' => 'opisy' ),
                'has_archive'   => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_icon'           => 'dashicons-welcome-learn-more',
        'can_export'          => true,
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
		'label' => __('CV - category',CV_OSFLAKE_DOMAIN_LANG),
		'show_ui' => true,
		'query_var' => true,
		'show_admin_column' => true,
		'labels' => array (
	  'search_items' => __('CV - category',CV_OSFLAKE_DOMAIN_LANG),
	  'popular_items' => __('Most popular',CV_OSFLAKE_DOMAIN_LANG),
	  'all_items' => __('All',CV_OSFLAKE_DOMAIN_LANG),
	  'parent_item' => __('Parent',CV_OSFLAKE_DOMAIN_LANG),
	  'parent_item_colon' => __('Parent category',CV_OSFLAKE_DOMAIN_LANG),
	  'edit_item' => __('Edit item',CV_OSFLAKE_DOMAIN_LANG),
	  'update_item' => __('Update',CV_OSFLAKE_DOMAIN_LANG),
	  'add_new_item' => __('Add new',CV_OSFLAKE_DOMAIN_LANG),
	  'new_item_name' => __('New category',CV_OSFLAKE_DOMAIN_LANG),
	  'separate_items_with_commas' => __('Separate by commas',CV_OSFLAKE_DOMAIN_LANG),
	  'add_or_remove_items' => __('Add or delete',CV_OSFLAKE_DOMAIN_LANG),
	  'choose_from_most_used' => __('Choose from most used',CV_OSFLAKE_DOMAIN_LANG),
	)
) ); 
}

function dodanie_kolumny_w_cv_admin( $column ) {
    $column['dod_opis'] = 'Opis';
    $column['obrazek'] = 'Obrazek';
    return $column;
}

function dodanie_wierszy_w_cv_admin( $column_name ) {

    $custom_fields = get_post_custom(); // $post_id 

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

add_filter( 'manage_opisy_posts_columns', 'dodanie_kolumny_w_cv_admin' );
add_action( 'manage_opisy_posts_custom_column', 'dodanie_wierszy_w_cv_admin', 10, 1 );

add_action( 'init', 'cv' );
add_action( 'init', 'taxonomia_cv_category');



?>