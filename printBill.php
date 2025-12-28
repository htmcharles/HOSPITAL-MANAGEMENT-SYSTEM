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

$annee = date('d').'-'.date('M').'-'.date('Y');


$heure = date('H').' : '.date('i').' : '.date('s').' : '.date('s');
$createfacture = $_GET['createfacture'];
// echo $heure;
// echo showBN();


	$numPa=$_GET['num'];
	$consuId=$_GET['idconsu'];
	
	
	$checkIdBill=$connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idbill ORDER BY b.id_bill LIMIT 1');

	$checkIdBill->execute(array(
	'idbill'=>$_GET['idbill']
	));

	$comptidBill=$checkIdBill->rowCount();

	// echo $comptidBill;
	
	if($comptidBill != 0)
	{
		$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBill->fetch();
		
		$idBilling = $ligne->id_bill;
		
		$numbill = $ligne->numbill;
		$createBill = 0;
		
		// echo $idBilling;
		
	}else{
		if ($createfacture == 0) {
			$numbill = showBN();
			$createBill = 1;
		}else{
			$numbill = $_GET['numbill'];
			$createBill = 0;
		}	
	}
	
	$getDette=$connexion->prepare('SELECT * FROM bills b WHERE b.id_bill=:idbill AND b.dette IS NOT NULL');
	$getDette->execute(array(
		'idbill'=>$_GET['idbill']
	));
	
	$getDette->setFetchMode(PDO::FETCH_OBJ);
	
	if(!isset($_GET['newdette']))
	{
		$idBillDebtCount = $getDette->rowCount();
		$existdette = '&newdette=ok';
	}else{
		$idBillDebtCount = 0;
		$existdette = '';
	}
	
	if($ligneIdBill=$getDette->fetch())
	{
		$dettes=$ligneIdBill->dette;
	}else{
		$dettes=NULL;
	}


if(isset($_GET['updatebill']))
{
	if($_GET['createBill']==1 && $_GET['createfacture']==0)
	{
		
		$createIdBill=$connexion->prepare('INSERT INTO bills (numbill) VALUES(:numbill)');

		$createIdBill->execute(array(
		'numbill'=>createBN()
		));
		
		$checkIdBilling=$connexion->query('SELECT * FROM bills b ORDER BY b.id_bill DESC LIMIT 1');
		
		$checkIdBilling->setFetchMode(PDO::FETCH_OBJ);
		
		$ligne=$checkIdBilling->fetch();
		
		$idBilling = $ligne->id_bill;
		$lastDette = $ligne->dette;
		$lastAmountpaid = $ligne->amountpaid;
		
	}else{
		$checkIdBilling=$connexion->query('SELECT * FROM bills b WHERE b.numbill IN ("'.$numbill.'") ORDER BY b.id_bill');

		$checkIdBilling->setFetchMode(PDO::FETCH_OBJ);
		$nnn = $checkIdBilling->rowCount();
		
		$ligne=$checkIdBilling->fetch();
		
		$idBilling = $ligne->id_bill;
		$lastDette = $ligne->dette;
		$lastAmountpaid = $ligne->amountpaid;
	}
	
	

	$updateIdBill=$connexion->prepare('UPDATE bills b SET b.numero=:num, b.codecashier=:codecash WHERE b.id_bill=:idbill');

	$updateIdBill->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['num'],
		'codecash'=>$_SESSION['codeCash']		
	));		
}



if(isset($_GET['finishbtn']))
{
	$createfacture = 1;
	/* if($_GET['createBill']==1)
	{
		createBN();
	}
	*/
	$selectbillnum = $connexion->prepare('SELECT * FROM bills WHERE numbill=:numbill');
	$selectbillnum->execute(array(
		'numbill'=>$numbill
	));
	$selectbillnum->setFetchMode(PDO::FETCH_OBJ);
	$nbre = $selectbillnum->rowCount();
	$ligneselectebillnum = $selectbillnum->fetch();



	//$idBilling=$_GET['idbill'];
	$idBilling = $ligneselectebillnum->id_bill;
	$idconsu=$_GET['idconsu'];
	$vouchernum=$_GET['vouchernum'];
	$codecash=$_SESSION['codeCash'];
	
	/*----------Update Bill----------------*/


	$updateIdBill=$connexion->prepare('UPDATE bills b SET b.vouchernum=:vouchernum, b.codecashier=:codecash WHERE b.id_bill=:idbill');

	$updateIdBill->execute(array(
	'idbill'=>$idBilling,
	'codecash'=>$codecash,
	'vouchernum'=>$vouchernum
	
	))or die( print_r($connexion->errorInfo()));
		
				
	
	/*----------Update Consult----------------*/
		
	$selectconsuBill = $connexion->prepare('SELECT * FROM consultations c WHERE id_consu=:idconsu AND c.id_factureConsult IS NULL');
	$selectconsuBill->execute(array(
		'idconsu'=>$idconsu
	));
	$selectconsuBill->setFetchMode(PDO::FETCH_OBJ);
	$nbreConsuBill = $selectconsuBill->rowCount();
	if ($nbreConsuBill != 0) {
		$updateIdFactureConsult=$connexion->prepare('UPDATE consultations c SET c.id_factureConsult=:idbill, c.codecashier=:codecashier WHERE c.id_consu=:idconsu AND c.numero=:num');

		$updateIdFactureConsult->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['num'],
		'idconsu'=>$idconsu,
		'codecashier'=>$_SESSION['codeCash']
		
		));
	}
		
		
	/*----------Update Med_Consult----------------*/
	
	$updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult mc SET mc.id_factureMedConsu=:idbill, mc.codecashier=:codecashier WHERE mc.id_consuMed=:idconsu AND mc.numero=:num AND (mc.id_factureMedConsu=0 OR mc.id_factureMedConsu IS NULL)');

	$updateIdFactureMedConsult->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	));
	
	
	
	/*----------Update Med_Surge----------------*/
	
	$updateIdFactureMedSurge=$connexion->prepare('UPDATE med_surge ms SET ms.id_factureMedSurge=:idbill, ms.codecashier=:codecashier WHERE ms.id_consuSurge=:idconsu AND ms.numero=:num AND (ms.id_factureMedSurge=0 OR ms.id_factureMedSurge IS NULL)');

	$updateIdFactureMedSurge->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash'] 
	
	));
	
	
	
	/*----------Update Med_Kine----------------*/
	
	$updateIdFactureMedKine=$connexion->prepare('UPDATE med_kine mk SET mk.id_factureMedKine=:idbill, mk.codecashier=:codecashier WHERE mk.id_consuKine=:idconsu AND mk.numero=:num AND (mk.id_factureMedKine=0 OR mk.id_factureMedKine IS NULL)');

	$updateIdFactureMedKine->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash'] 
	
	));



	/*----------Update Med_Ortho----------------*/

	$updateIdFactureMedOrtho=$connexion->prepare('UPDATE med_ortho mo SET mo.id_factureMedOrtho=:idbill, mo.codecashier=:codecashier WHERE mo.id_consuOrtho=:idconsu AND mo.numero=:num AND (mo.id_factureMedOrtho=0 OR mo.id_factureMedOrtho IS NULL)');

	$updateIdFactureMedOrtho->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']

	));
	

	/*----------Update Med_psy----------------*/

	$updateIdFactureMedPsycho=$connexion->prepare('UPDATE med_psy mp SET mp.id_factureMedPsy=:idbill, mp.codecashier=:codecashier WHERE mp.id_consuPSy=:idconsu AND mp.numero=:num AND (mp.id_factureMedPsy=0 OR mp.id_factureMedPsy IS NULL)');

	$updateIdFactureMedPsycho->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']

	));
	
	
	
	/*----------Update Med_Inf----------------*/
	
	$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf mi SET mi.id_factureMedInf=:idbill, mi.codecashier=:codecashier WHERE mi.id_consuInf=:idconsu AND mi.numero=:num AND (mi.id_factureMedInf=0 OR mi.id_factureMedInf IS NULL)');

	$updateIdFactureMedInf->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash'] 
	
	));
	
	
	
	/*----------Update Med_Labo----------------*/
	
	$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo ml SET ml.id_factureMedLabo=:idbill, ml.codecashier=:codecashier WHERE ml.id_consuLabo=:idconsu AND ml.numero=:num AND (ml.id_factureMedLabo=0 OR ml.id_factureMedLabo IS NULL)');

	$updateIdFactureMedLabo->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	));
	
	
	
	/*----------Update Med_Radio----------------*/
	
	$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_radio mr SET mr.id_factureMedRadio=:idbill, mr.codecashier=:codecashier WHERE mr.id_consuRadio=:idconsu AND mr.numero=:num AND (mr.id_factureMedRadio=0 OR mr.id_factureMedRadio IS NULL)');

	$updateIdFactureMedLabo->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	));
	
	
	
	/*----------Update Med_Consom----------------*/
	
	$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom mco SET mco.id_factureMedConsom=:idbill, mco.codecashier=:codecashier WHERE mco.id_consuConsom=:idconsu AND mco.numero=:num AND (mco.id_factureMedConsom=0 OR mco.id_factureMedConsom IS NULL)');

	$updateIdFactureMedConsom->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	));
	
	
	
	/*----------Update Med_Medoc----------------*/
	
	$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc mdo SET mdo.id_factureMedMedoc=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_consuMedoc=:idconsu AND mdo.numero=:num AND (mdo.id_factureMedMedoc=0 OR mdo.id_factureMedMedoc IS NULL)');

	$updateIdFactureMedMedoc->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	));
	
	
	
}
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<script type="text/javascript">
	window.history.forward();
</script>
<head>
	<title><?php echo 'Bill#'.$numbill; ?></title>

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

	.flashing {
	  position: relative;
	  animation-name: shake;
	  animation-duration: 1.5s;
	  animation-iteration-count: infinite;
	  animation-timing-function: ease-in;
	  cursor: pointer;
	}

	.flashing :hover {
	  animation-name: shakeAnim;
	}	

	@keyframes shakeAnim {
	  0% {left: 0}
	  1% {left: -3px}
	  2% {left: 5px}
	  3% {left: -8px}
	  4% {left: 8px}
	  5% {left: -5px}
	  6% {left: -3px}
	  7% {left:	-5px}
	  8% {left: -3px}
	  9% {left: -5px}
	  10% {left: 0}
	}

	@keyframes shake {
	  0% {left: 0}
	  1% {left: -3px}
	  2% {left: 5px}
	  3% {left: -8px}
	  4% {left: 8px}
	  5% {left: -5px}
	  6% {left: 3px}
	  7% {left: -3}
	  8% {left: -5px}
	  9% {left: -3px}
	  10% {left: 0}
	}
		body{
		font-family: Century Gothic;
	}
</style>
	
</head>



<body>

	<?php
	if(isset($_GET['finishbtn']))
	{
	?>
		<body onload="window.print(); start()">
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
		$code->setLabel('# '.$numbill.' #');
		$code->parse(''.$numbill.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecashier.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	<div >
		
		<table style="width:100%">

			<td>

			
			<table class="printPreview" style="margin-top:2px;">
				
				<?php
				$idmed = $_GET['idmed'];
	
				if($idmed)
				{
					
					$resultIdDoc=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
					$resultIdDoc->execute(array(
					'operation'=>$idmed
					));
					$resultIdDoc->setFetchMode(PDO::FETCH_OBJ);
					
					
					if($ligneIdDoc=$resultIdDoc->fetch())
					{
						$codeDoc=$ligneIdDoc->codemedecin;
						$fullnameDoc=$ligneIdDoc->nom_u.' '.$ligneIdDoc->prenom_u;
					}
				}
				
				$TotalGnl = 0;
				
				
					/*---------Billing Info Patient----------*/
				
				$resultatsPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
				$resultatsPatient->execute(array(
				'operation'=>$numPa
				));
				
				$resultatsPatient->setFetchMode(PDO::FETCH_OBJ);
				
				if($lignePatient=$resultatsPatient->fetch())
				{
					
					$fullname= $lignePatient->full_name;
					$bill= $lignePatient->bill;
					$idassurance=$lignePatient->id_assurance;
					$profession=$lignePatient->profession;
					
					@$numeroPa=$lignePatient->numero;
					
					@$uappercent= 100 - $lignePatient->bill;
					
					$percentpatient= 100 - $uappercent;

					if($lignePatient->sexe=="M")
					{
						$sexe = "Male";
					}elseif($lignePatient->sexe=="F"){
						$sexe = "Female";
					}else{
						$sexe="";
					}
			
					$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
					$resultAdresse->execute(array(
					'idProv'=>$lignePatient->province,
					'idDist'=>$lignePatient->district,
					'idSect'=>$lignePatient->secteur
					));
					
					$resultAdresse->setFetchMode(PDO::FETCH_OBJ);

					$comptAdress=$resultAdresse->rowCount();
					
					if($ligneAdresse=$resultAdresse->fetch())
					{
						if($ligneAdresse->id_province == $lignePatient->province)
						{
							$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
							
						}
					}elseif($lignePatient->autreadresse!=""){
							$adresse=$lignePatient->autreadresse;
					}else{
						$adresse="";
					}
					
					$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
					
					$resultAssurance->execute(array(
					'assuId'=>$lignePatient->id_assurance
					));
					
					$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

					if($ligneAssu=$resultAssurance->fetch())
					{
						if($ligneAssu->id_assurance == $lignePatient->id_assurance)
						{
							$idassu=$ligneAssu->id_assurance;
							$nomassurance=$ligneAssu->nomassurance;
							$numpolicebill=$lignePatient->numeropolice;
							$idcardbill= $lignePatient->carteassuranceid;
							$adherentbill=$lignePatient->adherent;
						}
					}
				}
				?>

					
					
			
			
		
						<?php
				
				$comptAssuUpdate=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
				
				$comptAssuUpdate->setFetchMode(PDO::FETCH_OBJ);
				
				$assuCount = $comptAssuUpdate->rowCount();
				
				for($i=1;$i<=$assuCount;$i++)
				{
					
					$getAssuUpdate=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
					$getAssuUpdate->execute(array(
					'idassu'=>$idassu
					));
					
					$getAssuUpdate->setFetchMode(PDO::FETCH_OBJ);

					if($ligneNomAssuUpdate=$getAssuUpdate->fetch())
					{
						$presta_assuUpdate='prestations_'.strtolower($ligneNomAssuUpdate->nomassurance);
					}
				}
				
				
				if(isset($_POST['vouchernum']))
				{
					$vouchernum=$_POST['vouchernum'];
				}else{
					if(isset($_GET['vouchernum']))
					{
						$vouchernum=$_GET['vouchernum'];
					}else{
						$vouchernum='';
					}
				}
				
					/*----------Update Bill----------------*/
					
					$idBilling=$_GET['idbill'];
					$updateIdBill=$connexion->prepare('UPDATE bills b SET b.vouchernum=:vouchernum WHERE b.id_bill=:idbill');

					$updateIdBill->execute(array(
					'idbill'=>$idBilling,
					'vouchernum'=>$vouchernum
					
					))or die( print_r($connexion->errorInfo()));
						
				
						if(isset($_POST['prixtypeconsult']))
						{
							
							$idprestatc = array();
							$prixtc = array();
							/*$prixtcCCO = array();*/
							$addtc = array();
							$idtc = array();
							$autretc = array();

							foreach($_POST['idprestaConsu'] as $mc)
							{
								$idprestatc[] = $mc;
							}
							
							foreach($_POST['prixtypeconsult'] as $valmc)
							{
								$prixtc[] = $valmc;
							}
							
							/*foreach($_POST['prixtypeconsultCCO'] as $valmcCCO)
							{
								$prixtcCCO[] = $valmcCCO;
							}*/
							
							foreach($_POST['percentTypeConsu'] as $valuemc)
							{
								$addtc[] = $valuemc;
							}
							
							foreach($_POST['idConsu'] as $valeurmc)
							{
								$idtc[] = $valeurmc;
							}
							
							foreach($_POST['autretypeconsult'] as $autrevaluemc)
							{
								$autretc[] = $autrevaluemc;
							}
							
							for($i=0;$i<sizeof($addtc);$i++)
							{
								// echo $addtc[$i].'_'.$idtc[$i].'_('.$prixtc[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestatc[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE consultations c SET c.id_assuConsu='.$_GET['idassu'].',c.insupercent='.$addtc[$i].',c.prixtypeconsult='.$prixtc[$i].' WHERE c.id_consu='.$idtc[$i].'');

										if ($presta_assuUpdate !="prestations_PRIVATE" AND $addtc[$i]!=100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixtc[$i].' WHERE p.id_prestation='.$idprestatc[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixtc[$i].',statupresta=1 WHERE p.id_prestation='.$idprestatc[$i].'');
											}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
				                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixtc[$i].',statupresta=1 WHERE p.id_prestation='.$idprestatc[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addtc[$i]==100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixtc[$i].',statupresta=2 WHERE p.id_prestation='.$idprestatc[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixtc[$i].',statupresta=2 WHERE p.id_prestation='.$idprestatc[$i].'');
											}
										}

									}
									
								}else{
								
									$results=$connexion->query("SELECT *FROM consultations c WHERE c.id_consu='.$idtc[$i].'");
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE consultations c SET c.id_assuConsu='.$_GET['idassu'].',c.insupercent='.$addtc[$i].',c.prixautretypeconsult=0,c.prixautretypeconsult='.$prixtc[$i].' WHERE c.id_consu='.$idtc[$i].'');
										
									}
								}

							}
						}


						if (isset($_GET['finishbtn'])) {
							$conditionConsu = "";
							if (isset($_POST['savebill']) && isset($_POST['idConsu'])) {
								$idconsu2 = array();

								foreach($_POST['idConsu'] as $valeurmc)
								{
									$idconsu2[] = $valeurmc;
								}
								$conditionConsu .= " AND (";
								for ($i=0; $i < sizeof($idconsu2); $i++) { 
									if ($i == 0) {
										$conditionConsu .= ' c.id_consu='.$idconsu2[$i].'';
									}else{
										$conditionConsu .= ' OR c.id_consu='.$idconsu2[$i].'';
									}
								}
								$conditionConsu .= " )";
							}else{
								$conditionConsu .= ' AND c.id_factureConsult!='.$_GET['idbill'].''; 
							}
							$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.numero=:num'.$conditionConsu.' ORDER BY c.id_consu');
						}else{
							$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.numero=:num AND c.id_factureConsult IS NULL ORDER BY c.id_consu');
						}
						
						$resultConsult->execute(array(
						'consuId'=>$_GET['idconsu'],
						'num'=>$numPa
						));

						$resultConsult->setFetchMode(PDO::FETCH_OBJ);

						$comptConsult=$resultConsult->rowCount();
						
						
						
						
						if(isset($_POST['idpresta']))
						{
							
							$idprestamc = array();
							$prixmc = array();
							/*$prixmcCCO = array();*/
							$add = array();
							$idmc = array();
							$autremc = array();

							foreach($_POST['idpresta'] as $mc)
							{
								$idprestamc[] = $mc;
							}
							
							foreach($_POST['prixprestaConsu'] as $valmc)
							{
								$prixmc[] = $valmc;
							}
							
							/*foreach($_POST['prixprestaConsuCCO'] as $valmcCCO)
							{
								$prixmcCCO[] = $valmcCCO;
							}*/
							
							foreach($_POST['percentConsu'] as $valuemc)
							{
								$add[] = $valuemc;
							}
							
							foreach($_POST['idmedConsu'] as $valeurmc)
							{
								$idmc[] = $valeurmc;
							}
							
							foreach($_POST['autreConsu'] as $autrevaluemc)
							{
								$autremc[] = $autrevaluemc;
							}
							
							for($i=0;$i<sizeof($add);$i++)
							{
								// echo $add[$i].'_'.$idmc[$i].'_('.$prixmc[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamc[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu='.$prixmc[$i].',mc.prixautreConsu=0 WHERE mc.id_medconsu='.$idmc[$i].'');

										if ($presta_assuUpdate !="prestations_PRIVATE" AND $add[$i]!=100){
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmc[$i].' WHERE p.id_prestation='.$idprestamc[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmc[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamc[$i].'');
											}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
				                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmc[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamc[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $add[$i]==100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmc[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamc[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmc[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamc[$i].'');
											}
										}
									}

									
								}else{
								
									$results=$connexion->query('SELECT *FROM med_consult mc WHERE mc.id_medconsu='.$idmc[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu=0,mc.prixautreConsu='.$prixmc[$i].' WHERE mc.id_medconsu='.$idmc[$i].'');
										
									}
								}

							}
						}
						
						if (isset($_GET['finishbtn'])) {
							$conditionMedConsu = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedConsu'])) {
								$idmedConsu2 = array();

								foreach($_POST['idmedConsu'] as $valeurmc)
								{
									$idmedConsu2[] = $valeurmc;
								}
								$conditionMedConsu .= ' AND (';
								for ($i=0; $i < sizeof($idmedConsu2) ; $i++) { 
									if ($i == 0) {
										$conditionMedConsu .= ' mc.id_medconsu='.$idmedConsu2[$i].'';
									}else{
										$conditionMedConsu .= ' OR mc.id_medconsu='.$idmedConsu2[$i].'';
									}
								}
								$conditionMedConsu .= ' )';
							}else{
								$conditionMedConsu .= ' AND mc.id_factureMedConsu!='.$_GET['idbill'].'';
							}
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_consuMed=:idconsu AND mc.dateconsu!="0000-00-00"'.$conditionMedConsu.' ORDER BY mc.id_medconsu');
						}else{
							$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_consuMed=:idconsu AND mc.dateconsu!="0000-00-00" AND mc.id_factureMedConsu=0 ORDER BY mc.id_medconsu');
						}

						$resultMedConsult->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsult=$resultMedConsult->rowCount();
					
						if($comptMedConsult!=0)
						{
						?>
						<?php
						}
						
						
						
						if(isset($_POST['idprestaSurge']))
						{
							$idprestams = array();
							$prixms = array();
							$addSurge = array();
							$idms = array();
							$autrems = array();

							foreach($_POST['idprestaSurge'] as $ms)
							{
								$idprestams[] = $ms;
							}
							
							foreach($_POST['prixprestaSurge'] as $valms)
							{
								$prixms[] = $valms;
							}
							
							foreach($_POST['percentSurge'] as $valeurSurge)
							{
								$addSurge[] = $valeurSurge;
							}
							
							foreach($_POST['idmedSurge'] as $valeurms)
							{
								$idms[] = $valeurms;
							}
							
							foreach($_POST['autreSurge'] as $autrevaluems)
							{
								$autrems[] = $autrevaluems;
							}
							
							
							for($i=0;$i<sizeof($addSurge);$i++)
							{
								
								// echo $addSurge[$i].'_'.$idms[$i].'_('.$prixms[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestams[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_surge ms SET ms.id_assuSurge='.$_GET['idassu'].',ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge='.$prixms[$i].',ms.prixautrePrestaS=0 WHERE ms.id_medsurge='.$idms[$i].'');

										if ($presta_assuUpdate !="prestations_PRIVATE" AND $addSurge[$i]!=100){
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixms[$i].' WHERE p.id_prestation='.$idprestams[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixms[$i].',statupresta=1 WHERE p.id_prestation='.$idprestams[$i].'');
											}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
				                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixms[$i].',statupresta=1 WHERE p.id_prestation='.$idprestams[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addSurge[$i]==100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixms[$i].',statupresta=2 WHERE p.id_prestation='.$idprestams[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixms[$i].',statupresta=2 WHERE p.id_prestation='.$idprestams[$i].'');
											}
										}
									}
								}else{
								
									$results=$connexion->query('SELECT *FROM med_surge ms WHERE ms.id_medsurge='.$idms[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_surge ms SET ms.id_assuSurge='.$_GET['idassu'].',ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge=0,ms.prixautrePrestaS='.$prixms[$i].' WHERE ms.id_medsurge='.$idms[$i].'');
										
									}
								}
								
							}
						}
					
						if (isset($_GET['finishbtn'])) {
							$conditionMedSurge = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedSurge'])) {
								$idmedSurge2 = array();

								foreach($_POST['idmedSurge'] as $valeurms)
								{
									$idmedSurge2[] = $valeurms;
								}
								$conditionMedSurge .= ' AND (';
								for ($i=0; $i < sizeof($idmedSurge2) ; $i++) { 
									if ($i == 0) {
										$conditionMedSurge .= ' ms.id_medsurge='.$idmedSurge2[$i].'';
									}else{
										$conditionMedSurge .= ' OR ms.id_medsurge='.$idmedSurge2[$i].'';
									}
								}
								$conditionMedSurge .= ' )';
							}else{
								$conditionMedSurge .= ' AND ms.id_factureMedSurge!='.$_GET['idbill'].'';
							}
							$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_consuSurge=:idconsu '.$conditionMedSurge.' ORDER BY ms.id_medsurge');
						}else{
							$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_consuSurge=:idconsu AND ms.id_factureMedSurge=0 ORDER BY ms.id_medsurge');
						}

						$resultMedSurge->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

						$comptMedSurge=$resultMedSurge->rowCount();
					
					

                        if(isset($_POST['idprestaKine']))
                        {
                            $idprestamk = array();
                            $prixmk = array();
                            $addKine = array();
                            $idmk = array();
                            $autremk = array();

                            foreach($_POST['idprestaKine'] as $mk)
                            {
                                $idprestamk[] = $mk;
                            }

                            foreach($_POST['prixprestaKine'] as $valmk)
                            {
                                $prixmk[] = $valmk;
                            }

                            foreach($_POST['percentKine'] as $valeurKine)
                            {
                                $addKine[] = $valeurKine;
                            }

                            foreach($_POST['idmedKine'] as $valeurmk)
                            {
                                $idmk[] = $valeurmk;
                            }

                            foreach($_POST['autreKine'] as $autrevaluemk)
                            {
                                $autremk[] = $autrevaluemk;
                            }


                            for($i=0;$i<sizeof($addKine);$i++)
                            {

                                // echo $addKine[$i].'_'.$idmk[$i].'_('.$prixmk[$i].')<br/>';

                                $result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamk[$i].'');

                                $result->setFetchMode(PDO::FETCH_OBJ);

                                $comptPresta=$result->rowCount();

                                if($comptPresta!=0)
                                {
                                	if($ligne=$result->fetch())
									{
	                                    $updatepercent=$connexion->query('UPDATE med_kine mk SET mk.id_assuKine='.$_GET['idassu'].',mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine='.$prixmk[$i].',mk.prixautrePrestaO=0 WHERE mk.id_medkine='.$idmk[$i].'');

	                                     if ($presta_assuUpdate !="prestations_PRIVATE" AND $addKine[$i]!=100) {
				                        	$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmk[$i].' WHERE p.id_prestation='.$idprestamk[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmk[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamk[$i].'');
											}
											
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
				                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmk[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamk[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addKine[$i]==100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmk[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamk[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmk[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamk[$i].'');
											}
										}
									}
                                }else{

                                    $results=$connexion->query('SELECT *FROM med_kine mk WHERE mk.id_medkine='.$idmk[$i].'');

                                    $results->setFetchMode(PDO::FETCH_OBJ);

                                    if($ligne=$results->fetch())
                                    {
                                        $updatepercent=$connexion->query('UPDATE med_kine mk SET mk.id_assuKine='.$_GET['idassu'].',mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine=0,mk.prixautrePrestaK='.$prixmk[$i].' WHERE mk.id_medkine='.$idmk[$i].'');

                                    }
                                }

                            }
                        }

                        if (isset($_GET['finishbtn'])) {
							$conditionMedKine = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedKine'])) {
								$idmedKine2 = array();

								foreach($_POST['idmedKine'] as $valeurmk)
	                            {
	                                $idmedKine2[] = $valeurmk;
	                            }
								$conditionMedKine .= ' AND (';
								for ($i=0; $i < sizeof($idmedKine2) ; $i++) { 

									if ($i == 0) {
										$conditionMedKine .= ' mk.id_medkine='.$idmedKine2[$i].'';
									}else{
										$conditionMedKine .= ' OR mk.id_medkine='.$idmedKine2[$i].'';
									}
									
								}
								$conditionMedKine .= ' )';
							}else{
								$conditionMedKine .= ' AND mk.id_factureMedKine!='.$_GET['idbill'].'';
							}
							$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_consuKine=:idconsu'.$conditionMedKine.' ORDER BY mk.id_medkine');
						}else{
							$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_consuKine=:idconsu AND mk.id_factureMedKine=0 ORDER BY mk.id_medkine');
						}

                        $resultMedKine->execute(array(
                            'num'=>$numPa,
                            'idconsu'=>$_GET['idconsu']
                        ));

                        $resultMedKine->setFetchMode(PDO::FETCH_OBJ);

                        $comptMedKine=$resultMedKine->rowCount();

                        if($comptMedKine!=0)
                        {
                            ?>
                            <th style="text-align:left">
                                Physiotherapy
                            </th>
                            <?php
                        }


                        if(isset($_POST['idprestaOrtho']))
						{
							$idprestamo = array();
							$prixmo = array();
							$addOrtho = array();
							$idmo = array();
							$autremo = array();

							foreach($_POST['idprestaOrtho'] as $mo)
							{
								$idprestamo[] = $mo;
							}
							
							foreach($_POST['prixprestaOrtho'] as $valmo)
							{
								$prixmo[] = $valmo;
							}
							
							foreach($_POST['percentOrtho'] as $valeurOrtho)
							{
								$addOrtho[] = $valeurOrtho;
							}
							
							foreach($_POST['idmedOrtho'] as $valeurmo)
							{
								$idmo[] = $valeurmo;
							}
							
							foreach($_POST['autreOrtho'] as $autrevaluemo)
							{
								$autremo[] = $autrevaluemo;
							}
							
							
							for($i=0;$i<sizeof($addOrtho);$i++)
							{
								
								// echo $addOrtho[$i].'_'.$idmo[$i].'_('.$prixmo[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamo[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_ortho mo SET mo.id_assuOrtho='.$_GET['idassu'].',mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho='.$prixmo[$i].',mo.prixautrePrestaO=0 WHERE mo.id_medortho='.$idmo[$i].'');
										
										if ($presta_assuUpdate !="prestations_PRIVATE" AND $addOrtho[$i]!=100) {
				                        	$status  = $ligne->statupresta;
				                        	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmo[$i].' WHERE p.id_prestation='.$idprestamo[$i].'');
				                        	}else{
				                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamo[$i].'');
				                        	}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
				                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamo[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addOrtho[$i]==100) {
											$status  = $ligne->statupresta;
				                        	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamo[$i].'');
				                        	}else{
				                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamo[$i].'');
				                        	}
										}
									}
								}else{
								
									$results=$connexion->query('SELECT *FROM med_ortho mo WHERE mo.id_medortho='.$idmo[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_ortho mo SET mo.id_assuOrtho='.$_GET['idassu'].',mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho=0,mo.prixautrePrestaO='.$prixmo[$i].' WHERE mo.id_medortho='.$idmo[$i].'');
										
									}
								}
								
							}
						}
					
						if (isset($_GET['finishbtn'])) {
							$conditionMedOrtho = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedOrtho'])) {
								$idmedOrtho2 = array();

								foreach($_POST['idmedOrtho'] as $valeurmo)
								{
									$idmedOrtho2[] = $valeurmo;
								}
								$conditionMedOrtho .= ' AND (';
								for ($i=0; $i < sizeof($idmedOrtho2) ; $i++) { 
									if ($i == 0) {
										$conditionMedOrtho .= ' mo.id_medOrtho='.$idmedOrtho2[$i].'';
									}else{
										$conditionMedOrtho .= ' OR mo.id_medOrtho='.$idmedOrtho2[$i].'';
									}
									
								}
								$conditionMedOrtho .= ' )';
							}else{
								$conditionMedOrtho .= ' AND mo.id_factureMedOrtho!='.$_GET['idbill'].'';
							}
							$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_consuOrtho=:idconsu'.$conditionMedOrtho.' ORDER BY mo.id_medortho');
						}else{
							$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_consuOrtho=:idconsu AND mo.id_factureMedOrtho=0 ORDER BY mo.id_medortho');
						}

						$resultMedOrtho->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

						$comptMedOrtho=$resultMedOrtho->rowCount();
					
						if($comptMedOrtho!=0)
						{
						?>
						<?php
						}


                        if(isset($_POST['idprestaPyscho']))
						{
							$idprestapsy = array();
							$prixpsy = array();
							$addPyscho = array();
							$idpsy = array();
							$autrepsy = array();

							foreach($_POST['idprestaPyscho'] as $psy)
							{
								$idprestapsy[] = $psy;
							}
							
							foreach($_POST['prixprestaPyscho'] as $valpsy)
							{
								$prixpsy[] = $valpsy;
							}
							
							foreach($_POST['percentPyscho'] as $valeurPyscho)
							{
								$addPyscho[] = $valeurPyscho;
							}
							
							foreach($_POST['idmedPyscho'] as $valeurpsy)
							{
								$idpsy[] = $valeurpsy;
							}
							
							foreach($_POST['autrePyscho'] as $autrevaluepsy)
							{
								$autrepsy[] = $autrevaluepsy;
							}
							
							
							for($i=0;$i<sizeof($addPyscho);$i++)
							{
								
								// echo $addPyscho[$i].'_'.$idpsy[$i].'_('.$prixpsy[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestapsy[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_psy mp SET mp.id_assuPsy='.$_GET['idassu'].',mp.insupercentPsy='.$addPyscho[$i].',mp.prixprestation='.$prixpsy[$i].',mp.prixautrePrestaM=0 WHERE mp.id_medpsy='.$idpsy[$i].'');
										
										if ($presta_assuUpdate !="prestations_PRIVATE" AND $addPyscho[$i]!=100) {
				                        	$status  = $ligne->statupresta;
				                        	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixpsy[$i].' WHERE p.id_prestation='.$idprestapsy[$i].'');
				                        	}else{
				                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixpsy[$i].',statupresta=1 WHERE p.id_prestation='.$idprestapsy[$i].'');
				                        	}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
				                        	$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixpsy[$i].',statupresta=1 WHERE p.id_prestation='.$idprestapsy[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addPyscho[$i]==100) {
											$status  = $ligne->statupresta;
				                        	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixpsy[$i].',statupresta=2 WHERE p.id_prestation='.$idprestapsy[$i].'');
				                        	}else{
				                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixpsy[$i].',statupresta=2 WHERE p.id_prestation='.$idprestapsy[$i].'');
				                        	}
										}
									}
								}else{
								
									$results=$connexion->query('SELECT *FROM med_psy mp WHERE mp.id_medpsy='.$idpsy[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_psy mp SET mp.id_assuPsy='.$_GET['idassu'].',mp.insupercentPsy='.$addPyscho[$i].',mp.prixprestation=0,mp.prixautrePrestaM='.$prixpsy[$i].' WHERE mp.id_medpsy='.$idpsy[$i].'');
										
									}
								}
								
							}
						}
					
						if (isset($_GET['finishbtn'])) {
							$conditionMedPsy = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedPyscho'])) {
								$idmedPsy2 = array();

								foreach($_POST['idmedPyscho'] as $valeurpsy)
								{
									$idmedPsy2[] = $valeurpsy;
								}
								$conditionMedPsy .= ' AND (';
								for ($i=0; $i < sizeof($idmedPsy2) ; $i++) { 
									if ($i == 0) {
										$conditionMedPsy .= ' mp.id_medpsy='.$idmedPsy2[$i].'';
									}else{
										$conditionMedPsy .= ' OR mp.id_medpsy='.$idmedPsy2[$i].'';
									}
								}
								$conditionMedPsy .= ' )';
							}else{
								$conditionMedPsy .= ' AND mp.id_factureMedPsy!='.$_GET['idbill'].'';
							}
							$resultMedPsy=$connexion->prepare('SELECT *FROM med_psy mp, patients p WHERE p.numero=:num AND p.numero=mp.numero AND mp.numero=:num AND mp.id_consuPSy=:idconsu'.$conditionMedPsy.' ORDER BY mp.id_medpsy');
						}else{
							$resultMedPsy=$connexion->prepare('SELECT *FROM med_psy mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_consuPSy=:idconsu AND mo.id_factureMedPsy=0 ORDER BY mo.id_medpsy');
						}

						$resultMedPsy->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedPsy->setFetchMode(PDO::FETCH_OBJ);

						$comptMedPsy=$resultMedPsy->rowCount();
					
						if($comptMedPsy!=0)


						
						if(isset($_POST['idprestaInf']))
						{
							$idprestami = array();
							$prixmi = array();
							$addInf = array();
							$idmi = array();
							$autremi = array();

							foreach($_POST['idprestaInf'] as $mi)
							{
								$idprestami[] = $mi;
							}
							
							foreach($_POST['prixprestaInf'] as $valmi)
							{
								$prixmi[] = $valmi;
							}
							
							foreach($_POST['percentInf'] as $valeurInf)
							{
								$addInf[] = $valeurInf;
							}
							
							foreach($_POST['idmedInf'] as $valeurmi)
							{
								$idmi[] = $valeurmi;
							}
							
							foreach($_POST['autreInf'] as $autrevaluemi)
							{
								$autremi[] = $autrevaluemi;
							}
							
							
							for($i=0;$i<sizeof($addInf);$i++)
							{
								
								// echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestami[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();

								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.prixautrePrestaM=0 WHERE mi.id_medinf='.$idmi[$i].'');
										
										if ($presta_assuUpdate !="prestations_PRIVATE" AND $addInf[$i]!=100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
				                    			//echo "string";
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmi[$i].' WHERE p.id_prestation='.$idprestami[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmi[$i].',statupresta=1 WHERE p.id_prestation='.$idprestami[$i].'');
											}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
			                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmi[$i].',statupresta=1 WHERE p.id_prestation='.$idprestami[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addInf[$i]==100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmi[$i].',statupresta=2 WHERE p.id_prestation='.$idprestami[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmi[$i].',statupresta=2 WHERE p.id_prestation='.$idprestami[$i].'');
											}
										}
									}

								}else{
								
									$results=$connexion->query('SELECT *FROM med_inf mi WHERE mi.id_medinf='.$idmi[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixautrePrestaM='.$prixmi[$i].' WHERE mi.id_medinf='.$idmi[$i].'');
										
									}
								}
								
							}
						}
					
						if (isset($_GET['finishbtn'])) {
							$conditionMedInf = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedInf'])) {
								$idmedInf2 = array();

								foreach($_POST['idmedInf'] as $valeurmi)
								{
									$idmedInf2[] = $valeurmi;
								}
								$conditionMedInf .= ' AND (';
								for ($i=0; $i < sizeof($idmedInf2) ; $i++) { 
									if ($i == 0) {
										$conditionMedInf .= ' mi.id_medinf='.$idmedInf2[$i].'';
									}else{
										$conditionMedInf .= ' or mi.id_medinf='.$idmedInf2[$i].'';
									}
									
								}
								$conditionMedInf .= ' )';
							}else{
								$conditionMedInf .= ' AND mi.id_factureMedInf!='.$_GET['idbill'].'';
							}
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_consuInf=:idconsu'.$conditionMedInf.' ORDER BY mi.id_medinf');
						}else{
							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_consuInf=:idconsu AND mi.id_factureMedInf=0 ORDER BY mi.id_medinf');
						}

						$resultMedInf->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

						$comptMedInf=$resultMedInf->rowCount();		

						
						
						
						if(isset($_POST['idprestaLab']))
						{
							$idprestaml = array();
							$prixml = array();
							$addLab = array();
							$idml = array();
							$autreml = array();


							foreach($_POST['idprestaLab'] as $ml)
							{
								$idprestaml[] = $ml;
							}
							
							foreach($_POST['prixprestaLab'] as $valml)
							{
								$prixml[] = $valml;
							}
							
							
							foreach($_POST['percentLab'] as $valeurLab)
							{
								$addLab[] = $valeurLab;
							}
							
							foreach($_POST['idmedLab'] as $valeurml)
							{
								$idml[] = $valeurml;
							}
							
							foreach($_POST['autreLab'] as $autrevalueml)
							{
								$autreml[] = $autrevalueml;
							}
							
							for($i=0;$i<sizeof($addLab);$i++)
							{
								
								// echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestaml[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.prixautreExamen=0 WHERE ml.id_medlabo='.$idml[$i].'');
										
										if (($presta_assuUpdate !="prestations_PRIVATE" AND $addLab[$i]!=100) OR $presta_assuUpdate == "prestations_PRIVATE" ) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixml[$i].' WHERE p.id_prestation='.$idprestaml[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixml[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaml[$i].'');
											}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
			                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixml[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaml[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addLab[$i]==100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixml[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaml[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixml[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaml[$i].'');
											}
										}
									}
								}else{
								
									$results=$connexion->query('SELECT *FROM med_labo ml WHERE ml.id_medlabo='.$idml[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa=0,ml.prixautreExamen='.$prixml[$i].' WHERE ml.id_medlabo='.$idml[$i].'');
										
									}
								}
							}
						}
						
						if (isset($_GET['finishbtn'])) {
							$conditionMedLab = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedLab'])) {
								$idmedLab2 = array();

								foreach($_POST['idmedLab'] as $valeurml)
								{
									$idmedLab2[] = $valeurml;
								}
								$conditionMedLab .= ' AND (';
								for ($i=0; $i < sizeof($idmedLab2) ; $i++) { 
									if ($i == 0) {
										$conditionMedLab .= ' ml.id_medlabo='.$idmedLab2[$i].'';
									}else{
										$conditionMedLab .= ' OR ml.id_medlabo='.$idmedLab2[$i].'';
									}
								}
								$conditionMedLab .= ')';
							}else{
								$conditionMedLab .= ' AND ml.id_factureMedLabo!='.$_GET['idbill'].'';
							}
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_consuLabo=:idconsu'.$conditionMedLab.' ORDER BY ml.id_medlabo');
						}else{
							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_consuLabo=:idconsu AND ml.id_factureMedLabo=0 ORDER BY ml.id_medlabo');
						}

						$resultMedLabo->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

						$comptMedLabo=$resultMedLabo->rowCount();
						
						if($comptMedLabo!=0)
						{
						?>
						<?php
						}
						
						
						
						if(isset($_POST['idprestaRad']))
						{
							$idprestamr = array();
							$prixmr = array();
							$addRad = array();
							$idmr = array();
							$autremr = array();


							foreach($_POST['idprestaRad'] as $mr)
							{
								$idprestamr[] = $mr;
							}
							
							foreach($_POST['prixprestaRad'] as $valmr)
							{
								$prixmr[] = $valmr;
							}
							
							
							foreach($_POST['percentRad'] as $valeurRad)
							{
								$addRad[] = $valeurRad;
							}
							
							foreach($_POST['idmedRad'] as $valeurmr)
							{
								$idmr[] = $valeurmr;
							}
							
							foreach($_POST['autreRad'] as $autrevaluemr)
							{
								$autremr[] = $autrevaluemr;
							}
							
							for($i=0;$i<sizeof($addRad);$i++)
							{
								
								// echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';
							
								$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamr[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.prixautreRadio=0 WHERE mr.id_medradio='.$idmr[$i].'');
										
										if (($presta_assuUpdate !="prestations_PRIVATE" AND $addRad[$i]!=100) OR $presta_assuUpdate == "prestations_PRIVATE" ) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmr[$i].' WHERE p.id_prestation='.$idprestamr[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmr[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamr[$i].'');
											}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
			                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmr[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamr[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addRad[$i]==100) {
											$status  = $ligne->statupresta;
				                    		if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmr[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamr[$i].'');
											}else{
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmr[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamr[$i].'');
											}
										}
									}
								}else{
								
									$results=$connexion->query('SELECT *FROM med_radio mr WHERE mr.id_medradio='.$idmr[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixautreRadio='.$prixmr[$i].' WHERE mr.id_medradio='.$idmr[$i].'');
										
									}
								}
							}
						}
						
						if (isset($_GET['finishbtn'])) {
							$conditionMedRad = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedRad'])) {
								$idmedRad2 = array();

								foreach($_POST['idmedRad'] as $valeurmr)
								{
									$idmedRad2[] = $valeurmr;
								}
								$conditionMedRad .= ' AND (';
								for ($i=0; $i < sizeof($idmedRad2) ; $i++) { 
									if ($i == 0) {
										$conditionMedRad .= ' mr.id_medradio='.$idmedRad2[$i].'';
									}else{
										$conditionMedRad .= ' OR mr.id_medradio='.$idmedRad2[$i].'';
									}									
								}
								$conditionMedRad .= ' )';
							}else{
								$conditionMedRad .= ' AND mr.id_factureMedRadio!='.$_GET['idbill'].'';
							}
							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_consuRadio=:idconsu'.$conditionMedRad.' ORDER BY mr.id_medradio');
						}else{
							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_consuRadio=:idconsu AND mr.id_factureMedRadio=0 ORDER BY mr.id_medradio');
						}

						$resultMedRadio->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

						$comptMedRadio=$resultMedRadio->rowCount();
						
						if($comptMedRadio!=0)
						{
						?>
						<?php
						}
						
						
						
						if(isset($_POST['idprestaConsom']))
						{
							$idprestaconsom = array();
							$prixmco = array();
							$addConsom = array();
							$idmco = array();
							$autreconsom = array();
							$qteConsom = array();


							foreach($_POST['idprestaConsom'] as $consom)
							{
								$idprestaconsom[] = $consom;
							}
							
							foreach($_POST['prixprestaConsom'] as $valConsom)
							{
								$prixmco[] = $valConsom;
							}
							
							foreach($_POST['percentConsom'] as $valeurConsom)
							{
								$addConsom[] = $valeurConsom;
							}
							
							foreach($_POST['idmedConsom'] as $valeurmco)
							{
								$idmco[] = $valeurmco;
							}
							
							foreach($_POST['autreConsom'] as $autrevalueconsom)
							{
								$autreconsom[] = $autrevalueconsom;
							}

							foreach($_POST['quantityConsom'] as $valueConsom)
							{
								$qteConsom[] = $valueConsom;
							}
							
							for($i=0;$i<sizeof($addConsom);$i++)
							{
								// echo $addConsom[$i].'_'.$idmco[$i].'_('.$prixmco[$i].' : '.$qteConsom[$i].')<br/>';
								
								$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuUpdate.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation='.$idprestaconsom[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.prixautreConsom=0,mco.qteConsom='.$qteConsom[$i].' WHERE mco.id_medconsom='.$idmco[$i].'');
										
										if ($presta_assuUpdate !="prestations_PRIVATE" AND $addConsom[$i]!=100) {
											$status  = $ligne->statupresta;
					                    	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmco[$i].' WHERE p.id_prestation='.$idprestaconsom[$i].'');
					                    	}else{
					                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmco[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaconsom[$i].'');
					                    	}
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
			                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmco[$i].',statupresta=1 WHERE p.id_prestation='.$idprestaconsom[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addConsom[$i]==100) {
											
											$status  = $ligne->statupresta;
					                    	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmco[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaconsom[$i].'');
					                    	}else{
					                    		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmco[$i].',statupresta=2 WHERE p.id_prestation='.$idprestaconsom[$i].'');
					                    	}
										}
									}

								}else{
									
									$results=$connexion->query('SELECT *FROM med_consom mco WHERE mco.id_medconsom='.$idmco[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0mco.prixautreConsom='.$prixmco[$i].',mco.qteConsom='.$qteConsom[$i].' WHERE mco.id_medconsom='.$idmco[$i].'');
									}
								}
							}
						}
						
						if (isset($_GET['finishbtn'])) {
							$conditionMedConsom = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedConsom'])) {
								$idmedConsom2 = array();

								foreach($_POST['idmedConsom'] as $valeurmco)
								{
									$idmedConsom2[] = $valeurmco;
								}
								$conditionMedConsom .= ' AND (';
								for ($i=0; $i < sizeof($idmedConsom2) ; $i++) { 
									if ($i==0) {
										$conditionMedConsom .= ' mco.id_medconsom='.$idmedConsom2[$i].'';
									}else{
										$conditionMedConsom .= ' OR mco.id_medconsom='.$idmedConsom2[$i].'';
									}									
								}
								$conditionMedConsom .= ' )';
							}else{
								$conditionMedConsom .= ' AND mco.id_factureMedConsom!='.$_GET['idbill'].'';
							}
							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_consuConsom=:idconsu'.$conditionMedConsom.' ORDER BY mco.id_medconsom');
						}else{
							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_consuConsom=:idconsu AND mco.id_factureMedConsom=0 ORDER BY mco.id_medconsom');
						}

						$resultMedConsom->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

						$comptMedConsom=$resultMedConsom->rowCount();
					
						if($comptMedConsom!=0)
						{
						?>

						<?php
						}
						
						
						
						if(isset($_POST['idprestaMedoc']))
						{
							$idprestamedoc = array();
							$prixmdo = array();
							$addMedoc = array();
							$idmdo = array();
							$autremedoc = array();
							$qteMedoc = array();


							foreach($_POST['idprestaMedoc'] as $medoc)
							{
								$idprestamedoc[] = $medoc;
							}
							
							foreach($_POST['prixprestaMedoc'] as $valMedoc)
							{
								$prixmdo[] = $valMedoc;
							}
							
							foreach($_POST['percentMedoc'] as $valeurMedoc)
							{
								$addMedoc[] = $valeurMedoc;
							}
							
							foreach($_POST['idmedMedoc'] as $valeurmdo)
							{
								$idmdo[] = $valeurmdo;
							}
							
							foreach($_POST['autreMedoc'] as $autrevaluemedoc)
							{
								$autremedoc[] = $autrevaluemedoc;
							}

							foreach($_POST['quantityMedoc'] as $valueMedoc)
							{
								$qteMedoc[] = $valueMedoc;
							}
							
							for($i=0;$i<sizeof($addMedoc);$i++)
							{
								// echo $addMedoc[$i].'_'.$idmdo[$i].'_('.$prixmdo[$i].' : '.$qteMedoc[$i].')<br/>';
								
								$result=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assuUpdate.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation='.$idprestamedoc[$i].'');
								
								$result->setFetchMode(PDO::FETCH_OBJ);
								
								$comptPresta=$result->rowCount();
								
								if($comptPresta!=0)
								{//echo "string2 = ".$presta_assuUpdate;
									if($ligne=$result->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.prixautreMedoc=0,mdo.qteMedoc='.$qteMedoc[$i].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
										
										if ($presta_assuUpdate !="prestations_PRIVATE" AND $addMedoc[$i]!=100) {

											$status  = $ligne->statupresta;
				                        	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmdo[$i].' WHERE p.id_prestation='.$idprestamedoc[$i].'');
				                        	}else{
				                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamedoc[$i].'');
				                        	}
											
										}elseif($presta_assuUpdate == "prestations_PRIVATE"){
			                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=1 WHERE p.id_prestation='.$idprestamedoc[$i].'');
										}elseif ($presta_assuUpdate !="prestations_PRIVATE" AND $addMedoc[$i]==100) {
											//echo "string";
											$status  = $ligne->statupresta;
											$newP = 0;
				                        	if ($status!=0) {
												$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamedoc[$i].'');
				                        	}else{
				                        		$updateAssu=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmdo[$i].',statupresta=2 WHERE p.id_prestation='.$idprestamedoc[$i].'');
				                        	}
										}
									}
								}else{
									
									$results=$connexion->query('SELECT *FROM med_medoc mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
								
									$results->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$results->fetch())
									{
										$updatepercent=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixautreMedoc='.$prixmdo[$i].',mdo.qteMedoc='.$qteMedoc[$i].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
									
									}
								}
							}
						}
					
						if (isset($_GET['finishbtn'])) {
							$conditionMedMedoc = '';
							if (isset($_POST['savebill']) && isset($_POST['idmedMedoc'])) {
								$idmedMedoc2 = array();

								foreach($_POST['idmedMedoc'] as $valeurmdo)
								{
									$idmedMedoc2[] = $valeurmdo;
								}
								$conditionMedMedoc .= ' AND (';
								for ($i=0; $i < sizeof($idmedMedoc2) ; $i++) { 
									if ($i==0) {
										$conditionMedMedoc .= ' mdo.id_medmedoc='.$idmedMedoc2[$i].'';
									}else{
										$conditionMedMedoc .= ' OR mdo.id_medmedoc='.$idmedMedoc2[$i].'';
									}									
								}
								$conditionMedMedoc .= ' )';
							}else{
								$conditionMedMedoc .= ' AND mdo.id_factureMedMedoc!='.$_GET['idbill'].'';
							}
							$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_consuMedoc=:idconsu'.$conditionMedMedoc.' ORDER BY mdo.id_medmedoc');
						}else{
							$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_consuMedoc=:idconsu AND mdo.id_factureMedMedoc=0 ORDER BY mdo.id_medmedoc');
						}

						$resultMedMedoc->execute(array(
						'num'=>$numPa,
						'idconsu'=>$_GET['idconsu']
						));
						
						$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

						$comptMedMedoc=$resultMedMedoc->rowCount();
						
						if($comptMedMedoc!=0)
						{
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
						
						$TotalGnlPrice=0;
						$TotalGnlPatientPrice=0;
						$TotalGnlInsurancePrice=0;
						
						
						if($comptConsult != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TotalpatientPrice=0;
							
							$TotaluapPrice=0;
						
							while($ligneConsult=$resultConsult->fetch())
							{
							
								$billpercent=$ligneConsult->insupercent;
								
								$idassu=$ligneConsult->id_assuConsu;
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
										}else{
										
											if($lignePresta->nompresta!='')
											{
												$nameprestaConsult=$lignePresta->nompresta;
											}
										}
										
								$prixPresta = $ligneConsult->prixtypeconsult;
								
								$TotalConsult=$TotalConsult + $prixPresta;
								

								$patientPrice=($prixPresta * $billpercent)/100;
								$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
							
								$uapPrice= $prixPresta - $patientPrice;
								$TotaluapPrice = $TotaluapPrice + $uapPrice;
									
									
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
							
										$prixPresta = $ligneNewPresta->prixautretypeconsult;
										
										$TotalConsult=$TotalConsult + $prixPresta;
										
										$patientPrice=($prixPresta * $billpercent)/100;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										
										$uapPrice= $prixPresta - $patientPrice;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									}
								}
							}
					
		$TotalGnlPrice=$TotalGnlPrice + $TotalConsult;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceConsult=$TotalpatientPrice;

		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceConsult=$TotaluapPrice;
					
							echo $TotalConsult;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
					
					
						/*------Requête pour AFFICHER Med_consult------*/
					
						
						$TotalMedConsult = 0;
						
						if($comptMedConsult != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
					
							$TotalpatientPrice=0;
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
									}else{
									
										$nameprestaMedConsult=$lignePresta->nompresta;
									}

									$prixPresta = $ligneMedConsult->prixprestationConsu;
									
									$TotalMedConsult=$TotalMedConsult + $prixPresta;
									
									$patientPrice=($prixPresta * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $prixPresta - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedConsult->id_prestationConsu==NULL AND ($ligneMedConsult->prixautreConsu!=0))
								{
									$nameprestaMedConsult=$ligneMedConsult->autreConsu;
									$prixPresta = $ligneMedConsult->prixautreConsu;
									
									$TotalMedConsult=$TotalMedConsult + $prixPresta;
									
									$patientPrice=($prixPresta * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $prixPresta - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
							}

		$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceServ=$TotalpatientPrice;
		
		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceServ=$TotaluapPrice;
							
							echo $TotalMedConsult;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
					
						/*-------Requête pour AFFICHER Med_surge--------*/
					
						$TotalMedSurge = 0;
			
						if($comptMedSurge != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							
							$TotalpatientPrice=0;
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
									}else{
									
										$nameprestaMedSurge=$lignePresta->nompresta;
									}
									
									$prixPresta = $ligneMedSurge->prixprestationSurge;
									
									$TotalMedSurge = $TotalMedSurge + $prixPresta;
								
									$patientPrice=($ligneMedSurge->prixprestationSurge * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $ligneMedSurge->prixprestationSurge - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedSurge->id_prestationSurge==NULL AND ($ligneMedSurge->prixautrePrestaS!=0))
								{
									$nameprestaMedSurge=$ligneMedSurge->autrePrestaS;
									
									$prixPresta = $ligneMedSurge->prixautrePrestaS;
									
									$TotalMedSurge = $TotalMedSurge + $prixPresta;
				
									$patientPrice=($prixPresta * $billpercent)/100;
									
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $prixPresta - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
								}
								
							}
							
		$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceSurge=$TotalpatientPrice;
		
		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceSurge=$TotaluapPrice;
							
							echo $TotalMedSurge;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
					
						/*-------Requête pour AFFICHER Med_kine--------*/
					
						$TotalMedKine = 0;
			
						if($comptMedKine != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							
							$TotalpatientPrice=0;
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
									}else{
									
										$nameprestaMedKine=$lignePresta->nompresta;
									}
									
									$prixPresta = $ligneMedKine->prixprestationKine;
									
									$TotalMedKine = $TotalMedKine + $prixPresta;
								
									$patientPrice=($ligneMedKine->prixprestationKine * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $ligneMedKine->prixprestationKine - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedKine->id_prestationKine==NULL AND ($ligneMedKine->prixautrePrestaK!=0))
								{
									$nameprestaMedKine=$ligneMedKine->autrePrestaK;
									
									$prixPresta = $ligneMedKine->prixautrePrestaK;
									
									$TotalMedKine = $TotalMedKine + $prixPresta;
				
									$patientPrice=($prixPresta * $billpercent)/100;
									
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $prixPresta - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
								}
								
							}
							
		$TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceKine=$TotalpatientPrice;
		
		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceKine=$TotaluapPrice;
							
							echo $TotalMedKine;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_ortho--------*/

						$TotalMedOrtho = 0;

						if($comptMedOrtho != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice=0;
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
									}else{

										$nameprestaMedOrtho=$lignePresta->nompresta;
									}

									$prixPresta = $ligneMedOrtho->prixprestationOrtho;

									$TotalMedOrtho = $TotalMedOrtho + $prixPresta;

									$patientPrice=($ligneMedOrtho->prixprestationOrtho * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

									$uapPrice= $ligneMedOrtho->prixprestationOrtho - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}

								if($ligneMedOrtho->id_prestationOrtho==NULL AND ($ligneMedOrtho->prixautrePrestaO!=0))
								{
									$nameprestaMedOrtho=$ligneMedOrtho->autrePrestaO;

									$prixPresta = $ligneMedOrtho->prixautrePrestaO;

									$TotalMedOrtho = $TotalMedOrtho + $prixPresta;

									$patientPrice=($prixPresta * $billpercent)/100;

									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

									$uapPrice= $prixPresta - $patientPrice;

									$TotaluapPrice= $TotaluapPrice + $uapPrice;

								}

							}

		$TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;

		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceOrtho=$TotalpatientPrice;

		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceOrtho=$TotaluapPrice;

							echo $TotalMedOrtho;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}


						/*-------Requête pour AFFICHER Med_psy--------*/

						$TotalMedPsycho = 0;

						if($comptMedPsy != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php

							$TotalpatientPrice=0;
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
									}else{

										$nameprestaMedPsy=$lignePresta->nompresta;
									}

									$prixPresta = $ligneMedPsy->prixprestation;

									$TotalMedPsy = $TotalMedPsy + $prixPresta;

									$patientPrice=($ligneMedPsy->prixprestation * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

									$uapPrice= $ligneMedPsy->prixprestation - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}

								if($ligneMedPsy->id_prestation==NULL AND ($ligneMedPsy->prixautrePrestaM!=0))
								{
									$nameprestaMedPsy=$ligneMedPsy->autrePrestaM;

									$prixPresta = $ligneMedPsy->prixautrePrestaM;

									$TotalMedPsy = $TotalMedPsy + $prixPresta;

									$patientPrice=($prixPresta * $billpercent)/100;

									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;

									$uapPrice= $prixPresta - $patientPrice;

									$TotaluapPrice= $TotaluapPrice + $uapPrice;

								}

							}

		$TotalGnlPrice=$TotalGnlPrice + $TotalMedPsy;

		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPricePsy=$TotalpatientPrice;

		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPricePsy=$TotaluapPrice;

							echo $TotalMedPsy;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
					
						/*-------Requête pour AFFICHER Med_inf--------*/
					
						$TotalMedInf = 0;
			
						if($comptMedInf != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							
							$TotalpatientPrice=0;
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
									}else{
									
										$nameprestaMedInf=$lignePresta->nompresta;
									}
									
									$prixPresta = $ligneMedInf->prixprestation;
									
									$TotalMedInf = $TotalMedInf + $prixPresta;
								
									$patientPrice=($ligneMedInf->prixprestation * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $ligneMedInf->prixprestation - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedInf->id_prestation==NULL AND ($ligneMedInf->prixautrePrestaM!=0))
								{
									$nameprestaMedInf=$ligneMedInf->autrePrestaM;
									
									$prixPresta = $ligneMedInf->prixautrePrestaM;
									
									$TotalMedInf = $TotalMedInf + $prixPresta;
				
									$patientPrice=($prixPresta * $billpercent)/100;
									
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $prixPresta - $patientPrice;
									
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
								}
								
							}
							
		$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceInf=$TotalpatientPrice;
		
		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceInf=$TotaluapPrice;
							
							echo $TotalMedInf;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
					
					
						/*-------Requête pour AFFICHER Med_labo--------*/
					
						$TotalMedLabo = 0;
						
						if($comptMedLabo != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
					
							$TotalpatientPrice=0;
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
									}else{
										$nameprestaMedLabo=$lignePresta->nompresta;
									}
									
									$prixPresta = $ligneMedLabo->prixprestationExa;
									
									$TotalMedLabo = $TotalMedLabo + $prixPresta;
									
									$patientPrice=($ligneMedLabo->prixprestationExa * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $ligneMedLabo->prixprestationExa - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedLabo->id_prestationExa==NULL AND ($ligneMedLabo->prixautreExamen!=0))
								{
									$nameprestaMedLabo=$ligneMedLabo->autreExamen;
									$prixPresta = $ligneMedLabo->prixautreExamen;
									
									$TotalMedLabo=$TotalMedLabo + $prixPresta;
									
									$patientPrice=($prixPresta * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $prixPresta - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
							}
							
		$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceLabo=$TotalpatientPrice;

		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceLabo=$TotaluapPrice;
		
							echo $TotalMedLabo;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
						
						/*-------Requête pour AFFICHER Med_radio------*/
					
						$TotalMedRadio = 0;

						if($comptMedRadio != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
					
							$TotalpatientPrice=0;
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

									$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
									
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedRadio->id_prestationRadio
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
									$comptPresta=$resultPresta->rowCount();
									
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedRadio=$lignePresta->namepresta;
									}else{
									
										$nameprestaMedRadio=$lignePresta->nompresta;
									}
									
									$prixPresta = $ligneMedRadio->prixprestationRadio;
									
									$TotalMedRadio = $TotalMedRadio + $prixPresta;
									
									$patientPrice=($prixPresta * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $prixPresta - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedRadio->id_prestationRadio==NULL AND ($ligneMedRadio->prixautreRadio!=0))
								{
									$nameprestaMedRadio=$ligneMedRadio->autreRadio;
									$prixPresta = $ligneMedRadio->prixautreRadio;
									
									$TotalMedRadio=$TotalMedRadio + $ligneMedRadio->prixautreRadio;
									
									$patientPrice=($ligneMedRadio->prixautreRadio * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $ligneMedRadio->prixautreRadio - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
							}
						
		$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceRadio=$TotalpatientPrice;

		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceRadio=$TotaluapPrice;
						
							echo $TotalMedRadio;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
						
						/*-------Requête pour AFFICHER Med_consom-------*/
		
						$TotalMedConsom = 0;
						
						
						if($comptMedConsom != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
					
							$TotalpatientPrice=0;
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
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
							
								if($comptPresta==0)
								{
									$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_PRIVATE p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation=:prestaId');
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedConsom->id_prestationConsom
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								}
								
								if($lignePresta=$resultPresta->fetch())
								{
								
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedConsom=$lignePresta->namepresta;
									}else{
									
										$nameprestaMedConsom=$lignePresta->nompresta;
									}
									
									$qteConsom=$ligneMedConsom->qteConsom;
									$prixPresta = $ligneMedConsom->prixprestationConsom;
									
									$balance=$prixPresta*$qteConsom;
								
									$TotalMedConsom=$TotalMedConsom + $balance;
									
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $balance - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
								
								if($ligneMedConsom->id_prestationConsom==0 AND ($ligneMedConsom->prixautreConsom!=0))
								{
								
									$nameprestaMedConsom=$ligneMedConsom->autreConsom;
									$qteConsom=$ligneMedConsom->qteConsom;
									$prixPresta = $ligneMedConsom->prixautreConsom;
									$balance=$prixPresta*$qteConsom;
									
									$TotalMedConsom=$TotalMedConsom + $balance;
									
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $balance - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
								}
							}
						
		$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceConsom=$TotalpatientPrice;
		
		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceConsom=$TotaluapPrice;
					
							echo $TotalMedConsom;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						
						
						/*-------Requête pour AFFICHER Med_medoc-------*/
						
						$TotalMedMedoc = 0;
						
						if($comptMedMedoc != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
					
							$TotalpatientPrice=0;
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
									$resultPresta=$connexion->prepare('SELECT *FROM categopresta_ins c, prestations_PRIVATE p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId');
									$resultPresta->execute(array(
									'prestaId'=>$ligneMedMedoc->id_prestationMedoc
									));
									
									$resultPresta->setFetchMode(PDO::FETCH_OBJ);
								}
								
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										$nameprestaMedMedoc=$lignePresta->namepresta;
									}else{
										$nameprestaMedMedoc=$lignePresta->nompresta;
									}
								
									$qteMedoc=$ligneMedMedoc->qteMedoc;
									$prixPresta = $ligneMedMedoc->prixprestationMedoc;
									$balance=$prixPresta*$qteMedoc;
									
									$TotalMedMedoc=$TotalMedMedoc + $balance;
									
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $balance - $patientPrice;
									$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
								}
								
								if($ligneMedMedoc->id_prestationMedoc==0 AND ($ligneMedMedoc->prixautreMedoc!=0))
								{
								
									$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
									$qteMedoc=$ligneMedMedoc->qteMedoc;
									$prixPresta = $ligneMedMedoc->prixautreMedoc;
									$balance=$prixPresta*$qteMedoc;
									
									$TotalMedMedoc=$TotalMedMedoc + $balance;
									
									
									$patientPrice=($balance * $billpercent)/100;
									$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
									
									$uapPrice= $balance - $patientPrice;								$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
								}
							}
							
		$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
		
		$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
		$TotalpatientPriceMedoc=$TotalpatientPrice;

		$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
		$TotaluapPriceMedoc=$TotaluapPrice;
						
							echo $TotalMedMedoc;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						?>
						
						<td style="text-align:left; font-size: 110%; font-weight: bold;">
							<?php
								echo $TotalGnlPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
					</tr>-->
					

					
					<!--<tr>
						 <td style="text-align:left;background:#eee;font-weight: bold;border-top:none;">
							Top Up
						</td> -->
						
						<?php

						/*-------AFFICHER Type consultation-------*/
				
						/*if($comptConsult != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupConsult = $TotalConsultCCO - $TotalConsult;
							echo $TopupConsult;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/
					
						/*------AFFICHER Med_consult------*/
						/*
						if($comptMedConsult != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedConsult = $TotalMedConsultCCO - $TotalMedConsult;
							echo $TopupMedConsult;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/
						
						/*-------AFFICHER Med_surge--------*/
					
						/*if($comptMedSurge != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedSurge = $TotalMedSurgeCCO - $TotalMedSurge;
							echo $TopupMedSurge;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/
						
						/*-------AFFICHER Med_kine--------*/
					
						/*if($comptMedKine != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedKine = $TotalMedKineCCO - $TotalMedKine;
							echo $TopupMedKine;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
*/
						/*-------AFFICHER Med_ortho--------*/

						/*if($comptMedOrtho != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedOrtho = $TotalMedOrthoCCO - $TotalMedOrtho;
							echo $TopupMedOrtho;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/

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
					
						/*if($comptMedInf != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedInf = $TotalMedInfCCO - $TotalMedInf;
							echo $TopupMedInf;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
					*/
						/*-------AFFICHER Med_labo--------*/
					
						/*if($comptMedLabo != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedLabo = $TotalMedLaboCCO - $TotalMedLabo;
							echo $TopupMedLabo;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/
						
						/*-------AFFICHER Med_radio------*/
					
						/*if($comptMedRadio != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedRadio = $TotalMedRadioCCO - $TotalMedRadio;
							echo $TopupMedRadio;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}
						*/
						/*-------AFFICHER Med_consom-------*/
		
						/*if($comptMedConsom != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedConsom = $TotalMedConsomCCO - $TotalMedConsom;
							echo $TopupMedConsom;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/
						
						/*-------AFFICHER Med_medoc-------*/
						
						/*if($comptMedMedoc != 0)
						{
						?>
						<td style="text-align:left; font-size: 110%; ">
						<?php
							$TopupMedMedoc = $TotalMedMedocCCO - $TotalMedMedoc;
							echo $TopupMedMedoc;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>
						<?php
						}*/
						?>
						
						<!-- <td style="text-align:left; font-size: 110%; font-weight: bold;">
						<?php
							$TopupGnlPrice = $TotalGnlPriceCCO - $TotalGnlPrice;
							echo $TopupGnlPrice;
						?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td> 
					</tr>-->
					

					
			
					
					<tr>

						<?php
						if($comptConsult!=0)
						{
						?>

						<?php
						}
						if($comptMedConsult!=0)
						{
						?>

						<?php
						}
						if($comptMedSurge!=0)
						{
						?>
		
						<?php
						}
						if($comptMedKine!=0)
						{
						?>

						<?php
						}
						if($comptMedOrtho!=0)
						{
						?>

						<?php
						}
						if($comptMedPsy!=0)
						{
						?>

						<?php
						}
						if($comptMedInf!=0)
						{
						?>
				
						<?php
						}
						if($comptMedLabo!=0)
						{
						?>
					
						<?php
						}
						if($comptMedRadio!=0)
						{
						?>
					
						<?php
						}
						if($comptMedConsom!=0)
						{
						?>
						
						<?php
						}
						if($comptMedMedoc!=0)
						{
						?>
				
						<?php
						}
						?>

						<?php
						
						$patientPayed = ($TotalGnlPatientPrice) - $dettes;
						
						if($idBillDebtCount!=0)
						{	
							if(isset($_GET['createBill']))
							{
						?>
								
						<?php
							}
						}
						?>
							</table>
						</td>
					</tr>
					
				</tbody>
			</table>
			</td>
		</tr>
		</table>
		
	</div>


	
	
	<div class="account-container" style="margin: 10px auto auto; width:95%; border: 1px solid #ccc; background:#fff; padding:5px; border-radius:3px; font-size:80%;">
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
				<img src="barcode/png/barcode'.$codecashier.'.png" style="height:auto;"/>
			</td>
			
		</tr>
		
	</table>';

echo $barcode;
?>

<?php

			$resultatConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:idconsu');
			$resultatConsu->execute(array(
			'idconsu'=>$consuId
			));
			
			$resultatConsu->setFetchMode(PDO::FETCH_OBJ);

			if($ligneConsu=$resultatConsu->fetch())
			{
				$dateconsu= date('d-M-Y', strtotime($ligneConsu->dateconsu));
			}
			$poids=$ligneConsu->poids;

		
		
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
			$idassurance=$lignePatient->id_assurance;
			
			
			$uappercent= 100 - $lignePatient->bill;
			
			$percentpartient= 100 - $uappercent;
			$telephone=$lignePatient->telephone;

			if($lignePatient->sexe=="M")
			{
				$sexe = "Male";
			}elseif($lignePatient->sexe=="F"){
				$sexe = "Female";
			}else{
				$sexe="";
			}
	
			$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
			$resultAdresse->execute(array(
			'idProv'=>$lignePatient->province,
			'idDist'=>$lignePatient->district,
			'idSect'=>$lignePatient->secteur
			));
			
			$resultAdresse->setFetchMode(PDO::FETCH_OBJ);

			$comptAdress=$resultAdresse->rowCount();
			
			if($ligneAdresse=$resultAdresse->fetch())
			{
				if($ligneAdresse->id_province == $lignePatient->province)
				{
					$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
					
				}
			}elseif($lignePatient->autreadresse!=""){
					$adresse=$lignePatient->autreadresse;
			}else{
				$adresse="";
			}

			

		$userinfo = '<table style="width:100%;">
			
			<tr>
				<td style="text-align:left;">
					Full name:
					<span style="font-weight:bold;font-size:16px;">'.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'</span><br/>
					Gender: <span style="font-weight:bold">'.$sexe.'</span><br/>
					Adress: <span style="font-weight:bold">'.$adresse.'</span><br />
					Telephone: <span style="font-weight:bold">'.$telephone.'</span>

				</td>
				
				<td style="text-align:center;">
					Insurance type: <span style="font-weight:bold">';
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$lignePatient->id_assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);

			if($ligneAssu=$resultAssurance->fetch())
			{
				if($ligneAssu->id_assurance == $lignePatient->id_assurance)
				{
					$idassu=$ligneAssu->id_assurance;
					$nomassurance=$ligneAssu->nomassurance;
					$numpolicebill=$lignePatient->numeropolice;
					$idcardbill= $lignePatient->carteassuranceid;
					$adherentbill=$lignePatient->adherent;
					
					$userinfo .= ''.$ligneAssu->nomassurance.'</span><br/>';
					
					if($idassurance!=1)
					{
						$userinfo .= 'N° insurance card:
						<span style="font-weight:bold">'.$idcardbill;
						
						if($numpolicebill!="")
						{
							$userinfo .= '</span><br/>
							
							N° police:
							<span style="font-weight:bold">'.$numpolicebill;
						}
						
						$userinfo .= '</span><br/>
						
						Principal member:
						<span style="font-weight:bold">'.$adherentbill;

						$userinfo .= '</span><br/>
						
						Affiliate Company:
						<span style="font-weight:bold">'.$profession;

						$userinfo .= '</span><br/>
						
						Beneficiare:
						<input type="checkbox"> Adherent Lui meme <input type="checkbox"> conjoint <input type="checkbox"> Enfant';

					}
				}
			}

				$userinfo .='</span>
				</td>
				
				<td style="text-align:right;">
					Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
					Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
					Entry Date: <span style="font-weight:bold">'.$dateconsu.'</span><br>
					Discharge Date: <span style="font-weight:bold"> <input type="date" style="width:100px"> </span><br>

					Status: <input type="checkbox"> OutPatient <input type="checkbox"> InPatient <br>

					Weight: <span style="font-weight:bold">'.$poids.'</span><br>

					Maladie Naturelle <input type="checkbox"><br />
					Maladie professionelle <input type="checkbox"><br />
					Accident de travail <input type="checkbox"> <br />
					Accident de circulation <input type="checkbox"></br>
					Autres <input type="checkbox"></br>



					
				</td>
				
			</tr>
		</table>';

		echo $userinfo;
		
		$objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
					 ->setLastModifiedBy(''.$doneby.'')
					 ->setTitle('Bill #'.$numbill.'')
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
						->setCellValue('B3', ''.$adresse.'')
						
						->setCellValue('A4', 'Insurance')
						->setCellValue('B4', ''.$nomassurance.' '.$percentpartient.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$annee.'');
			
		}
		
		
		$comptAssuUpdate=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
		
		$comptAssuUpdate->setFetchMode(PDO::FETCH_OBJ);
		
		$assuCount = $comptAssuUpdate->rowCount();
		
		for($i=1;$i<=$assuCount;$i++)
		{
			
			$getAssuUpdate=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
			$getAssuUpdate->execute(array(
			'idassu'=>$idassu
			));
			
			$getAssuUpdate->setFetchMode(PDO::FETCH_OBJ);

			if($ligneNomAssuUpdate=$getAssuUpdate->fetch())
			{
				$presta_assuUpdate='prestations_'.strtolower($ligneNomAssuUpdate->nomassurance);
			}
		}


		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		if(isset($_POST['prixtypeconsult']))
		{
			
			$idprestatc = array();
			$prixtc = array();
			$addtc = array();
			$idtc = array();
			$autretc = array();

			foreach($_POST['idprestaConsu'] as $mc)
			{
				$idprestatc[] = $mc;
			}
			
			foreach($_POST['prixtypeconsult'] as $valmc)
			{
				$prixtc[] = $valmc;
			}
			foreach($_POST['percentTypeConsu'] as $valuemc)
			{
				$addtc[] = $valuemc;
			}
			
			foreach($_POST['idConsu'] as $valeurmc)
			{
				$idtc[] = $valeurmc;
			}
			
			foreach($_POST['autretypeconsult'] as $autrevaluemc)
			{
				$autretc[] = $autrevaluemc;
			}
			
			for($i=0;$i<sizeof($addtc);$i++)
			{
				// echo $addtc[$i].'_'.$idtc[$i].'_('.$prixtc[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestatc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					if($ligne=$result->fetch())
					{
						$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixtc[$i].' WHERE p.id_prestation='.$idprestatc[$i].'');
					
						$updatepercent=$connexion->query('UPDATE consultations c SET c.id_assuConsu='.$_GET['idassu'].',c.insupercent='.$addtc[$i].',c.prixtypeconsult='.$prixtc[$i].',c.prixautretypeconsult=0 WHERE c.id_consu='.$idtc[$i].'');
						
					}
					
				}else{
				
					$results=$connexion->query('SELECT *FROM consultations c WHERE c.id_consu='.$idtc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE consultations c SET c.id_assuConsu='.$_GET['idassu'].',c.insupercent='.$addtc[$i].',c.prixtypeconsult=0,c.prixautretypeconsult='.$prixtc[$i].' WHERE c.id_consu='.$idtc[$i].'');
						
					}
				}

			}
		}
		
		if (isset($_GET['finishbtn'])) {
			$conditionConsu = "";
			if (isset($_POST['savebill']) && isset($_POST['idConsu'])) {
				$idconsu2 = array();

				foreach($_POST['idConsu'] as $valeurmc)
				{
					$idconsu2[] = $valeurmc;
				}
				$conditionConsu .= " AND (";
				for ($i=0; $i < sizeof($idconsu2); $i++) { 
					if ($i == 0) {
						$conditionConsu .= ' c.id_consu='.$idconsu2[$i].'';
					}else{
						$conditionConsu .= ' OR c.id_consu='.$idconsu2[$i].'';
					}
				}
				$conditionConsu .= " )";
			}else{
				$conditionConsu .= ' AND c.id_factureConsult!='.$_GET['idbill'].''; 
			}
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.numero=:num'.$conditionConsu.' ORDER BY c.id_consu');
		}else{
			$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_consu=:consuId AND p.numero=:num AND p.numero=c.numero AND c.numero=:num AND c.id_factureConsult IS NULL ORDER BY c.id_consu');
		}
		
		$resultConsult->execute(array(
		'consuId'=>$_GET['idconsu'],
		'num'=>$numPa
		));

		$resultConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		$TotalConsultCCO = 0;
		
		
	
		/*-------Requête pour AFFICHER Med_consult-----------*/
	
		
		if(isset($_POST['idpresta']))
		{
			
			$idprestamc = array();
			$prixmc = array();
			$add = array();
			$idmc = array();
			$autremc = array();

			foreach($_POST['idpresta'] as $mc)
			{
				$idprestamc[] = $mc;
			}
			
			foreach($_POST['prixprestaConsu'] as $valmc)
			{
				$prixmc[] = $valmc;
			}
			
			foreach($_POST['percentConsu'] as $valuemc)
			{
				$add[] = $valuemc;
			}
			
			foreach($_POST['idmedConsu'] as $valeurmc)
			{
				$idmc[] = $valeurmc;
			}
			
			foreach($_POST['autreConsu'] as $autrevaluemc)
			{
				$autremc[] = $autrevaluemc;
			}

			// print_r($idprestamc);
			
			for($i=0;$i<sizeof($add);$i++)
			{
				// echo $add[$i].'_'.$idmc[$i].'_('.$prixmc[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmc[$i].' WHERE p.id_prestation='.$idprestamc[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu='.$prixmc[$i].',mc.prixautreConsu=0 WHERE mc.id_medconsu='.$idmc[$i].'');
					
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_consult mc WHERE mc.id_medconsu='.$idmc[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consult mc SET mc.id_assuServ='.$_GET['idassu'].',mc.insupercentServ='.$add[$i].',mc.prixprestationConsu=0,mc.prixautreConsu='.$prixmc[$i].' WHERE mc.id_medconsu='.$idmc[$i].'');
						
					}
				}

			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedConsu = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedConsu'])) {
				$idmedConsu2 = array();

				foreach($_POST['idmedConsu'] as $valeurmc)
				{
					$idmedConsu2[] = $valeurmc;
				}
				$conditionMedConsu .= ' AND (';
				for ($i=0; $i < sizeof($idmedConsu2) ; $i++) { 
					if ($i == 0) {
						$conditionMedConsu .= ' mc.id_medconsu='.$idmedConsu2[$i].'';
					}else{
						$conditionMedConsu .= ' OR mc.id_medconsu='.$idmedConsu2[$i].'';
					}
				}
				$conditionMedConsu .= ' )';
			}else{
				$conditionMedConsu .= ' AND mc.id_factureMedConsu!='.$_GET['idbill'].'';
			}
			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_consuMed=:idconsu AND mc.dateconsu!="0000-00-00"'.$conditionMedConsu.' ORDER BY mc.id_medconsu');
		}else{
			$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_consuMed=:idconsu AND mc.dateconsu!="0000-00-00" AND (mc.id_factureMedConsu=0 OR mc.id_factureMedConsu IS NULL) ORDER BY mc.id_medconsu');
		}
		
		
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;
		$TotalMedConsultCCO = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_surge-----------*/
	
		
		if(isset($_POST['idprestaSurge']))
		{
	
			$idprestams = array();
			$prixms = array();
			$addSurge = array();
			$idms = array();
			$autrems = array();

			foreach($_POST['idprestaSurge'] as $ms)
			{
				$idprestams[] = $ms;
			}
			
			foreach($_POST['prixprestaSurge'] as $valms)
			{
				$prixms[] = $valms;
			}
			
			foreach($_POST['percentSurge'] as $valeurSurge)
			{
				$addSurge[] = $valeurSurge;
			}
			
			foreach($_POST['idmedSurge'] as $valeurms)
			{
				$idms[] = $valeurms;
			}
			
			foreach($_POST['autreSurge'] as $autrevaluems)
			{
				$autrems[] = $autrevaluems;
			}
			
			
			for($i=0;$i<sizeof($addSurge);$i++)
			{
				
				// echo $addSurge[$i].'_'.$idms[$i].'_('.$prixms[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestams[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixms[$i].' WHERE p.id_prestation='.$idprestams[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_surge ms SET ms.id_assuSurge='.$_GET['idassu'].',ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge='.$prixms[$i].',ms.prixautrePrestaS=0 WHERE ms.id_medsurge='.$idms[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_surge ms WHERE ms.id_medsurge='.$idms[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_surge ms SET ms.id_assuSurge='.$_GET['idassu'].',ms.insupercentSurge='.$addSurge[$i].',ms.prixprestationSurge=0,ms.prixautrePrestaS='.$prixms[$i].' WHERE ms.id_medsurge='.$idms[$i].'');
						
					}
				}
				
			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedSurge = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedSurge'])) {
				$idmedSurge2 = array();

				foreach($_POST['idmedSurge'] as $valeurms)
				{
					$idmedSurge2[] = $valeurms;
				}
				
				$conditionMedSurge .= ' AND (';
				for ($i=0; $i < sizeof($idmedSurge2) ; $i++) { 
					if ($i == 0) {
						$conditionMedSurge .= ' ms.id_medsurge='.$idmedSurge2[$i].'';
					}else{
						$conditionMedSurge .= ' OR ms.id_medsurge='.$idmedSurge2[$i].'';
					}
				}
				$conditionMedSurge .= ' )';
			}else{
				$conditionMedSurge .= ' AND ms.id_factureMedSurge!='.$_GET['idbill'].'';
			}
			$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_consuSurge=:idconsu '.$conditionMedSurge.' ORDER BY ms.id_medsurge');
		}else{
			$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_consuSurge=:idconsu AND ms.id_factureMedSurge=0 ORDER BY ms.id_medsurge');
		}
	
		
		$resultMedSurge->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();
	
		$TotalMedSurge = 0;
		$TotalMedSurgeCCO = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_kine-----------*/
	
		
		if(isset($_POST['idprestaKine']))
		{
	
			$idprestamk = array();
			$prixmk = array();
			$addKine = array();
			$idmk = array();
			$autremk = array();

			foreach($_POST['idprestaKine'] as $mk)
			{
				$idprestamk[] = $mk;
			}
			
			foreach($_POST['prixprestaKine'] as $valmk)
			{
				$prixmk[] = $valmk;
			}
			
			foreach($_POST['percentKine'] as $valeurKine)
			{
				$addKine[] = $valeurKine;
			}
			
			foreach($_POST['idmedKine'] as $valeurmk)
			{
				$idmk[] = $valeurmk;
			}
			
			foreach($_POST['autreKine'] as $autrevaluemk)
			{
				$autremk[] = $autrevaluemk;
			}
			
			
			for($i=0;$i<sizeof($addKine);$i++)
			{
				
				// echo $addKine[$i].'_'.$idmk[$i].'_('.$prixmk[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamk[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmk[$i].' WHERE p.id_prestation='.$idprestamk[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_kine mk SET mk.id_assuKine='.$_GET['idassu'].',mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine='.$prixmk[$i].',mk.prixautrePrestaK=0 WHERE mk.id_medkine='.$idmk[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_kine mk WHERE mk.id_medkine='.$idmk[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_kine mk SET mk.id_assuKine='.$_GET['idassu'].',mk.insupercentKine='.$addKine[$i].',mk.prixprestationKine=0,mk.prixautrePrestaK='.$prixmk[$i].' WHERE mk.id_medkine='.$idmk[$i].'');
						
					}
				}
				
			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedKine = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedKine'])) {
				$idmedKine2 = array();

				foreach($_POST['idmedKine'] as $valeurmk)
                {
                    $idmedKine2[] = $valeurmk;
                }
				
				$conditionMedKine .= ' AND (';
				for ($i=0; $i < sizeof($idmedKine2) ; $i++) { 
					if ($i == 0) {
						$conditionMedKine .= ' mk.id_medkine='.$idmedKine2[$i].'';
					}else{
						$conditionMedKine .= ' OR mk.id_medkine='.$idmedKine2[$i].'';
					}
					
				}
				$conditionMedKine .= ' )';
			}else{
				$conditionMedKine .= ' AND mk.id_factureMedKine!='.$_GET['idbill'].'';
			}
			$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_consuKine=:idconsu'.$conditionMedKine.' ORDER BY mk.id_medkine');
		}else{
			$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_consuKine=:idconsu AND mk.id_factureMedKine=0 ORDER BY mk.id_medkine');
		}
		
		$resultMedKine->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

		$comptMedKine=$resultMedKine->rowCount();
	
		$TotalMedKine = 0;
		$TotalMedKineCCO = 0;



		/*-------Requête pour AFFICHER Med_ortho-----------*/


		if(isset($_POST['idprestaOrtho']))
		{

			$idprestamo = array();
			$prixmo = array();
			$addOrtho = array();
			$idmo = array();
			$autremo = array();

			foreach($_POST['idprestaOrtho'] as $mo)
			{
				$idprestamo[] = $mo;
			}

			foreach($_POST['prixprestaOrtho'] as $valmo)
			{
				$prixmo[] = $valmo;
			}

			foreach($_POST['percentOrtho'] as $valeurOrtho)
			{
				$addOrtho[] = $valeurOrtho;
			}

			foreach($_POST['idmedOrtho'] as $valeurmo)
			{
				$idmo[] = $valeurmo;
			}

			foreach($_POST['autreOrtho'] as $autrevaluemo)
			{
				$autremo[] = $autrevaluemo;
			}


			for($i=0;$i<sizeof($addOrtho);$i++)
			{

				// echo $addOrtho[$i].'_'.$idmo[$i].'_('.$prixmo[$i].')<br/>';

				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamo[$i].'');

				$result->setFetchMode(PDO::FETCH_OBJ);

				$comptPresta=$result->rowCount();

				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmo[$i].' WHERE p.id_prestation='.$idprestamo[$i].'');

					$updatepercent=$connexion->query('UPDATE med_ortho mo SET mo.id_assuOrtho='.$_GET['idassu'].',mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho='.$prixmo[$i].',mo.prixautrePrestaO=0 WHERE mo.id_medortho='.$idmo[$i].'');

				}else{

					$results=$connexion->query('SELECT *FROM med_ortho mo WHERE mo.id_medortho='.$idmo[$i].'');

					$results->setFetchMode(PDO::FETCH_OBJ);

					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_ortho mo SET mo.id_assuOrtho='.$_GET['idassu'].',mo.insupercentOrtho='.$addOrtho[$i].',mo.prixprestationOrtho=0,mo.prixautrePrestaO='.$prixmo[$i].' WHERE mo.id_medortho='.$idmo[$i].'');

					}
				}

			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedOrtho = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedOrtho'])) {
				$idmedOrtho2 = array();

				foreach($_POST['idmedOrtho'] as $valeurmo)
				{
					$idmedOrtho2[] = $valeurmo;
				}
				$conditionMedOrtho .= ' AND (';
				for ($i=0; $i < sizeof($idmedOrtho2) ; $i++) { 
					if ($i == 0) {
						$conditionMedOrtho .= ' mo.id_medOrtho='.$idmedOrtho2[$i].'';
					}else{
						$conditionMedOrtho .= ' OR mo.id_medOrtho='.$idmedOrtho2[$i].'';
					}
					
				}
				$conditionMedOrtho .= ' )';
			}else{
				$conditionMedOrtho .= ' AND mo.id_factureMedOrtho!='.$_GET['idbill'].'';
			}
			$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_consuOrtho=:idconsu'.$conditionMedOrtho.' ORDER BY mo.id_medortho');
		}else{
			$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_consuOrtho=:idconsu AND mo.id_factureMedOrtho=0 ORDER BY mo.id_medortho');
		}
		
		$resultMedOrtho->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));

		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

		$comptMedOrtho=$resultMedOrtho->rowCount();

		$TotalMedOrtho = 0;
		$TotalMedOrthoCCO = 0;
	
	

		/*-------Requête pour AFFICHER Med_psy-----------*/


		if(isset($_POST['idprestaPyscho']))
		{

			$idprestapsy = array();
			$prixpsy = array();
			$addPyscho = array();
			$idpsy = array();
			$autrepsy = array();

			foreach($_POST['idprestaPyscho'] as $psy)
			{
				$idprestapsy[] = $psy;
			}

			foreach($_POST['prixprestaPyscho'] as $valpsy)
			{
				$prixpsy[] = $valpsy;
			}

			foreach($_POST['percentPyscho'] as $valeurPyscho)
			{
				$addPyscho[] = $valeurPyscho;
			}

			foreach($_POST['idmedPyscho'] as $valeurpsy)
			{
				$idpsy[] = $valeurpsy;
			}

			foreach($_POST['autrePyscho'] as $autrevaluepsy)
			{
				$autrepsy[] = $autrevaluepsy;
			}


			for($i=0;$i<sizeof($addPyscho);$i++)
			{

				// echo $addPyscho[$i].'_'.$idpsy[$i].'_('.$prixpsy[$i].')<br/>';

				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestapsy[$i].'');

				$result->setFetchMode(PDO::FETCH_OBJ);

				$comptPresta=$result->rowCount();

				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixpsy[$i].' WHERE p.id_prestation='.$idprestapsy[$i].'');

					$updatepercent=$connexion->query('UPDATE med_psy mo SET mo.id_assuPsy='.$_GET['idassu'].',mo.insupercentPsy='.$addPyscho[$i].',mo.prixprestation='.$prixpsy[$i].',mo.prixautrePrestaM=0 WHERE mo.id_medpsy='.$idpsy[$i].'');

				}else{

					$results=$connexion->query('SELECT *FROM med_psy mo WHERE mo.id_medpsy='.$idpsy[$i].'');

					$results->setFetchMode(PDO::FETCH_OBJ);

					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_psy mo SET mo.id_assuPsy='.$_GET['idassu'].',mo.insupercentPsy='.$addPyscho[$i].',mo.prixprestation=0,mo.prixautrePrestaM='.$prixpsy[$i].' WHERE mo.id_medpsy='.$idpsy[$i].'');

					}
				}

			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedPsy = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedPyscho'])) {
				$idmedPsy2 = array();

				foreach($_POST['idmedPyscho'] as $valeurpsy)
				{
					$idmedPsy2[] = $valeurpsy;
				}
				$conditionMedPsy .= ' AND (';
				for ($i=0; $i < sizeof($idmedPsy2) ; $i++) { 
					if ($i == 0) {
						$conditionMedPsy .= ' mp.id_medpsy='.$idmedPsy2[$i].'';
					}else{
						$conditionMedPsy .= ' OR mp.id_medpsy='.$idmedPsy2[$i].'';
					}
				}
				$conditionMedPsy .= ' )';
			}else{
				$conditionMedPsy .= ' AND mp.id_factureMedPsy!='.$_GET['idbill'].'';
			}
			$resultMedPsy=$connexion->prepare('SELECT *FROM med_psy mp, patients p WHERE p.numero=:num AND p.numero=mp.numero AND mp.numero=:num AND mp.id_consuPSy=:idconsu'.$conditionMedPsy.' ORDER BY mp.id_medpsy');
		}else{
			$resultMedPsy=$connexion->prepare('SELECT *FROM med_psy mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_consuPSy=:idconsu AND mo.id_factureMedPsy=0 ORDER BY mo.id_medpsy');
		}

		
		$resultMedPsy->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));

		$resultMedPsy->setFetchMode(PDO::FETCH_OBJ);

		$comptMedPsy=$resultMedPsy->rowCount();

		$TotalMedPsy = 0;
		$TotalMedPsyCCO = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_inf-----------*/
	
		
		if(isset($_POST['idprestaInf']))
		{
	
			$idprestami = array();
			$prixmi = array();
			$addInf = array();
			$idmi = array();
			$autremi = array();

			foreach($_POST['idprestaInf'] as $mi)
			{
				$idprestami[] = $mi;
			}
			
			foreach($_POST['prixprestaInf'] as $valmi)
			{
				$prixmi[] = $valmi;
			}
			foreach($_POST['percentInf'] as $valeurInf)
			{
				$addInf[] = $valeurInf;
			}
			
			foreach($_POST['idmedInf'] as $valeurmi)
			{
				$idmi[] = $valeurmi;
			}
			
			foreach($_POST['autreInf'] as $autrevaluemi)
			{
				$autremi[] = $autrevaluemi;
			}
			
			
			for($i=0;$i<sizeof($addInf);$i++)
			{
				
				// echo $addInf[$i].'_'.$idmi[$i].'_('.$prixmi[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestami[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmi[$i].' WHERE p.id_prestation='.$idprestami[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.insupercentInf='.$addInf[$i].',mi.prixprestation='.$prixmi[$i].',mi.prixautrePrestaM=0 WHERE mi.id_medinf='.$idmi[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_inf mi WHERE mi.id_medinf='.$idmi[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_inf mi SET mi.id_assuInf='.$_GET['idassu'].',mi.insupercentInf='.$addInf[$i].',mi.prixprestation=0,mi.prixautrePrestaM='.$prixmi[$i].' WHERE mi.id_medinf='.$idmi[$i].'');
						
					}
				}
				
			}
		}
		
		if (isset($_GET['finishbtn'])) {
			$conditionMedInf = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedInf'])) {
				$idmedInf2 = array();

				foreach($_POST['idmedInf'] as $valeurmi)
				{
					$idmedInf2[] = $valeurmi;
				}
				$conditionMedInf .= ' AND (';
				for ($i=0; $i < sizeof($idmedInf2) ; $i++) { 
					if ($i == 0) {
						$conditionMedInf .= ' mi.id_medinf='.$idmedInf2[$i].'';
					}else{
						$conditionMedInf .= ' OR mi.id_medinf='.$idmedInf2[$i].'';
					}
					
				}
				$conditionMedInf .= ' )';
			}else{
				$conditionMedInf .= ' AND mi.id_factureMedInf!='.$_GET['idbill'].'';
			}
			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_consuInf=:idconsu'.$conditionMedInf.' ORDER BY mi.id_medinf');
		}else{
			$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_consuInf=:idconsu AND mi.id_factureMedInf=0 ORDER BY mi.id_medinf');
		}

		
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
		$TotalMedInfCCO = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_labo-----------*/
	
		
		if(isset($_POST['idprestaLab']))
		{
			
			$idprestaml = array();
			$prixml = array();
			$addLab = array();
			$idml = array();
			$autreml = array();


			foreach($_POST['idprestaLab'] as $ml)
			{
				$idprestaml[] = $ml;
			}
			
			foreach($_POST['prixprestaLab'] as $valml)
			{
				$prixml[] = $valml;
			}
			
			foreach($_POST['percentLab'] as $valeurLab)
			{
				$addLab[] = $valeurLab;
			}
			
			foreach($_POST['idmedLab'] as $valeurml)
			{
				$idml[] = $valeurml;
			}
			
			foreach($_POST['autreLab'] as $autrevalueml)
			{
				$autreml[] = $autrevalueml;
			}
			
			for($i=0;$i<sizeof($addLab);$i++)
			{
				
				// echo $addLab[$i].'_'.$idml[$i].'_('.$prixml[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestaml[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixml[$i].' WHERE p.id_prestation='.$idprestaml[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa='.$prixml[$i].',ml.prixautreExamen=0 WHERE ml.id_medlabo='.$idml[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_labo ml WHERE ml.id_medlabo='.$idml[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_labo ml SET ml.id_assuLab='.$_GET['idassu'].',ml.insupercentLab='.$addLab[$i].',ml.prixprestationExa=0,ml.prixautreExamen='.$prixml[$i].' WHERE ml.id_medlabo='.$idml[$i].'');
						
					}
				}
			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedLab = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedLab'])) {
				$idmedLab2 = array();

				foreach($_POST['idmedLab'] as $valeurml)
				{
					$idmedLab2[] = $valeurml;
				}
				$conditionMedLab .= ' AND (';
				for ($i=0; $i < sizeof($idmedLab2) ; $i++) { 
					if ($i == 0) {
						$conditionMedLab .= ' ml.id_medlabo='.$idmedLab2[$i].'';
					}else{
						$conditionMedLab .= ' OR ml.id_medlabo='.$idmedLab2[$i].'';
					}
				}
				$conditionMedLab .= ')';
			}else{
				$conditionMedLab .= ' AND ml.id_factureMedLabo!='.$_GET['idbill'].'';
			}
			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_consuLabo=:idconsu'.$conditionMedLab.' ORDER BY ml.id_medlabo');
		}else{
			$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_consuLabo=:idconsu AND ml.id_factureMedLabo=0 ORDER BY ml.id_medlabo');
		}
		
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
		$TotalMedLaboCCO = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_radio-----------*/
	
		
		if(isset($_POST['idprestaRad']))
		{
			
			$idprestamr = array();
			$prixmr = array();
			$addRad = array();
			$idmr = array();
			$autremr = array();


			foreach($_POST['idprestaRad'] as $mr)
			{
				$idprestamr[] = $mr;
			}
			
			foreach($_POST['prixprestaRad'] as $valmr)
			{
				$prixmr[] = $valmr;
			}
			
			foreach($_POST['percentRad'] as $valeurRad)
			{
				$addRad[] = $valeurRad;
			}
			
			foreach($_POST['idmedRad'] as $valeurmr)
			{
				$idmr[] = $valeurmr;
			}
			
			foreach($_POST['autreRad'] as $autrevaluemr)
			{
				$autremr[] = $autrevaluemr;
			}
			
			for($i=0;$i<sizeof($addRad);$i++)
			{
				
				// echo $addRad[$i].'_'.$idmr[$i].'_('.$prixmr[$i].')<br/>';
			
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamr[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmr[$i].' WHERE p.id_prestation='.$idprestamr[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio='.$prixmr[$i].',mr.prixautreRadio=0 WHERE mr.id_medradio='.$idmr[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_radio mr WHERE mr.id_medradio='.$idmr[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_radio mr SET mr.id_assuRad='.$_GET['idassu'].',mr.insupercentRad='.$addRad[$i].',mr.prixprestationRadio=0,mr.prixautreRadio='.$prixmr[$i].' WHERE mr.id_medradio='.$idmr[$i].'');
						
					}
				}
			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedRad = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedRad'])) {
				$idmedRad2 = array();

				foreach($_POST['idmedRad'] as $valeurmr)
				{
					$idmedRad2[] = $valeurmr;
				}

				$conditionMedRad .= ' AND (';
				for ($i=0; $i < sizeof($idmedRad2) ; $i++) { 
					if ($i == 0) {
						$conditionMedRad .= ' mr.id_medradio='.$idmedRad2[$i].'';
					}else{
						$conditionMedRad .= ' OR mr.id_medradio='.$idmedRad2[$i].'';
					}									
				}
				$conditionMedRad .= ' )';
			}else{
				$conditionMedRad .= ' AND mr.id_factureMedRadio!='.$_GET['idbill'].'';
			}
			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_consuRadio=:idconsu'.$conditionMedRad.' ORDER BY mr.id_medradio');
		}else{
			$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_consuRadio=:idconsu AND mr.id_factureMedRadio=0 ORDER BY mr.id_medradio');
		}
		
		
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
	
	
	
		/*-------Requête pour AFFICHER Med_consom-----------*/
		
		if(isset($_POST['idprestaConsom']))
		{
			
			$idprestaconsom = array();
			$prixmco = array();
			$addConsom = array();
			$idmco = array();
			$autreconsom = array();
			$qteConsom = array();


			foreach($_POST['idprestaConsom'] as $consom)
			{
				$idprestaconsom[] = $consom;
			}
			
			foreach($_POST['prixprestaConsom'] as $valConsom)
			{
				$prixmco[] = $valConsom;
			}
			
			foreach($_POST['percentConsom'] as $valeurConsom)
			{
				$addConsom[] = $valeurConsom;
			}
			
			foreach($_POST['idmedConsom'] as $valeurmco)
			{
				$idmco[] = $valeurmco;
			}
			
			foreach($_POST['autreConsom'] as $autrevalueconsom)
			{
				$autreconsom[] = $autrevalueconsom;
			}

			foreach($_POST['quantityConsom'] as $valueConsom)
			{
				$qteConsom[] = $valueConsom;
			}
			
			for($i=0;$i<sizeof($addConsom);$i++)
			{
				// echo $addConsom[$i].'_'.$idmco[$i].'_('.$prixmco[$i].' : '.$qteConsom[$i].')<br/>';
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestaconsom[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmco[$i].' WHERE p.id_prestation='.$idprestaconsom[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom='.$prixmco[$i].',mco.prixautreConsom=0 WHERE mco.id_medconsom='.$idmco[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_consom mco WHERE mco.id_medconsom='.$idmco[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_consom mco SET mco.id_assuConsom='.$_GET['idassu'].',mco.insupercentConsom='.$addConsom[$i].',mco.prixprestationConsom=0,mco.prixautreConsom='.$prixmco[$i].', mco.qteConsom='.$qteConsom[$i].' WHERE mco.id_medconsom='.$idmco[$i].'');
					}
				}
			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedConsom = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedConsom'])) {
				$idmedConsom2 = array();

				foreach($_POST['idmedConsom'] as $valeurmco)
				{
					$idmedConsom2[] = $valeurmco;
				}
				$conditionMedConsom .= ' AND (';
				for ($i=0; $i < sizeof($idmedConsom2) ; $i++) { 
					if ($i==0) {
						$conditionMedConsom .= ' mco.id_medconsom='.$idmedConsom2[$i].'';
					}else{
						$conditionMedConsom .= ' OR mco.id_medconsom='.$idmedConsom2[$i].'';
					}									
				}
				$conditionMedConsom .= ' )';
			}else{
				$conditionMedConsom .= ' AND mco.id_factureMedConsom!='.$_GET['idbill'].'';
			}
			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_consuConsom=:idconsu'.$conditionMedConsom.' ORDER BY mco.id_medconsom');
		}else{
			$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_consuConsom=:idconsu AND mco.id_factureMedConsom=0 ORDER BY mco.id_medconsom');
		}
		
		
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_medoc-----------*/
	
		
		if(isset($_POST['idprestaMedoc']))
		{
			
			$idprestamedoc = array();
			$prixmdo = array();
			$addMedoc = array();
			$idmdo = array();
			$autremedoc = array();
			$qteMedoc = array();


			foreach($_POST['idprestaMedoc'] as $medoc)
			{
				$idprestamedoc[] = $medoc;
			}
			
			foreach($_POST['prixprestaMedoc'] as $valMedoc)
			{
				$prixmdo[] = $valMedoc;
			}
			
			foreach($_POST['percentMedoc'] as $valeurMedoc)
			{
				$addMedoc[] = $valeurMedoc;
			}
			
			foreach($_POST['idmedMedoc'] as $valeurmdo)
			{
				$idmdo[] = $valeurmdo;
			}
			
			foreach($_POST['autreMedoc'] as $autrevaluemedoc)
			{
				$autremedoc[] = $autrevaluemedoc;
			}

			foreach($_POST['quantityMedoc'] as $valueMedoc)
			{
				$qteMedoc[] = $valueMedoc;
			}
			
			for($i=0;$i<sizeof($addMedoc);$i++)
			{
				// echo $addMedoc[$i].'_'.$idmdo[$i].'_('.$prixmdo[$i].' : '.$qteMedoc[$i].')<br/>';
				
				$result=$connexion->query('SELECT *FROM '.$presta_assuUpdate.' p WHERE p.id_prestation='.$idprestamedoc[$i].'');
				
				$result->setFetchMode(PDO::FETCH_OBJ);
				
				$comptPresta=$result->rowCount();
				
				if($comptPresta!=0)
				{
					$updatePrixPresta=$connexion->query('UPDATE '.$presta_assuUpdate.' p SET p.prixpresta='.$prixmdo[$i].' WHERE p.id_prestation='.$idprestamedoc[$i].'');
					
					$updatepercent=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc='.$prixmdo[$i].',mdo.prixautreMedoc=0 WHERE mdo.id_medmedoc='.$idmdo[$i].'');
					
				}else{
				
					$results=$connexion->query('SELECT *FROM med_medoc mdo WHERE mdo.id_medmedoc='.$idmdo[$i].'');
				
					$results->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$results->fetch())
					{
						$updatepercent=$connexion->query('UPDATE med_medoc mdo SET mdo.id_assuMedoc='.$_GET['idassu'].',mdo.insupercentMedoc='.$addMedoc[$i].',mdo.prixprestationMedoc=0,mdo.prixautreMedoc='.$prixmdo[$i].', mdo.qteMedoc='.$qteMedoc[$i].' WHERE mdo.id_medmedoc='.$idmdo[$i].'');
					
					}
				}
			}
		}

		if (isset($_GET['finishbtn'])) {
			$conditionMedMedoc = '';
			if (isset($_POST['savebill']) && isset($_POST['idmedMedoc'])) {
				$idmedMedoc2 = array();

				foreach($_POST['idmedMedoc'] as $valeurmdo)
				{
					$idmedMedoc2[] = $valeurmdo;
				}

				$conditionMedMedoc .= ' AND (';
				for ($i=0; $i < sizeof($idmedMedoc2) ; $i++) { 
					if ($i==0) {
						$conditionMedMedoc .= ' mdo.id_medmedoc='.$idmedMedoc2[$i].'';
					}else{
						$conditionMedMedoc .= ' OR mdo.id_medmedoc='.$idmedMedoc2[$i].'';
					}									
				}
				$conditionMedMedoc .= ' )';
			}else{
				$conditionMedMedoc .= ' AND mdo.id_factureMedMedoc!='.$_GET['idbill'].'';
			}
			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_consuMedoc=:idconsu'.$conditionMedMedoc.' ORDER BY mdo.id_medmedoc');
		}else{
			$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_consuMedoc=:idconsu AND mdo.id_factureMedMedoc=0 ORDER BY mdo.id_medmedoc');
		}
		
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idconsu'=>$_GET['idconsu']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
	
	?>
	
	<table style="width:100%; margin-bottom:-10px;padding-top: 10px;padding-bottom: 10px;">
		<tr>
			<td style="text-align:left; width:25%;">
				<h4><?php echo $annee;?></h4>
			</td>
			
			<td style="text-align:center; width:25%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo $numbill;?></h2>
			</td>
			
			<td style="text-align:right;width:auto;">

				<form method="post" target="blank" action="printBill.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&dateconsu=<?php echo $dateconsu;?>&nomassurance=<?php echo $nomassurance;?>&idcardbill=<?php echo $idcardbill;?>&numpolicebill=<?php echo $numpolicebill;?>&adherentbill=<?php echo $adherentbill;?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['createfacture'])){ echo '&createfacture='.$createfacture;}?><?php if(isset($vouchernum)){ echo '&vouchernum='.$vouchernum;}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['dettes'])){ echo '&dettes='.$_GET['dettes'];}?><?php if(isset($_GET['payement'])){ echo '&payement='.$_GET['payement'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}else{ echo '&idbill='.$_GET['idbill'];}?>&updatebill=ok&createBill=<?php echo $createBill;?><?php echo $existdette;?>&finishbtn=ok&numbill=<?php echo $numbill;?>" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
			</td>

			<td style="text-align:left;" class="buttonBill">
				<a href="categoriesbill.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?>&idconsu=<?php echo $_GET['idconsu'];?>&idmed=<?php echo $_GET['idmed'];?>&dateconsu=<?php echo $_GET['dateconsu'];?>&idtypeconsu=<?php echo $_GET['idtypeconsu'];?><?php if(isset($vouchernum)){ echo '&vouchernum='.$vouchernum;}?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&previewprint=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="margin:30px;<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>" class="btn-large-inversed">
					<i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?>
				</a>

				<a href="categoriesbill.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}?>&finishbtn=ok<?php if(isset($_GET['idbill'])){ echo '&idbill='.$_GET['idbill'];}?><?php if(isset($_GET['idmed'])){ echo '&idmed='.$_GET['idmed'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($vouchernum)){ echo '&vouchernum='.$vouchernum;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="margin:30px;<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>" class="btn-large-inversed flashing">
					<i class="fa fa-arrow-left fa-lg fa-fw"></i> <?php echo getString(289);?>
				</a>
				<br>
			</td>
			<td>
				<div id="bip" class="display"></div>				
				<blockquote style="color: green;font-size: 15px;font-weight: bold;<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>" class="buttonBill"><i class="fa fa-check-circle" style="font-size: 20px;"></i> Bill Done</blockquote>
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
						<th style="width:10%;">Balance '.$nomassurance.'</th>
						<th style="width:10%;">Patient ('.$bill.'%)</th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>


				<tbody>';
				
			$TotalpatientPrice=0;
			$TotalpatientBalance=0;
			
					while($ligneConsult=$resultConsult->fetch())
					{
					
						$billpercent=$ligneConsult->insupercent;
						
						$idassu=$ligneConsult->id_assuConsu;
		$typeconsult .= '<tr style="text-align:center;">
						<td>';
						
						
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
					$TotaluapPrice =  $uapPrice;

			$typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
						</td>';
						
						
			// if($ligneConsult->id_factureConsult!=NULL)
			// {
			// 	$typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
			// }
			
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
				$TotaluapPrice= $TotaluapPrice + $uapPrice;
				
			$typeconsult .= $uapPrice.'<span style="font-size:70%; font-weight:normal;">Rwf</span>
			</td>';
			
			if($ligneConsult->id_factureConsult!=NULL)
			{
				$typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
			}
			
			$typeconsult .= '</tr>';

							}
						}
			?>
			<input type="hidden" name="idConsu[]" value="<?php echo $ligneConsult->id_consu;?>">
			<?php	
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
								->setCellValue('D'.(9+$i).'', ''.$TotalpatientPrice.'');

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
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
						
						if($ligneMedConsult->id_prestationConsu==NULL AND ($ligneMedConsult->prixautreConsu!=0))
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
			?>
				<input type="hidden" name="idmedConsu[]" value="<?php echo $ligneMedConsult->id_medconsu;?>">
			<?php			
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
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
						
						if(($ligneMedSurge->id_prestationSurge=="" OR $ligneMedSurge->id_prestationSurge==0) AND ($ligneMedSurge->prixautrePrestaS!=0 ))
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
			?>
				<input type="hidden" name="idmedSurge[]" value="<?php echo $ligneMedSurge->id_medsurge;?>">
			<?php			
						
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
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
			?>
				<input type="hidden" name="idmedKine[]" value="<?php echo $ligneMedKine->id_medkine;?>">
			<?php			
						
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
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
					$patientBalance = $patientPrice;
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

						if(($ligneMedOrtho->id_prestationOrtho=="" OR $ligneMedOrtho->id_prestationOrtho==0) AND ($ligneMedOrtho->prixautrePrestaO!=0))
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

			?>
				<input type="hidden" name="idmedOrtho[]" value="<?php echo $ligneMedOrtho->id_medortho;?>">
			<?php			
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
							->setCellValue('B'.(14+$i+$x).'', 'Psycho')
							->setCellValue('C'.(14+$i+$x).'', 'Price')
							->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
							->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');

			?>

			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead>
					<tr>
						<th><?php echo 'Psycho';?></th>
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
			?>
				<input type="hidden" name="idmedPyscho[]" value="<?php echo $ligneMedPsy->id_medpsy;?>">
			<?php			

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
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
						
						if(($ligneMedInf->id_prestation=="" OR $ligneMedInf->id_prestation==0) AND ($ligneMedInf->prixautrePrestaM!=0))
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
			?>
				<input type="hidden" name="idmedInf[]" value="<?php echo $ligneMedInf->id_medinf;?>">
			<?php		
						
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
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
					$patientBalance = $patientPrice;
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
						
						if($ligneMedLabo->id_prestationExa=="" AND ($ligneMedLabo->prixautreExamen!=0))
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
			?>
				<input type="hidden" name="idmedLab[]" value="<?php echo $ligneMedLabo->id_medlabo;?>">
			<?php			
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
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
						
						if($ligneMedRadio->id_prestationRadio=="" AND ($ligneMedRadio->prixautreRadio!=0))
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
			?>
				<input type="hidden" name="idmedRad[]" value="<?php echo $ligneMedRadio->id_medradio;?>">
			<?php				
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
						<th>Consommables</th>
						<th></th>
						<th style="width:4%">Qty</th>
						<th style="width:8%">P/U <?php echo $nomassurance;?></th>
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
						
						$resultPresta->setFetchMode(PDO::FETCH_OBJ);

						$comptPresta=$resultPresta->rowCount();
						
						if($lignePresta=$resultPresta->fetch())
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
						
						if($ligneMedConsom->id_prestationConsom==0 AND ($ligneMedConsom->prixautreConsom!=0))
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
			?>
				<input type="hidden" name="idmedConsom[]" value="<?php echo $ligneMedConsom->id_medconsom;?>">
			<?php				
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
						<th style="width:8%">P/U <?php echo $nomassurance;?></th>
						<th style="width:10%;">Balance <?php echo $nomassurance;?></th>
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
					
					$resultPresta->setFetchMode(PDO::FETCH_OBJ);

					$comptPresta=$resultPresta->rowCount();
					
					if($lignePresta=$resultPresta->fetch())
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
					
					if($ligneMedMedoc->id_prestationMedoc==0 AND ($ligneMedMedoc->prixautreMedoc!=0))
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
			?>
				<input type="hidden" name="idmedMedoc[]" value="<?php echo $ligneMedMedoc->id_medmedoc;?>">
			<?php			
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

function ShowTxtDette(dette)
{
	var payement=document.getElementById('payement').value;

	if( payement =='')
	{
		document.getElementById('dettes').style.display='inline';
	}else{
		document.getElementById('dettes').style.display='none';
		document.getElementById('dettes').value='';
	}

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
<script>
	var counter = 3;
	var intervalId = null;
	function finish() {
	  clearInterval(intervalId);
	  document.getElementById("bip").innerHTML = "TERMINE!";	
	  window.location.href = "patients1.php?caissier=ok&francais=francais";
	}
	function bip() {
	    counter--;
	    if(counter == 0) finish();
	    else {	
	        document.getElementById("bip").innerHTML = counter + " secondes restantes";
	    }	
	}
	function start(){
	  intervalId = setInterval(bip, 1000);
	}	
</script>

	</div>

	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:10px; border-radius:3px; font-size:85%;">
	
		<table class="printPreview" cellspacing="0" style="margin:auto;">
			<thead>
				<tr>
					<th style="width:15%"></th>
					<th style="width:15%;">Total balance <?php echo $nomassurance;?></th>
					<th style="width:15%;">Patient <?php echo '('.$bill.'%)';?></th>
					<th style="width:15%;">Patient balance</th>
					<th style="width:15%;">Insurance</th>
				</tr>
			</thead>

			<tbody>
				<tr style="text-align:center;">
					<td style="font-size: 110%; font-weight: bold;">Final Balance</td>
					<td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPatientPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 110%; font-weight: bold;"><?php
						
						$patientPayed = $TotalGnlPatientBalance - $dettes;
						echo $TotalGnlPatientBalance;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
						
					</td>
					<td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlInsurancePrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
				</tr>
			</tbody>
		</table>
		
		<?php
		if(!isset($_GET['payement']))
		{
		?>
			<table class="printPreview" cellspacing="0" style="margin-top:5px;border:none;" align="center">
				<tr style="text-align:center;">

					<td style="font-size: 110%; font-weight: bold;text-align:right;" class="buttonBill">
						Payement
					</td>
				
					<td style="font-size: 110%; font-weight: bold;text-align:left;" class="buttonBill">
						<select style="margin:5px;width:50%;" name="payement" id="payement" onclick="ShowTxtDette()">

							<option value='<?php echo $TotalGnlPatientBalance;?>'>Total</option>
							<option value="" <?php if($idBillDebtCount!=0) echo 'selected="selected"'; ?>>Partiel</option>
						</select>
					</td>
					
					<td style="font-size: 110%; font-weight: bold;text-align:left;" class="buttonBill">
						<input type = "text" name="dettes" id="dettes" value="<?php if($idBillDebtCount!=0) { echo $patientPayed;}else{ echo '';} ?>" style="width:auto;margin:5px;<?php if($idBillDebtCount!=0) { echo 'display:inline;';}else{ echo 'display:none;';}?>" placeholder="Montant payé ici.."/>
					</td>
				</tr>
			</table>
		<?php
		}
				
				
		if($idBillDebtCount!=0)
		{
		?>
			<table class="printPreview" cellspacing="0" style="margin-top:5px;border:none;">
				<tr class="buttonBill">
					<td style="font-size: 110%; font-weight: bold;border:none;"></td>

					<td style="font-size: 110%; font-weight: bold;text-align:right;border:2px solid #e8e8e8;width:10%;">
						<?php
						echo '<span>Payed : </span><span style="color:gray">'.$patientPayed.'</span>';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>

					<td style="font-size: 110%; font-weight: bold;text-align:left; border:2px solid #e8e8e8;width:10%;">
						<?php
						echo '<span>Debt : </span><span style="color:gray">'.$dettes.'</span>';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>

				</tr>
			</table>
		<?php
		}
		?>
		</form>
		
	</div>
	
	<div class="account-container" style="margin:20px auto auto; width:90%; background:#fff; border-radius:3px; font-size:85%;">
	
		<?php
		$footer = '

			<table style="width:100%">
				
				<tr>
					<td style="text-align:left; margin: 10px auto auto; width:200px; background:#fff; padding-bottom:20px; border-bottom:1px solid #333;">
						<span style="font-weight:bold">Patient Signature</span>
					</td>
					
					<td style="text-align:right;">
						 Done by : <span style="font-weight:bold">'.$fullnameDoc.'</span>
					</td>
					
				</tr>
				
			</table>';

		echo $footer;
		?>
		
	</div>
	
<?php

	if(isset($_GET['updatebill']))
		{
			
			/*----------Update Bills----------------*/
			
			/*if($comptConsult!=0)
			{
				*/
				if($_GET['createBill'] == 1){
					$checkIdBilling=$connexion->query('SELECT *FROM bills b ORDER BY b.id_bill DESC LIMIT 1');
					
					$checkIdBilling->setFetchMode(PDO::FETCH_OBJ);
					
					$ligne=$checkIdBilling->fetch();
				}else{
					if($_GET['createBill'] == 0){

						$checkIdBilling=$connexion->prepare('SELECT *FROM bills b WHERE id_bill=:idbill');
						$checkIdBilling->execute(array('idbill'=>$_GET['idbill']));
						
						$checkIdBilling->setFetchMode(PDO::FETCH_OBJ);
						
						$ligne=$checkIdBilling->fetch();

					}
				}
				
				$idBilling = $ligne->id_bill;
				$lastDette = $ligne->dette;
				$lastAmountpaid = $ligne->amountpaid;
					
				
				if(isset($_POST['payement']))
				{
					$payement=$_POST['payement'];
				}else{
					$payement=$_GET['payement'];
				}
				
				if(isset($_POST['dettes']))
				{
					if($_POST['dettes'] < $TotalGnlPatientBalance AND $_POST['dettes'] !="" AND $payement!= $TotalGnlPatientBalance)
					{
						$newDette = ($TotalGnlPatientBalance - $_POST['dettes']) + $lastDette;
						$amountpaid=$_POST['dettes'] + $lastAmountpaid;
						$detteIdIn=$_SESSION['id'];
						$detteIdOut=NULL;
					}else{
						$newDette=NULL;
						$amountpaid=NULL;
						$detteIdIn=NULL;
						$detteIdOut=NULL;
					}
					
					$montantpaye=$_POST['dettes'];
				}else{
					$montantpaye=$_GET['dettes'];
				}
				
				
				// echo $montantpaye.'=>'.$payement.' != '.$TotalGnlPatientBalance.'---UPDATE bills b SET b.totaltypeconsuprice='.$TotalConsult.', b.totalgnlprice='.$TotalGnlPrice.', b.numero='.$_GET['num'].', b.nomassurance='.$nomassurance.', b.idcardbill='.$idcardbill.', b.numpolicebill='.$numpolicebill.', b.adherentbill='.$adherentbill.', b.billpercent='.$bill.', b.codecashier='.$_SESSION['codeCash'].', b.dette='.$newDette.', b.detteIdIn='.$detteIdIn.', b.amountpaid='.$amountpaid.', b.detteIdOut='.$detteIdOut.' WHERE b.id_bill='.$idBilling.'';
				
				$updateIdBill=$connexion->prepare('UPDATE bills b SET b.totaltypeconsuprice=:totaltypeconsu, b.totalgnlprice=:totalgnl, b.numero=:num, b.nomassurance=:nomassu, b.idcardbill=:idcardbill, b.numpolicebill=:numpolicebill, b.adherentbill=:adherentbill, b.billpercent=:percentIdbill, b.codecashier=:codecash, b.dette=:dette, b.detteIdIn=:idIn, b.amountpaid=:amountpaid, b.detteIdOut=:idOut WHERE b.id_bill=:idbill');
				
				$updateIdBill->execute(array(
					'idbill'=>$idBilling,
					'totaltypeconsu'=>$TotalConsult,
					'totalgnl'=>$TotalGnlPrice,
					'num'=>$_GET['num'],
					'nomassu'=>$nomassurance,
					'idcardbill'=>$idcardbill,
					'numpolicebill'=>$numpolicebill,
					'adherentbill'=>$adherentbill,
					'percentIdbill'=>$bill,
					'codecash'=>$_SESSION['codeCash'],
					'dette'=>$newDette,
					'idIn'=>$detteIdIn,
					'amountpaid'=>$amountpaid,
					'idOut'=>$detteIdOut
				
				))or die( print_r($connexion->errorInfo()));
				
				
				
				//echo '<script text="text/javascript">document.location.href="printBill.php?num='.$_GET['num'].'&cashier='.$_SESSION['codeCash'].'&idconsu='.$_GET['idconsu'].'&dateconsu='.$_GET['dateconsu'].'&nomassurance='.$_GET['nomassurance'].'&idcardbill='.$_GET['idcardbill'].'&numpolicebill='.$_GET['numpolicebill'].'&adherentbill='.$_GET['adherentbill'].'&idbill='.$idBilling.'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idmed='.$_GET['idmed'].'&dettes='.$montantpaye.'&payement='.$_GET['payement'].'&vouchernum='.$vouchernum.''.$existdette.'&finishbtn=ok&createBill=0"</script>';
				
			/*}else{
											
				$checkIdBill=$connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idbill');

				$checkIdBill->execute(array(
				'idbill'=>$_GET['idbill']
				));

				$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
				
				$ligne=$checkIdBill->fetch();
				
				$idBilling = $ligne->id_bill;
				$lastDette = $ligne->dette;
				$lastAmountpaid = $ligne->amountpaid;
					
				
				if(isset($_POST['payement']))
				{
					$payement=$_POST['payement'];
				}else{
					$payement=$_GET['payement'];
				}
				
				if(isset($_POST['dettes']))
				{
					if($_POST['dettes'] < $TotalGnlPatientBalance AND $_POST['dettes'] !="" AND $payement!= $TotalGnlPatientBalance)
					{
						$newDette = ($TotalGnlPatientBalance - $_POST['dettes']) + $lastDette;
						$amountpaid=$_POST['dettes'] + $lastAmountpaid;
						$detteIdIn=$_SESSION['id'];
						$detteIdOut=NULL;
					}else{
						$newDette=NULL;
						$amountpaid=NULL;
						$detteIdIn=NULL;
						$detteIdOut=NULL;
					}
					
					$montantpaye=$_POST['dettes'];
				}else{
					$montantpaye=$_GET['dettes'];
				}
				
				
				// echo $montantpaye.'=>'.$payement.' != '.$TotalGnlPatientBalance.'---UPDATE bills b SET b.totaltypeconsuprice='.$TotalConsult.', b.totalgnlprice='.$TotalGnlPrice.', b.numero='.$_GET['num'].', b.nomassurance='.$nomassurance.', b.idcardbill='.$idcardbill.', b.numpolicebill='.$numpolicebill.', b.adherentbill='.$adherentbill.', b.billpercent='.$bill.', b.codecashier='.$_SESSION['codeCash'].', b.dette='.$newDette.', b.detteIdIn='.$detteIdIn.', b.amountpaid='.$amountpaid.', b.detteIdOut='.$detteIdOut.' WHERE b.id_bill='.$idBilling.'';
				
				$updateIdBill=$connexion->prepare('UPDATE bills b SET b.totaltypeconsuprice=:totaltypeconsu, b.totalgnlprice=:totalgnl, b.numero=:num, b.nomassurance=:nomassu, b.idcardbill=:idcardbill, b.numpolicebill=:numpolicebill, b.adherentbill=:adherentbill, b.billpercent=:percentIdbill, b.codecashier=:codecash, b.dette=:dette, b.detteIdIn=:idIn, b.amountpaid=:amountpaid, b.detteIdOut=:idOut WHERE b.id_bill=:idbill');
				
				$updateIdBill->execute(array(
					'idbill'=>$idBilling,
					'totaltypeconsu'=>$TotalConsult,
					'totalgnl'=>$TotalGnlPrice,
					'num'=>$_GET['num'],
					'nomassu'=>$nomassurance,
					'idcardbill'=>$idcardbill,
					'numpolicebill'=>$numpolicebill,
					'adherentbill'=>$adherentbill,
					'percentIdbill'=>$bill,
					'codecash'=>$_SESSION['codeCash'],
					'dette'=>$newDette,
					'idIn'=>$detteIdIn,
					'amountpaid'=>$amountpaid,
					'idOut'=>$detteIdOut
				
				))or die( print_r($connexion->errorInfo()));
				
				
				//echo '<script text="text/javascript">document.location.href="printBill.php?num='.$_GET['num'].'&cashier='.$_SESSION['codeCash'].'&idconsu='.$_GET['idconsu'].'&dateconsu='.$_GET['dateconsu'].'&nomassurance='.$_GET['nomassurance'].'&idcardbill='.$_GET['idcardbill'].'&numpolicebill='.$_GET['numpolicebill'].'&adherentbill='.$_GET['adherentbill'].'&idbill='.$idBilling.'&idtypeconsu='.$_GET['idtypeconsu'].'&idassu='.$_GET['idassu'].'&idmed='.$_GET['idmed'].'&dettes='.$montantpaye.'&payement='.$_GET['payement'].'&vouchernum='.$vouchernum.''.$existdette.'&finishbtn=ok&createBill=0"</script>';
				
			}*/
		}

}else{
	
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
	
	/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&cashier=CSC15A01&dateconsu=2015-09-19");
	file_put_contents("toPDF.html", $file); */

}
?>
</body>

</html>