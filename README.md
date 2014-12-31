Indexador de ftps Carola 
========================

Indexador del contenido de los ftps realizado con el microframework Silex.

##Configuración de la base de datos##

Los parámetros de la conexión se deben configurar en el archivo `config\prod.php`.

##Sugerencias para la configuración de los servidores http para Carola##

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





