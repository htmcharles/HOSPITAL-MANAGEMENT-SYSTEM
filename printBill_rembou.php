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
// echo showBN();


	$numPa=$_GET['num'];
	$consuId=$_GET['idconsu'];	
	
	
	$checkIdBill=$connexion->prepare('SELECT *FROM bills b, consultations c WHERE b.id_bill=c.id_factureConsult AND c.id_factureConsult=:idbill ORDER BY b.id_bill LIMIT 1');

	$checkIdBill->execute(array(
	'idbill'=>$_GET['idbill']
	));

	$comptidBill=$checkIdBill->rowCount();

	// echo $comptidBill;
	
	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		$idBilling = $ligne->id_bill;
		
		$numbill = $ligne->numbill;
		$createBill = 0;
		
		// echo $idBilling;
		
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
$idCashier=$_SESSION['id'];

if($connected==true AND isset($_SESSION['codeCash']))
{
	
	// echo 'New '.$idBilling;
		
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
		$code->setLabel('# '.$numbill.' #');
		$code->parse(''.$numbill.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecashier.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
<?php
$barcode = '

	<table style="width:100%">
		
		<tr>
			<td style="text-align:left; width:60%">
			  <table>
				<tbody>
				  <tr>
					<td>
					  <img src="images/pc_logo.jpg">
					</td>
					<td style="text-align:left">
					  <span style="border-bottom:2px solid #ccc; font-size:120%; font-weight:900">POLYCLINIC DE L\'ETOILE</span> <br>
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

			$resultatConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');		
			$resultatConsu->execute(array(
			'idconsu'=>$consuId
			));
			
			$resultatConsu->setFetchMode(PDO::FETCH_OBJ);

			if($ligneConsu=$resultatConsu->fetch())
			{
				$dateconsu= date('d-M-Y', strtotime($ligneConsu->dateconsu));
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
					Date of Consultation: <span style="font-weight:bold">'.$dateconsu.'</span>
					
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
						->setCellValue('B4', ''.$insurance.' '.$percentpartient.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.dateconsu=:dateconsu AND c.numero=:num AND c.id_assuConsu=:idassu ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'consuId'=>$consuId,
		'num'=>$numPa,
		'idassu'=>$idassurance,
		'dateconsu'=>date('Y-m-d', strtotime($dateconsu))
		));

		$resultConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		
			
	
		/*-------Requête pour AFFICHER Med_consult-----------*/
	
		if(isset($_POST['prixrembouServ']))
		{
			
			$prixremboumc = array();
			$idmc = array();

			
			foreach($_POST['prixrembouServ'] as $valmc)
			{
				$prixremboumc[] = $valmc;
			}
			
			foreach($_POST['idrembouServ'] as $valeurmc)
			{
				$idmc[] = $valeurmc;
			}
			
			for($i=0;$i<sizeof($idmc);$i++)
			{
				$results=$connexion->query('SELECT *FROM med_consult mc WHERE mc.id_medconsu='.$idmc[$i].'');
			
				$results->setFetchMode(PDO::FETCH_OBJ);
					
				if($ligne=$results->fetch())
				{
					$updateprixServ=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.prixrembouConsu='.$prixremboumc[$i].' WHERE mc.id_medconsu='.$idmc[$i].'');
				}

			}
	
		}
				
			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_consuMed=:idconsu AND mc.dateconsu!="0000-00-00" AND mc.id_assuServ=:idassu AND mc.id_factureMedConsu!=0 ORDER BY mc.id_medconsu');		
			$resultMedConsult->execute(array(
			'num'=>$numPa,
			'idassu'=>$idassurance,
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsult=$resultMedConsult->rowCount();
		
			$TotalMedConsult = 0;
			$TotalMedConsultRembou = 0;
		
		
		/*-------Requête pour AFFICHER Med_inf-----------*/
	
		if(isset($_POST['prixrembouInf']))
		{
			
			$prixremboumi = array();
			$idremboumi = array();

			foreach($_POST['prixrembouInf'] as $valmi)
			{
				$prixremboumi[] = $valmi;
			}
			
			foreach($_POST['idrembouInf'] as $valeurmi)
			{
				$idremboumi[] = $valeurmi;
			}
			
			
			for($i=0;$i<sizeof($idremboumi);$i++)
			{
				
				$results=$connexion->query('SELECT *FROM med_inf mi WHERE mi.id_medinf='.$idremboumi[$i].'');
				
				$results->setFetchMode(PDO::FETCH_OBJ);
					
				if($ligne=$results->fetch())
				{
					$updateprixInf=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.prixrembouInf='.$prixremboumi[$i].' WHERE mi.id_medinf='.$idremboumi[$i].'');
					
				}
				
			}
	
		}
			
			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_consuInf=:idconsu AND mi.id_assuInf=:idassu AND mi.id_factureMedInf!=0 ORDER BY mi.id_medinf');		
			$resultMedInf->execute(array(
			'num'=>$numPa,
			'idassu'=>$idassurance,
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

			$comptMedInf=$resultMedInf->rowCount();
		
			$TotalMedInf = 0;
			$TotalMedInfRembou = 0;
		
		
		/*-------Requête pour AFFICHER Med_labo-----------*/
	
		if(isset($_POST['prixrembouLabo']))
		{			

			$prixrembouml = array();
			$idrembouml = array();

			
			foreach($_POST['prixrembouLabo'] as $valml)
			{
				$prixrembouml[] = $valml;
			}
						
			foreach($_POST['idrembouLabo'] as $valeurml)
			{
				$idrembouml[] = $valeurml;
			}
			
			for($i=0;$i<sizeof($idrembouml);$i++)
			{
				
				$results=$connexion->query('SELECT *FROM med_labo ml WHERE ml.id_medlabo='.$idrembouml[$i].'');
				
				$results->setFetchMode(PDO::FETCH_OBJ);
					
				if($ligne=$results->fetch())
				{					
					$updateprixLabo=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.prixrembouLabo='.$prixrembouml[$i].' WHERE ml.id_medlabo='.$idrembouml[$i].'');
		
				}
			}
		
		}	
		
			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_consuLabo=:idconsu AND ml.id_factureMedLabo!=0 ORDER BY ml.id_medlabo');		
			$resultMedLabo->execute(array(
			'num'=>$numPa,
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

			$comptMedLabo=$resultMedLabo->rowCount();			
			
			$TotalMedLabo = 0;
			$TotalMedLaboRembou = 0;
		
		
		/*-------Requête pour AFFICHER Med_radio-----------*/
	
		if(isset($_POST['prixrembouRadio']))
		{
									
			$prixremboumr = array();
			$idremboumr = array();


			foreach($_POST['prixrembouRadio'] as $valmr)
			{
				$prixremboumr[] = $valmr;
			}
			
			foreach($_POST['idrembouRadio'] as $valeurmr)
			{
				$idremboumr[] = $valeurmr;
			}
			
			
			for($i=0;$i<sizeof($idremboumr);$i++)
			{				
				$results=$connexion->query('SELECT *FROM med_radio mr WHERE mr.id_medradio='.$idremboumr[$i].'');
				
				$results->setFetchMode(PDO::FETCH_OBJ);
					
				if($ligne=$results->fetch())
				{
					$updateprixRadio=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.prixrembouRadio='.$prixremboumr[$i].' WHERE mr.id_medradio='.$idremboumr[$i].'');
					
				}
			}
		
		}
		
			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_consuRadio=:idconsu AND mr.id_factureMedRadio!=0 ORDER BY mr.id_medradio');		
			$resultMedRadio->execute(array(
			'num'=>$numPa,
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

			$comptMedRadio=$resultMedRadio->rowCount();			
			
			$TotalMedRadio = 0;
			$TotalMedRadioRembou = 0;
		
		
		/*-------Requête pour AFFICHER Med_consom-----------*/
		
		if(isset($_POST['prixrembouConsom']))
		{
		
			$prixremboumco = array();
			$idremboumco = array();


			foreach($_POST['prixrembouConsom'] as $valmco)
			{
				$prixremboumco[] = $valmco;
			}
						
			foreach($_POST['idrembouConsom'] as $valeurmco)
			{
				$idremboumco[] = $valeurmco;
			}
			
			
			for($i=0;$i<sizeof($idremboumco);$i++)
			{
				$results=$connexion->query('SELECT *FROM med_consom mco WHERE mco.id_medconsom='.$idremboumco[$i].'');
				
				$results->setFetchMode(PDO::FETCH_OBJ);
					
				if($ligne=$results->fetch())
				{
					$updateprixConsom=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.prixrembouConsom='.$prixremboumco[$i].' WHERE mco.id_medconsom='.$idremboumco[$i].'');
				}
			}
				
		}
			
			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_consuConsom=:idconsu AND mco.id_factureMedConsom!=0 ORDER BY mco.id_medconsom');		
			$resultMedConsom->execute(array(
			'num'=>$numPa,
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

			$comptMedConsom=$resultMedConsom->rowCount();
		
			$TotalMedConsom = 0;
			$TotalMedConsomRembou = 0;
			
			
		/*-------Requête pour AFFICHER Med_medoc-----------*/
	
		if(isset($_POST['prixrembouMedoc']))
		{
			
			$prixremboumdo = array();
			$idremboumdo = array();

			
			foreach($_POST['prixrembouMedoc'] as $valmdo)
			{
				$prixremboumdo[] = $valmdo;
			}
			
			foreach($_POST['idrembouMedoc'] as $valeurmdo)
			{
				$idremboumdo[] = $valeurmdo;
			}
			
			
			for($i=0;$i<sizeof($idremboumdo);$i++)
			{
			
				$results=$connexion->query('SELECT *FROM med_medoc mdo WHERE mdo.id_medmedoc='.$idremboumdo[$i].'');
					
				$results->setFetchMode(PDO::FETCH_OBJ);
					
				if($ligne=$results->fetch())
				{
					$updateprixMedoc=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.prixrembouMedoc='.$prixremboumdo[$i].' WHERE mdo.id_medmedoc='.$idremboumdo[$i].'');
				
				}
				
			}
		
		}
				
			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_consuMedoc=:idconsu AND mdo.id_factureMedMedoc!=0 ORDER BY mdo.id_medmedoc');		
			$resultMedMedoc->execute(array(
			'num'=>$numPa,
			'idconsu'=>$_GET['idconsu']
			));
			
			$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

			$comptMedMedoc=$resultMedMedoc->rowCount();			
			
			$TotalMedMedoc = 0;
			$TotalMedMedocRembou = 0;
	
	?>
	
	<table style="width:100%; margin:20px auto auto;"> 
		<tr> 
			<td style="text-align:left; width:33%;">
				<h4><?php echo $annee;?></h4>
			</td>
			
			<td style="text-align:center; width:33%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo $numbill;?></h2>
			</td>
			
			<td style="text-align:right;width:33%;">
			
				<form method="post" action="printBill_rembou.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&dateconsu=<?php echo $dateconsu;?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?>&updatebill=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td class="buttonBill">
				<a href="formRembourser.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="formRembourser.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?>&finishbtn=ok<?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?>&createBill=<?php echo $createBill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
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
			
			$TotalGnlPriceRembou=0;
			$TotalGnlPatientPriceRembou=0;
			$TotalGnlInsurancePriceRembou=0;
			
			$i=0;
			$x=0;
			$y=0;
			$z=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Type of consultation')
							->setCellValue('C8', 'Price')
							->setCellValue('D8', 'Patient Price')
							->setCellValue('E8', 'Insurance Price');
				

		$typeconsult = '<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;"> 
				<thead> 
					<tr>
						<th style="width:30%;">Type of Consultation</th>
						<th style="width:15%;">Balance</th>
						<th style="width:5%;"></th>
						<th style="width:10%;">Percent</th>
						<th style="width:15%;">Patient balance</th>
						<th style="width:5%;"></th>
						<th style="width:15%;">Insurance balance</th>
						<th style="width:5%;"></th>
					</tr> 
				</thead> 


				<tbody>';
				
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;
			
			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			
			
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$billpercent=$ligneConsult->insupercent;
						
						$idassu=$ligneConsult->id_assuConsu;					
		$typeconsult .= '<tr style="text-align:center;">
						<td>';
												
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
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($comptPresta!=0)
						{
							if($lignePresta=$resultPresta->fetch())
							{
								if(isset($_POST['pourcentage']))
								{
									$resultats=$connexion->prepare('UPDATE consultations SET insupercent=:percent WHERE id_consu=:idConsult');
						
									$resultats->execute(array(
									'percent'=>$_POST['pourcentage'],
									'idConsult'=>$_GET['idconsu']
									
									))or die( print_r($connexion->errorInfo()));
								}
								
								if($lignePresta->namepresta!='')
								{
									$nameprestaConsult=$lignePresta->namepresta;
									
			$typeconsult .= $nameprestaConsult.'</td>';
								}else{	
								
									if($lignePresta->nompresta!='')
									{
										$nameprestaConsult=$lignePresta->nompresta;
			$typeconsult .= $nameprestaConsult.'</td>';
									}
								}
								
								$prixPresta = $ligneConsult->prixtypeconsult;
								
			$typeconsult .= '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							
							<td></td>
							<td>'.$ligneConsult->insupercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';
		
				
						$TotalConsult=$TotalConsult + $prixPresta;
						
		$typeconsult .= '<td>';

							$patientPrice=($prixPresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
		$typeconsult .= $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<td>';
						
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice = $TotaluapPrice + $uapPrice;
							
		$typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>';
						
		$typeconsult .= '<td style="font-weight:700">Payed</td>
					
					</tr>';
							
							}
							
						}else{
						
							if(isset($_POST['newprixtypeconsult']))
							{
								if(isset($_POST['pourcentage']))
								{
									$resultats=$connexion->prepare('UPDATE consultations SET prixautretypeconsult=:prixautretypeconsu,insupercent=:percent WHERE id_consu=:idConsult');
						
									$resultats->execute(array(
									'prixautretypeconsu'=>$_POST['newprixtypeconsult'],
									'percent'=>$_POST['pourcentage'],
									'idConsult'=>$_GET['idconsu']
									
									))or die( print_r($connexion->errorInfo()));
								}
							}
							
							$resultNewPresta=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');	
							
							$resultNewPresta->execute(array(
							'idconsu'=>$_GET['idconsu']
							));
							
							$resultNewPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptNewPresta=$resultNewPresta->rowCount();
							
							if($lignePresta=$resultNewPresta->fetch())
							{
								$nameprestaConsult=$lignePresta->autretypeconsult;
									
			$typeconsult .= $nameprestaConsult.'</td>';
			
								$prixPresta = $lignePresta->prixautretypeconsult;
								
		$typeconsult .= '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
			';
			
			
				
			$TotalConsult=$TotalConsult + $prixPresta;
			
		$typeconsult .= '<td>';

				$patientPrice=($prixPresta * $billpercent)/100;
				$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
				
			
		$typeconsult .= $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>
					<td>';
			
				$uapPrice= $prixPresta - $patientPrice;
				$TotaluapPrice= $TotaluapPrice + $uapPrice;
				
		$typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
			</td>
		</tr>';
				

							}
						}
						
						$arrayConsult[$i][0]=$nameprestaConsult;
						$arrayConsult[$i][1]=$prixPresta;
						$arrayConsult[$i][2]=$patientPrice;
						$arrayConsult[$i][3]=$uapPrice;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','B9');
		
					}

		/* $typeconsult .= '<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">'.$TotalConsult;
		*/						
				$TotalGnlPrice=$TotalGnlPrice + $TotalConsult;
				
		/*		
		 $typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">'.$TotalpatientPrice;
		*/						
				$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;			

		
		/*
		$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">'.$TotaluapPrice;
		*/						
				$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
				

		/*
		$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>';
		 */
		 
$typeconsult .= '</tbody>
			</table>';

					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(9+$i).'', ''.$TotalConsult.'')
								->setCellValue('D'.(9+$i).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(9+$i).'', ''.$TotaluapPrice.'');

				echo $typeconsult;
			
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
						<th style="width:30%">Services</th>
						<th style="width:15%;">Balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:10%;">Percent</th>
						<th style="width:15%;">Patient balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:15%;">Insurance balance</th>
						<th style="width:5%;">Refunded</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;
			
			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			

			
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
						<td>
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
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedConsult=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}

						$prixPrestaRembou=$ligneMedConsult->prixrembouConsu;
						
						$prixPresta = $ligneMedConsult->prixprestationConsu - $prixPrestaRembou;						
						
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>
						
						<td>
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$patientPriceRembou=$ligneMedConsult->prixrembouConsu;
							
							$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
							
							echo $patientPriceRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>						
		
						<td>
							<?php 
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
								$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
								$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
								
								echo $uapPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
						if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->prixautreConsu!=0)
						{
							$nameprestaMedConsult=$ligneMedConsult->autreConsu;
							echo $ligneMedConsult->autreConsu.'</td>';
							
							$prixPrestaRembou=$ligneMedConsult->prixrembouConsu;
						
							$prixPresta = $ligneMedConsult->prixautreConsu - $prixPrestaRembou;
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
							
						<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>
					
						<td>
						<?php 
							$patientPrice=($prixPresta * $billpercent)/100;							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php 
							$patientPriceRembou=$ligneMedConsult->prixrembouConsu;
							
							$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
							
							echo $patientPriceRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>						
		
						<td>
							<?php 
							$uapPrice= $prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>						
						</td>
						
						<td>
							<?php 
							$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
				$TotalMedConsult=$TotalMedConsult + $prixPresta;
				$TotalMedConsultRembou=$TotalMedConsultRembou + $prixPrestaRembou;		
						$arrayMedConsult[$x][0]=$nameprestaMedConsult;
						$arrayMedConsult[$x][1]=$prixPresta;
						$arrayMedConsult[$x][2]=$patientPrice;
						$arrayMedConsult[$x][3]=$uapPrice;
						
						$x++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedConsult,'','B'.(12+$i).'');
		
					}
			?>		
					</tr>	
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsult.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsultRembou.'';

								$TotalGnlPriceRembou=$TotalGnlPriceRembou + $TotalMedConsultRembou;							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
																
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPriceRembou.'';
																
								$TotalGnlPatientPriceRembou=$TotalGnlPatientPriceRembou + $TotalpatientPriceRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPriceRembou.'';
								
								$TotalGnlInsurancePriceRembou=$TotalGnlInsurancePriceRembou + $TotaluapPriceRembou;
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
						<th style="width:30%">Nursing Care</th>
						<th style="width:15%;">Balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:10%;">Percent</th>
						<th style="width:15%;">Patient balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:15%;">Insurance balance</th>
						<th style="width:5%;">Refunded</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;
			
			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			
			
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
						<td>
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
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedInf=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							
							$prixPrestaRembou=$ligneMedInf->prixrembouInf;
							
							$prixPresta = $ligneMedInf->prixprestation - $prixPrestaRembou;						
							
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneMedInf->insupercentInf;?>%</td>
						
						<td>
							<?php 
								$patientPrice=($prixPresta * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
								$patientPriceRembou=$ligneMedInf->prixrembouInf;
								$TotalpatientPriceRembou=$TotalpatientPrice + $patientPriceRembou;
								
								echo $patientPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
							
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						if($ligneMedInf->id_prestation==NULL AND $ligneMedInf->prixautrePrestaM!=0)
						{
							$nameprestaMedInf=$ligneMedInf->autrePrestaM;
							echo $ligneMedInf->autrePrestaM.'</td>';
							
							
							$prixPrestaRembou=$ligneMedInf->prixrembouInf;
							
							$prixPresta = $ligneMedInf->prixautrePrestaM - $prixPrestaRembou;						
							
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneMedInf->insupercentInf;?>%</td>
						
						<td>
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
								$patientPriceRembou=$ligneMedInf->prixrembouInf;
								$TotalpatientPriceRembou=$TotalpatientPrice + $patientPriceRembou;
								
								echo $patientPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
							
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					<?php
						}
									
				$TotalMedInf=$TotalMedInf + $prixPresta;
				$TotalMedInfRembou=$TotalMedInfRembou + $prixPrestaRembou;	
							
						$arrayMedInf[$y][0]=$nameprestaMedInf;
						$arrayMedInf[$y][1]=$prixPresta;
						$arrayMedInf[$y][2]=$patientPrice;
						$arrayMedInf[$y][3]=$uapPrice;
						
						$y++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedInf,'','B'.(15+$i+$x).'');
					}
					?>
					</tr>	
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedInf.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedInfRembou.'';
								
								$TotalGnlPriceRembou=$TotalGnlPriceRembou + $TotalMedInfRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPriceRembou.'';
								
								$TotalGnlPatientPriceRembou=$TotalGnlPatientPriceRembou + $TotalpatientPriceRembou;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPriceRembou.'';
								
								$TotalGnlInsurancePriceRembou=$TotalGnlInsurancePriceRembou + $TotaluapPriceRembou;						
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
						<th style="width:30%">Labs</th>
						<th style="width:15%;">Balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:10%;">Percent</th>
						<th style="width:15%;">Patient balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:15%;">Insurance balance</th>
						<th style="width:5%;">Refunded</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;
			
			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			
			
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
						<td>
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
							
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedLabo->id_prestationExa
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedLabo=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedLabo=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
						
							$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
							
							$prixPresta = $ligneMedLabo->prixprestationExa - $prixPrestaRembou;						
							
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
						
						<td>
							<?php 
								$patientPrice=($prixPresta * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
								$patientPriceRembou=$ligneMedLabo->prixrembouLabo;
								$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
								
								echo $patientPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php 
							$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						if($ligneMedLabo->id_prestationExa==NULL AND $ligneMedLabo->prixautreExamen!=0)
						{
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
							echo $ligneMedLabo->autreExamen.'</td>';
							
							
							$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
							
							$prixPresta = $ligneMedLabo->prixautreExamen - $prixPrestaRembou;						
							
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
						
						<td>
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
							echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$patientPriceRembou=$ligneMedLabo->prixrembouLabo;
							$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
							
							echo $patientPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
									
				$TotalMedLabo=$TotalMedLabo + $prixPresta;
				$TotalMedLaboRembou=$TotalMedLaboRembou + $prixPrestaRembou;		
						$arrayMedLabo[$z][0]=$nameprestaMedLabo;
						$arrayMedLabo[$z][1]=$prixPresta;
						$arrayMedLabo[$z][2]=$patientPrice;
						$arrayMedLabo[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedLabo,'','B'.(18+$i+$x+$y).'');
					}
			?>
					</tr>	
					<tr style="text-align:center;">
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedLabo.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedLaboRembou.'';
								
								$TotalGnlPriceRembou=$TotalGnlPriceRembou + $TotalMedLaboRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPriceRembou.'';
								
								$TotalGnlPatientPriceRembou=$TotalGnlPatientPriceRembou + $TotalpatientPriceRembou;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPriceRembou.'';
								
								$TotalGnlInsurancePriceRembou=$TotalGnlInsurancePriceRembou + $TotaluapPriceRembou;						
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
						<th style="width:30%">Radiologie</th>
						<th style="width:15%;">Balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:10%;">Percent</th>
						<th style="width:15%;">Patient balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:15%;">Insurance balance</th>
						<th style="width:5%;">Refunded</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;
			
			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			
			
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
						<td>
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
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedRadio=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
							
							$prixPresta = $ligneMedRadio->prixprestationRadio - $prixPrestaRembou;						
							
							?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
						
						<td>
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
							echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$patientPriceRembou=$ligneMedRadio->prixrembouRadio;
							$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
							
							echo $patientPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
						if($ligneMedRadio->id_prestationRadio==NULL AND $ligneMedRadio->prixautreRadio!=0)
						{
							$nameprestaMedRadio=$ligneMedRadio->autreRadio;
							echo $ligneMedRadio->autreRadio.'</td>';
							
							
							$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
							
							$prixPresta = $ligneMedRadio->prixautreRadio - $prixPrestaRembou;						
							
						?>
							
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
						
						<td>
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
							echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$patientPriceRembou=$ligneMedRadio->prixrembouRadio;
							$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
							
							echo $patientPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
					
				$TotalMedRadio = $TotalMedRadio + $prixPresta;
				$TotalMedRadioRembou = $TotalMedRadioRembou + $prixPrestaRembou;
							
						$arrayMedRadio[$z][0]=$nameprestaMedRadio;
						$arrayMedRadio[$z][1]=$prixPresta;
						$arrayMedRadio[$z][2]=$patientPrice;
						$arrayMedRadio[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedRadio,'','B'.(18+$i+$x+$y).'');
					}
			?>
					</tr>	
					<tr style="text-align:center;">
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedRadio.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedRadioRembou.'';
								
								$TotalGnlPriceRembou=$TotalGnlPriceRembou + $TotalMedRadioRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPriceRembou.'';
								
								$TotalGnlPatientPriceRembou=$TotalGnlPatientPriceRembou + $TotalpatientPriceRembou;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPriceRembou.'';
								
								$TotalGnlInsurancePriceRembou=$TotalGnlInsurancePriceRembou + $TotaluapPriceRembou;						
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
						<th style="width:10%">Consommables</th>
						<th style="width:10%">Qty</th>
						<th style="width:10%">P/U</th>
						<th style="width:15%;">Balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:10%;">Percent</th>
						<th style="width:15%;">Patient balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:15%;">Insurance balance</th>
						<th style="width:5%;">Refunded</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;

			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			
			
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
								<td>
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
									echo $prixPresta.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<?php							
								$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
						
								$balance=($prixPresta*$qteConsom) - $prixPrestaRembou;
								?>	
										
								<td>
									<?php echo $balance;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
										
								<td>
									<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
								
								<td>
									<?php 
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
									echo $patientPrice;
									
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<td>
									<?php
									$uapPrice= $balance - $patientPrice;									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;								
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
								<td>
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
									echo $prixPresta.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<?php							
								$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
						
								$balance=($prixPresta*$qteConsom) - $prixPrestaRembou;
								?>	
										
								<td>
									<?php echo $balance;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
										
								<td>
									<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
							
								<td>
									<?php 
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
									echo $patientPrice;
									
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<td>
									<?php 
									$patientPriceRembou=$ligneMedConsom->prixrembouConsom;
									$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
									
									echo $patientPriceRembou.'';
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
						
								<td>
									<?php
									$uapPrice= $balance - $patientPrice;									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
									echo $uapPrice;					
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
										
								<td>
									<?php 
									$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
									$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
									
									echo $uapPriceRembou.'';
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
			<?php
						}
						
				$TotalMedConsom= $TotalMedConsom + $balance;
				$TotalMedConsomRembou = $TotalMedConsomRembou + $prixPrestaRembou;
							
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
						<td></td>
						<td></td>
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsom.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsomRembou.'';
								
								$TotalGnlPriceRembou=$TotalGnlPriceRembou + $TotalMedConsomRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPriceRembou.'';
								
								$TotalGnlPatientPriceRembou=$TotalGnlPatientPriceRembou + $TotalpatientPriceRembou;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPriceRembou.'';
								
								$TotalGnlInsurancePriceRembou=$TotalGnlInsurancePriceRembou + $TotaluapPriceRembou;						
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
						<th style="width:10%">Medicaments</th>
						<th style="width:10%">Qty</th>
						<th style="width:10%">P/U</th>
						<th style="width:15%;">Balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:10%;">Percent</th>
						<th style="width:15%;">Patient balance</th>
						<th style="width:5%;">Refunded</th>
						<th style="width:15%;">Insurance balance</th>
						<th style="width:5%;">Refunded</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;
			
			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			
			
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
					
					if($lignePresta=$resultPresta->fetch())
					{
					?>
						<tr style="text-align:center;">
							<td>
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
								echo $prixPresta.'';
				?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
											
								<?php							
								$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
						
								$balance=($prixPresta*$qteMedoc) - $prixPrestaRembou;
								?>	
										
							<td>
								<?php echo $balance;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
									
							<td>
								<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
							
							<td>
							<?php 
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
								echo $patientPrice;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
										
							<td>
								<?php 
								$patientPriceRembou=$ligneMedMedoc->prixrembouMedoc;
								$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
								
								echo $patientPriceRembou.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
					
							<td>
								<?php
								$uapPrice= $balance - $patientPrice;									
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice;					
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
									
							<td>
								<?php 
								$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
								$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
								
								echo $uapPriceRembou.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
					{
					?>
						<tr style="text-align:center;">
							<td>
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
								echo $prixPresta.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>				
							</td>
										
							<?php							
							$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
					
							$balance=($prixPresta*$qteMedoc) - $prixPrestaRembou;
							?>	
									
							<td>
								<?php echo $balance;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
									
							<td>
								<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
						
							<td>
								<?php 
								$patientPrice=($balance * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;								
								echo $patientPrice;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
										
							<td>
								<?php 
								$patientPriceRembou=$ligneMedMedoc->prixrembouMedoc;
								$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
								
								echo $patientPriceRembou.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
					
							<td>
								<?php
								$uapPrice= $balance - $patientPrice;									
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice;					
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
									
							<td>
								<?php 
								$uapPriceRembou= $prixPrestaRembou - $patientPriceRembou;
								$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
								
								echo $uapPriceRembou.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
			<?php
					}
						
				$TotalMedMedoc= $TotalMedMedoc + $balance;
				$TotalMedMedocRembou = $TotalMedMedocRembou + $prixPrestaRembou;			
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
						<td></td>
						<td></td>
						<td></td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedMedoc.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedMedocRembou.'';
								
								$TotalGnlPriceRembou=$TotalGnlPriceRembou + $TotalMedMedocRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPriceRembou.'';
								
								$TotalGnlPatientPriceRembou=$TotalGnlPatientPriceRembou + $TotalpatientPriceRembou;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPriceRembou.'';
								
								$TotalGnlInsurancePriceRembou=$TotalGnlInsurancePriceRembou + $TotaluapPriceRembou;						
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
	
		<table class="printPreview" cellspacing="0" style="margin:auto;">
			<thead> 
				<tr>
					<th style="width:20%"></th>
					<th style="width:20%;">Total</th>
					<th style="width:20%;">Patient</th>
					<th style="width:20%;">Insurance</th>
				</tr> 
			</thead> 

			<tbody>
				<tr style="text-align:center;color:rgba(0, 0, 0, 0.3);">
					<td style="font-size: 13px; font-weight: bold;">Balance</td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlPrice + $TotalGnlPriceRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlPatientPrice + $TotalGnlPatientPriceRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlInsurancePrice + $TotalGnlInsurancePriceRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
				</tr>
				
				<tr style="text-align:center;color:rgba(0, 0, 0, 0.3);">
					<td style="font-size: 13px; font-weight: bold;">Refunded Balance</td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlPriceRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlPatientPriceRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlInsurancePriceRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
				</tr>
				
				<tr style="text-align:center;">
					<td style="font-size: 13px; font-weight: bold;">Remaining Balance</td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlPatientPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 13px; font-weight: bold;"><?php echo $TotalGnlInsurancePrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
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
			
			/*----------Update Bills----------------*/
						
			$updateIdBill=$connexion->prepare('UPDATE bills b SET  b.numero=:num, b.nomassurance=:nomassu WHERE b.id_bill=:idbill');

			$updateIdBill->execute(array(
			'idbill'=>$idBilling,
			'num'=>$_GET['num'],
			'nomassu'=>$nomassurance
			
			))or die( print_r($connexion->errorInfo()));
			
			
			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$IdBill=str_replace('/', '_', showBN());
			
			// $objWriter->save('C:/wamp/www/stjean/BillFiles/Bill#'.$IdBill.'.xlsx');
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			// createBN();
			
			echo '<script text="text/javascript">document.location.href="printBill.php?num='.$_GET['num'].'&cashier='.$_SESSION['codeCash'].'&idconsu='.$_GET['idconsu'].'&dateconsu='.$_GET['dateconsu'].'&idbill='.$idBilling.'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idmed='.$_GET['idmed'].'&finishbtn=ok"</script>';
			
		
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