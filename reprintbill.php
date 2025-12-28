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
	//$consuId=$_GET['idconsu'];
	
	
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
        $oldorgBill = $ligne->idorgBill;
		
		$numbill = $ligne->numbill;
		$bill=$ligne->billpercent;
		$nomassurancebill=$ligne->nomassurance;
		$idcardbill=$ligne->idcardbill;
		$numpolicebill=$ligne->numpolicebill;
		$adherentbill=$ligne->adherentbill;
		
		if($ligne->codecashier!=NULL)
		{
			$idDoneby=$ligne->codecashier;
			
			$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u AND c.codecashier=:operation');
			$resultatsDoneby->execute(array(
			'operation'=>$idDoneby
			));

			$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
			if($ligneDoneby=$resultatsDoneby->fetch())
			{
				$doneby = $ligneDoneby->full_name;
			}
	
		}elseif($ligne->codecoordi!=NULL){
			
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
		$dette=$ligne->dette;
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
	
	$getIdBill=$connexion->prepare('SELECT * FROM bills b WHERE b.id_bill=:idbill AND b.dette IS NOT NULL');
	$getIdBill->execute(array(
		'idbill'=>$_GET['idbill']
	));
	
	$getIdBill->setFetchMode(PDO::FETCH_OBJ);
	
	$idBillCount = $getIdBill->rowCount();
	
	if($ligneIdBill=$getIdBill->fetch())
	{
		$dettes=$ligneIdBill->dette;
	}else{
		$dettes=NULL;
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


	
	<!--<meta HTTP-EQUIV="Refresh" CONTENT="30; URL=http://www.tonSite.com/page.html"> -->
	
		
			<!------------------------------------>
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	

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

if($connected==true AND isset($_SESSION['codeC']))
{
	
	// echo 'New '.$idBilling;
	
	$resultatsManager=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.codecoordi=:operation');
	$resultatsManager->execute(array(
	'operation'=>$manager
	));

	$resultatsManager->setFetchMode(PDO::FETCH_OBJ);
	if($ligneManager=$resultatsManager->fetch())
	{
		$codecoordi = $ligneManager->codecoordi;
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
		$drawing = new BCGDrawing('barcode/png/barcode'.$codecoordi.'.png', $color_white);
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

						<td style="text-align:left;width:90%">
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
<hr>

<?php
		/*echo 'SELECT * FROM consultations c, med_consom mco, med_consult mc, med_inf mi, med_labo ml, med_medoc mm, med_kine mk, med_radio mr, med_ortho mo, med_psy mp, med_surge ms WHERE (c.id_factureConsult=:id_bill) OR (c.id_consu=mco.id_consuConsom AND mco.id_factureMedConsom=:id_bill) OR (c.id_consu=mc.id_consuMed AND mc.id_factureMedConsu=:id_bill) OR (c.id_consu=mi.id_consuInf AND mi.id_factureMedInf=:id_bill) OR (c.id_consu=ml.id_consuLabo AND ml.id_factureMedLabo=:id_bill) OR (c.id_consu=mm.id_consuMedoc AND mm.id_factureMedMedoc=:id_bill) OR (c.id_consu=mk.id_consuKine AND mk.id_factureMedKine=:id_bill) OR (c.id_consu=mr.id_consuRadio AND mr.id_factureMedRadio=:id_bill) OR (c.id_consu=mo.id_consuOrtho AND mo.id_factureMedOrtho=:id_bill) OR (c.id_consu=mp.id_consuPSy AND mp.id_factureMedPsy=:id_bill) OR (c.id_consu=ms.id_consuSurge AND ms.id_factureMedSurge=:id_bill)';
			$selectidconsu = $connexion->prepare('SELECT * FROM consultations c, med_consom mco, med_consult mc, med_inf mi, med_labo ml, med_medoc mm, med_kine mk, med_radio mr, med_ortho mo, med_psy mp, med_surge ms WHERE (c.id_factureConsult=:id_bill) OR (c.id_consu=mco.id_consuConsom AND mco.id_factureMedConsom=:id_bill) OR (c.id_consu=mc.id_consuMed AND mc.id_factureMedConsu=:id_bill) OR (c.id_consu=mi.id_consuInf AND mi.id_factureMedInf=:id_bill) OR (c.id_consu=ml.id_consuLabo AND ml.id_factureMedLabo=:id_bill) OR (c.id_consu=mm.id_consuMedoc AND mm.id_factureMedMedoc=:id_bill) OR (c.id_consu=mk.id_consuKine AND mk.id_factureMedKine=:id_bill) OR (c.id_consu=mr.id_consuRadio AND mr.id_factureMedRadio=:id_bill) OR (c.id_consu=mo.id_consuOrtho AND mo.id_factureMedOrtho=:id_bill) OR (c.id_consu=mp.id_consuPSy AND mp.id_factureMedPsy=:id_bill) OR (c.id_consu=ms.id_consuSurge AND ms.id_factureMedSurge=:id_bill)');
			$selectidconsu->execute(array(
				'id_bill'=>$_GET['idbill']
			));
			$selectidconsu->setFetchMode(PDO::FETCH_OBJ);
			if ($ligne = $selectidconsu->fetch()) {
				echo $id_consu = $id_consu.'<br>';
			}*/
			
			$dateConsult = date('Y-m-d', strtotime($_GET['datefacture']));

			$resultatConsu=$connexion->prepare('SELECT *FROM consultations c WHERE c.numero=:num AND (c.dateconsu=:dateconsu OR c.dateconsu!=:dateconsu) AND c.id_factureConsult=:id_factureConsult');
			$resultatConsu->execute(array(
			'num'=>$_GET['num'],
			'dateconsu'=>$dateConsult,
			'id_factureConsult'=>$_GET['idbill']
			));
			
			$resultatConsu->setFetchMode(PDO::FETCH_OBJ);
			//echo $countBill = $resultatConsu->rowCount();

			if($ligneConsu=$resultatConsu->fetch())
			{
				$idassurance=$ligneConsu->id_assuConsu;
				$dateconsu= date('d-M-Y', strtotime($ligneConsu->dateconsu));
				$resultIdDoc=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE m.id_u=:operation AND u.id_u=m.id_u');
				$resultIdDoc->execute(array(
				'operation'=>$ligneConsu->id_uM
				));
				$resultIdDoc->setFetchMode(PDO::FETCH_OBJ);
				
				
				if($ligneIdDoc=$resultIdDoc->fetch())
				{
					$codeDoc=$ligneIdDoc->codemedecin;
					$fullnameDoc=$ligneIdDoc->nom_u.' '.$ligneIdDoc->prenom_u;
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
				$userinfo .= ''.$nomassurancebill.'</span><br/>';
				
				if(isset($idassurance)){
					if($idassurance!=1)
					{
						if($idcardbill!="")
						{
							$userinfo .= 'N° insurance card:
							<span style="font-weight:bold">'.$idcardbill;
							
						}elseif($lignePatient->carteassuranceid!=""){
						
							$userinfo .= 'N° insurance card:
							<span style="font-weight:bold">'.$lignePatient->carteassuranceid;
						}
						
						if($numpolicebill!="")
						{
							$userinfo .= '</span><br/>
							
							N° police:
							<span style="font-weight:bold">'.$numpolicebill;
							
						}elseif($lignePatient->numeropolice!=""){
						
							$userinfo .= '</span><br/>
							
							N° police:
							<span style="font-weight:bold">'.$lignePatient->numeropolice;
						}
						
						if($adherentbill!="")
						{
							$userinfo .= '</span><br/>
							
							Principal member:
							<span style="font-weight:bold">'.$adherentbill;
							
						}elseif($lignePatient->adherent!=""){
						
							$userinfo .= '</span><br/>
							
							Principal member:
							<span style="font-weight:bold">'.$lignePatient->adherent;
						}
					}
				}
			}
				if (isset($dateconsu) && isset($fullnameDoc)) {
					$userinfo .='</span>
							</td>
							
							<td style="text-align:right;">
								Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
								Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
								Date of Consultation: <span style="font-weight:bold">'.$dateconsu.'</span><br/>
								Doctor: <span style="font-weight:bold">'.$fullnameDoc.'</span>
								
							</td>
							
						</tr>
					</table>';
				}else{
					$userinfo .='</span>
							</td>
							
							<td style="text-align:right;">
								Patient ID: <span style="font-weight:bold">'.$lignePatient->numero.'</span><br/>
								Date of birth: <span style="font-weight:bold">'.date('d-M-Y', strtotime($lignePatient->date_naissance)).'</span><br/>
							</td>
							
						</tr>
					</table>';
				}
				

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
						->setCellValue('B4', ''.$nomassurancebill.' '.$bill.'%')
						->setCellValue('F1', 'Bill #')
						->setCellValue('G1', ''.$numbill.'')
						->setCellValue('F2', 'Done by')
						->setCellValue('G2', ''.$doneby.'')
						->setCellValue('F3', 'Date')
						->setCellValue('G3', ''.$datebill.'');
			
		}
		
		/*-------Requête pour AFFICHER Type consultation-----------*/
		
		
		$resultConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_factureConsult=:idbill AND p.numero=:num AND p.numero=c.numero AND c.numero=:num ORDER BY c.id_consu');
		$resultConsult->execute(array(
		'idbill'=>$_GET['idbill'],
		'num'=>$numPa
		));

		$resultConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptConsult=$resultConsult->rowCount();
		
		$TotalConsult = 0;
		
		
	
		/*-------Requête pour AFFICHER Med_surge-----------*/
	
		$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge ms, patients p WHERE p.numero=:num AND p.numero=ms.numero AND ms.numero=:num AND ms.id_factureMedSurge=:idbill ORDER BY ms.id_medsurge');
		$resultMedSurge->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

		$comptMedSurge=$resultMedSurge->rowCount();
	
		$TotalMedSurge = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_inf-----------*/
	
		$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, patients p WHERE p.numero=:num AND p.numero=mi.numero AND mi.numero=:num AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf');
		$resultMedInf->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

		$comptMedInf=$resultMedInf->rowCount();
	
		$TotalMedInf = 0;
	
	
		/*-------Requête pour AFFICHER Med_labo-----------*/
		
		$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, patients p WHERE p.numero=:num AND p.numero=ml.numero AND ml.numero=:num AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo');
		$resultMedLabo->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

		$comptMedLabo=$resultMedLabo->rowCount();
		
		$TotalMedLabo = 0;
	
	
	
	
		/*-------Requête pour AFFICHER Med_radio-----------*/
		
		$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, patients p WHERE p.numero=:num AND p.numero=mr.numero AND mr.numero=:num AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio');
		$resultMedRadio->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

		$comptMedRadio=$resultMedRadio->rowCount();
		
		$TotalMedRadio = 0;
		
	
	
	
		/*-------Requête pour AFFICHER Med_kine-----------*/
	
		$resultMedKine=$connexion->prepare('SELECT *FROM med_kine mk, patients p WHERE p.numero=:num AND p.numero=mk.numero AND mk.numero=:num AND mk.id_factureMedKine=:idbill ORDER BY mk.id_medkine');
		$resultMedKine->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

		$comptMedKine=$resultMedKine->rowCount();
	
		$TotalMedKine = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_Ortho-----------*/
	
		$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho mo, patients p WHERE p.numero=:num AND p.numero=mo.numero AND mo.numero=:num AND mo.id_factureMedOrtho=:idbill ORDER BY mo.id_medortho');
		$resultMedOrtho->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

		$comptMedOrtho=$resultMedOrtho->rowCount();
	
		$TotalMedOrtho = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_consom-----------*/
		
		$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, patients p WHERE p.numero=:num AND p.numero=mco.numero AND mco.numero=:num AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom');
		$resultMedConsom->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsom=$resultMedConsom->rowCount();
	
		$TotalMedConsom = 0;
		
	
	
		/*-------Requête pour AFFICHER Med_medoc-----------*/
	
		$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, patients p WHERE p.numero=:num AND p.numero=mdo.numero AND mdo.numero=:num AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc');
		$resultMedMedoc->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

		$comptMedMedoc=$resultMedMedoc->rowCount();
		
		$TotalMedMedoc = 0;
	
	
		
		/*-------Requête pour AFFICHER Med_consult-----------*/
	
		$resultMedConsult=$connexion->prepare('SELECT *FROM med_consult mc, patients p WHERE p.numero=:num AND p.numero=mc.numero AND mc.numero=:num AND mc.id_factureMedConsu=:idbill GROUP BY mc.id_prestationConsu ORDER BY mc.id_medconsu');
		$resultMedConsult->execute(array(
		'num'=>$numPa,
		'idbill'=>$_GET['idbill']
		));
		
		$resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

		$comptMedConsult=$resultMedConsult->rowCount();
	
		$TotalMedConsult = 0;

	?>
	
	<table style="width:100%; margin-bottom:-5px">
		<tr>
			<td style="text-align:left; width:33%;">
				<h4><?php echo $datebill;?></h4>
			</td>
			
			<td style="text-align:center; width:33%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° <?php echo $numbill;?> <?php if($dette!=NULL){ echo '<span style="font-size:150%; font-weight:600;color:red;" class="buttonBill">Indebted</span>';}?></h2>
			</td>
			
			<td style="text-align:right;width:33%;">
			
				<form method="post" action="reprintbill.php?num=<?php echo $_GET['num'];?>&manager=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?><?php if(isset($_GET['idtypeconsu'])){ echo '&idtypeconsu='.$_GET['idtypeconsu'];}?><?php if(isset($_GET['idassu'])){ echo '&idassu='.$_GET['idassu'];}?><?php if(isset($idBilling)){ echo '&idbill='.$idBilling;}?>&finishbtn=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> <?php echo getString(142);?></button>
					
				</form>
			</td>
			
			<td>
				<form method="post" action="formModifierBill.php?num=<?php echo $_GET['num'];?>&numbill=<?php echo $numbill;?>&billpercent=<?php echo $bill;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $_GET['datefacture'];?>&idbill=<?php echo $_GET['idbill'];?>" enctype="multipart/form-data" class="buttonBill">
				
					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo 'Modifier';?></button>
					
				</form>
			</td>
			
			<td class="buttonBill">
				<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="cancelbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:inline";}else{ echo "display:none";}?>;margin:5px;">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
				</a>
				
				<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="finishbtn" style="<?php if(!isset($_GET['finishbtn'])){ echo "display:none";}?>;margin:5px;">
					<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(141);?></button>
				</a>
			</td>
			<td class="buttonBill">

				<span class="btn" id="myBtn2" onclick="displayModal('show')" title="Supprimer la facture n° <?php echo $numbill;?>" style="margin-left:10px;"><i class="fa fa-trash fa-1x fa-fw"></i></span>
			</td>
		</tr>

	</table>


        <?php
        try
        {
            $TotalGnlPrice=0;
            $TotalGnlTopupPrice=0;
            $TotalGnlPatientPrice=0;
            $TotalGnlPatientBalance=0;
            $TotalGnlPatientBalanceBeforeDiscount=0;
            $TotalGnlPatientdiscount=0;
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
						<th style="width:10%;">Percent</th>
						<th style="width:10%;">Patient ('.$bill.'%)</th>
						<th style="width:10%;">Patient balance</th>
						<th style="width:10%;">Insurance balance</th>
					</tr>
				</thead>


				<tbody>';

                $TotaltopupPrice=0;
                $TotalpatientPrice=0;
                $Patientdiscount=0;
                $TotalpatientBalance=0;
                $TotaluapPrice=0;

                while($ligneConsult=$resultConsult->fetch())
                {

                    $billpercent=$ligneConsult->insupercent;
                    $discountPercentConsu = $ligneConsult->discountpercentConsu;

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



                            
						
							 $typeconsult .= '<td>'.$ligneConsult->insupercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';


                            $TotalConsult=$TotalConsult + $prixPresta;


                            $typeconsult .= '<td style="font-weight:700">';
                            $patientPrice=($prixPresta * $billpercent)/100;
                            $patientPriceconsu=($prixPresta * $billpercent)/100;
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


                            /*if($ligneConsult->id_factureConsult!=NULL)
                            {
                                $typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
                            }*/
                            if($discountPercentConsu != ''){
                            $typeconsult .= '</tr>';
                            $typeconsult .= '<tr style="background:#d9bcab;">

                            <td style="font-weight:bold" colspan="3">
                           	 Discount Amount';
                           	 $typeconsult .='</td>
                           	 <td>Discount Percent :<td>'.$discountPercentConsu.'%';

                           	 $typeconsult .='
                            <td style="font-weight:bold;font-size:15px;" colspan="3">';
                            $discountCon = ($patientPriceconsu * $discountPercentConsu) / 100;
                            $typeconsult .=  $discountCon .' <span style="font-size:70%; font-weight:normal;">Rwf</span>';

                           	 $Patientdiscount = $Patientdiscount + $discountCon;
                           	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $patientPriceconsu;
							
                            $typeconsult .= '</td></tr>';
                        	}else{
                        		$Patientdiscount = $Patientdiscount + 0;
                        		$TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + 0;
                        	}
                        }

                    }else{

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
			
							<td style="font-weight:700">';
							
							'<td>'.$ligneConsult->insupercent.'<span style="font-size:70%; font-weight:normal;">%</span></td>';


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

                           /* if($ligneConsult->id_factureConsult!=NULL)
                            {
                                $typeconsult .= '<td style="font-weight:700">Consultation payed</td>';
                            }*/

	                   		if($discountPercentConsu != ''){
	                        $typeconsult .= '</tr>';
	                        $typeconsult .= '<tr style="background:#d9bcab;">

	                        <td style="font-weight:bold" colspan="3">
	                       	 Discount Amount';
	                       	 $typeconsult .='</td>
	                       	 <td>Discount Percent : <td>'.$discountPercentConsu.'%';

	                       	 $typeconsult .='
	                        <td style="font-weight:bold;font-size:15px;" colspan="3">';
	                        $discountCon = ($patientPriceconsu * $discountPercentConsu) / 100;
	                        $typeconsult .=  $discountCon .' <span style="font-size:70%; font-weight:normal;">Rwf</span>';
	                       	$Patientdiscount = $Patientdiscount + $discountCon;
							
	                        $typeconsult .= '</td></tr>';
	                    	}else{
	                    		$Patientdiscount = $Patientdiscount + 0;
	                    	}
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
                $TotalGnlPatientdiscount=$TotalGnlPatientdiscount + $Patientdiscount;

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
                        <th style="width:10%;">Percent</th>
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
                    $Patientdiscount=0;
                    $consult=0;
                    $TotaluapPrice=0;



                    while($ligneMedConsult=$resultMedConsult->fetch())
                    {
                    $billpercent=$ligneMedConsult->insupercentServ;
                    $discountconsult=$ligneMedConsult->discountpercentConsult;

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

                        

                        <td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($prixPresta * $billpercent)/100;
                            $consultonly=($prixPresta * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                            $consult = $consult + $consultonly;
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


                            <td><?php echo $ligneMedConsult->insupercentServ;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;

                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance =$patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                            	$consult = $consult + $consultonly;

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
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountconsult == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3">Consommables Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountconsult == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3"></td>
                    	<td style="" ><?php echo $discountconsult; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountconsult !=''){
	                    	 echo $discountConsult = ($consult * $discountconsult) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountConsult;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $consult;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotalpatientPrice=0;
                    $Patientdiscount=0;
                    $TotalpatientBalance=0;
                    $surge = 0;
                    $TotaluapPrice=0;

                    while($ligneMedSurge=$resultMedSurge->fetch())
                    {

                    $billpercent=$ligneMedSurge->insupercentSurge;
                    $discountSurge=$ligneMedSurge->discountpercentSurge;

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

                        <td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedSurge->prixprestationSurge * $billpercent)/100;
                            $surgeonly=($ligneMedSurge->prixprestationSurge * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance =$patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                            $surge = $surge + $surgeonly;

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

                            <td><?php echo $ligneMedSurge->insupercentSurge;?>%</td>

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
                            	$surge = $surge + $surgeonly;

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
                        <td></td>
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
                   	<tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountSurge == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3">Surgery Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountSurge == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3"></td>
                    	<td style="" ><?php echo $discountSurge; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountSurge !=''){
	                    	 echo $discountsurgery = ($surge * $discountSurge) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountsurgery;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $surge;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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
                        <th style="width:10%;">Percent</th>
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
                    $Patientdiscount=0;
                    $kine = 0;
                    $TotaluapPrice=0;

                    while($ligneMedKine=$resultMedKine->fetch())
                    {

                    $billpercent=$ligneMedKine->insupercentKine;
                    $discountkine=$ligneMedKine->discountpercentkine;

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

                        <td><?php echo $ligneMedKine->insupercentKine;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedKine->prixprestationKine * $billpercent)/100;
                            $kineonly=($ligneMedKine->prixprestationKine * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                            $kine  = $kine + $kineonly;
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

                            <td><?php echo $ligneMedKine->insupercentKine;?>%</td>

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
                            	$kine  = $kine + $kineonly;

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
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountkine == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3">Physiotherapy Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountkine == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3"></td>
                    	<td style="" ><?php echo $discountkine; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountkine !=''){
	                    	 echo $discountKine = ($kine * $discountkine) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountKine;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $kine;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $Patientdiscount=0;
                    $ortho = 0;
                    $TotaluapPrice=0;

                    while($ligneMedOrtho=$resultMedOrtho->fetch())
                    {

                    $billpercent=$ligneMedOrtho->insupercentOrtho;
                    $discountortho=$ligneMedOrtho->discountpercentOrtho;

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

                        <td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedOrtho->prixprestationOrtho * $billpercent)/100;
                            $orthoonly=($ligneMedOrtho->prixprestationOrtho * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                            $ortho = $ortho + $orthoonly;
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

                            <td><?php echo $ligneMedOrtho->insupercentOrtho;?>%</td>

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
                            	$ortho = $ortho + $orthoonly;

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
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountortho == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3">P&O Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountortho == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3"></td>
                    	<td style="" ><?php echo $discountortho; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountortho !=''){
	                    	 echo $discountOrthope = ($ortho * $discountortho) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountOrthope;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $ortho;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    </tbody>
                </table>
                <?php

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.(15+$i+$x+$y).'', ''.$TotalMedOrtho.'')
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
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $Patientdiscount=0;
                    $inf = 0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

                    while($ligneMedInf=$resultMedInf->fetch())
                    {

                    $billpercent=$ligneMedInf->insupercentInf;
                    $discountinf=$ligneMedInf->discountpercentInf;

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


                        <td><?php echo $ligneMedInf->insupercentInf;?>%</td>

                        <td>
                            <?php
                            $patientPrice=($ligneMedInf->prixprestation * $billpercent)/100;
                            $infonly =($ligneMedInf->prixprestation * $billpercent)/100;
                            $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                            echo $patientPrice.'';
                            ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                        </td>

                        <td>
                            <?php
                            $patientBalance = $patientPrice;
                            $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                      		$inf = $inf + $infonly;
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

                            <td><?php echo $ligneMedInf->insupercentInf;?>%</td>

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
                      			$inf = $inf + $infonly;
                                
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
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountinf == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3">Nursing Care Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountinf == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3"></td>
                    	<td style="" ><?php echo $discountinf; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountinf !=''){
	                    	 echo $discountInf = ($inf * $discountinf) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountInf;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $inf;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotaltopupPrice=0;
                    $TotalpatientPrice=0;
                    $Patientdiscount=0;
                    $labs=0;
                    $TotalpatientBalance=0;
                    $TotaluapPrice=0;

                    while($ligneMedLabo=$resultMedLabo->fetch())
                    {

                        $billpercent=$ligneMedLabo->insupercentLab;
                        $DiscountLab=$ligneMedLabo->discountpercentLab;

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


                            <td><?php echo $ligneMedLabo->insupercentLab;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($ligneMedLabo->prixprestationExa * $billpercent)/100;
                                $labsonly=($ligneMedLabo->prixprestationExa * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                $labs = $labs + $labsonly;

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

                            <td><?php echo $ligneMedLabo->insupercentLab;?>%</td>

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
                                $labs = $labs + $labsonly;
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
                        
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($DiscountLab == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3">Lab Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($DiscountLab == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3"></td>
                    	<td style="" ><?php echo $DiscountLab; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($DiscountLab !=''){
	                    	 echo $discountLab = ($labs * $DiscountLab) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountLab;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $labs;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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
                        <th style="width:10%;">Percent</th>
                        <th style="width:10%;">Patient <?php echo '('.$bill.'%)';?></th>
                        <th style="width:10%;">Patient balance</th>
                        <th style="width:10%;">Insurance balance</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php

                    $TotalpatientPrice=0;
                    $TotalpatientBalance=0;
                    $Patientdiscount=0;
                    $radio=0;
                    $TotaluapPrice=0;

                    while($ligneMedRadio=$resultMedRadio->fetch())
                    {

                        $billpercent=$ligneMedRadio->insupercentRad;
                        $discountradio=$ligneMedRadio->discountpercentRadio;

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


                            <td><?php echo $ligneMedRadio->insupercentRad;?>%</td>

                            <td>
                                <?php
                                $patientPrice=($prixPresta * $billpercent)/100;
                                $radioonly=($prixPresta * $billpercent)/100;
                                $TotalpatientPrice=$TotalpatientPrice + $patientPrice;

                                echo $patientPrice.'';
                                ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                            </td>

                            <td>
                                <?php
                                $patientBalance = $patientPrice;
                                $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                $radio = $radio + $radioonly;
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


                            <td><?php echo $ligneMedRadio->insupercentRad;?>%</td>

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
                                $radio = $radio + $radioonly;

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
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountradio == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3">Radiography Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountradio == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="3"></td>
                    	<td style="" ><?php echo $discountradio; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountradio !=''){
	                    	 echo $discountRadio = ($radio * $discountradio) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountRadio;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $radio;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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
                        <th style="width:8%">P/U <?php echo $nomassurancebill;?></th>
                        <th style="width:10%;">Balance <?php echo $nomassurancebill;?></th>
                        <th style="width:10%;">Percent</th>
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
                    $Patientdiscount=0;
                    $consom=0;
                    $TotaluapPrice=0;

                    while($ligneMedConsom=$resultMedConsom->fetch())
                    {

                        $billpercent=$ligneMedConsom->insupercentConsom;
                        $discountconsom=$ligneMedConsom->discountpercentConsom;

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


                                <td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>

                                <td>
                                    <?php
                                    $patientPrice=($balance * $billpercent)/100;
                                    $consomonly=($balance * $billpercent)/100;
                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                    echo $patientPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $patientBalance = $patientPrice;
                                    $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                    $consom = $consom + $consomonly;
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

                               

                                <td>
                                    <?php
                                    $balance=$prixPresta*$qteConsom;

                                    echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedConsom=$TotalMedConsom + $balance;
                                    ?>
                                </td>

                                <td><?php echo $ligneMedConsom->insupercentConsom;?>%</td>

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
                                    $consom = $consom + $consomonly;

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
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountconsom == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="6">Consommables Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountconsom == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="6"></td>
                    	<td style="" ><?php echo $discountconsom; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountconsom !=''){
	                    	 echo $discountConsom = ($consom * $discountconsom) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountConsom;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $consom;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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
                        <th style="width:10%;">Percent</th>
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
                    $Patientdiscount=0;
                    $medoc=0;
                    $TotaluapPrice=0;

                    while($ligneMedMedoc=$resultMedMedoc->fetch())
                    {

                        $billpercent=$ligneMedMedoc->insupercentMedoc;
                        $discountmedoc=$ligneMedMedoc->discountpercentMedoc;

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
                        //echo  'SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation=:prestaId';
                        //echo $presta_assu;
                        //echo $ligneMedMedoc->id_prestationMedoc;

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

                                <td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>

                                <td>
                                    <?php
                                    $patientPrice=($balance * $billpercent)/100;
                                    $medoconly=($balance * $billpercent)/100;
                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                    echo $patientPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $patientBalance = $patientPrice;
                                    $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                    $medoc = $medoc + $medoconly;
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

                        if($ligneMedMedoc->id_prestationMedoc==0 AND ($ligneMedMedoc->prixautreMedoc!=0 ))
                        {
                            ?>
                            <tr style="text-align:center;">
                                <td>
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

                                <td>
                                    <?php
                                    $balance=$prixPresta*$qteMedoc;

                                    echo $balance.'<span style="font-size:70%; font-weight:normal;">Rwf</span>';

                                    $TotalMedMedoc=$TotalMedMedoc + $balance;
                                    ?>
                                </td>


                                <td><?php echo $ligneMedMedoc->insupercentMedoc;?>%</td>

                                <td>
                                    <?php
                                    $patientPrice=($balance * $billpercent)/100;
                                    $TotalpatientPrice=$TotalpatientPrice + $patientPrice;
                                    echo $patientPrice;

                                    ?><span style="font-size:70%; font-weight:normal;">Rwf</span>
                                </td>

                                <td>
                                    <?php
                                    $patientBalance =  $patientPrice;
                                    $TotalpatientBalance = $TotalpatientBalance + $patientBalance;
                                    $medoc = $medoc + $medoconly;

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

                        $arrayMedMedoc[$z][0]='';
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
                        <td></td>
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
                    <tr style="text-align:center;border-top:2px solid #ddd;background: #d9bcab;<?php if($discountmedoc == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="6">Medicament Discount</td>
                    	<td style="font-weight: bold;" >Percent</td>
                    	<td style="font-weight: bold;" >Amount</td>
                    	<td style="font-weight: bold;" ></td>
                    </tr>
                    <tr style="text-align: center;<?php if($discountmedoc == ''){echo "display: none;";} ?>">
                    	<td style="font-weight: bold;" colspan="6"></td>
                    	<td style="" ><?php echo $discountmedoc; ?><span style="font-size:70%; font-weight:normal;">%</span></td>
                    	<td style="font-weight: bold;" ><?php
                    	if($discountmedoc !=''){
	                    	 echo $discountMedoc = ($medoc * $discountmedoc) /100; 
	                    	 $Patientdiscount = $Patientdiscount +  $discountMedoc;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
	                    	 $TotalGnlPatientBalanceBeforeDiscount = $TotalGnlPatientBalanceBeforeDiscount + $medoc;
                    	 }else{
                    	 	 $Patientdiscount = $Patientdiscount +  0;
	                    	 $TotalGnlPatientdiscount = $TotalGnlPatientdiscount +  $Patientdiscount;
                    	 }
                    	 ?>
                    	 <span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                    	<td style="font-weight: bold;" ></td>
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

<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  padding-top: 10px; /* Location of the box */
  width: 50%; /* Full width */
  height: 50%; /* Full height */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  font-family: century Gothic;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}

/* The Close Button */
.close,.close2 {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus,
.close2:hover,
.close2:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
.fa-close{
	-webkit-transition:-webkit-transform .25s, opacity .25s;
	-moz-transition:-moz-transform .25s, opacity .25s;
	transition: transform .25s, opacity .25s;
	color: #ccc;
}
.fa-close:hover{
	-webkit-transform: rotate(270deg);
    -moz-transform: rotate(270deg);
    transform: rotate(270deg);
	opacity:1;
}
.close ,.close2{
	background: #222;
	padding: 2px 5px;
	border-radius: 50%;
}
</style>

	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #ccc; background:#fff; padding:10px; border-radius:3px; font-size:85%;">
		<div class="buttonBill">
		<?php 
			$CheckEdi = $connexion->prepare('SELECT * FROM editedBillHisto WHERE eidbill=:eidbill');
			$CheckEdi->execute(array('eidbill'=>$_GET['idbill']));
			$CheckEdi->setFetchMode(PDO::FETCH_OBJ);
			$countB = $CheckEdi->rowCount();

			if($countB !=0){
		?>
			<tr>
				<td style="text-align:left; width:33%;">
					
				</td>
				<td style="text-align:center; width:33%;">
					<h5 style="font-size:100%; font-weight:600;text-align: center;color: #A00000;font-family: century Gothic;padding-bottom: 5px;">
						<i class="fa fa-info-circle" style="font-size: 20px;"></i> <span style="position: relative;bottom: 3px;">This Bill Has Been Edited.
						 <span style="text-transform: none;color: blue;cursor: pointer;" id="myBtn">Read More....</span>
						</span>
					</h5>
				</td>
			</tr>

						<!-- The Modal -->
			<div id="myModal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content">
			    <span class="close"><i class="fa fa-close"></i></span>
			    <p style="text-align: center;color: #A00000;border-bottom: 1px solid #ddd;">List Of Person Who Edited This Bill.</p>
			    <hr>
			    <div class="Info">
			    	<table class="printPreview">
			    		<thead>
			    			<th>#</th>
			    			<th style="text-align: center;">Who Edited Bill</th>
			    			<th style="text-align: center;">Edited On</th>
			    		</thead>
			    		<tbody>
			    			<?php 
			    			$count =1;
			    				while($fetchEd=$CheckEdi->fetch()){
			    					$GetUsername = $connexion->prepare("SELECT * FROM utilisateurs WHERE id_u=:id_u");
			    					$GetUsername->execute(array('id_u'=>$fetchEd->whoedit));
			    					$GetUsername->setFetchMode(PDO::FETCH_OBJ);
			    					$username = $GetUsername->fetch();
			    			?>
			    				<tr>
			    					<td><?php echo $count; ?></td>
			    					<td style="text-align: center;"><?php echo $username->full_name; ?></td>
			    					<td style="text-align: center;font-weight: bold;color: #A00000;"><?php echo $fetchEd->timee; ?></td>
			    				</tr>
			    			<?php $count++; }?>
			    		</tbody>
			    	</table>
			    </div>
			  </div>

			</div>
		<?php }?>
		</div>

		<!-- The Modal1 -->
		<div id="myModal2" class="modal flashing">

		  <!-- Modal content -->
		  <div class="modal-content">
		    <span class="close2" onclick="displayModal('close')" ><i class="fa fa-close"></i></span>
		    <p id="Content" style="text-align: center;color: #A00000;border-bottom: 1px solid #ddd;">Are You Sure You Want To Delete This Bill?</p>
		   
		    <div id="Info" style="margin-left: 35%;margin-top: 40px;">
				<a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?>&deletebill=ok&idbill=<?php echo $idBilling;?>" class="btn-large" title="Supprimer la facture n° <?php echo $numbill;?>"><i class="fa fa-trash fa-1x fa-fw"></i>Yes</a>    				

				<!-- <span class="btn-large" onclick="displayModal('motif')"><i class="fa fa-trash fa-1x fa-fw"></i>Yes</span>
 -->
				<span class="btn-large-inversed no" onclick="displayModal('hide')"  title="No" style="margin-left:10px;"><i class="fa fa-ban fa-1x fa-fw"></i>No</span>
		    		
		    </div>

		    
			<!-- <div id="formDiv" style="display: none;">
			    	<form method="POST" action="deletebill.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?>&deletebill=ok&idconsu=<?php echo $_GET['idconsu'];?>&idbill=<?php echo $idBilling;?>">
			    		<textarea class="form-control" name="motif_content" style="height: 100px;"></textarea>
			    		<button class="btn-large" type="submit" name="motif" style="position: relative;left: 100px;width: 300px;">Save</button>
			    	</form>
			    </div> -->
		  </div>

		</div>

        <table class="printPreview" cellspacing="0" style="margin:auto;">
            <thead>
            <tr>
                <th style="width:15%"></th>
                <th style="width:15%;text-align: center;">Total balance <?php echo $nomassurancebill;?></th>
<!--                 <th style="width:15%;">Patient <?php echo '('.$bill.'%)';?></th>
 -->                <th style="width:15%;text-align: center;">Patient balance</th>
                <th style="width:15%;color: #0d5920c9;border-radius: 3px;text-align: center;border-bottom: 1px solid #0d5920c9;">Amount To Be Paid After Discount</th>
                <th style="width:15%;text-align: center;">Insurance</th>
            </tr>
            </thead>

            <tbody>
            <tr style="text-align:center;">
                <td style="font-size: 110%; font-weight: bold;text-align: center;">Final Balance</td>
                <td style="font-size: 110%; font-weight: bold;text-align: center;"><?php echo number_format($TotalGnlPrice);?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
<!--                 <td style="font-size: 110%; font-weight: bold;"><?php echo $TotalGnlPatientPrice;?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
 -->                <td style="font-size: 110%; font-weight: bold;text-align: center;">
					<?php
					
					$patientPayed = $TotalGnlPatientBalance - $dettes;
					echo number_format($TotalGnlPatientBalance);?><span style="font-size:70%; font-weight:normal;">Rwf</span>
				</td>
                <td style="font-size: 110%; font-weight: bold;text-align: center;"><?php echo number_format(($TotalGnlPatientBalance - $TotalGnlPatientBalanceBeforeDiscount) + $TotalGnlPatientdiscount);?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
                <td style="font-size: 110%; font-weight: bold;text-align: center;"><?php echo number_format($TotalGnlInsurancePrice);?><span style="font-size:70%; font-weight:normal;">Rwf</span></td>
            </tr>
            </tbody>
        </table>
		
		<?php
		
		if($idBillCount!=0)
		{
			?>
			<table class="printPreview" class="buttonBill" cellspacing="0" style="margin-top:5px;border:none;">
				<tr style="text-align:center;" class="buttonBill">

					<td style="font-size: 110%; font-weight: bold;border:none;"></td>

					<td style="font-size: 110%; font-weight: bold;text-align:center;border:2px solid #e8e8e8;width:10%;">
						<?php
						echo '<span>Payed : </span><span style="color:gray">'.$patientPayed.'</span>';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>

					<td style="font-size: 110%; font-weight: bold;text-align:center; border:2px solid #e8e8e8;width:10%;">
						<?php
						echo '<span>Debt : </span><span style="color:gray">'.$dettes.'</span>';?><span style="font-size:70%; font-weight:normal;">Rwf</span>
					</td>
				</tr>
			</table>
			<?php
		}
		?>

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
	
	/* $file = file_get_contents("http://192.168.135.50/uap/printConsuBill.php?num=P9&cashier=CSC15A01&dateconsu=2015-09-19");
	file_put_contents("toPDF.html", $file); */

}
?>
</body>
<script>
// Get the modal
var modal = document.getElementById("myModal");
var modal2 = document.getElementById("myModal2");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
var span2 = document.getElementsByClassName("close2")[0];
var no = document.getElementsByClassName("no")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

function displayModal(fld){
	if (fld=='show') {
		modal2.style.display = "block";
	}	
	if (fld=='hide') {
		modal2.style.display = "none";
	}	
	if (fld=='close') {
		modal2.style.display = "none";
		document.getElementById('formDiv').style.display="none";
		document.getElementById('Info').style.display="block";
		document.getElementById('Content').innerHTML="Are You Sure You Want To Delete This Bill?";

	}	

	if (fld=='motif') {
		//modal2.style.display = "none";
		document.getElementById('formDiv').style.display="block";
		document.getElementById('Info').style.display="none";
		document.getElementById('Content').innerHTML="Why Do You Want To Delete This Bill?";
	}
}
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
  modal2.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
  
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
}
</script>
</html>