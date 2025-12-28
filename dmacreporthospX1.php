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



	if(isset($_GET['createRN']))
	{
		$createRN=$_GET['createRN'];
	}
	
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
			$sn = showRN('GHBD');
		}else{
			if($_GET['paVisit']=='monthlyGnlBill')
			{
				$sn = showRN('GHBM');
			}else{
				if($_GET['paVisit']=='annualyGnlBill')
				{
					$sn = showRN('GHBA');
				}else{
					if($_GET['paVisit']=='customGnlBill')
					{
						$sn = showRN('GHBC');
					}else{
						if($_GET['paVisit']=='gnlGnlBill')
						{
							$sn = showRN('GHBG');
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

	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
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

			if($mois<0)
			{
				$an= ($an-1).' ans	'.(12+$mois).' mois';
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
					
					<form method="post" action="dmacreporthosp.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&paVisit=<?php echo $paVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportPdf=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="dmacreporthosp.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&paVisit=<?php echo $paVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportExcel=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

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
							->setCellValue('A8', 'N�')
							->setCellValue('B8', 'Date of consultation')
							->setCellValue('C8', 'Type of consultation')
							->setCellValue('D8', 'Services')
							->setCellValue('E8', 'Nursing Care')
							->setCellValue('F8', 'Laboratory tests');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:7%; border-right: 1px solid #bbb">N�</th>
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
							
							$resultConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
							if($comptConsu!=0)
							{
								while($ligneConsu=$resultConsu->fetch())//on recupere la liste des �l�ments
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
					
					$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
					$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc, prestations p WHERE  mc.id_factureMedConsu=:idbill GROUP BY id_medconsu ORDER BY mc.id_medconsu DESC');
					$resultMedAutreConsu->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
					
					$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
				if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
				{
					if($comptMedConsu!=0)
					{
						while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des �l�ments
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
						while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())//on recupere la liste des �l�ments
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
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
					$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi, prestations p WHERE mi.id_factureMedInf=:idbill GROUP BY id_medinf ORDER BY mi.id_medinf DESC');
					$resultMedAutreInf->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreInf=$resultMedAutreInf->rowCount();
					
					$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
				if($comptMedInf!=0 or $comptMedAutreInf!=0)
				{
					if($comptMedInf!=0)
					{
						while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des �l�ments
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
						while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des �l�ments
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
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
					$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml, prestations p WHERE ml.id_factureMedLabo=:idbill GROUP BY id_medlabo ORDER BY ml.id_medlabo DESC');
					$resultMedAutreLabo->execute(array(
					'idbill'=>$ligneBillReport->id_bill
					));
					
					$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
					
					$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
				if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
				{
					if($comptMedLabo!=0)
					{
						while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des �l�ments
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
						while($ligneMedAutreLabo=$resultMedAutreInf->fetch())//on recupere la liste des �l�ments
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
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Patient Hospitalisation Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right; width:30%;">
						
					<form method="post" action="dmacreporthosp.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisit=<?php echo $paVisitgnl;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportPdf=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
						
					<form method="post" action="dmacreporthosp.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisit=<?php echo $paVisitgnl;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportExcel=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				
				<td style="text-align:right">
					
					<a href="report.php?audit=<?php echo $_SESSION['id'];?>&gnlreporthosp=ok&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
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
								->setCellValue('A6', 'N�')
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
			
			$resultGnlBillReport=$connexion->query('SELECT *FROM patients_hosp ph WHERE '.$dailydategnl.' ORDER BY dateSortie ASC');

			$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);

			$comptBillReport=$resultGnlBillReport->rowCount();
			
			if($comptBillReport!=0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A9', 'N°')
								->setCellValue('B9', 'Bill number')
								->setCellValue('C9', 'Insurance')
								->setCellValue('D9', 'Insurance card n°')
								->setCellValue('E9', 'Beneficiary\'s age')
								->setCellValue('F9', 'Gender ')
								->setCellValue('G9', 'Beneficiary\'s name')
								->setCellValue('H9', 'Principal member')
								->setCellValue('I9', 'Affiliate\'s affectation')
								->setCellValue('J9', 'Date Entrée')
								->setCellValue('K9', 'Date Sortie')
								->setCellValue('L9', 'Nbre de jours')
								->setCellValue('M9', 'Price per day (Rwf)')
								->setCellValue('N9', 'Total Price (Rwf)')
								->setCellValue('O9', 'Services')
								->setCellValue('P9', 'Nursing Care')
								->setCellValue('Q9', 'Laboratory tests')
								->setCellValue('R9', 'Medical imaging')
								->setCellValue('S9', 'Consommables')
								->setCellValue('T9', 'Medications')
								->setCellValue('U9', 'Total Amount')
								->setCellValue('V9', 'Total Patient')
								->setCellValue('W9', 'Total Insurance')
								->setCellValue('X9', 'Percent');
					
			?>
			<table style="width:100%" class="printPreview tablesorter3" cellspacing="0"> 
						
				<thead>
					<tr>
						<th style="border-right: 1px solid #bbb;text-align:center;">N°</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Bill number</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Insurance</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Insurance card n°</th>
						<th style="border-right: 1px solid #bbb;text-align:center;" colspan=2>Beneficiary's age / gender </th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Beneficiary's names</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Principal member</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's affectation</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Date Entrée</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Date Sortie</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Nbre de jours</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">P/Days CCO</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">P/Days</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Prix Total</th>
                        <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Surgery';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(99);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medical imaging';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Physiotherapy';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'P&O';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Consommables';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medications';?></th>
                        <th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?>s</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Patient</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Insurance</th>
					</tr> 
				</thead> 
				
				<tbody>
			<?php
			$TotalGnlTypeConsu=0;
			$TotalGnlTypeConsuCCO=0;
				$TotalGnlTypeConsuPatient=0;
				$TotalGnlTypeConsuInsu=0;
			$TotalGnlMedSurge=0;
			$TotalGnlMedSurgeCCO=0;
				$TotalGnlMedSurgePatient=0;
				$TotalGnlMedSurgeInsu=0;
			$TotalGnlMedInf=0;
			$TotalGnlMedInfCCO=0;
				$TotalGnlMedInfPatient=0;
				$TotalGnlMedInfInsu=0;
			$TotalGnlMedLabo=0;
			$TotalGnlMedLaboCCO=0;
				$TotalGnlMedLaboPatient=0;
				$TotalGnlMedLaboInsu=0;
			$TotalGnlMedRadio=0;
			$TotalGnlMedRadioCCO=0;
				$TotalGnlMedRadioPatient=0;
				$TotalGnlMedRadioInsu=0;
			$TotalGnlMedKine=0;
			$TotalGnlMedKineCCO=0;
				$TotalGnlMedKinePatient=0;
				$TotalGnlMedKineInsu=0;
			$TotalGnlMedOrtho=0;
			$TotalGnlMedOrthoCCO=0;
				$TotalGnlMedOrthoPatient=0;
				$TotalGnlMedOrthoInsu=0;
			$TotalGnlMedConsom=0;
			$TotalGnlMedConsomCCO=0;
				$TotalGnlMedConsomPatient=0;
				$TotalGnlMedConsomInsu=0;
			$TotalGnlMedMedoc=0;
			$TotalGnlMedMedocCCO=0;
				$TotalGnlMedMedocPatient=0;
				$TotalGnlMedMedocInsu=0;
            $TotalGnlMedConsu=0;
            $TotalGnlMedConsuCCO=0;
                $TotalGnlMedConsuPatient=0;
                $TotalGnlMedConsuInsu=0;
			$TotalGnlPrice=0;
			$TotalGnlPriceCCO=0;
				$TotalGnlPricePatient=0;
				$TotalGnlPriceInsu=0;
			
			$i=0;
			$compteur=1;
			
				while($ligneGnlBillReport=$resultGnlBillReport->fetch())
				{
					$TotalDayPrice=0;
					$TotalDayPriceCCO=0;
					$TotalDayPricePatient=0;
					$TotalDayPriceInsu=0;
					
					$consult ="";
					$medconsu ="";
					$medsurge ="";
					$medinf ="";
					$medlabo ="";
					$medradio ="";
					$medkine ="";
					$medortho ="";
					$medconsom ="";
					$medmedoc ="";
					
					
					$idassu = $ligneGnlBillReport->id_assuHosp;

					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();

					for($h=1;$h<=$assuCount;$h++)
					{
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassu
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
							$nomassu=$ligneNomAssu->nomassurance;
						}
					}

					$vouchernumHosp = $ligneGnlBillReport->vouchernumHosp;
					$carteassuid = $ligneGnlBillReport->idcardbillHosp;
					$insupercent = $ligneGnlBillReport->insupercent_hosp;
					$numpolice = $ligneGnlBillReport->numpolicebillHosp;
					$adherent =$ligneGnlBillReport->adherentbillHosp;
			?>
			
				<tr style="text-align:center;">
					<td style="text-align:center;"><?php echo $compteur;?></td>
					<td style="text-align:center;"><?php echo $ligneGnlBillReport->id_factureHosp;?></td>
					<td style="text-align:center;"><?php echo $nomassu;?><br/>(<?php echo '<span style="font-weight:bold">'.$insupercent.'</span>%';?>)</td>
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
						$resultPatient->execute(array(
						'operation'=>$ligneGnlBillReport->numero
						));
				
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())
						{
							$fullname = $lignePatient->full_name;
							$numero = $lignePatient->numero;
							$sexe = $lignePatient->sexe;
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
							
							
							// echo '<td style="text-align:center;">'.$vouchernumHosp.'</td>';	
							echo '<td style="text-align:center;">'.$carteassuid.'</td>';	
							// echo '<td style="text-align:center;">'.$numpolice.'</td>';	
							echo '<td style="text-align:center;">'.$age.'</td>';
							echo '<td style="text-align:center;">'.$sexe.'</td>';
							echo '<td style="text-align:center;font-weight:bold;">'.$fullname.'<br/>('.$numero.')</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$adherent.'</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$profession.'</td>';
						}else{
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
						}
						
					?>
					
					<td><?php echo $ligneGnlBillReport->dateEntree.' à '.$ligneGnlBillReport->heureEntree;?></td>
					<td style="font-weight:bold;"><?php echo $ligneGnlBillReport->dateSortie;?></td>
			
					<td style="text-align:center;">
					<?php
					
					$dateIn=strtotime($ligneGnlBillReport->dateEntree);
					$dateOut=strtotime($ligneGnlBillReport->dateSortie);
					
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
					$prixroom=$ligneGnlBillReport->prixroom;
					$prixroomCCO=$ligneGnlBillReport->prixroomCCO;

                    $balance=$prixroom*$nbrejrs;
                    $balanceCCO=$prixroomCCO*$nbrejrs;

                    $prixconsultpatient=($balance * $ligneGnlBillReport->insupercent_hosp)/100;
                    $prixconsultinsu= $balance - $prixconsultpatient;
                    ?>

                    <td>
                    <?php
                        echo $prixroomCCO;
					?>									
					</td>

					<td>
					<?php
                        echo $prixroom;
					?>
					</td>
					
					<td style="text-align:center;">
								
						<?php
                        $roomBalance = ($balanceCCO - $balance) + ($prixconsultpatient + $prixconsultinsu);

						echo $roomBalance;
							
			
						$TotalTypeConsu=0;
						$TotalTypeConsuCCO=0;
						$TotalTypeConsuPatient=0;
						$TotalTypeConsuInsu=0;
						


						$TotalTypeConsu=$TotalTypeConsu+$balance;
						$TotalTypeConsuCCO=$TotalTypeConsuCCO+$balanceCCO;
						$TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
						$TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
				
	
						$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
						$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalTypeConsuCCO;
						$TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
						$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalTypeConsuInsu;
					?>
					
					</td>
										
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
						<?php
						
					$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms, '.$presta_assu.' p WHERE ms.id_prestationSurge=p.id_prestation AND ms.id_factureMedSurge=:idbill ORDER BY ms.id_medsurge DESC');
					$resultMedSurge->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedSurge=$resultMedSurge->rowCount();
					
					$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);
					
					
					$resultMedAutreSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.id_prestationSurge IS NULL AND ms.id_factureMedSurge=:idbill ORDER BY ms.id_medsurge DESC');
					$resultMedAutreSurge->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedAutreSurge=$resultMedAutreSurge->rowCount();
					
					$resultMedAutreSurge->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedSurge=0;					
					$TotalMedSurgeCCO=0;
					$TotalMedSurgePatient=0;
					$TotalMedSurgeInsu=0;	
					
				if($comptMedSurge!=0 or $comptMedAutreSurge!=0)
				{
					if($comptMedSurge!=0)
					{
						while($ligneMedSurge=$resultMedSurge->fetch())
						{
							$qteSurge=$ligneMedSurge->qteSurge;
							
							if($ligneMedSurge->prixprestationSurge!=0 AND $ligneMedSurge->prixrembouSurge!=0)
							{
								$prixPrestaRembou=$ligneMedSurge->prixrembouSurge;
								
								$prixsurge=($ligneMedSurge->prixprestationSurge * $qteSurge) - $prixPrestaRembou;
								$prixsurgeCCO=($ligneMedSurge->prixprestationSurgeCCO * $qteSurge) - $prixPrestaRembou;

							}else{
								$prixsurge=$ligneMedSurge->prixprestationSurge * $qteSurge;
								$prixsurgeCCO=$ligneMedSurge->prixprestationSurgeCCO * $qteSurge;

							}
							
							$prixsurgepatient=($prixsurge * $ligneMedSurge->insupercentSurge)/100;							
							
							$prixsurgeinsu= $prixsurge - $prixsurgepatient;	
							
							if($prixsurge>=1)
							{
						?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedSurge->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixsurgeBalance = ($prixsurgeCCO - $prixsurge) + ($prixsurgepatient + $prixsurgeinsu);

									echo $prixsurgeBalance;
								?>
								</td>
							</tr>
						<?php
							
								$medsurge .= ''.$ligneMedSurge->nompresta.' ('.$prixsurgeBalance.' Rwf), ';
							
								$TotalMedSurge=$TotalMedSurge+$prixsurge;
								$TotalMedSurgeCCO=$TotalMedSurgeCCO+$prixsurgeCCO;
								$TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
								$TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
							}
						}
					}
					
					if($comptMedAutreSurge!=0)
					{
						while($ligneMedAutreSurge=$resultMedAutreSurge->fetch())
						{
							$qteSurge=$ligneMedAutreSurge->qteSurge;
						
							if($ligneMedAutreSurge->prixautrePrestaS!=0 AND $ligneMedAutreSurge->prixrembouSurge!=0)
							{
								$prixPrestaRembou=$ligneMedAutreSurge->prixrembouSurge;
								
								$prixsurge=($ligneMedAutreSurge->prixautrePrestaS * $qteSurge) - $prixPrestaRembou;
								$prixsurgeCCO=($ligneMedAutreSurge->prixautrePrestaSCCO * $qteSurge) - $prixPrestaRembou;

							}else{
								$prixsurge=$ligneMedAutreSurge->prixautrePrestaS * $qteSurge;
								$prixsurgeCCO=$ligneMedAutreSurge->prixautrePrestaSCCO * $qteSurge;

							}
							
							$prixsurgepatient=($prixsurge * $ligneMedAutreSurge->insupercentSurge)/100;			
							$prixsurgeinsu= $prixsurge - $prixsurgepatient;								
							
							if($prixsurge>=1)
							{
						?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php								
									echo $ligneMedAutreSurge->autrePrestaS;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixsurgeBalance = ($prixsurgeCCO - $prixsurge) + ($prixsurgepatient + $prixsurgeinsu);

									echo $prixsurgeBalance;
								?>
								</td>
							</tr>
							<?php
								
								$medsurge .= ''.$ligneMedAutreSurge->autrePrestaM.' ('.$prixsurgeBalance.' Rwf), ';
							
								$TotalMedSurge=$TotalMedSurge+$prixsurge;
								$TotalMedSurgeCCO=$TotalMedSurgeCCO+$prixsurgeCCO;
								$TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
								$TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
							}
						}
					}

				}				
				?>
								
							<tr>
								<td style="text-align:center">
								<?php
                                $TotalMedSurgeBalance = ($TotalMedSurgeCCO - $TotalMedSurge) + ($TotalMedSurgePatient + $TotalMedSurgeInsu);

									echo $TotalMedSurgeBalance;

									$TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedSurgeCCO;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedSurgeInsu;
								?>
								</td>
							</tr>
						</table>
					
					</td>
										
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
						<?php
						
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, '.$presta_assu.' p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedInf->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedInf=$resultMedInf->rowCount();
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
					
					
					$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.id_prestation IS NULL AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedAutreInf->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedAutreInf=$resultMedAutreInf->rowCount();
					
					$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedInf=0;					
					$TotalMedInfCCO=0;
					$TotalMedInfPatient=0;
					$TotalMedInfInsu=0;					
								
				if($comptMedInf!=0 or $comptMedAutreInf!=0)
				{
					if($comptMedInf!=0)
					{
						while($ligneMedInf=$resultMedInf->fetch())
						{
							$qteInf=$ligneMedInf->qteInf;
						
							if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
							{
								$prixPrestaRembou=$ligneMedInf->prixrembouInf;
								
								$prixinf=($ligneMedInf->prixprestation * $qteInf) - $prixPrestaRembou;
								$prixinfCCO=($ligneMedInf->prixprestationCCO * $qteInf) - $prixPrestaRembou;

							}else{
								$prixinf=$ligneMedInf->prixprestation * $qteInf;
								$prixinfCCO=$ligneMedInf->prixprestationCCO * $qteInf;

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
                                $prixinfBalance = ($prixinfCCO - $prixinf) + ($prixinfpatient + $prixinfinsu);

									echo $prixinfBalance;
								?>
								</td>
							</tr>
						<?php
							
								$medinf .= ''.$ligneMedInf->nompresta.' ('.$prixinfBalance.' Rwf), ';
							
								$TotalMedInf=$TotalMedInf+$prixinf;
								$TotalMedInfCCO=$TotalMedInfCCO+$prixinfCCO;
								$TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
								$TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;
							}
						}
					}
					
					if($comptMedAutreInf!=0)
					{
						while($ligneMedAutreInf=$resultMedAutreInf->fetch())
						{
							$qteInf=$ligneMedAutreInf->qteInf;
							
							if($ligneMedAutreInf->prixautrePrestaM!=0 AND $ligneMedAutreInf->prixrembouInf!=0)
							{
								$prixPrestaRembou=$ligneMedAutreInf->prixrembouInf;
								
								$prixinf=($ligneMedAutreInf->prixautrePrestaM * $qteInf) - $prixPrestaRembou;
								$prixinfCCO=($ligneMedAutreInf->prixautrePrestaMCCO * $qteInf) - $prixPrestaRembou;

							}else{
								$prixinf=$ligneMedAutreInf->prixautrePrestaM * $qteInf;
								$prixinfCCO=$ligneMedAutreInf->prixautrePrestaMCCO * $qteInf;

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
                                $prixinfBalance = ($prixinfCCO - $prixinf) + ($prixinfpatient + $prixinfinsu);

									echo $prixinfBalance;
								?>
								</td>
							</tr>
							<?php
								
								$medinf .= ''.$ligneMedAutreInf->autrePrestaM.' ('.$prixinfBalance.' Rwf), ';
							
								$TotalMedInf=$TotalMedInf+$prixinf;
								$TotalMedInfCCO=$TotalMedInfCCO+$prixinfCCO;
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
                                $TotalMedInfBalance = ($TotalMedInfCCO - $TotalMedInf) + ($TotalMedInfPatient + $TotalMedInfInsu);

									echo $TotalMedInfBalance;

									$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedInfCCO;
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
						
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, '.$presta_assu.' p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedLabo=$resultMedLabo->rowCount();
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
						
						$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_prestationExa IS NULL AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedAutreLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
						
						$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedLabo=0;
						$TotalMedLaboCCO=0;
						$TotalMedLaboPatient=0;
						$TotalMedLaboInsu=0;
						
				if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
				{
					if($comptMedLabo!=0)
					{
						while($ligneMedLabo=$resultMedLabo->fetch())
						{
							$qteLab=$ligneMedLabo->qteLab;
							
							if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
							{
								$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
												
								$prixlabo=($ligneMedLabo->prixprestationExa * $qteLab) - $prixPrestaRembou;
								$prixlaboCCO=($ligneMedLabo->prixprestationExaCCO * $qteLab) - $prixPrestaRembou;

							}else{
								$prixlabo=$ligneMedLabo->prixprestationExa * $qteLab;
								$prixlaboCCO=$ligneMedLabo->prixprestationExaCCO * $qteLab;

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
                                $prixlaboBalance = ($prixlaboCCO - $prixlabo) + ($prixlabopatient + $prixlaboinsu);
									echo $prixlaboBalance;
								?>
								</td>
							</tr>
							<?php
							
								$medlabo .= ''.$ligneMedLabo->nompresta.' ('.$prixlaboBalance.' Rwf), ';
								
								$TotalMedLabo=$TotalMedLabo+$prixlabo;
								$TotalMedLaboCCO=$TotalMedLaboCCO+$prixlaboCCO;
								$TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
								$TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
							}
						}
					}
					
					if($comptMedAutreLabo!=0)
					{
						while($ligneMedAutreLabo=$resultMedAutreLabo->fetch())
						{
							$qteLab=$ligneMedAutreLabo->qteLab;
							
							if($ligneMedAutreLabo->prixautreExamen!=0 AND $ligneMedAutreLabo->prixrembouLabo!=0)
							{
								$prixPrestaRembou=$ligneMedAutreLabo->prixrembouLabo;
								
								$prixlabo=($ligneMedAutreLabo->prixautreExamen * $qteLab) - $prixPrestaRembou;
								$prixlaboCCO=($ligneMedAutreLabo->prixautreExamenCCO * $qteLab) - $prixPrestaRembou;

							}else{
								$prixlabo=$ligneMedAutreLabo->prixautreExamen * $qteLab;
								$prixlaboCCO=$ligneMedAutreLabo->prixautreExamenCCO * $qteLab;

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
                                $prixlaboBalance = ($prixlaboCCO - $prixlabo) + ($prixlabopatient + $prixlaboinsu);

									echo $prixlaboBalance;
								?>
								</td>
							</tr>
				<?php
						
								$medlabo .= ''.$ligneMedAutreLabo->autreExamen.' ('.$prixlaboBalance.' Rwf), ';
								
								$TotalMedLabo=$TotalMedLabo+$prixlabo;
								$TotalMedLaboCCO=$TotalMedLaboCCO+$prixlaboCCO;
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
                                $TotalMedLaboBalance = ($TotalMedLaboCCO - $TotalMedLabo) + ($TotalMedLaboPatient + $TotalMedLaboInsu);

									echo $TotalMedLaboBalance;
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedLaboCCO;
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
							
					$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, '.$presta_assu.' p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
					$resultMedRadio->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedRadio=$resultMedRadio->rowCount();
					
					$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);
					
					$resultMedAutreRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.id_prestationRadio IS NULL AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
					$resultMedAutreRadio->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedAutreRadio=$resultMedAutreRadio->rowCount();
					
					$resultMedAutreRadio->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedRadio=0;
					$TotalMedRadioCCO=0;
					$TotalMedRadioPatient=0;
					$TotalMedRadioInsu=0;
					
				if($comptMedRadio!=0 or $comptMedAutreRadio!=0)
				{
					if($comptMedRadio!=0)
					{
						while($ligneMedRadio=$resultMedRadio->fetch())
						{
							$qteRad=$ligneMedRadio->qteRad;
							
							if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
							{
								$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
								
								$prixradio=($ligneMedRadio->prixprestationRadio * $qteRad) - $prixPrestaRembou;
								$prixradioCCO=($ligneMedRadio->prixprestationRadioCCO * $qteRad) - $prixPrestaRembou;

							}else{
								$prixradio=$ligneMedRadio->prixprestationRadio * $qteRad;
								$prixradioCCO=$ligneMedRadio->prixprestationRadioCCO * $qteRad;

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
                                $prixradioBalance = ($prixradioCCO - $prixradio) + ($prixradiopatient + $prixradioinsu);

									echo $prixradioBalance;
								?>
								</td>
							</tr>
					<?php
					
								$medradio .= ''.$ligneMedRadio->nompresta.' ('.$prixradioBalance.' Rwf), ';
								
								$TotalMedRadio=$TotalMedRadio+$prixradio;
								$TotalMedRadioCCO=$TotalMedRadioCCO+$prixradioCCO;
								$TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
								
								$TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
							}
						}
					}
					
					if($comptMedAutreRadio!=0)
					{
						while($ligneMedAutreRadio=$resultMedAutreRadio->fetch())//on recupere la liste des �l�ments
						{
							$qteRad=$ligneMedAutreRadio->qteRad;
							
							if($ligneMedAutreRadio->prixautreRadio!=0 AND $ligneMedAutreRadio->prixrembouRadio!=0)
							{
								$prixPrestaRembou=$ligneMedAutreRadio->prixrembouRadio;
								
								$prixradio=($ligneMedAutreRadio->prixautreRadio * $qteRad) - $prixPrestaRembou;
								$prixradioCCO=($ligneMedAutreRadio->prixautreRadioCCO * $qteRad) - $prixPrestaRembou;

							}else{
								$prixradio=$ligneMedAutreRadio->prixautreRadio * $qteRad;
								$prixradioCCO=$ligneMedAutreRadio->prixautreRadioCCO * $qteRad;

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
                                $prixradioBalance = ($prixradioCCO - $prixradio) + ($prixradiopatient + $prixradioinsu);

                                echo $prixradioBalance;
								?>
								</td>
							</tr>
				<?php
					
								$medradio .= ''.$ligneMedAutreRadio->autreRadio.' ('.$prixradioBalance.' Rwf), ';
								
								$TotalMedRadio=$TotalMedRadio+$prixradio;
								$TotalMedRadioCCO=$TotalMedRadioCCO+$prixradioCCO;
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
                                $TotalMedRadioBalance = ($TotalMedRadioCCO - $TotalMedRadio) + ($TotalMedRadioPatient + $TotalMedRadioInsu);

									echo $TotalMedRadioBalance;
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedRadioCCO;
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
							
					$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk, '.$presta_assu.' p WHERE mk.id_prestationKine=p.id_prestation AND mk.id_factureMedKine=:idbill ORDER BY mk.id_medkine DESC');
					$resultMedKine->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedKine=$resultMedKine->rowCount();
					
					$resultMedKine->setFetchMode(PDO::FETCH_OBJ);
					
					$resultMedAutreKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk WHERE mk.id_prestationKine IS NULL AND mk.id_factureMedKine==:idbill ORDER BY mk.id_medkine DESC');
					$resultMedAutreKine->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedAutreKine=$resultMedAutreKine->rowCount();
					
					$resultMedAutreKine->setFetchMode(PDO::FETCH_OBJ);
					
					// echo 'SELECT *FROM med_kine_hosp mk, '.$presta_assu.' p WHERE mk.id_prestationKine=p.id_prestation AND mk.id_factureMedKine=\''.$ligneGnlBillReport->id_factureHosp.'\' ORDER BY mk.id_medkine DESC';
					
					$TotalMedKine=0;
					$TotalMedKineCCO=0;
					$TotalMedKinePatient=0;
					$TotalMedKineInsu=0;
					
				if($comptMedKine!=0 OR $comptMedAutreKine!=0)
				{
				
					if($comptMedKine!=0)
					{
						while($ligneMedKine=$resultMedKine->fetch())
						{
							$qteKine=$ligneMedKine->qteKine;
							
							if($ligneMedKine->prixprestationKine!=0 AND $ligneMedKine->prixrembouKine!=0)
							{
								$prixPrestaRembou=$ligneMedKine->prixrembouKine;
								
								$prixkine=($ligneMedKine->prixprestationKine * $qteKine) - $prixPrestaRembou;
								$prixkineCCO=($ligneMedKine->prixprestationKineCCO * $qteKine) - $prixPrestaRembou;

							}else{								
								$prixkine=$ligneMedKine->prixprestationKine * $qteKine;
								$prixkineCCO=$ligneMedKine->prixprestationKineCCO * $qteKine;

							}
							
							$prixkinepatient=($prixkine * $ligneMedKine->insupercentKine)/100;							
							
							$prixkineinsu= $prixkine - $prixkinepatient;	
							
							if($prixkine>=1)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedKine->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixkineBalance = ($prixkineCCO - $prixkine) + ($prixkinepatient + $prixkineinsu);

									echo $prixkineBalance;
								?>
								</td>
							</tr>
					<?php
					
								$medkine .= ''.$ligneMedKine->nompresta.' ('.$prixkineBalance.' Rwf), ';
								
								$TotalMedKine=$TotalMedKine+$prixkine;
								$TotalMedKineCCO=$TotalMedKineCCO+$prixkineCCO;
								$TotalMedKinePatient=$TotalMedKinePatient+$prixkinepatient;
								
								$TotalMedKineInsu=$TotalMedKineInsu+$prixkineinsu;
							}
						}
					}
					
					if($comptMedAutreKine!=0)
					{
						while($ligneMedAutreKine=$resultMedAutreKine->fetch())//on recupere la liste des �l�ments
						{
							$qteKine=$ligneMedAutreKine->qteKine;
							
							if($ligneMedAutreKine->prixautrePrestaK!=0 AND $ligneMedAutreKine->prixrembouKine!=0)
							{
								$prixPrestaRembou=$ligneMedAutreKine->prixrembouKine;
								
								$prixkine=($ligneMedAutreKine->prixautrePrestaK * $qteKine) - $prixPrestaRembou;
								$prixkineCCO=($ligneMedAutreKine->prixautrePrestaKCCO * $qteKine) - $prixPrestaRembou;

							}else{
								$prixkine=$ligneMedAutreKine->prixautrePrestaK * $qteKine;
								$prixkineCCO=$ligneMedAutreKine->prixautrePrestaKCCO * $qteKine;

							}
							
							$prixkinepatient=($prixkine * $ligneMedAutreKine->insupercentKine)/100;							
							
							$prixkineinsu= $prixkine - $prixkinepatient;	
							
							if($prixkine>=1)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php								
									echo $ligneMedAutreKine->autreKine;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixkineBalance = ($prixkineCCO - $prixkine) + ($prixkinepatient + $prixkineinsu);

                                echo $prixkineBalance;
								?>
								</td>
							</tr>
				<?php
					
								$medkine .= ''.$ligneMedAutreKine->autreKine.' ('.$prixkineBalance.' Rwf), ';
								
								$TotalMedKine=$TotalMedKine+$prixkine;
								$TotalMedKineCCO=$TotalMedKineCCO+$prixkineCCO;
								$TotalMedKinePatient=$TotalMedKinePatient+$prixkinepatient;
								
								$TotalMedKineInsu=$TotalMedKineInsu+$prixkineinsu;
							}
						}
					}

				}					
				?>										
							<tr>
								<td style="text-align:center" colspan=2>
								<?php
                                $TotalMedKineBalance = ($TotalMedKineCCO - $TotalMedKine) + ($TotalMedKinePatient + $TotalMedKineInsu);

									echo $TotalMedKineBalance;
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedKine;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedKineCCO;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedKinePatient;

									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedKineInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td>
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

					<?php
							
					$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, '.$presta_assu.' p WHERE mo.id_prestationOrtho=p.id_prestation AND mo.id_factureMedOrtho=:idbill ORDER BY mo.id_medortho DESC');
					$resultMedOrtho->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedOrtho=$resultMedOrtho->rowCount();
					
					$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);
					
					$resultMedAutreOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.id_prestationOrtho IS NULL AND mo.id_factureMedOrtho=:idbill ORDER BY mo.id_medortho DESC');
					$resultMedAutreOrtho->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedAutreOrtho=$resultMedAutreOrtho->rowCount();
					
					$resultMedAutreOrtho->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedOrtho=0;
					$TotalMedOrthoCCO=0;
					$TotalMedOrthoPatient=0;
					$TotalMedOrthoInsu=0;
					
				if($comptMedOrtho!=0 or $comptMedAutreOrtho!=0)
				{
					if($comptMedOrtho!=0)
					{
						while($ligneMedOrtho=$resultMedOrtho->fetch())
						{
							$qteOrtho=$ligneMedOrtho->qteOrtho;
							
							if($ligneMedOrtho->prixprestationOrtho!=0 AND $ligneMedOrtho->prixrembouOrtho!=0)
							{
								$prixPrestaRembou=$ligneMedOrtho->prixrembouOrtho;
								
								$prixortho=($ligneMedOrtho->prixprestationOrtho * $qteOrtho) - $prixPrestaRembou;
								$prixorthoCCO=($ligneMedOrtho->prixprestationOrthoCCO * $qteOrtho) - $prixPrestaRembou;

							}else{
								$prixortho=$ligneMedOrtho->prixprestationOrtho * $qteOrtho;
								$prixorthoCCO=$ligneMedOrtho->prixprestationOrthoCCO * $qteOrtho;

							}
							
							$prixorthopatient=($prixortho * $ligneMedOrtho->insupercentOrtho)/100;							
							
							$prixorthoinsu= $prixortho - $prixorthopatient;	
							
							if($prixortho>=1)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php
									echo $ligneMedOrtho->nompresta;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixorthoBalance = ($prixorthoCCO - $prixortho) + ($prixorthopatient + $prixorthoinsu);

									echo $prixorthoBalance;
								?>
								</td>
							</tr>
					<?php
					
								$medortho .= ''.$ligneMedOrtho->nompresta.' ('.$prixorthoBalance.' Rwf), ';
								
								$TotalMedOrtho=$TotalMedOrtho+$prixortho;
								$TotalMedOrthoCCO=$TotalMedOrthoCCO+$prixorthoCCO;
								$TotalMedOrthoPatient=$TotalMedOrthoPatient+$prixorthopatient;
								
								$TotalMedOrthoInsu=$TotalMedOrthoInsu+$prixorthoinsu;
							}
						}
					}
					
					if($comptMedAutreOrtho!=0)
					{
						while($ligneMedAutreOrtho=$resultMedAutreOrtho->fetch())//on recupere la liste des �l�ments
						{
							$qteOrtho=$ligneMedAutreOrtho->qteOrtho;
							
							if($ligneMedAutreOrtho->prixautrePrestaO!=0 AND $ligneMedAutreOrtho->prixrembouOrtho!=0)
							{
								$prixPrestaRembou=$ligneMedAutreOrtho->prixrembouOrtho;
								
								$prixortho=($ligneMedAutreOrtho->prixautrePrestaO * $qteOrtho) - $prixPrestaRembou;
								$prixorthoCCO=($ligneMedAutreOrtho->prixautrePrestaOCCO * $qteOrtho) - $prixPrestaRembou;

							}else{
								$prixortho=$ligneMedAutreOrtho->prixautrePrestaO * $qteOrtho;
								$prixorthoCCO=$ligneMedAutreOrtho->prixautrePrestaOCCO * $qteOrtho;

							}
							
							$prixorthopatient=($prixortho * $ligneMedAutreOrtho->insupercentOrtho)/100;							
							
							$prixorthoinsu= $prixortho - $prixorthopatient;	
							
							if($prixortho>=1)
							{
					?>
							<tr style="display:none">
								<td style="text-align:center">
								<?php								
									echo $ligneMedAutreOrtho->autreOrtho;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixorthoBalance = ($prixorthoCCO - $prixortho) + ($prixorthopatient + $prixorthoinsu);

                                echo $prixorthoBalance;
								?>
								</td>
							</tr>
				<?php
					
								$medortho .= ''.$ligneMedAutreOrtho->autreOrtho.' ('.$prixorthoBalance.' Rwf), ';
								
								$TotalMedOrtho=$TotalMedOrtho+$prixortho;
								$TotalMedOrthoCCO=$TotalMedOrthoCCO+$prixorthoCCO;
								$TotalMedOrthoPatient=$TotalMedOrthoPatient+$prixorthopatient;
								
								$TotalMedOrthoInsu=$TotalMedOrthoInsu+$prixorthoinsu;
							}
						}
					}

				}					
				?>										
							<tr>
								<td style="text-align:center" colspan=2>
								<?php
                                $TotalMedOrthoBalance = ($TotalMedOrthoCCO - $TotalMedOrtho) + ($TotalMedOrthoPatient + $TotalMedOrthoInsu);

									echo $TotalMedOrthoBalance;
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedOrtho;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedOrthoCCO;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedOrthoPatient;

									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedOrthoInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td>
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

					<?php
							
					$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, '.$presta_assu.' p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
					$resultMedConsom->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedConsom=$resultMedConsom->rowCount();
					
					$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);
					
					$resultMedAutreConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.id_prestationConsom IS NULL AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
					$resultMedAutreConsom->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedAutreConsom=$resultMedAutreConsom->rowCount();
					
					$resultMedAutreConsom->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedConsom=0;
					$TotalMedConsomCCO=0;
					$TotalMedConsomPatient=0;
					$TotalMedConsomInsu=0;
					
				if($comptMedConsom!=0 or $comptMedAutreConsom!=0)
				{
					if($comptMedConsom!=0)
					{
						while($ligneMedConsom=$resultMedConsom->fetch())
						{
							$qteConsom=$ligneMedConsom->qteConsom;
							
							if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
							{
								$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
								
								$prixconsom=($ligneMedConsom->prixprestationConsom * $qteConsom) - $prixPrestaRembou;
								$prixconsomCCO=($ligneMedConsom->prixprestationConsomCCO * $qteConsom) - $prixPrestaRembou;

							}else{
								$prixconsom=$ligneMedConsom->prixprestationConsom * $qteConsom;
								$prixconsomCCO=$ligneMedConsom->prixprestationConsomCCO * $qteConsom;

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
									echo $qteConsom;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixconsomBalance = ($prixconsomCCO - $prixconsom) + ($prixconsompatient + $prixconsominsu);

									echo $prixconsomBalance;
								?>
								</td>
							</tr>
					<?php
					
								$medconsom .= ''.$ligneMedConsom->nompresta.' ('.$prixconsomBalance.' Rwf), ';
								
								$TotalMedConsom=$TotalMedConsom+$prixconsom;
								$TotalMedConsomCCO=$TotalMedConsomCCO+$prixconsomCCO;
								$TotalMedConsomPatient=$TotalMedConsomPatient + $prixconsompatient;
								
								$TotalMedConsomInsu=$TotalMedConsomInsu + $prixconsominsu;
							}
						}
					}
					
					if($comptMedAutreConsom!=0)
					{
						while($ligneMedAutreConsom=$resultMedAutreConsom->fetch())
						{
							$qteConsom=$ligneMedAutreConsom->qteConsom;
							
							if($ligneMedAutreConsom->prixautreConsom!=0 AND $ligneMedAutreConsom->prixrembouConsom!=0)
							{
								$prixPrestaRembou=$ligneMedAutreConsom->prixrembouConsom;
								
								$prixconsom=($ligneMedAutreConsom->prixautreConsom * $qteConsom) - $prixPrestaRembou;
								$prixconsomCCO=($ligneMedAutreConsom->prixautreConsomCCO * $qteConsom) - $prixPrestaRembou;

							}else{
								$prixconsom=$ligneMedAutreConsom->prixautreConsom * $qteConsom;
								$prixconsomCCO=$ligneMedAutreConsom->prixautreConsomCCO * $qteConsom;

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
									echo $qteConsom;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixconsomBalance = ($prixconsomCCO - $prixconsom) + ($prixconsompatient + $prixconsominsu);

                                echo $prixconsomBalance;
								?>
								</td>
							</tr>
				<?php
							
								$medconsom .= ''.$ligneMedAutreConsom->autreConsom.' ('.$prixconsomBalance.' Rwf), ';
								
								$TotalMedConsom=$TotalMedConsom+$prixconsom;
								$TotalMedConsomCCO=$TotalMedConsomCCO+$prixconsomCCO;
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
                                $TotalMedConsomBalance = ($TotalMedConsomCCO - $TotalMedConsom) + ($TotalMedConsomPatient + $TotalMedConsomInsu);

									echo $TotalMedConsomBalance;
									
									$TotalDayPrice=$TotalDayPrice + $TotalMedConsom;
									$TotalDayPriceCCO=$TotalDayPriceCCO + $TotalMedConsomCCO;
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
							
					$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, '.$presta_assu.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
					$resultMedMedoc->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedMedoc=$resultMedMedoc->rowCount();
					
					$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);
					
					$resultMedAutreMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.id_prestationMedoc IS NULL AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
					$resultMedAutreMedoc->execute(array(
					'idbill'=>$ligneGnlBillReport->id_factureHosp
					));
					
					$comptMedAutreMedoc=$resultMedAutreMedoc->rowCount();
					
					$resultMedAutreMedoc->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedMedoc=0;
					$TotalMedMedocCCO=0;
					$TotalMedMedocPatient=0;
					$TotalMedMedocInsu=0;

					
				if($comptMedMedoc!=0 or $comptMedAutreMedoc!=0)
				{
					if($comptMedMedoc!=0)
					{						
						while($ligneMedMedoc=$resultMedMedoc->fetch())
						{
							$qteMedoc=$ligneMedMedoc->qteMedoc;
							
							if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
							{
								$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
			
								$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $qteMedoc) - $prixPrestaRembou;
								$prixmedocCCO=($ligneMedMedoc->prixprestationMedocCCO * $qteMedoc) - $prixPrestaRembou;

							}else{
								$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $qteMedoc;
								$prixmedocCCO=$ligneMedMedoc->prixprestationMedocCCO * $qteMedoc;

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
									echo $qteMedoc;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixmedocBalance = ($prixmedocCCO - $prixmedoc) + ($prixmedocpatient + $prixmedocinsu);
									echo $prixmedocBalance;
								?>
								</td>
							</tr>
					<?php
					
								$medmedoc .= ''.$ligneMedMedoc->nompresta.' ('.$prixmedocBalance.' Rwf), ';
								
								$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								$TotalMedMedocCCO=$TotalMedMedocCCO+$prixmedocCCO;
								$TotalMedMedocPatient=$TotalMedMedocPatient + $prixmedocpatient;
								$TotalMedMedocInsu= $TotalMedMedocInsu + $prixmedocinsu;
								
							}
						}
					}
					
					if($comptMedAutreMedoc!=0)
					{
						while($ligneMedAutreMedoc=$resultMedAutreMedoc->fetch())
						{
							$qteMedoc=$ligneMedAutreMedoc->qteMedoc;
							
							if($ligneMedAutreMedoc->prixautreMedoc!=0 AND $ligneMedAutreMedoc->prixrembouMedoc!=0)
							{
								$prixPrestaRembou=$ligneMedAutreMedoc->prixrembouMedoc;
			
								$prixmedoc=($ligneMedAutreMedoc->prixautreMedoc * $qteMedoc) - $prixPrestaRembou;
								$prixmedocCCO=($ligneMedAutreMedoc->prixautreMedocCCO * $qteMedoc) - $prixPrestaRembou;

							}else{
								$prixmedoc=$ligneMedAutreMedoc->prixautreMedoc * $qteMedoc;
								$prixmedocCCO=$ligneMedAutreMedoc->prixautreMedocCCO * $qteMedoc;

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
									echo $qteMedoc;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
                                $prixmedocBalance = ($prixmedocCCO - $prixmedoc) + ($prixmedocpatient + $prixmedocinsu);

                                    echo $prixmedocBalance;
								?>
								</td>
							</tr>
				<?php
				
								$medmedoc .= ''.$ligneMedAutreMedoc->autreMedoc.' ('.$prixmedocBalance.' Rwf), ';
								
								$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								$TotalMedMedocCCO=$TotalMedMedocCCO+$prixmedocCCO;

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
                                $TotalMedMedocBalance = ($TotalMedMedocCCO - $TotalMedMedoc) + ($TotalMedMedocPatient + $TotalMedMedocInsu);

									echo $TotalMedMedocBalance.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedMedocCCO;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedMedocPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedMedocInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>

					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
								
						<?php
						
						$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult_hosp mc, '.$presta_assu.' p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
						$resultMedConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedConsu=$resultMedConsu->rowCount();
						
						$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.id_factureMedConsu=:idbill AND mc.id_prestationConsu IS NULL ORDER BY mc.id_medconsu DESC');
						$resultMedAutreConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_factureHosp
						));
						
						$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
						$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedConsu=0;
						$TotalMedConsuCCO=0;
						$TotalMedConsuPatient=0;
						$TotalMedConsuInsu=0;
						
				if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
				{
					if($comptMedConsu!=0)
					{
						while($ligneMedConsu=$resultMedConsu->fetch())
						{
							$qteConsu=$ligneMedConsu->qteConsu;
							
							if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
							{
								$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
								
								$prixconsu=($ligneMedConsu->prixprestationConsu * $qteConsu) - $prixPrestaRembou;
								$prixconsuCCO=($ligneMedConsu->prixprestationConsuCCO * $qteConsu) - $prixPrestaRembou;

							}else{
								$prixconsu=$ligneMedConsu->prixprestationConsu * $qteConsu;
								$prixconsuCCO=$ligneMedConsu->prixprestationConsuCCO * $qteConsu;

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
                                $prixconsuBalance = ($prixconsuCCO - $prixconsu) + ($prixconsupatient + $prixconsuinsu);

                                    echo $prixconsuBalance;
								?>
								</td>
							</tr>
						<?php

								$medconsu .= ''.$ligneMedConsu->nompresta.' ('.$prixconsuBalance.'), ';

								$TotalMedConsu=$TotalMedConsu+$prixconsu;
								$TotalMedConsuCCO=$TotalMedConsuCCO+$prixconsuCCO;
								$TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
								$TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;
							
							}
						}
					}
					
					if($comptMedAutreConsu!=0)
					{
						while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())
						{
							$qteConsu=$ligneMedAutreConsu->qteConsu;
							
							if($ligneMedAutreConsu->prixautreConsu!=0 AND $ligneMedAutreConsu->prixrembouConsu!=0)
							{
								$prixPrestaRembou=$ligneMedAutreConsu->prixrembouConsu;
								
								$prixconsu=($ligneMedAutreConsu->prixautreConsu * $qteConsu) - $prixPrestaRembou;
								$prixconsuCCO=($ligneMedAutreConsu->prixautreConsuCCO * $qteConsu) - $prixPrestaRembou;

							}else{
								$prixconsu=$ligneMedAutreConsu->prixautreConsu * $qteConsu;
								$prixconsuCCO=$ligneMedAutreConsu->prixautreConsuCCO * $qteConsu;

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
                                $prixconsuBalance = ($prixconsuCCO - $prixconsu) + ($prixconsupatient + $prixconsuinsu);
									echo $prixconsuBalance;
								?>
								</td>
							</tr>
				<?php
						
								$medconsu .= ''.$ligneMedAutreConsu->autreConsu.' ('.$prixconsuBalance.' Rwf), ';
								
								$TotalMedConsu=$TotalMedConsu+$prixconsu;
								$TotalMedConsuCCO=$TotalMedConsuCCO+$prixconsuCCO;
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
                                $TotalMedConsuBalance = ($TotalMedConsuCCO - $TotalMedConsu) + ($TotalMedConsuPatient + $TotalMedConsuInsu);

									echo $TotalMedConsuBalance;
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
									$TotalDayPriceCCO=$TotalDayPriceCCO+$TotalMedConsuCCO;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsuInsu;
								?>
								</td>
							</tr>
						</table>
					
					</td>
					
                    <td style="text-align:center;">
                        <?php
                        $TotalDayPriceBalance = ($TotalDayPriceCCO - $TotalDayPrice) + ($TotalDayPricePatient + $TotalDayPriceInsu);

                        echo $TotalDayPriceBalance;
                        ?>
                    </td>

                    <td style="text-align:center;">
                        <?php
                        $TotalDayPricePatientBalance = ($TotalDayPriceCCO - $TotalDayPrice) + $TotalDayPricePatient;

                        echo $TotalDayPricePatientBalance;
                        ?>
                    </td>

					<td style="text-align:center;"><?php echo $TotalDayPriceInsu;?></td>
				</tr>
				<?php
				$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
				$TotalGnlTypeConsuCCO=$TotalGnlTypeConsuCCO + $TotalTypeConsuCCO;
					$TotalGnlTypeConsuPatient = $TotalGnlTypeConsuPatient + $TotalTypeConsuPatient;
					$TotalGnlTypeConsuInsu = $TotalGnlTypeConsuInsu + $TotalTypeConsuInsu;
					
				$TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
				$TotalGnlMedSurgeCCO=$TotalGnlMedSurgeCCO + $TotalMedSurgeCCO;
					$TotalGnlMedSurgePatient = $TotalGnlMedSurgePatient + $TotalMedSurgePatient;
					$TotalGnlMedSurgeInsu = $TotalGnlMedSurgeInsu + $TotalMedSurgeInsu;
					
				$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
				$TotalGnlMedInfCCO=$TotalGnlMedInfCCO + $TotalMedInfCCO;
					$TotalGnlMedInfPatient = $TotalGnlMedInfPatient + $TotalMedInfPatient;
					$TotalGnlMedInfInsu = $TotalGnlMedInfInsu + $TotalMedInfInsu;
				
				$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
				$TotalGnlMedLaboCCO=$TotalGnlMedLaboCCO + $TotalMedLaboCCO;
					$TotalGnlMedLaboPatient=$TotalGnlMedLaboPatient + $TotalMedLaboPatient;
					$TotalGnlMedLaboInsu=$TotalGnlMedLaboInsu + $TotalMedLaboInsu;
				
				$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
				$TotalGnlMedRadioCCO=$TotalGnlMedRadioCCO + $TotalMedRadioCCO;
					$TotalGnlMedRadioPatient = $TotalGnlMedRadioPatient + $TotalMedRadioPatient;
					$TotalGnlMedRadioInsu = $TotalGnlMedRadioInsu + $TotalMedRadioInsu;
				
				$TotalGnlMedKine=$TotalGnlMedKine + $TotalMedKine;
				$TotalGnlMedKineCCO=$TotalGnlMedKineCCO + $TotalMedKineCCO;
					$TotalGnlMedKinePatient = $TotalGnlMedKinePatient + $TotalMedKinePatient;
					$TotalGnlMedKineInsu = $TotalGnlMedKineInsu + $TotalMedKineInsu;
				
				$TotalGnlMedOrtho=$TotalGnlMedOrtho + $TotalMedOrtho;
				$TotalGnlMedOrthoCCO=$TotalGnlMedOrthoCCO + $TotalMedOrthoCCO;
					$TotalGnlMedOrthoPatient = $TotalGnlMedOrthoPatient + $TotalMedOrthoPatient;
					$TotalGnlMedOrthoInsu = $TotalGnlMedOrthoInsu + $TotalMedOrthoInsu;
				
				$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
				$TotalGnlMedConsomCCO=$TotalGnlMedConsomCCO + $TotalMedConsomCCO;
					$TotalGnlMedConsomPatient = $TotalGnlMedConsomPatient + $TotalMedConsomPatient;
					$TotalGnlMedConsomInsu = $TotalGnlMedConsomInsu + $TotalMedConsomInsu;
				
				$TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
				$TotalGnlMedMedocCCO=$TotalGnlMedMedocCCO + $TotalMedMedocCCO;
					$TotalGnlMedMedocPatient = $TotalGnlMedMedocPatient + $TotalMedMedocPatient;
					$TotalGnlMedMedocInsu = $TotalGnlMedMedocInsu + $TotalMedMedocInsu;
					
				$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
				$TotalGnlMedConsuCCO=$TotalGnlMedConsuCCO + $TotalMedConsuCCO;
					$TotalGnlMedConsuPatient = $TotalGnlMedConsuPatient + $TotalMedConsuPatient;
					$TotalGnlMedConsuInsu = $TotalGnlMedConsuInsu + $TotalMedConsuInsu;
				
				$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
				$TotalGnlPriceCCO=$TotalGnlPriceCCO + $TotalDayPriceCCO;
					$TotalGnlPricePatient = $TotalGnlPricePatient + $TotalDayPricePatient;
					
					$TotalGnlPriceInsu = $TotalGnlPriceInsu + $TotalDayPriceInsu;
					
					
					$arrayGnlBillReport[$i][0]=$compteur;
					$arrayGnlBillReport[$i][1]=$ligneGnlBillReport->id_factureHosp;
					$arrayGnlBillReport[$i][2]=$nomassu;
					$arrayGnlBillReport[$i][3]=$carteassuid;
					$arrayGnlBillReport[$i][4]=$old;
					$arrayGnlBillReport[$i][5]=$sexe;
					$arrayGnlBillReport[$i][6]=$fullname;			
					$arrayGnlBillReport[$i][7]=$adherent;
					$arrayGnlBillReport[$i][8]=$profession;
					
					
					$arrayGnlBillReport[$i][9]=$ligneGnlBillReport->dateEntree;		
					$arrayGnlBillReport[$i][10]=$ligneGnlBillReport->dateSortie;		
					$arrayGnlBillReport[$i][11]=$nbrejrs;
					$arrayGnlBillReport[$i][12]=$prixroom;
					$arrayGnlBillReport[$i][13]=$balance;
					
					// $arrayGnlBillReport[$i][14]=$medconsu;		
					$arrayGnlBillReport[$i][14]=$TotalMedConsu;
					
					// $arrayGnlBillReport[$i][15]=$medinf;		
					$arrayGnlBillReport[$i][15]=$TotalMedInf;
					
					// $arrayGnlBillReport[$i][16]=$medlabo;		
					$arrayGnlBillReport[$i][16]=$TotalMedLabo;
					
					// $arrayGnlBillReport[$i][17]=$medradio;		
					$arrayGnlBillReport[$i][17]=$TotalMedRadio;
					
					// $arrayGnlBillReport[$i][18]=$medconsom;		
					$arrayGnlBillReport[$i][18]=$TotalMedConsom;
					
					// $arrayGnlBillReport[$i][19]=$medmedoc;		
					$arrayGnlBillReport[$i][19]=$TotalMedMedoc;
					
					$arrayGnlBillReport[$i][20]=$TotalDayPrice;
					$arrayGnlBillReport[$i][21]=$TotalDayPricePatient;
					$arrayGnlBillReport[$i][22]=$TotalDayPriceInsu;
					
					$arrayGnlBillReport[$i][23]=$insupercent.'%';
					
					$i++;
					
					$compteur++;
										
				}
				?>
					<tr style="text-align:center;">
						<td colspan=14></td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlTypeConsuBalance = ($TotalGnlTypeConsuCCO - $TotalGnlTypeConsu) + ($TotalGnlTypeConsuPatient + $TotalGnlTypeConsuInsu);

								echo $TotalGnlTypeConsuBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedSurgeBalance = ($TotalGnlMedSurgeCCO - $TotalGnlMedSurge) + ($TotalGnlMedSurgePatient + $TotalGnlMedSurgeInsu);

								echo $TotalGnlMedSurgeBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedInfBalance = ($TotalGnlMedInfCCO - $TotalGnlMedInf) + ($TotalGnlMedInfPatient + $TotalGnlMedInfInsu);

								echo $TotalGnlMedInfBalance;

							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedLaboBalance = ($TotalGnlMedLaboCCO - $TotalGnlMedLabo) + ($TotalGnlMedLaboPatient + $TotalGnlMedLaboInsu);

								echo $TotalGnlMedLaboBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedRadioBalance = ($TotalGnlMedRadioCCO - $TotalGnlMedRadio) + ($TotalGnlMedRadioPatient + $TotalGnlMedRadioInsu);

								echo $TotalGnlMedRadioBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedKineBalance = ($TotalGnlMedKineCCO - $TotalGnlMedKine) + ($TotalGnlMedKinePatient + $TotalGnlMedKineInsu);

								echo $TotalGnlMedKineBalance;

							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedOrthoBalance = ($TotalGnlMedOrthoCCO - $TotalGnlMedOrtho) + ($TotalGnlMedOrthoPatient + $TotalGnlMedOrthoInsu);

								echo $TotalGnlMedOrthoBalance;

							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedConsomBalance = ($TotalGnlMedConsomCCO - $TotalGnlMedConsom) + ($TotalGnlMedConsomPatient + $TotalGnlMedConsomInsu);

								echo $TotalGnlMedConsomBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
                            $TotalGnlMedMedocBalance = ($TotalGnlMedMedocCCO - $TotalGnlMedMedoc) + ($TotalGnlMedMedocPatient + $TotalGnlMedMedocInsu);

								echo $TotalGnlMedMedocBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
                        <td style="font-size: 13px; font-weight: bold;text-align:center;">
                            <?php
                            $TotalGnlMedConsuBalance = ($TotalGnlMedConsuCCO - $TotalGnlMedConsu) + ($TotalGnlMedConsuPatient + $TotalGnlMedConsuInsu);

                            echo $TotalGnlMedConsuBalance;

                            ?><span style="font-size:80%; font-weight:normal;">Rwf</span>
                        </td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php
                            $TotalGnlPriceBalance = ($TotalGnlPriceCCO - $TotalGnlPrice) + ($TotalGnlPricePatient + $TotalGnlPriceInsu);

								echo $TotalGnlPriceBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php
                            $TotalGnlPricePatientBalance = ($TotalGnlPriceCCO - $TotalGnlPrice) + $TotalGnlPricePatient;
								echo $TotalGnlPricePatientBalance;
								
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
					
							->setCellValue('N'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
							->setCellValue('O'.(10+$i).'', ''.$TotalGnlMedConsu.'')
							->setCellValue('P'.(10+$i).'', ''.$TotalGnlMedInf.'')
							->setCellValue('Q'.(10+$i).'', ''.$TotalGnlMedLabo.'')
							->setCellValue('R'.(10+$i).'', ''.$TotalGnlMedRadio.'')
							->setCellValue('S'.(10+$i).'', ''.$TotalGnlMedConsom.'')
							->setCellValue('T'.(10+$i).'', ''.$TotalGnlMedMedoc.'')
							->setCellValue('U'.(10+$i).'', ''.$TotalGnlPrice.'')
							->setCellValue('V'.(10+$i).'', ''.$TotalGnlPricePatient.'')
							->setCellValue('W'.(10+$i).'', ''.$TotalGnlPriceInsu.'');

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
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlHospitalisationBill/Daily/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlHospitalisationBill/Daily/");</script>';
					
					if($_GET['createRN']==1)
					{
						createRN('GHBD');
					}
					
				}else{
					if($_GET['paVisit']=='monthlyGnlBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlHospitalisationBill/Monthly/'.$reportsn.'.xlsx');
							
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlHospitalisationBill/Monthly/");</script>';
						
						if($_GET['createRN']==1)
						{
							createRN('GHBM');
						}
						
					}else{
						if($_GET['paVisit']=='annualyGnlBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlHospitalisationBill/Annualy/'.$reportsn.'.xlsx');
							
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlHospitalisationBill/Annualy/");</script>';
							
							if($_GET['createRN']==1)
							{
								createRN('GHBA');
							}
							
						}else{
							if($_GET['paVisit']=='customGnlBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlHospitalisationBill/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlHospitalisationBill/Custom/");</script>';
								
								if($_GET['createRN']==1)
								{
									createRN('GHBC');
								}
								
							}else{
								if($_GET['paVisit']=='gnlGnlBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientReport/GnlHospitalisationBill/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientReport/GnlHospitalisationBill/Alltimes/");</script>';
									
									if($_GET['createRN']==1)
									{
										createRN('GHBG');
									}
								}
							}
						}
					}
				}
			
				echo '<script text="text/javascript">document.location.href="dmacreporthosp.php?audit='.$_GET['audit'].'&dailydategnl='.$_GET['dailydategnl'].'&nomassu='.$_GET['nomassu'].'&idassu='.$_GET['idassu'].'&paVisit='.$_GET['paVisit'].'&percent='.$_GET['percent'].'&stringResult='.$_GET['stringResult'].'&divGnlBillReport=ok&gnlpatient=ok&createRN=0"</script>';
		
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