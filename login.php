<?php
session_start();
include_once($_SERVER["DOCUMENT_ROOT"].'/core/database.php');
if (isset($_POST['connexion']) && isset($_POST['pseudo']) && $_POST['pseudo']!= "" && isset($_POST['password']) && $_POST['password']!="")
{
	$Resultat = Requete("SELECT id,idprofil FROM user WHERE pseudo='".$_POST['pseudo']."' AND motdepasse='".$_POST['password']."' ","select");
	if (count($Resultat)==1)
	{
		// On crée les variables de sessions qui seront utilisées partout dans le site web
		$_SESSION['idconnecte'] = $Resultat[0]['id'];
		if ($Resultat[0]['idprofil']==1)
			$_SESSION['admin'] = true;
		else 
			$_SESSION['admin'] = false;
		header('Location: http://localhost');
	}
	else
	{
		header('Location: http://localhost/index.php?erreur=1');
	}
}
else if (isset($_POST['connexion']) &&  ((isset($_POST['pseudo']) && $_POST['pseudo']== "") or (isset($_POST['password']) && $_POST['password']=="")))
{
		header('Location: http://localhost/index.php?erreur=5');
}
if (isset($_POST['deconnexion']))
{
	$_SESSION['idconnecte'] = null;
	header('Location: http://localhost');
}
if (isset($_POST['register']))
{
	if ($_POST['pseudo']!="" && $_POST['nom']!="" && $_POST['prenom']!="" && $_POST['email']!="" && $_POST['password']!="")
	{
		// On teste si le pseudo existe déjà
		$Resultat = Requete("SELECT id FROM user WHERE pseudo='".$_POST['pseudo']."' ","select");
		if (count($Resultat)==0)
		{
			// On crée l'utilisateur
			$Resultat = Requete("INSERT INTO user (pseudo, nom, prenom, email, motdepasse,idprofil)
			VALUES ('".$_POST['pseudo']."','".$_POST['nom']."','".$_POST['prenom']."','".$_POST['email']."','".$_POST['password']."','2')","execute");
			header('Location: http://localhost/index.php?erreur=2');
		}
		else
		{
			// Message où utilisateur existe déjà
			//echo "Cet utilisateur existe déjà. Veuillez renseigner d'autres champs.";
			header('Location: http://localhost/index.php?erreur=3');
		}
	}
	else
		header('Location: http://localhost/index.php?erreur=4');
}
?>