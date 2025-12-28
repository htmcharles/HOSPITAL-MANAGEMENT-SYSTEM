<?php
try
{
	include("connect.php");

 	if(isset($_POST['savebtn']))
	{
	$e_mail=htmlspecialchars($_POST['mail']);
	$password=htmlspecialchars($_POST['password']);
	
		if(!empty($e_mail) AND !empty($password))
		{
			$sql=$connexion->query("SELECT *FROM utilisateurs u WHERE u.e_mail='$e_mail' AND u.password='$password'") /*  or die(print_r($sql->errorInfo())) */;
			
			$comptmail=$sql->rowCount();		
			
			if($comptmail!=0)
			{
			$sql->setFetchMode(PDO::FETCH_OBJ);
				while($ligne=$sql->fetch())
				{
					/* $sql=$connexion->query("SELECT *FROM utilisateurs u WHERE u.password='".$password."'"); */
					// or die(print_r($sql->errorInfo()));
						$_SESSION['connect']=true;
						$_SESSION['id']=$ligne->id_u;
						$_SESSION['nom']=$ligne->nom_u;
						$_SESSION['prenom']=$ligne->prenom_u;
						$_SESSION['sexe']=$ligne->sexe;
						$_SESSION['adresse']=$ligne->adresse;
						$_SESSION['telephone']=$ligne->telephone;
						$_SESSION['e_mail']=$ligne->e_mail;
						$_SESSION['password']=$ligne->password;
						$_SESSION['status']=$ligne->status;
						
						$_SESSION['langue']='francais';
					
				$sqlP=$connexion->query("SELECT *FROM patients p WHERE p.id_u='$ligne->id_u'");
				$sqlM=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$ligne->id_u'");
				$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$ligne->id_u'");
				$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$ligne->id_u'");
				$sqlC=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$ligne->id_u'");

				$comptidP=$sqlP->rowCount();
				$comptidM=$sqlM->rowCount();
				$comptidI=$sqlI->rowCount();
				$comptidL=$sqlL->rowCount();
				$comptidC=$sqlC->rowCount();

					if($comptidP!=0)
					{
						$sqlP->setFetchMode(PDO::FETCH_OBJ);
						$ligne=$sqlP->fetch();
						$_SESSION['numero']=$ligne->numero;
						// echo $_SESSION['numero'];
						
						echo '<script text="text/javascript">document.location.href="accueil.php"</script>';
					}
					if($comptidM!=0)
					{
						$sqlM->setFetchMode(PDO::FETCH_OBJ);
						$ligne=$sqlM->fetch();
						$_SESSION['codeM']=$ligne->codemedecin;
						// echo $_SESSION['codeM'];
						
						echo '<script text="text/javascript">document.location.href="patients1.php"</script>';
					
					}
					if($comptidI!=0)
					{
						$sqlI->setFetchMode(PDO::FETCH_OBJ);
						$ligne=$sqlI->fetch();
						$_SESSION['codeI']=$ligne->codeinfirmier;
						// echo $_SESSION['codeI'];
						
						echo '<script text="text/javascript">document.location.href="index.php"</script>';
					}
					if($comptidL!=0)
					{
						$sqlL->setFetchMode(PDO::FETCH_OBJ);
						$ligne=$sqlL->fetch();
						$_SESSION['codeL']=$ligne->codelabo;
						// echo $_SESSION['codeL'];
						
						echo '<script text="text/javascript">document.location.href="patients1.php"</script>';
					
					}
					if($comptidC!=0)
					{
						$sqlC->setFetchMode(PDO::FETCH_OBJ);
						$ligne=$sqlC->fetch();
						$_SESSION['codeC']=$ligne->codecoordi;
						// echo $_SESSION['codeC'];
						
						echo '<script text="text/javascript">document.location.href="acceuil.php"</script>';
					
					}
					
					// echo '	'. 	$_SESSION['nom'];
				}
			
			}else{
				$_SESSION['connect']=false;
				echo '<script text="text/javascript">alert("Saisie incorrect");</script>';
				echo '<script text="text/javascript">document.location.href="index.php"</script>';
				}
				
		}else{
			echo '<script text="text/javascript">alert("Vous n\'avez rien saisi");</script>';
			echo '<script text="text/javascript">document.location.href="index.php"</script>';
		}
	} 

}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}


?>