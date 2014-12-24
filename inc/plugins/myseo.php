<?php
if(!defined("IN_MYBB")){
die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");}

global $plugins;
$plugins->add_hook("global_start", "myseo_lang");
$plugins->add_hook("forumdisplay_start", "myseo_fd");
$plugins->add_hook("index_start", "myseo_i");
$plugins->add_hook("member_profile_start", "myseo_mp");
$plugins->add_hook("global_start", "myseo_ft");

function myseo_lang(){
    global $lang;
    $lang->load("myseo");
}

function myseo_info(){
    global $mybb, $plugins, $lang, $db;
	$lang->load("myseo");
	
	$url_foro = $mybb->settings['bburl'];
	
	
	$enlace_ajustes = "";
	$query = $db->simple_select('settinggroups', '*', "name='myseo'");
	if ($db->num_rows($query)){
	$enlace_ajustes = "<br/><img src=\"$url_foro/inc/plugins/myseo/imagenes/ajustes.gif\"/> <a href=\"index.php?module=config&action=change&search=myseo\">".$lang->boton_ajustes."</a></center>";
	}else{
	$enlace_ajustes = "<br/><img src=\"$url_foro/inc/plugins/myseo/imagenes/instalacion.gif\"/>".$lang->aviso_instalacion_primero." <a target=\"_blank\" href=\"$url_foro/inc/plugins/myseo/doc.".$lang->idioma_docs.".html#instalacion\">".$lang->aviso_instalacion_segunda."</a>.</span>";
	}
	
	$archivo_cp = file_get_contents(MYBB_ROOT."/inc/class_parser.php");
	$archivo_formateado = nl2br($archivo_cp);
	$cadena_buscada = "MySEO";
	if (strstr($archivo_formateado, $cadena_buscada)){
	$comprobado = "<br/><span style=\"color:green\"><img src=\"$url_foro/inc/plugins/myseo/imagenes/protegido.gif\"/> ".$lang->sistema_nofollow_activado."</span>";
	}else{
	$comprobado = "<br/><span style=\"color:red\"><img src=\"$url_foro/inc/plugins/myseo/imagenes/desprotegido.gif\"/> ".$lang->sistema_nofollow_noactivado_primero."</span> <a href=\"$url_foro/inc/plugins/myseo/doc.".$lang->idioma_docs.".html#nofollow\" target=\"_blank\">".$lang->sistema_nofollow_noactivado_segundo."</a>";
	}

	
	return array(
		"name" 		  		=> "MySEO",
		"description" 		=> "<br/><div style=\"background:#FFF;padding:5px;border-radius:5px;\">".$lang->descripcion_suite."<br/>".$enlace_ajustes."".$comprobado."".$actualizacion_ver."</div><center><b style=\"font-size:16px;\">".$lang->Cafe."</b>
	<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_blank\">
<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
<input type=\"hidden\" name=\"hosted_button_id\" value=\"FEC8RBQ2DJUCW\">
<table>
<tr style=\"border: 0px none;\"><td style=\"border: 0px none;\"><input type=\"hidden\" name=\"on0\" value=\"Donaciones\"><td style=\"border: 0px none;\"><select name=\"os0\">
	<option value=\"Donacion 1\">5,00</option>
	<option value=\"Donacion 2\">10,00</option>
	<option value=\"Donacion 3\">15,00</option>
	<option value=\"Donacion 4\">20,00</option>
</select> <b>$</b></td><td style=\"border: 0px none;\"><input type=\"hidden\" name=\"currency_code\" value=\"USD\">
<input type=\"image\" src=\"$url_foro/inc/plugins/myseo/imagenes/donar.".$lang->idioma_docs.".gif\" border=\"0\" name=\"submit\" alt=\"Donar\">
<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/es_ES/i/scr/pixel.gif\" width=\"1\" height=\"1\">
</td></tr>
</table>
</form>
</center>",
		"website" 	  		=> "",
		"author" 			=> "BitLiberal",
		"authorsite"  		=> "",
		"version"		    => "1.0",
		"compatibility" 	=> "18*",
		"guid"        		=> ''
	);
}

function myseo_install()
{
    global $settings, $mybb, $db, $lang;
	
if($db->field_exists("myseosis", "users"))
	{
	$db->write_query("ALTER TABLE ".TABLE_PREFIX."users DROP myseosis"); 
	}

    $settings_group = array(
        'gid'          => 'NULL',
        'name'         => 'myseo',
        'title'        => $lang->ajustes_seonpage,
        'description'  => $lang->ajustes_seonpage_descripcion,
        'disporder'    => '0',
        'isdefault'    => 'no'
    );
    $gid = $db->insert_query('settinggroups', $settings_group);
    
	
	global $settings, $mybb, $db;
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'previsualizacion',
        'title'        => $lang->previsualizacion,
        'description'  => $db->escape_string("".$lang->previsualizacion_descripcion_primera."".$lang->previsualizacion_descripcion_segunda."".$lang->previsualizacion_descripcion_tercera."
		<br/><iframe style=\"border: 0px none; height: 150px; width: 70%;\" id=\"preview_iframe\" src=\"../pgoogle.php\"></iframe>
		<script>
		function fs_do_preview(response)
		{
	iframe = $(\"preview_iframe\");
	var iframeDoc;
	if (iframe.contentDocument) {
		iframeDoc = iframe.contentDocument;
	}
	else if (iframe.contentWindow) {
		iframeDoc = iframe.contentWindow.document;
	}
	else if (window.frames[iframe.name]) {
		iframeDoc = window.frames[iframe.name].document;
	}
	if (iframeDoc) {
		iframeDoc.open();
		iframeDoc.write(response.responseText);
		iframeDoc.close();
	}
	return false;
		}
		</script>"),
        'optionscode'  => $db->escape_string('php'),
        'value'        => '',
        'disporder'    => '0',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'miniDescripcion',
        'title'        => $lang->minidescripcion,
        'description'  => $lang->minidescripcion_descripcion,
        'optionscode'  => 'textarea',
        'value'        => $lang->minidescripcion_predefinido,
        'disporder'    => '1',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'meta_descripcion',
        'title'        => $lang->metadescripcion,
        'description'  => $lang->metadescripcion_descripcion,
        'optionscode'  => 'textarea',
        'value'        => $lang->metadescripcion_predefinido,
        'disporder'    => '2',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	    $setting = array(
        'sid'          => 'NULL',
        'name'         => 'palabrasClave',
        'title'        => $lang->palabrasClave,
        'description'  => $lang->palabrasClave_descripcion,
        'optionscode'  => 'text',
        'value'        => $lang->palabrasClave_predefinido,
        'disporder'    => '3',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
		$setting = array(
        'sid'          => 'NULL',
        'name'         => 'google_ver',
        'title'        => $lang->google_ver,
        'description'  => $lang->google_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >6NO94briBMDv6s_mJCx9lJpPYWfl$oXGmEBSGVX3PaY </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;google-site-verification&quot; content=&quot;6NO94briBMDv6s_mJCx9lJpPYWfl$oXGmEBSGVX3PaY&quot;/&gt; </span>',
        'optionscode'  => 'text',
        'value'        => '',
        'disporder'    => '4',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'bingYahoo_ver',
        'title'        => $lang->bingYahoo_ver,
        'description'  => $lang->bingYahoo_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >11498CA0879048F6A573982A8F59D89 </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;msvalidate.01&quot; content=&quot;11498CA0879048F6A573982A8F59D89&quot;/&gt; </span>',
        'optionscode'  => 'text',
        'value'        => '',
        'disporder'    => '5',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'alexa_ver',
        'title'        => $lang->alexa_ver,
        'description'  => $lang->alexa_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >KzZWFeVVKe2I1saGWy-IPAKNiY1E </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;alexaVerifyID&quot; content=&quot;KzsaWFeVVKe2I1xGWy-IPAKNiY1E&quot;/&gt; </span>',
        'optionscode'  => 'text',
        'value'        => '',
        'disporder'    => '6',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'IndexFollow_usuarios',
        'title'        => $lang->IndexFollow_usuarios,
        'description'  => $lang->IndexFollow_usuarios_descripcion,
        'optionscode' => 'radio \nnoindex,nofollow='.$lang->No.' \nindex,follow='.$lang->Si.'',
        'value'        => 'noindex,nofollow',
        'disporder'    => '7',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
		$setting = array(
        'sid'          => 'NULL',
        'name'         => 'smChangeFreq',
        'title'        => $lang->smChangeFreq,
        'description'  => $lang->smChangeFreq_descripcion,
        'optionscode' => 'radio \nalways='.$lang->Siempre.' \nhourly='.$lang->Horario.' \ndaily='.$lang->Diario.' \nweekly='.$lang->Semanal.' \nmonthly='.$lang->Mensual.' \nyearly='.$lang->Anual.' \nnever='.$lang->Nunca.'',
        'value'        => 'hourly',
        'disporder'    => '8',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'smPriority',
        'title'        => $lang->smPriority,
        'description'  => $lang->smPriority_descripcion,
        'optionscode' => 'select \n0.9=90% \n0.8=80% \n0.7=70% \n0.6=60% \n0.5=50% \n0.4=40% \n0.3=30% \n0.2=20% \n0.1=10%',
        'value'        => '0.6',
        'disporder'    => '8',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'idAnalytics',
        'title'        => $lang->idAnalytics,
        'description'  => $lang->idAnalytics_descripcion,
        'optionscode' => 'text',
        'value'        => 'UA-XXXXXXXX-X',
        'disporder'    => '9',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	

	 // ------------------ SOCIAL MEDIA INICIO ---------------- //
	$settings_group = array(
        'gid'          => 'NULL',
        'name'         => 'myseosm',
        'title'        => $lang->myseosm,
        'description'  => $lang->myseosm_descripcion,
        'disporder'    => '1',
        'isdefault'    => 'no'
    );
   
    $gid = $db->insert_query('settinggroups', $settings_group);
    
		
		$nombrebb = $mybb->settings['bbname'];
		$setting = array(
        'sid'          => 'NULL',
        'name'         => 'pagina_Facebook',
        'title'        => $lang->pagina_Facebook,
        'description'  => $lang->pagina_Facebook_descripcion,
        'optionscode' => 'text',
        'value'        => 'http://facebook.com/'.$nombrebb.'',
        'disporder'    => '1',
        'gid'          => intval( $gid )
    );
	$db->insert_query( 'settings', $setting );
		
		$url_foro = $mybb->settings['bburl'];
		$setting = array(
        'sid'          => 'NULL',
        'name'         => 'urlLogoFB',
        'title'        => $lang->urlLogoFB,
        'description'  => $lang->urlLogoFB_descripcion,
        'optionscode'  => 'text',
        'value'        => $url_foro.'/logofacebook.png',
        'disporder'    => '2',
        'gid'          => intval( $gid )
    );
	 $db->insert_query( 'settings', $setting );
	
		$setting = array(
        'sid'          => 'NULL',
        'name'         => 'sitioTwitter',
        'title'        => $lang->sitioTwitter,
        'description'  => $lang->sitioTwitter_descripcion,
        'optionscode'  => 'text',
        'value'        => '@'.$nombrebb.'',
        'disporder'    => '3',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );


	
		 	    $setting = array(
        'sid'          => 'NULL',
        'name'         => 'urlLogoTW',
        'title'        => $lang->urlLogoTW,
        'description'  => $lang->urlLogoTW,
        'optionscode'  => 'text',
        'value'        => $url_foro.'/logotwitter.png',
        'disporder'    => '6',
        'gid'          => intval( $gid )
    );
	 $db->insert_query( 'settings', $setting );
	 
	 	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'pagina_Google',
        'title'        => $lang->pagina_Google,
        'description'  => $lang->pagina_Google_descripcion,
        'optionscode' => 'text',
        'value'        => 'https://plus.google.com/+'.$nombrebb.'',
        'disporder'    => '7',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );
	
	$setting = array(
        'sid'          => 'NULL',
        'name'         => 'perfil_pinterest',
        'title'        => $lang->perfil_pinterest,
        'description'  => $lang->perfil_pinterest_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >8819237419234-h1j23k4h </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;p:domain_verify&quot; content=&quot;8819237419234-h1j23k4h&quot;/&gt; </span><br/>  ',
        'optionscode' => 'text',
        'value'        => '',
        'disporder'    => '8',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

	// ------------------ SOCIAL MEDIA FIN ---------------------------- //
	
	$db->write_query("ALTER TABLE ".TABLE_PREFIX."users ADD myseosis int NOT NULL default 0");

	rebuild_settings();
	$insertarray = array(
		"title" => "seo_foros",
		"template" => $db->escape_string("<meta content=\"index,follow\" name=\"robots\"/>
<meta property=\"og:type\" content=\"forum\"/>
<meta property=\"og:image\" content=\"{\$mybb->settings['urlLogoFB']}\"/>
<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">
<meta name=\"twitter:image\" content=\"{\$mybb->settings['urlLogoTW']}\">"),
		"sid" => -1,
		"dateline" => TIME_NOW
	);
	$db->insert_query("templates", $insertarray);
	$insertarray = array(
		"title" => "seo_index",
		"template" => $db->escape_string("<title>{\$mybb->settings['bbname']} | {\$mybb->settings['miniDescripcion']}</title>
<meta name=\"description\" content=\"{\$mybb->settings['meta_descripcion']}\"/>
<meta content=\"index,follow\" name=\"robots\"/>
<meta property=\"og:type\" content=\"forum\"/>
<meta property=\"og:description\" content=\"{\$mybb->settings['meta_descripcion']}\"/>
<meta property='og:image' content=\"{\$mybb->settings['urlLogoFB']}\"/>
<meta property=\"og:title\" content=\"{\$mybb->settings['bbname']} | {\$mybb->settings['miniDescripcion']}\" />
<meta name=\"twitter:title\" content=\"{\$mybb->settings['bbname']} | {\$mybb->settings['miniDescripcion']}\">
<meta name=\"twitter:description\" content=\"{\$mybb->settings['meta_descripcion']}\">
<meta name=\"twitter:image\" content=\"{\$mybb->settings['urlLogoTW']}\">"),
		"sid" => -1,
		"dateline" => TIME_NOW
	);
	$db->insert_query("templates", $insertarray);
	$insertarray = array(
		"title" => "seo_miembro",
		"template" => $db->escape_string("<meta content=\"{\$mybb->settings['IndexFollow_usuarios']}\" name=\"robots\"/>"),
		"sid" => -1,
		"dateline" => TIME_NOW
	);
	$db->insert_query("templates", $insertarray);
	$insertarray = array(
		"title" => "seo_footer",
		"template" => $db->escape_string("<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '{\$mybb->settings['idAnalytics']}', 'auto');
  ga('send', 'pageview');

</script>"),
		"sid" => -1,
		"dateline" => TIME_NOW
	);
	$db->insert_query("templates", $insertarray);


}

function myseo_is_installed()
{
	global $db;
	
	if($db->field_exists("myseosis", "users"))
	{
		return true;
	}
	
	return false;
}

function myseo_activate()
{
	global $db;
	
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	
	find_replace_templatesets("headerinclude", "#".preg_quote("{\$stylesheets}")."#i", "{\$stylesheets}\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n<meta name=\"keywords\" content=\"{\$mybb->settings['palabrasClave']}\"/>\r\n<meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\"/>\r\n<meta content=\"{\$mybb->settings['bingYahoo_ver']}\" name=\"msvalidate.01\"/>\r\n<meta name=\"google-site-verification\" content=\"{\$mybb->settings['google_ver']}\" />\r\n<meta name=\"twitter:card\" content=\"summary\">\r\n<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">\r\n<link rel=\"publisher\" href=\"{\$mybb->settings['pagina_Google']}\"/>\r\n<meta property=\"article:publisher\" content=\"{\$mybb->settings['pagina_Facebook']}\" />\r\n<meta name=\"alexaVerifyID\" content=\"{\$mybb->settings['alexa_ver']}\"/>\r\n<meta name=\"p:domain_verify\" content=\"{\$mybb->settings['perfil_pinterest']}\"/>\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n");
	find_replace_templatesets("index", "#".preg_quote("<head>")."#i", "<head>\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n{\$seo_index}\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n");
	find_replace_templatesets("forumdisplay", "#".preg_quote("<head>")."#i", "<head>\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n<title>{\$foruminfo['name']} | {\$mybb->settings['bbname']}</title>\r\n{\$seo_foros}\r\n<meta property=\"og:description\" content=\"{\$foruminfo['description']}\"/>\r\n<meta property=\"og:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\" />\r\n<meta name=\"twitter:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\">\r\n<meta name=\"twitter:description\" content=\"{\$foruminfo['description']}\">\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n");
	find_replace_templatesets("member_profile", "#".preg_quote("<head>")."#i", "<head>\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n{\$seo_miembro}\r\n<meta property=\"og:title\" content=\"{\$lang->profile} | {\$mybb->settings['bbname']}\" />\r\n<meta name=\"twitter:title\" content=\"{\$lang->profile} | {\$mybb->settings['bbname']}\">\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n\r\n");
	find_replace_templatesets("showthread", "#".preg_quote("<head>")."#i", "<head>\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n<title>{\$thread['subject']} | {\$mybb->settings['bbname']}</title>\r\n<meta content=\"index,follow\" name=\"robots\"/>\r\n<meta property=\"og:type\" content=\"article\"/>\r\n<meta property=\"og:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\" />\r\n<meta name=\"twitter:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\">\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n");
	find_replace_templatesets("footer", "#".preg_quote("{\$task_image}")."#i", "{\$task_image}\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n{\$seo_footer}<!-- Site optimized with MySEO 1.0 -->\r\n\r\n");
	}

function myseo_deactivate()
{
    global $db;
	
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	
	find_replace_templatesets("headerinclude", "#".preg_quote("\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n<meta name=\"keywords\" content=\"{\$mybb->settings['palabrasClave']}\"/>\r\n<meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\"/>\r\n<meta content=\"{\$mybb->settings['bingYahoo_ver']}\" name=\"msvalidate.01\"/>\r\n<meta name=\"google-site-verification\" content=\"{\$mybb->settings['google_ver']}\" />\r\n<meta name=\"twitter:card\" content=\"summary\">\r\n<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">\r\n<link rel=\"publisher\" href=\"{\$mybb->settings['pagina_Google']}\"/>\r\n<meta property=\"article:publisher\" content=\"{\$mybb->settings['pagina_Facebook']}\" />\r\n<meta name=\"alexaVerifyID\" content=\"{\$mybb->settings['alexa_ver']}\"/>\r\n<meta name=\"p:domain_verify\" content=\"{\$mybb->settings['perfil_pinterest']}\"/>\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n")."#i", "", 0);
	find_replace_templatesets("index", "#".preg_quote("\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n{\$seo_index}\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n")."#i", "", 0);
	find_replace_templatesets("forumdisplay", "#".preg_quote("\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n<title>{\$foruminfo['name']} | {\$mybb->settings['bbname']}</title>\r\n{\$seo_foros}\r\n<meta property=\"og:description\" content=\"{\$foruminfo['description']}\"/>\r\n<meta property=\"og:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\" />\r\n<meta name=\"twitter:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\">\r\n<meta name=\"twitter:description\" content=\"{\$foruminfo['description']}\">\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n")."#i", "", 0);
	find_replace_templatesets("member_profile", "#".preg_quote("\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n{\$seo_miembro}\r\n<meta property=\"og:title\" content=\"{\$lang->profile} | {\$mybb->settings['bbname']}\" />\r\n<meta name=\"twitter:title\" content=\"{\$lang->profile} | {\$mybb->settings['bbname']}\">\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n\r\n")."#i", "", 0);
	find_replace_templatesets("showthread", "#".preg_quote("\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n<title>{\$thread['subject']} | {\$mybb->settings['bbname']}</title>\r\n<meta content=\"index,follow\" name=\"robots\"/>\r\n<meta property=\"og:type\" content=\"article\"/>\r\n<meta property=\"og:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\" />\r\n<meta name=\"twitter:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\">\r\n<!-- Site optimized with MySEO 1.0 -->\r\n\r\n")."#i", "", 0);
	find_replace_templatesets("footer", "#".preg_quote("\r\n\r\n<!-- Site optimized with MySEO 1.0 -->\r\n{\$seo_footer}<!-- Site optimized with MySEO 1.0 -->\r\n\r\n")."#i", "", 0);
	}
function myseo_uninstall()
{
	global $db;
	
	if($db->field_exists("myseosis", "users"))
	{
		$db->write_query("ALTER TABLE ".TABLE_PREFIX."users DROP myseosis"); 
	}
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='myseo'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='myseosm'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='meta_descripcion'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='palabrasClave'");	
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='urlLogoFB'");	
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='urlLogoTW'");	
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='sitioTwitter'");		
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='google_ver'");	
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='bingYahoo_ver'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='miniDescripcion'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='IndexFollow_usuarios'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='pagina_Google'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='pagina_Facebook'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='eleccionAutor'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='alexa_ver'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='perfil_pinterest'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='previsualizacion'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='idAnalytics'");
	$db->delete_query("templates", "title = 'seo_foros'");
	$db->delete_query("templates", "title = 'seo_index'");
	$db->delete_query("templates", "title = 'seo_miembro'");
	$db->delete_query("templates", "title = 'seo_footer'");
	rebuild_settings();
}
function myseo_fd()
{
	global $db, $mybb, $templates, $seo_foros;
	
	eval("\$seo_foros = \"".$templates->get("seo_foros")."\";");
}
function myseo_i()
{
	global $db, $mybb, $templates, $seo_index;
	
	eval("\$seo_index = \"".$templates->get("seo_index")."\";");
}
function myseo_mp()
{
	global $db, $mybb, $templates, $seo_miembro;
	
	eval("\$seo_miembro = \"".$templates->get("seo_miembro")."\";");
}
function myseo_ft()
{
	global $db, $mybb, $templates, $seo_footer;
	
	eval("\$seo_footer = \"".$templates->get("seo_footer")."\";");
}
?>