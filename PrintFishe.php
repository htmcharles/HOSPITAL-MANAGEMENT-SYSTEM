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

$annee = date('Y').'-'.date('m').'-'.date('d');

$heure = date('H').':'.date('i').':'.date('s');
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
		$poidsPa=$ligne->poidsPa;
		$taillePa=$ligne->taillePa;
		$temperaPa=$ligne->temperaturePa;
		$tensionartPa=$ligne->tensionarteriellePa;
		$poulsPa=$ligne->poulsPa;
		$oxgenPa=$ligne->oxgen;
		$bill=$ligne->bill;
		$idassu=$ligne->id_assurance;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$profession=$ligne->profession;
		$site=$_GET['num'];
		
		
		$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($ligne->date_naissance)));
		if (isset($_GET['showmore'])) {
			$selectdateconsu = $connexion->prepare("SELECT * FROM consultations WHERE id_consu=:idconsu");
			$selectdateconsu->execute(array(
				'idconsu'=>$_GET['idconsu']
			));
			$selectdateconsu->setFetchMode(PDO::FETCH_OBJ);
			$dateconsuHist = $selectdateconsu->fetch();
			$histdateconsu = $dateconsuHist->dateconsu;

			$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($histdateconsu)));
		}else{
			$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
		}
		$interval = $datetime1->diff($datetime2);
		
		if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
		{
			$an = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
		}
	
	}
	$result->closeCursor();
	
	
/* 
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
 */
	
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
			$assurancesName = $ligneNomAssu->nomassurance;
			$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
		}
	}


}


	
?><!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<meta charset="utf-8"/>
	<title>Patient File</title>

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

			.buttonBill
			{ 
				display:none;
				
			}
		}
	
	</style>
	
</head>


<body>

	<?php
	if(isset($_GET['createReportPdf']))
	{
	?>
		<body onload="window.print()">
	<?php
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$idDoneby=$_SESSION['id'];

if($connected==true AND isset($_SESSION['id']))
{
	
	if(isset($_SESSION['codeC']))
	{	
		$resultatsCoordi=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
		$resultatsCoordi->execute(array(
		'operation'=>$idDoneby	
		));

		$resultatsCoordi->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneCoordi=$resultatsCoordi->fetch())
		{
			$doneby = $ligneCoordi->full_name;
			$codeDoneby = $ligneCoordi->codecoordi;
		}
	}
	
	if(isset($_SESSION['codeM']))
	{	
		$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u and m.id_u=:operation');
		$resultatsMed->execute(array(
		'operation'=>$idDoneby	
		));

		$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneMed=$resultatsMed->fetch())
		{
			$doneby = $ligneMed->full_name;
			$codeDoneby = $ligneMed->codemedecin;
		}
	}
	
		$font = new BCGFontFile('barcode/font/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		// Barcode Part
		$code = new BCGcode93();
		$code->setScale(2);
		$code->setThickness(20);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setLabel('');
		$code->parse('');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codeDoneby.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	<div id="Report" class="account-container" style="margin: 10px auto auto; width:98%; border: 1px solid #eee; background:#fff; padding:5px; padding-bottom:0px; border-radius:3px; font-size:80%;">
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
				<img src="barcode/png/barcode'.$codeDoneby.'.png" style="height:auto;"/>	
			</td>
							
		</tr>
		
	</table>';

echo $barcode;
?>
		
	<?php
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
		$result->execute(array(
		'operation'=>$_SESSION['id']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeMed=$ligne->codemedecin;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			
			if($ligne->sexe=="M")
			{
				$sexe = "Male";
			}elseif($ligne->sexe=="F"){			
				$sexe = "Female";			
			}else{				
				$sexe="";
			}
			
			$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
			$resultAdresse->execute(array(
			'idProv'=>$ligne->province,
			'idDist'=>$ligne->district,
			'idSect'=>$ligne->secteur
			));
					
			$resultAdresse->setFetchMode(PDO::FETCH_OBJ);

			$comptAdress=$resultAdresse->rowCount();
			
			if($ligneAdresse=$resultAdresse->fetch())
			{
				if($ligneAdresse->id_province == $ligne->province)
				{
					$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
					
				}
			}elseif($ligne->autreadresse!=""){
					$adresse=$ligne->autreadresse;
			}else{
				$adresse="";
			}
		}
		
		$idDoc=$_SESSION['id'];
		//$dailydateperso=$_GET['dailydateperso'];
		//$docVisit=$_GET['docVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report')
					 ->setSubject("Report information")
					 ->setDescription('Report information for doctor : '.$codeMed.', '.$fullname.'')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'S/N')
					->setCellValue('B1', ''.$codeMed.'')
					->setCellValue('A2', 'Doctor name')
					->setCellValue('B2', ''.$fullname.'')
					->setCellValue('A3', 'Adresse')
					->setCellValue('B3', ''.$adresse.'')
					
					->setCellValue('I1', 'Report #')
					->setCellValue('J1', '')
					->setCellValue('I2', 'Done by')
					->setCellValue('J2', ''.$doneby.'')
					->setCellValue('I3', 'Date')
					->setCellValue('J3', ''.$annee.'');
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;">Patient File</h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="PrintFishe.php?dateconsu=<?= $_GET['dateconsu'];?>&num=<?php echo $_GET['num'];?>&showfiche=ok&idconsu=<?php echo $_GET['idconsu'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&showmore=ok&createReportPdf=ok" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<?php
				if(isset($_SESSION['codeC']))
				{
				?>
				<td style="text-align:left">
					
					<form method="post" action="" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<?php
				}
				?>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="consult.php?dateconsu=<?= $_GET['dateconsu'];?>&num=<?php echo $_GET['num'];?>&showfiche=ok&idconsu=<?php echo $_GET['idconsu'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&showmore=ok&createReportPdf=ok" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
			
	<?php
		$userinfo = '
	
	<table style="width:50%; margin-top:20px;font-size:15px;" align="center">		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Doctor name : </span>		
			</td>
			<td style="text-align:left;">'.$fullname.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">S/N : </span>		
			</td>
			<td style="text-align:left;">'.$codeMed.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Gender : </span>		
			</td>
			<td style="text-align:left;">'.$sexe.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Adress : </span>		
			</td>
			<td style="text-align:left;">'.$adresse.'</td>			
		</tr>		
	</table>';
	
		echo $userinfo;
		?>

		<?php

		if(isset($_GET['showmore']))
{
	$numero = $_GET['num'];
	$idConsu = $_GET['idconsu'];
	$dateconsu = $_GET['dateconsu'];
	
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptConsult=$resultConsult->rowCount();
	
	
	
		$resultMedMotif=$connexion->prepare('SELECT *FROM med_motif mm WHERE mm.id_consumotif=:idConsu ORDER BY mm.id_medmotif');	
		$resultMedMotif->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedMotif->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedMotif=$resultMedMotif->rowCount();
	
	
	
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idConsu ORDER BY mc.id_medconsu');	
		$resultMedConsult->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsult=$resultMedConsult->rowCount();
	
	
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_consuInf=:idConsu ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedInf=$resultMedInf->rowCount();
	
	
	
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idConsu ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedLabo=$resultMedLabo->rowCount();
	
	
	
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_consuRadio=:idConsu ORDER BY mr.id_medradio');		
		$resultMedRadio->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedRadio=$resultMedRadio->rowCount();
	
	
	
	
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_consuConsom=:idConsu ORDER BY mco.id_medconsom');	
		$resultMedConsom->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsom=$resultMedConsom->rowCount();
	
	
	
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_consuMedoc=:idConsu ORDER BY mdo.id_medmedoc');		
		$resultMedMedoc->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedMedoc=$resultMedMedoc->rowCount();	
	
	
	
		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk WHERE mk.id_consuKine=:idConsu ORDER BY mk.id_medkine');		
		$resultMedKine->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedKine=$resultMedKine->rowCount();
	
	
	
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo WHERE mo.id_consuOrtho=:idConsu ORDER BY mo.id_medortho');		
		$resultMedOrtho->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedOrtho=$resultMedOrtho->rowCount();
	
	
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_consuSurge=:idConsu ORDER BY ms.id_medsurge');		
		$resultMedSurge->execute(array(
		'idConsu'=>$idConsu	
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedSurge=$resultMedSurge->rowCount();
	
	
	
	
		$resultPreDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsudia AND (p.autrepredia!="" OR p.id_predia IS NOT NULL) ORDER BY p.id_dia');		
		$resultPreDia->execute(array(
		'idConsudia'=>$idConsu	
		));
		
		$resultPreDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptPreDia=$resultPreDia->rowCount();
	
	
	
	
		$resultPostDia=$connexion->prepare('SELECT *FROM prepostdia p WHERE p.id_consudia=:idConsudia AND (p.autrepostdia!="" OR p.id_postdia IS NOT NULL) ORDER BY p.id_dia');		
		$resultPostDia->execute(array(
		'idConsudia'=>$idConsu	
		));
		
		$resultPostDia->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptPostDia=$resultPostDia->rowCount();
	
	
	
	
	
	$start_week=strtotime("last week");
	$start_week=date("Y-m-d",$start_week);
				
	$getConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.dateconsu >=:startweek AND c.id_uM=:idMed ORDER BY c.id_consu DESC LIMIT 1');		
	$getConsult->execute(array(
	'num'=>$_GET['num'],
	'startweek'=>$start_week,
	'idMed'=>$_SESSION['id']
	));

	$comptGetConsult=$getConsult->rowCount();
	
	$getConsult->setFetchMode(PDO::FETCH_OBJ);
	
	if($comptGetConsult!=0)
	{
		if($ligneGetConsult=$getConsult->fetch())
		{
			$idconsult=$ligneGetConsult->id_consu;
			
			$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
			$resultatsTypeConsu->execute(array(
			'idTypeconsu'=>$ligneGetConsult->id_typeconsult
			));

			$resultatsTypeConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
			if($ligneTypeConsu=$resultatsTypeConsu->fetch())//on recupere la liste des éléments
			{
				if($ligneTypeConsu->namepresta!="")
				{
					$nomTypeConsult = $ligneTypeConsu->namepresta;
				}else{
					$nomTypeConsult = $ligneTypeConsu->nompresta;
				}
			}
			
			if(($ligneGetConsult->id_factureConsult!=NULL OR ($nomTypeConsult =="Pas de consultation" OR $nomTypeConsult =="No Consultation")) AND $ligneGetConsult->dateconsu == $annee)
			{
?>
			<!-- <a href="consult.php?num=<?php echo $ligneGetConsult->numero;?>&idtypeconsult=<?php echo $ligneGetConsult->id_typeconsult;?>&idconsu=<?php echo $_GET['idconsu'];?>&idassuconsu=<?php echo $ligneGetConsult->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a> -->

			
	<?php
			}else{
				if($ligneGetConsult->dateconsu == $annee)
				{
			?>
				<!-- <span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span> -->
				<!-- <a href="consult.php?num=<?php echo $ligneGetConsult->numero;?>&idtypeconsult=<?php echo $ligneGetConsult->id_typeconsult;?>&idconsu=<?php echo $_GET['idconsu'];?>&idassuconsu=<?php echo $ligneGetConsult->id_assuConsu;?>&consu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-stethoscope fa-lg fa-fw"></i><?php echo getString(101);?></a> -->
				
			<?php
			}else{
				// echo "<b>-- Consultation Has Been Expired --</b>";

			}
		  }
		}
	}else{
		$idconsult=0;
	?>
		---No consultation---
	<?php
	}
	?>
		
		<?php
					
		$getProfil=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num');		
		$getProfil->execute(array(
		'num'=>$_GET['num']
		));

		$comptgetProfil=$getProfil->rowCount();
		
		$getProfil->setFetchMode(PDO::FETCH_OBJ);
		
		if($comptgetProfil!=0)
		{
			if($ligneGetProfil=$getProfil->fetch())
			{
		?>
			<!-- <a href="consult.php?num=<?php echo $ligneGetProfil->numero?>&showfiche=ok&idconsu=<?php echo $ligneGetProfil->id_consu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#fichepatient" class="btn-large buttonBill"><span title="View Profile" name="fichebtn"><i class="fa fa-eye fa-lg fa-fw"></i><?php echo getString(100);?></span></a> -->
			
		<?php 
			}
		}else{
		?>
			<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 25px;"><?php echo getString(208) ?></span>
		<?php 
		}
		?>
		<?php
			if (isset($_GET['showmore'])) {
		?>
			<!-- <a href="PrintFishe.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idtypeconsult'])){ echo '&idtypeconsult='.$_GET['idtypeconsult'];}?><?php if(isset($_GET['idassuconsu'])){ echo '&idassuconsu='.$_GET['idassuconsu'];}?><?php if(isset($_GET['showfiche'])){ echo '&showfiche='.$_GET['showfiche'];}?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&finishbtn=ok" class="btn-large buttonBill"><span title="View Profile" name="fichebtn"><i class="fa fa-print fa-lg fa-fw"></i>Print</span></a> -->
		<?php
			}
		?>
		<br/>
		<br/>

	<div id="showmore">	

<?php

	if($ligneConsult=$resultConsult->fetch())//on recupere la liste des éléments
	{
?>
		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:80%;">
			<tr>
				<td style="font-size:18px; text-align:center; width:21%;">
					<span style="font-weight:bold;"><?php echo getString(89) ?> : </span><?php echo $nom_uti.' '.$prenom_uti;?>
					
				</td>

				<td style="font-size:18px; text-align:center; width:21%;">
					<span style="font-weight:bold;"><?php echo getString(280) ?> : </span><?php echo $assurancesName;?>
					
				</td>
				
				<td style="font-size:18px; text-align:center; width:21%;">
					<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
					<?php
					if($sexe=="M")
					{
						$sexe = "Male";
					}elseif($sexe=="F"){			
						$sexe = "Female";			
					}else{				
						$sexe="";
					}
						
					 echo $sexe;
					?>
						
				<?php
											
				$getEcho=$connexion->prepare('SELECT * FROM med_radio mr, prestations_private pp WHERE mr.numero=:num AND ((mr.id_prestationRadio IS NULL AND (mr.autreRadio LIKE "echo%" OR mr.autreRadio LIKE "eco%")) OR (mr.id_prestationRadio = pp.id_prestation AND (pp.namepresta LIKE "echo%" OR pp.nompresta LIKE "echo%"))) GROUP BY mr.id_medradio ORDER BY mr.dateconsu DESC LIMIT 5');		
				$getEcho->execute(array(
				'num'=>$_GET['num']
				));

				$comptEcho=$getEcho->rowCount();
				
				$getEcho->setFetchMode(PDO::FETCH_OBJ);
					
				if($comptEcho!=0)
				{
				?>
					<br/>
						
					<span title="View Echographie" name="viewechobtn" class="btn" id="viewecho" onclick="ShowHideEcho('viewecho')" style="display:none"><i class="fa fa-chevron-circle-down fa-lg fa-fw"></i><?php echo 'View Echos done';?></span>
				<?php
				}
				?>	
				</td>
				
				<td style="font-size:18px; text-align:center; width:33.333%;">
				
					<span style="font-weight:bold;">Age : </span><?php echo $an;?>
				</td>
			</tr>
		</table>
		<br><br><br>
		
		<span style="position:relative; font-size:200%;margin-bottom:2px; padding:5px;"><?php echo getString(129) ?> <span style="color:#a00000; font-size:120%; font-weight:100;"><?php echo date('d-M-Y', strtotime($ligneConsult->dateconsu));?></span></span>
		
		<table class="tablesorter" style="margin:30px auto auto; width:50%; padding-left:50px;" cellpadding=3>
			
			<tr>
				<td style="width:40%; text-align:left; vertical-align:top;">
					<span style="font-weight:bold;"><?php echo getString(113) ?> : </span>
					<?php
					
					$idassuConsu=$ligneConsult->id_assuConsu;
							
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();
					
					for($i=1;$i<=$assuCount;$i++)
					{
						
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassuConsu
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assuConsu='prestations_'.$ligneNomAssu->nomassurance;
						}
					}

					
					$resultatsTypeConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assuConsu.' p WHERE c.id_typeconsult=p.id_prestation AND p.id_prestation=:idTypeconsu') or die( print_r($connexion->errorInfo()));
					$resultatsTypeConsu->execute(array(
					'idTypeconsu'=>$ligneConsult->id_typeconsult
					));

					$resultatsTypeConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
					if($ligneTypeConsu=$resultatsTypeConsu->fetch())//on recupere la liste des éléments
					{
						if($ligneTypeConsu->namepresta!="")
						{
							echo $ligneTypeConsu->namepresta;
						
						}else{
						
							echo $ligneTypeConsu->nompresta;
						}
					}
					?>
				</td>
			</tr>
		</table>
		
		<table style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 10px; width: 80%; border: 1px solid rgb(238, 238, 238); margin: 10px auto auto; border-radius: 4px;" cellpadding=3>
			<tr>						
				<td style="text-align: center; width: 18%; border-right: 1px solid #ccc;">
					<?php echo getString(115); ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->poids != null)
					{
					?>						
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->poids ;?></span>
						<span style="font-size:90%;">Kg</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
					
				</td>
				
				<td style="text-align: center; width: 18%; border-right: 1px solid #ccc;">
					<?php echo 'Taille'; ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->taille != null)
					{
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->taille ;?></span>
						<span style="font-size:90%;">Cm</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>					
				</td>
				
				<td style="text-align: center; width: 20%; border-right: 1px solid #ccc;">
					<?php echo getString(116); ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->temperature != null)
					{
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->temperature ;?></span>
						<span style="font-size:90%;">°C</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>

				<td style="text-align: center; width: 20%; border-right: 1px solid #ccc;">
					<?php echo 'Oxgen'; ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->oxgen != null)
					{
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->oxgen ;?></span>
						<span style="font-size:90%;">O<sub>2</sub></span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>
				
				<td style="text-align: center; width: 26%; border-right: 1px solid #ccc;">
					<?php echo getString(117); ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->tensionart != null)
					{
					?>						
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->tensionart ;?></span>
						<span style="font-size:90%;">mmHg</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>
				
				<td style="text-align: center; width: 18%;">
					<?php echo 'Pouls'; ?>:
					<?php 
					if(isset($_GET['num']) && $ligneConsult->pouls != null)
					{ 
					?>
						<span style="font-weight:bold; font-size:150%"><?php echo $ligneConsult->pouls ;?></span>
						<span style="font-size:90%;">/min</span>
				
			<?php   }else
					{
						echo " --- ";
					}?>
				</td>
				
			</tr>
		</table>
		
		<?php
		if($comptMedMotif!=0)
		{
		?>
		<table style="width:50%; margin-top:15px;" class="tablesorter" cellspacing="0" align="center"> 
			
			<thead> 
				<tr style="height:45px;">
					<th style="font-size:20px; width:15%; border-radius:0; color:#333; background:rgb(228,228,228) !important" colspan=10><?php echo getString(154); ?></th>
				</tr> 
			</thead> 
			
			<tbody> 
		
				<tr>
					<?php
					while($ligneMedMotif=$resultMedMotif->fetch())
					{
						$resultatsPrestaMotif=$connexion->prepare('SELECT *FROM med_motif mm, motifs m WHERE m.id_motif=mm.id_motif AND mm.id_motif=:idConsu') or die( print_r($connexion->errorInfo()));
						$resultatsPrestaMotif->execute(array(
						'idConsu'=>$ligneMedMotif->id_motif
						));

						$resultatsPrestaMotif->setFetchMode(PDO::FETCH_OBJ);
							
						if($ligneMotif=$resultatsPrestaMotif->fetch())
						{ 
							if($ligneMotif->nommotif !="")
							{
								$nomMotif = $ligneMotif->nommotif;
							}
						}else{
							$nomMotif = $ligneMedMotif->autremotif;
						}						
						
						echo '<td style="padding:10px; text-align: center; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">'.$nomMotif.'</td>';
					}
					?>
				</tr>
			</tbody>
				
		</table>
		<?php
		}
		?>
		
		<table class="cons-info" cellpadding=3 style="margin-bottom:15px;">

			<tr>
				<?php 		
				if($ligneConsult->motif != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo getString(154) ?></td>
				<?php 		
				}		
				if($ligneConsult->anamnese != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Anamnèse'; ?></td>
				<?php 		
				}		
				if($ligneConsult->clihist != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Clinical History'; ?></td>
				<?php 		
				}
				if($ligneConsult->etatpatient != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold; text-align:center;"><?php echo getString(155) ?></td>
				<?php 		
				}
				if($ligneConsult->antecedent != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo getString(156) ?></td>
				<?php 		
				}
				if($ligneConsult->allergie != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Allergie'; ?></td>
				<?php 		
				}
				if($ligneConsult->examcli != "")
				{
				?>				
					<td style="border-bottom:1px solid #bbb; font-weight:bold;"><?php echo 'Examen Clinique'; ?></td>
				<?php 		
				}
				if($ligneConsult->signsymptomes != "")
				{
				?>
					<td style="border-bottom:1px solid #bbb; font-weight:bold; text-align:center;"><?php echo getString(157) ?></td>
	<?php		}
	?>
			</tr>
			
			<tr>
				<?php 		
				if($ligneConsult->motif != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->motif)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->anamnese != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->anamnese)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->clihist != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->clihist)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->etatpatient != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->etatpatient)?></textarea>
					</td>
				<?php		
				}
				if($ligneConsult->antecedent != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->antecedent)?></textarea>
					</td>
				<?php
				}
				if($ligneConsult->allergie != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->allergie)?></textarea>
					</td>
				<?php
				}
				if($ligneConsult->examcli != "")
				{
				?>
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->examcli)?></textarea>
					</td>
				<?php
				}
				if($ligneConsult->signsymptomes != "")
				{
				?>			
					<td style="text-align:center;">
						<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-width:300px; min-width:100px; max-height:300px; min-height:100px; background: rgb(255, 255, 255) none repeat scroll 0% 0%;" readonly='readonly'><?php echo strip_tags($ligneConsult->signsymptomes)?></textarea>
					</td>
				<?php		
				}
				?>				
			</tr>
	
		</table>

	<?php
	}
	?>
				
	<?php
	$Postdia = array();
	$DiagnoPostDone=0;
										
	$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
	
	$resuPostdiagnostic->execute(array(
	'idConsu'=>$idConsu
	))or die( print_r($connexion->errorInfo()));
		
	$resuPostdiagnostic->setFetchMode(PDO::FETCH_OBJ);
		
	$lignePostdiagnostic=$resuPostdiagnostic->fetch();
	
		$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
		
		$resultatsDiagnoPost->execute(array(
		'iddiagno'=>$lignePostdiagnostic->postdiagnostic
		))or die( print_r($connexion->errorInfo()));
			
		$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
		{
			$Postdia[] = $ligneDiagnoPost->nomdiagno;			
			$DiagnoPostDone=1;
		}else{
		
			if($lignePostdiagnostic->postdiagnostic != "")
			{
				$Postdia[] = $lignePostdiagnostic->postdiagnostic;
				$DiagnoPostDone=1;
			}
		}

		
	$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
	
	$resultatsPostDiagno->execute(array(
	'id_consudia'=>$lignePostdiagnostic->id_consu
	
	))or die( print_r($connexion->errorInfo()));
		
	$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
	$postdiaCount = $resultatsPostDiagno->rowCount();
	
	if($postdiaCount!=0)
	{
		
		while($linePostDiagno=$resultatsPostDiagno->fetch())
		{
			$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
			$resultsDiagno->execute(array(
			'iddiagno'=>$linePostDiagno->id_postdia
			));
			
			$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
			$comptDiagno=$resultsDiagno->rowCount();
			
			if($comptDiagno!=0)
			{
				$ligne=$resultsDiagno->fetch();			
				$Postdia[] = $ligne->nomdiagno;
				$DiagnoPostDone=1;
			}else{
				if($linePostDiagno->autrepostdia !="")
				{
					$Postdia[] = $linePostDiagno->autrepostdia;
					$DiagnoPostDone=1;
				}
			}
			
		}
	
	}
						
	if($DiagnoPostDone ==0)
	{
	?>			
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px;" id="prediagnotable">
			<?php
			$Predia = array();
			$DiagnoPreDone=0;
										
			$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
			
			$resuPrediagnostic->execute(array(
			'idConsu'=>$idConsu
			))or die( print_r($connexion->errorInfo()));
				
			$resuPrediagnostic->setFetchMode(PDO::FETCH_OBJ);
				
			$lignePrediagnostic=$resuPrediagnostic->fetch();
			
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$lignePrediagnostic->prediagnostic
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Predia[] = $ligneDiagnoPost->nomdiagno;
					$DiagnoPreDone=1;
				}else{
				
					if($lignePrediagnostic->prediagnostic != "")
					{
						$Predia[] = $lignePrediagnostic->prediagnostic;
						$DiagnoPreDone=1;
					}
				}
	
				
			$resultatsPrediagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
			
			$resultatsPrediagno->execute(array(
			'id_consudia'=>$lignePrediagnostic->id_consu
			
			))or die( print_r($connexion->errorInfo()));
				
			$resultatsPrediagno->setFetchMode(PDO::FETCH_OBJ);
			$prediaCount = $resultatsPrediagno->rowCount();
			
			if($prediaCount!=0)
			{
				
				while($linePrediagno=$resultatsPrediagno->fetch())
				{
					$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					$resultsDiagno->execute(array(
					'iddiagno'=>$linePrediagno->id_predia
					));
					
					$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
					$comptDiagno=$resultsDiagno->rowCount();
					
					if($comptDiagno!=0)
					{
						$ligne=$resultsDiagno->fetch();
						
						$Predia[] = $ligne->nomdiagno;
						$DiagnoPreDone=1;
					}else{
						$Predia[] = $linePrediagno->autrepredia;
						$DiagnoPreDone=1;
					}
					
				}
			
			}
				
			if($DiagnoPreDone==1)
			{
			?>
			<table style="width:50%;" class="tablesorter" cellspacing="0" align="center"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="font-size:20px; width:15%; border-radius:0; color:#333; background:rgb(228,228,228) !important" colspan=10><?php echo getString(246); ?></th>
					</tr> 
				</thead> 
				
				<tbody> 
			
					<tr>
						<?php
						
						for($p=0;$p<sizeof($Predia);$p++)
						{
							echo '<td style="padding:10px; text-align: center; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">'.$Predia[$p].'</td>';
						}
						
						/* if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
						{
							echo getString(209) .$ligneDiagnoPost->nomdiagno. " - " .getString(210);
						}else{
							echo getString(209) .$prediagno. " - " .getString(210);
						} */
						?>
					</tr>
				</tbody>
					
			</table>
			<?php
			}
			?>
		</div>
			
	<?php
	}
	?>
	
	
	<?php
	if($comptMedConsult!=0)
	{
	?>		
		<p style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;text-align: center;"><?php echo "Services"; ?></p>

		<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
		
			<table style="width:70%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="border-radius:0; color:#333; background:#ccc !important">Services</th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedConsult=$resultMedConsult->fetch())//on recupere la liste des éléments
						{
							
							$idassuServ=$ligneMedConsult->id_assuServ;
							
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
							
					?>
						<tr style="text-align:center;">
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
						
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_consult c, '.$presta_assuServ.' p WHERE c.id_prestationConsu=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedConsult->id_prestationConsu
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{
							?>
							
							<?php 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
								
								echo $ligneMedConsult->autreConsu;
							?>
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsult->dateconsu != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsult->dateconsu));}else{ echo '';}?></td>
							
							<td style="padding:10px; text-align:center;">
							<?php
														
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedConsult->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedConsult->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedConsult->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(131) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */

	if($comptMedInf!=0)
	{
?>
		<p style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;text-align: center;"><?php echo getString(98) ?></p>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table style="width:80%;" class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr style="height:45px;">
						<th style="width:25%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(98) ?></th>
						<th style="width:15%; color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="width:30%; color:#333; background:#ccc !important"><?php echo getString(21) ?></th>
						<th style="width:30%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
						{
							
							$idassuInf=$ligneMedInf->id_assuInf;
							
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
							
					?>
						<tr style="text-align:center;">
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_inf mi, '.$presta_assuInf.' p WHERE mi.id_prestation=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedInf->id_prestation
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedInf->autrePrestaM;
							?>
							</td>
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedInf->datesoins != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedInf->datesoins));}else{ echo '';}?></td>
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php
												
							$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u AND i.id_u=:idInf') or die( print_r($connexion->errorInfo()));
							$resultatsInf->execute(array(
							'idInf'=>$ligneMedInf->id_uI
							));

							$resultatsInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneInf=$resultatsInf->fetch())//on recupere la liste des éléments
							{
							
								if($ligneMedInf->id_uI==$ligneInf->id_u)
								{
									echo $ligneInf->full_name;
								}else{
									echo '';
								}
							}
							$resultatsInf->closeCursor();
							?>						
							</td>	
							<td style="padding:10px; text-align:center;">
							<?php
														
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedInf->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedInf->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedInf->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom:30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(104) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */
	
	if($comptMedLabo!=0)
	{
?>
		<p style="position:relative; font-size:250%;margin-bottom: 2px;padding:5px;text-align: center;"><?php echo getString(133) ?></p>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="labotable">
		
			<table class="tablesorter" cellspacing="0"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(99) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(3) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Valeur';?></th>
					    <th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Valeur (min-max)';?></th>
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(22) ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important" colspan=4><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
						{
							
							$idassuLab=$ligneMedLabo->id_assuLab;

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

							if($ligneMedLabo->moreresultats!=0)
							{
						?>
							<tr>
								
								<td style="background:#eee; padding:10px; text-align: center; border-right: 1px solid #ccc; font-weight:bold;" colspan=4>
								
								<?php									
								$resultatsPresta=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assuLab.' p WHERE ml.id_prestationExa=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
								$resultatsPresta->execute(array(
								'idConsu'=>$ligneMedLabo->id_prestationExa
								));

								$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
								{ 
									if($lignePresta->namepresta!="")
									{
										echo $lignePresta->namepresta;
									}else{
										echo $lignePresta->nompresta;
									}
								}
								
									echo $ligneMedLabo->autreExamen;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php 

								$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idL') or die( print_r($connexion->errorInfo()));
								$resultatsLabo->execute(array(
								'idL'=>$ligneMedLabo->id_uL
								));

								$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($ligneLabo=$resultatsLabo->fetch())//on recupere la liste des éléments
								{
									echo $ligneLabo->full_name;
								}else{
									echo '';
								}
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
									<?php 
									if($ligneMedLabo->dateresultats!="0000-00-00")
									{
										echo date('d-M-Y', strtotime($ligneMedLabo->dateresultats));
									}else{
										echo '';
									}								
									?>
								</td>
															
								<td style="padding:10px; text-align: center;" colspan=4>
								<?php
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedLabo->id_uM
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedLabo->id_uM==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>			
							</tr>
						
								<?php
								if($ligneMedLabo->moreresultats==1)
								{
							
									$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
									$resultMoreMedLabo->execute(array(
									'num'=>$_GET['num'],
									'idmedLab'=>$ligneMedLabo->id_medlabo
									));
									
									$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
									
									while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
									{
								?>									
									<tr>
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
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
													echo $lignePresta->namepresta;
												
												}else{
												
													$presta=$lignePresta->nompresta;
													echo $lignePresta->nompresta;
												}
												$mesure=$lignePresta->mesure;
											}else{
												$presta=$ligneMoreMedLabo->autreExamen;
												$mesure='';
												echo $ligneMoreMedLabo->autreExamen;
											}
										?>
										</td>
										
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
										<?php echo $ligneMoreMedLabo->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
										</td>
										
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px">
										<?php echo $ligneMoreMedLabo->valeurLab;?>
										</td>
									
									<td style="text-align:center;">
										<?php
										
										$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE nomexam=:nomexam ORDER BY valeur');
										$resultValeur->execute(array(
										'nomexam'=>$presta
										));
										
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
													
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px;display:none">
										<?php
										if($ligneMoreMedLabo->resultats!="")
										{
										?>
											<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
										<?php
										}
										?>
										</td>
								
										<td style="text-align:center;border-right: 1px solid #ccc;padding:5px" colspan=6></td>
										
									</tr>
									
								<?php
									}
								}
							
								if($ligneMedLabo->moreresultats==2)
								{
									$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');		
								
									$resultSpermoMedLabo->execute(array(
									'num'=>$_GET['num'],
									'idmedLab'=>$ligneMedLabo->id_medlabo
									));
									
									$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
									
									while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
									{
									?>									
									<tr>
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
									
									<tr>	
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
								
								echo '<tr style="background-color:#eee;height:0;min-height:0;">
									<td style="padding:5px" colspan=11></td>
								</tr>';
							
							}else{
							?>
							<tr style="text-align:center;">
							
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;font-weight:bold;">
								<?php									
								$resultatsPresta=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assuLab.' p WHERE ml.id_prestationExa=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
								$resultatsPresta->execute(array(
								'idConsu'=>$ligneMedLabo->id_prestationExa
								));

								$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
								{ 
									if($lignePresta->namepresta!="")
									{
										$presta=$lignePresta->namepresta;
									}else{
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneMedLabo->autreExamen;
									$mesure='';
								}
									echo $presta;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php
								if($ligneMedLabo->autreresultats!="")
								{
									echo $ligneMedLabo->autreresultats.' <span style="font-size:80%; font-weight:normal;padding:5px;"> '.$mesure.'</span>';
								}else
								{
									echo "<span style='color:#bc0000'>En attente...</span>";
								}
								?>
								</td>
									
								<td style="text-align:center;border-right: 1px solid #ccc;"><?php echo $ligneMedLabo->valeurLab;?></td>
								
								
								
							<td style="text-align:center;text-align:center;border-right: 1px solid #ccc;">
										<?php
										
										$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE nomexam=:nomexam ORDER BY valeur');
										$resultValeur->execute(array(
										'nomexam'=>$presta
										));
										
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
																
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;display:none">
								<?php
								if($ligneMedLabo->resultats!="")
								{
								?>
									<a href="<?php echo $ligneMedLabo->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
								<?php
								}
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php 

								$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idL') or die( print_r($connexion->errorInfo()));
								$resultatsLabo->execute(array(
								'idL'=>$ligneMedLabo->id_uL
								));

								$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								if($ligneLabo=$resultatsLabo->fetch())//on recupere la liste des éléments
								{
									echo $ligneLabo->full_name;
								}else{
									echo '';
								}
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
									<?php 
									if($ligneMedLabo->dateresultats!="0000-00-00")
									{
										echo date('d-M-Y', strtotime($ligneMedLabo->dateresultats));
									}else{
										echo '';
									}								
									?>
								</td>
															
								<td style="padding:10px; text-align: center;" colspan=4>
								<?php
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedLabo->id_uM
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedLabo->id_uM==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>			
								
							</tr>
							<?php								
							}
						
						
						}
						$resultMedLabo->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(107) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */	
	
	if($comptMedRadio!=0)
	{
?>
		<p style="position:relative; font-size:250%;margin-bottom: 2px;padding:5px;text-align: center;"><?php echo 'Radiologie'; ?></p>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="radiotable">
		
			<table class="tablesorter" cellspacing="0"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Radio demandée' ?></th>
						<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date Résultats</th>
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Done by'; ?></th>						
						<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedRadio=$resultMedRadio->fetch())//on recupere la liste des éléments
						{
							
							$idassuRad=$ligneMedRadio->id_assuRad;

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

					?>
						<tr style="text-align:center;">
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;" <?php if($ligneMedRadio->resultatsRad !=""){ echo 'colspan=3';}?>>
							<?php									
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_radio mr, '.$presta_assuRad.' p WHERE mr.id_prestationRadio=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedRadio->id_prestationRadio
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedRadio->autreRadio;
							?>
							</td>
							<?php 
							if($ligneMedRadio->resultatsRad =="")
							{
							?>
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">								
							</td>
								
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							</td>
							<?php
							}
							?>						
							<td style="padding:10px; text-align: center;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedRadio->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedRadio->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>			
							
						</tr>
						<?php
						if($ligneMedRadio->resultatsRad !="")
						{
						?>
						<tr style="text-align:center;background:#eee">
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;"readonly='readonly'><?php echo strip_tags($ligneMedRadio->resultatsRad)?></textarea>
							</td>
							
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
								<?php 
								if($ligneMedRadio->dateradio!="0000-00-00")
								{
									echo date('d-M-Y', strtotime($ligneMedRadio->dateradio));
								}else{
									echo '';
								}								
								?>
							</td>
								
							<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							<?php 

							$resultatsRadio=$connexion->prepare('SELECT *FROM utilisateurs u, medecins x WHERE u.id_u=x.id_u AND x.id_u=:idX') or die( print_r($connexion->errorInfo()));
							$resultatsRadio->execute(array(
							'idX'=>$ligneMedRadio->id_uX
							));

							$resultatsRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneRadio=$resultatsRadio->fetch())//on recupere la liste des éléments
							{
								echo $ligneRadio->full_name;
							}else{
								echo '';
							}
							?>
							</td>
													
							<td style="padding:10px; text-align: center;">						
							</td>			
							
						</tr>
						<?php
						}
						?>
						
					<?php
						}
						$resultMedRadio->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}/* else{
	?>
		<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(107) ?></th>
			</tr>
		</thead> 		
		</table>
	<?php
	} */	

	if($comptMedConsom!=0)
	{
?>
		<p style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;text-align: center;"><?php echo 'Consommables'; ?></p>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th style="color:#333; background:#ccc !important"><?php echo 'Consommables' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo 'Quantity' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedConsom=$resultMedConsom->fetch())
						{
							$idassuConsom=$ligneMedConsom->id_assuConsom;
							
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

							
					?>
						<tr style="text-align:center;">
							<td style="text-align:center;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_consom mco, '.$presta_assuConsom.' p WHERE mco.id_prestationConsom=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedConsom->id_prestationConsom
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedConsom->autreConsom;
							?>
							</td>
							
							<td><b style="border-radius: 5px;background: #bb090973;padding: 10px 30px;"><?php echo $ligneMedConsom->qteConsom;?></b></td>
							<td><?php if($ligneMedConsom->dateconsu != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsom->dateconsu));}else{ echo '';}?></td>
							
							<td>
							<?php
	
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedConsom->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedConsom->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedConsom->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}	
	
	
	
	if($comptMedMedoc!=0)
	{
?>
		<p style="position:relative; font-weight:400; font-size:250%;margin-top:20px; margin-bottom:10px; padding:5px;text-align: center;"><?php echo 'Medicaments'; ?></p>
		
		<div style="overflow:auto;height:auto;padding:5px; margin-bottom:30px;">
		
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th style="color:#333; background:#ccc !important"><?php echo 'Medicaments' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo 'Quantity' ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
						<th style="color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
					</tr> 
				</thead> 
			
			
				<tbody>	
					<?php
					try
					{
						while($ligneMedMedoc=$resultMedMedoc->fetch())//on recupere la liste des éléments
						{
							
							$idassuMedoc=$ligneMedMedoc->id_assuMedoc;
						
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

					?>
						<tr style="text-align:center;">
							<td style="text-align:center;">
							<?php
																
							$resultatsPresta=$connexion->prepare('SELECT *FROM med_medoc mdo, '.$presta_assuMedoc.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
							$resultatsPresta->execute(array(
							'idConsu'=>$ligneMedMedoc->id_prestationMedoc
							));

							$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
							{ 
								if($lignePresta->namepresta!="")
								{
									echo $lignePresta->namepresta;
								}else{
									echo $lignePresta->nompresta;
								}
							}
							
								echo $ligneMedMedoc->autreMedoc;
							?>
							</td>
							<td><b style="border-radius: 5px;background: #bb090973;padding: 10px 30px;"><?php echo $ligneMedMedoc->qteMedoc;?></b></td>

							<td><?php if($ligneMedMedoc->dateconsu != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedMedoc->dateconsu));}else{ echo '';}?></td>
							
							<td>
							<?php
	
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneMedMedoc->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								if($ligneMedMedoc->id_uM==$ligneMed->id_u)
								{
									echo $ligneMed->full_name;
								}else{
									echo '';
								}
							}
							$resultatsMed->closeCursor();
							?>						
							</td>	
						</tr>

					<?php
						}
						$resultMedMedoc->closeCursor();
					}

					catch(Excepton $e)
					{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
					}
					?>
				</tbody>
			</table>
		</div>	
	<?php	
	}

		$idcategopresta =21;
		$GetRecomm = $connexion->prepare("SELECT * FROM doctorRecommandations WHERE idcategopresta=:idcategopresta AND numero=:numero AND id_M=:idM AND idconsu=:idconsu ");
		$GetRecomm->execute(array('numero'=>$_GET['num'],'idconsu'=>$_GET['idconsu'],'idM'=>$_SESSION['id'],'idcategopresta'=>$idcategopresta));
		$GetRecomm->setFetchMode(PDO::FETCH_OBJ);
		$comptMedConsomRecomm = $GetRecomm->rowCount();


		if($comptMedConsomRecomm!=0)
		{
		?>		
			<p style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;text-align: center;" ><?php echo "Recommanded Consomables"; ?></p>

			<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
			
				<table style="width:80%;" class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr style="height:45px;">
							<th style="border-radius:0; color:#333; background:#ccc !important">#</th>
							<th style="border-radius:0; color:#333; background:#ccc !important">Consomables</th>
							<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
							<th style="color:#333; background:#ccc !important"><?php echo'Time'; ?></th>
							<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
						</tr> 
					</thead> 
				
					<tbody>	
						<?php
						try
						{
							$consomm = 1;
							while($ligneMedConsult=$GetRecomm->fetch())//on recupere la liste des éléments
							{
						?>
							<tr style="text-align:center;">
								<td><?php echo $consomm; ?></td>
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							
								<?php									
									echo $ligneMedConsult->recommandations;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsult->duration != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsult->duration));}else{ echo '';}?></td>

								<td><?php echo $ligneMedConsult->timet; ?></td>
								
								<td style="padding:10px; text-align:center;">
								<?php
															
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedConsult->id_M
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedConsult->id_M==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>	
							</tr>

						<?php
							$consomm ++;
							}
							$GetRecomm->closeCursor();
						}

						catch(Excepton $e)
						{
							echo 'Erreur:'.$e->getMessage().'<br/>';
							echo'Numero:'.$e->getCode();
						}
						?>
					</tbody>
				</table>
			</div>	
		<?php	
		} /*else{
		?>
			<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
					<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(131) ?></th>
				</tr>
			</thead> 		
			</table>
		<?php
		} */

		$idcategopresta =22;
		$GetRecomm = $connexion->prepare("SELECT * FROM doctorRecommandations WHERE idcategopresta=:idcategopresta AND numero=:numero AND id_M=:idM AND idconsu=:idconsu");
		$GetRecomm->execute(array('numero'=>$_GET['num'],'idconsu'=>$_GET['idconsu'],'idM'=>$_SESSION['id'],'idcategopresta'=>$idcategopresta));
		$GetRecomm->setFetchMode(PDO::FETCH_OBJ);
		$comptMedConsomRecomm = $GetRecomm->rowCount();


		if($comptMedConsomRecomm!=0)
		{
		?>		
			<p style="position:relative; font-weight:400; font-size:250%; margin-bottom:10px; padding:5px;text-align: center;"><?php echo "Recommended Drugs"; ?></p>

			<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
			
				<table style="width:80%;" class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr style="height:45px;">
							<th style="border-radius:0; color:#333; background:#ccc !important">#</th>
							<th style="border-radius:0; color:#333; background:#ccc !important">Drugs</th>
							<th style="color:#333; background:#ccc !important"><?php echo getString(71) ?></th>
							<th style="color:#333; background:#ccc !important"><?php echo 'Time' ?></th>
							<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
						</tr> 
					</thead> 
				
					<tbody>	
						<?php
						try
						{
							$medoc = 1;
							while($ligneMedConsult=$GetRecomm->fetch())//on recupere la liste des éléments
							{
						?>
							<tr style="text-align:center;">
								<td><?php echo $medoc; ?></td>
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
							
								<?php									
									echo $ligneMedConsult->recommandations;
								?>
								</td>
								
								<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;"><?php if($ligneMedConsult->duration != '0000-00-00'){ echo date('d-M-Y', strtotime($ligneMedConsult->duration));}else{ echo '';}?></td>

								<td><?php echo $ligneMedConsult->timet; ?></td>
								
								<td style="padding:10px; text-align:center;">
								<?php
															
								$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
								$resultatsMed->execute(array(
								'idMed'=>$ligneMedConsult->id_M
								));

								$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
									
								while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
								{
									if($ligneMedConsult->id_M==$ligneMed->id_u)
									{
										echo $ligneMed->full_name;
									}else{
										echo '';
									}
								}
								$resultatsMed->closeCursor();
								?>						
								</td>	
							</tr>

						<?php
							$medoc ++;
							}
							$GetRecomm->closeCursor();
						}

						catch(Excepton $e)
						{
							echo 'Erreur:'.$e->getMessage().'<br/>';
							echo'Numero:'.$e->getCode();
						}
						?>
					</tbody>
				</table>
			</div>	
		<?php	
		} /*else{
		?>
			<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
					<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(131) ?></th>
				</tr>
			</thead> 		
			</table>
		<?php
		} */
	?>
					
	<?php 
	if($DiagnoPostDone !=0)
	{
	?>
		
		<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px;" id="postdiagnotable">
		
			<table style="width:50%;" class="tablesorter" cellspacing="0" align="center"> 
				
				<thead> 
					<tr style="height:45px;">
						<th style="font-size:20px; width:15%; border-radius:0; color:#333; background:rgb(228,228,228) !important" colspan=10><?php echo getString(247); ?></th>
					</tr> 
				</thead> 
				
				<tbody> 
			
					<tr>
						<?php
						
						for($p=0;$p<sizeof($Postdia);$p++)
						{
							echo '<td style="padding:10px; text-align: center; border-right: 1px solid #ccc; border-left: 1px solid #ccc;">'.$Postdia[$p].'</td>';
						}
						
						/* if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
						{
							echo getString(209) .$ligneDiagnoPost->nomdiagno. " - " .getString(210);
						}else{
							echo getString(209) .$postdiagno. " - " .getString(210);
						} */
						?>
					</tr>
				</tbody>
					
			</table>
		</div>
		
	<?php
	}else{
			echo '
			<table style="width:100%;background:#fff; border:1px solid #eee; border-radius:4px; margin-bottom:10px; padding: 20px;">
				<tr>
					<td style="text-align:center;font-size:30px;">'.getString(267).'
					</td>
				</tr>
			</table>';
	}
	?>
		<?php 
		//if($recomm != "" OR $comptMedSurge != "")
		//{
		?>
		<!-- <table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin-bottom:10px; padding: 20px;width:auto;" align="center" id="diagnorecomm">
			<tr>
				<?php 
				if($recomm != "")
				{
				?>
				<td style="text-align:center;">			
					<table>					
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:30px;"><?php echo getString(159) ?>
							
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center; vertical-align:top;">
							
								<textarea style="background:#eee;; height: 50px; width: 100px; max-width:600px; min-width:500px; min-height:200px;text-align:center;" readonly="readonly"><?php echo strip_tags($recomm);?></textarea>
								
							</td>
							
						</tr>
					</table>
					
				</td> -->
				<?php
				// }else{
				// 	echo '<td style="text-align:center;font-size:30px;">Pas de traitments proposés pour cette consultation</td>';
				// }
				
				if($comptMedSurge!=0)
				{
				?>
				<td style="text-align:center;width:50%">
					
					<div style="overflow:auto;height:auto; padding:5px; margin-bottom:30px;">
					
						<table style="width:70%;" class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr style="height:45px;">
									<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(279); ?></th>
									<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19); ?></th>
								</tr> 
							</thead> 
						
							<tbody>	
								<?php
								try
								{
									while($ligneMedSurge=$resultMedSurge->fetch())
									{								
										$idassuSurge=$ligneMedSurge->id_assuSurge;
										
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

											if($ligneNomAssuSurge=$getAssuSurge->fetch())
											{
												$presta_assuSurge='prestations_'.$ligneNomAssuSurge->nomassurance;
											}
										}
										
								?>
									<tr style="text-align:center;">
										
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
									
										<?php									
										$resultatsPresta=$connexion->prepare('SELECT * FROM med_surge ms, '.$presta_assuSurge.' p WHERE ms.id_prestationSurge=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
										$resultatsPresta->execute(array(
										'idConsu'=>$ligneMedSurge->id_prestationSurge
										));

										$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);
										
										if($lignePresta=$resultatsPresta->fetch())
										{
										?>
										
										<?php 
											if($lignePresta->namepresta!="")
											{
												echo $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta;
											}
										}
											
											echo $ligneMedSurge->autrePrestaS;
										?>
										</td>
										
										<td style="padding:10px; text-align:center;">
										<?php
																	
										$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
										$resultatsMed->execute(array(
										'idMed'=>$ligneMedSurge->id_uM
										));

										$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
										{
											if($ligneMedSurge->id_uM==$ligneMed->id_u)
											{
												echo $ligneMed->full_name;
											}else{
												echo '';
											}
										}
										$resultatsMed->closeCursor();
										?>						
										</td>	
									</tr>

								<?php
									}
									$resultMedSurge->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>	
								
				</td>	
				<?php	
				}
				?>
			</tr>
		</table>
		<?php	
		}
		?>
		
		<table align="center">
			<tr>
				<td style="vertical-align:top;">

					<table class="cons-table" style="margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>
								
					<?php 
					if($hospitalized != NULL)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:15px;"><?php echo 'Hospitalisé'; ?>
									<input type="checkbox" checked="checked" disabled="disabled"/>
								</span>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center; vertical-align:top;">
							<?php
							if($motifhospitalized !="")
							{
							?>
								Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" readonly="readonly"><?php echo strip_tags($motifhospitalized);?></textarea>
							<?php
							}/* else{
							?>
								<span style="font-weight:bold;font-size:15px;"><?php echo '---'; ?>
								</span>
							<?php
							} */
							?>
							</td>
							
						</tr>
					<?php
					}
					?>					
					</table>
				</td>
				
				<td style="vertical-align:top;">

					<table class="cons-table" style="margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>
								
					<?php 
					if($physio != NULL)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:15px;"><?php echo 'Physioterapy'; ?>
									<input type="checkbox" checked="checked" disabled="disabled"/>
								</span>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center; vertical-align:top;">
							<?php
							if($motifphysio !="")
							{
							?>
								Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" readonly="readonly"><?php echo strip_tags($motifphysio);?></textarea>
								
							<?php
							}/* else{
							?>
								<span style="font-weight:bold;font-size:15px;"><?php echo '---'; ?>
								</span>
							<?php
							} */
							?>								
							</td>
							
						</tr>
					<?php
					}
								
					if($comptMedKine!=0)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top;">
							<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px" id="kinetable">
							
								<table class="tablesorter" cellspacing="0">	
									<thead> 
										<tr style="height:45px;">
											<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo 'Actes' ?></th>
											<th style="border-radius:0; color:#333; background:#ccc !important">Date</th>
											<th style="border-radius:0; color:#333; background:#ccc !important"><?php echo 'Done by'; ?></th>
										</tr> 
									</thead>
								
									<tbody>	
										<?php
										try
										{
											while($ligneMedKine=$resultMedKine->fetch())
											{							
												$idassuKine=$ligneMedKine->id_assuKine;

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

													if($ligneNomAssuKine=$getAssuKine->fetch())
													{
														$presta_assuKine='prestations_'.$ligneNomAssuKine->nomassurance;
													}
												}

										?>
											<tr style="text-align:center;">
												<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
												<?php									
												$resultatsPresta=$connexion->prepare('SELECT *FROM med_kine mk, '.$presta_assuKine.' p WHERE mk.id_prestationKine=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
												$resultatsPresta->execute(array(
												'idConsu'=>$ligneMedKine->id_prestationKine
												));

												$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
													
												if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
												{ 
													if($lignePresta->namepresta!="")
													{
														echo $lignePresta->namepresta;
													}else{
														echo $lignePresta->nompresta;
													}
												}
												
													echo $ligneMedKine->autrePrestaK;
												?>
												</td>
												
												<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
													<?php 
													if($ligneMedKine->datekine!="0000-00-00")
													{
														echo date('d-M-Y', strtotime($ligneMedKine->datekine));
													}else{
														echo '';
													}								
													?>
												</td>					
												<td style="padding:10px; text-align: center;">
												<?php
												$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
												$resultatsMed->execute(array(
												'idMed'=>$ligneMedKine->id_uK
												));

												$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
													
												while($ligneMed=$resultatsMed->fetch())
												{
													if($ligneMedKine->id_uK==$ligneMed->id_u)
													{
														echo $ligneMed->full_name;
													}else{
														echo '';
													}
												}
												$resultatsMed->closeCursor();
												?>						
												</td>			
												
											</tr>
										<?php
											}
											$resultMedKine->closeCursor();
										}

										catch(Excepton $e)
										{
											echo 'Erreur:'.$e->getMessage().'<br/>';
											echo'Numero:'.$e->getCode();
										}
										?>
									</tbody>
								</table>
							</div>	
							</td>
						</tr>
					<?php			
					}
					?>
					</table>
					
				
				</td>
				
				<?php			
				if($comptMedOrtho!=0)
				{
				?>
				<td style="text-align:center; vertical-align:top;">
				
					<span style="position:relative; font-size:250%;margin-bottom: 2px;"><?php echo 'P&O'; ?></span>
					
					<div style="overflow:auto;height:auto; margin-bottom:30px; padding:5px;" id="orthotable">
					
						<table class="tablesorter" cellspacing="0"> 
							
							<thead> 
								<tr style="height:45px;">
									<th style="width:15%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Matériel demandé' ?></th>
									<th style="width:10%; border-radius:0; color:#333; background:#ccc !important">Date</th>
									<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo 'Done by'; ?></th>						
									<th style="width:20%; border-radius:0; color:#333; background:#ccc !important"><?php echo getString(19) ?></th>
								</tr> 
							</thead> 
						
						
							<tbody>	
								<?php
								try
								{
									while($ligneMedOrtho=$resultMedOrtho->fetch())
									{							
										$idassuOrtho=$ligneMedOrtho->id_assuOrtho;

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

											if($ligneNomAssuOrtho=$getAssuOrtho->fetch())
											{
												$presta_assuOrtho='prestations_'.$ligneNomAssuOrtho->nomassurance;
											}
										}

								?>
									<tr style="text-align:center;">
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;" <?php if($ligneMedOrtho->resultatsOrtho !=""){ echo 'colspan=3';}?>>
										<?php									
										$resultatsPresta=$connexion->prepare('SELECT *FROM med_ortho mo, '.$presta_assuOrtho.' p WHERE mo.id_prestationOrtho=p.id_prestation AND p.id_prestation=:idConsu') or die( print_r($connexion->errorInfo()));
										$resultatsPresta->execute(array(
										'idConsu'=>$ligneMedOrtho->id_prestationOrtho
										));

										$resultatsPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										if($lignePresta=$resultatsPresta->fetch())//on recupere la liste des éléments
										{ 
											if($lignePresta->namepresta!="")
											{
												echo $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta;
											}
										}
										
											echo $ligneMedOrtho->autrePrestaO;
										?>
										</td>
																
										<td style="padding:10px; text-align: center;">
										<?php
										if($ligneMedOrtho->dateortho!='0000-00-00')
										{
											echo $ligneMedOrtho->dateortho;
										}else{
											echo '';
										}
										?>						
										</td>			
																
										<td style="padding:10px; text-align: center;">
										<?php
										$resultatsOrtho=$connexion->prepare('SELECT *FROM utilisateurs u, orthopedistes o WHERE u.id_u=o.id_u AND o.id_u=:idOrtho') or die( print_r($connexion->errorInfo()));
										$resultatsOrtho->execute(array(
										'idOrtho'=>$ligneMedOrtho->id_uO
										));

										$resultatsOrtho->setFetchMode(PDO::FETCH_OBJ);
											
										while($ligneOrtho=$resultatsOrtho->fetch())
										{
											if($ligneMedOrtho->id_uO==$ligneOrtho->id_u)
											{
												echo $ligneOrtho->full_name;
											}else{
												echo '';
											}
										}
										$resultatsOrtho->closeCursor();
										?>						
										</td>			
																
										<td style="padding:10px; text-align: center;">
										<?php
										$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
										$resultatsMed->execute(array(
										'idMed'=>$ligneMedOrtho->id_uM
										));

										$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										while($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
										{
											if($ligneMedOrtho->id_uM==$ligneMed->id_u)
											{
												echo $ligneMed->full_name;
											}else{
												echo '';
											}
										}
										$resultatsMed->closeCursor();
										?>						
										</td>			
										
									</tr>
									<?php
									if($ligneMedOrtho->resultatsOrtho !="")
									{
									?>
									<tr style="text-align:center;background:#eee">
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
											<textarea style="border-top:none; border-bottom:none; border-left:1px solid #ccc; border-right:1px solid #ccc; max-height: 300px; max-width: 500px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; height: 80px; min-height: 80px;"readonly='readonly'><?php echo strip_tags($ligneMedOrtho->resultatsOrtho)?></textarea>
										</td>
										
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
											<?php 
											if($ligneMedOrtho->dateortho!="0000-00-00")
											{
												echo date('d-M-Y', strtotime($ligneMedOrtho->dateortho));
											}else{
												echo '';
											}								
											?>
										</td>
											
										<td style="padding:10px; text-align: center; border-right: 1px solid #ccc;">
										<?php 

										$resultatsOrtho=$connexion->prepare('SELECT *FROM utilisateurs u, medecins x WHERE u.id_u=x.id_u AND x.id_u=:idX') or die( print_r($connexion->errorInfo()));
										$resultatsOrtho->execute(array(
										'idX'=>$ligneMedOrtho->id_uX
										));

										$resultatsOrtho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
											
										if($ligneOrtho=$resultatsOrtho->fetch())//on recupere la liste des éléments
										{
											echo $ligneOrtho->full_name;
										}else{
											echo '';
										}
										?>
										</td>
																
										<td style="padding:10px; text-align: center;">						
										</td>			
										
									</tr>
									<?php
									}
									?>
									
								<?php
									}
									$resultMedOrtho->closeCursor();
								}

								catch(Excepton $e)
								{
									echo 'Erreur:'.$e->getMessage().'<br/>';
									echo'Numero:'.$e->getCode();
								}
								?>
							</tbody>
						</table>
					</div>	
				</td>
				<?php	
				}/* else{
				?>
					<table style="margin-bottom: 30px;" class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th style="background: linear-gradient(black, gray);color:white;font-size:150%;padding:5px;"><?php echo getString(107) ?></th>
						</tr>
					</thead> 		
					</table>
				<?php
				} */	
				
				?>
				
				<td style="vertical-align:top;">

					<table class="cons-table" style="margin: 20px auto; background: #ddd none repeat scroll 0px 0px; border: 1px solid rgb(238, 238, 238); border-radius: 4px; width:auto;"  cellpadding=3>
								
					<?php 
					if($transfer != NULL)
					{
					?>
						<tr>
							<td style="text-align:center; vertical-align:top; margin-top:100px;">
								<span style="font-weight:bold;font-size:15px;"><?php echo 'Transfer'; ?>
									<input type="checkbox" checked="checked" disabled="disabled"/>
								</span>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:center;">
							<?php
							if($motiftransfer !="")
							{
							?>	
								Motif <textarea style="background: #eee; margin:auto; height:50px; width:100px; max-width:180px; max-height:250px; min-height:50px; min-width:100px; text-align:center;" readonly="readonly"><?php echo strip_tags($motiftransfer);?></textarea>
								
							<?php
							}/* else{
							?>
								<span style="font-weight:bold;font-size:15px;"><?php echo '---'; ?>
								</span>
							<?php
							} */
							?>	
							</td>
							
						</tr>
					<?php
					}
					?>					
					</table>
				</td>	
				<!-- <tr><button class="btn btn-large" style="width: 50%;font-family: arial;">Share Patient To Other Doctor</button></tr>			 -->
			</tr>
		</table>
	</div>

<?php
}

		?>
















	</div>
<?php }?>
</body>
</html>