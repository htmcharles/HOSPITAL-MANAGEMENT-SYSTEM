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
			
			
			if(isset($_GET['id_consu']))
			{
				$id_consuHosp=$_GET['id_consu'];
			}else{
				$id_consuHosp=0;			
			}
			
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
			
			
			$resultats=$connexion->prepare('INSERT INTO patients_hosp (id_uHosp,numero,reference_idHosp,numroomPa,numlitPa,dateEntree,heureEntree,prixroom,statusPaHosp,insupercent_hosp,id_assuHosp,nomassuranceHosp,id_consuHosp,createdbyPa) VALUES(:idPa,:num,:referenceId,:numroom,:numlit,:dateIn,:heureIn,:prixroom,:statusPaHosp,:bill,:idassu,:nomassu,:id_consuHosp,:idcreator)');
			
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
			'id_consuHosp'=>$id_consuHosp,
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
			
			if($id_consuHosp!=0)
			{
				$updateroom=$connexion->prepare('UPDATE consultations SET hospitalized=2 WHERE id_consu=:id_consu ');
				$updateroom->execute(array(
				'id_consu'=>$id_consuHosp
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
		
			$newIdroom=$_POST['roomnumber'];
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
			
			$resultNewPriceroom=$connexion->prepare('SELECT *FROM rooms r, '.$presta_assu.' p WHERE r.id_prestationHosp=p.id_prestation AND r.id_room=:idroom');
			$resultNewPriceroom->execute(array(
			'idroom'=>$newIdroom
			));
			
			if($ligneNewPriceroom=$resultNewPriceroom->fetch(PDO::FETCH_OBJ))
			{
				$newnumroom=$ligneNewPriceroom->numroom;
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

			
			/*------------Free rooms---------------*/
			
			if($_GET['numlitEdit']=="1")
			{
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusA=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			
			}elseif($_GET['numlitEdit']=="2"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusB=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="3"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusC=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="4"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusD=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="5"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusE=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="6"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusF=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="7"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusG=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="8"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusH=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="9"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusI=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="10"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusJ=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="11"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusK=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="12"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusL=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="13"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusM=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}elseif($_GET['numlitEdit']=="14"){
			
				$updateeditroom=$connexion->prepare('UPDATE rooms SET statusN=0 WHERE numroom=:numroom ');
				$updateeditroom->execute(array(
				'numroom'=>$_GET['numroomEdit']
				));
			}
			
			
			/*-----------Busy rooms--------------*/
			
			if($newnumlit=="1")
			{
				$updateroom=$connexion->prepare('UPDATE rooms SET statusA=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="2"){
				$updateroom=$connexion->prepare('UPDATE rooms SET statusB=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="3"){
				$updateroom=$connexion->prepare('UPDATE rooms SET statusC=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="4"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusD=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="5"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusE=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="6"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusF=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="7"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusG=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="8"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusH=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="9"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusI=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="10"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusJ=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="11"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusK=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="12"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusL=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="13"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusM=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}elseif($newnumlit=="14"){
			
				$updateroom=$connexion->prepare('UPDATE rooms SET statusN=1 WHERE numroom=:numroom ');
				$updateroom->execute(array(
				'numroom'=>$newnumroom
				));
			}
/* 
		echo 'Id Pa_hosp : '.$_GET['idhosp'].'<br/>';
		echo 'Num Room Before : '.$_GET['numroomEdit'].'<br/>';
		echo 'Num Lit Before : '.$_GET['numlitEdit'].'<br/><br/>';
		
		echo 'Id Pa_hosp : '.$_GET['idhosp'].'<br/>';
		echo 'Id room After : '.$newIdroom.'<br/>';
		echo 'Num Room After : '.$newnumroom.'<br/>';
		echo 'Num Lit After : '.$newnumlit.'<br/>';
		echo 'Price room After : '.$newprixroom;
		 */	
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