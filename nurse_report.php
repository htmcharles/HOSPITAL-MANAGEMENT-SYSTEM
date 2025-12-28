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

// $sn= date('H').''.date('i').''.date('s').'NR'.$_GET['inf'];
 
		if($_GET['infVisit']=='dailyPersoMedic')
		{
			$sn = showRN('NRD');
		}else{
			if($_GET['infVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('NRM');
			}else{
				if($_GET['infVisit']=='annualyPersoMedic')
				{
					$sn = showRN('NRA');
				}else{
					if($_GET['infVisit']=='customPersoMedic')
					{
						$sn = showRN('NRC');
					}else{
						if($_GET['infVisit']=='gnlPersoMedic')
						{
							$sn = showRN('NRG');
						}
					}
				}
			}
		}
		
		if(isset($_GET['infVisit']))
		{
			$infVisit=$_GET['infVisit'];
		}
		
		if(isset($_GET['infVisitgnl']))
		{
			$infVisitgnl=$_GET['infVisitgnl'];
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
	<title><?php echo 'Nurse Report#'.$sn; ?></title>

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
	if(isset($_GET['inf']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE i.id_u=:operation AND u.id_u=i.id_u');
		$result->execute(array(
		'operation'=>$_GET['inf']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeInf=$ligne->codeinfirmier;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			
			if($ligne->sexe=="M")
			{
				$sexe = "Male";
			}else{
			
				if($ligne->sexe=="F")
				
				$sexe = "Female";
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
			}else{
				$adresse='';
			}
			
		}
		
		$idInf=$_GET['inf'];
		$dailydateperso=$_GET['dailydateperso'];
		$infVisit=$_GET['infVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for nurse : '.$codeInf.', '.$fullname.'')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'S/N')
					->setCellValue('B1', ''.$codeInf.'')
					->setCellValue('A2', 'Full name')
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
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;">
			
			<tr>
				<td style="text-align:right">
					
					<form method="post" action="nurse_report.php?inf=<?php echo $_GET['inf'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&infVisit=<?php echo $infVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?>&createReportPdf=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="nurse_report.php?inf=<?php echo $_GET['inf'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&infVisit=<?php echo $infVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?>&createReportExcel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<td style="text-align:right">
					
						<a href="report.php?inf=<?php echo $_GET['inf'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
			
	<?php
		$userinfo = '<table style="width:100%; margin-top:20px;">
		
		<tr>
			<td style="text-align:left;">
				<span style="font-weight:bold">S/N: </span>'.$codeInf.'<br/>
				<span style="font-weight:bold">Nurse name: </span>
				'.$fullname.'<br/>
				<span style="font-weight:bold">Gender: </span>'.$sexe.'<br/>
				<span style="font-weight:bold">Adress: </span>'.$adresse.'
				
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
						<b><h3 style="padding:10px">Nurse Report #<?php echo $sn;?></h3></b>
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
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c, med_inf mi WHERE mi.id_consuInf=c.id_consu AND mi.id_uI=:inf '.$dailydateperso.' ORDER BY c.id_consu DESC');		
			$resultConsult->execute(array(
			'inf'=>$idInf
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A8', 'N°')
							->setCellValue('B8', 'Date of consultation')
							->setCellValue('C8', 'S/N')
							->setCellValue('D8', 'Patient name')
							->setCellValue('E8', 'Type of consultation')
							->setCellValue('F8', 'Nursing Care');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:7%; border-right: 1px solid #bbb">N°</th>
						<th style="width:11%; border-right: 1px solid #bbb">Date</th>
						<th style="width:11%; border-right: 1px solid #bbb">s/n</th>
						<th style="width:11%; border-right: 1px solid #bbb">Full name</th>
						<th style="width:35%; border-right: 1px solid #bbb"><?php echo getString(113);?></th>
						<th style="width:18%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
						
					</tr> 
				</thead> 


				<tbody>
			<?php
				// $date='0000-00-00';
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$nursery = "";
						
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
						<td style="text-align:left;">
						<?php
							echo $ligneConsult->numero;
							$numPa = $ligneConsult->numero;
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM consultations c, utilisateurs u, patients p WHERE u.id_u=p.id_u AND c.numero=p.numero AND c.numero=:operation AND c.motif!="" ORDER BY c.id_consu DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())
							{
								$fullnamePa = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								
								echo $fullnamePa;
							}else{
								echo '';
							}
							
						?>
						</td>
						<td>
						<?php
						$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
						
						$result->execute(array(
						'operation'=>$ligneConsult->id_uM
						));
						$result->setFetchMode(PDO::FETCH_OBJ);
						
						
						if($ligne=$result->fetch())
						{
							$fullnameDoc=$ligne->nom_u.' '.$ligne->prenom_u;
						}
								
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
								$nameprestaConsult = $lignePresta->namepresta.' ( by Dr '.$fullnameDoc.' )';
								echo $nameprestaConsult.'</td>';
							}else{	
							
								if($lignePresta->nompresta != '')
								{
									$nameprestaConsult = $lignePresta->nompresta.' ( by Dr '.$fullnameDoc.' )';
									echo $nameprestaConsult.'</td>';
								}

							}
						}
					
						echo '<td>';
						
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.soinsfait=1 AND mi.id_uI=:inf AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'inf'=>$idInf,					
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
						?>
						</td>
					</tr>
			<?php
						
						
						
						$arrayConsult[$i][0]=$compteur;
						$arrayConsult[$i][1]=$dateconsu;
						$arrayConsult[$i][2]=$numPa;
						$arrayConsult[$i][3]=$fullnamePa;
						$arrayConsult[$i][4]=$nameprestaConsult;
						$arrayConsult[$i][5]=$nursery;
						
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
				
				if($_GET['infVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/wamp/www/uap/Reports/NurseReport/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/NurseReport/Daily/");</script>';
					
					createRN('NRD');
					
				}else{
					if($_GET['infVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/wamp/www/uap/Reports/NurseReport/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/NurseReport/Monthly/");</script>';
						
						createRN('NRM');
					
					}else{
						if($_GET['infVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/wamp/www/uap/Reports/NurseReport/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/NurseReport/Annualy/");</script>';
							
							createRN('NRA');
							
						}else{
							if($_GET['infVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/wamp/www/uap/Reports/NurseReport/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/NurseReport/Custom/");</script>';
								
								createRN('NRC');
								
							}else{
								if($_GET['infVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/wamp/www/uap/Reports/NurseReport/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/NurseReport/Alltimes/");</script>';
									
									createRN('NRG');
								}
							}
						}
					}
				}
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