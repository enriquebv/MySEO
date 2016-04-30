# MySEO - SEO nas páginas de seu fórum MyBB

¿Estas buscando las **instrucciones en español**? [Entra aqui](https://github.com/BitLiberal/MySEO/blob/master/README.es.md)  

#### Índice:
 1. [Descrição](#description)
 2. [Como instalr MySEO](#how-to-install-myseo)
    * [Instalar extras para o Google SEO plugin](#install-extras-for-google-seo-plugin)
    * [Optimizar código para os modelos](#optimize-code-of-the-templates)
 3. [Como desinstalr MySEO](#how-to-unistall-myseo)
    * [Remover código modificado dos modelos.](#remove-modified-code-of-the-templates)

## Descrição
MySEO é um adicional para os sistemas de fórum MyBB.Ele cria e instala códigos HTML em seus modelos para otimizar o seu site para motores de buscas e integrações à redes sociais.
Por exemplo,adicionando palavras-chave,descrições de meta,melhores títulos e etc.

**Convertendo da seguinte maneira:**

![Péssimo SEO em página index](http://i.imgur.com/L2agGob.png)
![Péssimo SEO em página index](http://i.imgur.com/3i6UbnI.png) 

**Em bom SEO:**

![Bom SEO na página index](http://i.imgur.com/M2ajMql.png) 
![Bom SEO na página index](http://i.imgur.com/LJkB0EG.png)

## Como instalar MySEO

#### Apagar códigos de Edição
**Apenas necessário se tiver atualizando da versão 1.0**.  
Em versões anteriores da versão MySEO era necessário editar manualmente o arquivo "class_parser.php" para funcionar.
Nas versões anteriores do Myseo, para implementar o sistema de NoFollow é necessário editar manualmente o arquivo de núcleo class_parser.php arquivo para que ele funcione.  Agora não é mais necessário. Para remover essas alterações, encontrar esse código em /inc/class_parser.php
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
 
Modificado para:
  ````php
    $name = preg_replace("#&amp;\#([0-9]+);#si", "&#$1;", $name); // Fix & but allow unicode
    $link = "<a href=\"$fullurl\" target=\"_blank\"{$nofollow}>$name</a>";
    return $link;
 ````
 
Vá até o seu ACP > Plugins - você deve ver a palavra "Activated" na descrição de seu plugin.on.


#### Otimização de código de modelos

So that no confusion in your templates (eg title codes that already brings MyBB, if not removed may have duplications), follow these steps:
* No modelo *index*:
	* Remova as marcações `<title> </title>` e seu conteúdo (eg: `<title>{$mybb->settings['bbname']}</title>`).
* No Modelo *forumdisplay*:
    * Remova as marcações `<title> </title>` e seu conteúdo (eg: `<title>{$mybb->settings['bbname']} - {$foruminfo['name']}</title>`).
* No modelo *showthread*:
	* Remova as marcações `<title> </title>` e seu conteúdo (eg: `<title>{$thread['subject']}</title>`).
* No modelo *member_profile*:
	* Remova as marcações `<title> </title>` e seu conteúdo (eg: `<title>{$mybb->settings['bbname']} - {$lang->profile}</title>`).

#### Install extras for Google SEO plugin
(If you do not want to install these improvements, simply ignores these **instructions**).
MySEO brings standard configurations to improve and refine the use of [Google SEO Plugin](http://mods.mybb.com/view/google-seo).  
The improvements are:
 * Add the frequency change to sitemaps (a suggestion to Google to revisit your forum to see the new content, very well suited)
 * Add the URL's priority to sitemaps, **almost obligatory** by [various reasons](https://github.com/BitLiberal/MySEO/wiki/En-construccion---Coming-soon#under-construction)
 * Remove NoFollow system of Google SEO plugin (to not affect the MySEO the plugin).

To make these extras work, you must replace certain files from Google SEO, for that you need [download the **Extras Google Seo** folder](https://github.com/BitLiberal/MySEO/releases/download/v1.0/Extras.Google.SEO.rar), copy and paste files into the root folder of your forum MyBB. **Ready!**


## Como desinstalar MySEO

#### Remover modificações nos códigos dosmodelos
Para melhor identificação TODOS os códigos adicionados pelo plugin são comentados por `<!-- Site optimized with MySEO 1.0 --> `, limpe os códigos (tanto para desinstalar ou modificcar o código) você deverá acessar os seguintes modelos, incluindo comentários em html.
* forumdisplay
* footer
* index
* headerinclude
* member_profile


#### Informativo

Para facilitar a customização (e amplamente porque eu não sou um profissional desenvolvedor) se você modificar o código **standard** do plugin MySEO eles não serão excluídos,m
To facilitate the customization (and largely because I'm not a professional developer) if you modify the installed **standard** codes by the plugin MySEO, estes não serão apagados ou personalizados.
Relaxe, como expliquei [aqui](# remove-modified-code-of-templates), é muito simples remover o código.
