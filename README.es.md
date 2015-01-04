# MySEO - SEO OnPage en tu foro MyBB

#### Indice:
 1. [Descripcion](#descripcion)
 2. [Como instalar MySEO](#como-instalar-myseo)
    * [Instalar Sistema NoFollow](#instalar-sistema-nofollow)
	* [Instalar extras para el plugin Google SEO](#instalar-extras-para-el-plugin-google-seo)
    * [Optimiza el codigo de las plantillas](#optimiza-el-codigo-de-las-plantillas)
 3. [Como desinstalar MySEO](#como-desinstalar-myseo)
    * [Borrar codigo modificado de las plantillas.](#borrar-codigo-modificado-de-las-plantillas)

## Descripcion
MySEO es un plugin para el sistema de foro MyBB que crea e instala codigos HTML en las plantillas de tus temas para mejorar el seo interno (SEO OnPage) de tu foro.  
Por ejemplo, añadiendo meta keywords, meta descripciones, titles mejorados, etc.

**Convertira cosas como esto:**  
![Mal SEO OnPage en el Indice](http://i.imgur.com/L2agGob.png)
![Mal SEO OnPage en el Indice](http://i.imgur.com/3i6UbnI.png) 

**En esto:**  
![Buen SEO OnPage en el Indice](http://i.imgur.com/M2ajMql.png) 
![Buen SEO OnPage en el Indice](http://i.imgur.com/LJkB0EG.png)

## Como instalar MySEO

#### Instalar sistema NoFollow
Para instalar el sistema NoFollow tendras que editar un archivo del core de MyBB:

Abre el archivo **/inc/class_parser.php** y busca este codigo:
  ````php
    $name = preg_replace("#&amp;\#([0-9]+);#si", "&#$1;", $name); // Fix & but allow unicode
    $link = "<a href=\"$fullurl\" target=\"_blank\"{$nofollow}>$name</a>";
    return $link;
 ````
 
 Reemplazalo por este:
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
Si vas ahora a tu ACP > Plugins, deberias ver la palabra "Activado" en la descripcion del plugin. **¡Listo!**

#### Instalar extras para el plugin Google SEO
(Si no quieres instalar estas mejoras, **simplemente ignora estas instrucciones**).
MySEO trae unas configuraciones de serie (sera optativo en un futuro, aunque tenerlo no afecta en nada al funcionamiento del foro o del plugin) para mejorar y pulir el uso del plugin [Google SEO](http://mods.mybb.com/view/google-seo).
Las mejoras son:  
 * Añadir el frecuencia de cambio (una sugerencia a Google para que revisite tu foro para ver el nuevo contenido, muy indicado)
 * Añadir la prioridad a las URL's de temas, foros, anuncios, etc, **casi obligado** por [diversos motivos](https://github.com/BitLiberal/MySEO/wiki/En-construccion---Coming-soon#en-construccion)
 * Inutilizado el sistema de NoFollow de Google SEO (para que no afecte al del plugin MySEO). 
 * **Para los foros en español**, una traduccion del 404 de Google SEO.  
Para hacer que estos extras funcionen, debes ir a la carpeta *[Extras] Google Seo*, copiar los archivos y pegarlos en la carpeta raiz de tu foro MyBB. **¡Listo!**


#### Optimiza el codigo de las plantillas
Para que no existan confusiones en tus plantillas (los codigos title ya los trae MyBB de serie, y pueden haber duplicaciones), sigue estos pasos:  
En construccion

## Como desinstalar MySEO

#### Borrar codigo modificado de las plantillas
Para su correcta identificacion, TODOS los codigos introducidos por el plugin vienen rodeados de el comentario  
`html <!-- Site optimized with MySEO 1.0 --> `, para borrar los codigos (ya sea al desinstalar como explica [aqui](#aviso), o modificar los codigos) solo tienes que acceder a las siguientes plantillas y borrarlos, incluidos los comentarios html:
* forumdisplay
* footer
* index
* headerinclude
* member_profile


#### Aviso
Para favorecer la customizacion (y en gran parte porque yo no soy un programador profesional) si modificas los codigos instalados **de serie** por el plugin MySEO, estos no seran borrados, ni los personalizados ni los de serie.
Tranquil@, como explico [aqui](#borrar-codigo-modificado-de-las-plantillas), es muy facil borrarlo.