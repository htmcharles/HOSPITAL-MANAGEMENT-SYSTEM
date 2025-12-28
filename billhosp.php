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


$heure = date('H').' : '.date('i').' : '.date('s');

// echo $heure;
// echo showBN();


$numPa=$_GET['num'];
if (isset($_GET['idresto'])) {
	$idresto=$_GET['idresto'];
}
if (isset($_GET['idtourdesalle'])) {
	$idtourdesalle=$_GET['idtourdesalle'];
}
$hospId=$_GET['idhosp'];

$checkIdBill=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp ORDER BY ph.id_factureHosp LIMIT 1');

$checkIdBill->execute(array(
	'idhosp'=>$_GET['idhosp']
));

$comptidBill=$checkIdBill->rowCount();

// echo $comptidBill;

if($comptidBill != 0)
{
	$checkIdBill->setFetchMode(PDO::FETCH_OBJ);
	
	$ligne=$checkIdBill->fetch();
	
	$idBillingHosp = $ligne->id_hosp;
	$oldorgBill = $ligne->idorgBillHosp;
	
	$numbill = $ligne->id_factureHosp;
	$bill=$ligne->insupercent_hosp;
	$nomassurance=$ligne->nomassuranceHosp;
	$idcard=$ligne->idcardbillHosp;
	$numpolice=$ligne->numpolicebillHosp;
	$adherent=$ligne->adherentbillHosp;
	
	$datebill = date('d-M-Y', strtotime($ligne->dateSortie));
	$createBill = 0;
	
	if($ligne->codecashierHosp!="")
	{
		$idDoneby=$ligne->codecashierHosp;

		$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u AND c.codecashier=:operation');
		$resultatsDoneby->execute(array(
			'operation'=>$idDoneby
		));
		
		$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
		if($ligneDoneby=$resultatsDoneby->fetch())
		{
			$doneby = $ligneDoneby->full_name;
		}
		
	}elseif($ligne->codecoordiHosp!=""){
		
		$idDoneby=$ligne->codecoordiHosp;
		
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
	
	
}
?>

<!doctype html>
<html lang="en">
<noscript>
	Cette page requiert du Javascript.
	Veuillez l'activer pour votre navigateur
</noscript>
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
<body onload="window.print()">
<?php
}
?>

<?php
$connected=$_SESSION['connect'];
$idCashier=$_SESSION['id'];
$manager=$_GET['manager'];

if($connected==true AND isset($_SESSION['codeCash']))
{
	
	$resultatsManager=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u and c.codecashier=:operation');
	$resultatsManager->execute(array(
		'operation'=>$manager
	));
	
	$resultatsManager->setFetchMode(PDO::FETCH_OBJ);
	if($ligneManager=$resultatsManager->fetch())
	{
		$codecashier = $ligneManager->codecashier;
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
				<img src="barcode/png/barcode'.$codecashier.'.png" style="height:auto;"/>
			</td>
			
		</tr>
		
	</table>';
		
		echo $barcode;
		?>
		
		<?php
		
		$resultatHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:idhosp');
		$resultatHosp->execute(array(
			'idhosp'=>$hospId
		));
		
		$resultatHosp->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneHosp=$resultatHosp->fetch())
		{
			$idassurance=$ligneHosp->id_assuHosp;
			$datehosp= date('d-M-Y', strtotime($ligneHosp->dateEntree));
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
					<span style="font-weight:bold">'.$lignePatient->nom_u.' '.$lignePatient->prenom_u.'</span><br/>
					Gender: <span style="font-weight:bold">'.$sexe.'</span><br/>
					Adress: <span style="font-weight:bold">'.$adresse.'</span>
				</td>
				
				<td style="text-align:center;">
					 Organisation: <span style="font-weight:bold">';
	
				if (isset($_POST['org'])) {
					$org = $_POST['org'];
	
				} else {
					$org = $oldorgBill;
				}
	
				$resultOrg = $connexion->prepare('SELECT *FROM organisations o WHERE o.id_org=:orgId');
	
				$resultOrg->execute(array(
					'orgId' => $org
				));
	
				$resultOrg->setFetchMode(PDO::FETCH_OBJ);
	
				if ($ligneOrg = $resultOrg->fetch()) {
					$idorg = $ligneOrg->id_org;
					$nomorg = $ligneOrg->nomOrg;
	
					if($ligneOrg->lieuOrg!=NULL)
					{
						$lieuorg = ' _ '.$ligneOrg->lieuOrg;
					}else{
						$lieuorg = '';
					}
	
					$userinfo .= '' . $nomorg . '' . $lieuorg . '</span><br/>';
				}
	
				$userinfo .= '
     
					Insurance type: <span style="font-weight:bold">';
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
				'assuId'=>$lignePatient->id_assurance
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);
			
			if($ligneAssu=$resultAssurance->fetch())
			{
				$userinfo .= ''.$nomassurance.'</span><br/>';
				
				if($idassurance!=1)
				{
					if($idcard!="")
					{
						$userinfo .= 'N° insurance card:
						<span style="font-weight:bold">'.$idcard;
						
					}elseif($lignePatient->carteassuranceid!=""){
						
						$userinfo .= 'N° insurance card:
						<span style="font-weight:bold">'.$lignePatient->carteassuranceid;
					}
					
					if($numpolice!="")
					{
						$userinfo .= '</span><br/>
						
						N° police:
						<span style="font-weight:bold">'.$numpolice;
						
					}elseif($lignePatient->numeropolice!=""){
						
						$userinfo .= '</span><br/>
						
						N° police:
						<span style="font-weight:bold">'.$lignePatient->numeropolice;
					}
					
					if($adherent!="")
					{
						$userinfo .= '</span><br/>
						
						Principal member:
						<span style="font-weight:bold">'.$adherent;
						
					}elseif($lignePatient->adherent!=""){
						
						$userinfo .= '</span><br/>
						
						Principal member:
						<span style="font-weight:bold">'.$lignePatient->adherent;
					}
				}
			}
			
			$userinfo .='</span>
				</td>
				
				<td style="text-align:right;">
					Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
					Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
					Date of Hospitalisation: <span style="font-weight:bold">'.$datehosp.'</span>
					
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
				->setCellValue('B4', ''.$nomassurance.' '.$bill.'%')
				->setCellValue('F1', 'Bill #')
				->setCellValue('G1', ''.$numbill.'')
				->setCellValue('F2', 'Done by')
				->setCellValue('G2', ''.$doneby.'')
				->setCellValue('F3', 'Date')
				->setCellValue('G3', ''.$datebill.'');
			
		}
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		$resultHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:hospId AND ph.numero=:num AND ph.dateEntree=:datehosp AND ph.id_assuHosp=:idassu ORDER BY ph.id_hosp');
		$resultHosp->execute(array(
			'hospId'=>$hospId,
			'num'=>$numPa,
			'idassu'=>$idassurance,
			'datehosp'=>date('Y-m-d', strtotime($datehosp))
		));
		
		$resultHosp->setFetchMode(PDO::FETCH_OBJ);
		
		$comptHosp=$resultHosp->rowCount();
		
		$TotalHospPayed = 0;
		$TotalHospPayedCCO = 0;
		$TotalHosp = 0;
		$TotalHospCCO = 0;

		
		/*-------Requête pour AFFICHER Med_consult-----------*/
		
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc, patients_hosp p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_hospMed=:idconsu AND p.id_hosp=mc.id_hospMed AND mc.datehosp!="0000-00-00" AND mc.id_factureMedConsu!="" ORDER BY mc.id_medconsu');
		$resultMedConsult->execute(array(
			'num'=>$numPa,
			'idconsu'=>$_GET['idhosp']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedConsult=$resultMedConsult->rowCount();
		
		$TotalMedConsult = 0;
		$TotalMedConsultPayed = 0;
		
		
		
		/*-------Requête pour AFFICHER med_surge_hosp-----------*/
		
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms, patients_hosp p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_hospSurge=:idhosp AND p.id_hosp=ms.id_hospSurge AND ms.datehosp!="0000-00-00" AND ms.id_factureMedSurge!=""  ORDER BY ms.id_medsurge');
		$resultMedSurge->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedSurge=$resultMedSurge->rowCount();
		
		$TotalMedSurge = 0;
		$TotalMedSurgePayed = 0;
		
		
		
		
		
		/*-------Requête pour AFFICHER Med_inf-----------*/
		
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi, patients_hosp p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_hospInf=:idhosp AND p.id_hosp=mi.id_hospInf AND mi.id_factureMedInf!="" ORDER BY mi.id_medinf');
		$resultMedInf->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedInf=$resultMedInf->rowCount();
		
		$TotalMedInf = 0;
		$TotalMedInfPayed = 0;
		
		
		
		
		/*-------Requête pour AFFICHER Med_labo-----------*/
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml, patients_hosp p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_hospLabo=:idhosp AND p.id_hosp=ml.id_hospLabo AND ml.id_factureMedLabo!="" ORDER BY ml.id_medlabo');
		$resultMedLabo->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
		$TotalMedLaboPayed = 0;
		
		
		/*-------Requête pour AFFICHER Med_radio-----------*/
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr, patients_hosp p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_hospRadio=:idhosp AND p.id_hosp=mr.id_hospRadio AND mr.id_factureMedRadio!="" ORDER BY mr.id_medradio');
		$resultMedRadio->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		$TotalMedRadioPayed = 0;
		/*-------Requête pour AFFICHER med_kine_hosp-----------*/
		
		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk, patients_hosp p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_hospKine=:idhosp AND p.id_hosp=mk.id_hospKine AND mk.id_factureMedKine!="" ORDER BY mk.id_medKine');
		$resultMedKine->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedKine=$resultMedKine->rowCount();
		
		$TotalMedKine = 0;
		$TotalMedKinePayed = 0;
		
		
		
		
		/*-------Requête pour AFFICHER Med_consom-----------*/
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco, patients_hosp p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_hospConsom=:idhosp AND p.id_hosp=mco.id_hospConsom AND mco.id_factureMedConsom!="" ORDER BY mco.id_medconsom');
		$resultMedConsom->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedConsom=$resultMedConsom->rowCount();
		
		$TotalMedConsom = 0;
		$TotalMedConsomPayed = 0;
		
		
		
		/*-------Requête pour AFFICHER Med_medoc-----------*/
		
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo, patients_hosp p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_hospMedoc=:idhosp AND p.id_hosp=mdo.id_hospMedoc AND mdo.id_factureMedMedoc!="" ORDER BY mdo.id_medmedoc');
		$resultMedMedoc->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
		$TotalMedMedocPayed = 0;
		
		
		
		/*-------Requête pour AFFICHER med_ortho_hosp-----------*/
		
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo, patients_hosp p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_hospOrtho=:idhosp AND p.id_hosp=mo.id_hospOrtho AND mo.id_factureMedOrtho!="" ORDER BY mo.id_medOrtho');
		$resultMedOrtho->execute(array(
			'num'=>$numPa,
			'idhosp'=>$_GET['idhosp']
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);
		
		$comptMedOrtho=$resultMedOrtho->rowCount();
		
		$TotalMedOrtho = 0;
		$TotalMedOrthoPayed = 0;
		
		?>
		
		<table style="width:100%; margin-bottom:-5px">
			<tr>
				<td style="text-align:left; width:33%;">
					<h4><?php echo $datebill;?></h4>
				</td>
				
				<td style="text-align:center; width:43%;">
					<h2 style="font-size:150%; font-weight:600;">Hospitalisation bill-exit n° <?php echo $numbill;?></h2>
				</td>
				
				<td style="text-align:right;width:33%;">
					
					<form method="post" action="billhosp.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeCash'];?>&datehosp=<?php echo $datehosp;?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['numroom'])){ echo '&numroom='.$_GET['numroom'];}?><?php if(isset($_GET['numlit'])){ echo '&numlit='.$_GET['numlit'];}?><?php if(isset($_GET['nomassurance'])){ echo '&nomassurance='.$_GET['nomassurance'];}?><?php if(isset($_GET['billpercent'])){ echo '&billpercent='.$_GET['billpercent'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?>&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">
						
						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
					</form>
				</td>
				
				<td class="buttonBill">
					<a href="listfacture.php?codeCoord=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>;margin:5px;">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</a>
					
					<a href="listfacture.php?codeCoord=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>;margin:5px;">
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
			
			$TotalGnlPricePayed=0;
			$TotalGnlPatientPricePayed=0;
			$TotalGnlPatientBalancePayed=0;
			$TotalGnlInsurancePricePayed=0;
			
			$i=0;
			$x=0;
			$y=0;
			$z=0;
			
			if($comptHosp != 0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B8', 'Type of consultation')
					->setCellValue('C8', 'Price')
					->setCellValue('D8', 'Patient Price')
					->setCellValue('E8', 'Insurance Price');
				
				
				?>
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:0 auto 25px;">
					<thead>
						<tr>
							<th style="width:5%;text-align:center;">Room</th>
							<!--<th style="width:20%;text-align:center;">Type</th>-->
							<th style="width:10%;text-align:center;">Date In</th>
							<th style="width:13%;text-align:center;">Date Out</th>
							<th style="width:5%;text-align:center;">Days</th>
							<th style="width:8%;text-align:center;" class="buttonBill">Price/day</th>
							<th style="width:8%;text-align:center;">Balance <?php echo $nomassurance;?></th>
							<th style="width:8%;text-align:center;">Percent</th>
							<th style="width:13%;text-align:center;">Patient <?php echo '('.$bill.'%)'?></th>
							<th style="width:13%;text-align:center;">Patient balance</th>
							<th style="width:13%;text-align:center;">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotalPatientPriceHospPayed=0;
						$TotalInsurancePriceHospPayed=0;
						
						$TotalPatientPriceHosp=0;
						$TotalInsurancePriceHosp=0;
						
						while($ligneHosp=$resultHosp->fetch())
						{
							
							$billpercent=$ligneHosp->insupercent_hosp;
							
							$idassu=$ligneHosp->id_assuHosp;
							?>
							<tr style="font-weight:bold;">
								<td style="text-align:center;"><?php echo $ligneHosp->numroomPa;?></td>
							
							<?php
							
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
							
							
							
							$resultPresta=$connexion->prepare('SELECT *FROM rooms r,'.$presta_assu.' p WHERE r.numroom=:numroomPa AND r.id_prestationHosp=p.id_prestation');
							
							$resultPresta->execute(array(
								'numroomPa'=>$ligneHosp->numroomPa
							));
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($comptPresta!=0)
							{
								if($lignePresta=$resultPresta->fetch())
								{									
									if($lignePresta->namepresta!='')
									{
										$nameprestaHosp=$lignePresta->namepresta;
									}else{
										
										if($lignePresta->nompresta!='')
										{
											$nameprestaHosp=$lignePresta->nompresta;
										}
									}
									
									$prixPrestaHosp = $lignePresta->prixpresta;
									
									
									?>
									
									<td style="text-align:center;">
										<?php
										echo date('d-M-Y', strtotime($ligneHosp->dateEntree));
										?>
									</td>
									<td style="text-align:center;">
										<?php
										echo date('d-M-Y', strtotime($ligneHosp->dateSortie));
										?>
									</td>
									
									<td style="text-align:center;">
										<?php
											$dateIn=strtotime($ligneHosp->dateEntree);
											$dateOut=strtotime($ligneHosp->dateSortie);
											
											$datediff= abs($dateOut - $dateIn);

											$nbrejrs= floor($datediff /(60*60*24));
											
											if($nbrejrs==0)
											{
												$nbrejrs=1;
											}
											
											if($nbrejrs==1)
											{
												echo $nbrejrs.' day';
											}else{
												echo $nbrejrs.' days';
											}
										?>
									</td>
									
									<td style="text-align:center;" class="buttonBill"><?php echo $ligneHosp->prixroom;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
									
									
									<td style="text-align:center;">
										<?php
										$balanceHosp=$ligneHosp->prixroom * $nbrejrs;
										
										echo $balanceHosp;
										
										if($ligneHosp->statusPaHosp!=1)
										{
											$TotalHospPayed=$TotalHospPayed + $balanceHosp;
										}else{
											
											$TotalHosp=$TotalHosp + $balanceHosp;
										}
										
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<?php
									
									$patientPriceHosp=($balanceHosp * $billpercent)/100;
									$insurancePriceHosp= $balanceHosp - $patientPriceHosp;
									
										$TotalPatientPriceHosp=$TotalPatientPriceHosp + $patientPriceHosp;
										
										
										$TotalInsurancePriceHosp = $TotalInsurancePriceHosp + $insurancePriceHosp;
									
									?>
									
									<td style="text-align:center;"><?php echo $billpercent;?>%</td>
									
									<?php
									?>
									
									<td style="text-align:center;"><?php echo $patientPriceHosp;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
									
									<td>
										<?php
										$patientBalanceHosp = $patientPriceHosp;
										echo $patientBalanceHosp.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td style="text-align:center;">
										<?php
										echo $insurancePriceHosp;
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									</tr>
									<?php
								}
								
							}
							
							$arrayConsult[$i][0]=$nameprestaHosp;
							$arrayConsult[$i][1]=$prixPrestaHosp;
							$arrayConsult[$i][2]=$patientPriceHosp;
							$arrayConsult[$i][3]=$insurancePriceHosp;
							
							$i++;
							
							$objPHPExcel->setActiveSheetIndex(0)
								->fromArray($arrayConsult,'','B9');
							
						}
						
						?>
					</tbody>
				</table>
				<?php
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.(9+$i).'', ''.$balanceHosp.'')
					->setCellValue('D'.(9+$i).'', ''.$patientPriceHosp.'')
					->setCellValue('E'.(9+$i).'', ''.$insurancePriceHosp.'');
				
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
							<th style="width:20%" colspan=13>Surgery</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotalpatientPricePayed=0;
						$TotaluapPricePayed=0;
						
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
								
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedSurge->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedSurge=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											$nameprestaMedSurge=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
										}
										
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteSurge=$ligneMedSurge->qteSurge;
										echo $qteSurge;
										?>
									</td>
									
									<td>
										<?php
										$prixPresta = $ligneMedSurge->prixprestationSurge;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteSurge;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedSurge = $TotalMedSurge + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
									
									<td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
									if($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0 AND $ligneMedSurge->id_factureMedSurge!=$numbill)
										{
											echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
										}
									?>
									</td>
								</tr>
								<?php
							}
							
							if($ligneMedSurge->id_prestationSurge=="" AND $ligneMedSurge->prixautrePrestaS!=0)
							{
								?>
								<tr style="text-align:center;">
									<td><?php echo $ligneMedSurge->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedSurge=$ligneMedSurge->autrePrestaM;
										
										echo $ligneMedSurge->autrePrestaM;
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteSurge=$ligneMedSurge->qteSurge;
										echo $qteSurge;
										?>
									</td>
									
									<td>
										<?php
										$prixPrestaCCO = $ligneMedSurge->prixautrePrestaSCCO;
										echo $prixPrestaCCO.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
									<td>
										<?php
										$prixPresta = $ligneMedSurge->prixautrePrestaS;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteSurge;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										if ($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0  AND $ligneMedSurge->id_factureMedSurge!=$numbill) 
										{
											
										}else{
											$TotalMedSurge = $TotalMedSurge + $balance;
											$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
											$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
											$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										}
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									<td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										if($ligneMedSurge->id_factureMedSurge!="" AND $ligneMedSurge->id_factureMedSurge!=0  AND $ligneMedSurge->id_factureMedSurge!=$numbill)
										{
											echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
										}
										?>
									</td>
								</tr>
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
							<td colspan=5 ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
									echo $TotalMedSurge.'';
										
									$TotalGnlPrice=$TotalGnlPrice + $TotalMedSurge;
									$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedSurgePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
									echo $TotalpatientPrice.'';
									
									$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
									
									$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
									echo $TotalpatientBalance.'';
									
									$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
									echo $TotaluapPrice.'';
									
									$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
									
									$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
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
							<th style="width:20%" colspan=13>Nursing Care</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" >Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
								
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedInf->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedInf=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											$nameprestaMedInf=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
										}
										
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteInf=$ligneMedInf->qteInf;
										echo $qteInf;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedInf->prixprestation;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteInf;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedInf = $TotalMedInf + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
									
									<td><?php echo $ligneMedInf->insupercentInf;?>%</td>
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>

								</tr>
								<?php
							}
							
							if($ligneMedInf->id_prestation=="" AND $ligneMedInf->prixautrePrestaM!=0)
							{
								?>
								<tr style="text-align:center;">
									<td><?php echo $ligneMedInf->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedInf=$ligneMedInf->autrePrestaM;
										
										echo $ligneMedInf->autrePrestaM;
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteInf=$ligneMedInf->qteInf;
										echo $qteInf;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedInf->prixautrePrestaM;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteInf;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedInf = $TotalMedInf + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
									
									<td><?php echo $ligneMedInf->insupercentInf;?>%</td>
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td>
										<?php
										if($$ligneMedInf->id_factureMedInf!="" AND $ligneMedInf->id_factureMedInf!=0  AND $ligneMedInf->id_factureMedInf!=$numbill)
										{
											echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
										}
										?>
									</td>
								</tr>
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
							<td colspan=5 ></td>
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedInf.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedInf;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedInfPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<!--<td>
								<?php
							/*								if($ligneMedInf->id_factureMedInf!="" AND $ligneMedInf->id_factureMedInf!=0)
															{
																echo '<span style="font-size:100%; font-weight:bold;">Payed</span>';
															}
															*/?>
							</td>-->
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
							<th style="width:20%" colspan=13>Labs</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
							
							$resultPresta->setFetchMode(PDO::FETCH_OBJ);
							
							$comptPresta=$resultPresta->rowCount();
							
							if($lignePresta=$resultPresta->fetch())
							{
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedLabo->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedLabo=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											
											$nameprestaMedLabo=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
										}
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteLab=$ligneMedLabo->qteLab;
										echo $qteLab;
										?>
									</td>
									
									<td>
										<?php
										$prixPresta = $ligneMedLabo->prixprestationExa;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteLab;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
									
										$TotalMedLabo = $TotalMedLabo + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										
										?>
									
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
									
									<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									
								</tr>
								<?php
							}
							
							if($ligneMedLabo->id_prestationExa=="" AND $ligneMedLabo->prixautreExamen!=0)
							{
								?>
								<tr style="text-align:center;">
									<td><?php echo $ligneMedLabo->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedLabo=$ligneMedLabo->autreExamen;
										echo $ligneMedLabo->autreExamen;
										
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteLab=$ligneMedLabo->qteLab;
										echo $qteLab;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedLabo->prixautreExamen;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteLab;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedLabo = $TotalMedLabo + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
									
									<td><?php echo $ligneMedLabo->insupercentLab;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
								</tr>
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
							<td colspan=5 ></td>
							
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedLabo.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedLabo;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedLaboPayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
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
							<th style="width:20%" colspan=13>Radiologie</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" ></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" >Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedRadio->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedRadio=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											
											$nameprestaMedRadio=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
										}
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteRad=$ligneMedRadio->qteRad;
										echo $qteRad;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedRadio->prixprestationRadio;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
										<?php
										$balance=$prixPresta*$qteRad;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedRadio = $TotalMedRadio + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
									
									<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
								
								</tr>
								<?php
							}
							
							if($ligneMedRadio->id_prestationRadio=="" AND $ligneMedRadio->prixautreRadio!=0)
							{
								?>
								<tr style="text-align:center;">
									<td><?php echo $ligneMedRadio->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedRadio=$ligneMedRadio->autreRadio;
										
										echo $ligneMedRadio->autreRadio;
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteRad=$ligneMedRadio->qteRad;
										echo $qteRad;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedRadio->prixautreRadio;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteRad;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedRadio = $TotalMedRadio + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									
									
									<td><?php echo $ligneMedRadio->insupercentRad;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
								</tr>
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
							<td colspan=5 ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedRadio.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedRadio;
								
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedRadioPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
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
							<th style="width:20%" colspan=13>Physiotherapy</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
						while($ligneMedKine=$resultMedKine->fetch())
						{
							
							$billpercent=$ligneMedKine->insupercentKine;
							
							$idassu=$ligneMedKine->id_assuKine;
							
							$comptAssuKine=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuKine->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCount = $comptAssuKine->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuKine=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuKine->execute(array(
									'idassu'=>$idassu
								));
								
								$getAssuKine->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneNomAssu=$getAssuKine->fetch())
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
								
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedKine->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedKine=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											$nameprestaMedKine=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
										}
										
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteKine=$ligneMedKine->qteKine;
										echo $qteKine;
										?>
									</td>
									
									
									
									<td>
										<?php
										$prixPresta = $ligneMedKine->prixprestationKine;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteKine;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance =  $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
									
										$TotalMedKine = $TotalMedKine + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									
									<td><?php echo $ligneMedKine->insupercentKine;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
								</tr>
								<?php
							}
							
							if($ligneMedKine->id_prestationKine=="" AND $ligneMedKine->prixautrePrestaK!=0)
							{
								?>
								<tr style="text-align:center;">
									<td><?php echo $ligneMedKine->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedKine=$ligneMedKine->autrePrestaM;
										
										echo $ligneMedKine->autrePrestaM;
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteKine=$ligneMedKine->qteKine;
										echo $qteKine;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedKine->prixautrePrestaK;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteKine;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										
										$TotalMedKine= $TotalMedKine + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									<td><?php echo $ligneMedKine->insupercentKine;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>
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
							<td colspan=5 ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedKine.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedKine;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedKinePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
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
							<th style="width:20%" colspan=13>Consommables</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" >Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
									<td><?php echo $ligneMedConsom->datehosp;?></td>
									<td style="text-align:left;">
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
									
										<?php
										$balance=$prixPresta*$qteConsom;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
									
									
										$TotalMedConsom= $TotalMedConsom + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
								</tr>
								<?php
							}
							
							if($ligneMedConsom->id_prestationConsom==0 AND $ligneMedConsom->prixautreConsom!=0)
							{
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedConsom->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedConsom=$ligneMedConsom->autreConsom;
										echo $nameprestaMedConsom;
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
										$prixPresta = $ligneMedConsom->prixautreConsom;
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteConsom;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedConsom= $TotalMedConsom + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
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
							<td colspan=5 ></td>
							
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedConsom.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsom;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsomPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								// float round(float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP)
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
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
							<th style="width:20%" colspan=13>Medicaments</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)" >P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
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
									
									<td><?php echo $ligneMedMedoc->datehosp;?></td>
									<td style="text-align:left;">
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
									
										<?php
										$balance=$prixPresta*$qteMedoc;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedMedoc= $TotalMedMedoc + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>
								
								<?php
							}
							
							if($ligneMedMedoc->id_prestationMedoc==0 AND $ligneMedMedoc->prixautreMedoc!=0)
							{
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedMedoc->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedMedoc=$ligneMedMedoc->autreMedoc;
										echo $nameprestaMedMedoc;
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
										$prixPresta = $ligneMedMedoc->prixautreMedoc;
										echo $prixPresta.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteMedoc;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedMedoc= $TotalMedMedoc + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
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
							<td colspan=5 ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedMedoc.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedMedoc;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedMedocPayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
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
			
			
			if($comptMedOrtho != 0)
			{
				
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.(14+$i+$x).'', 'P & O')
					->setCellValue('C'.(14+$i+$x).'', 'Price')
					->setCellValue('D'.(14+$i+$x).'', 'Patient Price')
					->setCellValue('E'.(14+$i+$x).'', 'Insurance Price');
				
				?>
				
				<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
					<thead>
						<tr>
							<th style="width:20%" colspan=13>P & O</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)"></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
						while($ligneMedOrtho=$resultMedOrtho->fetch())
						{
							
							$billpercent=$ligneMedOrtho->insupercentOrtho;
							
							$idassu=$ligneMedOrtho->id_assuOrtho;
							
							$comptAssuOrtho=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
							
							$comptAssuOrtho->setFetchMode(PDO::FETCH_OBJ);
							
							$assuCount = $comptAssuOrtho->rowCount();
							
							for($i=1;$i<=$assuCount;$i++)
							{
								
								$getAssuOrtho=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');
								$getAssuOrtho->execute(array(
									'idassu'=>$idassu
								));
								
								$getAssuOrtho->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligneNomAssu=$getAssuOrtho->fetch())
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
								
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedOrtho->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedOrtho=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											$nameprestaMedOrtho=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
										}
										
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteOrtho=$ligneMedOrtho->qteOrtho;
										echo $qteOrtho;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedOrtho->prixprestationOrtho;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteOrtho;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
									
										$TotalMedOrtho = $TotalMedOrtho + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
								</tr>
								<?php
							}
							
							if($ligneMedOrtho->id_prestationOrtho=="" AND $ligneMedOrtho->prixautrePrestaO!=0)
							{
								?>
								<tr style="text-align:center;">
									<td><?php echo $ligneMedOrtho->datesoins;?></td>
									<td style="text-align:left;">
										<?php
										$nameprestaMedOrtho=$ligneMedOrtho->autrePrestaM;
										
										echo $ligneMedOrtho->autrePrestaM;
										?>
									</td>
									<td>
										<?php
										$qteOrtho=$ligneMedOrtho->qteOrtho;
										echo $qteOrtho;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedOrtho->prixautrePrestaO;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteOrtho;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedOrtho = $TotalMedOrtho + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>
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
							<td colspan=5 ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedOrtho.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedOrtho;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedOrthoPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
								
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
							<th style="width:20%" colspan=13>Services</th>
						</tr>
						<tr>
							<th style="width:8%;background:rgba(0, 0, 0, 0.05)">Date</th>
							<th style="width:15%;background:rgba(0, 0, 0, 0.05);text-align:left;">Name</th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)" ></th>
							<th style="width:4%;background:rgba(0, 0, 0, 0.05)">Qty</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">P/U <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Balance <?php echo $nomassurance;?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Percent</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient <?php echo '('.$bill.'%)';?></th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Patient balance</th>
							<th style="width:10%;background:rgba(0, 0, 0, 0.05)">Insurance</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						
						$TotaltopupPrice=0;
						$TotalpatientPrice=0;
						$TotalpatientBalance=0;
						$TotaluapPrice=0;
						
						
						$TotaltopupPricePayed=0;
						$TotalpatientPricePayed=0;
						$TotalpatientBalancePayed=0;
						$TotaluapPricePayed=0;
						
						
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
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedConsult->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										if($lignePresta->namepresta!='')
										{
											$nameprestaMedConsult=$lignePresta->namepresta;
											echo $lignePresta->namepresta;
											
										}else{
											
											$nameprestaMedConsult=$lignePresta->nompresta;
											echo $lignePresta->nompresta;
										}
										
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteConsu=$ligneMedConsult->qteConsu;
										echo $qteConsu;
										?>
									</td>
									
									
									<td>
										<?php
										$prixPresta = $ligneMedConsult->prixprestationConsu;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteConsu;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedConsult = $TotalMedConsult + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
									
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
								</tr>
								<?php
							}
							
							if($ligneMedConsult->id_prestationConsu=="" AND $ligneMedConsult->prixautreConsu!=0)
							{
								?>
								<tr style="text-align:center;">
									
									<td><?php echo $ligneMedConsult->datehosp;?></td>
									<td style="text-align:left;">
										<?php
										
										$nameprestaMedConsult=$ligneMedConsult->autreConsu;
										echo $ligneMedConsult->autreConsu;
										?>
									</td>
									<td></td>
									<td>
										<?php
										$qteConsu=$ligneMedConsult->qteConsu;
										echo $qteConsu;
										?>
									</td>
									
									<td>
										<?php
										$prixPresta = $ligneMedConsult->prixautreConsu;
										echo $prixPresta.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										?>
									</td>
									
										<?php
										$balance=$prixPresta*$qteConsu;
										$patientPrice=($balance * $billpercent)/100;
										$patientBalance = $topupPrice + $patientPrice;
										$uapPrice= $balance - $patientPrice;
										
										
										$TotalMedConsult = $TotalMedConsult + $balance;
										$TotalpatientPrice=$TotalpatientPrice + $patientPrice;
										$TotalpatientBalance = $TotalpatientBalance + $patientBalance;
										$TotaluapPrice= $TotaluapPrice + $uapPrice;
										
										?>
									
									<td>
										<?php
										echo $balance.'<span style="font-size:50%; font-weight:normal;">Rwf</span>';
										
										?>
									</td>
									
									
									<td><?php echo $ligneMedConsult->insupercentServ;?>%</td>
									
									<td>
										<?php
										echo $patientPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $patientBalance.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
									<td>
										<?php
										echo $uapPrice.'';
										?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									
								</tr>
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
						<tr style="text-align:center;">
							<td colspan=5 ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalMedConsult.'';
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalMedConsult;
								$TotalGnlPricePayed=$TotalGnlPricePayed + $TotalMedConsultPayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							
							<td ></td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotalpatientPrice.'';
								
								$TotalGnlPatientPrice=$TotalGnlPatientPrice + $TotalpatientPrice;
								
								$TotalGnlPatientPricePayed=$TotalGnlPatientPricePayed + $TotalpatientPricePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 110%; font-weight: bold;">
								<?php
								echo $TotalpatientBalance.'';
								
								$TotalGnlPatientBalance = $TotalGnlPatientBalance + $TotalpatientBalance;
								
								$TotalGnlPatientBalancePayed=$TotalGnlPatientBalancePayed + $TotalpatientBalancePayed;
								?><span style="font-size:70%; font-weight:normal;">Rwf</span>
							</td>
							
							<td style="font-size: 13px; font-weight: bold;">
								<?php
								echo $TotaluapPrice.'';
								
								$TotalGnlInsurancePrice=$TotalGnlInsurancePrice + $TotaluapPrice;
								
								$TotalGnlInsurancePricePayed=$TotalGnlInsurancePricePayed + $TotaluapPricePayed;
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
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
		<?php
		$TotalFinalPrice=0;
		$TotalFinalPatientPrice=0;
		$TotalFinalPatientBalance=0;
		$TotalFinalInsurancePrice=0;
		
		$TotalFinalPricePayed=0;
		$TotalFinalPatientPricePayed=0;
		$TotalFinalPatientBalancePayed=0;
		$TotalFinalInsurancePricePayed=0;
		
		?>
		<table class="printPreview" cellspacing="0" style="margin:auto;">
			<thead>
				<tr>
					<th style="width:20%;"></th>
					<th style="width:15%;">Total <?php echo $nomassurance;?></th>
					<th style="width:15%;">Patient <?php echo '('.$billpercent.'%)';?></th>
					<th style="width:15%;">Patient Balance</th>
					<th style="width:10%;">Insurance</th>
				</tr>
			</thead>
			
			<tbody>
				
				<?php
				
				$TotalFinalPrice=$TotalGnlPrice + $balanceHosp;
				$TotalFinalPatientPrice=$TotalGnlPatientPrice + $patientPriceHosp;
				$TotalFinalPatientBalance=$TotalGnlPatientBalance + $patientBalanceHosp;
				$TotalFinalInsurancePrice=$TotalGnlInsurancePrice + $insurancePriceHosp;
				
				
				$TotalFinalPricePayed=$TotalGnlPricePayed + $balanceHosp;
				$TotalFinalPatientPricePayed=$TotalGnlPatientPricePayed + $patientPriceHosp;
				$TotalFinalPatientBalancePayed=$TotalGnlPatientBalancePayed + $patientBalanceHosp;
				$TotalFinalInsurancePricePayed=$TotalGnlInsurancePricePayed + $insurancePriceHosp;
				
				
				/*
						if($TotalFinalPricePayed !=0)
						{
						?>
						<tr style="text-align:center;">
							<td style="font-size: 13px; font-weight: bold;">Final Balance not payed</td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPriceCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlTopupPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientPrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientBalance;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlInsurancePrice;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
						</tr>
						
						<tr style="text-align:center;color:rgba(0, 0, 0, 0.5);">
							<td style="font-size: 13px; font-weight: bold;">Final Balance payed</td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPricePayedCCO;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlTopupPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientPricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlPatientBalancePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
		
							<td style="font-size: 13px; font-weight: bold;">
							<?php
							echo $TotalGnlInsurancePricePayed;
							?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
						</tr>
						<?php
						}
				*/
				?>
				
				<tr style="text-align:center;">
					<td style="font-size: 13px; font-weight: bold;">Final Balance</td>
					<td style="font-size: 18px; font-weight: bold;">
						<?php
						echo $TotalFinalPrice;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
						<?php
						echo $TotalFinalPatientPrice;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
						<?php
						echo $TotalFinalPatientBalance;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
					<td style="font-size: 18px; font-weight: bold;">
						<?php
						echo $TotalFinalInsurancePrice;
						?><span style="font-size:75%; font-weight:normal;">Rwf</span></td>
				</tr>
			</tbody>
		</table>
	
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