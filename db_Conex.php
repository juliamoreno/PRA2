<?
	//	Connexi� a la base de dades
	//	Connexi� al servidor Local

	

	$localhost = "localhost";
	$user = "julian";
	$pwd = "";
	$database = "emisoresradio";


	//	Connexi� a la base de dades
	//	Connexi� al servidor Comoras

/*
	$localhost = "localhost";
	$user = "jmorenoal";
	$pwd = "UdLZrFkU";
	$database = "jmorenoal";
*/

	@ $mysql = mysql_connect( $localhost, $user, $pwd)
		or die("Ho sentim, en aquests moments no s'ha pogut connectar al servidor");
			
	//	Selecciono la base de dades
	@ mysql_select_db($database, $mysql)
		or die ("No s'ha pogut seleccionar la base de dades");

	//	Indiquem que els valors a retornar siguin amb la codificaci� UTF
		mysql_query("SET NAMES 'utf8'");

?>