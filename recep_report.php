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

// $sn= date('H').''.date('i').''.date('s').'RR'.$_GET['rec'];
 
		if($_GET['recVisit']=='dailyPersoMedic')
		{
			$sn = showRN('RRD');
		}else{
			if($_GET['recVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('RRM');
			}else{
				if($_GET['recVisit']=='annualyPersoMedic')
				{
					$sn = showRN('RRA');
				}else{
					if($_GET['recVisit']=='customPersoMedic')
					{
						$sn = showRN('RRC');
					}else{
						if($_GET['recVisit']=='gnlPersoMedic')
						{
							$sn = showRN('RRG');
						}
					}
				}
			}
		}
		
		if(isset($_GET['recVisit']))
		{
			$recVisit=$_GET['recVisit'];
		}
		
		if(isset($_GET['recVisitgnl']))
		{
			$recVisitgnl=$_GET['recVisitgnl'];
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
	<title><?php echo 'Receptionist Report#'.$sn; ?></title>

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
	if(isset($_GET['rec']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE r.id_u=:operation AND u.id_u=r.id_u');
		$result->execute(array(
		'operation'=>$_GET['rec']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeRec=$ligne->codereceptio;
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
		
		$idRec=$_GET['rec'];
		$dailydateperso=$_GET['dailydateperso'];
		$recVisit=$_GET['recVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for Reception : '.$codeRec.', '.$fullname.'')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'S/N')
					->setCellValue('B1', ''.$codeRec.'')
					->setCellValue('A2', 'Full name')
					->setCellValue('B2', ''.$fullname.'')
					->setCellValue('A3', 'Adresse')
					->setCellValue('B3', ''.$adresse.'')
					
					->setCellValue('D1', 'Report #')
					->setCellValue('E1', ''.$sn.'')
					->setCellValue('D2', 'Done by')
					->setCellValue('E2', ''.$doneby.'')
					->setCellValue('D3', 'Date')
					->setCellValue('E3', ''.$annee.'');
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;">
			
			<tr>
				<td style="text-align:right">
					
					<form method="post" action="recep_report.php?rec=<?php echo $_GET['rec'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&recVisit=<?php echo $recVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?>&createReportPdf=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="recep_report.php?rec=<?php echo $_GET['rec'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&recVisit=<?php echo $recVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?>&createReportExcel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<td style="text-align:right">
					
						<a href="report.php?rec=<?php echo $_GET['rec'];?>&audit=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
			
	<?php
		$userinfo = '<table style="width:100%; margin-top:20px;">
		
		<tr>
			<td style="text-align:left;">
				<span style="font-weight:bold">S/N: </span>'.$codeRec.'<br/>
				<span style="font-weight:bold">Receptionist name: </span>
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
						<b><h3 style="padding:10px">Receptionist Report #<?php echo $sn;?></h3></b>
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
			
			$resultConsult=$connexion->prepare('SELECT *FROM patients p, utilisateurs u WHERE p.createdbyPa=:rec AND p.id_u=u.id_u '.$dailydateperso.' ORDER BY p.numero ASC');		
			$resultConsult->execute(array(
			'rec'=>$idRec
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
		
			// echo $comptConsult;
			
			$i=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A8', 'N°')
							->setCellValue('B8', 'S/N')
							->setCellValue('C8', 'Patient name')
							->setCellValue('D8', 'Created Time');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:7%; border-right: 1px solid #bbb;text-align:center;">N°</th>
						<th style="width:11%; border-right: 1px solid #bbb;text-align:center;">s/n</th>
						<th style="width:11%; border-right: 1px solid #bbb;text-align:center;">Full name</th>
						<th style="width:18%; border-right: 1px solid #bbb;text-align:center;">Created Time</th>
						
					</tr> 
				</thead> 


				<tbody>
			<?php
				// $date='0000-00-00';
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$reception = "";
						
			?>
					<tr style="text-align:center;">
						<td style="text-align:center;">
						<?php
							echo $compteur;
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
							echo $ligneConsult->numero;
							$numPa = $ligneConsult->numero;
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le r?ltat soit r?p?ble sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des ?ments
							{
								$fullnamePa = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								$createdtime = $lignePatient->createdtimePa;
								
								echo $fullnamePa;
							
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
								echo $createdtime;
						?>
						</td>
						<?php
							}else{
								echo '<td></td>';
								echo '<td></td>';
							}
						?>
					</tr>
			<?php
						
						
						
						$arrayConsult[$i][0]=$compteur;
						$arrayConsult[$i][1]=$numPa;
						$arrayConsult[$i][2]=$fullnamePa;
						$arrayConsult[$i][3]=$createdtime;
						
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
				
				if($_GET['recVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/wamp/www/uap/Reports/ReceptionistReport/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/ReceptionistReport/Daily/");</script>';
					
					createRN('RRD');
					
				}else{
					if($_GET['recVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/wamp/www/uap/Reports/ReceptionistReport/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/ReceptionistReport/Monthly/");</script>';
						
						createRN('RRM');
					
					}else{
						if($_GET['recVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/wamp/www/uap/Reports/ReceptionistReport/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/ReceptionistReport/Annualy/");</script>';
							
							createRN('RRA');
							
						}else{
							if($_GET['recVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/wamp/www/uap/Reports/ReceptionistReport/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/ReceptionistReport/Custom/");</script>';
								
								createRN('RRC');
								
							}else{
								if($_GET['recVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/wamp/www/uap/Reports/ReceptionistReport/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/ReceptionistReport/Alltimes/");</script>';
									
									createRN('RRG');
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