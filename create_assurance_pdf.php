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


	$idDoneby=$_SESSION['id'];

	$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u AND c.id_u=:operation');
	$resultatsDoneby->execute(array(
	'operation'=>$idDoneby
	));

	$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
	if($ligneDoneby=$resultatsDoneby->fetch())
	{
	  $doneby = $ligneDoneby->full_name;
	}
	
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo 'Tarrif #'.$_GET['chosen_assurances']; ?></title>

	<link rel="icon" href="images/favicon.ico">
    <link rel="shortcut icon" href="images/favicon.ico" />

    <link href="cssBourbonCoffee/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	<meta charset="utf-8">
		
		
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
			font-family: calibri !important;
		}
	</style>
	
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
if($connected==true)
{
	
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
		$code->setLabel('#Tarrif_'.$_GET['chosen_assurances'].' #');
		$code->parse(''.$_GET['chosen_assurances'].'');
		
		// Drawing Part
		$drawing = new BCGDrawing('barcode/png/barcode'.$_GET['chosen_assurances'].'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

?>
	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #eee; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
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

						<td style="text-align:left;width:100%">
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
				<img src="barcode/png/barcode'.$_GET['chosen_assurances'].'.png" style="height:auto;"/>
			</td>
			
		</tr>
		
	</table>';

echo $barcode;
?>

	 <table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">

	<tr>
	    <td style="text-align:left;width:10%;">
	        <h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
	    </td>

	    <td style="text-align:left;">
	        <h2 style="font-size:150%; font-weight:600;">Tarrif <?php echo $_GET['chosen_assurances']; ?></h2>
	    </td>

	    <td style="text-align:right">

	        <form method="post" action="create_assurance_pdf.php?assurance_selection_btn=ok&chosen_assurances=<?php echo $_GET['chosen_assurances'];?>&createReportPdf=ok" enctype="multipart/form-data" class="buttonBill">

	            <button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
	        </form>

	    </td>
	    <td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">

	        <a href="prices_edit.php?assurances_name=<?php echo $_GET['chosen_assurances'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
	            <button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
	        </a>

	    </td>
	</tr>

	</table>
	<table class="printPreview tablesorter3" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:10px;">
    <thead>
    <tr>
    	<th style="width:8px;"><?php echo 'Categorie';?></th>
        <th style="width:25%"><?php echo 'Nom prestation';?></th>
        <th style="width:25%"><?php echo 'Name prestation';?></th>
		<th style="width:25%"><?php echo 'Prix';?></th>
		<th style="width:25%"><?php echo 'Mesure';?></th>
    </thead>
    <tbody>
		<?php
		$assurances_name = $_GET['chosen_assurances'];
		// echo 'SELECT * FROM prestations_'.$assurances_name.' ORDERBY id_categopresta ASC';
		$resultasPrestation=$connexion->query('SELECT * FROM prestations_'.$assurances_name.' ORDER BY id_categopresta ASC');
	    $resultasPrestation->setFetchMode(PDO::FETCH_OBJ);
	    //$ligneM = $resultasPrestation->fetchAll();
	   while ($ligneM = $resultasPrestation->fetch()) {
	   	?>
	   	<tr>
	   		<td>
				<?php
					$id_catego = $ligneM->id_categopresta;
					$selectenamecatego = $connexion->prepare('SELECT * FROM categopresta_ins WHERE id_categopresta=:id_categopresta');
					$selectenamecatego->execute(array(
						'id_categopresta'=>$id_catego
					));
					$selectenamecatego->setFetchMode(PDO::FETCH_OBJ);
					if($lignenamecatego = $selectenamecatego->fetch()){
						echo $lignenamecatego->nomcategopresta;
					}else{
						echo "---";
					}
				?>
			</td>
	   		<td><?php echo $ligneM->nompresta;?></td>
	   		<td><?php echo $ligneM->nompresta;?></td>
	   		<td style="text-align: right;font-weight: bold;"><?php echo $ligneM->prixpresta;?></td>
	   		<td style="text-align: right;"><?php echo $ligneM->mesure;?></td>
	   	</tr>
	   <?php }?>
	</tbody>
	</table>

</div>
<?php
}else{
	echo '<script text="text/javascript">alert("You are not logged in");</script>';
	echo '<script text="text/javascript">document.location.href="index.php"</script>';
}
?>
</body>
</html>