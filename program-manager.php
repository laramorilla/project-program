<?php
/*
Plugin Name: Program manager plugin
*/

require('XLSXReader.php');
require('pmanagerlib.php');

add_shortcode( 'write_long_program', 'write_program' );
add_action( 'wp_enqueue_scripts', 'my_plugin_register_scripts' );
add_shortcode("screenshot", "wps_screenshot");

function my_plugin_register_scripts() {
     wp_register_script('my-script',plugins_url( '/my-script.js', __FILE__ ), false, '1.0', 'all' );
     wp_register_style( 'my-style', plugins_url( '/my-style.css', __FILE__ ), false, '1.0', 'all' );
}

/**
 * Método que permite sacar una captura de la página y pasarla a formato imagen.
 * Este método se combinará más adelante para pasar esta foto a un pdf.
 */

function wps_screenshot($atts, $content = null) {
    
    extract(shortcode_atts(array(
        
        "screenshot" => 'http://s.wordpress.com/mshots/v1/',
        
        "url" => 'http://',
        
        "alt" => 'screenshot',
        
        "width" => '400',
        
        "height" => '300'
        
    ), $atts));
    
    return $screen = '<img src="' . $screenshot . '' . urlencode($url) . '?w=' . $width . '&h=' . $height . '" alt="' . $alt . '"/>';
    
}

function write_program(){
	date_default_timezone_set('UTC');
	wp_enqueue_style( 'my-style' );
	wp_enqueue_script( 'my-script' );

	$xlsx = new XLSXReader('./wp-content/plugins/program-manager/programme-main-v9.xlsx');
  $xlsxPRE = new XLSXReader('./wp-content/plugins/program-manager/programme-pre-v8.xlsx');

	$days = array(
    0 => "Monday",
    1 => "Tuesday",
		2 => "Wednesday",
		3 => "Thursday",
		4 => "Friday",
	);

	echo "<div class=\"w3-bar w3-black\">";
  $j = 0;
	foreach($days as $day) {
    if($j==0){
      echo  "<button class=\"w3-bar-item w3-button\" style=\"background:#000\" onclick=\"openTab('".$day."')\">".$day."</button>";
    }else{
      echo  "<button class=\"w3-bar-item w3-button\" onclick=\"openTab('".$day."')\">".$day."</button>";
    }
    $j++;

	}
	$j = 0;

	foreach($days as $day) {
    #Print tabular program
    if($j==0){
		      echo "<div id=\"". $day ."\" class=\"tab\">";
	  }else{
			     echo "<div id=\"". $day ."\" class=\"tab\" style=\"display:none\">";
		}

    #process Data from file
    if($day == "Monday" || $day == "Tuesday"){
      $daySheet = $xlsxPRE->getSheet($day);
      processPreDay($daySheet->getData());

    }else{
      $daySheet = $xlsx->getSheet($day);
      processDay($daySheet->getData());

    }
		echo '</div>';
		$j++;
    }

}
