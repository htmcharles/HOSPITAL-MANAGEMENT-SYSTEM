<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


if(isset($_GET['Restore']))
{


	//$id_Consu = $_GET['idconsu'];
	$id_Bill = $_GET['idbill'];
	$numbill = $_GET['numbill'];

	$Update = $connexion->query("UPDATE bills SET status=0 WHERE id_bill=".$id_Bill."");

	echo '<script type="text/javascript"> alert("Bill n° '.$_GET['numbill'].' Of Clinic Restored Successfuly! ");</script>';
	
	echo '<script type="text/javascript">document.location.href="RecycleBin.php";</script>';

}

if(isset($_GET['deletebill']))
{


	//$id_Consu = $_GET['idconsu'];
	$id_Bill = $_GET['idbill'];
	$numbill = $_GET['numbill'];
			
	$deleteConsult=$connexion->prepare('DELETE FROM consultations WHERE id_factureConsult=:id_Bill');
	
	$deleteConsult->execute(array(
	'id_Bill'=>$id_Bill
	));


	$deleteConsu=$connexion->prepare('DELETE FROM med_consult WHERE id_factureMedConsu=:id_Bill');
	
	$deleteConsu->execute(array(
	'id_Bill'=>$id_Bill
	
	));
	

	$deleteSurge=$connexion->prepare('DELETE FROM med_surge WHERE id_factureMedSurge=:id_Bill');
	
	$deleteSurge->execute(array(
	'id_Bill'=>$id_Bill
	
	));
	

	$deleteInf=$connexion->prepare('DELETE FROM med_inf WHERE id_factureMedInf=:id_Bill');
	
	$deleteInf->execute(array(
	'id_Bill'=>$id_Bill
	
	));

	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo WHERE id_factureMedLabo=:id_Bill');
	
	$deleteLabo->execute(array(
	'id_Bill'=>$id_Bill
	
	));

	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio WHERE id_factureMedRadio=:id_Bill');
	
	$deleteRadio->execute(array(
	'id_Bill'=>$id_Bill
	
	));
	

	$deleteKine=$connexion->prepare('DELETE FROM med_kine WHERE id_factureMedKine=:id_Bill');
	
	$deleteKine->execute(array(
	'id_Bill'=>$id_Bill
	
	));
	

	$deleteOrtho=$connexion->prepare('DELETE FROM med_ortho WHERE id_factureMedOrtho=:id_Bill');
	
	$deleteOrtho->execute(array(
	'id_Bill'=>$id_Bill
	
	));
	
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom WHERE id_factureMedConsom=:id_Bill');
	
	$deleteConsom->execute(array(
	'id_Bill'=>$id_Bill
	
	));

	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc WHERE id_factureMedMedoc=:id_Bill');
	
	$deleteMedoc->execute(array(
	'id_Bill'=>$id_Bill
	
	));

	$deleteBill=$connexion->prepare('DELETE FROM bills WHERE id_bill=:id_Bill');
	
	$deleteBill->execute(array(
	'id_Bill'=>$id_Bill	
	));
	
	
	echo '<script type="text/javascript"> alert("Bill '.$_GET['numbill'].' Of Clinic Deleted Permanently");</script>';
	
	echo '<script type="text/javascript">document.location.href="RecycleBin.php";</script>';

	
}

if(isset($_GET['RestoreHosp']))
{


	//$id_Consu = $_GET['idconsu'];
	$idhosp = $_GET['idhosp'];
	$numbill = $_GET['numbill'];

	$Update = $connexion->query("UPDATE patients_hosp SET statusBill=0 WHERE id_hosp=".$idhosp."");

	echo '<script type="text/javascript"> alert("Bill n° '.$_GET['numbill'].' Of Hospitalization Restored Successfuly! ");</script>';
	
	echo '<script type="text/javascript">document.location.href="RecycleBin.php";</script>';

}


if(isset($_GET['deletebillhosp']))
{

	$idhosp = $_GET['idhosp'];
	$numbill = $_GET['numbill'];
	
	
	$deleteConsu=$connexion->prepare('DELETE FROM med_consult_hosp WHERE id_hospMed=:idhosp');
	
	$deleteConsu->execute(array(
	'idhosp'=>$idhosp
	
	));
	

	$deleteSurge=$connexion->prepare('DELETE FROM med_surge_hosp WHERE id_hospSurge=:idhosp');
	
	$deleteSurge->execute(array(
	'idhosp'=>$idhosp
	
	));
	

	$deleteInf=$connexion->prepare('DELETE FROM med_inf_hosp WHERE id_hospInf=:idhosp');
	
	$deleteInf->execute(array(
	'idhosp'=>$idhosp
	
	));

	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo_hosp WHERE id_hospLabo=:idhosp');
	
	$deleteLabo->execute(array(
	'idhosp'=>$idhosp
	
	));

	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio_hosp WHERE id_hospRadio=:idhosp');
	
	$deleteRadio->execute(array(
	'idhosp'=>$idhosp
	
	));
	

	$deleteKine=$connexion->prepare('DELETE FROM med_kine_hosp WHERE id_hospKine=:idhosp');
	
	$deleteKine->execute(array(
	'idhosp'=>$idhosp
	
	));
	

	$deleteOrtho=$connexion->prepare('DELETE FROM med_ortho_hosp WHERE id_hospOrtho=:idhosp');
	
	$deleteOrtho->execute(array(
	'idhosp'=>$idhosp
	
	));
	
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom_hosp WHERE id_hospConsom=:idhosp');
	
	$deleteConsom->execute(array(
	'idhosp'=>$idhosp
	
	));

	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc_hosp WHERE id_hospMedoc=:idhosp');
	
	$deleteMedoc->execute(array(
	'idhosp'=>$idhosp
	
	));


	$deleteConsult=$connexion->prepare('DELETE FROM patients_hosp WHERE id_hosp=:idhosp');
	
	$deleteConsult->execute(array(
	'idhosp'=>$idhosp
	
	));
	
	
	
	echo '<script type="text/javascript"> alert("Bill '.$_GET['numbill'].' Of Hospitalization Deleted Permanently");</script>';
	
	echo '<script type="text/javascript">document.location.href="RecycleBin.php";</script>';

	
}
?>

<!doctype html>
<html lang="en">
<noscript>
	Cette page requiert du Javascript.
	Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<title><?php echo 'FACTURES';?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
		<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<script src="script.js"></script>
			
			<!------------------------------------->
			
	<link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
	
	<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	

	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<script src="myQuery.js"></script>
	<style type="text/css">
	body{
		font-family: calibri !important;
	}
	</style>
	
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

				$resultatssto=$connexion->prepare('SELECT *FROM utilisateurs u, stockkeeper sto WHERE u.id_u=:operation AND u.id_u=sto.id_u');
				$resultatssto->execute(array(
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
				$comptidsto=$resultatssto->rowCount();
				
				
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

				if($comptidsto!=0)
				{
					$resultatssto->setFetchMode(PDO::FETCH_OBJ);
					while($ligne=$resultatssto->fetch())
					{
						$codesto=$ligne->codestock;
						$anAff=$ligne->dateaffectionstock;
					}
					$resultatssto->closeCursor();
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
				$comptidsto=0;
			
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

<div class="navbar navbar-fixed-top" style="position: fixed;width: 100%;">
	
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
						<a href="utilisateurs.php?english=english<?php if(isset($_GET['iduti'])){echo "&iduti=".$_GET['iduti'];} ?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="utilisateurs.php?francais=francais<?php if(isset($_GET['iduti'])){echo "&iduti=".$_GET['iduti'];} ?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?>" class="btn"><?php echo getString(29);?></a>
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

		 <a href="prices.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="pricesbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left:10px;<?php if($id !=3460){echo "display: none;";} ?>">
			<?php echo 'Prices';?>
		</a> 

	</div>
	<?php
	}else{
	?>
		<div style="text-align:center;margin-top:20px;">
			
			<a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="paRecbtn" style="font-size:20px;height:40px;padding:10px 40px;">
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
		
		<a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 20px;margin-right:10px;">
			<?php echo 'Reports';?>
		</a>
		
		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Factures';?>
		</a>

		<a href="patients1_hosp.php?cashHosp=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 10px;margin-right:10px;">
			<?php echo 'Hospitalisation';?>
		</a>

		<a href="assurances.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="assurancebtn" style="font-size:20px;height:40px; padding:10px 10px;margin-right:10px;">
			<?php echo 'Assurances';?>
		</a>
		<a href="expenses.php?codeCoord=<?php echo $_SESSION['id'];?>&expenses=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 10px;margin-left: 10px;">
			<?php echo 'Expenses';?>
		</a>

		<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 10px;margin-left: 10px;">
			<?php echo 'Dettes';?>
		</a>

        <a href="prices.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="pricesbtn" style="font-size:20px;height:40px; padding:10px 10px;margin-left:10px;">
			<?php echo 'Prices';?>
		</a> 

        <a href="trackingbill.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="pricesbtn" style="font-size:20px;height:40px; padding:10px 10px;margin-left:10px;">
			<?php echo 'Tracking Bills';?>
		</a>  

		<a href="RecycleBin.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 10px;margin-left: 10px;">
			<?php echo getString(292);?>
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

<div class="account-container" style="width:95%">
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

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
					
					<?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
					
					<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>
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
			-->
			<a href="comptables1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(150);?></a>
			
			
			<a href="coordinateurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Manager';?></a>
			<a href="stockkeeper.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'stock keeper';?></a>
			

		</div>
		
	</div>

	
	<div style="background:#F8F8F8; margin-top:10px; padding:10px 50px; text-align:center;">
		<h2 style="margin-bottom:40px;"><?php echo getString(292);?></h2>
	<div style="text-align:center;margin-top:50px;margin-bottom:30px;">
		
		<span onclick="ShowBills('Clinic')" class="btn-large" id="clinicbill" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;<?php if(isset($_GET['divBill'])){ echo 'display:none;';}?>">
			<i class="fa fa-eye fa-lg fa-fw"></i> <?php echo 'Factures Clinic';?>
		</span>

		<span onclick="ShowBills('Hosp')" class="btn-large-inversed" id="hospbill" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;<?php if(isset($_GET['divBillHosp'])){ echo 'display:none;';}?>">
			<i class="fa fa-eye fa-lg fa-fw"></i> <?php echo 'Factures Hôpital';?>
		</span>

	</div>
	
	
	
	<div id="divclinicbills" style="<?php if(!isset($_GET['divBill'])){ echo 'display:none;';}?>">
		
		<b><h3><?php echo 'Liste des factures de la Clinic';?></h3></b>
		
		<form class="ajax" action="search.php" method="get">
			<p>
				<table align="center">
					<tr>
						<td>
							<label for="d"><?php echo 'Rechercher par date';?></label>
							<input type="text" name="d" id="d" onclick="ShowSearch('bydate')"/>
						</td>
						
						<td>
							<label for="n"><?php echo 'Rechercher par nom';?></label>
							<input type="text" name="n" id="n" onclick="ShowSearch('byname')"/>
						</td>
						
						<td>
							<label for="s"><?php echo 'Rechercher par S/N';?></label>
							<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
						</td>
						
						<td>
							<label for="b"><?php echo 'Rechercher par N° Facture';?></label>
							<input type="text" name="b" id="b" onclick="ShowSearch('bybn')"/>
						</td>
					</tr>
				</table>
			</p>
		</form>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsDate"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsName"></div>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsBN"></div>

		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#d').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 ) 
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({ 
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_facturesedit.php?trash=ok&date=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'd='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsDate').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#n').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_facturesedit.php?trash=ok&name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'n='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsName').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#s').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_facturesedit.php?trash=ok&sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 's='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsSN').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#b').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_facturesedit.php?trash=ok&bn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'b='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsBN').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
	
	<?php
	if(isset($_GET['divBill']))
	{
		$resultatsBills=$connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idBill');
		$resultatsBills->execute(array(
		'idBill'=>$_GET['idbill']
		));
	
		$resultatsBills->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$comptBill=$resultatsBills->rowCount();
		

		if($comptBill!=0)
		{
		?>
		<table class="tablesorter" cellspacing="0">
			
			<thead>
				<tr>
					<th style="width:12%"><?php echo getString(71);?></th>
					<th style="width:8%"><?php echo getString(166);?></th>
					<th style="width:20%"><?php echo getString(89);?></th>
					<th style="width:10%"><?php echo getString(76);?></th>
					<th style="width:10%" colspan=2><?php echo 'Actions';?></th>
				</tr>
			</thead>
			
			<tbody>
			<?php
			
			while($ligneBill=$resultatsBills->fetch())
			{
				$getIdDebt=$connexion->prepare('SELECT * FROM bills b WHERE b.numero=:numero AND b.detteDone IS NULL AND b.dette IS NOT NULL');
				$getIdDebt->execute(array(
					'numero'=>$ligneBill->numero
				));
				
				$getIdDebt->setFetchMode(PDO::FETCH_OBJ);
				
				$idDebtCount = $getIdDebt->rowCount();
				
				$totalDettes=NULL;
				
				while($ligneIdDebt=$getIdDebt->fetch())
				{
					$detteRestante=$ligneIdDebt->dette;
					
					if($ligneIdDebt->detteDone != 1)
					{
						$totalDettes=$totalDettes + $detteRestante;
					}
				}
			?>
				<tr style="text-align:center;<?php if($ligneBill->status==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
					<td><?php echo $ligneBill->datebill;?></td>
					
					<td>
						<a href="reprintbill.php?num=<?php echo $ligneBill->numero;?>&idbill=<?php echo $ligneBill->id_bill;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligneBill->datebill;?>&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneBill->numbill;?></a>
					</td>
					<td>
						<?php
						
						$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
						$resultPatient->execute(array(
							'operation'=>$ligneBill->numero
						));
						
						$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
						
						$comptFiche=$resultPatient->rowCount();
						
						if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
						{
							$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
							$snPa = $lignePatient->numero;
							
							echo $fullname.' ('.$snPa.')';
						}
						
						if($totalDettes!=NULL)
						{
							?>
							<br/>
							<span style="text-align:right;font-weight:700;color:gray;">Total debts</span>
							<a class="btn" href="dettesList.php?num=<?php echo $ligneBill->numero;?>&divDette=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="color:red;"><?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
							</a>
							<?php
						}
						?>
					</td>
					
					<td>
					<?php
						if($ligneBill->nomassurance !="")
							echo $ligneBill->nomassurance.' '.$ligneBill->billpercent.'%';
					?>
					</td>
					
					<td>
						<a href="RecycleBin.php?Restore=ok&numbill=<?php echo $ligneBill->numbill;?>&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-recycle"></i> <?php echo 'Restore';?></a>
					</td>
					
					<td>
						<a href="RecycleBin.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligneBill->numbill;?>&deletebill=ok&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligneBill->numbill;?>"><i class="fa fa-trash"></i> Permanently</a> 
					</td>
					
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<?php
		}else{
		?>
			<table class="tablesorter" cellspacing="0">
				
				<thead>
					<tr>
						<th style="text-align:center"><?php echo getString(169);?></th>
					</tr>
				</thead>
				
			</table>
		<?php
		}
		?>
		<br/>
		
		<!--
		<a href="facturesedit.php?page=1&iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Bills" class="btn-large"><?php echo getString(170);?></a>
		-->
	<?php
	}
	?>
		
		<?php
		if(!isset($_GET['divAcc']))
		{
			
			$resultats=$connexion->query("SELECT *FROM bills b WHERE  b.status=1 ORDER BY b.numbill DESC") ;

			$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
			$comptBill=$resultats->rowCount();
			
		?>
		<div style="overflow:auto;height:1000px;background-color:none;margin-top:10px;">
		<?php
		if($comptBill!=0)
		{
		?>
		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th style="width:6%">N°</th>
					<th style="width:12%"><?php echo getString(71);?></th>
					<th style="width:8%"><?php echo getString(166);?></th>
					<th style="width:20%"><?php echo getString(89);?></th>
					<th style="width:10%"><?php echo getString(76);?></th>
					<th style="width:10%" colspan=2><?php echo 'Actions';?></th>
				</tr>
			</thead>
			
			<tbody>
			<?php
			try
			{
				$i = 1;
				while($ligne=$resultats->fetch())//on recupere la liste des éléments
				{
					$getIdDebt=$connexion->prepare('SELECT * FROM bills b WHERE b.numero=:numero AND b.detteDone IS NULL AND b.dette IS NOT NULL');
					$getIdDebt->execute(array(
						'numero'=>$ligne->numero
					));
					
					$getIdDebt->setFetchMode(PDO::FETCH_OBJ);
					
					$idDebtCount = $getIdDebt->rowCount();
					
					$totalDettes=NULL;
					
					while($ligneIdDebt=$getIdDebt->fetch())
					{
						$detteRestante=$ligneIdDebt->dette;
						
						if($ligneIdDebt->detteDone != 1)
						{
							$totalDettes=$totalDettes + $detteRestante;
						}
					}
					
					$resultAssu=$connexion->prepare('SELECT *FROM assurances a WHERE a.nomassurance=:nomAssu');
					$resultAssu->execute(array(
						'nomAssu'=>$ligne->nomassurance
					));
					
					$resultAssu->setFetchMode(PDO::FETCH_OBJ);
					
					$comptAssu=$resultAssu->rowCount();
					
					if($ligneAssu=$resultAssu->fetch())
					{
						$idassuBill = $ligneAssu->id_assurance;
						$nomassuBill = $ligneAssu->nomassurance;
					}else{
						$idassuBill = "";
						$nomassuBill = "";
					}
			?>
					<tr style="text-align:center;<?php if($ligne->status==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
						<td><?= $i;?></td>
						<td><?php echo $ligne->datebill;?></td>
						
						<td>
							<a href="reprintbill.php?num=<?php echo $ligne->numero;?>&idbill=<?php echo $ligne->id_bill;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligne->datebill;?>&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->numbill;?></a>
						</td>
						<td>
							<?php
							
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
							$resultPatient->execute(array(
								'operation'=>$ligne->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								$snPa = $lignePatient->numero;
								
								echo $fullname.' ('.$snPa.')';
							}
							
							if($totalDettes!=NULL)
							{
								?>
								<br/>
								<span style="text-align:right;font-weight:700;color:gray;">Total debts</span>
								<a class="btn" href="dettesList.php?num=<?php echo $ligne->numero;?>&divDette=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="color:red;"><?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
								</a>
								<?php
							}
							?>
						</td>
						
						<td>
						<?php
							if($ligne->nomassurance !="")
								echo $ligne->nomassurance.' '.$ligne->billpercent.'%';
						?>
						</td>
						
						<td>
							<a href="RecycleBin.php?Restore=ok&numbill=<?php echo $ligne->numbill;?>&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-recycle"></i> <?php echo 'Recover';?></a>
						</td>
						
						<td>
							<a href="RecycleBin.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligne->numbill;?>&deletebill=ok&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligne->numbill;?>"><i class="fa fa-trash"></i> Permanently</a>
						</td>
					</tr>
			<?php
					$i++;
				}
				$resultats->closeCursor();

			}

			catch(Excepton $e)
			{
			echo 'Erreur:'.$e->getMessage().'<br/>';
			echo'Numero:'.$e->getCode();
			}


			?>
			</tbody>
		</table>
		<?php
			}else{
			?>
				<table class="tablesorter" cellspacing="0">
					
					<thead>
						<tr>
							<th style="text-align:center"><?php echo getString(169);?></th>
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
	</div>
	
	
	<div id="divhospbills" style="<?php if(!isset($_GET['divBillHosp'])){ echo 'display:none;';}?>">
		
		<b><h3><?php echo 'Liste des factures de l\'hôpital';?></h3></b>
		
		
		<form class="ajax" action="search.php" method="get">
			<p>
				<table align="center">
					<tr>
						<td>
							<label for="dh"><?php echo 'Rechercher par date';?></label>
							<input type="text" name="dh" id="dh" onclick="ShowSearch('bydateh')"/>
						</td>
						
						<td>
							<label for="nh"><?php echo 'Rechercher par nom';?></label>
							<input type="text" name="nh" id="nh" onclick="ShowSearch('bynameh')"/>
						</td>
						
						<td>
							<label for="sh"><?php echo 'Rechercher par S/N';?></label>
							<input type="text" name="sh" id="sh" onclick="ShowSearch('bysnh')"/>
						</td>
						
						<td>
							<label for="bh"><?php echo 'Rechercher par N° Facture';?></label>
							<input type="text" name="bh" id="bh" onclick="ShowSearch('bybnh')"/>
						</td>
					</tr>
				</table>
			</p>
		</form>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsDateH"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsNameH"></div>
		
		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSNH"></div>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsBNH"></div>

		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#dh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?trash=ok&datehosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'dh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsDateH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#nh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?trash=ok&namehosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'nh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsNameH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#sh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?trash=ok&snhosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'sh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsSNH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<script type="text/javascript">
		$(document).ready( function() {
		  // détection de la saisie dans le champ de recherche
		  $('#bh').keyup( function(){
			$field = $(this);
			$('#results').html(''); // on vide les resultats
			$('#ajax-loader').remove(); // on retire le loader
		 
			// on commence à traiter à partir du 2ème caractère saisie
			if( $field.val().length > 0 )
			{
			  // on envoie la valeur recherché en GET au fichier de traitement
			  $.ajax({
			type : 'GET', // envoi des données en GET ou POST
			url : 'search_factureshospedit.php?trash=ok&bnhosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			data : 'bh='+$(this).val() , // données à envoyer en  GET ou POST
			beforeSend : function() { // traitements JS à faire AVANT l'envoi
				$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			},
			success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
				$('#ajax-loader').remove(); // on enleve le loader
				$('#resultsBNH').html(data); // affichage des résultats dans le bloc
			}
			  });
			}
		  });
		});
		</script>
		
		<?php
		
		if(isset($_GET['divBillHosp']))
		{
			
			$resultatshosp=$connexion->prepare("SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:id_hosp") ;
			$resultatshosp->execute(array(
			'id_hosp'=>$_GET['id_hosp']
			));

			$resultatshosp->setFetchMode(PDO::FETCH_OBJ);
			
			$comptBillHosp=$resultatshosp->rowCount();
		

			if($comptBillHosp!=0)
			{
			?>
			<table class="tablesorter" cellspacing="0">
				<thead>
					<tr>
						<th style="width:12%"><?php echo getString(71).' Sortie';?></th>
						<th style="width:8%"><?php echo getString(166);?></th>
						<th style="width:20%"><?php echo getString(89);?></th>
						<th style="width:10%"><?php echo getString(76);?></th>
						<th style="width:10%" colspan=2><?php echo 'Actions';?></th>
					</tr>
				</thead>
				
				<tbody>
				<?php
				try
				{
					while($ligne=$resultatshosp->fetch())
					{
				?>
						<tr style="text-align:center;<?php if($ligne->statusPaHosp==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
							<td><?php echo $ligne->dateSortie.' '.$ligne->heureSortie;?></td>
							
							<td>
								<a href="reprintbillhosp.php?num=<?php echo $ligne->numero;?>&idhosp=<?php echo $ligne->id_hosp;?>&numroom=<?php echo $ligne->numroomPa;?>&numlit=<?php echo $ligne->numlitPa;?>&manager=<?php echo $_SESSION['codeC'];?>&idassu=<?php echo $ligne->id_assuHosp;?>&nomassurance=<?php echo $ligne->nomassuranceHosp;?>&billpercent=<?php echo $ligne->insupercent_hosp;?>&datefacture=<?php echo $ligne->dateSortie;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->id_factureHosp;?></a>
							</td>
							<td>
								<?php
								$totalDettes=NULL;
								$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
								$resultPatient->execute(array(
									'operation'=>$ligne->numero
								));
								
								$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
								
								$comptFiche=$resultPatient->rowCount();
								
								if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
								{
									$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
									$snPa = $lignePatient->numero;
									
									echo $fullname.' ('.$snPa.')';
								}
								
								if($totalDettes!=NULL)
								{
									?>
									<br/>
									<span style="text-align:right;font-weight:700;color:gray;">Total debts</span>
									<a class="btn" href="dettesList.php?num=<?php echo $ligne->numero;?>&divDette=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="color:red;"><?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
									</a>
									<?php
								}
								?>
							</td>
							
							<td>
							<?php
								if($ligne->nomassuranceHosp !="")
									echo $ligne->nomassuranceHosp.' '.$ligne->insupercent_hosp.'%';
							?>
							</td>
							
							<td>
								<a href="RecycleBin.php?RestoreHosp=ok&numbill=<?php echo $ligne->id_factureHosp;?>&idhosp=<?php echo $ligne->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Restore';?></a>
							</td>
							
							<td>
								<a href="RecycleBin.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligne->id_factureHosp;?>&deletebillhosp=ok&idhosp=<?php echo $ligne->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligne->id_factureHosp;?>"><i class="fa fa-trash fa-1x fa-fw"></i></a>
							</td>
						</tr>
				<?php
					}
					$resultatshosp->closeCursor();

				}

				catch(Excepton $e)
				{
				echo 'Erreur:'.$e->getMessage().'<br/>';
				echo'Numero:'.$e->getCode();
				}


				?>
				</tbody>
			</table>
			<?php
			}else{
			?>
				<table class="tablesorter" cellspacing="0">
					
					<thead>
						<tr>
							<th style="text-align:center"><?php echo getString(169);?></th>
						</tr>
					</thead>
					
				</table>
			<?php
			}
			?>
			<br/>
			
			<!--
			<a href="facturesedit.php?page=1&iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Bills" class="btn-large"><?php echo getString(170);?></a>
			-->
		<?php
		}
		?>

		
		<?php
		if(!isset($_GET['divAcc']))
		{
			
			
			$resultatshosp=$connexion->query("SELECT *FROM patients_hosp ph WHERE ph.id_factureHosp IS NOT NULL AND ph.dateSortie!='0000-00-00' AND ph.statusBill=1 ORDER BY ph.id_factureHosp DESC") ;

			$resultatshosp->setFetchMode(PDO::FETCH_OBJ);
			
			$comptBillHosp=$resultatshosp->rowCount();
			
		?>
		<div style="overflow:auto;height:1000px;background-color:none;margin-top:10px">
		<?php
		if($comptBillHosp!=0)
		{
		?>
		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th style="width:12%"><?php echo getString(71).' Sortie';?></th>
					<th style="width:8%"><?php echo getString(166);?></th>
					<th style="width:20%"><?php echo getString(89);?></th>
					<th style="width:10%"><?php echo getString(76);?></th>
					<th style="width:10%" colspan=2><?php echo 'Actions';?></th>
				</tr>
			</thead>
			
			<tbody>
			<?php
			try
			{
				while($ligne=$resultatshosp->fetch())
				{
			?>
					<tr style="text-align:center;<?php if($ligne->statusPaHosp==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
						<td><?php echo $ligne->dateSortie.' '.$ligne->heureSortie;?></td>
						
						<td>
							<a href="reprintbillhosp.php?num=<?php echo $ligne->numero;?>&idhosp=<?php echo $ligne->id_hosp;?>&numroom=<?php echo $ligne->numroomPa;?>&numlit=<?php echo $ligne->numlitPa;?>&manager=<?php echo $_SESSION['codeC'];?>&idassu=<?php echo $ligne->id_assuHosp;?>&nomassurance=<?php echo $ligne->nomassuranceHosp;?>&billpercent=<?php echo $ligne->insupercent_hosp;?>&datefacture=<?php echo $ligne->dateSortie;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->id_factureHosp;?></a>
						</td>
						<td>
							<?php
							$totalDettes = null;
							$resultPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:operation');
							$resultPatient->execute(array(
								'operation'=>$ligne->numero
							));
							
							$resultPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							$comptFiche=$resultPatient->rowCount();
							
							if($lignePatient=$resultPatient->fetch())//on recupere la liste des éléments
							{
								$fullname = $lignePatient->nom_u.' '.$lignePatient->prenom_u;
								$snPa = $lignePatient->numero;
								
								echo $fullname.' ('.$snPa.')';
							}
							
							if($totalDettes!=NULL)
							{
								?>
								<br/>
								<span style="text-align:right;font-weight:700;color:gray;">Total debts</span>
								<a class="btn" href="dettesList.php?num=<?php echo $ligne->numero;?>&divDette=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="color:red;"><?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
								</a>
								<?php
							}
							?>
						</td>
						
						<td>
						<?php
							if($ligne->nomassuranceHosp !="")
								echo $ligne->nomassuranceHosp.' '.$ligne->insupercent_hosp.'%';
						?>
						</td>
						
						<td>
							<a href="RecycleBin.php?RestoreHosp=ok&numbill=<?php echo $ligne->id_factureHosp;?>&idhosp=<?php echo $ligne->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Restore';?></a>
						</td>
						
						<td>
							<a href="RecycleBin.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligne->id_factureHosp;?>&deletebillhosp=ok&idhosp=<?php echo $ligne->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligne->id_factureHosp;?>"><i class="fa fa-trash"></i> Permanently</a>
						</td>
					</tr>
			<?php
				}
				$resultats->closeCursor();

			}

			catch(Excepton $e)
			{
			echo 'Erreur:'.$e->getMessage().'<br/>';
			echo'Numero:'.$e->getCode();
			}


			?>
			</tbody>
		</table>
		<?php
			}else{
			?>
				<table class="tablesorter" cellspacing="0">
					
					<thead>
						<tr>
							<th style="text-align:center"><?php echo getString(169);?></th>
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
	</div>
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


	function ShowBills(bills)
	{
		if(bills =='Clinic')
		{
			document.getElementById('divclinicbills').style.display='inline';
			document.getElementById('divhospbills').style.display='none';
			document.getElementById('hospbill').style.display='inline';
			document.getElementById('clinicbill').style.display='none';
		}
		
		if(bills =='Hosp')
		{
			document.getElementById('divhospbills').style.display='inline';
			document.getElementById('divclinicbills').style.display='none';
			document.getElementById('clinicbill').style.display='inline';
			document.getElementById('hospbill').style.display='none';
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
