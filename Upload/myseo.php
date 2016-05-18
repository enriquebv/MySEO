<?php

define('IN_MYBB', 1);
require './global.php';
include_once './inc/plugins/myseo/core.php';
$core = new Core();

if (isset($_GET['actn'])) {
    if (empty($_GET['actn'])) {
        dieError();
    } elseif ($_GET['actn'] == 'sitemapThreads') {
        sitemapThreads();
    } else {
        dieError();
    }
} else {
    dieError();
}

function dieError()
{
    die('Ups.');
}

function sitemapThreads()
{
    global $core;
    header('Content-type: application/xml');

    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $page = $_GET['page'];
    } elseif (!isset($_GET['page'])) {
        $page = 1;
    }elseif (is_numeric($_GET['page']) == false) {
        dieError();
    }
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
    $sitemap .= $core->generateSitemapThreads($page);
    $sitemap .= '</urlset>';

    echo $sitemap;
}
