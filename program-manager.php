<?php
/*
Plugin Name: Program manager plugin
Plugin Repository: https://github.com/laramorilla/project-program/
Description: Plugin para mostrar el programa de las jornadas de EGC
Version: 2.0
Authors: José Manuel Luque Mendoza, José Manuel Lara Morilla, Felix Gomez Rodriguez, Felix Blanco Castaño, Samuel Gonzalez Zuluaga
*/

//Función que añade una página de menú de administrador al plugin

function programManager_plugin_menu(){
	
	add_menu_page('Ajustes plugin program-manager',  						//Titulo de la pagina
					'program-manager',										//Titulo del menu
					'Administrator',											//Rol que puede acceder
					'program-manager-content-settings',						//Id de la pagina de opciones
					'program_manager_content_page_settings',				//Funcion que pinta la pagina de configuracion
					'dashicons-admin-generic');								//Icono del menu
					
			
}

add_action('admin_menu','programManager_plugin_menu');

/*
* Función que pinta la página de configuración del plugin
*/
function program_manager_content_page_settings(){
?>
	<div class="wrap">
		<h2>Configuración plugin Program-Manager</h2>
		<form method="POST" action="options.php">
			<?php 
				settings_fields('program-manager-settings-group');
				do_settings_sections( 'program-manager-settings-group' ); 
			?>
			<label>Longitud máxima de caracteres del post:&nbsp;</label>
			<input 	type="text" 
					name="program_manager_max_length_value" 
					id="program_manager_max_length_value" 
					value="<?php echo get_option('program_manager_max_length_value'); ?>" />
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}

/*
* Función que registra las opciones del formulario en una lista blanca para que puedan ser guardadas
*/
function program_manager_max_length_content_settings(){
	register_setting('program-manager-max-length-content-settings-group',
					 'program-manager-max_length_value',
					 'intval');
}
add_action('admin_init','program_manager_max_length_content_settings');

/*
* Función que devuelve el contenido de un post limitado a la longitud configurada en la página de opciones 
*/
function program_manager_max_length_action($content){
	global $post;
	//Comprobamos que sea un post de tipo programa y no estemos visualizando su vista individual
	if ($post && $post->post_type=='post' && !is_singular('post') && get_post_field('post_content',$ID ) === '[write_long_program]'){
		//Recuperamos el valor del parámetro program_manager_max_length_value
		$len = get_option('program_manager_max_length_value');
		$content = mb_substr($content, 3, $len);
	}

	return $content;
}
add_filter('the_content','program_manager_max_length_action');




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
    Introduce aquí tu email:  <input type="text" name="email" /><br />
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





