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
	

	if(isset($_GET['med']))
	{
		if($_GET['docVisit']=='dailyPersoMedic')
		{
			$sn = showRN('DRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('DRM');
			}else{
				if($_GET['docVisit']=='annualyPersoMedic')
				{
					$sn = showRN('DRA');
				}else{
					if($_GET['docVisit']=='customPersoMedic')
					{
						$sn = showRN('DRC');
					}else{
						if($_GET['docVisit']=='gnlPersoMedic')
						{
							$sn = showRN('DRG');
						}
					}
				}
			}
		}
		
		
		if($_GET['docVisit']=='dailyPersoBill')
		{
			$sn = showRN('DRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoBill')
			{
				$sn = showRN('DRM');
			}else{
				if($_GET['docVisit']=='annualyPersoBill')
				{
					$sn = showRN('DRA');
				}else{
					if($_GET['docVisit']=='customPersoBill')
					{
						$sn = showRN('DRC');
					}else{
						if($_GET['docVisit']=='gnlPersoBill')
						{
							$sn = showRN('DRG');
						}
					}
				}
			}
		}
	}	
	
	
	if(isset($_GET['gnlmed']))	
	{
		if($_GET['docVisit']=='dailyPersoMedic')
		{
			$sn = showRN('GDRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('GDRM');
			}else{
				if($_GET['docVisit']=='annualyPersoMedic')
				{
					$sn = showRN('GDRA');
				}else{
					if($_GET['docVisit']=='customPersoMedic')
					{
						$sn = showRN('GDRC');
					}else{
						if($_GET['docVisit']=='gnlPersoMedic')
						{
							$sn = showRN('GDRG');
						}
					}
				}
			}
		}
	
		if($_GET['docVisit']=='dailyPersoBill')
		{
			$sn = showRN('GDRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoBill')
			{
				$sn = showRN('GDRM');
			}else{
				if($_GET['docVisit']=='annualyPersoBill')
				{
					$sn = showRN('GDRA');
				}else{
					if($_GET['docVisit']=='customPersoBill')
					{
						$sn = showRN('GDRC');
					}else{
						if($_GET['docVisit']=='gnlPersoBill')
						{
							$sn = showRN('GDRG');
						}
					}
				}
			}
		}
	}

	
		if(isset($_GET['docVisit']))
		{
			$docVisit=$_GET['docVisit'];
		}
		
		if(isset($_GET['docVisitgnl']))
		{
			$docVisitgnl=$_GET['docVisitgnl'];
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
	<title><?php echo 'Doctor Report#'.$sn; ?></title>

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
	if(isset($_GET['med']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
		$result->execute(array(
		'operation'=>$_GET['med']	
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
		
		$idDoc=$_GET['med'];
		$dailydateperso=$_GET['dailydateperso'];
		$docVisit=$_GET['docVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
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
					->setCellValue('J1', ''.$sn.'')
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
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Hospitalisation Doctor Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?><?php if(isset($_GET['hospi'])){echo'&hospi=ok';} ?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<?php
				if(isset($_SESSION['codeC']))
				{
				?>
				<td style="text-align:left">
					
					<form method="post" action="doctor_report.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&coordi=<?php echo $_SESSION['id'];?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';} if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<?php
				}
				?>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="report.php?med=<?php echo $_GET['med'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
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
	
		if (isset($_GET['clinic'])) {
			if(isset($_GET['divPersoMedicReport']))
			{
		?>
			<div id="divPersoMedicReport">

				<?php
				
				$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
				$resultConsult->execute(array(
				'med'=>$idDoc
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
				//echo $comptConsult;
		
				$i=0;
				
				if($comptConsult != 0)
				{
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A8', 'N°')
								->setCellValue('B8', 'Date of consultation')
								->setCellValue('C8', 'Patient name')
								->setCellValue('D8', 'Age')
								->setCellValue('E8', 'Gender')
								->setCellValue('F8', 'Type of consultation')
								->setCellValue('G8', 'Services')
								->setCellValue('H8', 'Nursing Care')
								->setCellValue('I8', 'Laboratory tests')
								->setCellValue('J8', 'Diagnosis');
					
				?>
					
					<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
					
					<thead>
						<tr>
							<th style="width:2%; border-right: 1px solid #bbb;text-align: center;">N°</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Date of consultation</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Full name</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Assurance</th>
							<th style="text-align:center; width:10%; border-right: 1px solid #bbb;text-align: center;" colspan=2>Age/Sexe</th>
							<th style="width:15%; border-right: 1px solid #bbb;text-align: center;"><?php echo getString(113);?></th>
						    <th style="width:20%;text-align: center;"><?php echo getString(279);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medicament';?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo 'Consommables';?></th>
							<th style="width:15%;text-align:center;"><?php echo getString(99);?></th>
							<th style="width:15%;text-align:center;"><?php echo 'Diagnosis';?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
					// $date='0000-00-00';
					$compteur=1;
					
						while($ligneConsult=$resultConsult->fetch())
						{
						
							$consult = "";
							$nursery = "";
							$labs = "";						
							$diagno = "";
							
				?>
						<tr>
							<td style="text-align:center;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td style="text-align: center;">
							<?php 
								echo $ligneConsult->dateconsu;
								$dateconsu = $ligneConsult->dateconsu;
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneConsult->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
									
				$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($lignePatient->date_naissance)));
				$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
				$interval = $datetime1->diff($datetime2);
				
				if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
				{
					$agePa = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
				}
										
									$sexePa = $lignePatient->sexe;
									
									echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
								}else{
									$fullnamePa="";
									echo '';
								}
							?>
							</td>
							<td style="text-align: center;">
								<?php echo $ligneConsult->assuranceConsuName.'<br><b>('.$ligneConsult->insupercent.')</b>';  ?>
							</td>
							
							<td style="text-align:center;">
							<?php echo $agePa;?>
							</td>
							
							<td style="text-align:center;">
							<?php echo $sexePa; ?>
							</td>
							
							<td style="text-align:center;">
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								if($lignePresta->namepresta != '')
								{
									$nameprestaConsult = $lignePresta->namepresta;
									echo $nameprestaConsult.'</td>';
								}else{	
								
									if($lignePresta->nompresta != '')
									{
										$nameprestaConsult = $lignePresta->nompresta;
										echo $nameprestaConsult.'</td>';
									}

								}
							}
							?>

						<td style="text-align: center;">
						<?php
						$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_uM=:med AND ms.id_consuSurge=:idMedConsu ORDER BY ms.id_medsurge');		
						$resultMedSurge->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedSurge=$resultMedSurge->rowCount();
					
					
						if($comptMedSurge != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
								<?php
								while($ligneMedSurge=$resultMedSurge->fetch())
								{
								?>
								<tr style="text-align:center;">
									
									<td style="text-align:center;">
									<?php
									
									$idassuSurge=$ligneMedSurge->id_assuSurge;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($i=1;$i<=$assuCount;$i++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuSurge
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuServ='prestations_'.strtolower($ligneNomAssu->nomassurance);
										}
									}
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedSurge->id_prestationSurge
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
											
										}
									}else{
										
										echo $ligneMedSurge->autrePrestaS.'</td>';
									}
									?>
								</tr>
								<?php
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
							
							<td style="text-align:center;">
							
							<?php
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_factureMedConsu!=0 AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_consu
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsult=$resultMedConsult->rowCount();
						
						
							if($comptMedConsult != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								

								<tbody>
									<?php
										
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
									?>
									<tr>
										
										<td style="text-align:center;">
										<?php
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
										
										$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuConsu->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
										{
											
											$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuConsu->execute(array(
											'idassu'=>$idassuServ
											));
											
											$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssu=$getAssuConsu->fetch())
											{
												$presta_assuServ='prestations_'.strtolower($ligneNomAssu->nomassurance);
											}
										}
										
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												
												$consult .= ''.$lignePresta->namepresta.', ';
											}else{								
												echo $lignePresta->nompresta.'</td>';
												
												$consult .= ''.$lignePresta->nompresta.', ';
												
											}
										}else{
											
											echo $ligneMedConsult->autreConsu.'</td>';
											
											$consult .= ''.$ligneMedConsult->autreConsu.', ';
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							?>
							</td>
						
							<td style="text-align:left;">
							
							<?php
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE  mi.id_uM=:med AND mi.id_factureMedInf!=0 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_consu
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

							$comptMedInf=$resultMedInf->rowCount();
						
						
							if($comptMedInf != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
												
										$idassuInf=$ligneMedInf->id_assuInf;
										
										$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuInf->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuInf->execute(array(
											'idassu'=>$idassuInf
											));
											
											$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssuInf=$getAssuInf->fetch())
											{
												$presta_assuInf='prestations_'.strtolower($ligneNomAssuInf->nomassurance);
											}
										}
									?>
									<tr>
										<td style="text-align:center;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												
												$nursery .= ''.$lignePresta->namepresta.', ';
												
											}else{								
												echo $lignePresta->nompresta.'</td>';
												
												$nursery .= ''.$lignePresta->namepresta.', ';
											}
										}else{
											
											echo $ligneMedInf->autrePrestaM.'</td>';
											
											$nursery .= ''.$ligneMedInf->autrePrestaM.', ';
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							?>
							</td>
								<td style="text-align:left;">
							
							<?php

							$resultMedoc=$connexion->prepare('SELECT *FROM med_medoc medoc WHERE medoc.id_uM=:med AND medoc.id_factureMedMedoc!=0 AND medoc.id_consuMedoc=:id_consuMedoc ORDER BY medoc.id_medmedoc');		
							$resultMedoc->execute(array(
							'med'=>$idDoc,					
							'id_consuMedoc'=>$ligneConsult->id_consu
							));
							
							$resultMedoc->setFetchMode(PDO::FETCH_OBJ);

							$comptMedoc=$resultMedoc->rowCount();
						
							$TotalMedoc=0;	
							$TotalMedocPatient=0;	
							$TotalMedocInsurance=0;	
											
							if($comptMedoc != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedoc=$resultMedoc->fetch())
									{
												
										$idassumedoc=$ligneMedoc->id_assuMedoc;
										
										$comptAssumedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssumedoc->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssumedoc->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssumedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssumedoc->execute(array(
											'idassu'=>$idassumedoc
											));
											
											$getAssumedoc->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssumedoc=$getAssumedoc->fetch())
											{
												$presta_assumedoc='prestations_'.strtolower($ligneNomAssumedoc->nomassurance);
											}
										}
									?>
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedoc.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedoc->id_prestationMedoc
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.' <span style="color:red;font-weight:bold;">('.$ligneMedoc->qteMedoc.')</span></td>';					
												$prestamedoc[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.' <span style="color:red;font-weight:bold;">('.$ligneMedoc->qteMedoc.')</span></td>';			
												$prestamedoc[] = $lignePresta->nompresta;
											}					
																
											$nursery .= ''.$ligneMedoc->prixprestationMedoc.', ';
										}else{
											
											echo $ligneMedoc->autreMedoc.'</td>';
												$prestamedoc[] = $ligneMedoc->autreMedoc;
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
						
							?>
							</td>
							<td style="text-align:left;">
							
							<?php

							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom consom WHERE consom.id_uM=:med AND consom.id_factureMedConsom!=0 AND consom.id_consuConsom=:id_consuConsom ORDER BY consom.id_medconsom');		
							$resultMedConsom->execute(array(
							'med'=>$idDoc,					
							'id_consuConsom'=>$ligneConsult->id_consu
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

							$comptMedconsom=$resultMedConsom->rowCount();
						
							if($comptMedconsom != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedConsom=$resultMedConsom->fetch())
									{
												
										$idassumedconsom=$ligneMedConsom->id_assuConsom;
										
										$comptAssumedconsom=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssumedconsom->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssumedconsom->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssumedconsom=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssumedconsom->execute(array(
											'idassu'=>$idassumedconsom
											));
											
											$getAssumedconsom->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssumedconsom=$getAssumedconsom->fetch())
											{
												$presta_assumedconsom='prestations_'.strtolower($ligneNomAssumedconsom->nomassurance);
											}
										}
									?>
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedconsom.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsom->id_prestationConsom	
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.' <span style="color:red;font-weight:bold;">('.$ligneMedConsom->qteConsom.')</span></td>';					
											}else{								
												echo $lignePresta->nompresta.' <span style="color:red;font-weight:bold;">('.$ligneMedConsom->qteConsom.')</span></td>';		
												$prestamedoc[] = $lignePresta->nompresta;
											}					
																
										}else{
											
											echo $lignePresta->autreConsom.' <span style="color:red;font-weight:bold;">('.$ligneMedConsom->qteConsom.')</span></td>';
												$prestamedoc[] = $ligneMedConsom->autreConsom;
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
						
							?>
							</td>
						
							<td style="text-align:left;">
							
							<?php
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();


							if($comptMedLabo != 0)
							{
							?>	
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
										
										$idassuLab=$ligneMedLabo->id_assuLab;

										$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuLab->rowCount();
										
										for($l=1;$l<=$assuCount;$l++)
										{
											
											$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuLab->execute(array(
											'idassu'=>$idassuLab
											));
											
											$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssuLab=$getAssuLab->fetch())
											{
												$presta_assuLab='prestations_'.strtolower($ligneNomAssuLab->nomassurance);
											}
										}

									?>
									<tr>
										<td style="text-align:center;">
											<?php
											$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta=$resultPresta->rowCount();
											
											if($lignePresta=$resultPresta->fetch())
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta;
													
													$labs .= ''.$lignePresta->namepresta.', ';
												}else{
													
													echo $lignePresta->nompresta;
													
													$labs .= ''.$lignePresta->nompresta.', ';
												}
											}else{
												
												echo $ligneMedLabo->autreExamen;
												
												$labs .= ''.$ligneMedLabo->autreExamen.', ';
											}
											?>
										</td>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							?>
							</td>

							<td style="text-align:left;font-weight:bold;">
							<?php
										
						$Postdia = array();
						$DiagnoPostDone=0;
															
						$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
						
						$resuPostdiagnostic->execute(array(
						'idConsu'=>$ligneConsult->id_consu
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


			$Predia = array();
							
			$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
			
			$resuPrediagnostic->execute(array(
			'idConsu'=>$ligneConsult->id_consu
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
				}else{
				
					if($lignePrediagnostic->prediagnostic != "")
					{
						$Predia[] = $lignePrediagnostic->prediagnostic;
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
					}else{
						$Predia[] = $linePrediagno->autrepredia;
					}
					
				}
			
			}
		
			$Postdia = array();
						
			if(isset ($_GET['idconsu']) AND $ligneConsultation->postdiagnostic !="")
			{
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$ligneConsultation->postdiagnostic
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Postdia[] = $ligneDiagnoPost->nomdiagno;
				}else{
					$Postdia[] = $ligneConsultation->postdiagnostic;
				}
				
			}
				
			$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
			
			$resultatsPostDiagno->execute(array(
			'id_consudia'=>$ligneConsult->id_consu
			
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
					}else{
						$Postdia[] = $linePostDiagno->autrepostdia;
					}
					
				}
			
			}
						
						
						if($DiagnoPostDone ==0)
						{	
							for($p=0;$p<sizeof($Predia);$p++)
							{
								echo '-'.$Predia[$p].'<br/>';
							}
						}else{
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								echo '- '.$Postdia[$p].'<br/>';
							}
						}
						?>	
							</td>
							
						</tr>
				<?php
				
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateconsu;
							$arrayConsult[$i][2]=$fullnamePa;
							$arrayConsult[$i][3]=$agePa;
							$arrayConsult[$i][4]=$sexePa;
							$arrayConsult[$i][5]=$nameprestaConsult;
							// $arrayConsult[$i][5]='';
							$arrayConsult[$i][6]=$consult;
							$arrayConsult[$i][7]=$nursery;
							$arrayConsult[$i][8]=$labs;
							
							
							if($DiagnoPostDone ==0)
							{
								$diagnoPre ='';
								
								for($p=0;$p<sizeof($Predia);$p++)
								{
									$diagnoPre .= $Predia[$p].',';
								}
								$arrayConsult[$i][9]=$diagnoPre;
							}else{
							
								$diagnoPost ='';
								
								for($p=0;$p<sizeof($Postdia);$p++)
								{
									$diagnoPost .= $Postdia[$p].',';
								}
								$arrayConsult[$i][9]=$diagnoPost;
							}
							
							$i++;
							
							$compteur++;
						}
				?>		
					</tbody>
					</table>
				<?php
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','A10');
							
				}
				?>
			</div>
			<?php
			
				if(isset($_GET['createReportExcel']))
				{
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
					
					if($_GET['docVisit']=='dailyPersoMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Daily/'.$reportsn.'.xlsx');
						
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Daily/");</script>';
						
					}else{
						if($_GET['docVisit']=='monthlyPersoMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Monthly/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Monthly/");</script>';
							
						}else{
							if($_GET['docVisit']=='annualyPersoMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Annualy/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Annualy/");</script>';
								
							}else{
								if($_GET['docVisit']=='customPersoMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Custom/'.$reportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Custom/");</script>';
									
								}else{
									if($_GET['docVisit']=='gnlPersoMedic')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Alltimes/'.$reportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Alltimes/");</script>';
										
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
						createRN('DRD');
						
					}else{
						if($_GET['docVisit']=='monthlyPersoMedic')
						{
							createRN('DRM');

						}else{
							if($_GET['docVisit']=='annualyPersoMedic')
							{
								createRN('DRA');
								
							}else{
								if($_GET['docVisit']=='customPersoMedic')
								{	
									createRN('DRC');
								
								}else{
									if($_GET['docVisit']=='gnlPersoMedic')
									{
										createRN('DRG');
									
									}
								}
							}
						}
					}
					
					echo '<script text="text/javascript">document.location.href="doctor_report.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&clinic='.$_GET['clinic'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoMedicReport=ok&createReportPdf=ok&createRN=0"</script>';
				}			
			}
			
			if(isset($_GET['divPersoBillReport']))
			{
		?>
			<div id="2010">

				<?php
				$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.id_factureConsult IS NOT NULL AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
				$resultConsult->execute(array(
				'med'=>$idDoc
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
		
				$i=0;
				
				if($comptConsult != 0)
				{
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A8', 'N°')
								->setCellValue('B8', 'Date of consultation')
								->setCellValue('C8', 'Patient name')
								->setCellValue('D8', 'Type of consultation')
								->setCellValue('E8', 'Amount')
								->setCellValue('F8', 'Services')
								->setCellValue('G8', 'Amount')
								->setCellValue('H8', 'Nursing Care')
								->setCellValue('I8', 'Amount')
								->setCellValue('J8', 'Laboratory tests')
								->setCellValue('K8', 'Amount')
								->setCellValue('L8', 'Radiologie')
								->setCellValue('M8', 'Amount')
								->setCellValue('N8', 'Total');
					
				?>
					
					<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
					
					<thead>
						<tr>
							<th style="width:2%; border-right: 1px solid #bbb">N°</th>
							<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
							<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
							<th style="width:15%; border-right: 1px solid #bbb">Assurance</th>
							<th style="width:20%; border-right: 1px solid #bbb" colspan=2><?php echo getString(113);?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Medical Acts';?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Medicament';?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Consommables';?></th>
							<th style="width:15%;"><?php echo getString(99);?></th>
							<th style="width:10%;"><?php echo 'Radiologie';?></th>
							<th style="width:10%;">Total Final</th>
							<th style="width:10%;">Total Patients</th>
							<th style="width:10%;">Total Insurance</th>
						</tr> 
					</thead> 


					<tbody>
				<?php
					$TotalGnlTypeConsu=0;
						
					$TotalGnlMedConsu=0;
						
					$TotalGnlMedInf=0;

					$TotalGnlMedConsom=0;

					$TotalGnlMedoc=0;
						
					$TotalGnlMedLabo=0;

					$TotalGnlMedSurge=0;
						
					$TotalGnlMedRadio=0;
						
					$TotalGnlPrice=0;

					$TotalGnlPricePatient=0;

					$TotalGnlPriceInsurance=0;
						
					
					$compteur=1;
					
						while($ligneConsult=$resultConsult->fetch())
						{
							$presta_assu = 'prestations_'.$ligneConsult->assuranceConsuName;
							$prestaServ=array();
							$prixprestaServ=array();
							
							$prestaInf=array();
							$prixprestaInf=array();

							$prestamedoc=array();
							$prixprestamedoc=array();
							
							$prestaLabo=array();
							$prixprestaLabo=array();

							$prestaSurge=array();
							$prixprestaSurge=array();
							
							$prestaRadio=array();
							$prixprestaRadio=array();
							
							$TotalDayPrice=0;
							$TotalDayPricePatient=0;
							$TotalDayPriceInsurance=0;
							
							$consult = "";
							$nursery = "";
							$meddoc = "";
							$labs = "";						
							$radx = "";
							$consom ="";
							
				?>
						<tr>
							<td style="text-align:left;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td>
							<?php 
								echo $ligneConsult->dateconsu;
								$dateconsu = $ligneConsult->dateconsu;
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneConsult->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
									
									$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
									
								}else{
									$fullnamePa="";
									echo '';
								}
							?>
							</td>
							<td style="text-align: center;">
								<?php echo $ligneConsult->assuranceConsuName.'<br><b>('.$ligneConsult->insupercent.')</b>';  ?>
							</td>
							
							<td style="text-align:left;">
							<?php
							
							$TotalTypeConsu=0;
							$TotalTypeConsuPatient = 0;
							$TotalTypeConsuInsurance = 0;
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								if($lignePresta->namepresta != '')
								{
									$nameprestaConsult = $lignePresta->namepresta;
								}else{	
								
									if($lignePresta->nompresta != '')
									{
										$nameprestaConsult = $lignePresta->nompresta;
									}

								}
							}						
								echo $nameprestaConsult;
							?>
							</td>
							<td style="text-align:center;">
								<?php
									echo $ligneConsult->prixtypeconsult;
									$insupeConsu = $ligneConsult->insupercent;
									
									$prixconsult=$ligneConsult->prixtypeconsult;
									
									$TotalTypeConsu=$TotalTypeConsu+$prixconsult;

									$TotalTypeConsuPatient = $TotalTypeConsuPatient + (($prixconsult * $insupeConsu) / 100);
									$TotalTypeConsuInsurance = $TotalTypeConsuInsurance + ($TotalTypeConsu - $TotalTypeConsuPatient);
									
									$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
									$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalTypeConsuInsurance;
								?>
							</td>

							<td style="text-align:left;">
							
							<?php
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_factureMedConsu!=0 AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_consu
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsult=$resultMedConsult->rowCount();
						
							$TotalMedConsu=0;
							$TotalMedConsuPatient=0;
							$TotalMedConsuInsurance=0;
											
							if($comptMedConsult != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
										
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuServ->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
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
									<tr>
										
										<td style="text-align:left;">
										<?php	
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaServ[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';
												$prestaServ[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td>';
											$prixconsu=$ligneMedConsult->prixprestationConsu;											
											$consult .= ''.$ligneMedConsult->prixprestationConsu.', ';
										}else{
											
											echo $ligneMedConsult->autreConsu.'</td>';
											$prestaServ[] = $ligneMedConsult->autreConsu;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td>';
											
											$prixconsu=$ligneMedConsult->prixautreConsu;
											$consult .= ''.$ligneMedConsult->prixautreConsu.', ';
										}
										?>
									</tr>
									<?php
									
										$prixprestaServ[] = $prixconsu;
										$insupeService = $ligneMedConsult->insupercentServ;
										
										$TotalMedConsu=$TotalMedConsu+$prixconsu;

										$TotalMedConsuPatient = $TotalMedConsuPatient + (($prixconsu*$insupeService) / 100);
										$TotalMedConsuInsurance = $TotalMedConsuInsurance + ($TotalMedConsu - $TotalMedConsuPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedConsuInsurance;
							if ($TotalMedConsu!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:50px;">Total = '.$TotalMedConsu.'</b>';
							}
							?>
							</td> 
							<!-- <td> -->
								<td style="text-align: center;">
						<?php
						$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_uM=:med AND ms.id_consuSurge=:idMedConsu ORDER BY ms.id_medsurge');		
						$resultMedSurge->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedSurge=$resultMedSurge->rowCount();
					
						$TotalMedSurge = 0;
						$TotalMedSurgePatient = 0;
						$TotalMedSurgeInsurance = 0;

						if($comptMedSurge != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
								<?php
								while($ligneMedSurge=$resultMedSurge->fetch())
								{
								?>
								<tr style="text-align:center;">
									
									<td style="text-align:center;">
									<?php
									
									$idassuSurge=$ligneMedSurge->id_assuSurge;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($i=1;$i<=$assuCount;$i++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuSurge
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedSurge->id_prestationSurge
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedSurge->prixprestationSurge.'</td>';
											
											$prixSurge=$ligneMedSurge->prixprestationSurge;
											// $labs .= ''.$lignePresta->prixprestationSurge.', ';
										}else{								
											echo $lignePresta->nompresta.'</td>';

											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedSurge->prixautrePrestaS.'</td>';
											$prixSurge=$ligneMedSurge->prixautrePrestaS;
											//$labs .= ''.$ligneMedSurge->prixautreExamen.', ';
											
										}
									}else{
										
										echo $ligneMedSurge->autrePrestaS.'</td>';
									}
									?>
								</tr>
								<?php
									$prixprestaSurge[] = $prixSurge;
										
									$TotalMedSurge=$TotalMedSurge+$prixSurge;

									$insupeSurge = $ligneMedSurge->insupercentSurge;
										
									$TotalMedSurgePatient = $TotalMedSurgePatient +(($prixSurge*$insupeSurge) / 100);
									$TotalMedSurgeInsurance =$TotalMedSurgeInsurance +($TotalMedSurge - $TotalMedSurgePatient);
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						$TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
						$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;
						$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedSurgeInsurance;
						if ($TotalMedSurge!=0) {
							echo "<hr/>";
							echo '<b style="">Total = '.$TotalMedSurge.'</b>';
						}
						
						?>
						</td>
							<!-- </td> -->
							

						
							<td style="text-align:left;">
							
							<?php

							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_uM=:med AND mi.id_factureMedInf!=0 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_consu
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

							$comptMedInf=$resultMedInf->rowCount();
						
							$TotalMedInf=0;	
							$TotalMedInfPatient=0;	
							$TotalMedInfInsurance=0;	
											
							if($comptMedInf != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
												
										$idassuInf=$ligneMedInf->id_assuInf;
										
										$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuInf->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
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
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';					
												$prestaInf[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';		
												$prestaInf[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td>';
											$prixinf=$ligneMedInf->prixprestation;						
																
											$nursery .= ''.$ligneMedInf->prixprestation.', ';
										}else{
											
											echo $ligneMedInf->autrePrestaM.'</td>';
												$prestaInf[] = $ligneMedInf->autrePrestaM;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
											$prixinf=$ligneMedInf->prixautrePrestaM;						
																
											$nursery .= ''.$ligneMedInf->prixautrePrestaM.', ';
										}
										?>
									</tr>
									<?php
										$prixprestaInf[] = $prixinf;
										
										$TotalMedInf=$TotalMedInf+$prixinf;

										$insupercentInf = $ligneMedInf->insupercentInf;
										
										$TotalMedInfPatient = $TotalMedInfPatient + (($prixinf*$insupercentInf) / 100);
										$TotalMedInfInsurance = $TotalMedInfInsurance + ($TotalMedInf - $TotalMedInfPatient);

									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedInfPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedInfInsurance;
							if ($TotalMedInf!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:50px;">Total = '.$TotalMedInf.'</b>';
							}
							?>
							</td>
							<td style="text-align:left;">
							
							<?php

							$resultMedoc=$connexion->prepare('SELECT *FROM med_medoc medoc WHERE medoc.id_uM=:med AND medoc.id_factureMedMedoc!=0 AND medoc.id_consuMedoc=:id_consuMedoc ORDER BY medoc.id_medmedoc');		
							$resultMedoc->execute(array(
							'med'=>$idDoc,					
							'id_consuMedoc'=>$ligneConsult->id_consu
							));
							
							$resultMedoc->setFetchMode(PDO::FETCH_OBJ);

							$comptMedoc=$resultMedoc->rowCount();
						
							$TotalMedoc=0;	
							$TotalMedocPatient=0;	
							$TotalMedocInsurance=0;	
											
							if($comptMedoc != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedoc=$resultMedoc->fetch())
									{
												
										$idassumedoc=$ligneMedoc->id_assuMedoc;
										
										$comptAssumedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssumedoc->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssumedoc->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssumedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssumedoc->execute(array(
											'idassu'=>$idassumedoc
											));
											
											$getAssumedoc->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssumedoc=$getAssumedoc->fetch())
											{
												$presta_assumedoc='prestations_'.$ligneNomAssumedoc->nomassurance;
											}
										}
									?>
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedoc.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedoc->id_prestationMedoc
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';					
												$prestamedoc[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';		
												$prestamedoc[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedoc->prixprestationMedoc.'   <b style="font-size:7px;font-weight:400;">('.$ligneMedoc->qteMedoc.' Qty )</b></td>';
											$qty = $ligneMedoc->qteMedoc;			
											$prixmedoc=$ligneMedoc->prixprestationMedoc *$qty;						
																
											$nursery .= ''.$ligneMedoc->prixprestationMedoc*$qty.', ';
										}else{
											
											echo $ligneMedoc->autreMedoc.'</td>';
												$prestamedoc[] = $ligneMedoc->autreMedoc;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedoc->prixautreMedoc.'   <b style="font-size:7px;font-weight:400;">('.$ligneMedoc->qteMedoc.' Qty )</b></td>';
											$qty = $ligneMedoc->qteMedoc;		
											$prixmedoc=$ligneMedoc->prixautreMedoc*$qty;						
																
											$meddoc .= ''.$ligneMedoc->prixautreMedoc*$qty.', ';
										}
										?>
									</tr>
									<?php
										$prixprestamedoc[] = $prixmedoc;
										
										$TotalMedoc=$TotalMedoc+$prixmedoc;

										$insupercentMedoc = $ligneMedoc->insupercentMedoc;
										
										$TotalMedocPatient = $TotalMedocPatient + (($prixmedoc*$insupercentMedoc) / 100);
										$TotalMedocInsurance = $TotalMedocInsurance + ($TotalMedoc - $TotalMedocPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedoc;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedocPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedocInsurance;
							if ($TotalMedoc!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:50px;">Total = '.$TotalMedoc.'</b>';
							}
							?>
							</td>

														<td style="text-align:center;">
							
							<?php
							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mc WHERE mc.id_consuConsom =:idMedConsom AND id_factureMedConsom!=0 ORDER BY mc.id_consuConsom ');		
							$resultMedConsom->execute(array(
							'idMedConsom'=>$ligneConsult->id_consu
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsom=$resultMedConsom->rowCount();
							//echo $ligneConsult->id_consu;
						
							$TotalMedConsom=0;
							$TotalMedConsomPatient=0;
						    $TotalMedConsomInsurance=0;
											
							if($comptMedConsom != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
										
									while($ligneMedConsom=$resultMedConsom->fetch())
									{
										
										$idassuServ=$ligneMedConsom->id_assuConsom;
										
										$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuServ->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
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
									<tr>
										
										<td style="text-align:center;">
										<?php	
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsom->id_prestationConsom
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaConsom[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';
												$prestaConsom[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsom->prixprestationConsom.'   <b style="font-size:7px;font-weight:400;">('.$ligneMedConsom->qteConsom.' Qty )</b></td>';
											$qty = 	$ligneMedConsom->qteConsom;									
											$prixconsom = ($ligneMedConsom->prixprestationConsom) * $qty;	
											$consom .= ''.$ligneMedConsom->prixprestationConsom.', ';
										}else{
											
											echo $ligneMedConsom->autreConsom.'</td>';
											$prestaConsom[] = $ligneMedConsom->autreConsom;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsom->prixautreConsom.'</td>';
											$qty = 	$ligneMedConsom->qteConsom;									
											$prixconsom = ($ligneMedConsom->prixautreConsom) * $qty;
											$consom .= ''.$ligneMedConsom->prixautreConsom.', ';
										}
										?>
									</tr>
									<?php
											$prixprestaConsom[] = $prixconsom;
											$insupeConsom = $ligneMedConsom->insupercentConsom;
											
											$TotalMedConsom=$TotalMedConsom+$prixconsom;

											$TotalMedConsomPatient = $TotalMedConsomPatient + (($prixconsom*$insupeConsom) / 100);
											$TotalMedConsomInsurance = $TotalMedConsomInsurance + ($TotalMedConsom - $TotalMedConsomPatient);
										}
										?>		
									</tbody>
									</table>
								<?php
								}
								
								$TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
								
								$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsomPatient;
								$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedConsomInsurance;
								if ($TotalMedConsom!=0) {
									echo "<hr/>";
									echo '<b style="">Total = '.$TotalMedConsom.'</b>';
								}
							?>
							</td>
						
							<td style="text-align:left;">
							
							<?php
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_factureMedLabo!=0 AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();

							$TotalMedLabo=0;
							$TotalMedLaboPatient=0;
							$TotalMedLaboInsurance=0;
							
							if($comptMedLabo != 0)
							{
							?>	
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
									
										$idassuLab=$ligneMedLabo->id_assuLab;

										$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuLab->rowCount();
										
										for($l=1;$l<=$assuCount;$l++)
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

									?>
									<tr>
										<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta;
												$prestaLabo[] = $lignePresta->namepresta;
												
											}else{
												
												echo $lignePresta->nompresta;
												$prestaLabo[] = $lignePresta->nompresta;
											}
																
											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
											$prixlabo=$ligneMedLabo->prixprestationExa;
											$labs .= ''.$ligneMedLabo->prixprestationExa.', ';
										}else{
											
											echo $ligneMedLabo->autreExamen;
											$prestaLabo[] = $ligneMedLabo->autreExamen;
												
											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
											$prixlabo=$ligneMedLabo->prixautreExamen;
											$labs .= ''.$ligneMedLabo->prixautreExamen.', ';
										}
										?>
										</td>
									</tr>
									<?php
										$prixprestaLabo[] = $prixlabo;
										
										$TotalMedLabo=$TotalMedLabo+$prixlabo;

										$insupercentLab = $ligneMedLabo->insupercentLab;
										
										$TotalMedLaboPatient = $TotalMedLaboPatient + (($prixlabo*$insupercentLab) / 100);
										$TotalMedLaboInsurance = $TotalMedLaboInsurance + ($TotalMedLabo - $TotalMedLaboPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedLaboPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedLaboInsurance;
							if ($TotalMedLabo!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:40px;">Total = '.$TotalMedLabo.'</b>';
							}
							?>
							</td>

							<td style="text-align:left;font-weight:normal;">					
							<?php
									
							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_uM=:med AND mr.id_factureMedRadio!=0 AND mr.id_consuRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
							$resultMedRadio->execute(array(
							'med'=>$idDoc,					
							'idMedRadio'=>$ligneConsult->id_consu
							));
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

							$comptMedRadio=$resultMedRadio->rowCount();
							
							$TotalMedRadio=0;
							$TotalMedRadioPatient=0;
							$TotalMedRadioInsurance=0;
							
							if($comptMedRadio!=0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedRadio=$resultMedRadio->fetch())
									{
										
										$idassuRad=$ligneMedRadio->id_assuRad;

										$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuRad->rowCount();
										
										for($r=1;$r<=$assuCount;$r++)
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
									<tr>
										<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedRadio->id_prestationRadio
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaRadio[] = $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta.'</td>';
												$prestaRadio[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td>';
											$prixradio=$ligneMedRadio->prixprestationRadio;	
											$radx .= ''.$ligneMedRadio->prixprestationRadio.', ';
										}else{
											
											echo $ligneMedRadio->autreRadio.'</td>';
											$prestaRadio[] = $ligneMedRadio->autreRadio;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
											$prixradio=$ligneMedRadio->prixautreRadio;
											$radx .= ''.$ligneMedRadio->prixautreRadio.', ';
										}									
										?>
										
									</tr>
									<?php
										$prixprestaRadio[] = $prixradio;
										
										$TotalMedRadio=$TotalMedRadio+$prixradio;

										$insupercentRad = $ligneMedRadio->insupercentRad;
										
										$TotalMedRadioPatient = $TotalMedRadioPatient + (($prixradio*$insupercentRad) / 100);
										$TotalMedRadioInsurance = $TotalMedRadioInsurance + ($TotalMedRadio - $TotalMedRadioPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}							
							$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;							
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedRadioPatient;							
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedRadioInsurance;							
							?>	
								
							<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
							<!-- <?php
								$insupercent = $ligneConsult->insupercent;
								$idConsu = $ligneConsult->id_consu;
								echo "insupercent = ".$idConsu;
							?> -->
							<td style="text-align:center;">
								<?php
									echo $TotalDayPricePatient;
								?>
							</td>
							<td style="text-align:center;">
								<?php
									$AssuranceDayPrice = $TotalDayPrice - $TotalDayPricePatient;
									echo $AssuranceDayPrice;
								?>
							</td>
							
						</tr>
						<?php
						$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
					
						$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
							
						$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;

						$TotalGnlMedoc=$TotalGnlMedoc + $TotalMedoc;

						$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
						
						$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;

						$TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
							
						$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
						
						$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
							
						$TotalGnlPricePatient=$TotalGnlPricePatient + $TotalDayPricePatient;	
							
						$TotalGnlPriceInsurance=$TotalGnlPriceInsurance + $AssuranceDayPrice;	
							
						
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateconsu;
							$arrayConsult[$i][2]=$fullnamePa;
							$arrayConsult[$i][3]=$nameprestaConsult;
							$arrayConsult[$i][4]=$prixconsult;
							
						
							$highNumber = max(sizeof($prixprestaServ),sizeof($prixprestaInf),sizeof($prixprestaLabo),sizeof($prixprestaRadio));
							
							if($highNumber==0)
							{
								$i++;
							}
						// echo '- Services : '.sizeof($prixprestaServ).'<br/>- Inf : '.sizeof($prixprestaInf).'<br/>- Labo : '.sizeof($prixprestaLabo).'<br/>- Radio : '.sizeof($prixprestaRadio).'<br/>';
							
							for($xLigne=0;$xLigne<$highNumber;$xLigne++)
							{
								if($xLigne>0)
								{
									for($e=0;$e<5;$e++)
									{
										$arrayConsult[$i][$e]='';			
									}
								}
								
								if($xLigne < sizeof($prixprestaServ))
								{						
									$arrayConsult[$i][5]=$prestaServ[$xLigne];		
									$arrayConsult[$i][6]=$prixprestaServ[$xLigne];		
								}else{
									$arrayConsult[$i][5]='';
									$arrayConsult[$i][6]='';
								}
								
								if($xLigne < sizeof($prixprestaInf))
								{						
									$arrayConsult[$i][7]=$prestaInf[$xLigne];
									$arrayConsult[$i][8]=$prixprestaInf[$xLigne];
								}else{
									$arrayConsult[$i][7]='';
									$arrayConsult[$i][8]='';
								}
								
								if($xLigne < sizeof($prixprestaLabo))
								{						
									$arrayConsult[$i][9]=$prestaLabo[$xLigne];		
									$arrayConsult[$i][10]=$prixprestaLabo[$xLigne];		
								}else{
									$arrayConsult[$i][9]='';
									$arrayConsult[$i][10]='';
								}
								
								if($xLigne < sizeof($prixprestaRadio))
								{						
									$arrayConsult[$i][11]=$prestaRadio[$xLigne];		
									$arrayConsult[$i][12]=$prixprestaRadio[$xLigne];		
								}else{
									$arrayConsult[$i][11]='';
									$arrayConsult[$i][12]='';
								}
								
								if($xLigne==0)
								{
									$arrayConsult[$i][13]=$TotalDayPrice;
								}
									
								$i++;
							}					
							$compteur++;
						}
						
						?>	

						<tr style="text-align:center;">
							<td colspan=5></td>
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
									echo $TotalGnlMedSurge;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedInf;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedoc;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedConsom;				
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
									echo $TotalGnlPriceInsurance;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>					
					</tbody>
					</table>
				<?php
					
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','A10')
							
								->setCellValue('E'.(11+$i).'', ''.$TotalGnlTypeConsu.'')
								->setCellValue('G'.(11+$i).'', ''.$TotalGnlMedConsu.'')
								->setCellValue('I'.(11+$i).'', ''.$TotalGnlMedInf.'')
								->setCellValue('K'.(11+$i).'', ''.$TotalGnlMedLabo.'')
								->setCellValue('M'.(11+$i).'', ''.$TotalGnlMedRadio.'')
								->setCellValue('N'.(11+$i).'', ''.$TotalGnlPrice.'');

				}
				?>
			</div>
			<?php
			
				if(isset($_GET['createReportExcel']))
				{
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
					
					if($_GET['docVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Daily/'.$reportsn.'.xlsx');
						
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Daily/");</script>';
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Monthly/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Monthly/");</script>';
							
						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Annualy/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Annualy/");</script>';
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Custom/'.$reportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Custom/");</script>';
									
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Alltimes/'.$reportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
				}
				
				if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
				{				
					if($_GET['docVisit']=='dailyPersoBill')
					{
						createRN('DRD');
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							createRN('DRM');

						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								createRN('DRA');
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{	
									createRN('DRC');
								
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										createRN('DRG');
									
									}
								}
							}
						}
					}
					
					echo '<script text="text/javascript">document.location.href="doctor_report.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&clinic='.$_GET['clinic'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
				}
			}
		}
		if (isset($_GET['hospi'])) {
		
				if(isset($_GET['divPersoMedicReport']))
			{ 
				//echo "string2";
		?>
			<div id="divPersoMedicReport">

				<?php
				
				$resultConsult=$connexion->query('SELECT *FROM patients_hosp ph WHERE '.$dailydateperso.' ORDER BY ph.dateSortie ASC');	
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
				//echo $comptConsult;
		
				$i=0;
				
				if($comptConsult != 0)
				{
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A8', 'N°')
								->setCellValue('B8', 'Date de sortie')
								->setCellValue('C8', 'Patient name')
								->setCellValue('D8', 'Age')
								->setCellValue('E8', 'Gender')
								->setCellValue('F8', 'Services')
								->setCellValue('G8', 'Nursing Care')
								->setCellValue('H8', 'Laboratory tests')
								->setCellValue('I8', 'Diagnosis');
					
				?>
					
					<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
					
					<thead>
						<tr>
							<th style="width:2%; border-right: 1px solid #bbb;text-align: center;">N°</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Date de sortie</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Full name</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Assurance</th>
							<th style="text-align:center; width:10%; border-right: 1px solid #bbb;text-align: center;" colspan=2>Age/Sexe</th>
						    <th style="width:20%;text-align: center;"><?php echo getString(279);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medicament';?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align:center;"><?php echo 'consommables';?></th>
							<th style="width:15%;text-align:center;"><?php echo getString(99);?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
					// $date='0000-00-00';
					$compteur=1;
					
						while($ligneConsult=$resultConsult->fetch())
						{
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.id_uM=:med AND mc.id_factureMedConsu!=0 AND mc.id_hospMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_hosp
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);
							$comptMedConsult=$resultMedConsult->rowCount();

							$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.id_uM=:med AND ms.id_hospSurge=:idMedConsu ORDER BY ms.id_medsurge');		
							$resultMedSurge->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_hosp
							));
							
							$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							$comptMedSurge=$resultMedSurge->rowCount();


							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.id_uM=:med AND mi.id_factureMedInf!=0 AND mi.id_hospInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_hosp
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
							$comptMedInf=$resultMedInf->rowCount();

							$resultMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp medoc WHERE medoc.id_uM=:med AND medoc.id_factureMedMedoc!=0 AND medoc.id_hospMedoc=:id_hospMedoc ORDER BY medoc.id_medmedoc');		
							$resultMedoc->execute(array(
							'med'=>$idDoc,					
							'id_hospMedoc'=>$ligneConsult->id_hosp
							));
							
							$resultMedoc->setFetchMode(PDO::FETCH_OBJ);
							$comptMedoc=$resultMedoc->rowCount();


							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp consom WHERE consom.id_uM=:med AND consom.id_factureMedConsom!=0 AND consom.id_hospConsom=:id_hospConsom ORDER BY consom.id_medconsom');		
							$resultMedConsom->execute(array(
							'med'=>$idDoc,					
							'id_hospConsom'=>$ligneConsult->id_hosp
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);
							$comptMedConsom=$resultMedConsom->rowCount();

							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_uM=:med AND ml.id_factureMedLabo!=0 AND ml.id_hospLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_hosp
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);
							$comptMedLabo=$resultMedLabo->rowCount();


							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.id_uM=:med AND mr.id_factureMedRadio!=0 AND mr.id_hospRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
							$resultMedRadio->execute(array(
							'med'=>$idDoc,					
							'idMedRadio'=>$ligneConsult->id_hosp
							));
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);
							$comptMedRadio=$resultMedRadio->rowCount();


							if($comptMedConsult!=0 OR $comptMedSurge !=0 OR $comptMedInf!=0 OR $comptMedoc!=0 OR $comptMedoc!=0 OR $comptMedConsom!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0){
						
							$consult = "";
							$nursery = "";
							$labs = "";						
							$diagno = "";
							
				?>
						<tr>
							<td style="text-align:center;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td style="text-align: center;">
							<?php 
								echo $ligneConsult->dateSortie;
								$dateconsu = $ligneConsult->dateSortie;
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneConsult->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
									
									$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($lignePatient->date_naissance)));
									$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
									$interval = $datetime1->diff($datetime2);
									
									if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
									{
										$agePa = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
									}
										
									$sexePa = $lignePatient->sexe;
									
									echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
								}else{
									$fullnamePa="";
									echo '';
								}
							?>
							</td>
							<td style="text-align: center;">
								<?php echo $ligneConsult->nomassuranceHosp.'<br><b>('.$ligneConsult->insupercent_hosp.')</b>';  ?>
							</td>
							
							<td style="text-align:center;">
							<?php echo $agePa;?>
							</td>
							
							<td style="text-align:center;">
							<?php echo $sexePa; ?>
							</td>

						<td style="text-align: center;">
						<?php
						$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.id_uM=:med AND ms.id_hospSurge=:idMedConsu ORDER BY ms.id_medsurge');		
						$resultMedSurge->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_hosp
						));
						
						$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptMedSurge=$resultMedSurge->rowCount();
					
					
						if($comptMedSurge != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
								<?php
								while($ligneMedSurge=$resultMedSurge->fetch())
								{
								?>
								<tr style="text-align:center;">
									
									<td style="text-align:center;">
									<?php
									
									$idassuSurge=$ligneMedSurge->id_assuSurge;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($i=1;$i<=$assuCount;$i++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuSurge
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedSurge->id_prestationSurge
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>';
											
										}
									}else{
										
										echo $ligneMedSurge->autrePrestaS.'</td>';
									}
									?>
								</tr>
								<?php
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
							
							<td style="text-align:center;">
							
							<?php
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.id_uM=:med AND mc.id_factureMedConsu!=0 AND mc.id_hospMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_hosp
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsult=$resultMedConsult->rowCount();
						
						
							if($comptMedConsult != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								

								<tbody>
									<?php
										
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
									?>
									<tr>
										
										<td style="text-align:center;">
										<?php
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
										
										$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuConsu->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
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
										
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.' <span style="color:red;font-weight:bold;">('.$ligneMedConsult->qteConsu.')</span></td>';
												
												$consult .= ''.$lignePresta->namepresta.', ';
											}else{								
												echo $lignePresta->nompresta.' <span style="color:red;font-weight:bold;">('.$ligneMedConsult->qteConsu.')</span></td>';
												
												$consult .= ''.$lignePresta->nompresta.', ';
												
											}
										}else{
											
											echo $lignePresta->autreConsu.' <span style="color:red;font-weight:bold;">('.$ligneMedConsult->qteConsu.')</span></td>';
											
											$consult .= ''.$ligneMedConsult->autreConsu.', ';
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							?>
							</td>
						
							<td style="text-align:left;">
							
							<?php
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE  mi.id_uM=:med AND mi.id_factureMedInf!=0 AND mi.id_hospInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_hosp
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

							$comptMedInf=$resultMedInf->rowCount();
						
						
							if($comptMedInf != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
												
										$idassuInf=$ligneMedInf->id_assuInf;
										
										$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuInf->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
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
									<tr>
										<td style="text-align:center;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.' <span style="color:red;font-weight:bold;">('.$ligneMedInf->qteInf.')</span></td>';	
												
												$nursery .= ''.$lignePresta->namepresta.', ';
												
											}else{								
												echo $lignePresta->nompresta.' <span style="color:red;font-weight:bold;">('.$ligneMedInf->qteInf.')</span></td>';
												
												$nursery .= ''.$lignePresta->namepresta.', ';
											}
										}else{
											
											echo $lignePresta->autrePrestaM.' <span style="color:red;font-weight:bold;">('.$ligneMedInf->qteInf.')</span></td>';
											
											$nursery .= ''.$ligneMedInf->autrePrestaM.', ';
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							?>
							</td>
								<td style="text-align:left;">
							
							<?php

							$resultMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp medoc WHERE medoc.id_uM=:med AND medoc.id_factureMedMedoc!=0 AND medoc.id_hospMedoc=:id_hospMedoc ORDER BY medoc.id_medmedoc');		
							$resultMedoc->execute(array(
							'med'=>$idDoc,					
							'id_hospMedoc'=>$ligneConsult->id_hosp
							));
							
							$resultMedoc->setFetchMode(PDO::FETCH_OBJ);

							$comptMedoc=$resultMedoc->rowCount();
						
							$TotalMedoc=0;	
							$TotalMedocPatient=0;	
							$TotalMedocInsurance=0;	
											
							if($comptMedoc != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedoc=$resultMedoc->fetch())
									{
												
										$idassumedoc=$ligneMedoc->id_assuMedoc;
										
										$comptAssumedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssumedoc->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssumedoc->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssumedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssumedoc->execute(array(
											'idassu'=>$idassumedoc
											));
											
											$getAssumedoc->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssumedoc=$getAssumedoc->fetch())
											{
												$presta_assumedoc='prestations_'.$ligneNomAssumedoc->nomassurance;
											}
										}
									?>
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedoc.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedoc->id_prestationMedoc
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.' <span style="color:red;font-weight:bold;">('.$ligneMedoc->qteMedoc.')</span></td>';					
												$prestamedoc[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.' <span style="color:red;font-weight:bold;">('.$ligneMedoc->qteMedoc.')</span></td>';			
												$prestamedoc[] = $lignePresta->nompresta;
											}					
																
											$nursery .= ''.$ligneMedoc->prixprestationMedoc.', ';
										}else{
											
											echo $lignePresta->autreMedoc.' <span style="color:red;font-weight:bold;">('.$ligneMedoc->qteMedoc.')</span></td>';	
												$prestamedoc[] = $ligneMedoc->autreMedoc;
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
						
							?>
							</td>								

							<td style="text-align:left;">
							
							<?php

							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp consom WHERE consom.id_uM=:med AND consom.id_factureMedConsom!=0 AND consom.id_hospConsom=:id_hospConsom ORDER BY consom.id_medconsom');		
							$resultMedConsom->execute(array(
							'med'=>$idDoc,					
							'id_hospConsom'=>$ligneConsult->id_hosp
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

							$comptMedconsom=$resultMedConsom->rowCount();
						
							$TotalMedConsom=0;	
							$TotalMedconsomPatient=0;	
							$TotalMedconsomInsurance=0;	
											
							if($comptMedconsom != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedConsom=$resultMedConsom->fetch())
									{
												
										$idassumedconsom=$ligneMedConsom->id_assuConsom;
										
										$comptAssumedconsom=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssumedconsom->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssumedconsom->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssumedconsom=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssumedoc->execute(array(
											'idassu'=>$idassumedconsom
											));
											
											$getAssumedconsom->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssumedconsom=$getAssumedconsom->fetch())
											{
												$presta_assumedconsom='prestations_'.$ligneNomAssumedconsom->nomassurance;
											}
										}
									?>
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedoc.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsom->id_prestationConsom	
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.' <span style="color:red;font-weight:bold;">('.$ligneMedConsom->qteConsom.')</span></td>';					
												//	$prestamedoc[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.' <span style="color:red;font-weight:bold;">('.$ligneMedConsom->qteConsom.')</span></td>';		
												$prestamedoc[] = $lignePresta->nompresta;
											}					
																
											//$nursery .= ''.$ligneMedConsom->prixprestationMedoc.', ';
										}else{
											
											echo $lignePresta->autreConsom.' <span style="color:red;font-weight:bold;">('.$ligneMedConsom->qteConsom.')</span></td>';
												$prestamedoc[] = $ligneMedConsom->autreConsom;
										}
										?>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}
						
							?>
							</td>
						
							<td style="text-align:left;">
							
							<?php
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_uM=:med AND ml.id_factureMedLabo!=0 AND ml.id_hospLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_hosp
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();


							if($comptMedLabo != 0)
							{
							?>	
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
										
										$idassuLab=$ligneMedLabo->id_assuLab;

										$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuLab->rowCount();
										
										for($l=1;$l<=$assuCount;$l++)
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

									?>
									<tr>
										<td style="text-align:center;">
											<?php
											$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta=$resultPresta->rowCount();
											
											if($lignePresta=$resultPresta->fetch())
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta;
													
													$labs .= ''.$lignePresta->namepresta.', ';
												}else{
													
													echo $lignePresta->nompresta;
													
													$labs .= ''.$lignePresta->nompresta.', ';
												}
											}else{
												
												echo $ligneMedLabo->autreExamen;
												
												$labs .= ''.$ligneMedLabo->autreExamen.', ';
											}
											?>
										</td>
									</tr>
									<?php
									}
									?>		
								</tbody>
								</table>
							<?php
							}

							?>
							</td>
						</tr>
				<?php
				
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateconsu;
							$arrayConsult[$i][2]=$fullnamePa;
							$arrayConsult[$i][3]=$agePa;
							$arrayConsult[$i][4]=$sexePa;
							// $arrayConsult[$i][5]='';
							$arrayConsult[$i][5]=$consult;
							$arrayConsult[$i][6]=$nursery;
							$arrayConsult[$i][7]=$labs;

							$i++;
							
						$compteur++;
						}

					}
				?>		
					</tbody>
					</table>
				<?php
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','A10');
							
				}
				?>
			</div>
			<?php
			
				if(isset($_GET['createReportExcel']))
				{
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
					
					if($_GET['docVisit']=='dailyPersoMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Daily/'.$reportsn.'.xlsx');
						
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Daily/");</script>';
						
					}else{
						if($_GET['docVisit']=='monthlyPersoMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Monthly/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Monthly/");</script>';
							
						}else{
							if($_GET['docVisit']=='annualyPersoMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Annualy/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Annualy/");</script>';
								
							}else{
								if($_GET['docVisit']=='customPersoMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Custom/'.$reportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Custom/");</script>';
									
								}else{
									if($_GET['docVisit']=='gnlPersoMedic')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Alltimes/'.$reportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Alltimes/");</script>';
										
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
						createRN('DRD');
						
					}else{
						if($_GET['docVisit']=='monthlyPersoMedic')
						{
							createRN('DRM');

						}else{
							if($_GET['docVisit']=='annualyPersoMedic')
							{
								createRN('DRA');
								
							}else{
								if($_GET['docVisit']=='customPersoMedic')
								{	
									createRN('DRC');
								
								}else{
									if($_GET['docVisit']=='gnlPersoMedic')
									{
										createRN('DRG');
									
									}
								}
							}
						}
					}
					
					echo '<script text="text/javascript">document.location.href="doctor_report.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&hospi='.$_GET['hospi'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoMedicReport=ok&createReportPdf=ok&createRN=0"</script>';
				}			
			}
			
			if(isset($_GET['divPersoBillReport']))
			{
		?>
			<div id="2010">

				<?php
				
				$resultConsult=$connexion->query('SELECT *FROM patients_hosp ph WHERE '.$dailydateperso.' ORDER BY ph.dateSortie ASC');	
	
				// $resultConsult->execute(array(
				// 'med'=>$idDoc
				// ));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
			
				$i=0;
				
				if($comptConsult != 0)
				{
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A8', 'N°')
								->setCellValue('B8', 'Date de sortie')
								->setCellValue('C8', 'Patient name')
								->setCellValue('D8', 'Services')
								->setCellValue('E8', 'Amount')
								->setCellValue('F8', 'Nursing Care')
								->setCellValue('G8', 'Amount')
								->setCellValue('H8', 'Laboratory tests')
								->setCellValue('I8', 'Amount')
								->setCellValue('J8', 'Radiologie')
								->setCellValue('K8', 'Amount')
								->setCellValue('L8', 'Total');
					
				?>
					
					<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
					
					<thead>
						<tr>
							<th style="width:2%; border-right: 1px solid #bbb">N°</th>
							<th style="width:10%; border-right: 1px solid #bbb">Date de Entree</th>
							<th style="width:10%; border-right: 1px solid #bbb">Date de sortie</th>
							<th style="width:10%; border-right: 1px solid #bbb">Nbre de Jours</th>
							<th style="width:10%; border-right: 1px solid #bbb">P/Days</th>
							<th style="width:10%; border-right: 1px solid #bbb">Prix Total</th>
							<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
							<th style="text-align:center; width:10%; border-right: 1px solid #bbb" colspan=2>Age/Sexe</th>
							<th style="width:15%; border-right: 1px solid #bbb">Assurance</th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Medical Acts';?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Medicament';?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'consommables';?></th>
							<th style="width:15%;"><?php echo getString(99);?></th>
							<th style="width:10%;"><?php echo 'Radiologie';?></th>
							<th style="width:10%;">Total Final</th>
							<th style="width:10%;">Total Patients</th>
							<th style="width:10%;">Total Insurance</th>
						</tr> 
					</thead> 


					<tbody>
				<?php
					
					$TotalGnlTypeConsu=0;

					$TotalGnlMedConsu=0;
						
					$TotalGnlMedInf=0;

					$TotalGnlMedoc=0;

					$TotalGnlMedConsom=0;
						
					$TotalGnlMedLabo=0;

					$TotalGnlMedSurge=0;
						
					$TotalGnlMedRadio=0;
						
					$TotalGnlPrice=0;

					$TotalGnlPricePatient=0;

					$TotalGnlPriceInsurance=0;
						
					
					$compteur=1;
					
						while($ligneConsult=$resultConsult->fetch())
						{

							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.id_uM=:med AND mc.id_factureMedConsu!=0 AND mc.id_hospMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_hosp
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);
							$comptMedConsult=$resultMedConsult->rowCount();

							$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.id_uM=:med AND ms.id_hospSurge=:idMedConsu ORDER BY ms.id_medsurge');		
							$resultMedSurge->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_hosp
							));
							
							$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							$comptMedSurge=$resultMedSurge->rowCount();


							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.id_uM=:med AND mi.id_factureMedInf!=0 AND mi.id_hospInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_hosp
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
							$comptMedInf=$resultMedInf->rowCount();

							$resultMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp medoc WHERE medoc.id_uM=:med AND medoc.id_factureMedMedoc!=0 AND medoc.id_hospMedoc=:id_hospMedoc ORDER BY medoc.id_medmedoc');		
							$resultMedoc->execute(array(
							'med'=>$idDoc,					
							'id_hospMedoc'=>$ligneConsult->id_hosp
							));
							
							$resultMedoc->setFetchMode(PDO::FETCH_OBJ);
							$comptMedoc=$resultMedoc->rowCount();


							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp consom WHERE consom.id_uM=:med AND consom.id_factureMedConsom!=0 AND consom.id_hospConsom=:id_hospConsom ORDER BY consom.id_medconsom');		
							$resultMedConsom->execute(array(
							'med'=>$idDoc,					
							'id_hospConsom'=>$ligneConsult->id_hosp
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);
							$comptMedConsom=$resultMedConsom->rowCount();

							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_uM=:med AND ml.id_factureMedLabo!=0 AND ml.id_hospLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_hosp
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);
							$comptMedLabo=$resultMedLabo->rowCount();


							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.id_uM=:med AND mr.id_factureMedRadio!=0 AND mr.id_hospRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
							$resultMedRadio->execute(array(
							'med'=>$idDoc,					
							'idMedRadio'=>$ligneConsult->id_hosp
							));
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);
							$comptMedRadio=$resultMedRadio->rowCount();


							if($comptMedConsult!=0 OR $comptMedSurge !=0 OR $comptMedInf!=0 OR $comptMedoc!=0 OR $comptMedoc!=0 OR $comptMedConsom!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0){
							$presta_assu = 'prestations_'.$ligneConsult->nomassuranceHosp;
							$prestaServ=array();
							$prixprestaServ=array();
							
							$prestaInf=array();
							$prixprestaInf=array();

							$prestamedoc=array();
							$prixprestamedoc=array();
							
							$prestaLabo=array();
							$prixprestaLabo=array();

							$prestaSurge=array();
							$prixprestaSurge=array();
							
							$prestaRadio=array();
							$prixprestaRadio=array();
							
							$TotalDayPrice=0;
							$TotalDayPricePatient=0;
							$TotalDayPriceInsurance=0;
							
							$consult = "";
							$nursery = "";
							$meddoc = "";
							$labs = "";						
							$radx = "";
							
				?>
						<tr>
							<td style="text-align:left;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td>
							<?php 
								echo $ligneConsult->dateEntree;
								$dateconsu = $ligneConsult->dateEntree;
							?>
							</td>							
							<td>
							<?php 
								echo $ligneConsult->dateSortie;
								$dateconsu = $ligneConsult->dateSortie;
							?>
							</td>							

							<td style="text-align:center;">
							<?php
							
							$dateIn=strtotime($ligneConsult->dateEntree);
							$dateOut=strtotime($ligneConsult->dateSortie);
							
							$datediff= abs($dateOut - $dateIn);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
								echo $nbrejrs;
							?>
							</td>

							<?php
							$prixroom=$ligneConsult->prixroom;

							$balance=$prixroom*$nbrejrs;

							$prixconsultpatient=($balance * $ligneConsult->insupercent_hosp)/100;
							$prixconsultinsu= $balance - $prixconsultpatient;
							?>

							<td>
							<?php
								echo $prixroom;
							?>
							</td>
							
							<td style="text-align:center;">
										
								<?php
								$roomBalance = $balance;

							echo $roomBalance;

							$insupercent_hosp = $ligneConsult->insupercent_hosp;

															
							$TotalTypeConsu=0;
							$TotalTypeConsuPatient=0;
							$TotalTypeConsuInsu=0;
							


							$TotalTypeConsu=$TotalTypeConsu+$balance;
							$TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
							$TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
					
		
							$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalTypeConsuInsu;
							?>
							
							</td>
							
						<td style="text-align:left;font-weight:bold;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
								
								$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($lignePatient->date_naissance)));
								$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
								$interval = $datetime1->diff($datetime2);
								
								if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
								{
									$agePa = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
								}
									
								$sexePa = $lignePatient->sexe;
								
								echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
							}else{
								$fullnamePa="";
								echo '';
							}
						?>
						</td>
						
							<td style="text-align:left;">
							<?php echo $agePa;?>
							</td>
							
							<td style="text-align:left;">
							<?php echo $sexePa; ?>
							</td>
							<td style="text-align: center;">
								<?php echo $ligneConsult->nomassuranceHosp.'<br><b>('.$ligneConsult->insupercent_hosp.')</b>';  ?>
							</td>

							<td style="text-align:left;">
							
							<?php
						
							$TotalMedConsu=0;
							$TotalMedConsuPatient=0;
							$TotalMedConsuInsurance=0;
											
							if($comptMedConsult != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
										
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuServ->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
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
									<tr>
										
										<td style="text-align:left;">
										<?php	
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaServ[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';
												$prestaServ[] = $lignePresta->nompresta;
											}
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedConsult->qteConsu.'Qty</span></td></tr>';
											$prixconsu=$ligneMedConsult->prixprestationConsu * $ligneMedConsult->qteConsu;											
											$consult .= ''.$ligneMedConsult->prixprestationConsu * $ligneMedConsult->qteConsu.', ';
										}else{
											
											echo $ligneMedConsult->autreConsu.'</td>';
											$prestaServ[] = $ligneMedConsult->autreConsu * $ligneMedConsult->qteConsu;
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedConsult->qteConsu.'Qty</span></td></tr>';
											
											$prixconsu=$ligneMedConsult->prixautreConsu * $ligneMedConsult->qteConsu;
											$consult .= ''.$ligneMedConsult->prixautreConsu * $ligneMedConsult->qteConsu.', ';
										}
										?>
									</tr>
									<?php
									
										$prixprestaServ[] = $prixconsu;
										$insupeService = $ligneMedConsult->insupercentServ;
										
										$TotalMedConsu=$TotalMedConsu+$prixconsu;

										$TotalMedConsuPatient = $TotalMedConsuPatient + (($prixconsu*$insupeService) / 100);
										$TotalMedConsuInsurance = $TotalMedConsuInsurance + ($TotalMedConsu - $TotalMedConsuPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedConsuInsurance;
							if ($TotalMedConsu!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:50px;">Total = '.$TotalMedConsu.'</b>';
							}
							?>
							</td> 
							<!-- <td> -->
								<td style="text-align: center;">
						<?php
					
						$TotalMedSurge = 0;
						$TotalMedSurgePatient = 0;
						$TotalMedSurgeInsurance = 0;

						if($comptMedSurge != 0)
						{
						?>
							<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
							

							<tbody>
								<?php
								while($ligneMedSurge=$resultMedSurge->fetch())
								{
								?>
								<tr style="text-align:center;">
									
									<td style="text-align:center;">
									<?php
									
									$idassuSurge=$ligneMedSurge->id_assuSurge;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($i=1;$i<=$assuCount;$i++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuSurge
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedSurge->id_prestationSurge
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';

											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedSurge->prixprestationSurge.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedSurge->qteSurge.'Qty</span></td></tr>';
											
											$prixSurge=$ligneMedSurge->prixprestationSurge * $ligneMedSurge->qteSurge;
											// $labs .= ''.$lignePresta->prixprestationSurge.', ';
										}else{								
											echo $lignePresta->nompresta.'</td>';

											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedSurge->prixautrePrestaS.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedSurge->qteSurge.'Qty</span></td></tr>';
											$prixSurge=$ligneMedSurge->prixautrePrestaS * $ligneMedSurge->qteSurge;
											//$labs .= ''.$ligneMedSurge->prixautreExamen.', ';
											
										}
									}else{
										
										echo $ligneMedSurge->autrePrestaS.'</td>';
									}
									?>
								</tr>
								<?php
									$prixprestaSurge[] = $prixSurge;
										
									$TotalMedSurge=$TotalMedSurge+$prixSurge;

									$insupeSurge = $ligneMedSurge->insupercentSurge;
										
									$TotalMedSurgePatient = $TotalMedSurgePatient +(($prixSurge*$insupeSurge) / 100);
									$TotalMedSurgeInsurance =$TotalMedSurgeInsurance +($TotalMedSurge - $TotalMedSurgePatient);
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						$TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
						$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;
						$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedSurgeInsurance;
						if ($TotalMedSurge!=0) {
							echo "<hr/>";
							echo '<b style="">Total = '.$TotalMedSurge.'</b>';
						}
						
						?>
						</td>
							<!-- </td> -->
							

						
							<td style="text-align:left;">
							
							<?php
						
							$TotalMedInf=0;	
							$TotalMedInfPatient=0;	
							$TotalMedInfInsurance=0;	
											
							if($comptMedInf != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
												
										$idassuInf=$ligneMedInf->id_assuInf;
										
										$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuInf->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
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
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';					
												$prestaInf[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';		
												$prestaInf[] = $lignePresta->nompresta;
											}
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedInf->qteInf.'Qty</span></td></tr>';
											$prixinf=$ligneMedInf->prixprestation * $ligneMedInf->qteInf;						
																
											$nursery .= ''.$ligneMedInf->prixprestation * $ligneMedInf->qteInf.', ';
										}else{
											
											echo $ligneMedInf->autrePrestaM.'</td>';
												$prestaInf[] = $ligneMedInf->autrePrestaM;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
											$prixinf=$ligneMedInf->prixautrePrestaM;						
																
											$nursery .= ''.$ligneMedInf->prixautrePrestaM * $ligneMedInf->qteInf.', ';
										}
										?>
									</tr>
									<?php
										$prixprestaInf[] = $prixinf;
										
										$TotalMedInf=$TotalMedInf+$prixinf;

										$insupercent_hospInf = $ligneMedInf->insupercentInf;
										
										$TotalMedInfPatient = $TotalMedInfPatient + (($prixinf*$insupercent_hospInf) / 100);
										$TotalMedInfInsurance = $TotalMedInfInsurance + ($TotalMedInf - $TotalMedInfPatient);

									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedInfPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedInfInsurance;
							if ($TotalMedInf!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:50px;">Total = '.$TotalMedInf.'</b>';
							}
							?>
							</td>
							<td style="text-align:left;">
							
							<?php
						
							$TotalMedoc=0;	
							$TotalMedocPatient=0;	
							$TotalMedocInsurance=0;	
											
							if($comptMedoc != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedoc=$resultMedoc->fetch())
									{
												
										$idassumedoc=$ligneMedoc->id_assuMedoc;
										
										$comptAssumedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssumedoc->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssumedoc->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssumedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssumedoc->execute(array(
											'idassu'=>$idassumedoc
											));
											
											$getAssumedoc->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssumedoc=$getAssumedoc->fetch())
											{
												$presta_assumedoc='prestations_'.$ligneNomAssumedoc->nomassurance;
											}
										}
									?>
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedoc.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedoc->id_prestationMedoc
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';					
												$prestamedoc[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';		
												$prestamedoc[] = $lignePresta->nompresta;
											}
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedoc->prixprestationMedoc.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedoc->qteMedoc.'Qty</span></td></tr>';
											$prixmedoc=$ligneMedoc->prixprestationMedoc * $ligneMedoc->qteMedoc;						
																
											$nursery .= ''.$ligneMedoc->prixprestationMedoc * $ligneMedoc->qteMedoc.', ';
										}else{
											
											echo $ligneMedoc->autreMedoc.'</td>';
												$prestamedoc[] = $ligneMedoc->autreMedoc;
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedoc->prixautreMedoc.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedoc->qteMedoc.'Qty</span></td></tr>';
											$prixmedoc=$ligneMedoc->prixautreMedoc * $ligneMedoc->qteMedoc;						
																
											$meddoc .= ''.$ligneMedoc->prixautreMedoc * $ligneMedoc->qteMedoc.', ';
										}
										?>
									</tr>
									<?php
										$prixprestamedoc[] = $prixmedoc;
										
										$TotalMedoc=$TotalMedoc+$prixmedoc;

										$insupercent_hospMedoc = $ligneMedoc->insupercentMedoc;
										
										$TotalMedocPatient = $TotalMedocPatient + (($prixmedoc*$insupercent_hospMedoc) / 100);
										$TotalMedocInsurance = $TotalMedocInsurance + ($TotalMedoc - $TotalMedocPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedoc;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedocPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedocInsurance;
							if ($TotalMedoc!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:50px;">Total = '.$TotalMedoc.'</b>';
							}
							?>
							</td>							

							
							<td style="text-align:left;">
							
							<?php
						
							$TotalMedConsom=0;	
							$TotalConsomPatient=0;	
							$TotalConsomInsurance=0;	
											
							if($comptMedConsom!=0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneConsom=$resultMedConsom->fetch())
									{
												
										$idassuconsom=$ligneConsom->id_assuConsom;
										
										$comptAssuconsom=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuconsom->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuconsom->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
										{
											
											$getAssuconsom=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
											$getAssuconsom->execute(array(
											'idassu'=>$idassuconsom
											));
											
											$getAssuconsom->setFetchMode(PDO::FETCH_OBJ);

											if($ligneNomAssuconsom=$getAssuconsom->fetch())
											{
												$presta_assuconsom='prestations_'.$ligneNomAssuconsom->nomassurance;
											}
										}
									?>
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuconsom.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneConsom->id_prestationConsom
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';					
												$prestaconsom[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';		
												$prestaconsom[] = $lignePresta->nompresta;
											}
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneConsom->prixprestationConsom.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneConsom->qteConsom.'Qty</span></td></tr>';
											$prixconsom=$ligneConsom->prixprestationConsom * $ligneConsom->qteConsom;						
																
											$nursery .= ''.$ligneConsom->prixprestationConsom * $ligneConsom->qteConsom.', ';
										}else{
											
											echo $ligneConsom->autreConsom.'</td>';
												$prestaconsom[] = $ligneConsom->autreConsom;
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneConsom->prixautreConsom.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneConsom->qteConsom.'Qty</span></td></tr>';
											$prixconsom=$ligneConsom->prixautreConsom * $ligneConsom->qteConsom;						
																
											$meddoc .= ''.$ligneConsom->prixautreConsom * $ligneConsom->qteConsom.', ';
										}
										?>
									</tr>
									<?php
										$prixprestaconsom[] = $prixconsom;
										
										$TotalMedConsom=$TotalMedConsom+$prixconsom;

										$insupercent_hospConsom = $ligneConsom->insupercentConsom;
										
										$TotalConsomPatient = $TotalConsomPatient + (($prixconsom*$insupercent_hospConsom) / 100);
										$TotalConsomInsurance = $TotalConsomInsurance + ($TotalMedConsom - $TotalConsomPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalConsomPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalConsomInsurance;
							if ($TotalMedConsom!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:50px;">Total = '.$TotalMedConsom.'</b>';
							}
							?>
							</td>	
						
							<td style="text-align:left;">
							
							<?php

							$TotalMedLabo=0;
							$TotalMedLaboPatient=0;
							$TotalMedLaboInsurance=0;
							
							if($comptMedLabo != 0)
							{
							?>	
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
									
										$idassuLab=$ligneMedLabo->id_assuLab;

										$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuLab->rowCount();
										
										for($l=1;$l<=$assuCount;$l++)
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

									?>
									<tr>
										<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta;
												$prestaLabo[] = $lignePresta->namepresta;
												
											}else{
												
												echo $lignePresta->nompresta;
												$prestaLabo[] = $lignePresta->nompresta;
											}
																
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedLabo->prixprestationExa.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedLabo->qteLab.'Qty</span></td></tr>';
											$prixlabo=$ligneMedLabo->prixprestationExa * $ligneMedLabo->qteLab;
											$labs .= ''.$ligneMedLabo->prixprestationExa * $ligneMedLabo->qteLab.', ';
										}else{
											
											echo $ligneMedLabo->autreExamen;
											$prestaLabo[] = $ligneMedLabo->autreExamen;
												
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedLabo->prixautreExamen.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedLabo->qteLab.'Qty</span></td></tr>';
											$prixlabo=$ligneMedLabo->prixautreExamen * $ligneMedLabo->qteLab;
											$labs .= ''.$ligneMedLabo->prixautreExamen * $ligneMedLabo->qteLab.', ';
										}
										?>
										</td>
									</tr>
									<?php
										$prixprestaLabo[] = $prixlabo;
										
										$TotalMedLabo=$TotalMedLabo+$prixlabo;

										$insupercent_hospLab = $ligneMedLabo->insupercentLab;
										
										$TotalMedLaboPatient = $TotalMedLaboPatient + (($prixlabo*$insupercent_hospLab) / 100);
										$TotalMedLaboInsurance = $TotalMedLaboInsurance + ($TotalMedLabo - $TotalMedLaboPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedLaboPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedLaboInsurance;
							if ($TotalMedLabo!=0) {
								echo "<hr/>";
								echo '<b style="margin-left:40px;">Total = '.$TotalMedLabo.'</b>';
							}
							?>
							</td>

							<td style="text-align:left;font-weight:normal;">					
							<?php
							
							$TotalMedRadio=0;
							$TotalMedRadioPatient=0;
							$TotalMedRadioInsurance=0;
							
							if($comptMedRadio!=0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedRadio=$resultMedRadio->fetch())
									{
										
										$idassuRad=$ligneMedRadio->id_assuRad;

										$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuRad->rowCount();
										
										for($r=1;$r<=$assuCount;$r++)
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
									<tr>
										<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedRadio->id_prestationRadio
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaRadio[] = $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta.'</td>';
												$prestaRadio[] = $lignePresta->nompresta;
											}
											
											echo '<tr><td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td><br>';
											echo '<td style="border-left:1px solid #eee;"><span style="color:red;font-weight:bold;">'.$ligneMedRadio->qteRad.'Qty</span></td></tr>';
											$prixradio=$ligneMedRadio->prixprestationRadio * $ligneMedRadio->qteRad;	
											$radx .= ''.$ligneMedRadio->prixprestationRadio * $ligneMedRadio->qteRad.', ';
										}else{
											
											echo $ligneMedRadio->autreRadio.'</td>';
											$prestaRadio[] = $ligneMedRadio->autreRadio;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
											$prixradio=$ligneMedRadio->prixautreRadio;
											$radx .= ''.$ligneMedRadio->prixautreRadio.', ';
										}									
										?>
										
									</tr>
									<?php
										$prixprestaRadio[] = $prixradio;
										
										$TotalMedRadio=$TotalMedRadio+$prixradio;

										$insupercent_hospRad = $ligneMedRadio->insupercentRad;
										
										$TotalMedRadioPatient = $TotalMedRadioPatient + (($prixradio*$insupercent_hospRad) / 100);
										$TotalMedRadioInsurance = $TotalMedRadioInsurance + ($TotalMedRadio - $TotalMedRadioPatient);
									}
									?>		
								</tbody>
								</table>
							<?php
							}							
							$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;							
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedRadioPatient;							
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedRadioInsurance;							
							?>	
								
							<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
							<!-- <?php
								$insupercent_hosp = $ligneConsult->insupercent_hosp;
								$idConsu = $ligneConsult->id_hosp;
								echo "insupercent_hosp = ".$idConsu;
							?> -->
							<td style="text-align:center;">
								<?php
									echo $TotalDayPricePatient;
								?>
							</td>
							<td style="text-align:center;">
								<?php
									$AssuranceDayPrice = $TotalDayPrice - $TotalDayPricePatient;
									echo $AssuranceDayPrice;
								?>
							</td>
							
						</tr>
						<?php
					
						$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalMedSurge;

						$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
							
						$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;

						$TotalGnlMedoc=$TotalGnlMedoc + $TotalMedoc;

						$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
						
						$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;

						$TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
							
						$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
						
						$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
							
						$TotalGnlPricePatient=$TotalGnlPricePatient + $TotalDayPricePatient;	
							
						$TotalGnlPriceInsurance=$TotalGnlPriceInsurance + $AssuranceDayPrice;	
							
						
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateconsu;
							$arrayConsult[$i][2]=$fullnamePa;
							/*$arrayConsult[$i][3]=$nameprestaConsult;
							$arrayConsult[$i][4]=$prixconsult;*/
							
						
							$highNumber = max(sizeof($prixprestaServ),sizeof($prixprestaInf),sizeof($prixprestaLabo),sizeof($prixprestaRadio));
							
							if($highNumber==0)
							{
								$i++;
							}
						// echo '- Services : '.sizeof($prixprestaServ).'<br/>- Inf : '.sizeof($prixprestaInf).'<br/>- Labo : '.sizeof($prixprestaLabo).'<br/>- Radio : '.sizeof($prixprestaRadio).'<br/>';
							
							for($xLigne=0;$xLigne<$highNumber;$xLigne++)
							{
								if($xLigne>0)
								{
									for($e=0;$e<5;$e++)
									{
										$arrayConsult[$i][$e]='';			
									}
								}
								
								if($xLigne < sizeof($prixprestaServ))
								{						
									$arrayConsult[$i][3]=$prestaServ[$xLigne];		
									$arrayConsult[$i][4]=$prixprestaServ[$xLigne];		
								}else{
									$arrayConsult[$i][3]='';
									$arrayConsult[$i][4]='';
								}
								
								if($xLigne < sizeof($prixprestaInf))
								{						
									$arrayConsult[$i][5]=$prestaInf[$xLigne];
									$arrayConsult[$i][6]=$prixprestaInf[$xLigne];
								}else{
									$arrayConsult[$i][5]='';
									$arrayConsult[$i][6]='';
								}
								
								if($xLigne < sizeof($prixprestaLabo))
								{						
									$arrayConsult[$i][7]=$prestaLabo[$xLigne];		
									$arrayConsult[$i][8]=$prixprestaLabo[$xLigne];		
								}else{
									$arrayConsult[$i][7]='';
									$arrayConsult[$i][8]='';
								}
								
								if($xLigne < sizeof($prixprestaRadio))
								{						
									$arrayConsult[$i][9]=$prestaRadio[$xLigne];		
									$arrayConsult[$i][10]=$prixprestaRadio[$xLigne];		
								}else{
									$arrayConsult[$i][9]='';
									$arrayConsult[$i][10]='';
								}
								
								if($xLigne==0)
								{
									$arrayConsult[$i][11]=$TotalDayPrice;
								}
									
								$i++;
							}
							$compteur++;
						}					
						}
						
						?>	

						<tr style="text-align:center;">
							<td colspan=9></td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedConsu;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedSurge;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedInf;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedoc;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedConsom;				
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
									echo $TotalGnlPriceInsurance;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>					
					</tbody>
					</table>
				<?php
					
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','A10')

								->setCellValue('E'.(11+$i).'', ''.$TotalGnlMedConsu.'')
								->setCellValue('G'.(11+$i).'', ''.$TotalGnlMedInf.'')
								->setCellValue('I'.(11+$i).'', ''.$TotalGnlMedLabo.'')
								->setCellValue('K'.(11+$i).'', ''.$TotalGnlMedRadio.'')
								->setCellValue('M'.(11+$i).'', ''.$TotalGnlPrice.'');

				}
				?>
			</div>
			<?php
			
				if(isset($_GET['createReportExcel']))
				{
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
					
					if($_GET['docVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Daily/'.$reportsn.'.xlsx');
						
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Daily/");</script>';
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Monthly/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Monthly/");</script>';
							
						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Annualy/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Annualy/");</script>';
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Custom/'.$reportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Custom/");</script>';
									
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Alltimes/'.$reportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
				}
				
				if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
				{				
					if($_GET['docVisit']=='dailyPersoBill')
					{
						createRN('DRD');
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							createRN('DRM');

						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								createRN('DRA');
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{	
									createRN('DRC');
								
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										createRN('DRG');
									
									}
								}
							}
						}
					}
					
					echo '<script text="text/javascript">document.location.href="doctor_report.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&hospi='.$_GET['hospi'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
				}
			}
		}
		
	}

	
	if(isset($_GET['gnlmed']))
	{
		$dailydateperso=$_GET['dailydateperso'];
		$docVisit=$_GET['docVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for all Doctors')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Report #')
					->setCellValue('B1', ''.$sn.'')
					->setCellValue('A2', 'Done by')
					->setCellValue('B2', ''.$doneby.'')
					->setCellValue('A3', 'Date')
					->setCellValue('B3', ''.$annee.'');
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> General Doctor Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="doctor_report.php?gnlmed=<?php echo $_GET['gnlmed'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<?php
				if(isset($_SESSION['codeC']))
				{
				?>
				<td style="text-align:left">
					
					<form method="post" action="doctor_report.php?gnlmed=<?php echo $_GET['gnlmed'];?>&dailydateperso=<?php echo $dailydateperso;?>&coordi=<?php echo $_SESSION['id'];?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';} if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<?php
				}
				?>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="report.php?gnlmed=<?php echo $_GET['gnlmed'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
			
	<?php
	
		if(isset($_GET['divPersoMedicReport']))
		{
	?>
		<div id="divPersoMedicReport">

			<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
			
			<thead>
				<tr>
					<th style="width:2%; border-right: 1px solid #bbb">N°</th>
					<th style="width:10%; border-right: 1px solid #bbb">Doctor</th>
					<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
					<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
					<th style="text-align:center; width:10%; border-right: 1px solid #bbb" colspan=2>Age/Sexe</th>
					<th style="width:15%; border-right: 1px solid #bbb"><?php echo getString(113);?></th>
					<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
					<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(279);?></th>
					<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
					<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Medicament';?></th>
					<th style="width:15%;"><?php echo getString(99);?></th>
					<th style="width:10%;"><?php echo 'Diagnosis';?></th>
				</tr> 
			</thead> 


			<?php
			
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A8', 'N°')
						->setCellValue('B8', 'Doctors name')
						->setCellValue('C8', 'Date of consultation')
						->setCellValue('D8', 'Patients name')
						->setCellValue('E8', 'Age')
						->setCellValue('F8', 'Gender')
						->setCellValue('G8', 'Type of consultation')
						->setCellValue('H8', 'Services')
						->setCellValue('I8', 'Nursing Care')
						->setCellValue('J8', 'Laboratory tests')
						->setCellValue('K8', 'Diagnosis');
			
			$resultConsult=$connexion->query('SELECT *FROM consultations c WHERE c.id_factureConsult IS NOT NULL '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
			/* $resultConsult->execute(array(
			'med'=>$idDoc 
			)); */
			//echo 'SELECT *FROM consultations c WHERE c.id_factureConsult IS NOT NULL '.$dailydateperso.' ORDER BY c.dateconsu ASC';
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
			?>
				<tbody>
				<?php
				// $date='0000-00-00';
				$compteur=1;
				$nameDocCheck="";
				
					while($ligneConsult=$resultConsult->fetch())
					{
						$idDoc=$ligneConsult->id_uM;
						
						$consult = "";
						$nursery = "";
						$labs = "";						
						$diagno = "";
						
				?>
					
					<tr>
						
						<td style="text-align:left;">
						<?php
							echo $compteur;
						?>
						</td>
						<td style="text-align:center;background:#eee;">
						<?php
						
						$resultIdMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idmed ORDER BY u.full_name ASC');
						$resultIdMed->execute(array(
						'idmed'=>$ligneConsult->id_uM
						));
			
						$resultIdMed->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneIdMed=$resultIdMed->fetch())
						{	
							$nameDoc=$ligneIdMed->full_name;
						
							/* if($nameDocCheck!=$nameDoc)
							{ */
								echo $ligneIdMed->full_name;
							/* }else{
								$nameDoc="";
							} */
						}/* else{
								$nameDoc="";
							} */							
						?>
						</td>
						
						<td>
						<?php 
							echo $ligneConsult->dateconsu;
							$dateconsu = $ligneConsult->dateconsu;
						?>
						</td>
						
						<td style="text-align:left;font-weight:bold;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
								
			$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($lignePatient->date_naissance)));
			$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
			$interval = $datetime1->diff($datetime2);
			
			if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
			{
				$agePa = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
			}
									
								$sexePa = $lignePatient->sexe;
								
								echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
							}else{
								$fullnamePa="";
								echo '';
							}
						?>
						</td>
						
						<td style="text-align:left;">
						<?php echo $agePa;?>
						</td>
						
						<td style="text-align:left;">
						<?php echo $sexePa; ?>
						</td>
						
						<td style="text-align:left;">
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							
							if($lignePresta->namepresta != '')
							{
								$nameprestaConsult = $lignePresta->namepresta;
								echo $nameprestaConsult.'</td>';
							}else{	
							
								if($lignePresta->nompresta != '')
								{
									$nameprestaConsult = $lignePresta->nompresta;
									echo $nameprestaConsult.'</td>';
								}

							}
						}
						?>
						
						
						<td style="text-align:left;">
						
						<?php
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_factureMedConsu!=0 AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsult=$resultMedConsult->rowCount();
					
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="printPreview" cellspacing="0"> 
							

							<tbody>
								<?php
									
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
								?>
								<tr>
									
									<td style="text-align:left;">
									<?php
									
									$idassuServ=$ligneMedConsult->id_assuServ;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($s=1;$s<=$assuCount;$s++)
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
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsult->id_prestationConsu
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
									
									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
											
											$consult .= ''.$lignePresta->namepresta.', ';
										}else{								
											echo $lignePresta->nompresta.'</td>';
											
											$consult .= ''.$lignePresta->nompresta.', ';
											
										}
									}else{
										
										echo $ligneMedConsult->autreConsu.'</td>';
										
										$consult .= ''.$ligneMedConsult->autreConsu.', ';
									}
									?>
								</tr>
								<?php
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
							<td style="text-align: center;">
					<?php
					$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_uM=:med AND ms.id_factureMedSurge!=0 AND ms.id_consuSurge=:idMedSurge ORDER BY ms.id_medsurge');		
					$resultMedSurge->execute(array(
					'med'=>$idDoc,
					'idMedSurge'=>$ligneConsult->id_consu
					));
					
					$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					 $comptMedSurge=$resultMedSurge->rowCount();
					  //echo $idDoc;

					if($comptMedSurge != 0)
					{
					?>
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
						

						<tbody>
							<?php
							while($ligneMedSurge=$resultMedSurge->fetch())
							{
							?>
							<tr style="text-align:center;">
								
								<td style="text-align:center;">
								<?php
								
								$idassuSurge=$ligneMedSurge->id_assuSurge;
								
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuSurge
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
									}
								}
								
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneMedSurge->id_prestationSurge
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										echo $lignePresta->namepresta.'</td>';
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedSurge->prixprestationSurge.'</td>';
										
										$prixSurge=$ligneMedSurge->prixprestationSurge;
										// $labs .= ''.$lignePresta->prixprestationSurge.', ';
									}else{								
										echo $lignePresta->nompresta.'</td>';

										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedSurge->prixautrePrestaS.'</td>';
										$prixSurge=$ligneMedSurge->prixautrePrestaS;
										//$labs .= ''.$ligneMedSurge->prixautreExamen.', ';
										
									}
								}else{
									
									echo $ligneMedSurge->autrePrestaS.'</td>';
								}
								?>
							</tr>
							<?php
							}
							?>		
						</tbody>
						</table>
					<?php
					}
					?>
					</td>
					
						<td style="text-align:left;">
						
						<?php
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_uM=:med AND mi.id_factureMedInf!=0 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'med'=>$idDoc,					
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

						$comptMedInf=$resultMedInf->rowCount();
					
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="printPreview" cellspacing="0"> 
						
							<tbody>
								<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
											
									$idassuInf=$ligneMedInf->id_assuInf;
									
									$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuInf->rowCount();
									
									for($n=1;$n<=$assuCount;$n++)
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
								<tr>
									<td style="text-align:left;">
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
										'prestaId'=>$ligneMedInf->id_prestation
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
											
											$nursery .= ''.$lignePresta->namepresta.', ';
											
										}else{								
											echo $lignePresta->nompresta.'</td>';
											
											$nursery .= ''.$lignePresta->namepresta.', ';
										}
									}else{
										
										echo $ligneMedInf->autrePrestaM.'</td>';
										
										$nursery .= ''.$ligneMedInf->autrePrestaM.', ';
									}
									?>
								</tr>
								<?php
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>

								<td style="text-align:left;">
						
						<?php

						$resultMedoc=$connexion->prepare('SELECT *FROM med_medoc medoc WHERE medoc.id_uM=:med AND medoc.id_factureMedMedoc!=0 AND medoc.id_consuMedoc=:id_consuMedoc ORDER BY medoc.id_medmedoc');		
						$resultMedoc->execute(array(				
						'med'=>$idDoc,
						'id_consuMedoc'=>$ligneConsult->id_consu
						));
						
						$resultMedoc->setFetchMode(PDO::FETCH_OBJ);

						$comptMedoc=$resultMedoc->rowCount();
					
						$TotalMedoc=0;	
						$TotalMedocPatient=0;	
						$TotalMedocInsurance=0;	
										
						if($comptMedoc != 0)
						{
						?>		
							<table class="printPreview" cellspacing="0"> 
						
							<tbody>
								<?php
								while($ligneMedoc=$resultMedoc->fetch())
								{
											
									$idassumedoc=$ligneMedoc->id_assuMedoc;
									
									$comptAssumedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssumedoc->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssumedoc->rowCount();
									
									for($n=1;$n<=$assuCount;$n++)
									{
										
										$getAssumedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssumedoc->execute(array(
										'idassu'=>$idassumedoc
										));
										
										$getAssumedoc->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssumedoc=$getAssumedoc->fetch())
										{
											$presta_assumedoc='prestations_'.$ligneNomAssumedoc->nomassurance;
										}
									}
								?>
								<tr>
									<td style="text-align:left;">
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedoc.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedoc->id_prestationMedoc
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';					
											$prestamedoc[] = $lignePresta->namepresta;
										}else{								
											echo $lignePresta->nompresta.'</td>';		
											$prestamedoc[] = $lignePresta->nompresta;
										}
									}else{
										
										echo $ligneMedoc->autreMedoc.'</td>';
											$prestamedoc[] = $ligneMedoc->autreMedoc;
									}
									?>
								</tr>
								<?php
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>
					
						<td style="text-align:left;">
						
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_factureMedLabo!=0 AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'med'=>$idDoc,					
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();


						if($comptMedLabo != 0)
						{
						?>	
							<table class="printPreview" cellspacing="0"> 
							
							<tbody>
								<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
									
									$idassuLab=$ligneMedLabo->id_assuLab;

									$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuLab->rowCount();
									
									for($l=1;$l<=$assuCount;$l++)
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

								?>
								<tr>
									<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta;
												
												$labs .= ''.$lignePresta->namepresta.', ';
											}else{
												
												echo $lignePresta->nompresta;
												
												$labs .= ''.$lignePresta->nompresta.', ';
											}
										}else{
											
											echo $ligneMedLabo->autreExamen;
											
											$labs .= ''.$ligneMedLabo->autreExamen.', ';
										}
										?>
									</td>
								</tr>
								<?php
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						?>
						</td>

						<td style="text-align:left;font-weight:bold;">
						<?php
									
						$Postdia = array();
						$DiagnoPostDone=0;
																	
						$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu AND c.id_factureConsult IS NOT NULL');
						
						$resuPostdiagnostic->execute(array(
						'idConsu'=>$ligneConsult->id_consu
						));
							
						$resuPostdiagnostic->setFetchMode(PDO::FETCH_OBJ);
							
						while($lignePostdiagnostic=$resuPostdiagnostic->fetch()){
							$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
							
							$resultatsDiagnoPost->execute(array(
							'iddiagno'=>$lignePostdiagnostic->postdiagnostic
							));
								
							$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
							
							if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
							{
								$DiagnoPostDone=1;
							}else{
							
								if($lignePostdiagnostic->postdiagnostic != "")
								{
									$DiagnoPostDone=1;
								}
							}

							
							$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
							
							$resultatsPostDiagno->execute(array(
							'id_consudia'=>$lignePostdiagnostic->id_consu
							
							));
								
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
										$DiagnoPostDone=1;
									}else{
										if($linePostDiagno->autrepostdia !="")
										{
											$DiagnoPostDone=1;
										}
									}
									
								}
							
							}
						}


			$Predia = array();
							
			$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
			
			$resuPrediagnostic->execute(array(
			'idConsu'=>$ligneConsult->id_consu
			));
				
			$resuPrediagnostic->setFetchMode(PDO::FETCH_OBJ);
				
			while($lignePrediagnostic=$resuPrediagnostic->fetch()){
			
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$lignePrediagnostic->prediagnostic
				));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Predia[] = $ligneDiagnoPost->nomdiagno;
				}else{
				
					if($lignePrediagnostic->prediagnostic != "")
					{
						$Predia[] = $lignePrediagnostic->prediagnostic;
					}
				}

					
				$resultatsPrediagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
				
				$resultatsPrediagno->execute(array(
				'id_consudia'=>$lignePrediagnostic->id_consu
				
				));
					
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
						}else{
							$Predia[] = $linePrediagno->autrepredia;
						}
						
					}
				
				}
			}
			// $Postdia = array();
						
			if(isset ($_GET['idconsu']) AND $ligneConsultation->postdiagnostic !="")
			{
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$ligneConsultation->postdiagnostic
				));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Postdia[] = $ligneDiagnoPost->nomdiagno;
				}else{
					$Postdia[] = $ligneConsultation->postdiagnostic;
				}
				
			}
				
			$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
			
			$resultatsPostDiagno->execute(array(
			'id_consudia'=>$ligneConsult->id_consu
			
			));
				
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
					}else{
						$Postdia[] = $linePostDiagno->autrepostdia;
					}
					
				}
			
			}
						
						
						if($DiagnoPostDone ==0)
						{	
							for($p=0;$p<sizeof($Predia);$p++)
							{
								echo '-'.$Predia[$p].'<br/>';
							}
						}else{
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								echo '- '.$Postdia[$p].'<br/>';
							}
						}
						
						// echo $DiagnoPostDone.'<br/>';
						?>
						</td>
						
					</tr>
			<?php
			
						$arrayConsult[$i][0]=$compteur;
						$arrayConsult[$i][1]=$nameDoc;
						$arrayConsult[$i][2]=$dateconsu;
						$arrayConsult[$i][3]=$fullnamePa;
						$arrayConsult[$i][4]=$agePa;
						$arrayConsult[$i][5]=$sexePa;
						$arrayConsult[$i][6]=$nameprestaConsult;
						$arrayConsult[$i][7]=$consult;
						$arrayConsult[$i][8]=$nursery;
						$arrayConsult[$i][9]=$labs;
						
						
						if($DiagnoPostDone ==0)
						{
							$diagnoPre ='';
							
							for($p=0;$p<sizeof($Predia);$p++)
							{
								$diagnoPre .= $Predia[$p].',';
							}
							$arrayConsult[$i][10]=$diagnoPre;
						}else{
						
							$diagnoPost ='';
							
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								$diagnoPost .= $Postdia[$p].',';
							}
							$arrayConsult[$i][10]=$diagnoPost;
						}
						
						$i++;
						
						$nameDocCheck=$nameDoc;	
						$compteur++;
						
					}
			?>		
				</tbody>
			<?php
			
			}
				$objPHPExcel->setActiveSheetIndex(0)
							->fromArray($arrayConsult,'','A10');
			
			?>
		
			</table>
		</div>
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn= str_replace('/', '_', $sn);
				
				if($_GET['docVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Daily/");</script>';
					
				}else{
					if($_GET['docVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Monthly/");</script>';
						
					}else{
						if($_GET['docVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Annualy/");</script>';
							
						}else{
							if($_GET['docVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Custom/");</script>';
								
							}else{
								if($_GET['docVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Alltimes/");</script>';
									
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
					createRN('GDRD');
					
				}else{
					if($_GET['docVisit']=='monthlyPersoMedic')
					{
						createRN('GDRM');

					}else{
						if($_GET['docVisit']=='annualyPersoMedic')
						{
							createRN('GDRA');
							
						}else{
							if($_GET['docVisit']=='customPersoMedic')
							{	
								createRN('GDRC');
							
							}else{
								if($_GET['docVisit']=='gnlPersoMedic')
								{
									createRN('GDRG');
								
								}
							}
						}
					}
				}
				
				echo '<script text="text/javascript">document.location.href="doctor_report.php?gnlmed='.$_GET['gnlmed'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoMedicReport=ok&createReportPdf=ok&createRN=0"</script>';
			}			
		}
		
		if(isset($_GET['divPersoBillReport']))
		{
			if (!isset($_GET['gnlmed'])) {
				
	?>
			<div id="divPersoBillReport">

				<?php
				
				$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med c.id_factureConsult IS NOT NULL AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
				$resultConsult->execute(array(
				'med'=>$idDoc
				));
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
		
				$i=0;
				
				if($comptConsult != 0)
				{
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A8', 'N°')
								->setCellValue('B8', 'Date of consultation')
								->setCellValue('C8', 'Patient name')
								->setCellValue('D8', 'Type of consultation')
								->setCellValue('E8', 'Amount')
								->setCellValue('F8', 'Services')
								->setCellValue('G8', 'Amount')
								->setCellValue('H8', 'Nursing Care')
								->setCellValue('I8', 'Amount')
								->setCellValue('J8', 'Laboratory tests')
								->setCellValue('K8', 'Amount')
								->setCellValue('L8', 'Radiologie')
								->setCellValue('M8', 'Amount')
								->setCellValue('N8', 'Total');
					
				?>
					
					<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
					
					<thead>
						<tr>
							<th style="width:2%; border-right: 1px solid #bbb">N°</th>
							<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
							<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
							<th style="width:20%; border-right: 1px solid #bbb" colspan=2><?php echo getString(113);?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
							<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
							<th style="width:15%;"><?php echo getString(99);?></th>
							<th style="width:10%;"><?php echo 'Radiologie';?></th>
							<th style="width:10%;">Total Final</th>
						</tr> 
					</thead> 


					<tbody>
				<?php
					$TotalGnlTypeConsu=0;
						
					$TotalGnlMedConsu=0;
						
					$TotalGnlMedInf=0;
						
					$TotalGnlMedLabo=0;
						
					$TotalGnlMedRadio=0;
						
					$TotalGnlPrice=0;
						
					
					$compteur=1;
					
						while($ligneConsult=$resultConsult->fetch())
						{
						/* 
							$prestaServ=array();
							$prixprestaServ=array();
							 */
							$prestaServ=array();
							$prixprestaServ=array();
							
							$prestaInf=array();
							$prixprestaInf=array();
							
							$prestaLabo=array();
							$prixprestaLabo=array();
							
							$prestaRadio=array();
							$prixprestaRadio=array();
							
							$TotalDayPrice=0;
							
							$consult = "";
							$nursery = "";
							$labs = "";						
							$radx = "";
							
				?>
						<tr>
							<td style="text-align:left;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td>
							<?php 
								echo $ligneConsult->dateconsu;
								$dateconsu = $ligneConsult->dateconsu;
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneConsult->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
									
									$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
									
								}else{
									$fullnamePa="";
									echo '';
								}
							?>
							</td>
							
							<td style="text-align:left;">
							<?php
							
							$TotalTypeConsu=0;
							
							$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								if($lignePresta->namepresta != '')
								{
									$nameprestaConsult = $lignePresta->namepresta;
								}else{	
								
									if($lignePresta->nompresta != '')
									{
										$nameprestaConsult = $lignePresta->nompresta;
									}

								}
							}						
								echo $nameprestaConsult;
							?>
							</td>
							
							<td style="text-align:center;">
								<?php
									echo $ligneConsult->prixtypeconsult;
									
									$prixconsult=$ligneConsult->prixtypeconsult;
									
									$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
									
									$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
								?>
							</td>
							<td style="text-align:left;">
							
							<?php
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_factureMedConsu!=0 AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'med'=>$idDoc,
							'idMedConsu'=>$ligneConsult->id_consu
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsult=$resultMedConsult->rowCount();
						
							$TotalMedConsu=0;
											
							if($comptMedConsult != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
										
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuServ->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
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
									<tr>
										
										<td style="text-align:left;">
										<?php	
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaServ[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';
												$prestaServ[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td>';
											$prixconsu=$ligneMedConsult->prixprestationConsu;											
											$consult .= ''.$ligneMedConsult->prixprestationConsu.', ';
										}else{
											
											echo $ligneMedConsult->autreConsu.'</td>';
											$prestaServ[] = $ligneMedConsult->autreConsu;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td>';
											
											$prixconsu=$ligneMedConsult->prixautreConsu;
											$consult .= ''.$ligneMedConsult->prixautreConsu.', ';
										}
										?>
									</tr>
									<?php
									
										$prixprestaServ[] = $prixconsu;
										
										$TotalMedConsu=$TotalMedConsu+$prixconsu;
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
							?>
							</td>
						
							<td style="text-align:left;">
							
							<?php
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE AND mi.id_uM=:med AND mi.id_factureMedInf!=0 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'med'=>$idDoc,					
							'idMedInf'=>$ligneConsult->id_consu
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

							$comptMedInf=$resultMedInf->rowCount();
						
							$TotalMedInf=0;	
											
							if($comptMedInf != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
												
										$idassuInf=$ligneMedInf->id_assuInf;
										
										$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuInf->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
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
									<tr>
										<td style="text-align:left;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';					
												$prestaInf[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';		
												$prestaInf[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td>';
											$prixinf=$ligneMedInf->prixprestation;						
																
											$nursery .= ''.$ligneMedInf->prixprestation.', ';
										}else{
											
											echo $ligneMedInf->autrePrestaM.'</td>';
												$prestaInf[] = $ligneMedInf->autrePrestaM;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
											$prixinf=$ligneMedInf->prixautrePrestaM;						
																
											$nursery .= ''.$ligneMedInf->prixautrePrestaM.', ';
										}
										?>
									</tr>
									<?php
										$prixprestaInf[] = $prixinf;
										
										$TotalMedInf=$TotalMedInf+$prixinf;
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
							?>
							</td>
						
							<td style="text-align:left;">
							
							<?php
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_factureMedLabo IS NOT NUL AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();

							$TotalMedLabo=0;
							
							if($comptMedLabo != 0)
							{
							?>	
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
									
										$idassuLab=$ligneMedLabo->id_assuLab;

										$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuLab->rowCount();
										
										for($l=1;$l<=$assuCount;$l++)
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

									?>
									<tr>
										<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta;
												$prestaLabo[] = $lignePresta->namepresta;
												
											}else{
												
												echo $lignePresta->nompresta;
												$prestaLabo[] = $lignePresta->nompresta;
											}
																
											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
											$prixlabo=$ligneMedLabo->prixprestationExa;
											$labs .= ''.$ligneMedLabo->prixprestationExa.', ';
										}else{
											
											echo $ligneMedLabo->autreExamen;
											$prestaLabo[] = $ligneMedLabo->autreExamen;
												
											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
											$prixlabo=$ligneMedLabo->prixautreExamen;
											$labs .= ''.$ligneMedLabo->prixautreExamen.', ';
										}
										?>
										</td>
									</tr>
									<?php
										$prixprestaLabo[] = $prixlabo;
										
										$TotalMedLabo=$TotalMedLabo+$prixlabo;
									}
									?>		
								</tbody>
								</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
							?>
							</td>

							<td style="text-align:left;font-weight:normal;">					
							<?php
									
							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_uM=:med AND mr.id_factureMedRadio!=0 AND mr.id_consuRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
							$resultMedRadio->execute(array(
							'med'=>$idDoc,					
							'idMedRadio'=>$ligneConsult->id_consu
							));
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

							$comptMedRadio=$resultMedRadio->rowCount();
							
							$TotalMedRadio=0;
							
							if($comptMedRadio!=0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedRadio=$resultMedRadio->fetch())
									{
										
										$idassuRad=$ligneMedRadio->id_assuRad;

										$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuRad->rowCount();
										
										for($r=1;$r<=$assuCount;$r++)
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
									<tr>
										<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedRadio->id_prestationRadio
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaRadio[] = $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta.'</td>';
												$prestaRadio[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td>';
											$prixradio=$ligneMedRadio->prixprestationRadio;	
											$radx .= ''.$ligneMedRadio->prixprestationRadio.', ';
										}else{
											
											echo $ligneMedRadio->autreRadio.'</td>';
											$prestaRadio[] = $ligneMedRadio->autreRadio;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
											$prixradio=$ligneMedRadio->prixautreRadio;
											$radx .= ''.$ligneMedRadio->prixautreRadio.', ';
										}									
										?>
										
									</tr>
									<?php
										$prixprestaRadio[] = $prixradio;
										
										$TotalMedRadio=$TotalMedRadio+$prixradio;
									}
									?>		
								</tbody>
								</table>
							<?php
							}							
							$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;							
							?>	
								
							<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
							
						</tr>
						<?php
						$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
					
						$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
							
						$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
						
						$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
							
						$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
						
						$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
							
						
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateconsu;
							$arrayConsult[$i][2]=$fullnamePa;
							$arrayConsult[$i][3]=$nameprestaConsult;
							$arrayConsult[$i][4]=$prixconsult;
							
						
							$highNumber = max(sizeof($prixconsult),sizeof($prixprestaServ),sizeof($prixprestaInf),sizeof($prixprestaLabo),sizeof($prixprestaRadio));
							
							if($highNumber==0)
							{
								$i++;
							}
						// echo '- Services : '.sizeof($prixprestaServ).'<br/>- Inf : '.sizeof($prixprestaInf).'<br/>- Labo : '.sizeof($prixprestaLabo).'<br/>- Radio : '.sizeof($prixprestaRadio).'<br/>';
							
							for($xLigne=0;$xLigne<$highNumber;$xLigne++)
							{
								if($xLigne>0)
								{
									for($e=0;$e<5;$e++)
									{
										$arrayConsult[$i][$e]='';			
									}
								}
								
								if($xLigne < sizeof($prixprestaServ))
								{						
									$arrayConsult[$i][5]=$prestaServ[$xLigne];		
									$arrayConsult[$i][6]=$prixprestaServ[$xLigne];		
								}else{
									$arrayConsult[$i][5]='';
									$arrayConsult[$i][6]='';
								}
								
								if($xLigne < sizeof($prixprestaInf))
								{						
									$arrayConsult[$i][7]=$prestaInf[$xLigne];
									$arrayConsult[$i][8]=$prixprestaInf[$xLigne];
								}else{
									$arrayConsult[$i][7]='';
									$arrayConsult[$i][8]='';
								}
								
								if($xLigne < sizeof($prixprestaLabo))
								{						
									$arrayConsult[$i][9]=$prestaLabo[$xLigne];		
									$arrayConsult[$i][10]=$prixprestaLabo[$xLigne];		
								}else{
									$arrayConsult[$i][9]='';
									$arrayConsult[$i][10]='';
								}
								
								if($xLigne < sizeof($prixprestaRadio))
								{						
									$arrayConsult[$i][11]=$prestaRadio[$xLigne];		
									$arrayConsult[$i][12]=$prixprestaRadio[$xLigne];		
								}else{
									$arrayConsult[$i][11]='';
									$arrayConsult[$i][12]='';
								}
								
								if($xLigne==0)
								{
									$arrayConsult[$i][13]=$TotalDayPrice;
								}
									
								$i++;
							}					
							$compteur++;
						}
						
						?>	

						<tr style="text-align:center;">
							<td colspan=4></td>
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
							<td style="font-size: 13px; font-weight: bold;">
								<?php						
									echo $TotalGnlPrice;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>					
					</tbody>
					</table>
				<?php
					
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','A10')
							
								->setCellValue('E'.(11+$i).'', ''.$TotalGnlTypeConsu.'')
								->setCellValue('G'.(11+$i).'', ''.$TotalGnlMedConsu.'')
								->setCellValue('I'.(11+$i).'', ''.$TotalGnlMedInf.'')
								->setCellValue('K'.(11+$i).'', ''.$TotalGnlMedLabo.'')
								->setCellValue('M'.(11+$i).'', ''.$TotalGnlMedRadio.'')
								->setCellValue('N'.(11+$i).'', ''.$TotalGnlPrice.'');

				}
				?>
			</div>
			<?php
			
				if(isset($_GET['createReportExcel']))
				{
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
					
					if($_GET['docVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Daily/'.$reportsn.'.xlsx');
						
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Daily/");</script>';
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Monthly/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Monthly/");</script>';
							
						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Annualy/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Annualy/");</script>';
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Custom/'.$reportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Custom/");</script>';
									
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Alltimes/'.$reportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
				}
				
				if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
				{				
					if($_GET['docVisit']=='dailyPersoBill')
					{
						createRN('DRD');
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							createRN('DRM');

						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								createRN('DRA');
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{	
									createRN('DRC');
								
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										createRN('DRG');
									
									}
								}
							}
						}
					}
					
					echo '<script text="text/javascript">document.location.href="doctor_report.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
				}
			}

			if (isset($_GET['gnlmed'])) {
				
	?>
			<div id="divPersoBillReport">

				<?php
				
				// $resultConsult=$connexion->query('SELECT *FROM consultations c WHERE c.done=1 AND c.id_factureConsult IS NOT NULL '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
				$resultConsult=$connexion->query('SELECT *FROM consultations c WHERE c.id_factureConsult IS NOT NULL '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
				/*$resultConsult->execute(array(
				'med'=>$idDoc
				));*/
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
		
				$i=0;
				
				if($comptConsult != 0)
				{
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A8', 'N°')
								->setCellValue('B8', 'Date of consultation')
								->setCellValue('C8', 'Doctor Name')
								->setCellValue('D8', 'Patient name')
								->setCellValue('E8', 'Type of consultation')
								->setCellValue('F8', 'Amount')
								->setCellValue('G8', 'Services')
								->setCellValue('H8', 'Amount')
								->setCellValue('I8', 'Nursing Care')
								->setCellValue('J8', 'Amount')
								->setCellValue('K8', 'Laboratory tests')
								->setCellValue('L8', 'Amount')
								->setCellValue('M8', 'Radiologie')
								->setCellValue('N8', 'Amount')
								->setCellValue('O8', 'Total');
					
				?>
					
					<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
					
					<thead>
						<tr>
							<th style="width:2%; border-right: 1px solid #bbb;text-align: center;">N°</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Date of consultation</th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Doctor Name</th>
							<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Full Patient name</th>
							<th style="width:5%; border-right: 1px solid #bbb;text-align: center;">Insurance(Percent)</th>
							<th style="width:20%; border-right: 1px solid #bbb;text-align: center;" colspan=2><?php echo getString(113);?></th>
							<th style="width:20%; border-right: 1px solid #bbb;text-align: center;"><?php echo getString(214);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;"><?php echo getString(39);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;"><?php echo getString(279);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;"><?php echo getString(98);?></th>
							<th style="width:10%; border-right: 1px solid #bbb;text-align: center;"><?php echo 'Medicament';?></th>
							<th style="width:15%;text-align: center;"><?php echo getString(99);?></th>
							<th style="width:10%;text-align: center;"><?php echo 'Radiologie';?></th>
							<th style="width:5%;text-align: center;">Total Final</th>
							<th style="width:5%;text-align: center;">Total Patients</th>
							<th style="width:5%;text-align: center;">Total Insurance</th>
						</tr> 
					</thead> 


					<tbody>
				<?php
					$TotalGnlTypeConsu=0;
						
					$TotalGnlMedConsu=0;

					$TotalGnlMedConsom=0;

					$TotalGnlMedSurge=0;
						
					$TotalGnlMedInf=0;

					$TotalGnlMedoc=0;
						
					$TotalGnlMedLabo=0;
						
					$TotalGnlMedRadio=0;
						
					$TotalGnlPrice=0;

					$TotalGnlPricePatient=0;

					$TotalGnlPriceInsurance=0;
						
					
					$compteur=1;
					
						while($ligneConsult=$resultConsult->fetch())
						{
							$prestaServ=array();
							$prixprestaServ=array();

							$prestaConsom=array();
							$prixprestaConsom=array();
							
							$prixprestaSurge=array();
							$prestaSurge=array();

							$prixprestaInf=array();
							$prestaInf=array();

							$prestaLabo=array();
							$prixprestaLabo=array();
							
							$prestaRadio=array();
							$prixprestaRadio=array();
							
							$TotalDayPrice=0;
							$TotalDayPricePatient=0;
							$TotalDayPriceInsurance=0;
							
							$consult = "";
							$consom  = "";
							$Surge  = "";
							$nursery = "";
							$labs = "";						
							$radx = "";
							$presta_assu = 'prestations_'.$ligneConsult->assuranceConsuName;
							
				?>
						<tr>
							<td style="text-align:center;">
							<?php
								echo $compteur;
							?>
							</td>
							
							<td>
							<?php 
								echo $ligneConsult->dateconsu;
								$dateconsu = $ligneConsult->dateconsu;
							?>
							</td>
							<td style="text-align: center;">
								<?php
									$resultDoct=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND u.id_u=:operation ORDER BY u.id_u DESC');
									$resultDoct->execute(array(
									'operation'=>$ligneConsult->id_uM
									));
									
									$resultDoct->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

									$comptDoct=$resultDoct->rowCount();
									
									if($ligneDoct=$resultDoct->fetch())//on recupere la liste des éléments
									{
										$fullnameDoct = $ligneDoct->nom_u.' '.$ligneDoct->prenom_u;
										
										echo $fullnameDoct;
									}else{
										echo '';
									}
									
								?>
							</td>
							
							<td style="text-align:center;">
							<?php
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
								$resultPatient->execute(array(
								'operation'=>$ligneConsult->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
									
									$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
									
								}else{
									$fullnamePa="";
									echo '';
								}
							?>
							</td>
							<td style="text-align: center;font-size: 10px;">
								<?php
									echo $ligneConsult->assuranceConsuName.'<br>'.'<b>('.$ligneConsult->insupercent.'%)</b>';
								?>
							</td>
							
							<td style="text-align:center;">
							<?php
							
							$TotalTypeConsu=0;
							$TotalTypeConsuPatient = 0;
							$TotalTypeConsuInsurance = 0;
							
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
							$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								
								if($lignePresta->namepresta != '')
								{
									$nameprestaConsult = $lignePresta->namepresta;
								}else{	
								
									if($lignePresta->nompresta != '')
									{
										$nameprestaConsult = $lignePresta->nompresta;
									}

								}
							}						
								echo $nameprestaConsult;
							?>
							</td>
							
							<td style="text-align:center;">
								<?php
									echo $ligneConsult->prixtypeconsult;
									
									$prixconsult=$ligneConsult->prixtypeconsult;
									
									$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
									$insupeconsult = $ligneConsult->insupercent;

									$TotalTypeConsuPatient = $TotalTypeConsuPatient + (($prixconsult * $insupeconsult) / 100);
									$TotalTypeConsuInsurance = $TotalTypeConsuInsurance + ($TotalTypeConsu - $TotalTypeConsuPatient);
									
									$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
									$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalTypeConsuInsurance;
								?>
							</td>

							<td style="text-align:center;">
							
							<?php
							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mc WHERE mc.id_consuConsom =:idMedConsom AND id_factureMedConsom!=0 ORDER BY mc.id_consuConsom ');		
							$resultMedConsom->execute(array(
							'idMedConsom'=>$ligneConsult->id_consu
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsom=$resultMedConsom->rowCount();
							//echo $ligneConsult->id_consu;
						
							$TotalMedConsom=0;
							$TotalMedConsomPatient=0;
						    $TotalMedConsomInsurance=0;
											
							if($comptMedConsom != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
										
									while($ligneMedConsom=$resultMedConsom->fetch())
									{
										
										$idassuServ=$ligneMedConsom->id_assuConsom;
										
										$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuServ->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
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
									<tr>
										
										<td style="text-align:center;">
										<?php	
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsom->id_prestationConsom
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaConsom[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';
												$prestaConsom[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsom->prixprestationConsom.'   <b style="font-size:7px;font-weight:400;">('.$ligneMedConsom->qteConsom.' Qty )</b></td>';
											$qty = 	$ligneMedConsom->qteConsom;									
											$prixconsom = ($ligneMedConsom->prixprestationConsom) * $qty;	
											$consom .= ''.$ligneMedConsom->prixprestationConsom.', ';
										}else{
											
											echo $ligneMedConsom->autreConsom.'</td>';
											$prestaConsom[] = $ligneMedConsom->autreConsom;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsom->prixautreConsom.'</td>';
											$qty = 	$ligneMedConsom->qteConsom;									
											$prixconsom = ($ligneMedConsom->prixautreConsom) * $qty;
											$consom .= ''.$ligneMedConsom->prixautreConsom.', ';
										}
										?>
									</tr>
									<?php
											$prixprestaConsom[] = $prixconsom;
											$insupeConsom = $ligneMedConsom->insupercentConsom;
											
											$TotalMedConsom=$TotalMedConsom+$prixconsom;

											$TotalMedConsomPatient = $TotalMedConsomPatient + (($prixconsom*$insupeConsom) / 100);
											$TotalMedConsomInsurance = $TotalMedConsomInsurance + ($TotalMedConsom - $TotalMedConsomPatient);
										}
										?>		
									</tbody>
									</table>
								<?php
								}
								
								$TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
								
								$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsomPatient;
								$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedConsomInsurance;
								if ($TotalMedConsom!=0) {
									echo "<hr/>";
									echo '<b style="">Total = '.$TotalMedConsom.'</b>';
								}
							?>
							</td>
							<td style="text-align:center;">
							
							<?php
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idMedConsu AND id_factureMedConsu!=0 ORDER BY mc.id_medconsu');		
							$resultMedConsult->execute(array(
							'idMedConsu'=>$ligneConsult->id_consu
							));
							
							$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsult=$resultMedConsult->rowCount();
						
							$TotalMedConsu=0;
							$TotalMedConsuPatient=0;
							$TotalMedConsuInsurance=0;
											
							if($comptMedConsult != 0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
										
									while($ligneMedConsult=$resultMedConsult->fetch())
									{
										
										$idassuServ=$ligneMedConsult->id_assuServ;
										
										$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuServ->rowCount();
										
										for($s=1;$s<=$assuCount;$s++)
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
									<tr>
										
										<td style="text-align:center;">
										<?php	
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedConsult->id_prestationConsu
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
										
										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaServ[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';
												$prestaServ[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td>';
											$prixconsu=$ligneMedConsult->prixprestationConsu;											
											$consult .= ''.$ligneMedConsult->prixprestationConsu.', ';
										}else{
											
											echo $ligneMedConsult->autreConsu.'</td>';
											$prestaServ[] = $ligneMedConsult->autreConsu;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td>';
											
											$prixconsu=$ligneMedConsult->prixautreConsu;
											$consult .= ''.$ligneMedConsult->prixautreConsu.', ';
										}
										?>
									</tr>
									<?php
									
									$prixprestaServ[] = $prixconsu;
									$insupeService = $ligneMedConsult->insupercentServ;
									
									$TotalMedConsu=$TotalMedConsu+$prixconsu;

									$TotalMedConsuPatient = $TotalMedConsuPatient + (($prixconsu*$insupeService) / 100);
									$TotalMedConsuInsurance = $TotalMedConsuInsurance + ($TotalMedConsu - $TotalMedConsuPatient);
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
						
						$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
						$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedConsuInsurance;
						if ($TotalMedConsu!=0) {
									echo "<hr/>";
									echo '<b style="">Total = '.$TotalMedConsu.'</b>';
								}
							?>
							</td>

							<td style="text-align: center;">
								<?php
								$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_consuSurge=:idMedConsu ORDER BY ms.id_medsurge');		
								$resultMedSurge->execute(array(
								'idMedConsu'=>$ligneConsult->id_consu
								));
								
								$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptMedSurge=$resultMedSurge->rowCount();
							
								$TotalMedSurge = 0;
								$TotalMedSurgePatient = 0;
								$TotalMedSurgeInsurance = 0;

								if($comptMedSurge != 0)
								{
								?>
									<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
									

									<tbody>
										<?php
										while($ligneMedSurge=$resultMedSurge->fetch())
										{
										?>
										<tr style="text-align:center;">
											
											<td style="text-align:center;">
											<?php
											
											$idassuSurge=$ligneMedSurge->id_assuSurge;
											
											$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
											
											$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
													
											$assuCount = $comptAssuConsu->rowCount();
											
											for($i=1;$i<=$assuCount;$i++)
											{
												
												$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
												$getAssuConsu->execute(array(
												'idassu'=>$idassuSurge
												));
												
												$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

												if($ligneNomAssu=$getAssuConsu->fetch())
												{
													$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
												}
											}
											
											$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
												'prestaId'=>$ligneMedSurge->id_prestationSurge
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptPresta=$resultPresta->rowCount();
											
											if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta.'</td>';
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedSurge->prixprestationSurge.'</td>';
													
													$prixSurge=$ligneMedSurge->prixprestationSurge;
													// $labs .= ''.$lignePresta->prixprestationSurge.', ';
												}else{								
													echo $lignePresta->nompresta.'</td>';

													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedSurge->prixprestationSurge.'</td>';
													$prixSurge=$ligneMedSurge->prixprestationSurge;
													//$labs .= ''.$ligneMedSurge->prixautreExamen.', ';
													
												}
											}else{
												
												echo $ligneMedSurge->autrePrestaS.'</td>';
											}
											?>
										</tr>
										<?php
											$prixprestaSurge[] = $prixSurge;
												
											$TotalMedSurge=$TotalMedSurge+$prixSurge;

											$insupeSurge = $ligneMedSurge->insupercentSurge;
												
											$TotalMedSurgePatient = $TotalMedSurgePatient +(($prixSurge*$insupeSurge) / 100);
											$TotalMedSurgeInsurance =$TotalMedSurgeInsurance +($TotalMedSurge - $TotalMedSurgePatient);
										}
										?>		
									</tbody>
									</table>
								<?php
								}
								$TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
								$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;
								$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedSurgeInsurance;
								if ($TotalMedSurge!=0) {
									echo "<hr/>";
									echo '<b style="">Total = '.$TotalMedSurge.'</b>';
								}
								
								?>
								</td>
						
							<td style="text-align:center;">
							
							<?php
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_consuInf=:idMedInf AND mi.id_factureMedInf!=0 ORDER BY mi.id_medinf');		
							$resultMedInf->execute(array(
							'idMedInf'=>$ligneConsult->id_consu
							));
							
							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

							$comptMedInf=$resultMedInf->rowCount();
						
								$TotalMedInf=0;	
								$TotalMedInfPatient=0;	
								$TotalMedInfInsurance=0;
											
							if($comptMedInf != 0)
							{
							?>		
								<table class="printPreview" cellspacing="0"> 
							
								<tbody>
									<?php
									while($ligneMedInf=$resultMedInf->fetch())
									{
												
										$idassuInf=$ligneMedInf->id_assuInf;
										
										$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuInf->rowCount();
										
										for($n=1;$n<=$assuCount;$n++)
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
									<tr>
										<td style="text-align:center;">
										<?php 
											
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedInf->id_prestation
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';					
												$prestaInf[] = $lignePresta->namepresta;
											}else{								
												echo $lignePresta->nompresta.'</td>';		
												$prestaInf[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td>';
											$prixinf=$ligneMedInf->prixprestation;						
																
											$nursery .= ''.$ligneMedInf->prixprestation.', ';
										}else{
											
											echo $ligneMedInf->autrePrestaM.'</td>';
												$prestaInf[] = $ligneMedInf->autrePrestaM;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
											$prixinf=$ligneMedInf->prixautrePrestaM;						
																
											$nursery .= ''.$ligneMedInf->prixautrePrestaM.', ';
										}
										?>
									</tr>
									<?php
										$prixprestaInf[] = $prixinf;
									
										$TotalMedInf=$TotalMedInf+$prixinf;

										$insupercentInf = $ligneMedInf->insupercentInf;
										
										$TotalMedInfPatient = $TotalMedInfPatient + (($prixinf*$insupercentInf) / 100);
										$TotalMedInfInsurance = $TotalMedInfInsurance + ($TotalMedInf - $TotalMedInfPatient);
								}
								?>		
							</tbody>
							</table>
							<?php
							}
							
										$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
										$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedInfPatient;
										$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedInfInsurance;
										if ($TotalMedInf!=0) {
											echo "<hr/>";
											echo '<b style="">Total = '.$TotalMedInf.'</b>';
										}

							?>
							</td>
								<td style="text-align:left;">
						
						<?php

						$resultMedoc=$connexion->prepare('SELECT *FROM med_medoc medoc WHERE medoc.id_factureMedMedoc!=0 AND medoc.id_consuMedoc=:id_consuMedoc ORDER BY medoc.id_medmedoc');		
						$resultMedoc->execute(array(				
						'id_consuMedoc'=>$ligneConsult->id_consu
						));
						
						$resultMedoc->setFetchMode(PDO::FETCH_OBJ);

						$comptMedoc=$resultMedoc->rowCount();
					
						$TotalMedoc=0;	
						$TotalMedocPatient=0;	
						$TotalMedocInsurance=0;	
										
						if($comptMedoc != 0)
						{
						?>		
							<table class="printPreview" cellspacing="0"> 
						
							<tbody>
								<?php
								while($ligneMedoc=$resultMedoc->fetch())
								{
											
									$idassumedoc=$ligneMedoc->id_assuMedoc;
									
									$comptAssumedoc=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssumedoc->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssumedoc->rowCount();
									
									for($n=1;$n<=$assuCount;$n++)
									{
										
										$getAssumedoc=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssumedoc->execute(array(
										'idassu'=>$idassumedoc
										));
										
										$getAssumedoc->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssumedoc=$getAssumedoc->fetch())
										{
											$presta_assumedoc='prestations_'.$ligneNomAssumedoc->nomassurance;
										}
									}
								?>
								<tr>
									<td style="text-align:left;">
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assumedoc.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedoc->id_prestationMedoc
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';					
											$prestamedoc[] = $lignePresta->namepresta;
										}else{								
											echo $lignePresta->nompresta.'</td>';		
											$prestamedoc[] = $lignePresta->nompresta;
										}
	
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedoc->prixprestationMedoc.'  <b style="font-size:7px;font-weight:400;">('.$ligneMedoc->qteMedoc.' Qty )</b></td>';
										$qty = $ligneMedoc->qteMedoc;			
										$prixmedoc=$ligneMedoc->prixprestationMedoc *$qty;						
															
										$nursery .= ''.$ligneMedoc->prixprestationMedoc*$qty.', ';
									}else{
										
										echo $ligneMedoc->autreMedoc.'</td>';
											$prestamedoc[] = $ligneMedoc->autreMedoc;
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedoc->prixautreMedoc.'</td>';
										$qty = $ligneMedoc->qteMedoc;		
										$prixmedoc=$ligneMedoc->prixautreMedoc*$qty;						
															
										$meddoc .= ''.$ligneMedoc->prixautreMedoc*$qty.', ';
									}
									?>
								</tr>
								<?php
									$prixprestamedoc[] = $prixmedoc;
									
									$TotalMedoc=$TotalMedoc+$prixmedoc;

									$insupercentMedoc = $ligneMedoc->insupercentMedoc;
									
									$TotalMedocPatient = $TotalMedocPatient + (($prixmedoc*$insupercentMedoc) / 100);
									$TotalMedocInsurance = $TotalMedocInsurance + ($TotalMedoc - $TotalMedocPatient);
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedoc;
						$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedocPatient;
						$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedocInsurance;
						if ($TotalMedoc!=0) {
							echo "<hr/>";
							echo '<b style="margin-left:50px;">Total = '.$TotalMedoc.'</b>';
						}
						?>
						</td>
						
							<td style="text-align:center;">
							
							<?php
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo AND ml.id_factureMedLabo!=0 ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();

							$TotalMedLabo=0;
							$TotalMedLaboPatient=0;
							$TotalMedLaboInsurance=0;
							
							if($comptMedLabo != 0)
							{
							?>	
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
									
										$idassuLab=$ligneMedLabo->id_assuLab;

										$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuLab->rowCount();
										
										for($l=1;$l<=$assuCount;$l++)
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

									?>
									<tr>
										<td style="text-align:center;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta;
												$prestaLabo[] = $lignePresta->namepresta;
												
											}else{
												
												echo $lignePresta->nompresta;
												$prestaLabo[] = $lignePresta->nompresta;
											}
																
											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
											$prixlabo=$ligneMedLabo->prixprestationExa;
											$labs .= ''.$ligneMedLabo->prixprestationExa.', ';
										}else{
											
											echo $ligneMedLabo->autreExamen;
											$prestaLabo[] = $ligneMedLabo->autreExamen;
												
											echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
											$prixlabo=$ligneMedLabo->prixautreExamen;
											$labs .= ''.$ligneMedLabo->prixautreExamen.', ';
										}
										?>
										</td>
									</tr>
									<?php
										$prixprestaLabo[] = $prixlabo;
										
										$TotalMedLabo=$TotalMedLabo+$prixlabo;

										$insupercentLab = $ligneMedLabo->insupercentLab;
										
										$TotalMedLaboPatient = $TotalMedLaboPatient + (($prixlabo*$insupercentLab) / 100);
										$TotalMedLaboInsurance = $TotalMedLaboInsurance + ($TotalMedLabo - $TotalMedLaboPatient);

								}
								?>		
							</tbody>
							</table>
							<?php
							}
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedLaboPatient;
							$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedLaboInsurance;
							if ($TotalMedLabo!=0) {
											echo "<hr/>";
											echo '<b style="">Total = '.$TotalMedLabo.'</b>';
										}
								?>
							</td>

							<td style="text-align:center;font-weight:normal;">					
							<?php
									
							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_consuRadio=:idMedRadio AND mr.id_factureMedRadio!=0 ORDER BY mr.id_medradio DESC');
							$resultMedRadio->execute(array(
							'idMedRadio'=>$ligneConsult->id_consu
							));
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

							$comptMedRadio=$resultMedRadio->rowCount();
							
							$TotalMedRadio=0;
							$TotalMedRadioPatient=0;
							$TotalMedRadioInsurance=0;
							
							if($comptMedRadio!=0)
							{
							?>
								<table class="printPreview" cellspacing="0"> 
								
								<tbody>
									<?php
									while($ligneMedRadio=$resultMedRadio->fetch())
									{
										
										$idassuRad=$ligneMedRadio->id_assuRad;

										$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
										$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
												
										$assuCount = $comptAssuRad->rowCount();
										
										for($r=1;$r<=$assuCount;$r++)
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
									<tr>
										<td style="text-align:center;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMedRadio->id_prestationRadio
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$prestaRadio[] = $lignePresta->namepresta;
											}else{
												echo $lignePresta->nompresta.'</td>';
												$prestaRadio[] = $lignePresta->nompresta;
											}
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td>';
											$prixradio=$ligneMedRadio->prixprestationRadio;	
											$radx .= ''.$ligneMedRadio->prixprestationRadio.', ';
										}else{
											
											echo $ligneMedRadio->autreRadio.'</td>';
											$prestaRadio[] = $ligneMedRadio->autreRadio;
											
											echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
											$prixradio=$ligneMedRadio->prixautreRadio;
											$radx .= ''.$ligneMedRadio->prixautreRadio.', ';
										}									
										?>
										
									</tr>
									<?php
										$prixprestaRadio[] = $prixradio;
									
									$TotalMedRadio=$TotalMedRadio+$prixradio;

									$insupercentRad = $ligneMedRadio->insupercentRad;
									
									$TotalMedRadioPatient = $TotalMedRadioPatient + (($prixlabo*$insupercentRad) / 100);
									$TotalMedRadioInsurance = $TotalMedRadioInsurance + ($TotalMedRadio - $TotalMedRadioPatient);
								}
								?>		
							</tbody>
							</table>
						<?php
						}							
						$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;							
						$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedRadioPatient;							
						$TotalDayPriceInsurance=$TotalDayPriceInsurance+$TotalMedRadioInsurance;	
						if ($TotalMedRadio!=0) {
											echo "<hr/>";
											echo '<b style="">Total = '.$TotalMedRadio.'</b>';
										}				
							?>	
								
							<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
							<td style="text-align:center;"><?php echo $TotalDayPricePatient;?></td>
							<td style="text-align:center;">
							<?php
								$AssuranceDayPrice = $TotalDayPrice - $TotalDayPricePatient;
								echo $AssuranceDayPrice;
							?>
							</td>
							
						</tr>
						<?php
						$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
					
						$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;

						$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;

						$TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
							
						$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;

						$TotalGnlMedoc=$TotalGnlMedoc + $TotalMedoc;
						
						$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
							
						$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
						
						$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	

						$TotalGnlPricePatient=$TotalGnlPricePatient + $TotalDayPricePatient;	
						
						$TotalGnlPriceInsurance=$TotalGnlPriceInsurance + $AssuranceDayPrice;	
							
						
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateconsu;
							$arrayConsult[$i][2]=$fullnameDoct;
							$arrayConsult[$i][3]=$fullnamePa;
							$arrayConsult[$i][4]=$nameprestaConsult;
							$arrayConsult[$i][5]=$prixconsult;
							
						
							$highNumber = max(sizeof($prixprestaServ),sizeof($prixprestaInf),sizeof($prixprestaLabo),sizeof($prixprestaRadio));
							
							if($highNumber==0)
							{
								$i++;
							}
						// echo '- Services : '.sizeof($prixprestaServ).'<br/>- Inf : '.sizeof($prixprestaInf).'<br/>- Labo : '.sizeof($prixprestaLabo).'<br/>- Radio : '.sizeof($prixprestaRadio).'<br/>';
							
							for($xLigne=0;$xLigne<$highNumber;$xLigne++)
							{
								if($xLigne>0)
								{
									for($e=0;$e<5;$e++)
									{
										$arrayConsult[$i][$e]='';			
									}
								}
								
								if($xLigne < sizeof($prixprestaServ))
								{						
									$arrayConsult[$i][6]=$prestaServ[$xLigne];		
									$arrayConsult[$i][7]=$prixprestaServ[$xLigne];		
								}else{
									$arrayConsult[$i][6]='';
									$arrayConsult[$i][7]='';
								}
								
								if($xLigne < sizeof($prixprestaInf))
								{						
									$arrayConsult[$i][8]=$prestaInf[$xLigne];
									$arrayConsult[$i][9]=$prixprestaInf[$xLigne];
								}else{
									$arrayConsult[$i][8]='';
									$arrayConsult[$i][9]='';
								}
								
								if($xLigne < sizeof($prixprestaLabo))
								{						
									$arrayConsult[$i][10]=$prestaLabo[$xLigne];		
									$arrayConsult[$i][11]=$prixprestaLabo[$xLigne];		
								}else{
									$arrayConsult[$i][10]='';
									$arrayConsult[$i][11]='';
								}
								
								if($xLigne < sizeof($prixprestaRadio))
								{						
									$arrayConsult[$i][12]=$prestaRadio[$xLigne];		
									$arrayConsult[$i][13]=$prixprestaRadio[$xLigne];		
								}else{
									$arrayConsult[$i][12]='';
									$arrayConsult[$i][13]='';
								}
								
								if($xLigne==0)
								{
									$arrayConsult[$i][14]=$TotalDayPrice;
								}
									
								$i++;
							}					
							$compteur++;
						}
						
						?>	

						<tr style="text-align:center;">
							<td colspan=6></td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlTypeConsu;			
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedConsom;			
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedConsu;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedSurge;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedInf;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 13px; font-weight: bold;text-align:center;">
								<?php						
									echo $TotalGnlMedoc;				
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
									echo $TotalGnlPriceInsurance;				
								?><span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
						</tr>					
					</tbody>
					</table>
				<?php
					
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','A10')
							
								->setCellValue('F'.(11+$i).'', ''.$TotalGnlTypeConsu.'')
								->setCellValue('H'.(11+$i).'', ''.$TotalGnlMedConsu.'')
								->setCellValue('J'.(11+$i).'', ''.$TotalGnlMedInf.'')
								->setCellValue('L'.(11+$i).'', ''.$TotalGnlMedLabo.'')
								->setCellValue('N'.(11+$i).'', ''.$TotalGnlMedRadio.'')
								->setCellValue('O'.(11+$i).'', ''.$TotalGnlPrice.'');

				}
				?>
			</div>
			<?php
			
				if(isset($_GET['createReportExcel']))
				{
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
					
					if($_GET['docVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Daily/'.$reportsn.'.xlsx');
						
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Daily/");</script>';
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Monthly/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Monthly/");</script>';
							
						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Annualy/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Annualy/");</script>';
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Custom/'.$reportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Custom/");</script>';
									
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/Admin/Documents/Reports/DoctorReport/General/Alltimes/'.$reportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
				}
				
				if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
				{				
					if($_GET['docVisit']=='dailyPersoBill')
					{
						createRN('DRD');
						
					}else{
						if($_GET['docVisit']=='monthlyPersoBill')
						{
							createRN('DRM');

						}else{
							if($_GET['docVisit']=='annualyPersoBill')
							{
								createRN('DRA');
								
							}else{
								if($_GET['docVisit']=='customPersoBill')
								{	
									createRN('DRC');
								
								}else{
									if($_GET['docVisit']=='gnlPersoBill')
									{
										createRN('DRG');
									
									}
								}
							}
						}
					}
					if (isset($_GET['gnlmed'])) {
						echo '<script text="text/javascript">document.location.href="doctor_report.php?gnlmed='.$_GET['gnlmed'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
					}else{
						echo '<script text="text/javascript">document.location.href="doctor_report.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
					}
				}
			}
			
			
		}
		
	}


?>

	</div>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
	
		<?php
		$footer = '

			<table style="width:100%">
				
				<tr>
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px; border-bottom:1px solid #333;">
						<span style="font-weight:bold">Dr Signature</span>
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
	
}
?>
</body>

</html>