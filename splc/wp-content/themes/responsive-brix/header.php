<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?> class="no-js">

<head>
<?php
// Fire the wp_head action required for hooking in scripts, styles, and other <head> tags.
wp_head();
?>
</head>

<body <?php hoot_attr( 'body' ); ?>>

	<div <?php hoot_attr( 'page-wrapper' ); ?>>

		<div class="skip-link">
			<a href="#content" class="screen-reader-text"><?php _e( 'Skip to content', 'responsive-brix' ); ?></a>
		</div><!-- .skip-link -->

		<?php
		// Template modification Hook
		do_action( 'hoot_template_site_start' );

		// Displays a friendly note to visitors using outdated browser (Internet Explorer 8 or less)
		hoot_update_browser();
		?>

		<?php get_template_part( 'template-parts/topbar' ); ?>

		<header <?php hoot_attr( 'header' ); ?>>
			<div class="grid">
				<div class="table grid-span-12">
					<?php
					// Display Branding
					hoot_header_branding();

					// Display Menu
					hoot_header_aside();
					?>
				</div>
			</div>
		</header><!-- #header -->

		<div <?php hoot_attr( 'main' ); ?>>
			<?php
			// Template modification Hook
			do_action( 'hoot_template_main_wrapper_start' );