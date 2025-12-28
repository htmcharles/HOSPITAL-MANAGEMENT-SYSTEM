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

	$prestation = strtolower('prestations_'.$_GET['assurances_name']);
	//Get All Prestation

	/*$GetPrestation = $connexion->query("SELECT * FROM ".$prestation." ORDER BY id_prestation ASC");
	$GetPrestation->setFetchMode(PDO::FETCH_OBJ);
	$countPresta = $GetPrestation->rowCount();*/


	if (isset($_GET['callmodal'])) {
	    $GetPrestation = $connexion->query("SELECT * FROM ".$prestation." WHERE id_prestation=".$_GET['id_prestation']."");
	    $GetPrestation->setFetchMode(PDO::FETCH_OBJ);
	    $LignePresta = $GetPrestation->fetch();

	    //Get All category

	    $catego = $connexion->query("SELECT * FROM categopresta_ins");
	    $catego->setFetchMode(PDO::FETCH_OBJ);
	}
	if (isset($_POST['replacePresta'])) {
		$idprestationRe = $_POST['oldPresta'];
		$newid = $_POST['OGPresta'];
		$idassuR = $_POST['insId'];

		$selectacteConsom =$connexion->query('UPDATE med_consom SET id_prestationConsom='.$newid.' WHERE id_prestationConsom='.$idprestationRe.' AND 	id_assuConsom='.$idassuR.'');

		$selectacteConsom =$connexion->query('UPDATE med_consom SET id_prestationConsom='.$newid.' WHERE id_prestationConsom='.$idprestationRe.' AND 	id_assuConsom='.$idassuR.'');
		

		$selectacteConsomH =$connexion->query('UPDATE med_consom_hosp SET id_prestationConsom='.$newid.' WHERE id_prestationConsom='.$idprestationRe.' AND id_assuConsom='.$idassuR.'');
		

		$selectacteConsu =$connexion->query('UPDATE med_consult SET id_prestationConsu='.$newid.' WHERE id_prestationConsu='.$idprestationRe.' AND id_assuServ='.$idassuR.'');
		

		$selectacteConsuH =$connexion->query('UPDATE med_consult_hosp SET id_prestationConsu='.$newid.' WHERE id_prestationConsu='.$idprestationRe.' AND id_assuServ='.$idassuR.'');
		

		$selectacteInf =$connexion->query('UPDATE med_inf SET id_prestation='.$newid.' WHERE id_prestation='.$idprestationRe.' AND id_assuInf='.$idassuR.'');
		

		$selectacteInfH =$connexion->query('UPDATE med_inf_hosp SET id_prestation='.$newid.' WHERE id_prestation='.$idprestationRe.' AND id_assuInf='.$idassuR.'');
		

		$selectacteKine =$connexion->query('UPDATE med_kine SET id_prestationKine='.$newid.' WHERE id_prestationKine='.$idprestationRe.' AND id_assuKine='.$idassuR.'');
		

		$selectacteKineH =$connexion->query('UPDATE med_kine_hosp SET id_prestationKine='.$newid.' WHERE id_prestationKine='.$idprestationRe.' AND id_assuKine='.$idassuR.'');
		

		$selectacteLabo =$connexion->query('UPDATE med_labo SET id_prestationExa='.$newid.' WHERE id_prestationExa='.$idprestationRe.' AND id_assuLab='.$idassuR.'');
		

		$selectacteLaboH =$connexion->query('UPDATE med_labo_hosp SET id_prestationExa='.$newid.' WHERE id_prestationExa='.$idprestationRe.' AND id_assuLab='.$idassuR.'');
		

		$selectacteMedoc =$connexion->query('UPDATE med_medoc SET id_prestationMedoc='.$newid.' WHERE id_prestationMedoc='.$idprestationRe.' AND id_assuMedoc='.$idassuR.'');
		

		$selectacteMedocH =$connexion->query('UPDATE med_medoc_hosp SET id_prestationMedoc='.$newid.' WHERE id_prestationMedoc='.$idprestationRe.' AND id_assuMedoc='.$idassuR.'');
		

		$selectacteOrtho =$connexion->query('UPDATE med_ortho SET id_prestationOrtho='.$newid.' WHERE id_prestationOrtho='.$idprestationRe.' AND id_assuOrtho='.$idassuR.'');
		

		$selectacteOrthoH =$connexion->query('UPDATE med_ortho_hosp SET id_prestationOrtho='.$newid.' WHERE id_prestationOrtho='.$idprestationRe.' AND id_assuOrtho='.$idassuR.'');
		

		$selectacteRadio =$connexion->query('UPDATE med_radio SET id_prestationRadio='.$newid.' WHERE id_prestationRadio='.$idprestationRe.' AND id_assuRad='.$idassuR.'');
		

		$selectacteRadioH =$connexion->query('UPDATE med_radio_hosp SET id_prestationRadio='.$newid.' WHERE id_prestationRadio='.$idprestationRe.' AND id_assuRad='.$idassuR.'');
		

		$selectacteSurge =$connexion->query('UPDATE med_surge SET id_prestationSurge='.$newid.' WHERE id_prestationSurge='.$idprestationRe.' AND id_assuSurge='.$idassuR.'');

		$selectacteSurgeH =$connexion->query('UPDATE med_surge_hosp SET id_prestationSurge='.$newid.' WHERE id_prestationSurge='.$idprestationRe.' AND id_assuSurge='.$idassuR.'');

		/*verifier si on a bien remplace */
		$selectacteConsom =$connexion->query('SELECT * FROM med_consom WHERE id_prestationConsom='.$idprestationRe.' AND id_assuConsom='.$idassuR.'');
		$selectacteConsom->setFetchMode(PDO::FETCH_OBJ);
		$rowConsom = $selectacteConsom->rowCount();
		/*while ($ligneacteConsom = $selectacteConsom->fetch()) {
			echo "<br>id_medconsom = ".$ligneacteConsom->id_medconsom;
		}*/

		$selectacteConsomH =$connexion->query('SELECT * FROM med_consom_hosp WHERE id_prestationConsom='.$idprestationRe.' AND id_assuConsom='.$idassuR.'');
		$selectacteConsomH->setFetchMode(PDO::FETCH_OBJ);
		$rowConsomH = $selectacteConsomH->rowCount();
		/*while ($ligneacteConsomH = $selectacteConsomH->fetch()) {
			echo "<br>id_medconsom hosp = ".$ligneacteConsomH->id_medconsom;
		}*/

		$selectacteConsu =$connexion->query('SELECT * FROM med_consult WHERE id_prestationConsu='.$idprestationRe.' AND id_assuServ='.$idassuR.'');
		$selectacteConsu->setFetchMode(PDO::FETCH_OBJ);
		$rowConsu = $selectacteConsu->rowCount();
		/*while ($ligneacteConsu = $selectacteConsu->fetch()) {
			echo "<br>idmedServ = ".$ligneacteConsu->id_medconsu;
		}*/

		$selectacteConsuH =$connexion->query('SELECT * FROM med_consult_hosp WHERE id_prestationConsu='.$idprestationRe.' AND id_assuServ='.$idassuR.'');
		$selectacteConsuH->setFetchMode(PDO::FETCH_OBJ);
		$rowConsuH = $selectacteConsuH->rowCount();
		/*while ($ligneacteConsuH = $selectacteConsuH->fetch()) {
			echo "<br>idmedServ hosp = ".$ligneacteConsuH->id_medconsu;
		}*/

		$selectacteInf =$connexion->query('SELECT * FROM med_inf WHERE id_prestation='.$idprestationRe.' AND id_assuInf='.$idassuR.'');
		$selectacteInf->setFetchMode(PDO::FETCH_OBJ);
		$rowInf = $selectacteInf->rowCount();
		/*while ($ligneacteInf = $selectacteInf->fetch()) {
			echo "<br>id_medinf = ".$ligneacteInf->id_medinf;
		}*/

		$selectacteInfH =$connexion->query('SELECT * FROM med_inf_hosp WHERE id_prestation='.$idprestationRe.' AND id_assuInf='.$idassuR.'');
		$selectacteInfH->setFetchMode(PDO::FETCH_OBJ);
		$rowInfH = $selectacteInfH->rowCount();
		/*while ($ligneacteInfH = $selectacteInfH->fetch()) {
			echo "<br>id_medinf hosp = ".$ligneacteInfH->id_medinf;
		}*/

		$selectacteKine =$connexion->query('SELECT * FROM med_kine WHERE id_prestationKine='.$idprestationRe.' AND id_assuKine='.$idassuR.'');
		$selectacteKine->setFetchMode(PDO::FETCH_OBJ);
		$rowKine = $selectacteKine->rowCount();
		/*while ($ligneacteKine = $selectacteKine->fetch()) {
			echo "<br>id_medkine = ".$ligneacteKine->id_medkine;
		}*/

		$selectacteKineH =$connexion->query('SELECT * FROM med_kine_hosp WHERE id_prestationKine='.$idprestationRe.' AND id_assuKine='.$idassuR.'');
		$selectacteKineH->setFetchMode(PDO::FETCH_OBJ);
		$rowKineH = $selectacteKineH->rowCount();
		/*while ($ligneacteKineH = $selectacteKineH->fetch()) {
			echo "<br>id_medkine hosp = ".$ligneacteKineH->id_medkine;
		}*/

		$selectacteLabo =$connexion->query('SELECT * FROM med_labo WHERE id_prestationExa='.$idprestationRe.' AND id_assuLab='.$idassuR.'');
		$selectacteLabo->setFetchMode(PDO::FETCH_OBJ);
		$rowLabo = $selectacteLabo->rowCount();
		/*while ($ligneacteLabo = $selectacteLabo->fetch()) {
			echo "<br>id_medlabo = ".$ligneacteLabo->id_medlabo;
		}*/

		$selectacteLaboH =$connexion->query('SELECT * FROM med_labo_hosp WHERE id_prestationExa='.$idprestationRe.' AND id_assuLab='.$idassuR.'');
		$selectacteLaboH->setFetchMode(PDO::FETCH_OBJ);
		$rowLaboH = $selectacteLaboH->rowCount();
		/*while ($ligneacteLaboH = $selectacteLaboH->fetch()) {
			echo "<br>id_medlabo hosp = ".$ligneacteLaboH->id_medlabo;
		}*/

		$selectacteMedoc =$connexion->query('SELECT * FROM med_medoc WHERE id_prestationMedoc='.$idprestationRe.' AND id_assuMedoc='.$idassuR.'');
		$selectacteMedoc->setFetchMode(PDO::FETCH_OBJ);
		$rowMedoc = $selectacteMedoc->rowCount();
		/*while ($ligneacteMedoc = $selectacteMedoc->fetch()) {
			echo "<br>id_medmedoc = ".$ligneacteMedoc->id_medmedoc;
		}*/

		$selectacteMedocH =$connexion->query('SELECT * FROM med_medoc_hosp WHERE id_prestationMedoc='.$idprestationRe.' AND id_assuMedoc='.$idassuR.'');
		$selectacteMedocH->setFetchMode(PDO::FETCH_OBJ);
		$rowMedocH = $selectacteMedocH->rowCount();
		/*while ($ligneacteMedocH = $selectacteMedocH->fetch()) {
			echo "<br>id_medmedoc hosp = ".$ligneacteMedocH->id_medmedoc;
		}*/

		$selectacteOrtho =$connexion->query('SELECT * FROM med_ortho WHERE id_prestationOrtho='.$idprestationRe.' AND id_assuOrtho='.$idassuR.'');
		$selectacteOrtho->setFetchMode(PDO::FETCH_OBJ);
		$rowOrtho = $selectacteOrtho->rowCount();
		/*while ($ligneacteOrtho = $selectacteOrtho->fetch()) {
			echo "<br>id_medortho = ".$ligneacteOrtho->id_medortho;
		}*/

		$selectacteOrthoH =$connexion->query('SELECT * FROM med_ortho_hosp WHERE id_prestationOrtho='.$idprestationRe.' AND id_assuOrtho='.$idassuR.'');
		$selectacteOrthoH->setFetchMode(PDO::FETCH_OBJ);
		$rowOrthoH = $selectacteOrthoH->rowCount();
		/*while ($ligneacteOrthoH = $selectacteOrthoH->fetch()) {
			echo "<br>id_medortho hosp = ".$ligneacteOrthoH->id_medortho;
		}*/

		$selectacteRadio =$connexion->query('SELECT * FROM med_radio WHERE id_prestationRadio='.$idprestationRe.' AND id_assuRad='.$idassuR.'');
		$selectacteRadio->setFetchMode(PDO::FETCH_OBJ);
		$rowRadio = $selectacteRadio->rowCount();
		/*while ($ligneacteRadio = $selectacteRadio->fetch()) {
			echo "<br>id_medradio = ".$ligneacteRadio->id_medradio;
		}*/

		$selectacteRadioH =$connexion->query('SELECT * FROM med_radio_hosp WHERE id_prestationRadio='.$idprestationRe.' AND id_assuRad='.$idassuR.'');
		$selectacteRadioH->setFetchMode(PDO::FETCH_OBJ);
		$rowRadioH = $selectacteRadioH->rowCount();
		/*while ($ligneacteRadioH = $selectacteRadioH->fetch()) {
			echo "<br>id_medradio hosp = ".$ligneacteRadioH->id_medradio;
		}*/

		$selectacteSurge =$connexion->query('SELECT * FROM med_surge WHERE id_prestationSurge='.$idprestationRe.' AND id_assuSurge='.$idassuR.'');
		$selectacteSurge->setFetchMode(PDO::FETCH_OBJ);
		$rowSurge = $selectacteSurge->rowCount();
		/*while ($ligneacteSurge = $selectacteSurge->fetch()) {
			echo "<br>id_medsurge = ".$ligneacteSurge->id_medsurge;
		}*/

		$selectacteSurgeH =$connexion->query('SELECT * FROM med_surge_hosp WHERE id_prestationSurge='.$idprestationRe.' AND id_assuSurge='.$idassuR.'');
		$selectacteSurgeH->setFetchMode(PDO::FETCH_OBJ);
		$rowSurgeH = $selectacteSurgeH->rowCount();
		/*while ($ligneacteSurgeH = $selectacteSurgeH->fetch()) {
			echo "<br>id_medsurge hosp = ".$ligneacteSurgeH->id_medsurge;
		}*/

		if ($rowConsom==0 AND $rowConsomH==0 AND $rowConsu==0 AND $rowConsuH==0 AND $rowInf==0 AND $rowInfH==0 AND $rowKine==0 AND $rowKineH==0 AND $rowLabo==0 AND $rowLaboH==0 AND $rowMedoc==0 AND $rowMedocH==0 AND $rowOrtho==0 AND $rowOrthoH==0 AND $rowRadio==0 AND $rowRadioH==0 AND $rowSurge==0 AND $rowSurgeH==0) {
			echo "prestation a supprime = ".$nomprestaRe." avec id_prestation =".$idprestationRe."<br>";

			$deleteprestation = $connexion->prepare('DELETE FROM '.$prestation.' WHERE id_prestation=:id_prestation');
			$deleteprestation->execute(array('id_prestation'=>$idprestationRe));
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
	<title><?php echo "PRICES";?></title>
	<meta charset="utf-8"/>
	
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

	<!-----------------------Pagination--------------------->
	<link rel="stylesheet" media="screen" type="text/css" title="Chosen" href="chosen/chosen.min.css" />
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
    <link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
    <script src="myQuery.js"></script>
	
	
	<!-- <link href="css/patients1.css" rel="stylesheet" type="text/css" /> --><!--Header-->
	
	

	<style type="text/css">
			body{
		font-family:calibri !important;
	}

	</style>


	
	
<body>

<?php

$id=$_SESSION['id'];

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND (isset($_SESSION['codeC']) or isset($_SESSION['codeR'])))
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

		<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Dettes';?>
		</a>
            
        <a href="prices.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="pricesbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left:10px;">
			<?php echo 'Prices';?>
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

<div class="account-container" style="width:90%">

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
	<div class="account-container" style="width:90%">
		<div id='cssmenu' style="text-align:center">
			<a href="prices_edit.php?newcategopresta=ok&assurances_name=<?php echo $_GET['assurances_name'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="dettesListbtn" style="font-size:20px;height:20px; padding:10px 20px;font-family: calibri;">
				<i class="fa fa-plus-circle fa-lg fa-fw"></i> New prestation
			</a>

			<a href="create_assurance_excel.php?assurance_selection_btn=ok&chosen_assurances=<?php echo $_GET['assurances_name'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:20px; padding:10px 20px;margin-left: 5px;font-family: calibri;">
				<i class="fa fa-file-excel-o fa-lg fa-fw"></i> Create Excel
			</a>

			<a href="create_assurance_pdf.php?assurance_selection_btn=ok&chosen_assurances=<?php echo $_GET['assurances_name'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="dettesListbtn" style="font-size:20px;height:20px; padding:10px 20px;margin-left: 5px;font-family: calibri;">
				<i class="fa fa-file-pdf-o fa-lg fa-fw"></i> Create PDF
			</a>
		</div>
	</div>
	<div id="divCategoPresta">
		<table class="tablesorter" cellspacing="0" style="background-color: #FFF; width: auto; margin-bottom: 20px;" align="center">

			<tr style="<?php if(!isset($_GET['newcategopresta'])){ echo "display: none;"; }else{ echo "display: block;"; }?>">
				<td>
					<form method="post" class="cmxform form-horizontal style-form"  action="edit_prices.php?assurances_name=<?php echo $_GET['assurances_name'];?>" onsubmit="return controlFormCategoPresta(this)"style="padding-right: 60px; padding-left: 50px;">
						
							<table class="table table-bordered" id="newCU">
                           
								<tr align="center">
										<h4 style="text-align: right;"><i class="fa fa-angle-right"></i> New prestation <?php echo $_GET['assurances_name'].' Assurances';?></h4>
									<div class="form-group ">
					                    <label for="nompresta" class="control-label col-lg-2" style="text-align: left;">Nom du prestation</label>
					                    <div class="col-lg-4">
					                      <input type="text" name="nompresta" id="nompresta" placeholder="Entre le nom prestation" required="required" />
					                    </div>
				                    </div>
	
                                    <div class="form-group ">
					                    <label for="namepresta" class="control-label col-lg-2" style="text-align: left;">Name of prestation</label>
					                    <div class="col-lg-4">
					                      <input type="text" name="namepresta" id="namepresta" placeholder="Enter the name of prestation" required="required" />
					                    </div>
					                </div>


                                    <div class="form-group ">
					                    <label for="prixpresta" class="control-label col-lg-2" style="text-align: left;">Prix</label>
					                    <div class="col-lg-4">
					                      <input type="text" name="prixpresta" id="prixpresta" placeholder="Entre le prix de l'assurance" required="required" />
					                    </div>
					                </div>

                                    <div class="form-group ">
					                    <label for="id_categopresta" class="control-label col-lg-2" style="text-align: left;">Categorie</label>
					                    <div class="col-lg-4">
					                    	<select style="margin:auto" name="id_categopresta" id="id_categopresta" required="required">
					                    	<?php
					                    		$selectecatego =$connexion->query('SELECT * FROM categopresta_ins');
					                    		$selectecatego->setFetchMode(PDO::FETCH_OBJ);
					                    		echo '<optgroup label="Categorie">';
					                    		while ($lignecatego = $selectecatego->fetch()) {
					                    	?>
													<option value='<?php echo $lignecatego->id_categopresta;?>'><?php echo $lignecatego->nomcategopresta;?></option>
											<?php
					                    		}
					                    	?>
					                    	</select>
					                    </div>
					                </div>	

                                    <div class="form-group ">
					                    <label for="mesure" class="control-label col-lg-2" style="text-align: left;">Unite de Mesure</label>
					                    <div class="col-lg-4">
					                    	<select style="margin:auto" name="mesure" id="mesure" required="required">
												<option value="">---Selectionner l'unite de mesure---</option>
												<option value="Ampoule">Ampoule</option>
												<option value="Bandes">Bandes</option>
												<option value="Boite">Boite</option>
												<option value="Boite">Comprimes</option>
												<option value="Flancon">Flancon</option>
												<option value="Jour">Jour</option>
												<option value="Litres">Litres</option>
												<option value="Ml">Ml</option>
												<option value="Paire">Paire</option>
												<option value="Pieces">Pieces</option>
												<option value="Seance">Seance</option>
												<option value="SACHET">SACHET</option>
												<option value="TABLET">TABLET</option>
					                    	</select>
					                    </div>
					                </div>	

                                    <div class="form-group ">
					                    <label for="statupresta" class="control-label col-lg-2" style="text-align: left;">Status</label>
					                    <div class="col-lg-4">
					                    	<select style="margin:auto" name="statupresta" id="statupresta" required="required">
												<option value="">---Selectionner le status du prestation---</option>
												<option value="1">Oui couvert par l'assurance</option>
												<option value="0">Non couvert par l'assurance</option>
					                    	</select>
					                    </div>
					                </div>	
									<br>
									
							        <div class="form-group">
                                    	<div class="col-lg-offset-2 col-lg-10">
											<button class="btn-large" type="submit" name="prestabtn" style="width: 100%;"><i class="fa fa-check-circle fa-lg fa-fw"></i> &nbsp;Save</button>
				              				<button onclick="window.location.href=''prices_edit.php?assurances_name=<?php echo $_GET['assurances_name'];?>;" class="btn-large-inversed" style="width: 100%;"><i class="fa fa-arrow-circle-left fa-lg fa-fw"></i> Cancel 
											</button>
										</div>
									</div>
							
									</tr>
							</table>
						
						</form>
				</td>
			</tr>
		</table>
	</div>

	<?php
		if (!isset($_GET['divCash']) && !isset($_GET['newcategopresta'])) {
	?>
    <div style="margin-top:10px;" id="tableAssu">
    	<?php 
         	if (isset($_POST['assurance_selection_btn']) || isset($_GET['assurances_name'])) {
	         	
	         	if (isset($_POST['assurance_selection_btn'])) {
	         		$assurances_name=$_POST['chosen_assurances'];
	         	}
	         	if (isset($_GET['assurances_name'])) {
	         		$assurances_name=$_GET['assurances_name'];
	         	}

	         	$assurances_name = strtolower($assurances_name);
	         	// echo $assurances_name;
         	?>
                <h2 style="font-size: 23px;font-weight: bold;"><?php echo 'Edit prestation prices for &nbsp;'.strtoupper($assurances_name).' Assurances';?></h2>


			    <form class="ajax" action="search.php" method="get">
			        <p>
			        <table align="center">
			            <tr>
			                <td>
			                    <label for="q"><?php echo 'Recherche par le nom de la prestation';?></label>
			                    <input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
			                </td>

			                <td>
			                    <label for="s">Search by name prestation</label>
			                    <input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
			                </td>
			            </tr>
			        </table>
			        </p>
			    </form>
			    <div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

			    <div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

			    <script type="text/javascript">
			        $(document).ready( function() {
			            // détection de la saisie dans le champ de recherche
			            $('#q').keyup( function(){
			                $field = $(this);
			                $('#results').html(''); // on vide les resultats
			                $('#ajax-loader').remove(); // on retire le loader

			                // on commence à traiter à partir du 2ème caractère saisie
			                if( $field.val().length > 0 )
			                {
			                    // on envoie la valeur recherché en GET au fichier de traitement
			                    $.ajax({
			                        type : 'GET', // envoi des données en GET ou POST
			                        url : 'traitement_assurance.php?nom=ok&assurance=<?php echo $assurances_name;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			                        data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
			                        beforeSend : function() { // traitements JS à faire AVANT l'envoi
			                            $field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			                        },
			                        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			                            $('#ajax-loader').remove(); // on enleve le loader
			                            $('#results').html(data); // affichage des résultats dans le bloc
			                        }
			                    });
			                }
			            });
			        });

			    </script>

			    <script type="text/javascript">
			        $(document).ready( function() {
			            // d?ction de la saisie dans le champ de recherche
			            $('#s').keyup( function(){
			                $field = $(this);
			                $('#resultsSN').html(''); // on vide les resultats
			                $('#ajax-loader').remove(); // on retire le loader

			                // on commence ?raiter ?artir du 2? caract? saisie
			                if( $field.val().length > 0 )
			                {
			                    // on envoie la valeur recherch?n GET au fichier de traitement
			                    $.ajax({
			                        type : 'GET', // envoi des donn? en GET ou POST
			                        url : 'traitement_assurance.php?name=ok&assurance=<?php echo $assurances_name;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
			                        data : 's='+$(this).val() , // donn? ?nvoyer en  GET ou POST
			                        beforeSend : function() { // traitements JS ?aire AVANT l'envoi
			                            $field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
			                        },
			                        success : function(data){ // traitements JS ?aire APRES le retour d'ajax-search.php
			                            $('#ajax-loader').remove(); // on enleve le loader
			                            $('#resultsSN').html(data); // affichage des r?ltats dans le bloc
			                        }
			                    });
			                }
			            });
			        });
			    </script>

                <table class="tablesorter" cellspacing="0" style="width: 150%">
                    <thead>
                    <tr>
                        <th style="width:8px;"><?php echo 'Categorie';?></th>
                        <th style="width:8px;"><?php echo 'Nom prestation';?></th>
                        <th style="width:5px;"><?php echo 'Name prestation';?></th>
						<th style="width:1500px;"><?php echo 'Prix '.$assurances_name;?></th>
						<th style="width:20px;"><?php echo 'Mesure';?></th>
						<th style="width:25px;" colspan="3"><?php echo 'Actions';?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    //echo "assu =".$assurances_name; assurances_name=bk
                    $assu = "assurances_name=".$assurances_name;
                    // echo "assu = ".$assu;
                    try
                    {
                        function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
                        {
                            $pagination = '';
                            $link = preg_replace('`%([^d])`', '%%$1', $link);
                            if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
                            if ( $nb_pages > 1 ) {

                                /* if(isset($_GET['page']))
                                {
                                    $pageTable='#tableAssu';
                                }else{
                                    $pageTable='';
                                } */
                                // Lien Précédent
                                if ( $current_page > 1 )
                                {
                                    if(isset($_GET['english']))
                                    {
                                        // echo '&english='.$_GET['english'];
                                        $pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&assurances_name='.$_GET['assurances_name'].'&english='.$_GET['english'].'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];
                                            $pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&assurances_name='.$_GET['assurances_name'].'&francais='.$_GET['francais'].'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

                                        }else{

                                            $pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&assurances_name='.$_GET['assurances_name'].'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

                                        }
                                    }

                                }else{
                                    $pagination .= '';
                                }

                                // Lien(s) début
                                for ( $i=1 ; $i<=$firstlast ; $i++ ) {
                                    $pagination .= ' ';

                                    if(isset($_GET['english']))
                                    {
                                        // echo '&english='.$_GET['english'];
                                        $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'&english='.$_GET['english'].'">'.$i.'</a>';

                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];
                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'&francais='.$_GET['francais'].'">'.$i.'</a>';

                                        }else{

                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'">'.$i.'</a>';

                                        }
                                    }

                                }

                                // ... après pages début ?
                                if ( ($current_page-$around) > $firstlast+1 )
                                    $pagination .= '<span class="current">&hellip;</span>';

                                // On boucle autour de la page courante
                                $start = ($current_page-$around)>$firstlast ? $current_page-$around : $firstlast+1;
                                $end = ($current_page+$around)<=($nb_pages-$firstlast) ? $current_page+$around : $nb_pages-$firstlast;
                                for ( $i=$start ; $i<=$end ; $i++ ) {
                                    $pagination .= ' ';
                                    if ( $i==$current_page )
                                    {
                                        $pagination .= '<span class="current">'.$i.'</span>';
                                    }else{

                                        if(isset($_GET['english']))
                                        {
                                            // echo '&english='.$_GET['english'];
                                            $pagination .= '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'&english='.$_GET['english'].'">'.$i.'</a>';

                                        }else{
                                            if(isset($_GET['francais']))
                                            {
                                                // echo '&francais='.$_GET['francais'];
                                                $pagination .= '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'&francais='.$_GET['francais'].'">'.$i.'</a>';
                                            }else{

                                                $pagination .= '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'">'.$i.'</a>';
                                            }
                                        }
                                    }
                                }

                                // ... avant page nb_pages ?
                                if ( ($current_page+$around) < $nb_pages-$firstlast )
                                    $pagination .= '<span class="current">&hellip;</span>';

                                // Lien(s) fin
                                $start = $nb_pages-$firstlast+1;
                                if( $start <= $firstlast ) $start = $firstlast+1;

                                for ( $i=$start ; $i<=$nb_pages ; $i++ )
                                {
                                    $pagination .= ' ';

                                    if(isset($_GET['english']))
                                    {
                                        $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'&english='.$_GET['english'].'">'.$i.'</a>';
                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];
                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'&francais='.$_GET['francais'].'">'.$i.'</a>';

                                        }else{

                                            $pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&assurances_name='.$_GET['assurances_name'].'">'.$i.'</a>';
                                        }
                                    }

                                }

                                // Lien suivant
                                if ( $current_page < $nb_pages )
                                {
                                    if(isset($_GET['english']))
                                    {
                                        // echo '&english='.$_GET['english'];

                                        $pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&assurances_name='.$_GET['assurances_name'].'&english='.$_GET['english'].'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';


                                    }else{
                                        if(isset($_GET['francais']))
                                        {
                                            // echo '&francais='.$_GET['francais'];

                                            $pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&assurances_name='.$_GET['assurances_name'].'&francais='.$_GET['francais'].'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';

                                        }else{
                                            $pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&assurances_name='.$_GET['assurances_name'].'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';

                                        }
                                    }

                                }else{
                                    $pagination .= '';
                                }
                            }
                            return $pagination;
                        }
                        // }

                        // Numero de page (1 par défaut)
                        if( isset($_GET['page']) && is_numeric($_GET['page']) )
                            $page = $_GET['page'];
                        else
                            $page = 1;

                        // Nombre d'info par page
                        $pagination =15;

                        // Numero du 1er enregistrement à lire
                        $limit_start = ($page - 1) * $pagination;


                        /*-----------Requête pour coordinateur-----------*/

                         $resultasPrestation=$connexion->query('SELECT * FROM prestations_'.$assurances_name.' LIMIT '.$limit_start.', '.$pagination.'')or die( print_r($connexion->errorInfo()));
                            $resultasPrestation->setFetchMode(PDO::FETCH_OBJ);
                            //$ligneM = $resultasPrestation->fetchAll();
                           while ($ligneM = $resultasPrestation->fetch()) {
                           	?>
                           	<form action="edit_prices.php?id_prestation=<?php echo  $ligneM->id_prestation;?>&nompresta=<?php echo  $ligneM->nompresta;?>" method="POST">
	                           	<tr>
		                    		<input type="hidden" name="idpresta" value="<?php echo $ligneM->id_prestation;?>"> 
		                    		<input type="hidden" name="assurances_name" value="<?php echo  $assurances_name ;?>">
		                    		<td>
		                    			<?php
		                    				$id_catego = $ligneM->id_categopresta;
		                    				$selectenamecatego = $connexion->prepare('SELECT * FROM categopresta_ins WHERE id_categopresta=:id_categopresta');
		                    				$selectenamecatego->execute(array(
		                    					'id_categopresta'=>$id_catego
		                    				));
		                    				$selectenamecatego->setFetchMode(PDO::FETCH_OBJ);
		                    				$lignenamecatego = $selectenamecatego->fetch();
		                    				echo $lignenamecatego->nomcategopresta;
		                    			?>
		                    		</td> 
		                           	<td>
			                    		<input type="text" name="nompresta" style="width: 190px;" value="<?php echo $ligneM->nompresta;?>">
			                    	</td>
		                           	<td>
		                    			<input type="text" name="namepresta" style="width: 190px;" value="<?php echo $ligneM->namepresta;?>">
			                    	</td>
			                    	<td>
			                    		<?php 
			                    			if ($ligneM->statupresta==0) {
			                    				echo '<input type="text" name="prixpresta" style="width:150px;" value="'.$ligneM->prixpresta.'" readonly="readonly" >';
			                    			}else{
			                    				echo '<input type="text" name="prixpresta" style="width:150px;" value="'.$ligneM->prixpresta.'" >';
			                    			}
			                    			//echo '<input type="text" name="prixpresta" style="width:50%" value="'.$ligneM->prixpresta.'" ';
			                    		?>
			                    	</td>
			                    	<td>
			                    		<select name="mesure" style="width: 130px;">
											<option value='' <?php if($ligneM->mesure=='') {echo 'selected="selected"';}?>></option>
											<option value='Ampoule' <?php if($ligneM->mesure=='Ampoule') {echo 'selected="selected"';}?>>Ampoule</option>
											<option value='Bandes' <?php if($ligneM->mesure=='Bandes') {echo 'selected="selected"';}?>>Bandes</option>
											<option value='Boite' <?php if($ligneM->mesure=='Boite') {echo 'selected="selected"';}?>>Boite</option>
											<option value="Boite" <?php if($ligneM->mesure=='Bandes') {echo 'selected="selected"';}?>>Comprimes</option>
											<option value='Flancon' <?php if($ligneM->mesure=='Flancon') {echo 'selected="selected"';}?>>Flancon</option>
											<option value='Jour' <?php if($ligneM->mesure=='Jour') {echo 'selected="selected"';}?>>Jour</option>
											<option value='Litres' <?php if($ligneM->mesure=='Litres') {echo 'selected="selected"';}?>>Litres</option>
											<option value='Ml' <?php if($ligneM->mesure=='Ml') {echo 'selected="selected"';}?>>Ml</option>
											<option value='Paire' <?php if($ligneM->mesure=='Paire') {echo 'selected="selected"';}?>>Paire</option>
											<option value='Pieces' <?php if($ligneM->mesure=='Pieces') {echo 'selected="selected"';}?>>Pieces</option>
											<option value='Seance' <?php if($ligneM->mesure=='Seance') {echo 'selected="selected"';}?>>Seance</option>
										</select>
			                    	</td>
			                    	<td><button class="btn-large" style="width: 80px;" type="submit" name="updateprice">Update</button></td>
			                    	<td><a href="prices_edit.php?id_prestation=<?php echo  $ligneM->id_prestation;?>&nompresta=<?php echo  $ligneM->nompresta;?>&assurances_name=<?php echo  $assurances_name;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>&callmodal=ok" class="btn-large-inversed"><i class="fa fa-trash"></i></a></td>
			                    	</form>
			                    	<?php
			                    		if ($ligneM->statupresta==1) {
									?>
										<td>
			                                <a href="edit_prices.php?id_prestation=<?php echo  $ligneM->id_prestation;?>&nompresta=<?php echo  $ligneM->nompresta;?>&assurances_name=<?php echo  $assurances_name;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>&desactifbtn=ok">Active<input type="image" style="border-radius:100px;" src="images/onBlue.png" name="desactifbtn"/></a>
			                            </td>
									<?php			                    			
			                    		}else{
			                    	?>
			                    		<td>
			                                <a href="edit_prices.php?id_prestation=<?php echo  $ligneM->id_prestation;?>&nompresta=<?php echo  $ligneM->nompresta;?>&assurances_name=<?php echo  $assurances_name;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>&actifbtn=ok">Desactive<input type="image" style="border-radius:100px;" src="images/offBlue.png" name="actifbtn"/></a>
			                            </td>
			                    	<?php
			                    		}
			                    	?>
			                    </tr>
                           	<?php
                           }
                            $resultasPrestation->closeCursor();

                    }

                    catch(Excepton $e)
                    {
                        echo 'Erreur:'.$e->getMessage().'<br/>';
                        echo'Numero:'.$e->getCode();
                    }


                    ?>
                    </tbody>
                </table>

                <tr>
                    <td style="float: left;">
                        <?php

                        $nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM prestations_'.$assurances_name.' ORDER BY nompresta');
                        $nb_total=$nb_total->fetch();
                        $nb_total = $nb_total['nb_total'];
                        // Pagination
                        $nb_pages = ceil($nb_total / $pagination);
                        // Affichage
                        echo '<p class="pagination" style="margin-left:30%;">' . pagination($page, $nb_pages) . '</p>';
                        ?>
                    </td>
                </tr>
			<?php 
				}
			?>
            </div>

    <?php
	}
    if(isset($_GET['divCash']))
    {
    	$assurances_name = $_GET['assurances_name'];
    	$id_prestation = $_GET['id_prestation'];

        /*-----------Requête pour les prestation-----------*/

        $resultatsM=$connexion->prepare('SELECT *FROM prestations_'.$assurances_name.' p WHERE p.id_prestation=:idpresta');
        $resultatsM->execute(array(
            'idpresta'=>$_GET['id_prestation']
        ))or die( print_r($connexion->errorInfo()));


        $resultatsM->setFetchMode(PDO::FETCH_OBJ);

    ?>
            <table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:10px;">

                <thead>
                <tr>
                	<th style="width:8px;"><?php echo 'Categorie';?></th>
                    <th style="width:25%"><?php echo 'Nom prestation';?></th>
                    <th style="width:25%"><?php echo 'Name prestation';?></th>
					<th style="width:25%"><?php echo 'Prix';?></th>
					<th style="width:25%"><?php echo 'Mesure';?></th>
					<th style="width:25%" colspan="2"><?php echo 'Actions';?></th>
                </thead>
                <tbody>
                <?php
                while($ligneM=$resultatsM->fetch())
                {
                    ?>
                    <form action="edit_prices.php?id_prestation=<?php echo  $ligneM->id_prestation;?>&nompresta=<?php echo  $ligneM->nompresta;?>" method="POST">
                    <tr style="text-align:center;">
                		<input type="hidden" name="idpresta" value="<?php echo $ligneM->id_prestation;?>" style="width: 30%;" > 
                		<input type="hidden" name="assurances_name" value="<?php echo  $assurances_name ;?>">
                		<td>
                			<?php
                				$id_catego = $ligneM->id_categopresta;
                				$selectenamecatego = $connexion->prepare('SELECT * FROM categopresta_ins WHERE id_categopresta=:id_categopresta');
                				$selectenamecatego->execute(array(
                					'id_categopresta'=>$id_catego
                				));
                				$selectenamecatego->setFetchMode(PDO::FETCH_OBJ);
                				$lignenamecatego = $selectenamecatego->fetch();
                				echo $lignenamecatego->nomcategopresta;
                			?>
                		</td>  
                        <td>
	                		<input type="text" name="nompresta" style="width: 190px;" value="<?php echo $ligneM->nompresta;?>" style="width: 30%;" >
                    	</td>
                       	<td>
	                		<input type="text" name="nompresta" style="width: 190px;" value="<?php echo $ligneM->nompresta;?>">
                    	</td>
                    	<td>
                    		<?php 
                    			if ($ligneM->statupresta==0) {
                    				echo '<input type="text" name="prixpresta" style="width: 150px;" style="width:100%" value="'.$ligneM->prixpresta.'" readonly="readonly" >';
                    			}else{
                    				echo '<input type="text" name="prixpresta" style="width: 150px;" style="width:50%" value="'.$ligneM->prixpresta.'" >';
                    			}
                    			//echo '<input type="text" name="prixpresta" style="width:50%" value="'.$ligneM->prixpresta.'" ';
                    			/*echo '<input type="text" name="prixpresta" style="width:50%" value="'.$ligneM->prixpresta.'"';*/
                    		?>
                    	</td>
                    	<td>
                    		<select name="mesure" style="width: 130px;">
								<option value='' <?php if($ligneM->mesure=='') {echo 'selected="selected"';}?>></option>
								<option value='Ampoule' <?php if($ligneM->mesure=='Ampoule') {echo 'selected="selected"';}?>>Ampoule</option>
								<option value='Bandes' <?php if($ligneM->mesure=='Bandes') {echo 'selected="selected"';}?>>Bandes</option>
								<option value='Boite' <?php if($ligneM->mesure=='Boite') {echo 'selected="selected"';}?>>Boite</option>
								<option value="Boite" <?php if($ligneM->mesure=='Bandes') {echo 'selected="selected"';}?>>Comprimes</option>
								<option value='Flancon' <?php if($ligneM->mesure=='Flancon') {echo 'selected="selected"';}?>>Flancon</option>
								<option value='Jour' <?php if($ligneM->mesure=='Jour') {echo 'selected="selected"';}?>>Jour</option>
								<option value='Litres' <?php if($ligneM->mesure=='Litres') {echo 'selected="selected"';}?>>Litres</option>
								<option value='Ml' <?php if($ligneM->mesure=='Ml') {echo 'selected="selected"';}?>>Ml</option>
								<option value='Paire' <?php if($ligneM->mesure=='Paire') {echo 'selected="selected"';}?>>Paire</option>
								<option value='Pieces' <?php if($ligneM->mesure=='Pieces') {echo 'selected="selected"';}?>>Pieces</option>
								<option value='Seance' <?php if($ligneM->mesure=='Seance') {echo 'selected="selected"';}?>>Seance</option>
							</select>
                    	</td>
                    	<td><button class="btn-large" style="width: 80px;" type="submit" name="updateprice">Update</button></td>
                	</form>
                		<?php
                    		if ($ligneM->statupresta==1) {
						?>
							<td>
                                <a href="edit_prices.php?id_prestation=<?php echo  $ligneM->id_prestation;?>&nompresta=<?php echo  $ligneM->nompresta;?>&assurances_name=<?php echo  $assurances_name;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>&desactifbtn=ok&divCash=ok">Active<input type="image" style="border-radius:100px;" src="images/onBlue.png" name="desactifbtn"/></a>
                            </td>
						<?php			                    			
                    		}else{
                    	?>
                    		<td>
                                <a href="edit_prices.php?id_prestation=<?php echo  $ligneM->id_prestation;?>&nompresta=<?php echo  $ligneM->nompresta;?>&assurances_name=<?php echo  $assurances_name;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>&actifbtn=ok&divCash=ok">Desactive<input type="image" style="border-radius:100px;" src="images/offBlue.png" name="actifbtn"/></a>
                            </td>
                    	<?php
                    		}
                    	?>
                    </tr>
                    <?php
                }
                $resultatsM->closeCursor();
                ?>

                </tbody>

            </table>
            <?php
            $link = 'prices_edit.php';
        ?>

        <a href="<?php echo $link;?>?id_prestation=<?php echo $id_prestation;?>&assurances_name=<?php echo $_GET['assurances_name']?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Caissiers" class="btn-large">Show All</a>
        <?php
    }
    ?>
    <?php 
    if(isset($_GET['callmodal'])){
  ?>
      <!-- The Modal  -->
      <div id="myModal2" class="modal2">
        <!--  Modal content  -->
        <div class="modal-content2">
          <a href="prices_edit.php?assurances_name=<?php echo $_GET['assurances_name'];?>"><span class="closeS"><i class="fa fa-close"></i></span></a>
          <span class="modal-title"><span style="color: green;font-size: 20px;"></span> <i class="fa fa-plus-circle fa-0.5x fa-fw"></i>Prestation Modification </span>
          <hr>

          	<!-- <form method="POST">
            	<table class="table table-collapse" style="border: 1px solid #ddd;color: #222;">
              		<thead style="background: #ddd;">
		                <th style="text-align: center;">Nom de Prestation</th>
		                <th style="text-align: center;">Name Prestation</th>
		                <th style="text-align: center;">Price</th>
		                <th style="text-align: center;">Price CCO</th>
		                <th style="text-align: center;">Category</th>
		                <th style="text-align: center;">UM</th>
              		</thead>
              		<tbody>
                		<tr>
			                <td>
			                	<input type="text" name="nompresta" class="form-input" style="width: 120px;" value="<?= $LignePresta->nompresta;?>">
			                </td>
			                <td>
			                	<input type="text" name="namepresta" class="form-input" style="width: 120px;" value="<?= $LignePresta->namepresta;?>">
			                </td>
			                <td>
			                	<input type="text" name="pricepresta" class="form-input" style="width: 120px;" value="<?= $LignePresta->prixpresta;?>" style="text-align: center;">
			                </td>
			                <td>
			                	<input type="text" name="pricepresta" class="form-input" style="width: 120px;" value="<?= $LignePresta->prixprestaCCO;?>" style="text-align: center;">
			                </td>
			                <td>
			                    <select name="Category" class="form-input" style="width: 120px;">
			                      <option value="">Select Category</option>
			                      <?php while($lignecatego  = $catego->fetch()){?>
			                        <option value="<?= $lignecatego->id_categopresta;?>" <?php if($lignecatego->id_categopresta == $LignePresta->id_categopresta){echo "selected=selected";} ?>><?= $lignecatego->nomcategopresta;?></option>
			                      <?php }?>
			                    </select>
			                </td>
			                <td>
			                    <select name="um" class="form-input" style="width: 120px;">
			                      	<option value="">Select Unite Mesure</option>
									<option value='Ampoule' <?php if($LignePresta->mesure=='Ampoule') {echo 'selected="selected"';}?>>Ampoule</option>
									<option value='Bandes' <?php if($LignePresta->mesure=='Bandes') {echo 'selected="selected"';}?>>Bandes</option>
									<option value='Boite' <?php if($LignePresta->mesure=='Boite') {echo 'selected="selected"';}?>>Boite</option>
									<option value="Boite" <?php if($LignePresta->mesure=='Bandes') {echo 'selected="selected"';}?>>Comprimes</option>
									<option value='Flancon' <?php if($LignePresta->mesure=='Flancon') {echo 'selected="selected"';}?>>Flancon</option>
									<option value='Jour' <?php if($LignePresta->mesure=='Jour') {echo 'selected="selected"';}?>>Jour</option>
									<option value='Litres' <?php if($LignePresta->mesure=='Litres') {echo 'selected="selected"';}?>>Litres</option>
									<option value='Ml' <?php if($LignePresta->mesure=='Ml') {echo 'selected="selected"';}?>>Ml</option>
									<option value='Paire' <?php if($LignePresta->mesure=='Paire') {echo 'selected="selected"';}?>>Paire</option>
									<option value='Pieces' <?php if($LignePresta->mesure=='Pieces') {echo 'selected="selected"';}?>>Pieces</option>
									<option value='Seance' <?php if($LignePresta->mesure=='Seance') {echo 'selected="selected"';}?>>Seance</option>
			                    </select>
			                </td>
              			</tr>
              		</tbody>
            	</table>
            	<button class="btn btn-danger" style="float: right;" name="Deletebtn">Delete</button>
            	<button class="btn btn-success" style="float: right;" name="updatePresta">Update</button>
            	<hr>
          	</form> -->
          	<?php
          		if ($LignePresta->id_categopresta==1 OR $LignePresta->id_categopresta==2) {
          			echo "Cette prestation ne peut pas être supprimé!!!";
          		}else{

          			$nomassurance = strtoupper($_GET['assurances_name']);
	          		$selectedInsId = $connexion->prepare('SELECT * FROM assurances WHERE nomassurance=:nomassurance');
	          		$selectedInsId->execute(array(
	          			'nomassurance'=>$nomassurance
	          		));
	          		$selectedInsId->setFetchMode(PDO::FETCH_OBJ);
	          		$rowInsId = $selectedInsId->rowCount();
	          		if ($rowInsId != 0) {
		          		$ligneInsId = $selectedInsId->fetch();

		          		$idprestationRe = $_GET['id_prestation'];
		          		$nomprestaRe = $_GET['nompresta'];
		          		$prestation = strtolower('prestations_'.$_GET['assurances_name']);
						$idassuR = $ligneInsId->id_assurance;

						$selectacteConsom =$connexion->query('SELECT * FROM med_consom WHERE id_prestationConsom='.$idprestationRe.' AND 	id_assuConsom='.$idassuR.'');
						$selectacteConsom->setFetchMode(PDO::FETCH_OBJ);
						$rowConsom = $selectacteConsom->rowCount();
						/*while ($ligneacteConsom = $selectacteConsom->fetch()) {
							echo "<br>id_medconsom = ".$ligneacteConsom->id_medconsom;
						}*/

						$selectacteConsomH =$connexion->query('SELECT * FROM med_consom_hosp WHERE id_prestationConsom='.$idprestationRe.' AND id_assuConsom='.$idassuR.'');
						$selectacteConsomH->setFetchMode(PDO::FETCH_OBJ);
						$rowConsomH = $selectacteConsomH->rowCount();
						/*while ($ligneacteConsomH = $selectacteConsomH->fetch()) {
							echo "<br>id_medconsom hosp = ".$ligneacteConsomH->id_medconsom;
						}*/

						$selectacteConsu =$connexion->query('SELECT * FROM med_consult WHERE id_prestationConsu='.$idprestationRe.' AND id_assuServ='.$idassuR.'');
						$selectacteConsu->setFetchMode(PDO::FETCH_OBJ);
						$rowConsu = $selectacteConsu->rowCount();
						/*while ($ligneacteConsu = $selectacteConsu->fetch()) {
							echo "<br>idmedServ = ".$ligneacteConsu->id_medconsu;
						}*/

						$selectacteConsuH =$connexion->query('SELECT * FROM med_consult_hosp WHERE id_prestationConsu='.$idprestationRe.' AND id_assuServ='.$idassuR.'');
						$selectacteConsuH->setFetchMode(PDO::FETCH_OBJ);
						$rowConsuH = $selectacteConsuH->rowCount();
						/*while ($ligneacteConsuH = $selectacteConsuH->fetch()) {
							echo "<br>idmedServ hosp = ".$ligneacteConsuH->id_medconsu;
						}*/

						$selectacteInf =$connexion->query('SELECT * FROM med_inf WHERE id_prestation='.$idprestationRe.' AND id_assuInf='.$idassuR.'');
						$selectacteInf->setFetchMode(PDO::FETCH_OBJ);
						$rowInf = $selectacteInf->rowCount();
						/*while ($ligneacteInf = $selectacteInf->fetch()) {
							echo "<br>id_medinf = ".$ligneacteInf->id_medinf;
						}*/

						$selectacteInfH =$connexion->query('SELECT * FROM med_inf_hosp WHERE id_prestation='.$idprestationRe.' AND id_assuInf='.$idassuR.'');
						$selectacteInfH->setFetchMode(PDO::FETCH_OBJ);
						$rowInfH = $selectacteInfH->rowCount();
						/*while ($ligneacteInfH = $selectacteInfH->fetch()) {
							echo "<br>id_medinf hosp = ".$ligneacteInfH->id_medinf;
						}*/

						$selectacteKine =$connexion->query('SELECT * FROM med_kine WHERE id_prestationKine='.$idprestationRe.' AND id_assuKine='.$idassuR.'');
						$selectacteKine->setFetchMode(PDO::FETCH_OBJ);
						$rowKine = $selectacteKine->rowCount();
						/*while ($ligneacteKine = $selectacteKine->fetch()) {
							echo "<br>id_medkine = ".$ligneacteKine->id_medkine;
						}*/

						$selectacteKineH =$connexion->query('SELECT * FROM med_kine_hosp WHERE id_prestationKine='.$idprestationRe.' AND id_assuKine='.$idassuR.'');
						$selectacteKineH->setFetchMode(PDO::FETCH_OBJ);
						$rowKineH = $selectacteKineH->rowCount();
						/*while ($ligneacteKineH = $selectacteKineH->fetch()) {
							echo "<br>id_medkine hosp = ".$ligneacteKineH->id_medkine;
						}*/

						$selectacteLabo =$connexion->query('SELECT * FROM med_labo WHERE id_prestationExa='.$idprestationRe.' AND id_assuLab='.$idassuR.'');
						$selectacteLabo->setFetchMode(PDO::FETCH_OBJ);
						$rowLabo = $selectacteLabo->rowCount();
						/*while ($ligneacteLabo = $selectacteLabo->fetch()) {
							echo "<br>id_medlabo = ".$ligneacteLabo->id_medlabo;
						}*/

						$selectacteLaboH =$connexion->query('SELECT * FROM med_labo_hosp WHERE id_prestationExa='.$idprestationRe.' AND id_assuLab='.$idassuR.'');
						$selectacteLaboH->setFetchMode(PDO::FETCH_OBJ);
						$rowLaboH = $selectacteLaboH->rowCount();
						/*while ($ligneacteLaboH = $selectacteLaboH->fetch()) {
							echo "<br>id_medlabo hosp = ".$ligneacteLaboH->id_medlabo;
						}*/

						$selectacteMedoc =$connexion->query('SELECT * FROM med_medoc WHERE id_prestationMedoc='.$idprestationRe.' AND id_assuMedoc='.$idassuR.'');
						$selectacteMedoc->setFetchMode(PDO::FETCH_OBJ);
						$rowMedoc = $selectacteMedoc->rowCount();
						/*while ($ligneacteMedoc = $selectacteMedoc->fetch()) {
							echo "<br>id_medmedoc = ".$ligneacteMedoc->id_medmedoc;
						}*/

						$selectacteMedocH =$connexion->query('SELECT * FROM med_medoc_hosp WHERE id_prestationMedoc='.$idprestationRe.' AND id_assuMedoc='.$idassuR.'');
						$selectacteMedocH->setFetchMode(PDO::FETCH_OBJ);
						$rowMedocH = $selectacteMedocH->rowCount();
						/*while ($ligneacteMedocH = $selectacteMedocH->fetch()) {
							echo "<br>id_medmedoc hosp = ".$ligneacteMedocH->id_medmedoc;
						}*/

						$selectacteOrtho =$connexion->query('SELECT * FROM med_ortho WHERE id_prestationOrtho='.$idprestationRe.' AND id_assuOrtho='.$idassuR.'');
						$selectacteOrtho->setFetchMode(PDO::FETCH_OBJ);
						$rowOrtho = $selectacteOrtho->rowCount();
						/*while ($ligneacteOrtho = $selectacteOrtho->fetch()) {
							echo "<br>id_medortho = ".$ligneacteOrtho->id_medortho;
						}*/

						$selectacteOrthoH =$connexion->query('SELECT * FROM med_ortho_hosp WHERE id_prestationOrtho='.$idprestationRe.' AND id_assuOrtho='.$idassuR.'');
						$selectacteOrthoH->setFetchMode(PDO::FETCH_OBJ);
						$rowOrthoH = $selectacteOrthoH->rowCount();
						/*while ($ligneacteOrthoH = $selectacteOrthoH->fetch()) {
							echo "<br>id_medortho hosp = ".$ligneacteOrthoH->id_medortho;
						}*/

						$selectacteRadio =$connexion->query('SELECT * FROM med_radio WHERE id_prestationRadio='.$idprestationRe.' AND id_assuRad='.$idassuR.'');
						$selectacteRadio->setFetchMode(PDO::FETCH_OBJ);
						$rowRadio = $selectacteRadio->rowCount();
						/*while ($ligneacteRadio = $selectacteRadio->fetch()) {
							echo "<br>id_medradio = ".$ligneacteRadio->id_medradio;
						}*/

						$selectacteRadioH =$connexion->query('SELECT * FROM med_radio_hosp WHERE id_prestationRadio='.$idprestationRe.' AND id_assuRad='.$idassuR.'');
						$selectacteRadioH->setFetchMode(PDO::FETCH_OBJ);
						$rowRadioH = $selectacteRadioH->rowCount();
						/*while ($ligneacteRadioH = $selectacteRadioH->fetch()) {
							echo "<br>id_medradio hosp = ".$ligneacteRadioH->id_medradio;
						}*/

						$selectacteSurge =$connexion->query('SELECT * FROM med_surge WHERE id_prestationSurge='.$idprestationRe.' AND id_assuSurge='.$idassuR.'');
						$selectacteSurge->setFetchMode(PDO::FETCH_OBJ);
						$rowSurge = $selectacteSurge->rowCount();
						/*while ($ligneacteSurge = $selectacteSurge->fetch()) {
							echo "<br>id_medsurge = ".$ligneacteSurge->id_medsurge;
						}*/

						$selectacteSurgeH =$connexion->query('SELECT * FROM med_surge_hosp WHERE id_prestationSurge='.$idprestationRe.' AND id_assuSurge='.$idassuR.'');
						$selectacteSurgeH->setFetchMode(PDO::FETCH_OBJ);
						$rowSurgeH = $selectacteSurgeH->rowCount();
						/*while ($ligneacteSurgeH = $selectacteSurgeH->fetch()) {
							echo "<br>id_medsurge hosp = ".$ligneacteSurgeH->id_medsurge;
						}*/

						if ($rowConsom==0 AND $rowConsomH==0 AND $rowConsu==0 AND $rowConsuH==0 AND $rowInf==0 AND $rowInfH==0 AND $rowKine==0 AND $rowKineH==0 AND $rowLabo==0 AND $rowLaboH==0 AND $rowMedoc==0 AND $rowMedocH==0 AND $rowOrtho==0 AND $rowOrthoH==0 AND $rowRadio==0 AND $rowRadioH==0 AND $rowSurge==0 AND $rowSurgeH==0) {
							echo "prestation a supprime = ".$nomprestaRe." avec id_prestation =".$idprestationRe."<br>";

							/*$deleteprestation = $connexion->prepare('DELETE FROM '.$prestation.' WHERE id_prestation=:id_prestation');
							$deleteprestation->execute(array('id_prestation'=>$idprestationRe));*/
						}else{
							echo "Non il faut remplacer la prestation avant de le supprimer!!!<br> <br>";
							echo "Sélectionner la prestation qui va le remplacer";
				?>
							<form action="prices_edit.php?id_prestation=<?php echo  $LignePresta->id_prestation;?>&nompresta=<?php echo  $LignePresta->nompresta;?>&assurances_name=<?php echo $_GET['assurances_name'];?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>" method="post" enctype="multipart/form-data">
								<table class="cons-info" cellpadding=10 style="margin:0;margin:20px auto auto auto;background:#fff;width:90%;">
									<tr>
										<td style="text-align:right;">Prestation </td>
										<td style="text-align:left;">
											<!-- <select name="OGPresta" id="OGPresta" class="chosen-select" multiple="multiple">
												<?php
													//$bd=$connexion->query("SELECT * FROM ".$prestation." p WHERE p.id_prestation!=".$idprestationRe." AND (p.sourceEFT!="" OR p.sourceADT!="") ORDER BY p.id_prestation");
													$bd=$connexion->query("SELECT * FROM ".$prestation." p WHERE p.id_prestation!=".$idprestationRe." ORDER BY p.id_prestation");
													while ($ligne=$bd->fetch(PDO::FETCH_OBJ))
													{
												?>
														<option value="<?php echo $ligne->id_prestation;?>">
															<?php
																if ($ligne->nompresta!="") {
																 	echo $ligne->nompresta;
																}elseif ($ligne->namepresta!="") {
																	echo $ligne->namepresta;
																} 
															?>
														</option>	
												<?php
													}
													$bd->closeCursor();
												?>
											</select> -->
											<select style="margin:auto; width: 500px;" multiple="multiple" name="OGPresta[]" class="chosen-select" id="OGPresta">

				                                <?php
				                                  
				                                	$bd=$connexion->query("SELECT * FROM ".$prestation." p WHERE p.id_prestation!=".$idprestationRe." ORDER BY p.id_prestation");
													while ($ligne=$bd->fetch(PDO::FETCH_OBJ))
													{
												?>
														<option value="<?php echo $ligne->id_prestation;?>">
															<?php
																if ($ligne->nompresta!="") {
																 	echo $ligne->nompresta;
																}elseif ($ligne->namepresta!="") {
																	echo $ligne->namepresta;
																} 
															?>
														</option>	
												<?php
													}
													$bd->closeCursor();
												?>
			                                  
			                                    
			                                </select>
											<br/>
											<input name="oldPresta" id="oldPresta" type="hidden" style="width:200px;" value="<?php echo $idprestationRe; ?>">
											<input name="insId" id="insId" type="hidden" style="width:200px;" value="<?php echo $idassuR; ?>">
										</td>
									</tr>
				            		<button class="btn btn-large" style="float: right;" name="replacePresta">Replacer</button>
								</table>
							</form>

				<?php
						}
	          		}
          		}
          	?>
        </div>
      </div>
  <?php }?>   
        
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
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		
		$('#idMed').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
			
		function ShowAutrePa(){
			
			var patient=document.getElementById('OGPresta').value;
			
			if(patient=="0")
			{
				document.getElementById('OGPresta').style.display='inline';
				document.getElementById('newTelPa').style.display='inline';
			}else{
				document.getElementById('OGPresta').style.display='none';
				document.getElementById('newTelPa').style.display='none';
			}
			
		}
	</script>
	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
    var q;

    for(q=0;q<5;q++)
    {
        $('#idExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#checkExam'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#checkSousCatego'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#prixref'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
        $('#editAssu'+q).chosen({width:"250px", search_contains: true, inherit_select_classes: true});
    }
    $('#OGPresta').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
</script>
<script type="text/javascript">
      function closing() {
        document.getElementById('myModal').style.display='none';
      }

        // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal2) {
          modal2.style.display = "none";
        }
      }
    </script>
	
</html>
