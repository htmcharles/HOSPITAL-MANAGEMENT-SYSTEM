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
	
	if(isset($_GET['med']))
	{
		if($_GET['docVisit']=='dailyPersoMedic')
		{
			$sn = showRN('DRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('DRM');
			}else{
				if($_GET['docVisit']=='annualyPersoMedic')
				{
					$sn = showRN('DRA');
				}else{
					if($_GET['docVisit']=='customPersoMedic')
					{
						$sn = showRN('DRC');
					}else{
						if($_GET['docVisit']=='gnlPersoMedic')
						{
							$sn = showRN('DRG');
						}
					}
				}
			}
		}
		
		
		if($_GET['docVisit']=='dailyPersoBill')
		{
			$sn = showRN('DRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoBill')
			{
				$sn = showRN('DRM');
			}else{
				if($_GET['docVisit']=='annualyPersoBill')
				{
					$sn = showRN('DRA');
				}else{
					if($_GET['docVisit']=='customPersoBill')
					{
						$sn = showRN('DRC');
					}else{
						if($_GET['docVisit']=='gnlPersoBill')
						{
							$sn = showRN('DRG');
						}
					}
				}
			}
		}
	}

	
	if(isset($_GET['gnlmed']))	
	{
		if($_GET['docVisit']=='dailyPersoMedic')
		{
			$sn = showRN('GDRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('GDRM');
			}else{
				if($_GET['docVisit']=='annualyPersoMedic')
				{
					$sn = showRN('GDRA');
				}else{
					if($_GET['docVisit']=='customPersoMedic')
					{
						$sn = showRN('GDRC');
					}else{
						if($_GET['docVisit']=='gnlPersoMedic')
						{
							$sn = showRN('GDRG');
						}
					}
				}
			}
		}
	
		if($_GET['docVisit']=='dailyPersoBill')
		{
			$sn = showRN('GDRD');
		}else{
			if($_GET['docVisit']=='monthlyPersoBill')
			{
				$sn = showRN('GDRM');
			}else{
				if($_GET['docVisit']=='annualyPersoBill')
				{
					$sn = showRN('GDRA');
				}else{
					if($_GET['docVisit']=='customPersoBill')
					{
						$sn = showRN('GDRC');
					}else{
						if($_GET['docVisit']=='gnlPersoBill')
						{
							$sn = showRN('GDRG');
						}
					}
				}
			}
		}
	}	
			
		
		if(isset($_GET['docVisit']))
		{
			$docVisit=$_GET['docVisit'];
		}
		
		if(isset($_GET['docVisitgnl']))
		{
			$docVisitgnl=$_GET['docVisitgnl'];
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
	<title><?php echo 'Doctor Report#'.$sn; ?></title>

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

if($connected==true AND $_SESSION['dataM']!=NULL)
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
			$codeDoneby=$ligneCoordi->codecoordi;
		}
	}else{		
		if(isset($_SESSION['codeI']))
		{
			$resultatsInf=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=i.id_u and i.id_u=:operation');
			$resultatsInf->execute(array(
			'operation'=>$idDoneby	
			));

			$resultatsInf->setFetchMode(PDO::FETCH_OBJ);
			
			if($ligneInf=$resultatsInf->fetch())
			{
				$doneby = $ligneInf->full_name;
				$codeDoneby = $ligneInf->codeinfirmier;
			}
		}else{
			if(isset($_SESSION['codeL']))
			{
				$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u and l.id_u=:operation');
				$resultatsLabo->execute(array(
				'operation'=>$idDoneby	
				));

				$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneLabo=$resultatsLabo->fetch())
				{
					$doneby = $ligneLabo->full_name;
					$codeDoneby = $ligneLabo->codelabo;
				}
			}else{
				if(isset($_SESSION['codeR']))
				{
					$resultatsRecep=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u and r.id_u=:operation');
					$resultatsRecep->execute(array(
					'operation'=>$idDoneby	
					));

					$resultatsRecep->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneRecep=$resultatsRecep->fetch())
					{
						$doneby = $ligneRecep->full_name;
						$codeDoneby = $ligneRecep->codereceptio;
					}
				}else{
					if(isset($_SESSION['codeCash']))
					{
						$resultatsCash=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.id_u=:operation');
						$resultatsCash->execute(array(
						'operation'=>$idDoneby	
						));

						$resultatsCash->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneCash=$resultatsCash->fetch())
						{
							$doneby = $ligneCash->full_name;
							$codeDoneby = $ligneCash->codecashier;
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
	if(isset($_GET['med']))
	{
		
		$result=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
		$result->execute(array(
		'operation'=>$_GET['med']	
		));
		$result->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($ligne=$result->fetch())
		{
			$codeMed=$ligne->codemedecin;
			$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
			
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
		
		$idDoc=$_GET['med'];
		$dailydateperso=$_GET['dailydateperso'];
		$docVisit=$_GET['docVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for doctor : '.$codeMed.', '.$fullname.'')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'S/N')
					->setCellValue('B1', ''.$codeMed.'')
					->setCellValue('A2', 'Doctor name')
					->setCellValue('B2', ''.$fullname.'')
					->setCellValue('A3', 'Adresse')
					->setCellValue('B3', ''.$adresse.'')
					
					->setCellValue('I1', 'Report #')
					->setCellValue('J1', ''.$sn.'')
					->setCellValue('I2', 'Done by')
					->setCellValue('J2', ''.$doneby.'')
					->setCellValue('I3', 'Date')
					->setCellValue('J3', ''.$annee.'');
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Doctor Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="doctor_reportDataM.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];} if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<td style="text-align:left">
					
					<form method="post" action="doctor_reportDataM.php?med=<?php echo $_GET['med'];?>&dailydateperso=<?php echo $dailydateperso;?>&coordi=<?php echo $_SESSION['id'];?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';} if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportExcel=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];} if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="reportDataM.php?med=<?php echo $_GET['med'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];} if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
			
	<?php
		$userinfo = '
	
	<table style="width:50%; margin-top:20px;font-size:15px;" align="center">		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">Doctor name : </span>		
			</td>
			<td style="text-align:left;">'.$fullname.'</td>			
		</tr>
		
		<tr>
			<td style="text-align:right;">
				<span style="font-weight:bold;margin-right:5px;">S/N : </span>		
			</td>
			<td style="text-align:left;">'.$codeMed.'</td>			
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
	
		if(isset($_GET['divPersoMedicReport']))
		{
	?>
		<div id="divPersoMedicReport">

			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
			$resultConsult->execute(array(
			'med'=>$idDoc
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A8', 'N°')
							->setCellValue('B8', 'Date of consultation')
							->setCellValue('C8', 'Patient name')
							->setCellValue('D8', 'Age')
							->setCellValue('E8', 'Gender')
							->setCellValue('F8', 'Type of consultation')
							->setCellValue('G8', 'Services')
							->setCellValue('H8', 'Nursing Care')
							->setCellValue('I8', 'Laboratory tests')
							->setCellValue('J8', 'Diagnosis');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:2%; border-right: 1px solid #bbb">N°</th>
						<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
						<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
						<th style="text-align:center; width:10%; border-right: 1px solid #bbb" colspan=2>Age/Sexe</th>
						<th style="width:15%; border-right: 1px solid #bbb"><?php echo getString(113);?></th>
						<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
						<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
						<th style="width:15%;"><?php echo getString(99);?></th>
						<th style="width:10%;"><?php echo 'Diagnosis';?></th>
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
						$diagno = "";
						
			?>
					<tr>
						<td style="text-align:left;">
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
						
						<td style="text-align:left;font-weight:bold;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
								
			$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($lignePatient->date_naissance)));
			$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
			$interval = $datetime1->diff($datetime2);
			
			if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
			{
				$agePa = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
			}
									
								$sexePa = $lignePatient->sexe;
								
								echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
							}else{
								$fullnamePa="";
								echo '';
							}
						?>
						</td>
						
						<td style="text-align:left;">
						<?php echo $agePa;?>
						</td>
						
						<td style="text-align:left;">
						<?php echo $sexePa; ?>
						</td>
						
						<td style="text-align:left;">
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
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
						?>
						
						<td style="text-align:left;">
						
						<?php
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'med'=>$idDoc,
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
								<tr>
									
									<td style="text-align:left;">
									<?php
									
									$idassuServ=$ligneMedConsult->id_assuServ;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($s=1;$s<=$assuCount;$s++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuServ
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
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
									}else{
										
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
					
						<td style="text-align:left;">
						
						<?php
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE AND mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'med'=>$idDoc,					
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
											
									$idassuInf=$ligneMedInf->id_assuInf;
									
									$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuInf->rowCount();
									
									for($n=1;$n<=$assuCount;$n++)
									{
										
										$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuInf->execute(array(
										'idassu'=>$idassuInf
										));
										
										$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuInf=$getAssuInf->fetch())
										{
											$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
										}
									}
								?>
								<tr>
									<td style="text-align:left;">
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
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
									}else{
										
										echo $ligneMedInf->autrePrestaM.'</td>';
										
										$nursery .= ''.$ligneMedInf->autrePrestaM.', ';
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
					
						<td style="text-align:left;">
						
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'med'=>$idDoc,					
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
									
									$idassuLab=$ligneMedLabo->id_assuLab;

									$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuLab->rowCount();
									
									for($l=1;$l<=$assuCount;$l++)
									{
										
										$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuLab->execute(array(
										'idassu'=>$idassuLab
										));
										
										$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuLab=$getAssuLab->fetch())
										{
											$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
										}
									}

								?>
								<tr>
									<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta;
												
												$labs .= ''.$lignePresta->namepresta.', ';
											}else{
												
												echo $lignePresta->nompresta;
												
												$labs .= ''.$lignePresta->nompresta.', ';
											}
										}else{
											
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

						<td style="text-align:left;font-weight:bold;">
						<?php
									
						$Postdia = array();
						$DiagnoPostDone=0;
															
						$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
						
						$resuPostdiagnostic->execute(array(
						'idConsu'=>$ligneConsult->id_consu
						))or die( print_r($connexion->errorInfo()));
							
						$resuPostdiagnostic->setFetchMode(PDO::FETCH_OBJ);
							
						$lignePostdiagnostic=$resuPostdiagnostic->fetch();
						
							$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
							
							$resultatsDiagnoPost->execute(array(
							'iddiagno'=>$lignePostdiagnostic->postdiagnostic
							))or die( print_r($connexion->errorInfo()));
								
							$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
							
							if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
							{
								$DiagnoPostDone=1;
							}else{
							
								if($lignePostdiagnostic->postdiagnostic != "")
								{
									$DiagnoPostDone=1;
								}
							}

							
						$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
						
						$resultatsPostDiagno->execute(array(
						'id_consudia'=>$lignePostdiagnostic->id_consu
						
						))or die( print_r($connexion->errorInfo()));
							
						$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
						$postdiaCount = $resultatsPostDiagno->rowCount();
						
						if($postdiaCount!=0)
						{
							
							while($linePostDiagno=$resultatsPostDiagno->fetch())
							{
								$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
								$resultsDiagno->execute(array(
								'iddiagno'=>$linePostDiagno->id_postdia
								));
								
								$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
								$comptDiagno=$resultsDiagno->rowCount();
								
								if($comptDiagno!=0)
								{
									$DiagnoPostDone=1;
								}else{
									if($linePostDiagno->autrepostdia !="")
									{
										$DiagnoPostDone=1;
									}
								}
								
							}
						
						}


			$Predia = array();
							
			$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
			
			$resuPrediagnostic->execute(array(
			'idConsu'=>$ligneConsult->id_consu
			))or die( print_r($connexion->errorInfo()));
				
			$resuPrediagnostic->setFetchMode(PDO::FETCH_OBJ);
				
			$lignePrediagnostic=$resuPrediagnostic->fetch();
			
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$lignePrediagnostic->prediagnostic
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Predia[] = $ligneDiagnoPost->nomdiagno;
				}else{
				
					if($lignePrediagnostic->prediagnostic != "")
					{
						$Predia[] = $lignePrediagnostic->prediagnostic;
					}
				}

				
			$resultatsPrediagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
			
			$resultatsPrediagno->execute(array(
			'id_consudia'=>$lignePrediagnostic->id_consu
			
			))or die( print_r($connexion->errorInfo()));
				
			$resultatsPrediagno->setFetchMode(PDO::FETCH_OBJ);
			$prediaCount = $resultatsPrediagno->rowCount();
			
			if($prediaCount!=0)
			{
				
				while($linePrediagno=$resultatsPrediagno->fetch())
				{
					$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					$resultsDiagno->execute(array(
					'iddiagno'=>$linePrediagno->id_predia
					));
					
					$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
					$comptDiagno=$resultsDiagno->rowCount();
					
					if($comptDiagno!=0)
					{
						$ligne=$resultsDiagno->fetch();
						
						$Predia[] = $ligne->nomdiagno;
					}else{
						$Predia[] = $linePrediagno->autrepredia;
					}
					
				}
			
			}
		
			// $Postdia = array();
						
			if(isset ($_GET['idconsu']) AND $ligneConsultation->postdiagnostic !="")
			{
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$ligneConsultation->postdiagnostic
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Postdia[] = $ligneDiagnoPost->nomdiagno;
				}else{
					$Postdia[] = $ligneConsultation->postdiagnostic;
				}
				
			}
				
			$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
			
			$resultatsPostDiagno->execute(array(
			'id_consudia'=>$ligneConsult->id_consu
			
			))or die( print_r($connexion->errorInfo()));
				
			$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
			$postdiaCount = $resultatsPostDiagno->rowCount();
			
			if($postdiaCount!=0)
			{
				
				while($linePostDiagno=$resultatsPostDiagno->fetch())
				{
					$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					$resultsDiagno->execute(array(
					'iddiagno'=>$linePostDiagno->id_postdia
					));
					
					$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
					$comptDiagno=$resultsDiagno->rowCount();
					
					if($comptDiagno!=0)
					{
						$ligne=$resultsDiagno->fetch();
						
						$Postdia[] = $ligne->nomdiagno;
					}else{
						$Postdia[] = $linePostDiagno->autrepostdia;
					}
					
				}
			
			}
						
						
						if($DiagnoPostDone ==0)
						{	
							for($p=0;$p<sizeof($Predia);$p++)
							{
								echo '-'.$Predia[$p].'<br/>';
							}
						}else{
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								echo '- '.$Postdia[$p].'<br/>';
							}
						}
						
						// echo $DiagnoPostDone.'<br/>';
						?>
						</td>
						
					</tr>
			<?php
			
						$arrayConsult[$i][0]=$compteur;
						$arrayConsult[$i][1]=$dateconsu;
						$arrayConsult[$i][2]=$fullnamePa;
						$arrayConsult[$i][3]=$agePa;
						$arrayConsult[$i][4]=$sexePa;
						$arrayConsult[$i][5]=$nameprestaConsult;
						$arrayConsult[$i][6]=$consult;
						$arrayConsult[$i][7]=$nursery;
						$arrayConsult[$i][8]=$labs;
						
						
						if($DiagnoPostDone ==0)
						{
							$diagnoPre ='';
							
							for($p=0;$p<sizeof($Predia);$p++)
							{
								$diagnoPre .= $Predia[$p].',';
							}
							$arrayConsult[$i][9]=$diagnoPre;
						}else{
						
							$diagnoPost ='';
							
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								$diagnoPost .= $Postdia[$p].',';
							}
							$arrayConsult[$i][9]=$diagnoPost;
						}
						
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
				
				$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
				
				if($_GET['docVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Daily/");</script>';
					
				}else{
					if($_GET['docVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Monthly/");</script>';
						
					}else{
						if($_GET['docVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Annualy/");</script>';
							
						}else{
							if($_GET['docVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Custom/");</script>';
								
							}else{
								if($_GET['docVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Alltimes/");</script>';
									
								}
							}
						}
					}
				}
			}
			
			if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
			{				
				if($_GET['docVisit']=='dailyPersoMedic')
				{
					createRN('DRD');
					
				}else{
					if($_GET['docVisit']=='monthlyPersoMedic')
					{
						createRN('DRM');

					}else{
						if($_GET['docVisit']=='annualyPersoMedic')
						{
							createRN('DRA');
							
						}else{
							if($_GET['docVisit']=='customPersoMedic')
							{	
								createRN('DRC');
							
							}else{
								if($_GET['docVisit']=='gnlPersoMedic')
								{
									createRN('DRG');
								
								}
							}
						}
					}
				}
				
				echo '<script text="text/javascript">document.location.href="doctor_reportDataM.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoMedicReport=ok&createReportPdf=ok&createRN=0"</script>';
			}			
		}
		
		if(isset($_GET['divPersoBillReport']))
		{
	?>
		<div id="divPersoBillReport">

			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
			$resultConsult->execute(array(
			'med'=>$idDoc
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A8', 'N°')
							->setCellValue('B8', 'Date of consultation')
							->setCellValue('C8', 'Patient name')
							->setCellValue('D8', 'Type of consultation')
							->setCellValue('E8', 'Amount')
							->setCellValue('F8', 'Services')
							->setCellValue('G8', 'Amount')
							->setCellValue('H8', 'Nursing Care')
							->setCellValue('I8', 'Amount')
							->setCellValue('J8', 'Laboratory tests')
							->setCellValue('K8', 'Amount')
							->setCellValue('L8', 'Radiologie')
							->setCellValue('M8', 'Amount')
							->setCellValue('N8', 'Total');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:2%; border-right: 1px solid #bbb">N°</th>
						<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
						<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
						<th style="width:20%; border-right: 1px solid #bbb" colspan=2><?php echo getString(113);?></th>
						<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
						<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
						<th style="width:15%;"><?php echo getString(99);?></th>
						<th style="width:10%;"><?php echo 'Radiologie';?></th>
						<th style="width:10%;">Total Final</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
				$TotalGnlTypeConsu=0;
					
				$TotalGnlMedConsu=0;
					
				$TotalGnlMedInf=0;
					
				$TotalGnlMedLabo=0;
					
				$TotalGnlMedRadio=0;
					
				$TotalGnlPrice=0;
					
				
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())
					{
					/* 
						$prestaServ=array();
						$prixprestaServ=array();
						 */
						$prestaServ=array();
						$prixprestaServ=array();
						
						$prestaInf=array();
						$prixprestaInf=array();
						
						$prestaLabo=array();
						$prixprestaLabo=array();
						
						$prestaRadio=array();
						$prixprestaRadio=array();
						
						$TotalDayPrice=0;
						
						$consult = "";
						$nursery = "";
						$labs = "";						
						$radx = "";
						
			?>
					<tr>
						<td style="text-align:left;">
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
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
								
								$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
								
							}else{
								$fullnamePa="";
								echo '';
							}
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
						
						$TotalTypeConsu=0;
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
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
							}else{	
							
								if($lignePresta->nompresta != '')
								{
									$nameprestaConsult = $lignePresta->nompresta;
								}

							}
						}						
							echo $nameprestaConsult;
						?>
						</td>
						
						<td style="text-align:center;">
							<?php
								echo $ligneConsult->prixtypeconsult;
								
								$prixconsult=$ligneConsult->prixtypeconsult;
								
								$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
								
								$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
							?>
						</td>
						<td style="text-align:left;">
						
						<?php
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsult=$resultMedConsult->rowCount();
					
						$TotalMedConsu=0;
										
						if($comptMedConsult != 0)
						{
						?>
							<table class="printPreview" cellspacing="0"> 
							
							<tbody>
								<?php
									
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
									
									$idassuServ=$ligneMedConsult->id_assuServ;
									
									$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuServ->rowCount();
									
									for($s=1;$s<=$assuCount;$s++)
									{
										
										$getAssuServ=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuServ->execute(array(
										'idassu'=>$idassuServ
										));
										
										$getAssuServ->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuServ=$getAssuServ->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssuServ->nomassurance;
										}
									}
								?>
								<tr>
									
									<td style="text-align:left;">
									<?php	
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
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
											$prestaServ[] = $lignePresta->namepresta;
										}else{								
											echo $lignePresta->nompresta.'</td>';
											$prestaServ[] = $lignePresta->nompresta;
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td>';
										$prixconsu=$ligneMedConsult->prixprestationConsu;											
										$consult .= ''.$ligneMedConsult->prixprestationConsu.', ';
									}else{
										
										echo $ligneMedConsult->autreConsu.'</td>';
										$prestaServ[] = $ligneMedConsult->autreConsu;
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td>';
										
										$prixconsu=$ligneMedConsult->prixautreConsu;
										$consult .= ''.$ligneMedConsult->prixautreConsu.', ';
									}
									?>
								</tr>
								<?php
								
									$prixprestaServ[] = $prixconsu;
									
									$TotalMedConsu=$TotalMedConsu+$prixconsu;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
						?>
						</td>
					
						<td style="text-align:left;">
						
						<?php
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE AND mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'med'=>$idDoc,					
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

						$comptMedInf=$resultMedInf->rowCount();
					
						$TotalMedInf=0;	
										
						if($comptMedInf != 0)
						{
						?>		
							<table class="printPreview" cellspacing="0"> 
						
							<tbody>
								<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
											
									$idassuInf=$ligneMedInf->id_assuInf;
									
									$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuInf->rowCount();
									
									for($n=1;$n<=$assuCount;$n++)
									{
										
										$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuInf->execute(array(
										'idassu'=>$idassuInf
										));
										
										$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuInf=$getAssuInf->fetch())
										{
											$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
										}
									}
								?>
								<tr>
									<td style="text-align:left;">
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
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
											$prestaInf[] = $lignePresta->namepresta;
										}else{								
											echo $lignePresta->nompresta.'</td>';		
											$prestaInf[] = $lignePresta->nompresta;
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td>';
										$prixinf=$ligneMedInf->prixprestation;						
															
										$nursery .= ''.$ligneMedInf->prixprestation.', ';
									}else{
										
										echo $ligneMedInf->autrePrestaM.'</td>';
											$prestaInf[] = $ligneMedInf->autrePrestaM;
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
										$prixinf=$ligneMedInf->prixautrePrestaM;						
															
										$nursery .= ''.$ligneMedInf->prixautrePrestaM.', ';
									}
									?>
								</tr>
								<?php
									$prixprestaInf[] = $prixinf;
									
									$TotalMedInf=$TotalMedInf+$prixinf;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
						?>
						</td>
					
						<td style="text-align:left;">
						
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'med'=>$idDoc,					
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();

						$TotalMedLabo=0;
						
						if($comptMedLabo != 0)
						{
						?>	
							<table class="printPreview" cellspacing="0"> 
							
							<tbody>
								<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
								
									$idassuLab=$ligneMedLabo->id_assuLab;

									$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuLab->rowCount();
									
									for($l=1;$l<=$assuCount;$l++)
									{
										
										$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuLab->execute(array(
										'idassu'=>$idassuLab
										));
										
										$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuLab=$getAssuLab->fetch())
										{
											$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
										}
									}

								?>
								<tr>
									<td style="text-align:left;">
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta;
											$prestaLabo[] = $lignePresta->namepresta;
											
										}else{
											
											echo $lignePresta->nompresta;
											$prestaLabo[] = $lignePresta->nompresta;
										}
															
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
										$prixlabo=$ligneMedLabo->prixprestationExa;
										$labs .= ''.$ligneMedLabo->prixprestationExa.', ';
									}else{
										
										echo $ligneMedLabo->autreExamen;
										$prestaLabo[] = $ligneMedLabo->autreExamen;
											
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
										$prixlabo=$ligneMedLabo->prixautreExamen;
										$labs .= ''.$ligneMedLabo->prixautreExamen.', ';
									}
									?>
									</td>
								</tr>
								<?php
									$prixprestaLabo[] = $prixlabo;
									
									$TotalMedLabo=$TotalMedLabo+$prixlabo;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
						?>
						</td>

						<td style="text-align:left;font-weight:normal;">					
						<?php
								
						$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_uM=:med AND mr.id_consuRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
						$resultMedRadio->execute(array(
						'med'=>$idDoc,					
						'idMedRadio'=>$ligneConsult->id_consu
						));
						
						$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

						$comptMedRadio=$resultMedRadio->rowCount();
						
						$TotalMedRadio=0;
						
						if($comptMedRadio!=0)
						{
						?>
							<table class="printPreview" cellspacing="0"> 
							
							<tbody>
								<?php
								while($ligneMedRadio=$resultMedRadio->fetch())
								{
									
									$idassuRad=$ligneMedRadio->id_assuRad;

									$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuRad->rowCount();
									
									for($r=1;$r<=$assuCount;$r++)
									{
										
										$getAssuRad=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuRad->execute(array(
										'idassu'=>$idassuRad
										));
										
										$getAssuRad->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuRad=$getAssuRad->fetch())
										{
											$presta_assuRad='prestations_'.$ligneNomAssuRad->nomassurance;
										}
									}

								?>
								<tr>
									<td style="text-align:left;">
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedRadio->id_prestationRadio
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
											$prestaRadio[] = $lignePresta->namepresta;
										}else{
											echo $lignePresta->nompresta.'</td>';
											$prestaRadio[] = $lignePresta->nompresta;
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td>';
										$prixradio=$ligneMedRadio->prixprestationRadio;	
										$radx .= ''.$ligneMedRadio->prixprestationRadio.', ';
									}else{
										
										echo $ligneMedRadio->autreRadio.'</td>';
										$prestaRadio[] = $ligneMedRadio->autreRadio;
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
										$prixradio=$ligneMedRadio->prixautreRadio;
										$radx .= ''.$ligneMedRadio->prixautreRadio.', ';
									}									
									?>
									
								</tr>
								<?php
									$prixprestaRadio[] = $prixradio;
									
									$TotalMedRadio=$TotalMedRadio+$prixradio;
								}
								?>		
							</tbody>
							</table>
						<?php
						}							
						$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;							
						?>	
							
						<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
						
					</tr>
					<?php
					$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
				
					$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
						
					$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
					
					$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
						
					$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
					
					$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
						
					
						$arrayConsult[$i][0]=$compteur;
						$arrayConsult[$i][1]=$dateconsu;
						$arrayConsult[$i][2]=$fullnamePa;
						$arrayConsult[$i][3]=$nameprestaConsult;
						$arrayConsult[$i][4]=$prixconsult;
						
					
						$highNumber = max(sizeof($prixprestaServ),sizeof($prixprestaInf),sizeof($prixprestaLabo),sizeof($prixprestaRadio));
						
						if($highNumber==0)
						{
							$i++;
						}
					// echo '- Services : '.sizeof($prixprestaServ).'<br/>- Inf : '.sizeof($prixprestaInf).'<br/>- Labo : '.sizeof($prixprestaLabo).'<br/>- Radio : '.sizeof($prixprestaRadio).'<br/>';
						
						for($xLigne=0;$xLigne<$highNumber;$xLigne++)
						{
							if($xLigne>0)
							{
								for($e=0;$e<5;$e++)
								{
									$arrayConsult[$i][$e]='';			
								}
							}
							
							if($xLigne < sizeof($prixprestaServ))
							{						
								$arrayConsult[$i][5]=$prestaServ[$xLigne];		
								$arrayConsult[$i][6]=$prixprestaServ[$xLigne];		
							}else{
								$arrayConsult[$i][5]='';
								$arrayConsult[$i][6]='';
							}
							
							if($xLigne < sizeof($prixprestaInf))
							{						
								$arrayConsult[$i][7]=$prestaInf[$xLigne];
								$arrayConsult[$i][8]=$prixprestaInf[$xLigne];
							}else{
								$arrayConsult[$i][7]='';
								$arrayConsult[$i][8]='';
							}
							
							if($xLigne < sizeof($prixprestaLabo))
							{						
								$arrayConsult[$i][9]=$prestaLabo[$xLigne];		
								$arrayConsult[$i][10]=$prixprestaLabo[$xLigne];		
							}else{
								$arrayConsult[$i][9]='';
								$arrayConsult[$i][10]='';
							}
							
							if($xLigne < sizeof($prixprestaRadio))
							{						
								$arrayConsult[$i][11]=$prestaRadio[$xLigne];		
								$arrayConsult[$i][12]=$prixprestaRadio[$xLigne];		
							}else{
								$arrayConsult[$i][11]='';
								$arrayConsult[$i][12]='';
							}
							
							if($xLigne==0)
							{
								$arrayConsult[$i][13]=$TotalDayPrice;
							}
								
							$i++;
						}					
						$compteur++;
					}
					
					?>	

					<tr style="text-align:center;">
						<td colspan=4></td>
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
							->fromArray($arrayConsult,'','A10')
						
							->setCellValue('E'.(11+$i).'', ''.$TotalGnlTypeConsu.'')
							->setCellValue('G'.(11+$i).'', ''.$TotalGnlMedConsu.'')
							->setCellValue('I'.(11+$i).'', ''.$TotalGnlMedInf.'')
							->setCellValue('K'.(11+$i).'', ''.$TotalGnlMedLabo.'')
							->setCellValue('M'.(11+$i).'', ''.$TotalGnlMedRadio.'')
							->setCellValue('N'.(11+$i).'', ''.$TotalGnlPrice.'');

			}
			?>
		</div>
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
				
				if($_GET['docVisit']=='dailyPersoBill')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Daily/");</script>';
					
				}else{
					if($_GET['docVisit']=='monthlyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Monthly/");</script>';
						
					}else{
						if($_GET['docVisit']=='annualyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Annualy/");</script>';
							
						}else{
							if($_GET['docVisit']=='customPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Custom/");</script>';
								
							}else{
								if($_GET['docVisit']=='gnlPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/Individual/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/Individual/Alltimes/");</script>';
									
								}
							}
						}
					}
				}
			}
			
			if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
			{				
				if($_GET['docVisit']=='dailyPersoBill')
				{
					createRN('DRD');
					
				}else{
					if($_GET['docVisit']=='monthlyPersoBill')
					{
						createRN('DRM');

					}else{
						if($_GET['docVisit']=='annualyPersoBill')
						{
							createRN('DRA');
							
						}else{
							if($_GET['docVisit']=='customPersoBill')
							{	
								createRN('DRC');
							
							}else{
								if($_GET['docVisit']=='gnlPersoBill')
								{
									createRN('DRG');
								
								}
							}
						}
					}
				}
				
				if(isset($_GET['receptioniste']))
				{ 
					$recCaiss = '&receptioniste='.$_GET['receptioniste'];
				}else{				
					if(isset($_GET['caissier']))
					{
						$recCaiss = '&caissier='.$_GET['caissier'];
					}else{
						$recCaiss = '';
					}
				}

				echo '<script text="text/javascript">document.location.href="doctor_reportDataM.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0'.$recCaiss.'"</script>';
			}
			
			
		}
		
	}

	
	if(isset($_GET['gnlmed']))
	{
		$dailydateperso=$_GET['dailydateperso'];
		$docVisit=$_GET['docVisit'];
		

		// $dailydateperso;
	
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Report #'.$sn.'')
					 ->setSubject("Report information")
					 ->setDescription('Report information for all Doctors')
					 ->setKeywords("Report Excel")
					 ->setCategory("Report");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
	
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Report #')
					->setCellValue('B1', ''.$sn.'')
					->setCellValue('A2', 'Done by')
					->setCellValue('B2', ''.$doneby.'')
					->setCellValue('A3', 'Date')
					->setCellValue('B3', ''.$annee.'');
		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> General Doctor Report #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="doctor_reportDataM.php?gnlmed=<?php echo $_GET['gnlmed'];?>&dailydateperso=<?php echo $dailydateperso;?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];} if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<td style="text-align:left">
					
					<form method="post" action="doctor_reportDataM.php?gnlmed=<?php echo $_GET['gnlmed'];?>&dailydateperso=<?php echo $dailydateperso;?>&coordi=<?php echo $_SESSION['id'];?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';} if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportExcel=ok&createRN=<?php echo $createRN;?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];} if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="reportDataM.php?gnlmed=<?php echo $_GET['gnlmed'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];} if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
							<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
						</a>
					
				</td>
			</tr>
		
		</table>
			
	<?php
	
		if(isset($_GET['divPersoMedicReport']))
		{
	?>
		<div id="divPersoMedicReport">

			<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
			
			<thead>
				<tr>
					<th style="width:2%; border-right: 1px solid #bbb">N°</th>
					<th style="width:10%; border-right: 1px solid #bbb">Doctor</th>
					<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
					<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
					<th style="text-align:center; width:10%; border-right: 1px solid #bbb" colspan=2>Age/Sexe</th>
					<th style="width:15%; border-right: 1px solid #bbb"><?php echo getString(113);?></th>
					<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
					<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
					<th style="width:15%;"><?php echo getString(99);?></th>
					<th style="width:10%;"><?php echo 'Diagnosis';?></th>
				</tr> 
			</thead> 


			<?php
			
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A8', 'N°')
						->setCellValue('B8', 'Doctors name')
						->setCellValue('C8', 'Date of consultation')
						->setCellValue('D8', 'Patients name')
						->setCellValue('E8', 'Age')
						->setCellValue('F8', 'Gender')
						->setCellValue('G8', 'Type of consultation')
						->setCellValue('H8', 'Services')
						->setCellValue('I8', 'Nursing Care')
						->setCellValue('J8', 'Laboratory tests')
						->setCellValue('K8', 'Diagnosis');
			
			$resultConsult=$connexion->query('SELECT *FROM consultations c WHERE c.done=1 '.$dailydateperso.' ORDER BY c.id_uM');		
			/* $resultConsult->execute(array(
			'med'=>$idDoc
			)); */
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
			?>
				<tbody>
				<?php
				// $date='0000-00-00';
				$compteur=1;
				$nameDocCheck="";
				
					while($ligneConsult=$resultConsult->fetch())
					{
						$idDoc=$ligneConsult->id_uM;
						
						$consult = "";
						$nursery = "";
						$labs = "";						
						$diagno = "";
						
				?>
					
					<tr>
						
						<td style="text-align:left;">
						<?php
							echo $compteur;
						?>
						</td>
						<td style="text-align:center;background:#eee;">
						<?php
						
						$resultIdMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idmed ORDER BY u.full_name ASC');
						$resultIdMed->execute(array(
						'idmed'=>$ligneConsult->id_uM
						));
			
						$resultIdMed->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligneIdMed=$resultIdMed->fetch())
						{	
							$nameDoc=$ligneIdMed->full_name;
						
							/* if($nameDocCheck!=$nameDoc)
							{ */
								echo $ligneIdMed->full_name;
							/* }else{
								$nameDoc="";
							} */
						}							
						?>
						</td>
						
						<td>
						<?php 
							echo $ligneConsult->dateconsu;
							$dateconsu = $ligneConsult->dateconsu;
						?>
						</td>
						
						<td style="text-align:left;font-weight:bold;">
						<?php
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
								
			$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($lignePatient->date_naissance)));
			$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
			$interval = $datetime1->diff($datetime2);
			
			if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
			{
				$agePa = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
			}
									
								$sexePa = $lignePatient->sexe;
								
								echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
							}else{
								$fullnamePa="";
								echo '';
							}
						?>
						</td>
						
						<td style="text-align:left;">
						<?php echo $agePa;?>
						</td>
						
						<td style="text-align:left;">
						<?php echo $sexePa; ?>
						</td>
						
						<td style="text-align:left;">
						<?php
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
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
						?>
						
						<td style="text-align:left;">
						
						<?php
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'med'=>$idDoc,
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
								<tr>
									
									<td style="text-align:left;">
									<?php
									
									$idassuServ=$ligneMedConsult->id_assuServ;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($s=1;$s<=$assuCount;$s++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuServ
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
									
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');		
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
									}else{
										
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
					
						<td style="text-align:left;">
						
						<?php
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE AND mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'med'=>$idDoc,					
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
											
									$idassuInf=$ligneMedInf->id_assuInf;
									
									$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuInf->rowCount();
									
									for($n=1;$n<=$assuCount;$n++)
									{
										
										$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuInf->execute(array(
										'idassu'=>$idassuInf
										));
										
										$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuInf=$getAssuInf->fetch())
										{
											$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
										}
									}
								?>
								<tr>
									<td style="text-align:left;">
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
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
									}else{
										
										echo $ligneMedInf->autrePrestaM.'</td>';
										
										$nursery .= ''.$ligneMedInf->autrePrestaM.', ';
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
					
						<td style="text-align:left;">
						
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'med'=>$idDoc,					
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
									
									$idassuLab=$ligneMedLabo->id_assuLab;

									$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuLab->rowCount();
									
									for($l=1;$l<=$assuCount;$l++)
									{
										
										$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuLab->execute(array(
										'idassu'=>$idassuLab
										));
										
										$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuLab=$getAssuLab->fetch())
										{
											$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
										}
									}

								?>
								<tr>
									<td style="text-align:left;">
										<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												echo $lignePresta->namepresta;
												
												$labs .= ''.$lignePresta->namepresta.', ';
											}else{
												
												echo $lignePresta->nompresta;
												
												$labs .= ''.$lignePresta->nompresta.', ';
											}
										}else{
											
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

						<td style="text-align:left;font-weight:bold;">
						<?php
									
						$Postdia = array();
						$DiagnoPostDone=0;
															
						$resuPostdiagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
						
						$resuPostdiagnostic->execute(array(
						'idConsu'=>$ligneConsult->id_consu
						))or die( print_r($connexion->errorInfo()));
							
						$resuPostdiagnostic->setFetchMode(PDO::FETCH_OBJ);
							
						$lignePostdiagnostic=$resuPostdiagnostic->fetch();
						
							$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
							
							$resultatsDiagnoPost->execute(array(
							'iddiagno'=>$lignePostdiagnostic->postdiagnostic
							))or die( print_r($connexion->errorInfo()));
								
							$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
							
							if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
							{
								$DiagnoPostDone=1;
							}else{
							
								if($lignePostdiagnostic->postdiagnostic != "")
								{
									$DiagnoPostDone=1;
								}
							}

							
						$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
						
						$resultatsPostDiagno->execute(array(
						'id_consudia'=>$lignePostdiagnostic->id_consu
						
						))or die( print_r($connexion->errorInfo()));
							
						$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
						$postdiaCount = $resultatsPostDiagno->rowCount();
						
						if($postdiaCount!=0)
						{
							
							while($linePostDiagno=$resultatsPostDiagno->fetch())
							{
								$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
								$resultsDiagno->execute(array(
								'iddiagno'=>$linePostDiagno->id_postdia
								));
								
								$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
								$comptDiagno=$resultsDiagno->rowCount();
								
								if($comptDiagno!=0)
								{
									$DiagnoPostDone=1;
								}else{
									if($linePostDiagno->autrepostdia !="")
									{
										$DiagnoPostDone=1;
									}
								}
								
							}
						
						}


			$Predia = array();
							
			$resuPrediagnostic=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idConsu');
			
			$resuPrediagnostic->execute(array(
			'idConsu'=>$ligneConsult->id_consu
			))or die( print_r($connexion->errorInfo()));
				
			$resuPrediagnostic->setFetchMode(PDO::FETCH_OBJ);
				
			$lignePrediagnostic=$resuPrediagnostic->fetch();
			
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$lignePrediagnostic->prediagnostic
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Predia[] = $ligneDiagnoPost->nomdiagno;
				}else{
				
					if($lignePrediagnostic->prediagnostic != "")
					{
						$Predia[] = $lignePrediagnostic->prediagnostic;
					}
				}

				
			$resultatsPrediagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_predia IS NOT NULL OR d.autrepredia!="") ORDER BY d.id_dia');
			
			$resultatsPrediagno->execute(array(
			'id_consudia'=>$lignePrediagnostic->id_consu
			
			))or die( print_r($connexion->errorInfo()));
				
			$resultatsPrediagno->setFetchMode(PDO::FETCH_OBJ);
			$prediaCount = $resultatsPrediagno->rowCount();
			
			if($prediaCount!=0)
			{
				
				while($linePrediagno=$resultatsPrediagno->fetch())
				{
					$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					$resultsDiagno->execute(array(
					'iddiagno'=>$linePrediagno->id_predia
					));
					
					$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
					$comptDiagno=$resultsDiagno->rowCount();
					
					if($comptDiagno!=0)
					{
						$ligne=$resultsDiagno->fetch();
						
						$Predia[] = $ligne->nomdiagno;
					}else{
						$Predia[] = $linePrediagno->autrepredia;
					}
					
				}
			
			}
		
			// $Postdia = array();
						
			if(isset ($_GET['idconsu']) AND $ligneConsultation->postdiagnostic !="")
			{
				$resultatsDiagnoPost=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
				
				$resultatsDiagnoPost->execute(array(
				'iddiagno'=>$ligneConsultation->postdiagnostic
				))or die( print_r($connexion->errorInfo()));
					
				$resultatsDiagnoPost->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligneDiagnoPost=$resultatsDiagnoPost->fetch())
				{
					$Postdia[] = $ligneDiagnoPost->nomdiagno;
				}else{
					$Postdia[] = $ligneConsultation->postdiagnostic;
				}
				
			}
				
			$resultatsPostDiagno=$connexion->prepare('SELECT *FROM prepostdia d WHERE d.id_consudia=:id_consudia AND (d.id_postdia IS NOT NULL OR d.autrepostdia!="") ORDER BY d.id_dia');
			
			$resultatsPostDiagno->execute(array(
			'id_consudia'=>$ligneConsult->id_consu
			
			))or die( print_r($connexion->errorInfo()));
				
			$resultatsPostDiagno->setFetchMode(PDO::FETCH_OBJ);
			$postdiaCount = $resultatsPostDiagno->rowCount();
			
			if($postdiaCount!=0)
			{
				
				while($linePostDiagno=$resultatsPostDiagno->fetch())
				{
					$resultsDiagno=$connexion->prepare('SELECT *FROM diagnostic d WHERE d.id_diagno=:iddiagno ORDER BY d.nomdiagno');
					$resultsDiagno->execute(array(
					'iddiagno'=>$linePostDiagno->id_postdia
					));
					
					$resultsDiagno->setFetchMode(PDO::FETCH_OBJ);
					$comptDiagno=$resultsDiagno->rowCount();
					
					if($comptDiagno!=0)
					{
						$ligne=$resultsDiagno->fetch();
						
						$Postdia[] = $ligne->nomdiagno;
					}else{
						$Postdia[] = $linePostDiagno->autrepostdia;
					}
					
				}
			
			}
						
						
						if($DiagnoPostDone ==0)
						{	
							for($p=0;$p<sizeof($Predia);$p++)
							{
								echo '-'.$Predia[$p].'<br/>';
							}
						}else{
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								echo '- '.$Postdia[$p].'<br/>';
							}
						}
						
						// echo $DiagnoPostDone.'<br/>';
						?>
						</td>
						
					</tr>
			<?php
			
						$arrayConsult[$i][0]=$compteur;
						$arrayConsult[$i][1]=$nameDoc;
						$arrayConsult[$i][2]=$dateconsu;
						$arrayConsult[$i][3]=$fullnamePa;
						$arrayConsult[$i][4]=$agePa;
						$arrayConsult[$i][5]=$sexePa;
						$arrayConsult[$i][6]=$nameprestaConsult;
						$arrayConsult[$i][7]=$consult;
						$arrayConsult[$i][8]=$nursery;
						$arrayConsult[$i][9]=$labs;
						
						
						if($DiagnoPostDone ==0)
						{
							$diagnoPre ='';
							
							for($p=0;$p<sizeof($Predia);$p++)
							{
								$diagnoPre .= $Predia[$p].',';
							}
							$arrayConsult[$i][10]=$diagnoPre;
						}else{
						
							$diagnoPost ='';
							
							for($p=0;$p<sizeof($Postdia);$p++)
							{
								$diagnoPost .= $Postdia[$p].',';
							}
							$arrayConsult[$i][10]=$diagnoPost;
						}
						
						$i++;
						
						$nameDocCheck=$nameDoc;	
						$compteur++;
						
					}
			?>		
				</tbody>
			<?php
				
						// echo $arrayConsult[0][1].'__';
			
			}
				$objPHPExcel->setActiveSheetIndex(0)
							->fromArray($arrayConsult,'','A10');
			
			?>
		
			</table>
		</div>
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn= str_replace('/', '_', $sn);
				
				if($_GET['docVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Daily/");</script>';
					
				}else{
					if($_GET['docVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Monthly/");</script>';
						
					}else{
						if($_GET['docVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Annualy/");</script>';
							
						}else{
							if($_GET['docVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Custom/");</script>';
								
							}else{
								if($_GET['docVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Alltimes/");</script>';
									
								}
							}
						}
					}
				}
			}
			
			if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
			{				
				if($_GET['docVisit']=='dailyPersoMedic')
				{
					createRN('GDRD');
					
				}else{
					if($_GET['docVisit']=='monthlyPersoMedic')
					{
						createRN('GDRM');

					}else{
						if($_GET['docVisit']=='annualyPersoMedic')
						{
							createRN('GDRA');
							
						}else{
							if($_GET['docVisit']=='customPersoMedic')
							{	
								createRN('GDRC');
							
							}else{
								if($_GET['docVisit']=='gnlPersoMedic')
								{
									createRN('GDRG');
								
								}
							}
						}
					}
				}
				
				if(isset($_GET['receptioniste']))
				{ 
					$recCaiss = '&receptioniste='.$_GET['receptioniste'];
				}else{				
					if(isset($_GET['caissier']))
					{
						$recCaiss = '&caissier='.$_GET['caissier'];
					}else{
						$recCaiss = '';
					}
				}

				echo '<script text="text/javascript">document.location.href="doctor_reportDataM.php?gnlmed='.$_GET['gnlmed'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoMedicReport=ok&createReportPdf=ok&createRN=0'.$recCaiss.'"</script>';
			}			
		}
		
		if(isset($_GET['divPersoBillReport']))
		{
	?>
		<div id="divPersoBillReport">

			<?php
			
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_uM=:med AND c.done=1 '.$dailydateperso.' ORDER BY c.dateconsu ASC');		
			$resultConsult->execute(array(
			'med'=>$idDoc
			));
			
			$resultConsult->setFetchMode(PDO::FETCH_OBJ);

			$comptConsult=$resultConsult->rowCount();
	
			$i=0;
			
			if($comptConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A8', 'N°')
							->setCellValue('B8', 'Date of consultation')
							->setCellValue('C8', 'Patient name')
							->setCellValue('D8', 'Type of consultation')
							->setCellValue('E8', 'Amount')
							->setCellValue('F8', 'Services')
							->setCellValue('G8', 'Amount')
							->setCellValue('H8', 'Nursing Care')
							->setCellValue('I8', 'Amount')
							->setCellValue('J8', 'Laboratory tests')
							->setCellValue('K8', 'Amount')
							->setCellValue('L8', 'Radiologie')
							->setCellValue('M8', 'Amount')
							->setCellValue('N8', 'Total');
				
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:2%; border-right: 1px solid #bbb">N°</th>
						<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
						<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
						<th style="width:20%; border-right: 1px solid #bbb" colspan=2><?php echo getString(113);?></th>
						<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
						<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
						<th style="width:15%;"><?php echo getString(99);?></th>
						<th style="width:10%;"><?php echo 'Radiologie';?></th>
						<th style="width:10%;">Total Final</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
				$TotalGnlTypeConsu=0;
					
				$TotalGnlMedConsu=0;
					
				$TotalGnlMedInf=0;
					
				$TotalGnlMedLabo=0;
					
				$TotalGnlMedRadio=0;
					
				$TotalGnlPrice=0;
					
				
				$compteur=1;
				
					while($ligneConsult=$resultConsult->fetch())
					{
					/* 
						$prestaServ=array();
						$prixprestaServ=array();
						 */
						$prestaServ=array();
						$prixprestaServ=array();
						
						$prestaInf=array();
						$prixprestaInf=array();
						
						$prestaLabo=array();
						$prixprestaLabo=array();
						
						$prestaRadio=array();
						$prixprestaRadio=array();
						
						$TotalDayPrice=0;
						
						$consult = "";
						$nursery = "";
						$labs = "";						
						$radx = "";
						
			?>
					<tr>
						<td style="text-align:left;">
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
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
							$resultPatient->execute(array(
							'operation'=>$ligneConsult->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								echo $lignePatient->full_name.'<br/>('.$ligneConsult->numero.')';
								
								$fullnamePa = $lignePatient->full_name.' ('.$ligneConsult->numero.')';
								
							}else{
								$fullnamePa="";
								echo '';
							}
						?>
						</td>
						
						<td style="text-align:left;">
						<?php
						
						$TotalTypeConsu=0;
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations_private p WHERE p.id_prestation=:prestaId');		
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
							}else{	
							
								if($lignePresta->nompresta != '')
								{
									$nameprestaConsult = $lignePresta->nompresta;
								}

							}
						}						
							echo $nameprestaConsult;
						?>
						</td>
						
						<td style="text-align:center;">
							<?php
								echo $ligneConsult->prixtypeconsult;
								
								$prixconsult=$ligneConsult->prixtypeconsult;
								
								$TotalTypeConsu=$TotalTypeConsu+$prixconsult;
								
								$TotalDayPrice=$TotalDayPrice+$TotalTypeConsu;
							?>
						</td>
						<td style="text-align:left;">
						
						<?php
						$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc WHERE mc.id_uM=:med AND mc.id_consuMed=:idMedConsu ORDER BY mc.id_medconsu');		
						$resultMedConsult->execute(array(
						'med'=>$idDoc,
						'idMedConsu'=>$ligneConsult->id_consu
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsult=$resultMedConsult->rowCount();
					
						$TotalMedConsu=0;
										
						if($comptMedConsult != 0)
						{
						?>
							<table class="printPreview" cellspacing="0"> 
							
							<tbody>
								<?php
									
								while($ligneMedConsult=$resultMedConsult->fetch())
								{
									
									$idassuServ=$ligneMedConsult->id_assuServ;
									
									$comptAssuServ=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuServ->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuServ->rowCount();
									
									for($s=1;$s<=$assuCount;$s++)
									{
										
										$getAssuServ=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuServ->execute(array(
										'idassu'=>$idassuServ
										));
										
										$getAssuServ->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuServ=$getAssuServ->fetch())
										{
											$presta_assuServ='prestations_'.$ligneNomAssuServ->nomassurance;
										}
									}
								?>
								<tr>
									
									<td style="text-align:left;">
									<?php	
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuServ.' p WHERE p.id_prestation=:prestaId');
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
											$prestaServ[] = $lignePresta->namepresta;
										}else{								
											echo $lignePresta->nompresta.'</td>';
											$prestaServ[] = $lignePresta->nompresta;
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixprestationConsu.'</td>';
										$prixconsu=$ligneMedConsult->prixprestationConsu;											
										$consult .= ''.$ligneMedConsult->prixprestationConsu.', ';
									}else{
										
										echo $ligneMedConsult->autreConsu.'</td>';
										$prestaServ[] = $ligneMedConsult->autreConsu;
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedConsult->prixautreConsu.'</td>';
										
										$prixconsu=$ligneMedConsult->prixautreConsu;
										$consult .= ''.$ligneMedConsult->prixautreConsu.', ';
									}
									?>
								</tr>
								<?php
								
									$prixprestaServ[] = $prixconsu;
									
									$TotalMedConsu=$TotalMedConsu+$prixconsu;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedConsu;
						?>
						</td>
					
						<td style="text-align:left;">
						
						<?php
						$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi WHERE AND mi.id_uM=:med AND mi.id_consuInf=:idMedInf ORDER BY mi.id_medinf');		
						$resultMedInf->execute(array(
						'med'=>$idDoc,					
						'idMedInf'=>$ligneConsult->id_consu
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

						$comptMedInf=$resultMedInf->rowCount();
					
						$TotalMedInf=0;	
										
						if($comptMedInf != 0)
						{
						?>		
							<table class="printPreview" cellspacing="0"> 
						
							<tbody>
								<?php
								while($ligneMedInf=$resultMedInf->fetch())
								{
											
									$idassuInf=$ligneMedInf->id_assuInf;
									
									$comptAssuInf=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuInf->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuInf->rowCount();
									
									for($n=1;$n<=$assuCount;$n++)
									{
										
										$getAssuInf=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuInf->execute(array(
										'idassu'=>$idassuInf
										));
										
										$getAssuInf->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuInf=$getAssuInf->fetch())
										{
											$presta_assuInf='prestations_'.$ligneNomAssuInf->nomassurance;
										}
									}
								?>
								<tr>
									<td style="text-align:left;">
									<?php 
										
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuInf.' p WHERE p.id_prestation=:prestaId');		
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
											$prestaInf[] = $lignePresta->namepresta;
										}else{								
											echo $lignePresta->nompresta.'</td>';		
											$prestaInf[] = $lignePresta->nompresta;
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixprestation.'</td>';
										$prixinf=$ligneMedInf->prixprestation;						
															
										$nursery .= ''.$ligneMedInf->prixprestation.', ';
									}else{
										
										echo $ligneMedInf->autrePrestaM.'</td>';
											$prestaInf[] = $ligneMedInf->autrePrestaM;
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedInf->prixautrePrestaM.'</td>';
										$prixinf=$ligneMedInf->prixautrePrestaM;						
															
										$nursery .= ''.$ligneMedInf->prixautrePrestaM.', ';
									}
									?>
								</tr>
								<?php
									$prixprestaInf[] = $prixinf;
									
									$TotalMedInf=$TotalMedInf+$prixinf;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedInf;
						?>
						</td>
					
						<td style="text-align:left;">
						
						<?php
						$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
						$resultMedLabo->execute(array(
						'med'=>$idDoc,					
						'idMedLabo'=>$ligneConsult->id_consu
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();

						$TotalMedLabo=0;
						
						if($comptMedLabo != 0)
						{
						?>	
							<table class="printPreview" cellspacing="0"> 
							
							<tbody>
								<?php
								while($ligneMedLabo=$resultMedLabo->fetch())
								{
								
									$idassuLab=$ligneMedLabo->id_assuLab;

									$comptAssuLab=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuLab->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuLab->rowCount();
									
									for($l=1;$l<=$assuCount;$l++)
									{
										
										$getAssuLab=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuLab->execute(array(
										'idassu'=>$idassuLab
										));
										
										$getAssuLab->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuLab=$getAssuLab->fetch())
										{
											$presta_assuLab='prestations_'.$ligneNomAssuLab->nomassurance;
										}
									}

								?>
								<tr>
									<td style="text-align:left;">
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedLabo->id_prestationExa
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta;
											$prestaLabo[] = $lignePresta->namepresta;
											
										}else{
											
											echo $lignePresta->nompresta;
											$prestaLabo[] = $lignePresta->nompresta;
										}
															
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
										$prixlabo=$ligneMedLabo->prixprestationExa;
										$labs .= ''.$ligneMedLabo->prixprestationExa.', ';
									}else{
										
										echo $ligneMedLabo->autreExamen;
										$prestaLabo[] = $ligneMedLabo->autreExamen;
											
										echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
										$prixlabo=$ligneMedLabo->prixautreExamen;
										$labs .= ''.$ligneMedLabo->prixautreExamen.', ';
									}
									?>
									</td>
								</tr>
								<?php
									$prixprestaLabo[] = $prixlabo;
									
									$TotalMedLabo=$TotalMedLabo+$prixlabo;
								}
								?>		
							</tbody>
							</table>
						<?php
						}
						
						$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
						?>
						</td>

						<td style="text-align:left;font-weight:normal;">					
						<?php
								
						$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr WHERE mr.id_uM=:med AND mr.id_consuRadio=:idMedRadio ORDER BY mr.id_medradio DESC');
						$resultMedRadio->execute(array(
						'med'=>$idDoc,					
						'idMedRadio'=>$ligneConsult->id_consu
						));
						
						$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

						$comptMedRadio=$resultMedRadio->rowCount();
						
						$TotalMedRadio=0;
						
						if($comptMedRadio!=0)
						{
						?>
							<table class="printPreview" cellspacing="0"> 
							
							<tbody>
								<?php
								while($ligneMedRadio=$resultMedRadio->fetch())
								{
									
									$idassuRad=$ligneMedRadio->id_assuRad;

									$comptAssuRad=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
									$comptAssuRad->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuRad->rowCount();
									
									for($r=1;$r<=$assuCount;$r++)
									{
										
										$getAssuRad=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuRad->execute(array(
										'idassu'=>$idassuRad
										));
										
										$getAssuRad->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssuRad=$getAssuRad->fetch())
										{
											$presta_assuRad='prestations_'.$ligneNomAssuRad->nomassurance;
										}
									}

								?>
								<tr>
									<td style="text-align:left;">
									<?php
									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuRad.' p WHERE p.id_prestation=:prestaId');		
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedRadio->id_prestationRadio
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta=$resultPresta->rowCount();
									
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>';
											$prestaRadio[] = $lignePresta->namepresta;
										}else{
											echo $lignePresta->nompresta.'</td>';
											$prestaRadio[] = $lignePresta->nompresta;
										}
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixprestationRadio.'</td>';
										$prixradio=$ligneMedRadio->prixprestationRadio;	
										$radx .= ''.$ligneMedRadio->prixprestationRadio.', ';
									}else{
										
										echo $ligneMedRadio->autreRadio.'</td>';
										$prestaRadio[] = $ligneMedRadio->autreRadio;
										
										echo '<td style="border-left:1px solid #eee;">'.$ligneMedRadio->prixautreRadio.'</td>';
										$prixradio=$ligneMedRadio->prixautreRadio;
										$radx .= ''.$ligneMedRadio->prixautreRadio.', ';
									}									
									?>
									
								</tr>
								<?php
									$prixprestaRadio[] = $prixradio;
									
									$TotalMedRadio=$TotalMedRadio+$prixradio;
								}
								?>		
							</tbody>
							</table>
						<?php
						}							
						$TotalDayPrice=$TotalDayPrice+$TotalMedRadio;							
						?>	
							
						<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
						
					</tr>
					<?php
					$TotalGnlTypeConsu=$TotalGnlTypeConsu + $TotalTypeConsu;
				
					$TotalGnlMedConsu=$TotalGnlMedConsu + $TotalMedConsu;
						
					$TotalGnlMedInf=$TotalGnlMedInf + $TotalMedInf;
					
					$TotalGnlMedLabo=$TotalGnlMedLabo + $TotalMedLabo;
						
					$TotalGnlMedRadio=$TotalGnlMedRadio + $TotalMedRadio;
					
					$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
						
					
						$arrayConsult[$i][0]=$compteur;
						$arrayConsult[$i][1]=$dateconsu;
						$arrayConsult[$i][2]=$fullnamePa;
						$arrayConsult[$i][3]=$nameprestaConsult;
						$arrayConsult[$i][4]=$prixconsult;
						
					
						$highNumber = max(sizeof($prixprestaServ),sizeof($prixprestaInf),sizeof($prixprestaLabo),sizeof($prixprestaRadio));
						
						if($highNumber==0)
						{
							$i++;
						}
					// echo '- Services : '.sizeof($prixprestaServ).'<br/>- Inf : '.sizeof($prixprestaInf).'<br/>- Labo : '.sizeof($prixprestaLabo).'<br/>- Radio : '.sizeof($prixprestaRadio).'<br/>';
						
						for($xLigne=0;$xLigne<$highNumber;$xLigne++)
						{
							if($xLigne>0)
							{
								for($e=0;$e<5;$e++)
								{
									$arrayConsult[$i][$e]='';			
								}
							}
							
							if($xLigne < sizeof($prixprestaServ))
							{						
								$arrayConsult[$i][5]=$prestaServ[$xLigne];		
								$arrayConsult[$i][6]=$prixprestaServ[$xLigne];		
							}else{
								$arrayConsult[$i][5]='';
								$arrayConsult[$i][6]='';
							}
							
							if($xLigne < sizeof($prixprestaInf))
							{						
								$arrayConsult[$i][7]=$prestaInf[$xLigne];
								$arrayConsult[$i][8]=$prixprestaInf[$xLigne];
							}else{
								$arrayConsult[$i][7]='';
								$arrayConsult[$i][8]='';
							}
							
							if($xLigne < sizeof($prixprestaLabo))
							{						
								$arrayConsult[$i][9]=$prestaLabo[$xLigne];		
								$arrayConsult[$i][10]=$prixprestaLabo[$xLigne];		
							}else{
								$arrayConsult[$i][9]='';
								$arrayConsult[$i][10]='';
							}
							
							if($xLigne < sizeof($prixprestaRadio))
							{						
								$arrayConsult[$i][11]=$prestaRadio[$xLigne];		
								$arrayConsult[$i][12]=$prixprestaRadio[$xLigne];		
							}else{
								$arrayConsult[$i][11]='';
								$arrayConsult[$i][12]='';
							}
							
							if($xLigne==0)
							{
								$arrayConsult[$i][13]=$TotalDayPrice;
							}
								
							$i++;
						}					
						$compteur++;
					}
					
					?>	

					<tr style="text-align:center;">
						<td colspan=4></td>
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
							->fromArray($arrayConsult,'','A10')
						
							->setCellValue('E'.(11+$i).'', ''.$TotalGnlTypeConsu.'')
							->setCellValue('G'.(11+$i).'', ''.$TotalGnlMedConsu.'')
							->setCellValue('I'.(11+$i).'', ''.$TotalGnlMedInf.'')
							->setCellValue('K'.(11+$i).'', ''.$TotalGnlMedLabo.'')
							->setCellValue('M'.(11+$i).'', ''.$TotalGnlMedRadio.'')
							->setCellValue('N'.(11+$i).'', ''.$TotalGnlPrice.'');

			}
			?>
		</div>
		<?php
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn= $fullname.'--'.str_replace('/', '_', $sn);
				
				if($_GET['docVisit']=='dailyPersoBill')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Daily/'.$reportsn.'.xlsx');
					
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Daily/");</script>';
					
				}else{
					if($_GET['docVisit']=='monthlyPersoBill')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Monthly/'.$reportsn.'.xlsx');
					
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Monthly/");</script>';
						
					}else{
						if($_GET['docVisit']=='annualyPersoBill')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Annualy/'.$reportsn.'.xlsx');
						
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Annualy/");</script>';
							
						}else{
							if($_GET['docVisit']=='customPersoBill')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Custom/");</script>';
								
							}else{
								if($_GET['docVisit']=='gnlPersoBill')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/DoctorReport/General/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/My Documents/Reports/DoctorReport/General/Alltimes/");</script>';
									
								}
							}
						}
					}
				}
			}
			
			if((isset($_GET['createReportExcel']) OR isset($_GET['createReportPdf'])) AND $_GET['createRN']==1)
			{				
				if($_GET['docVisit']=='dailyPersoBill')
				{
					createRN('DRD');
					
				}else{
					if($_GET['docVisit']=='monthlyPersoBill')
					{
						createRN('DRM');

					}else{
						if($_GET['docVisit']=='annualyPersoBill')
						{
							createRN('DRA');
							
						}else{
							if($_GET['docVisit']=='customPersoBill')
							{	
								createRN('DRC');
							
							}else{
								if($_GET['docVisit']=='gnlPersoBill')
								{
									createRN('DRG');
								
								}
							}
						}
					}
				}
				
				if(isset($_GET['receptioniste']))
				{ 
					$recCaiss = '&receptioniste='.$_GET['receptioniste'];
				}else{				
					if(isset($_GET['caissier']))
					{
						$recCaiss = '&caissier='.$_GET['caissier'];
					}else{
						$recCaiss = '';
					}
				}

				
				echo '<script text="text/javascript">document.location.href="doctor_reportDataM.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0'.$recCaiss.'"</script>';
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
						<span style="font-weight:bold">Dr Signature</span>
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