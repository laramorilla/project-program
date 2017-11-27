<?php
/**
 * Premium Theme Options displayed in admin
 *
 * @package hoot
 * @subpackage responsive-brix
 * @since responsive-brix 1.0
 * @return array Return Hoot Options array to be merged to the original Options array
 */

$hoot_options_premium = array();
$imagepath =  trailingslashit( HOOT_THEMEURI ) . 'admin/images/';
$hoot_cta_top = '<a class="button button-primary button-buy-premium" href="https://wphoot.com/themes/responsive-brix/" target="_blank">' . __( 'Click here to know more', 'responsive-brix' ) . '</a>';
$hoot_cta_top = $hoot_cta = '<a class="button button-primary button-buy-premium" href="https://wphoot.com/themes/responsive-brix/" target="_blank">' . __( 'Buy Responsive Brix Premium', 'responsive-brix' ) . '</a>';
$hoot_cta_demo = '<a class="button button-secondary button-view-demo" href="https://demo.wphoot.com/responsive-brix/" target="_blank">' . __( 'View Demo Site', 'responsive-brix' ) . '</a>';
$hoot_cta_url = 'https://wphoot.com/themes/responsive-brix/';
$hoot_cta_demo_url = 'https://demo.wphoot.com/responsive-brix/';

$hoot_intro = array(
	'name' => __('Upgrade to <span>Responsive Brix <strong>Premium</strong></span>', 'responsive-brix'),
	'desc' => __("If you've enjoyed using Responsive Brix, you're going to love <strong>Responsive Brix Premium</strong>.<br>It's a robust upgrade to Responsive Brix that gives you many useful features.", 'responsive-brix'),
	);

$hoot_options_premium[] = array(
	'name' => __('Complete <br />Style <strong>Customization</strong>', 'responsive-brix'),
	'desc' => __('Different looks and styles. Choose from an extensive range of customization features in Responsive Brix Premium to setup your own unique front page. Give youe site the personality it deserves.', 'responsive-brix'),
	'img' => $imagepath . 'premium-style.jpg',
	'style' => 'hero-bottom',
	);

$hoot_options_premium[] = array(
	'name' => __('Unlimited Colors', 'responsive-brix'),
	'desc' => __('Responsive Brix Premium lets you select unlimited colors for different sections of your site. Select pre-existing backgrounds for site sections like header, footer etc. or upload your own background images/patterns.', 'responsive-brix'),
	'img' => $imagepath . 'premium-colors.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Fonts and <span>Typography Control</span>', 'responsive-brix'),
	'desc' => __('Assign different typography (fonts, text size, font color) to menu, topbar, logo, content headings, sidebar, footer etc.', 'responsive-brix'),
	'img' => $imagepath . 'premium-typography.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Unlimites Sliders, Unlimites Slides', 'responsive-brix'),
	'desc' => __('Responsive Brix Premium allows you to create unlimited sliders with as many slides as you need.<hr>You can use these sliders on your Homepage widgetized template, or add them anywhere using shortcodes - like in your Posts, Sidebars or Footer.', 'responsive-brix'),
	'img' => $imagepath . 'premium-sliders.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('600+ Google Fonts', 'responsive-brix'),
	'desc' => __("With the integrated Google Fonts library, you can find the fonts that match your site's personality, and there's over 600 options to choose from.", 'responsive-brix'),
	'img' => $imagepath . 'premium-googlefonts.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Shortcodes with <span>Easy Generator</span>', 'responsive-brix'),
	'desc' => __('Enjoy the flexibility of using shortcodes throughout your site with Responsive Brix premium. These shortcodes were specially designed for this theme and are very well integrated into the code to reduce loading times, thereby maximizing performance!<hr>Use shortcodes to insert buttons, sliders, tabs, toggles, columns, breaks, icons, lists, and a lot more design and layout modules.<hr>The intuitive Shortcode Generator has been built right into the Edit screen, so you dont have to hunt for shortcode syntax.', 'responsive-brix'),
	'img' => $imagepath . 'premium-shortcodes.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Image Carousels', 'responsive-brix'),
	'desc' => __('Add carousels to your post, in your sidebar, on your frontpage or in your footer. A simple drag and drop interface allows you to easily create and manage carousels.', 'responsive-brix'),
	'img' => $imagepath . 'premium-carousels.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __("Floating <br /><span>'Sticky' Header</span> &amp; <span>'Goto Top'</span> button (optional)", 'responsive-brix'),
	'desc' => __("The floating header follows the user down your page as they scroll, which means they never have to scroll back up to access your navigation menu, improving user experience.<hr>Or, use the 'Goto Top' button appears on the screen when users scroll down your page, giving them a quick way to go back to the top of the page.", 'responsive-brix'),
	'img' => $imagepath . 'premium-header-top.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('3 Blog Layouts (including pinterest <span>type mosaic)</span>', 'responsive-brix'),
	'desc' => __('Responsive Brix Premium gives you the option to display your post archives in 3 different layouts including a mosaic type layout similar to pinterest.', 'responsive-brix'),
	'img' => $imagepath . 'premium-blogstyles.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Custom Widgets', 'responsive-brix'),
	'desc' => __('Custom widgets crafted and designed specifically for Responsive Brix Premium Theme to give you the flexibility of adding stylized content.', 'responsive-brix'),
	'img' => $imagepath . 'premium-widgets.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Menu Icons', 'responsive-brix'),
	'desc' => __('Select from over 500 icons for your main navigation menu links.', 'responsive-brix'),
	'img' => $imagepath . 'premium-menuicons.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Premium Background Patterns (CC0)', 'responsive-brix'),
	'desc' => __('Responsive Brix Premium comes with many additional premium background patterns. You can always upload your own background image/pattern to match your site design.', 'responsive-brix'),
	'img' => $imagepath . 'premium-backgrounds.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Automatic Image Lightbox and <span>WordPress Gallery</span>', 'responsive-brix'),
	'desc' => __('Automatically open image links on your site with the integrates lightbox in Responsive Brix Premium.<hr>Automatically convert standard WordPress galleries to beautiful lightbox gallery slider.', 'responsive-brix'),
	'img' => $imagepath . 'premium-lightbox.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Developers <br />love {LESS}', 'responsive-brix'),
	'desc' => __('CSS is passe. Developers love the modularity and ease of using LESS, which is why Responsive Brix Premium comes with properly organized LESS files for the main stylesheet.', 'responsive-brix'),
	'img' => $imagepath . 'premium-lesscss.jpg',
	);

$hoot_options_premium[] = array(
	'name' => __('Easy Import/Export', 'responsive-brix'),
	'desc' => __('Moving to a new host? Or applying a new child theme? Easily import/export your customizer settings with just a few clicks - right from the backend.', 'responsive-brix'),
	'img' => $imagepath . 'premium-import-export.jpg',
	);

$hoot_options_premium[] = array(
	'style' => 'aside',
	'blocks' => array(
		array(
			'name' => __('Custom Javascript &amp; <span>Google Analytics</span>', 'responsive-brix'),
			'desc' => __("Easily insert any javascript snippet to your header without modifying the code files. This helps in adding scripts for Google Analytics, Adsense or any other custom code.", 'responsive-brix'),
			'img' => $imagepath . 'premium-customjs.jpg',
			),
		array(
			'name' => __('Continued <br />Updates', 'responsive-brix'),
			'desc' => __("You will help support the continued development of Responsive Brix - ensuring it works with future versions of WordPress for years to come.", 'responsive-brix'),
			'img' => $imagepath . 'premium-updates.jpg',
			),
		),
	);

$hoot_options_premium[] = array(
	'name' => __('Premium <br />Priority Support', 'responsive-brix'),
	'desc' => __("Need help setting up Responsive Brix? Upgrading to Responsive Brix Premium gives you prioritized support. We have a growing support team ready to help you with your questions.<hr>Need small modifications? If you are not a developer yourself, you can count on our support staff to help you with CSS snippets to get the look you're after.", 'responsive-brix'),
	'img' => $imagepath . 'premium-support.jpg',
	);