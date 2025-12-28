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
		}else{
			$fullname = "";
			$adresse ="";
		}
		
		$codeI=$_GET['codeI'];
		$dailydateperso=$_GET['dailydateperso'];
		$caVisit=$_GET['caVisit'];
				

		// $dailydateperso;
		if (!isset($_SESSION['codeC']) OR isset($_GET['codeI'])) {
			
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

		if (!isset($_SESSION['codeC']) OR isset($_GET['codeI'])) {
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
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Nurse Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="infirmier_report.php?codeI=<?php echo $_GET['codeI'];?>&dailydateperso=<?php echo $dailydateperso;?><?php if(isset($_GET['dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<?php
				/*if(isset($_SESSION['codeC']))
				{*/
				?>
				<td style="text-align:left">
					
					<form method="post" action="infirmier_report.php?codeI=<?php echo $_GET['codeI'];?>&dailydateperso=<?php echo $dailydateperso;?><?php if(isset($_GET['dailydatebillPerso'])){ echo '&dailydatebillPerso='.$_GET['dailydatebillPerso'];}?><?php if(isset($_GET['searchmonthlybillPerso'])){ echo '&searchmonthlybillPerso=ok'; if(isset($_GET['monthlydatebillPerso']) AND isset($_GET['monthlydatebillPersoYear'])){ echo '&monthlydatebillPerso='.$_GET['monthlydatebillPerso'].'&monthlydatebillPersoYear='.$_GET['monthlydatebillPersoYear'];}}?>&caVisit=<?php echo $caVisit;?>&stringResult=<?php echo $stringResult;?>&divPersoBillReport=ok&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>				
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
	if (!isset($_SESSION['codeC'])) {
			
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
		if(isset($_GET['divPersoBillReport']))
		{
			// echo $_GET['dailydateperso'];
		?>
		<div id="divPersoBillReport">
	
			<?php
						
			$resultCashierBillReport=$connexion->query('SELECT *FROM consultations WHERE '.$dailydateperso.' ORDER BY id_consu ASC');
			/*$resultCashierBillReport->execute(array(
			'codeCa'=>$codeI
			));*/
			
			$resultCashierBillReport->setFetchMode(PDO::FETCH_OBJ);

			$compCashBillReport=$resultCashierBillReport->rowCount();

			if($compCashBillReport!=0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A9', 'N° Month')
							->setCellValue('B9', 'N° Day')
							->setCellValue('C9', 'Date')
							->setCellValue('D9', 'Full name')
							->setCellValue('E9', 'Age ')
							->setCellValue('F9', 'Sexe')
							->setCellValue('G9', 'Poids')
							->setCellValue('H9', 'Taille')
							->setCellValue('I9', 'Insurance type')
							->setCellValue('J9', 'Address')
							->setCellValue('K9', 'Z/HZ/HD')
							->setCellValue('L9', 'Nursing Care')
							->setCellValue('M9', 'NC/AC')
							/*->setCellValue('N9', 'Status d\' Enregistrement')*/
							->setCellValue('N9', 'Plaintes/ Symptome / Signes Cliniques')
							->setCellValue('O9', 'Examen Labo')
							->setCellValue('P9', 'Resultat Labo')
							->setCellValue('Q9', 'Diagnostic')
							->setCellValue('R9', 'Traitement');
				
			?>
			<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
							
				<thead>
					<tr>

						<th style="border-right: 1px solid #bbb;text-align:center;">N° Month</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">N° Day</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Date</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Full name</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Age</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Sexe</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Poids</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Taille</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Oxgen</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Insurance type</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Address</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Z/HZ/HD</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">NC/AC</th>
						<!-- <th style="border-right: 1px solid #bbb;text-align:center;">Status d' Enregistrement</th> -->
						<th style="border-right: 1px solid #bbb;text-align:center;" colspan="2">Plaintes/Symptome/Signes Cliniques</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Examen Labo</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Resultat Labo</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Diagnostic</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Traitement</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Doctor</th>

					</tr> 
				</thead> 
				
				<tbody>
					<?php
					$compteur=1;
					$numMonth = 1;
					$numcustom = 1;
					$numDay = 1;
					$j=0;

						while($ligneCashierBillReport=$resultCashierBillReport->fetch())//on recupere la liste des éléments
						{
							//$TotalDayPrice=0;
					?>
					
							<tr style="text-align:center;">
								<td>
									<?php
										if ((isset($_GET['dailydatebillPerso'])) AND !(isset($_GET['searchmonthlybillPerso']))) {
											if ($compteur == 1) {
												$id = $ligneCashierBillReport->id_consu;
												$date = date_create($_GET['dailydatebillPerso']);
												$mois = $date->format('m');
												$year = $date->format('Y');

												$daysmonth= cal_days_in_month(CAL_GREGORIAN,$mois,$year);
												if($daysmonth<10)
												{
													$daysmonth='0'.$daysmonth;
												}
												
												$dailydateperso = 'dateconsu>=\''.$year.'-'.$mois.'-01\' AND (dateconsu<\''.$year.'-'.$mois.'-'.$daysmonth.'\' OR dateconsu LIKE \''.$year.'-'.$year.'-'.$daysmonth.'%\')';


												$resultCount=$connexion->query('SELECT * FROM consultations WHERE '.$dailydateperso.' AND id_consu<="'.$id.'" ORDER BY id_consu ASC');		
												
												$resultCount->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

												$comptresultCount=$resultCount->rowCount();

												

												while ($ligneresultCount = $resultCount->fetch()) {
													//echo $numMonth.' date= '.$year.'-'.$mois.'<br>';
													$numMonth++;
												}
											}else{
												$numMonth++;
											}
											echo $numMonth;
										}else{
											/*if (isset($_GET['searchcustombillPerso'])) {
												if ($compteur == 1) {
													
												}else{
													$numcustom++;
												}
											}*/
											if (isset($_GET['searchmonthlybillPerso'])) {
												echo $numMonth;
												$numMonth++;
											}
										}

										
									?>
								</td>
								<td>
									<?php
										if (isset($_GET['searchmonthlybillPerso'])) {
											$dateconsu = $ligneCashierBillReport->dateconsu;
											$id = $ligneCashierBillReport->id_consu;
											//echo "dateconsu = ".$dateconsu."<br>";

											$resultCountDay=$connexion->query('SELECT * FROM consultations WHERE dateconsu="'.$dateconsu.'" AND id_consu="'.$id.'" ORDER BY id_consu ASC');		
												
											$resultCountDay->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptresultCountDay=$resultCountDay->rowCount();

											

											while ($ligneresultCountDay = $resultCountDay->fetch()) {
												//echo $numMonth.' date= '.$year.'-'.$mois.'<br>';
												
												//echo "string";
												//echo $numDay;
												if($compteur == 1){
													$dateconsuTest = $ligneCashierBillReport->dateconsu;
												}

												if ($dateconsu == $dateconsuTest) {
													//echo $numDay;
													//$numDay++;
												}else{
													$numDay = 1;
													//echo $numDay;
												}
											}
											echo $numDay;

										}else{
											// $numDay++;
											echo $numDay;
											$numDay++;
										}
										//echo $numDay;
									?>
								</td>
								<td>
									<?php
										$dateconsu = $ligneCashierBillReport->dateconsu;
										echo $dateconsu;
									?>
								</td>
								<td>
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneCashierBillReport->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullname = $lignePatient->full_name.' ('.$lignePatient->numero.' )';
											
											echo $fullname;
										}else{
											echo '';
										}
									?>
								</td>
								<td>
									<?php
										$dateN = $lignePatient->date_naissance;

										$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//reçoit l'année de naissance
										$month=$dateN[5].''.$dateN[6].'	';//reçoit le mois de naissance

										$an= date('Y')-$old.'	';//recupere l'âge en année
										$mois= date('m')-$month.'	';//recupere l'âge en mois

										if($mois<0)
										{
											$an= ($an-1).' ans	'.(12+$mois).' mois';
											// echo $an= $an-1;

										}else{

											$an= $an.' ans';
											//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
											// echo $mois= date('m')-$month;
										}
										echo $an;
									?>
								</td>
								<td>
									<?php
										$sexe = $lignePatient->sexe;
										if ($sexe == 'M') {
											echo "Male";
										}else{
											if ($sexe == 'F') {
												echo "Female";
											}else{
												echo "";
											}
										}
									?>
								</td>
								<td>
									<?php
										$poids = $ligneCashierBillReport->poids;
										if ($poids != "") {
											echo $poids.' Kg';
										}else{
											echo "---";
										}
										
									?>
								</td>
								<td>
									<?php
										$taille = $ligneCashierBillReport->taille;
										if ($taille != "") {
											echo $taille.' Cm';
										}else{
											echo "---";
										}
									?>
								</td>	

								<td>
									<?php
										$oxgen = $ligneCashierBillReport->oxgen;
										if ($oxgen != "") {
											echo $oxgen.' Cm';
										}else{
											echo "---";
										}
									?>
								</td>
								<td>
									<?php
										$idassu = $ligneCashierBillReport->id_assuConsu;

										$comptAssuConsu = $connexion->query('SELECT * FROM assurances a ORDER BY a.id_assurance');
										$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										$assuCount = $comptAssuConsu->rowCount();

										for ($i=1; $i <=$assuCount ; $i++) { 
											$getAssuConsu=$connexion->prepare('SELECT * FROM assurances a  WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
											$getAssuConsu->execute(array(
												'idassu'=>$idassu
											));
											$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											if ($ligneNomAssu=$getAssuConsu->fetch()) {
												$prestations_assu = 'prestations_'.$ligneNomAssu->nomassurance;
											}
										}

										echo $ligneNomAssu->nomassurance.' ('.$ligneCashierBillReport->insupercent.' %)';
										$assuranceName = $ligneNomAssu->nomassurance.' ('.$ligneCashierBillReport->insupercent.' %)';
									?>
								</td>
								<td>
									<?php
										$idProvince = $lignePatient->province;
										$selectnameProvince = $connexion->prepare("SELECT * FROM province WHERE id_province=:idProvince");
										$selectnameProvince->execute(array(
											'idProvince'=>$idProvince
										));
										$selectnameProvince->setFetchMode(PDO::FETCH_OBJ);
										$ligneselectnameProvince = $selectnameProvince->fetch();
										if ($idProvince != 6) {
											$iddistrict = $lignePatient->district;
											$selectnameDistrict = $connexion->prepare("SELECT * FROM district WHERE id_district=:idDistrict AND id_province=:idProvince");
											$selectnameDistrict->execute(array(
												'idDistrict'=>$iddistrict,
												'idProvince'=>$idProvince
											));
											$selectnameDistrict->setFetchMode(PDO::FETCH_OBJ);
											$ligneselectnameDistrict = $selectnameDistrict->fetch();

											$idsector = $lignePatient->secteur;
											$selectnameSector = $connexion->prepare("SELECT * FROM sectors WHERE id_sector=:idSector AND id_district=:idDistrict");
											$selectnameSector->execute(array(
												'idSector'=>$idsector,
												'idDistrict'=>$iddistrict
											));
											$selectnameSector->setFetchMode(PDO::FETCH_OBJ);
											$ligneselectnameSector = $selectnameSector->fetch();

											echo $ligneselectnameProvince->nomprovince.", ".$ligneselectnameDistrict->nomdistrict.", ".$ligneselectnameSector->nomsector;
											$adresse = $ligneselectnameProvince->nomprovince.", ".$ligneselectnameDistrict->nomdistrict.", ".$ligneselectnameSector->nomsector;
										}else{
											echo $lignePatient->autreadresse;
											$adresse = $lignePatient->autreadresse;
										}
									?>
								</td>
								<td>
									<?php
										if ($iddistrict == 1) {
											if ($idsector == 6) {
												echo "Z";
												$ZHZHD = "Z";
											}else{
												echo "HZ";
												$ZHZHD = "HZ";
											}
										}else{
											echo "HD";
											$ZHZHD = "HD";
										}
									?>
								</td>
								<td>
									<?php
										if (($ligneCashierBillReport->dateconsu) == ($lignePatient->anneeadhesion)) {
											echo "NC";
											$NcAc = "NC";
										}else{
											$dateconsu = date_create($ligneCashierBillReport->dateconsu);
											$moisConsu = $dateconsu->format('m');
											$yearConsu = $dateconsu->format('Y');

											$daysmonth= cal_days_in_month(CAL_GREGORIAN,$moisConsu,$yearConsu);
											if($daysmonth<10)
											{
												$daysmonth='0'.$daysmonth;
											}
											
											$datedebut = $yearConsu.'-'.$moisConsu.'-01';
											$datefin = $ligneCashierBillReport->dateconsu;

											$researchNcAc = $connexion->prepare('SELECT * FROM consultations WHERE numero=:num AND dateconsu>=:datedebut AND dateconsu<=:datefin AND id_consu<=:id_consu ORDER BY id_consu');
											$researchNcAc->execute(array(
												'num'=>$lignePatient->numero,
												'datedebut'=>$datedebut,
												'datefin'=>$datefin,
												'id_consu'=>$ligneCashierBillReport->id_consu
											));
											$researchNcAc->setFetchMode(PDO::FETCH_OBJ);
											$compteurresearchNcAc = $researchNcAc->rowCount();
											//echo "compteurresearchNcAc = ".$compteurresearchNcAc;
											$compteurNcAc = 1;

											while ($ligneresearchNcAc = $researchNcAc->fetch()) {
												//echo "numero = ".$ligneresearchNcAc->numero;
												//echo "dateconsu = ".$ligneresearchNcAc->dateconsu;
												$compteurNcAc++;
											}
											if ($compteurresearchNcAc == 1 ) {
												echo "NC";
												$NcAc = "NC";
											}else{
												if ($compteurNcAc == 1) {
													echo "NC";
													$NcAc = "NC";
												}else{
													echo "AC";
													$NcAc = "AC";
												}
												
											}
											
										}
									?>
								</td>
								<!-- <td>
									<?php
										echo "----";
									?>
								</td> -->
								<!-- <td>
									<?php
										if (isset($ligneCashierBillReport->motif)) {
									?>
									<div style="width: 45px;">
									<?php
											echo $ligneCashierBillReport->motif;
										}
										
									?>
									</div>
								</td> -->
								<?php
									//Select the motif 
									$selectmotif = $connexion->prepare("SELECT * FROM med_motif WHERE numero=:numero AND id_consumotif=:id_consu ");
									$selectmotif->execute(array(
										'numero'=>$lignePatient->numero,
										'id_consu'=>$ligneCashierBillReport->id_consu
									));
									$selectmotif->setFetchMode(PDO::FETCH_OBJ);
									$autremotif = "";
									if ($ligneselectmotif = $selectmotif->fetch()) {
										$autremotif = $ligneselectmotif->autremotif;
									}
								?>
								<?php
									
								?>
								<td>
									<?php
										if (isset($autremotif) && $autremotif != "") {
											echo "1) Motif :<br>";
										}
										if (isset($ligneCashierBillReport->anamnese) && $ligneCashierBillReport->anamnese != "") {
											echo "2) Anamnèse :<br>";
										}
										if (isset($ligneCashierBillReport->clihist) && $ligneCashierBillReport->clihist != "") {
											echo "3) Clinical History :<br>";
										}
										if (isset($ligneCashierBillReport->antec) && $ligneCashierBillReport->antec != "") {
											echo "4) Antécédents du patient :<br>";
										}
										if (isset($ligneCashierBillReport->allergie) && $ligneCashierBillReport->allergie != "") {
											echo "5) Allergie :<br>";
										}
										if (isset($ligneCashierBillReport->examcli) && $ligneCashierBillReport->examcli != "") {
											echo "6) Examem Clinical :";
										}
									?>
									
								</td>
								<td>
									<?php 
										
										if (isset($autremotif) && $autremotif != "") {
											echo "1) ".$autremotif."<br>";
										}
										if (isset($ligneCashierBillReport->anamnese) && $ligneCashierBillReport->anamnese != "") {
											echo "2) ".$ligneCashierBillReport->anamnese."<br>";
										}
										if (isset($ligneCashierBillReport->clihist) && $ligneCashierBillReport->clihist != "") {
											echo "3) ".$ligneCashierBillReport->clihist."<br>";
										}
										if (isset($ligneCashierBillReport->antec) && $ligneCashierBillReport->antec != "") {
											echo "4) ".$ligneCashierBillReport->antec."<br>";
										}
										if (isset($ligneCashierBillReport->allergie) && $ligneCashierBillReport->allergie != "") {
											echo "5) ".$ligneCashierBillReport->allergie."<br>";
										}
										if (isset($ligneCashierBillReport->examcli) && $ligneCashierBillReport->examcli != "") {
											echo "6) ".$ligneCashierBillReport->examcli;
										}
									?>
								</td>
								<td class="nurse">
									<?php
										$dateresu = '0000-00-00';
										$examName = '';
 										$selectExa = $connexion->prepare("SELECT id_prestationExa, id_assuLab FROM med_labo WHERE numero=:num AND id_consuLabo=:id_consuLabo AND dateresultats!=:dateresultats");
										$selectExa->execute(array(
											'num'=>$ligneCashierBillReport->numero,
											'id_consuLabo'=>$ligneCashierBillReport->id_consu,
											'dateresultats'=>$dateresu
										));
										$selectExa->setFetchMode(PDO::FETCH_OBJ);
										$compteurselectExa = $selectExa->rowCount();

										while ($ligneselectExa = $selectExa->fetch()) {
											$id_presta = $ligneselectExa->id_prestationExa;
											$id_assuLab = $ligneselectExa->id_assuLab;

											$comptAssuConsu = $connexion->query('SELECT * FROM assurances a ORDER BY a.id_assurance');
											$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											$assuCount = $comptAssuConsu->rowCount();

											for ($i=1; $i <=$assuCount ; $i++) { 
												$getAssuConsu=$connexion->prepare('SELECT * FROM assurances a  WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
												$getAssuConsu->execute(array(
													'idassu'=>$id_assuLab
												));
												$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);
												if ($ligneNomAssu=$getAssuConsu->fetch()) {
													$prestations_assu = 'prestations_'.$ligneNomAssu->nomassurance;
												}
											}
											$selectnomExa=$connexion->prepare('SELECT nompresta FROM '.$prestations_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
											$selectnomExa->execute(array(
											'idPresta'=>$id_presta
											));

											$selectnomExa->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptselectnomExa=$selectnomExa->rowCount();
											while ($ligneselectnomExa = $selectnomExa->fetch()) {
												$examName = $ligneselectnomExa->nompresta;
										?>
										<div style="width: 45px;">
										<?php
												echo $ligneselectnomExa->nompresta;
											}
					
										}
									?>	
										</div>
								</td>
								<td class="nurse">
									<?php
										$dateresu = '0000-00-00';
										$resultatexam = '';
 										$selectresultExa = $connexion->prepare("SELECT autreresultats FROM med_labo WHERE numero=:num AND id_consuLabo=:id_consuLabo AND dateresultats!=:dateresultats");
										$selectresultExa->execute(array(
											'num'=>$ligneCashierBillReport->numero,
											'id_consuLabo'=>$ligneCashierBillReport->id_consu,
											'dateresultats'=>$dateresu
										));
										$selectresultExa->setFetchMode(PDO::FETCH_OBJ);
										$compteurselectresultExa = $selectresultExa->rowCount();
										//echo "compteurselectExa = ".$compteurselectExa;


										while ($ligneselectresultExa = $selectresultExa->fetch()) {
											$resultatexam = $ligneselectresultExa->autreresultats;
									?>
									<div style="width: 45px;">
									<?php
											echo $ligneselectresultExa->autreresultats;
										}
									?>
									</div>
								</td>
								<td class="nurse">
									<?php
									$diagno = '';
										$selectDiagno=$connexion->prepare('SELECT id_postdia,autrepostdia FROM prepostdia WHERE id_consudia=:id_consu ORDER BY id_dia');
										$selectDiagno->execute(array(
											'id_consu'=>$ligneCashierBillReport->id_consu
										));
										$selectDiagno->setFetchMode(PDO::FETCH_OBJ);
										while ($ligneselectDiagno=$selectDiagno->fetch()) {
											if (isset($ligneselectDiagno->id_postdia)) {
												$selectnomDiagno=$connexion->prepare('SELECT nomdiagno FROM diagnostic WHERE id_diagno=:id_diagno');
												$selectnomDiagno->execute(array(
													'id_diagno'=>$ligneselectDiagno->id_postdia
												));
												$selectnomDiagno->setFetchMode(PDO::FETCH_OBJ);
												$ligneselectnomDiagno=$selectnomDiagno->fetch();
												echo $ligneselectnomDiagno->nomdiagno;
												$diagno=$ligneselectnomDiagno->nomdiagno;
											}else{
												$diagno=$ligneselectDiagno->autrepostdia;
									?>
									<div style="width: 45px;">
									<?php
												echo $ligneselectDiagno->autrepostdia;
											}
										}

									?>
									</div>
								</td>
								<td class="nurse">
									<?php
										if (isset($ligneCashierBillReport->recommandations)) {
									?>
									<div style="width: 45px;">
									<?php
											echo $ligneCashierBillReport->recommandations;
										}

									?>
									</div>
								</td>
									<td>
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u WHERE u.id_u=:operation');
										$resultPatient->execute(array(
										'operation'=>$ligneCashierBillReport->id_uM
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient2=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullname = 'Dr . '.$lignePatient2->full_name;
											
											echo $fullname;
										}else{
											echo '';
										}
										//echo $ligneCashierBillReport->id_uM;
									?>
								</td>
								
							</tr>
						<?php
							//$numMonth++;
							$dateconsuTest = $ligneCashierBillReport->dateconsu;

							$arrayGnlBillReport[$j][0]=$numMonth;
							$arrayGnlBillReport[$j][1]=$numDay;
							$arrayGnlBillReport[$j][2]=$ligneCashierBillReport->dateconsu;
							$arrayGnlBillReport[$j][3]=$fullname;
							$arrayGnlBillReport[$j][4]=$an;
							$arrayGnlBillReport[$j][5]=$lignePatient->sexe;			
							$arrayGnlBillReport[$j][6]=$ligneCashierBillReport->poids;
							$arrayGnlBillReport[$j][7]=$ligneCashierBillReport->taille;
							$arrayGnlBillReport[$j][8]=$ligneNomAssu->nomassurance.' ('.$ligneCashierBillReport->insupercent.' %)';			
							$arrayGnlBillReport[$j][9]=$adresse;
							$arrayGnlBillReport[$j][10]=$ZHZHD;
							$arrayGnlBillReport[$j][11]=$NcAc;
							$arrayGnlBillReport[$j][12]=$ligneCashierBillReport->motif;
							$arrayGnlBillReport[$j][13]=$examName;
							$arrayGnlBillReport[$j][14]=$resultatexam;
							$arrayGnlBillReport[$j][15]=$diagno;
							$arrayGnlBillReport[$j][16]=$ligneCashierBillReport->recommandations;

							$numDay++;
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
				if($_GET['caVisit']=='dailyPersoBill')
				{
					createRN('CRD');
				}else{
					if($_GET['caVisit']=='monthlyPersoBill')
					{
						createRN('CRM');
					}else{
						if($_GET['caVisit']=='annualyPersoBill')
						{
							createRN('CRA');
						}else{
							if($_GET['caVisit']=='customPersoBill')
							{	
								createRN('CRC');
							}else{
								if($_GET['caVisit']=='gnlPersoBill')
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