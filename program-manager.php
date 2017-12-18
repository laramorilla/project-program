<?php
/*
Plugin Name: Program manager plugin
*/

require('XLSXReader.php');
require('pmanagerlib.php');

add_shortcode( 'write_long_program', 'write_program' );
add_action( 'wp_enqueue_scripts', 'my_plugin_register_scripts' );

function my_plugin_register_scripts(){
     wp_register_script('my-script',plugins_url( '/my-script.js', __FILE__ ), false, '1.0', 'all' );
     wp_register_style( 'my-style', plugins_url( '/my-style.css', __FILE__ ), false, '1.0', 'all' );
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
#Botón que recoge las cuentas de correo que quieran ser notificadas cuando un contenido de tipo programa se añada/modifique
	?>

	<iframe name="suscribirse" style="display:none;"></iframe>
	<form action="funcionmail.php" method="post" target="suscribirse">
    Introduce aquí tu email si quieres saber cuando se ha modificado el programa!:  <input type="text" name="email" /><br />
    <input type="submit" name="submit" value="¡Enviarme!" />
</form>


<!-- Boton subir archivos -->

<input type="text" name="upload_image" id="upload_image" value="" size='40' />
<input type="button" class='button-secondary' id="upload_image_button" value="Subir imagen" />
<?php



#funcion que incluye los plugin necesarios del core
function my_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', WP_PLUGIN_URL.'/scriptPrograma.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('my-upload');
}

function my_admin_styles() {
wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'program-manager') {
add_action('admin_print_scripts', 'my_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');
}

}
?>





