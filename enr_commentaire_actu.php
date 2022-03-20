<?php
session_start();
include_once($_SERVER["DOCUMENT_ROOT"].'/core/database.php');
/*=== Test de validation du formulaire ===*/
if (isset($_POST['commentaire']) && $_POST['commentaire']!= "" && isset($_POST['idactu']) && is_numeric($_POST['idactu']))
{

		$Resultat = Requete("INSERT INTO filactu_commentaires (iduser, datecom, heurecom, commentaire, idactu)
		VALUES ('".$_SESSION['idconnecte']."','".date("Y-m-d")."','".date("H:i:s")."','".addslashes($_POST['commentaire'])."','".$_POST['idactu']."')","lastinsert");
		
		$Pseudoactu = Requete("SELECT pseudo FROM user WHERE id = ".$_SESSION['idconnecte'], "select");
		if (isset($Pseudoactu[0]["pseudo"]))
		{			
			echo '<div id="commentaire_'.$Resultat.'" style="border-bottom:2px solid #333;"><p class="card-text" style="margin-bottom:5px;">'.$_POST['commentaire'].'</p><p style="font-size:10px;">'.$Pseudoactu[0]["pseudo"].' le '.date("Y-m-d").' à '.date("H:i:s").'<img src="/images/corbeille.png" onclick = "deletecom('.$Resultat.','.$_POST['idactu'].')" style="float:right;width:20px;"/></p></div>';
		}
		else
			Echo "Erreur technique";
}
else if (isset($_POST['idcom']) && is_numeric($_POST['idcom']) && $_POST['action'] == "delete")
{
	$Resultat = Requete("DELETE FROM filactu_commentaires WHERE idcom = ".$_POST['idcom'],"execute");

}
?>
