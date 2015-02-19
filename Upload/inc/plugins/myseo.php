<?php
if (!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// Hooks
global $plugins;
$plugins->add_hook("forumdisplay_start", "myseo_fd");
$plugins->add_hook("index_start", "myseo_i");
$plugins->add_hook("member_profile_start", "myseo_mp");
$plugins->add_hook("global_start", "myseo_ft");
$plugins->add_hook("parse_message_end", "myseo_nofollow");

// Cargar idiomas
function myseo_lang(){
    global $lang;
    $lang->load("myseo");
}

//Variables globales a utilizar en MySEO
global $mybb, $lang_docs;

if ($mybb->settings['bblanguage'] == "espanol") {
	$lang_docs = "es";
}else{
	$lang_docs = "en";
}
	

function myseo_info()
{
    // Crear variables a utilizar
    global $mybb, $plugins, $lang, $db, $lang_docs;
    
	myseo_lang();
    
	// Anchors para saber como instalar en ingles y español
    if ($lang_docs == "es") {
        $como_instalar   = "#como-instalar-myseo";
        $sistema_nofollow = "#instalar-sistema-nofollow";
		$instalar_extras_googleseo = "#install-extras-for-plugin-google-seo";
    } else {
        $como_instalar    = "#how-to-install-myseo";
        $sistema_nofollow = "#install-nofollow-system";
		$instalar_extras_googleseo = "#instalar-extras-para-el-plugin-google-seo";
    }

    
	// Enlace a ajustes y wiki (proximamente)
    $enlace_ajustes = "";
    $query          = $db->simple_select('settinggroups', '*', "name='myseo'");
    if ($db->num_rows($query)) {
        $enlace_ajustes = "<br/><img src=\"{$mybb->settings['bburl']}/inc/plugins/myseo/imagenes/ajustes.gif\"/> <a target=\"_blank\" href=\"index.php?module=config&action=change&search=myseo\">".$lang->boton_ajustes."</a></center>";
    } else {
        $enlace_ajustes = "<br/><img src=\"{$mybb->settings['bburl']}/inc/plugins/myseo/imagenes/instalacion.gif\"/>".$lang->aviso_instalacion_primero." <a target=\"_blank\" href=\"https://github.com/BitLiberal/MySEO/blob/master/README.".$lang_docs.".md".$como_instalar."\">".$lang->aviso_instalacion_segunda."</a>.</span>";
    }
    
    // Info del plugin
    return array(
        "name" => "MySEO",
        "description" => "<br/><div style=\"background:#FFF;padding:5px;border-radius:5px;\">".$lang->descripcion_suite."<br/>".$enlace_ajustes."</div><b style=\"font-size:12px;\">".$lang->Cafe."</b>
    <form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_blank\" style=\"display: inline;\">
<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
<input type=\"hidden\" name=\"hosted_button_id\" value=\"FEC8RBQ2DJUCW\">
<input type=\"hidden\" name=\"os0\" value=\"Donacion 1\">
<input type=\"hidden\" name=\"currency_code\" value=\"USD\">
<input type=\"hidden\" name=\"on0\" value=\"Donaciones\">
<input style=\"max-height: 20px; vertical-align: -5px; margin-left: 10px;\" type=\"image\" src=\"{$mybb->settings['bburl']}/inc/plugins/myseo/imagenes/donar.".$lang_docs.".gif\" border=\"0\" name=\"submit\" alt=\"Donar\">
<img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/es_ES/i/scr/pixel.gif\" width=\"1\" height=\"1\">
</form>",
        "website" => "https://github.com/BitLiberal/MySEO",
        "author" => "BitLiberal",
        "authorsite" => "https://github.com/BitLiberal",
        "version" => "1.1",
        "compatibility" => "18*",
        "guid" => ''
    );
}

// Procesos de instalacion
function myseo_install()
{
    global $mybb, $db, $lang, $lang_docs;

    // Grupo de ajustes para el social media
    $settings_group = array(
        'gid' => 'NULL',
        'name' => 'myseo',
        'title' => $lang->ajustes_seonpage,
        'description' => $lang->ajustes_seonpage_descripcion,
        'disporder' => '1',
        'isdefault' => 'no'
    );
    $gid = $db->insert_query('settinggroups', $settings_group);
	
	// Previsualizacion por iframe
    $setting = array(
        'sid' => 'NULL',
        'name' => 'previsualizacion',
        'title' => $lang->previsualizacion,
        'description' => $db->escape_string("".$lang->previsualizacion_descripcion_primera."".$lang->previsualizacion_descripcion_segunda."".$lang->previsualizacion_descripcion_tercera."
        <br/><iframe style=\"border: 0px none; height: 150px; width: 70%;\" id=\"preview_iframe\" src=\"../myseo.php?actn=preview\"></iframe>
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
        'optionscode' => $db->escape_string('php'),
        'value' => '',
        'disporder' => '0',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Ajuste de minidescripcion
    $setting = array(
        'sid' => 'NULL',
        'name' => 'miniDescripcion',
        'title' => $lang->minidescripcion,
        'description' => $lang->minidescripcion_descripcion,
        'optionscode' => 'textarea',
        'disporder' => '1',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Ajustes de metadescripcion general
    $setting = array(
        'sid' => 'NULL',
        'name' => 'meta_descripcion',
        'title' => $lang->metadescripcion,
        'description' => $lang->metadescripcion_descripcion,
        'optionscode' => 'textarea',
        'disporder' => '2',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Palabras clave principales
    $setting = array(
        'sid' => 'NULL',
        'name' => 'palabrasClave',
        'title' => $lang->palabrasClave,
        'description' => $lang->palabrasClave_descripcion,
        'optionscode' => 'text',
        'disporder' => '3',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Verificacion de Google Webmaster Tools
    $setting = array(
        'sid' => 'NULL',
        'name' => 'google_ver',
        'title' => $lang->google_ver,
        'description' => $lang->google_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >6NO94briBMDv6s_mJCx9lJpPYWfl$oXGmEBSGVX3PaY </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;google-site-verification&quot; content=&quot;6NO94briBMDv6s_mJCx9lJpPYWfl$oXGmEBSGVX3PaY&quot;/&gt; </span>',
        'optionscode' => 'text',
        'disporder' => '4',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Verificacion de Yahoo & Bing Webmaster tools
    $setting = array(
        'sid' => 'NULL',
        'name' => 'bingYahoo_ver',
        'title' => $lang->bingYahoo_ver,
        'description' => $lang->bingYahoo_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >11498CA0879048F6A573982A8F59D89 </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;msvalidate.01&quot; content=&quot;11498CA0879048F6A573982A8F59D89&quot;/&gt; </span>',
        'optionscode' => 'text',
        'disporder' => '5',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Verificacion de Alexa 
    $setting = array(
        'sid' => 'NULL',
        'name' => 'alexa_ver',
        'title' => $lang->alexa_ver,
        'description' => $lang->alexa_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >KzZWFeVVKe2I1saGWy-IPAKNiY1E </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;alexaVerifyID&quot; content=&quot;KzsaWFeVVKe2I1xGWy-IPAKNiY1E&quot;/&gt; </span>',
        'optionscode' => 'text',
        'disporder' => '6',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Ajustes para bloquear la indexacion de usuarios
    $setting = array(
        'sid' => 'NULL',
        'name' => 'IndexFollow_usuarios',
        'title' => $lang->IndexFollow_usuarios,
        'description' => $lang->IndexFollow_usuarios_descripcion,
        'optionscode' => 'onoff',
		'value' => '1',
        'disporder' => '7',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
	
	// Ajustes para incluir Google Analytics
    $setting = array(
        'sid' => 'NULL',
        'name' => 'idAnalytics',
        'title' => $lang->idAnalytics,
        'description' => $lang->idAnalytics_descripcion,
        'optionscode' => 'text',
        'disporder' => '9',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
	
	// Ajustes para incluir Google Analytics
    $setting = array(
        'sid' => 'NULL',
        'name' => 'activarNofollow',
        'title' => $lang->activarNofollow,
        'description' => $lang->activarNofollow_descripcion,
        'optionscode' => 'onoff',
		'value' => 1,
        'disporder' => '10',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
	
	$setting = array(
        'sid' => 'NULL',
        'name' => 'quitarNofollow',
        'title' => $lang->quitarNofollow,
        'description' => $lang->quitarNofollow_descripcion,
        'optionscode' => 'textarea',
		'value' => 'wikipedia.org\ngoogle.com',
        'disporder' => '11',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
	    
	// Anchors para saber como instalar en ingles y español
    if ($lang_docs == "es") {$instalar_extras_googleseo = "#instalar-extras-para-el-plugin-google-seo";} else {$instalar_extras_googleseo = "#install-extras-for-plugin-google-seo";}
    
	// Ajustes para cambio de frecuencia en los sitemaps
    $setting = array(
        'sid' => 'NULL',
        'name' => 'smChangeFreq',
        'title' => $lang->smChangeFreq,
        'description' => '<a href=\"https://github.com/BitLiberal/MySEO/blob/master/README.'.$lang_docs.'.md'.$instalar_extras_googleseo.'\">'.$lang->extras_googleseo_enlace.'</a> '.$lang->smChangeFreq_descripcion,
        'optionscode' => 'radio \nalways='.$lang->Siempre.' \nhourly='.$lang->Horario.' \ndaily='.$lang->Diario.' \nweekly='.$lang->Semanal.' \nmonthly='.$lang->Mensual.' \nyearly='.$lang->Anual.' \nnever='.$lang->Nunca.'',
        'disporder' => '8',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Ajustes para la prioridad en los sitemaps
    $setting = array(
        'sid' => 'NULL',
        'name' => 'smPriority',
        'title' => $lang->smPriority,
        'description' => '<a href=\"https://github.com/BitLiberal/MySEO/blob/master/README.'.$lang_docs.'.md'.$instalar_extras_googleseo.'\">'.$lang->extras_googleseo_enlace.'</a> '.$lang->smPriority_descripcion,
        'optionscode' => 'select \n0.9=90% \n0.8=80% \n0.7=70% \n0.6=60% \n0.5=50% \n0.4=40% \n0.3=30% \n0.2=20% \n0.1=10%',
        'disporder' => '8',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
	
    
   // Grupo de ajustes para el social media
    $settings_group = array(
        'gid' => 'NULL',
        'name' => 'myseosm',
        'title' => $lang->myseosm,
        'description' => $lang->myseosm_descripcion,
        'disporder' => '1',
        'isdefault' => 'no'
    );
    $gid = $db->insert_query('settinggroups', $settings_group);
    
    // Ajustes para pagina de Facebook
    $setting  = array(
        'sid' => 'NULL',
        'name' => 'pagina_Facebook',
        'title' => $lang->pagina_Facebook,
        'description' => $lang->pagina_Facebook_descripcion,
        'optionscode' => 'text',
        'disporder' => '1',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
	
	// Ajustes para el logo para facebook
	$setting  = array(
        'sid' => 'NULL',
        'name' => 'urlLogoFB',
        'title' => $lang->urlLogoFB,
        'description' => $lang->urlLogoFB_descripcion,
        'optionscode' => 'text',
        'disporder' => '2',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Ajustes para el Twitter del foro
    $setting = array(
        'sid' => 'NULL',
        'name' => 'sitioTwitter',
        'title' => $lang->sitioTwitter,
        'description' => $lang->sitioTwitter_descripcion,
        'optionscode' => 'text',
        'disporder' => '3',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
    // Ajustes para el logo para Twitter
    $setting = array(
        'sid' => 'NULL',
        'name' => 'urlLogoTW',
        'title' => $lang->urlLogoTW,
        'description' => $lang->urlLogoTW,
        'optionscode' => 'text',
        'disporder' => '6',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Ajustes para la pagina de Google+
    $setting = array(
        'sid' => 'NULL',
        'name' => 'pagina_Google',
        'title' => $lang->pagina_Google,
        'description' => $lang->pagina_Google_descripcion,
        'optionscode' => 'text',
        'disporder' => '7',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
    
	// Ajustes para verificar web en Pinterest
    $setting = array(
        'sid' => 'NULL',
        'name' => 'perfil_pinterest',
        'title' => $lang->perfil_pinterest,
        'description' => $lang->perfil_pinterest_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >8819237419234-h1j23k4h </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;p:domain_verify&quot; content=&quot;8819237419234-h1j23k4h&quot;/&gt; </span><br/>  ',
        'optionscode' => 'text',
        'disporder' => '8',
        'gid' => intval($gid)
    );
    $db->insert_query('settings', $setting);
 
    // No podia faltar
	rebuild_settings();
	
	// Plantilla global seo_forumdisplay
    $insertarray = array(
        "title" => "seo_forumdisplay",
        "template" => $db->escape_string("<meta content=\"index,follow\" name=\"robots\"/>
<meta property=\"og:type\" content=\"forum\"/>
<meta property=\"og:image\" content=\"{\$mybb->settings['urlLogoFB']}\"/>
<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">
<meta name=\"twitter:image\" content=\"{\$mybb->settings['urlLogoTW']}\">"),
        "sid" => -1,
        "dateline" => TIME_NOW
    );
	$db->insert_query("templates", $insertarray);
    
	// Plantilla global seo_index
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
    
	// Plantilla global seo_member
    $insertarray = array(
        "title" => "seo_member",
        "template" => $db->escape_string("<meta content=\"noindex,nofollow\" name=\"robots\"/>"),
        "sid" => -1,
        "dateline" => TIME_NOW
    );
    $db->insert_query("templates", $insertarray);
    
	// Plantilla global seo_footer
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

// Comprobar si MySEO esta instalado (por si las moscas)
function myseo_is_installed()
{
    global $db;
    $query = $db->simple_select('settinggroups', '*', "name='myseo'");
    if ($db->num_rows($query)) {
       return true;
    }
    return false;
}

// Proceso de activacion/instalacion del plugin
function myseo_activate()
{
    global $db;
    
    include MYBB_ROOT."/inc/adminfunctions_templates.php";
    
	// Añadir codigos necesarios
    find_replace_templatesets("headerinclude", "#".preg_quote("{\$stylesheets}")."#i", "{\$stylesheets}\n\n<!-- Site optimized with MySEO 1.0 -->\n<meta name=\"keywords\" content=\"{\$mybb->settings['palabrasClave']}\"/>\n<meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\"/>\n<meta content=\"{\$mybb->settings['bingYahoo_ver']}\" name=\"msvalidate.01\"/>\n<meta name=\"google-site-verification\" content=\"{\$mybb->settings['google_ver']}\" />\n<meta name=\"twitter:card\" content=\"summary\">\n<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">\n<link rel=\"publisher\" href=\"{\$mybb->settings['pagina_Google']}\"/>\n<meta property=\"article:publisher\" content=\"{\$mybb->settings['pagina_Facebook']}\" />\n<meta name=\"alexaVerifyID\" content=\"{\$mybb->settings['alexa_ver']}\"/>\n<meta name=\"p:domain_verify\" content=\"{\$mybb->settings['perfil_pinterest']}\"/>\n<!-- Site optimized with MySEO 1.0 -->\n\n");
    find_replace_templatesets("index", "#".preg_quote("<head>")."#i", "<head>\n\n<!-- Site optimized with MySEO 1.0 -->\n{\$seo_index}\n<!-- Site optimized with MySEO 1.0 -->\n\n");
    find_replace_templatesets("forumdisplay", "#".preg_quote("<head>")."#i", "<head>\n\n<!-- Site optimized with MySEO 1.0 -->\n<title>{\$foruminfo['name']} | {\$mybb->settings['bbname']}</title>\n{\$seo_forumdisplay}\n<meta property=\"og:description\" content=\"{\$foruminfo['description']}\"/>\n<meta property=\"og:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\">\n<meta name=\"twitter:description\" content=\"{\$foruminfo['description']}\">\n<!-- Site optimized with MySEO 1.0 -->\n\n");
    find_replace_templatesets("member_profile", "#".preg_quote("<head>")."#i", "<head>\n\n<!-- Site optimized with MySEO 1.0 -->\n{\$seo_member}\n<meta property=\"og:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO 1.0 -->\n\n\n");
    find_replace_templatesets("showthread", "#".preg_quote("<head>")."#i", "<head>\n\n<!-- Site optimized with MySEO 1.0 -->\n<title>{\$thread['subject']} | {\$mybb->settings['bbname']}</title>\n<meta content=\"index,follow\" name=\"robots\"/>\n<meta property=\"og:type\" content=\"article\"/>\n<meta property=\"og:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO 1.0 -->\n\n");
    find_replace_templatesets("footer", "#".preg_quote("{\$task_image}")."#i", "{\$task_image}\n\n<!-- Site optimized with MySEO 1.0 -->\n{\$seo_footer}<!-- Site optimized with MySEO 1.0 -->\n\n");
}

// Proceso de desactivacion del plugin
function myseo_deactivate()
{
    global $db;
    
    include MYBB_ROOT."/inc/adminfunctions_templates.php";
    
	// Añadir codigos (si son los de serie)
    find_replace_templatesets("headerinclude", "#".preg_quote("\n\n<!-- Site optimized with MySEO 1.0 -->\n<meta name=\"keywords\" content=\"{\$mybb->settings['palabrasClave']}\"/>\n<meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\"/>\n<meta content=\"{\$mybb->settings['bingYahoo_ver']}\" name=\"msvalidate.01\"/>\n<meta name=\"google-site-verification\" content=\"{\$mybb->settings['google_ver']}\" />\n<meta name=\"twitter:card\" content=\"summary\">\n<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">\n<link rel=\"publisher\" href=\"{\$mybb->settings['pagina_Google']}\"/>\n<meta property=\"article:publisher\" content=\"{\$mybb->settings['pagina_Facebook']}\" />\n<meta name=\"alexaVerifyID\" content=\"{\$mybb->settings['alexa_ver']}\"/>\n<meta name=\"p:domain_verify\" content=\"{\$mybb->settings['perfil_pinterest']}\"/>\n<!-- Site optimized with MySEO 1.0 -->\n\n")."#i", "", 0);
    find_replace_templatesets("index", "#".preg_quote("\n\n<!-- Site optimized with MySEO 1.0 -->\n{\$seo_index}\n<!-- Site optimized with MySEO 1.0 -->\n\n")."#i", "", 0);
    find_replace_templatesets("forumdisplay", "#".preg_quote("\n\n<!-- Site optimized with MySEO 1.0 -->\n<title>{\$foruminfo['name']} | {\$mybb->settings['bbname']}</title>\n{\$seo_forumdisplay}\n<meta property=\"og:description\" content=\"{\$foruminfo['description']}\"/>\n<meta property=\"og:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\">\n<meta name=\"twitter:description\" content=\"{\$foruminfo['description']}\">\n<!-- Site optimized with MySEO 1.0 -->\n\n")."#i", "", 0);
    find_replace_templatesets("member_profile", "#".preg_quote("\n\n<!-- Site optimized with MySEO 1.0 -->\n{\$seo_member}\n<meta property=\"og:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO 1.0 -->\n\n\n")."#i", "", 0);
    find_replace_templatesets("showthread", "#".preg_quote("\n\n<!-- Site optimized with MySEO 1.0 -->\n<title>{\$thread['subject']} | {\$mybb->settings['bbname']}</title>\n<meta content=\"index,follow\" name=\"robots\"/>\n<meta property=\"og:type\" content=\"article\"/>\n<meta property=\"og:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO 1.0 -->\n\n")."#i", "", 0);
    find_replace_templatesets("footer", "#".preg_quote("\n\n<!-- Site optimized with MySEO 1.0 -->\n{\$seo_footer}<!-- Site optimized with MySEO 1.0 -->\n\n")."#i", "", 0);
}

// Proceso de desinstalacion del plugin
function myseo_uninstall()
{
    global $db, $mybb;
    	
		// Elimina todos los ajustes
        $db->delete_query('settings', "name IN ('urlLogoTW', 'smPriority', 'smChangeFreq', 'IndexFollow_usuarios', 'miniDescripcion', 'eliminar_ajustes', 'meta_descripcion','palabrasClave','urlLogoFB','sitioTwitter','google_ver','bingYahoo_ver','pagina_Google','pagina_Facebook','eleccionAutor','alexa_ver','perfil_pinterest','previsualizacion','idAnalytics')");
        
		// Elimina todos los grupos de ajustes
		$db->delete_query('settinggroups', "name = 'myseops'");
        $db->delete_query('settinggroups', "name = 'myseo'");
		$db->delete_query('settinggroups', "name = 'myseosm'");
        
		// Elimina todas las plantillas globales
        $db->delete_query("templates", "title = 'seo_forumdisplay'");
        $db->delete_query("templates", "title = 'seo_index'");
        $db->delete_query("templates", "title = 'seo_member'");
        $db->delete_query("templates", "title = 'seo_footer'");
        
		// No podia faltar
		rebuild_settings();
}

// Para la plantilla global seo_forumdisplay
function myseo_fd()
{
    global $db, $mybb, $templates, $seo_forumdisplay;
    
    eval("\$seo_forumdisplay = \"".$templates->get("seo_forumdisplay")."\";");
}

// Para la plantilla global seo_index
function myseo_i()
{
    global $db, $mybb, $templates, $seo_index;
    
    eval("\$seo_index = \"".$templates->get("seo_index")."\";");
}

// Para la plantilla global seo_member
function myseo_mp()
{
    global $db, $mybb, $templates, $seo_member;
	if ($mybb->settings['IndexFollow_usuarios'] == 0){
    eval("\$seo_member = \"".$templates->get("seo_member")."\";");
	}
}

// Para la plantilla global seo_footer
function myseo_ft()
{
    global $db, $mybb, $templates, $seo_footer;
	if (strlen($mybb->settings['idAnalytics']) != 0){ 
		eval("\$seo_footer = \"".$templates->get("seo_footer")."\";");
	}
}

//Sistema NoFollow 
function myseo_nofollow($message){
	global $mybb;
	if($mybb->settings['activarNofollow'] == 1){
		$bburl_ignore = str_replace(array("http://", "https://"), '', $mybb->settings['bburl']);
		
		if($mybb->settings['quitarNofollow']){	
			$ignore = $mybb->settings['quitarNofollow'];
			$ignore = str_replace(array("\r\n", "\n", "\r"), ',', $ignore);
			$ignore = my_strtolower($ignore);	
			$ignore_list = explode(',', $ignore);
			array_push($ignore_list, $bburl_ignore);
		}else{
			$ignore_list = array($bburl_ignore);
		}
		
		return preg_replace(
			'%(<a\s*(?!.*\brel=)[^>]*)(href="https?://)((?!(?:(?:www\.)?'.implode('|(?:www\.)?', $ignore_list).'))[^"]+)"((?!.*\brel=)[^>]*)(?:[^>]*)>%',
			'$1$2$3"$4 rel="nofollow">',
		$message);
	}
}
?> 