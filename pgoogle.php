<?php
define ( 'IN_MYBB' , 1 );
require "./global.php" ;
global $settings, $mybb, $db;
$nombre_foro = $settings['bbname'];
$mini_descripcion = $settings['miniDescripcion'];
$titulo_foro = "$nombre_foro | $mini_descripcion";
$metadescripcion = $settings['meta_descripcion'];
$url_foro = $settings['bburl'];
if(strlen($titulo_foro)<65)
{$titulo_transformado = $titulo_foro;
}else{
$titulo_transformado = "".substr($titulo_foro,0,65)."...";}
if(strlen($metadescripcion)<158)
{$metadescripcion_transformada = $metadescripcion;
}else{
$metadescripcion_transformada = "".substr($metadescripcion,0,158)."...";}

echo "<html>
<head>
<meta content=\"noindex, nofollow\" name=\"robots\"/>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<style type=\"text/css\">
.texto{font-family:Arial;}
</style>
</head>
<body>
<input type=\"submit\" class=\"button\" onclick='location.reload(true); return false;' value=\"Recargar\">
<div style=\"background:#FFF;padding:5px;box-shadow:0px 0px 10px -4px #000;\">
<span class=\"texto\" style=\"color:#1A0DAB;font-size:16px;\">$titulo_transformado</span><br/>
<span class=\"texto\" style=\"color:#006621;font-size:12px;\">$url_foro</span><br/>
<span class=\"texto\" style=\"color:#545454;font-size:12px;\">$metadescripcion_transformada</span>
</div>
</body>
</html>
";
?>