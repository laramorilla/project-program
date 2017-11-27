<?php
/**
 * Content Posts Blocks Widget
 *
 * @package hoot
 * @subpackage responsive-brix
 * @since responsive-brix 1.0
 */

/**
* Class Hoot_Content_Posts_Blocks_Widget
*/
class Hoot_Content_Posts_Blocks_Widget extends Hoot_WP_Widget {

	function __construct() {

		$settings['id'] = 'hoot-posts-blocks-widget';
		$settings['name'] = __( 'Hoot > Content Blocks (Posts)', 'responsive-brix' );
		$settings['widget_options'] = array(
			'description'	=> __('Display Styled Content Blocks.', 'responsive-brix'),
			// 'classname'		=> 'hoot-content-blocks-widget', // CSS class applied to frontend widget container via 'before_widget' arg
		);
		$settings['control_options'] = array();
		$settings['form_options'] = array(
			//'name' => can be empty or false to hide the name
			array(
				'name'		=> __( "Title (optional)", 'responsive-brix' ),
				'id'		=> 'title',
				'type'		=> 'text',
			),
			array(
				'name'		=> __( 'Blocks Style', 'responsive-brix' ),
				'id'		=> 'style',
				'type'		=> 'images',
				'std'		=> 'style1',
				'options'	=> array(
					'style1'	=> trailingslashit( HOOT_THEMEURI ) . 'admin/images/content-block-style-1.png',
					'style2'	=> trailingslashit( HOOT_THEMEURI ) . 'admin/images/content-block-style-2.png',
					// 'style3'	=> trailingslashit( HOOT_THEMEURI ) . 'admin/images/content-block-style-3.png',
					'style4'	=> trailingslashit( HOOT_THEMEURI ) . 'admin/images/content-block-style-4.png',
				),
			),
			array(
				'name'		=> __( 'Category (Optional)', 'responsive-brix' ),
				'desc'		=> __( 'Leave empty to display from all categories.', 'responsive-brix' ),
				'id'		=> 'category',
				'type'		=> 'select',
				'options'	=> array( '0' => '' ) + Hoot_WP_Widget::get_tax_list('category') ,
			),
			array(
				'name'		=> __( 'Number of Posts to show', 'responsive-brix' ),
				'desc'		=> __( 'Default: 4', 'responsive-brix' ),
				'id'		=> 'count',
				'type'		=> 'text',
				'sanitize'	=> 'absint',
			),
			array(
				'name'		=> __( 'Display full content instead of excerpt', 'responsive-brix' ),
				'id'		=> 'fullcontent',
				'type'		=> 'checkbox',
			),
			array(
				'name'		=> __( 'Show Post Date', 'responsive-brix' ),
				'id'		=> 'show_date',
				'type'		=> 'checkbox',
			),
			array(
				'name'		=> __( 'Show number of comments', 'responsive-brix' ),
				'id'		=> 'show_comments',
				'type'		=> 'checkbox',
			),
			array(
				'name'		=> __( 'Show categories', 'responsive-brix' ),
				'id'		=> 'show_cats',
				'type'		=> 'checkbox',
			),
			array(
				'name'		=> __( 'Show tags', 'responsive-brix' ),
				'id'		=> 'show_tags',
				'type'		=> 'checkbox',
			),
			array(
				'name'		=> __( 'No. Of Columns', 'responsive-brix' ),
				'id'		=> 'columns',
				'type'		=> 'select',
				'std'		=> '4',
				'options'	=> array(
					'1'	=> __( '1', 'responsive-brix' ),
					'2'	=> __( '2', 'responsive-brix' ),
					'3'	=> __( '3', 'responsive-brix' ),
					'4'	=> __( '4', 'responsive-brix' ),
					'5'	=> __( '5', 'responsive-brix' ),
				),
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

		$settings = apply_filters( 'hoot_content_posts_blocks_widget_settings', $settings );

		parent::__construct( $settings['id'], $settings['name'], $settings['widget_options'], $settings['control_options'], $settings['form_options'] );

	}

	/**
	 * Echo the widget content
	 */
	function display_widget( $instance, $before_title = '', $title='', $after_title = '' ) {
		extract( $instance, EXTR_SKIP );
		include( hoot_locate_widget( 'content-posts-blocks' ) ); // Loads the widget/content-posts-blocks or template-parts/widget-content-posts-blocks.php template.
	}

}

/**
 * Register Widget
 */
function hoot_content_posts_blocks_widget_register(){
	register_widget('Hoot_Content_Posts_Blocks_Widget');
}
add_action('widgets_init', 'hoot_content_posts_blocks_widget_register');