Indexador de ftps Carola 
========================

Indexador del contenido de los ftps realizado con el microframework Silex para la red SNET.

## Configuración de la base de datos ##

Instalar la base de datos que se distribuye en la carpeta `database`.

Configurar los parámetros de conexión en el archivo `config/prod.php`.

## Instalación ##

Configurar un virtual host o alias apuntando a la carpeta `web`.

Para poder usar urls limpias debe activarse `mod_rewrite`.

## Utilización ##

Para indexar los ftps se puede utilizar el comando de consola `ftp:indexer`.

Este comando acepta un parámetro **ip** mediante el cuál se puede indicar la ip del servidor ftp que se quiere indexar. Si no se pasa ningún parámetro se indexan todos los ftps activos.

Para ejecutar el comando primero situarse en el directorio **bin**

Ej.:

`php console ftp:indexer 192.168.136.1` Ejecuta el escaneo e indexación para el ftp con ip 192.168.136.1.

`php console ftp:indexer` Ejecuta el escaneo para todos los ftps activos.

Con este comando de consola es posible configurar el crontab de Linux para establecer escaneos programados.

Para editar el crontab escribimos en la consola: `sudo crontab -e`. Luego editamos el archivo programando la tarea teniendo en cuenta el formato necesario para configurar una tarea programada.

Ej.: 

`* 0 * * 5 php /ruta/hasta/carola/bin/console ftp:indexer` Ejecuta el escaneo para todos los ftps activos a las 12:00 de la noche todos los viernes.

`30 17 * * * php /ruta/hasta/carola/bin/console ftp:indexer 192.168.128.2` Ejecuta el escaneo para el ftp 192.168.128.2 todos los días a las 5:30 pm.

## Backend o parte de administración ##

Las credenciasles para acceder a la parte de administración son:

* **usuario:** admin
* **password:** 1234

## Sugerencias para la configuración de los servidores http para Carola ##

**Apache**
```
	<IfModule mod_rewrite.c>
	    Options -MultiViews
	 
	    RewriteEngine On
	    RewriteBase /ruta/hasta/carola
	    RewriteCond %{REQUEST_FILENAME} !-f
	    RewriteRule ^ index.php [L]
	</IfModule>
```

**NGINX**
```
	server {
	    #site root is redirected to the app boot script
	    location = / {
	        try_files @site @site;
	    }
	 
	    #all other locations try other files first and go to our front controller if none of them exists
	    location / {
	        try_files $uri $uri/ @site;
	    }
	 
	    #return 404 for all php files as we do have a front controller
	    location ~ \.php$ {
	        return 404;
	    }
	 
	    location @site {
	        fastcgi_pass   unix:/var/run/php-fpm/www.sock;
	        include fastcgi_params;
	        fastcgi_param  SCRIPT_FILENAME $document_root/index.php;
	        #uncomment when running via https
	        #fastcgi_param HTTPS on;
	    }
	}
```

**IIS**
```
	<?xml version="1.0"?>
	<configuration>
	    <system.webServer>
	        <defaultDocument>
	            <files>
	                <clear />
	                <add value="index.php" />
	            </files>
	        </defaultDocument>
	        <rewrite>
	            <rules>
	                <rule name="Silex Front Controller" stopProcessing="true">
	                    <match url="^(.*)$" ignoreCase="false" />
	                    <conditions logicalGrouping="MatchAll">
	                        <add input="{REQUEST_FILENAME}" matchType="IsFile"
	                             ignoreCase="false" negate="true" />
	                    </conditions>
	                    <action type="Rewrite" url="index.php"
	                            appendQueryString="true" />
	                </rule>
	            </rules>
	        </rewrite>
	    </system.webServer>
	</configuration>
```

**Lighthttpd**
```
	server.document-root = "/ruta/hasta/carola"
	 
	url.rewrite-once = (
	    # configure some static files
	    "^/assets/.+" => "$0",
	    "^/favicon\.ico$" => "$0",
	 
	    "^(/[^\?]*)(\?.*)?" => "/index.php$1$2"
	)
```


