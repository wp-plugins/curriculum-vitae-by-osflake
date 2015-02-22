<?php
class osFlake_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'osflake_widget', // Base ID
			__('CV', CV_OSFLAKE_DOMAIN_LANG), // Name
			array( 'description' => __( 'A osFlake Widget to show Your CV', CV_OSFLAKE_DOMAIN_LANG ), ) // Args
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
		else {	$title = __( 'Curriculum Vitae', CV_OSFLAKE_DOMAIN_LANG );}?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:',CV_OSFLAKE_DOMAIN_LANG ); ?></label> 
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

add_action( 'widgets_init', 'register_osflake_widget' );

?>