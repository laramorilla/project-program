<?php
/**
 * Call To Action Widget
 *
 * @package hoot
 * @subpackage responsive-brix
 * @since responsive-brix 1.0
 */

/**
* Class Hoot_CTA_Widget
*/
class Hoot_CTA_Widget extends Hoot_WP_Widget {

	function __construct() {

		$settings['id'] = 'hoot-cta-widget';
		$settings['name'] = __( 'Hoot > Call To Action', 'responsive-brix' );
		$settings['widget_options'] = array(
			'description'	=> __('Display Call To Action block.', 'responsive-brix'),
			// 'classname'		=> 'hoot-cta-widget', // CSS class applied to frontend widget container via 'before_widget' arg
		);
		$settings['control_options'] = array();
		$settings['form_options'] = array(
			//'name' => can be empty or false to hide the name
			array(
				'name'		=> __( 'Headline', 'responsive-brix' ),
				'id'		=> 'headline',
				'type'		=> 'text',
			),
			array(
				'name'		=> __( 'Description', 'responsive-brix' ),
				'id'		=> 'description',
				'type'		=> 'textarea',
			),
			array(
				'name'		=> __( 'Button Text', 'responsive-brix' ),
				'desc'		=> __( 'Leave empty if you dont want to show button', 'responsive-brix' ),
				'id'		=> 'button_text',
				'type'		=> 'text',
				'std'		=> __( 'KNOW MORE', 'responsive-brix' ),
			),
			array(
				'name'		=> __( 'URL', 'responsive-brix' ),
				'desc'		=> __( 'Leave empty if you dont want to show button', 'responsive-brix' ),
				'id'		=> 'url',
				'type'		=> 'text',
				'sanitize'	=> 'url',
			),
			array(
				'name'		=> __( 'Border', 'responsive-brix' ),
				'desc'		=> __( 'Top and bottom borders.', 'responsive-brix' ),
				'id'		=> 'border',
				'type'		=> 'select',
				'std'		=> 'none none',
				'options'	=> array(
					'line line'	=> __( 'Top - Line || Bottom - Line', 'responsive-brix' ),
					'line shadow'	=> __( 'Top - Line || Bottom - DoubleLine', 'responsive-brix' ),
					'line none'	=> __( 'Top - Line || Bottom - None', 'responsive-brix' ),
					'shadow line'	=> __( 'Top - DoubleLine || Bottom - Line', 'responsive-brix' ),
					'shadow shadow'	=> __( 'Top - DoubleLine || Bottom - DoubleLine', 'responsive-brix' ),
					'shadow none'	=> __( 'Top - DoubleLine || Bottom - None', 'responsive-brix' ),
					'none line'	=> __( 'Top - None || Bottom - Line', 'responsive-brix' ),
					'none shadow'	=> __( 'Top - None || Bottom - DoubleLine', 'responsive-brix' ),
					'none none'	=> __( 'Top - None || Bottom - None', 'responsive-brix' ),
				),
			),
			array(
				'name'		=> __( 'CSS', 'responsive-brix' ),
				'id'		=> 'customcss',
				'type'		=> 'collapse',
				'fields'	=> array(
					array(
						'name'		=> __( 'Custom CSS Class', 'responsive-brix' ),
						'desc'		=> __( 'Give this widget a custom css classname', 'responsive-brix' ),
						'id'		=> 'class',
						'type'		=> 'text',
					),
					array(
						'name'		=> __( 'Margin Top', 'responsive-brix' ),
						'desc'		=> __( '(in pixels) Leave empty to load default margins', 'responsive-brix' ),
						'id'		=> 'mt',
						'type'		=> 'text',
						'settings'	=> array( 'size' => 3 ),
						'sanitize'	=> 'absint',
					),
					array(
						'name'		=> __( 'Margin Bottom', 'responsive-brix' ),
						'desc'		=> __( '(in pixels) Leave empty to load default margins', 'responsive-brix' ),
						'id'		=> 'mb',
						'type'		=> 'text',
						'settings'	=> array( 'size' => 3 ),
						'sanitize'	=> 'absint',
					),
				),
			),
		);

		$settings = apply_filters( 'hoot_cta_widget_settings', $settings );

		parent::__construct( $settings['id'], $settings['name'], $settings['widget_options'], $settings['control_options'], $settings['form_options'] );

	}

	/**
	 * Echo the widget content
	 */
	function display_widget( $instance, $before_title = '', $title='', $after_title = '' ) {
		extract( $instance, EXTR_SKIP );
		include( hoot_locate_widget( 'cta' ) ); // Loads the widget/cta or template-parts/widget-cta.php template.
	}

}

/**
 * Register Widget
 */
function hoot_cta_widget_register(){
	register_widget('Hoot_CTA_Widget');
}
add_action('widgets_init', 'hoot_cta_widget_register');