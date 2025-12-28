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
		
		if(isset($_GET['nomassu']))
		{
			$nomassu = $_GET['nomassu'];
		}
		
		if(isset($_GET['idassu']))
		{
			$idassu = $_GET['idassu'];
														
			$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
			
			$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
					
			$assuCount = $comptAssuConsu->rowCount();
			
			for($i=1;$i<=$assuCount;$i++)
			{
				
				$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
				$getAssuConsu->execute(array(
				'idassu'=>$idassu
				));
				
				$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

				if($ligneNomAssu=$getAssuConsu->fetch())
				{
					$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
				}
			}


		}
		
		if(isset($_GET['paVisit']))
		{
			$iVisit=$_GET['paVisit'];
			$sn = showRN(''.$nomassu.'');
		}
		
		if(isset($_GET['paVisitgnl']))
		{
			$iVisitgnl=$_GET['paVisitgnl'];
			$sn = showRN(''.$nomassu.'');
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
$barcode = '

<table style="width:100%">
		
		<tr>
			<td colspan=2 style="text-align:center;">
				<span style="text-align:center;background:#333;border-radius:40px;color:#eee;font-weight:400;padding:5px 50px">Powered by Medical <img src="images/smalllogo.png" style="width:5%;height:auto;"/> File , a product of Innovate Solutions Ltd. ©2022-'.date('Y').', All Rights Reserved.</span>
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
			$fullname=$ligne->full_name;
			
			$bill= $ligne->bill;
			$idassurance=$ligne->id_assurance;
			$numpolice=$ligne->numeropolice;
			$adherent=$ligne->adherent;
			
			if($ligne->carteassuranceid != "")
			{
				$idcard = $ligne->carteassuranceid;
			}else{
				$idcard = "";
			}
			
			if($ligne->sexe=="M")
			{
				$sexe = "Male";
			}elseif($ligne->sexe=="F"){			
				$sexe = "Female";			
			}else{				
				$sexe="";
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
			}elseif($ligne->autreadresse!=""){
					$adresse=$ligne->autreadresse;
			}else{
				$adresse="";
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
			
			$percentpatient= 100 - $uappercent;
							
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
					->setCellValue('B4', ''.$insurance.' '.$percentpatient.'%')
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
					
					<form method="post" action="dmacrssb_report.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&nomassu=<?php echo $nomassu;?>&paVisit=<?php echo $iVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="dmacrssb_report.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&paVisit=<?php echo $iVisit;?>&nomassu=<?php echo $nomassu;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				
				<td style="text-align:right">
					
						<a href="insurance_report.php?num=<?php echo $_GET['num'];?>&audit=<?php echo $_SESSION['id'];?>&nomassu=<?php echo $nomassu;?>&insureport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
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
				<span style="font-weight:bold">Patient payment: </span>'.$percentpatient.' %<br/>
				<span style="font-weight:bold">Insurance payment: </span>'.$uappercent.' %
			</td>
			
			<td style="text-align:right;">
				<span style="font-weight:bold">S/N: </span>'.$numPa.'<br/>
				<span style="font-weight:bold">Date of birth: </span>'.$dateN.'<br/>
				
			</td>
							
		</tr>		
	</table>';

		echo $userinfo;

		if(isset($_GET['divPersoBillReport']))
		{
			echo $_GET['dailydateperso'];
		?>
		<div id="divPersoBillReport">
	
			<table cellspacing="0" style="background:#fff; margin:20px auto auto">
				<tr>
					<td>
						<b><h3 style="padding:10px">Billing Report #<?php echo $sn;?></h3></b>
					</td>
				</tr>
			
			</table>
			<?php
			
			
			$resultBillReport=$connexion->prepare('SELECT *FROM bills WHERE nomassurance = :nomassu ORDER BY datebill ASC');
			$resultBillReport->execute(array(
			'nomassu'=>$_GET['nomassu']	
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
							->setCellValue('F8', 'Services')
							->setCellValue('G8', 'Nursing Care')
							->setCellValue('H8', 'Laboratory tests')
							->setCellValue('I8', 'Total Final');
				
			?>
			<table  class="printPreview" cellspacing="0" style="margin:10px auto auto; border-top:none"> 
						
				<thead>
					<tr>
						<th>N°</th>
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
			
			$compteur=1;
			
				while($ligneBillReport=$resultBillReport->fetch())//on recupere la liste des ?ments
				{
			?>
					<tr style="text-align:center;">
						<td><?php echo $compteur;?></td>
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
					
					
					
					$arrayPersoBillReport[$i][0]=$compteur;
					$arrayPersoBillReport[$i][1]=$ligneBillReport->datebill;
					$arrayPersoBillReport[$i][2]=$ligneBillReport->numbill;
					$arrayPersoBillReport[$i][3]=$ligneBillReport->nomassurance.' '.$ligneBillReport->billpercent.' %';
					$arrayPersoBillReport[$i][4]=$ligneBillReport->totaltypeconsuprice;
					$arrayPersoBillReport[$i][5]=$ligneBillReport->totalmedconsuprice;
					$arrayPersoBillReport[$i][6]=$ligneBillReport->totalmedinfprice;
					$arrayPersoBillReport[$i][7]=$ligneBillReport->totalmedlaboprice;
					$arrayPersoBillReport[$i][8]=$ligneBillReport->totalgnlprice;
					
					$i++;
					
					$compteur++;
	
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
								->fromArray($arrayPersoBillReport,'','A10')
								
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
				
				$reportsn=str_replace('/', '_', $sn);
				
				
					$objWriter->save('C:/wamp/www/uap/Reports/Insurances/'.$nomassu.'/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/wamp/www/uap/Reports/Insurances/'.$nomassu.'/");</script>';
					
					createRN(''.$nomassu.'');
					
			}
		}

	}
	
	
	if(isset($_GET['divGnlBillReport']))
	{
		$dailydategnl=$_GET['dailydategnl'];
		$iVisitgnl=$_GET['paVisitgnl'];
		
		if($_GET['percent'] !="All")
		{
			$percent=$_GET['percent'].'%';
		}else{
			$percent='All';
		}
	
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
					->setCellValue('C3', ''.$annee.'')
					->setCellValue('B5', ''.$nomassu.'')
					->setCellValue('C5', ''.$percent.'');
					
		// echo $dailydategnl;
		
		if(isset($_GET['stringResult']))
		{
			$stringResult=$_GET['stringResult'];
		}
		
	?>
		<table cellpadding=3 style="width:100%; margin:5px auto auto;">
			
			<tr>
				<td style="text-align:left;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:center; width:50%;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $_GET['nomassu'];?> <?php echo $stringResult;?> Report (<?php echo $percent;?>) #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right; width:30%;">
					
					<form method="post" action="dmacrssb_report.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['nomassu'])){echo '&nomassu='.$_GET['nomassu'];}?><?php if(isset($_GET['idassu'])){echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisitgnl=<?php echo $iVisitgnl;?>&percent=<?php echo $percent;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
						
					<form method="post" action="dmacrssb_report.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisitgnl=<?php echo $iVisitgnl;?>&nomassu=<?php echo $nomassu;?>&idassu=<?php echo $idassu;?>&percent=<?php echo $percent;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				
				<td style="text-align:right">
					
					<a href="insurance_report.php?audit=<?php echo $_SESSION['id'];?>&nomassu=<?php echo $nomassu;?>&idassu=<?php echo $idassu;?>&insugnlreport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</a>
					
				</td>
			</tr>
		
		</table>
		
		<?php
		if(isset($_GET['divGnlBillReport']))
		{
		?>
		<div id="divGnlBillReport" style="font-weight:normal;">
		
			<?php			
			
			$resultGnlBillReport=$connexion->prepare('SELECT *FROM bills WHERE nomassurance = :nomassu '.$dailydategnl.'');
			$resultGnlBillReport->execute(array(
			'nomassu'=>$_GET['nomassu']	
			));
			
			$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);

			$comptBillReport=$resultGnlBillReport->rowCount();
			
			if($comptBillReport!=0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A9', 'N°')
								->setCellValue('B9', 'DATE OF BILL')
								->setCellValue('C9', 'BILL NUMBER')
								->setCellValue('D9', 'VOUCHER IDENTIFICATION')
								->setCellValue('E9', 'BENEFIACIARY\'S AFFILIATION NUMBER')
								->setCellValue('F9', 'BENEFICIARY\'S AGE (.../.../...)')
								->setCellValue('G9', 'BENEFICIARY\'S SEX (F=Female, M=male)')
								->setCellValue('H9', 'BENEFICIARY\'S NAMES')
								->setCellValue('I9', 'AFFILIATE\'S NAMES')
								->setCellValue('J9', 'AFFILIATE\'S AFFECTATION')
								// ->setCellValue('K9', 'TYPE OF CONSULTATION')
								->setCellValue('K9', 'COST FOR CONSULTATION')
								->setCellValue('L9', 'COST FOR LABORATORY TESTS')
								->setCellValue('M9', 'COST FOR MEDICAL IMAGING')
								->setCellValue('N9', 'COST FOR PROCEDURES AND MATERIALS')
								->setCellValue('O9', 'COST FOR OTHER CONSUMABLES')
								->setCellValue('P9', 'COST FOR OTHER HOSPITALISATION')
								->setCellValue('Q9', 'COST FOR MEDICINES')
								->setCellValue('R9', 'TOTAL AMOUNT')
								->setCellValue('S9', 'TOTAL AMOUNT PATIENT')
								->setCellValue('T9', 'TOTAL AMOUNT '.$_GET['nomassu'].'');
					
			?>
			<table style="width:100%" class="printPreview tablesorter3" cellspacing="0"> 
						
				<thead>
					<tr>
						<th style="border-right: 1px solid #bbb;text-align:center;">N°</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Date</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Bill number</th>
						<th style="border-right: 1px solid #bbb;text-align:center;<?php if($_GET['idassu']==1){ echo 'display:none;';}?>">Voucher identification</th>
						<th style="border-right: 1px solid #bbb;text-align:center;<?php if($_GET['idassu']==1){ echo 'display:none;';}?>">Beneficiary's affiliation number</th>
						<th style="border-right: 1px solid #bbb;text-align:center;" colspan=2>Beneficiary's Age / Sex</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Beneficiary's names</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's names</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's affectation</th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Cost for Consultations';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Cost for Laboratory tests';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Cost for Medical imaging';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Cost for Procedures and materials';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Cost for Other Consumables';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Cost for Surgery';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Hospitalisation';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Cost for Medecines';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount Patient</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount <?php echo $nomassu;?></th>
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
			$TotalGnlMedLabo=0;
				$TotalGnlMedLaboPatient=0;
				$TotalGnlMedLaboInsu=0;
			$TotalGnlMedRadio=0;
				$TotalGnlMedRadioPatient=0;
				$TotalGnlMedRadioInsu=0;
			$TotalGnlMedConsu=0;
				$TotalGnlMedConsuPatient=0;
				$TotalGnlMedConsuInsu=0;
			$TotalGnlMedInf=0;
				$TotalGnlMedInfPatient=0;
				$TotalGnlMedInfInsu=0;
			
			$TotalprocedMat=0;
			$TotalGnlprocedMat=0;
				
			$TotalGnlMedConsom=0;
				$TotalGnlMedConsomPatient=0;
				$TotalGnlMedConsomInsu=0;

			$TotalGnlMedSurge=0;
				$TotalGnlMedSurgePatient=0;
				$TotalGnlMedSurgeInsu=0;
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
					$medconsom ="";
					$medsurge ="";
					$medmedoc ="";
			?>
			
				<tr style="text-align:center;">
					<td style="text-align:center;"><?php echo $compteur;?></td>
					<td style="text-align:center;"><?php echo $billArray[$b]['datebill'];?></td>
					<td style="text-align:center;"><?php echo $billArray[$b]['numbill'].' <br/>(<span style="font-weight:bold;">'.$billArray[$b]['billpercent'].'%</span>)';?></td>
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
							$vouchernum =$compteur.'/'.$lignePatient->numero.'/'.'10640002'.'/'.date('m').'/'.date('Y');
							
							if($billArray[$b]['idcardbill']!="")
							{
								$carteassuid = $billArray[$b]['idcardbill'];
							}else{
								$carteassuid = $lignePatient->carteassuranceid;
							}
							
							if($billArray[$b]['idcardbill']!="")
							{
								$numeropolice = $billArray[$b]['numpolicebill'];
							}else{
								$numeropolice = $lignePatient->numeropolice;
							}
							
							if($billArray[$b]['idcardbill']!="")
							{
								$adherent =$billArray[$b]['adherentbill'];
							}else{
								$adherent =$lignePatient->adherent;
							}
							
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
								
							
							echo '<td style="text-align:center;">'.$compteur.'/'.$numero.'/'.'10640002'.'/'.date('m').'/'.date('Y').'</td>';	
							echo '<td style="text-align:center;">'.$carteassuid.'</td>';
							echo '<td style="text-align:center;">'.date('d/m/Y',strtotime($dateN)).'</td>';
							echo '<td style="text-align:center;">'.$sexe.'</td>';
							echo '<td style="text-align:center;font-weight:bold;">'.$fullname.'</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$adherent.'</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$profession.'</td>';
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
						
					<td style="text-align:center;font-weight:normal;display:none;">
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
					
					<td style="text-align:center;font-weight:normal;display:none;">
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
								// var_dump($infArray[$billArray[$b]['id_bill']]);
								// echo sizeof($infArray[$billArray[$b]['id_bill']]).'<br/>';
								
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
					
					<td>
						<?php
						$TotalprocedMat =$TotalMedConsu+$TotalMedInf;
							
							echo $TotalprocedMat;			
						?>					
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
					
					<td style="text-align:center;font-weight:normal;">0</td>
					
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
					
				$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
					$TotalGnlMedLaboPatient=$TotalGnlMedLaboPatient + $TotalMedLaboPatient;
					$TotalGnlMedLaboInsu=$TotalGnlMedLaboInsu + $TotalMedLaboInsu;
				
				$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
					$TotalGnlMedRadioPatient = $TotalGnlMedRadioPatient + $TotalMedRadioPatient;
					$TotalGnlMedRadioInsu = $TotalGnlMedRadioInsu + $TotalMedRadioInsu;
				
				$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
					$TotalGnlMedConsuPatient = $TotalGnlMedConsuPatient + $TotalMedConsuPatient;
					$TotalGnlMedConsuInsu = $TotalGnlMedConsuInsu + $TotalMedConsuInsu;
					
				$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
					$TotalGnlMedInfPatient = $TotalGnlMedInfPatient + $TotalMedInfPatient;
					$TotalGnlMedInfInsu = $TotalGnlMedInfInsu + $TotalMedInfInsu;
					
					
				$TotalGnlprocedMat +=$TotalprocedMat;
				
				
				
				$TotalGnlMedConsom=$TotalGnlMedConsom + $TotalMedConsom;
					$TotalGnlMedConsomPatient = $TotalGnlMedConsomPatient + $TotalMedConsomPatient;
					$TotalGnlMedConsomInsu = $TotalGnlMedConsomInsu + $TotalMedConsomInsu;

				$TotalGnlMedSurge=$TotalGnlMedSurge + $TotalMedSurge;
							$TotalGnlMedSurgePatient = $TotalGnlMedSurgePatient + $TotalMedSurgePatient;
							$TotalGnlMedSurgeInsu = $TotalGnlMedSurgeInsu + $TotalMedSurgeInsu;
			
				$TotalGnlMedMedoc=$TotalGnlMedMedoc + $TotalMedMedoc;
					$TotalGnlMedMedocPatient = $TotalGnlMedMedocPatient + $TotalMedMedocPatient;
					$TotalGnlMedMedocInsu = $TotalGnlMedMedocInsu + $TotalMedMedocInsu;
				
				$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;
					$TotalGnlPricePatient = $TotalGnlPricePatient + $TotalDayPricePatient;
					
					$TotalGnlPriceInsu = $TotalGnlPriceInsu + $TotalDayPriceInsu;
					
					
					$arrayGnlBillReport[$i][0]=$compteur;
					$arrayGnlBillReport[$i][1]=date('d/m/Y',strtotime($billArray[$b]['datebill']));
					$arrayGnlBillReport[$i][2]=$billArray[$b]['numbill'];
					$arrayGnlBillReport[$i][3]=$compteur.'/'.$lignePatient->numero.'/'.'10640002'.'/'.date('m').'/'.date('Y');
					$arrayGnlBillReport[$i][4]=$carteassuid;
					$arrayGnlBillReport[$i][5]=date('d/m/Y',strtotime($dateN));
					$arrayGnlBillReport[$i][6]=$sexe;
					$arrayGnlBillReport[$i][7]=$fullname;			
					$arrayGnlBillReport[$i][8]=$adherent;
					$arrayGnlBillReport[$i][9]=$profession;
					
					// $arrayGnlBillReport[$i][10]=$consult;			
					$arrayGnlBillReport[$i][10]=$TotalTypeConsu;
					
					// $arrayGnlBillReport[$i][11]=$medlabo;		
					$arrayGnlBillReport[$i][11]=$TotalMedLabo;
					
					// $arrayGnlBillReport[$i][12]=$medradio;		
					$arrayGnlBillReport[$i][12]=$TotalMedRadio;
					
					
					$arrayGnlBillReport[$i][13]=$TotalprocedMat;
					
					// $arrayGnlBillReport[$i][14]=$medconsom;		
					$arrayGnlBillReport[$i][14]=$TotalMedConsom;
					
					$arrayGnlBillReport[$i][15]='';
					
					// $arrayGnlBillReport[$i][16]=$medmedoc;		
					$arrayGnlBillReport[$i][16]=$TotalMedMedoc;
					
					$arrayGnlBillReport[$i][17]=$TotalDayPrice;
					$arrayGnlBillReport[$i][18]=$TotalDayPricePatient;
					$arrayGnlBillReport[$i][19]=$TotalDayPriceInsu;
					
					if($_GET['percent'] =="All")
					{
						$arrayGnlBillReport[$i][20]=$billArray[$b]['billpercent'].' %';
					}
					
					$i++;
					
					$compteur++;
					
				}
				?>
					<tr style="text-align:center;">
						<td colspan=10></td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlTypeConsu;			
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
								echo $TotalGnlprocedMat;				
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $TotalGnlMedConsom;			
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>						

						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php
							$TotalGnlMedSurgeBalance = $TotalGnlMedSurgePatient + $TotalGnlMedSurgeInsu;

								echo $TotalGnlMedSurgeBalance;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo 0;			
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
					
							->setCellValue('K'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
							->setCellValue('L'.(10+$i).'', ''.$TotalGnlMedLabo.'')
							->setCellValue('M'.(10+$i).'', ''.$TotalGnlMedRadio.'')
							->setCellValue('N'.(10+$i).'', ''.$TotalGnlprocedMat.'')
							->setCellValue('O'.(10+$i).'', ''.$TotalGnlMedConsom.'')
							->setCellValue('Q'.(10+$i).'', ''.$TotalGnlMedMedoc.'')
							->setCellValue('R'.(10+$i).'', ''.$TotalGnlPrice.'')
							->setCellValue('S'.(10+$i).'', ''.$TotalGnlPricePatient.'')
							->setCellValue('T'.(10+$i).'', ''.$TotalGnlPriceInsu.'');

			}
			?>
		</div>
		<?php
			
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn=str_replace('/', '_', $sn);
				
				
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/Insurances/'.$nomassu.'/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/Insurances/'.$nomassu.'/");</script>';
			
			}
			
			if(isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf']))
			{
				if($_GET['createRN']==1)
				{			
					createRN(''.$nomassu.'');
					
					echo '<script text="text/javascript">document.location.href="dmacrssb_report.php?audit='.$_GET['audit'].'&dailydategnl='.$_GET['dailydategnl'].'&nomassu='.$_GET['nomassu'].'&idassu='.$_GET['idassu'].'&paVisitgnl='.$_GET['paVisitgnl'].'&percent='.$_GET['percent'].'&stringResult='.$_GET['stringResult'].'&divGnlBillReport=ok&gnlpatient=ok&createReportPdf=ok&createRN=0"</script>';
		
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
						<span style="font-weight:bold">Insurance Signature</span>
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