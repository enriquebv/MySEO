<?php
/*
*
*	MySEO v1.1 2015
*	Plugin for MyBB by BitLiberal
*	
*/
define("IN_MYBB", 1);
require "./global.php";


//Ejecutor
if(isset($_GET['actn'])){
	if(empty($_GET['actn'])){
		diepls();
	}elseif($_GET['actn'] == 'preview'){
		preview();
	}else{
		diepls();
	}
}else{
	diepls();
}



function diepls(){
	die("Ups.");
}



//Preview of Google
function preview(){
	global $mybb, $db;
	
	$nombre_foro = $mybb->settings['bbname'];
	$mini_descripcion = $mybb->settings['miniDescripcion'];
	$metadescripcion = $mybb->settings['meta_descripcion'];
	$url_foro = $mybb->settings['bburl'];

	if ($mybb->settings['bblanguage'] == "espanol"){
		if (strlen($mini_descripcion) == 0){$mini_descripcion = "Mini descripcion del foro, maximo de 65 caracteres";}
		if (strlen($metadescripcion) == 0){$metadescripcion = "Descripcion con una extension de 158 caracteres, que solo se mostrara en la URL principal de tu foro. Intenta que sea lo maximo descriptiva posible.";}
		$boton = "Recargar";
	}else{
		if (strlen($mini_descripcion) == 0){$mini_descripcion = "Small description of forum, maximum 65 characters";}
		if (strlen($metadescripcion) == 0){$metadescripcion = "Description with an maximum length of 158 characters, which only will be displayed with the main URL of your forum in Google. Try to be as descriptive as possible maximum.";}
		$boton = "Refresh";
	}

	$titulo_foro = "$nombre_foro | $mini_descripcion";

	if(strlen($titulo_foro)<65){
		$titulo_transformado = $titulo_foro;
	}else{
		$titulo_transformado = "".substr($titulo_foro,0,65)."...";
	}

	if(strlen($metadescripcion)<158){
		$metadescripcion_transformada = $metadescripcion;
	}else{
		$metadescripcion_transformada = "".substr($metadescripcion,0,158)."...";
	}

	echo "<html>
		<head>
			<meta content=\"noindex, nofollow\" name=\"robots\"/>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
			<style type=\"text/css\">
				.texto{font-family:Arial;}
			</style>
		</head>
		<body>
			<input type=\"submit\" class=\"button\" onclick='location.reload(true); return false;' value=\"$boton\">
			<div style=\"background:#FFF;padding:5px;box-shadow:0px 0px 10px -4px #000;\">
				<span class=\"texto\" style=\"color:#1A0DAB;font-size:16px;\">$titulo_transformado</span><br/>
				<span class=\"texto\" style=\"color:#006621;font-size:12px;\">$url_foro</span><br/>
				<span class=\"texto\" style=\"color:#545454;font-size:12px;\">$metadescripcion_transformada</span>
			</div>
		</body>
	</html>
	";
}

?>