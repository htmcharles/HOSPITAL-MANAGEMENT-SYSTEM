<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");

	/* if(isset($_GET['francais']))
	{
		$_SESSION['langue']='francais';
	}else{
		if(isset($_GET['anglais']))
		{
			$_SESSION['langue']='english';
		}else{
			$_SESSION['langue']='english';
		}
	} */
?>
<!DOCTYPE html>
<noscript>
	Cette page requiert du Javascript.
	Veuillez l'activer pour votre navigateur
</noscript>
<html>
<head>
	<meta charset="utf-8"/>
	<title><?php echo "Tacking Bills";?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />

		<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<script src="script.js"></script>
			
			<!------------------------------------>
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"/><!--Header-->

		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>
	<link href="css/form-signin.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php

		$id=$_SESSION['id'];

		$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");
		$comptidM=$sqlM->rowCount();

		// echo $_SESSION[''];

		$connect=$_SESSION['connect'];
		$status=$_SESSION['status'];

		if($connect==true AND $comptidM!=0)
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
									<form method="post" action="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}} if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}if(isset($_GET['audit'])){ echo '&audit='.$_GET['audit'];} if(isset($_GET['cash'])){ echo '&cash='.$_GET['cash'];} if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['lab'])){ echo '&lab='.$_GET['lab'];}if(isset($_GET['rec'])){ echo '&rec='.$_GET['rec'];}?>" onsubmit="return controlFormPassword(this)">
									
									<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
									
									<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
									
									<?php
									if($langue == 'francais')
									{
									?>
										<a href="report.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}if(isset($_GET['audit'])){ echo '&audit='.$_GET['audit'];}if(isset($_GET['cash'])){ echo '&cash='.$_GET['cash'];}if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['lab'])){ echo '&lab='.$_GET['lab'];}if(isset($_GET['rec'])){ echo '&rec='.$_GET['rec'];}?>" class="btn"><?php echo getString(30);?></a>
									<?php
									}else{
									?>
										<a href="report.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['report'])){ echo '&report='.$_GET['report'];}if(isset($_GET['audit'])){ echo '&audit='.$_GET['audit'];} if(isset($_GET['cash'])){ echo '&cash='.$_GET['cash'];} if(isset($_GET['med'])){ echo '&med='.$_GET['med'];}if(isset($_GET['inf'])){ echo '&inf='.$_GET['inf'];}if(isset($_GET['lab'])){ echo '&lab='.$_GET['lab'];}if(isset($_GET['rec'])){ echo '&rec='.$_GET['rec'];}?>" class="btn"><?php echo getString(29);?></a>
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
					if(isset($_SESSION['codeC']))
					{
					?>
						<div style="text-align:center;margin-top:20px;">
							
							<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
								<?php echo 'Factures';?>
							</a>
							<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
								<?php echo 'Dettes';?>
							</a>
						
						</div>
						
					<?php
					}
				?>

				<div class="account-container" style="width:90%; text-align:center;">

					<div id='cssmenu' style="text-align:center" class="menu">

					<?php
					if(isset($_SESSION['codeC']))
					{
					?>
						<ul>
							<?php
							if(isset($_GET['num']) OR isset($_GET['gnlreport']) OR isset($_GET['cash']) OR isset($_GET['med']) OR isset($_GET['gnlmed']) OR isset($_GET['inf']) OR isset($_GET['lab']) OR isset($_GET['rec']) OR isset($_GET['gnlreporthosp']))
							{
							?>
								<li style="width:33.33%;"><a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Utilisateurs"><i class="fa fa-users fa-lg fa-fw"></i> Utilisateurs</a></li>
							
								<li style="width:33.33%;"><a href="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-left:5px;margin-right:5px;" data-title="Select report"><i class="fa fa-file fa-lg fa-fw"></i> Select reports</a></li>
								
								<li style="width:33.33%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
							<?php
							}else{
							?>
							
								<li style="width:50%;"><a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Utilisateurs"><i class="fa fa-users fa-lg fa-fw"></i> Utilisateurs</a></li>
							
								<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
							<?php
							}
							?>
							
						</ul>

						<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">
							
								<div style="display:none;" id="divMenuMsg">

									<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
									
									<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
									
									<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

								</div>

						</ul>
					<?php
					}
					?>
				</div>	

				
				<div id="selectdateGnlBillReport" style="display:inline">
				<h1>Tracking Bills errors</h1>
				<hr>
					<form action="trackingbill.php?tracking=ok" method="post" style="margin:auto;padding:5px;width:90%;">

						<table id="dmacbillgnl" style="margin:auto auto 20px">
							<tr style="display:inline-block; margin-bottom:25px;">
								<td>
									<span style="text-align:center;width:100px;" id="dailygnlbtn" onclick="ShowSelectreportGnl('dailybillGnl')" class="btn">Daily</span>
								</td>
								
								<td>
									<span style="text-align:center;width:100px;" id="monthlygnlbtn" onclick="ShowSelectreportGnl('monthlybillGnl')" class="btn">Monthly</span>
								</td>
								
								<td>
									<span style="text-align:center;width:100px;" id="annualygnlbtn" onclick="ShowSelectreportGnl('annualybillGnl')" class="btn">Annualy</span>
								</td>
								
								<td>
									<span style="text-align:center;width:100px;" id="customgnlbtn" onclick="ShowSelectreportGnl('custombillGnl')" class="btn">Custom</span>
								</td>
							</tr>
							
							<tr style="visibility:visible">
							
								<td id="dailybillGnl" style="display:none">Select Date
									<input type="text" id="dailydatebillGnl" name="dailydatebillGnl" onclick="ds_sh(this);" value="" style="width: 150px;" />									
									<button type="submit"  name="searchdailybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
								</td>
								
								<td id="monthlybillGnl" style="display:none">Select Month
								
									<select name="monthlydatebillGnl" id="monthlydatebillGnl" style="width:100px;height:40px;">
									
										<option value='1' <?php if(date('m')=='01') {echo 'selected="selected"';}?>>January</option>
		                                <option value='2' <?php if(date('m')=='02') {echo 'selected="selected"';}?>>February</option>
		                                <option value='3' <?php if(date('m')=='03') {echo 'selected="selected"';}?>>March</option>
		                                <option value='4' <?php if(date('m')=='04') {echo 'selected="selected"';}?>>April</option>
		                                <option value='5' <?php if(date('m')=='05') {echo 'selected="selected"';}?>>May</option>
		                                <option value='6' <?php if(date('m')=='06') {echo 'selected="selected"';}?>>June</option>
		                                <option value='7' <?php if(date('m')=='07') {echo 'selected="selected"';}?>>July</option>
		                                <option value='8' <?php if(date('m')=='08') {echo 'selected="selected"';}?>>August</option>
		                                <option value='9' <?php if(date('m')=='09') {echo 'selected="selected"';}?>>September</option>
		                                <option value='10' <?php if(date('m')=='10') {echo 'selected="selected"';}?>>October</option>
		                                <option value='11' <?php if(date('m')=='11') {echo 'selected="selected"';}?>>November</option>
		                                <option value='12' <?php if(date('m')=='12') {echo 'selected="selected"';}?>>December</option>
									
									
									</select>
									
									<select name="monthlydatebillGnlYear" id="monthlydatebillGnlYear" style="width:100px;height:40px;">
									<?php 
									for($i=2016;$i<=2030;$i++)
									{
									?>
										<option value='<?php echo $i;?>' <?php if(date('Y')==$i) {echo 'selected="selected"';}?>><?php echo $i;?></option>
									<?php 
									}
									?>
									</select>
									<button type="submit"  name="searchmonthlybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
									
								</td>
								
								<td id="annualybillGnl" style="display:none">Select Year
								
									<select name="annualydatebillGnl" id="annualydatebillGnl" style="width:100px;height:40px;">
									<?php 
									for($i=2016;$i<=2030;$i++)
									{
									?>
										<option value='<?php echo $i;?>' <?php if(date('Y')==$i) {echo 'selected="selected"';}?>><?php echo $i;?></option>
									<?php 
									}
									?>
									</select>
									<button type="submit"  name="searchannualybillGnl" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
								</td>
								
								<td id="custombillGnl" style="display:none">
								
									<table>
										<tr>
											<td>From</td>
											<td>
												<input type="text" id="customdatedebutbillGnl" name="customdatedebutbillGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
											</td>
										
											<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
											<td>
												<input type="text" id="customdatefinbillGnl" name="customdatefinbillGnl" onclick="ds_sh(this);" value="" style="width:150px"/>
											</td>
										
											<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td>
											</td>
										
											<td style="vertical-align:top;">
												<button type="submit"  name="searchcustombillGnl" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						
						</table>

					</form>
					
				</div>
				<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
					<tr>
						<td style="padding:5px;" id="ds_calclass"></td>
					</tr>
				</table>
				<?php
					if (isset($_GET['tracking'])) {
						$stringResult = "";
						$dailydategnl = "";
						$paVisitgnl="gnlGnlBill";
						
						if(isset($_POST['searchdailybillGnl']))
						{
							if(isset($_POST['dailydatebillGnl']))
							{								
								
								$dailydategnl = 'datebill LIKE \''.$_POST['dailydatebillGnl'].'%\' ORDER BY datebill ASC';
								
								$paVisitgnl="dailyGnlBill";
								
								$stringResult="Daily results : ".$_POST['dailydatebillGnl'];
							
							}
						}
						
						if(isset($_POST['searchmonthlybillGnl']))
						{
							if(isset($_POST['monthlydatebillGnl']) AND isset($_POST['monthlydatebillGnlYear']))
							{
								
								if($_POST['monthlydatebillGnl']<10)
								{
									$ukwezi = '0'.$_POST['monthlydatebillGnl'];
								}else{						
									$ukwezi = $_POST['monthlydatebillGnl'];
								}
								
								$umwaka = $_POST['monthlydatebillGnlYear'];
							
								$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
								
								if($daysmonth<10)
								{
									$daysmonth='0'.$daysmonth;
								}
								
								$paVisitgnl="monthly";
								
								$dailydategnl = 'datebill>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (datebill<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR datebill LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\') ORDER BY datebill ASC';
								
								$paVisitgnl="monthlyGnlBill";
								
								$stringResult=date("F",mktime(0,0,0,$_POST['monthlydatebillGnl'],10))."-".$_POST['monthlydatebillGnlYear'];
								
							}

						}
						
						if(isset($_POST['searchannualybillGnl']))
						{
							if(isset($_POST['annualydatebillGnl']))
							{
								$year = $_POST['annualydatebillGnl'];
								
								$dailydategnl = 'datebill>=\''.$year.'-01-01\' AND datebill<=\''.$year.'-12-31\' ORDER BY datebill ASC';
								
								$paVisitgnl="annualyGnlBill";
								
								$stringResult="Annualy results : ".$_POST['annualydatebillGnl'];
						
						
							}
						
						}
						
						if(isset($_POST['searchcustombillGnl']))
						{
							if(isset($_POST['customdatedebutbillGnl']) AND isset($_POST['customdatefinbillGnl']))
							{
								$debut = $_POST['customdatedebutbillGnl'];
								$fin = $_POST['customdatefinbillGnl'];
							
								// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
								
								$dailydategnl = 'datebill>=\''.$debut.'\' AND (datebill<\''.$fin.'\' OR datebill LIKE \''.$fin.'%\') ORDER BY datebill ASC';
								
								$paVisitgnl="customGnlBill";
								
								$stringResult="Customer results : [ ".$_POST['customdatedebutbillGnl']."/".$_POST['customdatefinbillGnl']." ]";
						
						
							}

						}
				?>
				<div id="divGnlBillReport" style="display:inline;">
					
					<table style="width:100%;">
						<tr>
							<td style="text-align:center; width:33.333%;">
												
							</td>
							
							<td style="text-align:center; width:40%;">
								<span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>
						
							</td>
							
							<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
								
							</td>
						</tr>			
					</table>
					<?php
					$selectBills=$connexion->query('SELECT *FROM bills  WHERE '.$dailydategnl.'');	
					//echo 'SELECT *FROM patients_hosp ph WHERE ph.statusPaHosp!=1  '.$dailydategnl.'';
						
					$selectBills->setFetchMode(PDO::FETCH_OBJ);
					$nbreconsugnl = 0;
					$nbreconsomgnl = 0;
					$nbreconsultgnl = 0;
					$nbremedinfgnl = 0;
					$nbremedkinegnl = 0;
					$nbremedlabognl = 0;
					$nbremedocgnl = 0;
					$nbremedorthognl = 0;
					$nbremedpsygnl = 0;
					$nbremedradiognl = 0;
					$nbremedsurgegnl = 0;

					$condition = '(';

					$comptConsult=$selectBills->rowCount();
					if($comptConsult != 0)
					{
						$i = 0;
						while ($lignebills = $selectBills->fetch()) {
							$idBill = $lignebills->id_bill;
							$numero = $lignebills->numero;
							

							$checkconsu = $connexion->prepare('SELECT * FROM consultations c, bills b WHERE c.id_factureConsult=b.id_bill AND c.id_factureConsult=:id_factureConsult AND c.numero!=:numero');
							$checkconsu->execute(array(
								'id_factureConsult'=>$idBill,
								'numero'=>$numero
							));
							$checkconsu->setFetchMode(PDO::FETCH_OBJ);
							$nbreconsu = $checkconsu->rowCount();
							$nbreconsugnl += $nbreconsu;

							$checkconsom = $connexion->prepare('SELECT * FROM med_consom mc, bills b WHERE mc.id_factureMedConsom=b.id_bill AND mc.id_factureMedConsom=:id_factureMedConsom AND mc.numero!=:numero');
							$checkconsom->execute(array(
								'id_factureMedConsom'=>$idBill,
								'numero'=>$numero
							));
							$checkconsom->setFetchMode(PDO::FETCH_OBJ);
							$nbreconsom = $checkconsom->rowCount();
							$nbreconsomgnl += $nbreconsom;

							
							$checkconsult = $connexion->prepare('SELECT * FROM med_consult mc, bills b WHERE mc.id_factureMedConsu=b.id_bill AND mc.id_factureMedConsu=:id_factureMedConsu AND mc.numero!=:numero');
							$checkconsult->execute(array(
								'id_factureMedConsu'=>$idBill,
								'numero'=>$numero
							));
							$checkconsult->setFetchMode(PDO::FETCH_OBJ);
							$nbreconsult = $checkconsult->rowCount();
							$nbreconsultgnl += $nbreconsult;

							
							$checkmdeinf = $connexion->prepare('SELECT * FROM med_inf mi, bills b WHERE mi.id_factureMedInf=b.id_bill AND mi.id_factureMedInf=:id_factureMedInf AND mi.numero!=:numero');
							$checkmdeinf->execute(array(
								'id_factureMedInf'=>$idBill,
								'numero'=>$numero
							));
							$checkmdeinf->setFetchMode(PDO::FETCH_OBJ);
							$nbremedinf = $checkmdeinf->rowCount();
							$nbremedinfgnl += $nbremedinf;

							
							$checkmedkine = $connexion->prepare('SELECT * FROM med_kine mk, bills b WHERE mk.id_factureMedKine=b.id_bill AND mk.id_factureMedKine=:id_factureMedKine AND mk.numero!=:numero');
							$checkmedkine->execute(array(
								'id_factureMedKine'=>$idBill,
								'numero'=>$numero
							));
							$checkmedkine->setFetchMode(PDO::FETCH_OBJ);
							$nbremedkine = $checkmedkine->rowCount();
							$nbremedkinegnl += $nbremedkine;


							$checkmedlabo = $connexion->prepare('SELECT * FROM med_labo ml, bills b WHERE ml.id_factureMedLabo=b.id_bill AND ml.id_factureMedLabo=:id_factureMedLabo AND ml.numero!=:numero');
							$checkmedlabo->execute(array(
								'id_factureMedLabo'=>$idBill,
								'numero'=>$numero
							));
							$checkmedlabo->setFetchMode(PDO::FETCH_OBJ);
							$nbremedlabo = $checkmedlabo->rowCount();
							$nbremedlabognl += $nbremedlabo;


							$checkmedoc = $connexion->prepare('SELECT * FROM med_medoc mc, bills b WHERE mc.id_factureMedMedoc=b.id_bill AND mc.id_factureMedMedoc=:id_factureMedMedoc AND mc.numero!=:numero');
							$checkmedoc->execute(array(
								'id_factureMedMedoc'=>$idBill,
								'numero'=>$numero
							));
							$checkmedoc->setFetchMode(PDO::FETCH_OBJ);
							$nbremedoc = $checkmedoc->rowCount();
							$nbremedocgnl += $nbremedoc;

							
							$checkmedortho = $connexion->prepare('SELECT * FROM med_ortho mc, bills b WHERE mc.id_factureMedOrtho=b.id_bill AND mc.id_factureMedOrtho=:id_factureMedOrtho AND mc.numero!=:numero');
							$checkmedortho->execute(array(
								'id_factureMedOrtho'=>$idBill,
								'numero'=>$numero
							));
							$checkmedortho->setFetchMode(PDO::FETCH_OBJ);
							$nbremedortho = $checkmedortho->rowCount();
							$nbremedorthognl += $nbremedortho;

							
							$checkmdepsy = $connexion->prepare('SELECT * FROM med_psy mi, bills b WHERE mi.id_factureMedPsy=b.id_bill AND mi.id_factureMedPsy=:id_factureMedPsy AND mi.numero!=:numero');
							$checkmdepsy->execute(array(
								'id_factureMedPsy'=>$idBill,
								'numero'=>$numero
							));
							$checkmdepsy->setFetchMode(PDO::FETCH_OBJ);
							$nbremedpsy = $checkmdepsy->rowCount();
							$nbremedpsygnl += $nbremedpsy;

							
							$checkmedradio = $connexion->prepare('SELECT * FROM med_radio mk, bills b WHERE mk.id_factureMedRadio=b.id_bill AND mk.id_factureMedRadio=:id_factureMedRadio AND mk.numero!=:numero');
							$checkmedradio->execute(array(
								'id_factureMedRadio'=>$idBill,
								'numero'=>$numero
							));
							$checkmedradio->setFetchMode(PDO::FETCH_OBJ);
							$nbremedradio = $checkmedradio->rowCount();
							$nbremedradiognl += $nbremedradio;


							$checkmedsurge = $connexion->prepare('SELECT * FROM med_surge mk, bills b WHERE mk.id_factureMedSurge=b.id_bill AND mk.id_factureMedSurge=:id_factureMedSurge AND mk.numero!=:numero');
							$checkmedsurge->execute(array(
								'id_factureMedSurge'=>$idBill,
								'numero'=>$numero
							));
							$checkmedsurge->setFetchMode(PDO::FETCH_OBJ);
							$nbremedsurge = $checkmedsurge->rowCount();
							$nbremedsurgegnl += $nbremedsurge;

							if ($nbreconsu!=0 OR $nbreconsom!=0 OR $nbreconsult!=0 OR $nbremedinf!=0 OR $nbremedkine!=0 OR $nbremedlabo!=0 OR $nbremedoc!=0 OR $nbremedortho!=0 OR $nbremedpsy!=0 OR $nbremedradio!=0 OR $nbremedsurge!=0) {
								if ($i == 0) {
									$condition .= ' id_bill='.$idBill;
								}else{
									$condition .= ' OR id_bill='.$idBill;
								}
								$i++;
							}

							
						}
						$condition .= ')'; 
							if ($nbreconsugnl!=0 OR $nbreconsomgnl!=0 OR $nbreconsultgnl!=0 OR $nbremedinfgnl!=0 OR $nbremedkinegnl!=0 OR $nbremedlabognl!=0 OR $nbremedocgnl!=0 OR $nbremedorthognl!=0 OR $nbremedpsygnl!=0 OR $nbremedradiognl!=0 OR $nbremedsurgegnl!=0) {
							?>
								<a href="trackingdetails.php?tracking=ok&dailydategnl=<?php echo $dailydategnl;?>&stringResult=<?php echo $stringResult;?>&condition=<?php echo $condition;?>">
									
									<button style="width:350px;margin-left: 60px;" type="submit" name="printMedicReportGnl" id="printMedicReportGnl" class="btn-large-inversed">
										<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
									</button>
								
								</a>
								<!-- <table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
									<thead>			
										<tr>
											<th style="width:2%; border-right: 1px solid #bbb">N°</th>
											<th style="width:10%; border-right: 1px solid #bbb">Date of consultation</th>
											<th style="width:15%; border-right: 1px solid #bbb">Full name</th>
											<th style="width:15%; border-right: 1px solid #bbb">Assurance</th>
											<th style="width:20%; border-right: 1px solid #bbb" colspan=2><?php echo getString(113);?></th>
											<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(39);?></th>
											<th style="width:5%; border-right: 1px solid #bbb"><?php echo 'Medical Acts';?></th>
											<th style="width:10%; border-right: 1px solid #bbb"><?php echo getString(98);?></th>
											<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Medicament';?></th>
											<th style="width:10%; border-right: 1px solid #bbb"><?php echo 'Consommables';?></th>
											<th style="width:15%;"><?php echo getString(99);?></th>
											<th style="width:10%;"><?php echo 'Radiologie';?></th>
											<th style="width:10%;">Total Final</th>
											<th style="width:10%;">Total Patients</th>
											<th style="width:10%;">Total Insurance</th>
										</tr> 
									</thead>
									<tbody>
										
									</tbody>
								</table> -->
							<?php
							}else{
							?>
								<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
												
									<thead>
										<tr>
											<th style="width:12%;text-align:center">No Bills errors Found For This search</th>
										</tr> 
									</thead>
										
								</table>
							<?php
							}
				
					}
					?>
				</div>
				<?php
					}
				?>

			</div>

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
	alert("Your Browser does not support XMLHTTPRequest object...");
	return null;
}

return xhr;
}


function ShowList(list)
{
	if( list =='Users')
	{
	    document.getElementById('divMenuUser').style.display='inline';
	    document.getElementById('divMenuMsg').style.display='none';
	    document.getElementById('divListe').style.display='none';
	    document.getElementById('listOn').style.display='inline';
	    document.getElementById('listOff').style.display='none';
	}
	if( list =='Msg')
	{
		document.getElementById('divMenuMsg').style.display='inline';
	}
	
}

function controlFormPassword(theForm){
	var rapport="";
	
	rapport +=controlPass(theForm.Pass);

		if (rapport != "") {
		alert("Please review the following fields:\n" + rapport);
					return false;
		 }
}


function controlPass(fld){
	var erreur="";
	
	if(fld.value=="")
	{
		erreur="Your new password\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;
}
function ShowSelectAcc(fld){
			/*---For Accountants---*/
	
	if(fld=="dailyclearbillPerso")
	{
		document.getElementById('dailyclearbillPerso').style.display='inline-block';
	}else{
		document.getElementById('dailyclearbillPerso').style.display='none';
	}
	
	if(fld=="monthlyclearbillPerso")
	{
		document.getElementById('monthlyclearbillPerso').style.display='inline-block';
	}else{
		document.getElementById('monthlyclearbillPerso').style.display='none';
	}
	
	if(fld=="annualyclearbillPerso")
	{
		document.getElementById('annualyclearbillPerso').style.display='inline-block';
	}else{
		document.getElementById('annualyclearbillPerso').style.display='none';
	}
	
	if(fld=="customclearbillPerso")
	{
		document.getElementById('customclearbillPerso').style.display='inline-block';
	}else{
		document.getElementById('customclearbillPerso').style.display='none';
	}
}

function ShowSelect(fld){
	
	if(fld=="dailymedicPerso")
	{
		document.getElementById('dailymedicPerso').style.display='inline-block';
	}else{
		document.getElementById('dailymedicPerso').style.display='none';
	}
	
	if(fld=="monthlymedicPerso")
	{
		document.getElementById('monthlymedicPerso').style.display='inline-block';
	}else{
		document.getElementById('monthlymedicPerso').style.display='none';
	}
	
	if(fld=="annualymedicPerso")
	{
		document.getElementById('annualymedicPerso').style.display='inline-block';
	}else{
		document.getElementById('annualymedicPerso').style.display='none';
	}
	
	if(fld=="custommedicPerso")
	{
		document.getElementById('custommedicPerso').style.display='inline-block';
	}else{
		document.getElementById('custommedicPerso').style.display='none';
	}

			/*---For Billing---*/
	
	if(fld=="dailybillPerso")
	{
		document.getElementById('dailybillPerso').style.display='inline-block';
	}else{
		document.getElementById('dailybillPerso').style.display='none';
	}
	
	if(fld=="monthlybillPerso")
	{
		document.getElementById('monthlybillPerso').style.display='inline-block';
	}else{
		document.getElementById('monthlybillPerso').style.display='none';
	}
	
	if(fld=="annualybillPerso")
	{
		document.getElementById('annualybillPerso').style.display='inline-block';
	}else{
		document.getElementById('annualybillPerso').style.display='none';
	}
	
	if(fld=="custombillPerso")
	{
		document.getElementById('custombillPerso').style.display='inline-block';
	}else{
		document.getElementById('custombillPerso').style.display='none';
	}

}


function ShowSelectGnl(fld){
		
	/*---------For Gnl Medic report---------------*/
	
	if(fld=="dailymedicGnl")
	{
		document.getElementById('dailymedicGnl').style.display='inline-block';
	}else{
		document.getElementById('dailymedicGnl').style.display='none';
	}
	
	if(fld=="monthlymedicGnl")
	{
		document.getElementById('monthlymedicGnl').style.display='inline-block';
	}else{
		document.getElementById('monthlymedicGnl').style.display='none';
	}
	
	if(fld=="annualymedicGnl")
	{
		document.getElementById('annualymedicGnl').style.display='inline-block';
	}else{
		document.getElementById('annualymedicGnl').style.display='none';
	}
	
	if(fld=="custommedicGnl")
	{
		document.getElementById('custommedicGnl').style.display='inline-block';
	}else{
		document.getElementById('custommedicGnl').style.display='none';
	}
}


function ShowSelectreport(fld){
	
	if(fld=="dailybillPerso")
	{
		document.getElementById('dailybillPerso').style.display='inline-block';
	}else{
		document.getElementById('dailybillPerso').style.display='none';
	}
	
	if(fld=="monthlybillPerso")
	{
		document.getElementById('monthlybillPerso').style.display='inline-block';
	}else{
		document.getElementById('monthlybillPerso').style.display='none';
	}
	
	if(fld=="annualybillPerso")
	{
		document.getElementById('annualybillPerso').style.display='inline-block';
	}else{
		document.getElementById('annualybillPerso').style.display='none';
	}
	
	if(fld=="custombillPerso")
	{
		document.getElementById('custombillPerso').style.display='inline-block';
	}else{
		document.getElementById('custombillPerso').style.display='none';
	}
	
}


function ShowSelectreportGnl(fld){
	
	/*---------For Gnl Bill report---------------*/
	
	
	if(fld=="dailybillGnl")
	{
		document.getElementById('dailybillGnl').style.display='inline-block';
	}else{
		document.getElementById('dailybillGnl').style.display='none';
	}
	
	if(fld=="monthlybillGnl")
	{
		document.getElementById('monthlybillGnl').style.display='inline-block';
	}else{
		document.getElementById('monthlybillGnl').style.display='none';
	}
	
	if(fld=="annualybillGnl")
	{
		document.getElementById('annualybillGnl').style.display='inline-block';
	}else{
		document.getElementById('annualybillGnl').style.display='none';
	}
	
	if(fld=="custombillGnl")
	{
		document.getElementById('custombillGnl').style.display='inline-block';
	}else{
		document.getElementById('custombillGnl').style.display='none';
	}
}


function ShowDivReport(fld){

	if(fld=="divPersoMedicReport")
	{		
		document.getElementById('divPersoBillReport').style.display='none';
		document.getElementById('persobillingstring').style.display='none';
		document.getElementById('individualstring').style.display='none';
		document.getElementById('billingpersopreview').style.display='none';
		document.getElementById('selectdatePersoBillReport').style.display='none';
		document.getElementById('dmacBillReport').style.display='none';
		document.getElementById('dmacMedicReport').style.display='none';
		document.getElementById('dmacmedicalpersopreview').style.display='none';
		document.getElementById('dmacbillpersopreview').style.display='none';
	}
	
	if(fld=="divPersoBillReport")
	{
		document.getElementById('divPersoMedicReport').style.display='none';
		document.getElementById('persomedicalstring').style.display='none';
		document.getElementById('individualstring').style.display='none';
		document.getElementById('medicalpersopreview').style.display='none';
		document.getElementById('selectdatePersoMedicReport').style.display='none';
		document.getElementById('dmacMedicReport').style.display='none';
		document.getElementById('dmacBillReport').style.display='none';
		document.getElementById('dmacmedicalpersopreview').style.display='none';
		document.getElementById('dmacbillpersopreview').style.display='none';
		
	}
	
	if(fld=="divGnlMedicReport")
	{
		document.getElementById('divGnlBillReport').style.display='none';
		document.getElementById('gnlbillstring').style.display='none';
		document.getElementById('gnlmedicalstring').style.display='none';
		document.getElementById('billinggnlpreview').style.display='none';
		document.getElementById('selectdateGnlMedicReport').style.display='inline';
		document.getElementById('dmacMedicReport').style.display='none';
		document.getElementById('dmacBillReport').style.display='none';
		document.getElementById('dmacmedicalgnlpreview').style.display='none';
		document.getElementById('dmacbillgnlpreview').style.display='none';
	}
	
	if(fld=="divGnlBillReport")
	{
		document.getElementById('divGnlMedicReport').style.display='none';
		document.getElementById('gnlmedicalstring').style.display='none';
		document.getElementById('gnlbillstring').style.display='none';
		document.getElementById('medicalgnlpreview').style.display='none';
		document.getElementById('dmacMedicReport').style.display='none';
		document.getElementById('dmacBillReport').style.display='none';
		document.getElementById('dmacmedicalgnlpreview').style.display='none';
		document.getElementById('dmacbillgnlpreview').style.display='none';
	}
	
}

</script>

<?php
	
/*	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
	
}else{header('Location:index.php');}



	if(isset($_POST['confirmPass']))
	{
	
		$pass = $_POST['Pass'];
		$iduti = $_SESSION['id'];
				
		$resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');
						
		$resultats->execute(array(
		'pass'=>$pass,
		'modifierIduti'=>$iduti
		))or die( print_r($connexion->errorInfo()));
			
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}*/
?>

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
		$('#checkprestaServ').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>

	<?php
			}else{
				echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
				echo '<script language="javascript">document.location.href="index.php"</script>';
			}
		}else{
			header('Location:index.php');
		}

		if(isset($_POST['confirmPass']))
		{
		
			$pass = $_POST['Pass'];
			$iduti = $_SESSION['id'];
					
			$resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');
							
			$resultats->execute(array(
			'pass'=>$pass,
			'modifierIduti'=>$iduti
			))or die( print_r($connexion->errorInfo()));
				
			echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
			
		}
	?>
</body>
</html>