# MySEO - SEO OnPage your MyBB forum

¿Estas buscando las **instrucciones en español**? [Entra aqui](https://github.com/BitLiberal/MySEO/blob/master/README.es.md)  

#### Index:
 1. [Description](#description)
 2. [How to install MySEO](#how-to-install-myseo)
    * [Install NoFollow System](#install-nofollow-system)
    * [Optimize code of templates](#optimize-code-of-the-templates)
 3. [How to uninstall MySEO](#how-to-unistall-myseo)
    * [Remove modified code of templates.](#remove-modified-code-of-the-templates)

## Description
MySEO is a plugin for MyBB forum system. It creates and installs HTML codes into your themes templates to improve internal seo (SEO OnPage) of your forum, and also the social media integration.
For example, adding meta keywords, meta descriptions, best titles, etc.

**Will convert things like this:**

![Bad SEO OnPage in the index](http://i.imgur.com/L2agGob.png)
![Bad SEO OnPage in the index](http://i.imgur.com/3i6UbnI.png) 

**On this:**

![Good SEO OnPage in the index](http://i.imgur.com/M2ajMql.png) 
![Good SEO OnPage in the index](http://i.imgur.com/LJkB0EG.png)

## How to install MySEO

#### Install NoFollow System
You might have wondered how to make the hordes of spammers stop taking profit of your forum posting links in posts, signatures, etc.  
There are many ways to ensure that weak point of MyBB, but one of the simplest is to apply the attribute [rel = "nofollow"](http://es.wikipedia.org/wiki/Nofollow) in any external link.  
To install the NoFollow system you'll have to edit MyBB core:

Open **/inc/class_parser.php** y search this code:
  ````php
    $name = preg_replace("#&amp;\#([0-9]+);#si", "&#$1;", $name); // Fix & but allow unicode
    $link = "<a href=\"$fullurl\" target=\"_blank\"{$nofollow}>$name</a>";
    return $link;
 ````
 
Replace it by this:
````php
/* MOD MySEO
    Original code:
    $name = preg_replace("#&amp;\#([0-9]+);#si", "&#$1;", $name); // Fix & but allow unicode
    $link = "<a href=\"$fullurl\" target=\"_blank\"{$nofollow}>$name</a>";
    return $link;
    */

    global $mybb, $settings;
    $urldelforo = $settings['bburl'];
    $buscar_procedencia = strpos($fullurl, $urldelforo);
    $name = preg_replace("#&amp;\#([0-9]+);#si", "&#$1;", $name);
    if ($buscar_procedencia === false) {
    $link = "<a rel=\"nofollow\" href=\"$fullurl\" target=\"_blank\"{$nofollow}>$name</a>";
    return $link;
    } else {
    $link = "<a href=\"$fullurl\" target=\"_blank\">$name</a>";
    return $link;
            }
    /* MOD MySEO */
````
If you go to your ACP > Plugins, you should see the word "Activated" in the plugin description.


#### Optimize code of templates
So that no confusion in your templates (eg title codes that already brings MyBB, if not removed may have duplications), follow these steps:
* In template *index*:
	* Remove code tags `<title> </title>` and their content (eg: `<title>{$mybb->settings['bbname']}</title>`).
* In template *forumdisplay*:
    * Remove code tags `<title> </title>` and their content (eg: `<title>{$mybb->settings['bbname']} - {$foruminfo['name']}</title>`).
* In template *showthread*:
	* Remove code tags `<title> </title>` and their content (eg: `<title>{$thread['subject']}</title>`).
* In template *member_profile*:
	* Remove code tags `<title> </title>` and their content (eg: `<title>{$mybb->settings['bbname']} - {$lang->profile}</title>`).

#### Install NoFollow System
(If you do not want to install these improvements, simply ignores these **instructions**).
MySEO brings standard configurations to improve and refine the use of [Google SEO Plugin](http://mods.mybb.com/view/google-seo).  
The improvements are:
 * Add the frequency change to sitemaps (a suggestion to Google to revisit your forum to see the new content, very well suited)
 * Add the URL's priority to sitemaps, **almost obligatory** by [various reasons](https://github.com/BitLiberal/MySEO/wiki/En-construccion---Coming-soon#under-construction)
 * Remove NoFollow system of Google SEO plugin (to not affect the MySEO the plugin).

To make these extras work, you must replace certain files from Google SEO, for that you need [download the **[Extras] Google Seo** folder](https://github.com/BitLiberal/MySEO/tree/master/%5BExtras%5D%20Google%20SEO), copy and paste files into the root folder of your forum MyBB. **Ready!**


## How to uninstall MySEO

#### Remove modified code of templates
For proper identification, ALL the codes entered by the plugin are surrounded by the comment `<!-- Site optimized with MySEO 1.0 --> `, to clear the codes (either to uninstall or modify the code) you just have to access the following templates and delete them, including html comments:
* forumdisplay
* footer
* index
* headerinclude
* member_profile


#### Notice

To facilitate the customization (and largely because I'm not a professional developer) if you modify the installed **standard** codes by the plugin MySEO, these will not be deleted, custom or standard.
Relax, as I explain [here](# remove-modified-code-of-templates), is very easy to remove the codes.