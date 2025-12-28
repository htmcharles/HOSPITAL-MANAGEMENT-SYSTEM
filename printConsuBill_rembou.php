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
// echo $numbill;


	$numPa=$_GET['num'];
	$consuId=$_GET['idconsu'];	
	
	
	$checkIdBill=$connexion->prepare('SELECT *FROM bills b, consultations c WHERE b.id_bill=c.id_factureConsult AND c.id_factureConsult=:idbill ORDER BY b.id_bill LIMIT 1');

	$checkIdBill->execute(array(
	'idbill'=>$_GET['idbill']
	));

	$comptidBill=$checkIdBill->rowCount();

	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		$idBilling = $ligne->id_bill;
		
		$numbill = $ligne->numbill;
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
		$doneby = $ligneCashier->full_name;
		$codecashier = $ligneCashier->codecashier;
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
		$code->setLabel('# '.$numbill.' #');
		$code->parse(''.$numbill.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecashier.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:95%; border: 1px solid #eee; background:#fff; padding:5px; padding-bottom:0px; border-radius:3px; font-size:80%;">
<?php
$barcode = '

	<table style="width:100%">
		
		<tr>
			<td style="text-align:left; width:60%">
			  <table>
				<tbody>
				  <tr>
					<td>
					  <img style="width:60px; height:auto;" src="images/pc_logo.jpg">
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
			
			$insupercent= 100 - $lignePatient->bill;
			
			$percentpatient= 100 - $insupercent;

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

	$userinfo = '<table style="width:100%; margin-top:10px;">
			
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
						->setCellValue('B4', ''.$insurance.' '.$percentpatient.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		if(isset($_POST['prixrembou']))
		{
			$results=$connexion->query('SELECT *FROM consultations c WHERE c.id_consu='.$consuId.'');
			
			$results->setFetchMode(PDO::FETCH_OBJ);
				
			if($ligne=$results->fetch())
			{
				$updateprix=$connexion->query('UPDATE consultations c SET c.id_assuConsu='.$_GET['idassu'].',c.prixrembou='.$_POST['prixrembou'].' WHERE c.id_consu='.$consuId.'');					
			}
		
		}

		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.dateconsu=:dateconsu AND c.id_factureConsult IS NOT NULL AND c.numero=:num ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'consuId'=>$consuId,
		'num'=>$numPa,
		'dateconsu'=>date('Y-m-d', strtotime($dateconsu))
		));

		$resultConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		$TotalConsultRembou = 0;
		
			
	?>
	
	<table style="width:100%; margin:5px auto auto;"> 
		<tr> 
			<td style="text-align:left; width:33%;">
				<h4><?php echo $annee;?></h4>
			</td>
			
			<td style="text-align:center; width:33%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo $numbill;?></h2>
			</td>
			
			<td style="text-align:right; width:33%;">
			
				<form method="post" action="printConsuBill_rembou.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&dateconsu=<?php echo $dateconsu;?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?>&updatebill=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;margin-right:5px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
		
			<td class="buttonBill">
				<a href="formRembourser.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&idbill=<?php echo $_GET['idbill'];?>&numbill=<?php echo $numbill;?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idassu=<?php echo $_GET['idassu'];?>&idconsu=<?php echo $_GET['idconsu'];?>&cancel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="listfacture.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
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
							->setCellValue('C8', 'Total Balance')
							->setCellValue('D8', 'Patient Balance')
							->setCellValue('E8', 'Insurance Balance');
				

		?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead> 
					<tr>
						<th style="width:35%">Type of Consultation</th>
						<th style="width:10%;">Balance</th>
						<th style="width:10%;">Refunded</th>
						<th style="width:10%;">Percent</th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Refunded</th>
						<th style="width:7.5%;">Insurance balance</th>
						<th style="width:7.5%;">Refunded</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;			
			$TotaluapPrice=0;
			
			$TotalpatientPriceRembou=0;			
			$TotaluapPriceRembou=0;
			

					while($ligneConsult=$resultConsult->fetch())
					{
					
						$billpercent=$ligneConsult->insupercent;
						
						$idassu=$ligneConsult->id_assuConsu;				
						
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
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaConsult=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							}else{	
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaConsult=$lignePresta->nompresta;
									echo $lignePresta->nompresta.'</td>';
								}
							}
								
					
						$prixPrestaRembou=$ligneConsult->prixrembou;

						$prixPresta = $ligneConsult->prixtypeconsult - $prixPrestaRembou;						
						
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td><?php echo $ligneConsult->insupercent;?>%</td>
						
						<td>
							<?php 
							$patientPrice=($prixPresta * $billpercent)/100;
							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php 
							$patientPriceRembou=($ligneConsult->prixrembou * $billpercent)/100;
							
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
						
						if($ligneConsult->id_typeconsult==NULL AND $ligneConsult->prixautretypeconsult!=0)
						{
							$nameprestaConsult=$ligneConsult->autretypeconsult;
							echo $ligneConsult->autretypeconsult.'</td>';
							
							$prixPrestaRembou=$ligneConsult->prixrembou;
						
							$prixPresta = $ligneConsult->prixautretypeconsult - $prixPrestaRembou;
						?>
						
						<td>
							<?php echo $prixPresta;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php echo $prixPrestaRembou;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
							
						<td><?php echo $ligneConsult->insupercent;?>%</td>
					
						<td>
						<?php 
							$patientPrice=($prixPresta * $billpercent)/100;;							
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php 
							$patientPriceRembou=($ligneConsult->prixrembou * $billpercent)/100;;
							
							$TotalpatientPriceRembou=$TotalpatientPriceRembou + $patientPriceRembou;
							
							echo $patientPriceRembou;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>						
		
						<td>
							<?php 
							$uapPrice=$prixPresta - $patientPrice;
							
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>						
						</td>
						
						<td>
							<?php 
							$uapPriceRembou=$prixPrestaRembou - $patientPriceRembou;
							$TotaluapPriceRembou= $TotaluapPriceRembou + $uapPriceRembou;
							
							echo $uapPriceRembou.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
				$TotalConsult=$TotalConsult + $prixPresta;
				$TotalConsultRembou=$TotalConsultRembou + $prixPrestaRembou;	
						$arrayConsult[$i][0]=$nameprestaConsult;
						$arrayConsult[$i][1]=$prixPresta;
						$arrayConsult[$i][2]=$patientPrice;
						$arrayConsult[$i][3]=$uapPrice;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','B9');
		
					}

		?>		
					</tr>	
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalConsult.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalConsult;							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalConsultRembou.'';

								$TotalGnlPriceRembou=$TotalGnlPriceRembou + $TotalConsultRembou;							
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
								->setCellValue('C'.(9+$i).'', ''.$TotalConsult.'')
								->setCellValue('D'.(9+$i).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(9+$i).'', ''.$TotaluapPrice.'');

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

	<div class="account-container" style="margin: 5px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:5px; border-radius:3px; font-size:80%;">
	
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
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:5px; border-radius:3px; font-size:80%;">
	
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
			
			$updateIdBill=$connexion->prepare('UPDATE bills b SET b.totaltypeconsuprice=:totaltypeconsu, b.totalgnlprice=:totalgnl, b.dateconsu=:dateconsu, b.numero=:num, b.nomassurance=:nomassu, b.billpercent=:bill, b.codecashier=:codecash WHERE b.id_bill=:idbill');

			$updateIdBill->execute(array(
			'idbill'=>$idBilling,
			'totaltypeconsu'=>$TotalConsult,
			'totalgnl'=>$TotalGnlPrice,
			'dateconsu'=>$_GET['dateconsu'],
			'num'=>$_GET['num'],
			'nomassu'=>$nomassurance,
			'bill'=>$bill,
			'codecash'=>$_SESSION['codeCash']
			
			))or die( print_r($connexion->errorInfo()));
			
			
			// echo $idBilling.'<br/>'.$TotalConsult.'<br/>'.$TotalGnlPrice.'<br/>'.$_GET['num'].'<br/>'.$_GET['cashier'].'<br/>';
			
			
			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$IdBill=str_replace('/', '_', $numbill);
			
			// $objWriter->save('C:/wamp/www/stjean/BillFiles/Bill#'.$IdBill.'.xlsx');
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			// createBN();
			
			echo '<script text="text/javascript">document.location.href="printConsuBill_rembou.php?num='.$_GET['num'].'&cashier='.$_SESSION['codeCash'].'&idconsu='.$_GET['idconsu'].'&dateconsu='.$_GET['dateconsu'].'&idbill='.$idBilling.'&idassu='.$_GET['idassu'].'&finishbtn=ok"</script>';
		
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