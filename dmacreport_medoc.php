<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");

$arrayMedocReport= unserialize($_POST['arrayMedoc']);
			

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

if(isset($_GET['num']))
{
	$num=$_GET['num'];
}
$dailydateperso=$_GET['dailydateperso'];
$paVisit=$_GET['paVisit'];


		if($_GET['paVisit']=='dailyPersoMedic')
		{
			$sn = showRN('MEDRD');
		}else{
			if($_GET['paVisit']=='monthlyPersoMedic')
			{
				$sn = showRN('MEDRM');
			}else{
				if($_GET['paVisit']=='annualyPersoMedic')
				{
					$sn = showRN('MEDRA');
				}else{
					if($_GET['paVisit']=='customPersoMedic')
					{
						$sn = showRN('MEDRC');
					}else{
						if($_GET['paVisit']=='gnlPersoMedic')
						{
							$sn = showRN('MEDRG');
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
	<title>
	<?php 
	if(isset($_GET['medocreport']))
	{
		echo 'Medicament Gnl Report#'.$sn;
	}
	?>
	</title>

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
$idLabo=$_SESSION['id'];

if($connected==true AND isset($_SESSION['id']))
{
	if(isset($_SESSION['codeL']))
	{
		$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u and l.id_u=:operation');
		$resultatsLabo->execute(array(
		'operation'=>$idLabo	
		));
		$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);
		if($ligneLabo=$resultatsLabo->fetch())
		{
			$doneby = $ligneLabo->full_name;
			$codelabo = $ligneLabo->codelabo;
		}
	}else{	
		if(isset($_SESSION['codeC']))
		{
			$resultatsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
			$resultatsLabo->execute(array(
			'operation'=>$idLabo	
			));
			$resultatsLabo->setFetchMode(PDO::FETCH_OBJ);
			if($ligneLabo=$resultatsLabo->fetch())
			{
				$doneby = $ligneLabo->full_name;
				$codelabo = $ligneLabo->codecoordi;
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
		$drawing = new BCGDrawing('barcode/png/barcode'.$codelabo.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
?>
	<div id="Report" style="border:1px solid #eee; border-radius:4px; font-size:85%; margin:1% auto; padding:10px 20px; width:95%;">
	
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:80%;" class="buttonBill">
			
			<tr>
				<td style="text-align:right">
					
					<form method="post" action="dmacreport_medoc.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?>&dailydateperso=<?php echo $dailydateperso;?>&idlabo=<?php echo $_SESSION['id'];?><?php if(isset($_GET['medocreport'])){ echo '&medocreport=ok';}?>&paVisit=<?php echo $paVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportPdf=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">

						<input type="hidden" name="arrayMedoc" value="<?php echo htmlspecialchars(serialize($arrayMedocReport));?>"/>
							
						<button type="submit" class="btn-large" name="savebill" style="width:250px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Medicament Report</button>
					</form>
					
				</td>
				
				<td style="text-align:left">
					
					<form method="post" action="dmacreport_medoc.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?>&dailydateperso=<?php echo $dailydateperso;?>&idlabo=<?php echo $_SESSION['id'];?>&paVisit=<?php echo $paVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}?><?php if(isset($_GET['medocreport'])){ echo '&medocreport=ok';}?><?php if(isset($_GET['divPersoBillReport'])){ echo '&divPersoBillReport=ok';}?>&createReportExcel=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" enctype="multipart/form-data">

						<input type="hidden" name="arrayMedoc" value="<?php echo htmlspecialchars(serialize($arrayMedocReport));?>"/>
							
						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel</button>
					</form>
					
				</td>
				
				<td style="text-align:right">
					
					<a href="medoc_report.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?>&idlabo=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['medocreport'])){ echo '&medocreport=ok';}?><?php if(isset($_GET['createReportExcel'])){ echo '&finishbtn=ok&paVisit='.$paVisit;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['createReportPdf'])){ echo "display:inline";}else{ echo "display:none";}?>">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</a>
					
					<a href="medoc_report.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?>&idlabo=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['medocreport'])){ echo '&medocreport=ok';}?>&finishbtn=ok&paVisit=<?php echo $paVisit;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="<?php if(!isset($_GET['createReportPdf'])){ echo "display:none";}?>">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141);?></button>
					</a>
				</td>
			</tr>
		
		</table>
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
				<img src="barcode/png/barcode'.$codelabo.'.png" style="height:auto;"/>	
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
			$num=$ligne->numero;
			$fullname=$ligne->full_name;
			$bill= $ligne->bill;
			$idassurance=$ligne->id_assurance;
			$numpolice=$ligne->numeropolice;
			$adherent=$ligne->adherent;
			
			
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
			
			
			if($ligne->carteassuranceid != "")
			{
				$idcard = $ligne->carteassuranceid;
			}else{
				$idcard = "";
			}
			
			
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

			
			$insupercent= 100 - $ligne->bill;
			
			$percentpatient= 100 - $insupercent;
			
			
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
		

		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Labo analysis Report #'.$sn.'')
					 ->setSubject("Labo Report information")
					 ->setDescription('Labo Report information for patient : '.$num.', '.$fullname.'')
					 ->setKeywords("Labo Report Excel")
					 ->setCategory("Labo Report");

			for($col = ord('a'); $col <= ord('z'); $col++)
			{
				$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'S/N')
						->setCellValue('B1', ''.$num.'')
						->setCellValue('A2', 'Full name')
						->setCellValue('B2', ''.$fullname.'')
						
						->setCellValue('A3', 'Adresse')
						->setCellValue('B3', ''.$adresse.'')
						
						->setCellValue('A4', 'Insurance')
						->setCellValue('B4', ''.$insurance.' '.$percentpatient.'%')
						->setCellValue('F1', 'Labo Analysis Report #')
						->setCellValue('G1', ''.$sn.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.date('d-M-Y', strtotime($annee)).'');
			
	
		$userinfo = '<table style="width:100%; margin-top:10px;">
					
						<tr>
							<td style="text-align:left;">
								Full name: 
								<span style="font-weight:bold">'.$ligne->nom_u.' '.$ligne->prenom_u.'</span><br/>
								Gender: <span style="font-weight:bold">'.$sexe.'</span><br/>
								Adress: <span style="font-weight:bold">'.$adresse.'</span>
							</td>
							
							<td style="text-align:center;">
								Insurance type: <span style="font-weight:bold">';
								
					$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
					
					$resultAssurance->execute(array(
					'assuId'=>$ligne->id_assurance
					));
					
					$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

					if($ligneAssu=$resultAssurance->fetch())
					{
						if($ligneAssu->id_assurance == $ligne->id_assurance)
						{
							$idassu=$ligneAssu->id_assurance;
							$insurance=$ligneAssu->nomassurance;
							$numpolice=$ligne->numeropolice;
							$adherent=$ligne->adherent;
							
							$userinfo .= ''.$ligneAssu->nomassurance.'</span><br/>';
						
					
							if($idassurance!=1)
							{
								$userinfo .= 'N° insurance card: 
								<span style="font-weight:bold">'.$idcard;
								
								if($numpolice!="")
								{
									$userinfo .= '</span><br/>
									
									N° police: 
									<span style="font-weight:bold">'.$numpolice;
								}
									
								$userinfo .= '</span><br/>
								
								Principal member: 
								<span style="font-weight:bold">'.$adherent;
							}
						}
					}

						$userinfo .='</span>
						</td>
						
						<td style="text-align:right;">
							Patient ID: <span style="font-weight:bold">'.$ligne->numero.'</span><br/>
							Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($ligne->date_naissance)).'</span><br/>
							
							
						</td>
										
					</tr>		
				</table>';

		echo $userinfo;
		
		}
	
		if(isset($_GET['divPersoMedicReport']))
		{
	?>
		<div id="divPersoMedicReport">

			<table cellspacing="0" style="margin:10px auto auto; background:#fff; width:100%;">
				<tr>
					<td style="text-align:left; width:25%;">
						<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
					</td>
			
					<td style="text-align:center; width:50%;">
						<b><h3 style="padding:10px; font-size:150%;">Laboratory analysis results #<?php echo $sn;?></h3></b>
					</td>
					
					<td style="text-align:left; width:25%;">
						
					</td>
				</tr>
			
			</table>
					
			<?php
		
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A8', 'N°')
						->setCellValue('B8', 'Date of results')
						->setCellValue('C8', 'Laboratory tests')
						->setCellValue('D8', 'Results')
						->setCellValue('E8', 'More Results')
						->setCellValue('F8', 'Min')
						->setCellValue('G8', 'Max')
						->setCellValue('H8', 'Lab Files')
						->setCellValue('I8', 'Date of consultation')
						->setCellValue('J8', 'Doctors')
						->setCellValue('K8', 'Done by');
			
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="width:100%;">
				
				<thead>
					
					<tr>
						<th style="width:3%; border-right: 1px solid #bbb; text-align:center;">N°</th>
						<th style="width:10%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Date Résultats';?></th>
						
						<th style="width:18%; border-right: 1px solid #bbb; text-align:center;"><?php echo getString(99);?></th>
						<th style="width:11%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Results';?></th>
						<th style="width:5%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Min';?></th>
						<th style="width:5%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Max';?></th>
						
						<th style="width:5%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Lab Files';?></th>
						
						<th style="width:11%; border-right: 1px solid #bbb; text-align:center;"><?php echo getString(97);?></th>
						<th style="width:13%; border-right: 1px solid #bbb; text-align:center;"><?php echo getString(19);?></th>
						<th style="width:13%; border-right: 1px solid #bbb; text-align:center;" colspan=2><?php echo 'Done by';?></th>
					</tr> 
							
				</thead> 


				<tbody>
				<?php
				// $date='0000-00-00';
				$compteur=1;
				
				$i=0;
			
				$resultatsSouscategoPrestaLabo=$connexion->prepare('SELECT *FROM prestations_'.$insurance.' p, med_labo ml WHERE ml.numero=:num AND p.id_prestation=ml.id_prestationExa '.$dailydateperso.' AND ml.examenfait=1 GROUP BY p.id_souscategopresta');		
				$resultatsSouscategoPrestaLabo->execute(array(
				'num'=>$num
				));
				
				$resultatsSouscategoPrestaLabo->setFetchMode(PDO::FETCH_OBJ);

				$comptPrestaLabo=$resultatsSouscategoPrestaLabo->rowCount();
				
				
				while($ligneSouscategoPrestaLabo=$resultatsSouscategoPrestaLabo->fetch())
				{
					$idassuLab=$ligneSouscategoPrestaLabo->id_assuLab;
									
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();
					
					for($la=1;$la<=$assuCount;$la++)
					{
						
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassuLab
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
						}
					}
					
					$idcatego=$ligneSouscategoPrestaLabo->id_categopresta;
					$idsouscatego=$ligneSouscategoPrestaLabo->id_souscategopresta;
					
					// echo $idsouscatego;
					
					$getNomSouscatego=$connexion->prepare('SELECT *FROM souscategopresta sc WHERE catego_id=:idcatego AND souscatego_id=:idsouscatego');		
					$getNomSouscatego->execute(array(
					'idcatego'=>$idcatego,
					'idsouscatego'=>$idsouscatego
					));
					// ECHO 'SELECT *FROM souscategopresta sc WHERE catego_id='.$idcatego.' AND souscatego_id='.$idsouscatego.'';
					
					$getNomSouscatego->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneNomSouscatego=$getNomSouscatego->fetch())
					{
						$nomSousCatego='Category: <span style="font-weight:bold;font-size:150%">'.$ligneNomSouscatego->nomsouscatego.'</span>';
					}
				?>
					
					<tr style="background:#bbb">
						<td colspan=11><?php echo $nomSousCatego;?>
						</td>
					</tr>
						
					<?php
						
					$resultatsPrestaLabo=$connexion->prepare('SELECT *FROM '.$presta_assuMedoc.' p, med_labo ml WHERE ml.numero=:num AND p.id_prestation=ml.id_prestationExa '.$dailydateperso.' AND ml.examenfait=1 AND p.id_souscategopresta='.$idsouscatego.' ORDER BY ml.dateresultats DESC');		
					$resultatsPrestaLabo->execute(array(
					'num'=>$num
					));
					
					$resultatsPrestaLabo->setFetchMode(PDO::FETCH_OBJ);

					$comptPrestaLabo=$resultatsPrestaLabo->rowCount();
					
					while($lignePrestaLabo=$resultatsPrestaLabo->fetch())
					{
						$idassuLab=$lignePrestaLabo->id_assuLab;
													
						$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
						$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
								
						$assuCount = $comptAssuConsu->rowCount();
						
						for($la=1;$la<=$assuCount;$la++)
						{
							
							$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
							$getAssuConsu->execute(array(
							'idassu'=>$idassuLab
							));
							
							$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

							if($ligneNomAssu=$getAssuConsu->fetch())
							{
								$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
							}
						}
					
						if($lignePrestaLabo->moreresultats!=0)
						{
					?>
						
						<tr style="text-align:center;">
						
							<td style="text-align:center;"><?php echo $compteur;?></td>		
							
							<td style="text-align:center;font-weight:600;">
							<?php
							if($lignePrestaLabo->dateresultats != '0000-00-00')
							{
								$dateresultats=date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
							}else{
								$dateresultats="";
							}
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
							
								<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuMedoc.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$lignePrestaLabo->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$examen=$lignePrestaLabo->namepresta;
									}else{								
										$examen=$lignePrestaLabo->nompresta;
									}
									
									if($lignePresta->mesure!='')
									{
										$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
									}else{
										$mesure='';
									}
								}else{
									$examen=$lignePrestaLabo->autreExamen;
									$mesure='';
								}
								echo $examen;
								?>
							
							</td>
							
							<td colspan=4>
							</td>						
										
							<td style="text-align:center;">
							<?php 
								$dateconsu=date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
							?>
							</td>

							<td style="text-align:center;font-weight:bold;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$lignePrestaLabo->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
								
							if($ligneMed=$resultatsMed->fetch())
							{
								$nommedecin=$ligneMed->full_name;
								echo $ligneMed->full_name;
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
								
							<td style="text-align:center;font-weight:normal;" colspan=2>
							<?php
							$idLabo=$lignePrestaLabo->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$ligneLaboId->full_name;
								echo $ligneLaboId->full_name;		
							}
							$resultLaboId->closeCursor();
							?>
							</td>
						</tr>
						
							<?php
							if($lignePrestaLabo->moreresultats==1)
							{		

								$moreExaResuLab=array();
					
								$resultMoremedmedoc=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medmedoc=:idmedLab ORDER BY mml.id_moremedmedoc');		
								$resultMoremedmedoc->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$lignePrestaLabo->id_medmedoc
								));
								
								$resultMoremedmedoc->setFetchMode(PDO::FETCH_OBJ);

								$comptMoremedmedoc=$resultMoremedmedoc->rowCount();
								
								while($ligneMoremedmedoc=$resultMoremedmedoc->fetch())
								{
									$idassuLab=$ligneMoremedmedoc->id_assuLab;
													
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($la=1;$la<=$assuCount;$la++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuLab
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
					
								?>									
								<tr>
									<td colspan=2></td>
									
									<td style="text-align:center;font-weight:normal;">
									<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuMedoc.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoremedmedoc->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$moreexamen=$lignePresta->namepresta;			
												$moreExaResuLab[]=$lignePresta->namepresta;		
											}else{
											
												$moreexamen=$lignePresta->nompresta;
												$moreExaResuLab[]=$lignePresta->nompresta;
											}
													
											if($lignePresta->mesure!='')
											{
												$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
											}else{
												$mesure='';
											}
										}else{
											$moreexamen=$ligneMoremedmedoc->autreExamen;
											$mesure='';				
										}
										echo $moreexamen;
									?>
									</td>
									
									<td style="text-align:center;font-weight:bold;font-style:italic;">
									<?php 
										$resultats=$ligneMoremedmedoc->autreresultats;
										echo $ligneMoremedmedoc->autreresultats.''.$mesure;
									?>
									</td>
									
									<td style="text-align:center;">
									<?php
								$minresultats="";

								$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
								$resultExa->execute(array(
								'prestaId'=>$ligneMoremedmedoc->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneMoremedmedoc->minresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->min_valeur;
										
										$minresultats=$ligneMin->min_valeur;
									}
								}else{
									echo $ligneMoremedmedoc->minresultats;
									$minresultats=$ligneMoremedmedoc->minresultats;
								}
									?>
									</td>
									
									<td style="text-align:center;">
									<?php
								$maxresultats="";

								$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
								$resultExa->execute(array(
								'prestaId'=>$ligneMoremedmedoc->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneMoremedmedoc->maxresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->max_valeur;
																	
										$maxresultats=$ligneMin->max_valeur;
									}
								}else{
									echo $ligneMoremedmedoc->maxresultats;
								}
									?>
									</td>
												
									<td style="text-align:center;">
									<?php 
									if($ligneMoremedmedoc->resultats!="")
									{
										$fichierjoint='Un fichier a été joint';
										echo 'Un fichier a été joint sur ces résultats';
									}else{
										$fichierjoint='';
									}
									?>
									</td>
							
									<td colspan=4></td>
									
								</tr>
								<?php
								}
							}	
							
							if($lignePrestaLabo->moreresultats==2)
							{
								
								$moreExaResuLab=array();
								
								$moreexamen='';
								$resultats='';
								$minresultats='';
								$maxresultats='';
								$fichierjoint='';
								
								$resultSpermomedmedoc=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medmedoc=:idmedLab ORDER BY sml.id_spermomedmedoc');		
								$resultSpermomedmedoc->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$lignePrestaLabo->id_medmedoc
								));
								
								$resultSpermomedmedoc->setFetchMode(PDO::FETCH_OBJ);

								$comptSpermomedmedoc=$resultSpermomedmedoc->rowCount();
								
								while($ligneSpermomedmedoc=$resultSpermomedmedoc->fetch())
								{
								?>									
								<tr style="background-color:rgba(0,0,0,0.03)">
									<td colspan=5 style='text-align:center'>EXAMEN MACROSCOPIQUES</td>
									<td style='border-left:1px solid #aaa;text-align:center' colspan=6>EXAMEN MICROSCOPIQUES</td>
								</tr>
								
								<tr>
									<td style='text-align:center'>Volume</td>
									<td style='text-align:center'>Densité</td>
									<td style='text-align:center'>Viscosité</td>
									<td style='text-align:center'>PH</td>
									<td style='text-align:center'>Aspect</td>
									
									<td style='border-left:1px solid #aaa;text-align:center'>Examen direct</td>
									<td style='text-align:center'>Mobilité après</td>
									<td style='text-align:center;font-weight:normal;text-align:center'>Numération</td>
									<td style='text-align:center'>V.N</td>
									<td style='text-align:center'>Spermocytogramme</td>
									<td style='text-align:center'>Autres</td>
								
								</tr>
								
								<tr>							
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->volume;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->densite;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->viscosite;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->ph;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->aspect;?>
									</td>
									
									<td style='border-left:1px solid #aaa;text-align:center'>
									<?php echo $ligneSpermomedmedoc->examdirect;?>
									</td>
									
									<td>							
										<table>
											<tr>
												<td>0h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>1h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>2h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>3h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>4h après emission</td>
											</tr>
											
											<tr>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->zeroheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->uneheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->deuxheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->troisheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->quatreheureafter;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td style='text-align:center;font-weight:normal;'>
									<?php echo $ligneSpermomedmedoc->numeration;?>
									</td>
									
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->vn;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td>Forme typique</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>Forme atypique</td>
											</tr>
											
											<tr>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->formtypik;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->formatypik;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->autre;?>
									</td>
									
								</tr>
								
								<tr style="background-color:rgba(0,0,0,0.03)">	
									<td colspan=11 style='text-align:center'>CONCLUSION</td>
									
								</tr>
								<tr>	
									<td colspan=11 style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->conclusion;?>
									</td>
									
								</tr>
								<?php
								}
							}
								
						}else{
						?>
						<tr>
							<td style="text-align:center;"><?php echo $compteur;?></td>			
							
							<td style="text-align:center;font-weight:600;">
							<?php
							if($lignePrestaLabo->dateresultats != '0000-00-00')
							{
								$dateresultats=date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
							}else{
								$dateresultats="";
							}
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
								<?php 
								
								if($lignePrestaLabo->autreExamen=="")
								{
									if($lignePrestaLabo->namepresta!='')
									{
										$examen=$lignePrestaLabo->namepresta;
										$moreexamen="";					
									}else{
									
										$examen=$lignePrestaLabo->nompresta;
										$moreexamen="";
									}
									
									if($lignePrestaLabo->mesure!='')
									{
										$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePrestaLabo->mesure.'</span>]';
									}else{
										$mesure='';
									}
								}else{
									$examen=$lignePrestaLabo->autreExamen;
									$moreexamen="";
									$mesure='';
								}
								echo $examen;
								?>
							</td>						
							
							<td style="text-align:center;font-weight:bold;font-style:italic;">
							<?php
								$resultats=$lignePrestaLabo->autreresultats;
								echo $lignePrestaLabo->autreresultats.''.$mesure;
							?>
							</td>
													
							<td style="text-align:center; ">
							<?php 
							$minresultats="";

							$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
							$resultExa->execute(array(
							'prestaId'=>$lignePrestaLabo->id_prestationExa
							));
							
							$resultExa->setFetchMode(PDO::FETCH_OBJ);
							$comptExa=$resultExa->rowCount();

							if($lignePrestaLabo->minresultats =="")
							{
								if($comptExa ==1)
								{
									$ligneMin=$resultExa->fetch();
									echo $ligneMin->min_valeur;
									
									$minresultats=$ligneMin->min_valeur;
								}
							}else{
								echo $lignePrestaLabo->minresultats;
								
								$minresultats=$lignePrestaLabo->minresultats;
							}
							
							?>
							</td>
							
							<td style="text-align:center; ">
							<?php 
							$maxresultats="";

							$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');		
							$resultExa->execute(array(
							'prestaId'=>$lignePrestaLabo->id_prestationExa
							));
							
							$resultExa->setFetchMode(PDO::FETCH_OBJ);
							$comptExa=$resultExa->rowCount();

							if($lignePrestaLabo->maxresultats =="")
							{
								if($comptExa ==1)
								{
									$ligneMin=$resultExa->fetch();
									echo $ligneMin->max_valeur;
									
									$maxresultats=$ligneMin->max_valeur;
								}
							}else{
								echo $lignePrestaLabo->maxresultats;
								
								$maxresultats=$lignePrestaLabo->maxresultats;
							}
							?>
							</td>
							
							<td style="text-align:center; ">
							<?php 
							if($lignePrestaLabo->resultats!="")
							{
								$fichierjoint='Un fichier a été joint';
								echo 'Un fichier a été joint sur ces résultats';
							}else{
								$fichierjoint='';
							}
							?>
							</td>
							
							<td style="text-align:center;font-weight:normal;">
							<?php
								$dateconsu=date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$lignePrestaLabo->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								$nommedecin=$ligneMed->full_name;
								echo $ligneMed->full_name;
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
							
							<td style="text-align:center;font-weight:normal;">
							<?php
							$idLabo=$lignePrestaLabo->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$ligneLaboId->full_name;
								echo $ligneLaboId->full_name;
								
							}
							
							?>
							</td>
						</tr>
						<?php
						}	
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateresultats;
							$arrayConsult[$i][2]=$examen;
							$arrayConsult[$i][3]=$moreexamen;
							$arrayConsult[$i][4]='aaa';
							$arrayConsult[$i][5]=$minresultats;
							$arrayConsult[$i][6]=$maxresultats;
							$arrayConsult[$i][7]=$fichierjoint;
							$arrayConsult[$i][8]=$dateconsu;
							$arrayConsult[$i][9]=$nommedecin;
							$arrayConsult[$i][10]=$fullnameLabo;
							
							$i++;
							
							$objPHPExcel->setActiveSheetIndex(0)
										->fromArray($arrayConsult,'','A10');
										
							$compteur++;
						
					}
				}
				
				
					$resultatsPrestaLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.numero=:num AND ml.id_prestationExa IS NULL AND ml.autreExamen!="" '.$dailydateperso.' AND ml.examenfait=1 ORDER BY ml.dateresultats ASC');		
					$resultatsPrestaLabo->execute(array(
					'num'=>$num
					));
					
					$resultatsPrestaLabo->setFetchMode(PDO::FETCH_OBJ);

					$comptPrestaLabo=$resultatsPrestaLabo->rowCount();
					
					if($comptPrestaLabo!=0)
					{
						$nomSousCatego='Unspecified category';
				
					?>
					
					<tr style="background:#bbb">
						<td colspan=11><span style="font-weight:bold;font-size:150%"><?php echo $nomSousCatego;?></span>
						</td>
					</tr>
					
					<?php
					}
					
					while($lignePrestaLabo=$resultatsPrestaLabo->fetch())
					{
						if($lignePrestaLabo->moreresultats!=0)
						{
				?>
						
						<tr style="text-align:center;">
						
							<td style="text-align:center;"><?php echo $compteur;?></td>		
							
							<td style="text-align:center;font-weight:600;">
							<?php
							if($lignePrestaLabo->dateresultats != '0000-00-00')
							{
								$dateresultats=date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
							}else{
								$dateresultats="";
							}
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
							
								<?php
								$examen=$lignePrestaLabo->autreExamen;									
								echo $lignePrestaLabo->autreExamen;
								?>
							
							</td>
							
							<td colspan=4>
							</td>						
										
							<td style="text-align:center;">
							<?php 
								$dateconsu=date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
							?>
							</td>

							<td style="text-align:center;font-weight:bold;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$lignePrestaLabo->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
								
							if($ligneMed=$resultatsMed->fetch())
							{
								$nommedecin=$ligneMed->full_name;
								echo $ligneMed->full_name;
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
								
							<td style="text-align:center;font-weight:normal;" colspan=2>
							<?php
							$idLabo=$lignePrestaLabo->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$ligneLaboId->full_name;
								echo $ligneLaboId->full_name;		
							}
							$resultLaboId->closeCursor();
							?>
							</td>
						</tr>
						
							<?php
							if($lignePrestaLabo->moreresultats==1)
							{							
								$resultMoremedmedoc=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medmedoc=:idmedLab ORDER BY mml.id_moremedmedoc');		
								$resultMoremedmedoc->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$lignePrestaLabo->id_medmedoc
								));
								
								$resultMoremedmedoc->setFetchMode(PDO::FETCH_OBJ);

								$comptMoremedmedoc=$resultMoremedmedoc->rowCount();
								
								while($ligneMoremedmedoc=$resultMoremedmedoc->fetch())
								{
									$idassuLab=$ligneMoremedmedoc->id_assuLab;
													
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
											
									$assuCount = $comptAssuConsu->rowCount();
									
									for($la=1;$la<=$assuCount;$la++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
										$getAssuConsu->execute(array(
										'idassu'=>$idassuLab
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
										}
									}
					
								?>									
								<tr>
									<td colspan=2></td>
									
									<td style="text-align:center;font-weight:normal;">
									<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuMedoc.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoremedmedoc->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$moreexamen=$lignePresta->namepresta;			
											}else{
											
												$moreexamen=$lignePresta->nompresta;
											}
													
											if($lignePresta->mesure!='')
											{
												$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
											}else{
												$mesure='';
											}
										}else{
											$moreexamen=$ligneMoremedmedoc->autreExamen;
											$mesure='';
										}
										echo $moreexamen;
									?>
									</td>
									
									<td style="text-align:center;font-weight:bold;font-style:italic;">
									<?php 
										$resultats=$ligneMoremedmedoc->autreresultats;
										echo $ligneMoremedmedoc->autreresultats.''.$mesure;
									?>
									</td>
									
									<td style="text-align:center;">
									<?php 
										$minresultats=$ligneMoremedmedoc->minresultats;
										echo $ligneMoremedmedoc->minresultats;
									?>
									</td>
									
									<td style="text-align:center;">
									<?php 
										$maxresultats=$ligneMoremedmedoc->maxresultats;
										echo $ligneMoremedmedoc->maxresultats;
									?>
									</td>
												
									<td style="text-align:center;">
									<?php 
									if($ligneMoremedmedoc->resultats!="")
									{
										$fichierjoint='Un fichier a été joint';
										echo 'Un fichier a été joint sur ces résultats';
									}else{
										$fichierjoint='';
									}
									?>
									</td>
							
									<td colspan=4></td>
									
								</tr>
								<?php
								}
							}
							
							if($lignePrestaLabo->moreresultats==2)
							{
								$moreexamen='';
								$resultats='';
								$minresultats='';
								$maxresultats='';
								$fichierjoint='';
								
								$resultSpermomedmedoc=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medmedoc=:idmedLab ORDER BY sml.id_spermomedmedoc');		
								$resultSpermomedmedoc->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$lignePrestaLabo->id_medmedoc
								));
								
								$resultSpermomedmedoc->setFetchMode(PDO::FETCH_OBJ);

								$comptSpermomedmedoc=$resultSpermomedmedoc->rowCount();
								
								while($ligneSpermomedmedoc=$resultSpermomedmedoc->fetch())
								{
								?>									
								<tr style="background:rgba(0,0,0,0.03)">
									<td colspan=5 style='text-align:center'>EXAMEN MACROSCOPIQUES</td>
									<td style='border-left:1px solid #aaa;text-align:center' colspan=6>EXAMEN MICROSCOPIQUES</td>
								</tr>
								
								<tr>
									<td style='text-align:center'>Volume</td>
									<td style='text-align:center'>Densité</td>
									<td style='text-align:center'>Viscosité</td>
									<td style='text-align:center'>PH</td>
									<td style='text-align:center'>Aspect</td>
									
									<td style='border-left:1px solid #aaa;text-align:center'>Examen direct</td>
									<td style='text-align:center'>Mobilité après</td>
									<td style='text-align:center;font-weight:normal;text-align:center'>Numération</td>
									<td style='text-align:center'>V.N</td>
									<td style='text-align:center'>Spermocytogramme</td>
									<td style='text-align:center'>Autres</td>
								
								</tr>
								
								<tr>							
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->volume;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->densite;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->viscosite;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->ph;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->aspect;?>
									</td>
									
									<td style='border-left:1px solid #aaa;text-align:center'>
									<?php echo $ligneSpermomedmedoc->examdirect;?>
									</td>
									
									<td>							
										<table>
											<tr>
												<td>0h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>1h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>2h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>3h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>4h après emission</td>
											</tr>
											
											<tr>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->zeroheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->uneheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->deuxheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->troisheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->quatreheureafter;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td style='text-align:center;font-weight:normal;'>
									<?php echo $ligneSpermomedmedoc->numeration;?>
									</td>
									
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->vn;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td>Forme typique</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>Forme atypique</td>
											</tr>
											
											<tr>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->formtypik;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermomedmedoc->formatypik;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->autre;?>
									</td>
									
								</tr>
								
								<tr style="background-color:#eee">	
									<td colspan=11 style='text-align:center'>CONCLUSION</td>
									
								</tr>
								<tr>	
									<td colspan=11 style='text-align:center'>
									<?php echo $ligneSpermomedmedoc->conclusion;?>
									</td>
									
								</tr>
								<?php
								}
							}
								
						}else{
						?>
						<tr>
							<td style="text-align:center;"><?php echo $compteur;?></td>			
							
							<td style="text-align:center;font-weight:600;">
							<?php
							if($lignePrestaLabo->dateresultats != '0000-00-00')
							{
								$dateresultats=date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateresultats));
							}else{
								$dateresultats="";
							}
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
								<?php 
								
								if($lignePrestaLabo->autreExamen=="")
								{
									if($lignePrestaLabo->namepresta!='')
									{
										$examen=$lignePrestaLabo->namepresta;
										$moreexamen="";
									}else{
									
										$examen=$lignePrestaLabo->nompresta;
										$moreexamen="";
									}
											
									if($lignePrestaLabo->mesure!='')
									{
										$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePrestaLabo->mesure.'</span>]';
									}else{
										$mesure='';
									}
								}else{
									$examen=$lignePrestaLabo->autreExamen;
									$moreexamen="";
									$mesure='';
								}
								echo $examen;
								?>
							</td>						
							
							<td style="text-align:center;font-weight:bold;font-style:italic;">
							<?php
								$resultats=$lignePrestaLabo->autreresultats;
								echo $lignePrestaLabo->autreresultats.''.$mesure;
							?>
							</td>
													
							<td style="text-align:center; ">
							<?php 
								$minresultats=$lignePrestaLabo->minresultats;
								echo $lignePrestaLabo->minresultats;
							?>
							</td>
							
							<td style="text-align:center; ">
							<?php
								$maxresultats=$lignePrestaLabo->maxresultats;
								echo $lignePrestaLabo->maxresultats;
							?>
							</td>
							
							<td style="text-align:center; ">
							<?php 
							if($lignePrestaLabo->resultats!="")
							{
								$fichierjoint='Un fichier a été joint';
								echo 'Un fichier a été joint sur ces résultats';
							}else{
								$fichierjoint='';
							}
							?>
							</td>
							
							<td style="text-align:center;">
							<?php
								$dateconsu=date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
								echo date('d-M-Y',strtotime($lignePrestaLabo->dateconsu));
							?>
							</td>
							
							<td style="text-align:center;font-weight:bold;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$lignePrestaLabo->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								$nommedecin=$ligneMed->full_name;
								echo $ligneMed->full_name;
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
							
							<td style="text-align:center;font-weight:normal;">
							<?php
							$idLabo=$lignePrestaLabo->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$ligneLaboId->full_name;
								echo $ligneLaboId->full_name;
								
							}
							
							?>
							</td>
						</tr>
						<?php
						}	
							$arrayConsult[$i][0]=$compteur;
							$arrayConsult[$i][1]=$dateresultats;
							$arrayConsult[$i][2]=$examen;
							$arrayConsult[$i][3]=$moreexamen;
							$arrayConsult[$i][4]=$resultats;
							$arrayConsult[$i][5]=$minresultats;
							$arrayConsult[$i][6]=$maxresultats;
							$arrayConsult[$i][7]=$fichierjoint;
							$arrayConsult[$i][8]=$dateconsu;
							$arrayConsult[$i][9]=$nommedecin;
							$arrayConsult[$i][10]=$fullnameLabo;
							
							$i++;
							
							$objPHPExcel->setActiveSheetIndex(0)
										->fromArray($arrayConsult,'','A10');
										
							$compteur++;
							
					}
				?>
				</tbody>
			</table>
			
		</div>
		<?php
		
		
			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn=str_replace('/', '_', $sn);
				
		
				if($_GET['paVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Daily/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Daily/");</script>';
					
					// createRN('GBD');
					
				}else{
					if($_GET['paVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Monthly/'.$reportsn.'.xlsx');
							
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Monthly/");</script>';
						
						// createRN('GBM');
						
					}else{
						if($_GET['paVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Annualy/'.$reportsn.'.xlsx');
							
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Annualy/");</script>';
							
							// createRN('GBA');
							
						}else{
							if($_GET['paVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Custom/");</script>';
								
								// createRN('GBC');
								
							}else{
								if($_GET['paVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Alltimes/");</script>';
									
									// createRN('GBG');
								}
							}
						}
					}
				}
	
			}
			
		}
	}
	?>
	
	<?php
	if(isset($_GET['medocreport']))
	{
		
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Medicaments Report #'.$sn.'')
					 ->setSubject("Medicaments Report information")
					 ->setDescription('Medicaments Gnl Report')
					 ->setKeywords("Medicaments Report Excel")
					 ->setCategory("Medicaments Report");

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
			
	
		if(isset($_GET['divPersoMedicReport']) OR isset($_POST['printMedicReportPerso']))
		{
			// var_dump($arrayMedocReport);
	?>
		<div id="divPersoMedicReport">

			<table cellspacing="0" style="margin:10px auto auto; background:#fff; width:100%;">
				<tr>
					<td style="text-align:left; width:22%;">
						<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
					</td>
			
					<td style="text-align:center; width:56%;">
						<b><h3 style="padding:10px; font-size:150%;">Medicaments Report #<?php echo $sn;?></h3></b>
					</td>
					
					<td style="text-align:left; width:22%;">
						
					</td>
				</tr>
			
			</table>
					
			<?php
		
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A8', 'N°')
						->setCellValue('B8', 'Full name')
						->setCellValue('C8', 'Date')
						->setCellValue('D8', 'Medicament')
						->setCellValue('E8', 'Quantité')
						->setCellValue('F8', 'Cashiers')
						->setCellValue('G8', 'Docteur')
						->setCellValue('H8', 'Infirmier');
			
			?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="width:100%;padding:0px;font-size:90%;">
				
				<thead>
					
					<tr>
						<th style="width:3%; border-right: 1px solid #bbb; text-align:center;">N°</th>
						<th style="width:8%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Full name';?></th>
						<th style="width:10%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Date';?></th>
						<th style="width:8%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Matériels';?></th>
						<th style="width:8%; border-right: 1px solid #bbb; text-align:center;"><?php echo 'Cashiers';?></th>
						<th style="width:8%; border-right: 1px solid #bbb; text-align:center;"><?php echo getString(19);?></th>
						<th style="width:8%; border-right: 1px solid #bbb; text-align:center;" colspan=2><?php echo 'Infirmier';?></th>
					</tr> 
							
				</thead> 


				<tbody>
				<?php
				// $date='0000-00-00';
				$compteur=1;
				$fullnamePaCheck="";
				$qteMedocPrestaTotal=0;
				
				$i=0;
			
				foreach($arrayMedocReport as $key => $medmedoc)
				{
					$idassuMedoc=$arrayMedocReport[$key]['id_assuMedoc'];
									
					$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
					
					$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
							
					$assuCount = $comptAssuConsu->rowCount();
							
					for($la=1;$la<=$assuCount;$la++)
					{
						
						$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
						$getAssuConsu->execute(array(
						'idassu'=>$idassuMedoc
						));
						
						$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

						if($ligneNomAssu=$getAssuConsu->fetch())
						{
							$presta_assuMedoc='prestations_'.$ligneNomAssu->nomassurance;
						}
					}					
				
					?>
					<tr>
						<td style="text-align:center;"><?php echo $compteur;?></td>			
						<td style="text-align:center;">
						<?php
							
							$fullnamePa = $arrayMedocReport[$key]['numero']['full_name'].' ('.$arrayMedocReport[$key]['numero']['numero'].')';
								
							if($fullnamePaCheck!=$fullnamePa)
							{
								echo $arrayMedocReport[$key]['numero']['full_name'].' ('.$arrayMedocReport[$key]['numero']['numero'].')';
							}
						?>
						</td>
						
						<td style="text-align:center;font-weight:600;">
						<?php
						if($arrayMedocReport[$key]['dateconsu'] != '0000-00-00')
						{
							$datemedoc=date('d-M-Y',strtotime($arrayMedocReport[$key]['dateconsu']));
							echo $datemedoc;
						}else{
							$datemedoc="";
						}
						?>
						</td>
						
						<td style="text-align:center;font-weight:bold;">
							<?php 
							
							if($arrayMedocReport[$key]['id_prestationMedoc']!=0)
							{
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuMedoc.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$arrayMedocReport[$key]['id_prestationMedoc']
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())
								{	
									if($lignePresta->namepresta!='')
									{
										$medocPresta=$lignePresta->namepresta;					
									}else{
									
										$medocPresta=$lignePresta->nompresta;
									}
								}
							}else{
								$medocPresta=$arrayMedocReport[$key]['autreMedoc'];
							}
							$qtemedocPresta=$arrayMedocReport[$key]['qteMedoc'];
							echo $medocPresta.'<span style="margin:5px; padding-left:5px; border-left:1px solid #aaa;">'.$qtemedocPresta.'</span>';
							?>
						</td>						
						
						<td style="text-align:center;">
						<?php
						if($arrayMedocReport[$key]['codecashier']!='')
						{
							$caissierName=$arrayMedocReport[$key]['codecashier']['full_name'];
						}else{
							$caissierName="";
						}
						
						echo $caissierName;
						?>
						</td>
						
						<td style="text-align:center;">
						<?php
						if($arrayMedocReport[$key]['id_uM']!=0)
						{
							$nommedecin=$arrayMedocReport[$key]['id_uM']['full_name'];
						}else{
							$nommedecin="";
						}
						
						echo $nommedecin;						
						?>						
						</td>
						
						<td style="text-align:center;font-weight:normal;">
						<?php
						if($arrayMedocReport[$key]['id_uInfMedoc']!=0)
						{
							$infName= $arrayMedocReport[$key]['id_uInfMedoc']['full_name'];
						}else{
							$infName="";
						}
						
						echo $infName;
						?>	
						</td>
					</tr>
					<?php
	$qteMedocPrestaTotal=$qteMedocPrestaTotal+$qtemedocPresta;
	
						$arrayConsult[$i][0]=$compteur;
						
						if($fullnamePaCheck!=$fullnamePa)
						{
							$arrayConsult[$i][1]=$fullnamePa;
						}else{
							$arrayConsult[$i][1]='';
						}
						
						$arrayConsult[$i][2]=$datemedoc;
						$arrayConsult[$i][3]=$medocPresta;
						$arrayConsult[$i][4]=$qtemedocPresta;
						$arrayConsult[$i][5]=$caissierName;
						$arrayConsult[$i][6]=$nommedecin;
						$arrayConsult[$i][7]=$infName;
						
						$fullnamePaCheck=$fullnamePa;			
						$i++;
						$compteur++;
					
				}
				
					$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','A10')
								->setCellValue('E'.(10+$i).'', ''.$qteMedocPrestaTotal.'');

						
				?>
					<tr style="text-align:center;">
						<td colspan=3 style="font-size: 13px; font-weight: bold;text-align:center;">TOTAL</td>
						<td style="font-size: 13px; font-weight: bold;text-align:center;">
							<?php						
								echo $qteMedocPrestaTotal;			
							?>
						</td>
						<td colspan=3></td>
					</tr>
				</tbody>
			</table>
			
		</div>
		<?php
							

			if(isset($_GET['createReportExcel']))
			{
				$callStartTime = microtime(true);

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				
				$reportsn=str_replace('/', '_', $sn);
				
		
				if($_GET['paVisit']=='dailyPersoMedic')
				{
					$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Daily/'.$reportsn.'.xlsx');
							
					$callEndTime = microtime(true);
					$callTime = $callEndTime - $callStartTime;
					
					echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Daily/");</script>';
					
					// createRN('GBD');
					
				}else{
					if($_GET['paVisit']=='monthlyPersoMedic')
					{
						$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Monthly/'.$reportsn.'.xlsx');
							
						$callEndTime = microtime(true);
						$callTime = $callEndTime - $callStartTime;
						
						echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Monthly/");</script>';
						
						// createRN('GBM');
						
					}else{
						if($_GET['paVisit']=='annualyPersoMedic')
						{
							$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Annualy/'.$reportsn.'.xlsx');
							
							$callEndTime = microtime(true);
							$callTime = $callEndTime - $callStartTime;
							
							echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Annualy/");</script>';
							
							// createRN('GBA');
							
						}else{
							if($_GET['paVisit']=='customPersoMedic')
							{
								$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Custom/'.$reportsn.'.xlsx');
							
								$callEndTime = microtime(true);
								$callTime = $callEndTime - $callStartTime;
								
								echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Custom/");</script>';
								
								// createRN('GBC');
								
							}else{
								if($_GET['paVisit']=='gnlPersoMedic')
								{
									$objWriter->save('C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Alltimes/'.$reportsn.'.xlsx');
							
									$callEndTime = microtime(true);
									$callTime = $callEndTime - $callStartTime;
									
									echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/MedicamentsReport/Alltimes/");</script>';
									
									// createRN('GBG');
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