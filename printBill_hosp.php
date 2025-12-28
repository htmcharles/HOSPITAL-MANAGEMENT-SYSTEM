s<?php
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
	<title><?php echo 'Bill#'.$idBilling; ?></title>

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
	
	<div class="account-container" style="margin: 10px auto auto; width:95%; border: 1px solid #ccc; background:#fff; padding:5px; border-radius:3px; font-size:80%;">
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

			

		$userinfo = '<table style="width:100%;">
			
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
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
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
			
			for($i=0;$i<sizeof($add);$i++)
			{
				// echo $add[$i].'_'.$idmc[$i].'_('.$prixmc[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation='.$idprestamc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.insupercentServ='.$add[$i].',mc.prixprestationConsu='.$prixmc[$i].',mc.prixautreConsu=0 WHERE mc.id_medconsu='.$idmc[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_consult_hosp mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consult_hosp mc SET mc.insupercentServ='.$add[$i].',mc.prixprestationConsu=0,mc.prixautreConsu='.$prixmc[$i].' WHERE mc.id_medconsu='.$idmc[$i].'');
						
					}
				}
			}
		}
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc, patients_hosp p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_hospMed=:idconsu AND p.id_hosp=mc.id_hospMed AND mc.datehosp!="0000-00-00" AND mc.id_assuServ=:idassu AND mc.id_factureMedConsu="" ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idconsu'=>$_GET['idhosp']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
	
	
	
		/*-------Requête pour AFFICHER med_inf_hosp-----------*/
	
					
		if(isset($_POST['idprestaInf']))
		{
			
			$idassuInf=$idassu;
			
			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsu->execute(array(
				'idassu'=>$idassuInf
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
				{
					$presta_assuInf='prestations_'.$ligneNomAssu->nomassurance;
				}
			}


			$idprestami = array();
			$prixmi = array();
			$addInf = array();
			$idmi = array();
			$autremi = array();

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
			
			
			for($i=0;$i<sizeof($addInf);$i++)
			{
				
				// echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation='.$idprestami[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.prixautrePrestaM=0 WHERE mi.id_medinf='.$idmi[$i].'');
										
				}else{
				
					$results=$connexion->query('SELECT *FROM med_inf_hosp mi WHERE mi.id_medinf='.$idmi[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf_hosp mi SET mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixautrePrestaM='.$prixmi[$i].' WHERE mi.id_medinf='.$idmi[$i].'');
						
					}
				}
				
			}
		}
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, patients_hosp p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_hospInf=:idhosp AND p.id_hosp=mi.id_hospInf AND mi.id_assuInf=:idassu AND mi.id_factureMedInf="" ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		
	
	
		/*-------Requête pour AFFICHER med_labo_hosp-----------*/
	
					
		if(isset($_POST['idprestaLab']))
		{
			$idassuLab=$idassu;

			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsu->execute(array(
				'idassu'=>$idassuLab
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
				{
					$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
				}
			}
	
							
			$idprestaml = array();
			$prixml = array();
			$addLab = array();
			$idml = array();
			$autreml = array();


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
			
			for($i=0;$i<sizeof($addLab);$i++)
			{
				
				// echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation='.$idprestaml[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.prixautreExamen=0 WHERE ml.id_medlabo='.$idml[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_labo_hosp ml WHERE ml.id_medlabo='.$idml[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo_hosp ml SET ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa=0,ml.prixautreExamen='.$prixml[$i].' WHERE ml.id_medlabo='.$idml[$i].'');
						
					}
				}
			}
		}
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, patients_hosp p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_hospLabo=:idhosp AND p.id_hosp=ml.id_hospLabo AND ml.id_assuLab=:idassu AND ml.id_factureMedLabo="" ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$numPa,	 
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();			
		
		$TotalMedLabo = 0;
	
	
	
	
		/*-------Requête pour AFFICHER med_radio_hosp-----------*/
	
					
		if(isset($_POST['idprestaRad']))
		{
			$idassuRad=$idassu;

			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsu->execute(array(
				'idassu'=>$idassuRad
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
				{
					$presta_assuRad='prestations_'.$ligneNomAssu->nomassurance;
				}
			}
			
							
										
			$idprestamr = array();
			$prixmr = array();
			$addRad = array();
			$idmr = array();
			$autremr = array();


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
			
			for($i=0;$i<sizeof($addRad);$i++)
			{
				
				// echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation='.$idprestamr[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.prixautreRadio=0 WHERE mr.id_medradio='.$idmr[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_radio_hosp mr WHERE mr.id_medradio='.$idmr[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio_hosp mr SET mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixautreRadio='.$prixmr[$i].' WHERE mr.id_medradio='.$idmr[$i].'');
						
					}
				}
			}
		}
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, patients_hosp p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_hospRadio=:idhosp AND p.id_hosp=mr.id_hospRadio AND mr.id_assuRad=:idassu AND mr.id_factureMedRadio="" ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'num'=>$numPa,	
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();			
		
		$TotalMedRadio = 0;
	
	
	
		/*-------Requête pour AFFICHER med_consom_hosp-----------*/
					
		if(isset($_POST['idprestaConsom']))
		{
			$idassuConsom=$idassu;
			
			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsu->execute(array(
				'idassu'=>$idassuConsom
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
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
				
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuConsom.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation='.$idprestaconsom[$i].'');
					
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.prixautreConsom=0,mco.qteConsom='.$qteConsom[$i].' WHERE mco.id_medconsom='.$idmco[$i].'');
						
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_consom_hosp mco WHERE mco.id_medconsom='.$idmco[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consom_hosp mco SET mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0,mco.prixautreConsom='.$prixmco[$i].', mco.qteConsom='.$qteConsom[$i].' WHERE mco.id_medconsom='.$idmco[$i].'');

					}
				}				
			}
		}
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, patients_hosp p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_hospConsom=:idhosp AND p.id_hosp=mco.id_hospConsom AND mco.id_assuConsom=:idassu AND mco.id_factureMedConsom="" ORDER BY mco.id_medconsom');		
		$resultMedConsom->execute(array(
		'num'=>$numPa,	 
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		
	
	
		/*-------Requête pour AFFICHER med_medoc_hosp-----------*/
	
							
		if(isset($_POST['idprestaMedoc']))
		{
			$idassuMedoc=$idassu;
	
			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsu->execute(array(
				'idassu'=>$idassuMedoc
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
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
				
			
				$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuMedoc.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation='.$idprestamedoc[$i].'');
					
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
				
					$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.prixautreMedoc=0,mdo.qteMedoc='.$qteMedoc[$i].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
						
					
				}else{
					
					$results=$connexion->query('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
						
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc_hosp mdo SET mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixautreMedoc='.$prixmdo[$i].', mdo.qteMedoc='.$qteMedoc[$i].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
						
					}
				}
			}
		}
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, patients_hosp p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_hospMedoc=:idhosp AND p.id_hosp=mdo.id_hospMedoc AND mdo.id_assuMedoc=:idassu AND mdo.id_factureMedMedoc="" ORDER BY mdo.id_medmedoc');		
		$resultMedMedoc->execute(array(
		'num'=>$numPa,	
		'idassu'=>$idassurance,
		'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();			
		
		$TotalMedMedoc = 0;
	
	?>
	
	<table style="width:100%; margin:0 auto -10px;"> 
		<tr> 
			<td style="text-align:left; width:33%;">
				<h4><?php echo $annee;?></h4>
			</td>
			
			<td style="text-align:center; width:33%;">
				<h2 style="font-size:150%; font-weight:600;">Hospitalisation Bill n° <?php echo $idBilling;?></h2>
			</td>
			
			<td style="text-align:right;width:30%;">
			
				<form method="post" action="printBill_hosp.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&datehosp=<?php echo $datehosp;?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?>&updatebill=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td class="buttonBill">
				<a href="categoriesbill_hosp.php?inf=<?php echo 0;?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&id_uM=<?php echo $_GET['id_uM'];?>&datehosp=<?php echo $_GET['datehosp'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&previewprint=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="categoriesbill_hosp.php?inf=<?php echo 0;?>&num=<?php echo $_GET['num'];?><?php if(isset($_GET['datehosp'])){ echo '&datehosp='.$_GET['datehosp'];}?><?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['id_uM'])){ echo '&id_uM='.$_GET['id_uM'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?>&createBill=<?php echo $createBill;?>&previewprint=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&finishbtn=ok" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
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
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 10px;"> 
				<thead> 
					<tr>
						<th style="width:5%;text-align:center;">Room</th>
						<th style="width:20%;text-align:center;">Type</th>
						<th style="width:10%;text-align:center;">Date In</th>
						<th style="width:10%;text-align:center;">Date Out</th>
						<th style="width:5%;text-align:center;">Days</th>		
					</tr> 
				</thead>

				<tbody>
				<?php
				
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
								echo '<td style="text-align:center;">'.$lignePresta->namepresta.'</td>';
							}else{	
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaHosp=$lignePresta->nompresta;
									echo '<td style="text-align:center;">'.$lignePresta->nompresta.'</td>';
				
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
							if($ligneHosp->dateSortie>='0000-00-00')
							{
								echo '---------';
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$dateIn=strtotime($ligneHosp->dateEntree);
							$todaydate=time();
							
							$datediff= abs($todaydate - $dateIn);
							
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
							
							</td>
							<?php
							$balanceHosp=$ligneHosp->prixroom * $nbrejrs;
							$TotalHosp=$TotalHosp + $balanceHosp;
							
							$patientPrice=($balanceHosp * $billpercent)/100;
							$TotalPatientPriceHosp=$TotalPatientPriceHosp + $patientPrice;
							
							$uapPrice= $balanceHosp - $patientPrice;
							
							$TotalInsurancePriceHosp = $TotalInsurancePriceHosp + $uapPrice;
							?>
							
							
						</tr>
				<?php
						}
						
					}
					
					$arrayConsult[$i][0]=$nameprestaHosp;
					$arrayConsult[$i][1]=$prixPresta;
					$arrayConsult[$i][2]=$patientPrice;
					$arrayConsult[$i][3]=$uapPrice;
					
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
						<th style="width:20%" colspan=8>Nursing Care</th>
					</tr>
					<tr>
						<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="text-align:left; background:rgba(0, 0, 0, 0.05)">Name</th>
						<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
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

	
					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
					$resultPresta->execute(array(
						'prestaId'=>$ligneMedInf->id_prestation
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())
					{
					?>				
					<tr>
						<td><?php echo $ligneMedInf->datesoins;?></td>
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
							$prixPresta = $ligneMedInf->prixprestation;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
							$balance=$prixPresta*$qteInf;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
							$TotalMedInf=$TotalMedInf + $balance;
						?>				
						</td>

						<td><?php echo $ligneMedInf->insupercentInf;?>%</td>						
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPrice= $balance - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					<?php
					}
					
					if($ligneMedInf->id_prestation==NULL AND $ligneMedInf->prixautrePrestaM!=0)
					{
					?>				
	
					<tr>
						<td><?php echo $ligneMedInf->datehosp;?></td>
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
							$prixPresta = $ligneMedInf->prixautrePrestaM;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
							$balance=$prixPresta*$qteInf;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
							$TotalMedInf=$TotalMedInf + $balance;
						?>				
						</td>

						<td><?php echo $ligneMedInf->insupercentInf;?>%</td>						
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						<?php 
							$uapPrice= $balance - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
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
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalMedInf.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
												
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
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
						<th style="width:20%" colspan=8>Labs</th>
					</tr>
					<tr>
						<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="text-align:left; background:rgba(0, 0, 0, 0.05)">Name</th>
						<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
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


					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedLabo->id_prestationExa
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
					?>						
					<tr>
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
							$prixPresta = $ligneMedLabo->prixprestationExa;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
							$balance=$prixPresta*$qteLab;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
							$TotalMedLabo=$TotalMedLabo + $balance;
						?>				
						</td>

						<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
						
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPrice= $balance - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					<?php
					}
					
					if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->prixautreExamen!=0)
					{
					?>
					<tr>
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
							$prixPresta = $ligneMedLabo->prixautreExamen;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>
						
						<td>
						<?php
							$balance=$prixPresta*$qteLab;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
							$TotalMedLabo=$TotalMedLabo + $balance;
						?>				
						</td>

						<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
					
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPrice= $balance - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					<?php
					}
							
						$arrayMedLabo[$z][0]=$nameprestaMedLabo;
						$arrayMedLabo[$z][1]=$balance;
						$arrayMedLabo[$z][2]=$patientPrice;
						$arrayMedLabo[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedLabo,'','B'.(18+$i+$x+$y).'');
					}
					?>
					<tr style="text-align:center;">
						<td colspan=4></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalMedLabo.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
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
						<th style="width:20%" colspan=8>Radiologie</th>
					</tr>
					<tr>
						<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="text-align:left; background:rgba(0, 0, 0, 0.05)">Name</th>
						<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
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


			
					$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedRadio->id_prestationRadio
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
					?>
					<tr>
					
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
							$prixPresta = $ligneMedRadio->prixprestationRadio;
							echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
						?>
						</td>

						<td>
						<?php
							$balance=$prixPresta*$qteRad;
							
							echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							
							$TotalMedRadio=$TotalMedRadio + $balance;
						?>				
						</td>

						<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
						
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPrice= $balance - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					<?php
					}
					
					if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->prixautreRadio!=0)
					{
					?>
					<tr>
					
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
							
							$TotalMedRadio=$TotalMedRadio + $balance;
						?>				
						</td>
	
						<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
					
						<td>
						<?php 
							$patientPrice=($balance * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						<?php 
							$uapPrice= $balance - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
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
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalMedRadio.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
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
						<th style="width:20%" colspan=8>Consommables</th>
					</tr>
					<tr>
						<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="text-align:left; background:rgba(0, 0, 0, 0.05)">Name</th>
						<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Insurance balance</th>
					</tr>
					
				</thead> 

				<tbody>
			<?php
			
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
							
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
						?>
							<tr style="text-align:center;">
								
								<td><?php echo $ligneMedConsom->datehosp;?></td>
								<td style="text-align:left">
								<?php
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedConsom=$lignePresta->namepresta;
										echo ucwords(strtolower(strip_tags($lignePresta->namepresta)));
									
									}else{
									
										$nameprestaMedConsom=$lignePresta->nompresta;
										echo ucwords(strtolower(strip_tags($lignePresta->nompresta)));
								
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
									echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
								?>
								</td>
								
								<td>
								<?php
									$balance=$prixPresta*$qteConsom;
									
									echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
									
									$TotalMedConsom=$TotalMedConsom + $balance;
								?>				
								</td>
								
								<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
								
								<td>
								<?php 
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
									echo $patientPrice;
									
								?><span style="font-size:50%; font-weight:normal;">Rwf</span>
								</td>
								
								<td>
								<?php
									$uapPrice= $balance - $patientPrice;									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;								
									echo $uapPrice;
									
								?><span style="font-size:50%; font-weight:normal;">Rwf</span>
								</td>
								
							</tr>						
						<?php
						}
						
						if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->prixautreConsom!=0)
						{
						?>
							<tr style="text-align:center;">
								<td><?php echo $ligneMedConsom->datehosp;?></td>
								<td style="text-align:left">
								<?php
									$nameprestaMedConsom=$ligneMedConsom->autreConsom;
									echo ucwords(strtolower(strip_tags($nameprestaMedConsom)));
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
									echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
									
								?>				
								</td>
								
								<td>
								<?php
									$balance=$prixPresta*$qteConsom;
									
									echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
									
									$TotalMedConsom=$TotalMedConsom + $balance;
								?>				
								</td>
								
								<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
							
								<td>
								<?php 
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
									echo $patientPrice;
									
								?><span style="font-size:50%; font-weight:normal;">Rwf</span>
								</td>
								
								<td>
								<?php
									$uapPrice= $balance - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
									echo $uapPrice;
									
								?><span style="font-size:50%; font-weight:normal;">Rwf</span>
								</td>
							</tr>
			<?php
						}
							
						$arrayMedConsom[$z][0]=$nameprestaMedConsom;
						$arrayMedConsom[$z][1]=$balance;
						$arrayMedConsom[$z][2]=$patientPrice;
						$arrayMedConsom[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedConsom,'','B'.(21+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td colspan=4></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalMedConsom.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
			// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)
								
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
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
						<th style="width:20%" colspan=8>Medicaments</th>
					</tr>
					<tr>
						<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
						<th style="text-align:left; background:rgba(0, 0, 0, 0.05)">Name</th>
						<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance</th>
						<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
						<th style="width:15%;background:rgba(0, 0, 0, 0.05)">Insurance balance</th>
					</tr>
				</thead> 

				<tbody>
			<?php
			
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
							<td style="text-align:left">
						<?php
								if($lignePresta->namepresta!='')
								{
									$nameprestaMedMedoc=$lignePresta->namepresta;
									echo ucwords(strtolower(strip_tags($lignePresta->namepresta)));
								
								}else{
								
									$nameprestaMedMedoc=$lignePresta->nompresta;
									echo ucwords(strtolower(strip_tags($lignePresta->nompresta)));
							
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
								echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>				
							</td>
							
							<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
							
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
								echo $patientPrice;
								
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
								$uapPrice= $balance - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;								
								echo $uapPrice;
								
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
							</td>
							
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
					{
					?>
						<tr style="text-align:center;">
						
							<td><?php echo $ligneMedMedoc->datehosp;?></td>
							<td  style="text-align:left">
							<?php
								$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
								echo ucwords(strtolower(strip_tags($nameprestaMedMedoc)));
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
								echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
								
							?>				
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>				
							</td>
							
							<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
						
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
								echo $patientPrice;
								
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
								$uapPrice= $balance - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice;
								
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
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
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalMedMedoc.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
							?><span style="font-size:50%; font-weight:normal;">Rwf</span>
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

	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:5px; border-radius:3px; font-size:85%;">
	
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
				<tr style="text-align:center;">
					<td style="font-size: 110%; font-weight: bold;">Final Balance</td>
					<td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPrice;?><span style="font-size:50%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPatientPrice;?><span style="font-size:50%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlInsurancePrice;?><span style="font-size:50%; font-weight:normal;">Rwf</span></td>
				</tr>
			</tbody>
		</table>
		
	</div>
	
	<div class="account-container" style="margin:10px auto 20px; width:90%; background:#fff;border-radius:3px; font-size:85%;">
	
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
						
			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$IdBill=str_replace('/', '_', $idBilling);
			
			// $objWriter->save('C:/wamp/www/stjean/BillFiles/Bill#'.$IdBill.'.xlsx');
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			// createBN();
			
			echo '<script text="text/javascript">document.location.href="printBill_hosp.php?num='.$_GET['num'].'&idassu='.$_GET['idassu'].'&cashier='.$_SESSION['codeCash'].'&idhosp='.$_GET['idhosp'].'&datehosp='.$_GET['datehosp'].'&idhosp='.$_GET['idhosp'].'&id_uM='.$_GET['id_uM'].'&idbill='.$idBilling.'&numroom='.$_GET['numroom'].'&numlit='.$_GET['numlit'].'&finishbtn=ok"</script>';
			
		
		}

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
}
?>
</body>

</html>