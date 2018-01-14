<?php




$usuariosSuscritos = array();

static function enviarCorreo($post_ID)  {
 if(get_post_field('post_content',$ID ) === '[write_long_program]'){   
   
  
   array_push($usuariosSuscritos, $_POST['email']);
    
	mail($usuariosSuscritos,"modificaciones programa SPLC",'modificaciones en el post de programa SPLC');

    return $post_ID;
  }
}
add_action('publish_post', 'enviarCorreo');
?>