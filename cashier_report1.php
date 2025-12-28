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

	if(isset($_GET['createRN']))
	{
		$createRN=$_GET['createRN'];
	}
	
	if(isset($_GET['stringResult']))
	{
		$stringResult=$_GET['stringResult'];
	}
	
	
		if($_GET['caVisit']=='dailyPersoBill')
		{
			$sn = showRN('CRD');
		}else{
			if($_GET['caVisit']=='monthlyPersoBill')
			{
				$sn = showRN('CRM');
			}else{
				if($_GET['caVisit']=='annualyPersoBill')
				{
					$sn = showRN('CRA');
				}else{
					if($_GET['caVisit']=='customPersoBill')
					{
						$sn = showRN('CRC');
					}else{
						if($_GET['caVisit']=='gnlPersoBill')
						{
							$sn = showRN('CRG');
						}
					}
				}
			}
		}
		
		if(isset($_GET['caVisit']))
		{
			$caVisit=$_GET['caVisit'];
		}
		
		if(isset($_GET['caVisitgnl']))
		{
			$caVisitgnl=$_GET['caVisitgnl'];
		}
		

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<meta charset="utf-8"/>
	<title><?php echo 'Cashier Report#'.$sn; ?></title>

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
	
	if(isset($_SESSION['codeCash']))
	{	
		$resultatsCa=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers ca WHERE u.id_u=ca.id_u and ca.id_u=:operation');
		$resultatsCa->execute(array(
		'operation'=>$idDoneby	
		));

		$resultatsCa->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneCa=$resultatsCa->fetch())
		{
			$doneby = $ligneCa->full_name;
			$codeDoneby = $ligneCa->codecashier;
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
		$code->setLabel('# '.$sn.' #');
		$code->parse(''.$sn.'');
		
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
	if(isset($_GET['cash']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE c.codecashier=:operation AND u.id_u=c.id_u');
		$result->execute(array(
		'operation'=>$_GET['cash']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$idCa=$ligne->id_u;
			$fullname=$ligne->full_name;
			
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
		
		$codeCa=$_GET['cash'];
		$dailydateperso=$_GET['dailydateperso'];
		$caVisit=$_GET['caVisit'];
				

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for cashier : '.$codeCa.', '.$fullname.'')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'S/N')
					->setCellValue('B1', ''.$codeCa.'')
					->setCellValue('A2', 'Cashier name')
					->setCellValue('B2', ''.$fullname.'')
					->setCellValue('A3', 'Adresse')
					->setCellValue('B3', ''.$adresse.'')

					->setCellValue('F1', 'Report #')
					->setCellValue('G1', ''.$sn.'')
					->setCellValue('F2', 'Done by')
					->setCellValue('G2', ''.$doneby.'')
					->setCellValue('F3', 'Date')
					->setCellValue('G3', ''.$annee.'');
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
					
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Cashier Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="cashier_report.php?cash=<?php echo $_GET['cash'];?>&dailydateperso=<?php echo $dailydateperso;?>&caVisit=<?php echo $caVisit;?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<?php
				if(isset($_SESSION['codeC']))
				{
				?>
				<td style="text-align:left">
					
					<form method="post" action="cashier_report.php?cash=<?php echo $_GET['cash'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&caVisit=<?php echo $caVisit;?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>				
				<?php
				}
				?>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="report.php?cash=<?php echo $_GET['cash'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
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
				<span style="font-weight:bold;margin-right:5px;">Cashier name : </span>		
			</td>
			<td style="text-align:left;">'.$fullname.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">S/N : </span>		
			</td>
			<td style="text-align:left;">'.$codeCa.'</td>			
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

		if(isset($_GET['divPersoBillReport']))
		{
			// echo $_GET['dailydateperso'];
		?>
		<div id="divPersoBillReport">
	
			<?php
						
			$resultCashierBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.codecashier=:codeCa '.$dailydateperso.' ORDER BY b.datebill ASC');
			$resultCashierBillReport->execute(array(
			'codeCa'=>$codeCa
			));
			
			$resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);

			$compCashBillReport=$resultCashierBillReport->rowCount();

			if($compCashBillReport!=0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A9', 'N°')
							->setCellValue('B9', 'Date of bill')
							->setCellValue('C9', 'Bill number')
							->setCellValue('D9', 'Beneficiary\'s age')
							->setCellValue('E9', 'Gender ')
							->setCellValue('F9', 'Beneficiary\'s name')
							->setCellValue('G9', 'Principal member')
							->setCellValue('H9', 'Affiliate\'s affectation')
							->setCellValue('I9', 'Type of consultation')
							->setCellValue('J9', 'Price of consultation (Rwf)')
							->setCellValue('K9', 'Services')
							->setCellValue('L9', 'Nursing Care')
							->setCellValue('M9', 'Laboratory tests')
							->setCellValue('N9', 'Medical imaging')
							->setCellValue('O9', 'Consommables')
							->setCellValue('P9', 'Medications')
							->setCellValue('Q9', 'Total Amount')
							->setCellValue('R9', 'Total Amount Patient')
							->setCellValue('S9', 'Total Amount Insurance')
							->setCellValue('T9', 'Insurance Type');
				
			?>
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
							
				<thead>
					<tr>
						<th style="border-right: 1px solid #bbb;text-align:center;width:20px;">N°</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Date</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Bill number</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Insurance Type</th>
						<!--
						<th style="border-right: 1px solid #bbb;text-align:center;" colspan=2>Beneficiary's age / gender </th>
						-->
						<th style="border-right: 1px solid #bbb;text-align:center;">Beneficiary's names</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Principal member</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's affectation</th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(113);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(99);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medical imaging';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Consommables';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medications';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Patient</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Insurance</th>
					</tr> 
				</thead> 
				
				<tbody>
			<?php
			$TotalGnlTypeConsu=0;
				$TotalGnlTypeConsuPatient=0;
				$TotalGnlTypeConsuInsu=0;
			$TotalGnlMedConsu=0;
				$TotalGnlMedConsuPatient=0;
				$TotalGnlMedConsuInsu=0;
			$TotalGnlMedInf=0;
				$TotalGnlMedInfPatient=0;
				$TotalGnlMedInfInsu=0;
			$TotalGnlMedLabo=0;
				$TotalGnlMedLaboPatient=0;
				$TotalGnlMedLaboInsu=0;
			$TotalGnlMedRadio=0;
				$TotalGnlMedRadioPatient=0;
				$TotalGnlMedRadioInsu=0;
			$TotalGnlMedConsom=0;
				$TotalGnlMedConsomPatient=0;
				$TotalGnlMedConsomInsu=0;
			$TotalGnlMedMedoc=0;
				$TotalGnlMedMedocPatient=0;
				$TotalGnlMedMedocInsu=0;
			$TotalGnlPrice=0;
				$TotalGnlPricePatient=0;
				$TotalGnlPriceInsu=0;
			
			$i=0;
			$compteur=1;
			
				while($ligneCashierBillReport=$resultCashierBillReport->fetch())//on recupère la liste des éléments
				{
					$TotalDayPrice=0;
					$TotalDayPricePatient=0;
					$TotalDayPriceInsu=0;
					
					$consult ="";
					$medconsu ="";
					$medinf ="";
					$medlabo ="";
					$medradio ="";
					$medconsom ="";
					$medmedoc ="";
			?>
			
				<tr style="text-align:center;">
					<td style="text-align:center;"><?php echo $compteur;?></td>
					<td style="text-align:center;"><?php echo $ligneCashierBillReport->datebill;?></td>
					<td style="text-align:center;"><?php echo $ligneCashierBillReport->numbill;?></td>
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
						$resultPatient->execute(array(
						'operation'=>$ligneCashierBillReport->numero
						));
				
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit recupérable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
						{
							$fullname = $lignePatient->full_name;
							$numero = $lignePatient->numero;
							$sexe = $lignePatient->sexe;
							$carteassuid = $lignePatient->carteassuranceid;
							$insurancetype = $ligneCashierBillReport->nomassurance.' ('.$ligneCashierBillReport->billpercent.'%)';
							
							$adherent =$lignePatient->adherent;
							$dateN =$lignePatient->date_naissance;
							$profession=$lignePatient->profession;
										
			$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//re?t l'ann?de naissance
			$month=$dateN[5].''.$dateN[6].'	';//re?t le mois de naissance

			$an= date('Y')-$old.'	';//recupere l'? en ann?
			$mois= date('m')-$month.'	';//recupere l'? en mois

			if($mois<0)
			{
				$age= ($an-1).' ans	';
				// $an= ($an-1).' ans	'.(12+$mois).' mois';
				// echo $an= $an-1;

			}else{

				$age= $an.' ans';
				// $an= $an.' ans';
				//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
				// echo $mois= date('m')-$month;
			}
							
							
							
							echo '<td style="text-align:center;">'.$ligneCashierBillReport->nomassurance.' ('.$ligneCashierBillReport->billpercent.'%)</td>';	
							/* 
							echo '<td style="text-align:center;">'.$age.'</td>';
							echo '<td style="text-align:center;">'.$sexe.'</td>'; */
							
							echo '<td style="text-align:center; font-weight: bold;">'.$fullname.' ('.$numero.')</td>';
							echo '<td style="text-align:center; font-weight: normal;">'.$adherent.'</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$profession.'</td>';
						}else{
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
						}
			
					$getAssu=$connexion->prepare('SELECT *FROM assurances a WHERE a.nomassurance=:nomassurance ORDER BY a.id_assurance');		
					$getAssu->execute(array(
					'nomassurance'=>$ligneCashierBillReport->nomassurance
					));
					
					$getAssu->setFetchMode(PDO::FETCH_OBJ);

					if($ligneNomAssu=$getAssu->fetch())
					{
						$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
					}			
					?>
					
					<td style="text-align:center;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<?php
			
						$resultConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
						$resultConsu->execute(array(
						'idbill'=>$ligneCashierBillReport->id_bill
						));
						
						$comptConsu=$resultConsu->rowCount();
						
						$resultConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$resultAutreConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_typeconsult IS NULL AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
						$resultAutreConsu->execute(array(
						'idbill'=>$ligneCashierBillReport->id_bill
						));
						
						$comptAutreConsu=$resultAutreConsu->rowCount();
						$resultAutreConsu->setFetchMode(PDO::FETCH_OBJ);
							
						
						$TotalTypeConsu=0;
						$TotalTypeConsuPatient=0;
						$TotalTypeConsuInsu=0;
							
						if($comptConsu!=0)
						{
							while($ligneConsu=$resultConsu->fetch())
							{
								if($ligneConsu->prixtypeconsult!=0 AND $ligneConsu->prixrembou!=0)
								{
									$prixPrestaRembou=$ligneConsu->prixrembou;
																	
									$prixconsult=$ligneConsu->prixtypeconsult - $prixPrestaRembou;
								
								}else{
									$prixconsult=$ligneConsu->prixtypeconsult;

								}
									
								$prixconsultpatient=($prixconsult * $ligneConsu->insupercent)/100;							
								
								$prixconsultinsu= $prixconsult - $prixconsultpatient;	
								if($prixconsult>=0)
								{	
							?>
								<tr>
									<td style="text-align:center;font-weight:normal;">
									<?php
										echo $ligneConsu->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									// echo $prixconsult;
									?>
									</td>
								</tr>
							<?php
									$consult .= ''.$ligneConsu->nompresta;
									$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
									$TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
									$TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
								}
							}
						}
							
						if($comptAutreConsu!=0)
						{
							while($ligneAutreConsu=$resultAutreConsu->fetch())
							{
								if($ligneAutreConsu->prixautretypeconsult!=0 AND $ligneAutreConsu->prixrembou!=0)
								{
									$prixPrestaRembou=$ligneAutreConsu->prixrembou;
									
									$prixconsult=$ligneAutreConsu->prixautretypeconsult - $prixPrestaRembou;
								
								}else{
									$prixconsult=$ligneAutreConsu->prixautretypeconsult;

								}
									
								$prixconsultpatient=($prixconsult * $ligneAutreConsu->insupercent)/100;							
								
								$prixconsultinsu= $prixconsult - $prixconsultpatient;	
							
								if($prixconsult>=0)
								{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneAutreConsu->autretypeconsult;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									// echo $prixconsult;
									?>
									</td>
								</tr>
						<?php
									$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
									$TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
									$TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
								}
							}
						}
						?>
								
							<tr>
								<td style="text-align:center;font-weight:normal;" colspan=2>
								<?php
									echo $TotalTypeConsu;
								
									$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalTypeConsuInsu;
								?>
								<!-- <span style="font-size:80%; font-weight:normal;">Rwf</span> -->
					
								</td>
							</tr>
						</table>
					
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
								
						<?php
						
						$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, '.$presta_assu.' p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
						$resultMedConsu->execute(array(
						'idbill'=>$ligneCashierBillReport->id_bill
						));
						
						$comptMedConsu=$resultMedConsu->rowCount();
						
						$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_factureMedConsu=:idbill AND mc.id_prestationConsu IS NULL ORDER BY mc.id_medconsu DESC');
						$resultMedAutreConsu->execute(array(
						'idbill'=>$ligneCashierBillReport->id_bill
						));
						
						$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
						$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedConsu=0;
						$TotalMedConsuPatient=0;
						$TotalMedConsuInsu=0;
						
				if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
				{
					if($comptMedConsu!=0)
					{
						while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
						{
							if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
							{
								$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
								
								$prixconsu=$ligneMedConsu->prixprestationConsu - $prixPrestaRembou;
							
							}else{
								$prixconsu=$ligneMedConsu->prixprestationConsu;

							}
							
							$prixconsupatient=($prixconsu * $ligneMedConsu->insupercentServ)/100;							
							
							$prixconsuinsu= $prixconsu - $prixconsupatient;	
							
							if($prixconsu>=1)
							{
						?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedConsu->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixconsu;
								?>
								</td>
							</tr>
						<?php
								$medconsu .= ''.$ligneMedConsu->nompresta.' ('.$prixconsu.'), ';
								$TotalMedConsu=$TotalMedConsu+$prixconsu;
								$TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
								$TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;
							
							}
						}
					}
					
					if($comptMedAutreConsu!=0)
					{
						while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())
						{
							if($ligneMedAutreConsu->prixautreConsu!=0 AND $ligneMedAutreConsu->prixrembouConsu!=0)
							{
								$prixPrestaRembou=$ligneMedAutreConsu->prixrembouConsu;
								
								$prixconsu=$ligneMedAutreConsu->prixautreConsu - $prixPrestaRembou;
							
							}else{
								$prixconsu=$ligneMedAutreConsu->prixautreConsu;

							}
							
							$prixconsupatient=($prixconsu * $ligneMedAutreConsu->insupercentServ)/100;							
							
							$prixconsuinsu= $prixconsu - $prixconsupatient;	
							
							if($prixconsu>=1)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php							
									echo $ligneMedAutreConsu->autreConsu;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixconsu;				
								?>
								</td>
							</tr>
				<?php
						
								$medconsu .= ''.$ligneMedAutreConsu->autreConsu.' ('.$prixconsu.' Rwf), ';
								
								$TotalMedConsu=$TotalMedConsu+$prixconsu;
								$TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
								$TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;
							}
						}
					}

				}			
				?>
								
							<tr>
								<td style="text-align:center">
								<?php
									echo $TotalMedConsu.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsuInsu;
								?>
								</td>
							</tr>
						</table>
					
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
						<?php
						
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, '.$presta_assu.' p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedInf->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedInf=$resultMedInf->rowCount();
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
					
					
					$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_prestation IS NULL AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedAutreInf->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedAutreInf=$resultMedAutreInf->rowCount();
					
					$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedInf=0;					
					$TotalMedInfPatient=0;					
					$TotalMedInfInsu=0;					
								
				if($comptMedInf!=0 or $comptMedAutreInf!=0)
				{
					if($comptMedInf!=0)
					{
						while($ligneMedInf=$resultMedInf->fetch())
						{
							if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
							{
								$prixPrestaRembou=$ligneMedInf->prixrembouInf;
								
								$prixinf=$ligneMedInf->prixprestation - $prixPrestaRembou;
							
							}else{
								$prixinf=$ligneMedInf->prixprestation;
																
							}
							
							$prixinfpatient=($prixinf * $ligneMedInf->insupercentInf)/100;							
							
							$prixinfinsu= $prixinf - $prixinfpatient;	
							
							if($prixinf>=1)
							{
						?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedInf->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixinf.'';
								?>
								</td>
							</tr>
						<?php
							
								$medinf .= ''.$ligneMedInf->nompresta.' ('.$prixinf.' Rwf), ';
							
								$TotalMedInf=$TotalMedInf+$prixinf;
								$TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
								$TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;
							}
						}
					}
					
					if($comptMedAutreInf!=0)
					{
						while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des éléments
						{
							if($ligneMedAutreInf->prixautrePrestaM!=0 AND $ligneMedAutreInf->prixrembouInf!=0)
							{
								$prixPrestaRembou=$ligneMedAutreInf->prixrembouInf;
								
								$prixinf=$ligneMedAutreInf->prixautrePrestaM - $prixPrestaRembou;
							
							}else{
								$prixinf=$ligneMedAutreInf->prixautrePrestaM;
																
							}
							
							$prixinfpatient=($prixinf * $ligneMedAutreInf->insupercentInf)/100;			
							$prixinfinsu= $prixinf - $prixinfpatient;								
							
							if($prixinf>=1)
							{
						?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php								
									echo $ligneMedAutreInf->autrePrestaM;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixinf;					
								?>
								</td>
							</tr>
							<?php
								
								$medinf .= ''.$ligneMedAutreInf->autrePrestaM.' ('.$prixinf.' Rwf), ';
							
								$TotalMedInf=$TotalMedInf+$prixinf;
								$TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
								$TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;
							}
						}
					}

				}				
				?>
								
							<tr>
								<td style="text-align:center">
								<?php

									echo $TotalMedInf.'';					
									$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedInfPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedInfInsu;
								?>
								</td>
							</tr>
						</table>
					
					</td>
					
					<td style="text-align:center;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									
						<?php
						
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assu.' p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedLabo->execute(array(
						'idbill'=>$ligneCashierBillReport->id_bill
						));
						
						$comptMedLabo=$resultMedLabo->rowCount();
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_prestationExa IS NULL AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedAutreLabo->execute(array(
						'idbill'=>$ligneCashierBillReport->id_bill
						));
						
						$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
						
						$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedLabo=0;
						$TotalMedLaboPatient=0;
						$TotalMedLaboInsu=0;
						
				if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
				{
					if($comptMedLabo!=0)
					{
						while($ligneMedLabo=$resultMedLabo->fetch())
						{
							if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
							{
								$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
												
								$prixlabo=$ligneMedLabo->prixprestationExa - $prixPrestaRembou;
							
							}else{
								$prixlabo=$ligneMedLabo->prixprestationExa;

							}
							
							$prixlabopatient=($prixlabo * $ligneMedLabo->insupercentLab)/100;							
							
							$prixlaboinsu= $prixlabo - $prixlabopatient;	
							
							if($prixlabo>=1)
							{
						?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedLabo->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixlabo.'';
								?>
								</td>
							</tr>
							<?php
							
								$medlabo .= ''.$ligneMedLabo->nompresta.' ('.$prixlabo.' Rwf), ';
								
								$TotalMedLabo=$TotalMedLabo+$prixlabo;
								$TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
								$TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
							}
						}
					}
					
					if($comptMedAutreLabo!=0)
					{
						while($ligneMedAutreLabo=$resultMedAutreLabo->fetch())
						{
							if($ligneMedAutreLabo->prixautreExamen!=0 AND $ligneMedAutreLabo->prixrembouLabo!=0)
							{
								$prixPrestaRembou=$ligneMedAutreLabo->prixrembouLabo;
								
								$prixlabo=$ligneMedAutreLabo->prixautreExamen - $prixPrestaRembou;
							
							}else{
								$prixlabo=$ligneMedAutreLabo->prixautreExamen;
																
							}
							
							$prixlabopatient=($prixlabo * $ligneMedAutreLabo->insupercentLab)/100;							
							
							$prixlaboinsu= $prixlabo - $prixlabopatient;	
							
							if($prixlabo>=1)
							{
							?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php								
									echo $ligneMedAutreLabo->autreExamen;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixlabo;					
								?>
								</td>
							</tr>
				<?php
						
								$medlabo .= ''.$ligneMedAutreLabo->autreExamen.' ('.$prixlabo.' Rwf), ';
								
								$TotalMedLabo=$TotalMedLabo+$prixlabo;
								$TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
								$TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
							}
						
						}
					}
				}					
				?>
											
							<tr>
								<td style="text-align:center">
								<?php
									
									echo $TotalMedLabo.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedLaboPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedLaboInsu;
								?>
								</td>
							</tr>
						</table>
					
					</td>
					
					<td>
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

					<?php
							
					$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, '.$presta_assu.' p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
					$resultMedRadio->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedRadio=$resultMedRadio->rowCount();
					
					$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_prestationRadio IS NULL AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
					$resultMedAutreRadio->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedAutreRadio=$resultMedAutreRadio->rowCount();
					
					$resultMedAutreRadio->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedRadio=0;
					$TotalMedRadioPatient=0;
					$TotalMedRadioInsu=0;
					
				if($comptMedRadio!=0 or $comptMedAutreRadio!=0)
				{
					if($comptMedRadio!=0)
					{
						while($ligneMedRadio=$resultMedRadio->fetch())
						{
							if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
							{
								$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
								
								$prixradio=$ligneMedRadio->prixprestationRadio - $prixPrestaRembou;
							
							}else{
								$prixradio=$ligneMedRadio->prixprestationRadio;
																
							}
							
							$prixradiopatient=($prixradio * $ligneMedRadio->insupercentRad)/100;							
							
							$prixradioinsu= $prixradio - $prixradiopatient;	
							
							if($prixradio>=1)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedRadio->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixradio;
								?>
								</td>
							</tr>
					<?php
					
								$medradio .= ''.$ligneMedRadio->nompresta.' ('.$prixradio.' Rwf), ';
								
								$TotalMedRadio=$TotalMedRadio+$prixradio;
								$TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
								
								$TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
							}
						}
					}
					
					if($comptMedAutreRadio!=0)
					{
						while($ligneMedAutreRadio=$resultMedAutreRadio->fetch())//on recupere la liste des éléments
						{
							if($ligneMedAutreRadio->prixautreRadio!=0 AND $ligneMedAutreRadio->prixrembouRadio!=0)
							{
								$prixPrestaRembou=$ligneMedAutreRadio->prixrembouRadio;
								
								$prixradio=$ligneMedAutreRadio->prixautreRadio - $prixPrestaRembou;
							
							}else{
								$prixradio=$ligneMedAutreRadio->prixautreRadio;
							
							}
							
							$prixradiopatient=($prixradio * $ligneMedAutreRadio->insupercentRad)/100;							
							
							$prixradioinsu= $prixradio - $prixradiopatient;	
							
							if($prixradio>=1)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php								
									echo $ligneMedAutreRadio->autreRadio;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixradio;
								?>
								</td>
							</tr>
				<?php
					
								$medradio .= ''.$ligneMedAutreRadio->autreRadio.' ('.$prixradio.' Rwf), ';
								
								$TotalMedRadio=$TotalMedRadio+$prixradio;
								$TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
								
								$TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
							}
						}
					}

				}					
				?>										
							<tr>
								<td style="text-align:center" colspan=2>
								<?php
									
									echo $TotalMedRadio.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedRadioPatient;

									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedRadioInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td>
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

					<?php
							
					$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, '.$presta_assu.' p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
					$resultMedConsom->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedConsom=$resultMedConsom->rowCount();
					
					$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_prestationConsom=0 AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
					$resultMedAutreConsom->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedAutreConsom=$resultMedAutreConsom->rowCount();
					
					$resultMedAutreConsom->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedConsom=0;
					$TotalMedConsomPatient=0;
					$TotalMedConsomInsu=0;
					
				if($comptMedConsom!=0 or $comptMedAutreConsom!=0)
				{
					if($comptMedConsom!=0)
					{
						while($ligneMedConsom=$resultMedConsom->fetch())
						{
							if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
							{
								$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
								
								$prixconsom=($ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom) - $prixPrestaRembou;
							
							}else{
								$prixconsom=$ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom;
							
							}
							
							$prixconsompatient=($prixconsom * $ligneMedConsom->insupercentConsom)/100;							
							
							$prixconsominsu= $prixconsom - $prixconsompatient;	
							
							if($prixconsom!=0)
							{
				?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedConsom->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php							
									echo $ligneMedConsom->qteConsom;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixconsom;
								?>
								</td>
							</tr>
					<?php
					
								$medconsom .= ''.$ligneMedConsom->nompresta.' ('.$prixconsom.' Rwf), ';
								
								$TotalMedConsom=$TotalMedConsom+$prixconsom;
								$TotalMedConsomPatient=$TotalMedConsomPatient + $prixconsompatient;
								
								$TotalMedConsomInsu=$TotalMedConsomInsu + $prixconsominsu;
							}
						}
					}
					
					if($comptMedAutreConsom!=0)
					{
						while($ligneMedAutreConsom=$resultMedAutreConsom->fetch())//on recupere la liste des éléments
						{
							if($ligneMedAutreConsom->prixautreConsom!=0 AND $ligneMedAutreConsom->prixrembouConsom!=0)
							{
								$prixPrestaRembou=$ligneMedAutreConsom->prixrembouConsom;
								
								$prixconsom=($ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom) - $prixPrestaRembou;
							
							}else{
								$prixconsom=$ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom;
							
							}
							
							$prixconsompatient=($prixconsom * $ligneMedAutreConsom->insupercentConsom)/100;							
							
							$prixconsominsu= $prixconsom - $prixconsompatient;	
							
							if($prixconsom!=0)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php							
									echo $ligneMedAutreConsom->autreConsom;
								?>
								</td>
								
								<td style="text-align:center">
								<?php							
									echo $ligneMedAutreConsom->qteConsom;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixconsom;
								?>
								</td>
							</tr>
				<?php
							
								$medconsom .= ''.$ligneMedAutreConsom->autreConsom.' ('.$prixconsom.' Rwf), ';
								
								$TotalMedConsom=$TotalMedConsom+$prixconsom;
								$TotalMedConsomPatient=$TotalMedConsomPatient + $prixconsompatient;
								
								$TotalMedConsomInsu=$TotalMedConsomInsu + $prixconsominsu;
							}
						}
					}

				}					
				?>
								
							<tr>
								<td style="text-align:center" colspan=2>
								<?php
									
									echo $TotalMedConsom.'';
									
									$TotalDayPrice=$TotalDayPrice + $TotalMedConsom;
									$TotalDayPricePatient=$TotalDayPricePatient + $TotalMedConsomPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu + $TotalMedConsomInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td>
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

					<?php
							
					$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, '.$presta_assu.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
					$resultMedMedoc->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedMedoc=$resultMedMedoc->rowCount();
					
					$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_prestationMedoc=0 AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
					$resultMedAutreMedoc->execute(array(
					'idbill'=>$ligneCashierBillReport->id_bill
					));
					
					$comptMedAutreMedoc=$resultMedAutreMedoc->rowCount();
					
					$resultMedAutreMedoc->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedMedoc=0;
					$TotalMedMedocPatient=0;
					$TotalMedMedocInsu=0;

					
				if($comptMedMedoc!=0 or $comptMedAutreMedoc!=0)
				{
					if($comptMedMedoc!=0)
					{
						
						while($ligneMedMedoc=$resultMedMedoc->fetch())
						{
							if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
							{
								$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
			
								$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc) - $prixPrestaRembou;
							
							}else{
								$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc;
							
							}
							
							$prixmedocpatient=($prixmedoc * $ligneMedMedoc->insupercentMedoc)/100;							
							
							$prixmedocinsu= $prixmedoc - $prixmedocpatient;	
							
							if($prixmedoc!=0)
							{
				?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedMedoc->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php							
									echo $ligneMedMedoc->qteMedoc;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixmedoc;
								?>
								</td>
							</tr>
					<?php
					
								$medmedoc .= ''.$ligneMedMedoc->nompresta.' ('.$prixmedoc.' Rwf), ';
								
								$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								$TotalMedMedocPatient=$TotalMedMedocPatient + $prixmedocpatient;							
								$TotalMedMedocInsu= $TotalMedMedocInsu + $prixmedocinsu;
								
							}
						}
					}
					
					if($comptMedAutreMedoc!=0)
					{
						while($ligneMedAutreMedoc=$resultMedAutreMedoc->fetch())//on recupere la liste des éléments
						{
							if($ligneMedAutreMedoc->prixautreMedoc!=0 AND $ligneMedAutreMedoc->prixrembouMedoc!=0)
							{
								$prixPrestaRembou=$ligneMedAutreMedoc->prixrembouMedoc;
			
								$prixmedoc=($ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc) - $prixPrestaRembou;
							
							}else{
								$prixmedoc=$ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc;
							
							}
							
							$prixmedocpatient=($prixmedoc * $ligneMedAutreMedoc->insupercentMedoc)/100;							
							
							$prixmedocinsu= $prixmedoc - $prixmedocpatient;	
							
							if($prixmedoc!=0)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php							
									echo $ligneMedAutreMedoc->autreMedoc;
								?>
								</td>
								
								<td style="text-align:center">
								<?php							
									echo $ligneMedAutreMedoc->qteMedoc;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixmedoc;
									
								?>
								</td>
							</tr>
				<?php
				
								$medmedoc .= ''.$ligneMedAutreMedoc->autreMedoc.' ('.$prixmedoc.' Rwf), ';
								
								$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								
								$TotalMedMedocPatient=$TotalMedMedocPatient + $prixmedocpatient;							
								$TotalMedMedocInsu= $TotalMedMedocInsu + $prixmedocinsu;
								
							}
						}
					}

				}					
				?>
								
							<tr>
								<td style="text-align:center" colspan=2>
								<?php
										
									echo $TotalMedMedoc.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedMedocPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedMedocInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
					
					<td style="text-align:center;"><?php echo $TotalDayPricePatient;?></td>
					
					<td style="text-align:center;"><?php echo $TotalDayPriceInsu;?></td>
				</tr>
				<?php
				$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
					$TotalGnlTypeConsuPatient = $TotalGnlTypeConsuPatient + $TotalTypeConsuPatient;
					$TotalGnlTypeConsuInsu = $TotalGnlTypeConsuInsu + $TotalTypeConsuInsu;
					
				$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
					$TotalGnlMedConsuPatient = $TotalGnlMedConsuPatient + $TotalMedConsuPatient;
					$TotalGnlMedConsuInsu = $TotalGnlMedConsuInsu + $TotalMedConsuInsu;
					
				$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
					$TotalGnlMedInfPatient = $TotalGnlMedInfPatient + $TotalMedInfPatient;
					$TotalGnlMedInfInsu = $TotalGnlMedInfInsu + $TotalMedInfInsu;
				
				$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
					$TotalGnlMedLaboPatient=$TotalGnlMedLaboPatient + $TotalMedLaboPatient;
					$TotalGnlMedLaboInsu=$TotalGnlMedLaboInsu + $TotalMedLaboInsu;
				
				$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
					$TotalGnlMedRadioPatient = $TotalGnlMedRadioPatient + $TotalMedRadioPatient;
					$TotalGnlMedRadioInsu = $TotalGnlMedRadioInsu + $TotalMedRadioInsu;
				
				$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
					$TotalGnlMedConsomPatient = $TotalGnlMedConsomPatient + $TotalMedConsomPatient;
					$TotalGnlMedConsomInsu = $TotalGnlMedConsomInsu + $TotalMedConsomInsu;
				
				$TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
					$TotalGnlMedMedocPatient = $TotalGnlMedMedocPatient + $TotalMedMedocPatient;
					$TotalGnlMedMedocInsu = $TotalGnlMedMedocInsu + $TotalMedMedocInsu;
				
				$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
					$TotalGnlPricePatient = $TotalGnlPricePatient + $TotalDayPricePatient;
					
					$TotalGnlPriceInsu = $TotalGnlPriceInsu + $TotalDayPriceInsu;
					
					
					$arrayGnlBillReport[$i][0]=$compteur;
					$arrayGnlBillReport[$i][1]=$ligneCashierBillReport->datebill;
					$arrayGnlBillReport[$i][2]=$ligneCashierBillReport->numbill;
					$arrayGnlBillReport[$i][3]=$old;
					$arrayGnlBillReport[$i][4]=$sexe;
					$arrayGnlBillReport[$i][5]=$fullname;			
					$arrayGnlBillReport[$i][6]=$adherent;
					$arrayGnlBillReport[$i][7]=$profession;
					
					$arrayGnlBillReport[$i][8]=$consult;			
					$arrayGnlBillReport[$i][9]=$TotalTypeConsu;
					
					// $arrayGnlBillReport[$i][7]=$medconsu;		
					$arrayGnlBillReport[$i][10]=$TotalMedConsu;
					
					// $arrayGnlBillReport[$i][9]=$medinf;		
					$arrayGnlBillReport[$i][11]=$TotalMedInf;
					
					// $arrayGnlBillReport[$i][11]=$medlabo;		
					$arrayGnlBillReport[$i][12]=$TotalMedLabo;
					
					// $arrayGnlBillReport[$i][9]=$medradio;		
					$arrayGnlBillReport[$i][13]=$TotalMedRadio;
					
					// $arrayGnlBillReport[$i][11]=$medconsom;		
					$arrayGnlBillReport[$i][14]=$TotalMedConsom;
					
					// $arrayGnlBillReport[$i][11]=$medmedoc;		
					$arrayGnlBillReport[$i][15]=$TotalMedMedoc;
					
					$arrayGnlBillReport[$i][16]=$TotalDayPrice;
					$arrayGnlBillReport[$i][17]=$TotalDayPricePatient;
					$arrayGnlBillReport[$i][18]=$TotalDayPriceInsu;
					$arrayGnlBillReport[$i][19]=$insurancetype;
					
					$i++;
					
					$compteur++;
					
				}
				?>
					<tr style="text-align:center;">
						<td colspan=7></td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlTypeConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedInf;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedLabo;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedRadio;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedConsom;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedMedoc;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlPrice;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlPricePatient;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlPriceInsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
					
				
				$objPHPExcel->setActiveSheetIndex(0)
							->fromArray($arrayGnlBillReport,'','A10')	
							->setCellValue('J'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
							->setCellValue('K'.(10+$i).'', ''.$TotalGnlMedConsu.'')
							->setCellValue('L'.(10+$i).'', ''.$TotalGnlMedInf.'')
							->setCellValue('M'.(10+$i).'', ''.$TotalGnlMedLabo.'')
							->setCellValue('N'.(10+$i).'', ''.$TotalGnlMedRadio.'')
							->setCellValue('O'.(10+$i).'', ''.$TotalGnlMedConsom.'')
							->setCellValue('P'.(10+$i).'', ''.$TotalGnlMedMedoc.'')
							->setCellValue('Q'.(10+$i).'', ''.$TotalGnlPrice.'')
							->setCellValue('R'.(10+$i).'', ''.$TotalGnlPricePatient.'')
							->setCellValue('S'.(10+$i).'', ''.$TotalGnlPriceInsu.'');
			}
			?>
		</div>
		
		<?php
		
			
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$Careportsn=str_replace('/', '_', $sn);
				
				
				if($_GET['caVisit']=='dailyPersoBill')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/CashierReport/Daily/'.$Careportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/CashierReport/Daily/");</script>';
					
				}else{
					if($_GET['caVisit']=='monthlyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/CashierReport/Monthly/'.$Careportsn.'.xlsx');
							
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/CashierReport/Monthly/");</script>';
						
					}else{
						if($_GET['caVisit']=='annualyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/CashierReport/Annualy/'.$Careportsn.'.xlsx');
							
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/CashierReport/Annualy/");</script>';
							
						}else{
							if($_GET['caVisit']=='customPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/CashierReport/Custom/'.$Careportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/CashierReport/Custom/");</script>';
								
							}else{
								if($_GET['caVisit']=='gnlPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/CashierReport/Alltimes/'.$Careportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/CashierReport/Alltimes/");</script>';
									
								}
							}
						}
					}
				}
				
			}
			
			
			if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
			{				
				if($_GET['docVisit']=='dailyPersoMedic')
				{
					createRN('CRD');
					
				}else{
					if($_GET['docVisit']=='monthlyPersoMedic')
					{
						createRN('CRM');

					}else{
						if($_GET['docVisit']=='annualyPersoMedic')
						{
							createRN('CRA');
							
						}else{
							if($_GET['docVisit']=='customPersoMedic')
							{	
								createRN('CRC');
							
							}else{
								if($_GET['docVisit']=='gnlPersoMedic')
								{
									createRN('CRG');
								
								}
							}
						}
					}
				}
				
				echo '<script text="text/javascript">document.location.href="cashier_report.php?cash='.$_GET['cash'].'&dailydateperso='.$_GET['dailydateperso'].'&caVisit='.$_GET['caVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
			}
		}
	}
	
	?>

	</div>
<?php

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
}
?>
</body>

</html>