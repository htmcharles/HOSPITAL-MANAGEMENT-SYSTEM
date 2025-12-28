<?php
try
{
	include("connect.php");

	if(isset($_POST['savebtn']))
	{
		$new=$_POST['service'];
		$resultats=$connexion->query("SELECT nomservice FROM services WHERE nomservice='$new'");
		//$num_rows=$resultats->fetchColumn();
		$num_rows=$resultats->rowCount();
		if( $num_rows == 0)
		{
			$nomService=$_POST['service'];
			echo $nomService;
		
			$resultats=$connexion->prepare('INSERT INTO services (nomservice) VALUES(:nomServ)');
			
			$resultats->execute(array(
			'nomServ'=>$_POST['service']
			)); 
		}else{
		
		echo '<script type="text/javascript"> alert("Le service saisi existe deja");</script>';
		echo '<script type="text/javascript">document.location.href="services.php";</script>';
		
		}
	}
	
	if(isset($_POST['updatebtn']))
	{
	
	$newNom=$_POST['nom'];
	$newPrenom=$_POST['prenom'];
	$newMail=$_POST['e_mail'];
	$newPassword=md5($_POST['password']);
	$newType=$_POST['type'];
	$newStatut=$_POST['status'];
	$modifierUti=$_POST['idopere'];
	
	$resultats=$connexion->query("SELECT nom FROM utilisateurs WHERE nom='$newNom'");
	//$num_rows=$resultats->fetchColumn();
		$num_rows=$resultats->rowCount();
	if( $num_rows != 0)
	{
		$resultats=$connexion->prepare('UPDATE utilisateurs SET nom=:nom, prenom=:prenom, e_mail=:mail, password=:passwrd, type=:type, status=:status WHERE id_utilisateur=:iduti ');
		$resultats->execute(array(
		'iduti'=>$modifierUti,
		'nom'=>$_POST['nom'],/*le 'nom' est le même que celui de ':nom' de la ligne "prepare(...VALUES>"*/
		'prenom'=>$newPrenom,
		'mail'=>$newMail,
		'passwrd'=>$newPassword,
		'type'=>$newType,
		'status'=>$newStatut
		));

	}
	header("Location:utilisateurs1.php");		
	}
	
	if(isset($_POST['showbtn']))
	{
	header("Location:utilisateurs1.php");	
	}
}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}


	/*
	if(isset($_POST['delete']))
	{
	$resultats=$connexion->prepare('DELETE FROM utilisateurs WHERE nom=\'B\'');
	$resultats->execute(array(
	'nom'=>$_POST['nom'],
	'prenom'=>$_POST['prenom'],
	'e_mail'=>$_POST['e_mail'],
	'password'=>md5($_POST['password']),
	'status'=>$_POST['status']
	));
	echo "L'utilisateur a bien été supprimé<br/><br/>";

	$resultats->closeCursor();
	}


catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}
*/

?>