<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/core/database.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/core/header.php');

/*=== Test de validation du formulaire ===*/
if (isset($_POST['valider']))
{
	/*=== Envoi du fichier de l'avatar sur le serveur ===*/
	$namefile = generateFileName();
	$uploadfile=$_SERVER["DOCUMENT_ROOT"]."/upload_avatar/".$namefile.".jpg";
	if (move_uploaded_file($_FILES['avatar']['tmp_name'],$uploadfile))
	{
		$avatar =$namefile.'.jpg';
	}
	else
	{
		$avatar ="";
	}
	$Resultat = Requete("UPDATE user SET nom = '".$_POST['nom']."',prenom='".$_POST['prenom']."',pseudo='".$_POST['pseudo']."',avatar='".$avatar."' WHERE id = '".$_SESSION['idconnecte']."'","execute");
	header('Location: http://localhost');
}

echo '<div style="width:80%;background-color:#FFBFC5;margin-left:10%;margin-right:10%;height:100%;padding:10px;">';
$Resultat = Requete("SELECT * FROM user WHERE id='".$_SESSION['idconnecte']."' limit 0,1 ","select");
if (count($Resultat)==1)
{
	
/*=== Formulaire de modification des données du profil ===*/
echo '
<form class="row g-3 needs-validation" enctype = "multipart/form-data" action="profile.php" method="post">

  <div class="col-md-6">
  <br>
    <strong style = "margin-left : 40px; color:#fff; for="validationCustom01" class="form-label">Nom : </strong>
    <input style= "background-color: #FEE5E0 ; border-color : #eb86a8 ; border-radius : 4px; margin : 8px 40px; width : 300px;" type="text" class="form-control" id="nom" name="nom" value="'.$Resultat[0]['nom'].'" required>
  </div>
  
  <div class="col-md-6">
  <br>
    <strong style = "margin-left : 40px; color:#fff;" for="validationCustom02" class="form-label">Prénom : </strong>
    <input style= "background-color: #FEE5E0 ; border-color : #eb86a8 ; border-radius : 4px; margin : 8px 40px; width : 300px;" type="text" class="form-control" id="prenom" name="prenom" value="'.$Resultat[0]['prenom'].'" required>
  </div>
  <div class="col-md-6">
    <strong style = "margin-left : 40px; color:#fff;" for="validationCustom02" class="form-label">Pseudo : </strong>
    <input style= "background-color: #FEE5E0 ; border-color : #eb86a8 ; border-radius : 4px; margin : 8px 40px; width : 300px;" type="text" class="form-control" id="pseudo" name="pseudo" value="'.$Resultat[0]['pseudo'].'" required>
  </div>

  <div class="col-md-6">
    <strong style = "margin-left : 40px; color:#fff;" for="validationCustom02" class="form-label">Votre Avatar : </strong>
    <input style= "background-color: #FEE5E0 ; border-color : #eb86a8 ; border-radius : 4px; margin : 8px 40px; width : 410px;" type="file" class="form-control" id="avatar" name="avatar" value="'.$Resultat[0]['avatar'].'" required>
  </div>

  
  <div class="col-12">
    <button style="background-color: #A8D1E7 ; border-color : #A8D1E7 ; border-radius : 5px ; margin-left : 776px; class="btn btn-primary" type="submit" id="valider" name="valider" >Enregistrer les modifications</button>
  </div>
  
</form>';
}
else
{
	echo "Problème technique !";
}
echo "</div>";

/*=== Fonction qui génère un nom aléatoire de fichier pour pas que deux utilisateurs est le même nom de fichier d'avatar ===*/
function generateFileName()
{
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789_";
	$name = "";
	for($i=0; $i<12; $i++)
	$name.= $chars[rand(0,strlen($chars))];
	return $name;
}
include_once($_SERVER["DOCUMENT_ROOT"].'/core/footer.php');
?>
