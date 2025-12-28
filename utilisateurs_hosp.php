<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");

?>

<!doctype html>
<html lang="en">
<noscript>
	Cette page requiert du Javascript.
	Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
	<title><?php echo getString(4);?></title>
	
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

	
	<script type="text/javascript">
 
function controlFormUser(theForm){
	var rapport="";
	
	rapport +=controlAdhesion(theForm.anneeAd);
	rapport +=controlAdhesionToday(theForm.anneeAd,theForm.today);
	rapport +=controlProfil(theForm.profil);

	
	rapport +=controlDateNaiss(theForm.jours);
	// rapport +=controlProfession(theForm.profession);
	rapport +=compareDateNaissAd(theForm.annee,theForm.mois,theForm.jours,theForm.anneeAd);
	
	if(theForm.assurance.value!=1)
	{
		rapport +=controlcardIDassurance(theForm.cardIDassurance);
		rapport +=controlBill(theForm.bill);
	}
	
	
	rapport +=controlNom(theForm.nom);
	rapport +=controlPrenom(theForm.prenom);
	rapport +=controlProvince(theForm.province);
	
	if(theForm.province.value != 6)
	{
		rapport +=controlDistrict(theForm.district);
		rapport +=controlSecteur(theForm.secteur);
	}else{
		rapport +=controlAutreAdress(theForm.adresseExt);
	}
	
	rapport +=controlPhone(theForm.phone,theForm.profil);
	rapport +=controlEmail(theForm.mail,theForm.profil);
	
	// rapport +=controlAnneeAd(theForm.anneeAd);	
	
		if (rapport != "") {
		alert("Please correct the following errors:\n\n" + rapport);
					return false;
		 }
}


function controlProfil(fld){
	var erreur="";
		
	if(fld.value.trim()==""){
	erreur="The profil\n";
	fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;
}

function controlcardIDassurance(fld){
	var erreur="";
	
	if(fld.value.trim()=="")
	{
		erreur="The insurance card ID\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;			
}

	
function controlBill(fld){
	var erreur="";
	
	if(fld.value.trim()=="")
	{
		erreur="The percentage\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;			
}


function controlDateNaiss(fldJ){
	var erreur="";
	
	if(fldJ.value.trim()=="")
	{
		erreur="The day of birth\n";
		fldJ.style.background="rgba(0,255,0,0.3)";
	}else{
		fldJ.style.background="white";
	}
	
	return erreur;			
}

function compareDateNaissAd(fldA,fldM,fldJ,fldDNA){
	var erreur="";
	var dateNaissance=fldA.value+"-"+fldM.value+"-"+fldJ.value;
	var anneeAdzion=fldDNA.value;
	
	if(fldJ.value.trim()!="")
	{	
		if(dateNaissance>anneeAdzion){
			fldA.style.background="rgba(0,255,0,0.3)";
			fldM.style.background="rgba(0,255,0,0.3)";
			fldJ.style.background="rgba(0,255,0,0.3)";
			fldDNA.style.background="rgba(0,255,0,0.3)";

			erreur="The birth date is superior to today's date.\n";
		}else{
			fldA.style.background="white";
			fldM.style.background="white";
			fldJ.style.background="white";
			fldDNA.style.background="white";
		}
	}
	
	return erreur;
}


function controlNom(fld){
	var erreur="";
	var illegalChar=/[A-Za-z]/;
	if(fld.value.trim()=="")
	{
		erreur="The last name\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		if(fld.value.trim().match(illegalChar))
		{
			erreur="";
		}else{
			erreur="Invalid last name\n";
			fld.style.color="red";
		}
	}
	
	return erreur;
}

 function controlPrenom(fld){
	var erreur="";
	var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	if(fld.value==""){
	erreur="The first name\n";
	fld.style.background="rgba(0,255,0,0.3)";
	}else{
		if(fld.value.trim().match(illegalChar)){
		erreur="Invalid first name\n";
			}
	}
	
	return erreur;
}

function controlProvince(fld){
	var erreur="";
	
	if(fld.value.trim()==""){
	erreur="The province\n";
	fld.style.background="rgba(0,255,0,0.3)";
	} 
	return erreur;	
}  

function controlDistrict(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	
	if(fld.value.trim()==""){
		erreur="The district\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	return erreur;	
}  

function controlAutreAdress(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	
	if(fld.value.trim()==""){
		erreur="The other adress\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
	}
	return erreur;	
}  

function controlSecteur(fld){
	var erreur="";
	
	if(fld.value.trim()==""){
		erreur="The sector\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	return erreur;	
}  


function controlPhone(fld,fld1){
	var erreur="";
	// var illegalChar=/[0+]257[ ]7[1-9][ ]?([0-9]{3}[ ]?){2}/;
	var illegalChar=/[0+]7[1-9][ ]?([0-9][ ]?){7,}/;
	
	if(fld.value.trim().match(illegalChar) || fld.value == "")
	{
		fld.style.color="black";
	}else{
		erreur="Invalid phone number\n";
		fld.style.color="red";
	}
	return erreur;	
}  

function controlEmail(fld,fld1){
	var erreur="";
	var illegalChar=/[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}/;
	
	if(fld.value.trim().match(illegalChar) || fld.value == "")
	{
		fld.style.color="black";
	}else{
		erreur="Invalid mail\n";
		fld.style.color="red";
	}
	return erreur;	
}

	
function controlAdhesion(fld){
	var erreur="";
	
	if(fld.value.trim()==""){
		erreur="The date of assignment\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	return erreur;	
}

function controlAdhesionToday(fld1,fld2){
	var erreur="";
	var anneeAdzion=fld1.value;
	var today=fld2.value;
	
	if(anneeAdzion>today){
	fld1.style.background='yellow';
	fld2.style.background='yellow';

	erreur="The date of assignment is superior to today's date.\n";
	}
	
	return erreur;
}

</script>

</head>

<body onload="myScriptMois()">

<?php

$id=$_SESSION['id'];

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $_SESSION['infhosp']!=0)
{
	if($status==1)
	{
		if(isset($_GET['iduti']))
		{
			$resultats=$connexion->prepare('SELECT *FROM utilisateurs u WHERE u.id_u=:operation;');
			$resultats->execute(array(
			'operation'=>$_GET['iduti']	
			));
			
			
			$comptidU=$resultats->rowCount();	
			
			if($comptidU!=0)
			{
			
				
				$resultatsP=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=:operation AND u.id_u=p.id_u');
				$resultatsP->execute(array(
				'operation'=>$_GET['iduti']	
				));
			
				
				$comptidPa=$resultatsP->rowCount();
				
				
				$resultats->setFetchMode(PDO::FETCH_OBJ);
				while($ligne=$resultats->fetch())
				{
					$nom_uti=$ligne->nom_u;
					$prenom_uti=$ligne->prenom_u;
					$sexe=$ligne->sexe;
					$province=$ligne->province;
					$district=$ligne->district;
					$secteur=$ligne->secteur;
					$adresseExt=$ligne->autreadresse;
					$phone=$ligne->telephone;
					$mail=$ligne->e_mail;
					$password=$ligne->password;
					$site=$_GET['iduti'];
				}
				$resultats->closeCursor();
			
							
				if($comptidPa!=0)
				{
					$resultatsP->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsP->fetch())
					{
						$num=$ligne->numero;
						$referenceid=$ligne->reference_id;
						$anAff=$ligne->anneeadhesion;
						$profession=$ligne->profession;
						$anneeN=$ligne->anneeNaiss;
						$moisN=$ligne->moisNaiss;
						$jourN=$ligne->jourNaiss;
						$dateN=$ligne->date_naissance;
						$bill=$ligne->bill;
						$id_assurance=$ligne->id_assurance;
						$carteassuranceid=$ligne->carteassuranceid;
						$numeropolice=$ligne->numeropolice;
						$adherent=$ligne->adherent;
					}
					$resultatsP->closeCursor();
				}

				
			}
		}else{
			$comptidPa=0;		
		}

		
		if(isset($_GET['num']))
		{
			$resultatsP=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
			$resultatsP->execute(array(
			'operation'=>$_GET['num']	
			));
			
			$comptidPa=$resultatsP->rowCount();
			
			if($comptidPa!=0)
			{
				$resultatsP->setFetchMode(PDO::FETCH_OBJ);
				while($ligne=$resultatsP->fetch())
				{
					$nom_uti=$ligne->nom_u;
					$prenom_uti=$ligne->prenom_u;
					$sexe=$ligne->sexe;
					$dateN=$ligne->date_naissance;
					$poids=$ligne->poidsPa;
					$temperature=$ligne->temperaturePa;
					$tensionart=$ligne->tensionarteriellePa;
					
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
				$resultatsP->closeCursor();
			}

		}

?>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li class="">			
					<form method="post" action="utilisateurs.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ', '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="utilisateurs.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="utilisateurs.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['infhosp']))
{
	$infhosp=$_SESSION['infhosp'];
	
	if($infhosp!=0)
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
	}
}
?>

<div class="account-container" style="width:70%">

	<div id='cssmenu' style="text-align:center">
	<ul>
		<li style="width:50%;"><a href="patients1_hosp.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients hospitalisation"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients hospitalisation</a></li>
		
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
		
		<!--<li><a onclick="ShowList('Items')" data-title="Manage Items">Manage Items</a></li>-->
	</ul>

	<ul style="margin-top:20px;background:none;border:none;">

		<div id="divMenuUser" style="display:inline-block;">
		<?php
		if(isset($_GET['iduti']))
		{
		?>
			<a href="utilisateurs_hosp.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" id="newUser"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(88);?></a>
		<?php
		}
		?>
			<a onclick="ShowList('Liste')" id="listOn" class="btn-large" style="display:none;"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(55);?></a>
			
			<span onclick="ShowList('ListeNon')" id="listOff" class="btn-large" style="display:none;"><i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo getString(56);?></span>
		
		</div>

	</ul>

		<div style="display:none;" id="divMenuMsg">

			<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><?php echo getString(57);?></a>
			
			<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><?php echo getString(58);?></a>
			
			<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><?php echo getString(59);?></a>

		</div>
		
	</div>

		
	<div style="background:#F8F8F8; margin-top:10px; padding:10px 50px; text-align:center;">

		<?php 
		if(!isset($_GET['num']))
		{
			if(isset($_SESSION['infhosp']))
			{
				if(!isset($_GET['iduti']))
				{
		?>
					<h2 style="margin-bottom:40px;"><?php echo getString(196);?></h2>
				<?php 
				}else{
				?>
					<h2 style="margin-bottom:40px;"><?php echo getString(197);?></h2>
		<?php
				}
			}
		
		}else{
			if(isset($_GET['num']))
			{
		?>
				<h2><?php echo getString(74) ?></h2>
		<?php
			}
		}
		?>

	<form method="post" action="traitement_utilisateurs.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormUser(this)" enctype="multipart/form-data" class="formlogin">

		<table  style="margin-left:87px; margin-bottom: 25px;">
		<tr>
			<?php 
			if(!isset($_GET['num']))
			{
			?>
			<td>
				<span><?php echo getString(6);?></span>
			</td>
			<td>
				<?php 
				if(isset($_SESSION['infhosp']))
				{
				?>
					<input type="text" name="profil" value='<?php echo getString(20);?>' id="Patient" readonly="readonly">
					
				<?php
				}
				?>	
						
			</td>
			<?php 
			}
			?>	
		</tr>
		</table>

		<div class="add-user">

			<table>

			<?php 
			if(isset($_SESSION['infhosp']))
			{
			?>

			<tr>
				<td><label for="num"><?php echo getString(7);?></label></td>
				<td style="text-align:left">
					<input type="text" name="num" id="num" size="10px" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $num;}else{ echo showSN('P'); }?>" readonly="readonly" />
				</td>
			</tr>
			
			<tr>
				<td><label for="referenceid"><?php echo 'N° de référence';?></label></td>
				<td style="text-align:left">
					<input type="text" name="referenceid" id="referenceid" size="10px" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $referenceid;}else{ echo ''; }?>"/>
				</td>
			</tr>

			<?php 
			}
			?>

			<tr>
				<td><label for="nom"><?php echo getString(9);?></label></td>
				<td style="text-align:left">
					<input type="text" name="nom" id="nom" onfocus="ShowHelp('nom')" value="<?php if(isset($_GET['iduti'])){ echo $nom_uti;}else{echo '';}?>" placeholder="<?php echo getString(50);?>"/><span style='color:black;font-weight:bold'> *</span>
				</td>
			</tr>

			<tr>
				<td><label for="prenom"><?php echo getString(10);?></label></td>
				<td style="text-align:left">
					<input type="text" name="prenom" id="prenom" onfocus="ShowHelp('prenom')" value="<?php if(isset($_GET['iduti'])){ echo $prenom_uti;}else{echo '';}?>" placeholder="<?php echo getString(51);?>"/><span style='color:black;font-weight:bold'> *</span>
				</td>
			</tr>

			<tr>
				<td><?php echo getString(11);?></td>
				
				<td style="text-align:center">
					<i class="fa fa-mars fa-fw"></i><?php echo getString(12);?><input style="margin: 30px 50px 30px 10px" type="radio" name="sexe" value="M" id="M" <?php  if(isset($_GET['iduti']) AND $sexe=="M"){?> checked="checked" <?php }?> data-color="green" required/>

					<i class="fa fa-venus fa-fw"></i><?php echo getString(13);?><input type="radio" name="sexe" value="F" id="F" <?php  if(isset($_GET['iduti']) AND $sexe=="F"){?> checked="checked" <?php }?>/>
				</td>
			</tr>


			<?php 
			if(isset($_SESSION['infhosp']))
			{
			?>

			<tr>
				<td><?php echo getString(8);?></td>
				
				<td style="text-align:left">
					<select name="annee" id="annee" style="width:100px;" onchange="myScriptAnnee()">
						<?php
						for($a=1920;$a<=2020;$a++)
						{
						?>
							<option value="<?php echo $a;?>" <?php if(!isset($_GET['iduti']) AND date('Y')==$a) echo 'selected="selected"'; if(isset($_GET['iduti']) AND $comptidPa!=0 AND $anneeN==$a) echo 'selected="selected"';?>>
							<?php echo $a;?>
							</option>
						<?php
						}
						?>
					</select>
					
					<select name="mois" id="mois" style="width:120px;" onclick="myScriptMois()">
						<?php
						for($m=1;$m<=12;$m++)
						{
							$moisString=date("F",mktime(0,0,0,$m,10));
						?>
							<option value="<?php echo $m;?>" <?php if(!isset($_GET['iduti']) AND date('F')==$moisString) echo 'selected="selected"'; if(isset($_GET['iduti']) AND $comptidPa!=0 AND date("F",mktime(0,0,0,$moisN,10))==$moisString) echo 'selected="selected"'; ?>>
							<?php 
								echo $moisString;
							?>
							</option>
						<?php
						}
						?>
					</select>
					
					
					<select name="jours" id="jours" style="width:80px;">
						
						<option value=""></option>
						
					</select>
						
					<input size="25px" type="hidden" id="dateN" name="dateNaiss" onclick="ds_sh(this);" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $dateN;}else{echo '';}?>"/><span style="color:black;font-weight:bold"> *</span>
				</td>
			</tr>

			<tr>
				<td><label for="profession"><?php echo getString(33);?></label></td>
				<td style="text-align:left">
					<input type="text" name="profession" id="profession" onclick="ShowHelp('profession')" placeholder="<?php echo getString(79);?>" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $profession;}else{echo '';}?>"/>
				</td>
			</tr>

			<tr>
				<td><label for="assurance"><?php echo getString(76);?></label></td>
				<td style="text-align:left">
				<select name="assurance" id="assurance" onchange="ShowAssurance('assurance')">
				
					<option value='1' id="noinsurance" <?php if(isset($_GET['iduti']) and $id_assurance == 1){echo "selected='selected'";}?>>
					<?php echo getString(78);?>
					</option>
				<?php

					$resultats=$connexion->query('SELECT *FROM assurances WHERE id_assurance!=1 ORDER BY nomassurance');
					while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
					{
				?>
					<option value='<?php echo $ligne->id_assurance;?>' id="insurance"  <?php if(isset($_GET['iduti']) and $id_assurance == $ligne->id_assurance){echo "selected='selected'";}?>>
					<?php echo $ligne->nomassurance;?>
					</option>
				<?php
					}
				?>
				</select>
				</td>
			</tr>

			</table>

			<table id="insuranceIDbill" style="<?php if(isset($_GET['iduti']) and $id_assurance != 1){ ?>display:inline-block;<?php }else{ ?>display:none;<?php }?>margin-left:114px">

				<tr>		
					<td style="width:120px;"><label for="cardIDassurance"><?php echo getString(77);?></label></td>

					<td>
						<input style="width:315px" type="text" name="cardIDassurance" id="cardIDassurance" placeholder="Enter the insurance card ID" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $carteassuranceid;}else{echo '';}?>"/>
					</td>
				</tr>
				
				<tr>
					<td style="width:120px;"><label for="numeropolice"><?php echo getString(198);?></label></td>
					
					<td>
						<input style="width:315px" type="text" name="numeropolice" id="numeropolice" onclick="ShowHelp('numeropolice')" placeholder="<?php echo getString(200);?>" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $numeropolice;}?>"/>
					</td>
				</tr>

				<tr>
					<td><label for="bill"><?php echo getString(38);?></label></td>
					<td style="text-align:left">
						<input type="text" name="bill" id="bill" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $bill;}else{echo '';}?>" style="width:25px"/> %
					</td>
				</tr>

				<tr>
					<td style="width:120px;"><label for="adherent"><?php echo getString(199);?></label></td>
					
					<td>
						<input style="width:315px" type="text" name="adherent" id="adherent" onclick="ShowHelp('adherent')" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $adherent;}else{echo getString(201);}?>"/>
					</td>
				</tr>

			</table>
				

			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; width:430px;">
			<tr>
			<td style="padding:5px;" id="ds_calclass"></td>
			</tr>
			</table>
			

			<?php 
			}
			?>


			<?php

			if(!isset($_GET['iduti']))
			{
			?>

				<table>
				<tr>
					<td><?php echo getString(41);?></td>
					
					<td style="text-align:left">
						<select name="province" id="province" onchange="ShowAdresse('District')">
						<option value=""><?php echo getString(84);?></option>
						<?php

							$resultats=$connexion->query('SELECT *FROM province ORDER BY nomprovince');
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
							{
						?>
							<option value='<?php echo $ligne->id_province; ?>'>
							<?php echo $ligne->nomprovince;?>
							</option>
						<?php
							}
							
						?>
						</select><span style='color:black;font-weight:bold'> *</span>
						<script src="jQuery.js"></script>
						<script>
						$(function(){
							$("#province").change(function(){
						var provinceChoisi='province='+$(this).val();
							//alert(provinceChoisi);
							$.ajax({
								url:"provDisSect.php",
								type:"POST",
								data:provinceChoisi,
								
								success:function(resultat)
								{									
									// alert(resultat);
									$('#district').html(resultat);
								}
								});
							});
						});
						</script>
						
					</td>
				</tr>

				</table>

				<table id="distri" style="display:none; margin-left:185px;">

				<tr>
					<td style="width:50px"><?php echo getString(42);?></td>
					<td>		
						<select style="width:340px;" name="district" id="district" onchange="ShowAdresse('Secteur')">
							<option value=""><?php echo getString(52);?></option>
						
						</select><span style='color:black;font-weight:bold'> *</span>
					</td>	
						
				</tr>
				<script>

					$(function(){
						$("#district").change(function(){
					var districtChoisi='district='+$(this).val();
						//alert(districtChoisi);
						$.ajax({
							url:"provDisSect.php",
							type:"POST",
							data:districtChoisi,
							
							success:function(resultat)
							{
								
								//alert(resultat);
								$('#secteur').html(resultat);
							}
							});
						});
					});

				</script>
				</table>

				<table id="autreAdress" style="display:none; margin-left:185px;">

				<tr>
					<td style="width:100px"><?php echo "Autre adresse";?></td>
					<td>		
						<input type='text' name='adresseExt' id='adresseExt' value='' style="width:300px"/><span style='color:black;font-weight:bold'> *</span>
					</td>	
						
				</tr>
				
				</table>

				<table id="sect" style="display:none; margin-left:185px;">
				<tr>
					<td style="width:50px;"><?php echo getString(43);?></td>
					<td>
						<select style="width:340px;" name="secteur" id="secteur">
						<option value=""><?php echo getString(53);?></option>
						
						</select><span style='color:black;font-weight:bold'> *</span>
					</td>
				</tr>

				</table>

			<?php 
			}else{
			?>
				
				<table>
				<tr>
					<td style="width:20%"><?php echo getString(41);?></td>
					<td style="text-align:left">
						<select name="province" id="province" onchange="ShowAdresse('District')">
						<option value=""><?php echo getString(84);?></option>
						<?php

							$resultats=$connexion->query('SELECT *FROM province ORDER BY nomprovince');
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
							{
						?>
							<option value='<?php echo $ligne->id_province; ?>'<?php if($province == $ligne->id_province){ echo 'selected="selected"';}?>>
							<?php echo $ligne->nomprovince;?>
							</option>
						<?php
							}
							
						?>
						</select><span style='color:black;font-weight:bold'> *</span>
						<script src="jQuery.js"></script>
						<script>
						$(function(){
							$("#province").change(function(){
						var provinceChoisi='province='+$(this).val();
							//alert(provinceChoisi);
							$.ajax({
								url:"provDisSect.php",
								type:"POST",
								data:provinceChoisi,
								
								success:function(resultat)
								{
									
									// alert(resultat);
									$('#district').html(resultat);
								}
								});
							});
						});
						</script>
						
					</td>
				</tr>

				</table>
				
				<table>
				<tr>
					<td style="width:20%"><?php echo getString(42);?></td>
					<td style="text-align:left">
						<select name="district" id="district" onchange="ShowAdresse('District')">
						<option value=""><?php echo getString(52);?></option>
						<?php

							$resultats=$connexion->prepare('SELECT * FROM district WHERE id_province=:prov');
							$resultats->execute(array(
							'prov'=>$province
							));
							
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
							{
						?>
							<option value='<?php echo $ligne->id_district; ?>'<?php if($district == $ligne->id_district){ echo 'selected="selected"';}?>>
							<?php echo $ligne->nomdistrict;?>
							</option>
						<?php
							}
						?>
						</select><span style='color:black;font-weight:bold'> *</span>
						<script src="jQuery.js"></script>
						
						<script>
						$(function(){
							$("#district").change(function(){
						var districtChoisi='district='+$(this).val();
							//alert(districtChoisi);
							$.ajax({
								url:"provDisSect.php",
								type:"POST",
								data:districtChoisi,
								
								success:function(resultat)
								{
									
									//alert(resultat);
									$('#secteur').html(resultat);
								}
								});
							});
						});

					</script>
						
					</td>
				</tr>

				</table>

				<table>
				<tr>
					<td style="width:20%"><?php echo getString(43);?></td>
					<td style="text-align:left">
						<select name="secteur" id="secteur" onchange="ShowAdresse('District')">
						<option value=""><?php echo getString(53);?></option>
						<?php

							$resultats=$connexion->prepare('SELECT * FROM sectors WHERE id_district=:distri');
							$resultats->execute(array(
							'distri'=>$district
							));
							
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
							{
						?>
							<option value='<?php echo $ligne->id_sector; ?>'<?php if($secteur == $ligne->id_sector){ echo 'selected="selected"';}?>>
							<?php echo $ligne->nomsector;?>
							</option>
						<?php
							}
						?>
						</select><span style='color:black;font-weight:bold'> *</span>
						
					</td>
				</tr>

				</table>
				
				<table id="autreAdress" style="<?php if($adresseExt==""){ echo 'display:none;margin-left:185px;';}else{ echo 'display:inline-block;margin-left:185px;';}?>">

				<tr>
					<td><?php echo "Autre adresse";?></td>
					<td>		
						<input type='text' name='adresseExt' id='adresseExt' value='<?php echo $adresseExt;?>'/>
					</td>	
						
				</tr>
				
				</table>

				
			<?php 
			}
			?>


			<?php 
			if(!isset($_GET['num']))
			{
			?>
				<table style="width:100%;">
				<tr>
					<td><label for="phone"><?php echo getString(15);?></label></td>
					<td style="text-align:left">
						<input type="text" name="phone" id="phone" onfocus="ShowHelp('phone')" placeholder="<?php echo getString(83);?>" value="<?php if(isset($_GET['iduti'])){ echo $phone;}else{echo '';}?>"/>
					</td>
					
					<td style="text-align:left">	
						<span style="color:black;display:none;" id="pn"><strong><?php echo '10 chiffres minimum (e.g : 07xxxxxxxx)';?></strong></span>
					</td>
				</tr>

				<tr>
					<td><label for="mail"><?php echo getString(16);?></label></td>
					<td style="text-align:left">
						<input type="text" name="mail" id="mail" onfocus="ShowHelp('mail')" placeholder="<?php echo getString(54);?>" value="<?php if(isset($_GET['iduti'])){ echo $mail;}else{echo '';}?>"/>
					</td>
					
					<td>
						<span style="color:black;display:none;" id="em"><strong><?php echo 'Suivre l\'exemple (e.g : email@exemple.com)';?></strong></span>
					</td>
				</tr>

				<tr>
					<td><?php echo getString(18);?></td>
					
					<td style="text-align:left">
						<input size="25px" type="text" id="anneeAd" name="anAd" onclick="ds_sh(this);" value="<?php if(isset($_GET['iduti'])){ echo $anAff;}else{ $annee = date('Y').'-'.date('m').'-'.date('d'); echo $annee;}?>"/>
						
						<input size="25px" type="hidden" id="today" name="today" value="<?php  $annee = date('Y').'-'.date('m').'-'.date('d'); echo $annee;?>"/>
					</td>
				</tr>

				</table>
				
				
				<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; width:430px;">
				<tr>
				<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
				</table>
				

				<table>
				<tr> 
					<td><input type="hidden" name="idopere" value="<?php if(isset($_GET['iduti'])){ echo $site;}else{echo '';}?>" readonly/></td>
				</tr>  
				</table>

				<button type="submit" class="btn-large" name="savebtn" style="margin-left:-150px; font-size:20px;height:40px;margin-top:20px;width:30%;<?php if(isset($_GET['iduti'])){ echo 'display:none;';}?>">
					<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(28);?>
				</button>

			<?php 
			}
			?>

			<?php 

			if(isset($_GET['iduti']) AND !isset($_GET['num']))
			{
			?>
				<input type="submit" class="btn-large" name="updatebtn" value="<?php echo getString(28);?>" style="width:250px; margin-top:20px;"/>
				<input type="reset" class="btn-large" name="refreshbtn" value="Reset" style="width:150px; margin-top:20px;"/>
			<?php
			}
			?>
	

		</form>
			
				<a class="btn-large-inversed" style="width: 150px; margin-top: 20px; position: absolute; height: 28px; margin-left: 20px;" href="<?php if(isset($_SESSION['infhosp'])){ echo 'patients1_hosp.php?';}?><?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>">
					<i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?>
				</a>
		
		</div>

	
	<?php
		
	}else{
		echo '<script language="javascript"> alert("You\'ve been disabled!!\n Ask the administrator to enable you");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
}else{
	echo '<script language="javascript">document.location.href="index.php"</script>';
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

	</div>

</div>

<div>
	<footer style="bottom: 0px; width: 100%; text-align: center; background: #fff; border-top: 1px solid #eee; padding: 10px 0px; vertical-align: middle;">
		<p style="margin:0"><span style="color:#a00000">Clinic Plus®</span> is a product of <span style="font-style:bold;">CodeBlock</span>. ©2015-2016 All rights reserved.</p>
	</footer>
</div>

	<script type="text/javascript">
	
		function myScriptAnnee()
	   {
		   var i;
		   var test = [];
		   var annee = $('#annee').val();
		   var mois = $('#mois').val();
		   var jours = new Date(annee, mois , 0).getDate();
		   $('#jours').empty();
		   for(i = 1; i<= jours; i++)
		   {
				test[i-1] = i;
				$('#jours').append('<option value="' + i + '">'
						+ i + '</option>');
		   }
	   }
		
		function myScriptMois()
	   {
		   var i;
		   var test = [];
		   var annee = $('#annee').val();
		   var mois = $('#mois').val();
		   var jours = new Date(annee, mois , 0).getDate();
		   $('#jours').empty();
		   for(i = 1; i<= jours; i++)
		   {
				test[i-1] = i;
				$('#jours').append('<option value="' + i + '">'
						+ i + '</option>');
		   }
	   }
	    
	</script>
</body>

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


	function ShowHelp(help)
	{
		// var assurance=document.getElementById('assurance').value;
		
		if( help == "mail"){
			document.getElementById('em').style.display='inline-block';
		}else{
			document.getElementById('em').style.display='none';
		}
		
		if( help == "phone"){
			document.getElementById('pn').style.display='inline-block';
		}else{
			document.getElementById('pn').style.display='none';
		}
	}


	
	function ShowAssurance(assure)
	{
		var assurance=document.getElementById('assurance').value;
		
		if( assurance == 1){
			document.getElementById('insuranceIDbill').style.display='none';
		}else{
			document.getElementById('insuranceIDbill').style.display='inline-block';
		}
	}

	
	function ShowAdresse(adresse)
	{
		if( adresse =='District')
		{
			var province=document.getElementById('province').value;
			
			if( province =='')
			{			
				document.getElementById('distri').style.display='none';
				document.getElementById('sect').style.display='none';
				document.getElementById('autreAdress').style.display='none';
			
			}else if(province == 6){
				document.getElementById('autreAdress').style.display='inline-block';
				document.getElementById('distri').style.display='none';
				document.getElementById('sect').style.display='none';
			}else{				
				document.getElementById('distri').style.display='inline-block';
				document.getElementById('autreAdress').style.display='none';
			}
		}
		
		if( adresse =='Secteur')
		{
			var district=document.getElementById('district').value;
			
			if( district =='')
			{			
				document.getElementById('sect').style.display='none';
			}else{
				document.getElementById('sect').style.display='inline-block';
			}
		}

	}

	

	function ShowAdd()
	{		
		
		var service=document.getElementById('service').value;
		
		if (service=='12')
		{
			document.getElementById('divAdd').style.display='none';
		}else{
			if (service=='')
			{
				document.getElementById('divAdd').style.display='none';
			}else{
				document.getElementById('divAdd').style.display='inline-block';
			}
		}
	}

	function ShowList(list)
	{
		if( list =='Users')
		{
			document.getElementById('divMenuUser').style.display='inline-block';
			
			document.getElementById('listOn').style.display='inline';
			<?php
			if(isset($_GET['iduti']))
			{
			?>
			document.getElementById('newUser').style.display='inline';
			<?php
			}
			?>
			document.getElementById('listOff').style.display='none';			
			document.getElementById('divMenuMsg').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divMenuMsg').style.display='inline-block';
			document.getElementById('divMenuUser').style.display='none';
			
		}
		
		if( list =='Liste')
		{
			document.getElementById('listOff').style.display='inline';
			document.getElementById('listOn').style.display='none';
		}
		
		if( list =='ListeNon')
		{
			document.getElementById('listOn').style.display='inline';
			document.getElementById('listOff').style.display='none';
		}
		
		if( list =='MsgRecu')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='inline-block';
			document.getElementById('EnvoiMsg').style.display='inline-block';
			document.getElementById('MsgEnvoye').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
		if( list =='MsgEnvoye')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('MsgEnvoye').style.display='none';
			document.getElementById('EnvoiMsg').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='inline-block';
			document.getElementById('envoye').style.display='inline-block';
		}
		
		if( list =='EnvoiMsg')
		{
			document.getElementById('formMsg').style.display='inline-block';
			document.getElementById('MsgEnvoye').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='inline-block';
			document.getElementById('EnvoiMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
	}


	function controlFormPassword(theForm){
		var rapport="";
		
		rapport +=controlPass(theForm.Pass);

			if (rapport != "") {
			alert("Please check the following fields:\n" + rapport);
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



	</script>
</html>
