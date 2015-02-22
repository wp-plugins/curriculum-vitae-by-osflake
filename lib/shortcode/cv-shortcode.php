<?php
function show_cv_opisy(){

$pokaz = __('more...',CV_OSFLAKE_DOMAIN_LANG);
$ukryj = __('less...',CV_OSFLAKE_DOMAIN_LANG);
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
if ((get_option('upload_image')) and (get_option('imie_nazwisko'))){
echo "<div class='opis-left cv-head'>";
if (esc_attr(get_option('upload_image'))) :
	echo "<img src='".esc_attr(get_option('upload_image'))."' alt='".esc_attr(get_option('imie_nazwisko'))."' class='img-cv'/>";
endif;
echo "<h1 class='cv-category-name fonty-up'>".esc_attr(get_option('imie_nazwisko'))."</h1>";
if (esc_attr(get_option('motto'))) :
	echo "<p class='odstep'><i>".esc_attr(get_option('motto'))."</i></p>";
endif;

echo "</div><div class='clear'></div>";
}

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

while ( have_posts() ) : the_post(); //zawartość cv
	echo "<div class='cv-table-row'>";
	if ( has_post_thumbnail() ) { 
		echo "<div class='opis-left'><div class='img-position'>";the_post_thumbnail('cv-avatar');echo "</div>";
	}

		the_title( '<h2 class="cv-title fonty-lo">', '</h2>' ); 
		the_excerpt();
		if ( current_user_can('administrator')){ edit_post_link(__('edit',CV_OSFLAKE_DOMAIN_LANG));}
		if ( has_post_thumbnail() ) {echo "</div><div class='clear'></div>";}	


	if (get_the_content()){

		echo '<input class="show-info" type="button" target="'. $nr_pozycji.'" value="'. $pokaz.'" />';	
		echo '<div id="div-'.$nr_pozycji.'" style="display: none; ">';
			the_content();
		echo '</div><div class="clear"></div>';
	$nr_pozycji++;
	} //end zawartosci

	echo "</div>"; //end div cv-table-row
endwhile; 

echo "</div>";

wp_reset_query();
} //end foreach

}
//add_filter( 'get_the_content', 'shortcode_unautop');
//add_filter( 'get_the_content', 'do_shortcode');

add_shortcode('show-cv','show_cv_opisy');
?>