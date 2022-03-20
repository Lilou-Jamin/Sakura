<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/core/database.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/core/header.php');
echo '<div style="width:80%;background-color:#FFBFC5;margin-left:10%;margin-right:10%;height:100%;padding:10px;">';

/*=== Test de validation du formulaire ===*/
if (isset($_POST['valider']))
{
	if (isset($_POST['lien']) && $_POST['lien']!="" && $_POST['idupdate'] == 0)
	{
		$Resultat = Requete("INSERT INTO filactu (iduser, datepost, heure, lienphotovideo, description)
		VALUES ('".$_SESSION['idconnecte']."','".date("Y-m-d")."','".date("H:i:s")."','".$_POST['lien']."','".addslashes($_POST['description'])."')","execute");
		header('Location: http://localhost');
	}
	else if (isset($_POST['lien']) && $_POST['lien']!="" && $_POST['idupdate'] 	!= 0)
	{
		$Resultat = Requete("UPDATE filactu SET lienphotovideo = '".$_POST['lien']."',description='".addslashes($_POST['description'])."' WHERE id = '".$_POST['idupdate']."'","execute");
		header('Location: http://localhost');
	}
}

/*=== Test si on effectue une modification ou une insertion ===*/
if (isset($_GET['idactu']) && is_numeric($_GET['idactu']))
{
		/*=== Modification ===*/
		$Resultat = Requete("SELECT * FROM filactu WHERE id='".$_GET['idactu']."' AND iduser = '".$_SESSION['idconnecte']."' limit 0,1","select");
		if (count($Resultat)==1)
		{
			$lien = $Resultat[0]['lienphotovideo'];
			$description = $Resultat[0]['description'];
			$idupdate = $_GET['idactu'];
		}
		else
		{
			/*=== En insertion quand même ===*/
			$lien = "";
			$description = "";
			$idupdate =0;
		}
}
else 
{
		/*=== Insertion ===*/
		$lien = "";
		$description = "";
		$idupdate =0;

}

/*=== Formulaire de création de post ===*/
echo '
<form class="row g-3 needs-validation" action="addfil.php" method="post">
  <div class="col-md-9">
    <br>
    <strong style = "margin-left : 40px; color: white; for="validationCustom01" class="form-label">Lien vidéo ou image : </strong>
    <input style= "background-color: #FEE5E0 ; border-color : #eb86a8 ; border-radius : 4px; margin : 8px 40px; width : 990px; type="text" class="form-control" id="lien" name="lien" value="'.$lien.'" required>
	<input type="hidden" class="form-control" id="idupdate" name="idupdate" value="'.$idupdate.'" >
  </div>
  
  <div class="col-md-6">
    <strong style = "margin-left : 40px; color: white;" for="validationCustom02" class="form-label">Description : </strong>
    <textarea style= "background-color: #FEE5E0 ; border-color : #eb86a8 ; border-radius : 4px; margin : 8px 40px; resize : none; id="description" name="description" cols="121" rows="10">'.$description.'</textarea>
  </div>
  
  <div class="col-12">
    <button style="background-color: #A8D1E7 ; border-color : #A8D1E7 ; border-radius : 5px ; margin-left : 964px; class="btn btn-primary" type="submit" id="valider" name="valider" >Ajouter</button>
  </div>
</form>';
echo "</div>";
include_once($_SERVER["DOCUMENT_ROOT"].'/core/footer.php');
?>
