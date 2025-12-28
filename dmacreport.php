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

$heure = date('H').' : '.date('i').' : '.date('s');



		if($_GET['paVisit']=='dailyPersoMedic')
		{
			$sn = showRN('PVD');
		}else{
			if($_GET['paVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('PVM');
			}else{
				if($_GET['paVisit']=='annualyPersoMedic')
				{
					$sn = showRN('PVA');
				}else{
					if($_GET['paVisit']=='customPersoMedic')
					{
						$sn = showRN('PVC');
					}else{
						if($_GET['paVisit']=='gnlPersoMedic')
						{
							$sn = showRN('PVG');
						}
					}
				}
			}
		}
		
		if($_GET['paVisit']=='dailyPersoBill')
		{
			$sn = showRN('PBD');
		}else{
			if($_GET['paVisit']=='monthlyPersoBill')
			{
				$sn = showRN('PBM');
			}else{
				if($_GET['paVisit']=='annualyPersoBill')
				{
					$sn = showRN('PBA');
				}else{
					if($_GET['paVisit']=='customPersoBill')
					{
						$sn = showRN('PBC');
					}else{
						if($_GET['paVisit']=='gnlPersoBill')
						{
							$sn = showRN('PBG');
						}
					}
				}
			}
		}
		
		if($_GET['paVisit']=='dailyGnlMedic')
		{
			$sn = showRN('GVD');
		}else{
			if($_GET['paVisit']=='monthlyGnlMedic')
			{
				$sn = showRN('GVM');
			}else{
				if($_GET['paVisit']=='annualyGnlMedic')
				{
					$sn = showRN('GVA');
				}else{
					if($_GET['paVisit']=='customGnlMedic')
					{
						$sn = showRN('GVC');
					}else{
						if($_GET['paVisit']=='gnlGnlMedic')
						{
							$sn = showRN('GVG');
						}
					}
				}
			}
		}
		
		if($_GET['paVisit']=='dailyGnlBill')
		{
			$sn = showRN('GBD');
		}else{
			if($_GET['paVisit']=='monthlyGnlBill')
			{
				$sn = showRN('GBM');
			}else{
				if($_GET['paVisit']=='annualyGnlBill')
				{
					$sn = showRN('GBA');
				}else{
					if($_GET['paVisit']=='customGnlBill')
					{
						$sn = showRN('GBC');
					}else{
						if($_GET['paVisit']=='gnlGnlBill')
						{
							$sn = showRN('GBG');
						}
					}
				}
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
	<title><?php echo 'Report#'.$sn; ?></title>

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
	if(isset($_GET['createReportPdf']))
	{
	?>
		<body onload="window.print()">
	<?php
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$idCoordi=$_SESSION['id'];

if($connected==true AND isset($_SESSION['id']))
{
		
	$resultatsCoordi=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
	$resultatsCoordi->execute(array(
	'operation'=>$idCoordi	
	));

	$resultatsCoordi->setFetchMode(PDO::FETCH_OBJ);
	if($ligneCoordi=$resultatsCoordi->fetch())
	{
		$doneby = $ligneCoordi->full_name;
		$codecoordi = $ligneCoordi->codecoordi;
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
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecoordi.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	<div id="Report" class="account-container" style="margin: 10px auto auto; width:98%; border: 1px solid #eee; background:#fff; padding:5px; padding-bottom:0px; border-radius:3px; font-size:80%;">
<?php
$barcode ='

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
	if(isset($_GET['num']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
		$result->execute(array(
		'operation'=>$_GET['num']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$numPa=$ligne->numero;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			
			if($ligne->sexe=="M")
			{
				$sexe = "Male";
			}else{
			
				if($ligne->sexe=="F")
				
				$sexe = "Female";
			}
			$dateN=$ligne->date_naissance;
			
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
			}else{
				$adresse='';
			}
			$profession=$ligne->profession;
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$ligne->id_assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

			if($ligneAssu=$resultAssurance->fetch())
			{
				if($ligneAssu->id_assurance == $ligne->id_assurance)
				{
					$insurance=$ligneAssu->nomassurance;
				}
			}else{
				$insurance="";
			}

			
			$uappercent= 100 - $ligne->bill;
			
			$percentpartient= 100 - $uappercent;
							
			$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//re?t l'ann?de naissance
			$month=$dateN[5].''.$dateN[6].'	';//re?t le mois de naissance

			$an= date('Y')-$old.'	';//recupere l'? en ann?
			$mois= date('m')-$month.'	';//recupere l'? en mois

			if($mois>0)
			{
				$an = intval($an);
				$mois = intval($mois);
				$an = ($an-1).' ans	'.(12+$mois).' mois';
				// echo $an= $an-1;

			}else{

				$an= $an.' ans';
				//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
				// echo $mois= date('m')-$month;
			}
		}
		
		$numPa=$_GET['num'];
		$dailydateperso=$_GET['dailydateperso'];
		$paVisit=$_GET['paVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for patient : '.$numPa.', '.$fullname.'')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'S/N')
					->setCellValue('B1', ''.$numPa.'')
					->setCellValue('A2', 'Full name')
					->setCellValue('B2', ''.$fullname.'')
					
					->setCellValue('A3', 'Adresse')
					->setCellValue('B3', ''.$adresse.'')
					
					->setCellValue('A4', 'Insurance')
					->setCellValue('B4', ''.$insurance.' '.$percentpartient.'%')
					->setCellValue('F1', 'Report #')
					->setCellValue('G1', ''.$sn.'')
					->setCellValue('F2', 'Done by')
					->setCellValue('G2', ''.$doneby.'')
					->setCellValue('F3', 'Date')
					->setCellValue('G3', ''.$annee.'');
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;">
			
			<tr>
				<td style="text-align:right">
					
					<form method="post" action="dmacreport.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&paVisit=<?php echo $paVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportPdf=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="dmacreport.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&paVisit=<?php echo $paVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportExcel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<td style="text-align:right">
					
						<a href="report.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
			
	<?php
		$userinfo = '<table style="width:100%; margin-top:20px;">
		
		<tr>
			<td style="text-align:left;">
				<span style="font-weight:bold">Full name: </span>
				'.$fullname.'<br/>
				<span style="font-weight:bold">Gender: </span>'.$sexe.'<br/>
				<span style="font-weight:bold">Adress: </span>'.$adresse.'
			</td>
			
			<td style="text-align:center;">
				<span style="font-weight:bold">Insurance type: </span>'.$insurance.'<br/>
				<span style="font-weight:bold">Patient payment: </span>'.$percentpartient.' %<br/>
				<span style="font-weight:bold">Insurance payment: </span>'.$uappercent.' %
			</td>
			
			<td style="text-align:right;">
				<span style="font-weight:bold">S/N: </span>'.$numPa.'<br/>
				<span style="font-weight:bold">Date of birth: </span>'.$dateN.'<br/>
				
			</td>
							
		</tr>		
	</table>';

		echo $userinfo;
	
		if(isset($_GET['divPersoMedicReport']))
		{
	?>
		<div id="divPersoMedicReport">

			<table cellspacing="0" style="background:#fff; margin: 20px auto auto;">
				<tr>
					<td>
						<b><h3 style="padding:10px">Medical Report #<?php echo $sn;?></h3></b>
					</td>
				</tr>
			
			</table>
			
			<table style="width:100%">
			
				<tr>
					
					<td style="text-align:right;">
						 Done by : <?php echo $doneby;?>
					</td>
							
				</tr>
				
			</table>
		
			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num '.$dailydateperso.' ORDER BY c.id_consu DESC');		
			$resultConsult->execute(array(
			'num'=>$numPa
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A8', 'N°')
							->setCellValue('B8', 'Date of consultation')
							->setCellValue('C8', 'Type of consultation')
							->setCellValue('D8', 'Services')
							->setCellValue('E8', 'Nursing Care')
							->setCellValue('F8', 'Laboratory tests');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:7%; border-right: 1px solid #bbb">N°</th>
						<th style="width:11%; border-right: 1px solid #bbb">Date</th>
						<th style="width:35%; border-right: 1px solid #bbb"><?php echo getString(113);?></th>
						<th style="width:18%; border-right: 1px solid #bbb"><?php echo getString(39);?>s</th>
						<th style="width:18%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
						<th style="width:18%;"><?php echo getString(99);?></th>
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
						
			?>
					<tr style="text-align:center;">
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
						<td>
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
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
					
						echo '<td>';
						
						
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.numero=:num AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'num'=>$numPa,
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
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
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
									}
									
									if($ligneMedConsult->autreConsu != "")
									{
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
					
						echo '</td>';
						
						echo '<td>';
						
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'num'=>$numPa,					
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
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
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
									}
									
									if($ligneMedInf->autrePrestaM != "")
									{
										echo $ligneMedInf->autrePrestaM.'</td>';
										$nursery .= ''.$ligneMedInf->autrePrestaM.', ';
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

						
						echo '</td>';
						
						echo '<td>';
						
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.numero=:num AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'num'=>$numPa,					
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
						?>
								<tr style="text-align:center;">
									<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$labs .= ''.$lignePresta->namepresta.', ';
											}else{
												
												echo $lignePresta->nompresta.'</td>';
												$labs .= ''.$lignePresta->nompresta.', ';
											}
										}
										
										if($ligneMedLabo->autreExamen != "")
										{
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
						$arrayConsult[$i][2]=$nameprestaConsult;
						$arrayConsult[$i][3]=$consult;
						$arrayConsult[$i][4]=$nursery;
						$arrayConsult[$i][5]=$labs;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','A10');
									
						$compteur++;
		
					}
			?>		
				</tbody>
				</table>
			<?php
			}
			?>
		</div>
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn=str_replace('/', '_', $sn);
				
				if($_GET['paVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoMedicReportFiles/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoMedicReportFiles/Daily/");</script>';
					createRN('PVD');
					
				}else{
					if($_GET['paVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoMedicReportFiles/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoMedicReportFiles/Monthly/");</script>';
						createRN('PVM');
					
					}else{
						if($_GET['paVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoMedicReportFiles/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoMedicReportFiles/Annualy/");</script>';
							createRN('PVA');
							
						}else{
							if($_GET['paVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoMedicReportFiles/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoMedicReportFiles/Custom/");</script>';
								createRN('PVC');
								
							}else{
								if($_GET['paVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoMedicReportFiles/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoMedicReportFiles/Alltimes/");</script>';
									createRN('PVG');
								}
							}
						}
					}
				}
			}
			
		}
		
		if(isset($_GET['divPersoBillReport']))
		{
			// echo $_GET['dailydateperso'];
		?>
		<div id="divPersoBillReport">
	
			<table cellspacing="0" style="background:#fff; margin:20px auto auto">
				<tr>
					<td>
						<b><h3 style="padding:10px">Billing Report #<?php echo $sn;?></h3></b>
					</td>
				</tr>
			
			</table>
			
			<table style="width:100%">
			
			<tr>
				
				<td style="text-align:right;">
					Done by : <?php echo $doneby;?>
				</td>
						
			</tr>
			
		</table>
			<?php
			
			$resultBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.numero=:numPa '.$dailydateperso.'');		
			
			$resultBillReport->execute(array(
				'numPa'=>$_GET['num']
			));
			
			$resultBillReport->setFetchMode(PDO::FETCH_OBJ);

			$comptBillReport=$resultBillReport->rowCount();

			if($comptBillReport!=0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A8', 'N°')
							->setCellValue('B8', 'Date')
							->setCellValue('C8', 'Bill number')
							->setCellValue('D8', 'Insurance')
							->setCellValue('E8', 'Type of consultation')
							->setCellValue('F8', 'Total Type of consultation (Rwf)')
							->setCellValue('G8', 'Services')
							->setCellValue('H8', 'Total Services (Rwf)')
							->setCellValue('I8', 'Nursing Care')
							->setCellValue('J8', 'Total Nursing Care (Rwf)')
							->setCellValue('K8', 'Laboratory tests')
							->setCellValue('L8', 'Total Laboratory tests (Rwf)')
							->setCellValue('M8', 'Total Final');
				
			?>
			<table  class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; border-top:none"> 
						
				<thead>
					<tr>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;">N°</th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;">Date</th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;">Bill number</th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;">Insurance</th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(113);?></th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?>s</th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;"><?php echo getString(99);?></th>
						<th style="width:3%; border-right: 1px solid #bbb;text-align:center;">Total Final</th>
					</tr> 
				</thead> 
				
				<tbody>
			<?php
			$TotalGnlTypeConsu=0;
			$TotalGnlMedConsu=0;
			$TotalGnlMedInf=0;
			$TotalGnlMedLabo=0;
			$TotalGnlPrice=0;
			$i=0;
			
			$compteur=1;
			
				while($ligneBillReport=$resultBillReport->fetch())
				{
					$consult ="";
					$medconsu ="";
					$medinf ="";
					$medlabo ="";
			?>
					<tr style="text-align:center;">
						<td style="text-align:center;">
						<?php
							echo $compteur;
						?>
						</td>
						
						<td style="text-align:center;"><?php echo $ligneBillReport->datebill;?></td>
						<td style="text-align:center;"><?php echo $ligneBillReport->numbill;?></td>
						<td style="text-align:center;"><?php echo $ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';?></td>
						<td style="text-align:center;">
							<table cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
							<?php
				
							$resultConsu=$connexion->prepare('SELECT *FROM consultations c, prestations p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
							$resultConsu->execute(array(
							'idbill'=>$ligneBillReport->id_bill
							));
							
							$comptConsu=$resultConsu->rowCount();
							
							$resultConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							if($comptConsu!=0)
							{
								while($ligneConsu=$resultConsu->fetch())//on recupere la liste des éléments
								{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneConsu->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneConsu->prixpresta.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							<?php
									$consult .= ''.$ligneConsu->nompresta;
								}
							}
							?>
									
								<tr>
									<?php
									
									if($ligneBillReport->totaltypeconsuprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totaltypeconsuprice.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td style="text-align:center;">
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
										
							<?php
							
					$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
					$resultMedConsu->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedConsu=$resultMedConsu->rowCount();
					
					$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations p WHERE  mc.id_factureMedConsu=:idbill GROUP BY id_medconsu ORDER BY mc.id_medconsu DESC');
					$resultMedAutreConsu->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
					
					$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
				if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
				{
					if($comptMedConsu!=0)
					{
						while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des éléments
						{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedConsu->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedConsu->prixpresta.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							<?php
							
							$medconsu .= ''.$ligneMedConsu->nompresta.' ('.$ligneMedConsu->prixpresta.'), ';
						
						}
					}
					
					if($comptMedAutreConsu!=0)
					{
						while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())//on recupere la liste des éléments
						{
							
							if($ligneMedAutreConsu->prixautreConsu!=0 and $ligneMedAutreConsu->autreConsu!="")
							{
							?>
							<tr>
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreConsu->autreConsu;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreConsu->prixautreConsu.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
								
								?>
								</td>
							</tr>
							<?php
								
								$medconsu .= ''.$ligneMedAutreConsu->autreConsu.' ('.$ligneMedAutreConsu->prixautreConsu.' Rwf), ';
							
							}
							
						}
					}

				}					
				?>
									
								<tr>
									<?php
									
									if($ligneBillReport->totalmedconsuprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totalmedconsuprice.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							</table>
						
						</td>
						
						<td style="text-align:center;">
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
							<?php
							
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedInf->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedInf=$resultMedInf->rowCount();
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations p WHERE mi.id_factureMedInf=:idbill GROUP BY id_medinf ORDER BY mi.id_medinf DESC');
					$resultMedAutreInf->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreInf=$resultMedAutreInf->rowCount();
					
					$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
				if($comptMedInf!=0 or $comptMedAutreInf!=0)
				{
					if($comptMedInf!=0)
					{
						while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des éléments
						{
							if($ligneMedInf->prixpresta!=0 and $ligneMedInf->nompresta!="")
							{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedInf->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedInf->prixpresta.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							<?php
								
								$medinf .= ''.$ligneMedInf->nompresta.' ('.$ligneMedInf->prixpresta.' Rwf), ';
								
							}
						}
					}
					
					if($comptMedAutreInf!=0)
					{
						while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des éléments
						{
							
							if($ligneMedAutreInf->prixautrePrestaM != 0 and $ligneMedAutreInf->autrePrestaM != "")
							{
							?>
							<tr>
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreInf->autrePrestaM;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreInf->prixautrePrestaM.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
								
								?>
								</td>
							</tr>
							<?php
								
								$medinf .= ''.$ligneMedAutreInf->autrePrestaM.' ('.$ligneMedAutreInf->prixautrePrestaM.' Rwf), ';
							
							}
						}
					}

				}					
				?>
									
								<tr>
									<?php
									
									if($ligneBillReport->totalmedinfprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totalmedinfprice.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							</table>
									
						</td>
						<td style="text-align:center;">
							<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
											
							<?php
							
					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
					$resultMedLabo->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedLabo=$resultMedLabo->rowCount();
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations p WHERE ml.id_factureMedLabo=:idbill GROUP BY id_medlabo ORDER BY ml.id_medlabo DESC');
					$resultMedAutreLabo->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
					
					$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
				if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
				{
					if($comptMedLabo!=0)
					{
						while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des éléments
						{
							if($ligneMedLabo->nompresta != "" and $ligneMedLabo->prixpresta != 0)
							{
							?>
								<tr>
									<td style="text-align:center">
									<?php
										echo $ligneMedLabo->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
										echo $ligneMedLabo->prixpresta.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							<?php
							
								$medlabo .= ''.$ligneMedLabo->nompresta.' ('.$ligneMedLabo->prixpresta.' Rwf), ';
						
							}
						
						}
					}
					
					if($comptMedAutreLabo!=0)
					{
						while($ligneMedAutreLabo=$resultMedAutreInf->fetch())//on recupere la liste des éléments
						{
							
							if($ligneMedAutreLabo->prixautreExamen != 0 and $ligneMedAutreLabo->autreExamen != "")
							{
							?>
							<tr>
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreLabo->autreExamen;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
								
									echo $ligneMedAutreLabo->prixautreExamen.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
								
								?>
								</td>
							</tr>
							<?php
								
								$medlabo .= ''.$ligneMedAutreLabo->autreExamen.' ('.$ligneMedAutreLabo->prixautreExamen.' Rwf), ';
								
							}
						}
					}

				}					
				?>
									
								<tr>
									<?php
									
									if($ligneBillReport->totalmedlaboprice!=0)
									{
									?>
									<td></td>
									<?php
									}
									?>
									<td style="text-align:center">
									<?php

										echo $ligneBillReport->totalmedlaboprice.'<span style="font-size:80%; font-weight:normal;">Rwf</span>';
									?>
									</td>
								</tr>
							</table>
						
						</td>
						
						<td style="text-align:center;"><?php echo $ligneBillReport->totalgnlprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
					</tr>
			<?php
					$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneBillReport->totaltypeconsuprice;
					$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneBillReport->totalmedconsuprice;
					$TotalGnlMedInf= $TotalGnlMedInf + $ligneBillReport->totalmedinfprice;
					$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneBillReport->totalmedlaboprice;
					$TotalGnlPrice=$TotalGnlPrice + $ligneBillReport->totalgnlprice;
					
					
					
					$arrayPersoBillReport[$i][0]=$compteur;
					$arrayPersoBillReport[$i][1]=$ligneBillReport->datebill;
					$arrayPersoBillReport[$i][2]=$ligneBillReport->numbill;
					$arrayPersoBillReport[$i][3]=$ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';
					
					$arrayPersoBillReport[$i][4]=$consult;
					
					$arrayPersoBillReport[$i][5]=$ligneBillReport->totaltypeconsuprice;
					$arrayPersoBillReport[$i][6]=$medconsu;
					
					$arrayPersoBillReport[$i][7]=$ligneBillReport->totalmedconsuprice;
					$arrayPersoBillReport[$i][8]=$medinf;
					
					$arrayPersoBillReport[$i][9]=$ligneBillReport->totalmedinfprice;
					$arrayPersoBillReport[$i][10]=$medlabo;
					
					$arrayPersoBillReport[$i][11]=$ligneBillReport->totalmedlaboprice;
					
					$arrayPersoBillReport[$i][12]=$ligneBillReport->totalgnlprice;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayPersoBillReport,'','A10');
								
					$compteur++;
	
				}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
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
								echo $TotalGnlPrice;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
					
				$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('F'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
								->setCellValue('H'.(10+$i).'', ''.$TotalGnlMedConsu.'')
								->setCellValue('J'.(10+$i).'', ''.$TotalGnlMedInf.'')
								->setCellValue('L'.(10+$i).'', ''.$TotalGnlMedLabo.'')
								->setCellValue('M'.(10+$i).'', ''.$TotalGnlPrice.'');

				
			}
			?>
		</div>
		
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn=str_replace('/', '_', $sn);
				
				
				if($_GET['paVisit']=='dailyPersoBill')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoBillReportFiles/Daily/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoBillReportFiles/Daily/");</script>';
					createRN('PBD');

				}else{
					if($_GET['paVisit']=='monthlyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoBillReportFiles/Monthly/'.$reportsn.'.xlsx');
							
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoBillReportFiles/Monthly/");</script>';
						createRN('PBM');
						
					}else{
						if($_GET['paVisit']=='annualyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoBillReportFiles/Annualy/'.$reportsn.'.xlsx');
							
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoBillReportFiles/Annualy/");</script>';
							createRN('PBA');
							
						}else{
							if($_GET['paVisit']=='customPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoBillReportFiles/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoBillReportFiles/Custom/");</script>';
								createRN('PBC');
								
							}else{
								if($_GET['paVisit']=='gnlPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/PersoBillReportFiles/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/PersoBillReportFiles/Alltimes/");</script>';
									createRN('PBG');
								}
							}
						}
					}
				}
				
			}
		}

	}
	
	
	if(isset($_GET['gnlpatient']))
	{
		$dailydategnl=$_GET['dailydategnl'];
		$paVisitgnl=$_GET['paVisit'];
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.$sn.'')
					 ->setSubject("Billing information")
					 ->setDescription('Billing information for all patients')
					 ->setKeywords("Bill Excel")
					 ->setCategory("Bill");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B1', 'Report #')
					->setCellValue('C1', ''.$sn.'')
					->setCellValue('B2', 'Done by')
					->setCellValue('C2', ''.$doneby.'')
					->setCellValue('B3', 'Date')
					->setCellValue('C3', ''.$annee.'');
		
		if(isset($_GET['stringResult']))
		{
			$stringResult=$_GET['stringResult'];
		}
		
	?>
		<table cellpadding=3 style="width:100%; margin:5px auto auto;">
			
			<tr>
				
				<td style="text-align:left; width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:center; width:40%;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Patient Billing Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right; width:30%;">
						
					<form method="post" action="dmacreport.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisit=<?php echo $paVisitgnl;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportPdf=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
						
					<form method="post" action="dmacreport.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisit=<?php echo $paVisitgnl;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportExcel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				
				<td style="text-align:right">
					
					<a href="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreport=ok&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</a>
					
				</td>
			</tr>
		
		</table>
		
		<?php
		if(isset($_GET['divGnlMedicReport']))
		{
		?>
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;">
			<tr>
					<td style="font-size:18px; width:33.333%;text-align:center " id="gnlmedicalstring">
						<b><h3>General Medical Report #<?php echo $sn;?></h3></b>
					</td>
			</tr>
		</table>
		<?php 
		}
		?>
		
		<?php
		if(isset($_GET['divGnlMedicReport']))
		{
		?>	
			<div id="divGnlMedicReport">

			<?php
			
				$resultConsult=$connexion->query('SELECT *FROM consultations c '.$dailydategnl.' ORDER BY c.dateconsu DESC');		
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult=$resultConsult->rowCount();
			
				$i=0;

				if($comptConsult != 0)
				{
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A6', 'N°')
								->setCellValue('B6', 'Date of consultation')
								->setCellValue('C6', 'Name')
								->setCellValue('D6', 'Type of consultation')
								->setCellValue('E6', 'Services')
								->setCellValue('F6', 'Nursing Care')
								->setCellValue('G6', 'Laboratory tests');
					
			?>
				<table class="printPreview tablesorter3" cellspacing="0" style="background:#fff; width:100%;"> 
					<thead> 
						<tr>
							<th>N°</th>
							<th>Date</th>
							<th>Name</th>
							<th><?php echo getString(113);?></th>
							<th><?php echo getString(39);?>s</th>
							<th><?php echo getString(98);?></th>
							<th><?php echo getString(99);?></th>
						</tr> 
					</thead> 


					<tbody>
				<?php
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$consult = "";

						$nursery = "";

						$labs = "";
				?>
				
					<tr style="text-align:center;">
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
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo '<td>'.$fullname.'</td>';
							}else{
								echo '<td></td>';
							}
							
						?>
						
						<td>
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
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
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaConsult = $lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
						}
						?>
						<td>
						<?php
						
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsult=$resultMedConsult->rowCount();
					
					
						if($comptMedConsult != 0)
						{
						?>
							<table class="printPreview" cellspacing="0" style="background:#fff; margin-top:10px;"> 

							<tbody>
						<?php
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
						?>
								<tr style="text-align:center;">
									
									<td>
									<?php
									
									$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
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
									}
									
									if($ligneMedConsult->autreConsu != "")
									{
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
						
						<td>
						<?php
						
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(				
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

						$comptMedInf=$resultMedInf->rowCount();
					
					
						if($comptMedInf != 0)
						{
						?>		
							<table class="printPreview" cellspacing="0" style="background:#fff; margin-top:10px;"> 
						
							<tbody>
						<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
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
											$nursery .= ''.$lignePresta->nompresta.', ';
										}
									}
									
									if($ligneMedInf->autrePrestaM != "")
									{
										echo $ligneMedInf->autrePrestaM.'</td>';
										$nursery .= ''.$ligneMedInf->autrePrestaM.', ';
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
						
						<td>
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(			
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();


						if($comptMedLabo != 0)
						{
						?>	
							<table class="printPreview" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tbody>
						<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
						?>
								<tr style="text-align:center;">
									<td>
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta.'</td>';
												$labs .= ''.$lignePresta->namepresta.', ';
											}else{
					
												echo $lignePresta->nompresta.'</td>';
												$labs .= ''.$lignePresta->nompresta.', ';
											}
										}
										
										if($ligneMedLabo->autreExamen !="")
										{
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
						$arrayConsult[$i][2]=$fullname;
						$arrayConsult[$i][3]=$nameprestaConsult;
						$arrayConsult[$i][4]=$consult;
						$arrayConsult[$i][5]=$nursery;
						$arrayConsult[$i][6]=$labs;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','A8');
									
						$compteur++;
					}
				?>
					</tbody>
				</table>
				<?php
				}
				?>
			</div>
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn=str_replace('/', '_', $sn);
				
				
				if($_GET['paVisit']=='dailyGnlMedic')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlMedicReportFiles/Daily/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlMedicReportFiles/Daily/");</script>';
					
					createRN('GVD');
					
				}else{
					if($_GET['paVisit']=='monthlyGnlMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlMedicReportFiles/Monthly/'.$reportsn.'.xlsx');
							
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlMedicReportFiles/Monthly/");</script>';
						createRN('GVM');
						
					}else{
						if($_GET['paVisit']=='annualyGnlMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlMedicReportFiles/Annualy/'.$reportsn.'.xlsx');
							
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlMedicReportFiles/Annualy/");</script>';
							createRN('GVA');
							
						}else{
							if($_GET['paVisit']=='customGnlMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlMedicReportFiles/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlMedicReportFiles/Custom/");</script>';
								createRN('GVC');
								
							}else{
								if($_GET['paVisit']=='gnlGnlMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlMedicReportFiles/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlMedicReportFiles/Alltimes/");</script>';
									createRN('GVG');
								}
							}
						}
					}
				}
				
			}
		
		}
		?>
		
		<?php
		if(isset($_GET['divGnlBillReport']))
		{
		?>
		<div id="divGnlBillReport" style="font-weight:normal;">
		
			<?php
			
			$resultGnlBillReport=$connexion->query('SELECT *FROM bills WHERE numero !="" AND '.$dailydategnl.' ORDER BY datebill ASC');

			$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			
			$comptBillReport=$resultGnlBillReport->rowCount();
			
			if($comptBillReport!=0)
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
			<table class="printPreview tablesorter3" cellspacing="0" style="background:#fff; margin:auto;"> 
						
				<thead>
					<tr>
						<th style="border-right: 1px solid #bbb;text-align:center;">N°</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Date</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Bill number</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Insurance Type</th>
						<th style="border-right: 1px solid #bbb;text-align:center;" colspan=2>Beneficiary's age / gender </th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Beneficiary's names</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Principal member</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's affectation</th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(113);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?>s</th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(99);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medical imaging';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Dental';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Consommables';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medications';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Patient</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Insurance</th>
					</tr> 
				</thead> 
				
				<tbody>
				<?php
		
				$billArray = array();
				
				$idBillString = '(';
				
				// echo $comptBillReport.'<br/>';
				
				
				while($ligneGnlBillReport=$resultGnlBillReport->fetch(PDO::FETCH_ASSOC))
				{
					$billArray[] = $ligneGnlBillReport;
					$idBillString .= ''.$ligneGnlBillReport['id_bill'].',';	
				}
				
				$idBillString = substr($idBillString,0,-1).')';
								

								
				$resultConsu=$connexion->query('SELECT *FROM consultations c WHERE c.id_factureConsult in '.$idBillString.'');
				 
				$consuArray=array();
				 
				$comptConsu=$resultConsu->rowCount();
				
			
				while($ligneGnlConsultReport=$resultConsu->fetch(PDO::FETCH_ASSOC))
				{
					$consuArray[$ligneGnlConsultReport['id_factureConsult']] = $ligneGnlConsultReport;
					
				}							
				
				$resultServices=$connexion->query('SELECT *FROM med_consult mc WHERE mc.id_factureMedConsu in '.$idBillString.'');
				
				$comptServices=$resultServices->rowCount();
				
				$serviceArray=array();				
				$sCount=0;
				
				while($ligneGnlServices=$resultServices->fetch(PDO::FETCH_ASSOC))
				{
					if(array_key_exists($ligneGnlServices['id_factureMedConsu'], $serviceArray))
					{
						$sCount = sizeof($serviceArray[$ligneGnlServices['id_factureMedConsu']]);
					}else{
						$sCount=0;
					}
					
					$serviceArray[$ligneGnlServices['id_factureMedConsu']][$sCount] = $ligneGnlServices;
					
					
				}							
								
				
				$resultMedInf=$connexion->query('SELECT *FROM med_inf mi WHERE mi.id_factureMedInf in '.$idBillString.'');
				
				$comptMedInf=$resultMedInf->rowCount();
					

				$infArray=array();				
				$iCount=0;				
				
				while($ligneGnlInf=$resultMedInf->fetch(PDO::FETCH_ASSOC))
				{
					if(array_key_exists($ligneGnlInf['id_factureMedInf'], $infArray))
					{
						$iCount = sizeof($infArray[$ligneGnlInf['id_factureMedInf']]);
					}else{
						$iCount=0;
					}
						
						$infArray[$ligneGnlInf['id_factureMedInf']][$iCount] = $ligneGnlInf;
					
				}							
										
				
				$resultMedLabo=$connexion->query('SELECT *FROM med_labo ml WHERE ml.id_factureMedLabo in '.$idBillString.'');
				
				$comptMedLabo=$resultMedLabo->rowCount();
					

				$laboArray=array();				
				$lCount=0;
				
				while($ligneGnlLabo=$resultMedLabo->fetch(PDO::FETCH_ASSOC))
				{
					if(array_key_exists($ligneGnlLabo['id_factureMedLabo'], $laboArray))
					{
						$lCount = sizeof($laboArray[$ligneGnlLabo['id_factureMedLabo']]);
					}else{
						$lCount=0;
					}
					
					$laboArray[$ligneGnlLabo['id_factureMedLabo']][$lCount] = $ligneGnlLabo;
					
					
				}							
						// print_r($laboArray[20873]);					
										
				
				$resultMedRadio=$connexion->query('SELECT *FROM med_radio mr WHERE mr.id_factureMedRadio in '.$idBillString.'');
				
				$comptMedRadio=$resultMedRadio->rowCount();
					

				$radioArray=array();				
				$rCount=0;
				
				while($ligneGnlRadio=$resultMedRadio->fetch(PDO::FETCH_ASSOC))
				{
					if(array_key_exists($ligneGnlRadio['id_factureMedRadio'], $radioArray))
					{
						$rCount = sizeof($radioArray[$ligneGnlRadio['id_factureMedRadio']]);
					}else{
						$rCount=0;
					}
					
					$radioArray[$ligneGnlRadio['id_factureMedRadio']][$rCount] = $ligneGnlRadio;
					
				}							
						// print_r($radioArray[20873]);			



				$resultMedSurge=$connexion->query('SELECT *FROM med_surge ms WHERE ms.id_factureMedSurge in '.$idBillString.'');
				
				$comptMedSurge=$resultMedSurge->rowCount();
					

				$surgeArray=array();
				$sCount=0;
				
				while($ligneGnlSurge=$resultMedSurge->fetch(PDO::FETCH_ASSOC))
				{
					if(array_key_exists($ligneGnlSurge['id_factureMedSurge'], $surgeArray))
					{
						$sCount = sizeof($surgeArray[$ligneGnlSurge['id_factureMedSurge']]);
					}else{
						$sCount=0;
					}
						
						$surgeArray[$ligneGnlSurge['id_factureMedSurge']][$sCount] = $ligneGnlSurge;
					
				}				
										
				
				$resultMedConsom=$connexion->query('SELECT *FROM med_consom mco WHERE mco.id_factureMedConsom in '.$idBillString.'');
				
				$comptMedConsom=$resultMedConsom->rowCount();
					

				$consomArray=array();				
				$cCount=0;
				
				while($ligneGnlConsom=$resultMedConsom->fetch(PDO::FETCH_ASSOC))
				{
					if(array_key_exists($ligneGnlConsom['id_factureMedConsom'], $consomArray))
					{
						$cCount = sizeof($consomArray[$ligneGnlConsom['id_factureMedConsom']]);
					}else{
						$cCount=0;
					}
					
					$consomArray[$ligneGnlConsom['id_factureMedConsom']][$cCount] = $ligneGnlConsom;
					
				}							
						// print_r($consomArray[20873]);					
										
				
				$resultMedMedoc=$connexion->query('SELECT *FROM med_medoc mdo WHERE mdo.id_factureMedMedoc in '.$idBillString.'');
				
				$comptMedMedoc=$resultMedMedoc->rowCount();
					

				$medocArray=array();				
				$mCount=0;
				
				while($ligneGnlMedoc=$resultMedMedoc->fetch(PDO::FETCH_ASSOC))
				{
					if(array_key_exists($ligneGnlMedoc['id_factureMedMedoc'], $medocArray))
					{
						$mCount = sizeof($medocArray[$ligneGnlMedoc['id_factureMedMedoc']]);
					}else{
						$mCount=0;
					}
					
					$medocArray[$ligneGnlMedoc['id_factureMedMedoc']][$mCount] = $ligneGnlMedoc;
										
				}							
					// print_r($medocArray[20873]);					
					
										
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
				$TotalGnlMedSurge=0;
					$TotalGnlMedSurgePatient=0;
					$TotalGnlMedSurgeInsu=0;
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
								

				for($b=0;$b<sizeof($billArray);$b++)
				{
					$TotalDayPrice=0;
					$TotalDayPricePatient=0;
					$TotalDayPriceInsu=0;
					
					$consult ="";
					$medconsu ="";
					$medinf ="";
					$medlabo ="";
					$medradio ="";
					$medsurge ="";
					$medconsom ="";
					$medmedoc ="";
						
			?>
		<!-- ici -->
				<tr style="text-align:center;<?php if($billArray[$b]['codecashier']==''){ echo 'background:rgb(228,228,228)'; }?>">
					<td style="text-align:center;"><?php echo $compteur;?></td>
					<td style="text-align:center;"><?php echo $billArray[$b]['datebill']."  ".$billArray[$b]['codecashier'];?></td>
					<!-- <td style="text-align:center;"><?php echo $billArray[$b]['datebill']."  ".$billArray[$b]['codecashier'];?></td> -->
							
					<td style="text-align:center;"><?php echo $billArray[$b]['numbill'];?></td>
							
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
						$resultPatient->execute(array(
						'operation'=>$billArray[$b]['numero']
						));
				
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit recupérable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
						{
							$fullname = $lignePatient->full_name;
							$numero = $lignePatient->numero;
							$sexe = $lignePatient->sexe;
							$carteassuid = $billArray[$b]['idcardbill'];
							$insurancetype = $billArray[$b]['nomassurance'].' ('.$billArray[$b]['billpercent'].'%)';
							
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
							
							
							
							echo '<td style="text-align:center;">'.$billArray[$b]['nomassurance'].' ('.$billArray[$b]['billpercent'].'%)</td>';	
							
							echo '<td style="text-align:center;">'.$age.'</td>';
							echo '<td style="text-align:center;">'.$sexe.'</td>';
							echo '<td style="text-align:center; font-weight: bold;">'.$fullname.' ('.$numero.')</td>';
							echo '<td style="text-align:center; font-weight: normal;">'.$adherent.'</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$profession.'</td>';
						}else{
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
						}
						
					?>
										
					<td style="text-align:center;">
					
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">
								<?php
								
								$TotalTypeConsu=0;
								$TotalTypeConsuPatient=0;
								$TotalTypeConsuInsu=0;
							
							if($comptConsu!=0)
							{	
								if(array_key_exists($billArray[$b]['id_bill'], $consuArray))
								{
									if($consuArray[$billArray[$b]['id_bill']]['prixtypeconsult']!=0 AND $consuArray[$billArray[$b]['id_bill']]['prixrembou']!=0)
									{
										$prixPrestaRembou=$consuArray[$billArray[$b]['id_bill']]['prixrembou'];
																		
										$prixconsult=$consuArray[$billArray[$b]['id_bill']]['prixtypeconsult'] - $prixPrestaRembou;
									
									}else{
										$prixconsult=$consuArray[$billArray[$b]['id_bill']]['prixtypeconsult'];

									}
										
									$prixconsultpatient=($prixconsult * $consuArray[$billArray[$b]['id_bill']]['insupercent'])/100;							
									
									$prixconsultinsu= $prixconsult - $prixconsultpatient;	
									
									if($prixconsult>=0)
									{	
										$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
								
										$TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
										$TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
									}
								}
							}
								
								echo $TotalTypeConsu;
						$consult .= $TotalTypeConsu;
							
								$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
								$TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
								$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalTypeConsuInsu;
								
								?>									
								</td>
							</tr>
						</table>						
					</td>							
						
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">		
								<?php
												
								$TotalMedConsu=0;
								$TotalMedConsuPatient=0;
								$TotalMedConsuInsu=0;
								
							// print_r($serviceArray);	
							
						if($comptServices!=0)
						{
							if(array_key_exists($billArray[$b]['id_bill'], $serviceArray))
							{
								
								for($s=0;$s<sizeof($serviceArray[$billArray[$b]['id_bill']]);$s++)
								{
									$prixprestationConsu=$serviceArray[$billArray[$b]['id_bill']][$s]['prixprestationConsu'];
									$prixrembouConsu=$serviceArray[$billArray[$b]['id_bill']][$s]['prixrembouConsu'];
									$prixautreConsu=$serviceArray[$billArray[$b]['id_bill']][$s]['prixautreConsu'];
									$insupercentServ=$serviceArray[$billArray[$b]['id_bill']][$s]['insupercentServ'];
									
									
									if($prixprestationConsu!=0 AND $prixrembouConsu!=0)
									{
										$prixPrestaRembou=$prixrembouConsu;
										
										$prixconsu=$prixprestationConsu - $prixPrestaRembou;
									
									}else{
										if($prixautreConsu!=0 AND $prixrembouConsu!=0)
										{
											$prixPrestaRembou=$prixrembouConsu;
											
											$prixconsu=$prixautreConsu - $prixPrestaRembou;
										
										}else{
											if($prixprestationConsu!=0 AND $prixrembouConsu ==0)
											{	
												$prixconsu=$prixprestationConsu;
											}else{
												$prixconsu=$prixautreConsu;
											
											}
										}

									}
									
									$prixconsupatient=($prixconsu * $insupercentServ)/100;							
									
									$prixconsuinsu= $prixconsu - $prixconsupatient;	
									
									if($prixconsu>=1)
									{
										$TotalMedConsu=$TotalMedConsu+$prixconsu;
										$TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
										$TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;
									
									}
								
								}
							}

						}
								
							echo $TotalMedConsu;
						$medconsu .= $TotalMedConsu;
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
							$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsuInsu;											
								?>
								</td>
							</tr>
						</table>						
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">		
								<?php
												
								$TotalMedInf=0;
								$TotalMedInfPatient=0;
								$TotalMedInfInsu=0;
								
							// print_r($infArray);	
						
						if($comptMedInf!=0)
						{
							if(array_key_exists($billArray[$b]['id_bill'], $infArray))
							{
								for($n=0;$n<sizeof($infArray[$billArray[$b]['id_bill']]);$n++)
								{
									$prixprestation=$infArray[$billArray[$b]['id_bill']][$n]['prixprestation'];
									$prixrembouInf=$infArray[$billArray[$b]['id_bill']][$n]['prixrembouInf'];
									$prixautrePrestaM=$infArray[$billArray[$b]['id_bill']][$n]['prixautrePrestaM'];
									$insupercentInf=$infArray[$billArray[$b]['id_bill']][$n]['insupercentInf'];
									
									
									if($prixprestation!=0 AND $prixrembouInf!=0)
									{
										$prixPrestaRembou=$prixrembouInf;
										
										$prixinf=$prixprestation - $prixPrestaRembou;
									
									}else{
										if($prixautrePrestaM!=0 AND $prixrembouInf!=0)
										{
											$prixPrestaRembou=$prixrembouInf;
											
											$prixinf=$prixautrePrestaM - $prixPrestaRembou;
										
										}else{
											if($prixprestation!=0 AND $prixrembouInf ==0)
											{	
												$prixinf=$prixprestation;
											}else{
												$prixinf=$prixautrePrestaM;
											
											}
										}

									}
									
									$prixinfpatient=($prixinf * $insupercentInf)/100;							
									
									$prixinfinsu= $prixinf - $prixinfpatient;	
									
									if($prixinf>=1)
									{
										$TotalMedInf=$TotalMedInf+$prixinf;
										$TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
										$TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;
									
									}
								
								}
							}

						}
								
							echo $TotalMedInf;
						$medinf .= $TotalMedInf;
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedInfPatient;
							$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedInfInsu;											
								?>
								</td>
							</tr>
						</table>						
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">		
								<?php
												
								$TotalMedLabo=0;
								$TotalMedLaboPatient=0;
								$TotalMedLaboInsu=0;
								
							// print_r($laboArray);	
						
						if($comptMedLabo!=0)
						{
							
							if(array_key_exists($billArray[$b]['id_bill'], $laboArray))
							{
								for($l=0;$l<sizeof($laboArray[$billArray[$b]['id_bill']]);$l++)
								{
									$prixprestationExa=$laboArray[$billArray[$b]['id_bill']][$l]['prixprestationExa'];
									$prixrembouLabo=$laboArray[$billArray[$b]['id_bill']][$l]['prixrembouLabo'];
									$prixautreExamen=$laboArray[$billArray[$b]['id_bill']][$l]['prixautreExamen'];
									$insupercentLab=$laboArray[$billArray[$b]['id_bill']][$l]['insupercentLab'];
									
									
									if($prixprestationExa!=0 AND $prixrembouLabo!=0)
									{
										$prixPrestaRembou=$prixrembouLabo;
										
										$prixlabo=$prixprestationExa - $prixPrestaRembou;
									
									}else{
										if($prixautreExamen!=0 AND $prixrembouLabo!=0)
										{
											$prixPrestaRembou=$prixrembouLabo;
											
											$prixlabo=$prixautreExamen - $prixPrestaRembou;
										
										}else{
											if($prixprestationExa!=0 AND $prixrembouLabo ==0)
											{	
												$prixlabo=$prixprestationExa;
											}else{
												$prixlabo=$prixautreExamen;
											
											}
										}

									}
									
									$prixlabopatient=($prixlabo * $insupercentLab)/100;							
									
									$prixlaboinsu= $prixlabo - $prixlabopatient;	
									
									if($prixlabo>=1)
									{
										$TotalMedLabo=$TotalMedLabo+$prixlabo;
										$TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
										$TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
									
									}
								
								}
							}

						}
																			
							echo $TotalMedLabo;
						$medlabo .= $TotalMedLabo;
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedLaboPatient;
							$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedLaboInsu;
								?>
								</td>
							</tr>
						</table>						
					</td>
					
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">		
								<?php
												
								$TotalMedRadio=0;
								$TotalMedRadioPatient=0;
								$TotalMedRadioInsu=0;
								
							// print_r($infArray);	
						
						if($comptMedRadio!=0)
						{
							if(array_key_exists($billArray[$b]['id_bill'], $radioArray))
							{
								for($r=0;$r<sizeof($radioArray[$billArray[$b]['id_bill']]);$r++)
								{
									$prixprestationRadio=$radioArray[$billArray[$b]['id_bill']][$r]['prixprestationRadio'];
									$prixrembouRadio=$radioArray[$billArray[$b]['id_bill']][$r]['prixrembouRadio'];
									$prixautreRadio=$radioArray[$billArray[$b]['id_bill']][$r]['prixautreRadio'];
									$insupercentRad=$radioArray[$billArray[$b]['id_bill']][$r]['insupercentRad'];
									
									
									if($prixprestationRadio!=0 AND $prixrembouRadio!=0)
									{
										$prixPrestaRembou=$prixrembouRadio;
										
										$prixradio=$prixprestationRadio - $prixPrestaRembou;
									
									}else{
										if($prixautreRadio!=0 AND $prixrembouRadio!=0)
										{
											$prixPrestaRembou=$prixrembouRadio;
											
											$prixradio=$prixautreRadio - $prixPrestaRembou;
										
										}else{
											if($prixprestationRadio!=0 AND $prixrembouRadio ==0)
											{	
												$prixradio=$prixprestationRadio;
											}else{
												$prixradio=$prixautreRadio;
											
											}
										}

									}
									
									$prixradiopatient=($prixradio * $insupercentRad)/100;							
									
									$prixradioinsu= $prixradio - $prixradiopatient;	
									
									if($prixradio>=1)
									{
										$TotalMedRadio=$TotalMedRadio+$prixradio;
										$TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
										$TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
									
									}
								
								}
							}

						}
							
							echo $TotalMedRadio;
						$medradio .= $TotalMedRadio;
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedRadioPatient;
							$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedRadioInsu;												
								?>
								</td>
							</tr>
						</table>						
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">		
								<?php
												
								$TotalMedSurge=0;
								$TotalMedSurgePatient=0;
								$TotalMedSurgeInsu=0;
								
							// print_r($surgeArray);
						
						if($comptMedSurge!=0)
						{
							if(array_key_exists($billArray[$b]['id_bill'], $surgeArray))
							{
								for($n=0;$n<sizeof($surgeArray[$billArray[$b]['id_bill']]);$n++)
								{
									$prixprestation=$surgeArray[$billArray[$b]['id_bill']][$n]['prixprestationSurge'];
									$prixrembouSurge=$surgeArray[$billArray[$b]['id_bill']][$n]['prixrembouSurge'];
									$prixautrePrestaS=$surgeArray[$billArray[$b]['id_bill']][$n]['prixautrePrestaS'];
									$insupercentSurge=$surgeArray[$billArray[$b]['id_bill']][$n]['insupercentSurge'];
									
									
									if($prixprestation!=0 AND $prixrembouSurge!=0)
									{
										$prixPrestaRembou=$prixrembouSurge;
										
										$prixsurge=$prixprestation - $prixPrestaRembou;

									}else{
										if($prixautrePrestaS!=0 AND $prixrembouSurge!=0)
										{
											$prixPrestaRembou=$prixrembouSurge;
											
											$prixsurge=$prixautrePrestaS - $prixPrestaRembou;

										}else{
											if($prixprestation!=0 AND $prixrembouSurge ==0)
											{	
												$prixsurge=$prixprestation;
											}else{
												$prixsurge=$prixautrePrestaS;

											}
										}

									}
									
									$prixsurgepatient=($prixsurge * $insupercentSurge)/100;
									
									$prixsurgeinsu= $prixsurge - $prixsurgepatient;
									
									if($prixsurge>=1)
									{
										$TotalMedSurge=$TotalMedSurge+$prixsurge;
										$TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
										$TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
									
									}
								
								}
							}

						}

					$TotalMedSurgeBalance = $TotalMedSurgePatient + $TotalMedSurgeInsu;

						echo $TotalMedSurgeBalance;

						$medsurge .= $TotalMedSurgeBalance;
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;
							$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedSurgeInsu;
								?>
								</td>
							</tr>
						</table>						
					</td>
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">		
								<?php
												
								$TotalMedConsom=0;
								$TotalMedConsomPatient=0;
								$TotalMedConsomInsu=0;
								
							// print_r($medocArray);	
						
						if($comptMedConsom!=0)
						{
							if(array_key_exists($billArray[$b]['id_bill'], $consomArray))
							{
								for($c=0;$c<sizeof($consomArray[$billArray[$b]['id_bill']]);$c++)
								{
									$prixprestationConsom=$consomArray[$billArray[$b]['id_bill']][$c]['prixprestationConsom'];
									$prixrembouConsom=$consomArray[$billArray[$b]['id_bill']][$c]['prixrembouConsom'];
									$prixautreConsom=$consomArray[$billArray[$b]['id_bill']][$c]['prixautreConsom'];
									$qteConsom=$consomArray[$billArray[$b]['id_bill']][$c]['qteConsom'];
									$insupercentConsom=$consomArray[$billArray[$b]['id_bill']][$c]['insupercentConsom'];
									
									
									if($prixprestationConsom!=0 AND $prixrembouConsom!=0)
									{
										$prixPrestaRembou=$prixrembouConsom;
										
										$prixconsom=($prixprestationConsom * $qteConsom) - $prixPrestaRembou;
									
									}else{
										if($prixautreConsom!=0 AND $prixrembouConsom!=0)
										{
											$prixPrestaRembou=$prixrembouConsom;
											
											$prixconsom=($prixautreConsom * $qteConsom) - $prixPrestaRembou;
										
										}else{
											if($prixprestationConsom!=0 AND $prixrembouConsom ==0)
											{	
												$prixconsom=$prixprestationConsom * $qteConsom;
											}else{
												$prixconsom=$prixautreConsom * $qteConsom;
											
											}
										}

									}
									
									$prixconsompatient=($prixconsom * $insupercentConsom)/100;							
									
									$prixconsominsu= $prixconsom - $prixconsompatient;	
									
									if($prixconsom>=1)
									{
										$TotalMedConsom=$TotalMedConsom+$prixconsom;
										$TotalMedConsomPatient=$TotalMedConsomPatient+$prixconsompatient;
										$TotalMedConsomInsu=$TotalMedConsomInsu+$prixconsominsu;
									
									}
								
								}
							}

						}
							
							echo $TotalMedConsom;
						$medconsom .= $TotalMedConsom;
							
							$TotalDayPrice=$TotalDayPrice+$TotalMedConsom;
							$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsomPatient;
							$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsomInsu;												
								?>
								</td>
							</tr>
						</table>						
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
							
							<tr>
								<td style="text-align:center">		
								<?php
												
								$TotalMedMedoc=0;
								$TotalMedMedocPatient=0;
								$TotalMedMedocInsu=0;
								
							// print_r($medocArray);	
						
						if($comptMedMedoc!=0)
						{
							if(array_key_exists($billArray[$b]['id_bill'], $medocArray))
							{
								for($m=0;$m<sizeof($medocArray[$billArray[$b]['id_bill']]);$m++)
								{
									$prixprestationMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['prixprestationMedoc'];
									$prixrembouMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['prixrembouMedoc'];
									$prixautreMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['prixautreMedoc'];
									$qteMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['qteMedoc'];
									$insupercentMedoc=$medocArray[$billArray[$b]['id_bill']][$m]['insupercentMedoc'];
									
									
									if($prixprestationMedoc!=0 AND $prixrembouMedoc!=0)
									{
										$prixPrestaRembou=$prixrembouMedoc;
										
										$prixmedoc=($prixprestationMedoc * $qteMedoc) - $prixPrestaRembou;
									
									}else{
										if($prixautreMedoc!=0 AND $prixrembouMedoc!=0)
										{
											$prixPrestaRembou=$prixrembouMedoc;
											
											$prixmedoc=($prixautreMedoc * $qteMedoc) - $prixPrestaRembou;
										
										}else{
											if($prixprestationMedoc!=0 AND $prixrembouMedoc ==0)
											{	
												$prixmedoc=$prixprestationMedoc * $qteMedoc;
											}else{
												$prixmedoc=$prixautreMedoc * $qteMedoc;
											
											}
										}

									}
									
									$prixmedocpatient=($prixmedoc * $insupercentMedoc)/100;							
									
									$prixmedocinsu= $prixmedoc - $prixmedocpatient;	
									
									if($prixmedoc>=1)
									{
										$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
										$TotalMedMedocPatient=$TotalMedMedocPatient+$prixmedocpatient;
										$TotalMedMedocInsu=$TotalMedMedocInsu+$prixmedocinsu;
									
									}
								
								}
							}

						}
								
							echo $TotalMedMedoc;
						$medmedoc .= $TotalMedMedoc;
							
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
				$TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
					$TotalGnlMedSurgePatient = $TotalGnlMedSurgePatient + $TotalMedSurgePatient;
					$TotalGnlMedSurgeInsu = $TotalGnlMedSurgeInsu + $TotalMedSurgeInsu;
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
					$arrayGnlBillReport[$i][1]=$billArray[$b]['datebill'];
					$arrayGnlBillReport[$i][2]=$billArray[$b]['numbill'];
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
						<td colspan=9></td>
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
								echo $TotalGnlMedSurge;
								
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
				
				$reportsn=str_replace('/', '_', $sn);
				
				
				if($_GET['paVisit']=='dailyGnlBill')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlBillReportFiles/Daily/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlBillReportFiles/Daily/");</script>';
					
					createRN('GBD');
					
				}else{
					if($_GET['paVisit']=='monthlyGnlBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlBillReportFiles/Monthly/'.$reportsn.'.xlsx');
							
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlBillReportFiles/Monthly/");</script>';
						createRN('GBM');
						
					}else{
						if($_GET['paVisit']=='annualyGnlBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlBillReportFiles/Annualy/'.$reportsn.'.xlsx');
							
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlBillReportFiles/Annualy/");</script>';
							createRN('GBA');
							
						}else{
							if($_GET['paVisit']=='customGnlBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlBillReportFiles/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlBillReportFiles/Custom/");</script>';
								
								createRN('GBC');
								
							}else{
								if($_GET['paVisit']=='gnlGnlBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlBillReportFiles/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlBillReportFiles/Alltimes/");</script>';
									createRN('GBG');
								}
							}
						}
					}
				}
	
			}
			
		}
		?>
	<?php
	}
	?>

	</div>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
	
		<?php
		$footer = '

			<table style="width:100%">
				
				<tr>
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px;">
						
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