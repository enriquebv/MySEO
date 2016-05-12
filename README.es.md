# MySEO - SEO OnPage en tu foro MyBB  

Are you searching for **english instructions**? [Click here](https://github.com/BitLiberal/MySEO/blob/master/README.es.md)  

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

Instalarlo es muy facil, solo tienes que descargar la ultima version (desde [Github](https://github.com/BitLiberal/MySEO)) y subir el contenido de la carpeta "Upload" a la carpeta raiz de tu foro (la carpeta principal, donde se encuentra *index.php*).  
**Atencion:**  
Debes tener ciertos codigos en las plantillas para que se instale correctamente. Si tu plantilla es de serie no te preocupes, pero si no es asi:
* Ve a la plantilla *headerinclude* y busca `{$stylesheets}`. Si no lo encuentras, añadelo tu mismo.
* Ve a la plantilla *footer* y busca `{$task_image}`. Si no lo encuentras, añadelo tu mismo.


#### Optimiza el codigo de las plantillas
Es posible que existan etiquetas <title> duplicadas en las plantillas. Esto es porque MySEO no borra ese trozo de codigo (que esta por defecto en MyBB) al insertar el suyo mejorado. Para solucionarlo, sigue estos pasos:  
* En plantilla *index*:
	* Borrar etiquetas `<title> </title>` y su contenido (por defecto: `<title>{$mybb->settings['bbname']}</title>`).
* En plantilla *forumdisplay*:
    * Borrar etiquetas `<title> </title>` y su contenido (por defecto: `<title>{$mybb->settings['bbname']} - {$foruminfo['name']}</title>`).
* En plantilla *showthread*:
	* Borrar etiquetas `<title> </title>` y su contenido (por defecto: `<title>{$thread['subject']}</title>`).
* En plantilla *member_profile*:
	* Borrar etiquetas `<title> </title>` y su contenido (por defecto: `<title>{$mybb->settings['bbname']} - {$lang->profile}</title>`).

## Como desinstalar MySEO

#### Borrar codigo modificado de las plantillas
Para su correcta identificacion, TODOS los codigos introducidos por el plugin vienen rodeados de el comentario  
`<!-- Site optimized with MySEO 1.0 --> `, para borrar los codigos (ya sea al desinstalar como explica [aqui](#aviso), o modificar los codigos) solo tienes que acceder a las siguientes plantillas y borrarlos, incluidos los comentarios html:
	* forumdisplay
	* footer
	* index
	* headerinclude
	* member_profile


#### Aviso
Para favorecer la customizacion (y en gran parte porque yo no soy un programador profesional) si modificas los codigos instalados **de serie** por el plugin MySEO, estos no seran borrados, ni los personalizados ni los de serie.
Tranquil@, como explico [aqui](#borrar-codigo-modificado-de-las-plantillas), es muy facil borrarlo.
