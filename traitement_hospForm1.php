<?php 
try
{
	session_start();
	include("connect.php");
	
	if(isset($_GET['num']))
	{

		$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
		$result->execute(array(
		'operation'=>$_GET['num']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		while($ligne=$result->fetch())
		{
			$idPa=$ligne->id_u;
			$num=$ligne->numero;
			$referenceId=$ligne->reference_id;
			$nom_uti=$ligne->nom_u;
			$prenom_uti=$ligne->prenom_u;
			$sexe=$ligne->sexe;
			$dateN=$ligne->date_naissance;
			$poidsPa=$ligne->poidsPa;
			$taillePa=$ligne->taillePa;
			$temperaPa=$ligne->temperaturePa;
			$tensionartPa=$ligne->tensionarteriellePa;
			$poulsPa=$ligne->poulsPa;
			$billpercent=$ligne->bill;
			$idassu=$ligne->id_assurance;
			$province=$ligne->province;
			$district=$ligne->district;
			$secteur=$ligne->secteur;
			$profession=$ligne->profession;
			$site=$_GET['num'];
		}
		$result->closeCursor();
		
		

		$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//reçoit l'année de naissance
		$month=$dateN[5].''.$dateN[6].'	';//reçoit le mois de naissance

		$an= date('Y')-$old.'	';//recupere l'âge en année
		$mois= date('m')-$month.'	';//recupere l'âge en mois

		if($mois<0)
		{
			$an= ($an-1).' ans	'.(12+$mois).' mois';
			// echo $an= $an-1;

		}else{

			$an= $an.' ans';
			//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
			// echo $mois= date('m')-$month;
		}
				
													
			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsu->execute(array(
				'idassu'=>$idassu
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
				{
					$nomassu=''.$ligneNomAssu->nomassurance.'';
					$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
				}
			}
		
	
		if(isset($_POST['savehospbtn']))
		{

			$idPa = $_POST['idPa'];
			$num = $_POST['num'];
			$idroom = $_POST['roomnumber'];
			$numlit = $_POST['litnumber'];
			$statusPaHosp = 1;
			$dateIn = $_POST['dateEntree'];
			
			$heureIn=$_POST['heureEntree'];
			$minuteIn=$_POST['minuteEntree'];
			
			if($_POST['heureEntree']<10)
			{
				$heureIn= '0'.$_POST['heureEntree'];
			}
			
			if($_POST['minuteEntree']<10)
			{
				$minuteIn= '0'.$_POST['minuteEntree'];
			}
			$fullheureIn = $heureIn.':'.$minuteIn;
			
			
			$resultPriceroom=$connexion->prepare('SELECT *FROM rooms r, '.$presta_assu.' p WHERE r.id_prestationHosp=p.id_prestation AND r.id_room=:idroom');
			$resultPriceroom->execute(array(
			'idroom'=>$idroom
			));
			
			if($lignePriceroom=$resultPriceroom->fetch(PDO::FETCH_OBJ))
			{
				$prixroom=$lignePriceroom->prixpresta;
				$numroom=$lignePriceroom->numroom;
			}
			
			$comptePricerooms=$resultPriceroom->rowCount();
			
			
			$resultats=$connexion->prepare('INSERT INTO patients_hosp (id_uHosp,numero,reference_idHosp,numroomPa,numlitPa,dateEntree,heureEntree,prixroom,statusPaHosp,insupercent_hosp,id_assuHosp,nomassuranceHosp,createdbyPa) VALUES(:idPa,:num,:referenceId,:numroom,:numlit,:dateIn,:heureIn,:prixroom,:statusPaHosp,:bill,:idassu,:nomassu,:idcreator)');
			
			$resultats->execute(array(
			'idPa'=>$idPa,
			'num'=>$num,
			'referenceId'=>$referenceId,
			'numroom'=>$numroom,
			'numlit'=>$numlit,
			'dateIn'=>$dateIn,
			'heureIn'=>$fullheureIn,
			'prixroom'=>$prixroom,
			'statusPaHosp'=>$statusPaHosp,
			'bill'=>$billpercent,
			'idassu'=>$idassu,
			'nomassu'=>$nomassu,
			'idcreator'=>$_SESSION['id']
			));
			
			if($numlit=="A")
			{
				$updateroom=$connexion->prepare('UPDATE rooms SET statusA=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$numroom
				));
			}elseif($numlit=="B"){
				$updateroom=$connexion->prepare('UPDATE rooms SET statusB=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$numroom
				));
			}
			
			
			if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				header('Location:patients1_hosp.php?english='.$_GET['english'].'');						
			
			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					header('Location:patients1_hosp.php?francais='.$_GET['francais'].'');
					
				}else{
					header("Location:patients1_hosp.php");
				}
			}
		}
		
		
		if(isset($_POST['updatehospbtn']))
		{
		
			$newnumroom=$_POST['roomnumber'];
			$newnumlit=$_POST['litnumber'];
			$newdateIn=$_POST['dateEntree'];
			$modifierHosp=$_GET['idhosp'];
			
			$statusPaHosp = 1;
			
			$heureIn=$_POST['heureEntree'];
			$minuteIn=$_POST['minuteEntree'];
			
			if($_POST['heureEntree']<10)
			{
				$heureIn= '0'.$_POST['heureEntree'];
			}
			
			if($_POST['minuteEntree']<10)
			{
				$minuteIn= '0'.$_POST['minuteEntree'];
			}			
			$newfullheureIn = $heureIn.':'.$minuteIn;
			
			$resultNewPriceroom=$connexion->prepare('SELECT *FROM rooms r, '.$presta_assu.' p WHERE r.id_prestationHosp=p.id_prestation AND r.numroom=:numroom');
			$resultNewPriceroom->execute(array(
			'numroom'=>$newnumroom
			));
			
			if($ligneNewPriceroom=$resultNewPriceroom->fetch(PDO::FETCH_OBJ))
			{
				$newprixroom=$ligneNewPriceroom->prixpresta;
			}
			
			
			$resultats=$connexion->prepare('UPDATE patients_hosp SET numroomPa=:numroom, numlitPa=:numlit, dateEntree=:dateIn, heureEntree=:heureIn, prixroom=:prixroom, statusPaHosp=:statusPaHosp, insupercent_hosp=:billpercent, id_assuHosp=:idassu, nomassuranceHosp=:nomassu, updateBy=:idupdater WHERE id_hosp=:idhosp');
			$resultats->execute(array(
			'numroom'=>$newnumroom,
			'numlit'=>$newnumlit,
			'dateIn'=>$newdateIn,
			'heureIn'=>$newfullheureIn,
			'prixroom'=>$newprixroom,
			'statusPaHosp'=>$statusPaHosp,
			'billpercent'=>$billpercent,
			'idassu'=>$idassu,
			'nomassu'=>$nomassu,
			'idupdater'=>$_SESSION['id'],
			'idhosp'=>$modifierHosp
			));

			
			if($_GET['numlitEdit']=="A")
			{
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusA=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			
			}elseif($_GET['numlitEdit']=="B"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusB=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}
			
			
			if($newnumlit=="A")
			{
				$updateroom=$connexion->prepare('UPDATE rooms SET statusA=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="B"){
				$updateroom=$connexion->prepare('UPDATE rooms SET statusB=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}
			
		
			header("Location:patients1_hosp.php");		
		}
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