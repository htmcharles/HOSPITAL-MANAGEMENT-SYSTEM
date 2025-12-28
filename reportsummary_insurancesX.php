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


?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Insurance summary Report'; ?></title>

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
	
	if(isset($_SESSION['codeC']))
	{
		$resultatsCoordi=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u and c.id_u=:operation');
		$resultatsCoordi->execute(array(
		'operation'=>$idCoordi	
		));

		$resultatsCoordi->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneCoordi=$resultatsCoordi->fetch())
		{
			$doneby = $ligneCoordi->full_name;			
			$codecoordi=$ligneCoordi->codecoordi;
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
		$code->setLabel('#Insurance Summary Report #');
		$code->parse('Insurance Summary Report');
		
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
	
	if(isset($_GET['dailydategnl']))
	{
		$dailydategnl=$_GET['dailydategnl'];
		//$iVisitgnl=$_GET['paVisitgnl'];
		
		/*if($_GET['percent'] !="All")
		{
			$percent=$_GET['percent'].'<span style="font-size:80%; font-weight:normal;">%</span>';
		}else{
			$percent='All';
		}*/
	
	
		
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
					<h2 style="font-size:150%; font-weight:600;">Insurance Summary Report</h2>
				</td>
				
				<td style="text-align:right; width:30%;">
					
					<form method="post" action="reportsummary_insurances.php?dailydategnl=<?php echo $dailydategnl;?>" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>

				<td style="text-align:right">
					
					<a href="report.php?coordi=ok" class="buttonBill">
						<button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</a>
					
				</td>
			</tr>
		
		</table>
		
		<?php
		if(isset($_GET['dailydategnl']))
		{
		?>
		<div id="divGnlBillReport" style="font-weight:normal;">
		
			<?php			
			
			$resultGnlBillReport=$connexion->query('SELECT *FROM bills WHERE '.$dailydategnl.'  ORDER BY datebill ASC');
			/*$resultGnlBillReport->execute(array(
			'nomassu'=>$_GET['nomassu']	
			));*/
			
			$resultGnlBillReport->setFetchMode(PDO::FETCH_OBJ);

			$comptBillReport=$resultGnlBillReport->rowCount();
			
			if($comptBillReport!=0)
			{
			
			?>
			<table style="width:100%" class="printPreview tablesorter3" cellspacing="0"> 
						
				<thead>
					<tr>
						<th style="border-right: 1px solid #bbb;text-align:center;">N°</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Insurance name</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">Total Amount</th>
						<th style="border-right: 1px solid #bbb;text-align:center;">id bill</th>
					</tr> 
				</thead> 
				
				<tbody>
			<?php
			$i=0;
			$compteur=1;
			
				while($ligneGnlBillReport=$resultGnlBillReport->fetch())//on recup�re la liste des �l�ments
				{
			?>
				<hr>
				<tr style="text-align:center;">
					<td style="text-align:center;"><?php echo $compteur;?></td>
					<td style="text-align:center;"><?php echo $ligneGnlBillReport->nomassurance;?></td>
					<td style="text-align:center;"><?php echo $ligneGnlBillReport->id_bill;?></td>
					<td style="text-align:center;">
						<?php 
							$selecttotal = $connexion->prepare('SELECT * FROM bills WHERE '.$dailydategnl.' AND nomassurance=:nomassurance');
							$selecttotal->execute(array(
								'nomassurance'=>$ligneGnlBillReport->nomassurance
							));
							$selecttotal->setFetchMode(PDO::FETCH_OBJ);
							$total = 0;
							$totalgnl = 0;
							while ($lignetotal = $selecttotal->fetch()) {
								if ($ligneGnlBillReport->nomassurance != 'PRIVATE') {
									$presta_assu='prestations_'.strtolower($ligneGnlBillReport->nomassurance);
									$percent = $lignetotal->billpercent;
									$assu_percent = 100 - $percent;

									//consultation
									$resultConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
									$resultConsu->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									$comptConsu=$resultConsu->rowCount();
									$resultConsu->setFetchMode(PDO::FETCH_OBJ);
									if ($comptConsu!=0) {
										while($ligneConsu=$resultConsu->fetch())
										{
											if($ligneConsu->prixtypeconsult!=0 AND $ligneConsu->prixrembou!=0)
											{
												$prixPrestaRembou=$ligneConsu->prixrembou;
																				
												$prixconsult=$ligneConsu->prixtypeconsult - $prixPrestaRembou;
											
											}else{
												$prixconsult=$ligneConsu->prixtypeconsult;
											}
											//$totalgnl = $lignetotal->totalgnlprice;
											$insuranceamount = ($prixconsult * $assu_percent)/100;
											$totalgnl += $insuranceamount; 
										}
									}



									//med_consult
									$resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, '.$presta_assu.' p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
									$resultMedConsu->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									
									$comptMedConsu=$resultMedConsu->rowCount();
									
									$resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
									if ($comptMedConsu !=0) {
										while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des ??ents
										{
											if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
											{
												$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
												
												$prixconsu=$ligneMedConsu->prixprestationConsu - $prixPrestaRembou;
											
											}else{
												$prixconsu=$ligneMedConsu->prixprestationConsu;

											}
											$insuranceamount = ($prixconsu * $assu_percent)/100;
											$totalgnl += $insuranceamount; 
										}
							
									}
						

									//med_labo
									$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assu.' p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
									$resultMedLabo->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									
									$comptMedLabo=$resultMedLabo->rowCount();
									
									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);
									if ($comptMedLabo !=0) {
										while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des ??ents
										{
											if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
											{
												$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
																
												$prixlabo=$ligneMedLabo->prixprestationExa - $prixPrestaRembou;
											
											}else{
												$prixlabo=$ligneMedLabo->prixprestationExa;

											}
											$insuranceamount = ($prixlabo * $assu_percent)/100;
											$totalgnl += $insuranceamount; 
										}
							
									}
						

									//med_radio
									$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, '.$presta_assu.' p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
									$resultMedRadio->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									
									$comptMedRadio=$resultMedRadio->rowCount();
									
									$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);
									if ($comptMedRadio !=0) {
										while($ligneMedRadio=$resultMedRadio->fetch())//on recupere la liste des ??ents
										{
											//echo "string";
											if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
											{
												$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
												
												$prixradio=$ligneMedRadio->prixprestationRadio - $prixPrestaRembou;
											
											}else{
												$prixradio=$ligneMedRadio->prixprestationRadio;
																				
											}
											$insuranceamount = ($prixradio * $assu_percent)/100;
											//echo $insuranceamount ;
											$totalgnl += $insuranceamount; 
										}
							
									}
						

									//med_inf
									$resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, '.$presta_assu.' p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
									$resultMedInf->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									
									$comptMedInf=$resultMedInf->rowCount();
									
									$resultMedInf->setFetchMode(PDO::FETCH_OBJ);
									if ($comptMedInf !=0) {
										while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des ??ents
										{
											if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
											{
												$prixPrestaRembou=$ligneMedInf->prixrembouInf;
												
												$prixinf=$ligneMedInf->prixprestation - $prixPrestaRembou;
											
											}else{
												$prixinf=$ligneMedInf->prixprestation;
																				
											}
											$insuranceamount = ($prixinf * $assu_percent)/100;
											$totalgnl += $insuranceamount; 
										}
							
									}

									//med_consom
									$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, '.$presta_assu.' p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
									$resultMedConsom->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									
									$comptMedConsom=$resultMedConsom->rowCount();
									
									$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);
									if ($comptMedConsom !=0) {
										while($ligneMedConsom=$resultMedConsom->fetch())//on recupere la liste des ??ents
										{
											if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
											{
												$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
												
												$prixconsom=($ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom) - $prixPrestaRembou;
											
											}else{
												$prixconsom=$ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom;
											
											}
											$insuranceamount = ($prixconsom * $assu_percent)/100;
											$totalgnl += $insuranceamount; 
										}
							
									}
						

									//med_medoc
									$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, '.$presta_assu.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
									$resultMedMedoc->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									
									$comptMedMedoc=$resultMedMedoc->rowCount();
									
									$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);
									if ($comptMedMedoc !=0) {
										while($ligneMedMedoc=$resultMedMedoc->fetch())//on recupere la liste des ??ents
										{
											if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
											{
												$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
							
												$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc) - $prixPrestaRembou;
											
											}else{
												$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc;
											
											}
											$insuranceamount = ($prixmedoc * $assu_percent)/100;
											$totalgnl += $insuranceamount; 
										}
							
									}
									$total +=$totalgnl; 
									//echo "Total for $compteur  = ".$totalgnl." pour $percent<br>";
						
								}else{
									$presta_assu='prestations_'.strtolower($ligneGnlBillReport->nomassurance);
									$percent = $lignetotal->billpercent;
									$assu_percent = 100;

									//consultation
									echo $ligneGnlBillReport->id_bill.'<br>';
									$resultConsu=$connexion->prepare('SELECT *FROM consultations c, '.$presta_assu.' p WHERE c.id_typeconsult=p.id_prestation AND c.id_factureConsult=:idbill ORDER BY c.id_consu DESC');
									$resultConsu->execute(array(
									'idbill'=>$ligneGnlBillReport->id_bill
									));
									$comptConsu=$resultConsu->rowCount();
									$resultConsu->setFetchMode(PDO::FETCH_OBJ);

									if ($comptConsu!=0) {
										while($ligneConsu=$resultConsu->fetch())
										{
											$prixconsult=$ligneConsu->prixtypeconsult;
											echo $prixconsultn=$ligneConsu->numero.'<br>';

											echo $totalgnl = $prixconsult.'<br>';
											// $insuranceamount = ($prixconsult * $assu_percent)/100;
											// echo $insuranceamount.'<br>'; 
										}
									}

									// //med_consult
									// $resultMedConsu=$connexion->prepare('SELECT *FROM med_consult mc, '.$presta_assu.' p WHERE mc.id_prestationConsu=p.id_prestation AND mc.id_factureMedConsu=:idbill ORDER BY mc.id_medconsu DESC');
									// $resultMedConsu->execute(array(
									// 'idbill'=>$ligneGnlBillReport->id_bill
									// ));
									
									// $comptMedConsu=$resultMedConsu->rowCount();
									
									// $resultMedConsu->setFetchMode(PDO::FETCH_OBJ);
									// if ($comptMedConsu !=0) {
									// 	while($ligneMedConsu=$resultMedConsu->fetch())//on recupere la liste des ??ents
									// 	{
									// 		if($ligneMedConsu->prixprestationConsu!=0 AND $ligneMedConsu->prixrembouConsu!=0)
									// 		{
									// 			$prixPrestaRembou=$ligneMedConsu->prixrembouConsu;
												
									// 			$prixconsu=$ligneMedConsu->prixprestationConsu - $prixPrestaRembou;
											
									// 		}else{
									// 			$prixconsu=$ligneMedConsu->prixprestationConsu;

									// 		}
									// 		$insuranceamount = ($prixconsu * $assu_percent)/100;
									// 		$totalgnl += $insuranceamount; 
									// 	}
							
									// }
						

									// //med_labo
									// $resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml, '.$presta_assu.' p WHERE ml.id_prestationExa=p.id_prestation AND ml.id_factureMedLabo=:idbill ORDER BY ml.id_medlabo DESC');
									// $resultMedLabo->execute(array(
									// 'idbill'=>$ligneGnlBillReport->id_bill
									// ));
									
									// $comptMedLabo=$resultMedLabo->rowCount();
									
									// $resultMedLabo->setFetchMode(PDO::FETCH_OBJ);
									// if ($comptMedLabo !=0) {
									// 	while($ligneMedLabo=$resultMedLabo->fetch())//on recupere la liste des ??ents
									// 	{
									// 		if($ligneMedLabo->prixprestationExa!=0 AND $ligneMedLabo->prixrembouLabo!=0)
									// 		{
									// 			$prixPrestaRembou=$ligneMedLabo->prixrembouLabo;
																
									// 			$prixlabo=$ligneMedLabo->prixprestationExa - $prixPrestaRembou;
											
									// 		}else{
									// 			$prixlabo=$ligneMedLabo->prixprestationExa;

									// 		}
									// 		$insuranceamount = ($prixlabo * $assu_percent)/100;
									// 		$totalgnl += $insuranceamount; 
									// 	}
							
									// }
						

									// //med_radio
									// $resultMedRadio=$connexion->prepare('SELECT *FROM med_radio mr, '.$presta_assu.' p WHERE mr.id_prestationRadio=p.id_prestation AND mr.id_factureMedRadio=:idbill ORDER BY mr.id_medradio DESC');
									// $resultMedRadio->execute(array(
									// 'idbill'=>$ligneGnlBillReport->id_bill
									// ));
									
									// $comptMedRadio=$resultMedRadio->rowCount();
									
									// $resultMedRadio->setFetchMode(PDO::FETCH_OBJ);
									// if ($comptMedRadio !=0) {
									// 	while($ligneMedRadio=$resultMedRadio->fetch())//on recupere la liste des ??ents
									// 	{
									// 		//echo "string";
									// 		if($ligneMedRadio->prixprestationRadio!=0 AND $ligneMedRadio->prixrembouRadio!=0)
									// 		{
									// 			$prixPrestaRembou=$ligneMedRadio->prixrembouRadio;
												
									// 			$prixradio=$ligneMedRadio->prixprestationRadio - $prixPrestaRembou;
											
									// 		}else{
									// 			$prixradio=$ligneMedRadio->prixprestationRadio;
																				
									// 		}
									// 		$insuranceamount = ($prixradio * $assu_percent)/100;
									// 		//echo $insuranceamount ;
									// 		$totalgnl += $insuranceamount; 
									// 	}
							
									// }
						

									// //med_inf
									// $resultMedInf=$connexion->prepare('SELECT *FROM med_inf mi, '.$presta_assu.' p WHERE mi.id_prestation=p.id_prestation AND mi.id_factureMedInf=:idbill ORDER BY mi.id_medinf DESC');
									// $resultMedInf->execute(array(
									// 'idbill'=>$ligneGnlBillReport->id_bill
									// ));
									
									// $comptMedInf=$resultMedInf->rowCount();
									
									// $resultMedInf->setFetchMode(PDO::FETCH_OBJ);
									// if ($comptMedInf !=0) {
									// 	while($ligneMedInf=$resultMedInf->fetch())//on recupere la liste des ??ents
									// 	{
									// 		if($ligneMedInf->prixprestation!=0 AND $ligneMedInf->prixrembouInf!=0)
									// 		{
									// 			$prixPrestaRembou=$ligneMedInf->prixrembouInf;
												
									// 			$prixinf=$ligneMedInf->prixprestation - $prixPrestaRembou;
											
									// 		}else{
									// 			$prixinf=$ligneMedInf->prixprestation;
																				
									// 		}
									// 		$insuranceamount = ($prixinf * $assu_percent)/100;
									// 		$totalgnl += $insuranceamount; 
									// 	}
							
									// }

									// //med_consom
									// $resultMedConsom=$connexion->prepare('SELECT *FROM med_consom mco, '.$presta_assu.' p WHERE mco.id_prestationConsom=p.id_prestation AND mco.id_factureMedConsom=:idbill ORDER BY mco.id_medconsom DESC');
									// $resultMedConsom->execute(array(
									// 'idbill'=>$ligneGnlBillReport->id_bill
									// ));
									
									// $comptMedConsom=$resultMedConsom->rowCount();
									
									// $resultMedConsom->setFetchMode(PDO::FETCH_OBJ);
									// if ($comptMedConsom !=0) {
									// 	while($ligneMedConsom=$resultMedConsom->fetch())//on recupere la liste des ??ents
									// 	{
									// 		if($ligneMedConsom->prixprestationConsom!=0 AND $ligneMedConsom->prixrembouConsom!=0)
									// 		{
									// 			$prixPrestaRembou=$ligneMedConsom->prixrembouConsom;
												
									// 			$prixconsom=($ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom) - $prixPrestaRembou;
											
									// 		}else{
									// 			$prixconsom=$ligneMedConsom->prixprestationConsom * $ligneMedConsom->qteConsom;
											
									// 		}
									// 		$insuranceamount = ($prixconsom * $assu_percent)/100;
									// 		$totalgnl += $insuranceamount; 
									// 	}
							
									// }
						

									// //med_medoc
									// $resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc mdo, '.$presta_assu.' p WHERE mdo.id_prestationMedoc=p.id_prestation AND mdo.id_factureMedMedoc=:idbill ORDER BY mdo.id_medmedoc DESC');
									// $resultMedMedoc->execute(array(
									// 'idbill'=>$ligneGnlBillReport->id_bill
									// ));
									
									// $comptMedMedoc=$resultMedMedoc->rowCount();
									
									// $resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);
									// if ($comptMedMedoc !=0) {
									// 	while($ligneMedMedoc=$resultMedMedoc->fetch())//on recupere la liste des ??ents
									// 	{
									// 		if($ligneMedMedoc->prixprestationMedoc!=0 AND $ligneMedMedoc->prixrembouMedoc!=0)
									// 		{
									// 			$prixPrestaRembou=$ligneMedMedoc->prixrembouMedoc;
							
									// 			$prixmedoc=($ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc) - $prixPrestaRembou;
											
									// 		}else{
									// 			$prixmedoc=$ligneMedMedoc->prixprestationMedoc * $ligneMedMedoc->qteMedoc;
											
									// 		}
									// 		$insuranceamount = ($prixmedoc * $assu_percent)/100;
									// 		$totalgnl += $insuranceamount; 
									// 	}
							
									// }
									// $total +=$totalgnl; 
									//echo "Total for $compteur  = ".$totalgnl." pour $percent<br>";
									/*$totalgnl = $lignetotal->totalgnlprice;
									$total += $totalgnl;*/
								}


									
							}
							echo $total;
							//	}
						?>
					</td>
			<?php
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
			
			
		}
	//}
		
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