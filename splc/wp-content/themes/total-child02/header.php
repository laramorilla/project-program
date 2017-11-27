<?php
/**
 * The header for our theme.
 *
 * @package Total
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link type="text/css" rel="stylesheet" href="<?php echo esc_url( home_url( '/' ).'wp-content/themes/total-child02/clock_assets/flipclock.css');?>" />
<?php wp_head(); ?>
<script>
var aCountdown = {};
var countdownLegend = '';
aCountdown['Deadline for Tools papers'] = 1493208000;
aCountdown['Deadline for Doctoral papers'] = 1495540800;
aCountdown['Deadline for Workshops'] = 1495713600;
//aCountdown['End of Early Registration!'] = 1498255200;
aCountdown['End of Early Registration!'] = 1499774399;
aCountdown['Countdown for SPLC 2017!'] = 1506340800;


var countdownLegend = '';
var today = (new Date().getTime())/1000;
var min = 8506327400;
for (var key in aCountdown){        
	if (aCountdown[key] > today){
 		if (min > (aCountdown[key] - today)){
			min = aCountdown[key] - today;
			countdownLegend = key; 
		}
	}
}
</script>
</head>

<body <?php body_class(); ?>>
<div id="ht-page">
	<header id="ht-masthead" class="ht-site-header">
		<div class="ht-container ht-clearfix">
			<div id="ht-site-branding">
				<?php 
				if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) :
					the_custom_logo();
				else : 
					if ( is_front_page() ) : ?>
						<h1 class="ht-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="ht-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php endif; ?>
					<p class="ht-site-description"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'description' ); ?></a></p>
				<?php endif; ?>
			</div><!-- .site-branding -->
<div id="clock-description" class="clock-title"><a href="http://congreso.us.es/splc2017/important-dates/"></a></div><div class="clock-builder-output"></div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo esc_url( home_url( '/' ).'wp-content/themes/total-child02/clock_assets/flipclock.js');?>"></script>
<style text="text/css">body .flip-clock-wrapper ul li a div div.inn, body .flip-clock-small-wrapper ul li a div div.inn { color: #CCCCCC; background-color: #333333; } body .flip-clock-dot, body .flip-clock-small-wrapper .flip-clock-dot { background: #323434; } body .flip-clock-wrapper .flip-clock-meridium a, body .flip-clock-small-wrapper .flip-clock-meridium a { color: #323434; }</style>
<script type="text/javascript">
$(function(){
document.getElementById("clock-description").innerHTML = countdownLegend;
	FlipClock.Lang.Custom = { days:'Days', hours:'Hours', minutes:'Minutes', seconds:'Seconds' };
	var opts = {
		clockFace: 'DailyCounter',
		countdown: true,
		language: 'Custom',
		showSeconds: false
	};  
	opts.classes = {
		active: 'flip-clock-active',
		before: 'flip-clock-before',
		divider: 'flip-clock-divider',
		dot: 'flip-clock-dot',
		label: 'flip-clock-label',
		flip: 'flip',
		play: 'play',
		wrapper: 'flip-clock-small-wrapper'
	};  
	//25/03/2017 11:59:59 UTC (24/03/2017-23:59:59 AOT). Actualizado el 18 de Marzo para research papers. http://www.unixtimestamp.com/index.php
	var countdown = 1;
        if (countdownLegend.localeCompare("")){
		countdown = aCountdown[countdownLegend]- ((new Date().getTime())/1000);;
	}
       
	countdown = Math.max(1, countdown);
	$('.clock-builder-output').FlipClock(countdown, opts);
});
</script>
			<nav id="ht-site-navigation" class="ht-main-navigation">
				<div class="toggle-bar"><span></span></div>
				<?php 
				wp_nav_menu( array( 
					'theme_location' => 'primary', 
					'container_class' => 'ht-menu ht-clearfix' ,
					'menu_class' => 'ht-clearfix',
					'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				) ); 
				?>
			</nav><!-- #ht-site-navigation -->
		</div>
	</header><!-- #ht-masthead -->

	<div id="ht-content" class="ht-site-content ht-clearfix">