<?php

if (!defined('IN_MYBB')) {
    die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

global $plugins;
$plugins->add_hook('forumdisplay_start', 'myseo_fd');
$plugins->add_hook('index_start', 'myseo_i');
$plugins->add_hook('member_profile_start', 'myseo_mp');
$plugins->add_hook('global_start', 'myseo_ft');
$plugins->add_hook('parse_message_end', 'myseo_nofollow');

global $core;
require_once '/myseo/core.php';
$core = new Core();

function myseo_info()
{
    global $mybb, $plugins, $lang, $db, $core;
    $lang->load('myseo');

    return array(
        'name' => 'MySEO',
        'description' => $core->getDescription(),
        'website' => 'https://github.com/bit-dev/MySEO',
        'author' => 'BitDev',
        'authorsite' => 'https://github.com/bit-dev',
        'version' => '2.0',
        'compatibility' => '18*',
        'guid' => '',
    );
}

function myseo_install()
{
    global $mybb, $db, $lang, $core;

    $settings_group = array(
        'gid' => 'NULL',
        'name' => 'myseo',
        'title' => $lang->ajustes_seonpage,
        'description' => $lang->ajustes_seonpage_descripcion,
        'disporder' => '1',
        'isdefault' => 'no',
    );
    $gid = $db->insert_query('settinggroups', $settings_group);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'previsualizacion',
        'title' => $lang->previsualizacion,
        'description' => $db->escape_string(''.$lang->previsualizacion_descripcion_primera.''.$lang->previsualizacion_descripcion_segunda.''.$lang->previsualizacion_descripcion_tercera.'
        <br/><iframe style="border: 0px none; height: 150px; width: 70%;" id="preview_iframe" src="../myseo.php?actn=preview"></iframe>
        <script>
        function fs_do_preview(response)
        {
    iframe = $("preview_iframe");
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
        </script>'),
        'optionscode' => $db->escape_string('php'),
        'value' => '',
        'disporder' => '0',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'miniDescripcion',
        'title' => $lang->minidescripcion,
        'description' => $lang->minidescripcion_descripcion,
        'optionscode' => 'textarea',
        'disporder' => '1',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'meta_descripcion',
        'title' => $lang->metadescripcion,
        'description' => $lang->metadescripcion_descripcion,
        'optionscode' => 'textarea',
        'disporder' => '2',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'palabrasClave',
        'title' => $lang->palabrasClave,
        'description' => $lang->palabrasClave_descripcion,
        'optionscode' => 'text',
        'disporder' => '3',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'google_ver',
        'title' => $lang->google_ver,
        'description' => $lang->google_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >6NO94briBMDv6s_mJCx9lJpPYWfl$oXGmEBSGVX3PaY </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;google-site-verification&quot; content=&quot;6NO94briBMDv6s_mJCx9lJpPYWfl$oXGmEBSGVX3PaY&quot;/&gt; </span>',
        'optionscode' => 'text',
        'disporder' => '4',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'bingYahoo_ver',
        'title' => $lang->bingYahoo_ver,
        'description' => $lang->bingYahoo_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >11498CA0879048F6A573982A8F59D89 </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;msvalidate.01&quot; content=&quot;11498CA0879048F6A573982A8F59D89&quot;/&gt; </span>',
        'optionscode' => 'text',
        'disporder' => '5',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'alexa_ver',
        'title' => $lang->alexa_ver,
        'description' => $lang->alexa_ver_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >KzZWFeVVKe2I1saGWy-IPAKNiY1E </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;alexaVerifyID&quot; content=&quot;KzsaWFeVVKe2I1xGWy-IPAKNiY1E&quot;/&gt; </span>',
        'optionscode' => 'text',
        'disporder' => '6',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'IndexFollow_usuarios',
        'title' => $lang->IndexFollow_usuarios,
        'description' => $lang->IndexFollow_usuarios_descripcion,
        'optionscode' => 'onoff',
        'value' => '1',
        'disporder' => '7',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'idAnalytics',
        'title' => $lang->idAnalytics,
        'description' => $lang->idAnalytics_descripcion,
        'optionscode' => 'text',
        'disporder' => '9',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
            'sid' => 'NULL',
            'name' => 'smChangeFreq',
            'title' => $lang->smChangeFreq,
            'description' => $lang->smChangeFreq_descripcion,
            'optionscode' => 'radio \nalways='.$lang->Siempre.' \nhourly='.$lang->Horario.' \ndaily='.$lang->Diario.' \nweekly='.$lang->Semanal.' \nmonthly='.$lang->Mensual.' \nyearly='.$lang->Anual.' \nnever='.$lang->Nunca.'',
            'disporder' => '8',
            'gid' => intval($gid),
        );
    $db->insert_query('settings', $setting);

    $setting = array(
            'sid' => 'NULL',
            'name' => 'smPriority',
            'title' => $lang->smPriority,
            'description' => $lang->smPriority_descripcion,
            'optionscode' => 'select \n0.9=90% \n0.8=80% \n0.7=70% \n0.6=60% \n0.5=50% \n0.4=40% \n0.3=30% \n0.2=20% \n0.1=10%',
            'disporder' => '8',
            'gid' => intval($gid),
        );
    $db->insert_query('settings', $setting);

    $settings_group = array(
        'gid' => 'NULL',
        'name' => 'myseonf',
        'title' => $lang->settingsMySEONoFollow,
        'description' => $lang->settingsMySEONoFollowDescription,
        'disporder' => '1',
        'isdefault' => 'no',
    );
    $gid = $db->insert_query('settinggroups', $settings_group);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'activarNofollow',
        'title' => $lang->activarNofollow,
        'description' => $lang->activarNofollow_descripcion,
        'optionscode' => 'onoff',
        'value' => 1,
        'disporder' => '1',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'quitarNofollow',
        'title' => $lang->quitarNofollow,
        'description' => $lang->quitarNofollow_descripcion,
        'optionscode' => 'textarea',
        'value' => 'wikipedia.org\ngoogle.com',
        'disporder' => '2',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $settings_group = array(
        'gid' => 'NULL',
        'name' => 'myseosm',
        'title' => $lang->myseosm,
        'description' => $lang->myseosm_descripcion,
        'disporder' => '1',
        'isdefault' => 'no',
    );
    $gid = $db->insert_query('settinggroups', $settings_group);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'pagina_Facebook',
        'title' => $lang->pagina_Facebook,
        'description' => $lang->pagina_Facebook_descripcion,
        'optionscode' => 'text',
        'disporder' => '1',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'urlLogoFB',
        'title' => $lang->urlLogoFB,
        'description' => $lang->urlLogoFB_descripcion,
        'optionscode' => 'text',
        'disporder' => '2',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'sitioTwitter',
        'title' => $lang->sitioTwitter,
        'description' => $lang->sitioTwitter_descripcion,
        'optionscode' => 'text',
        'disporder' => '3',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'urlLogoTW',
        'title' => $lang->urlLogoTW,
        'description' => $lang->urlLogoTW,
        'optionscode' => 'text',
        'disporder' => '6',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'pagina_Google',
        'title' => $lang->pagina_Google,
        'description' => $lang->pagina_Google_descripcion,
        'optionscode' => 'text',
        'disporder' => '7',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    $setting = array(
        'sid' => 'NULL',
        'name' => 'perfil_pinterest',
        'title' => $lang->perfil_pinterest,
        'description' => $lang->perfil_pinterest_descripcion.'<br/><br/> <b style="font-size:16px;color:#27AE60;">'.$lang->bien.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >8819237419234-h1j23k4h </span><br/> <b style="font-size:16px;color:#c0392b;">'.$lang->mal.'</b> <span style="background:#FFF;padding:0px 5px;border:1px solid #D9D9D9;border-radius:5px;font-family:Courier;" >&lt;meta name=&quot;p:domain_verify&quot; content=&quot;8819237419234-h1j23k4h&quot;/&gt; </span><br/>  ',
        'optionscode' => 'text',
        'disporder' => '8',
        'gid' => intval($gid),
    );
    $db->insert_query('settings', $setting);

    rebuild_settings();

    $insertarray = array(
        'title' => 'seo_forumdisplay',
        'template' => $db->escape_string("<meta content=\"index,follow\" name=\"robots\"/>
<meta property=\"og:type\" content=\"forum\"/>
<meta property=\"og:image\" content=\"{\$mybb->settings['urlLogoFB']}\"/>
<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">
<meta name=\"twitter:image\" content=\"{\$mybb->settings['urlLogoTW']}\">"),
        'sid' => -1,
        'dateline' => TIME_NOW,
    );
    $db->insert_query('templates', $insertarray);

    $insertarray = array(
        'title' => 'seo_index',
        'template' => $db->escape_string("<title>{\$mybb->settings['bbname']} | {\$mybb->settings['miniDescripcion']}</title>
<meta name=\"description\" content=\"{\$mybb->settings['meta_descripcion']}\"/>
<meta content=\"index,follow\" name=\"robots\"/>
<meta property=\"og:type\" content=\"forum\"/>
<meta property=\"og:description\" content=\"{\$mybb->settings['meta_descripcion']}\"/>
<meta property='og:image' content=\"{\$mybb->settings['urlLogoFB']}\"/>
<meta property=\"og:title\" content=\"{\$mybb->settings['bbname']} | {\$mybb->settings['miniDescripcion']}\" />
<meta name=\"twitter:title\" content=\"{\$mybb->settings['bbname']} | {\$mybb->settings['miniDescripcion']}\">
<meta name=\"twitter:description\" content=\"{\$mybb->settings['meta_descripcion']}\">
<meta name=\"twitter:image\" content=\"{\$mybb->settings['urlLogoTW']}\">"),
        'sid' => -1,
        'dateline' => TIME_NOW,
    );
    $db->insert_query('templates', $insertarray);

    $insertarray = array(
        'title' => 'seo_member',
        'template' => $db->escape_string('<meta content="noindex,nofollow" name="robots"/>'),
        'sid' => -1,
        'dateline' => TIME_NOW,
    );
    $db->insert_query('templates', $insertarray);

    $insertarray = array(
        'title' => 'seo_footer',
        'template' => $db->escape_string("<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '{\$mybb->settings['idAnalytics']}', 'auto');
  ga('send', 'pageview');

</script>"),
        'sid' => -1,
        'dateline' => TIME_NOW,
    );
    $db->insert_query('templates', $insertarray);
}

function myseo_is_installed()
{
    global $db;
    $query = $db->simple_select('settinggroups', '*', "name='myseo'");
    if ($db->num_rows($query)) {
        return true;
    }

    return false;
}

function myseo_activate()
{
    global $db;

    include MYBB_ROOT.'/inc/adminfunctions_templates.php';

    find_replace_templatesets('headerinclude', '#'.preg_quote('{$stylesheets}').'#i', "{\$stylesheets}\n\n<!-- Site optimized with MySEO -->\n<meta name=\"keywords\" content=\"{\$mybb->settings['palabrasClave']}\"/>\n<meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\"/>\n<meta content=\"{\$mybb->settings['bingYahoo_ver']}\" name=\"msvalidate.01\"/>\n<meta name=\"google-site-verification\" content=\"{\$mybb->settings['google_ver']}\" />\n<meta name=\"twitter:card\" content=\"summary\">\n<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">\n<link rel=\"publisher\" href=\"{\$mybb->settings['pagina_Google']}\"/>\n<meta property=\"article:publisher\" content=\"{\$mybb->settings['pagina_Facebook']}\" />\n<meta name=\"alexaVerifyID\" content=\"{\$mybb->settings['alexa_ver']}\"/>\n<meta name=\"p:domain_verify\" content=\"{\$mybb->settings['perfil_pinterest']}\"/>\n<!-- Site optimized with MySEO -->\n\n");
    find_replace_templatesets('index', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n{\$seo_index}\n<!-- Site optimized with MySEO -->\n\n");
    find_replace_templatesets('forumdisplay', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n<title>{\$foruminfo['name']} | {\$mybb->settings['bbname']}</title>\n{\$seo_forumdisplay}\n<meta property=\"og:description\" content=\"{\$foruminfo['description']}\"/>\n<meta property=\"og:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\">\n<meta name=\"twitter:description\" content=\"{\$foruminfo['description']}\">\n<!-- Site optimized with MySEO -->\n\n");
    find_replace_templatesets('member_profile', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n{\$seo_member}\n<meta property=\"og:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO -->\n\n\n");
    find_replace_templatesets('showthread', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n<title>{\$thread['subject']} | {\$mybb->settings['bbname']}</title>\n<meta content=\"index,follow\" name=\"robots\"/>\n<meta property=\"og:type\" content=\"article\"/>\n<meta property=\"og:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO -->\n\n");
    find_replace_templatesets('footer', '#'.preg_quote('{$task_image}').'#i', "{\$task_image}\n\n<!-- Site optimized with MySEO -->\n{\$seo_footer}<!-- Site optimized with MySEO -->\n\n");
}

function myseo_deactivate()
{
    global $db;

    include MYBB_ROOT.'/inc/adminfunctions_templates.php';
    find_replace_templatesets('headerinclude', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n<meta name=\"keywords\" content=\"{\$mybb->settings['palabrasClave']}\"/>\n<meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\"/>\n<meta content=\"{\$mybb->settings['bingYahoo_ver']}\" name=\"msvalidate.01\"/>\n<meta name=\"google-site-verification\" content=\"{\$mybb->settings['google_ver']}\" />\n<meta name=\"twitter:card\" content=\"summary\">\n<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">\n<link rel=\"publisher\" href=\"{\$mybb->settings['pagina_Google']}\"/>\n<meta property=\"article:publisher\" content=\"{\$mybb->settings['pagina_Facebook']}\" />\n<meta name=\"alexaVerifyID\" content=\"{\$mybb->settings['alexa_ver']}\"/>\n<meta name=\"p:domain_verify\" content=\"{\$mybb->settings['perfil_pinterest']}\"/>\n<!-- Site optimized with MySEO -->\n\n").'#i', '', 0);
    find_replace_templatesets('index', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n{\$seo_index}\n<!-- Site optimized with MySEO -->\n\n").'#i', '', 0);
    find_replace_templatesets('forumdisplay', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n<title>{\$foruminfo['name']} | {\$mybb->settings['bbname']}</title>\n{\$seo_forumdisplay}\n<meta property=\"og:description\" content=\"{\$foruminfo['description']}\"/>\n<meta property=\"og:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\">\n<meta name=\"twitter:description\" content=\"{\$foruminfo['description']}\">\n<!-- Site optimized with MySEO -->\n\n").'#i', '', 0);
    find_replace_templatesets('member_profile', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n{\$seo_member}\n<meta property=\"og:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO -->\n\n\n").'#i', '', 0);
    find_replace_templatesets('showthread', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n<title>{\$thread['subject']} | {\$mybb->settings['bbname']}</title>\n<meta content=\"index,follow\" name=\"robots\"/>\n<meta property=\"og:type\" content=\"article\"/>\n<meta property=\"og:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$thread['subject']} | {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO -->\n\n").'#i', '', 0);
    find_replace_templatesets('footer', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n{\$seo_footer}<!-- Site optimized with MySEO -->\n\n").'#i', '', 0);
}

function myseo_uninstall()
{
    global $db, $mybb;

    $db->delete_query('settings', "name IN ('urlLogoTW', 'smPriority', 'smChangeFreq', 'IndexFollow_usuarios', 'miniDescripcion', 'eliminar_ajustes', 'meta_descripcion','palabrasClave','urlLogoFB','sitioTwitter','google_ver','bingYahoo_ver','pagina_Google','pagina_Facebook','eleccionAutor','alexa_ver','perfil_pinterest','previsualizacion','idAnalytics')");

    $db->delete_query('settinggroups', "name = 'myseo'");
    $db->delete_query('settinggroups', "name = 'myseosm'");
    $db->delete_query('settinggroups', "name = 'myseonf'");

    $db->delete_query('templates', "title = 'seo_forumdisplay'");
    $db->delete_query('templates', "title = 'seo_index'");
    $db->delete_query('templates', "title = 'seo_member'");
    $db->delete_query('templates', "title = 'seo_footer'");

    rebuild_settings();
}

function myseo_fd()
{
    global $db, $mybb, $templates, $seo_forumdisplay;

    eval('$seo_forumdisplay = "'.$templates->get('seo_forumdisplay').'";');
}

function myseo_i()
{
    global $db, $mybb, $templates, $seo_index;

    eval('$seo_index = "'.$templates->get('seo_index').'";');
}

function myseo_mp()
{
    global $db, $mybb, $templates, $seo_member;
    if ($mybb->settings['IndexFollow_usuarios'] == 0) {
        eval('$seo_member = "'.$templates->get('seo_member').'";');
    }
}

function myseo_ft()
{
    global $db, $mybb, $templates, $seo_footer;
    if (strlen($mybb->settings['idAnalytics']) != 0) {
        eval('$seo_footer = "'.$templates->get('seo_footer').'";');
    }
}

function myseo_nofollow($message)
{
    global $mybb;
    if ($mybb->settings['activarNofollow'] == 1) {
        $bburl_ignore = str_replace(array('http://', 'https://'), '', $mybb->settings['bburl']);

        if ($mybb->settings['quitarNofollow']) {
            $ignore = $mybb->settings['quitarNofollow'];
            $ignore = str_replace(array("\r\n", "\n", "\r"), ',', $ignore);
            $ignore = my_strtolower($ignore);
            $ignore_list = explode(',', $ignore);
            array_push($ignore_list, $bburl_ignore);
        } else {
            $ignore_list = array($bburl_ignore);
        }

        return preg_replace(
            '%(<a\s*(?!.*\brel=)[^>]*)(href="https?://)((?!(?:(?:www\.)?'.implode('|(?:www\.)?', $ignore_list).'))[^"]+)"((?!.*\brel=)[^>]*)(?:[^>]*)>%',
            '$1$2$3"$4 rel="nofollow">',
        $message);
    }
}
