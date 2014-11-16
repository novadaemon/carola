<?php
//************************************************************************************************
//
//			Archivo de configuracion del motor de indizacion de ftps
//
//************************************************************************************************
/*Seccion 1: Parametros para realizar la coneccion con la base de datos*/

	//Parametro 1: Usuario con el que se establecen las conecciones con el servidor, debe tener acceso root.

$user="root";

	//Parametro 2: Contrasena del usuario especificado en la variable anterior

$password="master";

	//Parametro 3: Servidor que contiene la base de datos

$server="localhost";

/*Seccion 2: Usuarios autorizados al panel administrativo, no tienen nada que ver con el acceso al servidor.
Para acceder a este los usuarios permitidos por esta seccion usaran la contrasena y el usuario
especificados en el apartado anterior. Esto no implica que los usuarios obtengan conocimiento de
los datos de seguridad del servidor. Ellos se registraran bajo los nombres de usuario
especificados aqui, y el sitio automaticamente usara el usuario anterior para realizar las acciones.*/

	//Parametro 1: Listado de usuarios permitidos a entrar en el panel administrativo
	//Ejemplo: $usuarios = array("user1", "user2", "user3");

$usuarios = array("user");

	//Parametro 2: Contrasenas de los usuarios especificados anteriormente
	//Ejemplo: $contrasenas = array("password1", "password2", "password3");
	//los pares usuario contrasena se emparejaran por indice, 
	//asegurese de que sean la misma cantidad de usuarios que de contrasenas
	//de lo contrario podrian ocurrir errores pues todavia no hay proteccion implementada

$contrasenas = array("123");

?>