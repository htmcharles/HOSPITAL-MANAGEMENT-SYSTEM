<?php


$html = '
<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l\'activer pour votre navigateur
</noscript>
<head>
	<title>Bill</title>

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


	
	<div class="account-container" style="margin: 10px auto auto; width:90%; border: 1px solid #eee; background:#fff; padding:20px; border-radius:3px; font-size:85%;">
<table style="width:100%">
		
		<tr>
			<td style="text-align:left; width:60%">
				 Done by : KARIKURUBU  Looz it
			</td>
			
			<td style="text-align:right;">
				<img src="barcode/png/barcodeCSC15A01.png" style="height:auto;"/>	
			</td>
							
		</tr>
		
	</table>			
<table style="width:100%; margin-top:20px;">
			
			<tr>
				<td style="text-align:left;">
					<span style="font-weight:bold">Full name: </span>
					abcd xxxx<br/>
					<span style="font-weight:bold">Gender: </span>homme<br/>
					<span style="font-weight:bold">Adress: </span>Kigali, aaaa, bbbb
				</td>
				
				<td style="text-align:center;">
					<span style="font-weight:bold">Insurance type: </span>SORAS<br/>
					<span style="font-weight:bold">Patient payment: </span>30 %<br/>
					<span style="font-weight:bold">Insurance payment: </span>70 %
				</td>
				
				<td style="text-align:right;">
					<span style="font-weight:bold">S/N: </span>P9<br/>
					<span style="font-weight:bold">Date of birth: </span>2009-06-09<br/>
					<span style="font-weight:bold">Date of Consultation: </span>2015-09-19
					
				</td>
								
			</tr>		
		</table><table style="width:100%; margin:20px auto auto;"> 
		<tr> 
			<td style="text-align:left; width:33%;">
				<h4>2015-09-26</h4>
			</td>
			
			<td style="text-align:center; width:33%;">
				<h2 style="font-size:150%; font-weight:600;">Bill n° 015-CSB/0A003</h2>
			</td>
			
			<td style="text-align:right; width:33%;">
				<form method="post" action="printTempBilling.php?num=P9cashier=CSC15A01&datefacture=2015-09-19&createPdf=ok" enctype="multipart/form-data" class="buttonBill">

					<button type="submit" class="btn-large" name="savebill" style="width:200px;"><i class="fa fa-print fa-lg fa-fw"></i> Print Bill</button>
				</form>
			</td>
			<td>
				<a href="billing.php?num=P9&cashier=CSC15A01&deletebill=49&datefacture=ok class="buttonBill""><button class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> Cancel</button></a>
			</td>
		</tr>
	</table><table class="printPreview" cellspacing="0" style="margin:auto auto 5px;"> 
				<thead> 
					<tr>
						<th style="width:40%;">Type de Consultation</th>
						<th style="width:20%;">Price</th>
						<th style="width:20%;">Patient price</th>
						<th style="width:20%;">Insurance price</th>
					</tr> 
				</thead> 


				<tbody><tr style="text-align:center;">
						<td>Urgent follow-up in 24h </td><td>8939<span style="font-size:80%; font-weight:normal;">Rwf</span></td><td>2681.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>6257.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr><tr style="text-align:center;">
						<td>Weekend, holidays and night general practitioner consultations</td><td>4470<span style="font-size:80%; font-weight:normal;">Rwf</span></td><td>1341<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>3129<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr><tr style="text-align:center;">
						<td>Paramedical follow-up</td><td>698<span style="font-size:80%; font-weight:normal;">Rwf</span></td><td>209.4<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>488.6<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr><tr style="text-align:center;">
						<td>Urgent follow-up in 24h </td><td>8939<span style="font-size:80%; font-weight:normal;">Rwf</span></td><td>2681.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>6257.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr><tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">23046<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">6913.8<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">16132.2<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>			
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead> 
					<tr>
						<th style="width:40%">Consultation</th>
						<th style="width:20%">Price</th>
						<th style="width:20%">Patient price</th>
						<th style="width:20%">Insurance price</th>
					</tr> 
				</thead> 

				<tbody>
								<tr style="text-align:center;">
						<td>
						Cauteris.verrues plant</td><td>6539<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						<td>
						1961.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4577.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Excision cheloide</td><td>6539<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						<td>
						1961.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4577.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Curet.Verrues plant</td><td>6539<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						<td>
						1961.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4577.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						azerty</td><td>6666<span style="font-size:80%; font-weight:normal;">Rwf</span></td>							<td>
							1999.8<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td>
							4666.2<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
								<tr style="text-align:center;">
						<td>
						Curetage molliscum</td><td>6539<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						<td>
						1961.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4577.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						qwerty</td><td>7777<span style="font-size:80%; font-weight:normal;">Rwf</span></td>							<td>
							2333.1<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td>
							5443.9<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
								<tr style="text-align:center;">
						<td>
						Excision cheloide</td><td>6539<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						<td>
						1961.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4577.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Cauteris.verrues</td><td>6539<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						<td>
						1961.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4577.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					
					</tr>	
					<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							53677<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							16103.1<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							37573.9<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
						
			<table class="printPreview" cellspacing="0" style="margin:auto auto 5px;">
				<thead> 
					<tr>
						<th style="width:40%">Nursery</th>
						<th style="width:20%">Price</th>						
						<th style="width:20%">Patient price</th>
						<th style="width:20%">Insurance price</th>
					</tr> 
				</thead> 

				<tbody>
								<tr style="text-align:center;">
						<td>
						Successive I.V injection/Day</td><td>425<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						</td>
						<td>
						127.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						297.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Wound care</td><td>600<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						</td>
						<td>
						180<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						420<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						zDXS</td><td>888<span style="font-size:80%; font-weight:normal;">Rwf</span></td>							<td>
							266.4<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td>
							621.6<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
								<tr style="text-align:center;">
						<td>
						Adult sampling</td><td>360<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						</td>
						<td>
						108<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						252<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Wound care</td><td>600<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						</td>
						<td>
						180<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						420<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Child blood sampling</td><td>855<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						</td>
						<td>
						256.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						598.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Successive I.V injection/Day</td><td>425<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						</td>
						<td>
						127.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						297.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td>
						Successive I.V injection/Day</td><td>425<span style="font-size:80%; font-weight:normal;">Rwf</span></td>						</td>
						<td>
						127.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						297.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
								<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							4578<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							1373.4<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							3204.6<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
				</tbody>
			</table>
						
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
								<tr style="text-align:center;">
						<td>
							Ca2+</td><td>6083 Rwf</td>						</td>
						<td>
						1824.9<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4258.1<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
								<tr style="text-align:center;">
						<td>
							Amylase</td><td>6083 Rwf</td>						</td>
						<td>
						1824.9<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td>
						4258.1<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
								<tr style="text-align:center;">
						<td>
							hahaha!!</td><td>9999 Rwf</td>							<td>
							2999.7<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
							<td>
							6999.3<span style="font-size:80%; font-weight:normal;">Rwf</span>
							</td>
								<tr style="text-align:center;">
						<td></td>
						<td style="font-size: 13px; font-weight: bold;">
							22165<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							6649.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
						<td style="font-size: 13px; font-weight: bold;">
							15515.5<span style="font-size:80%; font-weight:normal;">Rwf</span>
						</td>
					</tr>
					
				</tbody>
			</table>
						<table class="printPreview" cellspacing="0" style="margin:10px auto auto; border-top:none">
				
				<tr>
					<td style="width:40%; text-align:right; font-size:15px; font-weight:bold">Total Final</td>
					<td style="width: 20%; font-size: 15px; font-weight: bold;">
						103466<span style="font-size:80%; font-weight:normal;">Rwf</span>
					</td>
					<td style="width: 20%; font-size: 15px; font-weight: bold;">
						31039.8<span style="font-size:80%; font-weight:normal;">Rwf</span>
					</td>
					<td style="width: 20%; font-size: 15px; font-weight: bold;">
						72426.2<span style="font-size:80%; font-weight:normal;">Rwf</span>
					</td>
				</tr>
			</table>
			

	
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
			if( hour =="heures"){
			document.getElementById("tableheure").style.display="inline";
			}
			
		}

		</script>

	</div>

	
</body>

</html>
';


//==============================================================
//==============================================================
//==============================================================

include("/mpdf60/mpdf.php");
$mpdf=new mPDF('c'); 

$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
$mpdf->charset_in='windows-1252';
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================


?>