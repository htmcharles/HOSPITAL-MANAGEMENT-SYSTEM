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

	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico"/>
	
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
	
	if(isset($_SESSION['codeAcc']))
	{
		$resultatsCoordi=$connexion->prepare('SELECT *FROM utilisateurs u, accountants c WHERE u.id_u=c.id_u and c.id_u=:operation');
		$resultatsCoordi->execute(array(
		'operation'=>$idCoordi	
		));

		$resultatsCoordi->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneCoordi=$resultatsCoordi->fetch())
		{
			$doneby = $ligneCoordi->full_name;			
			$codecoordi=$ligneCoordi->codeaccount;
		}
	}else{		
		if(isset($_SESSION['codeI']))
		{
			$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u and i.id_u=:operation');
			$resultatsInf->execute(array(
			'operation'=>$idCoordi	
			));

			$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
			
			if($ligneInf=$resultatsInf->fetch())
			{
				$doneby = $ligneInf->full_name;
				$codecoordi = $ligneInf->codeinfirmier;
			}
		}else{
			if(isset($_SESSION['codeL']))
			{
				$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u and l.id_u=:operation');
				$resultatsLabo->execute(array(
				'operation'=>$idCoordi	
				));

				$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLabo=$resultatsLabo->fetch())
				{
					$doneby = $ligneLabo->full_name;
					$codecoordi = $ligneLabo->codelabo;
				}
			}else{
				if(isset($_SESSION['codeR']))
				{
					$resultatsRecep=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u and r.id_u=:operation');
					$resultatsRecep->execute(array(
					'operation'=>$idCoordi	
					));

					$resultatsRecep->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneRecep=$resultatsRecep->fetch())
					{
						$doneby = $ligneRecep->full_name;
						$codecoordi = $ligneRecep->codereceptio;
					}
				}else{
					if(isset($_SESSION['codeCash']))
					{
						$resultatsCash=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.id_u=:operation');
						$resultatsCash->execute(array(
						'operation'=>$idCoordi	
						));

						$resultatsCash->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneCash=$resultatsCash->fetch())
						{
							$doneby = $ligneCash->full_name;
							$codecoordi = $ligneCash->codecashier;
						}
					}
				}
			}
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
				<span style="text-align:center;background:#333;border-radius:40px;color:#eee;font-weight:400;padding:5px 50px">Powered by <font>Medical File</font> , a product of Innovate Solutions Ltd. �2015-'.date('Y').', All Rights Reserved.</span>
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
					
					<form method="post" action="accountinsurance_report.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&nomassu=<?php echo $nomassu;?>&paVisit=<?php echo $iVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="accountinsurance_report.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&audit=<?php echo $_SESSION['id'];?>&paVisit=<?php echo $iVisit;?>&nomassu=<?php echo $nomassu;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				
				<td style="text-align:right">
					
						<a href="report.php?codeAcc=<?php echo $_SESSION['codeAcc'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
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
							->setCellValue('A8', 'N�')
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
						<th>N�</th>
						<th>Date</th>
						<th>Bill number</th>
						<th>Insurance</th>
						<th><?php echo getString(113);?></th>
						<th><?php echo getString(39);?></th>
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
			$percent=$_GET['percent'].'<span style="font-size:80%; font-weight:normal;">%</span>';
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
					
					<form method="post" action="accountinsurance_report.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['nomassu'])){echo '&nomassu='.$_GET['nomassu'];}?><?php if(isset($_GET['idassu'])){echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisitgnl=<?php echo $iVisitgnl;?>&percent=<?php echo $percent;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportPdf=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
						
					<form method="post" action="accountinsurance_report.php?audit=<?php echo $_SESSION['id'];?>&dailydategnl=<?php echo $dailydategnl;?><?php if(isset($_GET['divGnlMedicReport'])){echo '&divGnlMedicReport=ok';}?>&paVisitgnl=<?php echo $iVisitgnl;?>&nomassu=<?php echo $nomassu;?>&idassu=<?php echo $idassu;?>&percent=<?php echo $percent;?><?php if(isset($_GET['divGnlBillReport'])){ echo '&divGnlBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?><?php if(isset($_GET['gnlpatient'])){ echo '&gnlpatient=ok';}?>&createReportExcel=ok&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				
				<td style="text-align:right">
					
					<a href="report.php?codeAcc=<?php echo $_SESSION['codeAcc'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
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
								->setCellValue('A9', 'N�')
								->setCellValue('B9', 'Date of bill')
								->setCellValue('C9', 'Bill number')
								->setCellValue('D9', 'Voucher number')
								->setCellValue('E9', 'Beneficiary\'s age')
								->setCellValue('F9', 'Gender ')
								->setCellValue('G9', 'Insurance card n�')
								->setCellValue('H9', 'Beneficiary\'s name')
								->setCellValue('I9', 'Principal member')
								->setCellValue('J9', 'Affiliate\'s affectation')
								->setCellValue('K9', 'Type of consultation')
								->setCellValue('L9', 'Price of consultation (Rwf)')
								->setCellValue('M9', 'Services')
								->setCellValue('N9', 'Nursing Care')
								->setCellValue('O9', 'Laboratory tests')
								->setCellValue('P9', 'Medical imaging')
								->setCellValue('Q9', 'Consommables')
								->setCellValue('R9', 'Medications')
								->setCellValue('S9', 'Total Amount')
								->setCellValue('T9', 'Total Amount'.$_GET['nomassu'].'');
					
			?>
			<table style="width:100%" class="printPreview tablesorter3" cellspacing="0"> 
						
				<thead>
					<tr>
						<th style="border-right: 1px solid #bbb;text-align:center;">No</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Date</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Bill number</th>
						<th style="border-right: 1px solid #bbb;text-align:center;<?php if($_GET['idassu']==1){ echo 'display:none;';}?>">Voucher number</th>
						<th style="border-right: 1px solid #bbb;text-align:center;<?php if($_GET['idassu']==1){ echo 'display:none;';}?>">Insurance card n�</th>
						<th style="border-right: 1px solid #bbb;text-align:center;<?php if($_GET['idassu']==1){ echo 'display:none;';}?>">Police n�</th>
						<th style="border-right: 1px solid #bbb;text-align:center;" colspan=2>Beneficiary's age / gender </th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Beneficiary's names</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Principal member</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Affiliate's affectation</th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(113);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(39);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(98);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo getString(99);?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medical imaging';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Dental';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Consommables';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;"><?php echo 'Medications';?></th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount <?php echo $nomassu;?></th>
					</tr> 
				</thead> 
				
				<tbody>
			<?php
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
			
				while($ligneGnlBillReport=$resultGnlBillReport->fetch())//on recup�re la liste des �l�ments
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
			
				<tr style="text-align:center;">
					<td style="text-align:center;"><?php echo $compteur;?></td>
					<td style="text-align:center;"><?php echo $ligneGnlBillReport->datebill;?></td>
					<td style="text-align:center;"><?php echo $ligneGnlBillReport->numbill;?></td>
					<?php
						$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
						$resultPatient->execute(array(
						'operation'=>$ligneGnlBillReport->numero
						));
				
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit recup�rable sous forme d'objet

						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des �l�ments
						{
							$fullname = $lignePatient->full_name;
							$numero = $lignePatient->numero;
							$sexe = $lignePatient->sexe;
							$vouchernum =$ligneGnlBillReport->vouchernum;
							if($ligneGnlBillReport->idcardbill!="")
							{
								$carteassuid = $ligneGnlBillReport->idcardbill;
							}else{
								$carteassuid = $lignePatient->carteassuranceid;
							}
							
							if($ligneGnlBillReport->idcardbill!="")
							{
								$numeropolice = $ligneGnlBillReport->numpolicebill;
							}else{
								$numeropolice = $lignePatient->numeropolice;
							}
							
							if($ligneGnlBillReport->idcardbill!="")
							{
								$adherent =$ligneGnlBillReport->adherentbill;
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
							
							
							if($_GET['idassu']!=1)
							{
								echo '<td style="text-align:center;">'.$vouchernum.'</td>';	
								echo '<td style="text-align:center;">'.$carteassuid.'</td>';	
								echo '<td style="text-align:center;">'.$numeropolice.'</td>';
							}
							echo '<td style="text-align:center;">'.$age.'</td>';
							echo '<td style="text-align:center;">'.$sexe.'</td>';
							echo '<td style="text-align:center;font-weight:bold;">'.$fullname.' ('.$numero.')</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$adherent.'</td>';
							echo '<td style="text-align:center;font-weight:normal;">'.$profession.'</td>';
						}else{
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
						}
						
					?>
					
					<td style="text-align:center;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<?php
						$resultConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
						$resultConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptConsu=$resultConsu->rowCount();
						
						$resultConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$resultAutreConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_typeconsult IS NULL AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
						$resultAutreConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptAutreConsu=$resultAutreConsu->rowCount();
						$resultAutreConsu->setFetchMode(PDO::FETCH_OBJ);
							
						
						$TotalTypeConsu=0;
						$TotalTypeConsuPatient=0;
						$TotalTypeConsuInsu=0;
							
						if($comptConsu!=0)
						{
							while($ligneConsu=$resultConsu->fetch())
							{
								if($ligneConsu->prixtypeconsult!=0 AND $ligneConsu->prixrembou!=0)
								{
									$prixPrestaRembou=$ligneConsu->prixrembou;
																	
									$prixconsult=$ligneConsu->prixtypeconsult - $prixPrestaRembou;
								
								}else{
									$prixconsult=$ligneConsu->prixtypeconsult;

								}
									
								$prixconsultpatient=($prixconsult * $ligneConsu->insupercent)/100;							
								
								$prixconsultinsu= $prixconsult - $prixconsultpatient;	
								if($prixconsult>=1)
								{	
							?>
								<tr style="display:none">
									<td style="text-align:center;font-weight:normal;">
									<?php
										echo $ligneConsu->nompresta;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									// echo $prixconsult;
									?>
									</td>
								</tr>
							<?php
									$consult .= ''.$ligneConsu->nompresta;
									$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
									$TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
									$TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
								}
							}
						}
							
						if($comptAutreConsu!=0)
						{
							while($ligneAutreConsu=$resultAutreConsu->fetch())
							{
								if($ligneAutreConsu->prixautretypeconsult!=0 AND $ligneAutreConsu->prixrembou!=0)
								{
									$prixPrestaRembou=$ligneAutreConsu->prixrembou;
									
									$prixconsult=$ligneAutreConsu->prixautretypeconsult - $prixPrestaRembou;
								
								}else{
									$prixconsult=$ligneAutreConsu->prixautretypeconsult;

								}
									
								$prixconsultpatient=($prixconsult * $ligneAutreConsu->insupercent)/100;							
								
								$prixconsultinsu= $prixconsult - $prixconsultpatient;	
							
								if($prixconsult>=1)
								{
							?>
								<tr style="display:none">
									<td style="text-align:center">
									<?php
										echo $ligneAutreConsu->autretypeconsult;
									?>
									</td>
									
									<td style="text-align:center">
									<?php
									// echo $prixconsult;
									?>
									</td>
								</tr>
						<?php
									$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
									$TotalTypeConsuPatient=$TotalTypeConsuPatient+$prixconsultpatient;
									$TotalTypeConsuInsu=$TotalTypeConsuInsu+$prixconsultinsu;
								}
							}
						}
						?>
								
							<tr>
								<td style="text-align:center;font-weight:normal;" colspan=2>
								<?php
									echo $TotalTypeConsu;
								
									$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalTypeConsuPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalTypeConsuInsu;
								?>
								<!-- <span style="font-size:80%; font-weight:normal;">Rwf</span> -->
					
								</td>
							</tr>
						</table>
					
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; margin-top:10px;"> 
								
						<?php
						
						$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, '.$presta_assu.' p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
						$resultMedConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedConsu=$resultMedConsu->rowCount();
						
						$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$resultMedAutreConsu=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_factureMedConsu=:idbill AND mc.id_prestationConsu IS NULL ORDER BY mc.id_medconsu DESC');
						$resultMedAutreConsu->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreConsu=$resultMedAutreConsu->rowCount();
						$resultMedAutreConsu->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedConsu=0;
						$TotalMedConsuPatient=0;
						$TotalMedConsuInsu=0;
						
				if($comptMedConsu!=0 or $comptMedAutreConsu!=0)
				{
					if($comptMedConsu!=0)
					{
						while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des �l�ments
						{
							if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
							{
								$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
								
								$prixconsu=$ligneMedConsu->prixprestationConsu - $prixPrestaRembou;
							
							}else{
								$prixconsu=$ligneMedConsu->prixprestationConsu;

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
									echo $prixconsu;
								?>
								</td>
							</tr>
						<?php
								$medconsu .= ''.$ligneMedConsu->nompresta.' ('.$prixconsu.'), ';
								$TotalMedConsu=$TotalMedConsu+$prixconsu;
								$TotalMedConsuPatient=$TotalMedConsuPatient+$prixconsupatient;
								$TotalMedConsuInsu=$TotalMedConsuInsu+$prixconsuinsu;
							
							}
						}
					}
					
					if($comptMedAutreConsu!=0)
					{
						while($ligneMedAutreConsu=$resultMedAutreConsu->fetch())
						{
							if($ligneMedAutreConsu->prixautreConsu!=0 AND $ligneMedAutreConsu->prixrembouConsu!=0)
							{
								$prixPrestaRembou=$ligneMedAutreConsu->prixrembouConsu;
								
								$prixconsu=$ligneMedAutreConsu->prixautreConsu - $prixPrestaRembou;
							
							}else{
								$prixconsu=$ligneMedAutreConsu->prixautreConsu;

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
									echo $prixconsu;				
								?>
								</td>
							</tr>
				<?php
						
								$medconsu .= ''.$ligneMedAutreConsu->autreConsu.' ('.$prixconsu.' Rwf), ';
								
								$TotalMedConsu=$TotalMedConsu+$prixconsu;
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
									echo $TotalMedConsu.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedConsuPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedConsuInsu;
								?>
								</td>
							</tr>
						</table>
					
					</td>
					
					<td style="text-align:center;font-weight:normal;">
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;">	
						<?php
						
					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, '.$presta_assu.' p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedInf->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedInf=$resultMedInf->rowCount();
					
					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
					
					
					$resultMedAutreInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE mi.id_prestation IS NULL AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
					$resultMedAutreInf->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedAutreInf=$resultMedAutreInf->rowCount();
					
					$resultMedAutreInf->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedInf=0;					
					$TotalMedInfPatient=0;					
					$TotalMedInfInsu=0;					
								
				if($comptMedInf!=0 or $comptMedAutreInf!=0)
				{
					if($comptMedInf!=0)
					{
						while($ligneMedInf=$resultMedInf->fetch())
						{
							if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
							{
								$prixPrestaRembou=$ligneMedInf->prixrembouInf;
								
								$prixinf=$ligneMedInf->prixprestation - $prixPrestaRembou;
							
							}else{
								$prixinf=$ligneMedInf->prixprestation;
																
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
									echo $prixinf.'';
								?>
								</td>
							</tr>
						<?php
							
								$medinf .= ''.$ligneMedInf->nompresta.' ('.$prixinf.' Rwf), ';
							
								$TotalMedInf=$TotalMedInf+$prixinf;
								$TotalMedInfPatient=$TotalMedInfPatient+$prixinfpatient;
								$TotalMedInfInsu=$TotalMedInfInsu+$prixinfinsu;
							}
						}
					}
					
					if($comptMedAutreInf!=0)
					{
						while($ligneMedAutreInf=$resultMedAutreInf->fetch())//on recupere la liste des �l�ments
						{
							if($ligneMedAutreInf->prixautrePrestaM!=0 AND $ligneMedAutreInf->prixrembouInf!=0)
							{
								$prixPrestaRembou=$ligneMedAutreInf->prixrembouInf;
								
								$prixinf=$ligneMedAutreInf->prixautrePrestaM - $prixPrestaRembou;
							
							}else{
								$prixinf=$ligneMedAutreInf->prixautrePrestaM;
																
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
									echo $prixinf;					
								?>
								</td>
							</tr>
							<?php
								
								$medinf .= ''.$ligneMedAutreInf->autrePrestaM.' ('.$prixinf.' Rwf), ';
							
								$TotalMedInf=$TotalMedInf+$prixinf;
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

									echo $TotalMedInf.'';					
									$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
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
						
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assu.' p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedLabo=$resultMedLabo->rowCount();
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
						
						$resultMedAutreLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_prestationExa IS NULL AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
						$resultMedAutreLabo->execute(array(
						'idbill'=>$ligneGnlBillReport->id_bill
						));
						
						$comptMedAutreLabo=$resultMedAutreLabo->rowCount();
						
						$resultMedAutreLabo->setFetchMode(PDO::FETCH_OBJ);
						
						
						$TotalMedLabo=0;
						$TotalMedLaboPatient=0;
						$TotalMedLaboInsu=0;
						
				if($comptMedLabo!=0 or $comptMedAutreLabo!=0)
				{
					if($comptMedLabo!=0)
					{
						while($ligneMedLabo=$resultMedLabo->fetch())
						{
							if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
							{
								$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
												
								$prixlabo=$ligneMedLabo->prixprestationExa - $prixPrestaRembou;
							
							}else{
								$prixlabo=$ligneMedLabo->prixprestationExa;

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
									echo $prixlabo.'';
								?>
								</td>
							</tr>
							<?php
							
								$medlabo .= ''.$ligneMedLabo->nompresta.' ('.$prixlabo.' Rwf), ';
								
								$TotalMedLabo=$TotalMedLabo+$prixlabo;
								$TotalMedLaboPatient=$TotalMedLaboPatient+$prixlabopatient;
								$TotalMedLaboInsu=$TotalMedLaboInsu+$prixlaboinsu;
							}
						}
					}
					
					if($comptMedAutreLabo!=0)
					{
						while($ligneMedAutreLabo=$resultMedAutreLabo->fetch())
						{
							if($ligneMedAutreLabo->prixautreExamen!=0 AND $ligneMedAutreLabo->prixrembouLabo!=0)
							{
								$prixPrestaRembou=$ligneMedAutreLabo->prixrembouLabo;
								
								$prixlabo=$ligneMedAutreLabo->prixautreExamen - $prixPrestaRembou;
							
							}else{
								$prixlabo=$ligneMedAutreLabo->prixautreExamen;
																
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
									echo $prixlabo;					
								?>
								</td>
							</tr>
				<?php
						
								$medlabo .= ''.$ligneMedAutreLabo->autreExamen.' ('.$prixlabo.' Rwf), ';
								
								$TotalMedLabo=$TotalMedLabo+$prixlabo;
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
									
									echo $TotalMedLabo.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
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
							
					$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, '.$presta_assu.' p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
					$resultMedRadio->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedRadio=$resultMedRadio->rowCount();
					
					$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
					$resultMedAutreRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_prestationRadio IS NULL AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
					$resultMedAutreRadio->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedAutreRadio=$resultMedAutreRadio->rowCount();
					
					$resultMedAutreRadio->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedRadio=0;
					$TotalMedRadioPatient=0;
					$TotalMedRadioInsu=0;
					
				if($comptMedRadio!=0 or $comptMedAutreRadio!=0)
				{
					if($comptMedRadio!=0)
					{
						while($ligneMedRadio=$resultMedRadio->fetch())
						{
							if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
							{
								$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
								
								$prixradio=$ligneMedRadio->prixprestationRadio - $prixPrestaRembou;
							
							}else{
								$prixradio=$ligneMedRadio->prixprestationRadio;
																
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
									echo $prixradio;
								?>
								</td>
							</tr>
					<?php
					
								$medradio .= ''.$ligneMedRadio->nompresta.' ('.$prixradio.' Rwf), ';
								
								$TotalMedRadio=$TotalMedRadio+$prixradio;
								$TotalMedRadioPatient=$TotalMedRadioPatient+$prixradiopatient;
								
								$TotalMedRadioInsu=$TotalMedRadioInsu+$prixradioinsu;
							}
						}
					}
					
					if($comptMedAutreRadio!=0)
					{
						while($ligneMedAutreRadio=$resultMedAutreRadio->fetch())//on recupere la liste des �l�ments
						{
							if($ligneMedAutreRadio->prixautreRadio!=0 AND $ligneMedAutreRadio->prixrembouRadio!=0)
							{
								$prixPrestaRembou=$ligneMedAutreRadio->prixrembouRadio;
								
								$prixradio=$ligneMedAutreRadio->prixautreRadio - $prixPrestaRembou;
							
							}else{
								$prixradio=$ligneMedAutreRadio->prixautreRadio;
							
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
									echo $prixradio;
								?>
								</td>
							</tr>
				<?php
					
								$medradio .= ''.$ligneMedAutreRadio->autreRadio.' ('.$prixradio.' Rwf), ';
								
								$TotalMedRadio=$TotalMedRadio+$prixradio;
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
									
									echo $TotalMedRadio.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;
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
							
					$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, '.$presta_assu.' p WHERE ms.id_prestationSurge=p.id_prestation AND ms.	id_factureMedSurge=:idbill ORDER BY ms.id_medsurge DESC');
					$resultMedSurge->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedSurge=$resultMedSurge->rowCount();
					
					$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
					$resultMedAutreSurge=$connexion->prepare('SELECT *FROM med_surge ms WHERE ms.id_factureMedSurge IS NULL AND ms.id_factureMedSurge=:idbill ORDER BY ms.id_medsurge DESC');
					$resultMedAutreSurge->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedAutreSurge=$resultMedAutreSurge->rowCount();
					
					$resultMedAutreSurge->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedSurge=0;
					$TotalMedSurgePatient=0;
					$TotalMedSurgeInsu=0;
					
				if($comptMedSurge!=0 or $comptMedAutreSurge!=0)
				{
					if($comptMedSurge!=0)
					{
						while($ligneMedSurge=$resultMedSurge->fetch())
						{
							if($ligneMedSurge->prixprestationSurge!=0 AND $ligneMedSurge->prixrembouSurge!=0)
							{
								$prixPrestaRembou=$ligneMedSurge->prixrembouSurge;
								
								$prixsurge=$ligneMedSurge->prixprestationSurge - $prixPrestaRembou;
							
							}else{
								$prixsurge=$ligneMedSurge->prixprestationSurge;
																
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
									echo $prixsurge;
								?>
								</td>
							</tr>
					<?php
					
								$medsurge .= ''.$ligneMedSurge->nompresta.' ('.$prixsurge.' Rwf), ';
								
								$TotalMedSurge=$TotalMedSurge+$prixsurge;
								$TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
								
								$TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
							}
						}
					}
					
					if($comptMedAutreSurge!=0)
					{
						while($ligneMedAutreSurge=$resultMedAutreSurge->fetch())//on recupere la liste des �l�ments
						{
							if($ligneMedAutreSurge->prixautreRadio!=0 AND $ligneMedAutreSurge->prixrembouSurge!=0)
							{
								$prixPrestaRembou=$ligneMedAutreSurge->prixrembouSurge;
								
								$prixSurge=$ligneMedAutreSurge->prixautrePrestaS - $prixPrestaRembou;
							
							}else{
								$prixsurge=$ligneMedAutreSurge->prixautrePrestaS;
							
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
									echo $prixsurge;
								?>
								</td>
							</tr>
				<?php
					
								$medsurge .= ''.$ligneMedAutreSurge->autrePrestaS.' ('.$prixsurge.' Rwf), ';
								
								$TotalMedSurge=$TotalMedSurge+$prixsurge;
								$TotalMedSurgePatient=$TotalMedSurgePatient+$prixsurgepatient;
								
								$TotalMedSurgeInsu=$TotalMedSurgeInsu+$prixsurgeinsu;
							}
						}
					}

				}					
				?>										
							<tr>
								<td style="text-align:center" colspan=2>
								<?php
									
									echo $TotalMedSurge.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedSurge;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedSurgePatient;

									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedSurgeInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td>
						<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 

					<?php
							
					$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, '.$presta_assu.' p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
					$resultMedConsom->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedConsom=$resultMedConsom->rowCount();
					
					$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
					$resultMedAutreConsom=$connexion->prepare('SELECT *FROM med_consom mco WHERE mco.id_prestationConsom=0 AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
					$resultMedAutreConsom->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedAutreConsom=$resultMedAutreConsom->rowCount();
					
					$resultMedAutreConsom->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedConsom=0;
					$TotalMedConsomPatient=0;
					$TotalMedConsomInsu=0;
					
				if($comptMedConsom!=0 or $comptMedAutreConsom!=0)
				{
					if($comptMedConsom!=0)
					{
						while($ligneMedConsom=$resultMedConsom->fetch())
						{
							if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
							{
								$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
								
								$prixconsom=($ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom) - $prixPrestaRembou;
							
							}else{
								$prixconsom=$ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom;
							
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
									echo $ligneMedConsom->qteConsom;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixconsom;
								?>
								</td>
							</tr>
					<?php
					
								$medconsom .= ''.$ligneMedConsom->nompresta.' ('.$prixconsom.' Rwf), ';
								
								$TotalMedConsom=$TotalMedConsom+$prixconsom;
								$TotalMedConsomPatient=$TotalMedConsomPatient + $prixconsompatient;
								
								$TotalMedConsomInsu=$TotalMedConsomInsu + $prixconsominsu;
							}
						}
					}
					
					if($comptMedAutreConsom!=0)
					{
						while($ligneMedAutreConsom=$resultMedAutreConsom->fetch())//on recupere la liste des �l�ments
						{
							if($ligneMedAutreConsom->prixautreConsom!=0 AND $ligneMedAutreConsom->prixrembouConsom!=0)
							{
								$prixPrestaRembou=$ligneMedAutreConsom->prixrembouConsom;
								
								$prixconsom=($ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom) - $prixPrestaRembou;
							
							}else{
								$prixconsom=$ligneMedAutreConsom->prixautreConsom * $ligneMedAutreConsom->qteConsom;
							
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
									echo $ligneMedAutreConsom->qteConsom;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixconsom;
								?>
								</td>
							</tr>
				<?php
							
								$medconsom .= ''.$ligneMedAutreConsom->autreConsom.' ('.$prixconsom.' Rwf), ';
								
								$TotalMedConsom=$TotalMedConsom+$prixconsom;
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
									
									echo $TotalMedConsom.'';
									
									$TotalDayPrice=$TotalDayPrice + $TotalMedConsom;
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
							
					$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, '.$presta_assu.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
					$resultMedMedoc->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedMedoc=$resultMedMedoc->rowCount();
					
					$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le r�sultat soit r�cup�rable sous forme d'objet
					
					$resultMedAutreMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo WHERE mdo.id_prestationMedoc=0 AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
					$resultMedAutreMedoc->execute(array(
					'idbill'=>$ligneGnlBillReport->id_bill
					));
					
					$comptMedAutreMedoc=$resultMedAutreMedoc->rowCount();
					
					$resultMedAutreMedoc->setFetchMode(PDO::FETCH_OBJ);
					
					
					$TotalMedMedoc=0;
					$TotalMedMedocPatient=0;
					$TotalMedMedocInsu=0;

					
				if($comptMedMedoc!=0 or $comptMedAutreMedoc!=0)
				{
					if($comptMedMedoc!=0)
					{
						
						while($ligneMedMedoc=$resultMedMedoc->fetch())
						{
							if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
							{
								$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
			
								$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc) - $prixPrestaRembou;
							
							}else{
								$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc;
							
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
									echo $ligneMedMedoc->qteMedoc;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixmedoc;
								?>
								</td>
							</tr>
					<?php
					
								$medmedoc .= ''.$ligneMedMedoc->nompresta.' ('.$prixmedoc.' Rwf), ';
								
								$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								$TotalMedMedocPatient=$TotalMedMedocPatient + $prixmedocpatient;							
								$TotalMedMedocInsu= $TotalMedMedocInsu + $prixmedocinsu;
								
							}
						}
					}
					
					if($comptMedAutreMedoc!=0)
					{
						while($ligneMedAutreMedoc=$resultMedAutreMedoc->fetch())//on recupere la liste des �l�ments
						{
							if($ligneMedAutreMedoc->prixautreMedoc!=0 AND $ligneMedAutreMedoc->prixrembouMedoc!=0)
							{
								$prixPrestaRembou=$ligneMedAutreMedoc->prixrembouMedoc;
			
								$prixmedoc=($ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc) - $prixPrestaRembou;
							
							}else{
								$prixmedoc=$ligneMedAutreMedoc->prixautreMedoc * $ligneMedAutreMedoc->qteMedoc;
							
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
									echo $ligneMedAutreMedoc->qteMedoc;
								?>
								</td>
								
								<td style="text-align:center">
								<?php
									echo $prixmedoc;
									
								?>
								</td>
							</tr>
				<?php
				
								$medmedoc .= ''.$ligneMedAutreMedoc->autreMedoc.' ('.$prixmedoc.' Rwf), ';
								
								$TotalMedMedoc=$TotalMedMedoc+$prixmedoc;
								
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
										
									echo $TotalMedMedoc.'';
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedMedoc;
									$TotalDayPricePatient=$TotalDayPricePatient+$TotalMedMedocPatient;
									$TotalDayPriceInsu=$TotalDayPriceInsu+$TotalMedMedocInsu;
								?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
					
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
					$TotalGnlPricePatient = $TotalGnlPricePatient + $TotalDayPrice;
					
					$TotalGnlPriceInsu = $TotalGnlPriceInsu + $TotalDayPriceInsu;
					
					
					$arrayGnlBillReport[$i][0]=$compteur;
					$arrayGnlBillReport[$i][1]=$ligneGnlBillReport->datebill;
					$arrayGnlBillReport[$i][2]=$ligneGnlBillReport->numbill;
					$arrayGnlBillReport[$i][3]=$vouchernum;
					$arrayGnlBillReport[$i][4]=$old;
					$arrayGnlBillReport[$i][5]=$sexe;
					$arrayGnlBillReport[$i][6]=$carteassuid;
					$arrayGnlBillReport[$i][7]=$numeropolice;
					$arrayGnlBillReport[$i][8]=$fullname;			
					$arrayGnlBillReport[$i][9]=$adherent;
					$arrayGnlBillReport[$i][10]=$profession;
					
					$arrayGnlBillReport[$i][11]=$consult;			
					$arrayGnlBillReport[$i][12]=$TotalTypeConsu;
					
					// $arrayGnlBillReport[$i][13]=$medconsu;		
					$arrayGnlBillReport[$i][13]=$TotalMedConsu;
					
					// $arrayGnlBillReport[$i][14]=$medinf;		
					$arrayGnlBillReport[$i][14]=$TotalMedInf;
					
					// $arrayGnlBillReport[$i][15]=$medlabo;		
					$arrayGnlBillReport[$i][15]=$TotalMedLabo;
					
					// $arrayGnlBillReport[$i][16]=$medradio;		
					$arrayGnlBillReport[$i][16]=$TotalMedRadio;
					
					// $arrayGnlBillReport[$i][17]=$medconsom;		
					$arrayGnlBillReport[$i][17]=$TotalMedConsom;
					
					// $arrayGnlBillReport[$i][18]=$medmedoc;		
					$arrayGnlBillReport[$i][18]=$TotalMedMedoc;
					
					$arrayGnlBillReport[$i][19]=$TotalDayPrice;
					$arrayGnlBillReport[$i][20]=$TotalDayPriceInsu;
					
					$i++;
					
					$compteur++;
					
				}
				?>
					<tr style="text-align:center;">
						<td colspan=<?php if($_GET['idassu']=='1'){ echo '8';}else{ echo '11';}?>></td>
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
								echo $TotalGnlPriceInsu;
								
							?><span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
					
				
				$objPHPExcel->setActiveSheetIndex(0)
							->fromArray($arrayGnlBillReport,'','A10')
					
							->setCellValue('L'.(10+$i).'', ''.$TotalGnlTypeConsu.'')
							->setCellValue('M'.(10+$i).'', ''.$TotalGnlMedConsu.'')
							->setCellValue('N'.(10+$i).'', ''.$TotalGnlMedInf.'')
							->setCellValue('O'.(10+$i).'', ''.$TotalGnlMedLabo.'')
							->setCellValue('P'.(10+$i).'', ''.$TotalGnlMedRadio.'')
							->setCellValue('Q'.(10+$i).'', ''.$TotalGnlMedConsom.'')
							->setCellValue('R'.(10+$i).'', ''.$TotalGnlMedMedoc.'')
							->setCellValue('S'.(10+$i).'', ''.$TotalGnlPrice.'')
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
					
					echo '<script text="text/javascript">document.location.href="accountinsurance_report.php?audit='.$_GET['audit'].'&dailydategnl='.$_GET['dailydategnl'].'&nomassu='.$_GET['nomassu'].'&idassu='.$_GET['idassu'].'&paVisitgnl='.$_GET['paVisitgnl'].'&percent='.$_GET['percent'].'&stringResult='.$_GET['stringResult'].'&divGnlBillReport=ok&gnlpatient=ok&createReportPdf=ok&createRN=0"</script>';
		
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