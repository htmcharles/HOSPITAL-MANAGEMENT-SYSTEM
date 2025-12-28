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

$annee = date('d') . '-' . date('M') . '-' . date('Y');


$heure = date('H') . ' : ' . date('i') . ' : ' . date('s');

// echo $heure;
// echo showBN();


$numPa = $_GET['num'];
//$consuId=$_GET['idconsu'];


$checkIdBill = $connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idbill ORDER BY b.id_bill LIMIT 1');

$checkIdBill->execute(array(
	'idbill' => $_GET['idbill']
));

$comptidBill = $checkIdBill->rowCount();

// echo $comptidBill;

if ($comptidBill != 0) {
	$checkIdBill->setFetchMode(PDO::FETCH_OBJ);

	$ligne = $checkIdBill->fetch();

	$idBilling = $ligne->id_bill;

	$idBilling = $ligne->id_bill;
	$oldorgBill = $ligne->idorgBill;

	$numbill = $ligne->numbill;
	$bill = $ligne->billpercent;
	$nomassurancebill = $ligne->nomassurance;
	$idcardbill = $ligne->idcardbill;
	$numpolicebill = $ligne->numpolicebill;
	$adherentbill = $ligne->adherentbill;

	if ($ligne->codecashier != "") {
		$idDoneby = $ligne->codecashier;

		$resultatsDoneby = $connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u AND r.codereceptio=:operation');
		$resultatsDoneby->execute(array(
			'operation' => $idDoneby
		));

		$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
		if ($ligneDoneby = $resultatsDoneby->fetch()) {
			$doneby = $ligneDoneby->full_name;
		} else {
			$resultatsDoneby = $connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u AND c.codecashier=:operation');
			$resultatsDoneby->execute(array(
				'operation' => $idDoneby
			));

			$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
			if ($ligneDoneby = $resultatsDoneby->fetch()) {
				$doneby = $ligneDoneby->full_name;
			}
		}
	} elseif ($ligne->codecoordi != "") {

		$idDoneby = $ligne->codecoordi;

		$resultatsDoneby = $connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u AND c.codecoordi=:operation');
		$resultatsDoneby->execute(array(
			'operation' => $idDoneby
		));

		$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
		if ($ligneDoneby = $resultatsDoneby->fetch()) {
			$doneby = $ligneDoneby->full_name;
		}
	}

	$datebill = date('d-M-Y', strtotime($ligne->datebill));
	$createBill = 0;

	// echo $idBilling;

}/* else{

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
		
		$numbill = showBN();
		$createBill = 1;
		
	} */

$getIdBill = $connexion->prepare('SELECT * FROM bills b WHERE b.id_bill=:idbill AND b.dette IS NOT NULL');
$getIdBill->execute(array(
	'idbill' => $_GET['idbill']
));

$getIdBill->setFetchMode(PDO::FETCH_OBJ);

$idBillCount = $getIdBill->rowCount();

if ($ligneIdBill = $getIdBill->fetch()) {
	$dettes = $ligneIdBill->dette;
} else {
	$dettes = NULL;
}

?>

<!doctype html>
<html lang="en">
<noscript>
	Cette page requiert du Javascript.
	Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<title><?php echo 'Bill#' . $numbill; ?></title>

	<link href="cssBourbonCoffee/css/style.css" rel="stylesheet" type="text/css"><!--Header-->

	<!--<meta HTTP-EQUIV="Refresh" CONTENT="30; URL=http://www.tonSite.com/page.html"> -->


	<!------------------------------------>

	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->

	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">

	<style type="text/css">
		@media print {

			.az {
				display: none;
			}

			.account-container {
				display: block;

			}

			.buttonBill {
				display: none;

			}
		}

		body {
			font-family: Century Gothic;
		}
	</style>

</head>



<body>


	<?php
	if (isset($_GET['finishbtn'])) {
	?>

		<body onload="window.print()">
		<?php
	}
		?>

		<?php
		$connected = $_SESSION['connect'];
		$idCashier = $_SESSION['id'];
		$cashier = $_GET['cashier'];

		if ($connected == true and isset($_SESSION['codeCash'])) {

			// echo 'New '.$idBilling;

			$resultatsCashier = $connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.codecashier=:operation');
			$resultatsCashier->execute(array(
				'operation' => $cashier
			));

			$resultatsCashier->setFetchMode(PDO::FETCH_OBJ);
			if ($ligneCashier = $resultatsCashier->fetch()) {
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
			$code->setLabel('# ' . $numbill . ' #');
			$code->parse('' . $numbill . '');

			// Drawing Part
			$drawing = new BCGDrawing('barcode/png/barcode' . $codecashier . '.png', $color_white);
			$drawing->setBarcode($code);
			$drawing->draw();

			$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

		?>
			<div>

				<table style="width:100%">



					<tr>
						<td>




							<table class="printPreview" style="margin-top:2px;">
								<thead>


									<?php

									$comptAssuUpdate = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuUpdate->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuUpdate->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuUpdate = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuUpdate->execute(array(
											'idassu' => @$idassu
										));

										$getAssuUpdate->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssuUpdate = $getAssuUpdate->fetch()) {
											$presta_assuUpdate = 'prestations_' . $ligneNomAssuUpdate->nomassurance;
										}
									}

									$resultConsult = $connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_factureConsult=:idbill AND p.numero=:num AND p.numero=c.numero AND c.numero=:num AND c.id_factureConsult IS NOT NULL ORDER BY c.id_consu');
									$resultConsult->execute(array(
										'idbill' => $_GET['idbill'],
										'num' => $numPa
									));

									$resultConsult->setFetchMode(PDO::FETCH_OBJ);

									$comptConsult = $resultConsult->rowCount();

									if ($comptConsult != 0) {
									?>
									<?php
									}

									$resultMedConsult = $connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_factureMedConsu=:idbill AND mc.dateconsu!="0000-00-00" AND mc.id_factureMedConsu!=0 ORDER BY mc.id_medconsu');
									$resultMedConsult->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

									$comptMedConsult = $resultMedConsult->rowCount();
									

									if ($comptMedConsult != 0) {
									?>
									<?php
									}

									$resultMedSurge = $connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_factureMedSurge=:idbill AND ms.id_factureMedSurge!=0 ORDER BY ms.id_medsurge');
									$resultMedSurge->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

									$comptMedSurge = $resultMedSurge->rowCount();

									if ($comptMedSurge != 0) {
									?>

									<?php
									}

									$resultMedKine = $connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_factureMedKine=:idbill AND mk.id_factureMedKine!=0 ORDER BY mk.id_medkine');
									$resultMedKine->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

									$comptMedKine = $resultMedKine->rowCount();

									if ($comptMedKine != 0) {
									?>

									<?php
									}

									$resultMedOrtho = $connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedOrtho=:idbill AND mo.id_factureMedOrtho!=0 ORDER BY mo.id_medortho');
									$resultMedOrtho->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

									$comptMedOrtho = $resultMedOrtho->rowCount();

									if ($comptMedOrtho != 0) {
									?>

									<?php
									}

									$resultMedPsy = $connexion->prepare('SELECT *FROM med_psy mp, patients p WHERE p.numero=:num AND p.numero=mp.numero AND mp.numero=:num AND mp.id_factureMedPsy=:idbill AND mp.id_factureMedPsy!=0 ORDER BY mp.id_medpsy');
									$resultMedPsy->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedPsy->setFetchMode(PDO::FETCH_OBJ);

									$comptMedPsy = $resultMedPsy->rowCount();

									if ($comptMedPsy != 0) {
									?>

									<?php
									}

									$resultMedInf = $connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_factureMedInf=:idbill AND mi.id_factureMedInf!=0 ORDER BY mi.id_medinf');
									$resultMedInf->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

									$comptMedInf = $resultMedInf->rowCount();

									if ($comptMedInf != 0) {
									?>

									<?php
									}

									$resultMedLabo = $connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_factureMedLabo=:idbill AND ml.id_factureMedLabo!=0 ORDER BY ml.id_medlabo');
									$resultMedLabo->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMedLabo = $resultMedLabo->rowCount();

									if ($comptMedLabo != 0) {
									?>

									<?php
									}

									$resultMedRadio = $connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_factureMedRadio=:idbill AND mr.id_factureMedRadio!=0 ORDER BY mr.id_medradio');
									$resultMedRadio->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

									$comptMedRadio = $resultMedRadio->rowCount();

									if ($comptMedRadio != 0) {
									?>

									<?php
									}

									$resultMedConsom = $connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_factureMedConsom=:idbill AND mco.id_factureMedConsom!=0 ORDER BY mco.id_medconsom');
									$resultMedConsom->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

									$comptMedConsom = $resultMedConsom->rowCount();

									if ($comptMedConsom != 0) {
									?>

									<?php
									}

									$resultMedMedoc = $connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_factureMedMedoc=:idbill AND mdo.id_factureMedMedoc!=0 ORDER BY mdo.id_medmedoc');
									$resultMedMedoc->execute(array(
										'num' => $numPa,
										'idbill' => $_GET['idbill']
									));

									$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

									$comptMedMedoc = $resultMedMedoc->rowCount();

									if ($comptMedMedoc != 0) {
									?>

									<?php
									}
									?>

								</thead>

								<tbody>

									<!--<tr>
						<td style="text-align:left;background:#eee;font-weight: bold;border-top:none;">
							Balance ra
						</td>
						
						<?php

						/*-------Requête pour AFFICHER Type consultation-------*/

						$TotalConsult = 0;
						$TotalConsultCCO = 0;

						$TotalGnlPrice = 0;
						$TotalGnlPriceCCO = 0;
						$TotalGnlPatientPrice = 0;
						$TotalGnlInsurancePrice = 0;


						if ($comptConsult != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TotalpatientPrice = 0;

							$TotaluapPrice = 0;

							while ($ligneConsult = $resultConsult->fetch()) {
								$consuId = $ligneConsult->id_consu;
								$billpercent = $ligneConsult->insupercent;

								$idassu = $ligneConsult->id_assuConsu;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}



								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

								$resultPresta->execute(array(
									'prestaId' => $ligneConsult->id_typeconsult
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta = $resultPresta->rowCount();

								if ($comptPresta != 0) {
									if ($lignePresta = $resultPresta->fetch()) {
										if ($lignePresta->namepresta != '') {
											$nameprestaConsult = $lignePresta->namepresta;
										} else {

											if ($lignePresta->nompresta != '') {
												$nameprestaConsult = $lignePresta->nompresta;
											}
										}

										$prixPresta = $ligneConsult->prixtypeconsult;
										$prixPrestaCCO = $ligneConsult->prixtypeconsultCCO;

										$TotalConsult = $TotalConsult + $prixPresta;
										$TotalConsultCCO = $TotalConsultCCO + $prixPrestaCCO;


										$patientPrice = ($prixPresta * $billpercent) / 100;
										$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

										$uapPrice = $prixPresta - $patientPrice;
										$TotaluapPrice = $TotaluapPrice + $uapPrice;
									}
								} else {

									$resultNewPresta = $connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');

									$resultNewPresta->execute(array(
										'idconsu' => $_GET['idconsu']
									));

									$resultNewPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptNewPresta = $resultNewPresta->rowCount();

									if ($ligneNewPresta = $resultNewPresta->fetch()) {
										$nameprestaConsult = $ligneNewPresta->autretypeconsult;

										$prixPresta = $ligneNewPresta->prixautretypeconsult;
										$prixPrestaCCO = $ligneNewPresta->prixautretypeconsultCCO;

										$TotalConsult = $TotalConsult + $prixPresta;
										$TotalConsultCCO = $TotalConsultCCO + $prixPrestaCCO;

										$patientPrice = ($prixPresta * $billpercent) / 100;
										$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

										$uapPrice = $prixPresta - $patientPrice;
										$TotaluapPrice = $TotaluapPrice + $uapPrice;
									}
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalConsult;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalConsultCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceConsult = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceConsult = $TotaluapPrice;

							echo $TotalConsultCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*------Requête pour AFFICHER Med_consult------*/


						$TotalMedConsult = 0;
						$TotalMedConsultCCO = 0;

						if ($comptMedConsult != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedConsult = $resultMedConsult->fetch()) {
								$consuId = $ligneMedConsult->id_consuMed;
								$billpercent = $ligneMedConsult->insupercentServ;
								$idassu = $ligneMedConsult->id_assuServ;

								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}


								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

								$resultPresta->execute(array(
									'prestaId' => $ligneMedConsult->id_prestationConsu
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta = $resultPresta->rowCount();

								if ($lignePresta = $resultPresta->fetch()) {

									if ($lignePresta->namepresta != '') {
										$nameprestaMedConsult = $lignePresta->namepresta;
									} else {

										$nameprestaMedConsult = $lignePresta->nompresta;
									}

									$prixPresta = $ligneMedConsult->prixprestationConsu;
									$prixPrestaCCO = $ligneMedConsult->prixprestationConsuCCO;

									$TotalMedConsult = $TotalMedConsult + $prixPresta;
									$TotalMedConsultCCO = $TotalMedConsultCCO + $prixPrestaCCO;

									$patientPrice = ($prixPresta * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedConsult->id_prestationConsu == NULL and ($ligneMedConsult->prixautreConsu != 0 or $ligneMedConsult->prixautreConsuCCO != 0)) {
									$nameprestaMedConsult = $ligneMedConsult->autreConsu;
									$prixPresta = $ligneMedConsult->prixautreConsu;
									$prixPrestaCCO = $ligneMedConsult->prixautreConsuCCO;

									$TotalMedConsult = $TotalMedConsult + $prixPresta;
									$TotalMedConsultCCO = $TotalMedConsultCCO + $prixPrestaCCO;

									$patientPrice = ($prixPresta * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;

									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedConsult;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedConsultCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceServ = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceServ = $TotaluapPrice;

							echo $TotalMedConsultCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_surge--------*/

						$TotalMedSurge = 0;
						$TotalMedSurgeCCO = 0;

						if ($comptMedSurge != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedSurge = $resultMedSurge->fetch()) {
								$consuId = $ligneMedSurge->id_consuSurge;
								$billpercent = $ligneMedSurge->insupercentSurge;

								$idassu = $ligneMedSurge->id_assuSurge;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}

								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
									'prestaId' => $ligneMedSurge->id_prestationSurge
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta = $resultPresta->rowCount();

								if ($lignePresta = $resultPresta->fetch()) {
									if ($lignePresta->namepresta != '') {
										$nameprestaMedSurge = $lignePresta->namepresta;
									} else {

										$nameprestaMedSurge = $lignePresta->nompresta;
									}

									$prixPresta = $ligneMedSurge->prixprestationSurge;
									$prixPrestaCCO = $ligneMedSurge->prixprestationSurgeCCO;

									$TotalMedSurge = $TotalMedSurge + $prixPresta;
									$TotalMedSurgeCCO = $TotalMedSurgeCCO + $prixPrestaCCO;

									$patientPrice = ($ligneMedSurge->prixprestationSurge * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $ligneMedSurge->prixprestationSurge - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedSurge->id_prestationSurge == NULL and ($ligneMedSurge->prixautrePrestaS != 0 or $ligneMedSurge->prixautrePrestaSCCO != 0)) {
									$nameprestaMedSurge = $ligneMedSurge->autrePrestaS;

									$prixPresta = $ligneMedSurge->prixautrePrestaS;
									$prixPrestaCCO = $ligneMedSurge->prixautrePrestaSCCO;

									$TotalMedSurge = $TotalMedSurge + $prixPresta;
									$TotalMedSurgeCCO = $TotalMedSurgeCCO + $prixPrestaCCO;

									$patientPrice = ($prixPresta * $billpercent) / 100;

									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;

									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedSurge;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedSurgeCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceSurge = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceSurge = $TotaluapPrice;

							echo $TotalMedSurgeCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_kine--------*/

						$TotalMedKine = 0;
						$TotalMedKineCCO = 0;

						if ($comptMedKine != 0) {
						?>
                            <td style="text-align:left; font-size: 110%; ">
                                <?php

								$TotalpatientPrice = 0;
								$TotaluapPrice = 0;

								while ($ligneMedKine = $resultMedKine->fetch()) {
									$consuId = $ligneMedKine->id_consuKine;
									$billpercent = $ligneMedKine->insupercentKine;

									$idassu = $ligneMedKine->id_assuKine;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

									$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
									$resultPresta->execute(array(
										'prestaId' => $ligneMedKine->id_prestationKine
									));

									$resultPresta->setFetchMode(PDO::FETCH_OBJ);

									$comptPresta = $resultPresta->rowCount();

									if ($lignePresta = $resultPresta->fetch()) {
										if ($lignePresta->namepresta != '') {
											$nameprestaMedKine = $lignePresta->namepresta;
										} else {

											$nameprestaMedKine = $lignePresta->nompresta;
										}

										$prixPresta = $ligneMedKine->prixprestationKine;
										$prixPrestaCCO = $ligneMedKine->prixprestationKineCCO;

										$TotalMedKine = $TotalMedKine + $prixPresta;
										$TotalMedKineCCO = $TotalMedKineCCO + $prixPrestaCCO;

										$patientPrice = ($ligneMedKine->prixprestationKine * $billpercent) / 100;
										$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

										$uapPrice = $ligneMedKine->prixprestationKine - $patientPrice;
										$TotaluapPrice = $TotaluapPrice + $uapPrice;
									}

									if ($ligneMedKine->id_prestationKine == NULL and ($ligneMedKine->prixautrePrestaK != 0 or $ligneMedKine->prixautrePrestaKCCO != 0)) {
										$nameprestaMedKine = $ligneMedKine->autrePrestaK;

										$prixPresta = $ligneMedKine->prixautrePrestaK;
										$prixPrestaCCO = $ligneMedKine->prixautrePrestaKCCO;

										$TotalMedKine = $TotalMedKine + $prixPresta;
										$TotalMedKineCCO = $TotalMedKineCCO + $prixPrestaCCO;

										$patientPrice = ($prixPresta * $billpercent) / 100;

										$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

										$uapPrice = $prixPresta - $patientPrice;

										$TotaluapPrice = $TotaluapPrice + $uapPrice;
									}
								}

								$TotalGnlPrice = $TotalGnlPrice + $TotalMedKine;
								$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedKineCCO;

								$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
								$TotalpatientPriceKine = $TotalpatientPrice;

								$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
								$TotaluapPriceKine = $TotaluapPrice;

								echo $TotalMedKineCCO;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
                            <?php
						}


						/*-------Requête pour AFFICHER Med_ortho--------*/

						$TotalMedOrtho = 0;
						$TotalMedOrthoCCO = 0;

						if ($comptMedOrtho != 0) {
							?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedOrtho = $resultMedOrtho->fetch()) {
								$consuId = $ligneMedOrtho->id_consuOrtho;
								$billpercent = $ligneMedOrtho->insupercentOrtho;

								$idassu = $ligneMedOrtho->id_assuOrtho;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}

								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
									'prestaId' => $ligneMedOrtho->id_prestationOrtho
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta = $resultPresta->rowCount();

								if ($lignePresta = $resultPresta->fetch()) {
									if ($lignePresta->namepresta != '') {
										$nameprestaMedOrtho = $lignePresta->namepresta;
									} else {

										$nameprestaMedOrtho = $lignePresta->nompresta;
									}

									$prixPresta = $ligneMedOrtho->prixprestationOrtho;
									$prixPrestaCCO = $ligneMedOrtho->prixprestationOrthoCCO;

									$TotalMedOrtho = $TotalMedOrtho + $prixPresta;
									$TotalMedOrthoCCO = $TotalMedOrthoCCO + $prixPrestaCCO;

									$patientPrice = ($ligneMedOrtho->prixprestationOrtho * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $ligneMedOrtho->prixprestationOrtho - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedOrtho->id_prestationOrtho == NULL and ($ligneMedOrtho->prixautrePrestaO != 0 or $ligneMedOrtho->prixautrePrestaOCCO != 0)) {
									$nameprestaMedOrtho = $ligneMedOrtho->autrePrestaO;

									$prixPresta = $ligneMedOrtho->prixautrePrestaO;
									$prixPrestaCCO = $ligneMedOrtho->prixautrePrestaOCCO;

									$TotalMedOrtho = $TotalMedOrtho + $prixPresta;
									$TotalMedOrthoCCO = $TotalMedOrthoCCO + $prixPrestaCCO;

									$patientPrice = ($prixPresta * $billpercent) / 100;

									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;

									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedOrtho;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedOrthoCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceOrtho = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceOrtho = $TotaluapPrice;

							echo $TotalMedOrthoCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_psy--------*/

						$TotalMedPsycho = 0;

						if ($comptMedPsy != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedPsy = $resultMedPsy->fetch()) {
								$consuId = $ligneMedPsy->id_consuPSy;
								$billpercent = $ligneMedPsy->insupercentPsy;

								$idassu = $ligneMedPsy->id_assuPsy;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}

								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
									'prestaId' => $ligneMedPsy->id_prestation
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta = $resultPresta->rowCount();

								if ($lignePresta = $resultPresta->fetch()) {
									if ($lignePresta->namepresta != '') {
										$nameprestaMedPsy = $lignePresta->namepresta;
									} else {

										$nameprestaMedPsy = $lignePresta->nompresta;
									}

									$prixPresta = $ligneMedPsy->prixprestation;

									$TotalMedPsy = $TotalMedPsy + $prixPresta;

									$patientPrice = ($ligneMedPsy->prixprestation * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $ligneMedPsy->prixprestation - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedPsy->id_prestation == NULL and ($ligneMedPsy->prixautrePrestaM != 0)) {
									$nameprestaMedPsy = $ligneMedPsy->autrePrestaM;

									$prixPresta = $ligneMedPsy->prixautrePrestaM;

									$TotalMedPsy = $TotalMedPsy + $prixPresta;

									$patientPrice = ($prixPresta * $billpercent) / 100;

									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;

									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedPsy;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPricePsy = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPricePsy = $TotaluapPrice;

							echo $TotalMedPsy;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------Requête pour AFFICHER Med_inf--------*/

						$TotalMedInf = 0;
						$TotalMedInfCCO = 0;

						if ($comptMedInf != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedInf = $resultMedInf->fetch()) {
								$consuId = $ligneMedInf->id_consuInf;
								$billpercent = $ligneMedInf->insupercentInf;

								$idassu = $ligneMedInf->id_assuInf;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}

								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
									'prestaId' => $ligneMedInf->id_prestation
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta = $resultPresta->rowCount();

								if ($lignePresta = $resultPresta->fetch()) {
									if ($lignePresta->namepresta != '') {
										$nameprestaMedInf = $lignePresta->namepresta;
									} else {

										$nameprestaMedInf = $lignePresta->nompresta;
									}

									$prixPresta = $ligneMedInf->prixprestation;
									$prixPrestaCCO = $ligneMedInf->prixprestationCCO;

									$TotalMedInf = $TotalMedInf + $prixPresta;
									$TotalMedInfCCO = $TotalMedInfCCO + $prixPrestaCCO;

									$patientPrice = ($ligneMedInf->prixprestation * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $ligneMedInf->prixprestation - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedInf->id_prestation == NULL and ($ligneMedInf->prixautrePrestaM != 0 or $ligneMedInf->prixautrePrestaMCCO != 0)) {
									$nameprestaMedInf = $ligneMedInf->autrePrestaM;

									$prixPresta = $ligneMedInf->prixautrePrestaM;
									$prixPrestaCCO = $ligneMedInf->prixautrePrestaMCCO;

									$TotalMedInf = $TotalMedInf + $prixPresta;
									$TotalMedInfCCO = $TotalMedInfCCO + $prixPrestaCCO;

									$patientPrice = ($prixPresta * $billpercent) / 100;

									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;

									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedInf;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedInfCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceInf = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceInf = $TotaluapPrice;

							echo $TotalMedInfCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_labo--------*/

						$TotalMedLabo = 0;
						$TotalMedLaboCCO = 0;

						if ($comptMedLabo != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedLabo = $resultMedLabo->fetch()) {
								$consuId = $ligneMedLabo->id_consuLabo;
								$billpercent = $ligneMedLabo->insupercentLab;

								$idassu = $ligneMedLabo->id_assuLab;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}

								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

								$resultPresta->execute(array(
									'prestaId' => $ligneMedLabo->id_prestationExa
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ); //on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta = $resultPresta->rowCount();

								if ($lignePresta = $resultPresta->fetch()) //on recupere la liste des éléments
								{
									if ($lignePresta->namepresta != '') {
										$nameprestaMedLabo = $lignePresta->namepresta;
									} else {
										$nameprestaMedLabo = $lignePresta->nompresta;
									}

									$prixPresta = $ligneMedLabo->prixprestationExa;
									$prixPrestaCCO = $ligneMedLabo->prixprestationExaCCO;

									$TotalMedLabo = $TotalMedLabo + $prixPresta;
									$TotalMedLaboCCO = $TotalMedLaboCCO + $prixPrestaCCO;

									$patientPrice = ($ligneMedLabo->prixprestationExa * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $ligneMedLabo->prixprestationExa - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedLabo->id_prestationExa == NULL and ($ligneMedLabo->prixautreExamen != 0 or $ligneMedLabo->prixautreExamenCCO != 0)) {
									$nameprestaMedLabo = $ligneMedLabo->autreExamen;
									$prixPresta = $ligneMedLabo->prixautreExamen;
									$prixPrestaCCO = $ligneMedLabo->prixautreExamenCCO;

									$TotalMedLabo = $TotalMedLabo + $prixPresta;
									$TotalMedLaboCCO = $TotalMedLaboCCO + $prixPrestaCCO;

									$patientPrice = ($prixPresta * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedLabo;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedLaboCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceLabo = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceLabo = $TotaluapPrice;

							echo $TotalMedLaboCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_radio------*/

						$TotalMedRadio = 0;
						$TotalMedRadioCCO = 0;

						if ($comptMedRadio != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedRadio = $resultMedRadio->fetch()) {
								$consuId = $ligneMedRadio->id_consuRadio;
								$billpercent = $ligneMedRadio->insupercentRad;

								$idassu = $ligneMedRadio->id_assuRad;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}

								$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

								$resultPresta->execute(array(
									'prestaId' => $ligneMedRadio->id_prestationRadio
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								$comptPresta = $resultPresta->rowCount();

								if ($lignePresta = $resultPresta->fetch()) {
									if ($lignePresta->namepresta != '') {
										$nameprestaMedRadio = $lignePresta->namepresta;
									} else {

										$nameprestaMedRadio = $lignePresta->nompresta;
									}

									$prixPresta = $ligneMedRadio->prixprestationRadio;
									$prixPrestaCCO = $ligneMedRadio->prixprestationRadioCCO;

									$TotalMedRadio = $TotalMedRadio + $prixPresta;
									$TotalMedRadioCCO = $TotalMedRadioCCO + $prixPrestaCCO;

									$patientPrice = ($prixPresta * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $prixPresta - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedRadio->id_prestationRadio == NULL and ($ligneMedRadio->prixautreRadio != 0 or $ligneMedRadio->prixautreRadioCCO != 0)) {
									$nameprestaMedRadio = $ligneMedRadio->autreRadio;
									$prixPresta = $ligneMedRadio->prixautreRadio;
									$prixPrestaCCO = $ligneMedRadio->prixautreRadioCCO;

									$TotalMedRadio = $TotalMedRadio + $ligneMedRadio->prixautreRadio;
									$TotalMedRadioCCO = $TotalMedRadioCCO + $ligneMedRadio->prixautreRadioCCO;

									$patientPrice = ($ligneMedRadio->prixautreRadio * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $ligneMedRadio->prixautreRadio - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedRadio;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedRadioCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceRadio = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceRadio = $TotaluapPrice;

							echo $TotalMedRadioCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_consom-------*/

						$TotalMedConsom = 0;
						$TotalMedConsomCCO = 0;


						if ($comptMedConsom != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedConsom = $resultMedConsom->fetch()) {
								$consuId = $ligneMedConsom->id_consuConsom;
								$billpercent = $ligneMedConsom->insupercentConsom;
								$idassu = $ligneMedConsom->id_assuConsom;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}

								$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, ' . $presta_assu . ' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');

								$resultPresta->execute(array(
									'prestaId' => $ligneMedConsom->id_prestationConsom
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta = $resultPresta->rowCount();


								if ($comptPresta == 0) {
									$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
									$resultPresta->execute(array(
										'prestaId' => $ligneMedConsom->id_prestationConsom
									));

									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								}

								if ($lignePresta = $resultPresta->fetch()) {

									if ($lignePresta->namepresta != '') {
										$nameprestaMedConsom = $lignePresta->namepresta;
									} else {

										$nameprestaMedConsom = $lignePresta->nompresta;
									}

									$qteConsom = $ligneMedConsom->qteConsom;
									$prixPresta = $ligneMedConsom->prixprestationConsom;
									$prixPrestaCCO = $ligneMedConsom->prixprestationConsomCCO;

									$balance = $prixPresta * $qteConsom;
									$balanceCCO = $prixPrestaCCO * $qteConsom;

									$TotalMedConsom = $TotalMedConsom + $balance;
									$TotalMedConsomCCO = $TotalMedConsomCCO + $balanceCCO;

									$patientPrice = ($balance * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $balance - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedConsom->id_prestationConsom == 0 and ($ligneMedConsom->prixautreConsom != 0 or $ligneMedConsom->prixautreConsomCCO != 0)) {

									$nameprestaMedConsom = $ligneMedConsom->autreConsom;
									$qteConsom = $ligneMedConsom->qteConsom;
									$prixPresta = $ligneMedConsom->prixautreConsom;
									$prixPrestaCCO = $ligneMedConsom->prixautreConsomCCO;
									$balance = $prixPresta * $qteConsom;
									$balanceCCO = $prixPrestaCCO * $qteConsom;

									$TotalMedConsom = $TotalMedConsom + $balance;
									$TotalMedConsomCCO = $TotalMedConsomCCO + $balanceCCO;

									$patientPrice = ($balance * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $balance - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedConsom;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedConsomCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceConsom = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceConsom = $TotaluapPrice;

							echo $TotalMedConsomCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_medoc-------*/

						$TotalMedMedoc = 0;
						$TotalMedMedocCCO = 0;

						if ($comptMedMedoc != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice = 0;
							$TotaluapPrice = 0;

							while ($ligneMedMedoc = $resultMedMedoc->fetch()) {
								$consuId = $ligneMedMedoc->id_consuMedoc;
								$billpercent = $ligneMedMedoc->insupercentMedoc;

								$idassu = $ligneMedMedoc->id_assuMedoc;
								$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								$assuCount = $comptAssuConsu->rowCount();

								for ($i = 1; $i <= $assuCount; $i++) {

									$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
									$getAssuConsu->execute(array(
										'idassu' => $idassu
									));

									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($ligneNomAssu = $getAssuConsu->fetch()) {
										$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
									}
								}


								$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, ' . $presta_assu . ' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');

								$resultPresta->execute(array(
									'prestaId' => $ligneMedMedoc->id_prestationMedoc
								));

								$resultPresta->setFetchMode(PDO::FETCH_OBJ); //on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta = $resultPresta->rowCount();


								if ($comptPresta == 0) {
									$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
									$resultPresta->execute(array(
										'prestaId' => $ligneMedMedoc->id_prestationMedoc
									));

									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								}

								if ($lignePresta = $resultPresta->fetch()) {
									if ($lignePresta->namepresta != '') {
										$nameprestaMedMedoc = $lignePresta->namepresta;
									} else {
										$nameprestaMedMedoc = $lignePresta->nompresta;
									}

									$qteMedoc = $ligneMedMedoc->qteMedoc;
									$prixPresta = $ligneMedMedoc->prixprestationMedoc;
									$prixPrestaCCO = $ligneMedMedoc->prixprestationMedocCCO;
									$balance = $prixPresta * $qteMedoc;
									$balanceCCO = $prixPrestaCCO * $qteMedoc;

									$TotalMedMedoc = $TotalMedMedoc + $balance;
									$TotalMedMedocCCO = $TotalMedMedocCCO + $balanceCCO;

									$patientPrice = ($balance * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $balance - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}

								if ($ligneMedMedoc->id_prestationMedoc == 0 and ($ligneMedMedoc->prixautreMedoc != 0 or $ligneMedMedoc->prixautreMedocCCO != 0)) {

									$nameprestaMedMedoc = $ligneMedMedoc->autreMedoc;
									$qteMedoc = $ligneMedMedoc->qteMedoc;
									$prixPresta = $ligneMedMedoc->prixautreMedoc;
									$prixPrestaCCO = $ligneMedMedoc->prixautreMedocCCO;
									$balance = $prixPresta * $qteMedoc;
									$balanceCCO = $prixPresta * $qteMedoc;

									$TotalMedMedoc = $TotalMedMedoc + $balance;
									$TotalMedMedocCCO = $TotalMedMedocCCO + $balanceCCO;


									$patientPrice = ($balance * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$uapPrice = $balance - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;
								}
							}

							$TotalGnlPrice = $TotalGnlPrice + $TotalMedMedoc;
							$TotalGnlPriceCCO = $TotalGnlPriceCCO + $TotalMedMedocCCO;

							$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
							$TotalpatientPriceMedoc = $TotalpatientPrice;

							$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
							$TotaluapPriceMedoc = $TotaluapPrice;

							echo $TotalMedMedocCCO;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						?>
						
						<td style="text-align:left; font-size: 110%; font-weight: bold;">
							<?php
							echo $TotalGnlPriceCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>-->



									<!--<tr>
						<td style="text-align:left;background:#eee;font-weight: bold;border-top:none;">
							Top Up
						</td>
						
						<?php

						/*-------AFFICHER Type consultation-------*/

						if ($comptConsult != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupConsult = $TotalConsultCCO - $TotalConsult;
							echo $TopupConsult;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*------AFFICHER Med_consult------*/

						if ($comptMedConsult != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedConsult = $TotalMedConsultCCO - $TotalMedConsult;
							echo $TopupMedConsult;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------AFFICHER Med_surge--------*/

						if ($comptMedSurge != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedSurge = $TotalMedSurgeCCO - $TotalMedSurge;
							echo $TopupMedSurge;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------AFFICHER Med_kine--------*/

						if ($comptMedKine != 0) {
						?>
                            <td style="text-align:left; font-size: 110%; ">
                                <?php
								$TopupMedKine = $TotalMedKineCCO - $TotalMedKine;
								echo $TopupMedKine;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>
                            <?php
						}

						/*-------AFFICHER Med_ortho--------*/

						if ($comptMedOrtho != 0) {
							?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedOrtho = $TotalMedOrthoCCO - $TotalMedOrtho;
							echo $TopupMedOrtho;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------AFFICHER Med_psy--------*/

						/*if($comptMedPsy != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedPsy = $TotalMedPsyCCO - $TotalMedPsy;
							echo $TopupMedPsy;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/

						/*-------AFFICHER Med_inf--------*/

						if ($comptMedInf != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedInf = $TotalMedInfCCO - $TotalMedInf;
							echo $TopupMedInf;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------AFFICHER Med_labo--------*/

						if ($comptMedLabo != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedLabo = $TotalMedLaboCCO - $TotalMedLabo;
							echo $TopupMedLabo;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------AFFICHER Med_radio------*/

						if ($comptMedRadio != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedRadio = $TotalMedRadioCCO - $TotalMedRadio;
							echo $TopupMedRadio;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------AFFICHER Med_consom-------*/

						if ($comptMedConsom != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedConsom = $TotalMedConsomCCO - $TotalMedConsom;
							echo $TopupMedConsom;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}

						/*-------AFFICHER Med_medoc-------*/

						if ($comptMedMedoc != 0) {
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedMedoc = $TotalMedMedocCCO - $TotalMedMedoc;
							echo $TopupMedMedoc;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						?>
						
						<td style="text-align:left; font-size: 110%; font-weight: bold;">
						<?php
						$TopupGnlPrice = $TotalGnlPriceCCO - $TotalGnlPrice;
						echo $TopupGnlPrice;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>-->

									<tr>
										<?php
										if ($comptConsult != 0) {
										?>
										<?php
										}
										if ($comptMedConsult != 0) {
										?>
										<?php
										}
										if ($comptMedSurge != 0) {
										?>
										<?php
										}
										if ($comptMedKine != 0) {
										?>
										<?php
										}
										if ($comptMedOrtho != 0) {
										?>
										<?php
										}
										if ($comptMedPsy != 0) {
										?>

										<?php
										}
										if ($comptMedInf != 0) {
										?>

										<?php
										}
										if ($comptMedLabo != 0) {
										?>

										<?php
										}
										if ($comptMedRadio != 0) {
										?>

										<?php
										}
										if ($comptMedConsom != 0) {
										?>

										<?php
										}
										if ($comptMedMedoc != 0) {
										?>

										<?php
										}
										?>

									</tr>



									<tr>

										<?php
										if ($comptConsult != 0) {
										?>

										<?php
										}
										if ($comptMedConsult != 0) {
										?>

										<?php
										}
										if ($comptMedSurge != 0) {
										?>

										<?php
										}
										if ($comptMedKine != 0) {
										?>

										<?php
										}
										if ($comptMedOrtho != 0) {
										?>

										<?php
										}
										if ($comptMedPsy != 0) {
										?>

										<?php
										}
										if ($comptMedInf != 0) {
										?>

										<?php
										}
										if ($comptMedLabo != 0) {
										?>

										<?php
										}
										if ($comptMedRadio != 0) {
										?>

										<?php
										}
										if ($comptMedConsom != 0) {
										?>

										<?php
										}
										if ($comptMedMedoc != 0) {
										?>

										<?php
										}
										?>

									</tr>

								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<?php
			$dateConsult = date('Y-m-d', strtotime($_GET['datefacture']));

			$resultatConsu = $connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.id_factureConsult=:id_factureConsult AND (c.dateconsu=:dateconsu OR c.dateconsu!=:dateconsu)');
			$resultatConsu->execute(array(
				'num' => $_GET['num'],
				'dateconsu' => $dateConsult,
				'id_factureConsult' => $_GET['idbill']
			));

			$resultatConsu->setFetchMode(PDO::FETCH_OBJ);

			if ($ligneConsu = $resultatConsu->fetch()) {
				$idassurance = $ligneConsu->id_assuConsu;
				$dateconsu = date('d-M-Y', strtotime($ligneConsu->dateconsu));
				$resultIdDoc = $connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
				$resultIdDoc->execute(array(
					'operation' => $ligneConsu->id_uM
				));
				$resultIdDoc->setFetchMode(PDO::FETCH_OBJ);


				if ($ligneIdDoc = $resultIdDoc->fetch()) {
					$codeDoc = $ligneIdDoc->codemedecin;
					$fullnameDoc = $ligneIdDoc->nom_u . ' ' . $ligneIdDoc->prenom_u;
				}
			}
			?>




			<div class="account-container" style="margin: 20px auto auto; width:95%; border: 1px solid #ccc; background:#fff; padding:5px; border-radius:3px; font-size:80%;font-family:consolas;<?php if (isset($_GET['smallsize'])) {
																																																		echo 'display:none;';
																																																	} ?>">

				<?php
				$barcode = '

	<table style="width:100%">
		
		<tr>
			<td colspan=2 style="text-align:center;">
				<span style="text-align:center;background:#333;border-radius:40px;color:#eee;font-weight:400;padding:5px 50px">Powered by <font>Medical File</font> , a product of Innovate Solutions Ltd. ©2022-' . date('Y') . ', All Rights Reserved.</span>
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
                                Phone: (+250) 786214461<br/>
                                E-mail: cliniquengororero@gmail.com<br/>
                                Ngororero - Ngororero - Rususa
                            </span>
						</td>
					</tr>
				</tbody>
			  </table>
			</td>
			
			<td style="text-align:right;">
				<img src="barcode/png/barcode' . $codecashier . '.png" style="height:auto;"/>
			</td>
			
		</tr>
		
	</table>';

				echo $barcode;
				?>

				<?php
				$dateConsult = date('Y-m-d', strtotime($_GET['datefacture']));

				$resultatConsu = $connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND c.id_factureConsult=:id_factureConsult AND (c.dateconsu=:dateconsu OR c.dateconsu!=:dateconsu)');
				$resultatConsu->execute(array(
					'num' => $_GET['num'],
					'dateconsu' => $dateConsult,
					'id_factureConsult' => $_GET['idbill']
				));

				$resultatConsu->setFetchMode(PDO::FETCH_OBJ);

				if ($ligneConsu = $resultatConsu->fetch()) {
					$idassurance = $ligneConsu->id_assuConsu;
					$dateconsu = date('d-M-Y', strtotime($ligneConsu->dateconsu));
					$poids=$ligneConsu->poids;
					$resultIdDoc = $connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
					$resultIdDoc->execute(array(
						'operation' => $ligneConsu->id_uM
					));
					$resultIdDoc->setFetchMode(PDO::FETCH_OBJ);


					if ($ligneIdDoc = $resultIdDoc->fetch()) {
						$codeDoc = $ligneIdDoc->codemedecin;
						$fullnameDoc = $ligneIdDoc->nom_u . ' ' . $ligneIdDoc->prenom_u;
					}
				}



				$TotalGnl = 0;


				/*--------------Billing Info Patient-----------------*/

				$resultatsPatient = $connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
				$resultatsPatient->execute(array(
					'operation' => $numPa
				));

				$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);

				if ($lignePatient = $resultatsPatient->fetch()) {
					$bill = $lignePatient->bill;
					$idassurance = $lignePatient->id_assurance;
					$telephone=$lignePatient->telephone;
					
					if ($lignePatient->sexe == "M") {
						$sexe = "Male";
					} elseif ($lignePatient->sexe == "F") {
						$sexe = "Female";
					} else {
						$sexe = "";
					}



					$profession = $lignePatient->profession;
					$cardnumber = $lignePatient->carteassuranceid;
					$adherentbill = $lignePatient->adherent;



					$resultAdresse = $connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
					$resultAdresse->execute(array(
						'idProv' => $lignePatient->province,
						'idDist' => $lignePatient->district,
						'idSect' => $lignePatient->secteur
					));

					$resultAdresse->setFetchMode(PDO::FETCH_OBJ);

					$comptAdress = $resultAdresse->rowCount();

					if ($ligneAdresse = $resultAdresse->fetch()) {
						if ($ligneAdresse->id_province == $lignePatient->province) {
							$adresse = $ligneAdresse->nomprovince . ', ' . $ligneAdresse->nomdistrict . ', ' . $ligneAdresse->nomsector;
						}
					} elseif ($lignePatient->autreadresse != "") {
						$adresse = $lignePatient->autreadresse;
					} else {
						$adresse = "";
					}



					$userinfo = '<table style="width:100%;">
			
			<tr>
				<td style="text-align:left;">
					Full name:
					<span style="font-weight:bold;font-size:16px;">' . $lignePatient->nom_u . ' ' . $lignePatient->prenom_u . '</span><br/>
					Gender: <span style="font-weight:bold">' . $sexe . '</span><br/>
					Adress: <span style="font-weight:bold">' . $adresse . '</span><br />
					Telephone: <span style="font-weight:bold">' . $telephone . '</span>

				</td>
				
				<td style="text-align:center;">
					Insurance type: <span style="font-weight:bold">';

					$resultAssurance = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuid ');

					$resultAssurance->execute(array(
						'assuid' => $idassurance
					));

					$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

					if ($ligneAssu = $resultAssurance->fetch()) {
						$userinfo .= '' . $nomassurancebill=$ligneAssu->nomassurance . '</span><br/>';

						if ($ligneAssu->id_assurance != 1) {

							if ($idcardbill != "") {
								$userinfo .= 'N° insurance card:
						<span style="font-weight:bold">' . $idcardbill;
							} elseif ($lignePatient->carteassuranceid != "") {

								$userinfo .= 'N° insurance card:
						<span style="font-weight:bold">' . $lignePatient->carteassuranceid;
							}

							if ($numpolicebill != "") {
								$userinfo .= '</span><br/>
						
						N° police:
						<span style="font-weight:bold">' . $numpolicebill;
							} elseif ($lignePatient->numeropolice != "") {

								$userinfo .= '</span><br/>
						
						N° police:
						<span style="font-weight:bold">' . $lignePatient->numeropolice;
							}

							if ($adherentbill != "") {
								
							} elseif ($lignePatient->adherent != "") {

								$userinfo .= '</span><br/>
						
						Principal member:
						<span style="font-weight:bold">' . $lignePatient->adherent;

								

								$userinfo .= '</span><br/>
						
						Beneficiare:
						<input type="checkbox"> Adherent Lui meme <input type="checkbox"> conjoint <input type="checkbox"> Enfant';
							}
						}
					}

					$userinfo .= '</span>';


					if ($profession != "") {
						$userinfo .= '</span><br/>
						
						Affiliate Company:
						<span style="font-weight:bold">' . $profession;
					}
					if ($cardnumber != "") {
						
					}
					if ($cardnumber != "") {
						$userinfo .= '</span><br/>
						
						Principal Member:
						<span style="font-weight:bold">' . $adherentbill;
					

						$userinfo .= '</span><br/>
						
						Beneficiare:
						<input type="checkbox"> Adherent Lui meme <input type="checkbox"> conjoint <input type="checkbox"> Enfant';
					}

					if (isset($dateconsu) && isset($fullnameDoc)) {
						$userinfo .= '</span>
							</td>
							
							<td style="text-align:right;">
								Patient ID: <span style="font-weight:bold">' . $lignePatient->numero . '</span><br/>
								Date of birth: <span style="font-weight:bold">' . date('d-M-Y', strtotime($lignePatient->date_naissance)) . '</span><br/>
								Entry Date: <span style="font-weight:bold">' . $dateconsu . '</span><br/>
								Discharge Date: <span style="font-weight:bold"> <input type="date" style="width:100px"> </span><br>

					Status: <input type="checkbox"> OutPatient <input type="checkbox"> InPatient <br>
								Weight: <span style="font-weight:bold">' . $poids . '</span><br/>

													Maladie Naturelle <input type="checkbox"><br />
					Maladie professionelle <input type="checkbox"><br />
					Accident de travail <input type="checkbox"> <br />
					Accident de circulation <input type="checkbox"></br>
					Autres <input type="checkbox"></br>
								
							</td>
							
						</tr>
					</table>';
					} else {
						$userinfo .= '</span>
							</td>
							
							<td style="text-align:right;">
								Patient ID: <span style="font-weight:bold">' . $lignePatient->numero . '</span><br/>
								Date of birth: <span style="font-weight:bold">' . date('d-M-Y', strtotime($lignePatient->date_naissance)) . '</span><br/>
							</td>
							
						</tr>
					</table>';
					}

					echo $userinfo;

					$objPHPExcel->getProperties()->setCreator('' . $nameHospital . '')
						->setLastModifiedBy('' . $doneby . '')
						->setTitle('Bill #' . $numbill . '')
						->setSubject("Billing information")
						->setDescription('Billing information for patient : ' . $lignePatient->numero . ', ' . $lignePatient->nom_u . ' ' . $lignePatient->prenom_u . '')
						->setKeywords("Bill Excel")
						->setCategory("Bill");

					for ($col = ord('a'); $col <= ord('z'); $col++) {
						$objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
					}

					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', 'S/N')
						->setCellValue('B1', '' . $lignePatient->numero . '')
						->setCellValue('A2', 'Full name')
						->setCellValue('B2', '' . $lignePatient->nom_u . '  ' . $lignePatient->prenom_u . '')

						->setCellValue('A3', 'Adresse')
						->setCellValue('B3', '' . $adresse . '')

						->setCellValue('A4', 'Insurance')
						->setCellValue('B4', '' . $nomassurancebill . ' ' . $bill . '%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', '' . $numbill . '')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', '' . $doneby . '')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', '' . $datebill . '');
				}

				/*-------Requête pour AFFICHER Type consultation-----------*/

				$resultConsult = $connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_factureConsult=:idbill AND p.numero=:num AND p.numero=c.numero AND c.numero=:num AND c.id_factureConsult IS NOT NULL ORDER BY c.id_consu');
				$resultConsult->execute(array(
					'idbill' => $_GET['idbill'],
					'num' => $numPa
				));

				$resultConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptConsult = $resultConsult->rowCount();

				$TotalConsult = 0;
				$TotalConsultCCO = 0;



				/*-------Requête pour AFFICHER Med_consult-----------*/

				$resultMedConsult = $connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_factureMedConsu=:idbill AND mc.dateconsu!="0000-00-00" AND mc.id_factureMedConsu!=0 ORDER BY mc.id_medconsu');
				$resultMedConsult->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

				$comptMedConsult = $resultMedConsult->rowCount();

				$TotalMedConsult = 0;
				$TotalMedConsultCCO = 0;



				/*-------Requête pour AFFICHER Med_surge-----------*/

				$resultMedSurge = $connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_factureMedSurge=:idbill AND ms.id_factureMedSurge!=0 ORDER BY ms.id_medsurge');
				$resultMedSurge->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

				$comptMedSurge = $resultMedSurge->rowCount();

				$TotalMedSurge = 0;
				$TotalMedSurgeCCO = 0;



				/*-------Requête pour AFFICHER Med_kine-----------*/

				$resultMedKine = $connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_factureMedKine=:idbill AND mk.id_factureMedKine!=0 ORDER BY mk.id_medkine');
				$resultMedKine->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

				$comptMedKine = $resultMedKine->rowCount();

				$TotalMedKine = 0;
				$TotalMedKineCCO = 0;



				/*-------Requête pour AFFICHER Med_ortho-----------*/

				$resultMedOrtho = $connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedOrtho=:idbill AND mo.id_factureMedOrtho!=0 ORDER BY mo.id_medortho');
				$resultMedOrtho->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

				$comptMedOrtho = $resultMedOrtho->rowCount();

				$TotalMedOrtho = 0;
				$TotalMedOrthoCCO = 0;



				/*-------Requête pour AFFICHER Med_psy-----------*/

				$resultMedPsy = $connexion->prepare('SELECT *FROM med_psy mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedPsy=:idbill AND mo.id_factureMedPsy!=0 ORDER BY mo.id_medpsy');
				$resultMedPsy->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedPsy->setFetchMode(PDO::FETCH_OBJ);

				$comptMedPsy = $resultMedPsy->rowCount();

				$TotalMedPsy = 0;
				$TotalMedPsyCCO = 0;



				/*-------Requête pour AFFICHER Med_inf-----------*/

				$resultMedInf = $connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_factureMedInf=:idbill AND mi.id_factureMedInf!=0 ORDER BY mi.id_medinf');
				$resultMedInf->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

				$comptMedInf = $resultMedInf->rowCount();

				$TotalMedInf = 0;
				$TotalMedInfCCO = 0;



				/*-------Requête pour AFFICHER Med_labo-----------*/

				$resultMedLabo = $connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_factureMedLabo=:idbill AND ml.id_factureMedLabo!=0 ORDER BY ml.id_medlabo');
				$resultMedLabo->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

				$comptMedLabo = $resultMedLabo->rowCount();

				$TotalMedLabo = 0;
				$TotalMedLaboCCO = 0;




				/*-------Requête pour AFFICHER Med_radio-----------*/

				$resultMedRadio = $connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_factureMedRadio=:idbill AND mr.id_factureMedRadio!=0 ORDER BY mr.id_medradio');
				$resultMedRadio->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

				$comptMedRadio = $resultMedRadio->rowCount();

				$TotalMedRadio = 0;
				$TotalMedRadioCCO = 0;



				/*-------Requête pour AFFICHER Med_consom-----------*/

				$resultMedConsom = $connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_factureMedConsom=:idbill AND mco.id_factureMedConsom!=0 ORDER BY mco.id_medconsom');
				$resultMedConsom->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

				$comptMedConsom = $resultMedConsom->rowCount();

				$TotalMedConsom = 0;
				$TotalMedConsomCCO = 0;



				/*-------Requête pour AFFICHER Med_medoc-----------*/

				$resultMedMedoc = $connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_factureMedMedoc=:idbill AND mdo.id_factureMedMedoc!=0 ORDER BY mdo.id_medmedoc');
				$resultMedMedoc->execute(array(
					'num' => $numPa,
					'idbill' => $_GET['idbill']
				));

				$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

				$comptMedMedoc = $resultMedMedoc->rowCount();

				$TotalMedMedoc = 0;
				$TotalMedMedocCCO = 0;

				?>

				<table style="width:100%; margin-bottom:-5px">
					<tr>
						<td style="text-align:left; width:33%;">
							<h4><?php echo $datebill; ?></h4>
						</td>

						<td style="text-align:center; width:33%;">
							<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo $numbill; ?> <?php if ($dettes != NULL) {
																												echo '<span style="font-size:150%; font-weight:600;color:red;" class="buttonBill">Indebted</span>';
																											} ?></h2>

						</td>

						<td style="text-align:right;width:33%;">

							<form method="post" action="bills.php?num=<?php echo $_GET['num']; ?>&cashier=<?php echo $_SESSION['codeCash']; ?><?php if (isset($dateconsu)) {
																																					echo "&dateconsu=" . $dateconsu;
																																				}; ?><?php if (isset($_GET['idconsu'])) {
																																							echo '&idconsu=' . $_GET['idconsu'];
																																						} ?><?php if (isset($_GET['idmed'])) {
																																							echo '&idmed=' . $_GET['idmed'];
																																						} ?><?php if (isset($_GET['datefacture'])) {
																																																						echo '&datefacture=' . $_GET['datefacture'];
																																																					} ?><?php if (isset($_GET['idtypeconsu'])) {
																																																																								echo '&idtypeconsu=' . $_GET['idtypeconsu'];
																																																																							} ?><?php if (isset($_GET['idassu'])) {
																																																																																								echo '&idassu=' . $_GET['idassu'];
																																																																																							} ?><?php if (isset($idBilling)) {
																																																																																																													echo '&idbill=' . $idBilling;
																																																																																																												} ?><?php if (isset($_GET['dettelist'])) {
																																																																																																																																		echo '&dettelist=' . $_GET['dettelist'];
																																																																																																																																	} ?>&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

								<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142); ?></button>

							</form>
						</td>

						<td class="buttonBill">
							<a href="<?php if (isset($_GET['dettelist'])) {
											echo 'dettesList.php';
										} else {
											echo 'listfacture.php';
										} ?>?codeCash=<?php echo $_SESSION['codeCash']; ?>&caissier=ok<?php if (isset($_GET['dettelist'])) {
																											echo '&dettelist=' . $_GET['dettelist'];
																										} ?><?php if (isset($_GET['english'])) {
																												echo '&english=' . $_GET['english'];
																											} else {
																												if (isset($_GET['francais'])) {
																													echo '&francais=' . $_GET['francais'];
																												}
																											} ?>" id="cancelbtn" style="<?php if (!isset($_GET['finishbtn'])) {
																																																								echo "display:inline";
																																																							} else {
																																																								echo "display:none";
																																																							} ?>;margin:5px;">
								<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140); ?></button>
							</a>

							<a href="<?php if (isset($_GET['dettelist'])) {
											echo 'dettesList.php';
										} else {
											echo 'listfacture.php';
										} ?>?codeCash=<?php echo $_SESSION['codeCash']; ?>&caissier=ok<?php if (isset($_GET['dettelist'])) {
																											echo '&dettelist=' . $_GET['dettelist'];
																										} ?><?php if (isset($_GET['english'])) {
																												echo '&english=' . $_GET['english'];
																											} else {
																												if (isset($_GET['francais'])) {
																													echo '&francais=' . $_GET['francais'];
																												}
																											} ?>" id="finishbtn" style="<?php if (!isset($_GET['finishbtn'])) {
																																																								echo "display:none";
																																																							} ?>;margin:5px;">
								<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141); ?></button>
							</a>
						</td>
					</tr>
				</table>


				<?php
				try {
					$TotalGnlPrice = 0;
					$TotalGnlPatientPrice = 0;
					$TotalGnlPatientBalance = 0;
					$TotalGnlInsurancePrice = 0;
					$i = 0;
					$x = 0;
					$y = 0;
					$z = 0;

					if ($comptConsult != 0) {
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B8', 'Type of consultation')
							->setCellValue('C8', 'Price')
							->setCellValue('D8', 'Patient Price')
							->setCellValue('E8', 'Insurance Price');


						$typeconsult = '<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th>Type of Consultation</th>
						<th style="width:10%;">Balance ' . $nomassurancebill . '</th>
						<th style="width:10%;">Patient (' . $bill . '%)</th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>


				<tbody>';

						$TotalpatientPrice = 0;
						$TotalpatientBalance = 0;
						$TotaluapPrice = 0;

						while ($ligneConsult = $resultConsult->fetch()) {

							$billpercent = $ligneConsult->insupercent;

							$idassu = $ligneConsult->id_assuConsu;
							$typeconsult .= '<tr style="text-align:center;">
						<td style="font-weight:bold;font-size:15px;">';


							$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

							$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

							$assuCount = $comptAssuConsu->rowCount();

							for ($i = 1; $i <= $assuCount; $i++) {

								$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuConsu->execute(array(
									'idassu' => $idassu
								));

								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if ($ligneNomAssu = $getAssuConsu->fetch()) {
									$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
								}
							}


							$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

							$resultPresta->execute(array(
								'prestaId' => $ligneConsult->id_typeconsult
							));

							$resultPresta->setFetchMode(PDO::FETCH_OBJ);

							$comptPresta = $resultPresta->rowCount();

							if ($comptPresta != 0) {
								if ($lignePresta = $resultPresta->fetch()) {
									if (isset($_POST['pourcentage'])) {
										$resultats = $connexion->prepare('UPDATE consultations SET insupercent=:percent WHERE id_consu=:idConsult');

										$resultats->execute(array(
											'percent' => $_POST['pourcentage'],
											'idConsult' => $_GET['idconsu']

										)) or die(print_r($connexion->errorInfo()));
									}

									if ($lignePresta->namepresta != '') {
										$nameprestaConsult = $lignePresta->namepresta;

										$typeconsult .= $lignePresta->namepresta . '</td>';
									} else {

										if ($lignePresta->nompresta != '') {
											$nameprestaConsult = $lignePresta->nompresta;
											$typeconsult .= $lignePresta->nompresta . '</td>';
										}
									}

									$prixPresta = $ligneConsult->prixtypeconsult;

									$typeconsult .= '
							
							<td style="font-weight:700">' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							';

									$TotalConsult = $TotalConsult + $prixPresta;
									$TotalConsultCCO = $TotalConsultCCO + $prixPrestaCCO;

									$typeconsult .= '<td style="font-weight:700">';

									$patientPrice = ($prixPresta * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$typeconsult .= $patientPrice . '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-weight:700">';

									$patientBalance = $patientPrice;
									$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

									$typeconsult .= $patientBalance . '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						
						<td style="font-weight:700">';

									$uapPrice = $prixPresta - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;

									$typeconsult .= $uapPrice . '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>';

									/*
			if($ligneConsult->id_factureConsult!=NULL)
			{
				$typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
			}
				 */
									$typeconsult .= '</tr>';
								}
							} else {

								if (isset($_POST['newprixtypeconsult'])) {
									if (isset($_POST['pourcentage'])) {
										$resultats = $connexion->prepare('UPDATE consultations SET prixautretypeconsult=:prixautretypeconsu,insupercent=:percent WHERE id_consu=:idConsult');

										$resultats->execute(array(
											'prixautretypeconsu' => $_POST['newprixtypeconsult'],
											'percent' => $_POST['pourcentage'],
											'idConsult' => $_GET['idconsu']

										)) or die(print_r($connexion->errorInfo()));
									}
								}

								$resultNewPresta = $connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');

								$resultNewPresta->execute(array(
									'idconsu' => $_GET['idconsu']
								));

								$resultNewPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptNewPresta = $resultNewPresta->rowCount();

								if ($ligneNewPresta = $resultNewPresta->fetch()) {
									$nameprestaConsult = $ligneNewPresta->autretypeconsult;

									$typeconsult .= $ligneNewPresta->autretypeconsult . '</td>';

									$prixPresta = $ligneNewPresta->prixautretypeconsult;

									$typeconsult .= '
							<td style="font-weight:700">' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


									$TotalConsult = $TotalConsult + $prixPresta;

									$typeconsult .= '<td style="font-weight:700">';

									$patientPrice = ($prixPresta * $billpercent) / 100;
									$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

									$typeconsult .= $patientPrice . '<span style="font-size:70%; font-weight:normal;">Rwf</span>
			</td>
						
						<td style="font-weight:700">';

									$patientBalance = $patientPrice;
									$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

									$typeconsult .= $patientBalance . '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-weight:700">';

									$uapPrice = $prixPresta - $patientPrice;
									$TotaluapPrice = $TotaluapPrice + $uapPrice;

									$typeconsult .= $uapPrice . '<span style="font-size:70%; font-weight:normal;">Rwf</span>
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

							$arrayConsult[$i][0] = $nameprestaConsult;
							$arrayConsult[$i][1] = $prixPresta;
							$arrayConsult[$i][2] = $patientPrice;
							$arrayConsult[$i][3] = $uapPrice;

							$i++;

							$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult, '', 'B9');
						}

						/* $typeconsult .= '<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">'.$TotalConsult;
		 */
						$TotalGnlPrice = $TotalGnlPrice + $TotalConsult;


						/*
			$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td></td>
						<td style="font-size: 110%; font-weight: bold;">'.$TotalpatientPrice;
			 */
						$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;


						$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;


						/*
			$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 110%; font-weight: bold;">'.$TotaluapPrice;
			 */
						$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;


						/*
			$typeconsult .= '<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>';
		 */

						$typeconsult .= '</tbody>
			</table>';

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (9 + $i) . '', '' . $TotalConsult . '')
							->setCellValue('D' . (9 + $i) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (9 + $i) . '', '' . $TotaluapPrice . '');

						echo $typeconsult;
					}


					if ($comptMedConsult != 0) {
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (11 + $i) . '', 'Services')
							->setCellValue('C' . (11 + $i) . '', 'Price')
							->setCellValue('D' . (11 + $i) . '', 'Patient Price')
							->setCellValue('E' . (11 + $i) . '', 'Insurance Price');

				?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th>Services</th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;



								while ($ligneMedConsult = $resultMedConsult->fetch()) {


									$billpercent = $ligneMedConsult->insupercentServ;

									$idassu = $ligneMedConsult->id_assuServ;

									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php

											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

											$resultPresta->execute(array(
												'prestaId' => $ligneMedConsult->id_prestationConsu
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) {

												if ($lignePresta->namepresta != '') {
													$nameprestaMedConsult = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedConsult = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedConsult->prixprestationConsu;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


												$TotalMedConsult = $TotalMedConsult + $prixPresta;
											?>

										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									<?php
											}

											if ($ligneMedConsult->id_prestationConsu == NULL and $ligneMedConsult->autreConsu != "") {
												$nameprestaMedConsult = $ligneMedConsult->autreConsu;
												echo $ligneMedConsult->autreConsu . '</td>';

												$prixPresta = $ligneMedConsult->prixautreConsu;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedConsult = $TotalMedConsult + $prixPresta;
									?>

										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;

												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>

										</td>
								<?php
											}

											$arrayMedConsult[$x][0] = $nameprestaMedConsult;
											$arrayMedConsult[$x][1] = $prixPresta;
											$arrayMedConsult[$x][2] = $patientPrice;
											$arrayMedConsult[$x][3] = $uapPrice;

											$x++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedConsult, '', 'B' . (12 + $i) . '');
										}
								?>
									</tr>
									<tr style="text-align:center;">
										<td></td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalMedConsult . '';

											$TotalGnlPrice = $TotalGnlPrice + $TotalMedConsult;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientPrice . '';

											$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientBalance . '';

											$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotaluapPrice . '';

											$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (12 + $i + $x) . '', '' . $TotalMedConsult . '')
							->setCellValue('D' . (12 + $i + $x) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (12 + $i + $x) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedSurge != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (14 + $i + $x) . '', 'Surgery')
							->setCellValue('C' . (14 + $i + $x) . '', 'Price')
							->setCellValue('D' . (14 + $i + $x) . '', 'Patient Price')
							->setCellValue('E' . (14 + $i + $x) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th>Surgery</th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedSurge = $resultMedSurge->fetch()) {

									$billpercent = $ligneMedSurge->insupercentSurge;

									$idassu = $ligneMedSurge->id_assuSurge;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php

											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
											$resultPresta->execute(array(
												'prestaId' => $ligneMedSurge->id_prestationSurge
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) {
												if ($lignePresta->namepresta != '') {
													$nameprestaMedSurge = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedSurge = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedSurge->prixprestationSurge;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedSurge = $TotalMedSurge + $prixPresta;
											?>
										</td>

										<td>
											<?php
												$patientPrice = ($ligneMedSurge->prixprestationSurge * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $ligneMedSurge->prixprestationSurge - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									<?php
											}

											if (($ligneMedSurge->id_prestationSurge == "" or $ligneMedSurge->id_prestationSurge == 0) and ($ligneMedSurge->prixautrePrestaS != 0)) {
												$nameprestaMedSurge = $ligneMedSurge->autrePrestaS;
												echo $ligneMedSurge->autrePrestaS . '</td>';


												$prixPresta = $ligneMedSurge->prixautrePrestaS;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


												$TotalMedSurge = $TotalMedSurge + $prixPresta;
									?>


										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
								<?php
											}


											$arrayMedSurge[$y][0] = $nameprestaMedSurge;
											$arrayMedSurge[$y][1] = $prixPresta;
											$arrayMedSurge[$y][2] = $patientPrice;
											$arrayMedSurge[$y][3] = $uapPrice;

											$y++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedSurge, '', 'B' . (15 + $i + $x) . '');
										}
								?>
									<tr style="text-align:center;">
										<td></td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalMedSurge . '';

											$TotalGnlPrice = $TotalGnlPrice + $TotalMedSurge;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientPrice . '';

											$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientBalance . '';

											$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotaluapPrice . '';

											$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (15 + $i + $x + $y) . '', '' . $TotalMedSurge . '')
							->setCellValue('D' . (15 + $i + $x + $y) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (15 + $i + $x + $y) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedKine != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (14 + $i + $x) . '', 'Physiotherapy')
							->setCellValue('C' . (14 + $i + $x) . '', 'Price')
							->setCellValue('D' . (14 + $i + $x) . '', 'Patient Price')
							->setCellValue('E' . (14 + $i + $x) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th><?php echo 'Physiotherapy'; ?></th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedKine = $resultMedKine->fetch()) {

									$billpercent = $ligneMedKine->insupercentKine;

									$idassu = $ligneMedKine->id_assuKine;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php

											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
											$resultPresta->execute(array(
												'prestaId' => $ligneMedKine->id_prestationKine
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) {
												if ($lignePresta->namepresta != '') {
													$nameprestaMedKine = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedKine = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedKine->prixprestationKine;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedKine = $TotalMedKine + $prixPresta;
											?>
										</td>

										<td>
											<?php
												$patientPrice = ($ligneMedKine->prixprestationKine * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $ligneMedKine->prixprestationKine - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									<?php
											}

											if (($ligneMedKine->id_prestationKine == "" or $ligneMedKine->id_prestationKine == 0) and ($ligneMedKine->prixautrePrestaK != 0)) {
												$nameprestaMedKine = $ligneMedKine->autrePrestaK;
												echo $ligneMedKine->autrePrestaK . '</td>';


												$prixPresta = $ligneMedKine->prixautrePrestaK;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


												$TotalMedKine = $TotalMedKine + $prixPresta;
												$TotalMedKineCCO = $TotalMedKineCCO + $prixPrestaCCO;
									?>


										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
								<?php
											}


											$arrayMedKine[$y][0] = $nameprestaMedKine;
											$arrayMedKine[$y][1] = $prixPresta;
											$arrayMedKine[$y][2] = $patientPrice;
											$arrayMedKine[$y][3] = $uapPrice;

											$y++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedKine, '', 'B' . (15 + $i + $x) . '');
										}
								?>
									<tr style="text-align:center;">
										<td></td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalMedKine . '';

											$TotalGnlPrice = $TotalGnlPrice + $TotalMedKine;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientPrice . '';

											$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientBalance . '';

											$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotaluapPrice . '';

											$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (15 + $i + $x + $y) . '', '' . $TotalMedKine . '')
							->setCellValue('D' . (15 + $i + $x + $y) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (15 + $i + $x + $y) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedOrtho != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (14 + $i + $x) . '', 'P&O')
							->setCellValue('C' . (14 + $i + $x) . '', 'Price')
							->setCellValue('D' . (14 + $i + $x) . '', 'Patient Price')
							->setCellValue('E' . (14 + $i + $x) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th><?php echo 'P&O'; ?></th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedOrtho = $resultMedOrtho->fetch()) {

									$billpercent = $ligneMedOrtho->insupercentOrtho;

									$idassu = $ligneMedOrtho->id_assuOrtho;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php

											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
											$resultPresta->execute(array(
												'prestaId' => $ligneMedOrtho->id_prestationOrtho
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) {
												if ($lignePresta->namepresta != '') {
													$nameprestaMedOrtho = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedOrtho = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedOrtho->prixprestationOrtho;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedOrtho = $TotalMedOrtho + $prixPresta;
											?>
										</td>

										<td>
											<?php
												$patientPrice = ($ligneMedOrtho->prixprestationOrtho * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance =  $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $ligneMedOrtho->prixprestationOrtho - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									<?php
											}

											if (($ligneMedOrtho->id_prestationOrtho == "" or $ligneMedOrtho->id_prestationOrtho == 0) and ($ligneMedOrtho->prixautrePrestaO != 0)) {
												$nameprestaMedOrtho = $ligneMedOrtho->autrePrestaO;
												echo $ligneMedOrtho->autrePrestaO . '</td>';


												$prixPresta = $ligneMedOrtho->prixautrePrestaO;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


												$TotalMedOrtho = $TotalMedOrtho + $prixPresta;
									?>


										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance =  $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
								<?php
											}


											$arrayMedOrtho[$y][0] = $nameprestaMedOrtho;
											$arrayMedOrtho[$y][1] = $prixPresta;
											$arrayMedOrtho[$y][2] = $patientPrice;
											$arrayMedOrtho[$y][3] = $uapPrice;

											$y++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedOrtho, '', 'B' . (15 + $i + $x) . '');
										}
								?>
									<tr style="text-align:center;">
										<td></td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalMedOrtho . '';

											$TotalGnlPrice = $TotalGnlPrice + $TotalMedOrtho;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientPrice . '';

											$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientBalance . '';

											$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotaluapPrice . '';

											$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (15 + $i + $x + $y) . '', '' . $TotalMedOrtho . '')
							->setCellValue('D' . (15 + $i + $x + $y) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (15 + $i + $x + $y) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedPsy != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (14 + $i + $x) . '', 'Psychologie')
							->setCellValue('C' . (14 + $i + $x) . '', 'Price')
							->setCellValue('D' . (14 + $i + $x) . '', 'Patient Price')
							->setCellValue('E' . (14 + $i + $x) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th><?php echo 'Psychologie'; ?></th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedPsy = $resultMedPsy->fetch()) {

									$billpercent = $ligneMedPsy->insupercentPsy;

									$idassu = $ligneMedPsy->id_assuPsy;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php

											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
											$resultPresta->execute(array(
												'prestaId' => $ligneMedPsy->id_prestation
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) {
												if ($lignePresta->namepresta != '') {
													$nameprestaMedPsy = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedPsy = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedPsy->prixprestation;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedPsy = $TotalMedPsy + $prixPresta;
											?>
										</td>


										<td>
											<?php
												$patientPrice = ($ligneMedPsy->prixprestation * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $ligneMedPsy->prixprestation - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									<?php
											}

											if (($ligneMedPsy->id_prestation == "" or $ligneMedPsy->id_prestation == 0) and ($ligneMedPsy->prixautrePrestaM != 0)) {
												$nameprestaMedPsy = $ligneMedPsy->autrePrestaM;
												echo $ligneMedPsy->autrePrestaM . '</td>';


												$prixPresta = $ligneMedPsy->prixautrePrestaM;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


												$TotalMedPsy = $TotalMedPsy + $prixPresta;
									?>


										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
								<?php
											}


											$arrayMedPsycho[$y][0] = $nameprestaMedPsy;
											$arrayMedPsycho[$y][1] = $prixPresta;
											$arrayMedPsycho[$y][2] = $patientPrice;
											$arrayMedPsycho[$y][3] = $uapPrice;

											$y++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedPsycho, '', 'B' . (15 + $i + $x) . '');
										}
								?>
									<tr style="text-align:center;">
										<td></td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalMedPsy . '';

											$TotalGnlPrice = $TotalGnlPrice + $TotalMedPsy;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientPrice . '';

											$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientBalance . '';

											$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotaluapPrice . '';

											$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (15 + $i + $x + $y) . '', '' . $TotalMedPsy . '')
							->setCellValue('D' . (15 + $i + $x + $y) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (15 + $i + $x + $y) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedInf != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (14 + $i + $x) . '', 'Nursing Care')
							->setCellValue('C' . (14 + $i + $x) . '', 'Price')
							->setCellValue('D' . (14 + $i + $x) . '', 'Patient Price')
							->setCellValue('E' . (14 + $i + $x) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th>Nursing Care</th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedInf = $resultMedInf->fetch()) {

									$billpercent = $ligneMedInf->insupercentInf;

									$idassu = $ligneMedInf->id_assuInf;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php

											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');
											$resultPresta->execute(array(
												'prestaId' => $ligneMedInf->id_prestation
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ);

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) {
												if ($lignePresta->namepresta != '') {
													$nameprestaMedInf = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedInf = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedInf->prixprestation;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedInf = $TotalMedInf + $prixPresta;
											?>
										</td>

										<td>
											<?php
												$patientPrice = ($ligneMedInf->prixprestation * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $ligneMedInf->prixprestation - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									<?php
											}

											if ($ligneMedInf->id_prestation == "" and $ligneMedInf->autrePrestaM != "") {
												$nameprestaMedInf = $ligneMedInf->autrePrestaM;
												echo $ligneMedInf->autrePrestaM . '</td>';


												$prixPresta = $ligneMedInf->prixautrePrestaM;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';


												$TotalMedInf = $TotalMedInf + $prixPresta;
									?>


										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
								<?php
											}


											$arrayMedInf[$y][0] = $nameprestaMedInf;
											$arrayMedInf[$y][1] = $prixPresta;
											$arrayMedInf[$y][2] = $patientPrice;
											$arrayMedInf[$y][3] = $uapPrice;

											$y++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedInf, '', 'B' . (15 + $i + $x) . '');
										}
								?>
									<tr style="text-align:center;">
										<td></td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalMedInf . '';

											$TotalGnlPrice = $TotalGnlPrice + $TotalMedInf;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientPrice . '';

											$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotalpatientBalance . '';

											$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
										<td style="font-size: 110%; font-weight: bold;">
											<?php
											echo $TotaluapPrice . '';

											$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (15 + $i + $x + $y) . '', '' . $TotalMedInf . '')
							->setCellValue('D' . (15 + $i + $x + $y) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (15 + $i + $x + $y) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedLabo != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (17 + $i + $x + $y) . '', 'Labs')
							->setCellValue('C' . (17 + $i + $x + $y) . '', 'Price')
							->setCellValue('D' . (17 + $i + $x + $y) . '', 'Patient Price')
							->setCellValue('E' . (17 + $i + $x + $y) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th>Labs</th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedLabo = $resultMedLabo->fetch()) {

									$billpercent = $ligneMedLabo->insupercentLab;

									$idassu = $ligneMedLabo->id_assuLab;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php
											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

											$resultPresta->execute(array(
												'prestaId' => $ligneMedLabo->id_prestationExa
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ); //on veut que le résultat soit récupérable sous forme d'objet

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) //on recupere la liste des éléments
											{
												if ($lignePresta->namepresta != '') {
													$nameprestaMedLabo = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedLabo = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedLabo->prixprestationExa;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedLabo = $TotalMedLabo + $prixPresta;
											?>
										</td>

										<td>
											<?php
												$patientPrice = ($ligneMedLabo->prixprestationExa * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance =  $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $ligneMedLabo->prixprestationExa - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $ligneMedLabo->prixprestationExa - $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
								<?php
											}

											if ($ligneMedLabo->id_prestationExa == "" and $ligneMedLabo->autreExamen != "") {
												$nameprestaMedLabo = $ligneMedLabo->autreExamen;
												echo $ligneMedLabo->autreExamen . '</td>';

												$prixPresta = $ligneMedLabo->prixautreExamen;

												echo '<td>' . $ligneMedLabo->prixautreExamen . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedLabo = $TotalMedLabo + $ligneMedLabo->prixautreExamen;
								?>


									<td>
										<?php
												$patientPrice = ($ligneMedLabo->prixautreExamen * $billpercent) / 100;

												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
												$uapPrice = $ligneMedLabo->prixautreExamen - $patientPrice;

												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
							<?php
											}

											$arrayMedLabo[$z][0] = $nameprestaMedLabo;
											$arrayMedLabo[$z][1] = $prixPresta;
											$arrayMedLabo[$z][2] = $patientPrice;
											$arrayMedLabo[$z][3] = $uapPrice;

											$z++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedLabo, '', 'B' . (18 + $i + $x + $y) . '');
										}
							?>
							<tr style="text-align:center;">
								<td></td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotalMedLabo . '';

									$TotalGnlPrice = $TotalGnlPrice + $TotalMedLabo;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotalpatientPrice . '';

									$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotalpatientBalance . '';

									$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotaluapPrice . '';

									$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
							</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (18 + $i + $x + $y + $z) . '', '' . $TotalMedLabo . '')
							->setCellValue('D' . (18 + $i + $x + $y + $z) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (18 + $i + $x + $y + $z) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedRadio != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (21 + $i + $x + $y) . '', 'Radio')
							->setCellValue('C' . (21 + $i + $x + $y) . '', 'Price')
							->setCellValue('D' . (21 + $i + $x + $y) . '', 'Patient Price')
							->setCellValue('E' . (21 + $i + $x + $y) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th>Radiology</th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotaltopupPrice = 0;
								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedRadio = $resultMedRadio->fetch()) {

									$billpercent = $ligneMedRadio->insupercentRad;

									$idassu = $ligneMedRadio->id_assuRad;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}

								?>
									<tr style="text-align:center;">
										<td>
											<?php
											$resultPresta = $connexion->prepare('SELECT *FROM ' . $presta_assu . ' p WHERE p.id_prestation=:prestaId');

											$resultPresta->execute(array(
												'prestaId' => $ligneMedRadio->id_prestationRadio
											));

											$resultPresta->setFetchMode(PDO::FETCH_OBJ); //on veut que le résultat soit récupérable sous forme d'objet

											$comptPresta = $resultPresta->rowCount();

											if ($lignePresta = $resultPresta->fetch()) //on recupere la liste des éléments
											{
												if ($lignePresta->namepresta != '') {
													$nameprestaMedRadio = $lignePresta->namepresta;
													echo $lignePresta->namepresta . '</td>';
												} else {

													$nameprestaMedRadio = $lignePresta->nompresta;
													echo $lignePresta->nompresta . '</td>';
												}

												$prixPresta = $ligneMedRadio->prixprestationRadio;

												echo '<td>' . $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedRadio = $TotalMedRadio + $prixPresta;
											?>
										</td>

										<td>
											<?php
												$patientPrice = ($prixPresta * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>

										<td>
											<?php
												$uapPrice = $prixPresta - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice . '';
											?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										</td>
									</tr>
								<?php
											}

											if ($ligneMedRadio->id_prestationRadio == "" and $ligneMedRadio->autreRadio != "") {
												$nameprestaMedRadio = $ligneMedRadio->autreRadio;
												echo $ligneMedRadio->autreRadio . '</td>';

												$prixPresta = $ligneMedRadio->prixautreRadio;

												echo '<td>' . $ligneMedRadio->prixautreRadio . '<span style="font-size:70%; font-weight:normal;">Rwf</span></td>';

												$TotalMedRadio = $TotalMedRadio + $ligneMedRadio->prixautreRadio;
								?>


									<td>
										<?php
												$patientPrice = ($ligneMedRadio->prixautreRadio * $billpercent) / 100;

												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;

												echo $patientPrice . '';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

									<td>
										<?php
												$uapPrice = $ligneMedRadio->prixautreRadio - $patientPrice;

												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $ligneMedRadio->prixautreRadio - $patientPrice . '';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
							<?php
											}

											$arrayMedRadio[$z][0] = $nameprestaMedRadio;
											$arrayMedRadio[$z][1] = $prixPresta;
											$arrayMedRadio[$z][2] = $patientPrice;
											$arrayMedRadio[$z][3] = $uapPrice;

											$z++;

											$objPHPExcel->setActiveSheetIndex(0)
												->fromArray($arrayMedRadio, '', 'B' . (18 + $i + $x + $y) . '');
										}
							?>
							<tr style="text-align:center;">
								<td></td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotalMedRadio . '';

									$TotalGnlPrice = $TotalGnlPrice + $TotalMedRadio;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotalpatientPrice . '';

									$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotalpatientBalance . '';

									$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
								<td style="font-size: 110%; font-weight: bold;">
									<?php
									echo $TotaluapPrice . '';

									$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
									?><span style="font-size:70%; font-weight:normal;">Rwf</span>
								</td>
							</tr>
							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (18 + $i + $x + $y + $z) . '', '' . $TotalMedRadio . '')
							->setCellValue('D' . (18 + $i + $x + $y + $z) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (18 + $i + $x + $y + $z) . '', '' . $TotaluapPrice . '');
					}


					if ($comptMedConsom != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (20 + $i + $x + $y) . '', 'Consommables')
							->setCellValue('C' . (20 + $i + $x + $y) . '', 'Price')
							->setCellValue('D' . (20 + $i + $x + $y) . '', 'Patient Price')
							->setCellValue('E' . (20 + $i + $x + $y) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th style="width:30%">Consommables</th>
									<th></th>
									<th style="width:10%">Qty</th>
									<th style="width:10%">P/U <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedConsom = $resultMedConsom->fetch()) {

									$billpercent = $ligneMedConsom->insupercentConsom;

									$idassu = $ligneMedConsom->id_assuConsom;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}


									$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, ' . $presta_assu . ' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');

									$resultPresta->execute(array(
										'prestaId' => $ligneMedConsom->id_prestationConsom
									));

									$resultPresta->setFetchMode(PDO::FETCH_OBJ); //on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta = $resultPresta->rowCount();


									if ($comptPresta == 0) {
										$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
										$resultPresta->execute(array(
											'prestaId' => $ligneMedConsom->id_prestationConsom
										));

										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
									}

									if ($lignePresta = $resultPresta->fetch()) //on recupere la liste des éléments
									{
								?>
										<tr style="text-align:center;">

											<td>
												<?php
												if ($lignePresta->namepresta != '') {
													$nameprestaMedConsom = $lignePresta->namepresta;
													echo $lignePresta->namepresta;
												} else {

													$nameprestaMedConsom = $lignePresta->nompresta;
													echo $lignePresta->nompresta;
												}
												?>

											</td>
											<td></td>
											<td>
												<?php

												$qteConsom = $ligneMedConsom->qteConsom;

												echo $qteConsom;
												?>
											</td>

											<td>
												<?php
												$prixPresta = $ligneMedConsom->prixprestationConsom;
												echo $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';
												?>
											</td>

											<td>
												<?php
												$balance = $prixPresta * $qteConsom;

												echo $balance . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';

												$TotalMedConsom = $TotalMedConsom + $balance;
												?>
											</td>

											<td>
												<?php
												$patientPrice = ($balance * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;
												echo $patientPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

											<td>
												<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

											<td>
												<?php
												$uapPrice = $balance - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;
												echo $uapPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

										</tr>
									<?php
									}

									if ($ligneMedConsom->id_prestationConsom == 0 and $ligneMedConsom->autreConsom != "") {
									?>
										<tr style="text-align:center;">
											<td>
												<?php
												$nameprestaMedConsom = $ligneMedConsom->autreConsom;
												echo $nameprestaMedConsom;
												?>
											</td>

											<td>
												<?php

												$qteConsom = $ligneMedConsom->qteConsom;

												echo $qteConsom;
												?>
											</td>

											<td>
												<?php
												$prixPresta = $ligneMedConsom->prixautreConsom;
												echo $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';

												?>
											</td>

											<td>
												<?php
												$balance = $prixPresta * $qteConsom;

												echo $balance . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';

												$TotalMedConsom = $TotalMedConsom + $balance;
												?>
											</td>


											<td>
												<?php
												$patientPrice = ($balance * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;
												echo $patientPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

											<td>
												<?php
												$uapPrice = $balance - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>
										</tr>
								<?php
									}

									$arrayMedConsom[$z][0] = $nameprestaMedConsom;
									$arrayMedConsom[$z][1] = $prixPresta;
									$arrayMedConsom[$z][2] = $patientPrice;
									$arrayMedConsom[$z][3] = $uapPrice;

									$z++;

									$objPHPExcel->setActiveSheetIndex(0)
										->fromArray($arrayMedConsom, '', 'B' . (21 + $i + $x + $y) . '');
								}
								?>
								<tr style="text-align:center;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>

									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotalMedConsom . '';

										$TotalGnlPrice = $TotalGnlPrice + $TotalMedConsom;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotalpatientPrice . '';

										$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotalpatientBalance . '';

										$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotaluapPrice . '';

										$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>

							</tbody>
						</table>
					<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (21 + $i + $x + $y + $z) . '', '' . $TotalMedConsom . '')
							->setCellValue('D' . (21 + $i + $x + $y + $z) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (21 + $i + $x + $y + $z) . '', '' . $TotaluapPrice . '');
					}

					if ($comptMedMedoc != 0) {

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . (23 + $i + $x + $y) . '', 'Medocs')
							->setCellValue('C' . (23 + $i + $x + $y) . '', 'Price')
							->setCellValue('D' . (23 + $i + $x + $y) . '', 'Patient Price')
							->setCellValue('E' . (23 + $i + $x + $y) . '', 'Insurance Price');

					?>

						<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
							<thead>
								<tr>
									<th>Medicaments</th>
									<th></th>
									<th style="width:4%">Qty</th>
									<th style="width:8%">P/U <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Balance <?php echo $nomassurancebill; ?></th>
									<th style="width:10%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
									<th style="width:10%;">Patient balance</th>
									<th style="width:10%;">Insurance balance</th>
								</tr>
							</thead>

							<tbody>
								<?php

								$TotalpatientPrice = 0;
								$TotalpatientBalance = 0;
								$TotaluapPrice = 0;

								while ($ligneMedMedoc = $resultMedMedoc->fetch()) {

									$billpercent = $ligneMedMedoc->insupercentMedoc;

									$idassu = $ligneMedMedoc->id_assuMedoc;
									$comptAssuConsu = $connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');

									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									$assuCount = $comptAssuConsu->rowCount();

									for ($i = 1; $i <= $assuCount; $i++) {

										$getAssuConsu = $connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
											'idassu' => $idassu
										));

										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if ($ligneNomAssu = $getAssuConsu->fetch()) {
											$presta_assu = 'prestations_' . strtolower($ligneNomAssu->nomassurance);
										}
									}


									$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, ' . $presta_assu . ' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');

									$resultPresta->execute(array(
										'prestaId' => $ligneMedMedoc->id_prestationMedoc
									));

									$resultPresta->setFetchMode(PDO::FETCH_OBJ); //on veut que le résultat soit récupérable sous forme d'objet

									$comptPresta = $resultPresta->rowCount();


									if ($comptPresta == 0) {
										$resultPresta = $connexion->prepare('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
										$resultPresta->execute(array(
											'prestaId' => $ligneMedMedoc->id_prestationMedoc
										));

										$resultPresta->setFetchMode(PDO::FETCH_OBJ);
									}

									if ($lignePresta = $resultPresta->fetch()) //on recupere la liste des éléments
									{
								?>
										<tr style="text-align:center;">
											<td>
												<?php
												if ($lignePresta->namepresta != '') {
													$nameprestaMedMedoc = $lignePresta->namepresta;
													echo $lignePresta->namepresta;
												} else {

													$nameprestaMedMedoc = $lignePresta->nompresta;
													echo $lignePresta->nompresta;
												}
												?>

											</td>
											<td></td>
											<td>
												<?php

												$qteMedoc = $ligneMedMedoc->qteMedoc;

												echo $qteMedoc;
												?>
											</td>

											<td>
												<?php
												$prixPresta = $ligneMedMedoc->prixprestationMedoc;
												echo $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';
												?>
											</td>

											<td>
												<?php
												$balance = $prixPresta * $qteMedoc;

												echo $balance . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';

												$TotalMedMedoc = $TotalMedMedoc + $balance;
												?>
											</td>

											<td>
												<?php
												$patientPrice = ($balance * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;
												echo $patientPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

											<td>
												<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

											<td>
												<?php
												$uapPrice = $balance - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;
												echo $uapPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

										</tr>

									<?php
									}

									if ($ligneMedMedoc->id_prestationMedoc == 0 and $ligneMedMedoc->autreMedoc != "") {
									?>
										<tr style="text-align:center;">
											<td>
												<?php
												$nameprestaMedMedoc = $ligneMedMedoc->autreMedoc;
												echo $nameprestaMedMedoc;
												?>
											</td>

											<td>
												<?php

												$qteMedoc = $ligneMedMedoc->qteMedoc;

												echo $qteMedoc;
												?>
											</td>

											<td>
												<?php
												$prixPresta = $ligneMedMedoc->prixautreMedoc;
												echo $prixPresta . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';

												?>
											</td>

											<td>
												<?php
												$balance = $prixPresta * $qteMedoc;

												echo $balance . '<span style="font-size:70%; font-weight:normal;">Rwf</span>';

												$TotalMedMedoc = $TotalMedMedoc + $balance;
												?>
											</td>

											<td>
												<?php
												$patientPrice = ($balance * $billpercent) / 100;
												$TotalpatientPrice = $TotalpatientPrice + $patientPrice;
												echo $patientPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

											<td>
												<?php
												$patientBalance = $patientPrice;
												$TotalpatientBalance = $TotalpatientBalance + $patientBalance;

												echo $patientBalance . '';
												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>

											<td>
												<?php
												$uapPrice = $balance - $patientPrice;
												$TotaluapPrice = $TotaluapPrice + $uapPrice;

												echo $uapPrice;

												?><span style="font-size:70%; font-weight:normal;">Rwf</span>
											</td>
										</tr>
								<?php
									}

									$arrayMedMedoc[$z][0] = $nameprestaMedMedoc;
									$arrayMedMedoc[$z][1] = $prixPresta;
									$arrayMedMedoc[$z][2] = $patientPrice;
									$arrayMedMedoc[$z][3] = $uapPrice;

									$z++;

									$objPHPExcel->setActiveSheetIndex(0)
										->fromArray($arrayMedMedoc, '', 'B' . (21 + $i + $x + $y) . '');
								}
								?>
								<tr style="text-align:center;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>

									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotalMedMedoc . '';

										$TotalGnlPrice = $TotalGnlPrice + $TotalMedMedoc;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotalpatientPrice . '';

										$TotalGnlPatientPrice = $TotalGnlPatientPrice + $TotalpatientPrice;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotalpatientBalance . '';

										$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td style="font-size: 110%; font-weight: bold;">
										<?php
										echo $TotaluapPrice . '';

										$TotalGnlInsurancePrice = $TotalGnlInsurancePrice + $TotaluapPrice;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>

							</tbody>
						</table>
				<?php

						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('C' . (24 + $i + $x + $y + $z) . '', '' . $TotalMedMedoc . '')
							->setCellValue('D' . (24 + $i + $x + $y + $z) . '', '' . $TotalpatientPrice . '')
							->setCellValue('E' . (24 + $i + $x + $y + $z) . '', '' . $TotaluapPrice . '');
					}
				} catch (Excepton $e) {
					echo 'Erreur:' . $e->getMessage() . '<br/>';
					echo 'Numero:' . $e->getCode();
				}

				?>

				<script>
					function getXMLHttpRequest() {
						var xhr = null;

						if (window.XMLHttpRequest || window.ActiveXObject) {
							if (window.ActiveXObject) {
								try {
									xhr = new ActiveXObject("Msxml2.XMLHTTP");
								} catch (e) {
									xhr = new ActiveXObject("Microsoft.XMLHTTP");
								}
							} else {
								xhr = new XMLHttpRequest();
							}
						} else {
							alert("Your Browser does not support   XMLHTTPRequest object...");
							return null;
						}

						return xhr;
					}

					function CheckOrders(order) {
						if (hour == 'heures') {
							document.getElementById('tableheure').style.display = 'inline';
						}

					}

					function() {
						document.
					}

					function ShowFinish(finish) {
						if (finish == 'finishbtn') {
							document.getElementById('finishbtn').style.display = 'inline';
						}

					}
				</script>

			</div>

			<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:10px; border-radius:3px; font-size:85%;<?php if (isset($_GET['smallsize'])) {
																																													echo 'display:none;';
																																												} ?>">

				<table class="printPreview" cellspacing="0" style="margin:auto;">
					<thead>
						<tr>
							<th style="width:15%"></th>
							<th style="width:15%;">Total balance <?php echo $nomassurancebill; ?></th>
							<th style="width:15%;">Patient <?php echo '(' . $bill . '%)'; ?></th>
							<th style="width:15%;">Patient balance</th>
							<th style="width:15%;">Insurance</th>
						</tr>
					</thead>

					<tbody>
						<tr style="text-align:center;">
							<td style="font-size: 110%; font-weight: bold;">Final Balance</td>
							<td style="font-size: 110%; font-weight: bold;"><?php echo number_format($TotalGnlPrice); ?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							<td style="font-size: 110%; font-weight: bold;"><?php echo number_format($TotalGnlPatientPrice); ?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
							<td style="font-size: 110%; font-weight: bold;">
								<?php

								$patientPayed = $TotalGnlPatientBalance - $dettes;

								echo number_format($TotalGnlPatientBalance); ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							<td style="font-size: 110%; font-weight: bold;"><?php echo number_format($TotalGnlInsurancePrice); ?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
						</tr>
					</tbody>
				</table>

				<?php
				if ($idBillCount != 0) {
				?>
					<table class="printPreview" cellspacing="0" style="margin-top:5px;border:none;">
						<tr class="buttonBill">
							<td style="font-size: 110%; font-weight: bold;border:none;"></td>

							<td style="font-size: 110%; font-weight: bold;text-align:right;border:2px solid #e8e8e8;width:10%;">
								<?php
								echo '<span>Payed : </span><span style="color:gray">' . $patientPayed . '</span>'; ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

							<td style="font-size: 110%; font-weight: bold;text-align:left; border:2px solid #e8e8e8;width:10%;">
								<?php
								echo '<span>Debt : </span><span style="color:gray">' . $dettes . '</span>'; ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>

						</tr>
					</table>
				<?php
				}
				?>
			</div>

			<div class="account-container" style="margin:20px auto auto; width:90%; background:#fff; border-radius:3px; font-size:85%;<?php if (isset($_GET['smallsize'])) {
																																			echo 'display:none;';
																																		} ?>">

				<?php
				$footer = '

			<table style="width:100%">
				
				<tr>
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px; border-bottom:1px solid #333;">
						<span style="font-weight:bold">Patient Signature</span>
					</td>
					
					<td style="text-align:right;">
						 Done by : <span style="font-weight:bold">' . $fullnameDoc . '</span>
					</td>
					
				</tr>
				
			</table>';

				echo $footer;
				?>

			</div>

		<?php

		} else {

			echo '<script text="text/javascript">alert("You are not logged in");</script>';

			echo '<script text="text/javascript">document.location.href="index.php"</script>';

			/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&cashier=CSC15A01&dateconsu=2015-09-19");
	file_put_contents("toPDF.html", $file); */
		}
		?>
		</body>

</html>