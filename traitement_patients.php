<?php
try
{
	include("connect.php");

	if(isset($_POST['savebtn']))
	{
	
 		if(!($_FILES['photoPa']['error']== UPLOAD_ERR_NO_FILE))
		{
			$extAuthorisedPic=array('.png','.gif','.jpg','.jpeg','.PNG','.GIF','.JPG','.JPEG','');
			$extensionPic=strrchr($_FILES['photoPa']['name'],'.');
			//echo $extensionPic.'<br/>';
			
			if(!in_array($extensionPic,$extAuthorisedPic))
			{
				echo '<script language="javascript"> alert("C pas une photo!");</script>';
				echo '<script language="javascript">document.location.href="patients.php"</script>';
				
			}else{
				if($_FILES['photoPa']['error']== UPLOAD_ERR_FORM_SIZE)
				{
						echo '<script language="javascript"> alert ("La taille de cette photo depasse la taille autorisée!");</script>';
						echo '<script language="javascript">document.location.href="patients.php"</script>';
				}else{
					$nomPic=time().strtolower($extensionPic);
					echo $nomPic;
					$resultat=move_uploaded_file($_FILES['photoPa']['tmp_name'], $nomPic);
					
					/* if($resultat)
					{
						echo "<br/>Fichier transferé.<br/>";
						echo '<img src="'.$nomPic.'" height="70px" width="70px"/>';
					} */
					
					
				
				}

			}
			$nom=strip_tags($_POST['nom']);
			$prenom=strip_tags($_POST['prenom']);
			$mail=strip_tags($_POST['mail']);
			$phone=strip_tags($_POST['phone']);
			$date=$_POST['dateNaiss'];
			$password=$_POST['password'];
			
			if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#",@$_POST['mail']))
			{
				if (preg_match("#^[0+]257[ ]7[1-9][ ]?([0-9]{3}[ ]?){2}$#",@$_POST['phone']))
				{
				
					$resultats=$connexion->query("SELECT e_mail FROM utilisateurs WHERE e_mail='$mail'");

					//$num_rows=$resultats->fetchColumn();
					$num_rows=$resultats->rowCount();
					if( $num_rows == 0)
					{
						
						// echo $nom.'__'.$date.'__'.$password;

						$resultats=$connexion->prepare('INSERT INTO utilisateurs (nom_u,prenom_u,sexe,date_naissance,adresse,telephone,e_mail,password) VALUES(:nom, :prenom, :sexe, :dateNaiss, :adresse, :phone, :e_mail, :password)');
						
						$resultats->execute(array(
						'nom'=>$nom,/*le 'nom' est le même que celui de ':nom' de la ligne "prepare(...VALUES>"*/
						'prenom'=>$prenom,
						'sexe'=>$_POST['sexe'],
						'dateNaiss'=>$_POST['dateNaiss'],
						'adresse'=>$_POST['adresse'],
						'phone'=>$_POST['phone'],
						'e_mail'=>$_POST['mail'],
						'password'=>$_POST['password']
						)); 
						
						$res=$connexion->query('SELECT *FROM utilisateurs ORDER BY id_u DESC LIMIT 1');
						
						$res->setFetchMode(PDO::FETCH_OBJ);
						while($ligne=$res->fetch())
						{
							$resultats=$connexion->prepare('INSERT INTO patients (id_u,numero,anneeadhesion,photo,profession) VALUES(:id,:numero,:annee,:photo,:profess)');
							$resultats->execute(array(
							'id'=>$ligne->id_u,
							'numero'=>$_POST['num'],
							'annee'=>$_POST['anAd'],
							'photo'=>$nomPic,
							'profess'=>$_POST['profession']
							))or die( print_r($connexion->errorInfo()));

						}
					
					//header ("Location:utilisateurs1.php");
					}else{
				
						echo '<script type="text/javascript"> alert("L\'e_mail saisi existe deja");</script>';
						echo '<script type="text/javascript">document.location.href="patients.php";</script>';
						
					}
				
				}else{
						echo '<script type="text/javascript">alert("Le numero de telephone '.$_POST['phone'].' saisi est non valide :\n- Verifiez si vous avez saisi que des chiffres\n- Verifiez les espacement entre les chiffres\n\n\nOu tout simplement SUIVEZ L\'EXEMPLE");</script>';
						echo '<script type="text/javascript">document.location.href="patients.php";</script>';
				}
				
			}else{
					echo '<script type="text/javascript">alert("L\'e_mail '.$_POST['mail'].' saisi est non valide\n\nCliquer dans la zone de saisi d\'e_mail puis SUIVEZ L\'EXEMPLE");</script>';
					echo '<script type="text/javascript">document.location.href="patients.php";</script>';
			}
			
		}
		 /*else{
			echo '<script language="javascript"> alert("Veuillez charger une photo!!");</script>';
			echo '<script language="javascript">document.location.href="patients.php"</script>';
		} */
	

	}
	
	if(isset($_POST['updatebtn']))
	{
	
		$newNum=$_POST['num'];
		$newNom=$_POST['nom'];
		$newPrenom=$_POST['prenom'];
		$newSexe=$_POST['sexe'];
		$newDateNaiss=$_POST['dateNaiss'];
		$newAdresse=$_POST['adresse'];
		$newPhone=$_POST['phone'];
		$newMail=$_POST['mail'];
		$newAnAd=$_POST['anAd'];
		$newPhoto=$_POST['photoPa'];
		$newProfession=$_POST['profession'];
		$newPassword=$_POST['password'];
		$modifierUti=$_POST['idopere'];
		
		if(!($_FILES['photoPa']['error']== UPLOAD_ERR_NO_FILE))
		{
				$extAuthorisedPic=array('.png','.gif','.jpg','.jpeg','.PNG','.GIF','.JPG','.JPEG');
				$extensionPic=strrchr($_FILES['photoPa']['name'],'.');
				//echo $extensionPic.'<br/>';
				
				if(!in_array($extensionPic,$extAuthorisedPic))
				{
					echo '<script language="javascript"> alert("C pas une photo!");</script>';
					echo '<script language="javascript">document.location.href="patients1.php"</script>';
					
				}else{
					if($_FILES['photoPa']['error']== UPLOAD_ERR_FORM_SIZE)
					{
							echo '<script language="javascript"> alert ("La taille de cette photo depasse la taille autorisée!");</script>';
							echo '<script language="javascript">document.location.href="patients1.php"</script>';
					}else{
						$nomPic=time().strtolower($extensionPic);
						echo $nomPic;
						$resultat=move_uploaded_file($_FILES['photoPa']['tmp_name'], $nomPic);
						
						/* if($resultat)
						{
							echo "<br/>Fichier transferé.<br/>";
							echo '<img src="'.$nomPic.'" height="70px" width="70px"/>';
						} */
						
						
					
					}

				}
			
			
			$resultats=$connexion->query("SELECT id_u FROM utilisateurs WHERE id_u='$modifierUti'");
			//$num_rows=$resultats->fetchColumn();
				$num_rows=$resultats->rowCount();
			if( $num_rows != 0)
			{
				$resultats=$connexion->prepare('UPDATE utilisateurs SET nom_u=:nom, prenom_u=:prenom, sexe=:sexe, date_naissance=:dateNaiss, adresse=:adresse, telephone=:phone, e_mail=:mail, password=:password WHERE id_u=:iduti ');
				$resultats->execute(array(
				'nom'=>$_POST['nom'],/*le 'nom' est le même que celui de ':nom' de la ligne "prepare(...VALUES>"*/
				'prenom'=>$newPrenom,
				'sexe'=>$newSexe,
				'dateNaiss'=>$newDateNaiss,
				'adresse'=>$newAdresse,
				'phone'=>$newPhone,
				'mail'=>$newMail,
				'password'=>$newPassword,
				'iduti'=>$modifierUti
				));
				
				$resultats=$connexion->prepare('UPDATE patients SET numero=:num, anneeadhesion=:annee, photo=:photo, profession=:profession  WHERE id_u=:iduti ');
				$resultats->execute(array(
				'num'=>$newNum,
				'annee'=>$newAnAd,
				'photo'=>$nomPic,
				'profession'=>$newProfession,
				'iduti'=>$modifierUti
				));
			}
		
		}/*else{
			echo '<script language="javascript"> alert("Veuillez charger une photo!!");</script>';
			echo '<script language="javascript">document.location.href="patients.php"</script>';
		} */
		
		header("Location:patients1.php");		
	}
	
/* 	if(isset($_POST['showbtn']))
	{
	header("Location:utilisateurs1.php");	
	} */
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