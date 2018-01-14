<?php
/*
Plugin Name: Program manager plugin
*/

require('XLSXReader.php');
require('pmanagerlib.php');
require('DOCXReader.php');

/*Inicio funcion exportar a pdf*/
require_once("pdf_class.php");

function post_to_pdf($content){
    global $wp_query;
    include("config.inc.php");
    if(!preg_match($exclude_tag,$content))
    {
        $siteurl = get_option("siteurl");
        $id = $wp_query->post->ID;
        $temp = preg_replace("[##SITEURL##]",$siteurl, $html_post_code);
        $temp = preg_replace("[##GLOBALID##]",$id ,$temp);
        $check = preg_match($export_tag, $content);
        if(!$default_post_action)
        {
            if($check)
            {
                $content = preg_replace($export_tag, $temp, $content);
            }
        }
        else
        {
            if(!$check)
            {
                $content = $content." ".$temp;
            }
            else
            {
                $content = preg_replace($export_tag, $temp, $content);
            }
        }
    }
    return $content;
}

function archive_to_pdf($content, $category=null){
    include("config.inc.php");
    global $wpdb;
    if ($category && $export_category) {
        $siteurl = get_option("siteurl");
        $temp = preg_replace("##CATEGORYID##", $category->cat_ID, $html_category_code);
        $temp = preg_replace("##CATEGORYNAME##", $category->cat_name, $temp);
        $temp = preg_replace("##SITEURL##",$siteurl,$temp);
        return $content." ".$temp;
    }else return $content;
}

add_filter("the_content", "post_to_pdf");
add_filter("list_cats", "archive_to_pdf",10,2);

/*Fin funcion exportar a pdf*/

add_shortcode( 'write_long_program', 'write_program' );
add_action( 'wp_enqueue_scripts', 'my_plugin_register_scripts' );


function read_program(){
    $docx = new DOCXReader;
    $docx->readFile('./wp-content/plugins/program-manager/programme-main-v9.docx');
}

function my_plugin_register_scripts(){
     wp_register_script('my-script',plugins_url( '/my-script.js', __FILE__ ), false, '1.0', 'all' );
     wp_register_style( 'my-style', plugins_url( '/my-style.css', __FILE__ ), false, '1.0', 'all' );
}

function write_program(){
	date_default_timezone_set('UTC');
	wp_enqueue_style( 'my-style' );
	wp_enqueue_script( 'my-script' );

	$xlsx = new XLSXReader('./wp-content/plugins/project-program/programme-main-v9.xlsx');
  $xlsxPRE = new XLSXReader('./wp-content/plugins/project-program/programme-pre-v8.xlsx');

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
?>
	<iframe name="suscribirse" style="display:none;"></iframe>
	<form action="funcionmail.php" method="post" target="suscribirse">
    Introduce aquí tu email si quieres saber cuando se ha modificado el programa!:  <input type="text" name="email" /><br />
    <input type="submit" name="submit" value="¡Enviarme!" />
</form>
	<?php


}
?>





