<?php
session_start();
echo "<!doctype html>
<html lang='fr'>
<head>
  <meta charset = 'utf-8'>
  <title>Sakura</title>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
  <link rel='stylesheet' href='//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css'>
  <link rel='stylesheet' href='http://localhost/css/sakura.css' >
  <meta http-equiv = 'refresh' content = 'seconds'; url = 'http://localhost/'/>
</head>
<body>
";

if (isset($_SESSION['idconnecte']))
{
	$utilisateur= Requete("SELECT pseudo FROM user WHERE id = '".$_SESSION['idconnecte']."'","select");

/*=== Réitération de la requête de la page 'index' pour afficher la photo de profil ===*/
	$ListeActus = Requete("SELECT user.avatar FROM user WHERE id = '".$_SESSION['idconnecte']."'","select");
			
		foreach($ListeActus as $Valeur)
		{
			if ($Valeur['avatar']!="")
				$avatar_photo = '<img src="/upload_avatar/'.$Valeur['avatar'].'" style="max-width:40px;border-radius:20px; margin-right:10px;">';
			else 
				$avatar_photo ="";
		}
		
/*=== Création et affichage de la barre bleue commune à toutes les pages ===*/
	echo '
<nav class="navbar navbar-dark navbar-expand-lg" style="background-color: #A8D1E7;>
	  <div class="container-fluid">
		<a class="navbar-brand" href="http://localhost">&emsp;·&ensp;SAKURA&ensp;·</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigastion">
		  <span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
		  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
			<li class="nav-item">
			<a class="nav-link" href="register.php">
				<img src= "../images/exit.png" />
			</a>
			</li>
			<li class="nav-item">
			  <a class="nav-link" href = "addfil.php">
				<img src= "../images/add2.png" />
			  </a>
			</li>
			<li class="nav-item" style="margin-top:8px; margin-left: 62.5rem;">
			  <a class="profile" style="font-size: 20px;" href="profile.php">'.$utilisateur[0]['pseudo'].'</a>
			</li>

			<span style="margin-left:10px;">'.$avatar_photo.'</span>

		  </ul>
		</div>
	  </div>
</nav>	';
}
?>