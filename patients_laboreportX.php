<?php
session_start();

include("connectLangues.php");
include("connect.php");
include("serialNumber.php");

$annee = date('Y').'-'.date('m').'-'.date('d');

if(isset($_GET['finishbtn']))
{
	
	if($_GET['paVisit']=='dailyPersoMedic')
	{
		createRN('LARD');
		
	}else{
		if($_GET['paVisit']=='monthlyPersoMedic')
		{
			createRN('LARM');
			
		}else{
			if($_GET['paVisit']=='annualyPersoMedic')
			{
				createRN('LARA');
				
			}else{
				if($_GET['paVisit']=='customPersoMedic')
				{
					createRN('LARC');
					
				}else{
					if($_GET['paVisit']=='gnlPersoMedic')
					{
						createRN('LARG');
					}
				}
			}
		}
	}

}
			
			
if(isset($_GET['num']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
	$result->execute(array(
	'operation'=>$_GET['num']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
		$num=$ligne->numero;
		$nom_uti=$ligne->nom_u;
		$prenom_uti=$ligne->prenom_u;
		$fullname=$ligne->nom_u.' '.$ligne->prenom_u;
		$sexe=$ligne->sexe;
		$dateN=$ligne->date_naissance;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$profession=$ligne->profession;
		$site=$_GET['num'];
	}
	$result->closeCursor();

	$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//reçoit l'année de naissance
	$month=$dateN[5].''.$dateN[6].'	';//reçoit le mois de naissance

	$an= date('Y')-$old.'	';//recupere l'âge en année
	$mois= date('m')-$month.'	';//recupere l'âge en mois

	if($mois<0)
	{
		$an= ($an-1).' ans	'.(12+$mois).' mois';
		// echo $an= $an-1;

	}else{

		$an= $an.' ans';
		//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
		// echo $mois= date('m')-$month;
	}


}


if(isset($_GET['cash']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE c.codecashier=:operation AND u.id_u=c.id_u');
	$result->execute(array(
	'operation'=>$_GET['cash']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
		$cash=$ligne->codecashier;
		$nom_uti=$ligne->nom_u;
		$prenom_uti=$ligne->prenom_u;
		$sexe=$ligne->sexe;
		$province=$ligne->province;
		$district=$ligne->district;
		$secteur=$ligne->secteur;
		$site=$_GET['cash'];
	}
	$result->closeCursor();

}

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title>Labo Results Reports</title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
				<!--------Chosen----------->
				
	<link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />	
	
			<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<script src="script.js"></script>
			
			<!------------------------------------>
			
	<link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
	
	<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	
		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>	
	<link href="css/form-signin.css" rel="stylesheet" type="text/css">

				<!--------Pagination----------->
				
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	
	<script src="myQuery.js"></script>
	<script type="text/javascript">

function controlFormCustom(theForm){
	var rapport="";
	
	var custom=document.getElementById('custommedicPerso').style.display;
	
	if(custom == "inline-block")
	{
		rapport +=controlDateDebut(theForm.customdatedebutPerso);
		rapport +=compareDateDebuFin(theForm.customdatedebutPerso,theForm.customdatefinPerso);
		// rapport +=compareHeures(theForm.heurerdvDebut,theForm.heurerdvFin,theForm.minrdvDebut,theForm.minrdvFin);
		
	}
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

$id=$_SESSION['id'];

$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");

$comptidL=$sqlL->rowCount();
$comptidM=$sqlM->rowCount();

// echo $_SESSION[''];

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND ($comptidL!=0 OR $comptidM!=0))
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
					<form method="post" action="report_patientLabo.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}} if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="patients_laboreport.php?<?php if(isset($_GET['laboreport'])){ echo 'laboreport=ok';} ?>&english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="patients_laboreport.php?<?php if(isset($_GET['laboreport'])){ echo 'laboreport=ok';} ?>&francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_GET['num']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients_laboreport.php?laboreport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Reports';?>
		</a>

	</div>
	
<?php
}
?>

<?php
if(isset($_SESSION['codeC']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Factures';?>
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
			if(isset($_GET['num']) OR isset($_GET['gnlreport']) OR isset($_GET['cash']) OR isset($_GET['med']) OR isset($_GET['inf']) OR isset($_GET['lab']) OR isset($_GET['laboreport']) OR isset($_GET['rec']))
			{
			?>
				<li style="width:30%;"><a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Utilisateurs"><i class="fa fa-users fa-lg fa-fw"></i> Utilisateurs</a></li>
			
				<li style="width:30%;"><a href="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-left:5px;margin-right:5px;" data-title="Select report"><i class="fa fa-file fa-lg fa-fw"></i> Select reports</a></li>
				
					<?php
		$lu=0;
        $selectmsg=$connexion->prepare("SELECT * FROM messages WHERE lu=:lu AND receiverId=:receiverId");
        $selectmsg->execute(array("lu"=>$lu,"receiverId"=>$_SESSION['id']));
        $selectmsg->setFetchMode(PDO::FETCH_OBJ);
        $lignecount=$selectmsg->rowCount();
        /*echo $_SESSION['id'];*/
        /*echo $lignecount;*/
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:40%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge flashing"><?php echo $lignecount; ?></i> </a></li>
        <?php }else{?>
        	<li style="width:40%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>
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
					
					<?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
					
					<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

				</div>

		</ul>
	<?php
	}
	?>
	
	<?php
	if(isset($_SESSION['codeL']))
	{
	?>
		<ul>
			<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
			
			
				<?php
		$lu=0;
        $selectmsg=$connexion->prepare("SELECT * FROM messages WHERE lu=:lu AND receiverId=:receiverId");
        $selectmsg->execute(array("lu"=>$lu,"receiverId"=>$_SESSION['id']));
        $selectmsg->setFetchMode(PDO::FETCH_OBJ);
        $lignecount=$selectmsg->rowCount();
        /*echo $_SESSION['id'];*/
        /*echo $lignecount;*/
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge flashing"><?php echo $lignecount; ?></i> </a></li>
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>
			
		</ul>

		<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">
			
			<div style="display:none;" id="divMenuMsg">

					<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
					
					<?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
					
					<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

				</div>

		</ul>
	<?php
	}
	?>	
	</div>
	
	<?php
	
	if(isset($_GET['num']))
	{
	?>
		<table style="margin:auto;">
			<tr>
				<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
					<b><h2>LABORATORY ANALYSIS RESULTS</h2></b>
				</td>
				
			</tr>
		</table>
		
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 10px auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $fullname;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($sexe=="M")
						{
							$sexe = "Male";
						}else{
						
							if($sexe=="F")
							
							$sexe = "Female";
						}
						
						echo $sexe;
						?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;">Age : </span><?php echo $an;?>
					</td>
				</tr>
				
				<tr>
					<td></td>
				</tr>
			</table>
		
		<div id="selectdatePersoMedicReport">
		
			<form action="patients_laboreport.php?num=<?php echo $_GET['num'];?>&idlabo=<?php echo $_SESSION['id'];?>&report=ok&dmac=ok&selectPersoMedic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;" onsubmit="return controlFormCustom(this)" >
			
				<table id="dmac" style="margin:auto auto 20px">
					<tr style="display:inline-block; margin-bottom:25px;">
						<td>
							<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
						</td>
						
						<td>
							<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
						</td>
					</tr>
					
					<tr id="dmacligne" style="visibility:visible">
					
						<td id="dailymedicPerso" style="display:none;">Select date
							<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value="<?php echo $annee;?>"/>
						
							<button type="submit" name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
						</td>
						
						<td id="monthlymedicPerso" style="display:none">Select Month
						
							<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
							
								<option value='1'>January</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							
							</select>
							
							<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							
							<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							
						</td>
						
						<td id="annualymedicPerso" style="display:none">Select Year
						
							<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
							<?php 
							for($i=2016;$i<=2030;$i++)
							{
							?>
								<option value='<?php echo $i;?>'><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
						
							<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						</td>
						
						<td id="custommedicPerso" style="display:none">
						
							<table>
								<tr>
									<td>From</td>
									<td>
										<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
									<td>
										<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
									</td>
								
									<td style="vertical-align:top;">
										<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
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
		
		if(isset($_GET['dmac']) OR isset($_GET['selectPersoMedic']))
		{
			$dailydateperso = " AND ml.dateresultats != '0000-00-00'";
			$paVisit="gnlPersoMedic";
			
			if(isset($_POST['searchdailyPerso']))
			{
				if(isset($_POST['dailydatePerso']))
				{
					$dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\'';
					
					$paVisit="dailyPersoMedic";
					
					$stringResult="Daily results : ".$_POST['dailydatePerso'];
				
				}

			}
			
			if(isset($_POST['searchmonthlyPerso']))
			{
				if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
				{
					$ukwezi = $_POST['monthlydatePerso'];
					$umwaka = $_POST['monthlydatePersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\'';
					
					$paVisit="monthlyPersoMedic";
					
					$stringResult="Monthly results : ".$_POST['monthlydatePerso']."-".$_POST['monthlydatePersoYear'];
					
				}

			}
			
			if(isset($_POST['searchannualyPerso']))
			{
				if(isset($_POST['annualydatePerso']))
				{
					$year = $_POST['annualydatePerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\'';
					
					$paVisit="annualyPersoMedic";
					
					$stringResult="Annualy results : ".$_POST['annualydatePerso'];
			
				}
			
			}
			
			if(isset($_POST['searchcustomPerso']))
			{
				if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
				{
					$debut = $_POST['customdatedebutPerso'];
					$fin = $_POST['customdatefinPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\'';
					$paVisit="customPersoMedic";
					
					$stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
			
				}
			}
			
				// echo $dailydateperso;
				// echo $ukwezi.' et '.$year;
				// echo $year;

		?>
		
			<div id="dmacMedicReport" style="display:inline">
			
			<table style="width:100%;">
				<tr>
					<td style="text-align:center; width:33.333%;">
										
					</td>
					
					<td style="text-align:center; width:33.333%;">
						<span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>
				
					</td>
					
					<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
						
					</td>
				</tr>			
			</table>
			
			<?php
			
			$resultLaboPa=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.numero=:num AND ml.examenfait=1 '.$dailydateperso.' ORDER BY ml.dateconsu DESC');		
			$resultLaboPa->execute(array(
			'num'=>$num
			));
			
			$resultLaboPa->setFetchMode(PDO::FETCH_OBJ);

			$comptLaboPa=$resultLaboPa->rowCount();
		

			if($comptLaboPa != 0)
			{
			?>
			
			<table style="width:100%;">
				<tr>
					<td style="text-align:center; width:33.333%;">
								
						<a href="dmacreport_patientLabo.php?num=<?php echo $_GET['num'];?>&dailydateperso=<?php echo $dailydateperso;?>&paVisit=<?php echo $paVisit;?>&divPersoMedicReport=ok" style="text-align:center" id="dmacmedicalpersopreview">
							
							<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
								<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
							</button>
						
						</a>
				
						<input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>
				
					</td>
					
					<td style="text-align:center; width:33.333%;">
						
					</td>
					
					<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
						<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
					</td>
				</tr>			
			</table>
			
			<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;"> 
				<thead> 
					<tr>
						<th style="text-align:center;">N°</th>
						<th style="text-align:center;"><?php echo 'Date Résultats';?></th>
						<th style="text-align:center;"><?php echo getString(99);?></th>
						<th style="text-align:center;"><?php echo 'Résultats';?></th>
						<th style="text-align:center;"><?php echo 'Valeur (min-max)';?></th>
						<th style="text-align:center;"><?php echo 'Lab Files';?></th>
						<th style="text-align:center;"><?php echo getString(97);?></th>
						<th style="text-align:center;"><?php echo getString(19) ?></th>
						<th style="text-align:center;" colspan=2><?php echo 'Laboratory technician';?></th>
					</tr> 
				</thead> 

				<tbody>
				<?php
				// $date='0000-00-00';
				$compteur=1;
				
					while($ligneLaboPa=$resultLaboPa->fetch())
					{
						$idassuLab=$ligneLaboPa->id_assuLab;
									
						$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
						$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
								
						$assuCount = $comptAssuConsu->rowCount();
						
						for($i=1;$i<=$assuCount;$i++)
						{
							
							$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
							$getAssuConsu->execute(array(
							'idassu'=>$idassuLab
							));
							
							$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

							if($ligneNomAssu=$getAssuConsu->fetch())
							{
								$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
							}
						}
				
						
						if($ligneLaboPa->moreresultats!=0)
						{
				?>						
						<tr>
						
							<td style="text-align:center;"><?php echo $compteur;?></td>		
							
							<td style="text-align:center;color:blue;">
							<?php
							if($ligneLaboPa->dateresultats != '0000-00-00')
							{
								echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
							}
							?>
							</td>
							
							<td style="text-align:center;">
							
								<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneLaboPa->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;											
										echo $lignePresta->namepresta;
									
									}else{
									
										$presta=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
									$mesure=' (<span style="font-size:80%; font-weight:normal;padding:5px;">'.$lignePresta->mesure.'</span>)';
								}else{
									$presta=$ligneLaboPa->autreExamen;
									$mesure='';
								}
									echo $presta;
								?>
							
							</td>
							
							<td colspan=3>
							</td>						
										
							<td style="text-align:center;"><?php echo date('d-M-Y',strtotime($ligneLaboPa->dateconsu));?></td>

							<td style="text-align:center;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneLaboPa->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
								echo $ligneMed->full_name;
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
								
							<td style="text-align:center;" colspan=2>
							<?php
							$idLabo=$ligneLaboPa->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								echo $ligneLaboId->full_name;		
							}
							$resultLaboId->closeCursor();
							?>
							</td>
						</tr>
						
							<?php
							if($ligneLaboPa->moreresultats==1)
							{
								$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
								$resultMoreMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneLaboPa->id_medlabo
								));
								
								$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
								
								while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
								{
								?>									
								<tr>
									<td colspan=2></td>
									
									<td style="text-align:center;">
									<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											}else{
											
												$presta=$lignePresta->nompresta;			
											}
											
											if($lignePresta->mesure!='')
											{
												$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
											}else{
												$mesure='';
											}
										}else{
											$presta=$ligneMoreMedLabo->autreExamen;
											
											$mesure='';
										}
										echo $presta;
									?>
									</td>
									
									<td style="text-align:center;">
									<?php echo $ligneMoreMedLabo->autreresultats.''.$mesure;?>
									</td>
									
									<td>
										<?php							
											$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE nomexam=:nomexam ORDER BY valeur');
											$resultValeur->execute(array(
											'nomexam'=>$presta
											));
											
											$resultValeur->setFetchMode(PDO::FETCH_OBJ);

											$comptValeur=$resultValeur->rowCount();
											
											$resultValeur->setFetchMode(PDO::FETCH_OBJ);

											$comptValeur=$resultValeur->rowCount();
											
											if($comptValeur!=0)
											{
												$v=0;
											
												while($ligneValeur=$resultValeur->fetch())
												{
												?>
														
														<table class="printPreview tablesorter3" cellspacing="0" style="width:100%;">
															<tr>
																<td style="text-align:center;">
																<?php 
																/* if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL)
																{ */
																?>
																	<span type="text" id="valeur<?php echo $v;?>" name="valeur[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL){ echo $ligneValeur->valeur;}else{ echo '---';}?></span>
																<?php 
																// }
																
																if($ligneValeur->min_valeur !="" OR $ligneValeur->max_valeur !="")
																{
																?>
																( 
																<span type="text" id="min<?php echo $v;?>" name="min[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->min_valeur !=""){ echo $ligneValeur->min_valeur;}?></span> 
																- 
																<span type="text" id="max<?php echo $v;?>" name="max[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->max_valeur !=""){ echo $ligneValeur->max_valeur;}?></span> )
																<?php
																}
																?>
																</td>						
															</tr>						
														</table>						
											<?php
													$v++;
												}
											}
										?>
								</td>
												
									<td style="text-align:center;">
									<?php
									if($ligneMoreMedLabo->resultats!="")
									{
									?>
										<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
									<?php
									}
									?>
									</td>
							
									<td colspan=4></td>
									
								</tr>
								<?php
								}
							}
							
							if($ligneLaboPa->moreresultats==2)
							{
								$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');		
								$resultSpermoMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneLaboPa->id_medlabo
								));
								
								$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
								
								while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
								{
								?>									
								<tr style="background-color:#eee">
									<td colspan=5 style='text-align:center'>EXAMEN MACROSCOPIQUES</td>
									<td style='border-left:1px solid #aaa;text-align:center' colspan=6>EXAMEN MICROSCOPIQUES</td>
								</tr>
								
								<tr>
									<td style='text-align:center'>Volume</td>
									<td style='text-align:center'>Densité</td>
									<td style='text-align:center'>Viscosité</td>
									<td style='text-align:center'>PH</td>
									<td style='text-align:center'>Aspect</td>
									
									<td style='border-left:1px solid #aaa;text-align:center'>Examen direct</td>
									<td style='text-align:center'>Mobilité après</td>
									<td style='text-align:center;font-weight:normal;text-align:center'>Numération</td>
									<td style='text-align:center'>V.N</td>
									<td style='text-align:center'>Spermocytogramme</td>
									<td style='text-align:center'>Autres</td>
								
								</tr>
								
								<tr>							
									<td style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->volume;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->densite;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->viscosite;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->ph;?>
									</td>						
									<td style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->aspect;?>
									</td>
									
									<td style='border-left:1px solid #aaa;text-align:center'>
									<?php echo $ligneSpermoMedLabo->examdirect;?>
									</td>
									
									<td>							
										<table>
											<tr>
												<td>0h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>1h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>2h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>3h après emission</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>4h après emission</td>
											</tr>
											
											<tr>
												<td style='text-align:center'>
												<?php echo $ligneSpermoMedLabo->zeroheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermoMedLabo->uneheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermoMedLabo->deuxheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermoMedLabo->troisheureafter;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermoMedLabo->quatreheureafter;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td style='text-align:center;font-weight:normal;'>
									<?php echo $ligneSpermoMedLabo->numeration;?>
									</td>
									
									<td style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->vn;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td>Forme typique</td>
												<td style='border-left:1px solid #aaa;padding:5px;'>Forme atypique</td>
											</tr>
											
											<tr>
												<td style='text-align:center'>
												<?php echo $ligneSpermoMedLabo->formtypik;?>
												</td>
												<td style='text-align:center'>
												<?php echo $ligneSpermoMedLabo->formatypik;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->autre;?>
									</td>
									
								</tr>
								
								<tr style="background-color:#eee">	
									<td colspan=11 style='text-align:center'>CONCLUSION</td>
									
								</tr>
								<tr>	
									<td colspan=11 style='text-align:center'>
									<?php echo $ligneSpermoMedLabo->conclusion;?>
									</td>
									
								</tr>
								<?php
								}
							}
								
						}else{
						?>
						<tr>
							
							<td style="text-align:center;"><?php echo $compteur;?></td>		
							
							<td style="text-align:center;color:blue;">
							<?php
							if($ligneLaboPa->dateresultats != '0000-00-00')
							{
								echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
							}
							?>
							</td>
							
							<td style="text-align:center;">
								<?php 
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneLaboPa->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
									}else{							
										$presta=$lignePresta->nompresta;
									}
									
									if($lignePresta->mesure!='')
									{
										$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
									}else{
										$mesure='';
									}
								}else{
									$presta=$ligneLaboPa->autreExamen;
									$mesure='';
								}
								echo $presta;
								?>
							</td>
														
							<td style="text-align:center;"><?php echo $ligneLaboPa->autreresultats.''.$mesure;?></td>
							
							<td>
								<?php							
									$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE nomexam=:nomexam ORDER BY valeur');
									$resultValeur->execute(array(
									'nomexam'=>$presta
									));
									
									$resultValeur->setFetchMode(PDO::FETCH_OBJ);

									$comptValeur=$resultValeur->rowCount();
									
									$resultValeur->setFetchMode(PDO::FETCH_OBJ);

									$comptValeur=$resultValeur->rowCount();
									
									if($comptValeur!=0)
									{
										$v=0;
									
										while($ligneValeur=$resultValeur->fetch())
										{
										?>
												
												<table class="printPreview tablesorter3" cellspacing="0" style="width:100%;">
													<tr>
														<td style="text-align:center;">
														<?php 
														/* if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL)
														{ */
														?>
															<span type="text" id="valeur<?php echo $v;?>" name="valeur[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL){ echo $ligneValeur->valeur;}else{ echo '---';}?></span>
														<?php 
														// }
														
														if($ligneValeur->min_valeur !="" OR $ligneValeur->max_valeur !="")
														{
														?>
														( 
														<span type="text" id="min<?php echo $v;?>" name="min[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->min_valeur !=""){ echo $ligneValeur->min_valeur;}?></span> 
														- 
														<span type="text" id="max<?php echo $v;?>" name="max[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->max_valeur !=""){ echo $ligneValeur->max_valeur;}?></span> )
														<?php
														}
														?>
														</td>						
													</tr>						
												</table>						
									<?php
											$v++;
										}
									}
								?>
								</td>
										
							<td style="text-align:center;">
							<?php
							if($ligneLaboPa->resultats!="")
							{
							?>
								<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
							<?php
							}
							?>
							</td>
								
							<td style="text-align:center;font-weight:normal;"><?php echo date('d-M-Y',strtotime($ligneLaboPa->dateconsu));?></td>

							<td style="text-align:center;">
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
							$resultatsMed->execute(array(
							'idMed'=>$ligneLaboPa->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
							?>	
								<?php echo $ligneMed->full_name;?>
														
							<?php
							}
							$resultatsMed->closeCursor();
							?>						
							</td>
								
							<td style="text-align:center;">
							<?php
							$idLabo=$ligneLaboPa->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
								echo $ligneLaboId->full_name;
		
							}
							
							?>
							</td>
						</tr>
					<?php
						}
						$compteur++;
					}
					?>		
				</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
							
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Laboratory Report for this search</th>
						</tr> 
					</thead> 
				</table> 
				
			<?php
			}
			?>
			</div>
	<?php
		}
	}
	?>


	<?php
			if (isset($_GET['chooseReports'])) {
	?>
<!-- 			<h3>Laboratory Report</h3>
 -->		  <table cellpadding=3 style="margin:auto auto 10px auto; padding: 10px; width:95%;">
                <tr>
                    <td style="font-size:18px; text-align:center; width:33.333%;">
                        <a href="patients_laboreport.php?peek=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['reportclinic'])){ echo 'display:none;';}else{ echo 'display:inline;';}?>">
                            <button id="gnlMedicReport" class="btn-large">General Report For Exams</button>
                        </a>

                        <a href="patients_laboreport.php?chooseReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['reporthospCash'])){ echo 'display:none;';}else{ echo 'display:inline;';}?>">
                            <button id="gnlBillReport" class="btn-large-inversed">General Report According to selected Exam</button>
                        </a>

                    </td>
                </tr>
            </table>

	<?php
			}
	?>	


	<?php
			if (isset($_GET['chooseReport'])) {
	?>
<!-- 			<h3>Laboratory Report</h3>
 -->		  <table cellpadding=3 style="margin:auto auto 10px auto; padding: 10px; width:95%;">
                <tr>
                    <td style="font-size:18px; text-align:center; width:33.333%;">
                        <a href="patients_laboreport.php?chooseReport=ok&coordi=<?php echo $_SESSION['id'];?>&laboreport=ok&reportclinic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['reportclinic'])){ echo 'display:none;';}else{ echo 'display:inline;';}?>">
                            <button id="gnlMedicReport" class="btn-large">Clinic Report</button>
                        </a>

                        <a href="patients_laboreport.php?chooseReport=ok&coordi=<?php echo $_SESSION['id'];?>&laboreportHosp=ok&reporthospCash=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['reporthospCash'])){ echo 'display:none;';}else{ echo 'display:inline;';}?>">
                            <button id="gnlBillReport" class="btn-large-inversed">Hospital Report</button>
                        </a>

                    </td>
                </tr>
            </table>

	<?php
			}
	?>	


	<?php
			if (isset($_GET['peek'])) {
	?>
<!-- 			<h3>Laboratory Report</h3>
 -->		  <table cellpadding=3 style="margin:auto auto 10px auto; padding: 10px; width:95%;">
                <tr>
                    <td style="font-size:18px; text-align:center; width:33.333%;">
                        <a href="patients_laboreport.php?peek=ok&coordi=<?php echo $_SESSION['id'];?>&laboreportpeeked=ok&reportclinic=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['reportclinic'])){ echo 'display:none;';}else{ echo 'display:inline;';}?>">
                            <button id="gnlMedicReport" class="btn-large">Clinic Report</button>
                        </a>

                        <a href="patients_laboreport.php?peek=ok&coordi=<?php echo $_SESSION['id'];?>&laboreportHosppeeked=ok&reporthospCash=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center;<?php if(isset($_GET['reporthospCash'])){ echo 'display:none;';}else{ echo 'display:inline;';}?>">
                            <button id="gnlBillReport" class="btn-large-inversed">Hospital Report</button>
                        </a>

                    </td>
                </tr>
            </table>

	<?php
			}
	?>
	
	
	<?php

	if(isset($_GET['laboreport']))
	{
		if (!isset($_SESSION['codeC'])) {
	?>
			<table style="margin:auto;">
                <tr>
                    <td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
                        <b><h2><?php if(isset($_GET['reportclinic'])){ echo 'Labo Clinic Report';} if(isset($_GET['reporthospCash'])){ echo 'Labo Hospital Report';}?></h2></b>
                    </td>
                </tr>
            </table>
			
			<div id="selectdatePersoMedicReport">
			
				<form action="patients_laboreport.php?report=ok&dmac=ok&selectGnlLaboReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&laboreport=ok" method="post" style="margin:auto;padding:5px;width:90%;" onsubmit="return controlFormCustom(this)" >
				
					<table id="dmac" style="margin:auto auto 20px">
						<tr style="display:inline-block; margin-bottom:25px;">
							<td>
								<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
							</td>
						</tr>
						
						<tr id="dmacligne" style="visibility:visible">
						
							<td id="dailymedicPerso" style="display:none;">Select Exam/Date
								
								<select style='margin:auto' class='chosen-select' name='dailyIdexaPerso' id='dailyIdexaPerso' onchange="OtherDailyExa('dailyExa')">
									<?php
								
									$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
									
									$req->setFetchMode(PDO::FETCH_OBJ);

									$comptReq=$req->rowCount();
													
									while($ligneReq=$req->fetch())
									{
									?>	
										<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
									<?php
									}
									?>
										<option value='0'><?php echo 'Autre Examen...';?></option>
								</select>
								
								<input type="text" id="dailyexaPerso" name="dailyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value="<?php echo $annee;?>"/>
							
								<button type="submit" name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
							</td>
							
							<td id="monthlymedicPerso" style="display:none">Select Exam/Month
							
								<select style='margin:auto' class='chosen-select' name='monthlyIdexaPerso' id='monthlyIdexaPerso' onchange="OtherDailyExa('monthlyExa')">
									<?php
								
									$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
									
									$req->setFetchMode(PDO::FETCH_OBJ);

									$comptReq=$req->rowCount();
													
									while($ligneReq=$req->fetch())
									{
									?>	
										<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
									<?php
									}
									?>
										<option value='0'><?php echo 'Autre Examen...';?></option>
								</select>
								
								<input type="text" id="monthlyexaPerso" name="monthlyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
								
									<option value='1'>January</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
								
								</select>
								
								<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
								
								<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
								
							</td>
							
							<td id="annualymedicPerso" style="display:none">Select Exam/Year
							
								<select style='margin:auto' class='chosen-select' name='annualyIdexaPerso' id='annualyIdexaPerso' onchange="OtherDailyExa('annualyExa')">
									<?php
								
									$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
									
									$req->setFetchMode(PDO::FETCH_OBJ);

									$comptReq=$req->rowCount();
													
									while($ligneReq=$req->fetch())
									{
									?>	
										<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
									<?php
									}
									?>
										<option value='0'><?php echo 'Autre Examen...';?></option>
								</select>
								
								<input type="text" id="annualyexaPerso" name="annualyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
							
								<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							</td>
							
							<td id="custommedicPerso" style="display:none">
							
								<table>
									<tr>
										<td>Select Exam/...</td>
										<td style="width:250px" >
											
											<select style='margin:auto' class='chosen-select' name='customIdexaPerso' id='customIdexaPerso' onchange="OtherDailyExa('customExa')">
												<?php
											
												$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
												
												$req->setFetchMode(PDO::FETCH_OBJ);

												$comptReq=$req->rowCount();
																
												while($ligneReq=$req->fetch())
												{
												?>	
													<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
												<?php
												}
												?>
													<option value='0'><?php echo 'Autre Examen...';?></option>
											</select>
											
											<input type="text" id="customexaPerso" name="customexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
										
										</td>
										
										<td>From</td>
										<td>
											<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
										<td>
											<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td style="vertical-align:top;">
											<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>


				</form>
				
			</div>
			
			
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; cursor:pointer;" align="center">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
			</table>

		
			<?php
			
			if(isset($_GET['dmac']) OR isset($_GET['selectGnlLaboReport']))
			{
				$dailydateperso = " AND ml.dateresultats != '0000-00-00'";
				$paVisit="gnlPersoMedic";
				
				if(isset($_POST['searchdailyPerso']))
				{
					if(isset($_POST['dailydatePerso']))
					{
						
						if($_POST['dailyIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['dailyIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['dailyIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
									$prestaExa = "";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;
									$prestaExa = $ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
									$prestaExa = "";
								}
								
								$dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
							}
							
						}else{
							$nomExa=$_POST['dailyexaPerso'];
							$prestaExa=$_POST['dailyexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						}
						
						// $dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="dailyPersoMedic";
						
						$stringResult="Daily results : ".$_POST['dailydatePerso']." ( ".$prestaExa." )";
					
					}

				}
				
				if(isset($_POST['searchmonthlyPerso']))
				{
					if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
					{
						
						$ukwezi = $_POST['monthlydatePerso'];
						$umwaka = $_POST['monthlydatePersoYear'];
					
						$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						if($_POST['monthlyIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['monthlyIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['monthlyIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{						
								$prestaExa = "";
								
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;	
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;	
									$prestaExa=$ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
								}
															
								$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
						
							}
							
						}else{
							$nomExa=$_POST['monthlyexaPerso'];
							$prestaExa=$_POST['monthlyexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						}
						
						// $dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="monthlyPersoMedic";
						
						$stringResult="Monthly results : ".$_POST['monthlydatePerso']."-".$_POST['monthlydatePersoYear']." ( ".$prestaExa." )";
						
					}

				}
				
				if(isset($_POST['searchannualyPerso']))
				{
					if(isset($_POST['annualydatePerso']))
					{
						$year = $_POST['annualydatePerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
						
						if($_POST['annualyIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['annualyIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['annualyIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{							
								$prestaExa = "";
								
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;	
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;	
									$prestaExa=$ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
								}
								
								$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
						
							}
							
						}else{
							$nomExa=$_POST['annualyexaPerso'];
							$prestaExa=$_POST['annualyexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						}
						
						// $dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="annualyPersoMedic";
						
						$stringResult="Annualy results : ".$_POST['annualydatePerso']." ( ".$prestaExa." )";
				
					}
				
				}
				
				if(isset($_POST['searchcustomPerso']))
				{
					if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
					{
						$debut = $_POST['customdatedebutPerso'];
						$fin = $_POST['customdatefinPerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						if($_POST['customIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['customIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['customIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{						
								$prestaExa = "";
								
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;	
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;	
									$prestaExa=$ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
								}
								
								$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
							
							}
							
						}else{
							$nomExa=$_POST['customexaPerso'];
							$prestaExa=$_POST['customexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
							
						}
						
						// $dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="customPersoMedic";
						
						$stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ] ( ".$prestaExa." )";
				
					}
				}
				
					// echo $dailydateperso;
					// echo $ukwezi.' et '.$year;
					// echo $year;

			?>
			
				<div id="dmacMedicReport" style="display:inline;">
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:30%;">
											
						</td>
						
						<td style="text-align:center; width:40%;">
							<span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>
					
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:30%;">
							
						</td>
					</tr>			
				</table>
				
				<?php
				
				$resultLaboPa=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uL=:id_uL AND  ml.examenfait=1 '.$dailydateperso.' ORDER BY ml.dateresultats');		
				 $resultLaboPa->execute(array(
				'id_uL'=>$_SESSION['id']
				));
				
				$resultLaboPa->setFetchMode(PDO::FETCH_OBJ);
				
				// echo 'SELECT *FROM med_labo ml WHERE ml.examenfait=1 '.$dailydateperso.'<br/>';
				
				$comptLaboPa=$resultLaboPa->rowCount();
			
				// echo $dailydateperso;
				
				if($comptLaboPa != 0)
				{
					// echo $comptLaboPa;
				?>
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:33.333%;">
									
							<a href="dmacreport_patientLabo.php?dailydateperso=<?php echo $dailydateperso;?>&paVisit=<?php echo $paVisit;?>&divPersoMedicReport=ok&laboreport=ok" style="text-align:center" id="dmacmedicalpersopreview">
								
								<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
									<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
								</button>
							
							</a>
					
							<input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>
					
						</td>
						
						<td style="text-align:center; width:33.333%;">
							
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
							<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
						</td>
					</tr>			
				</table>
				
				<div style="overflow:auto;height:500px;width:auto;">
				
					<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
						<thead> 
							<tr>
								<th style="text-align:center;">N°</th>
								<th style="text-align:center;"><?php echo 'Full name';?></th>
								<th style="text-align:center;"><?php echo 'Date Résultats';?></th>
								<th style="text-align:center;"><?php echo getString(99);?></th>
								<th style="text-align:center;"><?php echo 'Résultats';?></th>
								<th style="text-align:center;"><?php echo 'Min';?></th>
								<th style="text-align:center;"><?php echo 'Max'; ?></th>
								<th style="text-align:center;"><?php echo 'Lab Files';?></th>
								<th style="text-align:center;"><?php echo getString(97);?></th>
								<th style="text-align:center;"><?php echo getString(19) ?></th>
								<th style="text-align:center;" colspan=2><?php echo 'Laboratory technician';?></th>
							</tr> 
						</thead> 

						<tbody>
						<?php
						// $date='0000-00-00';
						$compteur=1;
						$fullnamePaCheck="";
						
							while($ligneLaboPa=$resultLaboPa->fetch())
							{
								$idassuLab=$ligneLaboPa->id_assuLab;
											
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuLab
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
									}
								}
						
								
								if($ligneLaboPa->moreresultats!=0)
								{
						?>						
								<tr>
								
									<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td style="text-align:center;">
									
										<?php 
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneLaboPa->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											}else{
											
												$presta=$lignePresta->nompresta;
											}
											$mesure=' (<span style="font-size:80%; font-weight:normal;padding:5px;">'.$lignePresta->mesure.'</span>)';
										}else{
											$presta=$ligneLaboPa->autreExamen;
											$mesure='';
										}
											echo $presta;
										?>
									
									</td>
									
									<td colspan=4>
									</td>						
												
									<td style="text-align:center;"><?php echo date('d-M-Y',strtotime($ligneLaboPa->dateconsu));?></td>

									<td style="text-align:center;">
									<?php
									$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
									$resultatsMed->execute(array(
									'idMed'=>$ligneLaboPa->id_uM
									));

									$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
										
									if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
									{
										echo $ligneMed->full_name;
									}
									$resultatsMed->closeCursor();
									?>						
									</td>
										
									<td style="text-align:center;font-weight:normal;" colspan=2>
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										echo $ligneLaboId->full_name;		
									}
									$resultLaboId->closeCursor();
									?>
									</td>
								</tr>
								
									<?php
									if($ligneLaboPa->moreresultats==1)
									{
										$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
										$resultMoreMedLabo->execute(array(
										'idmedLab'=>$ligneLaboPa->id_medlabo
										));
										
										$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

										$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
										
										while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
										{
										?>									
										<tr>
											<td colspan=3></td>
											
											<td style="text-align:center;">
											<?php
												$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
												'prestaId'=>$ligneMoreMedLabo->id_prestationExa
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

												$comptPresta=$resultPresta->rowCount();
												
												if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
												{
													if($lignePresta->namepresta!='')
													{
														$presta=$lignePresta->namepresta;
													}else{
													
														$presta=$lignePresta->nompresta;			
													}
													
													if($lignePresta->mesure!='')
													{
														$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
													}else{
														$mesure='';
													}
												}else{
													$presta=$ligneMoreMedLabo->autreExamen;
													
													$mesure='';
												}
												echo $presta;
											?>
											</td>
											
											<td style="text-align:center;">
											<?php echo $ligneMoreMedLabo->autreresultats.''.$mesure;?>
											</td>
											
											<td style="text-align:center;">
											<?php echo $ligneMoreMedLabo->minresultats;?>
											</td>
											
											<td style="text-align:center;">
											<?php echo $ligneMoreMedLabo->maxresultats;?>
											</td>
														
											<td style="text-align:center;">
											<?php
											if($ligneMoreMedLabo->resultats!="")
											{
											?>
												<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
											<?php
											}
											?>
											</td>
									
											<td colspan=4></td>
											
										</tr>
										<?php
										}
									}
									
									if($ligneLaboPa->moreresultats==2)
									{
										$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');		
										$resultSpermoMedLabo->execute(array(
										'num'=>$_GET['num'],
										'idmedLab'=>$ligneLaboPa->id_medlabo
										));
										
										$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

										$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
										
										while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
										{
										?>									
										<tr style="background-color:#eee">
											<td colspan=5 style='text-align:center'>EXAMEN MACROSCOPIQUES</td>
											<td style='border-left:1px solid #aaa;text-align:center' colspan=6>EXAMEN MICROSCOPIQUES</td>
										</tr>
										
										<tr>
											<td style='text-align:center'>Volume</td>
											<td style='text-align:center'>Densité</td>
											<td style='text-align:center'>Viscosité</td>
											<td style='text-align:center'>PH</td>
											<td style='text-align:center'>Aspect</td>
											
											<td style='border-left:1px solid #aaa;text-align:center'>Examen direct</td>
											<td style='text-align:center'>Mobilité après</td>
											<td style='text-align:center;font-weight:normal;text-align:center'>Numération</td>
											<td style='text-align:center'>V.N</td>
											<td style='text-align:center'>Spermocytogramme</td>
											<td style='text-align:center'>Autres</td>
										
										</tr>
										
										<tr>							
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->volume;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->densite;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->viscosite;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->ph;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->aspect;?>
											</td>
											
											<td style='border-left:1px solid #aaa;text-align:center'>
											<?php echo $ligneSpermoMedLabo->examdirect;?>
											</td>
											
											<td>							
												<table>
													<tr>
														<td>0h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>1h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>2h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>3h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>4h après emission</td>
													</tr>
													
													<tr>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->zeroheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->uneheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->deuxheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->troisheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->quatreheureafter;?>
														</td>
													</tr>
												</table>
											</td>
											
											<td style='text-align:center;font-weight:normal;'>
											<?php echo $ligneSpermoMedLabo->numeration;?>
											</td>
											
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->vn;?>
											</td>
											
											<td>
												<table>
													<tr>
														<td>Forme typique</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>Forme atypique</td>
													</tr>
													
													<tr>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->formtypik;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->formatypik;?>
														</td>
													</tr>
												</table>
											</td>
											
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->autre;?>
											</td>
											
										</tr>
										
										<tr style="background-color:#eee">	
											<td colspan=11 style='text-align:center'>CONCLUSION</td>
											
										</tr>
										<tr>	
											<td colspan=11 style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->conclusion;?>
											</td>
											
										</tr>
										<?php
										}
									}
										
								}else{
								?>
								<tr>
									
									<td style="text-align:center;"><?php echo $compteur;?></td>		
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td style="text-align:center;">
										<?php 
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneLaboPa->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											}else{							
												$presta=$lignePresta->nompresta;
											}
											
											if($lignePresta->mesure!='')
											{
												$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
											}else{
												$mesure='';
											}
										}else{
											$presta=$ligneLaboPa->autreExamen;
											$mesure='';
										}
										echo $presta;
										?>
									</td>
																
									<td style="text-align:center;"><?php echo $ligneLaboPa->autreresultats.''.$mesure;?></td>
									
									<td style="text-align:center;"><?php echo $ligneLaboPa->minresultats;?></td>
									
									<td style="text-align:center;"><?php echo $ligneLaboPa->maxresultats;?></td>
												
									<td style="text-align:center;">
									<?php
									if($ligneLaboPa->resultats!="")
									{
									?>
										<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
									<?php
									}
									?>
									</td>
										
									<td style="text-align:center;font-weight:normal;"><?php echo date('d-M-Y',strtotime($ligneLaboPa->dateconsu));?></td>

									<td style="text-align:center;">
									<?php
									$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
									$resultatsMed->execute(array(
									'idMed'=>$ligneLaboPa->id_uM
									));

									$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
										
									if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
									{
									?>	
										<?php echo $ligneMed->full_name;?>
																
									<?php
									}
									$resultatsMed->closeCursor();
									?>						
									</td>
										
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>
								</tr>
							<?php
								}
								
								$fullnamePaCheck=$fullnamePa;
								$compteur++;
							}
							?>		
						</tbody>
					</table>
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Laboratory Report for this search</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
				</div>
		<?php
			}
		}else{
		?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>LABORATORY GENERAL REPORT</h2></b>
					</td>
					
				</tr>
			</table>
			
			<div id="selectdatePersoMedicReport">
			
				<form action="patients_laboreport.php?report=ok&dmac=ok&selectGnlLaboReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&laboreport=ok" method="post" style="margin:auto;padding:5px;width:90%;" onsubmit="return controlFormCustom(this)" >
				
					<table id="dmac" style="margin:auto auto 20px">
						<tr style="display:inline-block; margin-bottom:25px;">
							<td>
								<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
							</td>
						</tr>
						
						<tr id="dmacligne" style="visibility:visible">
						
							<td id="dailymedicPerso" style="display:none;">Select Exam/Date
								
								<input type="text" id="dailyexaPerso" name="dailyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value="<?php echo $annee;?>"/>
							
								<button type="submit" name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
							</td>
							
							<td id="monthlymedicPerso" style="display:none">Select Exam/Month
							
								<input type="text" id="monthlyexaPerso" name="monthlyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
								
									<option value='1'>January</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
								
								</select>
								
								<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
								
								<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
								
							</td>
							
							<td id="annualymedicPerso" style="display:none">Select Exam/Year
															
								<input type="text" id="annualyexaPerso" name="annualyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
							
								<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							</td>
							
							<td id="custommedicPerso" style="display:none">
							
								<table>
									<tr>
										
										<td>From</td>
										<td>
											<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
										<td>
											<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td style="vertical-align:top;">
											<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>


				</form>
				
			</div>
			
			
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; cursor:pointer;" align="center">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
			</table>

		
			<?php
			
			if(isset($_GET['dmac']) OR isset($_GET['selectGnlLaboReport']))
			{
				$dailydateperso = " AND ml.dateresultats != '0000-00-00'";
				$paVisit="gnlPersoMedic";
				
				if(isset($_POST['searchdailyPerso']))
				{
					if(isset($_POST['dailydatePerso']))
					{
						$dailydateperso  = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\'';
						// $dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="dailyPersoMedic";
						
						$stringResult="Daily results : ".$_POST['dailydatePerso']."";
					
					}

				}
				
				if(isset($_POST['searchmonthlyPerso']))
				{
					if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
					{
						
						$ukwezi = $_POST['monthlydatePerso'];
						$umwaka = $_POST['monthlydatePersoYear'];
					
						$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' ';
						
						$paVisit="monthlyPersoMedic";
						
						$stringResult="Monthly results : ".$_POST['monthlydatePerso']."-".$_POST['monthlydatePersoYear']."";
						
					}

				}
				
				if(isset($_POST['searchannualyPerso']))
				{
					if(isset($_POST['annualydatePerso']))
					{
						$year = $_POST['annualydatePerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
						
						$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\'';
						
						$paVisit="annualyPersoMedic";
						
						$stringResult="Annualy results : ".$_POST['annualydatePerso']."";
				
					}
				
				}
				
				if(isset($_POST['searchcustomPerso']))
				{
					if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
					{
						$debut = $_POST['customdatedebutPerso'];
						$fin = $_POST['customdatefinPerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
											
						$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\'';
						
						$paVisit="customPersoMedic";
						
						$stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
				
					}
				}
				
				
					// echo $dailydateperso;
					// echo $ukwezi.' et '.$year;
					// echo $year;

			?>
			
				<div id="dmacMedicReport" style="display:inline;">
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:30%;">
											
						</td>
						
						<td style="text-align:center; width:40%;">
							<span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>
					
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:30%;">
							
						</td>
					</tr>			
				</table>
				
				<?php
				
				$resultLaboPa=$connexion->query('SELECT *FROM med_labo ml WHERE ml.examenfait=1 '.$dailydateperso.' ORDER BY ml.dateresultats');		
				/* $resultLaboPa->execute(array(
				'dailydateperso'=>$dailydateperso
				));
				*/
				$resultLaboPa->setFetchMode(PDO::FETCH_OBJ);
				
				// echo 'SELECT *FROM med_labo ml WHERE ml.examenfait=1 '.$dailydateperso.'<br/>';
				
				$comptLaboPa=$resultLaboPa->rowCount();
			
				// echo $dailydateperso;
				
				if($comptLaboPa != 0)
				{
					// echo $comptLaboPa;
				?>
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:33.333%;">
									
							<a href="dmacreport_patientLabo.php?dailydateperso=<?php echo $dailydateperso;?>&paVisit=<?php echo $paVisit;?>&divPersoMedicReport=ok&laboreport=ok" style="text-align:center" id="dmacmedicalpersopreview">
								
								<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
									<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
								</button>
							
							</a>
					
							<input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>
					
						</td>
						
						<td style="text-align:center; width:33.333%;">
							
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
							<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
						</td>
					</tr>			
				</table>
				
				<div style="overflow:auto;height:500px;width:auto;">
				
					<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
						<thead> 
							<tr>
								<th style="text-align:center;">N°</th>
								<th style="text-align:center;"><?php echo 'Laboratory technician';?></th>
								<th style="text-align:center;"><?php echo 'Full name';?></th>
								<th style="text-align:center;"><?php echo 'Date Résultats';?></th>
								<th style="text-align:center;"><?php echo getString(99);?></th>
								<th style="text-align:center;">Total Final</th>
							</tr> 
						</thead> 

						<tbody>
						<?php
						// $date='0000-00-00';
						$compteur=1;
						$fullnamePaCheck="";
						$TotalGnlMedLabo=0;
						$TotalGnlPrice=0;
						
							while($ligneLaboPa=$resultLaboPa->fetch())
							{
								$TotalDayPrice =0;
								$idassuLab=$ligneLaboPa->id_assuLab;
											
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuLab
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
									}
								}
						
								
								if($ligneLaboPa->moreresultats!=0)
								{
						?>						
								<tr>
								
									<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td>
									<?php
									$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
									$resultMedLabo->execute(array(
									'idMedLabo'=>$ligneLaboPa->id_consuLabo
									));
									
									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMedLabo=$resultMedLabo->rowCount();

									$TotalMedLabo=0;
									
									if($comptMedLabo != 0)
									{
									?>	
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										
										<tbody>
										<?php
											while($ligneMedLabo=$resultMedLabo->fetch())
											{
										?>
											<tr style="text-align:center;">
												<td style="text-align:center;font-weight:normal;">
												<?php
												$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);

												$comptPresta=$resultPresta->rowCount();


												if($lignePresta=$resultPresta->fetch())
												{
													if($lignePresta->namepresta!='')
													{
														echo $lignePresta->namepresta.'</td>';
													}else{
														echo $lignePresta->nompresta.'</td>';
													}
																		
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
													$prixlabo=$ligneMedLabo->prixprestationExa;
												}else{
													
													echo $ligneMedLabo->autreExamen.'</td>';					
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
													$prixlabo=$ligneMedLabo->prixautreExamen;
												}									
												?>
											</tr>
											<?php
												$TotalMedLabo=$TotalMedLabo+$prixlabo;
											}
											?>		
										</tbody>
										</table>
									<?php
									}
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									?>
									</td>
									
									<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
								</tr>
								
									<?php
									if($ligneLaboPa->moreresultats==1)
									{
										$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
										$resultMoreMedLabo->execute(array(
										'idmedLab'=>$ligneLaboPa->id_medlabo
										));
										
										$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

										$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
										
										while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
										{
										?>									
										<tr>
											<td colspan=3></td>
											
											<td>
											<?php
											$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
											$resultMedLabo->execute(array(
											'idMedLabo'=>$ligneLaboPa->id_consuLabo
											));
											
											$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

											$comptMedLabo=$resultMedLabo->rowCount();

											$TotalMedLabo=0;
											
											if($comptMedLabo != 0)
											{
											?>	
												<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
												
												<tbody>
												<?php
													while($ligneMedLabo=$resultMedLabo->fetch())
													{
												?>
													<tr style="text-align:center;">
														<td style="text-align:center;font-weight:normal;">
														<?php
														$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
														$resultPresta->execute(array(
														'prestaId'=>$ligneMedLabo->id_prestationExa
														));
														
														$resultPresta->setFetchMode(PDO::FETCH_OBJ);

														$comptPresta=$resultPresta->rowCount();


														if($lignePresta=$resultPresta->fetch())
														{
															if($lignePresta->namepresta!='')
															{
																echo $lignePresta->namepresta.'</td>';
															}else{
																echo $lignePresta->nompresta.'</td>';
															}
																				
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
															$prixlabo=$ligneMedLabo->prixprestationExa;
														}else{
															
															echo $ligneMedLabo->autreExamen.'</td>';					
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
															$prixlabo=$ligneMedLabo->prixautreExamen;
														}									
														?>
													</tr>
													<?php
														$TotalMedLabo=$TotalMedLabo+$prixlabo;
													}
													?>		
												</tbody>
												</table>
											<?php
											}
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
											?>
											</td>
											
											<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
											
										</tr>
										<?php
										}
									}
									
									if($ligneLaboPa->moreresultats==2)
									{
										?>
										<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>		
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
										<td>
											<?php
											$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
											$resultMedLabo->execute(array(
											'idMedLabo'=>$ligneLaboPa->id_consuLabo
											));
											
											$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

											$comptMedLabo=$resultMedLabo->rowCount();

											$TotalMedLabo=0;
											
											if($comptMedLabo != 0)
											{
											?>	
												<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
												
												<tbody>
												<?php
													while($ligneMedLabo=$resultMedLabo->fetch())
													{
												?>
													<tr style="text-align:center;">
														<td style="text-align:center;font-weight:normal;">
														<?php
														$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
														$resultPresta->execute(array(
														'prestaId'=>$ligneMedLabo->id_prestationExa
														));
														
														$resultPresta->setFetchMode(PDO::FETCH_OBJ);

														$comptPresta=$resultPresta->rowCount();


														if($lignePresta=$resultPresta->fetch())
														{
															if($lignePresta->namepresta!='')
															{
																echo $lignePresta->namepresta.'</td>';
															}else{
																echo $lignePresta->nompresta.'</td>';
															}
																				
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
															$prixlabo=$ligneMedLabo->prixprestationExa;
														}else{
															
															echo $ligneMedLabo->autreExamen.'</td>';					
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
															$prixlabo=$ligneMedLabo->prixautreExamen;
														}									
														?>
													</tr>
													<?php
														$TotalMedLabo=$TotalMedLabo+$prixlabo;
													}
													?>		
												</tbody>
												</table>
											<?php
											}
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
											?>
										</td>
										<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
										<?php
									}
										
								}else{
								?>
								<tr>
									
									<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>		
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td>
									<?php
									$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
									$resultMedLabo->execute(array(
									'idMedLabo'=>$ligneLaboPa->id_consuLabo
									));
									
									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMedLabo=$resultMedLabo->rowCount();

									$TotalMedLabo=0;
									
									if($comptMedLabo != 0)
									{
									?>	
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										
										<tbody>
										<?php
											while($ligneMedLabo=$resultMedLabo->fetch())
											{
										?>
											<tr style="text-align:center;">
												<td style="text-align:center;font-weight:normal;">
												<?php
												$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);

												$comptPresta=$resultPresta->rowCount();


												if($lignePresta=$resultPresta->fetch())
												{
													if($lignePresta->namepresta!='')
													{
														echo $lignePresta->namepresta.'</td>';
													}else{
														echo $lignePresta->nompresta.'</td>';
													}
																		
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
													$prixlabo=$ligneMedLabo->prixprestationExa;
												}else{
													
													echo $ligneMedLabo->autreExamen.'</td>';					
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
													$prixlabo=$ligneMedLabo->prixautreExamen;
												}									
												?>
											</tr>
											<?php
												$TotalMedLabo=$TotalMedLabo+$prixlabo;
											}
											?>		
										</tbody>
										</table>
									<?php
									}
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									?>
									</td>
																
									<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>	
									
								</tr>
							<?php
								}
								
								$fullnamePaCheck=$fullnamePa;
								$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
								$compteur++;
							}
							?>	
							<tr style="text-align:center;">
								<td colspan=5></td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlPrice;				
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
							</tr>	
						</tbody>
					</table>
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Laboratory Report for this search</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
				</div>
		<?php
			}
		}
	}
	?>

	<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
	<?php

	if(isset($_GET['laboreportpeeked']))
	{
		if (!isset($_SESSION['codeC'])) {
	?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>LABORATORY GENERAL REPORT</h2></b>
					</td>
					
				</tr>
			</table>
			
			<div id="selectdatePersoMedicReport">
			
				<form action="patients_laboreport.php?report=ok&dmac=ok&selectGnlLaboReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&laboreportpeeked=ok" method="post" style="margin:auto;padding:5px;width:90%;" onsubmit="return controlFormCustom(this)" >
				
					<table id="dmac" style="margin:auto auto 20px">
						<tr style="display:inline-block; margin-bottom:25px;">
							<td>
								<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
							</td>
						</tr>
						
						<tr id="dmacligne" style="visibility:visible">
						
							<td id="dailymedicPerso" style="display:none;">Select Exam/Date
								
								<input type="text" id="dailyexaPerso" name="dailyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value="<?php echo $annee;?>"/>
							
								<button type="submit" name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
							</td>
							
							<td id="monthlymedicPerso" style="display:none">Select Exam/Month
							
								<input type="text" id="monthlyexaPerso" name="monthlyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
								
									<option value='1'>January</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
								
								</select>
								
								<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
								
								<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
								
							</td>
							
							<td id="annualymedicPerso" style="display:none">Select Exam/Year
															
								<input type="text" id="annualyexaPerso" name="annualyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
							
								<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							</td>
							
							<td id="custommedicPerso" style="display:none">
							
								<table>
									<tr>
										
										<td>From</td>
										<td>
											<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
										<td>
											<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td style="vertical-align:top;">
											<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>


				</form>
				
			</div>
			
			
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; cursor:pointer;" align="center">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
			</table>

		
			<?php
			
			if(isset($_GET['dmac']) OR isset($_GET['selectGnlLaboReport']))
			{
				$dailydateperso = " AND ml.dateresultats != '0000-00-00'";
				$paVisit="gnlPersoMedic";
				
				if(isset($_POST['searchdailyPerso']))
				{
					if(isset($_POST['dailydatePerso']))
					{
						$dailydateperso  = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\'';
						// $dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="dailyPersoMedic";
						
						$stringResult="Daily results : ".$_POST['dailydatePerso']."";
					
					}

				}
				
				if(isset($_POST['searchmonthlyPerso']))
				{
					if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
					{
						
						$ukwezi = $_POST['monthlydatePerso'];
						$umwaka = $_POST['monthlydatePersoYear'];
					
						$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' ';
						
						$paVisit="monthlyPersoMedic";
						
						$stringResult="Monthly results : ".$_POST['monthlydatePerso']."-".$_POST['monthlydatePersoYear']."";
						
					}

				}
				
				if(isset($_POST['searchannualyPerso']))
				{
					if(isset($_POST['annualydatePerso']))
					{
						$year = $_POST['annualydatePerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
						
						$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\'';
						
						$paVisit="annualyPersoMedic";
						
						$stringResult="Annualy results : ".$_POST['annualydatePerso']."";
				
					}
				
				}
				
				if(isset($_POST['searchcustomPerso']))
				{
					if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
					{
						$debut = $_POST['customdatedebutPerso'];
						$fin = $_POST['customdatefinPerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
											
						$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\'';
						
						$paVisit="customPersoMedic";
						
						$stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
				
					}
				}
				
				
					// echo $dailydateperso;
					// echo $ukwezi.' et '.$year;
					// echo $year;

			?>
			
				<div id="dmacMedicReport" style="display:inline;">
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:30%;">
											
						</td>
						
						<td style="text-align:center; width:40%;">
							<span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>
					
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:30%;">
							
						</td>
					</tr>			
				</table>
				
				<?php
				
				$resultLaboPa=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_uL=:id_uL AND ml.examenfait=1 '.$dailydateperso.' ORDER BY ml.dateresultats');		
				 $resultLaboPa->execute(array(
				'id_uL'=>$_SESSION['id']
				));
				
				$resultLaboPa->setFetchMode(PDO::FETCH_OBJ);
				
				// echo 'SELECT *FROM med_labo ml WHERE ml.examenfait=1 '.$dailydateperso.'<br/>';
				
				$comptLaboPa=$resultLaboPa->rowCount();
			
				// echo $dailydateperso;
				
				if($comptLaboPa != 0)
				{
					// echo $comptLaboPa;
				?>
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:33.333%;">
									
							<a href="dmacreport_patientLabo.php?dailydateperso=<?php echo $dailydateperso;?>&paVisit=<?php echo $paVisit;?>&divPersoMedicReport=ok&laboreport=ok" style="text-align:center" id="dmacmedicalpersopreview">
								
								<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
									<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
								</button>
							
							</a>
					
							<input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>
					
						</td>
						
						<td style="text-align:center; width:33.333%;">
							
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
							<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
						</td>
					</tr>			
				</table>
				
				<div style="overflow:auto;height:500px;width:auto;">
				
					<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
						<thead> 
							<tr>
								<th style="text-align:center;">N°</th>
								<th style="text-align:center;"><?php echo 'Laboratory technician';?></th>
								<th style="text-align:center;"><?php echo 'Full name';?></th>
								<th style="text-align:center;"><?php echo 'Date Résultats';?></th>
								<th style="text-align:center;"><?php echo getString(99);?></th>
								<th style="text-align:center;">Doctor Name</th>
							</tr> 
						</thead> 

						<tbody>
						<?php
						// $date='0000-00-00';
						$compteur=1;
						$fullnamePaCheck="";
						$TotalGnlMedLabo=0;
						$TotalGnlPrice=0;
						
							while($ligneLaboPa=$resultLaboPa->fetch())
							{
								$TotalDayPrice =0;
								$idassuLab=$ligneLaboPa->id_assuLab;
											
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuLab
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
									}
								}
						
								
								/*if($ligneLaboPa->moreresultats!=0)
								{*/
						?>						
								<tr>
								
									<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td>
									<?php
									$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
									$resultMedLabo->execute(array(
									'idMedLabo'=>$ligneLaboPa->id_consuLabo
										));
									
									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMedLabo=$resultMedLabo->rowCount();

									$TotalMedLabo=0;
									
									if($comptMedLabo != 0)
									{
									?>	
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										
										<tbody>
										<?php
											while($ligneMedLabo=$resultMedLabo->fetch())
											{
										?>
											<tr style="text-align:center;">
												<td style="text-align:center;font-weight:normal;">
												<?php
												$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);

												$comptPresta=$resultPresta->rowCount();


												if($lignePresta=$resultPresta->fetch())
												{
													if($lignePresta->namepresta!='')
													{
														echo $lignePresta->namepresta.'</td>';
													}else{
														echo $lignePresta->nompresta.'</td>';
													}
																		
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
													$prixlabo=$ligneMedLabo->prixprestationExa;
												}else{
													
													echo $ligneMedLabo->autreExamen.'</td>';					
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
													$prixlabo=$ligneMedLabo->prixautreExamen;
												}									
												?>
											</tr>
											<?php
												$TotalMedLabo=$TotalMedLabo+$prixlabo;
											}
											?>		
										</tbody>
										</table>
									<?php
									}
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									?>
									</td>
									
									<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
								</tr>
								
							<?php
								//}
								
								$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
								$compteur++;
							}
							?>	
							<tr style="text-align:center;">
								<td colspan=5></td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlPrice;				
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
							</tr>	
						</tbody>
					</table>
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Laboratory Report for this search</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
				</div>
		<?php
			}
		}else{
		?>
			<table style="margin:auto;">
				<tr>
					<td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
						<b><h2>LABORATORY GENERAL REPORT</h2></b>
					</td>
					
				</tr>
			</table>
			
			<div id="selectdatePersoMedicReport">
			
				<form action="patients_laboreport.php?report=ok&dmac=ok&selectGnlLaboReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&laboreport=ok" method="post" style="margin:auto;padding:5px;width:90%;" onsubmit="return controlFormCustom(this)" >
				
					<table id="dmac" style="margin:auto auto 20px">
						<tr style="display:inline-block; margin-bottom:25px;">
							<td>
								<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
							</td>
						</tr>
						
						<tr id="dmacligne" style="visibility:visible">
						
							<td id="dailymedicPerso" style="display:none;">Select Exam/Date
								
								<input type="text" id="dailyexaPerso" name="dailyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value="<?php echo $annee;?>"/>
							
								<button type="submit" name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
							</td>
							
							<td id="monthlymedicPerso" style="display:none">Select Exam/Month
							
								<input type="text" id="monthlyexaPerso" name="monthlyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
								
									<option value='1'>January</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
								
								</select>
								
								<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
								
								<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
								
							</td>
							
							<td id="annualymedicPerso" style="display:none">Select Exam/Year
															
								<input type="text" id="annualyexaPerso" name="annualyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
							
								<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							</td>
							
							<td id="custommedicPerso" style="display:none">
							
								<table>
									<tr>
										
										<td>From</td>
										<td>
											<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
										<td>
											<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td style="vertical-align:top;">
											<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>


				</form>
				
			</div>
			
			
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; cursor:pointer;" align="center">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
			</table>

		
			<?php
			
			if(isset($_GET['dmac']) OR isset($_GET['selectGnlLaboReport']))
			{
				$dailydateperso = " AND ml.dateresultats != '0000-00-00'";
				$paVisit="gnlPersoMedic";
				
				if(isset($_POST['searchdailyPerso']))
				{
					if(isset($_POST['dailydatePerso']))
					{
						$dailydateperso  = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\'';
						// $dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="dailyPersoMedic";
						
						$stringResult="Daily results : ".$_POST['dailydatePerso']."";
					
					}

				}
				
				if(isset($_POST['searchmonthlyPerso']))
				{
					if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
					{
						
						$ukwezi = $_POST['monthlydatePerso'];
						$umwaka = $_POST['monthlydatePersoYear'];
					
						$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' ';
						
						$paVisit="monthlyPersoMedic";
						
						$stringResult="Monthly results : ".$_POST['monthlydatePerso']."-".$_POST['monthlydatePersoYear']."";
						
					}

				}
				
				if(isset($_POST['searchannualyPerso']))
				{
					if(isset($_POST['annualydatePerso']))
					{
						$year = $_POST['annualydatePerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
						
						$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\'';
						
						$paVisit="annualyPersoMedic";
						
						$stringResult="Annualy results : ".$_POST['annualydatePerso']."";
				
					}
				
				}
				
				if(isset($_POST['searchcustomPerso']))
				{
					if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
					{
						$debut = $_POST['customdatedebutPerso'];
						$fin = $_POST['customdatefinPerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
											
						$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\'';
						
						$paVisit="customPersoMedic";
						
						$stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ]";
				
					}
				}
				
				
					// echo $dailydateperso;
					// echo $ukwezi.' et '.$year;
					// echo $year;

			?>
			
				<div id="dmacMedicReport" style="display:inline;">
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:30%;">
											
						</td>
						
						<td style="text-align:center; width:40%;">
							<span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>
					
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:30%;">
							
						</td>
					</tr>			
				</table>
				
				<?php
				
				$resultLaboPa=$connexion->query('SELECT *FROM med_labo ml WHERE ml.examenfait=1 '.$dailydateperso.' ORDER BY ml.dateresultats');		
				/* $resultLaboPa->execute(array(
				'dailydateperso'=>$dailydateperso
				));
				*/
				$resultLaboPa->setFetchMode(PDO::FETCH_OBJ);
				
				// echo 'SELECT *FROM med_labo ml WHERE ml.examenfait=1 '.$dailydateperso.'<br/>';
				
				$comptLaboPa=$resultLaboPa->rowCount();
			
				// echo $dailydateperso;
				
				if($comptLaboPa != 0)
				{
					// echo $comptLaboPa;
				?>
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:33.333%;">
									
							<a href="dmacreport_patientLabo.php?dailydateperso=<?php echo $dailydateperso;?>&paVisit=<?php echo $paVisit;?>&divPersoMedicReport=ok&laboreport=ok" style="text-align:center" id="dmacmedicalpersopreview">
								
								<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
									<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
								</button>
							
							</a>
					
							<input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>
					
						</td>
						
						<td style="text-align:center; width:33.333%;">
							
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
							<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
						</td>
					</tr>			
				</table>
				
				<div style="overflow:auto;height:500px;width:auto;">
				
					<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
						<thead> 
							<tr>
								<th style="text-align:center;">N°</th>
								<th style="text-align:center;"><?php echo 'Laboratory technician';?></th>
								<th style="text-align:center;"><?php echo 'Full name';?></th>
								<th style="text-align:center;"><?php echo 'Date Résultats';?></th>
								<th style="text-align:center;"><?php echo getString(99);?></th>
								<th style="text-align:center;">Total Final</th>
							</tr> 
						</thead> 

						<tbody>
						<?php
						// $date='0000-00-00';
						$compteur=1;
						$fullnamePaCheck="";
						$TotalGnlMedLabo=0;
						$TotalGnlPrice=0;
						
							while($ligneLaboPa=$resultLaboPa->fetch())
							{
								$TotalDayPrice =0;
								$idassuLab=$ligneLaboPa->id_assuLab;
											
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuLab
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
									}
								}
						
								
								if($ligneLaboPa->moreresultats!=0)
								{
						?>						
								<tr>
								
									<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td>
									<?php
									$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
									$resultMedLabo->execute(array(
									'idMedLabo'=>$ligneLaboPa->id_consuLabo
									));
									
									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMedLabo=$resultMedLabo->rowCount();

									$TotalMedLabo=0;
									
									if($comptMedLabo != 0)
									{
									?>	
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										
										<tbody>
										<?php
											while($ligneMedLabo=$resultMedLabo->fetch())
											{
										?>
											<tr style="text-align:center;">
												<td style="text-align:center;font-weight:normal;">
												<?php
												$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);

												$comptPresta=$resultPresta->rowCount();


												if($lignePresta=$resultPresta->fetch())
												{
													if($lignePresta->namepresta!='')
													{
														echo $lignePresta->namepresta.'</td>';
													}else{
														echo $lignePresta->nompresta.'</td>';
													}
																		
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
													$prixlabo=$ligneMedLabo->prixprestationExa;
												}else{
													
													echo $ligneMedLabo->autreExamen.'</td>';					
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
													$prixlabo=$ligneMedLabo->prixautreExamen;
												}									
												?>
											</tr>
											<?php
												$TotalMedLabo=$TotalMedLabo+$prixlabo;
											}
											?>		
										</tbody>
										</table>
									<?php
									}
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									?>
									</td>
									
									<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
								</tr>
								
									<?php
									if($ligneLaboPa->moreresultats==1)
									{
										$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
										$resultMoreMedLabo->execute(array(
										'idmedLab'=>$ligneLaboPa->id_medlabo
										));
										
										$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

										$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
										
										while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
										{
										?>									
										<tr>
											<td colspan=3></td>
											
											<td>
											<?php
											$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
											$resultMedLabo->execute(array(
											'idMedLabo'=>$ligneLaboPa->id_consuLabo
											));
											
											$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

											$comptMedLabo=$resultMedLabo->rowCount();

											$TotalMedLabo=0;
											
											if($comptMedLabo != 0)
											{
											?>	
												<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
												
												<tbody>
												<?php
													while($ligneMedLabo=$resultMedLabo->fetch())
													{
												?>
													<tr style="text-align:center;">
														<td style="text-align:center;font-weight:normal;">
														<?php
														$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
														$resultPresta->execute(array(
														'prestaId'=>$ligneMedLabo->id_prestationExa
														));
														
														$resultPresta->setFetchMode(PDO::FETCH_OBJ);

														$comptPresta=$resultPresta->rowCount();


														if($lignePresta=$resultPresta->fetch())
														{
															if($lignePresta->namepresta!='')
															{
																echo $lignePresta->namepresta.'</td>';
															}else{
																echo $lignePresta->nompresta.'</td>';
															}
																				
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
															$prixlabo=$ligneMedLabo->prixprestationExa;
														}else{
															
															echo $ligneMedLabo->autreExamen.'</td>';					
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
															$prixlabo=$ligneMedLabo->prixautreExamen;
														}									
														?>
													</tr>
													<?php
														$TotalMedLabo=$TotalMedLabo+$prixlabo;
													}
													?>		
												</tbody>
												</table>
											<?php
											}
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
											?>
											</td>
											
											<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
											
										</tr>
										<?php
										}
									}
									
									if($ligneLaboPa->moreresultats==2)
									{
										?>
										<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>		
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
										<td>
											<?php
											$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
											$resultMedLabo->execute(array(
											'idMedLabo'=>$ligneLaboPa->id_consuLabo
											));
											
											$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

											$comptMedLabo=$resultMedLabo->rowCount();

											$TotalMedLabo=0;
											
											if($comptMedLabo != 0)
											{
											?>	
												<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
												
												<tbody>
												<?php
													while($ligneMedLabo=$resultMedLabo->fetch())
													{
												?>
													<tr style="text-align:center;">
														<td style="text-align:center;font-weight:normal;">
														<?php
														$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
														$resultPresta->execute(array(
														'prestaId'=>$ligneMedLabo->id_prestationExa
														));
														
														$resultPresta->setFetchMode(PDO::FETCH_OBJ);

														$comptPresta=$resultPresta->rowCount();


														if($lignePresta=$resultPresta->fetch())
														{
															if($lignePresta->namepresta!='')
															{
																echo $lignePresta->namepresta.'</td>';
															}else{
																echo $lignePresta->nompresta.'</td>';
															}
																				
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
															$prixlabo=$ligneMedLabo->prixprestationExa;
														}else{
															
															echo $ligneMedLabo->autreExamen.'</td>';					
															echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
															$prixlabo=$ligneMedLabo->prixautreExamen;
														}									
														?>
													</tr>
													<?php
														$TotalMedLabo=$TotalMedLabo+$prixlabo;
													}
													?>		
												</tbody>
												</table>
											<?php
											}
											
											$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
											?>
										</td>
										<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>
										<?php
									}
										
								}else{
								?>
								<tr>
									
									<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>		
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td>
									<?php
									$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
									$resultMedLabo->execute(array(
									'idMedLabo'=>$ligneLaboPa->id_consuLabo
									));
									
									$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

									$comptMedLabo=$resultMedLabo->rowCount();

									$TotalMedLabo=0;
									
									if($comptMedLabo != 0)
									{
									?>	
										<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
										
										<tbody>
										<?php
											while($ligneMedLabo=$resultMedLabo->fetch())
											{
										?>
											<tr style="text-align:center;">
												<td style="text-align:center;font-weight:normal;">
												<?php
												$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);

												$comptPresta=$resultPresta->rowCount();


												if($lignePresta=$resultPresta->fetch())
												{
													if($lignePresta->namepresta!='')
													{
														echo $lignePresta->namepresta.'</td>';
													}else{
														echo $lignePresta->nompresta.'</td>';
													}
																		
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixprestationExa.'</td>';
													$prixlabo=$ligneMedLabo->prixprestationExa;
												}else{
													
													echo $ligneMedLabo->autreExamen.'</td>';					
													echo '<td style="border-left:1px solid #eee;font-weight:normal;">'.$ligneMedLabo->prixautreExamen.'</td>';
													$prixlabo=$ligneMedLabo->prixautreExamen;
												}									
												?>
											</tr>
											<?php
												$TotalMedLabo=$TotalMedLabo+$prixlabo;
											}
											?>		
										</tbody>
										</table>
									<?php
									}
									
									$TotalDayPrice=$TotalDayPrice+$TotalMedLabo;
									?>
									</td>
																
									<td style="text-align:center;"><?php echo $TotalDayPrice;?></td>	
									
								</tr>
							<?php
								}
								
								$fullnamePaCheck=$fullnamePa;
								$TotalGnlPrice=$TotalGnlPrice + $TotalDayPrice;	
								$compteur++;
							}
							?>	
							<tr style="text-align:center;">
								<td colspan=5></td>
								<td style="font-size: 13px; font-weight: bold;">
									<?php						
										echo $TotalGnlPrice;				
									?><span style="font-size:80%; font-weight:normal;">Rwf</span>
								</td>
							</tr>	
						</tbody>
					</table>
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Laboratory Report for this search</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
				</div>
		<?php
			}
		}
	}
	?>
	<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->


	<?php 

			if (isset($_GET['reporthospCash'])) {
	?>

	<table style="margin:auto;">
                <tr>
                    <td style="font-size:18px; width:33.333%;display:inline" id="individualstring">
                        <b><h2><?php if(isset($_GET['reportclinic'])){ echo 'Labo Clinic Report';} if(isset($_GET['reporthospCash'])){ echo 'Labo Hospital Report';}?></h2></b>
                    </td>
                </tr>
            </table>
			
			<div id="selectdatePersoMedicReport">
			
				<form action="patients_laboreport.php?reporthospCash=ok&dmac=ok&selectGnlLaboReport=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>&laboreportHosp=ok" method="post" style="margin:auto;padding:5px;width:90%;" onsubmit="return controlFormCustom(this)" >
				
					<table id="dmac" style="margin:auto auto 20px">
						<tr style="display:inline-block; margin-bottom:25px;">
							<td>
								<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelect('dailymedicPerso')" class="btn">Daily</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelect('monthlymedicPerso')" class="btn">Monthly</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelect('annualymedicPerso')" class="btn">Annualy</span>
							</td>
							
							<td>
								<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelect('custommedicPerso')" class="btn">Custom</span>
							</td>
						</tr>
						
						<tr id="dmacligne" style="visibility:visible">
						
							<td id="dailymedicPerso" style="display:none;">Select Exam/Date
								
								<select style='margin:auto' class='chosen-select' name='dailyIdexaPerso' id='dailyIdexaPerso' onchange="OtherDailyExa('dailyExa')">
									<?php
								
									$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
									
									$req->setFetchMode(PDO::FETCH_OBJ);

									$comptReq=$req->rowCount();
													
									while($ligneReq=$req->fetch())
									{
									?>	
										<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
									<?php
									}
									?>
										<option value='0'><?php echo 'Autre Examen...';?></option>
								</select>
								
								<input type="text" id="dailyexaPerso" name="dailyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<input type="text" id="dailydatePerso" name="dailydatePerso" onclick="ds_sh(this);" value="<?php echo $annee;?>"/>
							
								<button type="submit" name="searchdailyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
							</td>
							
							<td id="monthlymedicPerso" style="display:none">Select Exam/Month
							
								<select style='margin:auto' class='chosen-select' name='monthlyIdexaPerso' id='monthlyIdexaPerso' onchange="OtherDailyExa('monthlyExa')">
									<?php
								
									$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
									
									$req->setFetchMode(PDO::FETCH_OBJ);

									$comptReq=$req->rowCount();
													
									while($ligneReq=$req->fetch())
									{
									?>	
										<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
									<?php
									}
									?>
										<option value='0'><?php echo 'Autre Examen...';?></option>
								</select>
								
								<input type="text" id="monthlyexaPerso" name="monthlyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="monthlydatePerso" id="monthlydatePerso" style="width:100px;height:40px;">
								
									<option value='1'>January</option>
									<option value='2'>February</option>
									<option value='3'>March</option>
									<option value='4'>April</option>
									<option value='5'>May</option>
									<option value='6'>June</option>
									<option value='7'>July</option>
									<option value='8'>August</option>
									<option value='9'>September</option>
									<option value='10'>October</option>
									<option value='11'>November</option>
									<option value='12'>December</option>
								
								</select>
								
								<select name="monthlydatePersoYear" id="monthlydatePersoYear" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
								
								<button type="submit"  name="searchmonthlyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
								
							</td>
							
							<td id="annualymedicPerso" style="display:none">Select Exam/Year
							
								<select style='margin:auto' class='chosen-select' name='annualyIdexaPerso' id='annualyIdexaPerso' onchange="OtherDailyExa('annualyExa')">
									<?php
								
									$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
									
									$req->setFetchMode(PDO::FETCH_OBJ);

									$comptReq=$req->rowCount();
													
									while($ligneReq=$req->fetch())
									{
									?>	
										<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
									<?php
									}
									?>
										<option value='0'><?php echo 'Autre Examen...';?></option>
								</select>
								
								<input type="text" id="annualyexaPerso" name="annualyexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
							
								<select name="annualydatePerso" id="annualydatePerso" style="width:100px;height:40px;">
								<?php 
								for($i=2016;$i<=2030;$i++)
								{
								?>
									<option value='<?php echo $i;?>'><?php echo $i;?></option>
								<?php 
								}
								?>
								</select>
							
								<button type="submit"  name="searchannualyPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
							</td>
							
							<td id="custommedicPerso" style="display:none">
							
								<table>
									<tr>
										<td>Select Exam/...</td>
										<td style="width:250px" >
											
											<select style='margin:auto' class='chosen-select' name='customIdexaPerso' id='customIdexaPerso' onchange="OtherDailyExa('customExa')">
												<?php
											
												$req=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_categopresta=12 ORDER BY p.nompresta ASC');
												
												$req->setFetchMode(PDO::FETCH_OBJ);

												$comptReq=$req->rowCount();
																
												while($ligneReq=$req->fetch())
												{
												?>	
													<option value='<?php echo $ligneReq->id_prestation;?>'><?php echo $ligneReq->nompresta;?></option>
												<?php
												}
												?>
													<option value='0'><?php echo 'Autre Examen...';?></option>
											</select>
											
											<input type="text" id="customexaPerso" name="customexaPerso" value="" placeholder="Enter the Exam..." style="display:none"/>
										
										</td>
										
										<td>From</td>
										<td>
											<input type="text" id="customdatedebutPerso" name="customdatedebutPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
										<td>
											<input type="text" id="customdatefinPerso" name="customdatefinPerso" onclick="ds_sh(this);" value="" style="width:150px"/>
										</td>
									
										<td style="vertical-align:top;">
											<button type="submit"  name="searchcustomPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					
					</table>


				</form>
				
			</div>
			
			
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; cursor:pointer;" align="center">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
			</table>

		
			<?php
			
			if(isset($_GET['dmac']) OR isset($_GET['selectGnlLaboReport']))
			{
				$dailydateperso = " AND ml.dateresultats != '0000-00-00'";
				$paVisit="gnlPersoMedic";
				
				if(isset($_POST['searchdailyPerso']))
				{
					if(isset($_POST['dailydatePerso']))
					{
						
						if($_POST['dailyIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['dailyIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['dailyIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
									$prestaExa = "";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;
									$prestaExa = $ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
									$prestaExa = "";
								}
								
								$dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
							}
							
						}else{
							$nomExa=$_POST['dailyexaPerso'];
							$prestaExa=$_POST['dailyexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						}
						
						// $dailydateperso = ' AND ml.dateresultats=\''.$_POST['dailydatePerso'].'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="dailyPersoMedic";
						
						$stringResult="Daily results : ".$_POST['dailydatePerso']." ( ".$prestaExa." )";
					
					}

				}
				
				if(isset($_POST['searchmonthlyPerso']))
				{
					if(isset($_POST['monthlydatePerso']) AND isset($_POST['monthlydatePersoYear']))
					{
						
						$ukwezi = $_POST['monthlydatePerso'];
						$umwaka = $_POST['monthlydatePersoYear'];
					
						$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						if($_POST['monthlyIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['monthlyIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['monthlyIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{						
								$prestaExa = "";
								
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;	
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;	
									$prestaExa=$ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
								}
															
								$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
						
							}
							
						}else{
							$nomExa=$_POST['monthlyexaPerso'];
							$prestaExa=$_POST['monthlyexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						}
						
						// $dailydateperso = 'AND ml.dateresultats>=\''.$umwaka.'-'.$ukwezi.'-1\' AND ml.dateresultats<=\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="monthlyPersoMedic";
						
						$stringResult="Monthly results : ".$_POST['monthlydatePerso']."-".$_POST['monthlydatePersoYear']." ( ".$prestaExa." )";
						
					}

				}
				
				if(isset($_POST['searchannualyPerso']))
				{
					if(isset($_POST['annualydatePerso']))
					{
						$year = $_POST['annualydatePerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
						
						if($_POST['annualyIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['annualyIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['annualyIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{							
								$prestaExa = "";
								
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;	
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;	
									$prestaExa=$ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
								}
								
								$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
						
							}
							
						}else{
							$nomExa=$_POST['annualyexaPerso'];
							$prestaExa=$_POST['annualyexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						}
						
						// $dailydateperso = 'AND ml.dateresultats>=\''.$year.'-01-01\' AND ml.dateresultats<=\''.$year.'-12-31\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="annualyPersoMedic";
						
						$stringResult="Annualy results : ".$_POST['annualydatePerso']." ( ".$prestaExa." )";
				
					}
				
				}
				
				if(isset($_POST['searchcustomPerso']))
				{
					if(isset($_POST['customdatedebutPerso']) AND isset($_POST['customdatefinPerso']))
					{
						$debut = $_POST['customdatedebutPerso'];
						$fin = $_POST['customdatefinPerso'];
					
						// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
						
						if($_POST['customIdexaPerso']!=0)
						{
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.id_prestation ='.$_POST['customIdexaPerso'].'');
							/* $resultExaPresta->execute(array(
							'idExa'=>$_POST['customIdexaPerso']
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{						
								$prestaExa = "";
								
								if($ligneExaPresta->nompresta != "")
								{
									$nomExa = $ligneExaPresta->nompresta;	
									$prestaExa=$ligneExaPresta->nompresta;
								}else{
									$nomExa = "blablabla";
								}
								
								if($ligneExaPresta->namepresta != "")
								{
									$nameExa = $ligneExaPresta->namepresta;	
									$prestaExa=$ligneExaPresta->namepresta;
								}else{
									$nameExa = "blablabla";
								}
								
								$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\' AND (ml.id_prestationExa='.$ligneExaPresta->id_prestation.' OR ml.autreExamen LIKE \''.$nomExa.'\' OR ml.autreExamen LIKE \''.$nameExa.'\')';
							
							}
							
						}else{
							$nomExa=$_POST['customexaPerso'];
							$prestaExa=$_POST['customexaPerso'];
						
							$resultExaPresta=$connexion->query('SELECT *FROM prestations_private p WHERE p.nompresta LIKE \''.$nomExa.'\'');
							/* $resultExaPresta->execute(array(
							'nomExa'=>$nomExa
							)); */
											
							$comptExaPresta=$resultExaPresta->rowCount();
							
							$resultExaPresta->setFetchMode(PDO::FETCH_OBJ);
							if($ligneExaPresta=$resultExaPresta->fetch())
							{
								$id_exa = $ligneExaPresta->id_prestation;
							}else{
								$id_exa =0;
							}
							
							$dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
							
						}
						
						// $dailydateperso = 'AND ml.dateresultats>=\''.$debut.'\' AND ml.dateresultats<=\''.$fin.'\' AND (ml.id_prestationExa='.$id_exa.' OR ml.autreExamen LIKE \''.$nomExa.'\')';
						
						$paVisit="customPersoMedic";
						
						$stringResult="Customer results : [ ".$_POST['customdatedebutPerso']."/".$_POST['customdatefinPerso']." ] ( ".$prestaExa." )";
				
					}
				}
				
					// echo $dailydateperso;
					// echo $ukwezi.' et '.$year;
					// echo $year;

			?>
			
				<div id="dmacMedicReport" style="display:inline;">
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:30%;">
											
						</td>
						
						<td style="text-align:center; width:40%;">
							<span style="position:relative; font-size:150%;"></i> <?php echo $stringResult;?></span>
					
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:30%;">
							
						</td>
					</tr>			
				</table>
				
				<?php
				
				$resultLaboPa=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.id_uL=:id_uL AND ml.examenfait=1 '.$dailydateperso.' ORDER BY ml.dateresultats');		
				 $resultLaboPa->execute(array(
				'id_uL'=>$_SESSION['id']
				));
				
				$resultLaboPa->setFetchMode(PDO::FETCH_OBJ);
				
				//echo 'SELECT *FROM med_labo_hosp ml WHERE ml.examenfait=1 '.$dailydateperso.' ORDER BY ml.dateresultats';
				
				$comptLaboPa=$resultLaboPa->rowCount();
			
				 //echo $dailydateperso;
				
				if($comptLaboPa != 0)
				{
					// echo $comptLaboPa;
				?>
				
				<table style="width:100%;">
					<tr>
						<td style="text-align:center; width:33.333%;">
									
							<a href="dmacreport_patientLabo.php?dailydateperso=<?php echo $dailydateperso;?>&paVisit=<?php echo $paVisit;?>&divPersoMedicReport=ok&laboreportHosp=ok" style="text-align:center" id="dmacmedicalpersopreview">
								
								<button style="width:250px; margin:auto;" type="submit" name="printMedicReportPerso" id="printMedicReportPerso" class="btn-large-inversed">
									<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print
								</button>
							
							</a>
					
							<input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>
					
						</td>
						
						<td style="text-align:center; width:33.333%;">
							
						</td>
						
						<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
							<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
						</td>
					</tr>			
				</table>
				
				<div style="overflow:auto;height:500px;width:auto;">
				
					<table class="tablesorter tablesorter3" cellspacing="0" style="background:#fff;width:100%;"> 
						<thead> 
							<tr>
								<th style="text-align:center;">N°</th>
								<th style="text-align:center;"><?php echo 'Full name';?></th>
								<th style="text-align:center;"><?php echo 'Date Résultats';?></th>
								<th style="text-align:center;"><?php echo getString(99);?></th>
								<th style="text-align:center;"><?php echo 'Résultats';?></th>
								<th style="text-align:center;"><?php echo 'Value(Min-Max)';?></th>
								<th style="text-align:center;"><?php echo 'Lab Files';?></th>
								<th style="text-align:center;"><?php echo 'Date d\'Hospitalization';?></th>
								<th style="text-align:center;"><?php echo getString(19) ?></th>
								<th style="text-align:center;" colspan=2><?php echo 'Laboratory technician';?></th>
							</tr> 
						</thead> 

						<tbody>
						<?php
						// $date='0000-00-00';
						$compteur=1;
						$fullnamePaCheck="";
						
							while($ligneLaboPa=$resultLaboPa->fetch())
							{
								$idassuLab=$ligneLaboPa->id_assuLab;
											
								$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
								
								$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
										
								$assuCount = $comptAssuConsu->rowCount();
								
								for($i=1;$i<=$assuCount;$i++)
								{
									
									$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
									$getAssuConsu->execute(array(
									'idassu'=>$idassuLab
									));
									
									$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

									if($ligneNomAssu=$getAssuConsu->fetch())
									{
										$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
									}
								}
						
								
								if($ligneLaboPa->moreresultats!=0)
								{
						?>						
								<tr>
								
									<td style="text-align:center;"><?php echo $compteur;?></td>
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td style="text-align:center;">
									
										<?php 
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
										'prestaId'=>$ligneLaboPa->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											}else{
											
												$presta=$lignePresta->nompresta;
											}
											$mesure=' (<span style="font-size:80%; font-weight:normal;padding:5px;">'.$lignePresta->mesure.'</span>)';
										}else{
											$presta=$ligneLaboPa->autreExamen;
											$mesure='';
										}
											echo $presta;
										?>
									
									</td>
									
									<td colspan=4>
									</td>						
												
									<td style="text-align:center;"><?php echo date('d-M-Y',strtotime($ligneLaboPa->dateconsu));?></td>

									<td style="text-align:center;">
									<?php
									$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
									$resultatsMed->execute(array(
									'idMed'=>$ligneLaboPa->id_uM
									));

									$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
										
									if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
									{
										echo $ligneMed->full_name;
									}
									$resultatsMed->closeCursor();
									?>						
									</td>
										
									<td style="text-align:center;font-weight:normal;" colspan=2>
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										echo $ligneLaboId->full_name;		
									}
									$resultLaboId->closeCursor();
									?>
									</td>
								</tr>
								
									<?php
									if($ligneLaboPa->moreresultats==1)
									{
										$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');		
										$resultMoreMedLabo->execute(array(
										'idmedLab'=>$ligneLaboPa->id_medlabo
										));
										
										$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

										$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
										
										while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
										{
										?>									
										<tr>
											<td colspan=3></td>
											
											<td style="text-align:center;">
											<?php
												$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
												$resultPresta->execute(array(
												'prestaId'=>$ligneMoreMedLabo->id_prestationExa
												));
												
												$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

												$comptPresta=$resultPresta->rowCount();
												
												if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
												{
													if($lignePresta->namepresta!='')
													{
														$presta=$lignePresta->namepresta;
													}else{
													
														$presta=$lignePresta->nompresta;			
													}
													
													if($lignePresta->mesure!='')
													{
														$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
													}else{
														$mesure='';
													}
												}else{
													$presta=$ligneMoreMedLabo->autreExamen;
													
													$mesure='';
												}
												echo $presta;
											?>
											</td>
											
											<td style="text-align:center;">
											<?php echo $ligneMoreMedLabo->autreresultats.''.$mesure;?>
											</td>
											
										<!-- 	<td style="text-align:center;">
											<?php echo $ligneMoreMedLabo->minresultats;?>
											</td> -->
											
											<td style="text-align:center;">
											<?php echo'dfd';?>
											</td>
														
											<td style="text-align:center;">
											<?php
											if($ligneMoreMedLabo->resultats!="")
											{
											?>
												<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
											<?php
											}
											?>
											</td>
									
											<td colspan=4></td>
											
										</tr>
										<?php
										}
									}
									
									if($ligneLaboPa->moreresultats==2)
									{
										$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');		
										$resultSpermoMedLabo->execute(array(
										'num'=>$_GET['num'],
										'idmedLab'=>$ligneLaboPa->id_medlabo
										));
										
										$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

										$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
										
										while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
										{
										?>									
										<tr style="background-color:#eee">
											<td colspan=5 style='text-align:center'>EXAMEN MACROSCOPIQUES</td>
											<td style='border-left:1px solid #aaa;text-align:center' colspan=6>EXAMEN MICROSCOPIQUES</td>
										</tr>
										
										<tr>
											<td style='text-align:center'>Volume</td>
											<td style='text-align:center'>Densité</td>
											<td style='text-align:center'>Viscosité</td>
											<td style='text-align:center'>PH</td>
											<td style='text-align:center'>Aspect</td>
											
											<td style='border-left:1px solid #aaa;text-align:center'>Examen direct</td>
											<td style='text-align:center'>Mobilité après</td>
											<td style='text-align:center;font-weight:normal;text-align:center'>Numération</td>
											<td style='text-align:center'>V.N</td>
											<td style='text-align:center'>Spermocytogramme</td>
											<td style='text-align:center'>Autres</td>
										
										</tr>
										
										<tr>							
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->volume;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->densite;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->viscosite;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->ph;?>
											</td>						
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->aspect;?>
											</td>
											
											<td style='border-left:1px solid #aaa;text-align:center'>
											<?php echo $ligneSpermoMedLabo->examdirect;?>
											</td>
											
											<td>							
												<table>
													<tr>
														<td>0h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>1h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>2h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>3h après emission</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>4h après emission</td>
													</tr>
													
													<tr>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->zeroheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->uneheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->deuxheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->troisheureafter;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->quatreheureafter;?>
														</td>
													</tr>
												</table>
											</td>
											
											<td style='text-align:center;font-weight:normal;'>
											<?php echo $ligneSpermoMedLabo->numeration;?>
											</td>
											
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->vn;?>
											</td>
											
											<td>
												<table>
													<tr>
														<td>Forme typique</td>
														<td style='border-left:1px solid #aaa;padding:5px;'>Forme atypique</td>
													</tr>
													
													<tr>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->formtypik;?>
														</td>
														<td style='text-align:center'>
														<?php echo $ligneSpermoMedLabo->formatypik;?>
														</td>
													</tr>
												</table>
											</td>
											
											<td style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->autre;?>
											</td>
											
										</tr>
										
										<tr style="background-color:#eee">	
											<td colspan=11 style='text-align:center'>CONCLUSION</td>
											
										</tr>
										<tr>	
											<td colspan=11 style='text-align:center'>
											<?php echo $ligneSpermoMedLabo->conclusion;?>
											</td>
											
										</tr>
										<?php
										}
									}
										
								}else{
								?>
								<tr>
									
									<td style="text-align:center;"><?php echo $compteur;?></td>		
									<td style="text-align:left;font-weight:bold;">
									<?php
										$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation ORDER BY p.numero DESC');
										$resultPatient->execute(array(
										'operation'=>$ligneLaboPa->numero
										));
										
										$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptFiche=$resultPatient->rowCount();
										
										if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
										{
											$fullnamePa = $lignePatient->full_name.' ('.$ligneLaboPa->numero.')';
											
											if($fullnamePaCheck!=$fullnamePa)
											{
												echo $lignePatient->full_name.'<br/>('.$ligneLaboPa->numero.')';
											}
										}else{
											$fullnamePa="";
											echo '';
										}
									?>
									</td>
								
									<td style="text-align:center;color:blue;">
									<?php
									if($ligneLaboPa->dateresultats != '0000-00-00')
									{
										echo date('d-M-Y',strtotime($ligneLaboPa->dateresultats));
									}
									?>
									</td>
									
									<td style="text-align:center;">
										<?php 
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');		
										$resultPresta->execute(array(
											'prestaId'=>$ligneLaboPa->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											}else{							
												$presta=$lignePresta->nompresta;
											}
											
											if($lignePresta->mesure!='')
											{
												$mesure=' [<span style="font-size:80%; font-weight:normal;">'.$lignePresta->mesure.'</span>]';
											}else{
												$mesure='';
											}
										}else{
											$presta=$ligneLaboPa->autreExamen;
											$mesure='';
										}
										echo $presta;
										?>
									</td>
																
									<td style="text-align:center;"><?php echo $ligneLaboPa->autreresultats.''.$mesure;?></td>
									
									<td style="text-align:center;">
									<?php 
									$minresultats="";
									$maxresultats="";
									
									$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE nomexam=:nomexam ORDER BY valeur');
									$resultValeur->execute(array(
									'nomexam'=>$presta
									));
									
									$resultValeur->setFetchMode(PDO::FETCH_OBJ);

									$comptValeur=$resultValeur->rowCount();
									
									if($comptValeur!=0)
									{
										$v=0;
										while($ligneValeur=$resultValeur->fetch())
										{
										?>
											
											<table class="printPreview tablesorter3" cellspacing="0" style="width:100%;">
												<tr>
													<td style="text-align:center;">
													<?php 
													/* if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL)
													{ */
													?>
														<span type="text" id="valeur<?php echo $v;?>" name="valeur[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->valeur !="" OR $ligneValeur->valeur !=NULL){ echo $ligneValeur->valeur;}else{ echo '---';}?></span>
													<?php 
													// }
													
													if($ligneValeur->min_valeur !="" OR $ligneValeur->max_valeur !="")
													{
													?>
													( 
													<span type="text" id="min<?php echo $v;?>" name="min[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->min_valeur !=""){ echo $ligneValeur->min_valeur;}?></span> 
													- 
													<span type="text" id="max<?php echo $v;?>" name="max[]" style="height:15px; width:130px; font-weight:bold; font-size:10px;"><?php if($ligneValeur->max_valeur !=""){ echo $ligneValeur->max_valeur;}?></span> )
													<?php
													}
													?>
													</td>						
												</tr>						
											</table>						
									<?php
											$v++;
										}
									}
									$fichierjoint='';
									?>
									</td>
												
									<td style="text-align:center;">
									<?php
									if($ligneLaboPa->resultats!="")
									{
									?>
										<span><?php echo 'Un fichier a été joint sur ces résultats';?></span>
									<?php
									}
									?>
									</td>
										
									<td style="text-align:center;font-weight:normal;"><?php echo date('d-M-Y',strtotime($ligneLaboPa->datehosp));?></td>

									<td style="text-align:center;">
									<?php
									$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') or die( print_r($connexion->errorInfo()));
									$resultatsMed->execute(array(
									'idMed'=>$ligneLaboPa->id_uM
									));

									$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
										
									if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
									{
									?>	
										<?php echo $ligneMed->full_name;?>
																
									<?php
									}
									$resultatsMed->closeCursor();
									?>						
									</td>
										
									<td style="text-align:center;font-weight:normal;">
									<?php
									$idLabo=$ligneLaboPa->id_uL;
									
									$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
									$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
									if( $ligneLaboId = $resultLaboId->fetch())
									{
										$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
										echo $ligneLaboId->full_name;
				
									}
									
									?>
									</td>
								</tr>
							<?php
								}
								
								$fullnamePaCheck=$fullnamePa;
								$compteur++;
							}
							?>		
						</tbody>
					</table>
				</div>
				<?php
				}else{
				?>
					<table class="tablesorter tablesorter4" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
						<thead>
							<tr>
								<th style="width:12%;text-align:center">No Laboratory(Hosp) Report for this search</th>
							</tr> 
						</thead> 
					</table> 
					
				<?php
				}
				?>
				</div>
		<?php
			}
		?>

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


function OtherDailyExa(fld){

	if(fld="dailyExa")
	{
		var idExa = document.getElementById('dailyIdexaPerso').value;
		
		if(idExa==0)
		{
			document.getElementById('dailyexaPerso').style.display='inline';
		}else{
			document.getElementById('dailyexaPerso').style.display='none';
		}
	}
	
	if(fld="monthlyExa")
	{
		var idExa = document.getElementById('monthlyIdexaPerso').value;
		
		if(idExa==0)
		{
			document.getElementById('monthlyexaPerso').style.display='inline';
		}else{
			document.getElementById('monthlyexaPerso').style.display='none';
		}
	}
	
	if(fld="annualyExa")
	{
		var idExa = document.getElementById('annualyIdexaPerso').value;
		
		if(idExa==0)
		{
			document.getElementById('annualyexaPerso').style.display='inline';
		}else{
			document.getElementById('annualyexaPerso').style.display='none';
		}
	}
	
	if(fld="customExa")
	{
		var idExa = document.getElementById('customIdexaPerso').value;
		
		if(idExa==0)
		{
			document.getElementById('customexaPerso').style.display='inline';
		}else{
			document.getElementById('customexaPerso').style.display='none';
		}
	}
}
</script>

<?php
	
	}else{
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
		
	}
?>
	
	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	
	<script type="text/javascript">
	
		$('#dailyIdexaPerso').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#monthlyIdexaPerso').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#annualyIdexaPerso').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#customIdexaPerso').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	
</body>

</html>