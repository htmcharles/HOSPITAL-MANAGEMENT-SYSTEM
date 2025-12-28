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
	
	if(isset($_SESSION['codeI']))
	{	
		$resultatsCa=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u and i.id_u=:operation');
		$resultatsCa->execute(array(
		'operation'=>$idDoneby	
		));

		$resultatsCa->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneCa=$resultatsCa->fetch())
		{
			$doneby = $ligneCa->full_name;
			$codeDoneby = $ligneCa->codeinfirmier;
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
	if(isset($_GET['codeI']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE i.codeinfirmier=:operation AND u.id_u=i.id_u');
		$result->execute(array(
		'operation'=>$_GET['codeI']	
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
		
		$codeI=$_GET['codeI'];
		$dailydateperso=$_GET['dailydateperso'];
		$caVisit=$_GET['caVisit'];
				

		// $dailydateperso;
		if (!isset($_GET['coordi'])) {
			
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for nurse : '.$codeI.', '.$fullname.'')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");
		}
	

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
		if (!isset($_GET['coordi'])) {
			
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'S/N')
					->setCellValue('B1', ''.$codeI.'')
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
		}
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
					
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Nurse Report(<?php if(isset($_GET['medicament'])){echo "Medicament";}else{if(isset($_GET['consommable'])){echo "Consommable";}else{if(isset($_GET['Hospitalisation'])){echo "Hospitalisation";}else{if(isset($_GET['petitchirugie'])){echo "Petit Chirugie";}}}} ?>) #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="NurseReportMedicament.php?codeI=<?php echo $_GET['codeI'];?>&dailydateperso=<?php echo $dailydateperso;?><?php if(isset($_GET['dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoNurseReport=ok&createReportPdf=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['petitchirugie'])){echo '&petitchirugie=ok';} ?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<?php
				/*if(isset($_SESSION['codeC']))
				{*/
				?>
				<!-- <td style="text-align:left">
					
					<form method="post" action="NurseReportMedicament.php?codeI=<?php echo $_GET['codeI'];?>&dailydateperso=<?php echo $dailydateperso;?><?php if(isset($_GET['dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoNurseReport=ok&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>	 -->			
				<?php
				//}
				?>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="report.php?codeI=<?php echo $_GET['codeI'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
		
	<?php
	if (!isset($_GET['coordi'])) {
			
		$userinfo = '
	
	<table style="width:50%; margin-top:20px;font-size:15px;" align="center">		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Nurse name : </span>		
			</td>
			<td style="text-align:left;">'.$fullname.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">S/N : </span>		
			</td>
			<td style="text-align:left;">'.$codeI.'</td>			
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
}
		if(isset($_GET['divPersoNurseReport']))
		{
			// echo $_GET['dailydateperso'];
			if (isset($_GET['medicament'])) {
					
			?>
			<div id="divPersoBillReport">
		
				<?php
							
				$resultCashierBillReport=$connexion->query('SELECT *FROM med_medoc WHERE '.$dailydateperso.' AND qteMedoc!=0 GROUP BY id_consuMedoc');
				/*$resultCashierBillReport->execute(array(
				'codeCa'=>$codeI
				));*/
				
				$resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);

				$compCashBillReport=$resultCashierBillReport->rowCount();

				if($compCashBillReport!=0)
				{
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A9', 'N°')
								->setCellValue('B9', 'Date')
								->setCellValue('C9', 'Full name')
								->setCellValue('D9', 'Medicament')
								->setCellValue('E9', 'Nurse ')
								->setCellValue('F9', 'Doctor')
								->setCellValue('G9', 'Sexe');
					
				?>
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
								
					<thead>
						<tr>

							<th style="width:10%; text-align: center;">N°</th>
							<th style="width:12%; text-align: center;">Date</th>
							<th style="width:20%; text-align: center;">Full name</th>
							<th style="width:5%; text-align: center;">Assurance</th>
							<th style="width:20%; text-align: center;">Medicament</th>
							<th style="width:10%; text-align: center;">Quantités</th>
							<th style="width:20%; text-align: center;">Nurse</th>
							<th style="width:20%; text-align: center;">Doctor</th>

						</tr> 
					</thead> 
					
					<tbody>
						<?php
						$compteur=1;
						$i = 0;
						
							while($ligneNurseReport=$resultCashierBillReport->fetch())//on recupere la liste des éléments
							{
								
						?>
						
							<tr style="text-align:center;">
									<td style="text-align: center;">
										<?php echo $compteur; ?>
									</td>
									<td style="text-align: center;">
										<?php echo $ligneNurseReport->dateconsu; ?>
									</td>
									<td style="text-align: center;">
										<?php
										 	$SelectPaName = $connexion->prepare("SELECT * FROM Utilisateurs uti,Patients pa WHERE uti.id_u=pa.id_u AND  pa.numero=:numero");
										 	$SelectPaName->execute(array('numero'=>$ligneNurseReport->numero));
										 	$SelectPaName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetName=$SelectPaName->fetch();
										 	$Patientsname = $GetName->full_name;
										 	echo $GetName->full_name.' <b>('. $GetName->numero.')</b>';
										?>
									</td>
									<td style="text-align: center;font-weight: bold;">
										<?php
											$GetAssu = $connexion->prepare('SELECT * FROM assurances WHERE id_assurance=:id_assuMedoc');
											$GetAssu->execute(array('id_assuMedoc'=>$ligneNurseReport->id_assuMedoc));
											$GetAssu->setFetchMode(PDO::FETCH_OBJ);
											$count = $GetAssu->rowCount();

											if($GetAssuname = $GetAssu->fetch()){
												echo $GetAssuname ->nomassurance;
												$presta_assu='prestations_'.$GetAssuname->nomassurance;
											}
										?>
									</td>
									<td style="text-align: center;font-weight: bold;">
										<?php

											$SeleMedoc = $connexion->prepare('SELECT * FROM med_medoc WHERE id_consuMedoc=:id_consuMedoc');
											$SeleMedoc->execute(array('id_consuMedoc'=>$ligneNurseReport->id_consuMedoc));
											$SeleMedoc->setFetchMode(PDO::FETCH_OBJ);
											//echo $count = $SeleMedoc->rowCount();
											while($GetPrestaId = $SeleMedoc->fetch()){

											 	$SelectPrestationName = $connexion->prepare('SELECT * FROM '.$presta_assu.' WHERE id_prestation=:id_prestation');
											 	$SelectPrestationName->execute(array('id_prestation'=>$GetPrestaId->id_prestationMedoc));
											 	$SelectPrestationName->setFetchMode(PDO::FETCH_OBJ);
											 	$count = $SelectPrestationName->rowCount();
											 	if($GetPrestationName=$SelectPrestationName->fetch()){
												 	$Medicament = $GetPrestationName->nompresta;
												 	//echo $GetPrestationName->nompresta.'<br>';
													if($GetPrestationName->namepresta!='')
													{
														echo '- '. $GetPrestationName->namepresta.'<br><hr>';					
														//$prestamedoc[] = $GetPrestationName->namepresta;
													}else{								
														echo '- '.$GetPrestationName->nompresta.'<br><hr>';		
														//$prestamedoc[] = $GetPrestationName->nompresta;
													}
											 	}
											}
										?>
									</td>

									<td style="text-align: center;font-weight: bold;">
										<?php

											$SeleMedoc = $connexion->prepare('SELECT * FROM med_medoc WHERE id_consuMedoc=:id_consuMedoc');
											$SeleMedoc->execute(array('id_consuMedoc'=>$ligneNurseReport->id_consuMedoc));
											$SeleMedoc->setFetchMode(PDO::FETCH_OBJ);
											//echo $count = $SeleMedoc->rowCount();
											while($GetPrestaId = $SeleMedoc->fetch()){

												echo $GetPrestaId->qteMedoc.'<br><hr>';
											}
										?>
									</td>


									<td style="font-weight: bold;text-align: center;">
										<?php
										if($ligneNurseReport->id_uInfMedoc != 0){
										    $SelectNurseName = $connexion->prepare("SELECT * FROM Utilisateurs uti WHERE  uti.id_u=:id_u");
										 	$SelectNurseName->execute(array('id_u'=>$ligneNurseReport->id_uInfMedoc));
										 	$SelectNurseName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetName=$SelectNurseName->fetch();
										 	$nurseName = $GetName->full_name;
										 	//echo $count=$SelectNurseName->rowCount();
										 	echo $GetName->full_name;
										 }else{
										 	echo "----";
										 	$nurseName ="-----";
										 }
										?>
									</td>

									<td style="font-weight: bold;text-align: center;">
										<?php
										    $SelectDocName = $connexion->prepare("SELECT * FROM Utilisateurs uti WHERE  uti.id_u=:id_u");
										 	$SelectDocName->execute(array('id_u'=>$ligneNurseReport->id_uM));
										 	$SelectDocName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetName=$SelectDocName->fetch();
										 	$doctorName = $GetName->full_name;

										 	//echo $count=$SelectNurseName->rowCount();
										 	echo $GetName->full_name;
										?>
									</td>
									
								</tr>
							<?php
								//$numMonth++;
								$dateconsuTest = $ligneNurseReport->dateconsu;

								$arrayGnlBillReport[$i][0]=$compteur;
								$arrayGnlBillReport[$i][1]=$dateconsuTest;
								$arrayGnlBillReport[$i][2]=$Patientsname;
								$arrayGnlBillReport[$i][3]=$Medicament;
								$arrayGnlBillReport[$i][4]=$nurseName;		
								$arrayGnlBillReport[$i][5]=$doctorName ;		

								$compteur++;

							}
							?>
							</tbody>
				</table>
				<?php
						
					
					/*$objPHPExcel->setActiveSheetIndex(0)
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
								->setCellValue('S'.(10+$i).'', ''.$TotalGnlPriceInsu.'');*/
				}
				?>
			</div>
			
			<?php
			
				
				if(isset($_GET['createReportExcel']))
				{
					echo "string";
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$Careportsn=str_replace('/', '_', $sn);
					
					
					if($_GET['caVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/'.$Careportsn.'.xlsx');
								
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/");</script>';
						
					}else{
						if($_GET['caVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/'.$Careportsn.'.xlsx');
								
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/");</script>';
							
						}else{
							if($_GET['caVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/'.$Careportsn.'.xlsx');
								
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/");</script>';
								
							}else{
								if($_GET['caVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/'.$Careportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/");</script>';
									
								}else{
									if($_GET['caVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/'.$Careportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
					
				}
				
				
				if((isset($_GET['createReportExcel']) /*OR isset($_GET['createReportPdf'])*/) AND $_GET['createRN']==1)
				{				
					if($_GET['caVisit']=='dailyPersoMedic')
					{
						createRN('CRD');
						
					}else{
						if($_GET['caVisit']=='monthlyPersoMedic')
						{
							createRN('CRM');

						}else{
							if($_GET['caVisit']=='annualyPersoMedic')
							{
								createRN('CRA');
								
							}else{
								if($_GET['caVisit']=='customPersoMedic')
								{	
									createRN('CRC');
								
								}else{
									if($_GET['caVisit']=='gnlPersoMedic')
									{
										createRN('CRG');
									
									}
								}
							}
						}
					}
					$caVisit=$_GET['caVisit'];
					/*echo "<script text='text/javascript'>document.location.href='infirmier_report.php?codeI=".$_GET['codeI']."&dailydateperso=".$dailydateperso if(isset($_GET[\'dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createReportPdf=ok&createRN=<?php echo $createRN;?>'</script>";
	*/
					echo '<script text="text/javascript">document.location.href="infirmier_report.php?codeI='.$_GET['codeI'].'&dailydateperso='.$_GET['dailydateperso'].'&caVisit='.$_GET['caVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
				}
			}


			// echo $_GET['dailydateperso'];
			if (isset($_GET['consommable'])) {
					
			?>
			<div id="divPersoBillReport">
		
				<?php
							
				$resultCashierBillReport=$connexion->query('SELECT *FROM med_consom WHERE '.$dailydateperso.' AND qteConsom!=0 GROUP BY id_consuConsom');
				/*$resultCashierBillReport->execute(array(
				'codeCa'=>$codeI
				));*/
				
				$resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);

				$compCashBillReport=$resultCashierBillReport->rowCount();

				if($compCashBillReport!=0)
				{
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A9', 'N°')
								->setCellValue('B9', 'Date')
								->setCellValue('C9', 'Full name')
								->setCellValue('D9', 'Medicament')
								->setCellValue('E9', 'Nurse ')
								->setCellValue('F9', 'Doctor')
								->setCellValue('G9', 'Sexe');
					
				?>
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
								
					<thead>
						<tr>

							<th style="width:10%; text-align: center;">N°</th>
							<th style="width:12%; text-align: center;">Date</th>
							<th style="width:20%; text-align: center;">Full name</th>
							<th style="width:5%; text-align: center;">Assurance</th>
							<th style="width:15%; text-align: center;">Consommable</th>
							<th style="width:5%; text-align: center;">Quantity</th>
							<th style="width:20%; text-align: center;">Nurse</th>
							<th style="width:20%; text-align: center;">Doctor</th>

						</tr> 
					</thead> 
					
					<tbody>
						<?php
						$compteur=1;
						$i = 0;
						
							while($ligneNurseReport=$resultCashierBillReport->fetch())//on recupere la liste des éléments
							{
								
						?>
						
							<tr style="text-align:center;">
									<td style="text-align: center;">
										<?php echo $compteur; ?>
									</td>
									<td style="text-align: center;">
										<?php echo $ligneNurseReport->dateconsu; ?>
									</td>
									<td style="text-align: center;">
										<?php
										 	$SelectPaName = $connexion->prepare("SELECT * FROM Utilisateurs uti,Patients pa WHERE uti.id_u=pa.id_u AND  pa.numero=:numero");
										 	$SelectPaName->execute(array('numero'=>$ligneNurseReport->numero));
										 	$SelectPaName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetName=$SelectPaName->fetch();
										 	$Patientsname = $GetName->full_name;
										 	echo $GetName->full_name.' <b>('. $GetName->numero.')</b>';
										?>
									</td>

									<td style="text-align: center;font-weight: bold;">
										<?php
											$GetAssu = $connexion->prepare('SELECT * FROM assurances WHERE id_assurance=:id_assuConsom');
											$GetAssu->execute(array('id_assuConsom'=>$ligneNurseReport->id_assuConsom));
											$GetAssu->setFetchMode(PDO::FETCH_OBJ);
											$count = $GetAssu->rowCount();

											if($GetAssuname = $GetAssu->fetch()){
												echo $GetAssuname ->nomassurance;
												$presta_assu='prestations_'.$GetAssuname->nomassurance;
											}
										?>
									</td>
									
									<td style="text-align: center;font-weight: bold;">
										<?php
										$SeleConsom = $connexion->prepare('SELECT * FROM med_consom WHERE '.$dailydateperso.' AND id_consuConsom=:id_consuConsom');
										$SeleConsom->execute(array('id_consuConsom'=>$ligneNurseReport->id_consuConsom));
										$SeleConsom->setFetchMode(PDO::FETCH_OBJ);
										$count = $SeleConsom->rowCount();
										//echo $ligneNurseReport->id_consuConsom;
										while($GetPrestaId = $SeleConsom->fetch()){
										 	$SelectPrestationName = $connexion->prepare('SELECT * FROM '.$presta_assu.'  WHERE id_prestation=:id_prestation');
										 	$SelectPrestationName->execute(array('id_prestation'=>$GetPrestaId->id_prestationConsom));
										 	$SelectPrestationName->setFetchMode(PDO::FETCH_OBJ);
										 	$count = $SelectPrestationName->rowCount();		

											if($GetPrestationName=$SelectPrestationName->fetch()){
											 	$Medicament = $GetPrestationName->nompresta;
											 	//echo $GetPrestationName->nompresta.'<br>';
												if($GetPrestationName->namepresta!='')
												{
													echo '- '. $GetPrestationName->namepresta.'<br><hr>';					
													//$prestamedoc[] = $GetPrestationName->namepresta;
												}else{								
													echo '- '.$GetPrestationName->nompresta.'<br><hr>';		
													//$prestamedoc[] = $GetPrestationName->nompresta;
												}
								 			}
										 }
										?>
										</td>
										<td style="text-align: center;font-weight: bold;">
										<?php
										$SeleConsom = $connexion->prepare('SELECT * FROM med_consom WHERE '.$dailydateperso.' AND id_consuConsom=:id_consuConsom');
										$SeleConsom->execute(array('id_consuConsom'=>$ligneNurseReport->id_consuConsom));
										$SeleConsom->setFetchMode(PDO::FETCH_OBJ);
										$count = $SeleConsom->rowCount();
										//echo $ligneNurseReport->id_consuConsom;
										while($GetPrestaId = $SeleConsom->fetch()){
										 	$Medicament = $GetPrestationName->nompresta;
										 	echo $GetPrestaId->qteConsom.'<br><hr>';
										 }
										?>
										</td>
									


									<!-- <td style="text-align: center;">
										<?php
										 	echo $ligneNurseReport->qteConsom;
										?>
									</td> -->

									<td style="font-weight: bold;text-align: center;">
										<?php
										if($ligneNurseReport->id_uInfConsom != 0){
										    $SelectNurseName = $connexion->prepare("SELECT * FROM Utilisateurs uti WHERE  uti.id_u=:id_u");
										 	$SelectNurseName->execute(array('id_u'=>$ligneNurseReport->id_uInfConsom));
										 	$SelectNurseName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetName=$SelectNurseName->fetch();
										 	$nurseName = $GetName->full_name;
										 	//echo $count=$SelectNurseName->rowCount();
										 	echo $GetName->full_name;
										 }else{
										 	echo "----";
										 	$nurseName ="-----";
										 }
										?>
									</td>

									<td style="font-weight: bold;text-align: center;">
										<?php
										    $SelectDocName = $connexion->prepare("SELECT * FROM Utilisateurs uti WHERE  uti.id_u=:id_u");
										 	$SelectDocName->execute(array('id_u'=>$ligneNurseReport->id_uM));
										 	$SelectDocName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetName=$SelectDocName->fetch();
										 	$doctorName = $GetName->full_name;

										 	//echo $count=$SelectNurseName->rowCount();
										 	echo $GetName->full_name;
										?>
									</td>
									
								</tr>
							<?php
								//$numMonth++;
								$dateconsuTest = $ligneNurseReport->dateconsu;

								$arrayGnlBillReport[$i][0]=$compteur;
								$arrayGnlBillReport[$i][1]=$dateconsuTest;
								$arrayGnlBillReport[$i][2]=$Patientsname;
								$arrayGnlBillReport[$i][3]=$Medicament;
								$arrayGnlBillReport[$i][4]=$nurseName;		
								$arrayGnlBillReport[$i][5]=$doctorName ;		

								$compteur++;

							}
							?>
							</tbody>
				</table>
				<?php
						
					
					/*$objPHPExcel->setActiveSheetIndex(0)
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
								->setCellValue('S'.(10+$i).'', ''.$TotalGnlPriceInsu.'');*/
				}
				?>
			</div>
			
			<?php
			
				
				if(isset($_GET['createReportExcel']))
				{
					echo "string";
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$Careportsn=str_replace('/', '_', $sn);
					
					
					if($_GET['caVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/'.$Careportsn.'.xlsx');
								
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/");</script>';
						
					}else{
						if($_GET['caVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/'.$Careportsn.'.xlsx');
								
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/");</script>';
							
						}else{
							if($_GET['caVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/'.$Careportsn.'.xlsx');
								
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/");</script>';
								
							}else{
								if($_GET['caVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/'.$Careportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/");</script>';
									
								}else{
									if($_GET['caVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/'.$Careportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
					
				}
				
				
				if((isset($_GET['createReportExcel']) /*OR isset($_GET['createReportPdf'])*/) AND $_GET['createRN']==1)
				{				
					if($_GET['caVisit']=='dailyPersoMedic')
					{
						createRN('CRD');
						
					}else{
						if($_GET['caVisit']=='monthlyPersoMedic')
						{
							createRN('CRM');

						}else{
							if($_GET['caVisit']=='annualyPersoMedic')
							{
								createRN('CRA');
								
							}else{
								if($_GET['caVisit']=='customPersoMedic')
								{	
									createRN('CRC');
								
								}else{
									if($_GET['caVisit']=='gnlPersoMedic')
									{
										createRN('CRG');
									
									}
								}
							}
						}
					}
					$caVisit=$_GET['caVisit'];
					/*echo "<script text='text/javascript'>document.location.href='infirmier_report.php?codeI=".$_GET['codeI']."&dailydateperso=".$dailydateperso if(isset($_GET[\'dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createReportPdf=ok&createRN=<?php echo $createRN;?>'</script>";
	*/
					echo '<script text="text/javascript">document.location.href="infirmier_report.php?codeI='.$_GET['codeI'].'&dailydateperso='.$_GET['dailydateperso'].'&caVisit='.$_GET['caVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
				}
			}

			// echo $_GET['dailydateperso'];
			if (isset($_GET['Hospitalisation'])) {
					
			?>
			<div id="divPersoBillReport">
		
				<?php
							
				$resultCashierBillReport=$connexion->query('SELECT *FROM patients_hosp WHERE '.$dailydateperso.'');
				/*$resultCashierBillReport->execute(array(
				'codeCa'=>$codeI
				));*/
				
				$resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);

				$compCashBillReport=$resultCashierBillReport->rowCount();

				if($compCashBillReport!=0)
				{
					
					// $objPHPExcel->setActiveSheetIndex(0)
					// 			->setCellValue('A9', 'N°')
					// 			->setCellValue('B9', 'Full name')
					// 			->setCellValue('C9', 'Insurance')
					// 			->setCellValue('D9', 'Date D\'entree')
					// 			->setCellValue('E9', 'Date De Sortie')
					// 			->setCellValue('F9', 'Nursing Care')
					// 			->setCellValue('G9', 'Medicament')
					// 			->setCellValue('H9', 'Consommable')
					// 			->setCellValue('I9', 'Other')
					// 			->setCellValue('J9', 'Doctor');
					
				?>
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
								
					<thead>
						<tr>

							<th style="width:10%; text-align: center;">N°</th>
							<th style="width:12%; text-align: center;">Full name</th>
							<th style="width:5%; text-align: center;">Insurance</th>
							<th style="width:5%; text-align: center;">Date D'entree</th>
							<th style="width:5%; text-align: center;">Date De Sortie</th>
							<th style="width:15%; text-align: center;">Nursing Care</th>
							<th style="width:20%; text-align: center;">Medicament</th>
							<th style="width:20%; text-align: center;">Consommable</th>
							<th style="width:20%; text-align: center;">Other</th>
							<th style="width:20%; text-align: center;">Doctor</th>

						</tr> 
					</thead> 
					
					<tbody>
						<?php
						$compteur=1;
						$i = 0;
						
							while($ligneNurseReport=$resultCashierBillReport->fetch())//on recupere la liste des éléments
							{
								
						?>
						
								<td>
									<?php echo $compteur; ?>
								</td>
								
								<td>
									<?php
									 	$SelectPaName = $connexion->prepare("SELECT * FROM Utilisateurs uti,Patients pa WHERE uti.id_u=pa.id_u AND  pa.numero=:numero");
									 	$SelectPaName->execute(array('numero'=>$ligneNurseReport->numero));
									 	$SelectPaName->setFetchMode(PDO::FETCH_OBJ);
									 	$GetName=$SelectPaName->fetch();
									 	echo $GetName->full_name.' <b>('. $GetName->numero.')</b>';
									?>
								</td>
								<td style="text-align: center;">
									<?php 
										echo $ligneNurseReport->nomassuranceHosp;
										$presta_assuHosp='prestations_'.$ligneNurseReport->nomassuranceHosp;
									?>
								</td>
								<td>
									<?php 
										echo $ligneNurseReport->dateEntree;
									?>

								</td>
								<td>
									<?php 
										echo $ligneNurseReport->dateSortie;
									?>
								</td>
								<td style="text-align: center;">
									<?php
										$selectnurseHosp = $connexion->prepare('SELECT * FROM med_inf_hosp mi WHERE mi.numero=:num AND mi.id_hospInf=:id_hosp ');
										$selectnurseHosp->execute(array(
											'num'=>$ligneNurseReport->numero,
											'id_hosp'=>$ligneNurseReport->id_hosp
										)); 
										$selectnurseHosp->setFetchMode(PDO::FETCH_OBJ);
									 	while($GetPrestationNameHosp=$selectnurseHosp->fetch()){

									 		$SelectPrestationName = $connexion->prepare("SELECT * FROM ".$presta_assuHosp." WHERE id_prestation=:id_prestation ");
										 	$SelectPrestationName->execute(array('id_prestation'=>$GetPrestationNameHosp->id_prestation));
										 	$SelectPrestationName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetPrestationName=$SelectPrestationName->fetch();
										 	echo '<p style="font-size:10px;border:1px solid #ddd;padding:7px;">- '.$GetPrestationName->nompresta.' <b style="color:#A00000;">('.$GetPrestationNameHosp->qteInf.')</b><br><p>';
									 	}
									 	
									?>
								</td>
								
								<td style="text-align: center;">
									<?php
										$selectnurseHosp = $connexion->prepare('SELECT * FROM med_medoc_hosp medoc WHERE medoc.numero=:num AND medoc.id_hospMedoc=:id_hosp ');
										$selectnurseHosp->execute(array(
											'num'=>$ligneNurseReport->numero,
											'id_hosp'=>$ligneNurseReport->id_hosp
										)); 
										$selectnurseHosp->setFetchMode(PDO::FETCH_OBJ);
									 	while($GetPrestationNameHosp=$selectnurseHosp->fetch()){

									 		$SelectPrestationName = $connexion->prepare("SELECT * FROM ".$presta_assuHosp." WHERE id_prestation=:id_prestation ");
										 	$SelectPrestationName->execute(array('id_prestation'=>$GetPrestationNameHosp->id_prestationMedoc));
										 	$SelectPrestationName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetPrestationName=$SelectPrestationName->fetch();
										 	echo '<p style="font-size:10px;border:1px solid #ddd;padding:7px;">- '.$GetPrestationName->nompresta.' <b style="color:#A00000;">('.$GetPrestationNameHosp->qteMedoc.')</b><br></p>';
									 	}
									 	
									?>
								</td>

								<td style="text-align: center;">
									<?php
										$selectnurseHosp = $connexion->prepare('SELECT * FROM  med_consom_hosp consom WHERE consom.numero=:num AND consom.id_hospConsom=:id_hosp ');
										$selectnurseHosp->execute(array(
											'num'=>$ligneNurseReport->numero,
											'id_hosp'=>$ligneNurseReport->id_hosp
										)); 
										$selectnurseHosp->setFetchMode(PDO::FETCH_OBJ);
									 	while($GetPrestationNameHosp=$selectnurseHosp->fetch()){

									 		$SelectPrestationName = $connexion->prepare("SELECT * FROM ".$presta_assuHosp." WHERE id_prestation=:id_prestation ");
										 	$SelectPrestationName->execute(array('id_prestation'=>$GetPrestationNameHosp->id_prestationConsom));
										 	$SelectPrestationName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetPrestationName=$SelectPrestationName->fetch();
										 	echo '<p style="font-size:10px;border:1px solid #ddd;padding:7px;">- '.$GetPrestationName->nompresta.' <b style="color:#A00000;">('.$GetPrestationNameHosp->qteConsom.')</b>'.'<br></p>';
									 	}
									 	
									?>
								</td>


								<td style="text-align: center;">
									<?php
										$selectnurseHosp = $connexion->prepare('SELECT * FROM  med_consult_hosp consult WHERE consult.numero=:num AND consult.id_hospMed=:id_hosp ');
										$selectnurseHosp->execute(array(
											'num'=>$ligneNurseReport->numero,
											'id_hosp'=>$ligneNurseReport->id_hosp
										)); 
										$selectnurseHosp->setFetchMode(PDO::FETCH_OBJ);
									 	while($GetPrestationNameHosp=$selectnurseHosp->fetch()){

									 		$SelectPrestationName = $connexion->prepare("SELECT * FROM ".$presta_assuHosp." WHERE id_prestation=:id_prestation ");
										 	$SelectPrestationName->execute(array('id_prestation'=>$GetPrestationNameHosp->id_prestationConsu));
										 	$SelectPrestationName->setFetchMode(PDO::FETCH_OBJ);
										 	$GetPrestationName=$SelectPrestationName->fetch();
										 	echo '<p style="font-size:10px;border:1px solid #ddd;padding:7px;">- '.$GetPrestationName->nompresta.' <b style="color:#A00000;">('.$GetPrestationNameHosp->qteConsu.')</b>'.'<br></p>';
									 	}
									 	
									?>
								</td>

								<td style="font-weight: bold;text-align: center;">
									<?php
										if ($ligneNurseReport->id_consuHosp != 0) {

											$selectInfoConsu = $connexion->prepare('SELECT id_uM FROM consultations WHERE id_consu=:id_consu');
											$selectInfoConsu->execute(array(
												'id_consu'=>$ligneNurseReport->id_consuHosp
											));
											$selectInfoConsu->setFetchMode(PDO::FETCH_OBJ);
											while ($ligneInfoConsu = $selectInfoConsu->fetch()) {
											    $SelectDocName = $connexion->prepare("SELECT * FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND u.id_u=:id_u");
											 	$SelectDocName->execute(array('id_u'=>$ligneInfoConsu->id_uM));
											 	$SelectDocName->setFetchMode(PDO::FETCH_OBJ);
											 	$GetName=$SelectDocName->fetch();
											 	//echo $count=$SelectNurseName->rowCount();
											 	echo $GetName->full_name;
											}
										}else{
											echo "---";
										}
									?>
								</td>
								
							</tr>
							<?php
								//$numMonth++;
								/*$dateconsuTest = $ligneNurseReport->dateconsu;

								$arrayGnlBillReport[$i][0]=$compteur;
								$arrayGnlBillReport[$i][1]=$dateconsuTest;
								$arrayGnlBillReport[$i][2]=$Patientsname;
								$arrayGnlBillReport[$i][3]=$Medicament;
								$arrayGnlBillReport[$i][4]=$nurseName;		
								$arrayGnlBillReport[$i][5]=$doctorName ;*/		

								$compteur++;

							}
							?>
							</tbody>
				</table>
				<?php
						
					
					/*$objPHPExcel->setActiveSheetIndex(0)
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
								->setCellValue('S'.(10+$i).'', ''.$TotalGnlPriceInsu.'');*/
				}
				?>
			</div>
			
			<?php
			
				
				if(isset($_GET['createReportExcel']))
				{
					echo "string";
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$Careportsn=str_replace('/', '_', $sn);
					
					
					if($_GET['caVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/'.$Careportsn.'.xlsx');
								
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/");</script>';
						
					}else{
						if($_GET['caVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/'.$Careportsn.'.xlsx');
								
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/");</script>';
							
						}else{
							if($_GET['caVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/'.$Careportsn.'.xlsx');
								
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/");</script>';
								
							}else{
								if($_GET['caVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/'.$Careportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/");</script>';
									
								}else{
									if($_GET['caVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/'.$Careportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
					
				}
				
				
				if((isset($_GET['createReportExcel']) /*OR isset($_GET['createReportPdf'])*/) AND $_GET['createRN']==1)
				{				
					if($_GET['caVisit']=='dailyPersoMedic')
					{
						createRN('CRD');
						
					}else{
						if($_GET['caVisit']=='monthlyPersoMedic')
						{
							createRN('CRM');

						}else{
							if($_GET['caVisit']=='annualyPersoMedic')
							{
								createRN('CRA');
								
							}else{
								if($_GET['caVisit']=='customPersoMedic')
								{	
									createRN('CRC');
								
								}else{
									if($_GET['caVisit']=='gnlPersoMedic')
									{
										createRN('CRG');
									
									}
								}
							}
						}
					}
					$caVisit=$_GET['caVisit'];
					/*echo "<script text='text/javascript'>document.location.href='infirmier_report.php?codeI=".$_GET['codeI']."&dailydateperso=".$dailydateperso if(isset($_GET[\'dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createReportPdf=ok&createRN=<?php echo $createRN;?>'</script>";
	*/
					echo '<script text="text/javascript">document.location.href="infirmier_report.php?codeI='.$_GET['codeI'].'&dailydateperso='.$_GET['dailydateperso'].'&caVisit='.$_GET['caVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
				}
			}		}
	}

			// echo $_GET['dailydateperso'];
			if (isset($_GET['petitchirugie'])) {
					
			?>
			<div id="divPersoBillReport">
		
				<?php
							
			$nompresta = array('CIRCONCISION','pansement simple','pansement complique','Suture simple','SUTURE COMPLIQUE','seringuage','Incision d\'abces superficiel','Curetage molluscum','Exérése de Kyste-Lipome','Injection intra-articulaire','injection im','injection iv','injection sc','Incision + Drainage d\'abces profond','Ongle incarnée','Suture tendons','reduction de l\'epaule','Bains de pieds','Bains de siège','Infiltraton','Pansement Humide','Insertion DIU','Insertion de Norplan','Incision frein-langue','Extraction corps étranger superficiel','Soins locaux','Suture plaie nez','Sonde Gastrique','sonde urinaire','Temponement du nez');
			//print_r($nompresta);
			//echo sizeof($nompresta);
			$resultArray = array();
			$resultMed = array();
			$resultNurse = array();
			$dateconsu = array();
			for ($i=0; $i < sizeof($nompresta); $i++) { 
				//echo "nom = ".$nompresta[$i].'<br>';

				$nbrecirc = 0;
				//echo "ii = ".$i;

				//echo "nom = ".$nompresta[$i].'<br>';
				$nameprestaS = $nompresta[$i];

				$GetAssu1 = $connexion->query('SELECT * FROM assurances');
				$GetAssu1->setFetchMode(PDO::FETCH_OBJ);
				$AssuAccount = $GetAssu1->rowCount();

				while ($ligneNomAssu = $GetAssu1->fetch()) {
					//echo "<br>nameprestaS = ".$nameprestaS;
					$presta_assuConsu = 'prestations_'.$ligneNomAssu->nomassurance;
					$idAssur = $ligneNomAssu->id_assurance;

					$GetIdprestation = $connexion->query('SELECT * FROM '.$presta_assuConsu.' WHERE nompresta IN ("'.$nameprestaS.'") ');
					$GetIdprestation->setFetchMode(PDO::FETCH_OBJ);
					$Pcount = $GetIdprestation->rowCount();

					//echo "Pcount = ".$Pcount.'<br>';
					//echo "nom = ".$nameprestaS.'<br>';


					while($FetchIdPresta = $GetIdprestation->fetch()){

						//echo 'ASSU='.$presta_assuConsu.',idPrestation = '.$FetchIdPresta->id_prestation.'<br>';

						$idPrestationF = $FetchIdPresta->id_prestation;

						$SelectMedinf = $connexion->prepare('SELECT * FROM med_inf mf WHERE mf.id_prestation=:id_prestation AND mf.id_assuInf=:id_assuInf AND '.$dailydateperso.'');
						$SelectMedinf->execute(array('id_prestation'=>$idPrestationF,'id_assuInf'=>$idAssur));
						$SelectMedinf->setFetchMode(PDO::FETCH_OBJ);
						$countF = $SelectMedinf->rowCount();
						$nbrecirc += $countF;

						if($GetInfo = $SelectMedinf->fetch()){
							$resultMed[]= $GetInfo->id_uM;
							$resultNurse[]= $GetInfo->id_uI;
							$dateconsu[]=$GetInfo->dateconsu;
						}

						//echo 'ASSU='.$presta_assuConsu.',idPrestation = '.$FetchIdPresta->id_prestation.',count='.$countF.'<br>';
						//echo 'assu= '.$presta_assuConsu.','.$nompresta[$i].','.$countF.'<br>'	;
					}
				}
				$resultArray[$i] = $nbrecirc; 
			}

				if($nbrecirc==0)
				{
					
					// $objPHPExcel->setActiveSheetIndex(0)
					// 			->setCellValue('A9', 'N°')
					// 			->setCellValue('B9', 'Full name')
					// 			->setCellValue('C9', 'Insurance')
					// 			->setCellValue('D9', 'Date D\'entree')
					// 			->setCellValue('E9', 'Date De Sortie')
					// 			->setCellValue('F9', 'Nursing Care')
					// 			->setCellValue('G9', 'Medicament')
					// 			->setCellValue('H9', 'Consommable')
					// 			->setCellValue('I9', 'Other')
					// 			->setCellValue('J9', 'Doctor');
					
				?>
					<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:80%;">
						<thead>
							<tr>
								<th style="text-align: center;">#</th>
								<th style="text-align: center;">Prestation</th>
								<th style="text-align: center;">Quantity</th>
		<!-- 						<th style="text-align: center;">Date consultation</th>
								<th style="text-align: center;">Nurse</th>
								<th style="text-align: center;">Doctor</th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
							$compteur = 1;
							for($x=0;$x<sizeof($nompresta);$x++){ 

									// $GetDocName = $connexion->prepare('SELECT * FROM Utilisateurs WHERE id_u=:id_u');
									// $GetDocName->execute(array('id_u'=>$resultMed[$x]));
									// $GetDocName->setFetchMode(PDO::FETCH_OBJ);

									// if($GetName = $GetDocName->fetch()){
									// 	$DocName = $GetName->full_name;
									// }else{
									// 	$DocName = '----';
									// }					

									// $GetNurseName = $connexion->prepare('SELECT * FROM Utilisateurs WHERE id_u=:id_u');
									// $GetNurseName->execute(array('id_u'=>$resultNurse[$x]));
									// $GetNurseName->setFetchMode(PDO::FETCH_OBJ);

									// if($GetNameN = $GetNurseName->fetch()){
									// 	$NurseName = $GetNameN->full_name;
									// }else{
									// 	$NurseName = '----';
									// }
							?>
								<tr>
									<td style="text-align: center;"><?php echo $compteur; ?></td>
									<td style="text-align: center;"><?php echo $nompresta[$x]; ?></td>
									<td style="text-align: center;"><?php echo $resultArray[$x]; ?></td>
									<!-- <td style="text-align: center;"><?php echo $dateconsu[$x];?></td> -->
									<!-- <td style="text-align: center;"><?php echo $NurseName.'<br>';?></td>
									<td style="text-align: center;"><?php echo $DocName.'<br>';?></td> -->
								<!-- 	<td></td>
									<td></td>
									<td style="text-align: center;"></td> -->
								</tr>
							<?php
								//$numMonth++;
								/*$dateconsuTest = $ligneNurseReport->dateconsu;

								$arrayGnlBillReport[$i][0]=$compteur;
								$arrayGnlBillReport[$i][1]=$dateconsuTest;
								$arrayGnlBillReport[$i][2]=$Patientsname;
								$arrayGnlBillReport[$i][3]=$Medicament;
								$arrayGnlBillReport[$i][4]=$nurseName;		
								$arrayGnlBillReport[$i][5]=$doctorName ;*/		

								$compteur++;

							}
							?>
							</tbody>
				</table>
				<?php
						
					
					/*$objPHPExcel->setActiveSheetIndex(0)
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
								->setCellValue('S'.(10+$i).'', ''.$TotalGnlPriceInsu.'');*/
				}
				?>
			</div>
			
			<?php
			
				
				if(isset($_GET['createReportExcel']))
				{
					echo "string";
					$callStartTime = microtime(true);

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					
					$Careportsn=str_replace('/', '_', $sn);
					
					
					if($_GET['caVisit']=='dailyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/'.$Careportsn.'.xlsx');
								
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Daily/");</script>';
						
					}else{
						if($_GET['caVisit']=='monthlyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/'.$Careportsn.'.xlsx');
								
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Monthly/");</script>';
							
						}else{
							if($_GET['caVisit']=='annualyPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/'.$Careportsn.'.xlsx');
								
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Annualy/");</script>';
								
							}else{
								if($_GET['caVisit']=='customPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/'.$Careportsn.'.xlsx');
								
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Custom/");</script>';
									
								}else{
									if($_GET['caVisit']=='gnlPersoBill')
									{
										$objWriter->save('C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/'.$Careportsn.'.xlsx');
								
										$callEndTime = microtime(true);
										$callTime = $callEndTime - $callStartTime;
										
										echo '<script type="text/javascript"> alert("File name : '.$Careportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/NurseReport/Alltimes/");</script>';
										
									}
								}
							}
						}
					}
					
				}
				
				
				if((isset($_GET['createReportExcel']) /*OR isset($_GET['createReportPdf'])*/) AND $_GET['createRN']==1)
				{				
					if($_GET['caVisit']=='dailyPersoMedic')
					{
						createRN('CRD');
						
					}else{
						if($_GET['caVisit']=='monthlyPersoMedic')
						{
							createRN('CRM');

						}else{
							if($_GET['caVisit']=='annualyPersoMedic')
							{
								createRN('CRA');
								
							}else{
								if($_GET['caVisit']=='customPersoMedic')
								{	
									createRN('CRC');
								
								}else{
									if($_GET['caVisit']=='gnlPersoMedic')
									{
										createRN('CRG');
									
									}
								}
							}
						}
					}
					$caVisit=$_GET['caVisit'];
					/*echo "<script text='text/javascript'>document.location.href='infirmier_report.php?codeI=".$_GET['codeI']."&dailydateperso=".$dailydateperso if(isset($_GET[\'dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createReportPdf=ok&createRN=<?php echo $createRN;?>'</script>";
	*/
					echo '<script text="text/javascript">document.location.href="infirmier_report.php?codeI='.$_GET['codeI'].'&dailydateperso='.$_GET['dailydateperso'].'&caVisit='.$_GET['caVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
				}
			}
	
	?>

	</div>
	<div class="account-container" style="margin: 10px auto auto; width:90%; background:#fff; padding:5px; border-radius:3px; font-size:80%;">
	
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
	
<?php

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
}
?>
</body>

</html>
