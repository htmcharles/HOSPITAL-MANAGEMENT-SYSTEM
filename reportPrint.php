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

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Report#'.$_SESSION['codeA']; ?></title>

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
$idAudit=$_SESSION['id'];

if($connected==true AND isset($_SESSION['id']))
{
			
	$resultatsAudit=$connexion->prepare('SELECT *FROM utilisateurs u, auditors a WHERE u.id_u=a.id_u and a.id_u=:operation');
	$resultatsAudit->execute(array(
	'operation'=>$idAudit	
	));

	$resultatsAudit->setFetchMode(PDO::FETCH_OBJ);
	if($ligneAudit=$resultatsAudit->fetch())
	{
		$doneby = $ligneAudit->nom_u.'  '.$ligneAudit->prenom_u;
		$codeaudit = $ligneAudit->codeaudit;
	}	
?>
	<div id="Report" style="border:1px solid #eee; border-radius:4px; font-size:85%; margin:1% auto; padding:10px 20px; width:95%;">
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
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

			if($ligneAssu=$resultAssurance->fetch())//on recupere la liste des �l�ments
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
		

		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$_SESSION['codeA'].'')
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
					->setCellValue('G1', ''.$_SESSION['codeA'].'')
					->setCellValue('F2', 'Done by')
					->setCellValue('G2', ''.$doneby.'')
					->setCellValue('F3', 'Date')
					->setCellValue('G3', ''.$annee.'');
		
	?>

		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;">
			
			<tr>
				<td style="text-align:right">
					
					<form method="post" action="reportPrint.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportPdf=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="reportPrint.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportExcel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

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
						<b><h2 style="padding:10px">Medical Report</h2></b>
					</td>
				</tr>
			
			</table>
			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num ORDER BY c.id_consu');		
			$resultConsult->execute(array(
			'num'=>$numPa
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Date of consultation')
							->setCellValue('C8', 'Type of consultation')
							->setCellValue('D8', 'Services')
							->setCellValue('E8', 'Nursing Care')
							->setCellValue('F8', 'Laboratory tests');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
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
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$consult = "";

						$nursery = "";

						$labs = "";
						
			?>
					<tr style="text-align:center;">
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
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

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
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

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
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  r�sultat soit r�cup�rable sous forme d'objet

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
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
						
						$arrayConsult[$i][0]=$dateconsu;
						$arrayConsult[$i][1]=$nameprestaConsult;
						$arrayConsult[$i][2]=$consult;
						$arrayConsult[$i][3]=$nursery;
						$arrayConsult[$i][4]=$labs;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','B10');
		
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
				
				$IdCoordReport=str_replace('/', '_', $_SESSION['codeA']);
				
				$objWriter->save('C:/Users/ADMIN/Documents/Reports/PersoMedicReportFiles/PersoMedicalReport#'.$IdCoordReport.'.xlsx');
				$callEndTime = microtime(true);
				$callTime = $callEndTime - $callStartTime;
				
				echo '<script type="text/javascript"> alert("File name : PersoMedicalReport#'.$IdCoordReport.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/PersoMedicReportFiles");</script>';
			}
			
		}
		
		if(isset($_GET['divPersoBillReport']))
		{
		?>
		<div id="divPersoBillReport">
	
			<table cellspacing="0" style="background:#fff; margin:20px auto auto">
				<tr>
					<td>
						<b><h2 style="padding:10px">Billing Report</h2></b>
					</td>
				</tr>
			
			</table>
			<?php
			
			$resultBillReport=$connexion->prepare('SELECT *FROM bills b WHERE b.numero=:numPa');		
			
			$resultBillReport->execute(array(
				'numPa'=>$_GET['num']
			));
			
			$resultBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

			$comptBillReport=$resultBillReport->rowCount();

			if($comptBillReport!=0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Date')
							->setCellValue('C8', 'Bill number')
							->setCellValue('D8', 'Insurance')
							->setCellValue('E8', 'Type of consultation')
							->setCellValue('F8', 'Services')
							->setCellValue('G8', 'Nursing Care')
							->setCellValue('H8', 'Laboratory tests')
							->setCellValue('I8', 'Total Final');
				
			?>
			<table  class="printPreview" cellspacing="0" style="margin:10px auto auto; border-top:none"> 
						
				<thead>
					<tr>
						<th>Date</th>
						<th>Bill number</th>
						<th>Insurance</th>
						<th><?php echo getString(113);?></th>
						<th><?php echo getString(39);?>s</th>
						<th><?php echo getString(98);?></th>
						<th><?php echo getString(99);?></th>
						<th>Total Final</th>
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
			
				while($ligneBillReport=$resultBillReport->fetch())//on recupere la liste des �l�ments
				{
			?>
					<tr style="text-align:center;">
						<td><?php echo $ligneBillReport->datebill;?></td>
						<td><?php echo $ligneBillReport->numbill;?></td>
						<td><?php echo $ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';?></td>
						<td><?php echo $ligneBillReport->totaltypeconsuprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
						<td><?php echo $ligneBillReport->totalmedconsuprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
						<td><?php echo $ligneBillReport->totalmedinfprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
						<td><?php echo $ligneBillReport->totalmedlaboprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
						<td><?php echo $ligneBillReport->totalgnlprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
					</tr>
			<?php
					$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneBillReport->totaltypeconsuprice;
					$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneBillReport->totalmedconsuprice;
					$TotalGnlMedInf= $TotalGnlMedInf + $ligneBillReport->totalmedinfprice;
					$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneBillReport->totalmedlaboprice;
					$TotalGnlPrice=$TotalGnlPrice + $ligneBillReport->totalgnlprice;
					
					
					
					$arrayPersoBillReport[$i][0]=$ligneBillReport->datebill;
					$arrayPersoBillReport[$i][1]=$ligneBillReport->numbill;
					$arrayPersoBillReport[$i][2]=$ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';
					$arrayPersoBillReport[$i][3]=$ligneBillReport->totaltypeconsuprice;
					$arrayPersoBillReport[$i][4]=$ligneBillReport->totalmedconsuprice;
					$arrayPersoBillReport[$i][5]=$ligneBillReport->totalmedinfprice;
					$arrayPersoBillReport[$i][6]=$ligneBillReport->totalmedlaboprice;
					$arrayPersoBillReport[$i][7]=$ligneBillReport->totalgnlprice;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayPersoBillReport,'','B10');
	
				}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td></td>
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlTypeConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedInf;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedLabo;
								
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
								->setCellValue('E'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
								->setCellValue('F'.(10+$i).'', ''.$TotalGnlMedConsu.'')
								->setCellValue('G'.(10+$i).'', ''.$TotalGnlMedInf.'')
								->setCellValue('H'.(10+$i).'', ''.$TotalGnlMedLabo.'')
								->setCellValue('I'.(10+$i).'', ''.$TotalGnlPrice.'');

				
			}
			?>
		</div>
		
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$IdCoordReport=str_replace('/', '_', $_SESSION['codeA']);
				
				$objWriter->save('C:/Users/ADMIN/Documents/Reports/PersoBillReportFiles/PersoBillReport#'.$IdCoordReport.'.xlsx');
				$callEndTime = microtime(true);
				$callTime = $callEndTime - $callStartTime;
				
				echo '<script type="text/javascript"> alert("File name : PersoBillReport#'.$IdCoordReport.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/PersoBillReportFiles");</script>';
			}
		}

	}
	
	
	if(isset($_GET['gnlpatient']))
	{
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.$_SESSION['codeA'].'')
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
					->setCellValue('C1', ''.$_SESSION['codeA'].'')
					->setCellValue('B2', 'Done by')
					->setCellValue('C2', ''.$doneby.'')
					->setCellValue('B3', 'Date')
					->setCellValue('C3', ''.$annee.'');
		
	?>
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;">
			
			<tr>
				<td style="text-align:right">
					
					<form method="post" action="reportPrint.php?audit=<?php echo $_SESSION['id'];?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportPdf=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
						
					<form method="post" action="reportPrint.php?audit=<?php echo $_SESSION['id'];?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportExcel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

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
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;">
			<tr>
				<?php
				if(isset($_GET['divGnlMedicReport']))
				{
				?>
				<td style="font-size:18px; width:33.333%; " id="gnlmedicalstring">
					<b><h2>General Medical Report</h2></b>
				</td>
				<?php 
				}
				if(isset($_GET['divGnlBillReport']))
				{
				?>
					<td style="font-size:18px; width:33.333%;" id="gnlbillingstring">
						<b><h2>General Billing Report</h2></b>
					</td>
				<?php 
				}
				?>
			</tr>
		</table>
		
	
		<?php
		if(isset($_GET['divGnlMedicReport']))
		{
		?>	
			<div id="divGnlMedicReport">
					
			<?php
			
				$resultConsult=$connexion->query('SELECT *FROM consultations c ORDER BY c.dateconsu DESC');		
				
				$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

				$comptConsult=$resultConsult->rowCount();
			
				$i=0;

				if($comptConsult != 0)
				{
					
					$objPHPExcel->setActiveSheetIndex(0)
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
					while($ligneConsult=$resultConsult->fetch())//on recupere la liste des �l�ments
					{
					
						$consult = "";

						$nursery = "";

						$labs = "";
				?>
				
					<tr style="text-align:center;">
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
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des �l�ments
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
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

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
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

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
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  r�sultat soit r�cup�rable sous forme d'objet

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
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des �l�ments
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
				
							
						$arrayConsult[$i][0]=$dateconsu;
						$arrayConsult[$i][1]=$fullname;
						$arrayConsult[$i][2]=$nameprestaConsult;
						$arrayConsult[$i][3]=$consult;
						$arrayConsult[$i][4]=$nursery;
						$arrayConsult[$i][5]=$labs;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','B8');
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
				
				$IdCoordReport=str_replace('/', '_', $_SESSION['codeA']);
				
				$objWriter->save('C:/Users/ADMIN/Documents/Reports/GnlMedicReportFiles/GnlMedicalReport#'.$IdCoordReport.'.xlsx');
				$callEndTime = microtime(true);
				$callTime = $callEndTime - $callStartTime;
				
				echo '<script type="text/javascript"> alert("File name : GnlMedicalReport#'.$IdCoordReport.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/GnlMedicReportFiles");</script>';
			}
		
		}
		?>
		
		<?php
		if(isset($_GET['divGnlBillReport']))
		{
		?>
		<div id="divGnlBillReport">
		
			<?php
			
			$resultGnlBillReport=$connexion->query('SELECT *FROM bills WHERE numero != ""');
			
			$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

			$comptBillReport=$resultGnlBillReport->rowCount();
			
			if($comptBillReport!=0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B8', 'Date')
								->setCellValue('C8', 'Bill number')
								->setCellValue('D8', 'Full name')
								->setCellValue('E8', 'Insurance')
								->setCellValue('F8', 'Type of consultation')
								->setCellValue('G8', 'Services')
								->setCellValue('H8', 'Nursing Care')
								->setCellValue('I8', 'Laboratory tests')
								->setCellValue('J8', 'Total Final');
					
			?>
			<table class="printPreview" cellspacing="0" style="background:#fff; margin:auto;"> 
						
				<thead>
					<tr>
						<th>Date</th>
						<th>Bill number</th>
						<th>Full name</th>
						<th>Insurance</th>
						<th><?php echo getString(113);?></th>
						<th><?php echo getString(39);?>s</th>
						<th><?php echo getString(98);?></th>
						<th><?php echo getString(99);?></th>
						<th>Total Final</th>
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
			
				while($ligneGnlBillReport=$resultGnlBillReport->fetch())//on recupere la liste des �l�ments
				{
			?>
			
				<tr style="text-align:center;">
					<td><?php echo $ligneGnlBillReport->datebill;?></td>
					<td><?php echo $ligneGnlBillReport->numbill;?></td>
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
						$resultPatient->execute(array(
						'operation'=>$ligneGnlBillReport->numero
						));
						
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des �l�ments
						{
							$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
							
							echo '<td>'.$fullname.'</td>';
						}else{
							echo '<td></td>';
						}
						
					?>
					
					<td><?php echo $ligneGnlBillReport->nomassurance.' '.$ligneGnlBillReport->billpercent.' %';?></td>
					<td><?php echo $ligneGnlBillReport->totaltypeconsuprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
					<td><?php echo $ligneGnlBillReport->totalmedconsuprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
					<td><?php echo $ligneGnlBillReport->totalmedinfprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
					<td><?php echo $ligneGnlBillReport->totalmedlaboprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
					<td><?php echo $ligneGnlBillReport->totalgnlprice;?><span style="font-size:80%; font-weight:normal;">Rwf</span></td>
				</tr>
			<?php
					$TotalGnlTypeConsu=$TotalGnlTypeConsu + $ligneGnlBillReport->totaltypeconsuprice;
					$TotalGnlMedConsu= $TotalGnlMedConsu + $ligneGnlBillReport->totalmedconsuprice;
					$TotalGnlMedInf= $TotalGnlMedInf + $ligneGnlBillReport->totalmedinfprice;
					$TotalGnlMedLabo=$TotalGnlMedLabo + $ligneGnlBillReport->totalmedlaboprice;
					$TotalGnlPrice=$TotalGnlPrice + $ligneGnlBillReport->totalgnlprice;
					
					
					
					
					$arrayGnlBillReport[$i][0]=$ligneGnlBillReport->datebill;
					$arrayGnlBillReport[$i][1]=$ligneGnlBillReport->numbill;
					$arrayGnlBillReport[$i][2]=$fullname;
					
					$arrayGnlBillReport[$i][3]=$ligneGnlBillReport->nomassurance.' '.$ligneGnlBillReport->billpercent.' %';
					
					$arrayGnlBillReport[$i][4]=$ligneGnlBillReport->totaltypeconsuprice;
					$arrayGnlBillReport[$i][5]=$ligneGnlBillReport->totalmedconsuprice;
					$arrayGnlBillReport[$i][6]=$ligneGnlBillReport->totalmedinfprice;
					$arrayGnlBillReport[$i][7]=$ligneGnlBillReport->totalmedlaboprice;
					$arrayGnlBillReport[$i][8]=$ligneGnlBillReport->totalgnlprice;
					
					$i++;
					
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayGnlBillReport,'','B10');
	
				}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlTypeConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedConsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedInf;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalGnlMedLabo;
								
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
							->setCellValue('F'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
							->setCellValue('G'.(10+$i).'', ''.$TotalGnlMedConsu.'')
							->setCellValue('H'.(10+$i).'', ''.$TotalGnlMedInf.'')
							->setCellValue('I'.(10+$i).'', ''.$TotalGnlMedLabo.'')
							->setCellValue('J'.(10+$i).'', ''.$TotalGnlPrice.'');

			}
			?>
		</div>
		<?php
			
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$IdCoordReport=str_replace('/', '_', $_SESSION['codeA']);
				
				$objWriter->save('C:/Users/ADMIN/Documents/Reports/GnlBillReportFiles/GnlBillReport#'.$IdCoordReport.'.xlsx');
				$callEndTime = microtime(true);
				$callTime = $callEndTime - $callStartTime;
				
				echo '<script type="text/javascript"> alert("File name : GnlBillReport#'.$IdCoordReport.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/GnlBillReportFiles");</script>';
			}
			
		}
		?>
	<?php
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