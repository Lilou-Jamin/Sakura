<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/core/database.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/core/header.php');
$diverreur="";$divinscrit="";

/*=== Test si message d'erreur à la connexion ou l'inscription ===*/
if (isset($_GET['erreur']))
{
	if ($_GET['erreur'] == 1)
	{
		$diverreur = '<br /><br /><br /><br /><div class="alert alert-danger alert-dismissible fade show" role="alert">Vous vous êtes trompé dans votre mot de passe ou pseudo !</div>';
	}else if ($_GET['erreur'] == 2)
	{
		$diverreur = '<br /><br /><br /><br /><div class="alert alert-success alert-dismissible fade show" role="alert">Votre inscription a été réalisée</div>';
	}
	else if ($_GET['erreur'] == 3)
	{
		$diverreur = '<br /><br /><br /><br /><div class="alert alert-danger alert-dismissible fade show" role="alert">Ce pseudo existe déjà !</div>';
	}
	else if ($_GET['erreur'] == 4)
	{
		$diverreur = '<br /><br /><br /><br /><div class="alert alert-danger alert-dismissible fade show" role="alert">Veuillez renseigner tous les champs du formulaire</div>';
	}	
	else if ($_GET['erreur'] == 5)
	{
		$diverreur = '<br /><br /><br /><br /><div class="alert alert-danger alert-dismissible fade show" role="alert">Le pseudo avec ce mot de passe n\'existe pas dans Sakura</div>';
	}	
}

/*=== Test si l'utilisateur est connecté avec une variable de session ===*/ 
if (isset($_SESSION['idconnecte']))
{
	/*=== Formulaire pour saisir les messages mis sous les posts ===*/
	echo '<div id="dialog-message" title="Commentaire" style="display:none; background-color : #FFBFC5; font-background-color : #A8D1E7;">
	  <i> Vous pouvez saisir un commentaire pour ce post : </i>
		<textarea style= "background-color: #FEE5E0 ; border-color : #FEE5E0 ; border-radius : 4px; resize : none;" id="commentaire" name="commentaire" rows="5" cols="40"></textarea>
	</div>';
	
		/*=== On est connecté ===*/
		echo '<div style="width:80%;background-color:#FFBFC5;margin-left:10%;margin-right:10%;height:100%;padding:10px;overflow-y: auto;">';
		$ListeActus = Requete("SELECT filactu.*,user.pseudo,user.avatar FROM filactu INNER JOIN user ON filactu.iduser = user.id ORDER BY datepost desc, heure desc","select");
			
		foreach($ListeActus as $Valeur)
		{
			$datepostaffiche = substr($Valeur['datepost'],8,2) . "/" .substr($Valeur['datepost'],5,2)."/".substr($Valeur['datepost'],0,4);
			/*=== Test si l'utilisateur est celui qui a posté le post. Si c'est le cas il peut alors le modifier ===*/
			if ($Valeur['iduser'] == $_SESSION['idconnecte'])
				$imageaffiche = '<a href="addfil.php?idactu='.$Valeur["id"].'"><img src="/images/modif.png" style="float:right; width:20px;"/></a>';
			else
				$imageaffiche ="";
			
			/* === Affichage du bouton pour ajouter un commentaire uniquement si la personne connectée n'est pas celle qui a créé le post ===*/
			if ($Valeur['iduser'] != $_SESSION['idconnecte'])
				$imageboutoncommentaire = '<button style="background-color: #FEE5E0 ; border-color : #FFF; font-size:28px; color: #eb86a8;" onclick="addCommentaire('.$Valeur["id"].')" class="btn btn-primary">+</button>';
			else
				$imageboutoncommentaire = "";
			
			/*=== Test si on a une photo d'avatar ===*/
			if ($Valeur['avatar']!="")
				$avatar_photo = '<img src="/upload_avatar/'.$Valeur['avatar'].'" style="width:40px;border-radius:20px; margin-right:10px;">';
			else 
				$avatar_photo ="";
			
			/*=== Affichage des posts ===*/
			echo '
				<div class="row">
				  <div class="col-sm-6">
					<div class="card" style="margin-bottom:20px;">
						<img src="'.$Valeur['lienphotovideo'].'" class="card-img-top" alt="Photo">
						<div class="card-body">
							<h5 class="card-title">'.$avatar_photo.$Valeur['pseudo'].'</h5>
							'.$imageaffiche.'
							<h6 class="card-subtitle mb-2 text-muted">'.$datepostaffiche.' '.$Valeur['heure'].'</h6>
							<p class="card-text">'.$Valeur['description'].'</p>
						</div>
					</div>
				  </div>
				<div class="col-sm-6" style="height:287px;overflow-y: scroll; width: 530px;">
					<div class="card">
						'.$imageboutoncommentaire;
					$ListeComActus = Requete("SELECT filactu_commentaires.*,user.pseudo,user.idprofil,user.avatar FROM filactu_commentaires INNER JOIN user ON filactu_commentaires.iduser = user.id WHERE idactu = '".$Valeur["id"]."' ORDER BY datecom desc, heurecom desc","select");

					echo "<br><strong>&ensp; <span id='nbcom_".$Valeur["id"]."' >".count($ListeComActus)."</span> commentaire(s)</strong>";
					
					  echo '<div class="card-body" id="divcommentaire_'.$Valeur["id"].'">';
					  
						/*=== Lecture et affichage de tous les commentaires postés pour chaque post ===*/				
						foreach($ListeComActus as $ValeurCom)
						{
							/*=== Test si c'est la même personne qui a créé la commentaire ou si l'utilisateur est admin alors il peut supprimer les commentaires ===*/
							if (($_SESSION['idconnecte'] == $ValeurCom['iduser'])or($_SESSION['admin'] == true) )
								$affichecorbeille = '<img src="/images/corbeille.png" onclick = "deletecom('.$ValeurCom['idcom'].','.$Valeur["id"].')" style="float:right;width:15px;"/>';
							else 
								$affichecorbeille ="";
							echo '<div id="commentaire_'.$ValeurCom['idcom'].'" style="border-bottom:1px solid #333;"><p class="card-text" style="margin-bottom:5px;">'.$ValeurCom['commentaire'].'</p><i style="font-size:13px;">'.$ValeurCom['pseudo'].' le '.$ValeurCom['datecom'].' à '.$ValeurCom['heurecom'].$affichecorbeille.'</i></div>';
						}
					  echo '</div>
					</div>
				  </div>
		  </div>';
		}
		echo "</div>";
}

else
{
	/*=== On est déconnecté, affichage de la page connexion / inscription ===*/
	echo '
	<div class="login-reg-panel">
		<div class="login-info-box">
			<font size = "5.5"><b>Vous avez déjà un compte ?</b></font>
			<label id="label-register" for="log-reg-show">Connectez-vous à votre compte !</label>
			<input type="radio" name="active-log-panel" id="log-reg-show"  checked="checked">
		</div>
							
		<div class="register-info-box">
			<font size = "8"><i><b>SAKURA</b></i></font>
			<br /> 
			<font size="4">Bienvenue sur Sakura, le réseau social dédié aux mangas et aux animés ! 
			<br /> 
			Vous n\'avez pas de compte ? </font><br>
			<label id="label-login" for="log-login-show">Inscrivez-vous !</label>
			<input type="radio" name="active-log-panel" id="log-login-show">
		</div>
							
		<div class="white-panel">
			<div class="login-show">
			<form action="login.php" method="post">
				<h2>Connexion à Sakura</h2>
				<input type="text" placeholder="Pseudo" value="" name="pseudo">
				<input type="password" placeholder="Mot de passe" value="" name="password">
				<input type="submit" name="connexion" value="Connexion">
				
			</form>
			'.$diverreur.'
			</div>
			<div class="register-show">
			<form action="login.php" method="post">
				<h2>S\'inscrire à Sakura</h2>
				<input type="text" placeholder="Pseudo" name="pseudo">
				<input type="text" placeholder="Nom" name="nom">
				<input type="text" placeholder="Prénom" name="prenom">
				<input type="text" placeholder="Email" name="email">
				<input type="password" placeholder="Mot de passe" name="password">
				<input type="submit" name="register" value="S\'inscrire">
			</form>
			</div>
		</div>
	</div>';
}
include_once($_SERVER["DOCUMENT_ROOT"].'/core/footer.php');
?>

<script>
function deletecom(idcom,idactu)
{
	$.ajax({
				url: "enr_commentaire_actu.php", 
				dataType: "text",
				type: 'POST',
				data: {idcom: idcom, action: "delete"},			  
				success: function(data_retour) {
					$('#commentaire_'+idcom).hide('slide');
					
					/*=== Récupération du nombre de commentaires ===*/
					var nombreactu = $('#nbcom_'+idactu).text();
					
					/*=== Décrémentation de 1 au nombre de commentaires ===*/
					nombreactu = parseInt(nombreactu)-1;
					
					/*=== Affichage du nouveau nombre de commentaire décrémenter de 1 ===*/
					$('#nbcom_'+idactu).text(nombreactu);
			  }
		   });
}
function addCommentaire(idactuvalue)
{
	$('#commentaire').val("");
	$( "#dialog-message" ).dialog({
      modal: true,
	  height:320,
	  width:400,
      show: {
        effect: "slide",
        duration: 400
      },
      hide: {
        effect: "drop",
        duration: 400
      },
      buttons: {
        Enregistrer: function() {
			/*=== Appel de la page php pour sauvegarder les données ===*/
			$.ajax({
				url: "enr_commentaire_actu.php", 
				dataType: "text",
				type: 'POST',
				data: {idactu: idactuvalue, commentaire: $("#commentaire").val()},			  
				success: function(data_retour) {

					/*=== Récupération du nombre de commentaires ===*/
					var nombreactu = $('#nbcom_'+idactuvalue).text();
					
					/*=== Incrémentation de 1 au nombre de commentaires ===*/
					nombreactu = parseInt(nombreactu)+1;
					
					/*=== Affichage du nouveau nombre de commentaire incrémenter de 1 ===*/
					$('#nbcom_'+idactuvalue).text(nombreactu);
					
					/*=== Ajout d'une ligne en affichage suite à l'ajout d'un commentaire ===*/
					$('#divcommentaire_'+idactuvalue).prepend(data_retour);
			  }
		   });
		   $( this ).dialog( "close" );
        },
		Annuler : function() {
         $( this ).dialog( "close" );
        }
 }
    });
}
</script>