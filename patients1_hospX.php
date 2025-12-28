<?php
session_start();

include("connectLangues.php");
include("connect.php");
include("serialNumber.php");



	$annee = date('Y').'-'.date('m').'-'.date('d');


if(isset($_GET['num']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
	$result->execute(array(
	'operation'=>$_GET['num']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
	$num=$ligne->numero;
	$nom_uti=$ligne->nom_u;
	$prenom_uti=$ligne->prenom_u;
	$sexe=$ligne->sexe;
	$dateN=$ligne->date_naissance;
	$province=$ligne->province;
	$district=$ligne->district;
	$secteur=$ligne->secteur;
	$profession=$ligne->profession;
	$assuId=$ligne->id_assurance;
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
	
}


	if(isset($_GET['sortie']))
	{
		if($_GET['createBill']==1)
		{
			createON('H');
		}
		
		if(isset($_GET['id_consuHosp']))
		{
			$id_consuHosp=$_GET['id_consuHosp'];

            /*----------Update Consultations Clinic----------------*/

            $updateroom=$connexion->prepare('UPDATE consultations SET hospitalized=0 WHERE id_consu=:id_consu ');
            $updateroom->execute(array(
                'id_consu'=>$id_consuHosp
            ));

        }
		
		$idBilling=$_GET['idbill'];
		$idhosp=$_GET['idhosp'];
		$heureSortie = date('H').':'.date('i').':'.date('s');
		$statusPaHosp = 0;
		$numroom=$_GET['numroom'];

				
		/*----------Update Hosp----------------*/
		
		$updateIdPatientHosp=$connexion->prepare('UPDATE patients_hosp ph SET ph.id_factureHosp=:idbill, ph.statusPaHosp=:statusPaHosp, ph.codecashierHosp=:codecash WHERE ph.id_hosp=:idhosp');

		$updateIdPatientHosp->execute(array(
		'idhosp'=>$_GET['idhosp'],
		'idbill'=>$idBilling,
		'statusPaHosp'=>$statusPaHosp,
		'codecash'=>$_SESSION['codeCash']
		
		))or die( print_r($connexion->errorInfo()));
			
				/*----------Update Rooms----------------*/
				
				if($_GET['numlit']=='1')
				{
					$statusA=0;
										
					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusA=:statusA WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusA'=>$statusA,
					'numroom'=>$numroom
					
					))or die( print_r($connexion->errorInfo()));
					
					
				}elseif($_GET['numlit']=='2'){
				
					$statusB=0;
										
					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusB=:statusB WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusB'=>$statusB,
					'numroom'=>$numroom
					
					))or die( print_r($connexion->errorInfo()));
					
				}elseif($_GET['numlit']=='3'){

					$statusC=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusC=:statusC WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusC'=>$statusC,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='4'){

					$statusD=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusD=:statusD WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusD'=>$statusD,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='5'){

					$statusE=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusE=:statusE WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusE'=>$statusE,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='6'){

					$statusF=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusF=:statusF WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusF'=>$statusF,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='7'){

					$statusG=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusG=:statusG WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusG'=>$statusG,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='8'){

					$statusH=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusH=:statusH WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusH'=>$statusH,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='9'){

					$statusI=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusI=:statusI WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusI'=>$statusI,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='10'){

					$statusJ=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusJ=:statusJ WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusJ'=>$statusJ,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='11'){

					$statusK=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusK=:statusK WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusK'=>$statusK,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='12'){

					$statusL=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusL=:statusL WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusL'=>$statusL,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='13'){

					$statusM=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusM=:statusM WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusM'=>$statusM,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}elseif($_GET['numlit']=='14'){

					$statusN=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusN=:statusN WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusN'=>$statusN,
					'numroom'=>$numroom

					))or die( print_r($connexion->errorInfo()));

				}
		


        /*----------Update Med_Surge----------------*/
		
		$updateIdFactureMedSurge=$connexion->prepare('UPDATE med_surge_hosp ms SET ms.id_factureMedSurge=:idbill, ms.codecashier=:codecashier WHERE ms.id_hospSurge=:idhosp AND ms.numero=:num AND ms.id_factureMedSurge=""');

		$updateIdFactureMedSurge->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'] 
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Inf----------------*/

		$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.id_factureMedInf=:idbill, mi.codecashier=:codecashier WHERE mi.id_hospInf=:idhosp AND mi.numero=:num AND mi.id_factureMedInf=""');

		$updateIdFactureMedInf->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']

		))or die( print_r($connexion->errorInfo()));



		/*----------Update Med_Labo----------------*/
		
		$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.id_factureMedLabo=:idbill, ml.codecashier=:codecashier WHERE ml.id_hospLabo=:idhosp AND ml.numero=:num AND ml.id_factureMedLabo=""');

		$updateIdFactureMedLabo->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Radio----------------*/
		
		$updateIdFactureMedRadio=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.id_factureMedRadio=:idbill, mr.codecashier=:codecashier WHERE mr.id_hospRadio=:idhosp AND mr.numero=:num AND mr.id_factureMedRadio=""');

		$updateIdFactureMedRadio->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Consom----------------*/
		
		$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.id_factureMedConsom=:idbill, mco.codecashier=:codecashier WHERE mco.id_hospConsom=:idhosp AND mco.numero=:num AND mco.id_factureMedConsom=""');

		$updateIdFactureMedConsom->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Medoc----------------*/
		
		$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.id_factureMedMedoc=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_hospMedoc=:idhosp AND mdo.numero=:num AND mdo.id_factureMedMedoc=""');

		$updateIdFactureMedMedoc->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		))or die( print_r($connexion->errorInfo()));



		/*----------Update Med_Kine----------------*/

		$updateIdFactureMedKine=$connexion->prepare('UPDATE med_kine_hosp mk SET mk.id_factureMedKine=:idbill, mk.codecashier=:codecashier WHERE mk.id_hospKine=:idhosp AND mk.numero=:num AND mk.id_factureMedKine=""');

		$updateIdFactureMedKine->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']

		))or die( print_r($connexion->errorInfo()));



		/*----------Update Med_Ortho----------------*/

		$updateIdFactureMedOrtho=$connexion->prepare('UPDATE med_ortho_hosp mo SET mo.id_factureMedOrtho=:idbill, mo.codecashier=:codecashier WHERE mo.id_hospOrtho=:idhosp AND mo.numero=:num AND mo.id_factureMedOrtho=""');

		$updateIdFactureMedOrtho->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']

		))or die( print_r($connexion->errorInfo()));



        /*----------Update Med_Consult----------------*/

        $updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.id_factureMedConsu=:idbill, mc.codecashier=:codecashier WHERE mc.id_hospMed=:idhosp AND mc.numero=:num AND mc.id_factureMedConsu=""');

        $updateIdFactureMedConsult->execute(array(
            'idbill'=>$idBilling,
            'num'=>$_GET['numPa'],
            'idhosp'=>$idhosp,
            'codecashier'=>$_SESSION['codeCash']

        ))or die( print_r($connexion->errorInfo()));




    }


?>

<!doctype html>
<html lang="en">
<noscript>
This page requires Javascript.
Please enable it in your browser.
</noscript>
<head>
	<meta charset="utf-8"/>
	<title><?php echo getString(92);?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
			<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<script src="script.js"></script>
			
			<!------------------------------------>
			
	<link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
	
	<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	

	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<script src="myQuery.js"></script>
	

</head>

<body>
<?php
$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true)
{
	if($status==1)
	{
?>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li class="">			
					<form method="post" action="patients1_hosp.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="patients1_hosp.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="patients1_hosp.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(29);?></a>
					<?php
					}					
					?>
						<br/>						
					
						<input type="text" name="Pass" id="Pass" placeholder="<?php echo getString(26);?>" style="margin-top:10px;margin-bottom:0;height:20px;"/>
						
						<input type="submit" name="confirmPass" id="confirmPass" class="btn"  value="<?php echo getString(27);?>"/>
						
					
					</form>
				</li>	
				</ul>
			</div><!--/.nav-collapse -->
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div><br><br><br><br><br>

<?php
if(isset($_SESSION['infhosp']))
{
	$infhosp=$_SESSION['infhosp'];
	
	if($infhosp!=0)
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
	}
}
?>

<?php
if(isset($_SESSION['codeO']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}
?>

<div class="account-container" style="width:90%; text-align:center;">

<?php

$id=$_SESSION['id'];

$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$id'");
$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");

$comptidI=$sqlI->rowCount();
$comptidO=$sqlO->rowCount();
$comptidD=$sqlD->rowCount();
$comptidC=$sqlC->rowCount();


if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidC!=0)
{
?>
<div id='cssmenu' style="text-align:center">

<ul style="margin-top:20px;background:none;border:none;">
	<?php
	if($comptidI!=0)
	{
	?>
		<li style="width:50%;"><a href="patients1_inf.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Voir autres patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Voir autres patients</a></li>
	<?php
	}elseif($comptidC!=0){
	?>
		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients de la clinique"><i class="fa fa-wheelchair fa-lg fa-fw"></i>Patients de la clinique</a></li>
	<?php
	}
	
	if($comptidO!=0)
	{
	?>
		<li style="width:50%;">----</li>
							
	<?php
	}
	
	if($comptidD!=0)
	{
	?>
		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&all=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Liste des patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(92);?></a></li>
							
	<?php
	}
	?>
	
	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>

</ul>

<ul style="margin-top:20px; background:none;border:none;">
	<?php
	if($comptidI!=0)
	{
	?>
		<a href="utilisateurs_hosp.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(88);?></a>
	<?php
	}
	?>
		
	<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>
		
</ul>
		
</div>


<?php 
}

	if(isset($_GET['divPa']))
	{
		
		/*-----------------Requête pour Nurse--------------*/
		
		if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidC!=0)
		{
			$resultatsI=$connexion->query('SELECT *FROM utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.id_factureHosp IS NULL AND u.full_name LIKE \'%'.$_GET['fullname'].'%\' ');
			/* $resultatsI->execute(array(
			'idPa'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
		 */
			
			$resultatsI->setFetchMode(PDO::FETCH_OBJ);
			
			$comptPaI=$resultatsI->rowCount();
			
		}
	?>
	
	<div style="margin-top:15px;">
	
	
	<?php
	
	if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidC!=0)
	{
		if($comptPaI!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			<thead> 
				<tr>
					<th>S/N</th>
					<th><?php echo getString(222);?></th>
					<th><?php echo getString(9);?></th>
					<th><?php echo getString(11);?></th>
					<th><?php echo "Numero chambre";?></th>
					<th><?php echo "Lit";?></th>
					<th><?php echo "Date d'entrée";?></th>
					<th><?php echo "Nbre de jours";?></th>
					<th colspan=2>Actions</th>
				</tr> 
			</thead>
			
			<tbody> 
			
			<span style="position:relative; font-size:250%;"><?php echo getString(60);?></span>
			
<?php

			while($ligneI=$resultatsI->fetch())//on recupere la liste des éléments
			{
?>
				<tr style="text-align:center;">
					<td><?php echo $ligneI->numero;?></td>
					<td><?php echo $ligneI->reference_idHosp;?></td>
					<td><?php echo $ligneI->full_name ;?></td>
					<td>
					<?php 
					if($ligneI->sexe=="M")
					{
						echo getString(12);
					}elseif($ligneI->sexe=="F")
					{
						echo getString(13);
					}
					?>
					</td>
					
					<td><?php echo $ligneI->numroomPa;?></td>
					<td><?php echo $ligneI->numlitPa;?></td>
					<td><?php echo $ligneI->dateEntree.' à '.$ligneI->heureEntree;?></td>
					
					<td>
					<?php
					
					$dateIn=strtotime($ligneI->dateEntree);
					$todaydate=strtotime($annee);
					
					$datediff= abs($todaydate - $dateIn);
					
					$nbrejrs= floor($datediff /(60*60*24));
					
					if($nbrejrs==0)
					{
						$nbrejrs=1;
					}
						echo $nbrejrs;
					?>
					</td>
					
					<?php
					if($comptidI!=0)
					{
					?>
					<td>	
						<a class="btn" href="hospForm.php?updateidPahosp=ok&inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
					</td>
					<td>	
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>

					</td>
					<?php
					}
					
					if($comptidO!=0)
					{
					?>
					<td>	
						<a class="btn" href="hospForm.php?updateidPahosp=ok&inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
					</td>
					<td>	
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>

					</td>
					<?php
					}
					
					if($comptidD!=0)
					{
					?>
					<td></td>
					<td>	
						<a class="btn" href="categoriesbill_hosp_medecin.php?idmed=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>

					</td>
					<?php
					}
					
					if($comptidC!=0)
					{
					?>
					<td>
					<?php
					
					$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.surgefait=1 AND ms.numero=:num AND ms.id_factureMedSurge="" AND ms.id_hospSurge=:idhosp ORDER BY ms.id_medsurge');
					$resultMedSurge->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

					$comptMedSurge=$resultMedSurge->rowCount();



					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');
					$resultMedInf->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));

					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

					$comptMedInf=$resultMedInf->rowCount();



					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.examenfait=0 AND ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');
					$resultMedLabo->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

					$comptMedLabo=$resultMedLabo->rowCount();



					$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.radiofait=0 AND mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
					$resultMedRadio->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

					$comptMedRadio=$resultMedRadio->rowCount();



					$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom="" AND mco.id_hospConsom=:idhosp ORDER BY mco.id_medconsom');		
					$resultMedConsom->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

					$comptMedConsom=$resultMedConsom->rowCount();



					$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.id_hospMedoc=:idhosp ORDER BY mdo.id_medmedoc');		
					$resultMedMedoc->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

					$comptMedMedoc=$resultMedMedoc->rowCount();



					$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk WHERE mk.numero=:num AND mk.id_factureMedKine="" AND mk.id_hospKine=:idhosp ORDER BY mk.id_medkine');
					$resultMedKine->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));

					$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

					$comptMedKine=$resultMedKine->rowCount();



					$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.id_hospOrtho=:idhosp ORDER BY mo.id_medortho');
					$resultMedOrtho->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));

					$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

					$comptMedOrtho=$resultMedOrtho->rowCount();



                    $resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu="" AND mc.id_hospMed=:idhosp ORDER BY mc.id_medconsu');
                    $resultMedConsult->execute(array(
                        'num'=>$ligneI->numero,
                        'idhosp'=>$ligneI->id_hosp
                    ));

                    $resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

                    $comptMedConsult=$resultMedConsult->rowCount();


/* 
                    if($comptMedSurge!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedKine!=0 OR $comptMedOrtho!=0 OR $comptMedConsult!=0)
					{
					?>	
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&id_uM=<?php echo '0';?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&previewprint=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							<i class="fa fa-money fa-1x fa-fw"></i>
						</a>
					<?php
					}
*/
					?>
					</td>
					
					<td>
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&id_uM=<?php echo '0';?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&id_consuHosp=<?php echo $ligneI->id_consuHosp;?>&previewprint=ok&sortie=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Sortir Patient
						</a>
					</td>
					
					<?php
					}
					?>
				</tr>
			<?php
			}
			$resultatsI->closeCursor();
					
			?>
			</tbody> 
	
		</table>
		<?php
		}else{
		?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th><?php echo getString(152).' ';?> 
						<?php 
							if($ligneI=$resultatsI->fetch())//on récupère la liste des éléments
							{
								echo $ligneI->full_name;
							}
						?>
						<?php echo ' '. getString(153);?></th>
					</tr>
				</thead> 
			<table>
	<?php
		}
	}
	
	?>
	
	
	<a href="patients1_hosp.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#listPatientHosp" style="margin-right:5px;" class="btn-large"><?php echo 'Afficher tous les patients hospitalisés';?></a>
	
	<br/><br/>
	
	</div>
	
	<?php
	}else{ echo '';}

	
		$getPaSentByDr=$connexion->query('SELECT *FROM consultations c WHERE c.hospitalized=1');
		/* $getPaSentByDr->execute(array(
			'idPa'=>$_GET['iduti']	
			))or die( print_r($connexion->errorInfo()));
		*/

		$getPaSentByDr->setFetchMode(PDO::FETCH_OBJ);
		
		$comptPaSentByDr=$getPaSentByDr->rowCount();
		
		if($comptPaSentByDr!=0 AND ($comptidD==0 OR $comptidO==0))
		{
		?>
			<h2 style="margin-top:10px;"><?php echo getString(276);?></h2>
			
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;margin-bottom:20px;margin-top:10px;">
				<thead> 
					<tr>
						<th style="width:10%">S/N</th> 
						<th style="width:25%"><?php echo getString(9);?></th>
						<th style="width:10%"><?php echo getString(11);?></th>
						<th style="width:15%"><?php echo 'Assurance';?></th> 
						<th style="width:20%"><?php echo 'Medecin expediteur';?></th> 
						<th style="padding:0; width:20%" colspan=2>Actions</th>
					</tr> 
				</thead>
				
				<tbody> 
				
				<?php

				while($lignePaSentByDr=$getPaSentByDr->fetch())
				{
					$resultStatuPaHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.numero=:num AND ph.statusPaHosp=1');
					$resultStatuPaHosp->execute(array(
					'num'=>$lignePaSentByDr->numero
					));
						
					$compteStatuPaHosp=$resultStatuPaHosp->rowCount();

				?>
					<tr style="text-align:center;">
						<td><?php echo $lignePaSentByDr->numero;?></td>
						<td>
						<?php 
						
						$getPa=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
						$getPa->execute(array(
						'operation'=>$lignePaSentByDr->numero	
						));
						$getPa->setFetchMode(PDO::FETCH_OBJ);
			
						if($lignePa=$getPa->fetch())
						{
							$idPatient = $lignePa->id_u;
							$nomPatient = $lignePa->full_name;
							$sexePatient = $lignePa->sexe;
							$idAssuPatient = $lignePa->id_assurance;
							
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idAssuPatient
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$assuPatient=$ligneNomAssu->nomassurance;
								}
								
							$statuPatient = $lignePa->status;
							echo $nomPatient;
						}
						?>
						</td>
						
						<td>
						<?php 
						if($sexePatient=="M")
						{
							echo getString(12);
						}else{
							if($sexePatient=="F")
							echo getString(13);
						}
						?>
						</td>
						
						<td><?php echo $assuPatient;?></td>
						
						<td>
						<?php 
						
						$getDr=$connexion->prepare('SELECT *FROM utilisateurs u WHERE u.id_u=:operation');
						$getDr->execute(array(
						'operation'=>$lignePaSentByDr->id_uM	
						));
						$getDr->setFetchMode(PDO::FETCH_OBJ);
			
						if($ligneDr=$getDr->fetch())
						{
							$nomMedecin = $ligneDr->full_name;
							echo $nomMedecin;
						}
						?>
						</td>
						
						<td>	
						<?php
						if($compteStatuPaHosp==0)
						{
						?>
							<a class="btn" href="hospForm.php?idInf=<?php echo $_SESSION['id'];?>&num=<?php echo $lignePaSentByDr->numero;?>&id_consu=<?php echo $lignePaSentByDr->id_consu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
								Hospitaliser
							</a>
						<?php
						}else{
						?>
							Déjà hospitalisé
						<?php
						}
						?>
						</td>
						
						<td>
							<a class="btn" href="utilisateurs_hosp.php?iduti=<?php echo $idPatient;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($statuPatient==0){ echo "display:none";}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
						</td>
					</tr>
				<?php
				}
				$getPaSentByDr->closeCursor();
						
				?>
				</tbody> 
		
			</table>
			
<?php
		}


	if(!isset($_GET['num']) AND !isset($_GET['divPa']))
	{
	?>
				
	<form class="ajax" action="search.php" method="get" id="listPatientHosp">
		<p style="margin-top:50px">
			
			<table align="center">
				<tr>
					<td></td>
					
					<td>
						<h2><?php echo getString(20);?>s hospitalisés</h2>
					</td>
					
					<td></td>
				</tr>
				
				<tr>
					<td>
						<label for="q"><?php echo getString(80);?></label>
						<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
					</td>
					
					<td>
						<label for="r"><?php echo getString(223);?></label>
						<input type="text" name="r" id="r" onclick="ShowSearch('byri')"/>
					</td>
					
					<td>
						<label for="s"><?php echo getString(91);?></label>
						<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
					</td>
				</tr>
			</table>
			
		</p>
	</form>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsRI"></div>

	<script type="text/javascript">
	$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#q').keyup( function(){
		$field = $(this);
		$('#results').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_patients1_hosp.php?name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" style="margin:5px" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#results').html(data); // affichage des résultats dans le bloc
		}
		  });
		}		
	  });
	});

	</script>

	<script type="text/javascript">
	$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#s').keyup( function(){
		$field = $(this);
		$('#resultsSN').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_patients1_hosp.php?sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 's='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#resultsSN').html(data); // affichage des résultats dans le bloc
		}
		  });
		}		
	  });
	});

	</script>

	<script type="text/javascript">
	$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#r').keyup( function(){
		$field = $(this);
		$('#resultsRI').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_patients1_hosp.php?ri=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 'r='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#resultsRI').html(data); // affichage des résultats dans le bloc
		}
		  });
		}		
	  });
	});

	</script>

		<?php
		
	}


		
if(!isset($_GET['divPa']))
{

		try
		{
			function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
			{
				
				$caissrecep='';
			
				$pagination = '';
				$link = preg_replace('`%([^d])`', '%%$1', $link);
				if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
				if ( $nb_pages > 1 ) {
			 
					// Lien Précédent
					if ( $current_page > 1 )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];									
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}else{
								
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).''.$caissrecep.'#listPatientHosp" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}
						}
						
					}else{
						$pagination .= '';
					}
			 
					// Lien(s) début
					for ( $i=1 ; $i<=$firstlast ; $i++ ) {
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						
							}
						}
						
					}
			 
					// ... après pages début ?
					if ( ($current_page-$around) > $firstlast+1 )
						$pagination .= '<span class="current">&hellip;</span>';
			 
					// On boucle autour de la page courante
					$start = ($current_page-$around)>$firstlast ? $current_page-$around : $firstlast+1;
					$end = ($current_page+$around)<=($nb_pages-$firstlast) ? $current_page+$around : $nb_pages-$firstlast;
					for ( $i=$start ; $i<=$end ; $i++ ) {
						$pagination .= ' ';
						if ( $i==$current_page )
						{
							$pagination .= '<span class="current">'.$i.'</span>';
						}else{
						
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
								}else{
									
									$pagination .= '<a href="'.sprintf($link, $i).''.$caissrecep.'#listPatientHosp">'.$i.'</a>';				
								}
							}
						}
					}
			 
					// ... avant page nb_pages ?
					if ( ($current_page+$around) < $nb_pages-$firstlast )
						$pagination .= '<span class="current">&hellip;</span>';
			 
				// Lien(s) fin
					$start = $nb_pages-$firstlast+1;
					if( $start <= $firstlast ) $start = $firstlast+1;
					
					for ( $i=$start ; $i<=$nb_pages ; $i++ )
					{
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';	
								
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.'#listPatientHosp">'.$i.'</a>';				
							}
						}
						
					}
			 
					// Lien suivant
					if ( $current_page < $nb_pages )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							
							$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
							}else{
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).''.$caissrecep.'#listPatientHosp" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
										
							}
						}
						
					}else{
						$pagination .= '';
					}
				}
				return $pagination;
			}
	
				
			// Numero de page (1 par défaut)
				if( isset($_GET['page']) && is_numeric($_GET['page']) )
						$page = $_GET['page'];
					else
						$page = 1;
					 
					// Nombre d'info par page
					$pagination =5;
					 
					// Numero du 1er enregistrement à lire
					$limit_start = ($page - 1) * $pagination;


		?>

			<?php
			
			if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidC!=0)
			{
			
				$resultatsI=$connexion->query('SELECT *FROM utilisateurs u, patients_hosp ph WHERE ph.id_uHosp=u.id_u AND ph.statusPaHosp=1 ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'');
						
				$resultatsI->setFetchMode(PDO::FETCH_OBJ);
			
				$comptResultI=$resultatsI->rowCount();

			?>
				<table class="tablesorter tablesorter2" style="width:100%; margin-bottom:30px;">
				<?php
				
				if($comptResultI!=0)
				{
				?>	
					<thead> 
						<tr>
							<th style="width:10%">S/N</th> 
							<th style="width:10%"><?php echo getString(222);?></th> 
							<th style="width:20%"><?php echo getString(9);?></th>
							<th style="width:10%"><?php echo getString(11);?></th>
							<th style="width:10%"><?php echo "N° chambre";?></th>
							<th style="width:5%"><?php echo "Lit";?></th>
							<th style="width:10%"><?php echo "Date d'entrée";?></th>
							<th style="width:10%"><?php echo "Nbre de jours";?></th>
							<th style="padding:0; width:15%" colspan=3>Actions</th>
						</tr> 
					</thead>
			
					<tbody>
					<?php
					while($ligneI=$resultatsI->fetch())//on recupere la liste des éléments
					{
					?>
						<tr style="text-align:center;">
							<td><?php echo $ligneI->numero;?></td>
							<td><?php echo $ligneI->reference_idHosp;?></td>
							<td><?php echo $ligneI->full_name;?></td>
							<td>
							<?php 
							if($ligneI->sexe=="M")
							{
								echo getString(12);
							}elseif($ligneI->sexe=="F")
							{
								echo getString(13);
							}
							?>
							</td>
							
							<td><?php echo $ligneI->numroomPa;?></td>
							<td><?php echo $ligneI->numlitPa;?></td>
							<td>
							<?php
							
								echo $ligneI->dateEntree.' à '.$ligneI->heureEntree;
							?>
							</td>
							
							<td>
							<?php
							
							$dateIn=strtotime($ligneI->dateEntree);
							$todaydate=strtotime($annee);
							
							$datediff= abs($todaydate - $dateIn);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
								echo $nbrejrs;
							?>
							</td>
							
							<?php
							if($comptidI!=0)
							{
							?>
							<td>	
								<a class="btn" href="hospForm.php?updateidPahosp=ok&inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
							</td>

							<td>	
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
									Traiter
								</a>

							</td>
							<?php
							}
							
							if($comptidO!=0)
							{
							?>
							<td></td>

							<td>	
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
									Traiter
								</a>

							</td>
							<?php
							}
							
							if($comptidD!=0)
							{
							?>
							<td></td>
							<td>	
								<a class="btn" href="categoriesbill_hosp_medecin.php?idmed=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									Traiter
								</a>

							</td>
							<?php
							}
							
							if($comptidC!=0)
							{
							?>
							<td>
							<?php
							
							$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.surgefait=1 AND ms.numero=:num AND ms.id_factureMedSurge="" AND ms.id_hospSurge=:idhosp ORDER BY ms.id_medsurge');
							$resultMedSurge->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

							$comptMedSurge=$resultMedSurge->rowCount();



							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');
							$resultMedInf->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));

							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

							$comptMedInf=$resultMedInf->rowCount();



							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.examenfait=0 AND ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');
							$resultMedLabo->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();



							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.radiofait=0 AND mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
							$resultMedRadio->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

							$comptMedRadio=$resultMedRadio->rowCount();



							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom="" AND mco.id_hospConsom=:idhosp ORDER BY mco.id_medconsom');		
							$resultMedConsom->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsom=$resultMedConsom->rowCount();



							$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.id_hospMedoc=:idhosp ORDER BY mdo.id_medmedoc');		
							$resultMedMedoc->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

							$comptMedMedoc=$resultMedMedoc->rowCount();



							$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedKine="" AND mdo.id_hospKine=:idhosp ORDER BY mdo.id_medkine');
							$resultMedKine->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));

							$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

							$comptMedKine=$resultMedKine->rowCount();



							$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.id_hospOrtho=:idhosp ORDER BY mo.id_medortho');
							$resultMedOrtho->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));

							$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

							$comptMedOrtho=$resultMedOrtho->rowCount();


                            $resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu="" AND mc.id_hospMed=:idhosp ORDER BY mc.id_medconsu');
                            $resultMedConsult->execute(array(
                                'num'=>$ligneI->numero,
                                'idhosp'=>$ligneI->id_hosp
                            ));

                            $resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

                            $comptMedConsult=$resultMedConsult->rowCount();

/* 
                            if($comptMedSurge!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedKine!=0 OR $comptMedOrtho!=0 OR $comptMedConsult!=0)
							{
							?>	
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&id_uM=<?php echo '0';?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&previewprint=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									<i class="fa fa-money fa-1x fa-fw"></i>
								</a>
							<?php
							} 
					*/
							?>
							</td>
							
							<td>
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&id_uM=<?php echo '0';?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&id_consuHosp=<?php echo $ligneI->id_consuHosp;?>&previewprint=ok&sortie=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									Sortir Patient
								</a>
							</td>
							<?php
							}
							?>						
						</tr>
					<?php
					}
					$resultatsI->closeCursor();
					?>
					</tbody>
					
				<?php
				}else{
				?>
					<thead> 
						<tr>
							<th><?php echo 'Aucun patient hospitalisé';?></th>
						</tr> 
					</thead>
				<?php
				}
				?>
		
				</table>
			<?php
			}
			
		}

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}


?>

	<?php
	
	if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidC!=0)
	{
		echo '
		<tr>
			<td>';
			
			$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM utilisateurs u, patients_hosp ph WHERE ph.id_uHosp=u.id_u AND ph.statusPaHosp=1 ORDER BY u.nom_u');
			
			$nb_total=$nb_total->fetch();
			
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
			// Affichage
			echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		
		echo '
			</td>
		</tr>
		';
	}
	?>
	

</div>

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


function ShowSearch(search)
{
	if(search =='byname')
	{
		document.getElementById('results').style.display='inline';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsRI').style.display='none';
	}
	
	if(search =='bysn')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='inline';
		document.getElementById('resultsRI').style.display='none';
	}
	
	if(search =='byri')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsRI').style.display='inline';
	}
}

function ShowList(list)
{
	
	if( list =='Users')
	{
		document.getElementById('divMenuUser').style.display='inline';
		document.getElementById('divMenuMsg').style.display='none';
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
	}
	
	if( list =='Msg')
	{
		document.getElementById('divMenuMsg').style.display='inline';
		document.getElementById('divMenuUser').style.display='none';
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
	}
	
	if( list =='Liste')
	{
		document.getElementById('listOff').style.display='inline';
		document.getElementById('listOn').style.display='none';
	}
	
	if( list =='ListeNon')
	{
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
	}
	
}

function controlFormPassword(theForm){
	var rapport="";
	
	rapport +=controlPass(theForm.Pass);

		if (rapport != "") {
		alert("Please review the following fields:\n" + rapport);
					return false;
		 }
}


function controlPass(fld){
	var erreur="";
	
	if(fld.value=="")
	{
		erreur="Your new password\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;
}

</script>

<?php
	
	}else{
		echo '<script language="javascript"> alert("You\'ve been disabled!!\n Ask the administrator to enable you");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
}else{
	echo '<script language="javascript">document.location.href="index.php"</script>';
}



	if(isset($_POST['confirmPass']))
	{
		
		if(isset($_GET['receptioniste']))
		{
			$caissrecep= '&receptioniste='.$_GET['receptioniste'];
		}else{
			if(isset($_GET['caissier']))
			{
				$caissrecep= '&caissier='.$_GET['caissier'];
			}else{
				$caissrecep='';
			}
		}

	
		$pass = $_POST['Pass'];
		$iduti = $_SESSION['id'];
				
		$resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');
						
		$resultats->execute(array(
		'pass'=>$pass,
		'modifierIduti'=>$iduti
		))or die( print_r($connexion->errorInfo()));
			
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}

?>
</div>

<div> <!-- footer -->
	<?php
		include('footer.php');
	?>
</div> <!-- /footer -->
	
</body>

</html>