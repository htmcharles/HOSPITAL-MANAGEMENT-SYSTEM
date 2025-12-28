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
		
		$lastDate=$_POST['annee'].'-'.$mois.'-'.$jours;
		
		$dateSortie = date('Y-m-d', strtotime($lastDate));
		$heureSortie=$_POST['heureout'].':'.$_POST['minuteout'].':00';
		
		$prixprestaHosp=$_POST['prixprestaHosp'];
		
		if($idassu==1)
		{
			$percentHosp=100;
		}else{
			$percentHosp=$_POST['percentHosp'];
		}
		$idHosp=$_GET['idhosp'];
		
		$updatepercent=$connexion->prepare('UPDATE patients_hosp ph SET ph.dateSortie=:dateSortie,ph.heureSortie=:heureSortie,ph.prixroom=:prixprestaHosp,ph.insupercent_hosp=:percentHosp WHERE ph.id_hosp=:idHosp');
		
		$updatepercent->execute(array(
		'dateSortie'=>$dateSortie,
		'heureSortie'=>$heureSortie,
		'prixprestaHosp'=>$prixprestaHosp,
		'percentHosp'=>$percentHosp,
		'idHosp'=>$idHosp
		
		))or die( print_r($connexion->errorInfo()));
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
						$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.insupercentServ='.$add[$i].',mc.prixprestationConsu='.$prixmc[$i].',mc.qteConsu='.$qteConsu[$i].',mc.id_assuServ='.$idassurance.' WHERE mc.id_medconsu='.$idmc[$i].'');

						if ($presta_assuServ !="prestations_PRIVATE" AND $add[$i]!=100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuServ.' p SET p.prixpresta='.$prixmc[$i].' WHERE p.id_prestation='.$idprestamc[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuServ.' p SET p.prixpresta='.$prixmc[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamc[$i].'');
							}
						}elseif($presta_assuServ == "prestations_PRIVATE"){
                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuServ.' p SET p.prixpresta='.$prixmc[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamc[$i].'');
						}elseif ($presta_assuServ !="prestations_PRIVATE" AND $add[$i]==100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuServ.' p SET p.prixpresta='.$prixmc[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamc[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuServ.' p SET p.prixpresta='.$prixmc[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamc[$i].'');
							}
						}

					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_consult_hosp mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.insupercentServ='.$add[$i].',mc.prixprestationConsu=0,mc.prixautreConsu='.$prixmc[$i].',mc.qteConsu='.$qteConsu[$i].',mc.id_assuServ='.$idassurance.' WHERE mc.id_medconsu='.$idmc[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationConsu FROM med_consult_hosp mco WHERE mco.id_prestationConsu=:idprestaConsu AND numero=:numPa AND id_factureMedKine=0 ');

				$valueGroupBy->execute(array(
					'idprestaConsu'=>$idprestamc[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_consult_hosp mc SET mc.prixprestationConsu='.$prixmco[$i].' WHERE mc.id_prestationConsu='.$idprestamc[$i].'');
				}
			}
			
		}
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc, patients_hosp p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_hospMed=:idconsu AND p.id_hosp=mc.id_hospMed AND mc.datehosp!="0000-00-00" AND mc.id_assuServ=:idassu ORDER BY mc.id_medconsu');
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idconsu'=>$_GET['idhosp']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
		$TotalMedConsultPayed = 0;

	

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
						$updatepercent=$connexion->query('UPDATE med_surge_hosp ms SET ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge='.$prixms[$i].',ms.qteSurge='.$qteSurge[$i].',ms.id_assuSurge='.$idassurance.' WHERE ms.id_medsurge='.$idms[$i].'');

						if ($presta_assuSurge !="prestations_PRIVATE" AND $addSurge[$i]!=100){
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuSurge.' p SET p.prixpresta='.$prixms[$i].' WHERE p.id_prestation='.$idprestams[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuSurge.' p SET p.prixpresta='.$prixms[$i].',statupresta=1 WHERE p.id_prestation='.$idprestams[$i].'');
							}
						}elseif($presta_assuSurge == "prestations_PRIVATE"){
                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuSurge.' p SET p.prixpresta='.$prixms[$i].',statupresta=1 WHERE p.id_prestation='.$idprestams[$i].'');
						}elseif ($presta_assuSurge !="prestations_PRIVATE" AND $addSurge[$i]==100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuSurge.' p SET p.prixpresta='.$prixms[$i].',statupresta=2 WHERE p.id_prestation='.$idprestams[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuSurge.' p SET p.prixpresta='.$prixms[$i].',statupresta=2 WHERE p.id_prestation='.$idprestams[$i].'');
							}
						}

					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_surge_hosp ms WHERE ms.id_medsurge='.$idms[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_surge_hosp ms SET ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge=0,ms.prixautrePrestaS='.$prixms[$i].',ms.qteSurge='.$qteSurge[$i].',ms.id_assuSurge='.$idassurance.' WHERE ms.id_medsurge='.$idms[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationSurge FROM med_surge_hosp ms WHERE ms.id_prestationSurge=:idprestaSurge AND numero=:numPa AND id_factureMedSurge=0 ');

				$valueGroupBy->execute(array(
					'idprestaSurge'=>$idprestams[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_surge_hosp ms SET ms.prixprestationSurge='.$prixms[$i].' WHERE ms.id_prestationSurge='.$idprestams[$i].'');
				}
			}
		}
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms, patients_hosp p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_hospSurge=:idhosp AND p.id_hosp=ms.id_hospSurge AND ms.id_assuSurge=:idassu ORDER BY ms.id_medsurge');
		$resultMedSurge->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();

		$TotalMedSurge = 0;
		$TotalMedSurgePayed = 0;

	
	

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
						$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.qteInf='.$qteInf[$i].',mi.id_assuInf='.$idassurance.' WHERE mi.id_medinf='.$idmi[$i].'');


						if ($presta_assuInf !="prestations_PRIVATE" AND $addInf[$i]!=100){
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuInf.' p SET p.prixpresta='.$prixmi[$i].' WHERE p.id_prestation='.$idprestami[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuInf.' p SET p.prixpresta='.$prixmi[$i].',statupresta=1 WHERE p.id_prestation='.$idprestami[$i].'');
							}
						}elseif($presta_assuInf == "prestations_PRIVATE"){
                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuInf.' p SET p.prixpresta='.$prixmi[$i].',statupresta=1 WHERE p.id_prestation='.$idprestami[$i].'');
						}elseif ($presta_assuInf !="prestations_PRIVATE" AND $addInf[$i]==100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuInf.' p SET p.prixpresta='.$prixmi[$i].',statupresta=2 WHERE p.id_prestation='.$idprestami[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuInf.' p SET p.prixpresta='.$prixmi[$i].',statupresta=2 WHERE p.id_prestation='.$idprestami[$i].'');
							}
						}

					}

				}else{

					$results=$connexion->query('SELECT *FROM med_inf_hosp mi WHERE mi.id_medinf='.$idmi[$i].'');

					$results->setFetchMode(PDO::FETCH_OBJ);

					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixautrePrestaM='.$prixmi[$i].',mi.qteInf='.$qteInf[$i].',mi.id_assuInf='.$idassurance.' WHERE mi.id_medinf='.$idmi[$i].'');

					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
					$valueGroupBy = $connexion->prepare('SELECT prixprestation FROM med_inf_hosp mco WHERE mco.id_prestation=:idpresta AND numero=:numPa AND id_factureMedInf=0 ');

				$valueGroupBy->execute(array(
					'idpresta'=>$idprestami[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_inf_hosp mi SET mi.prixprestation='.$prixmi[$i].' WHERE id_prestation='.$idprestami[$i].'');
				}
			}
		}

		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, patients_hosp p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_hospInf=:idhosp AND p.id_hosp=mi.id_hospInf AND mi.id_assuInf=:idassu ORDER BY mi.id_medinf');
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
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

				if($ligneNomAssu=$getAssuLab->fetch())
				{
					$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
				}
			}
			
			
			
			$idprestaml = array();
			$prixml = array();
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
						$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.qteLab='.$qteLab[$i].',ml.id_assuLab='.$idassurance.' WHERE ml.id_medlabo='.$idml[$i].'');

						if ($presta_assuLab !="prestations_PRIVATE" AND $addLab[$i]!=100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuLab.' p SET p.prixpresta='.$prixml[$i].' WHERE p.id_prestation='.$idprestaml[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuLab.' p SET p.prixpresta='.$prixml[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaml[$i].'');
							}
						}elseif($presta_assuLab == "prestations_PRIVATE"){
                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuLab.' p SET p.prixpresta='.$prixml[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaml[$i].'');
						}elseif ($presta_assuLab !="prestations_PRIVATE" AND $addLab[$i]==100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuLab.' p SET p.prixpresta='.$prixml[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaml[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuLab.' p SET p.prixpresta='.$prixml[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaml[$i].'');
							}
						}

					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_labo_hosp ml WHERE ml.id_medlabo='.$idml[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa=0,ml.prixautreExamen='.$prixml[$i].',ml.qteLab='.$qteLab[$i].',ml.id_assuLab='.$idassurance.' WHERE ml.id_medlabo='.$idml[$i].'');

					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationExa FROM med_labo_hosp mco WHERE mco.id_prestationExa=:idprestaExa AND numero=:numPa AND id_factureMedLabo=0 ');

				$valueGroupBy->execute(array(
					'idprestaExa'=>$idprestaml[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_labo_hosp ml SET ml.prixprestationExa='.$prixml[$i].' WHERE id_prestationExa='.$idprestaml[$i].'');
				}
			}
		}
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, patients_hosp p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_hospLabo=:idhosp AND p.id_hosp=ml.id_hospLabo AND ml.id_assuLab=:idassu ORDER BY ml.id_medlabo');
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
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

				if($ligneNomAssu=$getAssuRad->fetch())
				{
					$presta_assuRad='prestations_'.$ligneNomAssu->nomassurance;
				}
			}

			
			
			$idprestamr = array();
			$prixmr = array();
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
						$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.qteRad='.$qteRad[$i].',mr.id_assuRad='.$idassurance.' WHERE mr.id_medradio='.$idmr[$i].'');

						if ($presta_assuRad !="prestations_PRIVATE" AND $addRad[$i]!=100){
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuRad.' p SET p.prixpresta='.$prixmr[$i].' WHERE p.id_prestation='.$idprestamr[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuRad.' p SET p.prixpresta='.$prixmr[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamr[$i].'');
							}
						}elseif($presta_assuRad == "prestations_PRIVATE"){
                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuRad.' p SET p.prixpresta='.$prixmr[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamr[$i].'');
						}elseif ($presta_assuRad !="prestations_PRIVATE" AND $addRad[$i]==100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuRad.' p SET p.prixpresta='.$prixmr[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamr[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuRad.' p SET p.prixpresta='.$prixmr[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamr[$i].'');
							}
						}
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_radio_hosp mr WHERE mr.id_medradio='.$idmr[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixautreRadio='.$prixmr[$i].',mr.qteRad='.$qteRad[$i].',mr.id_assuRad='.$idassurance.' WHERE mr.id_medradio='.$idmr[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau 
				$valueGroupBy = $connexion->prepare('SELECT prixprestationRadio FROM med_radio_hosp mco WHERE mco.id_prestationRadio=:idprestaRadio AND numero=:numPa AND id_factureMedRadio=0 ');

				$valueGroupBy->execute(array(
					'idprestaRadio'=>$idprestamr[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_radio_hosp mr SET mr.prixprestationRadio='.$prixmr[$i].' WHERE id_prestationRadio='.$idprestamr[$i].'');
				}
			}
		}
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, patients_hosp p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_hospRadio=:idhosp AND p.id_hosp=mr.id_hospRadio AND mr.id_assuRad=:idassu ORDER BY mr.id_medradio');
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		$TotalMedRadioPayed = 0;



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
                        $updatepercent=$connexion->query('UPDATE med_kine_hosp mk SET mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine='.$prixmk[$i].',mk.qteKine='.$qteKine[$i].',mk.id_assuKine='.$idassurance.' WHERE mk.id_medkine='.$idmk[$i].'');

                        if ($presta_assuKine !="prestations_PRIVATE" AND $addKine[$i]!=100) {
                        	$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuKine.' p SET p.prixpresta='.$prixmk[$i].' WHERE p.id_prestation='.$idprestamk[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuKine.' p SET p.prixpresta='.$prixmk[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamk[$i].'');
							}
							
						}elseif($presta_assuKine == "prestations_PRIVATE"){
                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuKine.' p SET p.prixpresta='.$prixmk[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamk[$i].'');
						}elseif ($presta_assuKine !="prestations_PRIVATE" AND $addKine[$i]==100) {
							$status  = $ligne->statupresta;
                    		if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuKine.' p SET p.prixpresta='.$prixmk[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamk[$i].'');
							}else{
								$updateAssu=$connexion->query('UPDATE '.$presta_assuKine.' p SET p.prixpresta='.$prixmk[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamk[$i].'');
							}
						}
                    }

                }else{

                    $results=$connexion->query('SELECT *FROM med_kine_hosp mk WHERE mk.id_medkine='.$idmk[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->query('UPDATE med_kine_hosp mk SET mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine=0,mk.prixautrePrestaK='.$prixmk[$i].',mk.qteKine='.$qteKine[$i].',mk.id_assuKine='.$idassurance.' WHERE mk.id_medkine='.$idmk[$i].'');

                    }
                }
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationKine FROM med_Kine_hosp mco WHERE mco.id_prestationKine=:idprestaKine AND numero=:numPa AND id_factureMedKine=0 ');

				$valueGroupBy->execute(array(
					'idprestaKine'=>$idprestamk[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_Kine_hosp mco SET mco.prixprestationKine='.$prixmk[$i].' WHERE id_prestionKine='.$idprestamk[$i].'');
				}
            }
            
        }

        $resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk, patients_hosp p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_hospKine=:idhosp AND p.id_hosp=mk.id_hospKine AND mk.id_assuKine=:idassu ORDER BY mk.id_medKine');
        $resultMedKine->execute(array(
            'num'=>$numPa,
            'idassu'=>$idassurance,
            'idhosp'=>$_GET['idhosp']
        ));

        $resultMedKine->setFetchMode(PDO::FETCH_OBJ);

        $comptMedKine=$resultMedKine->rowCount();

        $TotalMedKine = 0;
        $TotalMedKinePayed = 0;




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
					$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.qteConsom='.$qteConsom[$i].',mco.id_assuConsom='.$idassurance.' WHERE mco.id_medconsom='.$idmco[$i].'');

					if ($presta_assuConsom !="prestations_PRIVATE" AND $addConsom[$i]!=100){

						$status  = $ligne->statupresta;
                    	if ($status!=0) {
							$updateAssu=$connexion->query('UPDATE '.$presta_assuConsom.' p SET p.prixpresta='.$prixmco[$i].' WHERE p.id_prestation='.$idprestaconsom[$i].'');
                    	}else{
                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuConsom.' p SET p.prixpresta='.$prixmco[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaconsom[$i].'');
                    	}
					}elseif($presta_assuConsom == "prestations_PRIVATE"){
                		$updateAssu=$connexion->query('UPDATE '.$presta_assuConsom.' p SET p.prixpresta='.$prixmco[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaconsom[$i].'');
					}elseif ($presta_assuConsom !="prestations_PRIVATE" AND $addConsom[$i]==100) {
						
						$status  = $ligne->statupresta;
                    	if ($status!=0) {
							$updateAssu=$connexion->query('UPDATE '.$presta_assuConsom.' p SET p.prixpresta='.$prixmco[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaconsom[$i].'');
                    	}else{
                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuConsom.' p SET p.prixpresta='.$prixmco[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaconsom[$i].'');
                    	}
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consom_hosp mco WHERE mco.id_medconsom='.$idmco[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0,mco.prixautreConsom='.$prixmco[$i].', mco.qteConsom='.$qteConsom[$i].',mco.id_assuConsom='.$idassurance.' WHERE mco.id_medconsom='.$idmco[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau 
				$valueGroupBy = $connexion->prepare('SELECT prixprestationConsom FROM med_consom_hosp mco WHERE mco.id_prestationConsom=:idprestaconsom AND numero=:numPa AND id_factureMedConsom=0 ');

				$valueGroupBy->execute(array(
					'idprestaconsom'=>$idprestaconsom[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_consom_hosp mco SET mco.prixprestationConsom='.$prixmco[$i].' WHERE id_prestationConsom='.$idprestaconsom[$i].'');
					/*$compt = $updateprice->rowCount();
					if ($compt) {
						echo 'OK';
					}*/ 
				}
			}
		}


		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, patients_hosp p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_hospConsom=:idhosp AND p.id_hosp=mco.id_hospConsom AND mco.id_assuConsom=:idassu ORDER BY mco.id_medconsom');
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
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

				if($ligneNomAssu=$getAssuMedoc->fetch())
				{
					$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
				}
			}
			
			

			$idprestamedoc = array();
			$prixmdo = array();
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
						$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.qteMedoc='.$qteMedoc[$i].',mdo.id_assuMedoc='.$idassurance.' WHERE mdo.id_medmedoc='.$idmdo[$i].'');

						if ($presta_assuMedoc !="prestations_PRIVATE" AND $addMedoc[$i]!=100) {

							$status  = $ligne->statupresta;
                        	if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuMedoc.' p SET p.prixpresta='.$prixmdo[$i].' WHERE p.id_prestation='.$idprestamedoc[$i].'');
                        	}else{
                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuMedoc.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamedoc[$i].'');
                        	}
							
						}elseif($presta_assuMedoc == "prestations_PRIVATE"){
                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuMedoc.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamedoc[$i].'');
						}elseif ($presta_assuMedoc !="prestations_PRIVATE" AND $addMedoc[$i]==100) {
							
							$status  = $ligne->statupresta;
                        	if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuMedoc.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamedoc[$i].'');
                        	}else{
                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuMedoc.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamedoc[$i].'');
                        	}
						}
					}
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixautreMedoc='.$prixmdo[$i].', mdo.qteMedoc='.$qteMedoc[$i].',mdo.id_assuMedoc='.$idassurance.' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
						
					}
				}
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationMedoc FROM med_medoc_hosp mdo WHERE mdo.id_prestationMedoc=:idprestaMedoc AND numero=:numPa AND mdo.id_factureMedMedoc=0 ');

				$valueGroupBy->execute(array(
					'idprestaMedoc'=>$idprestamedoc[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					$updateprice = $connexion->query('UPDATE med_medoc_hosp mdo SET mdo.prixprestationMedoc='.$prixmdo[$i].' WHERE id_prestationMedoc='.$idprestamedoc[$i].'');
				}
			}
		}
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, patients_hosp p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_hospMedoc=:idhosp AND p.id_hosp=mdo.id_hospMedoc AND mdo.id_assuMedoc=:idassu ORDER BY mdo.id_medmedoc');
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
		$TotalMedMedocPayed = 0;



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
                        $updatepercent=$connexion->query('UPDATE med_ortho_hosp mo SET mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho='.$prixmo[$i].',mo.qteOrtho='.$qteOrtho[$i].',mo.id_assuOrtho='.$idassurance.' WHERE mo.id_medortho='.$idmo[$i].'');

                        if ($presta_assuOrtho !="prestations_PRIVATE" AND $addOrtho[$i]!=100){
                        	$status  = $ligne->statupresta;
                        	if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuOrtho.' p SET p.prixpresta='.$prixmo[$i].' WHERE p.id_prestation='.$idprestamo[$i].'');
                        	}else{
                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuOrtho.' p SET p.prixpresta='.$prixmo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamo[$i].'');
                        	}
						}elseif($presta_assuOrtho == "prestations_PRIVATE"){
                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuOrtho.' p SET p.prixpresta='.$prixmo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamo[$i].'');
						}elseif ($presta_assuOrtho !="prestations_PRIVATE" AND $addOrtho[$i]==100) {
							$status  = $ligne->statupresta;
                        	if ($status!=0) {
								$updateAssu=$connexion->query('UPDATE '.$presta_assuOrtho.' p SET p.prixpresta='.$prixmo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamo[$i].'');
                        	}else{
                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuOrtho.' p SET p.prixpresta='.$prixmo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamo[$i].'');
                        	}
						}
                    }

                }else{

                    $results=$connexion->query('SELECT *FROM med_ortho_hosp mo WHERE mo.id_medortho='.$idmo[$i].'');

                    $results->setFetchMode(PDO::FETCH_OBJ);

                    if($ligne=$results->fetch())
                    {
                        $updatepercent=$connexion->query('UPDATE med_ortho_hosp mo SET mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho=0,mo.prixautrePrestaO='.$prixmo[$i].',mo.qteOrtho='.$qteOrtho[$i].',mo.id_assuOrtho='.$idassurance.' WHERE mo.id_medOrtho='.$idmo[$i].'');

                    }
                }
				/*Update the value Group by*/
				//Recuperer les valeurs du tableau
				$valueGroupBy = $connexion->prepare('SELECT prixprestationOrtho FROM med_ortho_hosp mo WHERE mo.id_prestationOrtho=:idprestaOrtho AND numero=:numPa AND mo.id_factureMedOrtho=0 ');

				$valueGroupBy->execute(array(
					'idprestaOrtho'=>$idprestamo[$i],
					'numPa'=>$numPa
				));

				$valueGroupBy->setFetchMode(PDO::FETCH_OBJ);

				while ($updatePriceGroupBy=$valueGroupBy->fetch()) {
					//print_r($updatePriceGroupBy);
					$updateprice = $connexion->query('UPDATE med_ortho_hosp mo SET mdo.prixprestationOrtho='.$prixmo[$i].' WHERE id_prestationOrtho='.$idprestamo[$i].'');
					/*$compt = $updateprice->rowCount();
					if ($compt) {
					 	echo 'OK';
					}*/ 
				}
            }
        }

        $resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, patients_hosp p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_hospOrtho=:idhosp AND p.id_hosp=mo.id_hospOrtho AND mo.id_assuOrtho=:idassu ORDER BY mo.id_medOrtho');
        $resultMedOrtho->execute(array(
            'num'=>$numPa,
            'idassu'=>$idassurance,
            'idhosp'=>$_GET['idhosp']
        ));

        $resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

        $comptMedOrtho=$resultMedOrtho->rowCount();

        $TotalMedOrtho = 0;
        $TotalMedOrthoPayed = 0;


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
				<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
			</td>
			
			<td style="text-align:center;">
				<h2 style="font-size:150%; font-weight:600;">Hospitalisation bill-exit n° <?php echo $idBilling;?></h2>
			</td>
			
			<td style="text-align:right;width:15%;">
			
				<form method="post" action="printBill_hospReport.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&datehosp=<?php echo $datehosp;?>&dateSortie=<?php if(isset($_GET['dateSortie'])){ echo $_GET['dateSortie'];}else{ echo $dateSortie;}?>&heureSortie=<?php if(isset($_GET['heureSortie'])){ echo $_GET['heureSortie'];}else{ echo $heureSortie;}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?>&updatebill=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td class="buttonBill" style="width:15%;">
				<a href="categoriesbill_hosp.php?inf=<?php echo 0;?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&previewprint=ok&sortie=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="patients1_hosp.php?numPa=<?php echo $_GET['num'];?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&createBill=<?php echo $createBill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&sortie=ok" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141);?></button>
				</a>
			</td>
		</tr>
	</table>
	
	
	<?php
		try
		{

			$TotalGnlPrice=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlPatientBalance=0;
			$TotalGnlInsurancePrice=0;

            $TotalGnlPricePayed=0;
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
						<th style="width:8%;text-align:center;" class="buttonBill">Price/day</th>
						<th style="width:8%;text-align:center;">Balance <?php echo $nomassurance;?></th>
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
								echo date('d-M-Y', strtotime($ligneHosp->dateEntree));
							?>
							</td>
							<td style="text-align:center;">
							<?php
							
							if(isset($_GET['dateSortie']))
							{
								$dateSortie=$_GET['dateSortie'];
							}
							
							if($ligneHosp->dateSortie>='0000-00-00')
							{
								echo date('d-M-Y', strtotime($dateSortie));
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
								$dateIn=strtotime($ligneHosp->dateEntree);
								$dateOut=strtotime($dateSortie);
								
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

							<td style="text-align:center;" class="buttonBill"><?php echo $ligneHosp->prixroom;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td style="text-align:center;">
							<?php
							$balanceHosp=$ligneHosp->prixroom * $nbrejrs;

							echo $balanceHosp;

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
                                $patientBalanceHosp = $patientPriceHosp;
                                echo $patientBalanceHosp.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td style="text-align:center;"><?php echo $insurancePriceHosp;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
								
							<td class="buttonBill">
							<?php

							if($ligneHosp->statusPaHosp!=1 )
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
					<?php
					}

					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(9+$i).'', ''.$balanceHosp.'')
								->setCellValue('D'.(9+$i).'', ''.$patientPriceHosp.'')
								->setCellValue('E'.(9+$i).'', ''.$insurancePriceHosp.'');
			
			/*}catch(){
				echo "Erreur:".$e->getMessage().'<br/>';
				echo "Numero:".$e->getCode();
			}*/
		

			
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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" >Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" >Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
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
					<tr style="text-align:center;">
						
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
						<td></td>
						<td>
						<?php
							$qteSurge=$ligneMedSurge->qteSurge;
							echo $qteSurge;
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
                            $patientBalance = $patientPrice;
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
					<tr style="text-align:center;">
						<td><?php echo $ligneMedSurge->datesoins;?></td>
						<td style="text-align:left;">
						<?php
						$nameprestaMedSurge=$ligneMedSurge->autrePrestaM;
						
						echo $ligneMedSurge->autrePrestaM;
						?>
						</td>
						<td></td>
						<td>
						<?php
							$qteSurge=$ligneMedSurge->qteSurge;
							echo $qteSurge;
						?>
						</td>
						
						<td>
						<?php
							$prixPresta = $ligneMedSurge->prixautrePrestaS;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<?php
                            $balance=$prixPresta*$qteSurge;
                            $patientPrice=($balance * $billpercent)/100;
                            $patientBalance = $patientPrice;
                            $uapPrice= $balance - $patientPrice;


        if($ligneMedSurge->id_factureMedSurge=="" OR $ligneMedSurge->id_factureMedSurge==0)
        {
            $TotalMedSurge = $TotalMedSurge + $balance;
            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
            $TotaluapPrice= $TotaluapPrice + $uapPrice;
        }else{
            $TotalMedSurgePayed= $TotalMedSurgePayed + $balance;
            $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
            $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
            $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
        }

						?>
						<td>
						<?php
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

						?>
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
						<td colspan=5 ></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedSurge.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedSurgePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td ></td>

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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
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
					<tr style="text-align:center;">

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
						<td></td>
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

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
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
                            $patientBalance = $patientPrice;
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
					<tr style="text-align:center;">
						<td><?php echo $ligneMedInf->datesoins;?></td>
						<td style="text-align:left;">
						<?php
						$nameprestaMedInf=$ligneMedInf->autrePrestaM;

						echo $ligneMedInf->autrePrestaM;
						?>
						</td>
						<td></td>
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

						if($ligneMedInf->id_factureMedInf=="" OR $ligneMedInf->id_factureMedInf==0)
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
                            $patientBalance = $patientPrice;
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
						<td colspan=5 ></td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedInf.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedInfPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td ></td>

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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
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
					<tr style="text-align:center;">
					
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
						<td></td>
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

						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
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
                            $patientBalance = $patientPrice;
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
					<tr style="text-align:center;">
						<td><?php echo $ligneMedLabo->datehosp;?></td>
						<td style="text-align:left;">
						<?php
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
							echo $ligneMedLabo->autreExamen;
							
						?>
						</td>
						<td></td>
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

						if($ligneMedLabo->id_factureMedLabo=="" OR $ligneMedLabo->id_factureMedLabo==0)
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
                            $patientBalance = $patientPrice;
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
						<td colspan=5></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedLabo.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedLaboPayed;

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td ></td>
						
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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" >Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
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
					<tr style="text-align:center;">
					
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
						<td></td>
						<td>
						<?php
							$qteRad=$ligneMedRadio->qteRad;
							echo $qteRad;
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
								$balance=$prixPresta*$qteRad;

								echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';


							if($ligneMedRadio->id_factureMedRadio=="" OR $ligneMedRadio->id_factureMedRadio==0)
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
                            $patientBalance = $patientPrice;
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
					<tr style="text-align:center;">
						<td><?php echo $ligneMedRadio->datehosp;?></td>
						<td style="text-align:left;">
						<?php
						$nameprestaMedRadio=$ligneMedRadio->autreRadio;
						
						echo $ligneMedRadio->autreRadio;
						?>
						</td>
						<td></td>
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
                            $patientBalance = $patientPrice;
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
						<td colspan=5 ></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedRadio.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;

								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedRadioPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td ></td>
						
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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" >Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
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
                            <tr style="text-align:center;">

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
                                <td></td>
                                <td>
                                    <?php
                                    $qteKine=$ligneMedKine->qteKine;
                                    echo $qteKine;
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    $prixPresta = $ligneMedKine->prixprestationKine;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                    <?php
                                    $balance=$prixPresta*$qteKine;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedKine->id_factureMedKine=="" OR $ligneMedKine->id_factureMedKine==0)
                                    {
                                        $TotalMedKine = $TotalMedKine + $balance;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedKinePayed= $TotalMedKinePayed + $balance;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    ?>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
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
                            <tr style="text-align:center;">
                                <td><?php echo $ligneMedKine->datesoins;?></td>
                                <td style="text-align:left;">
                                    <?php
                                    $nameprestaMedKine=$ligneMedKine->autrePrestaM;

                                    echo $ligneMedKine->autrePrestaM;
                                    ?>
                                </td>
                                <td></td>
                                <td>
                                    <?php
                                    $qteKine=$ligneMedKine->qteKine;
                                    echo $qteKine;
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    $prixPresta = $ligneMedKine->prixautrePrestaK;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                    <?php
                                    $balance=$prixPresta*$qteKine;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


        if($ligneMedKine->id_factureMedKine=="" OR $ligneMedKine->id_factureMedKine==0)
        {
            $TotalMedKine = $TotalMedKine + $balance;
            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
            $TotaluapPrice= $TotaluapPrice + $uapPrice;
        }else{
            $TotalMedKinePayed= $TotalMedKinePayed + $balance;
            $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
            $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
            $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
        }

                                    ?>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
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
                        <td colspan=5 ></td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedKine.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
                            $TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedKinePayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>


                        <td ></td>

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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" >Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" >Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" >Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
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
						<tr style="text-align:center;" >
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
							<td></td>
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

								<?php
								$balance=$prixPresta*$qteConsom;
								$patientPrice=($balance * $billpercent)/100;
								$patientBalance = $patientPrice;
								$uapPrice= $balance - $patientPrice;


	if($ligneMedConsom->id_factureMedConsom=="" OR $ligneMedConsom->id_factureMedConsom==0)
	{
		$TotalMedConsom = $TotalMedConsom + $balance;
		$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
		$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
		$TotaluapPrice= $TotaluapPrice + $uapPrice;
	}else{
		$TotalMedConsomPayed= $TotalMedConsomPayed + $balance;
		$TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
		$TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
		$TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
	}

								?>

							<td>
								<?php
								echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

								?>
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
						<tr style="text-align:center;" >
						
							<td><?php echo $ligneMedConsom->datehosp;?></td>
							<td style="text-align:left;">
							<?php
								$nameprestaMedConsom=$ligneMedConsom->autreConsom;
								echo $nameprestaMedConsom;
							?>
							</td>
							<td></td>
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
                                $patientBalance = $patientPrice;
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
						<td colspan=5 ></td>

						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedConsom.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsomPayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

                        <td ></td>
						
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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
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
							<td></td>
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

							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
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
                                $patientBalance = $patientPrice;
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
						<tr style="text-align:center;">
						
							<td><?php echo $ligneMedMedoc->datehosp;?></td>
							<td style="text-align:left;">
							<?php
								$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
								echo $nameprestaMedMedoc;
							?>
							</td>
							<td></td>
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
                                $patientPrice=($balance * $billpercent)/100;
                                $patientBalance = $patientPrice;



                            echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							
							if($ligneMedMedoc->id_factureMedMedoc=="" OR $ligneMedMedoc->id_factureMedMedoc==0)
							{
								$TotalMedMedoc=$TotalMedMedoc + $balance;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
							}else{
								$TotalMedMedocPayed=$TotalMedMedocPayed + $balance;
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
						<td colspan=5 ></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php
								echo $TotalMedMedoc.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedMedocPayed;

							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td ></td>
						
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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" >Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;" >Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)" >Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
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
                            <tr style="text-align:center;">

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
                                <td></td>
                                <td>
                                    <?php
                                    $qteOrtho=$ligneMedOrtho->qteOrtho;
                                    echo $qteOrtho;
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    $prixPresta = $ligneMedOrtho->prixprestationOrtho;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                    <?php
                                    $balance=$prixPresta*$qteOrtho;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedOrtho->id_factureMedOrtho=="" OR $ligneMedOrtho->id_factureMedOrtho==0)
                                    {
                                        $TotalMedOrtho = $TotalMedOrtho + $balance;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedOrthoPayed= $TotalMedOrthoPayed + $balance;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    ?>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
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
                            <tr style="text-align:center;" >
                                <td><?php echo $ligneMedOrtho->datesoins;?></td>
                                <td style="text-align:left;">
                                    <?php
                                    $nameprestaMedOrtho=$ligneMedOrtho->autrePrestaM;

                                    echo $ligneMedOrtho->autrePrestaM;
                                    ?>
                                </td>
                                <td></td>
                                <td>
                                    <?php
                                    $qteOrtho=$ligneMedOrtho->qteOrtho;
                                    echo $qteOrtho;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $prixPresta = $ligneMedOrtho->prixautrePrestaO;
                                    echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
                                    ?>
                                </td>

                                    <?php
                                    $balance=$prixPresta*$qteOrtho;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedOrtho->id_factureMedOrtho=="" OR $ligneMedOrtho->id_factureMedOrtho==0)
                                    {
                                        $TotalMedOrtho = $TotalMedOrtho + $balance;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedOrthoPayed= $TotalMedOrthoPayed + $balance;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    ?>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
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
                        <td colspan=5></td>

                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedOrtho.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
                            $TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedOrthoPayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>


                        <td ></td>

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
                        <th style="width:8%;background:rgba(0, 0, 0, 0.05)" >Date</th>
                        <th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
                        <th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
                        <th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
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
                            <tr style="text-align:center;" >

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
                                <td></td>
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

                                    <?php
                                    $balance=$prixPresta*$qteConsu;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedConsult->id_factureMedConsu=="" OR $ligneMedConsult->id_factureMedConsu==0)
                                    {
                                        $TotalMedConsult = $TotalMedConsult + $balance;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedConsultPayed= $TotalMedConsultPayed + $balance;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    ?>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
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
                            <tr style="text-align:center;" >

                                <td><?php echo $ligneMedConsult->datehosp;?></td>
                                <td style="text-align:left;">
                                    <?php

                                    $nameprestaMedConsult=$ligneMedConsult->autreConsu;
                                    echo $ligneMedConsult->autreConsu;
                                    ?>
                                </td>
                                <td></td>
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

                                    <?php
                                    $balance=$prixPresta*$qteConsu;
                                    $patientPrice=($balance * $billpercent)/100;
                                    $patientBalance = $topupPrice + $patientPrice;
                                    $uapPrice= $balance - $patientPrice;


                                    if($ligneMedConsult->id_factureMedConsu=="" OR $ligneMedConsult->id_factureMedConsu==0)
                                    {
                                        $TotalMedConsult = $TotalMedConsult + $balance;
                                        $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                        $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                        $TotaluapPrice= $TotaluapPrice + $uapPrice;
                                    }else{
                                        $TotalMedConsultPayed= $TotalMedConsultPayed + $balance;
                                        $TotalpatientPricePayed=$TotalpatientPricePayed + $patientPrice;
                                        $TotalpatientBalancePayed = $TotalpatientBalancePayed + $patientBalance;
                                        $TotaluapPricePayed= $TotaluapPricePayed + $uapPrice;
                                    }

                                    ?>

                                <td>
                                    <?php
                                    echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';

                                    ?>
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
                        <td colspan=5 ></td>
                        <td style="font-size: 13px; font-weight: bold;">
                            <?php
                            echo $TotalMedConsult.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
                            $TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsultPayed;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>


                        <td ></td>

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
			$TotalFinalPrice=0;
			$TotalFinalPatientPrice=0;
			$TotalFinalPatientBalance=0;
			$TotalFinalInsurancePrice=0;

			$TotalFinalPricePayed=0;
			$TotalFinalPatientPricePayed=0;
			$TotalFinalPatientBalancePayed=0;
			$TotalFinalInsurancePricePayed=0;

		?>
		<table class="printPreview" cellspacing="0" style="margin:auto;">
			<thead>
				<tr>
					<th style="width:auto;"></th>
					<th style="width:15%;">Total <?php echo $nomassurance;?></th>
					<th style="width:15%;">Total Patient <?php echo '('.$billpercent.'%)';?></th>
					<th style="width:15%;">Patient Balance</th>
					<th style="width:10%;">Insurance</th>
				</tr>
			</thead>

			<tbody>
			
				<?php
					$TotalFinalPrice=$TotalGnlPrice + $balanceHosp;
					$TotalFinalPatientPrice=$TotalGnlPatientPrice + $patientPriceHosp;
					$TotalFinalPatientBalance=$TotalGnlPatientBalance + $patientBalanceHosp;
					$TotalFinalInsurancePrice=$TotalGnlInsurancePrice + $insurancePriceHosp;
					
					
					$TotalFinalPricePayed=$TotalGnlPricePayed + $balanceHosp;
					$TotalFinalPatientPricePayed=$TotalGnlPatientPricePayed + $patientPriceHosp;
					$TotalFinalPatientBalancePayed=$TotalGnlPatientBalancePayed + $patientBalanceHosp;
					$TotalFinalInsurancePricePayed=$TotalGnlInsurancePricePayed + $insurancePriceHosp;

			       
		
		
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
						echo $TotalFinalPrice;
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
			
			
			/*----------Update Rooms----------------*/
			
			if($_GET['numlit']=='1')
			{
				$statusA=0;
				
				$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusA=:statusA WHERE r.numroom=:numroom');

				$updateIdRoomHosp->execute(array(
				'statusA'=>$statusA,
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
			
			echo '<script text="text/javascript">document.location.href="printBill_hospReport.php?num='.$_GET['num'].'&idassu='.$_GET['idassu'].'&cashier='.$_SESSION['codeCash'].'&idhosp='.$_GET['idhosp'].'&datehosp='.$_GET['datehosp'].'&dateSortie='.$_GET['dateSortie'].'&heureSortie='.$_GET['heureSortie'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&idbill='.$idBilling.'&numroom='.$_GET['numroom'].'&idresto='.$_GET['idresto'].'&idtourdesalle='.$_GET['idtourdesalle'].'&numlit='.$_GET['numlit'].'&finishbtn=ok"</script>';
			
		
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