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
		
		/*----------Update Restauration Hosp----------------*/
		
		$updateIdPatientHosp=$connexion->prepare('UPDATE restauration r SET r.id_factureHosp=:idbill, r.statusPaResto=:statusPaResto, r.codecashierHosp=:codecash WHERE r.id_resto=:idresto');

		$updateIdPatientHosp->execute(array(
		'idresto'=>$_GET['idresto'],
		'idbill'=>$idBilling,
		'statusPaResto'=>$statusPaHosp,
		'codecash'=>$_SESSION['codeCash']
		
		))or die( print_r($connexion->errorInfo()));
		
		/*----------Update Tour de salle Hosp----------------*/
		
		$updateIdPatientHosp=$connexion->prepare('UPDATE tour_de_salle ts SET ts.id_factureHosp=:idbill, ts.statusPa=:statusPa, ts.codecashierHosp=:codecash WHERE ts.id_tour_de_salle=:idtourdesalle');

		$updateIdPatientHosp->execute(array(
		'idtourdesalle'=>$_GET['idtourdesalle'],
		'idbill'=>$idBilling,
		'statusPa'=>$statusPaHosp,
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

    if (isset($_GET['datefacturedebut'])) {
    	$datedebut = $_GET['datefacturedebut'];
    }

	if (isset($_GET['datefacturefin'])) {
    	$datefin = $_GET['datefacturefin'];
    }

    if(isset($_GET['facturer']))
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
		//$heureSortie = date('H').':'.date('i').':'.date('s');
		$statusPaHosp = 0;
		$numroom=$_GET['numroom'];

$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		/*----------Insert into jourhosp_fact----------------*/
		
		$insertjourHosp_fact=$connexion->prepare('INSERT INTO jourHosp_fact(numbill,id_hosp,numero,datedebut,datefin,codecashier) VALUES(:numbill,:idhosp,:numero,:datedebut,:datefin,:codecashier)');

		$insertjourHosp_fact->execute(array(
		'numbill'=>$_GET['idbill'],
		'idhosp'=>$_GET['idhosp'],
		'numero'=>$_GET['numPa'],
		'datedebut'=>$_GET['datefacturedebut'],
		'datefin'=>$_GET['datefacturefin'],
		'codecashier'=>$_SESSION['codeCash']
		
		))or die( print_r($connexion->errorInfo()));
		

        /*----------Update Med_Surge----------------*/
		
		$updateIdFactureMedSurge=$connexion->prepare('UPDATE med_surge_hosp ms SET ms.id_factureMedSurge=:idbill, ms.codecashier=:codecashier WHERE ms.id_hospSurge=:idhosp AND ms.numero=:num AND ms.id_factureMedSurge="" AND ms.datehosp>=:datedebut AND ms.datehosp<=:datefin');

		$updateIdFactureMedSurge->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Inf----------------*/

		$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.id_factureMedInf=:idbill, mi.codecashier=:codecashier WHERE mi.id_hospInf=:idhosp AND mi.numero=:num AND mi.id_factureMedInf="" AND mi.datehosp>=:datedebut AND mi.datehosp<=:datefin');

		$updateIdFactureMedInf->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 

		))or die( print_r($connexion->errorInfo()));



		/*----------Update Med_Labo----------------*/
		
		$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.id_factureMedLabo=:idbill, ml.codecashier=:codecashier WHERE ml.id_hospLabo=:idhosp AND ml.numero=:num AND ml.id_factureMedLabo="" AND ml.datehosp>=:datedebut AND ml.datehosp<=:datefin');

		$updateIdFactureMedLabo->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Radio----------------*/
		
		$updateIdFactureMedRadio=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.id_factureMedRadio=:idbill, mr.codecashier=:codecashier WHERE mr.id_hospRadio=:idhosp AND mr.numero=:num AND mr.id_factureMedRadio="" AND mr.datehosp>=:datedebut AND mr.datehosp<=:datefin');

		$updateIdFactureMedRadio->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Consom----------------*/
		
		$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.id_factureMedConsom=:idbill, mco.codecashier=:codecashier WHERE mco.id_hospConsom=:idhosp AND mco.numero=:num AND mco.id_factureMedConsom="" AND mco.datehosp>=:datedebut AND mco.datehosp<=:datefin');

		$updateIdFactureMedConsom->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		))or die( print_r($connexion->errorInfo()));
		
		
		
		/*----------Update Med_Medoc----------------*/
		
		$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.id_factureMedMedoc=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_hospMedoc=:idhosp AND mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.datehosp>=:datedebut AND mdo.datehosp<=:datefin');

		$updateIdFactureMedMedoc->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		))or die( print_r($connexion->errorInfo()));



		/*----------Update Med_Kine----------------*/

		$updateIdFactureMedKine=$connexion->prepare('UPDATE med_kine_hosp mk SET mk.id_factureMedKine=:idbill, mk.codecashier=:codecashier WHERE mk.id_hospKine=:idhosp AND mk.numero=:num AND mk.id_factureMedKine="" AND mk.datehosp>=:datedebut AND mk.datehosp<=:datefin');

		$updateIdFactureMedKine->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 

		))or die( print_r($connexion->errorInfo()));



		/*----------Update Med_Ortho----------------*/

		$updateIdFactureMedOrtho=$connexion->prepare('UPDATE med_ortho_hosp mo SET mo.id_factureMedOrtho=:idbill, mo.codecashier=:codecashier WHERE mo.id_hospOrtho=:idhosp AND mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.datehosp>=:datedebut AND mo.datehosp<=:datefin');

		$updateIdFactureMedOrtho->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 

		))or die( print_r($connexion->errorInfo()));



        /*----------Update Med_Consult----------------*/

        $updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.id_factureMedConsu=:idbill, mc.codecashier=:codecashier WHERE mc.id_hospMed=:idhosp AND mc.numero=:num AND mc.id_factureMedConsu="" AND mc.datehosp>=:datedebut AND mc.datehosp<=:datefin');

        $updateIdFactureMedConsult->execute(array(
            'idbill'=>$idBilling,
            'num'=>$_GET['numPa'],
            'idhosp'=>$idhosp,
            'codecashier'=>$_SESSION['codeCash'],
            'datedebut'=>$datedebut,
			'datefin'=>$datefin 

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

if(isset($_SESSION['codeL']))
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

$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");

$comptidL=$sqlL->rowCount();



if($comptidL!=0)
{
?>
	<div id='cssmenu' style="text-align:center">

		<ul style="margin-top:20px;background:none;border:none;">
			<?php

			if($comptidL!=0)
			{
			?>
				<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&all=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Liste des patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(92);?></a></li>
									
			<?php
			}
			?>
			
			
					<?php
		$lu=0;
        $selectmsg=$connexion->prepare("SELECT * FROM messages WHERE lu=:lu AND receiverId=:receiverId");
        $selectmsg->execute(array("lu"=>$lu,"receiverId"=>$_SESSION['id']));
        $selectmsg->setFetchMode(PDO::FETCH_OBJ);
        $lignecount=$selectmsg->rowCount();
        /*echo $_SESSION['id'];*/
        /*echo $lignecount;*/
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge flashing"><?php echo $lignecount; ?></i> </a></li>
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>

		</ul>

		<ul style="margin-top:20px; background:none;border:none;">				
			<div style="display:none;" id="divMenuMsg">

					<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
					
					<?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
					
					<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

				</div>
				
		</ul>
			
	</div>
		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 30px; padding: 10px; width:80%;">
			<tr>
				<td style="font-size:18px; text-align:center; width:33.333%;">
					<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $nom_uti.' '.$prenom_uti;?>
				</td>
				
				<td style="font-size:18px; text-align:center; width:33.333%;">
					<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
					<?php
					if($sexe=="M")
					{
						echo getString(12);
					}else{
						if($sexe=="F")
						echo getString(13);
					}
					?>
				</td>
				
				<td style="font-size:18px; text-align:center; width:33.333%;">
					<span style="font-weight:bold;">Age: </span><?php echo $an;?>
				</td>
			</tr>
		</table>
	<?php
	}



		if($comptidL!=0)
		{
			if(isset($_GET['examenPaHosp']))
			{
				
				if(isset($_GET['idmedLab']))
				{
				
					$annee = date('Y').'-'.date('m').'-'.date('d');
			?>
				<!-- <form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&idmedLab=<?php echo $_GET['idmedLab']; ?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
					
					
					<table class="tablesorter" style="margin: 20px auto auto; background: rgb(255, 255, 255) none repeat scroll 0% 0%; width: 80%; border: 1px solid rgb(238, 238, 238); border-radius: 5px; padding: 5px;">
						<tbody>
							<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
								<td style="font-size: 20px;">
									<span style="font-weight:bold;"><?php echo getString(139) ?>: </span>
						<?php echo $_GET['presta'];?>
							
								</td>
								
								<td style="color:#a00000; padding-right: 20px; padding-top: 10px; padding-bottom: 10px;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo 'Date d\'envoi : '.$annee;?></td>
							</tr>
							
							<tr>
								<td>
									<label style="padding-left: 40px; padding-top: 10px;" for="examen"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo getString(137) ?></label>
									
									<input type="text" id="examen" name="examen" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value=""/>
								</td>
								
								<td align="left" style="vertical-align: top; padding-left: 10px; padding-top:35px;display:none;">
								
									<label for="result"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo getString(138) ?></label>
									
									<input type="file" name="result" id="result" style="border:1px solid #eee; height:40px; padding 2px; width:95%;"/>
								
									<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
								</td>
							</tr>
						</tbody>
						
						<tfoot>
							<tr>
								<td colspan=2 style="text-align:center">
				
									<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="resultbtn" class="btn-large">
									<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
									</button>
									
									<a href="patients1_hosp.php?num=<?php echo $_GET['num'];?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></a>
									
									<input type="hidden" name="submitExa" value="<?php echo $_GET['idmedLab'];?>"/>
							
									
								</td>
							</tr>
						</tfoot>
						
					</table>
				</form> -->
			<?php
				}
				
				if(isset($_GET['updateidmedLabo']))
				{
					
					$updateResult=$connexion->prepare("SELECT *FROM med_labo_hosp WHERE id_medlabo =:updateidmedLabo");
					$updateResult->execute(array(
					'updateidmedLabo'=>$_GET['updateidmedLabo']
				
					))or die( print_r($connexion->errorInfo()));
					
					$updateResult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
					$comptUpdateResult=$updateResult->rowCount();

					if($ligneUpdateResult=$updateResult->fetch())
					{
						$autreresultats=$ligneUpdateResult->autreresultats;
						$idFactureMedLabo=$ligneUpdateResult->id_factureMedLabo;
						$idPrestationExa=$ligneUpdateResult->id_prestationExa;
						$valeurLab=$ligneUpdateResult->valeurLab;
						$minresultats=$ligneUpdateResult->minresultats;
						$maxresultats=$ligneUpdateResult->maxresultats;
						$resultats=$ligneUpdateResult->resultats;
						
					}
				
			?>
				<form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&updateidmedLabo=<?php echo $_GET['updateidmedLabo']; ?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
					
					
					<table class="tablesorter" style="padding: 5px;">
						<tbody>
							<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
								<td colspan=3 style="font-size: 20px;">
									<span style="font-weight:bold;"><?php echo getString(139) ?>: </span><?php echo $_GET['presta'];?>
							
								</td>
								<td style="color:#a00000; padding-right: 20px; padding-top: 10px; padding-bottom: 10px;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo 'Date d\'envoi : '.$annee;?></td>
							</tr>
							
							<tr>
								<td>
									<label style="text-align:right;padding-bottom:0;" for="examen"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo 'Modification des résultats' ?></label>
								</td>
								
								<td>
									<label style="text-align:center;padding-bottom:0;" for="valeur"><?php echo 'Valeur' ?></label>
								</td>
								
								<td>
									<label style="text-align:center;padding-bottom:0;" for="min"><?php echo 'Min' ?></label>
								</td>
								
								<td>
									<label style="text-align:left;padding-bottom:0;padding-left:30px;" for="max"><?php echo 'Max' ?></label>
									
								</td>
								
								<td align="center" style="vertical-align: top; padding-bottom:0;display:none;">
								
									<label for="result"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo 'Joindre nouveau résultat' ?></label>
								</td>
							</tr>
							
							<tr>
								<td style="text-align:right">
									<input type="text" id="examen" name="examen" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $autreresultats; ?>"/>
								</td>
								
								<td style="text-align:center">
									
									<input type="hidden" id="idprestationExa" name="idprestationExa[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $idPrestationExa;?>"/>
							
								<?php
								$k=0;
								
								if($idFactureMedLabo>=0)
								{
									$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen ORDER BY valeur');
									$resultValeur->execute(array(
									'idexamen'=>$idPrestationExa
									));
									
									$resultValeur->setFetchMode(PDO::FETCH_OBJ);

									$comptValeur=$resultValeur->rowCount();
									
									if($comptValeur!=0)
									{
									?>
										<select name="valeur" id="valeur" style="width:100px;">
							
											<option value='----' id="nominmax">
											<?php echo 'Select here...';?>
											</option>
											<?php

											while($ligneValeur=$resultValeur->fetch())
											{
											?>
											<option value='<?php echo $ligneValeur->valeur;?>' id='minmax' <?php if(isset($_GET['updateidmedLabo']) AND $ligneValeur->valeur==$valeurLab){ echo "selected='selected'";}else{ echo '';}?>>
											<?php
											if($ligneValeur->valeur!="")
											{
												echo $ligneValeur->valeur;
											}else{
												echo 'Default value';
											}
											?>
											</option>
											<?php
											}
											?>
										</select>
									
									<?php
									}elseif($comptValeur==0){
									
										if(isset($_GET['updateidmedLabo']))
										{
											echo "<input type='text' name='valeur' id='valeur' style='width:100px;' value='".$valeurLab."'/>";
										}else{
											echo "<input type='text' name='valeur' id='valeur' style='width:100px;' value=''/>";
										}
									
									}
								}
								?>
								</td>
								
								<td style="text-align:center">
									<input type="text" id="min" name="min" style="width:80px" value="<?php echo $minresultats;?>"/>
								</td>
								
								<td style="text-align:left">
									<input type="text" id="max" name="max" style="width:80px" value="<?php echo $maxresultats;?>"/>
								
									<?php
									/* if($resultats!="")
									{
									?>
										<a href="<?php echo $resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
										
									<?php
									} */
									?>
								</td>
								
								
								<td style="text-align:center;display:none;">
								
									<input type="file" name="result" id="result" style="border:1px solid #eee; height:40px;width:250px;"/>
								
									<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
								</td>
								
							</tr>
						</tbody>
						
						<tfoot>
							<tr>
								<td colspan=4 style="text-align:center">
				
									<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="updateresultHospbtn" class="btn-large">
									<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(32) ?>
									</button>
									
									
									<a href="patients1_hosp.php?num=<?php echo $_GET['num'];?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></a>
									
									<input type="hidden" name="submitExa" value="<?php echo $_GET['updateidmedLabo'];?>"/>
							
									
								</td>
							</tr>
						</tfoot>
						
					</table>
				</form>
			<?php
				}
			}
			?>
			
			<?php
			if(isset($_GET['examenPaHosp']))
			{
			?>
				<span style="font-size:250%;"><?php echo getString(99);?></span>
			
			<?php
			}
			?>
			<table class="<?php  if(isset($_GET['examenPaHosp'])){ echo 'previewprint tablesorter1';}else{ echo 'tablesorter';}?>" cellpadding="5" style="background-color:#FFF;margin-top:10px;">
			<?php
		
			/*-----------Examen à faire---------------*/
			
			if(isset($_GET['examenPaHosp']))
			{
				$resultatsExamPa=$connexion->prepare("SELECT *FROM med_labo_hosp mlh,utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND mlh.numero=ph.numero AND (mlh.id_prestationExa IS NOT NULL OR mlh.autreExamen !='') AND mlh.examenfait=0 AND mlh.numero=:numPa ORDER BY mlh.id_medlabo DESC");
				
				$resultatsExamPa->execute(array(
				'numPa'=>$_GET['num']
				));
				
				$resultatsExamPa->setFetchMode(PDO::FETCH_OBJ);
			
				$comptResultExamPa=$resultatsExamPa->rowCount();

				
				$resultatsExamPaEdit=$connexion->prepare("SELECT *FROM med_labo_hosp mlh,utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND mlh.numero=ph.numero AND (mlh.id_prestationExa IS NOT NULL OR mlh.autreExamen !='') AND mlh.examenfait=1 AND mlh.numero=:numPa ORDER BY mlh.dateresultats DESC");
				
				$resultatsExamPaEdit->execute(array(
				'numPa'=>$_GET['num']
				));
				
				$resultatsExamPaEdit->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
				$comptResultExamPaEdit=$resultatsExamPaEdit->rowCount();
				
			}else{
				$start_week=strtotime("last week");
				$start_week=date("Y-m-d",$start_week);
				
				$resultatsL=$connexion->prepare("SELECT *FROM utilisateurs u, patients_hosp ph, med_labo_hosp mlh WHERE u.id_u=ph.id_uHosp AND mlh.numero=ph.numero AND mlh.dateconsu =:annee AND (mlh.dateresultats =:annee OR mlh.dateresultats ='0000-00-00' OR mlh.dateresultats IS NULL) AND (mlh.id_prestationExa IS NOT NULL OR mlh.autreExamen !='') GROUP BY ph.numero ORDER BY mlh.dateconsu DESC");
				$resultatsL->execute(array(
				'annee'=>$annee
				
				))or die( print_r($connexion->errorInfo()));
			
				
				$resultatsL->setFetchMode(PDO::FETCH_OBJ);
		
				$comptResultL=$resultatsL->rowCount();
			
			}
			
			
			if(!isset($_GET['examenPaHosp']))
			{
				if($comptResultL!=0)
				{
			?>
				<thead>
					<tr>
						<th>S/N</th>
						<th><?php echo getString(9);?></th>
						<th><?php echo getString(10);?></th>
						<th><?php echo getString(11);?></th>
						<th style="text-align:right;"><?php echo getString(99);?></th>
						<th>Rapport</th>
					</tr>
				</thead>
		<?php
				}
				
			}else{
				
				if(isset($_GET['examenPaHosp']))
				{
		?>
					<thead>
						<tr style="background-color:#A00000;color:white;margin-top:10px;">
							<th style="width:8%"><?php echo getString(97);?></th>
							<th><?php echo getString(19) ?></th>
							<th><?php echo getString(99);?></th>
							<th><?php echo 'Résultats';?></th>
							<th style="text-align:center;"><?php echo 'Valeur (min-max)';?></th>
							<th style="width:8%"><?php echo 'Date Résultats';?></th>
							<th><?php echo 'Done by';?></th>
							<th style="width:15%" colspan=2>Actions</th>
						</tr>
					</thead>
			<?php
				}
			}
			
			
			if(isset($_GET['examenPaHosp']))
			{
			?>
				<tbody>
				<?php
				if($comptResultExamPa != 0)
				{
					$comptFacture=0;
				?>
				<form method="post" action="traitement_resultats.php?num=<?php echo $_GET['num'];?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResultat(this)" enctype="multipart/form-data">
					
					<?php
					$i=0;
					
					while($ligneExamPa=$resultatsExamPa->fetch())
					{
					
						$idassu=$ligneExamPa->id_assuLab;
					
						$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
						$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$assuCount = $comptAssuConsu->rowCount();
						
						for($l=1;$l<=$assuCount;$l++)
						{
							
							$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
							$getAssuConsu->execute(array(
							'idassu'=>$idassu
							));
							
							$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

							if($ligneNomAssu=$getAssuConsu->fetch())
							{
								$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
							}
						}

					?>
						<tr style="text-align:center;">
						
							<td>
								<?php echo $ligneExamPa->datehosp;?>
								<input type="hidden" id="idmedLaboResult<?php echo $i;?>" name="idmedLaboResult[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneExamPa->id_medlabo;?>"/>
								
								<input type="hidden" id="idprestationExa<?php echo $i;?>" name="idprestationExa[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneExamPa->id_prestationExa;?>"/>
							</td>
							
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPa->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
							
							if($ligneMed=$resultatsMed->fetch())
							{
								echo $ligneMed->full_name;
							}
							$resultatsMed->closeCursor();
							?>
							</td>
						
							<td>
							<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPa->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
										echo $lignePresta->namepresta;
									
									}else{
									
										$presta=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPa->autreExamen;
									$mesure='';
									
									echo $ligneExamPa->autreExamen;
								}
							
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<input type="text" id="resultats" name="resultats[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;width:130px" value="" placeholder="Taper les résultats ici..."/> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
							<?php
							}else{
								echo '---';
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
								$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen ORDER BY valeur');
								$resultValeur->execute(array(
								'idexamen'=>$ligneExamPa->id_prestationExa
								));
								
								$resultValeur->setFetchMode(PDO::FETCH_OBJ);

								$comptValeur=$resultValeur->rowCount();
								
								if($comptValeur!=0)
								{
								?>
									<select name="<?php echo $i;?>" id="valeur<?php echo $i;?>" style="width:100px;" onchange="ShowMinMax()">
						
										<option value='----' id="nominmax">
										<?php echo 'Select here...';?>
										</option>
										<?php
										$j = 0;
										while($ligneValeur=$resultValeur->fetch())
										{
										?>
										<option value='<?php echo $ligneValeur->valeur;?>' id='minmax<?php echo $j;?>'>
										<?php
										if($ligneValeur->valeur!="")
										{
											echo $ligneValeur->valeur;
										}else{
											echo 'Default value';
										}
										?>
										</option>
										<?php
										$j++;
										}
										?>
									</select>
								
								<?php
								}elseif($comptValeur==0){
								
									echo "<input type='text' name='".$i."' id='valeur'".$i."' style='width:100px;' value=''/>";
								
								}
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<input type="text" id="min<?php echo $i;?>" name="min[]" style="width:60px;" value=""/>
							<?php
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<input type="text" id="max<?php echo $i;?>" name="max[]" style="width:60px;" value=""/>
							<?php
							}
							?>
							</td>
							
							
							<td style="display:none">
								<input type="file" name="autreresult[]" id="autreresult" style="border:1px solid #eee; height:40px; padding 2px; width:230px;"/>
								
								<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
							</td>
							
							
							<td>
							<?php
							if($ligneExamPa->dateresultats != '0000-00-00')
							{
								echo $ligneExamPa->dateresultats;
							}
							?>
							</td>

							<td></td>
							
							<td colspan=2>
							<?php
							
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<a href="moreresultats.php?num=<?php echo $ligneExamPa->numero;?>&examenPaHosp=ok&idmedLab=<?php echo $ligneExamPa->id_medlabo;?>&idmed=<?php echo $ligneExamPa->id_uM;?>&idassu=<?php echo $ligneExamPa->id_assuLab;?>&dateconsu=<?php echo $ligneExamPa->datehosp;?>&dateresultats=<?php echo $ligneExamPa->dateresultats;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-plus fa-0.5x fa-fw"> </i> <?php echo 'Add'; ?></a>
								
								
							<?php
							}else{
							?>
								<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span>
							<?php
							}
							?>
							</td>
							
						</tr>
					<?php
						$comptFacture=$comptFacture+$ligneExamPa->id_factureMedLabo;
						
						$i++;	
					}
					$resultatsExamPa->closeCursor();
					
					if($comptFacture>=0)
					{
					?>
						
						
						<input type='hidden' id='iVal' value='<?php echo $i-1;?>'/>
						<tr>
							<td colspan=11 style="text-align:center">
								<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="resultatHospbtn" class="btn-large">
								<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
								</button>
								
							</td>
							
						</tr>
				<?php
					}
				}
				?>
				</form>
				<?php
				
				if($comptResultExamPaEdit != 0)
				{
					while($ligneExamPaEdit=$resultatsExamPaEdit->fetch())//on recupere la liste des éléments
					{
					
						$idassu=$ligneExamPaEdit->id_assuLab;
						
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
								$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
							}
						}


					
						if($ligneExamPaEdit->moreresultats!=0)
						{
				?>
						
						<tr style="text-align:center;<?php if($ligneExamPaEdit->id_uL == $_SESSION['id']){ echo "background-color:rgba(0,50,255,0.15);";}else{ echo "background-color:rgba(250,250,0,0.15);";}?>">
						
							<td><?php echo $ligneExamPaEdit->dateconsu;?></td>
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPaEdit->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
							?>
								<?php echo $ligneMed->full_name;?>
								
							<?php
							}
							$resultatsMed->closeCursor();
							?>
							</td>
							
							<td>
							
								<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
									}else{
									
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPaEdit->autreExamen;
									$mesure='';
								}
								
									echo $presta;
								?>
							</td>
							
							<td colspan=4>
							</td>
						
							<td>
							<?php
							if($ligneExamPaEdit->dateresultats != '0000-00-00')
							{
								echo $ligneExamPaEdit->dateresultats;
							}
							?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
							</td>
							
							<td>
							<?php
							$idLabo=$ligneExamPaEdit->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameLabo==$ligneLaboId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneLaboId->full_name;
								}
							}
							
							?>
							</td>
							
							
							<td colspan=2>
							<?php
							if($ligneExamPaEdit->examenfait==1)
							{
							?>
								<a href="<?php if($ligneExamPaEdit->moreresultats==1){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==2){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidspermomedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==0){ echo 'patients1_hosp_labresult.php?updateidmedLabo='.$ligneExamPaEdit->id_medlabo.'&idassuLab='.$ligneExamPaEdit->id_assuLab.'&examenPaHosp=ok';}}}?>&num=<?php echo $ligneExamPaEdit->numero;?>&dateconsu=<?php echo $ligneExamPaEdit->datehosp;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="envoiresult" name="envoiresult" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"> </i> <?php echo getString(32) ?></a>
							<?php
							}
							?>
							</td>
							
							
						</tr>
						
							<?php
							if($ligneExamPaEdit->moreresultats==1)
							{
								$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');
								$resultMoreMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneExamPaEdit->id_medlabo
								));
								
								$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
								
								while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
								{
									
									$idassuLab=$ligneExamPaEdit->id_assuLab;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
									$assuCount = $comptAssuConsu->rowCount();
									
									for($a=1;$a<=$assuCount;$a++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassuLab ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
										'idassuLab'=>$idassuLab
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
										}
									}

								?>
								<tr>
									<td colspan=2></td>
									<td>
									<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											
											}else{
											
												$presta=$lignePresta->nompresta;
											}
											$mesure=$lignePresta->mesure;
										}else{
											$presta=$ligneMoreMedLabo->autreExamen;
											$mesure='';
										}
										
										echo $presta;
									?>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->valeurLab;?>
									</td>
									
									<td>
									<?php
										$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');
										$resultExa->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
								
										$resultExa->setFetchMode(PDO::FETCH_OBJ);
										$comptExa=$resultExa->rowCount();

										if($ligneMoreMedLabo->minresultats =="")
										{
											if($comptExa ==1)
											{
												$ligneMin=$resultExa->fetch();
												echo $ligneMin->min_valeur;
											}
										}else{
											echo $ligneMoreMedLabo->minresultats;
										}
									?>
									</td>
									
									<td>
									<?php
										$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');
										$resultExa->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
										
										$resultExa->setFetchMode(PDO::FETCH_OBJ);
										$comptExa=$resultExa->rowCount();

										if($ligneMoreMedLabo->maxresultats =="")
										{
											if($comptExa ==1)
											{
												$ligneMin=$resultExa->fetch();
												echo $ligneMin->max_valeur;
											}
										}else{
											echo $ligneMoreMedLabo->maxresultats;
										}
									?>
									</td>
									
									<td style='display:none'>
									<?php
									if($ligneMoreMedLabo->resultats!="")
									{
									?>
										<a href="<?php echo $ligneMoreMedLabo->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
									<?php
									}
									?>
									</td>
							
									<td colspan=4></td>
									
								</tr>
							<?php
								}
							}
							
							if($ligneExamPaEdit->moreresultats==2)
							{
								
								$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');
								$resultSpermoMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneExamPaEdit->id_medlabo
								));
								
								$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
								
								while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
								{
								?>
								<tr style="background-color:#eee">
									<td colspan=5>EXAMEN MACROSCOPIQUES</td>
									<td style='border-left:1px solid #aaa;' colspan=6>EXAMEN MICROSCOPIQUES</td>
								</tr>
								
								<tr>
									<td>Volume</td>
									<td>Densité</td>
									<td>Viscosité</td>
									<td>PH</td>
									<td>Aspect</td>
									
									<td style='border-left:1px solid #aaa;'>Examen direct</td>
									<td>Mobilité après</td>
									<td>Numération</td>
									<td>V.N</td>
									<td>Spermocytogramme</td>
									<td>Autres</td>
								
								</tr>
								
								<tr>
									<td>
									<?php echo $ligneSpermoMedLabo->volume;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->densite;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->viscosite;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->ph;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->aspect;?>
									</td>
									
									<td style='border-left:1px solid #aaa;'>
									<?php echo $ligneSpermoMedLabo->examdirect;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td style='border-left:1px solid #aaa;'>0h après emission</td>
												<td>1h après emission</td>
												<td>2h après emission</td>
												<td>3h après emission</td>
												<td style='border-right:1px solid #aaa;'>4h après emission</td>
											</tr>
											
											<tr>
												<td style='border-left:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->zeroheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->uneheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->deuxheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->troisheureafter;?>
												</td>
												<td style='border-right:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->quatreheureafter;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td>
									<?php echo $ligneSpermoMedLabo->numeration;?>
									</td>
									
									<td>
									<?php echo $ligneSpermoMedLabo->vn;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td style='border-left:1px solid #aaa;'>Forme typique</td>
												<td style='border-right:1px solid #aaa;'>Forme atypique</td>
											</tr>
											
											<tr>
												<td style='border-left:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->formtypik;?>
												</td>
												<td style='border-right:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->formatypik;?>
												</td>
											</tr>
										</table>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->autre;?>
									</td>
									
								</tr>
								
								<tr style="background-color:#eee">
									<td colspan=11>CONCLUSION</td>
									
								</tr>
								<tr>
									<td colspan=11>
									<?php echo $ligneSpermoMedLabo->conclusion;?>
									</td>
									
								</tr>
								<?php
								}
							}
						}else{
						?>
						
						<tr style="text-align:center;<?php if($ligneExamPaEdit->id_uL == $_SESSION['id']){ echo "background-color:rgba(0,50,255,0.15);";}else{ echo "background-color:rgba(250,250,0,0.15);";}?>">
						
							<td><?php echo $ligneExamPaEdit->datehosp;?></td>
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPaEdit->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
							?>
								<?php echo $ligneMed->full_name;?>
								
							<?php
							}
							$resultatsMed->closeCursor();
							?>
							</td>
							
							<td>
							
								<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
									}else{
									
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPaEdit->autreExamen;
									$mesure='';
								}
									
									echo $presta;
								?>
								
							</td>
							
							<td><?php echo $ligneExamPaEdit->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span></td>
							
							<!-- <td><?php echo $ligneExamPaEdit->valeurLab;?></td> -->
							
							
								<td>
										<?php							
											$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE nomexam=:nomexam ORDER BY valeur');
											$resultValeur->execute(array(
											'nomexam'=>$presta
											));
											
											$resultValeur->setFetchMode(PDO::FETCH_OBJ);

											$comptValeur=$resultValeur->rowCount();
											
											$resultValeur->setFetchMode(PDO::FETCH_OBJ);

											$comptValeur=$resultValeur->rowCount();
											
											if($comptValeur!=0)
											{
												$v=0;
											
												while($ligneValeur=$resultValeur->fetch())
												{
												?>
														
														<table class="printPreview tablesorter3" cellspacing="0" style="width:100%;">
															<tr>
																<td style="text-align:center;">
																<?php 
																/* if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL)
																{ */
																?>
																	<span type="text" id="valeur<?php echo $v;?>" name="valeur[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL){ echo $ligneValeur->valeur;}else{ echo '---';}?></span>
																<?php 
																// }
																
																if($ligneValeur->min_valeur !="" OR $ligneValeur->max_valeur !="")
																{
																?>
																( 
																<span type="text" id="min<?php echo $v;?>" name="min[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->min_valeur !=""){ echo $ligneValeur->min_valeur;}?></span> 
																- 
																<span type="text" id="max<?php echo $v;?>" name="max[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->max_valeur !=""){ echo $ligneValeur->max_valeur;}?></span> )
																<?php
																}
																?>
																</td>						
															</tr>						
														</table>						
											<?php
													$v++;
												}
											}
										?>
							</td>
							
							<td style='display:none'>
							<?php
							if($ligneExamPaEdit->resultats!="")
							{
							?>
								<a href="<?php echo $ligneExamPaEdit->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
							<?php
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPaEdit->dateresultats != '0000-00-00')
							{
								echo $ligneExamPaEdit->dateresultats;
							}
							?>
							</td>
							
							<td>
							<?php
							$idLabo=$ligneExamPaEdit->id_uL;
							
							$resultLaboId=$connexion->prepare( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idLabo");
							$resultLaboId->execute(array(
							'idLabo'=>$idLabo
							));
							
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							
							$comptLaboId=$resultLaboId->rowCount();
							
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameLabo==$ligneLaboId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneLaboId->full_name;
								}
							}
							?>
							</td>
							
							
							<td>
							<?php
							if($ligneExamPaEdit->examenfait==1 AND $ligneExamPaEdit->id_uL==$_SESSION['id'])
							{
								/* if($ligneExamPaEdit->postdiagnostic=='')
								{ */
							?>
								<a href="<?php if($ligneExamPaEdit->moreresultats==1){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==2){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==0){ echo 'patients1_hosp_labresult.php?updateidmedLabo='.$ligneExamPaEdit->id_medlabo.'&idassuLab='.$ligneExamPaEdit->id_assuLab.'&examenPaHosp=ok';}}}?>&num=<?php echo $ligneExamPaEdit->numero;?>&dateconsu=<?php echo $ligneExamPaEdit->datehosp;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="envoiresult" name="envoiresult" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"> </i> <?php echo getString(32) ?></a>
							<?php
								/* }else{
									echo 'Dignosis Done';
								} */
							}
							?>
							</td>
							
						</tr>
						<?php
						}
				
					}
					$resultatsExamPaEdit->closeCursor();
				}

			
			}else{
				
				if($comptResultL!=0)
				{
			?>
					<span style="position:relative; font-size:250%;"><?php echo getString(108);?></span>
					
					<?php

					while($ligneL=$resultatsL->fetch())//on recupere la liste des éléments
					{
						
						$doneResultsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c, med_labo ml WHERE u.id_u=p.id_u AND c.numero=p.numero AND ml.numero=p.numero AND ml.numero=:num AND c.id_consu=ml.id_consuLabo AND (ml.dateresultats="0000-00-00" OR ml.dateresultats IS NULL) ORDER BY c.numero');

						$doneResultsLabo->execute(array(
						'num'=>$ligneL->numero
						));

						$doneResultsLabo->setFetchMode(PDO::FETCH_OBJ);
					
						$comptDoneResultsLabo=$doneResultsLabo->rowCount();
				
					?>
						<tr style="text-align:center;<?php if($comptDoneResultsLabo==0){ echo 'background:rgba(0,100,255,0.5);';}?>">

							<td><?php echo $ligneL->numero;?></td>
							<td><?php echo $ligneL->nom_u;?></td>
							<td><?php echo $ligneL->prenom_u;?></td>
							<td>
							<?php
							if($ligneL->sexe=="M")
							{
								echo getString(12);
							}else{
								if($ligneL->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td style="text-align:right;">
							
								<a href="patients1_hosp.php?num=<?php echo $ligneL->numero;?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(109);?></a>
								
							</td>
							
							<td>
							
								<a href="patients_laboreport.php?num=<?php echo $ligneL->numero;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo 'Rapport labo';?></a>
								
							</td>

						</tr>
				<?php
					}
					$resultatsL->closeCursor();
	
				}else{
				?>
					
					<tr>
						<td colspan=5><h3><?php echo getString(190);?></h3></td>
					</tr>
				<?php
				}
				?>
				</tbody>
			
			<?php
			}
			?>
			
			</table>
	
		<?php
//		}
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