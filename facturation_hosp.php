<?php
	session_start();

	include("connectLangues.php");
	include("connect.php");


	$annee = date('Y').'-'.date('m').'-'.date('d');

?>

<!doctype html>
<html lang="en">
<noscript>
This page requires Javascript.
Please enable it in your browser.
</noscript>
	<head>
		<meta charset="utf-8"/>
		<title>Facturation</title>
		
		<link rel="icon" href="images/favicon.ico">
		<link rel="shortcut icon" href="images/favicon.ico" />
		
				<!-------------------barre de menu------------------->

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="source/cssmenu/styles.css">
		<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
		
		<script src="script.js"></script>
				
				<!------------------------------------>

		<link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />
		
		<link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
		
		<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
		
		<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
		<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
		
		<!-------------------calendrier------------------->
		
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
		<script type="text/javascript" src="calender/calendrier.js"></script>	
		<link href="css/form-signin.css" rel="stylesheet" type="text/css">

	
		
		<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
		<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
		<script src="myQuery.js"></script>
		<script type="text/javascript">

			function controlFormCustom(theForm){
				var rapport="";
				
				rapport +=controlDateDebut(theForm.customdatedebutPerso);
				rapport +=compareDateDebuFin(theForm.customdatedebutPerso,theForm.customdatefinPerso);
				// rapport +=compareHeures(theForm.heurerdvDebut,theForm.heurerdvFin,theForm.minrdvDebut,theForm.minrdvFin);
				
					if (rapport != "") {
					alert("Check error please :\n" + rapport);
								return false;
					 }
			}

			function controlDateDebut(fld){
				var erreur="";
				//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
				
				if(fld.value.trim()==""){
					erreur="Begining date";
					fld.style.background="cyan";
				}
				return erreur;	
			} 

			function compareDateDebuFin(fld1,fld2){
				var erreur="";
				var dateDebut=fld1.value;
				var dateFin=fld2.value;
				

				if(dateFin != "")
				{
					if(dateDebut>dateFin)
					{
						fld1.style.background='yellow';
						fld2.style.background='yellow';
						
						erreur="\nInvalid Search\n Check the dates input\n";
					}
				}

				return erreur;	
			}
			
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
					alert("Your Browser does not support XMLHTTPRequest object...");
					return null;
				}

				return xhr;
			}


		</script>
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
			$connect=$_SESSION['connect'];
			$status=$_SESSION['status'];

			if($connect==true)
			{
				if($status==1)
				{
		?>

		<div class="navbar navbar-fixed-top">
			
			<div class="navbar-inner">
				
				<div class="container">
					
					<img src="images/logo_large.png" class="brand" />
					
					<div class="nav-collapse">
					
						<ul class="nav pull-right">
							<li class="">
								<form method="post" action="patients1.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}?><?php if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
								
									<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
									
									<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
									
									<?php
									if($langue == 'francais')
									{
									?>
										<a href="patients1.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['fullname'])){ echo '&fullname='.$_GET['fullname'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(30);?></a>
									<?php
									}else{
									?>
										<a href="patients1.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['fullname'])){ echo '&fullname='.$_GET['fullname'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(29);?></a>
									<?php
									}
									?>
										<br/>
									
										<input type="text" name="Pass" id="Pass" placeholder="<?php echo getString(26);?>" style="margin-top:10px;margin-bottom:0;height:20px;"/>
										
										<input type="submit" name="confirmPass" id="confirmPass" class="btn"  value="<?php echo getString(27);?>"/>
									
								
								</form>
							</li>
						</ul>
					</div><!--/.nav-collapse -->
			
				</div> <!-- /container -->
				
			</div> <!-- /navbar-inner -->
			
		</div><br><br><br><br><br>
		<?php
			if(isset($_SESSION['codeCash']) AND !isset($_SESSION['codeR']))
			{
			?>
				<div style="text-align:center;margin-top:20px;">
				
					<a href="patients1_hosp.php?cashHosp=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
						<?php echo 'Hospitalisation';?>
					</a>
				
					<a href="listfacture.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
						<?php echo 'Factures';?>
					</a>
				
					<a href="report.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;"><?php echo getString(94);?></a>
				
					<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
						<?php echo 'Dettes';?>
					</a>
				
				</div>
			<?php
				}
			?>
			<div class="" style="text-align:center;margin-top:20px;">
				<?php
					if(isset($_GET['num']))
					{
						$nomPatient = $connexion->prepare('SELECT * FROM patients_hosp WHERE numero=:num AND id_factureHosp IS NULL');
						$nomPatient->execute(array(
							'num'=>$_GET['num']
						));
						$nomPatientrow = $nomPatient->rowcount();
						$nomPatient->setFetchMode(PDO::FETCH_OBJ);
						if ($nomPatientrow != 0) {
							if ($namePatient=$nomPatient->fetch()) {
								//echo "namePatient = ".$namePatient;
								$id_uHosp=$namePatient->id_uHosp;
								$fullname = $connexion->prepare('SELECT * FROM utilisateurs WHERE id_u=:id_u');
								$fullname->execute(array(
									'id_u'=>$id_uHosp
								));
								$fullname->setFetchMode(PDO::FETCH_OBJ);
								if ($name = $fullname->fetch()) {
								
							
						
				?>
				<h3>Facturer <?php echo $name->full_name;?></h3>
				<p><?php echo $name->full_name;?> a été hospitalisé le <strong> <?php echo $namePatient->dateEntree;?></strong></p>
				<p>Facturer le patient <strong><?php echo $name->full_name;?></strong></p>
				<form action="categoriesbill_fact_hosp.php?inf=<?php echo $_GET['inf'];?>&num=<?php echo $_GET['num'];?>&idhosp=<?php echo $_GET['idhosp'];?>&datehosp=<?php echo $_GET['datehosp'];?>&id_uM=<?php echo '0';?>&idassu=<?php echo $_GET['idassu'];?>&idbill=<?php echo $_GET['idbill'];?>&numroom=<?php echo $_GET['numroom'];?>&numlit=<?php echo $_GET['numlit'];?>&id_consuHosp=<?php echo $_GET['id_consuHosp'];?>&previewprint=ok&facturer=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="POST" class="form-inline">
					<table style="text-align: center; margin-left: 450px;">
						<tr style="text-align: center;" id="custommedicPerso">
							<td><strong>From</strong></td>
							<td>
								<input type="text" name="customdatedebutbillPerso" id="customdatedebutbillPerso" onclick="ds_sh(this);" value="" style="width:150px">
							</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;<strong>To</strong></td>
							<td>
								<input type="text" id="customdatefinbillPerso" name="customdatefinbillPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
							</td>
							<td style="vertical-align:top;">
								<button type="submit"  name="custombillPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Facturer</button>
							</td>
						</tr>
					</table>
				</form>
				<?php
								}
							}
						}
					}
				?>
				
			</div>

		<?php
				}
			}
		?>
	</body>
</html>