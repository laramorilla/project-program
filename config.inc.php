<?php 
/* 
    1 habilita la exportación automática de todos los post, menos los incluidos dentro de tag
    0 desabilita lo anterior y solo exporta lo que está incluido en tag
*/

$default_post_action = 1; 

/* 
    0 desahabilita la exportación
    1 habilita la exportación
*/

$export_category = 1;

$exclude_tag = "<!--post2pdf_exclude-->";
$export_tag = "<!--post2pdf_export-->";
$html_text = "convert this post to pdf.";
$html_post_code = "<span class=\"post2pdf_span\" style=\"border: 1px solid gray; width: 160px; text-align: left; \"><a href=\"##SITEURL##/wp-content/plugins/project-program/generate.php?post=##GLOBALID##\" rel=\"nofollow\"><img src=\"##SITEURL##/wp-content/plugins/project-program/icon/pdf.png\" width=\"16px\" height=\"16px\" />".$html_text."</a></span>";
$html_category_code = "&nbsp;<a href='##SITEURL##/wp-content/plugins/project-program/generate.php?category=##CATEGORYID##&amp;name=##CATEGORYNAME##' rel='nofollow' title='Export category ##CATEGORYNAME## as pdf'>(<img src='##SITEURL##/wp-content/plugins/project-program/icon/pdf.png' width='10px' height='10px'/>)</a>";

?>