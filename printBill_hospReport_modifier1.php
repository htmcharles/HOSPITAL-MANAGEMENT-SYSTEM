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
		
		$numbill = $ligne->id_factureHosp;
		$vouchernum = $ligne->vouchernumHosp;
		
		$idassubill=$ligne->id_assuHosp;
		$percentIdbill=$ligne->insupercent_hosp;
		$nomassurance=$ligne->nomassuranceHosp;
		$idcardbill=$ligne->idcardbillHosp;
		$numpolicebill=$ligne->numpolicebillHosp;
		$adherentbill=$ligne->adherentbillHosp;
		
		$datebill = date('d-M-Y', strtotime($ligne->dateSortie));
		$createBill = 0;
		
	}

	
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Bill#'.$numbill; ?></title>

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
$idCoordi=$_SESSION['id'];

if($connected==true AND isset($_SESSION['codeC']))
{
		
	$resultatsManager=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
	$resultatsManager->execute(array(
	'operation'=>$idCoordi	
	));

	$resultatsManager->setFetchMode(PDO::FETCH_OBJ);
	if($ligneManager=$resultatsManager->fetch())
	{
		$doneby = $ligneManager->nom_u.'  '.$ligneManager->prenom_u;
		$codecoordi = $ligneManager->codecoordi;
		$codecashier = "";
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
		$code->setLabel('# '.$numbill.' #');
		$code->parse(''.$numbill.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecoordi.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
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
				<img src="barcode/png/barcode'.$codecoordi.'.png" style="height:auto;"/>	
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
			
			if(isset($_POST['assurance']))
			{
				$assurance = $_POST['assurance'];
			
			}else{
				$assurance = $_GET['idassu'];
			}
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

			if($ligneAssu=$resultAssurance->fetch())
			{
				$idassu=$ligneAssu->id_assurance;
				$insurance=$ligneAssu->nomassurance;
				
				
				$userinfo .= ''.$insurance.'</span><br/>';
				
				if($idassu!=1)
				{
					if(isset($_POST['percentIdbill']))
					{
						$percentIdbill= $_POST['percentIdbill'];
					}else{
						$percentIdbill=$_GET['billpercent'];
					}
				
					if(isset($_POST['idcardbill']))
					{
						$idcardbill=$_POST['idcardbill'];
					}
					
					if(isset($_POST['numpolicebill']))
					{
						$numpolicebill=$_POST['numpolicebill'];
					}
					
					if(isset($_POST['adherentbill']))
					{
						$adherentbill=$_POST['adherentbill'];
					}
				
					if(isset($_POST['vouchernum']))
					{
						$vouchernum=$_POST['vouchernum'];
					}
				
					$userinfo .= 'N° insurance card: 
					<span style="font-weight:bold">'.$idcardbill;
					
					if($numpolicebill!="")
					{
						$userinfo .= '</span><br/>
						
						N° police: 
						<span style="font-weight:bold">'.$numpolicebill;
					}
						
					$userinfo .= '</span><br/>
					
					Principal member: 
					<span style="font-weight:bold">'.$adherentbill;
				
				}else{
					
					$percentIdbill=100;
			
					$idcardbill="";
					$numpolicebill="";
					$adherentbill="";
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
					 ->setTitle('Bill #'.$numbill.'')
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
						->setCellValue('B4', ''.$insurance.' '.$percentIdbill.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		/*-------Requête pour AFFICHER Type Hospitalisation-----------*/
		
	if(isset($_POST['previewbtn']))
	{
		
		$idHosp=$_GET['idhosp'];					
		$numroom=$_POST['numroom'];
		$prixprestaHosp=$_POST['prixprestaHosp'];
		
		if($idassu==1)
		{
			$percentHosp=100;
		}else{
			$percentHosp=$_POST['percentHosp'];
		}
		
			
			
		if($_POST['mois']<10)
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
					
				if($_POST['heurein']<10)
				{		
					$heurein='0'.$_POST['heurein'];
				}else{
					$heurein=$_POST['heurein'];
				}
				
				if($_POST['minutein']<10)
				{		
					$minutein='0'.$_POST['minutein'];
				}else{
					$minutein=$_POST['minutein'];
				}
				
				if($_POST['secondein']<10)
				{		
					$secondein='0'.$_POST['secondein'];
				}else{
					$secondein=$_POST['secondein'];
				}
				
		$firstDate=$_POST['annee'].'-'.$mois.'-'.$jours;
		
		$dateEntree = date('Y-m-d', strtotime($firstDate));
		$heureEntree=$heurein.':'.$minutein.':'.$secondein;
			
			
		if($_POST['moisC']<10)
		{		
			$moisC='0'.$_POST['moisC'];
		}else{
			$moisC=$_POST['moisC'];
		}
		
		if($_POST['joursC']<10)
		{		
			$joursC='0'.$_POST['joursC'];
		}else{
			$joursC=$_POST['joursC'];
		}
					
				if($_POST['heureout']<10)
				{		
					$heureout='0'.$_POST['heureout'];
				}else{
					$heureout=$_POST['heureout'];
				}
				
				if($_POST['minuteout']<10)
				{		
					$minuteout='0'.$_POST['minuteout'];
				}else{
					$minuteout=$_POST['minuteout'];
				}
				
				if($_POST['secondeout']<10)
				{		
					$secondeout='0'.$_POST['secondeout'];
				}else{
					$secondeout=$_POST['secondeout'];
				}
				
		$lastDate=$_POST['anneeC'].'-'.$moisC.'-'.$joursC;
		
		$dateSortie = date('Y-m-d', strtotime($lastDate));
		$heureSortie=$heureout.':'.$minuteout.':'.$secondeout;
		
		
		$updatepercent=$connexion->prepare('UPDATE patients_hosp ph SET ph.numroomPa=:numroomPa,ph.dateEntree=:dateEntree,ph.heureEntree=:heureEntree,ph.dateSortie=:dateSortie,ph.heureSortie=:heureSortie,ph.prixroom=:prixprestaHosp,ph.insupercent_hosp=:percentHosp,ph.id_assuHosp=:idassuHosp,ph.nomassuranceHosp=:nomassuranceHosp,ph.idcardbillHosp=:idcardbillHosp,ph.numpolicebillHosp=:numpolicebillHosp,ph.adherentbillHosp=:adherentbillHosp,ph.codecashierHosp=:codecashierHosp,ph.codecoordiHosp=:codecoordiHosp,ph.vouchernumHosp=:vouchernum WHERE ph.id_hosp=:idHosp');
		
		$updatepercent->execute(array(
		'numroomPa'=>$numroom,
		'dateEntree'=>$dateEntree,
		'heureEntree'=>$heureEntree,
		'dateSortie'=>$dateSortie,
		'heureSortie'=>$heureSortie,
		'prixprestaHosp'=>$prixprestaHosp,
		'percentHosp'=>$percentHosp,
		'idassuHosp'=>$idassu,
		'nomassuranceHosp'=>$insurance,
		'idcardbillHosp'=>$idcardbill,
		'numpolicebillHosp'=>$numpolicebill,
		'adherentbillHosp'=>$adherentbill,
		'codecashierHosp'=>$codecashier,
		'codecoordiHosp'=>$codecoordi,
		'vouchernum'=>$vouchernum,
		'idHosp'=>$idHosp
		
		))or die( print_r($connexion->errorInfo()));
	}
	
		$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:hospId AND ph.numero=:num ORDER BY ph.id_hosp');		
		$resultHosp->execute(array(
		'hospId'=>$hospId,
		'num'=>$numPa
		));

		$resultHosp->setFetchMode(PDO::FETCH_OBJ);

		$comptHosp=$resultHosp->rowCount();
		
		$TotalHospPayed = 0;
		$TotalHosp = 0;
		
		/*-------Requête pour AFFICHER med_consult_hosp-----------*/
	
			
		if(isset($_POST['idpresta']))
		{
			
			$idassuServ=$idassu;

			$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

			$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuServ->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuServ=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuServ->execute(array(
				'idassu'=>$idassuServ
				));
				
				$getAssuServ->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssuServ=$getAssuServ->fetch())
				{
					$presta_assuServ='prestations_'.$ligneNomAssuServ->nomassurance;
				}
			}
			


			$idprestamc = array();
			$prixmc = array();
			$add = array();
			$idmc = array();
			$autremc = array();
			$qteConsu = array();
			$anneemc = array();
			$moismc = array();
			$jourmc = array();

			foreach($_POST['idpresta'] as $mc)
			{
				$idprestamc[] = $mc;
			}
			
			foreach($_POST['prixprestaConsu'] as $valmc)
			{
				$prixmc[] = $valmc;
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
			
			foreach($_POST['anneeMedConsu'] as $anmc)
			{
				$anneemc[] = $anmc;
			}
			
			foreach($_POST['moisMedConsu'] as $moimc)
			{
				$moismc[] = $moimc;
			}
			
			foreach($_POST['joursMedConsu'] as $jrmc)
			{
				$jourmc[] = $jrmc;
			}
			
			for($i=0;$i<sizeof($add);$i++)
			{
				// echo $add[$i].'_'.$idmc[$i].'_('.$prixmc[$i].')<br/>';
				
				if($moismc[$i]<10)
				{
					$moismc[$i]='0'.$moismc[$i];
				}
				
				if($jourmc[$i]<10)
				{
					$jourmc[$i]='0'.$jourmc[$i];
				}
				
				$datehospMedConsu=$anneemc[$i].'-'.$moismc[$i].'-'.$jourmc[$i];
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation='.$idprestamc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
			
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.datehosp=:datehosp,mc.id_assuServ=:assurance,mc.insupercentServ=:add,mc.prixprestationConsu=:prixmc,mc.prixautreConsu=0,mc.qteConsu=:qteConsu,mc.codecashier=:codecashier,mc.codecoordi=:codecoordi WHERE mc.id_medconsu=:idmc');
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedConsu,
						'assurance'=>$assurance,
						'add'=>$add[$i],
						'prixmc'=>$prixmc[$i],
						'qteConsu'=>$qteConsu[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmc'=>$idmc[$i]
						
						))or die( print_r($connexion->errorInfo()));
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consult_hosp mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					$comptPrestas=$results->rowCount();
					
		
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.datehosp=:datehosp,mc.id_assuServ=:assurance,mc.insupercentServ=:add,mc.prixprestationConsu=0,mc.prixautreConsu=:prixmc,mc.qteConsu=:qteConsu,mc.codecashier=:codecashier,mc.codecoordi=:codecoordi WHERE mc.id_medconsu=:idmc');
						
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedConsu,
						'assurance'=>$assurance,
						'add'=>$add[$i],
						'prixmc'=>$prixmc[$i],
						'qteConsu'=>$qteConsu[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmc'=>$idmc[$i]

						))or die( print_r($connexion->errorInfo()));
						
					}
				}

			}
		}
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc, patients_hosp p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_hospMed=:idhosp AND p.id_hosp=mc.id_hospMed AND mc.datehosp!="0000-00-00" AND mc.id_factureMedConsu!="" ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
		$TotalMedConsultPayed = 0;
	
	
	
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

				if($ligneNomAssuInf=$getAssuInf->fetch())
				{
					$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
				}
			}



			$idprestami = array();
			$prixmi = array();
			$addInf = array();
			$idmi = array();
			$autremi = array();
			$qteInf = array();
			$anneemi = array();
			$moismi = array();
			$jourmi = array();

			foreach($_POST['idprestaInf'] as $mi)
			{
				$idprestami[] = $mi;
			}
			
			foreach($_POST['prixprestaInf'] as $valmi)
			{
				$prixmi[] = $valmi;
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
			
			foreach($_POST['anneeMedInf'] as $anmi)
			{
				$anneemi[] = $anmi;
			}
			
			foreach($_POST['moisMedInf'] as $moimi)
			{
				$moismi[] = $moimi;
			}
			
			foreach($_POST['joursMedInf'] as $jrmi)
			{
				$jourmi[] = $jrmi;
			}

			
			for($i=0;$i<sizeof($addInf);$i++)
			{
				
				// echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';
				
				if($moismi[$i]<10)
				{
					$moismi[$i]='0'.$moismi[$i];
				}
				
				if($jourmi[$i]<10)
				{
					$jourmi[$i]='0'.$jourmi[$i];
				}
				
				$datehospMedInf=$anneemi[$i].'-'.$moismi[$i].'-'.$jourmi[$i];
				
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation='.$idprestami[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.datehosp=:datehosp,mi.datesoins=:datesoins,mi.id_assuInf=:assurance,mi.insupercentInf=:addInf,mi.prixprestation=:prixmi,mi.prixautrePrestaM=0,mi.qteInf=:qteInf,mi.codecashier=:codecashier,mi.codecoordi=:codecoordi WHERE mi.id_medinf=:idmi');
						
						$updatepercent->execute(array(
						'datehosp'=>$dateEntree,
						'datesoins'=>$datehospMedInf,
						'assurance'=>$assurance,
						'addInf'=>$addInf[$i],
						'prixmi'=>$prixmi[$i],
						'qteInf'=>$qteInf[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmi'=>$idmi[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_inf_hosp mi WHERE mi.id_medinf='.$idmi[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.datehosp=:datehosp,mi.datesoins=:datesoins,mi.id_assuInf=:assurance,mi.insupercentInf=:addInf,mi.prixprestation=0,mi.prixautrePrestaM=:prixmi,mi.qteInf=:qteInf,mi.codecashier=:codecashier,mi.codecoordi=:codecoordi WHERE mi.id_medinf=:idmi');
						
						$updatepercent->execute(array(
						'datehosp'=>$dateEntree,
						'datesoins'=>$datehospMedInf,
						'assurance'=>$assurance,
						'addInf'=>$addInf[$i],
						'prixmi'=>$prixmi[$i],
						'qteInf'=>$qteInf[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmi'=>$idmi[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
				}
				
			}
		}
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, patients_hosp p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_hospInf=:idhosp AND p.id_hosp=mi.id_hospInf AND mi.id_factureMedInf!="" ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		$TotalMedInfPayed = 0;
		
	
	
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

				if($ligneNomAssuLab=$getAssuLab->fetch())
				{
					$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
				}
			}
						
							
							
			$idprestaml = array();
			$prixml = array();
			$addLab = array();
			$idml = array();
			$autreml = array();
			$qteLab = array();
			$anneeml = array();
			$moisml = array();
			$jourml = array();


			foreach($_POST['idprestaLab'] as $ml)
			{
				$idprestaml[] = $ml;
			}
			
			foreach($_POST['prixprestaLab'] as $valml)
			{
				$prixml[] = $valml;
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
			
			foreach($_POST['anneeMedLabo'] as $anml)
			{
				$anneeml[] = $anml;
			}
			
			foreach($_POST['moisMedLabo'] as $moiml)
			{
				$moisml[] = $moiml;
			}
			
			foreach($_POST['joursMedLabo'] as $jrml)
			{
				$jourml[] = $jrml;
			}

			
			for($i=0;$i<sizeof($addLab);$i++)
			{
				
				// echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';
			
				if($moisml[$i]<10)
				{
					$moisml[$i]='0'.$moisml[$i];
				}
				
				if($jourml[$i]<10)
				{
					$jourml[$i]='0'.$jourml[$i];
				}
				
				$datehospMedLabo=$anneeml[$i].'-'.$moisml[$i].'-'.$jourml[$i];
					
				$result=$connexion->query('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation='.$idprestaml[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.datehosp=:datehosp,ml.id_assuLab=:assurance,ml.insupercentLab=:addLab,ml.prixprestationExa=:prixml,ml.prixautreExamen=0,ml.qteLab=:qteLab,ml.codecashier=:codecashier,ml.codecoordi=:codecoordi WHERE ml.id_medlabo=:idml');
						
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedLabo,
						'assurance'=>$assurance,
						'addLab'=>$addLab[$i],
						'prixml'=>$prixml[$i],
						'qteLab'=>$qteLab[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,					
						'idml'=>$idml[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_labo_hosp ml WHERE ml.id_medlabo='.$idml[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.datehosp=:datehosp,ml.id_assuLab=:assurance,ml.insupercentLab=:addLab,ml.prixprestationExa=0,ml.prixautreExamen=:prixml,ml.qteLab=:qteLab,ml.codecashier=:codecashier,ml.codecoordi=:codecoordi WHERE ml.id_medlabo=:idml');
						
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedLabo,
						'assurance'=>$assurance,
						'addLab'=>$addLab[$i],
						'prixml'=>$prixml[$i],
						'qteLab'=>$qteLab[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,	
						'idml'=>$idml[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
				}
			}
		}
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, patients_hosp p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_hospLabo=:idhosp AND p.id_hosp=ml.id_hospLabo AND ml.id_factureMedLabo!="" ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();			
		
		$TotalMedLabo = 0;
		$TotalMedLaboPayed = 0;
	
	
	
	
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

				if($ligneNomAssuRad=$getAssuRad->fetch())
				{
					$presta_assuRad='prestations_'.$ligneNomAssuRad->nomassurance;
				}
			}

							
										
			$idprestamr = array();
			$prixmr = array();
			$addRad = array();
			$idmr = array();
			$autremr = array();
			$qteRad = array();
			$anneeemr = array();
			$moismr = array();
			$jourmr = array();


			foreach($_POST['idprestaRad'] as $mr)
			{
				$idprestamr[] = $mr;
			}
			
			foreach($_POST['prixprestaRad'] as $valmr)
			{
				$prixmr[] = $valmr;
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
			
			foreach($_POST['anneeMedRadio'] as $anmr)
			{
				$anneemr[] = $anmr;
			}
			
			foreach($_POST['moisMedRadio'] as $moimr)
			{
				$moismr[] = $moimr;
			}
			
			foreach($_POST['joursMedRadio'] as $jrmr)
			{
				$jourmr[] = $jrmr;
			}

			
			
			for($i=0;$i<sizeof($addRad);$i++)
			{
				
				// echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';
				
				if($moismr[$i]<10)
				{
					$moismr[$i]='0'.$moismr[$i];
				}
				
				if($jourmr[$i]<10)
				{
					$jourmr[$i]='0'.$jourmr[$i];
				}
				
				$datehospMedRadio=$anneemr[$i].'-'.$moismr[$i].'-'.$jourmr[$i];
					
				$result=$connexion->query('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation='.$idprestamr[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
				
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.datehosp=:datehosp,mr.id_assuRad=:assurance,mr.insupercentRad=:addRad,mr.prixprestationRadio=:prixmr,mr.prixautreRadio=0,mr.qteRad=:qteRad,mr.codecashier=:codecashier,mr.codecoordi=:codecoordi WHERE mr.id_medradio=:idmr');

						$updatepercent->execute(array(
						'datehosp'=>$datehospMedRadio,
						'assurance'=>$assurance,
						'addRad'=>$addRad[$i],
						'prixmr'=>$prixmr[$i],
						'qteRad'=>$qteRad[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmr'=>$idmr[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_radio_hosp mr WHERE mr.id_medradio='.$idmr[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.datehosp=:datehosp,mr.id_assuRad=:assurance,mr.insupercentRad=:addRad,mr.prixprestationRadio=0,mr.prixautreRadio=:prixmr,mr.qteRad=:qteRad,mr.codecashier=:codecashier,mr.codecoordi=:codecoordi WHERE mr.id_medradio=:idmr');

						$updatepercent->execute(array(
						'datehosp'=>$datehospMedRadio,
						'assurance'=>$assurance,
						'addRad'=>$addRad[$i],
						'prixmr'=>$prixmr[$i],
						'qteRad'=>$qteRad[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmr'=>$idmr[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
				}
			}
		}
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, patients_hosp p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_hospRadio=:idhosp AND p.id_hosp=mr.id_hospRadio AND mr.id_factureMedRadio!="" ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();			
		
		$TotalMedRadio = 0;
		$TotalMedRadioPayed = 0;
	
	
	
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

				if($ligneNomAssuConsom=$getAssuConsom->fetch())
				{
					$presta_assuConsom='prestations_'.$ligneNomAssuConsom->nomassurance;
				}
			}



			$idprestaconsom = array();
			$prixmco = array();
			$addConsom = array();
			$idmco = array();
			$autreconsom = array();
			$qteConsom = array();
			$anneeeConsom = array();
			$moisConsom = array();
			$jourConsom = array();

			
			foreach($_POST['idprestaConsom'] as $consom)
			{
				$idprestaconsom[] = $consom;
			}
			
			foreach($_POST['prixprestaConsom'] as $valConsom)
			{
				$prixmco[] = $valConsom;
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
			
			foreach($_POST['anneeMedConsom'] as $anConsom)
			{
				$anneeConsom[] = $anConsom;
			}
			
			foreach($_POST['moisMedConsom'] as $moiConsom)
			{
				$moisConsom[] = $moiConsom;
			}
			
			foreach($_POST['joursMedConsom'] as $jrConsom)
			{
				$jourConsom[] = $jrConsom;
			}

			
			for($i=0;$i<sizeof($addConsom);$i++)
			{
			
				// echo $addConsom[$i].'_'.$idmco[$i].'_('.$prixmco[$i].' : '.$qteConsom[$i].')<br/>';
				
			
				if($moisConsom[$i]<10)
				{
					$moisConsom[$i]='0'.$moisConsom[$i];
				}
				
				if($jourConsom[$i]<10)
				{
					$jourConsom[$i]='0'.$jourConsom[$i];
				}
				
				$datehospMedConsom=$anneeConsom[$i].'-'.$moisConsom[$i].'-'.$jourConsom[$i];
				
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation='.$idprestaconsom[$i].'');
					
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.datehosp=:datehosp,mco.id_assuConsom=:assurance,mco.insupercentConsom=:addConsom,mco.prixprestationConsom=:prixmco,mco.prixautreConsom=0,mco.qteConsom=:qteConsom,mco.codecashier=:codecashier,mco.codecoordi=:codecoordi WHERE mco.id_medconsom=:idmco');
					
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedConsom,
						'assurance'=>$assurance,
						'addConsom'=>$addConsom[$i],
						'prixmco'=>$prixmco[$i],
						'qteConsom'=>$qteConsom[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmco'=>$idmco[$i]

						))or die( print_r($connexion->errorInfo()));
														
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consom_hosp mco WHERE mco.id_medconsom='.$idmco[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.datehosp=:datehosp,mco.id_assuConsom=:assurance,mco.insupercentConsom=:addConsom,mco.prixprestationConsom=0,mco.prixautreConsom=:prixmco,mco.qteConsom=:qteConsom,mco.codecashier=:codecashier,mco.codecoordi=:codecoordi WHERE mco.id_medconsom=:idmco');
					
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedConsom,
						'assurance'=>$assurance,
						'addConsom'=>$addConsom[$i],
						'prixmco'=>$prixmco[$i],
						'qteConsom'=>$qteConsom[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmco'=>$idmco[$i]

						))or die( print_r($connexion->errorInfo()));
							
					}
				}				
			}
		}
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, patients_hosp p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_hospConsom=:idhosp AND p.id_hosp=mco.id_hospConsom AND mco.id_factureMedConsom!="" ORDER BY mco.id_medconsom');		
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		$TotalMedConsomPayed = 0;
		
	
	
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

				if($ligneNomAssuMedoc=$getAssuMedoc->fetch())
				{
					$presta_assuMedoc='prestations_'.$ligneNomAssuMedoc->nomassurance;
				}
			}
								
			

			$idprestamedoc = array();
			$prixmdo = array();
			$addMedoc = array();
			$idmdo = array();
			$autremedoc = array();
			$qteMedoc = array();
			$anneeeMedoc = array();
			$moisMedoc = array();
			$jourMedoc = array();

			
			foreach($_POST['idprestaMedoc'] as $medoc)
			{
				$idprestamedoc[] = $medoc;
			}
			
			foreach($_POST['prixprestaMedoc'] as $valMedoc)
			{
				$prixmdo[] = $valMedoc;
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
			
			foreach($_POST['anneeMedMedoc'] as $anMedoc)
			{
				$anneeMedoc[] = $anMedoc;
			}
			
			foreach($_POST['moisMedMedoc'] as $moiMedoc)
			{
				$moisMedoc[] = $moiMedoc;
			}
			
			foreach($_POST['joursMedMedoc'] as $jrMedoc)
			{
				$jourMedoc[] = $jrMedoc;
			}


			for($i=0;$i<sizeof($addMedoc);$i++)
			{
			
				// echo $addMedoc[$i].'_'.$idmdo[$i].'_('.$prixmdo[$i].' : '.$qteMedoc[$i].')<br/>';
				
				if($moisMedoc[$i]<10)
				{
					$moisMedoc[$i]='0'.$moisMedoc[$i];
				}
				
				if($jourMedoc[$i]<10)
				{
					$jourMedoc[$i]='0'.$jourMedoc[$i];
				}
				
				$datehospMedMedoc=$anneeMedoc[$i].'-'.$moisMedoc[$i].'-'.$jourMedoc[$i];
				
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation='.$idprestamedoc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{				
					if($ligne=$result->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.datehosp=:datehosp,mdo.id_assuMedoc=:assurance,mdo.insupercentMedoc=:addMedoc,mdo.prixprestationMedoc=:prixmdo,mdo.prixautreMedoc=0,mdo.qteMedoc=:qteMedoc,mdo.codecashier=:codecashier,mdo.codecoordi=:codecoordi WHERE mdo.id_medmedoc=:idmdo');
						
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedMedoc,
						'assurance'=>$assurance,
						'addMedoc'=>$addMedoc[$i],
						'prixmdo'=>$prixmdo[$i],
						'qteMedoc'=>$qteMedoc[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmdo'=>$idmdo[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.datehosp=:datehosp,mdo.id_assuMedoc=:assurance,mdo.insupercentMedoc=:addMedoc,mdo.prixprestationMedoc=0,mdo.prixautreMedoc=:prixmdo,mdo.qteMedoc=:qteMedoc,mdo.codecashier=:codecashier,mdo.codecoordi=:codecoordi WHERE mdo.id_medmedoc=:idmdo');
						
						$updatepercent->execute(array(
						'datehosp'=>$datehospMedMedoc,
						'assurance'=>$assurance,
						'addMedoc'=>$addMedoc[$i],
						'prixmdo'=>$prixmdo[$i],
						'qteMedoc'=>$qteMedoc[$i],
						'codecashier'=>$codecashier,
						'codecoordi'=>$codecoordi,
						'idmdo'=>$idmdo[$i]
						
						))or die( print_r($connexion->errorInfo()));
						
					}
				}
				
			}
		}
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, patients_hosp p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_hospMedoc=:idhosp AND p.id_hosp=mdo.id_hospMedoc AND mdo.id_factureMedMedoc!="" ORDER BY mdo.id_medmedoc');		
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();			
		
		$TotalMedMedoc = 0;
		$TotalMedMedocPayed = 0;
	
	
	?>
	
	<table style="width:100%; margin:20px auto auto;"> 
		<tr> 
			<td style="text-align:left; width:33%;">
				<h4><?php echo $datebill;?></h4>
			</td>
			
			<td style="text-align:center; width:43%;">
				<h2 style="font-size:150%; font-weight:600;">Hospitalisation bill-exit n° <?php echo $numbill;?></h2>
			</td>
			
			<td style="text-align:right;width:33%;">
			
				<form method="post" action="printBill_hospReport_modifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&datehosp=<?php echo $datehosp;?>&dateSortie=<?php if(isset($_GET['dateSortie'])){ echo $_GET['dateSortie'];}else{ echo $dateSortie;}?>&numbill=<?php echo $_GET['numbill'];?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>&updatebill=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td style="text-align:right;width:25%;" class="buttonBill">
				
				<a href="formModifierBillHosp.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}else{ echo '&idassu='.$assurance;}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}else{ echo '&nomassurance='.$nomassurance;}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}else{ echo '&billpercent='.$percentIdbill;}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>" id="updatebtn" style="margin:5px;">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo 'Modifier';?></button>
				</a>
			
			</td>
			
			<td class="buttonBill" style="text-align:right;width:25%;">
				<a href="categoriesbill_hospmodifier.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}else{ echo '&datehosp='.$datehosp;}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}else{ echo '&idassu='.$assurance;}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}else{ echo '&nomassurance='.$nomassurance;}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}else{ echo '&billpercent='.$percentIdbill;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="margin:5px;">
					<button class="btn-large" style="width:150px;"><i class="fa fa-plus fa-lg fa-fw"></i> <?php echo getString(221);?></button>
				</a>
								
				<a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="margin:5px;">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-check fa-lg fa-fw"></i> <?php echo getString(141);?></button>
				</a>
				
			</td>
		</tr>
	</table>
	
	
	<?php
		try
		{
			$TotalGnlPricePayed=0;
			$TotalGnlPatientPricePayed=0;
			$TotalGnlInsurancePricePayed=0;
			
			
			$TotalGnlPrice=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlInsurancePrice=0;
			
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
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 25px;"> 
				<thead> 
					<tr>
						<th style="width:5%;text-align:center;">Room</th>
						<th style="width:10%;text-align:center;">Date In</th>
						<th style="width:10%;text-align:center;">Date Out</th>
						<th style="width:5%;text-align:center;">Days</th>
						<th style="width:8%;text-align:center;">Price/day</th>
						<th style="width:8%;text-align:center;">Balance</th>
						<th style="width:8%;text-align:center;">Percent</th>
						<th style="width:13%;text-align:center;">Patient balance</th>
						<th style="width:13%;text-align:center;">Insurance balance</th>
					</tr> 
				</thead>

				<tbody>
				<?php
						
				$TotalPatientPriceHospPayed=0;				
				$TotalInsurancePriceHospPayed=0;
				
				$TotalPatientPriceHosp=0;				
				$TotalInsurancePriceHosp=0;
		
				while($ligneHosp=$resultHosp->fetch())
				{
					
						$billpercent=$ligneHosp->insupercent_hosp;
						
						$idassu=$ligneHosp->id_assuHosp;						
					?>
					<tr style="font-weight:bold;">
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
							}else{	
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaHosp=$lignePresta->nompresta;	
								}
							}
							
							$prixPresta = $lignePresta->prixpresta;
							
						?>
							
							<td style="text-align:center;">
							<?php
								echo date('d-M-Y', strtotime($ligneHosp->dateEntree));
							?>
							</td>
							<td style="text-align:center;">
							<?php
								echo date('d-M-Y', strtotime($ligneHosp->dateSortie));
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$dateIn=strtotime($ligneHosp->dateEntree);
							$dateOut=strtotime($ligneHosp->dateSortie);
							
							$datediff= abs($dateOut - $dateIn);
							
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
							
							<td style="text-align:center;"><?php echo $ligneHosp->prixroom;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;">
							<?php
							$balanceHosp=$ligneHosp->prixroom * $nbrejrs;
							
							echo $balanceHosp;
							
							if($ligneHosp->statusPaHosp!=1)
							{
								$TotalHospPayed=$TotalHospPayed + $balanceHosp;
							}else{
								
								$TotalHosp=$TotalHosp + $balanceHosp;
							}
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<?php
							if($ligneHosp->statusPaHosp!=1)
							{
								$patientPricePayed=($balanceHosp * $billpercent)/100;
								
								$TotalPatientPriceHospPayed=$TotalPatientPriceHospPayed + $patientPricePayed;
								
								$uapPricePayed= $balanceHosp - $patientPricePayed;
								
								$TotalInsurancePriceHospPayed = $TotalInsurancePriceHospPayed + $uapPricePayed;
							}else{
								
								$patientPrice=($balanceHosp * $billpercent)/100;
								$TotalPatientPriceHosp=$TotalPatientPriceHosp + $patientPrice;
								
								$uapPrice= $balanceHosp - $patientPrice;
								
								$TotalInsurancePriceHosp = $TotalInsurancePriceHosp + $uapPrice;
								
							}
							?>
							
							<td style="text-align:center;"><?php echo $billpercent;?>%</td>
							
							<td style="text-align:center;">
							<?php 
							if($ligneHosp->statusPaHosp!=1)
							{
								echo $patientPricePayed;
							}else{
								echo $patientPrice;
							}
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;">
							<?php 
							if($ligneHosp->statusPaHosp!=1)
							{
								echo $uapPricePayed;
							}else{
								echo $uapPrice;
							}
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
								
							<?php	
								
							$TotalGnlPrice=$TotalGnlPrice + $TotalHosp;
							
							$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalHospPayed;	
							
							
							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalPatientPriceHosp;
								
							$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalPatientPriceHospPayed;
						
						
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotalInsurancePriceHosp;
								
							$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotalInsurancePriceHospPayed;
							
							?>
					</tr>
				<?php
						}
						
					}
					
					$arrayConsult[$i][0]=$nameprestaHosp;
					$arrayConsult[$i][1]=$prixPresta;
					$arrayConsult[$i][2]=$patientPricePayed;
					$arrayConsult[$i][3]=$uapPricePayed;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','B9');
		
				}
					
				?>
				</tbody>
			</table>
			<?php
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(9+$i).'', ''.$TotalHosp.'')
								->setCellValue('D'.(9+$i).'', ''.$TotalPatientPriceHosp.'')
								->setCellValue('E'.(9+$i).'', ''.$TotalInsurancePriceHosp.'');
			
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
						<th style="width:20%" colspan=9>Services</th>
					</tr>
					<tr>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="width:20%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
						<th style="width:5%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
				<?php
				
				$TotalpatientPricePayed=0;
				$TotaluapPricePayed=0;
				
				$TotalpatientPrice=0;
				$TotaluapPrice=0;
				

			
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

				?>
					<tr style="text-align:center;">
						
						<td><?php echo $ligneMedConsult->datehosp;?></td>
						<td style="text-align:left;">
					<?php 
		
					
					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedConsult->id_prestationConsu
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())
					{
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
							$prixPresta = $ligneMedConsult->prixprestationConsu;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balance=$prixPresta*$qteConsu;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
						
						if($ligneMedConsult->id_factureMedConsu=="")
						{
							$TotalMedConsult=$TotalMedConsult + $balance;
							
						}else{
						
							$TotalMedConsultPayed=$TotalMedConsultPayed + $balance;
							
						}
						?>				
						</td>
						
						<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>
						
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							
						if($ligneMedConsult->id_factureMedConsu=="")
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
							$uapPrice= $balance - $patientPrice;
						
						if($ligneMedConsult->id_factureMedConsu=="")
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
						
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
					</tr>
					<?php
					}
					
					if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu!=0)
					{
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
							$prixPresta = $ligneMedConsult->prixautreConsu;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balance=$prixPresta*$qteConsu;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
						
						if($ligneMedConsult->id_factureMedConsu=="")
						{
							$TotalMedConsult=$TotalMedConsult + $balance;
							
						}else{
						
							$TotalMedConsultPayed=$TotalMedConsultPayed + $balance;
							
						}
						?>				
						</td>
							
						<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>
					
						<td>
						<?php 
							$patientPrice=($ligneMedConsult->prixautreConsu * $billpercent)/100;
						
						if($ligneMedConsult->id_factureMedConsu=="")
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
							$uapPrice= $ligneMedConsult->prixautreConsu - $patientPrice;
						
						if($ligneMedConsult->id_factureMedConsu=="")
						{	
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{	
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						
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
						<td colspan=4></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
							echo $TotalMedConsultPayed.'';

							$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
							
							$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsultPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
							echo $TotalpatientPricePayed.'';
															
							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							
							$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
							echo $TotaluapPricePayed.'';
							
							$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
							
							$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(12+$i+$x).'', ''.$TotalMedConsult.'')
								->setCellValue('D'.(12+$i+$x).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(12+$i+$x).'', ''.$TotaluapPrice.'');
			
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
						<th style="width:20%" colspan=9>Nursing Care</th>
					</tr>
					<tr>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="width:20%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
						<th style="width:5%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Insurance balance</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;
			
			$TotalpatientPrice=0;
			$TotaluapPrice=0;
			
				
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

				?>
					<tr style="text-align:center;">
						
						<td><?php echo $ligneMedInf->datesoins;?></td>
						<td style="text-align:left;">
					<?php 
		
					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
					$resultPresta->execute(array(
						'prestaId'=>$ligneMedInf->id_prestation
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())
					{
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
							$prixPresta = $ligneMedInf->prixprestation;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
							$balance=$prixPresta*$qteInf;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
						if($ligneMedInf->id_factureMedInf=="")
						{
							$TotalMedInf = $TotalMedInf + $balance;
						}else{
							$TotalMedInfPayed= $TotalMedInfPayed + $balance;
						}
						?>				
						</td>
						
						<td><?php echo $ligneMedInf->insupercentInf;?>%</td>						
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
						
						if($ligneMedInf->id_factureMedInf=="")
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
							$uapPrice= $balance - $patientPrice;
							
						if($ligneMedInf->id_factureMedInf=="")
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
					</tr>	
					<?php
					}
					
					if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM!=0)
					{
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
							$prixPresta = $ligneMedInf->prixautrePrestaM;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
							$balance=$prixPresta*$qteInf;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
						if($ligneMedInf->id_factureMedInf=="")
						{
							$TotalMedInf = $TotalMedInf + $balance;
						}else{
							$TotalMedInfPayed= $TotalMedInfPayed + $balance;
						}
						?>				
						</td>
							
						<td><?php echo $ligneMedInf->insupercentInf;?>%</td>						
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							
						if($ligneMedInf->id_factureMedInf=="")
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
							$uapPrice= $balance - $patientPrice;
							
						if($ligneMedInf->id_factureMedInf=="")
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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
						<td colspan=4></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedInfPayed.'';
							
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedInfPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPricePayed.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
										
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPricePayed.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;	
							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
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
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:20%" colspan=9>Labs</th>
					</tr>
					<tr>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="width:20%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
						<th style="width:5%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;
			
			$TotalpatientPrice=0;
			$TotaluapPrice=0;
			
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

				?>
					<tr style="text-align:center;">
					
						<td><?php echo $ligneMedLabo->datehosp;?></td>
						<td style="text-align:left;">
						<?php			
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedLabo->id_prestationExa
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{	
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
							$prixPresta = $ligneMedLabo->prixprestationExa;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
						$balance=$prixPresta*$qteLab;
						
						echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
						if($ligneMedLabo->id_factureMedLabo=="")
						{
							$TotalMedLabo = $TotalMedLabo + $balance;
						}else{
							$TotalMedLaboPayed = $TotalMedLaboPayed + $balance;
						}
							?>
						</td>
						
						<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
						
						<td>
							<?php 
							$patientPrice=($balance * $billpercent)/100;
						
						if($ligneMedLabo->id_factureMedLabo=="")
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
							$uapPrice= $balance - $patientPrice;
							
						if($ligneMedLabo->id_factureMedLabo=="")
						{
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
								
					</tr>
					<?php
						}
						
						if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen!=0)
						{
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
							$prixPresta = $ligneMedLabo->prixautreExamen;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
						$balance=$prixPresta*$qteLab;
						
						echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
						if($ligneMedLabo->id_factureMedLabo=="")
						{
							$TotalMedLabo = $TotalMedLabo + $balance;
						}else{
							$TotalMedLaboPayed = $TotalMedLaboPayed + $balance;
						}
							?>
						</td>
					
						<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
					
						<td>
						<?php 
							$patientPrice=($ligneMedLabo->prixautreExamen * $billpercent)/100;
							
						if($ligneMedLabo->id_factureMedLabo=="")
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
							$uapPrice= $ligneMedLabo->prixautreExamen - $patientPrice;
							
						if($ligneMedLabo->id_factureMedLabo=="")
						{	
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
						}else{	
							$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
						}
						
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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
						<td colspan=4></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedLaboPayed.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;					
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedLaboPayed;	
														
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPricePayed.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPricePayed.'';
	
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;																	
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
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
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:20%" colspan=9>Radiologie</th>
					</tr>
					<tr>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="width:20%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
						<th style="width:5%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05);">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;
			
			$TotalpatientPrice=0;
			$TotaluapPrice=0;
			
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

				?>
					<tr style="text-align:center;">
					
						<td><?php echo $ligneMedRadio->datehosp;?></td>
						<td style="text-align:left;">
						<?php			
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedRadio->id_prestationRadio
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
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
								$prixPresta = $ligneMedRadio->prixprestationRadio;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteRad;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedRadio->id_factureMedRadio=="")
							{
								$TotalMedRadio = $TotalMedRadio + $balance;
							}else{
								$TotalMedRadioPayed = $TotalMedRadioPayed + $balance;
							}
								?>
							</td>
							
							<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
							
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
							
							if($ligneMedRadio->id_factureMedRadio=="")
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
								$uapPrice= $balance - $patientPrice;
								
							if($ligneMedRadio->id_factureMedRadio=="")
							{
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
								
								echo $uapPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
						</tr>
						<?php
						}
						
						if($ligneMedRadio->id_prestationRadio=="" AND $ligneMedRadio->prixautreRadio!=0)
						{
							$nameprestaMedRadio=$ligneMedRadio->autreRadio;
							echo $nameprestaMedRadio;
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
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteRad;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedRadio->id_factureMedRadio=="")
							{
								$TotalMedRadio = $TotalMedRadio + $balance;
							}else{
								$TotalMedRadioPayed = $TotalMedRadioPayed + $balance;
							}
								?>
							</td>
							
							<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
							
							<td>
								<?php 
									$patientPrice=($balance * $billpercent)/100;
								
								if($ligneMedRadio->id_factureMedRadio=="")
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
								$uapPrice= $balance - $patientPrice;
								
							if($ligneMedRadio->id_factureMedRadio=="")
							{
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
								
								echo $uapPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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
						<td colspan=4></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedRadioPayed.'';
							
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
								
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedRadioPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPricePayed.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPricePayed.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;												
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedRadio.'')
							->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			
			if($comptMedConsom != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(20+$i+$x+$y).'', 'Consommables')
							->setCellValue('C'.(20+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(20+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(20+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:20%" colspan=9>Consommables</th>
					</tr>
					<tr>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="width:20%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
						<th style="width:5%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;
			
			$TotalpatientPrice=0;
			$TotaluapPrice=0;
			
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



						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');	
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsom->id_prestationConsom
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
					
						if($comptPresta==0)
						{
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedConsom->id_prestationConsom
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
						}
							
						if($lignePresta=$resultPresta->fetch())
						{
						?>
						<tr style="text-align:center;">
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
								$prixPresta = $ligneMedConsom->prixprestationConsom;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteConsom;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedConsom->id_factureMedConsom!="")
							{
								$TotalMedConsom=$TotalMedConsom + $balance;
							}else{
								$TotalMedConsomPayed=$TotalMedConsomPayed + $balance;
							}
							?>				
							</td>
							
							<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
							
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
							
							if($ligneMedConsom->id_factureMedConsom=="")
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
								$uapPrice= $balance - $patientPrice;

							if($ligneMedConsom->id_factureMedConsom=="")
							{								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{								
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
							
								echo $uapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						</tr>						
						<?php
						}
						
						if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->prixautreConsom!=0)
						{
						?>
						<tr style="text-align:center;">
						
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
							
							if($ligneMedConsom->id_factureMedConsom=="")
							{	
								$TotalMedConsom=$TotalMedConsom + $balance;
							}else{	
								$TotalMedConsomPayed=$TotalMedConsomPayed + $balance;
							}
							?>				
							</td>
							
							<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
						
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
								
							if($ligneMedConsom->id_factureMedConsom=="")
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
								$uapPrice= $balance - $patientPrice;
							
							if($ligneMedConsom->id_factureMedConsom=="")
							{
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
							
								echo $uapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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
						<td colspan=4></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsomPayed.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsomPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
						<?php						
							echo $TotalpatientPricePayed.'';

							$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							
							$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
							
		// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)
							
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPricePayed.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;									
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
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
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:20%" colspan=9>Medicaments</th>
					</tr>
					<tr>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="width:20%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
						<th style="width:5%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPricePayed=0;
			$TotaluapPricePayed=0;
			
			$TotalpatientPrice=0;
			$TotaluapPrice=0;
			
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


					
					$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');	
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedMedoc->id_prestationMedoc
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
				
					if($comptPresta==0)
					{
						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation=:prestaId');
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedMedoc->id_prestationMedoc
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
					}
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
					?>
						<tr style="text-align:center;">
							
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
								$prixPresta = $ligneMedMedoc->prixprestationMedoc;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedMedoc->id_factureMedMedoc=="")
							{
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							}else{
								$TotalMedMedocPayed=$TotalMedMedocPayed + $balance;
							}
							?>				
							</td>
							
							<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
							
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
							if($ligneMedMedoc->id_factureMedMedoc=="")
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
								$uapPrice= $balance - $patientPrice;
							
							if($ligneMedMedoc->id_factureMedMedoc=="")
							{	
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{	
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
							
								echo $uapPrice;					
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
					{
					?>
						<tr style="text-align:center;">
						
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
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedMedoc->id_factureMedMedoc=="")
							{
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							}else{
								$TotalMedMedocPayed=$TotalMedMedocPayed + $balance;
							}
							?>				
							</td>
							
							<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
						
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
							
							if($ligneMedMedoc->id_factureMedMedoc=="")
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
								$uapPrice= $balance - $patientPrice;
							
							if($ligneMedMedoc->id_factureMedMedoc=="")
							{
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
							}else{
								$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
							}
							
								echo $uapPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
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
						<td colspan=4></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedMedocPayed.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedMedocPayed;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPricePayed.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPricePayed.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;									
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C'.(24+$i+$x+$y+$z).'', ''.$TotalMedMedoc.'')
							->setCellValue('D'.(24+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(24+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
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
			$TotalFinalPrice=0;
			$TotalFinalPatientPrice=0;
			$TotalFinalInsurancePrice=0;
		
		?>
		<table class="printPreview" cellspacing="0" style="margin:auto;">
			<thead> 
				<tr>
					<th style="width:20%"></th>
					<th style="width:20%;">Total balance</th>
					<th style="width:20%;">Patient balance</th>
					<th style="width:20%;">Insurance balance</th>
				</tr> 
			</thead> 

			<tbody>
				<tr style="text-align:center;display:none;">
					<td style="font-size: 13px; font-weight: bold;">Final Balance not payed</td>
					<td style="font-size: 13px; font-weight: bold;">
					<?php
						$TotalFinalPrice=$TotalGnlPrice + $TotalHosp;
					
					echo $TotalFinalPrice;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					
					<td style="font-size: 13px; font-weight: bold;">
					<?php
						$TotalFinalPatientPrice=$TotalGnlPatientPrice + $TotalPatientPriceHosp;
					
					echo $TotalFinalPatientPrice;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					
					<td style="font-size: 13px; font-weight: bold;">
					<?php
						$TotalFinalInsurancePrice=$TotalGnlInsurancePrice + $TotalInsurancePriceHosp;
					
					echo $TotalGnlInsurancePrice + $TotalInsurancePriceHosp;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
				</tr>
				
				
				<tr style="text-align:center;color:rgba(0, 0, 0, 0.5);display:none;">
					<td style="font-size: 13px; font-weight: bold;">Final Balance payed</td>
					<td style="font-size: 13px; font-weight: bold;">
					<?php
						$TotalFinalPrice=$TotalFinalPrice + $TotalGnlPricePayed;
					
					echo $TotalGnlPricePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;">
					<?php
						$TotalFinalPatientPrice=$TotalFinalPatientPrice + $TotalGnlPatientPricePayed;
					
					echo $TotalGnlPatientPricePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;">
					<?php 
						$TotalFinalInsurancePrice=$TotalFinalInsurancePrice + $TotalGnlInsurancePricePayed;
					
					echo $TotalGnlInsurancePricePayed;
					?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
				</tr>
				
				
				<tr style="text-align:center;">
					<td style="font-size: 18px; font-weight: bold;">Final Balance</td>
					<td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalPrice;
					?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
					<?php
						echo $TotalFinalPatientPrice;
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
		
}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
	/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&manager=CSC15A01&dateconsu=2015-09-19");
	file_put_contents("toPDF.html", $file); */

}
?>
</body>

</html>