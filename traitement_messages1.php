<?php
session_start();

//connexion à la base de données 
include("connect.php");

$id=$_SESSION['id'];


	$resultD=$connexion->query( 'SELECT *FROM utilisateurs u, medecins m WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND m.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');
	
	$resultI=$connexion->query( 'SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND i.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');
	
	$resultL=$connexion->query( 'SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND l.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');
	
	$resultR=$connexion->query( 'SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND r.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');
	
	$resultA=$connexion->query( 'SELECT *FROM utilisateurs u, auditors a WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND a.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');
	
	$resultC=$connexion->query( 'SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND c.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');
	
	$resultM=$connexion->query( 'SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND c.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');
	
	$resultAcc=$connexion->query( 'SELECT *FROM utilisateurs u, accountants acc WHERE u.id_u!='.$_SESSION['id'].' AND u.status=1 AND acc.id_u=u.id_u AND u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\'  ORDER BY u.nom_u');

/*--------affichage d'un message "pas de résultats"------------*/

	$numRowsD=$resultD->rowCount();
	$numRowsI=$resultI->rowCount();
	$numRowsL=$resultL->rowCount();
	$numRowsR=$resultR->rowCount();
	$numRowsA=$resultA->rowCount();
	$numRowsC=$resultC->rowCount();
	$numRowsM=$resultM->rowCount();
	$numRowsAcc=$resultAcc->rowCount();

	if( $numRowsD == 0 AND $numRowsI == 0 AND $numRowsL == 0 AND $numRowsR == 0 AND $numRowsA == 0 AND $numRowsC == 0 AND $numRowsM == 0 AND $numRowsAcc == 0)
	{
	?>
		<option value='' id="uti">Pas de résultats</option>
	<?php
	}
	else{

	?>

	<?php
		// parcours et affichage des résultats
		$resultD->setFetchMode(PDO::FETCH_OBJ);
		while( $postM = $resultD->fetch())
		{
	?>

			
				<option value='<?php echo $postM->id_u;?>' id="utiM" onclick="GetOption('Uti')"><?php echo $postM->nom_u.' '.$postM->prenom_u.' ('.$postM->codemedecin.')';?></option>
			
			
	<?php
		}
				
		$resultI->setFetchMode(PDO::FETCH_OBJ);
		while( $postI = $resultI->fetch())
		{
	?>

			
				<option value='<?php echo $postI->id_u;?>' id="utiI" onclick="GetOption('Uti')"><?php echo $postI->nom_u.' '.$postI->prenom_u.' ('.$postI->codeinfirmier.')';?></option>
			
			
	<?php
		}
				
		$resultL->setFetchMode(PDO::FETCH_OBJ);
		while( $postL = $resultL->fetch())
		{
	?>

			
				<option value='<?php echo $postL->id_u;?>' id="utiL" onclick="GetOption('Uti')"><?php echo $postL->nom_u.' '.$postL->prenom_u.' ('.$postL->codelabo.')';?></option>
			
			
	<?php
		}
		
		$resultR->setFetchMode(PDO::FETCH_OBJ);
		while( $postR = $resultR->fetch())
		{
	?>

			
				<option value='<?php echo $postR->id_u;?>' id="utiR" onclick="GetOption('Uti')"><?php echo $postR->nom_u.' '.$postR->prenom_u.' ('.$postR->codereceptio.')';?></option>
			
			
	<?php
		}
		
		$resultA->setFetchMode(PDO::FETCH_OBJ);
		while( $postA = $resultA->fetch())
		{
	?>

			
				<option value='<?php echo $postA->id_u;?>' id="utiA" onclick="GetOption('Uti')"><?php echo $postA->nom_u.' '.$postA->prenom_u.' ('.$postA->codeaudit.')';?></option>
			
			
	<?php
		}
		
		$resultC->setFetchMode(PDO::FETCH_OBJ);
		while( $postC = $resultC->fetch())
		{
	?>

			
				<option value='<?php echo $postC->id_u;?>' id="utiC" onclick="GetOption('Uti')"><?php echo $postC->nom_u.' '.$postC->prenom_u.' ('.$postC->codecashier.')';?></option>
			
			
	<?php
		}
		
		$resultM->setFetchMode(PDO::FETCH_OBJ);
		while( $postM = $resultM->fetch())
		{
	?>

			
				<option value='<?php echo $postM->id_u;?>' id="utiM" onclick="GetOption('Uti')"><?php echo $postM->nom_u.' '.$postM->prenom_u.' ('.$postM->codecoordi.')';?></option>
			
			
	<?php
		}
		
		$resultAcc->setFetchMode(PDO::FETCH_OBJ);
		while( $postAcc = $resultAcc->fetch())
		{
	?>

			
				<option value='<?php echo $postAcc->id_u;?>' id="utiAcc" onclick="GetOption('Uti')"><?php echo $postAcc->nom_u.' '.$postAcc->prenom_u.' ('.$postAcc->codeaccount.')';?></option>
			
			
	<?php
		}
	?>



		
	<script>

	function getXMLHttpRequest() {
	var xhr = null;

	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	}else {
		alert("Your Browser does not support XMLHTTPRequest object...");
		return null;
	}

	return xhr;
	}


	</script>
	 <?php
	}

/*****
fonctions
*****/
function safe($var)
{
	//$var = mysql_real_escape_string($var);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}
?>