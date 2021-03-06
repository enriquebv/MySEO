<?php

if (!defined('IN_MYBB')) {
    die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.');
}

global $plugins;
$plugins->add_hook('forumdisplay_start', 'myseo_forumdisplay');
$plugins->add_hook('index_start', 'myseo_index');
$plugins->add_hook('member_profile_start', 'myseo_memberprofile');
$plugins->add_hook('global_start', 'myseo_global');
$plugins->add_hook('parse_message_end', 'myseo_nofollow');
$plugins->add_hook('showthread_end', 'myseo_showthread');

global $core;
require_once '/myseo/core.php';
$core = new Core();

function myseo_info()
{
    global $lang, $core;

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
    global $core;

    $core->installSettings();
    rebuild_settings();
    $core->installTemplates();
}

function myseo_is_installed()
{
    global $core;

    return $core->isInstalled();
}

function myseo_activate()
{
    global $db;

    include MYBB_ROOT.'/inc/adminfunctions_templates.php';

    find_replace_templatesets('headerinclude', '#'.preg_quote('{$stylesheets}').'#i', "{\$stylesheets}\n\n<!-- Site optimized with MySEO -->\n<meta name=\"keywords\" content=\"{\$mybb->settings['palabrasClave']}\"/>\n<meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\"/>\n<meta content=\"{\$mybb->settings['bingYahoo_ver']}\" name=\"msvalidate.01\"/>\n<meta name=\"google-site-verification\" content=\"{\$mybb->settings['google_ver']}\" />\n<meta name=\"twitter:card\" content=\"summary\">\n<meta name=\"twitter:site\" content=\"{\$mybb->settings['sitioTwitter']}\">\n<link rel=\"publisher\" href=\"{\$mybb->settings['pagina_Google']}\"/>\n<meta property=\"article:publisher\" content=\"{\$mybb->settings['pagina_Facebook']}\" />\n<meta name=\"alexaVerifyID\" content=\"{\$mybb->settings['alexa_ver']}\"/>\n<meta name=\"p:domain_verify\" content=\"{\$mybb->settings['perfil_pinterest']}\"/>\n<!-- Site optimized with MySEO -->\n\n");
    find_replace_templatesets('index', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n{\$seo_index}\n<!-- Site optimized with MySEO -->\n\n");
    find_replace_templatesets('forumdisplay', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n<title>{\$foruminfo['name']} | {\$mybb->settings['bbname']}</title>\n{\$seo_forumdisplay}\n<meta property=\"og:description\" content=\"{\$foruminfo['description']}\"/>\n<meta property=\"og:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$foruminfo['name']} | {\$mybb->settings['bbname']}\">\n<meta name=\"twitter:description\" content=\"{\$foruminfo['description']}\">\n<!-- Site optimized with MySEO -->\n\n");
    find_replace_templatesets('member_profile', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n{\$seo_member}\n<meta property=\"og:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\" />\n<meta name=\"twitter:title\" content=\"{\$lang->profile} en {\$mybb->settings['bbname']}\">\n<!-- Site optimized with MySEO -->\n\n\n");
    find_replace_templatesets('showthread', '#'.preg_quote('<head>').'#i', "<head>\n\n<!-- Site optimized with MySEO -->\n{\$seo_thread}<!-- Site optimized with MySEO -->\n\n");
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
    find_replace_templatesets('showthread', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n{\$seo_thread}<!-- Site optimized with MySEO -->\n\n").'#i', '', 0);
    find_replace_templatesets('footer', '#'.preg_quote("\n\n<!-- Site optimized with MySEO -->\n{\$seo_footer}<!-- Site optimized with MySEO -->\n\n").'#i', '', 0);
}

function myseo_uninstall()
{
    global $core;
    $core->uninstallPlugin();
}

function myseo_forumdisplay()
{
    global $db, $mybb, $templates, $seo_forumdisplay;

    eval('$seo_forumdisplay = "'.$templates->get('seo_forumdisplay').'";');
}

function myseo_index()
{
    global $db, $mybb, $templates, $seo_index;

    eval('$seo_index = "'.$templates->get('seo_index').'";');
}

function myseo_memberprofile()
{
    global $db, $mybb, $templates, $seo_member;
    if ($mybb->settings['IndexFollow_usuarios'] == 0) {
        eval('$seo_member = "'.$templates->get('seo_member').'";');
    }
}

function myseo_global()
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

function myseo_showthread(){
    global $thread, $core, $templates, $db, $mybb, $seo_thread, $myseoMetaDescription;
    $myseoMetaDescription = $core->getMetaDescription($thread['tid']);
    eval('$seo_thread = "'.$templates->get('seo_thread').'";');
}
