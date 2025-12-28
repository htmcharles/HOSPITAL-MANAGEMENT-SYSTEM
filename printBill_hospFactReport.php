<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


/** Include PHPExcel */
require_once 'PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

require_once('barcode/class/BCGFontFile.php');
require_once('barcode/class/BCGColor.php');
require_once('barcode/class/BCGDrawing.php');
require_once('barcode/class/BCGcode93.barcode.php');

$annee = date('d').'-'.date('M').'-'.date('Y');


$heure = date('H').' : '.date('i').' : '.date('s');

// echo $heure;
// echo showON('H');


	$numPa=$_GET['num'];
	$hospId=$_GET['idhosp'];
	
	
	$checkIdBill=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp ORDER BY ph.id_factureHosp LIMIT 1');

	$checkIdBill->execute(array(
	'idhosp'=>$_GET['idhosp']
	));

	$comptidBill=$checkIdBill->rowCount();

	// echo $comptidBill;
	
	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		
		// echo $idBilling;
		
		if($ligne->id_factureHosp!=NULL)
		{
			$idBilling = $ligne->id_factureHosp;
			$createBill = 0;
			
		}else{
			
			$idBilling = showON('H');
			$createBill = 1;
			
		}
		
	}

	
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Bill#'.showON('H'); ?></title>

	<link href="cssBourbonCoffee/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	
	<!--<meta HTTP-EQUIV="Refresh" CONTENT="30; URL=http://www.tonSite.com/page.html"> -->
	
		
			<!------------------------------------>
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<style type="text/css">

		@media print {
		 
			.az
			{
				display:none;
			}

			.account-container
			{
				display:block;
				
			}
			
			.buttonBill
			{
				display:none;
				
			}
		}
	
	</style>
	
</head>



<body>


	<?php
	if(isset($_GET['finishbtn']))
	{
	?>
		<body onload="window.print()">
	<?php
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$idCashier=$_SESSION['id'];

if($connected==true AND isset($_SESSION['codeCash']))
{
	
	$resultatsCashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.id_u=:operation');
	$resultatsCashier->execute(array(
	'operation'=>$idCashier
	));

	$resultatsCashier->setFetchMode(PDO::FETCH_OBJ);
	if($ligneCashier=$resultatsCashier->fetch())
	{
		$doneby = $ligneCashier->nom_u.'  '.$ligneCashier->prenom_u;
		$codecashier = $ligneCashier->codecashier;
	}

		$font = new BCGFontFile('barcode/font/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		// Barcode Part
		$code = new BCGcode93();
		$code->setScale(2);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setLabel('# '.$idBilling.' #');
		$code->parse(''.$idBilling.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecashier.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:80%;">
<?php
$barcode = '

	<table style="width:100%">
		
		<tr>
			<td colspan=2 style="text-align:center;">
				<span style="text-align:center;background:#333;border-radius:40px;color:#eee;font-weight:400;padding:5px 50px">Powered by <font>Medical File</font> , a product of Innovate Solutions Ltd. ©2022-'.date('Y').', All Rights Reserved.</span>
			</td>
		</tr>
	 
		<tr>
			<td style="text-align:left; width:60%">
			  <table>
				<tbody>
					<tr>
						<td style="text-align:right;padding:5px;border-top:none;">
							<img src="images/Logo.jpg">
						</td>

						<td style="text-align:left;width:80%">
							<span style="border-top:none;border-bottom:2px solid #ccc; font-size:110%; font-weight:900"></span>
							<span style="font-size:90%;">
                                Phone: (+250) 788404430<br/>
                                E-mail: clinicumurage@gmail.com<br/>
                                Muhanga - Nyamabuye - Gahogo
                            </span>
						</td>
					</tr>
				</tbody>
			  </table>
			</td>
			
			<td style="text-align:right;">
				<img src="barcode/png/barcode'.$codecashier.'.png" style="height:auto;"/>
			</td>
			
		</tr>
		
	</table>';

echo $barcode;
?>

<?php

			$resultatHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp');
			$resultatHosp->execute(array(
			'idhosp'=>$hospId
			));
			
			$resultatHosp->setFetchMode(PDO::FETCH_OBJ);

			if($ligneHosp=$resultatHosp->fetch())
			{
				$datehosp= date('d-M-Y', strtotime($ligneHosp->dateEntree));
			}

		
		
		$TotalGnl = 0;
		
		
			/*--------------Billing Info Patient-----------------*/
			
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa
		));
		
		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignePatient=$resultatsPatient->fetch())
		{
			
			$bill= $lignePatient->bill;
			$idassurance=$lignePatient->id_assurance;
			$numpolice=$lignePatient->numeropolice;
			$adherent=$lignePatient->adherent;
			
			if($lignePatient->carteassuranceid != "")
			{
				$idcard = $lignePatient->carteassuranceid;
			}else{
				$idcard = "";
			}
			
			$resultAssu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu');
			$resultAssu->execute(array(
			'idassu'=>$lignePatient->id_assurance
			));
			
			$resultAssu->setFetchMode(PDO::FETCH_OBJ);

			$comptAssu=$resultAssu->rowCount();
			
			if($ligneAssu=$resultAssu->fetch())
			{
				$nomassurance = $ligneAssu->nomassurance;
			}else{
				$nomassurance = "";
			}
			
			$uappercent= 100 - $lignePatient->bill;
			
			$percentpartient= 100 - $uappercent;

			if($lignePatient->sexe=="M")
			{
				$sexe = "Male";
			}elseif($lignePatient->sexe=="F"){
				$sexe = "Female";
			}else{
				$sexe="";
			}
	
			$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
			$resultAdresse->execute(array(
			'idProv'=>$lignePatient->province,
			'idDist'=>$lignePatient->district,
			'idSect'=>$lignePatient->secteur
			));
			
			$resultAdresse->setFetchMode(PDO::FETCH_OBJ);

			$comptAdress=$resultAdresse->rowCount();
			
			if($ligneAdresse=$resultAdresse->fetch())
			{
				if($ligneAdresse->id_province == $lignePatient->province)
				{
					$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
					
				}
			}elseif($lignePatient->autreadresse!=""){
					$adresse=$lignePatient->autreadresse;
			}else{
				$adresse="";
			}

			

		$userinfo = '<table style="width:100%; margin-top:20px;">
			
			<tr>
				<td style="text-align:left;">
					Full name:
					<span style="font-weight:bold">'.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'</span><br/>
					Gender: <span style="font-weight:bold">'.$sexe.'</span><br/>
					Adress: <span style="font-weight:bold">'.$adresse.'</span>
				</td>
				
				<td style="text-align:center;">
					Insurance type: <span style="font-weight:bold">';
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$lignePatient->id_assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

			if($ligneAssu=$resultAssurance->fetch())
			{
				if($ligneAssu->id_assurance == $lignePatient->id_assurance)
				{
					$idassu=$ligneAssu->id_assurance;
					$insurance=$ligneAssu->nomassurance;
					$numpolice=$lignePatient->numeropolice;
					$adherent=$lignePatient->adherent;
					
					$userinfo .= ''.$ligneAssu->nomassurance.'</span><br/>';
					
					if($idassurance!=1)
					{
						$userinfo .= 'N° insurance card:
						<span style="font-weight:bold">'.$idcard;
						
						if($numpolice!="")
						{
							$userinfo .= '</span><br/>
							
							N° police:
							<span style="font-weight:bold">'.$numpolice;
						}
						
						$userinfo .= '</span><br/>
						
						Principal member:
						<span style="font-weight:bold">'.$adherent;
					}
				}
			}

				$userinfo .='</span>
				</td>
				
				<td style="text-align:right;">
					Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
					Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
					Date of Hospitalisation: <span style="font-weight:bold">'.$datehosp.'</span>
					
				</td>
				
			</tr>
		</table>';

		echo $userinfo;
		
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.$idBilling.'')
					 ->setSubject("Billing information")
					 ->setDescription('Billing information for patient : '.$lignePatient->numero.', '.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'')
					 ->setKeywords("Bill Excel")
					 ->setCategory("Bill");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
		
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'S/N')
						->setCellValue('B1', ''.$lignePatient->numero.'')
						->setCellValue('A2', 'Full name')
						->setCellValue('B2', ''.$lignePatient->nom_u.'  '.$lignePatient->prenom_u.'')
						
						->setCellValue('A3', 'Adresse')
						->setCellValue('B3', ''.$adresse.'')
						
						->setCellValue('A4', 'Insurance')
						->setCellValue('B4', ''.$insurance.' '.$percentpartient.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$idBilling.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		/*-------Requête pour AFFICHER Type Hospitalisation---------*/
		
	if(isset($_POST['previewbtn']))
	{
		/*if($_POST['mois']<10)
		{
			$mois='0'.$_POST['mois'];
		}else{
			$mois=$_POST['mois'];
		}
		
		if($_POST['jours']<10)
		{
			$jours='0'.$_POST['jours'];
		}else{
			$jours=$_POST['jours'];
		}
		
		$lastDate=$_POST['annee'].'-'.$mois.'-'.$jours;
		
		$dateSortie = date('Y-m-d', strtotime($lastDate));
		$heureSortie=$_POST['heureout'].':'.$_POST['minuteout'].':00';*/
		$datefacture = $_POST['datefactfin'];
		
		$prixprestaHosp=$_POST['prixprestaHosp'];
		$prixprestaHospCCO=$_POST['prixprestaHospCCO'];

		/*$prixresto=$_POST['prixresto'];
		$prixrestoCCO=$_POST['prixrestoCCO'];
		$idresto = $_POST['idresto'];
		$idtourdesalle= $_POST['idtourdesalle'];*/

		
		if($idassu==1)
		{
			$percentHosp=100;
		}else{
			$percentHosp=$_POST['percentHosp'];
		}
		$idHosp=$_GET['idhosp'];
		
		$updatepercent=$connexion->prepare('UPDATE patients_hosp ph SET ph.prixroom=:prixprestaHosp,ph.prixroomCCO=:prixprestaHospCCO,ph.insupercent_hosp=:percentHosp WHERE ph.id_hosp=:idHosp');
		
		$updatepercent->execute(array(
		'prixprestaHosp'=>$prixprestaHosp,
		'prixprestaHospCCO'=>$prixprestaHospCCO,
		'percentHosp'=>$percentHosp,
		'idHosp'=>$idHosp
		
		))or die( print_r($connexion->errorInfo()));
/*
		$updateresto=$connexion->prepare('UPDATE restauration r SET r.dateSortie=:dateSortie,r.heureSortie=:heureSortie,r.prixresto=:prixresto,r.prixrestoCCO=:prixrestoCCO WHERE r.id_resto=:idresto');
		
		$updateresto->execute(array(
		'dateSortie'=>$dateSortie,
		'heureSortie'=>$heureSortie,
		'prixresto'=>$prixprestaHosp,
		'prixrestoCCO'=>$prixprestaHospCCO,
		'idresto'=>$idresto
		
		))or die( print_r($connexion->errorInfo()));*/
	}
	
		$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:hospId AND ph.numero=:num AND ph.dateEntree=:datehosp AND ph.id_assuHosp=:idassu ORDER BY ph.id_hosp');
		$resultHosp->execute(array(
		'hospId'=>$hospId,
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'datehosp'=>date('Y-m-d', strtotime($datehosp))
		));

		$resultHosp->setFetchMode(PDO::FETCH_OBJ);

		$comptHosp=$resultHosp->rowCount();
		
		$TotalHosp = 0;
		$TotalHospCCO = 0;

		if (isset($_GET['datefacturedebut'])) {
			$datefactudebut = $_GET['datefacturedebut'];
		}
		if (isset($_POST['datefactdebut'])) {
			$datefactudebut = $_POST['datefactdebut'];
		}

		if (isset($_GET['datefacturefin'])) {
			$datefactufin = $_GET['datefacturefin'];
		}
		if (isset($_POST['datefactfin'])) {
			$datefactufin = $_POST['datefactfin'];
		}
		/*-------Requête pour AFFICHER med_consult_hosp-----------*/
	
		
		if(isset($_POST['idpresta']))
		{
			
			$idassuServ=$idassu;
			
			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
			
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuConsu->execute(array(
				'idassu'=>$idassuServ
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
				{
					$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
				}
			}



			$idprestamc = array();
			$prixmc = array();
			$prixmcCCO = array();
			$add = array();
			$idmc = array();
			$autremc = array();
			$qteConsu = array();

			foreach($_POST['idpresta'] as $mc)
			{
				$idprestamc[] = $mc;
			}
			
			foreach($_POST['prixprestaConsu'] as $valmc)
			{
				$prixmc[] = $valmc;
			}

			foreach($_POST['prixprestaConsuCCO'] as $valmcCCO)
			{
				$prixmcCCO[] = $valmcCCO;
			}
			
			foreach($_POST['percentConsu'] as $valuemc)
			{
				$add[] = $valuemc;
			}
			
			foreach($_POST['idmedConsu'] as $valeurmc)
			{
				$idmc[] = $valeurmc;
			}
			
			foreach($_POST['autreConsu'] as $autrevaluemc)
			{
				$autremc[] = $autrevaluemc;
			}
			
			foreach($_POST['quantityConsu'] as $valueConsu)
			{
				$qteConsu[] = $valueConsu;
			}
			
			for($i=0;$i<sizeof($add);$i++)
			{
				// echo $add[$i].'_'.$idmc[$i].'_('.$prixmc[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation='.$idprestamc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.insupercentServ='.$add[$i].',mc.prixprestationConsu='.$prixmc[$i].',mc.prixprestationConsuCCO='.$prixmcCCO[$i].',mc.qteConsu='.$qteConsu[$i].',mc.id_assuServ='.$idassurance.' WHERE mc.id_medconsu='.$idmc[$i].'');
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_consult_hosp mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.insupercentServ='.$add[$i].',mc.prixprestationConsu=0,mc.prixprestationConsuCCO=0,mc.prixautreConsu='.$prixmc[$i].',mc.prixautreConsuCCO='.$prixmcCCO[$i].',mc.qteConsu='.$qteConsu[$i].',mc.id_assuServ='.$idassurance.' WHERE mc.id_medconsu='.$idmc[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationConsu, prixprestationConsuCCO FROM med_consult_hosp mco WHERE mco.id_prestationConsu=:idprestaConsu AND numero=:numPa AND id_factureMedKine=0 ');

				$valueGroupBy->execute(array(
					'idprestaConsu'=>$idprestamc[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_consult_hosp mc SET mc.prixprestationConsu='.$prixmco[$i].',mc.prixprestationConsuCCO='.$prixmcoCCO[$i].' WHERE mc.id_prestationConsu='.$idprestamc[$i].'');
				}
			}
			
		}
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc, patients_hosp p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_hospMed=:idconsu AND p.id_hosp=mc.id_hospMed AND mc.datehosp!="0000-00-00" AND mc.id_assuServ=:idassu AND mc.id_prestationConsu!=1068 AND mc.id_prestationConsu!=984 AND mc.id_prestationConsu!=1216 AND mc.id_prestationConsu!=983 AND mc.id_prestationConsu!=1143 AND mc.id_prestationConsu!=1144 AND mc.datehosp>=:datedebut AND mc.datehosp<=:datefin ORDER BY mc.id_medconsu');
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idconsu'=>$_GET['idhosp'],
		'datedebut'=>$datefactudebut,
		'datefin'=>$datefactufin
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
		$TotalMedConsultCCO = 0;
		$TotalMedConsultPayed = 0;
		$TotalMedConsultPayedCCO = 0;

	

		/*-------Requête pour AFFICHER med_surge_hosp-----------*/
	
		
		if(isset($_POST['idprestaSurge']))
		{
			
			$idassuSurge=$idassu;
		
			$comptAssuSurge=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuSurge->setFetchMode(PDO::FETCH_OBJ);
			
			$assuCount = $comptAssuSurge->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuSurge=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuSurge->execute(array(
				'idassu'=>$idassuSurge
				));
				
				$getAssuSurge->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuSurge->fetch())
				{
					$presta_assuSurge='prestations_'.$ligneNomAssu->nomassurance;
				}
			}



			$idprestams = array();
			$prixms = array();
			$prixmsCCO = array();
			$addSurge = array();
			$idms = array();
			$autrems = array();
			$qteSurge = array();

			foreach($_POST['idprestaSurge'] as $ms)
			{
				$idprestams[] = $ms;
			}
			
			foreach($_POST['prixprestaSurge'] as $valms)
			{
				$prixms[] = $valms;
			}

			foreach($_POST['prixprestaSurgeCCO'] as $valmsCCO)
			{
				$prixmsCCO[] = $valmsCCO;
			}
			
			foreach($_POST['percentSurge'] as $valeurSurge)
			{
				$addSurge[] = $valeurSurge;
			}
			
			foreach($_POST['idmedSurge'] as $valeurms)
			{
				$idms[] = $valeurms;
			}
			
			foreach($_POST['autreSurge'] as $autrevaluems)
			{
				$autrems[] = $autrevaluems;
			}
			
			foreach($_POST['quantitySurge'] as $valueSurge)
			{
				$qteSurge[] = $valueSurge;
			}
			
			
			for($i=0;$i<sizeof($addSurge);$i++)
			{
				
				// echo $addSurge[$i].'_'.$idms[$i].'_('.$prixms[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuSurge.' p WHERE p.id_prestation='.$idprestams[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_surge_hosp ms SET ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge='.$prixms[$i].',ms.prixprestationSurgeCCO='.$prixmsCCO[$i].',ms.qteSurge='.$qteSurge[$i].',ms.id_assuSurge='.$idassurance.' WHERE ms.id_medsurge='.$idms[$i].'');
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_surge_hosp ms WHERE ms.id_medsurge='.$idms[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_surge_hosp ms SET ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge=0,ms.prixprestationSurgeCCO=0,ms.prixautrePrestaS='.$prixms[$i].',ms.prixautrePrestaSCCO='.$prixmsCCO[$i].',ms.qteSurge='.$qteSurge[$i].',ms.id_assuSurge='.$idassurance.' WHERE ms.id_medsurge='.$idms[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationSurge, prixprestationSurgeCCO FROM med_surge_hosp ms WHERE ms.id_prestationSurge=:idprestaSurge AND numero=:numPa AND id_factureMedSurge=0 ');

				$valueGroupBy->execute(array(
					'idprestaSurge'=>$idprestams[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_surge_hosp ms SET ms.prixprestationSurge='.$prixms[$i].',ms.prixprestationSurgeCCO='.$prixmsCCO[$i].' WHERE ms.id_prestationSurge='.$idprestams[$i].'');
				}
			}
		}
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms, patients_hosp p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_hospSurge=:idhosp AND p.id_hosp=ms.id_hospSurge AND ms.id_assuSurge=:idassu AND ms.datehosp>=:datedebut AND ms.datehosp<=:datefin ORDER BY ms.id_medsurge');
		$resultMedSurge->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp'],
		'datedebut'=>$datefactudebut,
		'datefin'=>$datefactufin
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();

		$TotalMedSurge = 0;
		$TotalMedSurgeCCO = 0;
		$TotalMedSurgePayed = 0;
		$TotalMedSurgePayedCCO = 0;

	
	

		/*-------Requête pour AFFICHER med_inf_hosp-----------*/


		if(isset($_POST['idprestaInf']))
		{

			$idassuInf=$idassu;

			$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);

			$assuCount = $comptAssuInf->rowCount();

			for($i=1;$i<=$assuCount;$i++)
			{

				$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuInf->execute(array(
				'idassu'=>$idassuInf
				));

				$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuInf->fetch())
				{
					$presta_assuInf='prestations_'.$ligneNomAssu->nomassurance;
				}
			}



			$idprestami = array();
			$prixmi = array();
			$prixmiCCO = array();
			$addInf = array();
			$idmi = array();
			$autremi = array();
			$qteInf = array();

			foreach($_POST['idprestaInf'] as $mi)
			{
				$idprestami[] = $mi;
			}

			foreach($_POST['prixprestaInf'] as $valmi)
			{
				$prixmi[] = $valmi;
			}

			foreach($_POST['prixprestaInfCCO'] as $valmiCCO)
			{
				$prixmiCCO[] = $valmiCCO;
			}

			foreach($_POST['percentInf'] as $valeurInf)
			{
				$addInf[] = $valeurInf;
			}

			foreach($_POST['idmedInf'] as $valeurmi)
			{
				$idmi[] = $valeurmi;
			}

			foreach($_POST['autreInf'] as $autrevaluemi)
			{
				$autremi[] = $autrevaluemi;
			}

			foreach($_POST['quantityInf'] as $valueInf)
			{
				$qteInf[] = $valueInf;
			}


			for($i=0;$i<sizeof($addInf);$i++)
			{

				// echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';

				$result=$connexion->query('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation='.$idprestami[$i].'');

				$result->setFetchMode(PDO::FETCH_OBJ);

				$comptPresta=$result->rowCount();

				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.prixprestationCCO='.$prixmiCCO[$i].',mi.qteInf='.$qteInf[$i].',mi.id_assuInf='.$idassurance.' WHERE mi.id_medinf='.$idmi[$i].'');

					}

				}else{

					$results=$connexion->query('SELECT *FROM med_inf_hosp mi WHERE mi.id_medinf='.$idmi[$i].'');

					$results->setFetchMode(PDO::FETCH_OBJ);

					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixprestationCCO=0,mi.prixautrePrestaM='.$prixmi[$i].',mi.prixautrePrestaMCCO='.$prixmiCCO[$i].',mi.qteInf='.$qteInf[$i].',mi.id_assuInf='.$idassurance.' WHERE mi.id_medinf='.$idmi[$i].'');

					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
					$valueGroupBy = $connexion->prepare('SELECT prixprestation, prixprestationCCO FROM med_inf_hosp mco WHERE mco.id_prestation=:idpresta AND numero=:numPa AND id_factureMedInf=0 ');

				$valueGroupBy->execute(array(
					'idpresta'=>$idprestami[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_inf_hosp mi SET mi.prixprestation='.$prixmi[$i].',mi.prixprestationCCO='.$prixmiCCO[$i].' WHERE id_prestation='.$idprestami[$i].'');
				}
			}
		}

		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, patients_hosp p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_hospInf=:idhosp AND p.id_hosp=mi.id_hospInf AND mi.id_assuInf=:idassu AND mi.datehosp>=:datedebut AND mi.datehosp<=:datefin ORDER BY mi.id_medinf');
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp'],
		'datedebut'=>$datefactudebut,
		'datefin'=>$datefactufin
		));

		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();

		$TotalMedInf = 0;
		$TotalMedInfCCO = 0;
		$TotalMedInfPayed = 0;
		$TotalMedInfPayedCCO = 0;



		/*-------Requête pour AFFICHER med_labo_hosp-----------*/
	
		
		if(isset($_POST['idprestaLab']))
		{
			$idassuLab=$idassu;

			$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
			
			$assuCount = $comptAssuLab->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuLab->execute(array(
				'idassu'=>$idassuLab
				));
				
				$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuLab->fetch())
				{
					$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
				}
			}
			
			
			
			$idprestaml = array();
			$prixml = array();
			$prixmlCCO = array();
			$addLab = array();
			$idml = array();
			$autreml = array();
			$qteLab = array();


			foreach($_POST['idprestaLab'] as $ml)
			{
				$idprestaml[] = $ml;
			}
			
			foreach($_POST['prixprestaLab'] as $valml)
			{
				$prixml[] = $valml;
			}

			foreach($_POST['prixprestaLabCCO'] as $valmlCCO)
			{
				$prixmlCCO[] = $valmlCCO;
			}
			
			foreach($_POST['percentLab'] as $valeurLab)
			{
				$addLab[] = $valeurLab;
			}
			
			foreach($_POST['idmedLab'] as $valeurml)
			{
				$idml[] = $valeurml;
			}
			
			foreach($_POST['autreLab'] as $autrevalueml)
			{
				$autreml[] = $autrevalueml;
			}
			
			foreach($_POST['quantityLab'] as $valueLab)
			{
				$qteLab[] = $valueLab;
			}
			
			for($i=0;$i<sizeof($addLab);$i++)
			{
				
				// echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation='.$idprestaml[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
				
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.prixprestationExaCCO='.$prixmlCCO[$i].',ml.qteLab='.$qteLab[$i].',ml.id_assuLab='.$idassurance.' WHERE ml.id_medlabo='.$idml[$i].'');
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_labo_hosp ml WHERE ml.id_medlabo='.$idml[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa=0,ml.prixprestationExaCCO=0,ml.prixautreExamen='.$prixml[$i].',ml.prixautreExamenCCO='.$prixmlCCO[$i].',ml.qteLab='.$qteLab[$i].',ml.id_assuLab='.$idassurance.' WHERE ml.id_medlabo='.$idml[$i].'');

					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationExa, prixprestationExaCCO FROM med_labo_hosp mco WHERE mco.id_prestationExa=:idprestaExa AND numero=:numPa AND id_factureMedLabo=0 ');

				$valueGroupBy->execute(array(
					'idprestaExa'=>$idprestaml[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_labo_hosp ml SET ml.prixprestationExa='.$prixml[$i].',ml.prixprestationExaCCO='.$prixmlCCO[$i].' WHERE id_prestationExa='.$idprestaml[$i].'');
				}
			}
		}
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, patients_hosp p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_hospLabo=:idhosp AND p.id_hosp=ml.id_hospLabo AND ml.id_assuLab=:idassu AND ml.datehosp>=:datedebut AND ml.datehosp<=:datefin ORDER BY ml.id_medlabo');
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp'],
		'datedebut'=>$datefactudebut,
		'datefin'=>$datefactufin
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
		$TotalMedLaboCCO = 0;
		$TotalMedLaboPayed = 0;
		$TotalMedLaboPayedCCO = 0;

	
	
	
		/*-------Requête pour AFFICHER med_radio_hosp-----------*/
	
		
		if(isset($_POST['idprestaRad']))
		{
			$idassuRad=$idassu;

			$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
			
			$assuCount = $comptAssuRad->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuRad=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuRad->execute(array(
				'idassu'=>$idassuRad
				));
				
				$getAssuRad->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuRad->fetch())
				{
					$presta_assuRad='prestations_'.$ligneNomAssu->nomassurance;
				}
			}

			
			
			$idprestamr = array();
			$prixmr = array();
			$prixmrCCO = array();
			$addRad = array();
			$idmr = array();
			$autremr = array();
			$qteRad = array();


			foreach($_POST['idprestaRad'] as $mr)
			{
				$idprestamr[] = $mr;
			}
			
			foreach($_POST['prixprestaRad'] as $valmr)
			{
				$prixmr[] = $valmr;
			}

			foreach($_POST['prixprestaRadCCO'] as $valmrCCO)
			{
				$prixmrCCO[] = $valmrCCO;
			}
			
			foreach($_POST['percentRad'] as $valeurRad)
			{
				$addRad[] = $valeurRad;
			}
			
			foreach($_POST['idmedRad'] as $valeurmr)
			{
				$idmr[] = $valeurmr;
			}
			
			foreach($_POST['autreRad'] as $autrevaluemr)
			{
				$autremr[] = $autrevaluemr;
			}
			
			foreach($_POST['quantityRad'] as $valueRad)
			{
				$qteRad[] = $valueRad;
			}
			
			for($i=0;$i<sizeof($addRad);$i++)
			{
				
				// echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation='.$idprestamr[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
				
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.prixprestationRadioCCO='.$prixmrCCO[$i].',mr.qteRad='.$qteRad[$i].',mr.id_assuRad='.$idassurance.' WHERE mr.id_medradio='.$idmr[$i].'');
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_radio_hosp mr WHERE mr.id_medradio='.$idmr[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixprestationRadioCCO=0,mr.prixautreRadio='.$prixmr[$i].',mr.prixautreRadioCCO='.$prixmrCCO[$i].',mr.qteRad='.$qteRad[$i].',mr.id_assuRad='.$idassurance.' WHERE mr.id_medradio='.$idmr[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau 
				$valueGroupBy = $connexion->prepare('SELECT prixprestationRadio, prixprestationRadioCCO FROM med_radio_hosp mco WHERE mco.id_prestationRadio=:idprestaRadio AND numero=:numPa AND id_factureMedRadio=0 ');

				$valueGroupBy->execute(array(
					'idprestaRadio'=>$idprestamr[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_radio_hosp mr SET mr.prixprestationRadio='.$prixmr[$i].',mr.prixprestationRadioCCO='.$prixmrCCO[$i].' WHERE id_prestationRadio='.$idprestamr[$i].'');
				}
			}
		}
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, patients_hosp p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_hospRadio=:idhosp AND p.id_hosp=mr.id_hospRadio AND mr.id_assuRad=:idassu AND mr.datehosp>=:datedebut AND mr.datehosp<=:datefin ORDER BY mr.id_medradio');
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp'],
		'datedebut'=>$datefactudebut,
		'datefin'=>$datefactufin
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		$TotalMedRadioCCO = 0;
		$TotalMedRadioPayed = 0;
		$TotalMedRadioPayedCCO = 0;



        /*-------Requête pour AFFICHER med_kine_hosp-----------*/


        if(isset($_POST['idprestaKine']))
        {

            $idassuKine=$idassu;

            $comptAssuKine=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

            $comptAssuKine->setFetchMode(PDO::FETCH_OBJ);

            $assuCount = $comptAssuKine->rowCount();

            for($i=1;$i<=$assuCount;$i++)
            {

                $getAssuKine=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
                $getAssuKine->execute(array(
                    'idassu'=>$idassuKine
                ));

                $getAssuKine->setFetchMode(PDO::FETCH_OBJ);

                if($ligneNomAssu=$getAssuKine->fetch())
                {
                    $presta_assuKine='prestations_'.$ligneNomAssu->nomassurance;
                }
            }



            $idprestamk = array();
            $prixmk = array();
            $prixmkCCO = array();
            $addKine = array();
            $idmk = array();
            $autremk = array();
            $qteKine = array();

            foreach($_POST['idprestaKine'] as $mk)
            {
                $idprestamk[] = $mk;
            }

            foreach($_POST['prixprestaKine'] as $valmk)
            {
                $prixmk[] = $valmk;
            }

            foreach($_POST['prixprestaKineCCO'] as $valmkCCO)
            {
                $prixmkCCO[] = $valmkCCO;
            }

            foreach($_POST['percentKine'] as $valeurKine)
            {
                $addKine[] = $valeurKine;
            }

            foreach($_POST['idmedKine'] as $valeurmk)
            {
                $idmk[] = $valeurmk;
            }

            foreach($_POST['autreKine'] as $autrevaluemk)
            {
                $autremk[] = $autrevaluemk;
            }

            foreach($_POST['quantityKine'] as $valueKine)
            {
                $qteKine[] = $valueKine;
            }


            for($i=0;$i<sizeof($addKine);$i++)
            {

                // echo $addKine[$i].'_'.$idmk[$i].'_('.$prixmk[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assuKine.' p WHERE p.id_prestation='.$idprestamk[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    if($ligne=$result->fetch())
                    {
                        $updatepercent=$connexion->query('UPDATE med_kine_hosp mk SET mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine='.$prixmk[$i].',mk.prixprestationKineCCO='.$prixmkCCO[$i].',mk.qteKine='.$qteKine[$i].',mk.id_assuKine='.$idassurance.' WHERE mk.id_medkine='.$idmk[$i].'');

                    }

                }else{

                    $results=$connexion->query('SELECT *FROM med_kine_hosp mk WHERE mk.id_medkine='.$idmk[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->query('UPDATE med_kine_hosp mk SET mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine=0,mk.prixprestationKineCCO=0,mk.prixautrePrestaK='.$prixmk[$i].',mk.prixautrePrestaKCCO='.$prixmkCCO[$i].',mk.qteKine='.$qteKine[$i].',mk.id_assuKine='.$idassurance.' WHERE mk.id_medkine='.$idmk[$i].'');

                    }
                }
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationKine, prixprestationKineCCO FROM med_Kine_hosp mco WHERE mco.id_prestationKine=:idprestaKine AND numero=:numPa AND id_factureMedKine=0 ');

				$valueGroupBy->execute(array(
					'idprestaKine'=>$idprestamk[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_Kine_hosp mco SET mco.prixprestationKine='.$prixmk[$i].',mco.prixprestationKineCCO='.$prixmkCCO[$i].' WHERE id_prestionKine='.$idprestamk[$i].'');
					/*$compt = $updateprice->rowCount();
					if ($compt) {
						echo 'OK';
					}*/ 
				}
            }
            
        }

        $resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk, patients_hosp p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_hospKine=:idhosp AND p.id_hosp=mk.id_hospKine AND mk.id_assuKine=:idassu AND mk.datehosp>=:datedebut AND mk.datehosp<=:datefin ORDER BY mk.id_medKine');
        $resultMedKine->execute(array(
            'num'=>$numPa,
            'idassu'=>$idassurance,
            'idhosp'=>$_GET['idhosp'],
            'datedebut'=>$datefactudebut,
            'datefin'=>$datefactufin
        ));

        $resultMedKine->setFetchMode(PDO::FETCH_OBJ);

        $comptMedKine=$resultMedKine->rowCount();

        $TotalMedKine = 0;
        $TotalMedKineCCO = 0;
        $TotalMedKinePayed = 0;
        $TotalMedKinePayedCCO = 0;




    /*-------Requête pour AFFICHER med_consom_hosp-----------*/
				
		if(isset($_POST['idprestaConsom']))
		{
			
			$idassuConsom=$idassu;
			
			$comptAssuConsom=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsom->setFetchMode(PDO::FETCH_OBJ);
			
			$assuCount = $comptAssuConsom->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsom=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuConsom->execute(array(
				'idassu'=>$idassuConsom
				));
				
				$getAssuConsom->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsom->fetch())
				{
					$presta_assuConsom='prestations_'.$ligneNomAssu->nomassurance;
				}
			}
			


			$idprestaconsom = array();
			$prixmco = array();
			$prixmcoCCO = array();
			$addConsom = array();
			$idmco = array();
			$autreconsom = array();
			$qteConsom = array();


			foreach($_POST['idprestaConsom'] as $consom)
			{
				$idprestaconsom[] = $consom;
			}
			
			foreach($_POST['prixprestaConsom'] as $valConsom)
			{
				$prixmco[] = $valConsom;
			}

			foreach($_POST['prixprestaConsomCCO'] as $valConsomCCO)
			{
				$prixmcoCCO[] = $valConsomCCO;
			}
			
			foreach($_POST['percentConsom'] as $valeurConsom)
			{
				$addConsom[] = $valeurConsom;
			}
			
			foreach($_POST['idmedConsom'] as $valeurmco)
			{
				$idmco[] = $valeurmco;
			}
			
			foreach($_POST['autreConsom'] as $autrevalueconsom)
			{
				$autreconsom[] = $autrevalueconsom;
			}

			foreach($_POST['quantityConsom'] as $valueConsom)
			{
				$qteConsom[] = $valueConsom;
			}
			
			for($i=0;$i<sizeof($addConsom);$i++)
			{
			
				// echo $addConsom[$i].'_'.$idmco[$i].'_('.$prixmco[$i].' : '.$qteConsom[$i].')<br/>';
				
			
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation='.$idprestaconsom[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.prixprestationConsomCCO='.$prixmcoCCO[$i].',mco.qteConsom='.$qteConsom[$i].',mco.id_assuConsom='.$idassurance.' WHERE mco.id_medconsom='.$idmco[$i].'');
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consom_hosp mco WHERE mco.id_medconsom='.$idmco[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0,mco.prixprestationConsomCCO=0,mco.prixautreConsom='.$prixmco[$i].',mco.prixautreConsomCCO='.$prixmcoCCO[$i].', mco.qteConsom='.$qteConsom[$i].',mco.id_assuConsom='.$idassurance.' WHERE mco.id_medconsom='.$idmco[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau 
				$valueGroupBy = $connexion->prepare('SELECT prixprestationConsom, prixprestationConsomCCO FROM med_consom_hosp mco WHERE mco.id_prestationConsom=:idprestaconsom AND numero=:numPa AND id_factureMedConsom=0 ');

				$valueGroupBy->execute(array(
					'idprestaconsom'=>$idprestaconsom[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_consom_hosp mco SET mco.prixprestationConsom='.$prixmco[$i].',mco.prixprestationConsomCCO='.$prixmcoCCO[$i].' WHERE id_prestationConsom='.$idprestaconsom[$i].'');
					/*$compt = $updateprice->rowCount();
					if ($compt) {
						echo 'OK';
					}*/ 
				}
			}
		}


		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, patients_hosp p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_hospConsom=:idhosp AND p.id_hosp=mco.id_hospConsom AND mco.id_assuConsom=:idassu AND mco.datehosp>=:datedebut AND mco.datehosp<=:datefin ORDER BY mco.id_medconsom');
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp'],
		'datedebut'=>$datefactudebut,
		'datefin'=>$datefactufin
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		$TotalMedConsomCCO = 0;
		$TotalMedConsomPayed = 0;
		$TotalMedConsomPayedCCO = 0;

	
	
		/*-------Requête pour AFFICHER med_medoc_hosp-----------*/
	
		
		if(isset($_POST['idprestaMedoc']))
		{
			$idassuMedoc=$idassu;
			
			$comptAssuMedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuMedoc->setFetchMode(PDO::FETCH_OBJ);
			
			$assuCount = $comptAssuMedoc->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuMedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
				$getAssuMedoc->execute(array(
				'idassu'=>$idassuMedoc
				));
				
				$getAssuMedoc->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuMedoc->fetch())
				{
					$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
				}
			}
			
			

			$idprestamedoc = array();
			$prixmdo = array();
			$prixmdoCCO = array();
			$addMedoc = array();
			$idmdo = array();
			$autremedoc = array();
			$qteMedoc = array();


			foreach($_POST['idprestaMedoc'] as $medoc)
			{
				$idprestamedoc[] = $medoc;
			}
			
			foreach($_POST['prixprestaMedoc'] as $valMedoc)
			{
				$prixmdo[] = $valMedoc;
			}

			foreach($_POST['prixprestaMedocCCO'] as $valMedocCCO)
			{
				$prixmdoCCO[] = $valMedocCCO;
			}
			
			foreach($_POST['percentMedoc'] as $valeurMedoc)
			{
				$addMedoc[] = $valeurMedoc;
			}
			
			foreach($_POST['idmedMedoc'] as $valeurmdo)
			{
				$idmdo[] = $valeurmdo;
			}
			
			foreach($_POST['autreMedoc'] as $autrevaluemedoc)
			{
				$autremedoc[] = $autrevaluemedoc;
			}

			foreach($_POST['quantityMedoc'] as $valueMedoc)
			{
				$qteMedoc[] = $valueMedoc;
			}
			
			for($i=0;$i<sizeof($addMedoc);$i++)
			{
			
				// echo $addMedoc[$i].'_'.$idmdo[$i].'_('.$prixmdo[$i].' : '.$qteMedoc[$i].')<br/>';
				
				
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation='.$idprestamedoc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
				
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.prixprestationMedocCCO='.$prixmdoCCO[$i].',mdo.qteMedoc='.$qteMedoc[$i].',mdo.id_assuMedoc='.$idassurance.' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
						
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixprestationMedocCCO=0,mdo.prixautreMedoc='.$prixmdo[$i].',mdo.prixautreMedocCCO='.$prixmdoCCO[$i].', mdo.qteMedoc='.$qteMedoc[$i].',mdo.id_assuMedoc='.$idassurance.' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationMedoc, prixprestationMedocCCO FROM med_medoc_hosp mdo WHERE mdo.id_prestationMedoc=:idprestaMedoc AND numero=:numPa AND mdo.id_factureMedMedoc=0 ');

				$valueGroupBy->execute(array(
					'idprestaMedoc'=>$idprestamedoc[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_medoc_hosp mdo SET mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.prixprestationMedocCCO='.$prixmdoCCO[$i].' WHERE id_prestationMedoc='.$idprestamedoc[$i].'');
					/*$compt = $updateprice->rowCount();
					if ($compt) {
					 	echo 'OK';
					}*/ 
				}
			}
		}
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, patients_hosp p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_hospMedoc=:idhosp AND p.id_hosp=mdo.id_hospMedoc AND mdo.id_assuMedoc=:idassu AND mdo.datehosp>=:datedebut AND mdo.datehosp<=:datefin ORDER BY mdo.id_medmedoc');
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp'],
		'datedebut'=>$datefactudebut,
		'datefin'=>$datefactufin
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
		$TotalMedMedocCCO = 0;
		$TotalMedMedocPayed = 0;
		$TotalMedMedocPayedCCO = 0;



        /*-------Requête pour AFFICHER med_ortho_hosp-----------*/


        if(isset($_POST['idprestaOrtho']))
        {

            $idassuOrtho=$idassu;

            $comptAssuOrtho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

            $comptAssuOrtho->setFetchMode(PDO::FETCH_OBJ);

            $assuCount = $comptAssuOrtho->rowCount();

            for($i=1;$i<=$assuCount;$i++)
            {

                $getAssuOrtho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
                $getAssuOrtho->execute(array(
                    'idassu'=>$idassuOrtho
                ));

                $getAssuOrtho->setFetchMode(PDO::FETCH_OBJ);

                if($ligneNomAssu=$getAssuOrtho->fetch())
                {
                    $presta_assuOrtho='prestations_'.$ligneNomAssu->nomassurance;
                }
            }



            $idprestamo = array();
            $prixmo = array();
            $prixmoCCO = array();
            $addOrtho = array();
            $idmo = array();
            $autremo = array();
            $qteOrtho = array();

            foreach($_POST['idprestaOrtho'] as $mo)
            {
                $idprestamo[] = $mo;
            }

            foreach($_POST['prixprestaOrtho'] as $valmo)
            {
                $prixmo[] = $valmo;
            }

            foreach($_POST['prixprestaOrthoCCO'] as $valmoCCO)
            {
                $prixmoCCO[] = $valmoCCO;
            }

            foreach($_POST['percentOrtho'] as $valeurOrtho)
            {
                $addOrtho[] = $valeurOrtho;
            }

            foreach($_POST['idmedOrtho'] as $valeurmo)
            {
                $idmo[] = $valeurmo;
            }

            foreach($_POST['autreOrtho'] as $autrevaluemo)
            {
                $autremo[] = $autrevaluemo;
            }

            foreach($_POST['quantityOrtho'] as $valueOrtho)
            {
                $qteOrtho[] = $valueOrtho;
            }


            for($i=0;$i<sizeof($addOrtho);$i++)
            {

                // echo $addOrtho[$i].'_'.$idmo[$i].'_('.$prixmo[$i].')<br/>';

                $result=$connexion->query('SELECT *FROM '.$presta_assuOrtho.' p WHERE p.id_prestation='.$idprestamo[$i].'');

                $result->setFetchMode(PDO::FETCH_OBJ);

                $comptPresta=$result->rowCount();

                if($comptPresta!=0)
                {
                    if($ligne=$result->fetch())
                    {
                        $updatepercent=$connexion->query('UPDATE med_ortho_hosp mo SET mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho='.$prixmo[$i].',mo.prixprestationOrthoCCO='.$prixmoCCO[$i].',mo.qteOrtho='.$qteOrtho[$i].',mo.id_assuOrtho='.$idassurance.' WHERE mo.id_medortho='.$idmo[$i].'');

                    }

                }else{

                    $results=$connexion->query('SELECT *FROM med_ortho_hosp mo WHERE mo.id_medortho='.$idmo[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->query('UPDATE med_ortho_hosp mo SET mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho=0,mo.prixprestationOrthoCCO=0,mo.prixautrePrestaO='.$prixmo[$i].',mo.prixautrePrestaOCCO='.$prixmoCCO[$i].',mo.qteOrtho='.$qteOrtho[$i].',mo.id_assuOrtho='.$idassurance.' WHERE mo.id_medOrtho='.$idmo[$i].'');

                    }
                }
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationOrtho, prixprestationOrthoCCO FROM med_ortho_hosp mo WHERE mo.id_prestationOrtho=:idprestaOrtho AND numero=:numPa AND mo.id_factureMedOrtho=0 ');

				$valueGroupBy->execute(array(
					'idprestaOrtho'=>$idprestamo[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_ortho_hosp mo SET mdo.prixprestationOrtho='.$prixmo[$i].',mo.prixprestationOrthoCCO='.$prixmoCCO[$i].' WHERE id_prestationOrtho='.$idprestamo[$i].'');
					/*$compt = $updateprice->rowCount();
					if ($compt) {
					 	echo 'OK';
					}*/ 
				}
            }
        }

        $resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, patients_hosp p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_hospOrtho=:idhosp AND p.id_hosp=mo.id_hospOrtho AND mo.id_assuOrtho=:idassu AND mo.datehosp>=:datedebut AND mo.datehosp<=:datefin ORDER BY mo.id_medOrtho');
        $resultMedOrtho->execute(array(
            'num'=>$numPa,
            'idassu'=>$idassurance,
            'idhosp'=>$_GET['idhosp'],
            'datedebut'=>$datefactudebut,
            'datefin'=>$datefactufin
        ));

        $resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

        $comptMedOrtho=$resultMedOrtho->rowCount();

        $TotalMedOrtho = 0;
        $TotalMedOrthoCCO = 0;
        $TotalMedOrthoPayed = 0;
        $TotalMedOrthoPayedCCO = 0;


?>
	
	<table style="width:100%; margin:20px auto auto;">
		<tr>
			<td style="text-align:left; width:30%;">
				<!-- <?php $annee = $_POST['jours'].'-'.$_POST['mois'].'-'.$_POST['annee'];?> -->
				<?php
					if (isset($_GET['dateSortie'])) {
						$annee = $_GET['dateSortie'];
					}
				?>
				<h4><?php echo date('d-M-Y', strtotime($datefactufin));?></h4>
			</td>
			
			<td style="text-align:center;">
				<h2 style="font-size:150%; font-weight:600;">Hospitalisation bill-exit n° <?php echo $idBilling;?></h2>
			</td>
			
			<td style="text-align:right;width:15%;">
			
				<form method="post" action="printBill_hospFactReport.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&datefacturedebut=<?php if(isset($_POST['datefactdebut'])){ echo $_POST['datefactdebut'];}else{ if(isset($_GET['datefacturedebut'])){ echo $_GET['datefacturedebut'];}} ?>&datefacturefin=<?php if(isset($_POST['datefactfin'])){ echo $_POST['datefactfin'];}else{ if(isset($_GET['datefacturefin'])){ echo $_GET['datefacturefin'];}} ?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?>&idresto=<?php if(isset($_POST['idresto'])){ echo $_POST['idresto'];}else{ if(isset($_GET['idresto'])){ echo $_GET['idresto'];}}?>&idtourdesalle=<?php if(isset($_POST['idtourdesalle'])){ echo $_POST['idtourdesalle'];}else{ if(isset($_GET['idtourdesalle'])){ echo $_GET['idtourdesalle'];}}?>&updatebill=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td class="buttonBill" style="width:15%;">
				<a href="categoriesbill_fact_hosp.php?inf=<?php echo 0;?>&num=<?php echo $_GET['num'];?>&datefacturedebut=<?php if(isset($_POST['datefactdebut'])){ echo $_POST['datefactdebut'];}else{ if(isset($_GET['datefacturedebut'])){ echo $_GET['datefacturedebut'];}} ?>&datefacturefin=<?php if(isset($_POST['datefactfin'])){ echo $_POST['datefactfin'];}else{ if(isset($_GET['datefacturefin'])){ echo $_GET['datefacturefin'];}} ?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&previewprint=ok&facturer=ok&idresto=<?php if(isset($_POST['idresto'])){ echo $_POST['idresto'];}else{ if(isset($_GET['idresto'])){ echo $_GET['idresto'];}}?>&idtourdesalle=<?php if(isset($_POST['idtourdesalle'])){ echo $_POST['idtourdesalle'];}else{ if(isset($_GET['idtourdesalle'])){ echo $_GET['idtourdesalle'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="patients1_hosp.php?numPa=<?php echo $_GET['num'];?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?>&datefacturedebut=<?php if(isset($_POST['datefactdebut'])){ echo $_POST['datefactdebut'];}else{ if(isset($_GET['datefacturedebut'])){ echo $_GET['datefacturedebut'];}} ?>&datefacturefin=<?php if(isset($_POST['datefactfin'])){ echo $_POST['datefactfin'];}else{ if(isset($_GET['datefacturefin'])){ echo $_GET['datefacturefin'];}} ?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&createBill=<?php echo $createBill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&idresto=<?php if(isset($_POST['idresto'])){ echo $_POST['idresto'];}else{ if(isset($_GET['idresto'])){ echo $_GET['idresto'];}}?>&idtourdesalle=<?php if(isset($_POST['idtourdesalle'])){ echo $_POST['idtourdesalle'];}else{ if(isset($_GET['idtourdesalle'])){ echo $_GET['idtourdesalle'];}}?>&facturer=ok" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141);?></button>
				</a>
			</td>
		</tr>
	</table>
	
	
	<?php
		try
		{

            $TotalGnlPriceCCO=0;
			$TotalGnlPrice=0;
			$TotalGnlTopupPrice=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlPatientBalance=0;
			$TotalGnlInsurancePrice=0;

            $TotalGnlPricePayedCCO=0;
            $TotalGnlPricePayed=0;
            $TotalGnlTopupPricePayed=0;
            $TotalGnlPatientPricePayed=0;
            $TotalGnlPatientBalancePayed=0;
            $TotalGnlInsurancePricePayed=0;

            $i=0;
			$x=0;
			$y=0;
			$z=0;
			
			if($comptHosp != 0)
			{
		
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Type of consultation')
							->setCellValue('C8', 'Price')
							->setCellValue('D8', 'Patient Price')
							->setCellValue('E8', 'Insurance Price');
				
					
	?>
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 25px;width:90%;">
				<thead>
					<tr>
						<th style="width:5%;text-align:center;">Room</th>
						<!--<th style="width:20%;text-align:center;">Type</th>-->
						<th style="width:10%;text-align:center;">Date In</th>
						<th style="width:13%;text-align:center;">Date Out</th>
						<th style="width:5%;text-align:center;">Days</th>
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day ra</th>
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day</th>
						<th style="width:8%;text-align:center;">Balance ra</th>
						<th style="width:8%;text-align:center;">Balance <?php echo $nomassurance;?></th>
						<th style="width:6%;text-align:center;">Top Up</th>
						<th style="width:8%;text-align:center;">Percent</th>
						<th style="width:13%;text-align:center;">Patient <?php echo '('.$bill.'%)'?></th>
						<th style="width:13%;text-align:center;">Patient balance</th>
						<th style="width:13%;text-align:center;">Insurance</th>
						<th style="width:5%;" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
				<?php
				while($ligneHosp=$resultHosp->fetch())
				{

					
						$billpercent=$ligneHosp->insupercent_hosp;
						
						$idassu=$ligneHosp->id_assuHosp;
					?>
					<tr style="font-weight:bold;<?php if($ligneHosp->statusPaHosp!=1){ echo 'color:rgba(0, 0, 0, 0.5);';}?>">
						<td style="text-align:center;"><?php echo $ligneHosp->numroomPa;?></td>
						
						<?php
						
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


					
					$resultPresta=$connexion->prepare('SELECT *FROM rooms r,'.$presta_assu.' p WHERE r.numroom=:numroomPa AND r.id_prestationHosp=p.id_prestation');

					$resultPresta->execute(array(
					'numroomPa'=>$ligneHosp->numroomPa
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($comptPresta!=0)
					{
						if($lignePresta=$resultPresta->fetch())
						{
							if(isset($_POST['pourcentage']))
							{
								$resultats=$connexion->prepare('UPDATE patients_hosp SET insupercent_hosp=:percent WHERE id_hosp=:idHosp');
					
								$resultats->execute(array(
								'percent'=>$_POST['pourcentage'],
								'idHosp'=>$_GET['idhosp']
								
								))or die( print_r($connexion->errorInfo()));
							}
							
							if($lignePresta->namepresta!='')
							{
								$nameprestaHosp=$lignePresta->namepresta;
								// echo '<td style="text-align:center;">'.$lignePresta->namepresta.'</td>';
							}else{
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaHosp=$lignePresta->nompresta;
									// echo '<td style="text-align:center;">'.$lignePresta->nompresta.'</td>';
				
								}
							}

							$prixPrestaHosp = $lignePresta->prixpresta;
							$prixPrestaHospCCO = $lignePresta->prixprestaCCO;

						?>
							
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactdebut'])) {
								echo date('d-M-Y', strtotime($_POST['datefactdebut']));
							}
							if (isset($_GET['datefacturedebut'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturedebut']));
							}
								
							?>
							</td>
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactfin'])) {
								echo date('d-M-Y', strtotime($_POST['datefactfin']));
							}
							if (isset($_GET['datefacturefin'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturefin']));
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php

							if (isset($_POST['datefactdebut']) AND isset($_POST['datefactfin'])) {
								$datefactdebut=strtotime($_POST['datefactdebut']);
								$datefactfin=strtotime($_POST['datefactfin']);
							}
							if (isset($_GET['datefacturedebut']) AND isset($_GET['datefacturefin'])) {
								$datefactdebut=strtotime($_GET['datefacturedebut']);
								$datefactfin=strtotime($_GET['datefacturefin']);
							}
							
							
							
							$datediff= abs($datefactfin - $datefactdebut);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
							
							if($nbrejrs==1)
							{
								echo $nbrejrs.' day';
							}else{
								echo $nbrejrs.' days';
							}
							?>
							</td>

                            <td style="text-align:center;" class="buttonBill"><?php echo $ligneHosp->prixroomCCO;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;" class="buttonBill"><?php echo $ligneHosp->prixroom;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;">
							<?php


							$balanceHospCCO=$ligneHosp->prixroomCCO * $nbrejrs;
							
							echo $balanceHospCCO;
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
							<?php
							$balanceHosp=$ligneHosp->prixroom * $nbrejrs;

							echo $balanceHosp;

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
                                <?php
                                $topupHosp = $balanceHospCCO - $balanceHosp;

                                echo $topupHosp;
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;"><?php echo $billpercent;?>%</td>

                            <?php
                            $patientPriceHosp=($balanceHosp * $billpercent)/100;
                            $insurancePriceHosp= $balanceHosp - $patientPriceHosp;
                            ?>
							
							<td style="text-align:center;"><?php echo $patientPriceHosp;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                            <td>
                                <?php
                                $patientBalanceHosp = $topupHosp + $patientPriceHosp;
                                echo $patientBalanceHosp.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;"><?php echo $insurancePriceHosp;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
								
							<td class="buttonBill">
							<?php
							if($ligneHosp->statusPaHosp!=1)
							{
								echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
							}
							?>
							</td>
						</tr>
				<?php
						}
						
					}
					
					$arrayConsult[$i][0]=$nameprestaHosp;
					$arrayConsult[$i][1]=$prixPrestaHosp;
					$arrayConsult[$i][2]=$patientPriceHosp;
					$arrayConsult[$i][3]=$insurancePriceHosp;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','B9');
		
				}
				
				?>
				</tbody>
			</table>
			<!-- ---------------------- -->
			<!-- Restauration -->
			<?php
				if(isset($_GET['idresto']) || isset($_POST['idresto']))
				{
			?>
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 25px;width:90%;">
				<thead>
					<tr>
						<th style="width:5%;text-align:center;"></th>
						<!--<th style="width:20%;text-align:center;">Type</th>-->
						<th style="width:10%;text-align:center;">Date In</th>
						<th style="width:13%;text-align:center;">Date Out</th>
						<th style="width:5%;text-align:center;">Days</th>
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day ra</th>
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day</th>
						<th style="width:8%;text-align:center;">Balance ra</th>
						<th style="width:8%;text-align:center;">Balance <?php echo $nomassurance;?></th>
						<th style="width:6%;text-align:center;">Top Up</th>
						<th style="width:8%;text-align:center;">Percent</th>
						<th style="width:13%;text-align:center;">Patient <?php echo '('.$bill.'%)'?></th>
						<th style="width:13%;text-align:center;">Patient balance</th>
						<th style="width:13%;text-align:center;">Insurance</th>
						<th style="width:5%;" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
					
					<?php
					$TotalResto = 0;
					$TotalRestoCCO = 0;
					$TotalRestoPayed = 0;
					$TotalRestoPayedCCO = 0;

					$TotaltopupPrice=0;
		            $TotalpatientPrice=0;
		            $TotalpatientBalance=0;
		            $TotaluapPrice=0;
					/*while($ligneHosp1=$resultHosp->fetch())
					{
						
							$billpercent=$ligneHosp1->insupercent_hosp;
							
							$idassu=$ligneHosp1->id_assuHosp;*/
							if (isset($_POST['idresto'])) {
								$idrestoPost = $_POST['idresto'];
							}
							if (isset($_GET['idresto'])) {
								$idrestoPost = $_GET['idresto'];
								//echo 'GET idresto = '.$_GET['idresto'];
							}
						
						$resultResto=$connexion->prepare('SELECT *FROM restauration r WHERE r.id_resto=:idresto');
						$resultResto->execute(array(
						'idresto'=>$idrestoPost
						));
						$ligneResto = $resultResto->fetch(PDO::FETCH_OBJ);
						//$resultResto->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($comptPresta!=0)
						{
							$id_prestationResto = $ligneResto->id_prestationResto;
							$comptAssuConsuResto=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
							$comptAssuConsuResto->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCountResto = $comptAssuConsuResto->rowCount();
							
							for($i=1;$i<=$assuCountResto;$i++)
							{
								
								$getAssuConsuResto=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuConsuResto->execute(array(
								'idassu'=>$id_prestationResto
								));
								
								$getAssuConsuResto->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuResto=$getAssuConsuResto->fetch())
								{
									$presta_assu='prestations_'.$ligneNomAssuResto->nomassurance;
									echo $presta_assu;
								}
							}
							$presta_assu = strtolower($presta_assu);

							$selectResto =  $connexion->prepare('SELECT * FROM '.$presta_assu.' WHERE id_prestation=:id_prestation');
							$selectResto->execute(array(
							'id_prestation'=>$id_prestationResto
							)) or die(print_r($connexion->errorInfo()));

							$ligneSelectResto = $selectResto->fetch(PDO::FETCH_OBJ);
							$comptSelectResto = $selectResto->rowCount(); 
							if ($comptSelectResto!=0) {
					?>
					<tr style="font-weight:bold;">
						<td style="text-align:center;">
							<?php
								echo $ligneSelectResto->nompresta;

							?>
						</td>
						
						<?php
						/*
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


					
					
						if($lignePresta=$resultPresta->fetch())
						{
							if(isset($_POST['pourcentage']))
							{
								$resultats=$connexion->prepare('UPDATE patients_hosp SET insupercent_hosp=:percent WHERE id_hosp=:idHosp');
					
								$resultats->execute(array(
								'percent'=>$_POST['pourcentage'],
								'idHosp'=>$_GET['idhosp']
								
								))or die( print_r($connexion->errorInfo()));
							}
							
							if($lignePresta->namepresta!='')
							{
								$nameprestaHosp=$lignePresta->namepresta;
								// echo '<td style="text-align:center;">'.$lignePresta->namepresta.'</td>';
							}else{
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaHosp=$lignePresta->nompresta;
									// echo '<td style="text-align:center;">'.$lignePresta->nompresta.'</td>';
				
								}
							}

							$prixPrestaHosp = $lignePresta->prixpresta;
							$prixPrestaHospCCO = $lignePresta->prixprestaCCO;
*/
						?> 
							
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactdebut'])) {
								echo date('d-M-Y', strtotime($_POST['datefactdebut']));
							}
							if (isset($_GET['datefacturedebut'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturedebut']));
							}
								
							?>
							</td>
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactfin'])) {
								echo date('d-M-Y', strtotime($_POST['datefactfin']));
							}
							if (isset($_GET['datefacturefin'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturefin']));
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php

							if (isset($_POST['datefactdebut']) AND isset($_POST['datefactfin'])) {
								$datefactdebut=strtotime($_POST['datefactdebut']);
								$datefactfin=strtotime($_POST['datefactfin']);
							}
							if (isset($_GET['datefacturedebut']) AND isset($_GET['datefacturefin'])) {
								$datefactdebut=strtotime($_GET['datefacturedebut']);
								$datefactfin=strtotime($_GET['datefacturefin']);
							}
							
							
							$datediff= abs($datefactfin - $datefactdebut);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
							
							if($nbrejrs==1)
							{
								echo $nbrejrs.' day';
							}else{
								echo $nbrejrs.' days';
							}
							?>
							</td>

                            <td style="text-align:center;" class="buttonBill">
                            	<?php echo $ligneSelectResto->prixprestaCCO;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;" class="buttonBill"><?php echo $ligneSelectResto->prixpresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;">
							<?php
							$balanceRestoCCO=$ligneSelectResto->prixprestaCCO * $nbrejrs;
							
							echo $balanceRestoCCO;
										
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
							<?php

							$balanceResto=$ligneSelectResto->prixpresta * $nbrejrs;

							echo $balanceResto;
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
                                <?php
                                $topupResto = $balanceRestoCCO - $balanceResto;

                                echo $topupResto;
								

                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;"><?php echo $billpercent;?>%</td>

                            <?php
                            $patientPriceResto=($balanceResto * $billpercent)/100;
                            $insurancePriceResto= $balanceResto - $patientPriceResto;
                            ?>
							
							<td style="text-align:center;">
								<?php
								 	echo $patientPriceResto;
								 	
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

                            <td>
                                <?php
                                $patientBalanceResto = $topupResto + $patientPriceResto;
                                echo $patientBalanceResto.'';
                               
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;">
                            	<?php 
                            		echo $insurancePriceResto;
                            		
                            	?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
								
							<td class="buttonBill">
							<?php
							if($ligneResto->statusPaResto!=1)
							{
								echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
							}
							?>
							</td>
						</tr>
				<?php
						}else{
							$selectRestoPrivate =  $connexion->prepare('SELECT * FROM prestations_private WHERE id_prestation=:id_prestation');
							$selectRestoPrivate->execute(array(
							'id_prestation'=>$id_prestationResto
							)) or die(print_r($connexion->errorInfo()));

							$ligneSelectRestoPrivate = $selectRestoPrivate->fetch(PDO::FETCH_OBJ);
							?>
					<tr style="font-weight:bold;">
						<td style="text-align:center;">
							<?php
								echo $ligneSelectRestoPrivate->nompresta;

							?>
						</td>
						
						<?php
						/*
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


					
					
						if($lignePresta=$resultPresta->fetch())
						{
							if(isset($_POST['pourcentage']))
							{
								$resultats=$connexion->prepare('UPDATE patients_hosp SET insupercent_hosp=:percent WHERE id_hosp=:idHosp');
					
								$resultats->execute(array(
								'percent'=>$_POST['pourcentage'],
								'idHosp'=>$_GET['idhosp']
								
								))or die( print_r($connexion->errorInfo()));
							}
							
							if($lignePresta->namepresta!='')
							{
								$nameprestaHosp=$lignePresta->namepresta;
								// echo '<td style="text-align:center;">'.$lignePresta->namepresta.'</td>';
							}else{
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaHosp=$lignePresta->nompresta;
									// echo '<td style="text-align:center;">'.$lignePresta->nompresta.'</td>';
				
								}
							}

							$prixPrestaHosp = $lignePresta->prixpresta;
							$prixPrestaHospCCO = $lignePresta->prixprestaCCO;
*/
						?> 
							
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactdebut'])) {
								echo date('d-M-Y', strtotime($_POST['datefactdebut']));
							}
							if (isset($_GET['datefacturedebut'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturedebut']));
							}
								
							?>
							</td>
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactfin'])) {
								echo date('d-M-Y', strtotime($_POST['datefactfin']));
							}
							if (isset($_GET['datefacturefin'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturefin']));
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php

							if (isset($_POST['datefactdebut']) AND isset($_POST['datefactfin'])) {
								$datefactdebut=strtotime($_POST['datefactdebut']);
								$datefactfin=strtotime($_POST['datefactfin']);
							}
							if (isset($_GET['datefacturedebut']) AND isset($_GET['datefacturefin'])) {
								$datefactdebut=strtotime($_GET['datefacturedebut']);
								$datefactfin=strtotime($_GET['datefacturefin']);
							}
							
							
							$datediff= abs($datefactfin - $datefactdebut);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
							
							if($nbrejrs==1)
							{
								echo $nbrejrs.' day';
							}else{
								echo $nbrejrs.' days';
							}
							?>
							</td>

                            <td style="text-align:center;" class="buttonBill">
                            	<?php echo $ligneSelectRestoPrivate->prixprestaCCO;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;" class="buttonBill"><?php echo 0;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;">
							<?php
							$balanceRestoCCO=$ligneSelectRestoPrivate->prixprestaCCO * $nbrejrs;
							
							echo $balanceRestoCCO;
										
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
							<?php

							$balanceResto= 0 * $nbrejrs;

							echo $balanceResto;
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
                                <?php
                                $topupResto = $balanceRestoCCO - $balanceResto;

                                echo $topupResto;
								

                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;"><?php echo $billpercent;?>%</td>

                            <?php
                            $patientPriceResto=($balanceResto * $billpercent)/100;
                            $insurancePriceResto= $balanceResto - $patientPriceResto;
                            ?>
							
							<td style="text-align:center;">
								<?php
								 	echo $patientPriceResto;
								 	
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

                            <td>
                                <?php
                                $patientBalanceResto = $topupResto + $patientPriceResto;
                                echo $patientBalanceResto.'';
                               
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;">
                            	<?php 
                            		echo $insurancePriceResto;
                            		
                            	?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
								
							<td class="buttonBill">
							<?php
							if($ligneResto->statusPaResto!=1)
							{
								echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
							}
							?>
							</td>
						</tr>
				<?php
						}
						

					//}
					
					$arrayConsult[$i][0]=$nameprestaHosp;
					$arrayConsult[$i][1]=$prixPrestaHosp;
					$arrayConsult[$i][2]=$patientPriceHosp;
					$arrayConsult[$i][3]=$insurancePriceHosp;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','B9');
		
				}
				
				?>
				</tbody>
			</table>

					<?php
					}
					?>
					<!-- Restauration -->
			<?php
				if(isset($_GET['idtourdesalle']) || isset($_POST['idtourdesalle']))
				{
			?>
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 25px;width:90%;">
				<thead>
					<tr>
						<th style="width:5%;text-align:center;"></th>
						<!--<th style="width:20%;text-align:center;">Type</th>-->
						<th style="width:10%;text-align:center;">Date In</th>
						<th style="width:13%;text-align:center;">Date Out</th>
						<th style="width:5%;text-align:center;">Days</th>
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day ra</th>
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day</th>
						<th style="width:8%;text-align:center;">Balance ra</th>
						<th style="width:8%;text-align:center;">Balance <?php echo $nomassurance;?></th>
						<th style="width:6%;text-align:center;">Top Up</th>
						<th style="width:8%;text-align:center;">Percent</th>
						<th style="width:13%;text-align:center;">Patient <?php echo '('.$bill.'%)'?></th>
						<th style="width:13%;text-align:center;">Patient balance</th>
						<th style="width:13%;text-align:center;">Insurance</th>
						<th style="width:5%;" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
					
					<?php
					$Totaltourdesalle = 0;
					$TotaltourdesalleCCO = 0;
					$TotaltourdesallePayed = 0;
					$TotaltourdesallePayedCCO = 0;

					$TotaltopupPrice=0;
		            $TotalpatientPrice=0;
		            $TotalpatientBalance=0;
		            $TotaluapPrice=0;
					/*while($ligneHosp1=$resultHosp->fetch())
					{
						
							$billpercent=$ligneHosp1->insupercent_hosp;
							
							$idassu=$ligneHosp1->id_assuHosp;*/
							if (isset($_POST['idtourdesalle'])) {
								$idtourdesallePost = $_POST['idtourdesalle'];
							}
							if (isset($_GET['idtourdesalle'])) {
								$idtourdesallePost = $_GET['idtourdesalle'];
								//echo 'GET idresto = '.$_GET['idresto'];
							}
						
						$resultTourdesalle=$connexion->prepare('SELECT *FROM tour_de_salle ts WHERE ts.id_tour_de_salle=:idtourdesalle');
						$resultTourdesalle->execute(array(
						'idtourdesalle'=>$idtourdesallePost
						));
						$ligneTourdesalle = $resultTourdesalle->fetch(PDO::FETCH_OBJ);
						//$resultResto->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($comptPresta!=0)
						{
							$id_prestationTourdesalle = $ligneTourdesalle->id_prestation;
							$comptAssuConsuTourdesalle=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
							$comptAssuConsuTourdesalle->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCountTourdesalle = $comptAssuConsuResto->rowCount();
							
							for($i=1;$i<=$assuCountTourdesalle;$i++)
							{
								
								$getAssuConsuTourdesalle=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuConsuResto->execute(array(
								'idassu'=>$id_prestationTourdesalle
								));
								
								$getAssuConsuTourdesalle->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssuTourdesalle=$getAssuConsuTourdesalle->fetch())
								{
									$presta_assu='prestations_'.$ligneNomAssuTourdesalle->nomassurance;
									echo $presta_assu;
								}
							}
							$presta_assu = strtolower($presta_assu);

							$selectTourdesalle =  $connexion->prepare('SELECT * FROM '.$presta_assu.' WHERE id_prestation=:id_prestation');
							$selectTourdesalle->execute(array(
							'id_prestation'=>$id_prestationTourdesalle
							)) or die(print_r($connexion->errorInfo()));

							$ligneSelectTourdesalle = $selectTourdesalle->fetch(PDO::FETCH_OBJ);
							$comptSelectTourdesalle = $selectTourdesalle->rowCount(); 
							if ($comptSelectTourdesalle!=0) {
					?>
					<tr style="font-weight:bold;">
						<td style="text-align:center;">
							<?php
								echo $ligneSelectTourdesalle->nompresta;

							?>
						</td>
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactdebut'])) {
								echo date('d-M-Y', strtotime($_POST['datefactdebut']));
							}
							if (isset($_GET['datefacturedebut'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturedebut']));
							}
								
							?>
							</td>
							<td style="text-align:center;">
							<?php
							if (isset($_POST['datefactfin'])) {
								echo date('d-M-Y', strtotime($_POST['datefactfin']));
							}
							if (isset($_GET['datefacturefin'])) {
								echo date('d-M-Y', strtotime($_GET['datefacturefin']));
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php

							if (isset($_POST['datefactdebut']) AND isset($_POST['datefactfin'])) {
								$datefactdebut=strtotime($_POST['datefactdebut']);
								$datefactfin=strtotime($_POST['datefactfin']);
							}
							if (isset($_GET['datefacturedebut']) AND isset($_GET['datefacturefin'])) {
								$datefactdebut=strtotime($_GET['datefacturedebut']);
								$datefactfin=strtotime($_GET['datefacturefin']);
							}
							
							
							$datediff= abs($datefactfin - $datefactdebut);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
							
							if($nbrejrs==1)
							{
								echo $nbrejrs.' day';
							}else{
								echo $nbrejrs.' days';
							}
							?>
							</td>

                            <td style="text-align:center;" class="buttonBill">
                            	<?php echo $ligneSelectTourdesalle->prixprestaCCO;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;" class="buttonBill"><?php echo $ligneSelectTourdesalle->prixpresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;">
							<?php
							$balanceTourdesalleCCO=$ligneSelectTourdesalle->prixprestaCCO * $nbrejrs;
							
							echo $balanceTourdesalleCCO;
										
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
							<?php

							$balanceTourdesalle=$ligneSelectTourdesalle->prixpresta * $nbrejrs;

							echo $balanceTourdesalle;
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="text-align:center;">
                                <?php
                                $topupTourdesalle = $balanceTourdesalleCCO - $balanceTourdesalle;

                                echo $topupTourdesalle;
								

                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;"><?php echo $billpercent;?>%</td>

                            <?php
                            $patientPriceTourdesalle=($balanceTourdesalle * $billpercent)/100;
                            $insurancePriceTourdesalle= $balanceTourdesalle - $patientPriceTourdesalle;
                            ?>
							
							<td style="text-align:center;">
								<?php
								 	echo $patientPriceTourdesalle;
								 	
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

                            <td>
                                <?php
                                $patientBalanceTourdesalle = $topupTourdesalle + $patientPriceTourdesalle;
                                echo $patientBalanceTourdesalle.'';
                               
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;">
                            	<?php 
                            		echo $insurancePriceTourdesalle;
                            		
                            	?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
								
							<td class="buttonBill">
							<?php
							if($ligneTourdesalle->statusPa!=1)
							{
								echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
							}
							?>
							</td>
						</tr>
				<?php
						}
						
						
					//}
					
					$arrayConsult[$i][0]=$nameprestaHosp;
					$arrayConsult[$i][1]=$prixPrestaHosp;
					$arrayConsult[$i][2]=$patientPriceHosp;
					$arrayConsult[$i][3]=$insurancePriceHosp;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','B9');
		
				}
				
				?>
				</tbody>
			</table>

					<?php
					}

					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(9+$i).'', ''.$balanceHosp.'')
								->setCellValue('D'.(9+$i).'', ''.$patientPriceHosp.'')
								->setCellValue('E'.(9+$i).'', ''.$insurancePriceHosp.'');
			
			}
		

			
			if($comptMedSurge != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(14+$i+$x).'', 'Surgery')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="width:20%" colspan=13>Surgery</th>
					</tr>
					<tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
			<?php

            $TotaltopupPrice=0;
            $TotalpatientPrice=0;
            $TotalpatientBalance=0;
            $TotaluapPrice=0;

            $TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;

				while($ligneMedSurge=$resultMedSurge->fetch())
				{
				
					$billpercent=$ligneMedSurge->insupercentSurge;
					
					$idassu=$ligneMedSurge->id_assuSurge;
					
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
					

					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
					$resultPresta->execute(array(
						'prestaId'=>$ligneMedSurge->id_prestationSurge
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())
					{
					
					?>
					<tr style="text-align:center;" class="buttonBill">
						
						<td><?php echo $ligneMedSurge->datehosp;?></td>
						<td style="text-align:left;">
						<?php
						if($lignePresta->namepresta!='')
						{
							$nameprestaMedSurge=$lignePresta->namepresta;
							echo $lignePresta->namepresta;
						
						}else{
							$nameprestaMedSurge=$lignePresta->nompresta;
							echo $lignePresta->nompresta;
						}
						
						?>
						</td>
						
						<td>
						<?php
							$qteSurge=$ligneMedSurge->qteSurge;
							echo $qteSurge;
						?>
						</td>
						
						<td>
						<?php
							$prixPrestaCCO = $ligneMedSurge->prixprestationSurgeCCO;
							echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$prixPresta = $ligneMedSurge->prixprestationSurge;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balanceCCO=$prixPrestaCCO*$qteSurge;
							
							echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
						if($ligneMedSurge->id_factureMedSurge=="" OR $ligneMedSurge->id_factureMedSurge==0)
						{
							$TotalMedSurgeCCO = $TotalMedSurgeCCO + $balanceCCO;
						}else{
							$TotalMedSurgePayedCCO= $TotalMedSurgePayedCCO + $balanceCCO;
						}
						?>
						</td>

						<td>
						<?php
							$balance=$prixPresta*$qteSurge;

							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						if($ligneMedSurge->id_factureMedSurge=="" OR $ligneMedSurge->id_factureMedSurge==0)
						{
							$TotalMedSurge = $TotalMedSurge + $balance;
						}else{
							$TotalMedSurgePayed= $TotalMedSurgePayed + $balance;
						}
						?>
						</td>

                        <td>
                            <?php
                            $topupPrice = $balanceCCO - $balance;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>
						<td>
						<?php
							$patientPrice=($balance * $billpercent)/100;
						
						if($ligneMedSurge->id_factureMedSurge=="" OR $ligneMedSurge->id_factureMedSurge==0)
						{
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
						}else{
							$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
						}
						
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							$uapPrice= $balance - $patientPrice;
							
						if($ligneMedSurge->id_factureMedSurge=="" OR $ligneMedSurge->id_factureMedSurge==0)
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
						if($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
					<?php
					}
					
					if($ligneMedSurge->id_prestationSurge=="" AND $ligneMedSurge->prixautrePrestaS!=0)
					{
					?>
					<tr style="text-align:center;" class="buttonBill">
						<td><?php echo $ligneMedSurge->datesoins;?></td>
						<td style="text-align:left;">
						<?php
						$nameprestaMedSurge=$ligneMedSurge->autrePrestaM;
						
						echo $ligneMedSurge->autrePrestaM;
						?>
						</td>
						
						<td>
						<?php
							$qteSurge=$ligneMedSurge->qteSurge;
							echo $qteSurge;
						?>
						</td>
						
						<td>
						<?php
							$prixPrestaCCO = $ligneMedSurge->prixautrePrestaSCCO;
							echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$prixPresta = $ligneMedSurge->prixautrePrestaS;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balanceCCO=$prixPrestaCCO*$qteSurge;
                            $balance=$prixPresta*$qteSurge;
                            $topupPrice = $balanceCCO - $balance;
                            $patientPrice=($balance * $billpercent)/100;
                            $patientBalance = $topupPrice + $patientPrice;
                            $uapPrice= $balance - $patientPrice;


        if($ligneMedSurge->id_factureMedSurge=="" OR $ligneMedSurge->id_factureMedSurge==0)
        {
            $TotalMedSurgeCCO = $TotalMedSurgeCCO + $balanceCCO;
            $TotalMedSurge = $TotalMedSurge + $balance;
            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
            $TotaluapPrice= $TotaluapPrice + $uapPrice;
        }else{
            $TotalMedSurgePayedCCO= $TotalMedSurgePayedCCO + $balanceCCO;
            $TotalMedSurgePayed= $TotalMedSurgePayed + $balance;
            $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
            $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
            $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
            $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
        }

                        echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						?>
						</td>

                        <td>
                        <?php
                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

						<td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>

                        <td>
						<?php
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                        <?php
                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
						if($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
			<?php
					}
					
					
					$arrayMedSurge[$y][0]=$nameprestaMedSurge;
					$arrayMedSurge[$y][1]=$prixPresta;
					$arrayMedSurge[$y][2]=$patientPrice;
					$arrayMedSurge[$y][3]=$uapPrice;
					
					$y++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayMedSurge,'','B'.(15+$i+$x).'');
				}
			?>
					<tr style="text-align:center;">
						<td colspan=5 class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedSurgeCCO.'';
							
								$TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedSurgeCCO;
								$TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedSurgePayedCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedSurge.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedSurgePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
				$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
				
				$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td class="buttonBill"></td>
					</tr>
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedSurge.'')
							->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
			
			}



			if($comptMedInf != 0)
			{

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(14+$i+$x).'', 'Nursing Care')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');

			?>

			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="width:20%" colspan=13>Nursing Care</th>
					</tr>
					<tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
			<?php

            $TotaltopupPrice=0;
            $TotalpatientPrice=0;
            $TotalpatientBalance=0;
            $TotaluapPrice=0;

            $TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;

				while($ligneMedInf=$resultMedInf->fetch())
				{

					$billpercent=$ligneMedInf->insupercentInf;

					$idassu=$ligneMedInf->id_assuInf;

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


					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
					$resultPresta->execute(array(
						'prestaId'=>$ligneMedInf->id_prestation
					));

					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();

					if($lignePresta=$resultPresta->fetch())
					{

					?>
					<tr style="text-align:center;" class="buttonBill">

						<td><?php echo $ligneMedInf->datehosp;?></td>
						<td style="text-align:left;">
						<?php
						if($lignePresta->namepresta!='')
						{
							$nameprestaMedInf=$lignePresta->namepresta;
							echo $lignePresta->namepresta;

						}else{
							$nameprestaMedInf=$lignePresta->nompresta;
							echo $lignePresta->nompresta;
						}

						?>
						</td>

						<td>
						<?php
							$qteInf=$ligneMedInf->qteInf;
							echo $qteInf;
						?>
						</td>

						<td>
						<?php
							$prixPrestaCCO = $ligneMedInf->prixprestationCCO;
							echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$prixPresta = $ligneMedInf->prixprestation;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balanceCCO=$prixPrestaCCO*$qteInf;

							echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotalMedInfCCO = $TotalMedInfCCO + $balanceCCO;
						}else{
							$TotalMedInfPayedCCO= $TotalMedInfPayedCCO + $balanceCCO;
						}
						?>
						</td>

						<td>
						<?php
							$balance=$prixPresta*$qteInf;

							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotalMedInf = $TotalMedInf + $balance;
						}else{
							$TotalMedInfPayed= $TotalMedInfPayed + $balance;
						}
						?>
						</td>

                        <td>
                            <?php
                            $topupPrice = $balanceCCO - $balance;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedInf->insupercentInf;?>%</td>
						<td>
						<?php
							$patientPrice=($balance * $billpercent)/100;

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
						}else{
							$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
						}

							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							$uapPrice= $balance - $patientPrice;

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}

							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td>
						<?php
						if($ligneMedInf->id_factureMedInf!="" AND $ligneMedInf->id_factureMedInf!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
					<?php
					}

					if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM!=0)
					{
					?>
					<tr style="text-align:center;" class="buttonBill">
						<td><?php echo $ligneMedInf->datesoins;?></td>
						<td style="text-align:left;">
						<?php
						$nameprestaMedInf=$ligneMedInf->autrePrestaM;

						echo $ligneMedInf->autrePrestaM;
						?>
						</td>

						<td>
						<?php
							$qteInf=$ligneMedInf->qteInf;
							echo $qteInf;
						?>
						</td>

						<td>
						<?php
							$prixPrestaCCO = $ligneMedInf->prixautrePrestaMCCO;
							echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$prixPresta = $ligneMedInf->prixautrePrestaM;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balanceCCO=$prixPrestaCCO*$qteInf;

							echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotalMedInfCCO = $TotalMedInfCCO + $balanceCCO;
						}else{
							$TotalMedInfPayedCCO= $TotalMedInfPayedCCO + $balanceCCO;
						}
						?>
						</td>

						<td>
						<?php
							$balance=$prixPresta*$qteInf;

							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotalMedInf = $TotalMedInf + $balance;
						}else{
							$TotalMedInfPayed= $TotalMedInfPayed + $balance;
						}
						?>
						</td>

                        <td>
                            <?php
                            $topupPrice = $balanceCCO - $balance;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

						<td><?php echo $ligneMedInf->insupercentInf;?>%</td>
						<td>
						<?php
							$patientPrice=($balance * $billpercent)/100;

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
						}else{
							$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
						}

							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							$uapPrice= $balance - $patientPrice;

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}

							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td>
						<?php
						if($ligneMedInf->id_factureMedInf!="" AND $ligneMedInf->id_factureMedInf!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
			<?php
					}


					$arrayMedInf[$y][0]=$nameprestaMedInf;
					$arrayMedInf[$y][1]=$prixPresta;
					$arrayMedInf[$y][2]=$patientPrice;
					$arrayMedInf[$y][3]=$uapPrice;

					$y++;

					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayMedInf,'','B'.(15+$i+$x).'');
				}
			?>
					<tr style="text-align:center;">
						<td colspan=5 class="buttonBill"></td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedInfCCO.'';

								$TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedInfCCO;
								$TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedInfPayedCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedInf.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedInfPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';

				$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;

				$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;

								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td class="buttonBill"></td>
					</tr>
				</tbody>
			</table>
			<?php

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedInf.'')
							->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');

			}
			
			
			if($comptMedLabo != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(17+$i+$x+$y).'', 'Labs')
							->setCellValue('C'.(17+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(17+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(17+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="width:20%" colspan=13>Labs</th>
					</tr>
					<tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
			<?php

            $TotaltopupPrice=0;
            $TotalpatientPrice=0;
            $TotalpatientBalance=0;
            $TotaluapPrice=0;

            $TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;

				while($ligneMedLabo=$resultMedLabo->fetch())
				{
				
					$billpercent=$ligneMedLabo->insupercentLab;
					
					$idassu=$ligneMedLabo->id_assuLab;
					
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
				
					
					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedLabo->id_prestationExa
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())
					{
					?>
					<tr style="text-align:center;" class="buttonBill">
					
						<td><?php echo $ligneMedLabo->datehosp;?></td>
						<td style="text-align:left;">
						<?php
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedLabo=$lignePresta->namepresta;
								echo $lignePresta->namepresta;
							
							}else{
							
								$nameprestaMedLabo=$lignePresta->nompresta;
								echo $lignePresta->nompresta;
							}
						?>
						</td>

						<td>
						<?php
							$qteLab=$ligneMedLabo->qteLab;
							echo $qteLab;
						?>
						</td>
						
						<td>
						<?php
							$prixPrestaCCO = $ligneMedLabo->prixprestationExaCCO;
							echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$prixPresta = $ligneMedLabo->prixprestationExa;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
						$balanceCCO=$prixPrestaCCO*$qteLab;
						
						echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						
						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotalMedLaboCCO = $TotalMedLabo + $balanceCCO;
						}else{
							$TotalMedLaboPayedCCO = $TotalMedLaboPayedCCO + $balanceCCO;
						}
							?>
						</td>

						<td>
						<?php
						$balance=$prixPresta*$qteLab;

						echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotalMedLabo = $TotalMedLabo + $balance;
						}else{
							$TotalMedLaboPayed = $TotalMedLaboPayed + $balance;
						}
							?>
						</td>

                        <td>
                            <?php
                            $topupPrice = $balanceCCO - $balance;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
						
						<td>
							<?php
							$patientPrice=($balance * $billpercent)/100;
						
						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
						}else{
							$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
						}
						
							echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							$uapPrice= $balance - $patientPrice;
							
						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
						if($ligneMedLabo->id_factureMedLabo!="" AND $ligneMedLabo->id_factureMedLabo!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
					<?php
					}
					
					if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen!=0)
					{
					?>
					<tr style="text-align:center;" class="buttonBill">
						<td><?php echo $ligneMedLabo->datehosp;?></td>
						<td style="text-align:left;">
						<?php
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
							echo $ligneMedLabo->autreExamen;
							
						?>
						</td>
						
						<td>
						<?php
							$qteLab=$ligneMedLabo->qteLab;
							echo $qteLab;
						?>
						</td>
						
						<td>
						<?php
							$prixPrestaCCO = $ligneMedLabo->prixautreExamenCCO;
							echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$prixPresta = $ligneMedLabo->prixautreExamen;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
						$balanceCCO=$prixPrestaCCO*$qteLab;
						
						echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						
						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotalMedLaboCCO = $TotalMedLaboCCO + $balanceCCO;
						}else{
							$TotalMedLaboPayedCCO = $TotalMedLaboPayedCCO + $balanceCCO;
						}
							?>
						</td>

                        <td>
						<?php
						$balance=$prixPresta*$qteLab;

						echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotalMedLabo = $TotalMedLabo + $balance;
						}else{
							$TotalMedLaboPayed = $TotalMedLaboPayed + $balance;
						}
							?>
						</td>

                        <td>
                            <?php
                            $topupPrice = $balanceCCO - $balance;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
					
						<td>
						<?php
							$patientPrice=($balance * $billpercent)/100;
							
						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
						}else{
							$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
						}
						
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							$uapPrice= $ligneMedLabo->prixautreExamen - $patientPrice;
							
						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
						if($ligneMedLabo->id_factureMedLabo!="" AND $ligneMedLabo->id_factureMedLabo!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
		<?php
					}
					
					$arrayMedLabo[$z][0]=$nameprestaMedLabo;
					$arrayMedLabo[$z][1]=$prixPresta;
					$arrayMedLabo[$z][2]=$patientPrice;
					$arrayMedLabo[$z][3]=$uapPrice;
					
					$z++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayMedLabo,'','B'.(18+$i+$x+$y).'');
				}
			?>
					<tr style="text-align:center;">
						<td colspan=5 class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedLaboCCO.'';
								
								$TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedLaboCCO;
								$TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedLaboPayedCCO;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedLabo.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedLaboPayed;

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotaluapPrice.'';
	
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td class="buttonBill"></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedLabo.'')
							->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			
			if($comptMedRadio != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(21+$i+$x+$y).'', 'Radio')
							->setCellValue('C'.(21+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(21+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(21+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="width:20%" colspan=13>Radiologie</th>
					</tr>
					<tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
			<?php

            $TotaltopupPrice=0;
            $TotalpatientPrice=0;
            $TotalpatientBalance=0;
            $TotaluapPrice=0;

            $TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;

				while($ligneMedRadio=$resultMedRadio->fetch())
				{
				
					$billpercent=$ligneMedRadio->insupercentRad;
					
					$idassu=$ligneMedRadio->id_assuRad;
					
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
					
					
					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedRadio->id_prestationRadio
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())
					{
					?>
					<tr style="text-align:center;" class="buttonBill">
					
						<td><?php echo $ligneMedRadio->datehosp;?></td>
						<td style="text-align:left;">
						<?php
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedRadio=$lignePresta->namepresta;
								echo $lignePresta->namepresta;
							
							}else{
							
								$nameprestaMedRadio=$lignePresta->nompresta;
								echo $lignePresta->nompresta;
							}
						?>
						</td>
						
						<td>
						<?php
							$qteRad=$ligneMedRadio->qteRad;
							echo $qteRad;
						?>
						</td>
						
						<td>
						<?php
							$prixPrestaCCO = $ligneMedRadio->prixprestationRadioCCO;
							echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$prixPresta = $ligneMedRadio->prixprestationRadio;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balanceCCO=$prixPrestaCCO*$qteRad;
							
							echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
							
						if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
						{
							$TotalMedRadioCCO = $TotalMedRadioCCO + $balanceCCO;
						}else{
							$TotalMedRadioPayedCCO = $TotalMedRadioPayedCCO + $balanceCCO;
						}
						?>

						<td>
						<?php
							$balance=$prixPresta*$qteRad;

							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';


						if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
						{
							$TotalMedRadio = $TotalMedRadio + $balance;
						}else{
							$TotalMedRadioPayed = $TotalMedRadioPayed + $balance;
						}
						?>

                        <td>
                            <?php
                            $topupPrice = $balanceCCO - $balance;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
						
						<td>
						<?php
							$patientPrice=($balance * $billpercent)/100;
						
						if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
						{
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
						}else{
							$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
						}
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							$uapPrice= $balance - $patientPrice;
							
						if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
						if($ligneMedRadio->id_factureMedRadio!="" AND $ligneMedRadio->id_factureMedRadio!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
					<?php
					}
					
					if($ligneMedRadio->id_prestationRadio=="" AND $ligneMedRadio->prixautreRadio!=0)
					{
					?>
					<tr style="text-align:center;" class="buttonBill">
						<td><?php echo $ligneMedRadio->datehosp;?></td>
						<td style="text-align:left;">
						<?php
						$nameprestaMedRadio=$ligneMedRadio->autreRadio;
						
						echo $ligneMedRadio->autreRadio;
						?>
						</td>

						<td>
						<?php
							$qteRad=$ligneMedRadio->qteRad;
							echo $qteRad;
						?>
						</td>
						
						<td>
						<?php
							$prixPresta = $ligneMedRadio->prixautreRadio;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balance=$prixPresta*$qteRad;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
							
						if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
						{
							$TotalMedRadio = $TotalMedRadio + $balance;
						}else{
							$TotalMedRadioPayed = $TotalMedRadioPayed + $balance;
						}
						?>

                        <td>
                            <?php
                            $topupPrice = $balanceCCO - $balance;
                            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                            echo $topupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
					
						<td>
						<?php
							$patientPrice=($balance * $billpercent)/100;
						
						if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
						{
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
						}else{
							$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
						}
						
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td>
                            <?php
                            $patientBalance = $topupPrice + $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
						<?php
							$uapPrice= $balance - $patientPrice;
							
						if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
						if($ligneMedRadio->id_factureMedRadio!="" AND $ligneMedRadio->id_factureMedRadio!=0)
						{
							echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
						}
						?>
						</td>
					</tr>
				<?php
					}
					
					$arrayMedRadio[$z][0]=$nameprestaMedRadio;
					$arrayMedRadio[$z][1]=$prixPresta;
					$arrayMedRadio[$z][2]=$patientPrice;
					$arrayMedRadio[$z][3]=$uapPrice;
					
					$z++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayMedRadio,'','B'.(18+$i+$x+$y).'');
				}
				?>
					<tr style="text-align:center;">
						<td colspan=5 class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedRadioCCO.'';
							
								$TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedRadioCCO;
								
								$TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedRadioPayedCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedRadio.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;

								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedRadioPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td class="buttonBill"></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedRadio.'')
							->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}



            if($comptMedKine != 0)
            {

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.(14+$i+$x).'', 'Physiotherapy')
                    ->setCellValue('C'.(14+$i+$x).'', 'Price')
                    ->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
                    ->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');

                ?>

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th style="width:20%" colspan=13>Physiotherapy</th>
                    </tr>
                    <tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

                    $TotalpatientPricePayed=0;
                    $TotaluapPricePayed=0;

                    while($ligneMedKine=$resultMedKine->fetch())
                    {

                        $billpercent=$ligneMedKine->insupercentKine;

                        $idassu=$ligneMedKine->id_assuKine;

                        $comptAssuKine=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

                        $comptAssuKine->setFetchMode(PDO::FETCH_OBJ);

                        $assuCount = $comptAssuKine->rowCount();

                        for($i=1;$i<=$assuCount;$i++)
                        {

                            $getAssuKine=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
                            $getAssuKine->execute(array(
                                'idassu'=>$idassu
                            ));

                            $getAssuKine->setFetchMode(PDO::FETCH_OBJ);

                            if($ligneNomAssu=$getAssuKine->fetch())
                            {
                                $presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
                            }
                        }


                        $resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
                        $resultPresta->execute(array(
                            'prestaId'=>$ligneMedKine->id_prestationKine
                        ));

                        $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                        $comptPresta=$resultPresta->rowCount();

                        if($lignePresta=$resultPresta->fetch())
                        {

                            ?>
                            <tr style="text-align:center;" class="buttonBill">

                                <td><?php echo $ligneMedKine->datehosp;?></td>
                                <td style="text-align:left;">
                                    <?php
                                    if($lignePresta->namepresta!='')
                                    {
                                        $nameprestaMedKine=$lignePresta->namepresta;
                                        echo $lignePresta->namepresta;

                                    }else{
                                        $nameprestaMedKine=$lignePresta->nompresta;
                                        echo $lignePresta->nompresta;
                                    }

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $qteKine=$ligneMedKine->qteKine;
                                    echo $qteKine;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPrestaCCO = $ligneMedKine->prixprestationKineCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedKine->prixprestationKine;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteKine;
                                    $balance=$prixPresta*$qteKine;
                                    $topupPrice = $balanceCCO - $balance;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedKine->id_factureMedKine=="" OR $ligneMedKine->id_factureMedKine==0)
                                    {
                                        $TotalMedKineCCO = $TotalMedKineCCO + $balanceCCO;
                                        $TotalMedKine = $TotalMedKine + $balance;
                                        $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedKinePayedCCO= $TotalMedKinePayedCCO + $balanceCCO;
                                        $TotalMedKinePayed= $TotalMedKinePayed + $balance;
                                        $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedKine->insupercentKine;?>%</td>

                                <td>
                                    <?php
                                    echo $patientPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $uapPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    if($ligneMedKine->id_factureMedKine!="" AND $ligneMedKine->id_factureMedKine!=0)
                                    {
                                        echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }

                        if($ligneMedKine->id_prestationKine=="" AND $ligneMedKine->prixautrePrestaK!=0)
                        {
                            ?>
                            <tr style="text-align:center;" class="buttonBill">
                                <td><?php echo $ligneMedKine->datesoins;?></td>
                                <td style="text-align:left;">
                                    <?php
                                    $nameprestaMedKine=$ligneMedKine->autrePrestaM;

                                    echo $ligneMedKine->autrePrestaM;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $qteKine=$ligneMedKine->qteKine;
                                    echo $qteKine;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPrestaCCO = $ligneMedKine->prixautrePrestaKCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedKine->prixautrePrestaK;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteKine;
                                    $balance=$prixPresta*$qteKine;
                                    $topupPrice = $balanceCCO - $balance;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


        if($ligneMedKine->id_factureMedKine=="" OR $ligneMedKine->id_factureMedKine==0)
        {
            $TotalMedKineCCO = $TotalMedKineCCO + $balanceCCO;
            $TotalMedKine = $TotalMedKine + $balance;
            $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
            $TotaluapPrice= $TotaluapPrice + $uapPrice;
        }else{
            $TotalMedKinePayedCCO= $TotalMedKinePayedCCO + $balanceCCO;
            $TotalMedKinePayed= $TotalMedKinePayed + $balance;
            $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
            $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
            $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
            $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
        }

                                    echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedKine->insupercentKine;?>%</td>

                                <td>
                                    <?php
                                    echo $patientPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $uapPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    if($ligneMedKine->id_factureMedKine!="" AND $ligneMedKine->id_factureMedKine!=0)
                                    {
                                        echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }


                        $arrayMedKine[$y][0]=$nameprestaMedKine;
                        $arrayMedKine[$y][1]=$prixPresta;
                        $arrayMedKine[$y][2]=$patientPrice;
                        $arrayMedKine[$y][3]=$uapPrice;

                        $y++;

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->fromArray($arrayMedKine,'','B'.(15+$i+$x).'');
                    }
                    ?>
                    <tr style="text-align:center;">
                        <td colspan=5 class="buttonBill"></td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedKineCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedKineCCO;
                            $TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedKinePayedCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedKine.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
                            $TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedKinePayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;

                            $TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;

                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;

                            $TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;

                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td class="buttonBill"></td>
                    </tr>
                    </tbody>
                </table>
                <?php

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedKine.'')
                    ->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
                    ->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');

            }


            if($comptMedConsom != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(20+$i+$x+$y).'', 'Consommables')
							->setCellValue('C'.(20+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(20+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(20+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="width:20%" colspan=13>Consommables</th>
					</tr>
					<tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
			<?php

            $TotaltopupPrice=0;
            $TotalpatientPrice=0;
            $TotalpatientBalance=0;
            $TotaluapPrice=0;

            $TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;

					while($ligneMedConsom=$resultMedConsom->fetch())
					{
					
						$billpercent=$ligneMedConsom->insupercentConsom;
						
						$idassu=$ligneMedConsom->id_assuConsom;
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
					

						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsom->id_prestationConsom
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
					
						if($comptPresta==0)
						{
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedConsom->id_prestationConsom
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
						}
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
						?>
						<tr style="text-align:center;" class="buttonBill">
							<td><?php echo $ligneMedConsom->datehosp;?></td>
							<td style="text-align:left;">
							<?php
								if($lignePresta->namepresta!='')
								{
									$nameprestaMedConsom=$lignePresta->namepresta;
									echo $lignePresta->namepresta;
								
								}else{
								
									$nameprestaMedConsom=$lignePresta->nompresta;
									echo $lignePresta->nompresta;
							
								}
							?>
							
							</td>

							<td>
							<?php
								$qteConsom=$ligneMedConsom->qteConsom;
								
								echo $qteConsom;
							?>
							</td>
							
							<td>
							<?php
								$prixPrestaCCO = $ligneMedConsom->prixprestationConsomCCO;
								echo $prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>

							<td>
							<?php
								$prixPresta = $ligneMedConsom->prixprestationConsom;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>

							<td>
								<?php
								$balanceCCO=$prixPrestaCCO*$qteConsom;
								$balance=$prixPresta*$qteConsom;
								$topupPrice = $balanceCCO - $balance;
								$patientPrice=($balance * $billpercent)/100;
								$patientBalance = $topupPrice + $patientPrice;
								$uapPrice= $balance - $patientPrice;


	if($ligneMedConsom->id_factureMedConsom=="" OR $ligneMedConsom->id_factureMedConsom==0)
	{
		$TotalMedConsomCCO = $TotalMedConsomCCO + $balanceCCO;
		$TotalMedConsom = $TotalMedConsom + $balance;
		$TotaltopupPrice=$TotaltopupPrice + $topupPrice;
		$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
		$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
		$TotaluapPrice= $TotaluapPrice + $uapPrice;
	}else{
		$TotalMedConsomPayedCCO= $TotalMedConsomPayedCCO + $balanceCCO;
		$TotalMedConsomPayed= $TotalMedConsomPayed + $balance;
		$TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
		$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
		$TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
		$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
	}

								echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
								?>
							</td>

							<td>
								<?php
								echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

								?>
							</td>

							<td>
								<?php
								echo $topupPrice;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>

							<td>
								<?php
								echo $patientPrice.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td>
								<?php
								echo $patientBalance.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td>
								<?php
								echo $uapPrice.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td>
								<?php
								if($ligneMedConsom->id_factureMedConsom!="" AND $ligneMedConsom->id_factureMedConsom!=0)
								{
									echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
								}
								?>
							</td>
						</tr>
						<?php
						}
						
						if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->prixautreConsom!=0)
						{
						?>
						<tr style="text-align:center;" class="buttonBill">
						
							<td><?php echo $ligneMedConsom->datehosp;?></td>
							<td style="text-align:left;">
							<?php
								$nameprestaMedConsom=$ligneMedConsom->autreConsom;
								echo $nameprestaMedConsom;
							?>
							</td>
						
							<td>
							<?php
								$qteConsom=$ligneMedConsom->qteConsom;
								echo $qteConsom;
							?>
							</td>
							
							<td>
							<?php
								$prixPresta = $ligneMedConsom->prixautreConsom;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteConsom;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedConsom->id_factureMedConsom=="" OR $ligneMedConsom->id_factureMedConsom==0)
							{
								$TotalMedConsom=$TotalMedConsom + $balance;
							}else{
								$TotalMedConsomPayed=$TotalMedConsomPayed + $balance;
							}
							?>
							</td>

                            <td>
                                <?php
                                $topupPrice = $balanceCCO - $balance;
                                $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                echo $topupPrice;
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
						
							<td>
							<?php
								$patientPrice=($balance * $billpercent)/100;
								
							if($ligneMedConsom->id_factureMedConsom=="" OR $ligneMedConsom->id_factureMedConsom==0)
							{
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							}else{
								$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
							}
							
								echo $patientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
							<?php
								$uapPrice= $balance - $patientPrice;
							
							if($ligneMedConsom->id_factureMedConsom=="" OR $ligneMedConsom->id_factureMedConsom==0)
							{
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
							
								echo $uapPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
							if($ligneMedConsom->id_factureMedConsom!="" AND $ligneMedConsom->id_factureMedConsom!=0)
							{
								echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
							}
							?>
							</td>
						</tr>
			<?php
						}
						
						$arrayMedConsom[$z][0]=$nameprestaMedConsom;
						$arrayMedConsom[$z][1]=$prixPresta;
						$arrayMedConsom[$z][2]=$patientPrice;
						$arrayMedConsom[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedConsom,'','B'.(21+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td colspan=5 class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedConsomCCO.'';
								
								$TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedConsomCCO;
								$TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedConsomPayedCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedConsom.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsomPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
			// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td class="buttonBill"></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(21+$i+$x+$y+$z).'', ''.$TotalMedConsom.'')
							->setCellValue('D'.(21+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(21+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			
			if($comptMedMedoc != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(23+$i+$x+$y).'', 'Medocs')
							->setCellValue('C'.(23+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(23+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(23+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="width:20%" colspan=13>Medicaments</th>
					</tr>
					<tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
					</tr>
				</thead>

				<tbody>
			<?php

            $TotaltopupPrice=0;
            $TotalpatientPrice=0;
            $TotalpatientBalance=0;
            $TotaluapPrice=0;

            $TotaltopupPricePayed=0;
            $TotalpatientPricePayed=0;
            $TotalpatientBalancePayed=0;
			$TotaluapPricePayed=0;

					while($ligneMedMedoc=$resultMedMedoc->fetch())
					{
					
						$billpercent=$ligneMedMedoc->insupercentMedoc;
						
							$idassu=$ligneMedMedoc->id_assuMedoc;
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
						

					
					$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedMedoc->id_prestationMedoc
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
				
					if($comptPresta==0)
					{
						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedMedoc->id_prestationMedoc
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
					}
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
					?>
						<tr style="text-align:center;" class="buttonBill">
							
							<td><?php echo $ligneMedMedoc->datehosp;?></td>
							<td style="text-align:left;">
						<?php
								if($lignePresta->namepresta!='')
								{
									$nameprestaMedMedoc=$lignePresta->namepresta;
									echo $lignePresta->namepresta;
								
								}else{
								
									$nameprestaMedMedoc=$lignePresta->nompresta;
									echo $lignePresta->nompresta;
							
								}
						?>
							
							</td>
							
							<td>
							<?php
								$qteMedoc=$ligneMedMedoc->qteMedoc;
								
								echo $qteMedoc;
							?>
							</td>
							
							<td>
							<?php
								$prixPrestaCCO = $ligneMedMedoc->prixprestationMedocCCO;
								echo $prixPrestaCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>

							<td>
							<?php
								$prixPresta = $ligneMedMedoc->prixprestationMedoc;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>

							<td>
							<?php
								$balanceCCO=$prixPrestaCCO*$qteMedoc;
								
								echo $balanceCCO.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
							{
								$TotalMedMedocCCO=$TotalMedMedocCCO + $balanceCCO;
							}else{
								$TotalMedMedocPayedCCO=$TotalMedMedocPayedCCO + $balanceCCO;
							}
							?>
							</td>

							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;

								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
							{
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							}else{
								$TotalMedMedocPayed=$TotalMedMedocPayed + $balance;
							}
							?>
							</td>

                            <td>
                                <?php
                                $topupPrice = $balanceCCO - $balance;
                                $TotaltopupPrice=$TotaltopupPrice + $topupPrice;

                                echo $topupPrice;
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
							
							<td>
							<?php
								$patientPrice=($balance * $billpercent)/100;
							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
							{
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							}else{
								$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
							}
							
								echo $patientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

                            <td>
                                <?php
                                $patientBalance = $topupPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
							<?php
								$uapPrice= $balance - $patientPrice;
							
							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
							{
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
							
								echo $uapPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
							if($ligneMedMedoc->id_factureMedMedoc!="" AND $ligneMedMedoc->id_factureMedMedoc!=0)
							{
								echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
							}
							?>
							</td>
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
					{
					?>
						<tr style="text-align:center;" class="buttonBill">
						
							<td><?php echo $ligneMedMedoc->datehosp;?></td>
							<td style="text-align:left;">
							<?php
								$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
								echo $nameprestaMedMedoc;
							?>
							</td>
						
							<td>
							<?php
								$qteMedoc=$ligneMedMedoc->qteMedoc;
								echo $qteMedoc;
							?>
							</td>
							
							<td>
							<?php
								$prixPresta = $ligneMedMedoc->prixautreMedoc;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
                                $topupPrice = $balanceCCO - $balance;
                                $patientPrice=($balance * $billpercent)/100;
                                $patientBalance = $topupPrice + $patientPrice;



                            echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
							{
								$TotalMedMedoc=$TotalMedMedoc + $balance;
                                $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
							}else{
								$TotalMedMedocPayed=$TotalMedMedocPayed + $balance;
                                $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
                                $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
							}
							?>
							</td>

                            <td>
                                <?php
                                echo $topupPrice;
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
						
							<td>
							<?php
								echo $patientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

                            <td>
                                <?php
                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
							<?php
								$uapPrice= $balance - $patientPrice;
							
							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
							{
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
							
								echo $uapPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
							if($ligneMedMedoc->id_factureMedMedoc!="" AND $ligneMedMedoc->id_factureMedMedoc!=0)
							{
								echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
							}
							?>
							</td>
						</tr>
			<?php
					}
						
						$arrayMedMedoc[$z][0]=$nameprestaMedMedoc;
						$arrayMedMedoc[$z][1]=$prixPresta;
						$arrayMedMedoc[$z][2]=$patientPrice;
						$arrayMedMedoc[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedMedoc,'','B'.(21+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td colspan=5 class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedMedocCCO.'';
								
								$TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedMedocCCO;
								$TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedMedocPayedCCO;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedMedoc.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedMedocPayed;

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            $TotalGnlTopupPricePayed=$TotalGnlTopupPricePayed + $TotaltopupPricePayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

						<td class="buttonBill"></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td class="buttonBill"></td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(24+$i+$x+$y+$z).'', ''.$TotalMedMedoc.'')
							->setCellValue('D'.(24+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(24+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}


            if($comptMedOrtho != 0)
            {

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.(14+$i+$x).'', 'P & O')
                    ->setCellValue('C'.(14+$i+$x).'', 'Price')
                    ->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
                    ->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');

                ?>

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th style="width:20%" colspan=13>P & O</th>
                    </tr>
                    <tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

                    $TotalpatientPricePayed=0;
                    $TotaluapPricePayed=0;

                    while($ligneMedOrtho=$resultMedOrtho->fetch())
                    {

                        $billpercent=$ligneMedOrtho->insupercentOrtho;

                        $idassu=$ligneMedOrtho->id_assuOrtho;

                        $comptAssuOrtho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

                        $comptAssuOrtho->setFetchMode(PDO::FETCH_OBJ);

                        $assuCount = $comptAssuOrtho->rowCount();

                        for($i=1;$i<=$assuCount;$i++)
                        {

                            $getAssuOrtho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
                            $getAssuOrtho->execute(array(
                                'idassu'=>$idassu
                            ));

                            $getAssuOrtho->setFetchMode(PDO::FETCH_OBJ);

                            if($ligneNomAssu=$getAssuOrtho->fetch())
                            {
                                $presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
                            }
                        }


                        $resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
                        $resultPresta->execute(array(
                            'prestaId'=>$ligneMedOrtho->id_prestationOrtho
                        ));

                        $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                        $comptPresta=$resultPresta->rowCount();

                        if($lignePresta=$resultPresta->fetch())
                        {

                            ?>
                            <tr style="text-align:center;" class="buttonBill">

                                <td><?php echo $ligneMedOrtho->datehosp;?></td>
                                <td style="text-align:left;">
                                    <?php
                                    if($lignePresta->namepresta!='')
                                    {
                                        $nameprestaMedOrtho=$lignePresta->namepresta;
                                        echo $lignePresta->namepresta;

                                    }else{
                                        $nameprestaMedOrtho=$lignePresta->nompresta;
                                        echo $lignePresta->nompresta;
                                    }

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $qteOrtho=$ligneMedOrtho->qteOrtho;
                                    echo $qteOrtho;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPrestaCCO = $ligneMedOrtho->prixprestationOrthoCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedOrtho->prixprestationOrtho;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteOrtho;
                                    $balance=$prixPresta*$qteOrtho;
                                    $topupPrice = $balanceCCO - $balance;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedOrtho->id_factureMedOrtho=="" OR $ligneMedOrtho->id_factureMedOrtho==0)
                                    {
                                        $TotalMedOrthoCCO = $TotalMedOrthoCCO + $balanceCCO;
                                        $TotalMedOrtho = $TotalMedOrtho + $balance;
                                        $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedOrthoPayedCCO= $TotalMedOrthoPayedCCO + $balanceCCO;
                                        $TotalMedOrthoPayed= $TotalMedOrthoPayed + $balance;
                                        $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

                                <td>
                                    <?php
                                    echo $patientPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $uapPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    if($ligneMedOrtho->id_factureMedOrtho!="" AND $ligneMedOrtho->id_factureMedOrtho!=0)
                                    {
                                        echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }

                        if($ligneMedOrtho->id_prestationOrtho=="" AND $ligneMedOrtho->prixautrePrestaO!=0)
                        {
                            ?>
                            <tr style="text-align:center;" class="buttonBill">
                                <td><?php echo $ligneMedOrtho->datesoins;?></td>
                                <td style="text-align:left;">
                                    <?php
                                    $nameprestaMedOrtho=$ligneMedOrtho->autrePrestaM;

                                    echo $ligneMedOrtho->autrePrestaM;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $qteOrtho=$ligneMedOrtho->qteOrtho;
                                    echo $qteOrtho;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPrestaCCO = $ligneMedOrtho->prixautrePrestaOCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedOrtho->prixautrePrestaO;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteOrtho;
                                    $balance=$prixPresta*$qteOrtho;
                                    $topupPrice = $balanceCCO - $balance;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedOrtho->id_factureMedOrtho=="" OR $ligneMedOrtho->id_factureMedOrtho==0)
                                    {
                                        $TotalMedOrthoCCO = $TotalMedOrthoCCO + $balanceCCO;
                                        $TotalMedOrtho = $TotalMedOrtho + $balance;
                                        $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedOrthoPayedCCO= $TotalMedOrthoPayedCCO + $balanceCCO;
                                        $TotalMedOrthoPayed= $TotalMedOrthoPayed + $balance;
                                        $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

                                <td>
                                    <?php
                                    echo $patientPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $uapPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    if($ligneMedOrtho->id_factureMedOrtho!="" AND $ligneMedOrtho->id_factureMedOrtho!=0)
                                    {
                                        echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }


                        $arrayMedOrtho[$y][0]=$nameprestaMedOrtho;
                        $arrayMedOrtho[$y][1]=$prixPresta;
                        $arrayMedOrtho[$y][2]=$patientPrice;
                        $arrayMedOrtho[$y][3]=$uapPrice;

                        $y++;

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->fromArray($arrayMedOrtho,'','B'.(15+$i+$x).'');
                    }
                    ?>
                    <tr style="text-align:center;">
                        <td colspan=5 class="buttonBill"></td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedOrthoCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedOrthoCCO;
                            $TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedOrthoPayedCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedOrtho.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
                            $TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedOrthoPayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;

                            $TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;

                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;

                            $TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;

                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td class="buttonBill"></td>
                    </tr>
                    </tbody>
                </table>
                <?php

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedOrtho.'')
                    ->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
                    ->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');

            }


            if($comptMedConsult != 0)
            {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.(11+$i).'', 'Services')
                    ->setCellValue('C'.(11+$i).'', 'Price')
                    ->setCellValue('D'.(11+$i).'', 'Patient Price')
                    ->setCellValue('E'.(11+$i).'', 'Insurance Price');

                ?>

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th style="width:20%" colspan=13>Services</th>
                    </tr>
                    <tr>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" class="buttonBill">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U CCO</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance ra</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)">Top Up</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" class="buttonBill">Percent</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
                        <th style="width:5%;background:rgba(0, 0, 0, 0.05)" class="buttonBill"></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;


                    $TotaltopupPricePayed=0;
                    $TotalpatientPricePayed=0;
                    $TotalpatientBalancePayed=0;
                    $TotaluapPricePayed=0;


                    while($ligneMedConsult=$resultMedConsult->fetch())
                    {

                        $billpercent=$ligneMedConsult->insupercentServ;

                        $idassu=$ligneMedConsult->id_assuServ;
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


                        $resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');

                        $resultPresta->execute(array(
                            'prestaId'=>$ligneMedConsult->id_prestationConsu
                        ));

                        $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                        $comptPresta=$resultPresta->rowCount();

                        if($lignePresta=$resultPresta->fetch())
                        {
                            ?>
                            <tr style="text-align:center;" class="buttonBill">

                                <td><?php echo $ligneMedConsult->datehosp;?></td>
                                <td style="text-align:left;">
                                    <?php
                                    if($lignePresta->namepresta!='')
                                    {
                                        $nameprestaMedConsult=$lignePresta->namepresta;
                                        echo $lignePresta->namepresta;

                                    }else{

                                        $nameprestaMedConsult=$lignePresta->nompresta;
                                        echo $lignePresta->nompresta;
                                    }

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $qteConsu=$ligneMedConsult->qteConsu;
                                    echo $qteConsu;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPrestaCCO = $ligneMedConsult->prixprestationConsuCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedConsult->prixprestationConsu;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteConsu;
                                    $balance=$prixPresta*$qteConsu;
                                    $topupPrice = $balanceCCO - $balance;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedConsult->id_factureMedConsu=="" OR $ligneMedConsult->id_factureMedConsu==0)
                                    {
                                        $TotalMedConsultCCO = $TotalMedConsultCCO + $balanceCCO;
                                        $TotalMedConsult = $TotalMedConsult + $balance;
                                        $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedConsultPayedCCO= $TotalMedConsultPayedCCO + $balanceCCO;
                                        $TotalMedConsultPayed= $TotalMedConsultPayed + $balance;
                                        $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

                                <td>
                                    <?php
                                    echo $patientPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $uapPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>
                            </tr>
                            <?php
                        }

                        if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu!=0)
                        {
                            ?>
                            <tr style="text-align:center;" class="buttonBill">

                                <td><?php echo $ligneMedConsult->datehosp;?></td>
                                <td style="text-align:left;">
                                    <?php

                                    $nameprestaMedConsult=$ligneMedConsult->autreConsu;
                                    echo $ligneMedConsult->autreConsu;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $qteConsu=$ligneMedConsult->qteConsu;
                                    echo $qteConsu;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPrestaCCO = $ligneMedConsult->prixautreConsuCCO;
                                    echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedConsult->prixautreConsu;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $balanceCCO=$prixPrestaCCO*$qteConsu;
                                    $balance=$prixPresta*$qteConsu;
                                    $topupPrice = $balanceCCO - $balance;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedConsult->id_factureMedConsu=="" OR $ligneMedConsult->id_factureMedConsu==0)
                                    {
                                        $TotalMedConsultCCO = $TotalMedConsultCCO + $balanceCCO;
                                        $TotalMedConsult = $TotalMedConsult + $balance;
                                        $TotaltopupPrice=$TotaltopupPrice + $topupPrice;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedConsultPayedCCO= $TotalMedConsultPayedCCO + $balanceCCO;
                                        $TotalMedConsultPayed= $TotalMedConsultPayed + $balance;
                                        $TotaltopupPricePayed=$TotaltopupPricePayed + $topupPrice;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    echo $balanceCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $topupPrice;
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

                                <td>
                                    <?php
                                    echo $patientPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $patientBalance.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    echo $uapPrice.'';
                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    if($ligneMedConsult->id_factureMedConsu!="" OR $ligneMedConsult->id_factureMedConsu!=0)
                                    {
                                        echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }

                        $arrayMedConsult[$x][0]=$nameprestaMedConsult;
                        $arrayMedConsult[$x][1]=$prixPresta;
                        $arrayMedConsult[$x][2]=$patientPrice;
                        $arrayMedConsult[$x][3]=$uapPrice;

                        $x++;

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->fromArray($arrayMedConsult,'','B'.(12+$i).'');

                    }
                    ?>
                    <tr style="text-align:center;">
                        <td colspan=5 class="buttonBill"></td>
                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedConsultCCO.'';

                            $TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalMedConsultCCO;
                            $TotalGnlPricePayedCCO=$TotalGnlPricePayedCCO + $TotalMedConsultPayedCCO;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedConsult.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
                            $TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsultPayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotaltopupPrice;

                            $TotalGnlTopupPrice=$TotalGnlTopupPrice + $TotaltopupPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td class="buttonBill"></td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;

                            $TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;

                            $TotalGnlPatientBalancePayed=$TotalGnlPatientBalancePayed + $TotalpatientBalancePayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotaluapPrice.'';

                            $TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;

                            $TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td class="buttonBill"></td>
                    </tr>
                    </tbody>
                </table>
                <?php

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.(12+$i+$x).'', ''.$TotalMedConsult.'')
                    ->setCellValue('D'.(12+$i+$x).'', ''.$TotalpatientPrice.'')
                    ->setCellValue('E'.(12+$i+$x).'', ''.$TotaluapPrice.'');

            }

        }

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
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
	alert("Your Browser does not support   XMLHTTPRequest object...");
	return null;
}

return xhr;
}

function CheckOrders(order)
{
	if( hour =='heures'){
	document.getElementById('tableheure').style.display='inline';
	}
	
}


function ShowFinish(finish)
{
	if( finish =='finishbtn'){
		document.getElementById('finishbtn').style.display='inline';
	}
	
}

</script>

	</div>

	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
		<?php
            $TotalFinalPriceCCO=0;
			$TotalFinalPrice=0;
			$TotalFinalTopupPrice=0;
			$TotalFinalPatientPrice=0;
			$TotalFinalPatientBalance=0;
			$TotalFinalInsurancePrice=0;

            $TotalFinalPricePayedCCO=0;
			$TotalFinalPricePayed=0;
			$TotalFinalTopupPricePayed=0;
			$TotalFinalPatientPricePayed=0;
			$TotalFinalPatientBalancePayed=0;
			$TotalFinalInsurancePricePayed=0;

		?>
		<table class="printPreview" cellspacing="0" style="margin:auto;">
			<thead>
				<tr>
					<th style="width:auto;"></th>
					<th style="width:auto;">Total CCO</th>
					<th style="width:15%;">Total <?php echo $nomassurance;?></th>
					<th style="width:auto;">Total Top Up</th>
					<th style="width:15%;">Total Patient <?php echo '('.$billpercent.'%)';?></th>
					<th style="width:15%;">Patient Balance</th>
					<th style="width:10%;">Insurance</th>
				</tr>
			</thead>

			<tbody>
			
				<?php

				if (isset($_GET['idresto']) || isset($_POST['idresto']) || isset($_GET['idtourdesalle']) || isset($_POST['idtourdesalle'])) {
					$TotalFinalPriceCCO=$TotalGnlPriceCCO + $balanceHospCCO + $balanceRestoCCO + $balanceTourdesalleCCO;
					$TotalFinalPrice=$TotalGnlPrice + $balanceHosp + $balanceResto + $balanceTourdesalle;
					$TotalFinalTopupPrice=$TotalGnlTopupPrice + $topupHosp + $topupResto + $topupTourdesalle;
					$TotalFinalPatientPrice=$TotalGnlPatientPrice + $patientPriceHosp + $patientPriceResto + $patientPriceTourdesalle;
					$TotalFinalPatientBalance=$TotalGnlPatientBalance + $patientBalanceHosp + $patientBalanceResto + $patientBalanceTourdesalle;
					$TotalFinalInsurancePrice=$TotalGnlInsurancePrice + $insurancePriceHosp + $insurancePriceResto + $insurancePriceTourdesalle;


					$TotalFinalPricePayedCCO=$TotalGnlPricePayedCCO + $balanceHospCCO + $balanceRestoCCO + $balanceTourdesalleCCO;
					$TotalFinalPricePayed=$TotalGnlPricePayed + $balanceHosp + $balanceResto + $balanceTourdesalle;
					$TotalFinalTopupPricePayed=$TotalGnlTopupPricePayed + $topupHosp + $topupResto + $topupTourdesalle;
					$TotalFinalPatientPricePayed=$TotalGnlPatientPricePayed + $patientPriceHosp + $patientPriceResto + $patientPriceTourdesalle;
					$TotalFinalPatientBalancePayed=$TotalGnlPatientBalancePayed + $patientBalanceHosp +$patientBalanceResto +$patientBalanceTourdesalle;
					$TotalFinalInsurancePricePayed=$TotalGnlInsurancePricePayed + $insurancePriceHosp + $insurancePriceResto + $insurancePriceTourdesalle;
				}else{
					$TotalFinalPriceCCO=$TotalGnlPriceCCO + $balanceHospCCO;
					$TotalFinalPrice=$TotalGnlPrice + $balanceHosp;
					$TotalFinalTopupPrice=$TotalGnlTopupPrice + $topupHosp;
					$TotalFinalPatientPrice=$TotalGnlPatientPrice + $patientPriceHosp;
					$TotalFinalPatientBalance=$TotalGnlPatientBalance + $patientBalanceHosp;
					$TotalFinalInsurancePrice=$TotalGnlInsurancePrice + $insurancePriceHosp;
					
					
					$TotalFinalPricePayedCCO=$TotalGnlPricePayedCCO + $balanceHospCCO;
					$TotalFinalPricePayed=$TotalGnlPricePayed + $balanceHosp;
					$TotalFinalTopupPricePayed=$TotalGnlTopupPricePayed + $topupHosp;
					$TotalFinalPatientPricePayed=$TotalGnlPatientPricePayed + $patientPriceHosp;
					$TotalFinalPatientBalancePayed=$TotalGnlPatientBalancePayed + $patientBalanceHosp;
					$TotalFinalInsurancePricePayed=$TotalGnlInsurancePricePayed + $insurancePriceHosp;
				}

			       
		
		
		/*
				if($TotalFinalPricePayed !=0)
				{
				?>
				<tr style="text-align:center;">
					<td style="font-size: 13px; font-weight: bold;">Final Balance not payed</td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPriceCCO;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPrice;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlTopupPrice;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

					<td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPatientPrice;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

					<td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPatientBalance;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

					<td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlInsurancePrice;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
				</tr>
				
				<tr style="text-align:center;color:rgba(0, 0, 0, 0.5);">
					<td style="font-size: 13px; font-weight: bold;">Final Balance payed</td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPricePayedCCO;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPricePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlTopupPricePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPatientPricePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlPatientBalancePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>

                    <td style="font-size: 13px; font-weight: bold;">
					<?php
					echo $TotalGnlInsurancePricePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
				</tr>
				<?php
				}
		*/
				?>
				
				<tr style="text-align:center;">
					<td style="font-size: 18px; font-weight: bold;">Final Balance</td>
					<td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalPriceCCO;
					?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
                    <td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalPrice;
					?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
                    <td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalTopupPrice;
					?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalPatientPrice;
					?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalPatientBalance;
					?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalInsurancePrice;
					?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
				</tr>
			</tbody>
		</table>
		
	</div>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
	
		<?php
		$footer = '

			<table style="width:100%">
				
				<tr>
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px; border-bottom:1px solid #333;">
						<span style="font-weight:bold">Patient Signature</span>
					</td>
					
					<td style="text-align:right;">
						 Done by : <span style="font-weight:bold">'.$doneby.'</span>
					</td>
					
				</tr>
				
			</table>';

		echo $footer;
		?>
		
	</div>
	
<?php
		if(isset($_GET['updatebill']))
		{
			
			$numroom=$_GET['numroom'];
			$idresto=$_GET['idresto'];
		
			/*----------Update Patients_Hosp----------------*/
			
			$updateIdPatientHosp=$connexion->prepare('UPDATE patients_hosp ph SET ph.codecashierHosp=:codecash WHERE ph.id_hosp=:idhosp AND ph.id_factureHosp=:idbill');

			$updateIdPatientHosp->execute(array(
			'idhosp'=>$_GET['idhosp'],
			'idbill'=>$idBilling,
			'codecash'=>$_SESSION['codeCash']
			
			))or die( print_r($connexion->errorInfo()));
			
			/*----------Update Restauration----------------*/
			
			$updateIdPatientHosp=$connexion->prepare('UPDATE restauration r SET r.codecashierHosp=:codecash WHERE r.id_resto=:idresto AND r.id_factureHosp=:idbill');

			$updateIdPatientHosp->execute(array(
			'idresto'=>$idresto,
			'idbill'=>$idBilling,
			'codecash'=>$_SESSION['codeCash']
			
			))or die( print_r($connexion->errorInfo()));
			
			
			/*----------Update Rooms----------------*/
			
			if($_GET['numlit']=='A')
			{
				$statusA=0;
				
				$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusA=:statusA WHERE r.numroom=:numroom');

				$updateIdRoomHosp->execute(array(
				'statusA'=>$statusA,
				'numroom'=>$numroom
				
				))or die( print_r($connexion->errorInfo()));
				
				
			}elseif($_GET['numlit']=='B'){
			
				$statusB=0;
				
				$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusB=:statusB WHERE r.numroom=:numroom');

				$updateIdRoomHosp->execute(array(
				'statusB'=>$statusB,
				'numroom'=>$numroom
				
				))or die( print_r($connexion->errorInfo()));
				
			}
			
			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$IdBill=str_replace('/', '_', $idBilling);
			
			// $objWriter->save('C:/wamp/www/stjean/BillFiles/Bill#'.$IdBill.'.xlsx');
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			// createBN();
			
			echo '<script text="text/javascript">document.location.href="printBill_hospFactReport.php?num='.$_GET['num'].'&idassu='.$_GET['idassu'].'&cashier='.$_SESSION['codeCash'].'&idhosp='.$_GET['idhosp'].'&datefacturedebut='.$_GET['datefacturedebut'].'&datefacturefin='.$_GET['datefacturefin'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&idbill='.$idBilling.'&numroom='.$_GET['numroom'].'&idresto='.$_GET['idresto'].'&idtourdesalle='.$_GET['idtourdesalle'].'&numlit='.$_GET['numlit'].'&finishbtn=ok"</script>';
			
		
		}

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
	/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&cashier=CSC15A01&dateconsu=2015-09-19");
	file_put_contents("toPDF.html", $file); */

}
?>
</body>

</html>