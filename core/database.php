<?php
/*
typerequete == select, execute, lastinsert
si Insert ==> retourne le dernier id créé
*/
function Requete($sql,$typerequete)
{
	/*=== Connexion à la base de données ===*/
	$hostname="localhost";
	$username="root";
	$password="";
	$dbname="sakura";
	$mysqli = new mysqli($hostname, $username, $password,$dbname);
	if($mysqli->connect_error){
                die('Erreur : ' .utf8_encode($mysqli->connect_error));
            }
	$mysqli->set_charset("utf8");		
			
	$res = $mysqli->query($sql);
	
	/*=== Test type requete ===*/
	if ($typerequete=="select")
	{	
		$row = $res->fetch_all(MYSQLI_ASSOC);
		return $row;
	}
	else if ($typerequete == "lastinsert")
	{
		return $mysqli->insert_id;
	}
	else
		return true;
}
?>
