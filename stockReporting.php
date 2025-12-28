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

	if(isset($_GET['ConsumablesReport'])){
		$statusCate = 21;
	}else{
		if(isset($_GET['MedicamentReport'])){
			$statusCate = 22;
		}else{
			$statusCate = 23;
		}
	}

	if(isset($_GET['createRN']))
	{
		$createRN=$_GET['createRN'];
	}
	
	if(isset($_GET['stringResult']))
	{
		$stringResult=$_GET['stringResult'];
	}
	

	if(isset($_GET['stockInrepo']))
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
	
	
	if(isset($_GET['stockOutrepo']))	
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
	
	}	

	if(isset($_GET['ExpiredDrugs']))	
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
	<title><?php echo 'Stock Report#'.$sn; ?></title>

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

if($connected==true AND isset($_SESSION['id']))
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
			$codeDoneby = $ligneCoordi->codecoordi;
		}
	}
	
	if(isset($_SESSION['codeM']))
	{	
		$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u and m.id_u=:operation');
		$resultatsMed->execute(array(
		'operation'=>$idDoneby	
		));

		$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
		
		if($ligneMed=$resultatsMed->fetch())
		{
			$doneby = $ligneMed->full_name;
			$codeDoneby = $ligneMed->codemedecin;
		}
	}	

	if(isset($_SESSION['codeS']))
	{	
		$resultatsto=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper st WHERE u.id_u=st.id_u and st.id_u=:operation');
		$resultatsto->execute(array(
		'operation'=>$_GET['iduser']	
		));
		//echo $count = $resultatsto->rowCount();
		//echo 'count='.$count;
		$resultatsto->setFetchMode(PDO::FETCH_OBJ);
		
		if($lignesto=$resultatsto->fetch())
		{
			$doneby = $lignesto->full_name;
			$codeDoneby = $lignesto->codestock;
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

		if(isset($_GET['pid']) AND $_GET['pid'] !=""){
			$productId = 'AND sti.pid='.$_GET['pid'];
		}else{
			$productId = "";
		}


?>
	<div id="Report" class="account-container" style="margin: 10px auto auto; width:98%; border: 1px solid #eee; background:#fff; padding:5px; padding-bottom:0px; border-radius:3px; font-size:95%;">
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
							<img src="images/logos.png">				  
						</td>

						<td style="text-align:left;width:80%">
						  <span style="border-top:none;border-bottom:2px solid #ccc; font-size:110%; font-weight:900;display: none;">POLYCLINIC DE L\'ETOILE</span> <br/> 
						  <span style="font-size:90%;display: none;"> KG 1 AVENUE No 36 Remera, Kigali, Rwanda.<br/>
							 Phone: (+250) 788 574 667/ Call Reception (+250) 788 309 166 <br/>
	                   		 E-mail: polycliniquedeletoile@gmail.com </span>
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
	if(isset($_SESSION['codeS']))
	{
		
		$resultatsto=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper st WHERE u.id_u=st.id_u and st.id_u=:operation');
		$resultatsto->execute(array(
		'operation'=>$_GET['iduser']	
		));
		$resultatsto->setFetchMode(PDO::FETCH_OBJ);
		
		
		if($lignesto=$resultatsto->fetch())
		{
			$codeMed=$lignesto->codestock;
			$fullname=$lignesto->nom_u.' '.$lignesto->prenom_u;
			
			if($lignesto->sexe=="M")
			{
				$sexe = "Male";
			}elseif($lignesto->sexe=="F"){			
				$sexe = "Female";			
			}else{				
				$sexe="";
			}
			
			$resultAdresse=$connexion->prepare('SELECT *FROM province p, district d, sectors s WHERE p.id_province=d.id_province AND d.id_district=s.id_district AND p.id_province=:idProv AND d.id_district=:idDist AND s.id_sector=:idSect');
			$resultAdresse->execute(array(
			'idProv'=>$lignesto->province,
			'idDist'=>$lignesto->district,
			'idSect'=>$lignesto->secteur
			));
					
			$resultAdresse->setFetchMode(PDO::FETCH_OBJ);

			$comptAdress=$resultAdresse->rowCount();
			
			if($ligneAdresse=$resultAdresse->fetch())
			{
				if($ligneAdresse->id_province == $lignesto->province)
				{
					$adresse = $ligneAdresse->nomprovince.', '.$ligneAdresse->nomdistrict.', '.$ligneAdresse->nomsector;
					
				}
			}elseif($lignesto->autreadresse!=""){
					$adresse=$lignesto->autreadresse;
			}else{
				$adresse="";
			}
		}
		
		$idDoc=$_GET['iduser'];
		$dailydateperso=$_GET['dailydateperso'];
		$docVisit=$_GET['docVisit'];		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;"><?php echo $stringResult;?> Store Keeper Report <span style="font-weight: bold;background: #fff;padding: 3px 10px;border-radius: 3px;border: 1px solid #ddd"> <?php if(isset($_GET['stockInrepo'])){echo "Stock In";}else{if(isset($_GET['stockOutrepo'])){echo "Stock Out";}else{if(isset($_GET['ExpiredDrugs'])){echo "Expired Drugs";}}} ?></span> #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="stockReporting.php?iduser=<?php echo $_GET['iduser'];?>&dailydateperso=<?php echo $dailydateperso;?>&pid=<?php $productId; ?>&docVisit=<?php echo $docVisit;?><?php if(isset($_GET['divPersoMedicReport'])){echo '&divPersoMedicReport=ok';}if(isset($_GET['divPersoBillReport'])){echo '&divPersoBillReport=ok';}?>&stringResult=<?php echo $_GET['stringResult'];?>&createReportPdf=ok&<?php if(isset($_GET['stockInrepo'])){echo'&stockInrepo=ok';}else{if(isset($_GET['stockOutrepo'])){echo'&stockOutrepo=ok';}else{if(isset($_GET['ExpiredDrugs'])){echo'&ExpiredDrugs=ok';}}} ?>&createRN=<?php echo $createRN;?>" enctype="multipart/form-data" class="buttonBill">
						<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
						<a href="report.php?iduser=<?php echo $_GET['iduser'];?>&coordi=<?php echo $_SESSION['id'];?>&StockChoose=ok<?php if(isset($_GET['MedicamentReport'])){echo "&MedicamentReport=ok";}else{if(isset($_GET['ConsumablesReport'])){echo "&ConsumablesReport=ok";}else{if(isset($_GET['MaterialsReport'])){echo "&MaterialsReport=ok";}}} ?>&stockInrepo=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
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
				<span style="font-weight:bold;margin-right:5px;">Store keeper name : </span>		
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
	
		if(isset($_GET['stockInrepo']))
		{
	?>
		<div id="divPersoMedicReport">

			<?php
			$resultStockIn=$connexion->query('SELECT *  FROM stockin_history sthI,stockin sti WHERE sthI.status = '.$statusCate.' '.$productId.' AND sti.sid=sthI.sid '.$dailydateperso);	

			$resultStockIn->setFetchMode(PDO::FETCH_OBJ);

			$comptStockIn=$resultStockIn->rowCount();
			//echo $comptStockIn;
	
			$i=0;
			
			if($comptStockIn != 0)
			{
				?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:2%; border-right: 1px solid #bbb;text-align: center;">N°</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Product Name</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Qantity</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Price</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Total</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">manufacturedate</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">expireddate</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">barcode</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Done On</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Done By</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
				// $date='0000-00-00';
				$TotalAmount = 0;
				$compteur=1;
				
					while($ligneStock=$resultStockIn->fetch())
					{
					
						$consult = "";
						$nursery = "";
						$labs = "";						
						$diagno = "";
						
			?>
					<tr>
						<td style="text-align:center;">
						<?php
							echo $compteur;
						?>
						</td>
						
						<td style="text-align: center;">
						<?php 
							echo $ligneStock->product_name.'('. $ligneStock->s_hid.')';
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->quantityIn;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $price = $ligneStock->price;
							
						?>
						</td>

						<td style="text-align: center;">
							<?php echo $total = $price * $ligneStock->quantityIn; 
								$TotalAmount = $TotalAmount + $total;
							?>
						</td>
						<td style="text-align: center;">
						<?php 
							echo $ligneStock->manufacturedate;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->expireddate;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->barcode;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->stockouttime;
						?>
						</td>						

						<td style="text-align: center;">
						<?php 
							$resultatsto=$connexion->prepare('SELECT * FROM utilisateurs u, stockkeeper st WHERE u.id_u=st.id_u and st.id_u=:operation');
							$resultatsto->execute(array(
							'operation'=>$ligneStock->doneby	
							));
							$resultatsto->setFetchMode(PDO::FETCH_OBJ);
							
							
							if($lignesto=$resultatsto->fetch())
							{
								echo $lignesto->full_name;
							}
						?>
						</td>
						
					</tr>
			<?php
						$i++;
						
						$compteur++;
					}
			?>		
			<tr>
				<td colspan="4" style="font-weight: bold;color: #A00000;">Total Amount Used To Buy All products</td>
				<td style="text-align: center;font-weight: bold;font-size: 20px;border-right: 1px solid #bbb;"><?php echo number_format($TotalAmount,2); ?> <span style="font-weight: normal;font-size: 15px;">Rwf</span></td>
			</tr>
				</tbody>
				</table>
			<?php
			}
		}else{

			if(isset($_GET['stockOutrepo']))
			{
			?>

			<?php
			$resultStockOut=$connexion->query('SELECT *  FROM stockout_history sth,stockin sti WHERE sth.status = '.$statusCate.' '.$productId.' AND sti.sid=sth.sid '.$dailydateperso);		
					
			$resultStockOut->setFetchMode(PDO::FETCH_OBJ);

			$comptStockOut=$resultStockOut->rowCount();
			//echo $comptConsult;
	
			$i=0;
			
			if($comptStockOut != 0)
			{
				?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:2%; border-right: 1px solid #bbb;text-align: center;">N°</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Product Name</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Existing Quantity</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Taken Quantity</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Remain Balance</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Price</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Total</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Manufacture Date</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Expired Date</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Barcode</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Done On</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Taken By</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Done By</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
				// $date='0000-00-00';
				$compteur=1;
				$TotalAmountOut = 0;
					while($ligneStock=$resultStockOut->fetch())
					{
					
						$consult = "";
						$nursery = "";
						$labs = "";						
						$diagno = "";
						
			?>
					<tr>
						<td style="text-align:center;">
						<?php
							echo $compteur;
						?>
						</td>
						
						<td style="text-align: center;">
						<?php 
							echo $ligneStock->product_name;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $existingQty = $ligneStock->existing_qty;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $orderedQty = $ligneStock->quantityOut;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $currentQty = $existingQty - $orderedQty;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $priceOut = $ligneStock->price;
						?>
						</td>

						<td style="text-align: center;">
							<?php echo $totalOut = $priceOut * $ligneStock->quantityOut; 
								$TotalAmountOut = $TotalAmountOut + $totalOut;
							?>
						</td>
						<td style="text-align: center;">
						<?php 
							echo $ligneStock->manufacturedate;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->expireddate;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->barcode;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->stockouttime;
						?>
						</td>		

						<td style="text-align: center;">
						<?php 
							echo strtoupper($ligneStock->takenby) ;
						?>
						</td>						

						<td style="text-align: center;">
						<?php 
							$resultatsto=$connexion->prepare('SELECT * FROM utilisateurs u, stockkeeper st WHERE u.id_u=st.id_u and st.id_u=:operation');
							$resultatsto->execute(array(
							'operation'=>$ligneStock->doneby	
							));
							$resultatsto->setFetchMode(PDO::FETCH_OBJ);
							
							
							if($lignesto=$resultatsto->fetch())
							{
								echo $lignesto->full_name;
							}else{
								echo "Not Found!";
							}
						?>
						</td>
						
					</tr>
			<?php
						$i++;
						
						$compteur++;
					}
			?>		
			<tr>
				<td colspan="6" style="font-weight: bold;color: #A00000;">Total Amount Used To Buy All products</td>
				<td style="text-align: center;font-weight: bold;font-size: 20px;border-right: 1px solid #bbb;"><?php echo number_format($TotalAmountOut,2); ?> <span style="font-weight: normal;font-size: 15px;">Rwf</span></td>
			</tr>
				</tbody>
				</table>


		<?php
		 }
	}else{
		if(isset($_GET['ExpiredDrugs']))
			{
			?>

			<?php
			//echo 'SELECT *  FROM stockout_history sth,stockin sti WHERE sth.status = '.$statusCate.'   AND sti.sid=sth.sid '.$dailydateperso.'  GROUP BY sti.sid ';
			$resultStockOut=$connexion->query('SELECT *  FROM stockout_history sth,stockin sti WHERE sti.status = '.$statusCate.' AND sti.quantity!=0   AND sti.sid=sth.sid '.$dailydateperso.' GROUP BY sti.sid');		
					
			$resultStockOut->setFetchMode(PDO::FETCH_OBJ);

			$comptStockOut=$resultStockOut->rowCount();
			//echo $comptStockOut;
	
			$i=0;
			
			if($comptStockOut != 0)
			{
				?>
				
				<table class="printPreview tablesorter3" cellspacing="0" style="margin:10px auto auto; width:100%;">
				
				<thead>
					<tr>
						<th style="width:2%; border-right: 1px solid #bbb;text-align: center;">N°</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Product Name</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align: center;">Qantity</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Price</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Total</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Manufacture Date</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Expired Date</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Barcode</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Done On</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Done By</th>
					</tr> 
				</thead> 


				<tbody>
			<?php
				// $date='0000-00-00';
				$compteur=1;
				
					while($ligneStock=$resultStockOut->fetch())
					{
					
						$consult = "";
						$nursery = "";
						$labs = "";						
						$diagno = "";
						
			?>
					<tr>
						<td style="text-align:center;">
						<?php
							echo $compteur;
						?>
						</td>
						
						<td style="text-align: center;">
						<?php 
							echo $ligneStock->product_name;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->quantity;
						?>
						</td>
						<td style="text-align: center;">
						<?php 
							echo $price = $ligneStock->price;
						?>
						</td>
						<td style="text-align: center;">
							<?php echo $total = $price * $ligneStock->quantity; 
								$TotalAmount = $TotalAmount + $total;
							?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->manufacturedate;
						?>
						</td>

						<td style="text-align: center;background: red;color: white;font-weight: bold;">
						<?php 
							echo $ligneStock->expireddate;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->barcode;
						?>
						</td>

						<td style="text-align: center;">
						<?php 
							echo $ligneStock->stokin;
						?>
						</td>							

						<td style="text-align: center;">
						<?php 
							$resultatsto=$connexion->prepare('SELECT * FROM utilisateurs u, stockkeeper st WHERE u.id_u=st.id_u and st.id_u=:operation');
							$resultatsto->execute(array(
							'operation'=>$ligneStock->addby	
							));
							$resultatsto->setFetchMode(PDO::FETCH_OBJ);
							
							
							if($lignesto=$resultatsto->fetch())
							{
								echo $lignesto->full_name;
							}
						?>
						</td>
						
					</tr>
			<?php
						$i++;
						
						$compteur++;
					}
			?>		
			<tr>
				<td colspan="4" style="font-weight: bold;color: #A00000;">Total Amount Used To Buy All products</td>
				<td style="text-align: center;font-weight: bold;font-size: 20px;border-right: 1px solid #bbb;"><?php echo number_format($TotalAmount,2); ?> <span style="font-weight: normal;font-size: 15px;">Rwf</span></td>
			</tr>
				</tbody>
				</table>


				<?php
				 }
				}
			}
		}
			?>
		</div>

			</div>
			<?php
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
					// if (isset($_GET['gnlmed'])) {
					// 	echo '<script text="text/javascript">document.location.href="doctor_report.php?gnlmed='.$_GET['gnlmed'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
					// }else{
					// 	echo '<script text="text/javascript">document.location.href="doctor_report.php?med='.$_GET['med'].'&dailydateperso='.$_GET['dailydateperso'].'&docVisit='.$_GET['docVisit'].'&stringResult='.$_GET['stringResult'].'&divPersoBillReport=ok&createReportPdf=ok&createRN=0"</script>';
					// }
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