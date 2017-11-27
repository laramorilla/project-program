<?php
/**
 * Data Sets
 *
 * @package hoot
 * @subpackage framework
 * @since hoot 2.0.0
 */

/**
 * Get background repeat settings
 *
 * @param string $return array to return icons|sections|list/empty
 * @return array
 */
if ( !function_exists( 'hoot_enum_icons' ) ):
function hoot_enum_icons( $return = 'list' ) {
	$return = ( empty( $return ) ) ? 'list' : $return;
	$default = Hoot_Options_Helper::icons( $return );
	return apply_filters( 'hoot_enum_icons', $default, $return );
}
endif;

/**
 * Get background repeat settings
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_background_repeat' ) ):
function hoot_enum_background_repeat() {
	$default = array(
		'no-repeat' => __( 'No Repeat', 'responsive-brix' ),
		'repeat-x'  => __( 'Repeat Horizontally', 'responsive-brix' ),
		'repeat-y'  => __( 'Repeat Vertically', 'responsive-brix' ),
		'repeat'    => __( 'Repeat All', 'responsive-brix' ),
		);
	return apply_filters( 'hoot_enum_background_repeat', $default );
}
endif;

/**
 * Get background positions
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_background_position' ) ):
function hoot_enum_background_position() {
	$default = array(
		'top left'      => __( 'Top Left', 'responsive-brix' ),
		'top center'    => __( 'Top Center', 'responsive-brix' ),
		'top right'     => __( 'Top Right', 'responsive-brix' ),
		'center left'   => __( 'Middle Left', 'responsive-brix' ),
		'center center' => __( 'Middle Center', 'responsive-brix' ),
		'center right'  => __( 'Middle Right', 'responsive-brix' ),
		'bottom left'   => __( 'Bottom Left', 'responsive-brix' ),
		'bottom center' => __( 'Bottom Center', 'responsive-brix' ),
		'bottom right'  => __( 'Bottom Right', 'responsive-brix')
		);
	return apply_filters( 'hoot_enum_background_position', $default );
}
endif;

/**
 * Get background attachment settings
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_background_attachment' ) ):
function hoot_enum_background_attachment() {
	$default = array(
		'scroll' => __( 'Scroll Normally', 'responsive-brix' ),
		'fixed'  => __( 'Fixed in Place', 'responsive-brix'),
		);
	return apply_filters( 'hoot_enum_background_attachment', $default );
}
endif;

/**
 * Get background types
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_background_type' ) ):
function hoot_enum_background_type() {
	$default = array(
		'predefined' => __( 'Predefined Pattern', 'responsive-brix' ),
		'custom'     => __( 'Custom Image', 'responsive-brix' ),
		);
	return apply_filters( 'hoot_enum_background_type', $default );
}
endif;

/**
 * Get background patterns
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_background_pattern' ) ):
function hoot_enum_background_pattern() {
	$relative = trailingslashit( substr( trailingslashit( HOOT_IMAGES ) . 'patterns' , ( strlen( THEME_URI ) + 1 ) ) );
	$default = array(
		0 => trailingslashit( HOOT_IMAGES ) . 'patterns/0_preview.jpg',
		$relative . '1.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/1_preview.jpg',
		$relative . '2.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/2_preview.jpg',
		$relative . '3.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/3_preview.jpg',
		$relative . '4.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/4_preview.jpg',
		$relative . '5.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/5_preview.jpg',
		$relative . '6.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/6_preview.jpg',
		$relative . '7.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/7_preview.jpg',
		$relative . '8.png' => trailingslashit( HOOT_IMAGES ) . 'patterns/8_preview.jpg',
		);
	return apply_filters( 'hoot_enum_background_pattern', $default );
}
endif;

/**
 * Get background attachment
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_background_attachment' ) ):
function hoot_enum_background_attachment() {
	$default = array(
		'scroll' => __( 'Scroll Normally', 'responsive-brix' ),
		'fixed'  => __( 'Fixed in Place', 'responsive-brix')
		);
	return apply_filters( 'hoot_enum_background_attachment', $default );
}
endif;

/**
 * Get font sizes.
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_font_sizes' ) ):
function hoot_enum_font_sizes( $min = 9, $max = 82 ) {
	static $cache = array();
	if ( empty( $cache ) || $min != 9 || $max != 82 ) {
		$range = wp_parse_args( apply_filters( 'hoot_enum_font_sizes', array() ), array(
			'min' => $min,
			'max' => $max,
			) );
		$sizes = range( $range['min'], $range['max'] );
		$sizes = array_map( 'absint', $sizes );
	}
	if ( empty( $cache ) && $min == 9 && $max -= 82 )
		$cache = $sizes;
	return $sizes;
}
endif;

/**
 * Get font sizes for optiosn array
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_font_sizes_array' ) ):
function hoot_enum_font_sizes_array( $min = 9, $max = 82, $postfix = 'px' ) {
	$sizes = hoot_enum_font_sizes( $min, $max );
	$output = array();
	foreach ( $sizes as $size )
		$output[ $size ] = $size . $postfix;
	return $output;
}
endif;

/**
 * Get font faces.
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @param string $return array to return websafe|google-fonts|empty/list
 * @return array
 */
if ( !function_exists( 'hoot_enum_font_faces' ) ):
function hoot_enum_font_faces( $return = '' ) {
	$default = ( empty( $return ) || $return == 'list' ) ?
		array_merge( Hoot_Options_Helper::fonts('websafe'), Hoot_Options_Helper::fonts('google-fonts') ):
		Hoot_Options_Helper::fonts($return);
	return apply_filters( 'hoot_enum_font_faces', $default, $return );
}
endif;

/**
 * Get font styles.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_font_styles' ) ):
function hoot_enum_font_styles() {
	$default = array(
		'none'                     => __( 'None', 'responsive-brix' ),
		'italic'                   => __( 'Italic', 'responsive-brix' ),
		'bold'                     => __( 'Bold', 'responsive-brix' ),
		'bold italic'              => __( 'Bold Italic', 'responsive-brix' ),
		'lighter'                  => __( 'Light', 'responsive-brix' ),
		'lighter italic'           => __( 'Light Italic', 'responsive-brix' ),
		'uppercase'                => __( 'Uppercase', 'responsive-brix' ),
		'uppercase italic'         => __( 'Uppercase Italic', 'responsive-brix' ),
		'uppercase bold'           => __( 'Uppercase Bold', 'responsive-brix' ),
		'uppercase bold italic'    => __( 'Uppercase Bold Italic', 'responsive-brix' ),
		'uppercase lighter'        => __( 'Uppercase Light', 'responsive-brix' ),
		'uppercase lighter italic' => __( 'Uppercase Light Italic', 'responsive-brix' )
		);
	return apply_filters( 'hoot_enum_font_styles', $default );
}
endif;

/**
 * Get social profiles and icons
 *
 * Returns an array of all recognized social profiles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return array
 */
if ( !function_exists( 'hoot_enum_social_profiles' ) ):
function hoot_enum_social_profiles() {
	return apply_filters( 'hoot_enum_social_profiles', array(
		'fa-amazon'         => __( 'Amazon', 'responsive-brix' ),
		'fa-android'        => __( 'Android', 'responsive-brix' ),
		'fa-apple'          => __( 'Apple', 'responsive-brix' ),
		'fa-bandcamp'       => __( 'Bandcamp', 'responsive-brix' ),
		'fa-behance'        => __( 'Behance', 'responsive-brix' ),
		'fa-bitbucket'      => __( 'Bitbucket', 'responsive-brix' ),
		'fa-btc'            => __( 'BTC', 'responsive-brix' ),
		'fa-buysellads'     => __( 'BuySellAds', 'responsive-brix' ),
		'fa-codepen'        => __( 'Codepen', 'responsive-brix' ),
		'fa-codiepie'       => __( 'Codie Pie', 'responsive-brix' ),
		'fa-contao'         => __( 'Contao', 'responsive-brix' ),
		'fa-dashcube'       => __( 'Dash Cube', 'responsive-brix' ),
		'fa-delicious'      => __( 'Delicious', 'responsive-brix' ),
		'fa-deviantart'     => __( 'Deviantart', 'responsive-brix' ),
		'fa-digg'           => __( 'Digg', 'responsive-brix' ),
		'fa-dribbble'       => __( 'Dribbble', 'responsive-brix' ),
		'fa-dropbox'        => __( 'Dropbox', 'responsive-brix' ),
		'fa-eercast'        => __( 'Eercast', 'responsive-brix' ),
		'fa-envelope'       => __( 'Email', 'responsive-brix' ),
		'fa-etsy'           => __( 'Etsy', 'responsive-brix' ),
		'fa-facebook'       => __( 'Facebook', 'responsive-brix' ),
		'fa-flickr'         => __( 'Flickr', 'responsive-brix' ),
		'fa-forumbee'       => __( 'Forumbee', 'responsive-brix' ),
		'fa-foursquare'     => __( 'Foursquare', 'responsive-brix' ),
		'fa-free-code-camp' => __( 'Free Code Camp', 'responsive-brix' ),
		'fa-get-pocket'     => __( 'Pocket (getpocket)', 'responsive-brix' ),
		'fa-github'         => __( 'Github', 'responsive-brix' ),
		'fa-google'         => __( 'Google', 'responsive-brix' ),
		'fa-google-plus'    => __( 'Google Plus', 'responsive-brix' ),
		'fa-google-wallet'  => __( 'Google Wallet', 'responsive-brix' ),
		'fa-imdb'           => __( 'IMDB', 'responsive-brix' ),
		'fa-instagram'      => __( 'Instagram', 'responsive-brix' ),
		'fa-jsfiddle'       => __( 'JS Fiddle', 'responsive-brix' ),
		'fa-lastfm'         => __( 'Last FM', 'responsive-brix' ),
		'fa-leanpub'        => __( 'Leanpub', 'responsive-brix' ),
		'fa-linkedin'       => __( 'Linkedin', 'responsive-brix' ),
		'fa-meetup'         => __( 'Meetup', 'responsive-brix' ),
		'fa-mixcloud'       => __( 'Mixcloud', 'responsive-brix' ),
		'fa-paypal'         => __( 'Paypal', 'responsive-brix' ),
		'fa-pinterest'      => __( 'Pinterest', 'responsive-brix' ),
		'fa-quora'          => __( 'Quora', 'responsive-brix' ),
		'fa-reddit'         => __( 'Reddit', 'responsive-brix' ),
		'fa-rss'            => __( 'RSS', 'responsive-brix' ),
		'fa-scribd'         => __( 'Scribd', 'responsive-brix' ),
		'fa-skype'          => __( 'Skype', 'responsive-brix' ),
		'fa-slack'          => __( 'Slack', 'responsive-brix' ),
		'fa-slideshare'     => __( 'Slideshare', 'responsive-brix' ),
		'fa-snapchat'       => __( 'Snapchat', 'responsive-brix' ),
		'fa-soundcloud'     => __( 'Soundcloud', 'responsive-brix' ),
		'fa-spotify'        => __( 'Spotify', 'responsive-brix' ),
		'fa-stack-exchange' => __( 'Stack Exchange', 'responsive-brix' ),
		'fa-stack-overflow' => __( 'Stack Overflow', 'responsive-brix' ),
		'fa-steam'          => __( 'Steam', 'responsive-brix' ),
		'fa-stumbleupon'    => __( 'Stumbleupon', 'responsive-brix' ),
		'fa-trello'         => __( 'Trello', 'responsive-brix' ),
		'fa-tripadvisor'    => __( 'Trip Advisor', 'responsive-brix' ),
		'fa-tumblr'         => __( 'Tumblr', 'responsive-brix' ),
		'fa-twitch'         => __( 'Twitch', 'responsive-brix' ),
		'fa-twitter'        => __( 'Twitter', 'responsive-brix' ),
		'fa-viadeo'         => __( 'Viadeo', 'responsive-brix' ),
		'fa-vimeo-square'   => __( 'Vimeo', 'responsive-brix' ),
		'fa-wikipedia-w'    => __( 'Wikipedia', 'responsive-brix' ),
		'fa-windows'        => __( 'Windows', 'responsive-brix' ),
		'fa-wordpress'      => __( 'Wordpress', 'responsive-brix' ),
		'fa-xing'           => __( 'Xing', 'responsive-brix' ),
		'fa-y-combinator'   => __( 'Y Combinator', 'responsive-brix' ),
		'fa-yelp'           => __( 'Yelp', 'responsive-brix' ),
		'fa-youtube'        => __( 'Youtube', 'responsive-brix' ),
	) );
}
endif;