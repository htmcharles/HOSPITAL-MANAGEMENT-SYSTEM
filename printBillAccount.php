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
// echo $_GET['numbill'];


	$checkIdBill=$connexion->prepare('SELECT *FROM bills b WHERE b.numbill=:numbill ORDER BY b.id_bill LIMIT 1');
	
	$checkIdBill->execute(array(
	'numbill'=>$_GET['numbill']
	));

	$comptidBill=$checkIdBill->rowCount();

	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		$idBilling = $ligne->id_bill;
		
		$oldorgBill = $ligne->idorgBill;

        $numbill = $ligne->numbill;
        $bill=$ligne->billpercent;
        $nomassurancebill=$ligne->nomassurance;
        $idcardbill=$ligne->idcardbill;
        $numpolicebill=$ligne->numpolicebill;
        $adherentbill=$ligne->adherentbill;

        if($ligne->codecashier!="")
		{
			$idDoneby=$ligne->codecashier;
			
			$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u AND r.codereceptio=:operation');
			$resultatsDoneby->execute(array(
			'operation'=>$idDoneby
			));

			$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
			if($ligneDoneby=$resultatsDoneby->fetch())
			{
				$doneby = $ligneDoneby->full_name;
			}else{
				$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u AND c.codecashier=:operation');
				$resultatsDoneby->execute(array(
				'operation'=>$idDoneby
				));

				$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
				if($ligneDoneby=$resultatsDoneby->fetch())
				{
					$doneby = $ligneDoneby->full_name;
				}
			}
	
		}elseif($ligne->codecoordi!=""){
			
			$idDoneby=$ligne->codecoordi;
			
			$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u AND c.codecoordi=:operation');
			$resultatsDoneby->execute(array(
			'operation'=>$idDoneby
			));

			$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
			if($ligneDoneby=$resultatsDoneby->fetch())
			{
				$doneby = $ligneDoneby->full_name;
			}
	
		}
		
		$datebill = date('d-M-Y', strtotime($ligne->datebill));
		$status = $ligne->status;
		
	}
	
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Bill#'.$_GET['numbill']; ?></title>

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
	if(isset($_GET['finishbtn']))
	{
	?>
		<body onload="window.print()">
	<?php
	}
	?>
	
<?php
$connected=$_SESSION['connect'];
$cashier=$_GET['cashier'];

if($connected==true AND isset($_SESSION['codeAcc']))
{
	
	// echo 'New '.$idBilling;
		
	$resultatsCashier=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.codecashier=:operation');
	$resultatsCashier->execute(array(
	'operation'=>$cashier	
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
		$code->setLabel('# '.$_GET['numbill'].' #');
		$code->parse(''.$_GET['numbill'].'');
		
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
		$idbill=$_GET['idbill'];
		
		$getdateConsu=$connexion->prepare('SELECT *FROM bills b WHERE id_bill=:idbill');
		
		$getdateConsu->execute(array(
		'idbill'=>$_GET['idbill'],
		));
		
		$getdateConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		if($lignedateConsu=$getdateConsu->fetch())
		{
			$dateconsu=$lignedateConsu->dateconsu;
		}

		
		
		
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
					<span style="font-weight:bold">Date of Consultation: </span>'.$dateconsu.'
					
				</td>
								
			</tr>		
		</table>';

		echo $userinfo;
		
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.$_GET['numbill'].'')
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
						->setCellValue('G1', ''.$_GET['numbill'].'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE p.numero=:num AND p.numero=c.numero AND c.numero=:num AND c.id_factureConsult=:idbill AND c.dateconsu!="0000-00-00" ORDER BY c.id_consu');		
		$resultConsult->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill	
		));
		
		$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		
			
	
		/*-------Requête pour AFFICHER Med_consult-----------*/
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.dateconsu!="0000-00-00" AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu');		
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;


	
		/*-------Requête pour AFFICHER Med_surge-----------*/
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_factureMedSurge:=idbill ORDER BY ms.id_medsurge');
		$resultMedSurge->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();
	
		$TotalMedSurge = 0;


        /*-------Requête pour AFFICHER Med_kine-----------*/

        $resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_factureMedKine:=idbill ORDER BY mk.id_medkine');
        $resultMedKine->execute(array(
            'num'=>$numPa,
            'idbill'=>$idbill
        ));

        $resultMedKine->setFetchMode(PDO::FETCH_OBJ);

        $comptMedKine=$resultMedKine->rowCount();

        $TotalMedKine = 0;



        /*-------Requête pour AFFICHER Med_ortho-----------*/
	
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedOrtho:=idbill ORDER BY mo.id_medortho');
		$resultMedOrtho->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

		$comptMedOrtho=$resultMedOrtho->rowCount();
	
		$TotalMedOrtho = 0;
	
	

		/*-------Requête pour AFFICHER Med_psy-----------*/

		$resultMedPsy=$connexion->prepare('SELECT *FROM med_psy mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedPsy:=idbill ORDER BY mo.id_medpsy');
		$resultMedPsy->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));

		$resultMedPsy->setFetchMode(PDO::FETCH_OBJ);

		$comptMedPsy=$resultMedPsy->rowCount();

		$TotalMedPsy = 0;
	

	
	
	
		/*-------Requête pour AFFICHER Med_inf-----------*/
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.soinsfait=1 AND mi.numero=:num AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf');		
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_labo-----------*/
	
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.examenfait=1 AND ml.numero=:num AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo');		
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptMedLabo=$resultMedLabo->rowCount();			
		
		$TotalMedLabo = 0;


	
	
		/*-------Requête pour AFFICHER Med_radio-----------*/

		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_factureMedRadio:=idbill ORDER BY mr.id_medradio');
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_consom-----------*/
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_factureMedConsom:=idbill ORDER BY mco.id_medconsom');
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_medoc-----------*/
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_factureMedMedoc:=idbill ORDER BY mdo.id_medmedoc');
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idbill'=>$idbill
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
	
	?>
	
	<table style="width:100%; margin:20px auto auto;"> 
		<tr> 
			<td style="text-align:left; width:33%;">
				<h4><?php echo $_GET['datefacture'];?></h4>
			</td>
			
			<td style="text-align:center; width:33%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo $_GET['numbill'];?></h2>
			</td>
			
			<td style="text-align:right; width:33%;">
				<form method="post" action="printBillAccount.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&numbill=<?php echo $_GET['numbill'];?>&idbill=<?php echo $_GET['idbill'];?>&datefacture=<?php echo $_GET['datefacture'];?>&createPdf=ok&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
				</form>
			</td>
		
			<td class="buttonBill">
				<a href="billsaccount.php" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="billsaccount.php" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>">
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
			$TotalGnlPatientBalance=0;
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
						<th>Type of Consultation</th>
						<th style="width:10%;">Balance '.$nomassurancebill.'</th>
						<th style="width:10%;">Patient ('.$bill.'%)</th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>


				<tbody>';
				
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$billpercent=$ligneConsult->insupercent;
						
						$idassu=$ligneConsult->id_assuConsu;
		$typeconsult .= '<tr style="text-align:center;">
						<td style="font-weight:bold;font-size:15px;">';
						
						
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

						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');

						$resultPresta->execute(array(
						'prestaId'=>$ligneConsult->id_typeconsult
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($comptPresta!=0)
						{
							if($lignePresta=$resultPresta->fetch())
							{
								if(isset($_POST['pourcentage']))
								{
									$resultats=$connexion->prepare('UPDATE consultations SET insupercent=:percent WHERE id_consu=:idConsult');
						
									$resultats->execute(array(
									'percent'=>$_POST['pourcentage'],
									'idConsult'=>$_GET['idconsu']
									
									))or die( print_r($connexion->errorInfo()));
								}
								
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
								
								$prixPresta = $ligneConsult->prixtypeconsult;
								
			$typeconsult .= '
							
							<td style="font-weight:700">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							';
						
					$TotalConsult=$TotalConsult + $prixPresta;
					
			$typeconsult .= '<td style="font-weight:700">';

					$patientPrice=($prixPresta * $billpercent)/100;
					$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
					
			$typeconsult .= $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-weight:700">';

					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
			$typeconsult .= $patientBalance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-weight:700">';
						
					$uapPrice= $prixPresta - $patientPrice;
					$TotaluapPrice = $TotaluapPrice + $uapPrice;
					
			$typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>';
						
			/*
			if($ligneConsult->id_factureConsult!=NULL)
			{
				$typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
			}
				 */
			$typeconsult .= '</tr>';
							
							}
							
						}else{
						
							if(isset($_POST['newprixtypeconsult']))
							{
								if(isset($_POST['pourcentage']))
								{
									$resultats=$connexion->prepare('UPDATE consultations SET prixautretypeconsult=:prixautretypeconsu,insupercent=:percent WHERE id_consu=:idConsult');
						
									$resultats->execute(array(
									'prixautretypeconsu'=>$_POST['newprixtypeconsult'],
									'percent'=>$_POST['pourcentage'],
									'idConsult'=>$_GET['idconsu']
									
									))or die( print_r($connexion->errorInfo()));
								}
							}
							
							$resultNewPresta=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');
							
							$resultNewPresta->execute(array(
							'idconsu'=>$_GET['idconsu']
							));
							
							$resultNewPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptNewPresta=$resultNewPresta->rowCount();
							
							if($ligneNewPresta=$resultNewPresta->fetch())
							{
								$nameprestaConsult=$ligneNewPresta->autretypeconsult;
								
			$typeconsult .= $ligneNewPresta->autretypeconsult.'</td>';
			
								$prixPresta = $ligneNewPresta->prixautretypeconsult;
								
			$typeconsult .= '
							<td style="font-weight:700">'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';		
		 
				
			$TotalConsult=$TotalConsult + $prixPresta;
			
			$typeconsult .= '<td style="font-weight:700">';

				$patientPrice=($prixPresta * $billpercent)/100;
				$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
				
			$typeconsult .= $patientPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
			</td>
						
						<td style="font-weight:700">';

				$patientBalance = $patientPrice;
				$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
				
			$typeconsult .= $patientBalance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-weight:700">';
			
				$uapPrice= $prixPresta - $patientPrice;
				$TotaluapPrice= $TotaluapPrice + $uapPrice;
				
			$typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
			</td>';
			/*
			if($ligneConsult->id_factureConsult!=NULL)
			{
				$typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
			}
			 */
			$typeconsult .= '</tr>';

							}
						}
						
						$arrayConsult[$i][0]=$nameprestaConsult;
						$arrayConsult[$i][1]=$prixPresta;
						$arrayConsult[$i][2]=$patientPrice;
						$arrayConsult[$i][3]=$uapPrice;
						
						$i++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayConsult,'','B9');
		
					}

		/* $typeconsult .= '<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">'.$TotalConsult;
		 */
				$TotalGnlPrice=$TotalGnlPrice + $TotalConsult;
				
				
		/*
			$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">'.$TotalpatientPrice;
			 */
				$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;

				
				$TotalGnlPatientBalance=$TotalGnlPatientBalance + $TotalpatientBalance;

		
		/*
			$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">'.$TotaluapPrice;
			 */
				$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
				

		/*
			$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>';
		 */
		 
			$typeconsult .= '</tbody>
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
						<th>Services</th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			

			
					while($ligneMedConsult=$resultMedConsult->fetch())
					{
						
						
						$billpercent=$ligneMedConsult->insupercentServ;
						
						$idassu=$ligneMedConsult->id_assuServ;
						
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
						
			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsult->id_prestationConsu
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
						
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedConsult=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedConsult=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}

						$prixPresta = $ligneMedConsult->prixprestationConsu;
						
						echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
						
						
						$TotalMedConsult=$TotalMedConsult + $prixPresta;
						?>
						
						<td>
							<?php
								$patientPrice=($prixPresta * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
							<?php
								$patientBalance = $patientPrice;
								$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
		
						<td>
							<?php
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
						if($ligneMedConsult->id_prestationConsu==NULL AND $ligneMedConsult->autreConsu!="")
						{
							$nameprestaMedConsult=$ligneMedConsult->autreConsu;
							echo $ligneMedConsult->autreConsu.'</td>';
							
							$prixPresta = $ligneMedConsult->prixautreConsu;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedConsult=$TotalMedConsult + $prixPresta;
						?>
													
							<td>
							<?php
								$patientPrice=($prixPresta * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						
							<td>
								<?php
						$patientBalance = $patientPrice;
						$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
						
									echo $patientBalance.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
			
							<td>
							<?php
						$uapPrice= $prixPresta - $patientPrice;
						$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice.'';
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
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedConsult.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
			
			
			if($comptMedSurge != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(14+$i+$x).'', 'Surgery')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Surgery</th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneMedSurge=$resultMedSurge->fetch())
					{
					
						$billpercent=$ligneMedSurge->insupercentSurge;
						
						$idassu=$ligneMedSurge->id_assuSurge;
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

			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedSurge->id_prestationSurge
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedSurge=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedSurge=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedSurge->prixprestationSurge;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedSurge = $TotalMedSurge + $prixPresta;
						?>
						</td>
						
						<td>
							<?php
			$patientPrice=($ligneMedSurge->prixprestationSurge * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php
					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
			$uapPrice= $ligneMedSurge->prixprestationSurge - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						if(($ligneMedSurge->id_prestationSurge=="" OR $ligneMedSurge->id_prestationSurge==0) AND ($ligneMedSurge->prixautrePrestaS!=0))
						{
							$nameprestaMedSurge=$ligneMedSurge->autrePrestaS;
							echo $ligneMedSurge->autrePrestaS.'</td>';
							
							
							$prixPresta = $ligneMedSurge->prixautrePrestaS;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							
							$TotalMedSurge = $TotalMedSurge + $prixPresta;
			?>
						
						
						<td>
						<?php
			$patientPrice=($prixPresta * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php
					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
			$uapPrice= $prixPresta - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						
						$arrayMedSurge[$y][0]=$nameprestaMedSurge;
						$arrayMedSurge[$y][1]=$prixPresta;
						$arrayMedSurge[$y][2]=$patientPrice;
						$arrayMedSurge[$y][3]=$uapPrice;
						
						$y++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedSurge,'','B'.(15+$i+$x).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedSurge.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
							->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedSurge.'')
							->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
			
			}


            if($comptMedKine != 0)
            {

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.(14+$i+$x).'', 'Physiotherapy')
                    ->setCellValue('C'.(14+$i+$x).'', 'Price')
                    ->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
                    ->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');

                ?>

                <table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
                    <thead>
                    <tr>
                        <th><?php echo 'Physiotherapy';?></th>
                        <th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
                        <th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

                    while($ligneMedKine=$resultMedKine->fetch())
                    {

                    $billpercent=$ligneMedKine->insupercentKine;

                    $idassu=$ligneMedKine->id_assuKine;
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

                    ?>
                    <tr style="text-align:center;">
                        <td>
                            <?php

                            $resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
                            $resultPresta->execute(array(
                                'prestaId'=>$ligneMedKine->id_prestationKine
                            ));

                            $resultPresta->setFetchMode(PDO::FETCH_OBJ);

                            $comptPresta=$resultPresta->rowCount();

                            if($lignePresta=$resultPresta->fetch())
                            {
                            if($lignePresta->namepresta!='')
                            {
                                $nameprestaMedKine=$lignePresta->namepresta;
                                echo $lignePresta->namepresta.'</td>';

                            }else{

                                $nameprestaMedKine=$lignePresta->nompresta;
                                echo $lignePresta->nompresta.'</td>';
                            }

                            $prixPresta = $ligneMedKine->prixprestationKine;

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

                            $TotalMedKine = $TotalMedKine + $prixPresta;
                            ?>
                        </td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedKine->prixprestationKine * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                            echo $patientBalance.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $uapPrice= $ligneMedKine->prixprestationKine - $patientPrice;
                            $TotaluapPrice= $TotaluapPrice + $uapPrice;

                            echo $uapPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <?php
                        }

                        if(($ligneMedKine->id_prestationKine=="" OR $ligneMedKine->id_prestationKine==0) AND ($ligneMedKine->prixautrePrestaK!=0))
                        {
                            $nameprestaMedKine=$ligneMedKine->autrePrestaK;
                            echo $ligneMedKine->autrePrestaK.'</td>';


                            $prixPresta = $ligneMedKine->prixautrePrestaK;

                            echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


                            $TotalMedKine = $TotalMedKine + $prixPresta;
                            $TotalMedKineCCO = $TotalMedKineCCO + $prixPrestaCCO;
                            ?>


                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;

                                echo $patientBalance.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $uapPrice= $prixPresta - $patientPrice;
                                $TotaluapPrice= $TotaluapPrice + $uapPrice;

                                echo $uapPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
                            <?php
                        }


                        $arrayMedKine[$y][0]=$nameprestaMedKine;
                        $arrayMedKine[$y][1]=$prixPresta;
                        $arrayMedKine[$y][2]=$patientPrice;
                        $arrayMedKine[$y][3]=$uapPrice;

                        $y++;

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->fromArray($arrayMedKine,'','B'.(15+$i+$x).'');
                        }
                        ?>
                    <tr style="text-align:center;">
                        <td></td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalMedKine.'';

                            $TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientPrice.'';

                            $TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
                            <?php
                            echo $TotalpatientBalance.'';

                            $TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>
                        <td style="font-size: 110%; font-weight: bold;">
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
                    ->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedKine.'')
                    ->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
                    ->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');

            }


            if($comptMedOrtho != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(14+$i+$x).'', 'P&O')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th><?php echo 'P&O';?></th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneMedOrtho=$resultMedOrtho->fetch())
					{
					
						$billpercent=$ligneMedOrtho->insupercentOrtho;
						
						$idassu=$ligneMedOrtho->id_assuOrtho;
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

			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedOrtho->id_prestationOrtho
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedOrtho=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedOrtho=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedOrtho->prixprestationOrtho;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedOrtho = $TotalMedOrtho + $prixPresta;
						?>
						</td>
						
						<td>
							<?php
			$patientPrice=($ligneMedOrtho->prixprestationOrtho * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php
					$patientBalance =  $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
			$uapPrice= $ligneMedOrtho->prixprestationOrtho - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						if(($ligneMedOrtho->id_prestationOrtho=="" OR $ligneMedOrtho->id_prestationOrtho==0) AND ($ligneMedOrtho->prixautrePrestaO!=0 ))
						{
							$nameprestaMedOrtho=$ligneMedOrtho->autrePrestaO;
							echo $ligneMedOrtho->autrePrestaO.'</td>';
							
							
							$prixPresta = $ligneMedOrtho->prixautrePrestaO;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							
							$TotalMedOrtho = $TotalMedOrtho + $prixPresta;
			?>
						
						
						<td>
						<?php
			$patientPrice=($prixPresta * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php
					$patientBalance =  $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
			$uapPrice= $prixPresta - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						
						$arrayMedOrtho[$y][0]=$nameprestaMedOrtho;
						$arrayMedOrtho[$y][1]=$prixPresta;
						$arrayMedOrtho[$y][2]=$patientPrice;
						$arrayMedOrtho[$y][3]=$uapPrice;
						
						$y++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedOrtho,'','B'.(15+$i+$x).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedOrtho.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
							->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedOrtho.'')
							->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');
			
			}


			if($comptMedPsy != 0)
			{

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(14+$i+$x).'', 'Psychologie')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');

			?>

			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th><?php echo 'Psychologie';?></th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php

			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;

					while($ligneMedPsy=$resultMedPsy->fetch())
					{

						$billpercent=$ligneMedPsy->insupercentPsy;

						$idassu=$ligneMedPsy->id_assuPsy;
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

			?>
					<tr style="text-align:center;">
						<td>
						<?php

						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedPsy->id_prestation
						));

						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();

						if($lignePresta=$resultPresta->fetch())
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedPsy=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';

							}else{

								$nameprestaMedPsy=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}

							$prixPresta = $ligneMedPsy->prixprestation;

							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

							$TotalMedPsy = $TotalMedPsy + $prixPresta;
						?>
						</td>


						<td>
							<?php
			$patientPrice=($ligneMedPsy->prixprestation * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td>
							<?php
					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td>
						<?php
			$uapPrice= $ligneMedPsy->prixprestation - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;

							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}

						if(($ligneMedPsy->id_prestation=="" OR $ligneMedPsy->id_prestation==0) AND ($ligneMedPsy->prixautrePrestaM!=0))
						{
							$nameprestaMedPsy=$ligneMedPsy->autrePrestaM;
							echo $ligneMedPsy->autrePrestaM.'</td>';


							$prixPresta = $ligneMedPsy->prixautrePrestaM;

							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


							$TotalMedPsy = $TotalMedPsy + $prixPresta;
			?>


						<td>
						<?php
			$patientPrice=($prixPresta * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td>
							<?php
					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>

						<td>
						<?php
			$uapPrice= $prixPresta - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;

							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}


						$arrayMedPsycho[$y][0]=$nameprestaMedPsy;
						$arrayMedPsycho[$y][1]=$prixPresta;
						$arrayMedPsycho[$y][2]=$patientPrice;
						$arrayMedPsycho[$y][3]=$uapPrice;

						$y++;

						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedPsycho,'','B'.(15+$i+$x).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedPsy.'';

								$TotalGnlPrice=$TotalGnlPrice + $TotalMedPsy;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';

								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';

								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
							->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedPsy.'')
							->setCellValue('D'.(15+$i+$x+$y).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(15+$i+$x+$y).'', ''.$TotaluapPrice.'');

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
						<th>Nursing Care</th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneMedInf=$resultMedInf->fetch())
					{
					
						$billpercent=$ligneMedInf->insupercentInf;
						
						$idassu=$ligneMedInf->id_assuInf;
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
					
			?>
					<tr style="text-align:center;">
						<td>
						<?php
						
						$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
						$resultPresta->execute(array(
							'prestaId'=>$ligneMedInf->id_prestation
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedInf=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedInf=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedInf->prixprestation;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedInf = $TotalMedInf + $prixPresta;
						?>
						</td>
						
						<td>
							<?php
			$patientPrice=($ligneMedInf->prixprestation * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php
					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
			$uapPrice= $ligneMedInf->prixprestation - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
			<?php
						}
						
						if($ligneMedInf->id_prestation=="" AND $ligneMedInf->autrePrestaM!="")
						{
							$nameprestaMedInf=$ligneMedInf->autrePrestaM;
							echo $ligneMedInf->autrePrestaM.'</td>';
							
							
							$prixPresta = $ligneMedInf->prixautrePrestaM;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							
							$TotalMedInf = $TotalMedInf + $prixPresta;
			?>
						
						
						<td>
						<?php
			$patientPrice=($prixPresta * $billpercent)/100;
			$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
							echo $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php
					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
			$uapPrice= $prixPresta - $patientPrice;
			$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
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
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedInf.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Labs</th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneMedLabo=$resultMedLabo->fetch())
					{
					
						$billpercent=$ligneMedLabo->insupercentLab;
						
						$idassu=$ligneMedLabo->id_assuLab;
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
					
			?>
					<tr style="text-align:center;">
						<td>
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							
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
							
							$prixPresta = $ligneMedLabo->prixprestationExa;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedLabo = $TotalMedLabo + $prixPresta;
							?>
						</td>
						
						<td>
							<?php
								$patientPrice=($ligneMedLabo->prixprestationExa * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
							<?php
					$patientBalance =  $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
								echo $patientBalance.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
							$uapPrice= $ligneMedLabo->prixprestationExa - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $ligneMedLabo->prixprestationExa - $patientPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
			<?php
						}
						
						if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->autreExamen!="")
						{
							$nameprestaMedLabo=$ligneMedLabo->autreExamen;
							echo $ligneMedLabo->autreExamen.'</td>';
							
							$prixPresta = $ligneMedLabo->prixautreExamen;
							
							echo '<td>'.$ligneMedLabo->prixautreExamen.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
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
						$patientBalance = $patientPrice;
						$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
						
									echo $patientBalance.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
								$uapPrice= $ligneMedLabo->prixautreExamen - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice.'';
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
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedLabo.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
			
			
			if($comptMedRadio != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(21+$i+$x+$y).'', 'Radio')
							->setCellValue('C'.(21+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(21+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(21+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Radiology</th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotaltopupPrice=0;
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneMedRadio=$resultMedRadio->fetch())
					{
					
						$billpercent=$ligneMedRadio->insupercentRad;
						
						$idassu=$ligneMedRadio->id_assuRad;
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
					
			?>
					<tr style="text-align:center;">
						<td>
							<?php
							$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
							
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedRadio->id_prestationRadio
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

							$comptPresta=$resultPresta->rowCount();
							
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
							if($lignePresta->namepresta!='')
							{
								$nameprestaMedRadio=$lignePresta->namepresta;
								echo $lignePresta->namepresta.'</td>';
							
							}else{
							
								$nameprestaMedRadio=$lignePresta->nompresta;
								echo $lignePresta->nompresta.'</td>';
							}
							
							$prixPresta = $ligneMedRadio->prixprestationRadio;
							
							echo '<td>'.$prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedRadio = $TotalMedRadio + $prixPresta;
							?>
						</td>
						
						<td>
							<?php
								$patientPrice=($prixPresta * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					
						<td>
						<?php
					$patientBalance = $patientPrice;
					$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
					
							echo $patientBalance.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td>
						<?php
							$uapPrice= $prixPresta - $patientPrice;
							$TotaluapPrice= $TotaluapPrice + $uapPrice;
							
							echo $uapPrice.'';
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
			<?php
						}
						
						if($ligneMedRadio->id_prestationRadio=="" AND $ligneMedRadio->autreRadio!="")
						{
							$nameprestaMedRadio=$ligneMedRadio->autreRadio;
							echo $ligneMedRadio->autreRadio.'</td>';
							
							$prixPresta = $ligneMedRadio->prixautreRadio;
							
							echo '<td>'.$ligneMedRadio->prixautreRadio.'<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';
							
							$TotalMedRadio=$TotalMedRadio + $ligneMedRadio->prixautreRadio;
			?>
						
						
							<td>
							<?php
								$patientPrice=($ligneMedRadio->prixautreRadio * $billpercent)/100;
								
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								
								echo $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
					
							<td>
								<?php
						$patientBalance = $patientPrice;
						$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
						
									echo $patientBalance.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
								$uapPrice= $ligneMedRadio->prixautreRadio - $patientPrice;
								
								$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $ligneMedRadio->prixautreRadio - $patientPrice.'';
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
			<?php
						}
						
						$arrayMedRadio[$z][0]=$nameprestaMedRadio;
						$arrayMedRadio[$z][1]=$prixPresta;
						$arrayMedRadio[$z][2]=$patientPrice;
						$arrayMedRadio[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedRadio,'','B'.(18+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedRadio.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
							->setCellValue('C'.(18+$i+$x+$y+$z).'', ''.$TotalMedRadio.'')
							->setCellValue('D'.(18+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(18+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			
			if($comptMedConsom != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(20+$i+$x+$y).'', 'Consommables')
							->setCellValue('C'.(20+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(20+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(20+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th style="width:30%">Consommables</th>
						<th></th>
						<th style="width:10%">Qty</th>
						<th style="width:10%">P/U <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneMedConsom=$resultMedConsom->fetch())
					{
					
						$billpercent=$ligneMedConsom->insupercentConsom;
						
						$idassu=$ligneMedConsom->id_assuConsom;
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
					

						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
						
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedConsom->id_prestationConsom
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPresta=$resultPresta->rowCount();
						
					
						if($comptPresta==0)
						{
							$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
							$resultPresta->execute(array(
							'prestaId'=>$ligneMedConsom->id_prestationConsom
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
						}
						
						if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
						{
						?>
							<tr style="text-align:center;">

								<td>
							<?php
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedConsom=$lignePresta->namepresta;
										echo $lignePresta->namepresta;
									
									}else{
									
										$nameprestaMedConsom=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
								
									}
							?>
								
								</td>
								<td></td>
								<td>
								<?php
									
									$qteConsom=$ligneMedConsom->qteConsom;
									
									echo $qteConsom;
								?>
								</td>
								
								<td>
								<?php
									$prixPresta = $ligneMedConsom->prixprestationConsom;
									echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								?>
								</td>
								
								<td>
								<?php
									$balance=$prixPresta*$qteConsom;
									
									echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									
									$TotalMedConsom=$TotalMedConsom + $balance;
								?>
								</td>
								
								<td>
								<?php
						$patientPrice=($balance * $billpercent)/100;
						$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									echo $patientPrice;
									
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
					
								<td>
									<?php
							$patientBalance = $patientPrice;
							$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
							
										echo $patientBalance.'';
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<td>
								<?php
						$uapPrice= $balance - $patientPrice;
						$TotaluapPrice= $TotaluapPrice + $uapPrice;
									echo $uapPrice;
									
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
							</tr>
						<?php
						}
						
						if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->autreConsom!="")
						{
						?>
							<tr style="text-align:center;">
								<td>
								<?php
									$nameprestaMedConsom=$ligneMedConsom->autreConsom;
									echo $nameprestaMedConsom;
								?>
								</td>
							
								<td>
								<?php
									
									$qteConsom=$ligneMedConsom->qteConsom;
									
									echo $qteConsom;
								?>
								</td>
								
								<td>
								<?php
									$prixPresta = $ligneMedConsom->prixautreConsom;
									echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									
								?>
								</td>
								
								<td>
								<?php
									$balance=$prixPresta*$qteConsom;
									
									echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
									
									$TotalMedConsom=$TotalMedConsom + $balance;
								?>
								</td>
								
							
								<td>
								<?php
						$patientPrice=($balance * $billpercent)/100;
						$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									echo $patientPrice;
									
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								
								<td>
								<?php
						$uapPrice= $balance - $patientPrice;
						$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
									echo $uapPrice;
									
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
							</tr>
			<?php
						}
						
						$arrayMedConsom[$z][0]=$nameprestaMedConsom;
						$arrayMedConsom[$z][1]=$prixPresta;
						$arrayMedConsom[$z][2]=$patientPrice;
						$arrayMedConsom[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedConsom,'','B'.(21+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedConsom.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
							->setCellValue('C'.(21+$i+$x+$y+$z).'', ''.$TotalMedConsom.'')
							->setCellValue('D'.(21+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(21+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
			}
			
			if($comptMedMedoc != 0)
			{
			
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B'.(23+$i+$x+$y).'', 'Medocs')
							->setCellValue('C'.(23+$i+$x+$y).'', 'Price')
							->setCellValue('D'.(23+$i+$x+$y).'', 'Patient Price')
							->setCellValue('E'.(23+$i+$x+$y).'', 'Insurance Price');
				
			?>
			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Medicaments</th>
						<th></th>
						<th style="width:4%">Qty</th>
						<th style="width:8%">P/U <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
						<th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>

				<tbody>
			<?php
			
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			$TotaluapPrice=0;
			
					while($ligneMedMedoc=$resultMedMedoc->fetch())
					{
					
						$billpercent=$ligneMedMedoc->insupercentMedoc;
						
						$idassu=$ligneMedMedoc->id_assuMedoc;
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
					
					
					$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
					
					$resultPresta->execute(array(
					'prestaId'=>$ligneMedMedoc->id_prestationMedoc
					));
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPresta=$resultPresta->rowCount();
					
				
					if($comptPresta==0)
					{
						$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
						$resultPresta->execute(array(
						'prestaId'=>$ligneMedMedoc->id_prestationMedoc
						));
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);
					}
					
					if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
					{
					?>
						<tr style="text-align:center;">
							<td>
							<?php
								if($lignePresta->namepresta!='')
								{
									$nameprestaMedMedoc=$lignePresta->namepresta;
									echo $lignePresta->namepresta;
								
								}else{
								
									$nameprestaMedMedoc=$lignePresta->nompresta;
									echo $lignePresta->nompresta;
							
								}
							?>
							
							</td>
							<td></td>
							<td>
							<?php
								
								$qteMedoc=$ligneMedMedoc->qteMedoc;
								
								echo $qteMedoc;
							?>
							</td>

							<td>
							<?php
								$prixPresta = $ligneMedMedoc->prixprestationMedoc;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>
							</td>
						
							<td>
							<?php
						$patientPrice=($balance * $billpercent)/100;
						$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								echo $patientPrice;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
					
							<td>
								<?php
						$patientBalance = $patientPrice;
						$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
						
									echo $patientBalance.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						
							<td>
							<?php
						$uapPrice= $balance - $patientPrice;
						$TotaluapPrice= $TotaluapPrice + $uapPrice;
								echo $uapPrice;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
						</tr>
					
					<?php
					}
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->autreMedoc!="")
					{
					?>
						<tr style="text-align:center;">
							<td>
							<?php
								$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
								echo $nameprestaMedMedoc;
							?>
							</td>
						
							<td>
							<?php
								
								$qteMedoc=$ligneMedMedoc->qteMedoc;
								
								echo $qteMedoc;
							?>
							</td>
							
							<td>
							<?php
								$prixPresta = $ligneMedMedoc->prixautreMedoc;
								echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
							?>
							</td>
							
							<td>
							<?php
								$balance=$prixPresta*$qteMedoc;
								
								echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
								
								$TotalMedMedoc=$TotalMedMedoc + $balance;
							?>
							</td>
						
							<td>
							<?php
						$patientPrice=($balance * $billpercent)/100;
						$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
								echo $patientPrice;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
					
							<td>
								<?php
						$patientBalance = $patientPrice;
						$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
						
									echo $patientBalance.'';
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td>
							<?php
						$uapPrice= $balance - $patientPrice;
						$TotaluapPrice= $TotaluapPrice + $uapPrice;
								
								echo $uapPrice;
								
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
						</tr>
			<?php
					}
						
						$arrayMedMedoc[$z][0]=$nameprestaMedMedoc;
						$arrayMedMedoc[$z][1]=$prixPresta;
						$arrayMedMedoc[$z][2]=$patientPrice;
						$arrayMedMedoc[$z][3]=$uapPrice;
						
						$z++;
						
						$objPHPExcel->setActiveSheetIndex(0)
									->fromArray($arrayMedMedoc,'','B'.(21+$i+$x+$y).'');
					}
			?>
					<tr style="text-align:center;">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalMedMedoc.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">
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
							->setCellValue('C'.(24+$i+$x+$y+$z).'', ''.$TotalMedMedoc.'')
							->setCellValue('D'.(24+$i+$x+$y+$z).'', ''.$TotalpatientPrice.'')
							->setCellValue('E'.(24+$i+$x+$y+$z).'', ''.$TotaluapPrice.'');
		
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
		
			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$IdBill=str_replace('/', '_', $_GET['numbill']);
			
			$objWriter->save('C:/wamp/www/uap/BillFiles/Bill#'.$IdBill.'.xlsx');
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			echo '<script text="text/javascript">document.location.href="printBillAccount.php?num='.$_GET['num'].'&cashier='.$_GET['cashier'].'&numbill='.$_GET['numbill'].'&idbill='.$_GET['idbill'].'&datefacture='.$_GET['datefacture'].'&finishbtn=ok"</script>';
			
		
		}

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
}
?>
</body>

</html>