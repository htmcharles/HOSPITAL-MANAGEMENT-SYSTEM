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

// echo $heure;
// echo showBN();


	$checkIdBill=$connexion->prepare('SELECT *FROM bills b WHERE b.numbill=:numbill ORDER BY b.id_bill LIMIT 1');

	$checkIdBill->execute(array(
	'numbill'=>showBN()
	));

	$comptidBill=$checkIdBill->rowCount();

	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		$idBilling = $ligne->id_bill;
		
		// echo $idBilling;
		
	}else{

		$createIdBill=$connexion->prepare('INSERT INTO bills (numbill) VALUES(:numbill)');

		$createIdBill->execute(array(
		'numbill'=>showBN()
		));
		
		$checkIdBilling=$connexion->prepare('SELECT *FROM bills b WHERE b.numbill=:numbill ORDER BY b.id_bill LIMIT 1');
		
		$checkIdBilling->execute(array(
		'numbill'=>showBN()
		));
		
		$checkIdBilling->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBilling->fetch();
		
		$idBilling = $ligne->id_bill;
		
	}

	
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Bill#'. showBN(); ?></title>

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
	if(isset($_GET['finishbtn']))
	{
	?>
		<body onload="window.print()">
	<?php
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$idCashier=$_SESSION['id'];

if($connected==true AND isset($_SESSION['codeCash']))
{
	
	// echo 'New '.$idBilling;
		
	$resultatsCashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.id_u=:operation');
	$resultatsCashier->execute(array(
	'operation'=>$idCashier	
	));

	$resultatsCashier->setFetchMode(PDO::FETCH_OBJ);
	if($ligneCashier=$resultatsCashier->fetch())
	{
		$doneby = $ligneCashier->nom_u.'  '.$ligneCashier->prenom_u;
		$codecashier = $ligneCashier->codecashier;
	}	

		$font = new BCGFontFile('barcode/font/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		// Barcode Part
		$code = new BCGcode93();
		$code->setScale(2);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setLabel('# '.showBN().' #');
		$code->parse(''.showBN().'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecashier.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #eee; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
<?php
$barcode = '<table style="width:100%">
		
		<tr>
			<td style="text-align:left; width:60%">
				 Done by : '.$doneby.'
			</td>
			
			<td style="text-align:right;">
				<img src="barcode/png/barcode'.$codecashier.'.png" style="height:auto;"/>	
			</td>
							
		</tr>
		
	</table>';

echo $barcode;
?>
			
<?php

		$numPa=$_GET['num'];
		$datefacture=$_GET['datefacture'];
				

		$getidMedConsult=$connexion->query('SELECT mc.id_medconsu FROM med_consult mc');
		
		$getidMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		while($ligneConsult=$getidMedConsult->fetch())
		{
			
			if(isset($_POST['prixautreConsu'.$ligneConsult->id_medconsu.'']))
			{
				// echo $_POST[''.$ligneConsult->id_medconsu.''];
				
				if($ligneConsult->id_medconsu == $_POST[''.$ligneConsult->id_medconsu.''])
				{
					$resultats=$connexion->prepare('UPDATE med_consult mc SET mc.prixautreConsu=:prixautreConsu WHERE mc.id_medconsu=:idmedconsu');
					
					$resultats->execute(array(
					'idmedconsu'=>$_POST[''.$ligneConsult->id_medconsu.''],
					'prixautreConsu'=>$_POST["prixautreConsu$ligneConsult->id_medconsu"]
					
					))or die( print_r($connexion->errorInfo()));	
				}
			}
		}
		
		
		
	
		$getidMedInf=$connexion->query('SELECT mi.id_medinf FROM med_inf mi');
		
		$getidMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat
		
		while($ligneInf=$getidMedInf->fetch())
		{
			if(isset($_POST['prixautrePrestaM'.$ligneInf->id_medinf.'']))
			{
				// echo $_POST[''.$ligneInf->id_medinf.''];
				
				if($ligneInf->id_medinf == $_POST[''.$ligneInf->id_medinf.''])
				{
					$resultats=$connexion->prepare('UPDATE med_inf mi SET mi.prixautrePrestaM=:prixautrePrestaM WHERE mi.id_medinf=:idmedinf AND mi.datesoins!="0000-00-00"');
					
					$resultats->execute(array(
					'idmedinf'=>$_POST[''.$ligneInf->id_medinf.''],
					'prixautrePrestaM'=>$_POST["prixautrePrestaM$ligneInf->id_medinf"]
					
					))or die( print_r($connexion->errorInfo()));	
				}
			}
		}
	
	
	
	
		$getidMedLabo=$connexion->query('SELECT ml.id_medlabo FROM med_labo ml');
		
		$getidMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat
		
		while($ligneLabo=$getidMedLabo->fetch())
		{
			if(isset($_POST['prixautreExamen'.$ligneLabo->id_medlabo.'']))
			{
				if($ligneLabo->id_medlabo == $_POST[''.$ligneLabo->id_medlabo.''])
				{
					$resultats=$connexion->prepare('UPDATE med_labo ml SET ml.prixautreExamen=:prixautreExamen WHERE ml.id_medlabo=:idmedlabo');
					
					$resultats->execute(array(
					'idmedlabo'=>$_POST[''.$ligneLabo->id_medlabo.''],
					'prixautreExamen'=>$_POST["prixautreExamen$ligneLabo->id_medlabo"]
					
					))or die( print_r($connexion->errorInfo()));	
				}
			}
		}

		
		
		$TotalGnl = 0;
		
		
			/*--------------Billing Info Patient-----------------*/
		
		$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultatsPatient->execute(array(
		'operation'=>$numPa	
		));
		
		$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignePatient=$resultatsPatient->fetch())
		{
			
			$bill= $lignePatient->bill;
			
			$resultAssu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu');		
			$resultAssu->execute(array(
			'idassu'=>$lignePatient->id_assurance
			));
			
			$resultAssu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptAssu=$resultAssu->rowCount();
			
			if($ligneAssu=$resultAssu->fetch())//on recupere la liste des éléments
			{
				$nomassurance = $ligneAssu->nomassurance;
			}else{
				$nomassurance = "";
			}
			
			$uappercent= 100 - $lignePatient->bill;
			
			$percentpartient= 100 - $uappercent;

			if($lignePatient->sexe=="M")
			{
				$sexe = "Male";
			}else{
				if($lignePatient->sexe=="F")
					$sexe = "Female";
			}
	
			$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
			$resultAdresse->execute(array(
			'idProv'=>$lignePatient->province,
			'idDist'=>$lignePatient->district,
			'idSect'=>$lignePatient->secteur
			));
					
			$resultAdresse->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptAdress=$resultAdresse->rowCount();
			
			if($ligneAdresse=$resultAdresse->fetch())
			{
				if($ligneAdresse->id_province == $lignePatient->province)
				{
					$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
					
				}
			}else{
				$adresse ='';
			}

		$userinfo = '<table style="width:100%; margin-top:20px;">
			
			<tr>
				<td style="text-align:left;">
					<span style="font-weight:bold">Full name: </span>
					'.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'<br/>
					<span style="font-weight:bold">Gender: </span>'.$sexe.'<br/>
					<span style="font-weight:bold">Adress: </span>'.$adresse.'
				</td>
				
				<td style="text-align:center;">
					<span style="font-weight:bold">Insurance type: </span>';
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$lignePatient->id_assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			if($ligneAssu=$resultAssurance->fetch())//on recupere la liste des éléments
			{
				if($ligneAssu->id_assurance == $lignePatient->id_assurance)
				{
					$insurance=$ligneAssu->nomassurance;
					
					$userinfo .= ''.$ligneAssu->nomassurance;
				}
			}

				$userinfo .='<br/>
					<span style="font-weight:bold">Patient share: </span>'.$percentpartient.' %<br/>
					<span style="font-weight:bold">Insurance share: </span>'.$uappercent.' %
				</td>
				
				<td style="text-align:right;">
					<span style="font-weight:bold">S/N: </span>'.$lignePatient->numero.'<br/>
					<span style="font-weight:bold">Date of birth: </span>'.$lignePatient->date_naissance.'<br/>
					<span style="font-weight:bold">Date of Consultation: </span>'.$datefacture.'
					
				</td>
								
			</tr>		
		</table>';

		echo $userinfo;
		
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.showBN().'')
					 ->setSubject("Billing information")
					 ->setDescription('Billing information for patient : '.$lignePatient->numero.', '.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'')
					 ->setKeywords("Bill Excel")
					 ->setCategory("Bill");

		for($col = ord('a'); $col <= ord('z'); $col++)
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
		}
		
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'S/N')
						->setCellValue('B1', ''.$lignePatient->numero.'')
						->setCellValue('A2', 'Full name')
						->setCellValue('B2', ''.$lignePatient->nom_u.'  '.$lignePatient->prenom_u.'')
						
						->setCellValue('A3', 'Adresse')
						->setCellValue('B3', ''.$lignePatient->province.', '.$lignePatient->district.', '.$lignePatient->secteur.'')
						
						->setCellValue('A4', 'Insurance')
						->setCellValue('B4', ''.$insurance.' '.$percentpartient.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.showBN().'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE p.numero=:num AND p.numero=c.numero AND c.dateconsu=:datefacture AND c.id_factureConsult IS NULL AND c.numero=:num AND c.dateconsu!="0000-00-00" ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'num'=>$numPa,
		'datefacture'=>$datefacture	
		));

		$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		
			
	
		/*-------Requête pour AFFICHER Med_consult-----------*/
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.dateconsu=:datefacture AND mc.numero=:num AND mc.id_factureMedConsu=0 ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'datefacture'=>$datefacture	
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_inf-----------*/
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.dateconsu=:datefacture AND mi.soinsfait=1 AND mi.numero=:num AND mi.id_factureMedInf=0 ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'datefacture'=>$datefacture	
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_labo-----------*/
	
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.dateconsu=:datefacture AND ml.examenfait=1 AND ml.numero=:num AND ml.id_factureMedLabo=0 ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'datefacture'=>$datefacture	
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedLabo=$resultMedLabo->rowCount();			
		
		$TotalMedLabo = 0;
		
	?>
	
	<table style="width:100%; margin:20px auto auto;"> 
		<tr> 
			<td style="text-align:left; width:33%;">
				<h4><?php echo $annee;?></h4>
			</td>
			
			<td style="text-align:center; width:33%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo showBN();?></h2>
			</td>
			
			<td style="text-align:right; width:33%;">
				<form method="post" action="printTempBilling.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&datefacture=<?php echo $_GET['datefacture'];?>&createPdf=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
				</form>
			</td>
		
			<td class="buttonBill">
				<a href="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&deletebill=<?php echo $idBilling;?>&datefacture=<?php echo $_GET['datefacture'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>&finishbtn=ok<?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141);?></button>
				</a>
			</td>
		</tr>
	</table>
	
	
	<?php
		try
		{
			$TotalGnlPrice=0;
			$TotalGnlPatientPrice=0;
			$TotalGnlInsurancePrice=0;
			$i=0;
			$x=0;
			$y=0;
			$z=0;
			
			if($comptConsult != 0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Type of consultation')
							->setCellValue('C8', 'Price')
							->setCellValue('D8', 'Patient Price')
							->setCellValue('E8', 'Insurance Price');
				

		$typeconsult = '<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;"> 
				<thead> 
					<tr>
						<th style="width:40%;">Type of Consultation</th>
						<th style="width:20%;">Price</th>
						<th style="width:20%;">Patient price</th>
						<th style="width:20%;">Insurance price</th>
					</tr> 
				</thead> 


				<tbody>';
				
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$billpercent=$ligneConsult->bill;
						
		$typeconsult .= '<tr style="text-align:center;">
						<td>';
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaConsult=$lignePresta->namepresta;
								
		$typeconsult .= $lignePresta->namepresta.'</td>';
							}else{	
							
								if($lignePresta->nompresta!='')
								{
									$nameprestaConsult=$lignePresta->nompresta;
		$typeconsult .= $lignePresta->nompresta.'</td>';
								}
							}
							
							$prixPresta = $lignePresta->prixpresta;
							
		$typeconsult .= '<td>'.$lignePresta->prixpresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
						}
						
						$TotalConsult=$TotalConsult + $lignePresta->prixpresta;
						
		$typeconsult .= '<td>';

							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
		$typeconsult .= $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td>';
						
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
		$typeconsult .= $lignePresta->prixpresta - $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>';
			
						$arrayConsult[$i][0]=$nameprestaConsult;
						$arrayConsult[$i][1]=$prixPresta;
						$arrayConsult[$i][2]=$patientPrice;
						$arrayConsult[$i][3]=$uapPrice;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','B9');
		
					}

		$typeconsult .= '<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">'.$TotalConsult;
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalConsult;
								
				
				
		$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">'.$TotalpatientPrice;
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;			

		
		
		$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">'.$TotaluapPrice;
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
				

				
		$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>';

					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(9+$i).'', ''.$TotalConsult.'')
								->setCellValue('D'.(9+$i).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(9+$i).'', ''.$TotaluapPrice.'');

			echo $typeconsult;
			
			}
			
			if($comptMedConsult != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(11+$i).'', 'Services')
							->setCellValue('C'.(11+$i).'', 'Price')
							->setCellValue('D'.(11+$i).'', 'Patient Price')
							->setCellValue('E'.(11+$i).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead> 
					<tr>
						<th style="width:40%">Services</th>
						<th style="width:20%">Price</th>
						<th style="width:20%">Patient price</th>
						<th style="width:20%">Insurance price</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
			
					while($ligneMedConsult=$resultMedConsult->fetch())
					{
						
						$billpercent=$ligneMedConsult->bill;
			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsult->id_prestationConsu
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedConsult=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedConsult=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							$prixPresta = $lignePresta->prixpresta;
							
							echo '<td>'.$lignePresta->prixpresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
						
						
						$TotalMedConsult=$TotalMedConsult + $lignePresta->prixpresta;
						?>
						<td>
						<?php 
							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						<?php 
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $lignePresta->prixpresta - $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu!=0)
						{
							$nameprestaMedConsult=$ligneMedConsult->autreConsu;
							echo $ligneMedConsult->autreConsu.'</td>';
							
							$prixPresta = $ligneMedConsult->prixautreConsu;
							echo '<td>'.$ligneMedConsult->prixautreConsu.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedConsult=$TotalMedConsult + $ligneMedConsult->prixautreConsu;
			?>
							<td>
							<?php 
								$patientPrice=($ligneMedConsult->prixautreConsu * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							<td>
							<?php 
								$uapPrice= $ligneMedConsult->prixautreConsu - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $ligneMedConsult->prixautreConsu - $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
			<?php
						}
							
						$arrayMedConsult[$x][0]=$nameprestaMedConsult;
						$arrayMedConsult[$x][1]=$prixPresta;
						$arrayMedConsult[$x][2]=$patientPrice;
						$arrayMedConsult[$x][3]=$uapPrice;
						
						$x++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedConsult,'','B'.(12+$i).'');
		
					}
			?>		
					</tr>	
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedConsult.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;							
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
																
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(12+$i+$x).'', ''.$TotalMedConsult.'')
								->setCellValue('D'.(12+$i+$x).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(12+$i+$x).'', ''.$TotaluapPrice.'');
			}
			
			if($comptMedInf != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(14+$i+$x).'', 'Nursing Care')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead> 
					<tr>
						<th style="width:40%">Nursing Care</th>
						<th style="width:20%">Price</th>						
						<th style="width:20%">Patient price</th>
						<th style="width:20%">Insurance price</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedInf=$resultMedInf->fetch())
					{
					
						$billpercent=$ligneMedInf->bill;
			?>
					<tr style="text-align:center;">
						<td>
						<?php 
							
						$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedInf->id_prestation
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedInf=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedInf=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $lignePresta->prixpresta;
							echo '<td>'.$lignePresta->prixpresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
						
						
						$TotalMedInf = $TotalMedInf + $lignePresta->prixpresta;
						?>
						</td>
						<td>
						<?php 
							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						<?php 
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $lignePresta->prixpresta - $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM!=0)
						{
							$nameprestaMedInf=$ligneMedInf->autrePrestaM;
							echo $ligneMedInf->autrePrestaM.'</td>';
							
							
							$prixPresta = $ligneMedInf->prixautrePrestaM;
							echo '<td>'.$ligneMedInf->prixautrePrestaM.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							
							$TotalMedInf = $TotalMedInf + $ligneMedInf->prixautrePrestaM;
			?>
							<td>
							<?php 
								$patientPrice=($ligneMedInf->prixautrePrestaM * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							<td>
							<?php 
								$uapPrice= $ligneMedInf->prixautrePrestaM - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $ligneMedInf->prixautrePrestaM - $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
			<?php
						}
						
							
						$arrayMedInf[$y][0]=$nameprestaMedInf;
						$arrayMedInf[$y][1]=$prixPresta;
						$arrayMedInf[$y][2]=$patientPrice;
						$arrayMedInf[$y][3]=$uapPrice;
						
						$y++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedInf,'','B'.(15+$i+$x).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedInf.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
												
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;						
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedInf.'')
								->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
			}
			
			if($comptMedLabo != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(17+$i+$x+$y).'', 'Labs')
							->setCellValue('C'.(17+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(17+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(17+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto;">
				<thead> 
					<tr>
						<th style="width:40%">Labs</th>
						<th style="width:20%">Price</th>						
						<th style="width:20%">Patient price</th>
						<th style="width:20%">Insurance price</th>
					</tr> 
				</thead> 

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			
			$TotaluapPrice=0;
			
					while($ligneMedLabo=$resultMedLabo->fetch())
					{
					
						$billpercent=$ligneMedLabo->bill;
			?>
					<tr style="text-align:center;">
						<td>
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
							
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedLabo->id_prestationExa
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedLabo=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedLabo=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $lignePresta->prixpresta;
							echo '<td>'.$lignePresta->prixpresta.' Rwf</td>';
						
							$TotalMedLabo = $TotalMedLabo + $lignePresta->prixpresta;
							?>
						</td>
						<td>
						<?php 
							$patientPrice=($lignePresta->prixpresta * $billpercent)/100;
							$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						<?php 
							$uapPrice= $lignePresta->prixpresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $lignePresta->prixpresta - $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
			<?php
						}
						
						if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen!=0)
						{
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
							echo $ligneMedLabo->autreExamen.'</td>';
							
							$prixPresta = $ligneMedLabo->prixautreExamen;
							echo '<td>'.$ligneMedLabo->prixautreExamen.' Rwf</td>';
							
							$TotalMedLabo=$TotalMedLabo + $ligneMedLabo->prixautreExamen;
			?>
							<td>
							<?php 
								$patientPrice=($ligneMedLabo->prixautreExamen * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							<td>
							<?php 
								$uapPrice= $ligneMedLabo->prixautreExamen - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $ligneMedLabo->prixautreExamen - $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
			<?php
						}
							
						$arrayMedLabo[$z][0]=$nameprestaMedLabo;
						$arrayMedLabo[$z][1]=$prixPresta;
						$arrayMedLabo[$z][2]=$patientPrice;
						$arrayMedLabo[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedLabo,'','B'.(18+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalMedLabo.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							<?php						
								echo $TotaluapPrice.'';

								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;																	
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					
				</tbody>
			</table>
			<?php
			
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedLabo.'')
								->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
								->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
			}
			?>
			<table class="printPreview" cellspacing="0" style="margin:10px auto auto; border-top:none">
				
				<tr>
					<td style="width:40%; text-align:right; font-size:15px; font-weight:bold">Total Final</td>
					<td style="width: 20%; font-size: 15px; font-weight: bold;">
						<?php echo $TotalGnlPrice.'';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>
					<td style="width: 20%; font-size: 15px; font-weight: bold;">
						<?php echo $TotalGnlPatientPrice.'';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>
					<td style="width: 20%; font-size: 15px; font-weight: bold;">
						<?php echo $TotalGnlInsurancePrice.'';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>
				</tr>
			</table>
		<?php
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C'.(20+$i+$x+$y+$z).'', ''.$TotalGnlPrice.'')
						->setCellValue('D'.(20+$i+$x+$y+$z).'', ''.$TotalGnlPatientPrice.'')
						->setCellValue('E'.(20+$i+$x+$y+$z).'', ''.$TotalGnlInsurancePrice.'');

		}

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}

		?>
	

	
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

	</div>

	
<?php
		if(isset($_GET['createPdf']))
		{
		
			
			/*----------Update Bills----------------*/
			
			$updateIdBill=$connexion->prepare('UPDATE bills b SET b.totaltypeconsuprice=:totaltypeconsu, b.totalmedconsuprice=:totalmedconsu, b.totalmedinfprice=:totalmedinf, b.totalmedlaboprice=:totalmedlabo, b.totalgnlprice=:totalgnl, b.dateconsu=:dateconsu, b.numero=:num, b.nomassurance=:nomassu, b.billpercent=:bill, b.codecashier=:codecash WHERE b.id_bill=:idbill');

			$updateIdBill->execute(array(
			'idbill'=>$idBilling,
			'totaltypeconsu'=>$TotalConsult,
			'totalmedconsu'=>$TotalMedConsult,
			'totalmedinf'=>$TotalMedInf,
			'totalmedlabo'=>$TotalMedLabo,
			'totalgnl'=>$TotalGnlPrice,
			'dateconsu'=>$_GET['datefacture'],
			'num'=>$_GET['num'],
			'nomassu'=>$nomassurance,
			'bill'=>$bill,
			'codecash'=>$_GET['cashier']
			
			))or die( print_r($connexion->errorInfo()));
			
			
			// echo $idBilling.'<br/>'.$TotalConsult.'<br/>'.$TotalMedConsult.'<br/>'.$TotalMedInf.'<br/>'.$TotalMedLabo.'<br/>'.$TotalGnlPrice.'<br/>'.$_GET['num'].'<br/>'.$_GET['cashier'].'<br/>';
			
			
			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$IdBill=str_replace('/', '_', showBN());
			
			$objWriter->save('C:/wamp/www/uap/BillFiles/Bill#'.$IdBill.'.xlsx');
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			// createBN();
			
			echo '<script text="text/javascript">document.location.href="printTempBilling.php?num='.$_GET['num'].'&cashier='.$_GET['cashier'].'&datefacture='.$_GET['datefacture'].'&idbill='.$idBilling.'&finishbtn=ok"</script>';
			
		
		}

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
	/* $file = file_get_contents("http://192.168.135.50/uap/printTempBilling.php?num=P9&cashier=CSC15A01&datefacture=2015-09-19");
	file_put_contents("toPDF.html", $file); */

}
?>
</body>

</html>