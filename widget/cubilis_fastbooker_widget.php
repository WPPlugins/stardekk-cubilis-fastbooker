<?php

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

/**
 * Adds My_Widget widget.
 */
class Cubilis_Fastbooker_Widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Cubilis_Fastbooker_Widget', // Base ID
			__('Cubilis Fastbooker Widget', 'text_domain'), // Name
			array( 'description' => __( 'Cubilis Fastbooker Integration', 'text_domain' ), ) // Args
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) 
	{
		// enqueue styles and scripts
		$this->enqueueAssets();
		
		// print html
		$this->printHtml($args, $instance);
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) 
	{
		if ( isset( $instance[ 'font-color' ] ) ) {
			$fontColor = $instance[ 'font-color' ];
		}
		if ( isset( $instance[ 'button-font-color' ] ) ) {
			$buttonFontColor = $instance[ 'button-font-color' ];
		}
		if ( isset( $instance[ 'button-back-color' ] ) ) {
			$buttonBackColor = $instance[ 'button-back-color' ];
		}
		if ( isset( $instance[ 'button-hover-color' ] ) ) {
			$buttonHoverColor = $instance[ 'button-hover-color' ];
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'font-color' ); ?>"><?php _e( 'Font Color:', 'text_domain' ); ?></label> 
			<input type="color" class="widefat" id="<?php echo $this->get_field_id( 'font-color' ); ?>" name="<?php echo $this->get_field_name( 'font-color' ); ?>" type="text" value="<?php echo esc_attr( $fontColor ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button-font-color' ); ?>"><?php _e( 'Button Font Color:', 'text_domain' ); ?></label> 
			<input type="color" class="widefat" id="<?php echo $this->get_field_id( 'button-font-color' ); ?>" name="<?php echo $this->get_field_name( 'button-font-color' ); ?>" type="text" value="<?php echo esc_attr( $buttonFontColor ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button-back-color' ); ?>"><?php _e( 'Button Background Color:', 'text_domain' ); ?></label> 
			<input type="color" class="widefat" id="<?php echo $this->get_field_id( 'button-back-color' ); ?>" name="<?php echo $this->get_field_name( 'button-back-color' ); ?>" type="text" value="<?php echo esc_attr( $buttonBackColor ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'button-hover-color' ); ?>"><?php _e( 'Button hover Color:', 'text_domain' ); ?></label> 
			<input type="color" class="widefat" id="<?php echo $this->get_field_id( 'button-hover-color' ); ?>" name="<?php echo $this->get_field_name( 'button-hover-color' ); ?>" type="text" value="<?php echo esc_attr( $buttonHoverColor ); ?>">
		</p>
		<?php
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) 
	{
		$instance = array();
		$instance['font-color'] 		= ( ! empty( $new_instance['font-color'] ) ) ? strip_tags( sanitize_text_field( $new_instance['font-color'] ) ) : '';
		$instance['button-font-color'] 	= ( ! empty( $new_instance['button-font-color'] ) ) ? strip_tags( sanitize_text_field ($new_instance['button-font-color'] ) ) : '';
		$instance['button-back-color'] 	= ( ! empty( $new_instance['button-back-color'] ) ) ? strip_tags( sanitize_text_field( $new_instance['button-back-color'] ) ) : '';
		$instance['button-hover-color'] = ( ! empty( $new_instance['button-hover-color'] ) ) ? strip_tags( sanitize_text_field( $new_instance['button-hover-color'] ) ) : '';
		return $instance;
	}
	
	private function enqueueAssets()
	{
		// STYLES
		wp_enqueue_style('widget-jquery-style', 'https://static.cubilis.eu/jquery/ui/smoothness/jquery-ui-1.8.16.custom.css');
		wp_enqueue_style('widget-fancybox-style', 'https://static.cubilis.eu/fancybox/jquery.fancybox-1.3.4.css');
		wp_enqueue_style('widget-font-awesome-style', plugins_url( plugin_basename( dirname( __FILE__ ) ) . '/../font-awesome/css/font-awesome.min.css' ));
		wp_enqueue_style('widget-style', plugins_url( plugin_basename( dirname( __FILE__ ) ) .'/../css/fastbooker-widget.css' ));
		
		// SCRIPTS
		wp_register_script( 'jquery', 'https://static.cubilis.eu/jquery/jquery-1.6.4.min.js', 'jquery','1.0', false);
		wp_enqueue_script( 'jquery' );
		
		wp_register_script( 'widget-jquery-ui-js', 'https://static.cubilis.eu/jquery/jquery-ui-1.8.16.custom.min.js', 'jquery','1.0', false);
		wp_enqueue_script( 'widget-jquery-ui-js' );
	
		// load internalization jquery-ui js
		if (get_locale() !== null) {
			$arrLocale = explode("_", get_locale(), 2);
			$locale = $arrLocale[0];
			
			if ($locale !== null) {			
				$jqueryUILocaleScript 		= plugins_url( plugin_basename( dirname( __FILE__ ) ) .'/../js/jquery-ui/i18n/datepicker-' . $locale . '.js' );
				if (!$this->is_url_exist($jqueryUILocaleScript)) {
					$jqueryUILocaleScript 	= plugins_url( plugin_basename( dirname( __FILE__ ) ) .'/../js/jquery-ui/i18n/datepicker-en.js' );
				}
				wp_register_script( 'widget-jquery-ui-locale-js',  $jqueryUILocaleScript, 'jquery','1.0', false);
				wp_enqueue_script( 'widget-jquery-ui-locale-js' );
			}
		}

		wp_register_script( 'widget-fancybox-js', 'https://static.cubilis.eu/fancybox/jquery.fancybox-1.3.4.js', 'jquery','1.0', false);
		wp_enqueue_script( 'widget-fancybox-js' );

		wp_register_script( 'widget-default-js', plugins_url( plugin_basename( dirname( __FILE__ ) ). '/../js/fastbooker-widget.js' ), 'jquery','1.0', false);
		wp_enqueue_script( 'widget-default-js' );
	}
	
	private function printHtml($args, $instance)
	{
		
		// init colors cubilis_fastbooker
		$fontColor 		  = ($instance['font-color'] !== null && $instance['font-color'] !== "" ? $instance['font-color'] : "#000");
		$buttonFontColor  = ($instance['button-font-color'] !== null && $instance['button-font-color'] !== "" ? $instance['button-font-color'] : "#fff");
		$buttonBackColor  = ($instance['button-back-color'] !== null && $instance['button-back-color'] !== "" ? $instance['button-back-color'] : "#000");
		$buttonHoverColor = ($instance['button-hover-color'] !== null && $instance['button-hover-color'] !== "" ? $instance['button-hover-color'] : "#222");
		
		// init booker url
		$fastbookerUrl = "https://booking.cubilis.eu/check_availability.aspx";
		if (get_option('cubilis_fastbooker_integration') == "fullscreen") {
			$fastbookerUrl  = "https://reservations.cubilis.eu/";
			$fastbookerUrl .= get_option('cubilis_fastbooker_identifier');
			$fastbookerUrl .= "/Rooms/Select";
		}
		
		// init locale
		$locale 	 = get_locale();
		$shortLocale = esc_attr(get_option('cubilis_fastbooker_lang'));
		if (isset($locale) && !empty($locale) && substr($locale, 0, 2) !== false && substr($locale, 0, 2) != "") {
			$shortLocale = substr($locale, 0, 2);
		}

		$longLocale = $this->getCubilisLongLang($shortLocale);
				
		
		// echo html
     	echo $args['before_widget'];
		
		echo '<div class="cubilis-fastbooker-div" style="color: ' . esc_attr($fontColor) . ';">';
			echo '<h2 class="fastbooker-title">' . __( 'Reservation', 'text_domain' ) . '</h2>';
			
			if (get_option('cubilis_fastbooker_general_overview') && get_option('cubilis_fastbooker_integration') == "iframe") {
				$url  = "https://booking.cubilis.eu/popups/overview.aspx?logisid=" . esc_attr(get_option('cubilis_fastbooker_logiesid'));
				$url .= "&taal=" . esc_attr(get_option('cubilis_fastbooker_lang')) . "domname=booking.cubilis.eu&booktemplate=1";

				echo '<a href="' . $url . '" title="' . __( 'General Availability', 'text_domain' ) . '" style="color:' . esc_attr($buttonBackColor) . ';" data-button-back-color="' . $buttonBackColor . '" data-button-hover-color="' . esc_attr($buttonHoverColor) . '" class="fancybox-cubilis ">' . __( 'General Availability', 'text_domain' ) . '</a>';
			}

			echo '<form method="get" class="fastbooker-form-widget" action="' . $fastbookerUrl . '">';
				echo '<table id="CheckAvailabilityContainer" cellpadding="10">';
				
					// start date
					echo '<tr>';
						echo '<td>';
							echo '<label class="lbl startdate">' . __( 'Arrival', 'text_domain' ) . '</label>';
							echo '<input type="text" class="input-text startdate-widget" name="startdate" />';
						echo '</td>';
					echo '</tr>';
					
					// end date
					echo '<tr>';
						echo '<td>';
							echo '<label class="lbl enddate">' . __( 'Departure', 'text_domain' ) . '</label>';
							echo '<input type="text" class="input-text enddate-widget" name="enddate" />';
						echo '</td>';
					echo '</tr>';
					
					if (get_option('cubilis_fastbooker_discount')) {
						echo '<tr>';
							echo '<td>';
								echo '<label class="lbl discount">' . __( 'Discount', 'text_domain' ) . '</label>';
								echo '<input type="text" class="input-text discount-widget" name="discount" />';
							echo '</td>';
						echo '</tr>';
					}

					echo '<tr>';
						echo '<td>';
							echo '<input type="hidden" name="logis" class="logis-widget" value="' . esc_attr(get_option('cubilis_fastbooker_logiesid')) . '" />';
							echo '<input type="hidden" name="integration" class="integration-widget" value="' . esc_attr(get_option('cubilis_fastbooker_integration')) . '" />';
							echo '<input type="hidden" name="lang"  class="lang-widget"  value="' . esc_attr($shortLocale) . '" />';
							echo '<input type="hidden" name="langLong"  class="lang-long-widget"  value="' . esc_attr($longLocale) . '" />';
							echo '<input type="submit" style="color: ' . esc_attr($buttonFontColor) . '; background-color: ' . esc_attr($buttonBackColor) . '; border-color:' . esc_attr($buttonBackColor) . ';"  data-button-back-color="' . esc_attr($buttonBackColor) . '" data-button-hover-color="' . esc_attr($buttonHoverColor) . '" class="btnCheckAvail" value="' . __( 'Check availability', 'text_domain' ) . '" id="btnFastbookerSubmit-widget" />';
						echo '</td>';
					echo '</tr>';
					
					echo '<tr>';
						echo '<td>';
							echo '<div class="cubilis-secure-box">';
								echo '<i class="cubilis-secure-box-icon fa fa-lock"></i>';
								echo __('Secure booking system!', 'text_domain') . '<br />';
								echo '<i class="cubilis-secure-box-icon fa fa-eur"></i>';
								echo __('Best rate guaranteed!', 'text_domain');							
							echo '</div>';
						echo '</td>';
					echo '</tr>';
					
				echo '</table>';
			echo '</form>';
		echo '</div>';

		echo $args['after_widget'];
	}
	
	private function is_url_exist($url)
	{
		$ch = curl_init($url);    
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		return (200 == $code ? true : false );
	}
	
	private function getCubilisLongLang($shortLocale) 
	{
		//nl-NL, fr-FR, en-GB, de-DE
		$longLocale = strtr (get_locale(), array ('_' => '-'));
		if ($shortLocale !== null && !empty($shortLocale)) {
			if ($shortLocale == 'nl') {
				$longLocale = "nl-NL";
			} elseif ($shortLocale == 'fr') {
				$longLocale = "fr-FR";
			} elseif ($shortLocale  == 'en') {
				$longLocale = "en-GB";
			} elseif ($shortLocale  == 'de') {
				$longLocale = "de-DE";
			} else {
				$longLocale = "en-GB";
			}
		}
		
		return $longLocale;
	}
	
} // class Cubilis_Fastbooker_Widget