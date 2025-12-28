<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");

/** Include PHPExcel */
require_once 'PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$annee= date('d').'-'.date('M').'-'.date('Y');
$heure= date('H').'-'.date('i').'-'.date('s');

require_once('barcode/class/BCGFontFile.php');
require_once('barcode/class/BCGColor.php');
require_once('barcode/class/BCGDrawing.php');
require_once('barcode/class/BCGcode93.barcode.php');

	if (isset($_GET['patientList'])) {
		$sn = showRN('P_L'); 
	}

	
?>

<!DOCTYPE html>
<html lang="en">
<noscript>
	Cette page requiert du JavaScript
	Veuillez l'active pour votre navigateur
</noscript>
<head>
	<title>Patients List#<?php echo $sn;?></title>
	<link href="cssBourbonCoffee/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	<meta charset="utf-8">
	
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
		if(isset($_GET['finishbtn'])){
	?>
		<body onload="window.print()">
	<?php
		}
	?>
	<?php
	$connected=$_SESSION['connect'];
	$idCoordi=$_SESSION['id'];

	if($connected==true AND isset($_SESSION['codeC']))
	{
		$resultatsCoordinateur=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation ');
		$resultatsCoordinateur->execute(array(
		'operation'=>$idCoordi	
		));

		$resultatsCoordinateur->setFetchMode(PDO::FETCH_OBJ);
		if($ligneCoordi=$resultatsCoordinateur->fetch())
		{
			$doneby = $ligneCoordi->nom_u.'  '.$ligneCoordi->prenom_u;
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
	<div style="margin: 10px auto auto; width:95%; border: 1px solid #ccc; background:#fff; padding:2px; border-radius:3px; font-size:70%;">
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

//echo $barcode;
?>

		<table style="width:100%">
		
			<tr>
				<td colspan=2 style="text-align:center;">
					<span style="text-align:center;background:#333;border-radius:40px;color:#eee;font-weight:400;padding:5px 50px">Powered by <font>Medical File</font> , a product of Innovate Solutions Ltd. ©2022-<?php echo date('Y');?>, All Rights Reserved.</span>
				</td>
			</tr>
		  
			<tr>
				<td>
					<table>
						<tr>
							<td style="text-align:right;padding:5px;border-top:none;">
							  	<img src="images/Logo.jpg">
							</td>
							
							<td style="text-align:left;width:80%">
							  	<span style="border-top:none;border-bottom:2px solid #ccc; font-size:110%; font-weight:900"></span>
								<span style="font-size:90%;">
									Phone: (+250) 784275588<br/>
									E-mail: horebumedicalclinic@gmail.com<br/>
									Gasobo - Remera - Rukiri II
								</span>

							</td>

							<td style="border-top:none;">
								<?php
									echo '<img src="barcode/png/barcode'.$codecoordi.'.png" style="height:auto;"/>';
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
			$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
						->setLastModifiedBy(''.$doneby.'')
						->setTitle('Patients List #'.$sn.'')
						->setSubject("Patients List information")
						->setDescription('Report information for all Patients')
						->setKeywords("Report Excel")
						->setCategory("Report");

			for($col = ord('a'); $col <= ord('z'); $col++)
			{
				$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
			}
		
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'List Patients #')
						->setCellValue('B1', ''.$sn.'')
						->setCellValue('A2', 'Done by')
						->setCellValue('B2', ''.$doneby.'')
						->setCellValue('A3', 'Date')
						->setCellValue('B3', ''.$annee.'');
		

			$resultatsPatient=$connexion->query('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u GROUP BY u.id_u ORDER BY u.id_u');
			/*$resultatsPatient->execute(array(
			'operation'=>$numPa	
			));*/
			
			$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
			$comptPa = $resultatsPatient->rowCount();
		?>
		
		<table style="width:100%; margin-bottom:-10px"> 
			<tr> 
				<td style="text-align:left; width:33%;">
					<h4><?php echo $annee;?></h4>
				</td>
				
				<td style="text-align:center; width:33%;">
					<h2 style="font-size:150%; font-weight:600;">Listes des patients #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right;width:33%;">
				
					<form method="post" action="printPatients.php?<?php if(isset($_GET['coordi'])){ echo 'coordi='.$_GET['coordi'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?><?php if(isset($_GET['patientList'])){ echo '&patientList='.$_GET['patientList'];}?>&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo "Print";?></button>
					</form>	
					
				</td>

				<td style="text-align:left">
					
					<form method="post" action="printPatients.php?<?php if(isset($_GET['coordi'])){ echo 'coordi='.$_GET['coordi'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?><?php if(isset($_GET['patientList'])){ echo '&patientList='.$_GET['patientList'];}?>&createReportExcel=ok" enctype="multipart/form-data">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
			
				<td style="text-align:left;" class="buttonBill">
					<a href="utilisateurs.php?" id="cancelbtn" style="margin:30px;<?php if((!isset($_GET['finishbtn'])) && (!isset($_GET['createReportExcel']))){ echo "display:inline";}else{ echo "display:none";}?>" class="btn-large-inversed">
						<i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?>
					</a>

					<a href="utilisateurs.php?" id="finishbtn" style="margin:30px;<?php if((!isset($_GET['finishbtn'])) && (!isset($_GET['createReportExcel']))){ echo "display:none";}?>" class="btn-large-inversed">
						<i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141);?>
					</a>
				</td>
			</tr>
		</table>
	<?php
		/*try
		{*/
			
			if($comptPa != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A7', 'N°')
							->setCellValue('B7', 'Nom')
							->setCellValue('C7', 'Prenom')
							->setCellValue('D7', 'Telephone');
					
	?>
	<br><br>
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 25px;width:90%;"> 
				<thead> 
					<tr>
						<th style="width:15%;text-align:center;">N°</th>
						<th style="width:15%;text-align:center;">Nom</th>
						<th style="width:15%;text-align:center;">Prenom</th>
						<th style="width:15%;text-align:center;">Telephone</th>
					</tr> 
				</thead>
				<tbody>
					<?php
					$i = 0;
					$compteur = 1;
						while($lignePatient=$resultatsPatient->fetch())
						{
					?>
						<tr style="text-align:center;">
							<td style="text-align:center;">
								<?php
									echo $compteur;
								?>
							</td>
							<td style="text-align:center;">
								<?php
									echo $lignePatient->nom_u;
								?>
							</td>
							<td style="text-align:center;">
								<?php
									echo $lignePatient->prenom_u;
								?>
							</td>
							<td style="text-align:center;">
								<?php
									echo $lignePatient->telephone;
								?>
							</td>
					<?php

							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$lignePatient->nom_u;
							$arrayConsult[$i][2]=$lignePatient->prenom_u;
							$arrayConsult[$i][3]=$lignePatient->telephone;
							
							$i++;

							$compteur++;
						}

					?>

				</tbody>
	
	<?php
			}
			$objPHPExcel->setActiveSheetIndex(0)
						->fromArray($arrayConsult,'','A8');
	
			if(isset($_GET['createReportExcel']))
			{
				//echo "string";
				$callStartTime = microtime(true);
				//print_r($objPHPExcel);
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn= str_replace('/', '_', $sn);
				//echo "reportsn = ".$reportsn;
				if (isset($_GET['patientList'])) {
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/PatientList/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/PatientList/");</script>';
					$n = "P_L";
					createRN(''.$n.'');
				} 
			}
	?>
		</table>

	<?php
			
			
		/*}	

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}*/
		?>
	</div>
		<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
		
			<?php
			$footer = '

				<table style="width:100%">
					
					<tr>
						
						<td style="text-align:right;">
							 Done by : <span style="font-weight:bold">'.$doneby.'</span>
						</td>
										
					</tr>
					
				</table>';

			echo $footer;
			?>
			
		</div>
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
<?php
	}else{
	
		echo '<script text="text/javascript">alert("You are not logged in");</script>';
		
		echo '<script text="text/javascript">document.location.href="index.php"</script>';
		
		/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&cashier=CSC15A01&dateconsu=2015-09-19");
		file_put_contents("toPDF.html", $file); */

	}
?>
</body>
</html>