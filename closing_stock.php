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

	if(isset($_GET['createRN']))
	{
		$createRN = $_GET['createRN'];
	}else{
		$createRN = 0;
	}

	$sn = showRn('CS');


    if(isset($_GET['closeStock'])){
        //check if this transaction does not happed earlier

        $checkTransa = $connexion->prepare("SELECT * FROM closed_stock_counter WHERE closed_on=:closed_on");
        $checkTransa->execute(array('closed_on'=>$annee));
        $count = $checkTransa->rowCount();

        if($count==0){

            $ClosingStock=$connexion->query('SELECT *  FROM stockin');
            $ClosingStock->setFetchMode(PDO::FETCH_OBJ);
            $comptStock=$ClosingStock->rowCount();	
            if($comptStock!=0){

                while($StockResults = $ClosingStock->fetch()){
                    // save in closed stock
                    $insertCloseHisto = $connexion->prepare("INSERT INTO closed_stock(sid,closed_quantity,closed_on,closed_by) VALUES(:sid,:closed_quantity,:closed_on,:closed_by)");
                    $insertCloseHisto->execute(array('sid'=>$StockResults->sid,'closed_quantity'=>$StockResults->quantity,'closed_on'=>$annee,'closed_by'=>$_SESSION['id']));
                }

                //Record this transaction

                $SaveTrans = $connexion->prepare("INSERT INTO closed_stock_counter (closed_on,closed_by)VALUES(:closed_on,:closed_by)");
                $SaveTrans->execute(array('closed_on'=>$annee,'closed_by'=>$_SESSION['id']));

            echo"<script>alert('Stock Closed Successfully!');</script>";
	        echo '<script type="text/javascript">document.location.href="closing_stock.php";</script>';
                
            }	
        }else{
	        echo '<script type="text/javascript">document.location.href="closing_stock.php?displayModal=ok";</script>';
        }
    }

    if(isset($_GET['updateStock'])){
            $ClosingStock=$connexion->query('SELECT *  FROM stockin');
            $ClosingStock->setFetchMode(PDO::FETCH_OBJ);
            $comptStock=$ClosingStock->rowCount();	
            if($comptStock!=0){

            while($StockResults = $ClosingStock->fetch()){
                //Update closed stock
                $insertCloseHisto = $connexion->query("UPDATE `closed_stock` SET `closed_quantity`=".$StockResults->quantity." WHERE `sid`=".$StockResults->sid." AND `closed_on`='".$annee."'");
				//echo "UPDATE `closed_stock` SET `closed_quantity`=".$StockResults->quantity." WHERE `sid`=".$StockResults->sid." AND `closed_on`='".$annee."'";
            }

            echo"<script>alert('Closed Stock Updated Successfully!');</script>";
	        echo '<script type="text/javascript">document.location.href="closing_stock.php";</script>';

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
	<meta charset="utf-8"/>
	<title><?php echo 'Stock Closing#'.$sn; ?></title>

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

        /* The Modal (background) */
        .modal {
        /* display: none; Hidden by default */
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
        font-family: Ubuntu;
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

	if(isset($_SESSION['codeS']))
	{	
		$resultatsto=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper st WHERE u.id_u=st.id_u and st.id_u=:operation');
		$resultatsto->execute(array(
		'operation'=>$idDoneby	
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

?>
	<div id="Report" class="account-container" style="margin: 10px auto auto; width:98%; border: 1px solid #eee; background:#fff; padding:5px; padding-bottom:0px; border-radius:3px; font-size:80%;">
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
				<img src="barcode/png/barcode'.$codeDoneby.'.png" style="height:auto;"/>	
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
		'operation'=>$idDoneby		
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
		
		$idDoc=$idDoneby;
		// $dailydateperso=$_GET['dailydateperso'];
		// $docVisit=$_GET['docVisit'];		
	?>
		
		<table cellpadding=3 style="background:#fff; margin:auto auto 10px auto; padding: 10px; width:100%;">
			
			<tr>
				<td style="text-align:left;width:10%;">
					<h4><?php echo date('d-M-Y', strtotime($annee));?></h4>
				</td>
				
				<td style="text-align:left;">
					<h2 style="font-size:150%; font-weight:600;">Today Current Report <span style="font-weight: bold;background: #fff;padding: 3px 10px;border-radius: 3px;border: 1px solid #ddd"> Closing Stock</span> #<?php echo $sn;?></h2>
				</td>
				
				<td style="text-align:right">
					
					<form method="post" action="Currentstock.php?StockChoose=ok&<?php if(isset($_GET['ConsumablesReport'])){echo "ConsumablesReport=ok";}else{if(isset($_GET['MedicamentReport'])){echo "MedicamentReport=ok";}else{if(isset($_GET['MaterialsReport'])){echo "MaterialsReport=ok";}}} ?>&createReportPdf=ok&createRN=1" enctype="multipart/form-data" class="buttonBill">

						<button type="submit" class="btn-large" name="savebill" style="width:200px;font-family:calibri !important;"><i class="fa fa-print fa-lg fa-fw"></i> Print Report</button>
					</form>
					
				</td>
                <td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
                    <a href="closing_stock.php?closeStock<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
                        <button class="btn-large-inversed" style="width:200px;font-family:calibri !important;"><i class="fa fa-check fa-lg fa-fw"></i> Close The Day</button>
                    </a>
				</td>
				<td style="text-align:<?php if(isset($_SESSION['codeC'])){ echo 'right';}else{ echo 'left';}?>">
					
                    <a href="report.php?StockChoose=ok&<?php if(isset($_GET['ConsumablesReport'])){echo "ConsumablesReport=ok";}else{if(isset($_GET['MedicamentReport'])){echo "MedicamentReport=ok";}else{if(isset($_GET['MaterialsReport'])){echo "MaterialsReport=ok";}}} ?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="buttonBill">
                        <button class="btn-large-inversed" style="width:150px;font-family:calibri !important;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
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
	?>
		<div id="divPersoMedicReport">

			<?php
			//echo 'SELECT *  FROM stockin sti WHERE sti.status = '.$statusCate;
			$resultStockIn=$connexion->query('SELECT *  FROM stockin');		
					
			$resultStockIn->setFetchMode(PDO::FETCH_OBJ);

			$comptStockIn=$resultStockIn->rowCount();
			//echo $comptConsult;
	
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
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Manufacturedate</th>
						<th style="width:15%; border-right: 1px solid #bbb;text-align: center;">Expireddate</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Barcode</th>
						<th style="width:10%; border-right: 1px solid #bbb;text-align:center;">Supplier</th>
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
						
						<td style="text-align: left;text-transform: capitalize;font-weight: bold;">
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
							$price = $ligneStock->price;
							echo number_format($price);
							
						?>
						</td>

						<td style="text-align: center;">
							<?php $total = $price * $ligneStock->quantity; 
								$TotalAmount = $TotalAmount + $total;

								echo number_format($total);
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
							echo $ligneStock->suppliername;
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
			?>
		</div>

			</div>
			<?php
				if(isset($_GET['createReportPdf']) AND $_GET['createRN']==1)
				{				
					createRN('CS');
				}
			
			
		}
    ?>

        <?php if(isset($_GET['displayModal'])){ ?>
            <!-- The Modal -->
            <div id="myModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                <a href="closing_stock.php"><span class="close"><i class="fa fa-close"></i></span></a>
                <p style="text-align: center;color: #A00000;border-bottom: 1px solid #ddd;">Closing Day</p>
                <br>
                    <h4>This Transaction Already Done! Do you Want To make change?</h4>
                    <br>
                    <a href="closing_stock.php?updateStock=ok" class="btn-large" style="float:right;">Yes</a>
                </div>
                </div>
            </div>
        <?php }?>

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