<?php

if ( ! class_exists( 'Simple_Hijri_Calendar' ) ) {

	/**
	* Hijri Calendar
	*/
	class Simple_Hijri_Calendar extends WP_Widget {
		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			// widget actual processes
			$widget_ops = array( 'classname' 	=> 'widget_simple_hijri_calendar', 'description' 	=> __( 'Simple hijri calendar widget', 'simple-hijri-calendar' ) );
			parent::__construct( 'simple_hijri_calendar', __( 'Simple Hijri Calendar', 'simple-hijri-calendar' ), $widget_ops );
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			// outputs the content of the widget
			$hijri = new uCal;
			$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Simple Hijri Calendar', 'simple-hijri-calendar' );
			$format = isset( $instance['format'] ) ? $instance['format'] : get_option( 'date_format' );
			$language = isset( $instance['language'] ) ? $instance['language'] : 'arabic';
			$fonts = isset( $instance['fonts'] ) ? $instance['fonts'] : '';
			$align = isset( $instance['align'] ) ? $instance['align'] : 'alignleft';

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			#date_default_timezone_set('Asia/Jakarta');
			$hijri->setLang( $language );

			$hijri_date = $hijri->date( $format, 0, 1 );
			$calendar 	= 'ar' == $language ? simple_hijri_calendar_arabic( $hijri_date ) : $hijri_date;
			$class 		= 'ar' == $language ? 'arabic' : 'english'; 
			
			if ( ! empty( $fonts ) ) { 
				if ( 'ar' == $language ) {
					echo simple_hijri_calendar_fonts_css( $fonts );
				}
			}

			printf(
				'<div class="simple-hijri-calendar %1$s %2$s">
					<span>%3$s</span>
				</div>',
				esc_attr( $class ),
				esc_attr( $align ),
				esc_html( $calendar )
			);

			echo $args['after_widget'];
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			// outputs the options form on admin
			$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Simple Hijri Calendar', 'simple-hijri-calendar' );
			$format = isset( $instance['format'] ) ? $instance['format'] : get_option( 'date_format' );
			$language = isset( $instance['language'] ) ? $instance['language'] : 'arabic';
			$align = isset( $instance['align'] ) ? $instance['align'] : 'alignleft';
			$fonts = isset( $instance['fonts'] ) ? $instance['fonts'] : '';
			?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'simple-hijri-calendar' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'format' ); ?>"><?php _e( 'Format:', 'simple-hijri-calendar' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>" type="text" value="<?php echo esc_attr( $format ); ?>">
				<small><?php _e( 'Example: l, jS F, Y. For complete list time formatting, read:', 'simple-hijri-calendar' ); ?> <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php _e( 'Documentation on date and time formatting.', 'simple-hijri-calendar' ); ?></a></small>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'language' ); ?>"><?php _e( 'Language:', 'simple-hijri-calendar' ); ?></label><br> 
				<select id="<?php echo $this->get_field_id( 'language' ); ?>" name="<?php echo $this->get_field_name( 'language' ); ?>">
					<option value="ar" <?php selected( $language, 'ar' ); ?>><?php _e( 'Arabic', 'simple-hijri-calendar' ); ?></option>
					<option value="en" <?php selected( $language, 'en' ); ?>><?php _e( 'English', 'simple-hijri-calendar' ); ?></option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'fonts' ); ?>"><?php _e( 'Fonts:', 'simple-hijri-calendar' ); ?></label><br>
				<select id="<?php echo $this->get_field_id( 'fonts' ); ?>" name="<?php echo $this->get_field_name( 'fonts' ); ?>">
					<option value=""><?php _e( '&mdash; Select Fonts &mdash;', 'simple-hijri-calendar' ); ?></option>
					<?php
						$fonts_options = simple_hijri_calendar_fonts();
						foreach ($fonts_options as $css => $family ) {
							printf(
								'<option value="%1$s"%3$s>%2$s</option>',
								esc_attr( $css ),
								esc_html( $family ),
								selected( $fonts, $css, false )
							);
						}
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e( 'Align:', 'simple-hijri-calendar' ); ?></label><br> 
				<select id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
					<option value="alignleft" <?php selected( $align, 'alignleft' ); ?>><?php _e( 'Left', 'simple-hijri-calendar' ); ?></option>
					<option value="aligncenter" <?php selected( $align, 'aligncenter' ); ?>><?php _e( 'Center', 'simple-hijri-calendar' ); ?></option>
					<option value="alignright" <?php selected( $align, 'alignright' ); ?>><?php _e( 'Right', 'simple-hijri-calendar' ); ?></option>
				</select>
			</p>
			<?php 
		}

		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			// processes widget options to be saved
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['format'] = ( ! empty( $new_instance['format'] ) ) ? strip_tags( $new_instance['format'] ) : '';
			$instance['language'] = $new_instance['language'];
			$instance['fonts'] = $new_instance['fonts'];
			$instance['align'] = $new_instance['align'];

			return $instance; 
		}
	}
}
/*EOF*/