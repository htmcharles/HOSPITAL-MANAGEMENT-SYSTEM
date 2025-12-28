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
		
	if(theForm.profil.value == "Medecin")
	{
		rapport +=controlGrade(theForm.grade);
	}
		
	if(theForm.profil.value == "Infirmier")
	{
		rapport +=controlAcces(theForm.clinik,theForm.hosp);
	}
	
	rapport +=controlAdhesion(theForm.anneeAd);
	rapport +=controlAdhesionToday(theForm.anneeAd,theForm.today);
	rapport +=controlProfil(theForm.profil);

	if(theForm.profil.value=="Patient")
	{
		rapport +=controlDateNaiss(theForm.jours);
		// rapport +=controlProfession(theForm.profession);
		rapport +=compareDateNaissAd(theForm.annee,theForm.mois,theForm.jours,theForm.anneeAd);

		
		if(theForm.assurance.value!=1)
		{
			rapport +=controlcardIDassurance(theForm.cardIDassurance);
			rapport +=controlBill(theForm.bill);
		}
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
		
	if(theForm.profil.value != "Patient")
	{
		rapport +=controlPassword(theForm.password);
	}
	
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
	}else{
		fld.style.background="white";
	}
	
	return erreur;
}

function controlGrade(fld){
	var erreur="";
		
	if(fld.value.trim()==""){
		erreur="The grade\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
	}
	
	return erreur;
}

function controlAcces(fld1,fld2){
	var erreur="";
	
	if(fld1.checked || fld2.checked)
	{}else{
	
		erreur="Cocher une des cases (Donner un accès)\n";
		erreur +="- Accès à la clinic\n";
		erreur +="- Accès à l'hospitalisation\n";
		fld1.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;
}

/* 

function controlProfession(fld){
	var erreur="";
	var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	if(fld.value==""){
		erreur="The affiliate company\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;	
}

*/	

function controlcardIDassurance(fld){
	var erreur="";
	
	if(fld.value.trim()=="")
	{
		erreur="The insurance card ID\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
	}
	
	return erreur;			
}

	
function controlBill(fld){
	var erreur="";
	
	if(fld.value.trim()=="")
	{
		erreur="The percentage\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
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
	var mois = fldM.value;
	var jour = fldJ.value;
	
	if(fldM.value<10){
		mois = '0'+fldM.value;
	}
	if(fldJ.value<10){
		jour = '0'+fldJ.value;
	}
	
	var dateNaissance=fldA.value+"-"+mois+"-"+jour;
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
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	if(fld.value.trim()==""){
		erreur="The province\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
	}
	return erreur;	
}  

function controlDistrict(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	
	if(fld.value.trim()==""){
		erreur="The district\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
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
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	if(fld.value.trim()==""){
		erreur="The sector\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
	}
	return erreur;	
}  


function controlPhone(fld,fld1){
	var erreur="";
	// var illegalChar=/[0+]257[ ]7[1-9][ ]?([0-9]{3}[ ]?){2}/;
	var illegalChar=/[0+]7[1-9][ ]?([0-9][ ]?){7,}/;
	
	if(fld1.value != "Patient")
	{
		if(fld.value.trim()!="")
		{
			if(fld.value.trim().match(illegalChar))
			{
				
			}else{
				erreur="Invalid phone number\n";
				fld.style.color="red";
			}
		}
	}else{
		if(fld.value.trim().match(illegalChar) || fld.value == "")
		{
			fld.style.color="black";
		}else{
			erreur="Invalid phone number\n";
			fld.style.color="red";
		}
	}
	return erreur;	
}  

function controlEmail(fld,fld1){
	var erreur="";
	var illegalChar=/[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}/;
	
	if(fld1.value != "Patient")
	{
		
		if(fld.value.trim()!="")
		{
			if(fld.value.trim().match(illegalChar))
			{
				
			}else{
				erreur="Invalid e-mail\n";
				fld.style.color="red";
			}
		}

	}else{
		if(fld.value.trim().match(illegalChar) || fld.value == "")
		{
			fld.style.color="black";
		}else{
			erreur="Invalid mail\n";
			fld.style.color="red";
		}
	}
	return erreur;	
}
	
function controlPassword(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	if(fld.value.trim()==""){
		erreur="The password\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
	}
	
	return erreur;	
}
	
function controlAdhesion(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	if(fld.value.trim()==""){
		erreur="The date of assignment\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}else{
		fld.style.background="white";
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

if($connect==true AND (isset($_SESSION['codeC']) or isset($_SESSION['codeR'])))
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
			
				$resultatsD=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m, grades g, categopresta cp, servicemed sm WHERE u.id_u=:operation AND u.id_u=m.id_u and g.id_grade=cp.id_grade and sm.id_categopresta=cp.id_categopresta and sm.codemedecin=m.codemedecin');
				$resultatsD->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsP=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=:operation AND u.id_u=p.id_u');
				$resultatsP->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsI=$connexion->prepare('SELECT *FROM utilisateurs u, infirmiers i WHERE u.id_u=:operation AND u.id_u=i.id_u');
				$resultatsI->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsL=$connexion->prepare('SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=:operation AND u.id_u=l.id_u');
				$resultatsL->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsX=$connexion->prepare('SELECT *FROM utilisateurs u, radiologues x WHERE u.id_u=:operation AND u.id_u=x.id_u');
				$resultatsX->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsM=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=:operation AND u.id_u=c.id_u');
				$resultatsM->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsR=$connexion->prepare('SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=:operation AND u.id_u=r.id_u');
				$resultatsR->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsC=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=:operation AND u.id_u=c.id_u');
				$resultatsC->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsA=$connexion->prepare('SELECT *FROM utilisateurs u, auditors a WHERE u.id_u=:operation AND u.id_u=a.id_u');
				$resultatsA->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsAcc=$connexion->prepare('SELECT *FROM utilisateurs u, accountants acc WHERE u.id_u=:operation AND u.id_u=acc.id_u');
				$resultatsAcc->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$resultatsO=$connexion->prepare('SELECT *FROM utilisateurs u, orthopedistes o WHERE u.id_u=:operation AND u.id_u=o.id_u');
				$resultatsO->execute(array(
				'operation'=>$_GET['iduti']	
				));
				
				$comptidDoc=$resultatsD->rowCount();	
				$comptidPa=$resultatsP->rowCount();
				$comptidInf=$resultatsI->rowCount();
				$comptidLabo=$resultatsL->rowCount();
				$comptidRadio=$resultatsX->rowCount();
				$comptidMan=$resultatsM->rowCount();
				$comptidRec=$resultatsR->rowCount();
				$comptidCash=$resultatsC->rowCount();
				$comptidAudit=$resultatsA->rowCount();
				$comptidAcc=$resultatsAcc->rowCount();
				$comptidO=$resultatsO->rowCount();
				
				
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
					$dataM=$ligne->datamanager;
					$site=$_GET['iduti'];
				}
				$resultats->closeCursor();
			
			
				if($comptidDoc!=0)
				{
					$resultatsD->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsD->fetch())
					{
						$codeM=$ligne->codemedecin;
						$grade=$ligne->nomgrade;
						$idcategopresta=$ligne->id_categopresta;
						$service=$ligne->nomcategopresta;
						$anAff=$ligne->dateaffectationmed;
					}
					$resultatsD->closeCursor();
				}
				
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

				if($comptidInf!=0)
				{
					$resultatsI->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsI->fetch())
					{
						$codeInfirm=$ligne->codeinfirmier;
						$infhosp=$ligne->inf_hosp;
						$anAff=$ligne->dateaffectationinf;
					}
					$resultatsI->closeCursor();
				}
				
				if($comptidLabo!=0)
				{
					$resultatsL->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsL->fetch())
					{
						$codeLaboran=$ligne->codelabo;
						$anAff=$ligne->dateaffectationlabo;
					}
					$resultatsL->closeCursor();
				}
				
				if($comptidRadio!=0)
				{
					$resultatsX->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsX->fetch())
					{
						$codeRadiolo=$ligne->coderadio;
						$anAff=$ligne->dateaffectationradio;
					}
					$resultatsX->closeCursor();
				}
				
				if($comptidMan!=0)
				{
					$resultatsM->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsM->fetch())
					{
						$codeCoordi=$ligne->codecoordi;
						$anAff=$ligne->dateaffectationcoordi;
					}
					$resultatsM->closeCursor();
				}
				
				if($comptidRec!=0)
				{
					$resultatsR->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsR->fetch())
					{
						$codereceptio=$ligne->codereceptio;
						$codeC=$ligne->codeC;
						$anAff=$ligne->dateaffectationreceptio;
					}
					$resultatsR->closeCursor();
				}
				
				if($comptidCash!=0)
				{
					$resultatsC->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsC->fetch())
					{
						$codecashier=$ligne->codecashier;
						$codeR=$ligne->codeR;
						$anAff=$ligne->dateaffectationcash;
					}
					$resultatsC->closeCursor();
				}
				
				if($comptidAudit!=0)
				{
					$resultatsA->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsA->fetch())
					{
						$codeaudit=$ligne->codeaudit;
						$anAff=$ligne->dateaffectationaudit;
					}
					$resultatsA->closeCursor();
				}
				
				if($comptidAcc!=0)
				{
					$resultatsAcc->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsAcc->fetch())
					{
						$codeacc=$ligne->codeaccount;
						$anAff=$ligne->dateaffectationaccount;
					}
					$resultatsAcc->closeCursor();
				}
				
				if($comptidO!=0)
				{
					$resultatsO->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatsO->fetch())
					{
						$codeo=$ligne->codeortho;
						$anAff=$ligne->dateaffectationortho;
					}
					$resultatsO->closeCursor();
				}
				
			}
		}else{
				
				$comptidDoc=0;	
				$comptidPa=0;
				$comptidInf=0;
				$comptidLabo=0;
				$comptidRadio=0;
				$comptidMan=0;
				$comptidRec=0;
				$comptidCash=0;
				$comptidAudit=0;
				$comptidAcc=0;
				$comptidO=0;
			
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
					$anneeN=$ligne->anneeNaiss;
					$moisN=$ligne->moisNaiss;
					$jourN=$ligne->jourNaiss;
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
					<form method="post" action="utilisateurs.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ', '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="utilisateurs.php?english=english<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="utilisateurs.php?francais=francais<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['codeR']) AND isset($_SESSION['codeCash']))
{
	if(isset($_GET['receptioniste']))
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="paCashbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Caisse';?>
		</a>

	</div>
	<?php
	}else{
	?>
		<div style="text-align:center;margin-top:20px;">
			
			<a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="paRecbtn" style="font-size:20px;height:40px;paddin:10px 40px;">
				<?php echo 'Reception';?>
			</a>

		</div>
<?php
	}
}
?>

<?php
if(isset($_SESSION['codeC']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reports';?>
		</a>
		
		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Factures';?>
		</a>

		<a href="assurances.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="assurancebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Assurances';?>
		</a>
	
	</div>
	
<?php
}
?>

<?php
if(!isset($_SESSION['codeC']) AND $_SESSION['dataM']!=NULL)
{
?>
	<div style="text-align:center;margin-top:20px;">
	
		<a href="reportDataM.php?dataMan=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';} if(isset($_GET['caissier'])){ echo '&caissier=ok';}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reports/Data Manager';?>
		</a>

	</div>
	
<?php
}
?>

<div class="account-container" style="width:70%">

	<div id='cssmenu' style="text-align:center">
	<ul>
		<?php
		if(isset($_SESSION['codeR']) AND isset($_SESSION['codeCash']))
		{
			if(!isset($_GET['caissier']))
			{
		?>
			<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
		<?php
			}else{
		?>
				<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
		<?php
			}
		}else{
			if(isset($_SESSION['codeR']))
			{
			?>
				<li style="width:50%;"><a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
			<?php
			}else{
			?>
				<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-lg fa-fw"></i> <?php echo getString(48);?></a></li>
		<?php
			}
		}
		?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
		
		<!--<li><a onclick="ShowList('Items')" data-title="Manage Items">Manage Items</a></li>-->
	</ul>

	<ul style="margin-top:20px;background:none;border:none;">

		<div id="divMenuUser" style="display:inline-block;">
		<?php
		if(isset($_GET['iduti']))
		{
		?>
			<a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" id="newUser"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(87);?></a>
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
		
		<div style="display:none;" id="divItem">

			<a href="items.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn btn-success btn-large" name="item" id="item">Add item</a>
			
			<a href="items.php?showitems=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="ShowItem" class="btn btn-success btn-large">Show items</a>


		</div>
		
	
		<div style="display:none;margin-top:50px;" id="divListe" align="center">

		<br/>

			<a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&listPa=1<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(20);?></a>
			
			<a href="medecins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(19);?></a>
			
			<a href="infirmiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(21);?></a>
			
			<a href="laborantins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(22);?></a>
			
			<a href="radiologues1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Radiologue';?></a>
			
			<a href="receptionistes1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(40);?></a>
			
			<a href="caissiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(23);?></a>
			
			<a href="orthopedistes1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Orthopediste';?></a>
			
			<!--
			<a href="auditeurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(149);?></a>
			
			<a href="comptables1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(150);?></a>
			-->
			
			<a href="coordinateurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Manager';?></a>
			

		</div>
		
	</div>

		
	<div style="background:#F8F8F8; margin-top:10px; padding:10px 50px; text-align:center;">

		<?php 
		if(!isset($_GET['num']))
		{
			if(isset($_SESSION['codeR']))
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
			}else{
			
				if(!isset($_GET['iduti']))
				{
		?>
				<h2 style="margin-bottom:40px;"><?php echo getString(4);?></h2>
		<?php
				}else{
				?>
					<h2 style="margin-bottom:40px;"><?php echo getString(213);?></h2>
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

		<table style="margin-left:87px; margin-bottom: 25px;">
			<tr>
				<?php 
				if(!isset($_GET['num']))
				{
				?>
				<td>
					<span><?php echo getString(6);?></span>
				</td>
				
				<td style="text-align:left">
					<?php 
					if(isset($_SESSION['codeR']))
					{
					?>
						<input type="text" name="profil" value='<?php echo getString(20);?>' id="Patient" readonly="readonly">
						
					<?php
					}else{
					
						if(isset($_GET['iduti']))
						{		
					?>
						<input type="text" name="profil" value='<?php if($comptidDoc!=0){ echo ''.getString(19).'';} if($comptidInf!=0){ echo ''.getString(21).'';} if($comptidLabo!=0){ echo ''.getString(22).'';} if($comptidRadio!=0){ echo 'Radiologue';} if($comptidRec!=0 AND isset($_GET['updaterecep'])){ echo ''.getString(40).'';} if($comptidCash!=0 AND isset($_GET['updatecash'])){ echo ''.getString(23).'';} if($comptidAudit!=0){ echo ''.getString(149).'';} if($comptidAcc!=0){ echo ''.getString(150).'';} if($comptidO!=0){ echo 'Orthopediste';}if($comptidMan!=0){ echo ''.getString(179).'';}?>' id="profil" readonly="readonly">
						<?php
						}else{
						?>
						<select name="profil" id="profil" onchange="ShowProfil('Profil')">
						
							<option value='' id="noprofil">
							<?php echo getString(31).'...';?>
							</option>

							<option value='Medecin' id="Medecin">
							<?php echo getString(19);?>
							</option>
							
							<option value='Infirmier' id="Infirmier">
							<?php echo getString(21);?>
							</option>
							
							<option value='Laborantin' id="Laborantin">
							<?php echo getString(22);?>
							</option>
							
							<option value='Radiologue' id="Radiologue">
							<?php echo 'Radiologue';?>
							</option>
							
							<option value='Receptioniste' id="Receptioniste">
							<?php echo getString(40);?>
							</option>
							
							<option value='Caissier' id="Caissier">
							<?php echo getString(23);?>
							</option>
							
							<option value='Orthopediste' id="Orthopediste">
							<?php echo 'Orthopediste';?>
							</option>
							
							<!--
							<option value='Auditeur' id="Auditeur">
							<?php echo getString(149);?>
							</option>
							
							<option value='Comptable' id="Comptable">
							<?php echo getString(150);?>
							</option>
							
							-->
							
							<option value='Coordinateur' id="Coordinateur"><?php echo getString(179);?></option>
							
						</select>
					<?php 
						}
					}
					?>	
							
				</td>
				<?php 
				}
				?>	
			</tr>
		</table>
		<?php 
		if(isset($_SESSION['codeC']))
		{
		?>
		<table align="center">
			<tr>		
				<td></td>	
				<td>
					Data Manager<input type="checkbox" name="dataM" id="dataM" <?php if(isset($_GET['iduti']) AND $dataM!=NULL) { echo "checked='checked'";}?>/>
				</td>
			</tr>
		</table>
		<?php 
		}
		?>

		<fieldset id="fieldInf" <?php if(isset ($_GET['iduti']) and $comptidInf!=0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="codeinf"><?php echo getString(7);?></label></td>
				<td style="text-align:left">
					<input type="text" name="codeinf" id="codeinf" value="<?php if(isset ($_GET['iduti']) and $comptidInf!=0){ echo $codeInfirm;}else{ echo showSN('N'); }?>" readonly/>
				</td>
			</tr>
			
			<tr>	
				<td></td>
				<td style="text-align:right">
					<table align="center" cellpadding=10>
						<tr>
							<td style="text-align:left">
								<label for="clinik"><?php echo "Accès à la clinique";?></label>
							</td>
							<td style="text-align:right">
								<label for="hosp"><?php echo "Accès à l'hospitalisation";?></label>
							</td>
						</tr>
						<tr>
							<td style="text-align:center">
								<input type='checkbox' name='clinik' id='clinik' <?php if(isset($_GET['iduti']) AND $comptidInf!=0 AND ($infhosp!=1)) { echo "checked='checked'";}?>/>
							</td>
							<td style="text-align:center">				
								<input type='checkbox' name='hosp' id='hosp' <?php if(isset($_GET['iduti']) AND $comptidInf!=0 AND ($infhosp!=0)) { echo "checked='checked'";}?>/>			
							</td>
						</tr>
					</table>
				</td>				
			</tr>			
		</table>		
		</fieldset>


		<fieldset id="fieldLabo" <?php if(isset ($_GET['iduti']) and $comptidLabo!=0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="codelab"><?php echo getString(7);?></label></td>
				<td>
					<input type="text" name="codelab" id="codelab" value="<?php if(isset ($_GET['iduti']) and $comptidLabo!=0){ echo $codeLaboran;}else{ echo showSN('L'); }?>" readonly="readonly"/>
				</td>
			</tr>
		</table>
		</fieldset>


		<fieldset id="fieldRadio" <?php if(isset ($_GET['iduti']) and $comptidRadio!=0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="coderad"><?php echo getString(7);?></label></td>
				<td>
					<input type="text" name="coderad" id="coderad" value="<?php if(isset ($_GET['iduti']) and $comptidRadio!=0){ echo $codeRadiolo;}else{ echo showSN('X'); }?>" readonly="readonly"/>
				</td>
			</tr>
		</table>
		</fieldset>

		<fieldset id="fieldRecep" <?php if(isset ($_GET['iduti']) and $comptidRec!=0 AND $comptidCash==0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="coderec"><?php echo getString(7);?></label></td>
				<td>
					<input type="text" name="coderec" id="coderec" value="<?php if(isset ($_GET['iduti']) and $comptidRec!=0){ echo $codereceptio;}else{ echo showSN('R'); }?>" readonly="readonly"/>
				</td>
			</tr>
		</table>

		<table align="center">
			<tr>
				<td></td>
				<td style="text-align:center"><label for="checkCashier"><?php echo "Accès à la caisse";?></label>
					<input type='checkbox' name='checkCashier' id='checkCashier'/>
				</td>
			</tr>
		</table>
		
		</fieldset>
		
		
		<fieldset id="fieldCash" <?php if(isset ($_GET['iduti']) and $comptidCash!=0 AND $comptidRec==0){ echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		
		<table>

		<tr>
			<td><label for="codecash"><?php echo getString(7);?></label></td>
			<td>
				<input type="text" name="codecash" id="codecash" value="<?php if(isset ($_GET['iduti']) and $comptidCash!=0){ echo $codecashier;}else{ echo showSN('C'); }?>" readonly="readonly"/>
			</td>
		</tr>

		</table>
		
		<table align="center">
		<tr>
			<td></td>
			<td style="text-align:center"><label for="checkRec"><?php echo "Accès à la réception";?></label>
				<input type='checkbox' name='checkRec' id='checkRec'/>
			</td>
		</tr>

		</table>
		</fieldset>
		
		
		<?php
		if($comptidCash!=0 AND $comptidRec!=0)
		{
		?>
		<fieldset id="fieldRecCash" <?php if(isset($_GET['iduti'])){ echo "style='display:inline-block'";}?>>
		<table>

		<tr>
			<td><label for="codereccash"><?php echo getString(7);?></label></td>
			<td>
				<input type="text" name="codereccash" id="codereccash" value="<?php if(isset ($_GET['iduti']) and isset($_GET['updatecash'])){ echo $codecashier;}elseif(isset ($_GET['iduti']) and isset($_GET['updaterecep'])){ echo $codereceptio;}?>" readonly="readonly"/>
			</td>
		</tr>
		
		<tr>	
			<td></td>
			<td style="text-align:center">
			<?php
			if(isset($_GET['updaterecep']))
			{
			?>
				<label for="checkCashRec"><?php echo "Accès à la caisse";?></label>
				<input type='checkbox' name='checkCashRec' id='checkCashRec' checked="checked"/>
			<?php
			}
			?>
			<?php
			if(isset($_GET['updatecash']))
			{
			?>	
				<label for="checkRecCash"><?php echo "Accès à la réception";?></label>
				<input type='checkbox' name='checkRecCash' id='checkRecCash' checked="checked"/>
			<?php
			}
			?>
			</td>
		</tr>

		</table>

		</fieldset>
		
		
		<?php
		}
		?>
		
		<fieldset id="fieldAudit" <?php if(isset ($_GET['iduti']) and $comptidAudit!=0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="codeaudit"><?php echo getString(7);?></label></td>
				<td>
					<input type="text" name="codeaudit" id="codeaudit" value="<?php if(isset ($_GET['iduti']) and $comptidAudit!=0){ echo $codeaudit;}else{ echo showSN('A'); }?>" readonly="readonly"/>
				</td>
			</tr>
		</table>
		</fieldset>
		
		
		<fieldset id="fieldAcc" <?php if(isset ($_GET['iduti']) and $comptidAcc!=0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="codeacc"><?php echo getString(7);?></label></td>
				<td>
					<input type="text" name="codeacc" id="codeacc" value="<?php if(isset ($_GET['iduti']) and $comptidAcc!=0){ echo $codeacc;}else{ echo showSN('B'); }?>" readonly="readonly"/>
				</td>
			</tr>
		</table>
		</fieldset>
		

		<fieldset id="fieldOrtho" <?php if(isset ($_GET['iduti']) and $comptidLabo!=0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="codeortho"><?php echo getString(7);?></label></td>
				<td>
					<input type="text" name="codeortho" id="codeortho" value="<?php if(isset ($_GET['iduti']) and $comptidO!=0){ echo $codeo;}else{ echo showSN('O'); }?>" readonly="readonly"/>
				</td>
			</tr>
		</table>
		</fieldset>
		
		
		<fieldset id="fieldCoordi" <?php if(isset ($_GET['iduti']) and $comptidMan!=0){echo "style='display:inline-block'";}else{echo "style='display:none'";}?>>
		<table>
			<tr>
				<td><label for="codecoord"><?php echo getString(7);?></label></td>
				<td>
					<input type="text" name="codecoord" id="codecoord" value="<?php if(isset ($_GET['iduti']) and $comptidMan!=0){ echo $codeCoordi;}else{ echo showSN('M'); }?>" readonly="readonly"/>
				</td>
			</tr>
		</table>
		</fieldset>


		
		<fieldset id="fieldMed" <?php if(isset($_GET['iduti']) and isset ($codeM)){echo "style='display:inline-block'";}else{echo "style='display:none'";}?> >

			<table id="medField">

				<tr>
					<td><label for="codedoc"><?php echo getString(7);?></label></td>
					<td style="text-align:left">
						<input type="text" name="codedoc" id="codedoc" value="<?php if(isset($_GET['iduti']) and $comptidDoc!=0){ echo $codeM;}else{ echo showSN('D'); }?>" readonly/>
					</td>

				</tr>
			
				<tr>
					<td><?php echo getString(34);?></td>
					
					<td style="text-align:left">
						<select name="grade" id="grade" onchange="ShowService('Service')">
							<option value=""><?php echo getString(194) ?></option>
						<?php

							$resultats=$connexion->query('SELECT *FROM grades ORDER BY id_grade');
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
							{
						?>
							<option value='<?php echo $ligne->id_grade;?>' id="grade"  <?php if(isset($_GET['iduti']) and $grade == $ligne->nomgrade){echo "selected='selected'";}?>>
							<?php echo $ligne->nomgrade;?>
							</option>
						<?php
							}
						?>
						</select><span style="color:black;font-weight:bold"> *</span>
							
					<script src="jQuery.js"></script>
					<script>
					$(function(){
						$("#grade").change(function(){
						var gradeChoisi='grade='+$(this).val();
							
							//alert(gradeChoisi);
							$.ajax({
								url:"traitement_grade.php",
								type:"POST",
								data:gradeChoisi,
								
								success:function(resultat)
								{
									
									// alert(resultat);
									$('#categopresta').html(resultat);
								}	
							});
							

						});
					});
					</script>

					</td>
				</tr>
			
			</table>
			<?php
			if(!isset($_GET['iduti']))
			{
			?>
			<table id="service" style="display:none;">

				<tr>
					<td>
					<?php echo getString(39);?>
						<select name="categopresta" id="categopresta">
						
						</select>
					</td>
				</tr>

			</table>
			<?php
			}else{
			
			?>
				<table id="service" style="display:<?php if($idcategopresta!=1){ echo "inline";}else{ echo "none";}?>; margin-left:185px;">

					<tr>
						<td style="width:20%"><?php echo getString(39);?></td>
						<td style="text-align:left">
							<select name="categopresta" id="categopresta">
								<option value=""><?php echo getString(84);?></option>
								<?php

								$resultats=$connexion->query('SELECT *FROM categopresta_ins ORDER BY nomcategopresta');
								while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
								?>
								<option value='<?php echo $ligne->id_categopresta; ?>'<?php if($idcategopresta == $ligne->id_categopresta){ echo 'selected="selected"';}?>>
								<?php echo $ligne->nomcategopresta;?>
								</option>
								<?php
								}
								?>
							</select>
						</td>
					</tr>

				</table>
			<?php
			}
			?>
		</fieldset>
		
		<div class="add-user">

			<table>
			<?php 
			if(isset($_SESSION['codeR']) != 0)
			{
			?>
				<tr>
					<td><label for="num"><?php echo getString(7);?></label></td>
					<td style="text-align:left">
						<input type="text" name="num" id="num" size="10px" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $num;}else{ echo showSN('P'); }?>" readonly="readonly" />
					</td>
				</tr>
				
				<tr>
					<td><label for="referenceid"><?php echo 'N° référence';?></label></td>
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
						<input type="text" name="nom" id="nom" onfocus="ShowHelp('nom')" value="<?php if(isset($_GET['iduti'])){ echo $nom_uti;}else{echo '';}?>" placeholder="<?php echo getString(50);?>"/><span style="color:black;font-weight:bold"> *</span>
					</td>
				</tr>

				<tr>
					<td><label for="prenom"><?php echo getString(10);?></label></td>
					<td style="text-align:left">
						<input type="text" name="prenom" id="prenom" onfocus="ShowHelp('prenom')" value="<?php if(isset($_GET['iduti'])){ echo $prenom_uti;}else{echo '';}?>" placeholder="<?php echo getString(51);?>"/><span style="color:black;font-weight:bold"> *</span>
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
			if(isset($_SESSION['codeR']))
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
						
						<select name="mois" id="mois" style="width:120px;" onchange="myScriptMois()">
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
				
						<input size="25px" type="hidden" id="jourN" name="jourN" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $jourN;}else{echo '';}?>"/>
						
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
						while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
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
						<input style="width:315px" type="text" name="cardIDassurance" id="cardIDassurance" placeholder="Enter the insurance card ID" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $carteassuranceid;}else{echo '';}?>" onfocus="ShowHelp('cardIDassurance')"/>
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
						<input type="text" name="bill" id="bill" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $bill;}else{echo '';}?>" style="width:25px" onfocus="ShowHelp('bill')"/> %
					</td>
				</tr>

				<tr>
					<td style="width:120px;"><label for="adherent"><?php echo getString(199);?></label></td>
					
					<td>
						<input style="width:315px" type="text" name="adherent" id="adherent" onclick="ShowHelp('adherent')" value="<?php if(isset($_GET['iduti']) and $comptidPa!=0){ echo $adherent;}else{echo getString(201);}?>" onfocus="ShowHelp('adherent')"/>
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
						while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
						{
							if($ligne->id_province!=6)
							{
						?>
							<option value='<?php echo $ligne->id_province; ?>'>
							<?php echo $ligne->nomprovince;?>
							</option>
						<?php
							}else{
						?>
							<option value='<?php echo $ligne->id_province; ?>'>
							<?php echo "Autre";?>
							</option>
						<?php
							}
						}							
						?>
						</select><span style="color:black;font-weight:bold"> *</span>
						
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
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
							{
								if($ligne->id_province!=6)
								{
							?>
								<option value="<?php echo $ligne->id_province; ?>" <?php if($province == $ligne->id_province){ echo 'selected="selected"';}?>>
								<?php echo $ligne->nomprovince;?>
								</option>
							<?php
								}else{
							?>
								<option value="<?php echo $ligne->id_province; ?>" <?php if($province == $ligne->id_province){ echo 'selected="selected"';}?>>
								<?php echo "Autre";?>
								</option>
							<?php
								}
							
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
				
				<table id="distri" style="<?php if($adresseExt!=""){ echo 'display:none;margin-left:185px;';}else{ echo 'display:inline-block;margin-left:185px;';}?>">
				<tr>
					<td style="width:20%"><?php echo getString(42);?></td>
					<td style="text-align:left">
						<select name="district" id="district" onchange="ShowAdresse('Secteur')">
						<option value=""><?php echo getString(52);?></option>
						<?php

							$resultats=$connexion->prepare('SELECT * FROM district WHERE id_province=:prov');
							$resultats->execute(array(
							'prov'=>$province
							));
							
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
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

				
				<table id="sect" style="<?php if($adresseExt!=""){ echo 'display:none;margin-left:185px;';}else{ echo 'display:inline-block;margin-left:185px;';}?>">
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
							
							while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
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

				<?php
				if(isset($_SESSION['codeC']))
				{
				?>
				<tr>
					<td><label for="password"><?php echo getString(17);?></label></td>
					<td style="text-align:left">
					<input type="text" name="password" id="password" placeholder="<?php echo getString(47);?>" value="<?php if(isset($_GET['iduti'])){ echo $password;}else{ echo '';}?>" onfocus="ShowHelp('password')"/><span style="color:black;font-weight:bold"> *</span>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td><?php echo getString(18);?></td>
					
					<td style="text-align:left">
						<input size="25px" type="text" id="anneeAd" name="anAd" onclick="ds_sh(this);" value="<?php if(isset($_GET['iduti'])){ echo $anAff;}else{ $annee = date('Y').'-'.date('m').'-'.date('d'); echo $annee;}?>"/>
					</td>
					
					<td>
						<input size="25px" type="text" id="today" name="today" value="<?php  $annee = date('Y').'-'.date('m').'-'.date('d'); echo $annee;?>" style="display:none;"/>
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
			
				<a class="btn-large-inversed" style="width: 150px; margin-top: 20px; position: absolute; height: 28px; margin-left: 20px;" href="<?php if(isset($_SESSION['codeR'])){ echo 'patients1.php?';}else{ if($comptidDoc!=0){echo 'medecins1.php?';} if($comptidInf!=0){ echo'infirmiers1.php?';} if($comptidLabo!=0){ echo'laborantins1.php?';} if($comptidRadio!=0){ echo'radiologues1.php?';} if($comptidMan!=0){ echo 'coordinateurs1.php?';} if($comptidRec!=0){ echo'receptionistes1.php?';} if($comptidCash!=0){ echo 'caissiers1.php?';} if($comptidAudit!=0){ echo 'auditeurs1.php?';}if($comptidAcc!=0){ echo 'comptables1.php?';}if($comptidO!=0){ echo 'orthopedistes1.php?';}}?><?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>">
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
	<?php include('footer.php');?>
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
				var j = $('#jourN').val();
				var h = '';
				if(j==i)
				{
					h = 'selected = "selected"';
				}
				
				$('#jours').append('<option value="' + i + '"' + h +'>'
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


	function ShowService(service)
	{	
		
		if( service =='Service')
		{
			var grade=document.getElementById('grade').value;
			
			if( grade ==2 || grade =='')
			{
				document.getElementById('service').style.display='none';
			}else{
				document.getElementById('service').style.display='inline-block';
			}
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

	function ShowProfil(pro)
	{
		// alert(pro);
		var profil=document.getElementById('profil').value;
		
		if( profil =='Medecin')
		{
			document.getElementById('fieldMed').style.display='inline-block';
		}else{
			document.getElementById('fieldMed').style.display='none';
			// document.getElementById('divAdd').style.display='none';
		}
		
		
		if( profil =='Infirmier')
		{
			document.getElementById('fieldInf').style.display='inline-block';
			
		}else{
			document.getElementById('fieldInf').style.display='none';
		}
		
		
		if( profil =='Laborantin')
		{
			document.getElementById('fieldLabo').style.display='inline-block';
		}else{
			document.getElementById('fieldLabo').style.display='none';
		}
		
		
		if( profil =='Radiologue')
		{
			document.getElementById('fieldRadio').style.display='inline-block';
		}else{
			document.getElementById('fieldRadio').style.display='none';
		}
		
		
		if( profil =='Receptioniste')
		{
			document.getElementById('fieldRecep').style.display='inline-block';
		}else{
			document.getElementById('fieldRecep').style.display='none';
		}
			
		
		if( profil =='Caissier')
		{
			document.getElementById('fieldCash').style.display='inline-block';
		}else{
			document.getElementById('fieldCash').style.display='none';
		}
			
		
		if( profil =='Auditeur')
		{
			document.getElementById('fieldAudit').style.display='inline-block';
		}else{
			document.getElementById('fieldAudit').style.display='none';
		}
		
		
		if( profil =='Comptable')
		{
			document.getElementById('fieldAcc').style.display='inline-block';
		}else{
			document.getElementById('fieldAcc').style.display='none';
		}
		
		
		if( profil =='Orthopediste')
		{
			document.getElementById('fieldOrtho').style.display='inline-block';
		}else{
			document.getElementById('fieldOrtho').style.display='none';
		}
		
				
		if( profil =='Coordinateur')
		{
			document.getElementById('fieldCoordi').style.display='inline-block';
		}else{
			document.getElementById('fieldCoordi').style.display='none';
		}
		
		
		
	}


	function ShowAdd(){
		
		
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
			document.getElementById('divItem').style.display='none';
			document.getElementById('divListe').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divMenuMsg').style.display='inline-block';
			document.getElementById('divMenuUser').style.display='none';
			document.getElementById('divItem').style.display='none';
			document.getElementById('divListe').style.display='none';
			
		}
		
		if( list =='Items')
		{
			document.getElementById('divItem').style.display='inline-block';
			document.getElementById('divMenuUser').style.display='none';
			document.getElementById('divMenuMsg').style.display='none';
			document.getElementById('divListe').style.display='none';
		}
		
		if( list =='Liste')
		{
			document.getElementById('divListe').style.display='inline';
			document.getElementById('listOff').style.display='inline';
			document.getElementById('listOn').style.display='none';
		}
		
		if( list =='ListeNon')
		{
			document.getElementById('divListe').style.display='none';
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
