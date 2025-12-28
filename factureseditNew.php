<?php
session_start();

include("connectLangues.php");
include("connect.php");
	$annee = date('Y').'-'.date('m').'-'.date('d');


if(isset($_GET['deletebill']))
{


	$id_Consu = $_GET['idconsu'];
	$id_Bill = $_GET['idbill'];
	$numbill = $_GET['numbill'];
	
	$deleteConsult=$connexion->prepare('DELETE FROM consultations WHERE id_consu=:idconsu');
	
	$deleteConsult->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));
		
		$deleteBill=$connexion->prepare('DELETE FROM bills WHERE id_bill=:id_Bill OR numbill=:numbill');
		
		$deleteBill->execute(array(
		'id_Bill'=>$id_Bill,
		'numbill'=>$numbill
		
		))or die( print_r($connexion->errorInfo()));


	$deleteConsu=$connexion->prepare('DELETE FROM med_consult WHERE id_consuMed=:idconsu');
	
	$deleteConsu->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteSurge=$connexion->prepare('DELETE FROM med_surge WHERE id_consuSurge=:idconsu');
	
	$deleteSurge->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteInf=$connexion->prepare('DELETE FROM med_inf WHERE id_consuInf=:idconsu');
	
	$deleteInf->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));

	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo WHERE id_consuLabo=:idconsu');
	
	$deleteLabo->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));

	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio WHERE id_consuRadio=:idconsu');
	
	$deleteRadio->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteKine=$connexion->prepare('DELETE FROM med_kine WHERE id_consuKine=:idconsu');
	
	$deleteKine->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteOrtho=$connexion->prepare('DELETE FROM med_ortho WHERE id_consuOrtho=:idconsu');
	
	$deleteOrtho->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));
	
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom WHERE id_consuConsom=:idconsu');
	
	$deleteConsom->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));

	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc WHERE id_consuMedoc=:idconsu');
	
	$deleteMedoc->execute(array(
	'idconsu'=>$id_Consu
	
	))or die( print_r($connexion->errorInfo()));
	
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer la facture n° '.$_GET['numbill'].' de la clinic");</script>';
	
	echo '<script type="text/javascript">document.location.href="facturesedit.php?manager='.$_GET['manager'].'";</script>';

	
}

if(isset($_GET['deletebillhosp']))
{

	$idhosp = $_GET['idhosp'];
	$numbill = $_GET['numbill'];
	
	$deleteConsult=$connexion->prepare('DELETE FROM patients_hosp WHERE id_hosp=:idhosp');
	
	$deleteConsult->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	
	
	$deleteConsu=$connexion->prepare('DELETE FROM med_consult_hosp WHERE id_hospMed=:idhosp');
	
	$deleteConsu->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteSurge=$connexion->prepare('DELETE FROM med_surge_hosp WHERE id_hospSurge=:idhosp');
	
	$deleteSurge->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteInf=$connexion->prepare('DELETE FROM med_inf_hosp WHERE id_hospInf=:idhosp');
	
	$deleteInf->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));

	
	$deleteLabo=$connexion->prepare('DELETE FROM med_labo_hosp WHERE id_hospLabo=:idhosp');
	
	$deleteLabo->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));

	
	$deleteRadio=$connexion->prepare('DELETE FROM med_radio_hosp WHERE id_hospRadio=:idhosp');
	
	$deleteRadio->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteKine=$connexion->prepare('DELETE FROM med_kine_hosp WHERE id_hospKine=:idhosp');
	
	$deleteKine->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	

	$deleteOrtho=$connexion->prepare('DELETE FROM med_ortho_hosp WHERE id_hospOrtho=:idhosp');
	
	$deleteOrtho->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	
	
	$deleteConsom=$connexion->prepare('DELETE FROM med_consom_hosp WHERE id_hospConsom=:idhosp');
	
	$deleteConsom->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));

	
	$deleteMedoc=$connexion->prepare('DELETE FROM med_medoc_hosp WHERE id_hospMedoc=:idhosp');
	
	$deleteMedoc->execute(array(
	'idhosp'=>$idhosp
	
	))or die( print_r($connexion->errorInfo()));
	
	
	echo '<script type="text/javascript"> alert("Vous venez de supprimer la facture n° '.$_GET['numbill'].' de l\'Hôpital");</script>';
	
	echo '<script type="text/javascript">document.location.href="facturesedit.php?manager='.$_GET['manager'].'";</script>';

	
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
		font-family: Century Gothic;
	}
</style>
	
</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlCash=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");


$comptidCoord=$sqlCash->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidCoord!=0)
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
					<form method="post" action="facturesedit.php?<?php if(isset($_GET['codeCoord'])){ echo 'codeCoord='.$_GET['codeCoord'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
						<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
						
						<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
						
						<?php
						if($langue == 'francais')
						{
						?>
							<a href="facturesedit.php?english=english<?php if(isset($_GET['codeCoord'])){ echo '&codeAcc='.$_GET['codeCoord'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?>" class="btn"><?php echo getString(30);?></a>
						<?php
						}else{
						?>
							<a href="facturesedit.php?francais=francais<?php if(isset($_GET['codeCoord'])){ echo '&codeAcc='.$_GET['codeCoord'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?>" class="btn"><?php echo getString(29);?></a>
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
	<div style="text-align:center;margin:20px;">
		
		<a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reports';?>
		</a>

		<a href="dettesList.php?<?php if(isset($_SESSION['codeCash'])){ echo 'cashier='.$_SESSION['codeCash'];}?><?php if(isset($_SESSION['codeC'])){ echo 'coordi='.$_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Dettes';?>
		</a>

	</div>
	
<?php
}
?>

<div class="account-container" style="width:95%;margin:auto; text-align:center;">

	<div id='cssmenu' style="text-align:center;margin:10px;">

		<ul>
			<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-1x fa-fw"></i> <?php echo getString(48);?></a></li>
			
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


		<ul style="margin-top:20px;background:none;border:none;">

			<div id="divMenuUser" style="display:inline-block;">
			
				<a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" id="newUser" style="display:none;"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(87);?></a>
			
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

		
			<div style="display:none;margin-top:50px;" id="divListe" align="center">

				<br/>

				<a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&listPa=1<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(20);?></a>
				
				<a href="medecins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(19);?></a>
				
				<a href="infirmiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(21);?></a>
				
				<a href="laborantins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(22);?></a>
				
				<a href="radiologues1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Radiologue';?></a>
				
				<a href="receptionistes1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(40);?></a>
				
				<a href="caissiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(23);?></a>
				
				<a href="coordinateurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Manager';?></a>
				
			</div>
		
	</div>
	
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
			url : 'search_facturesedit.php?date=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
			url : 'search_facturesedit.php?name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
			url : 'search_facturesedit.php?sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
			url : 'search_facturesedit.php?bn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
		
		$resultatsBills=$connexion->prepare('SELECT *FROM bills b, consultations c WHERE c.id_factureConsult=b.id_bill AND b.id_bill=:idBill');
		$resultatsBills->execute(array(
		'idBill'=>$_GET['idbill']
		))or die( print_r($connexion->errorInfo()));
	
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
						<a href="reprintbill.php?num=<?php echo $ligneBill->numero;?>&idbill=<?php echo $ligneBill->id_bill;?>&idconsu=<?php echo $ligneBill->id_consu;?>&idmed=<?php echo $ligneBill->id_uM;?>&dateconsu=<?php echo $ligneBill->dateconsu;?>&idtypeconsu=<?php echo $ligneBill->id_typeconsult;?>&idassu=<?php echo $ligneBill->id_assuConsu;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligneBill->datebill;?>&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneBill->numbill;?></a>
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
						<a href="formModifierBill.php?num=<?php echo $ligneBill->numero;?>&numbill=<?php echo $ligneBill->numbill;?>&idconsu=<?php echo $ligneBill->id_consu;?>&idmed=<?php echo $ligneBill->id_uM;?>&dateconsu=<?php echo $ligneBill->dateconsu;?>&idtypeconsu=<?php echo $ligneBill->id_typeconsult;?>&idassu=<?php echo $ligneBill->id_assuConsu;?>&nomassurance=<?php echo $ligneBill->nomassurance;?>&billpercent=<?php echo $ligneBill->billpercent;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligneBill->datebill;?>&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Modifier';?></a>
					</td>
					
					<td>
						<a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligneBill->numbill;?>&deletebill=ok&idconsu=<?php echo $ligneBill->id_consu;?>&idbill=<?php echo $ligneBill->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligneBill->numbill;?>"><i class="fa fa-trash fa-1x fa-fw"></i></a>
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
			
			$resultats=$connexion->query("SELECT *FROM bills b, consultations c WHERE c.id_factureConsult=b.id_bill AND b.status=0 AND b.numero!='NULL' AND c.dateconsu='".$annee."' ORDER BY b.numbill DESC") or die( print_r($connexion->errorInfo()));

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
				$id_consu = 0;
				$id_bill = 0;
				$numbill = 0;
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
						<td><?php echo $ligne->datebill;?></td>
						
						<td>
							<a href="reprintbill.php?num=<?php echo $ligne->numero;?>&idbill=<?php echo $ligne->id_bill;?>&idconsu=<?php echo $ligne->id_consu;?>&idmed=<?php echo $ligne->id_uM;?>&dateconsu=<?php echo $ligne->dateconsu;?>&idtypeconsu=<?php echo $ligne->id_typeconsult;?>&idassu=<?php echo $idassuBill;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligne->datebill;?>&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligne->numbill;?></a>
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
							<a href="formModifierBill.php?num=<?php echo $ligne->numero;?>&numbill=<?php echo $ligne->numbill;?>&idconsu=<?php echo $ligne->id_consu;?>&idmed=<?php echo $ligne->id_uM;?>&dateconsu=<?php echo $ligne->dateconsu;?>&idtypeconsu=<?php echo $ligne->id_typeconsult;?>&idassu=<?php echo $idassuBill;?>&nomassurance=<?php echo $nomassuBill;?>&billpercent=<?php echo $ligne->billpercent;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligne->datebill;?>&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Modifier';?></a>
						</td>
						
						<td>
							<!-- <a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligne->numbill;?>&deletebill=ok&idconsu=<?php echo $ligne->id_consu;?>&idbill=<?php echo $ligne->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligne->numbill;?>"><i class="fa fa-trash fa-1x fa-fw"></i></a> -->
							<?php
								$numbill = $ligne->numbill;
								$id_consu = $ligne->id_consu;
								$id_bill = $ligne->id_bill;
							?>
							<span class="btn" id="myBtn2" onclick="displayModal('show')" title="Supprimer la facture n° <?php echo $ligne->numbill;?>" style="margin-left:10px;"><i class="fa fa-trash fa-1x fa-fw"></i></span>
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
			url : 'search_factureshospedit.php?datehosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
			url : 'search_factureshospedit.php?namehosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
			url : 'search_factureshospedit.php?snhosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
			url : 'search_factureshospedit.php?bnhosp=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
			
			$resultatshosp=$connexion->prepare("SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:id_hosp") or die( print_r($connexion->errorInfo()));
			$resultatshosp->execute(array(
			'id_hosp'=>$_GET['id_hosp']
			))or die( print_r($connexion->errorInfo()));

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
					$idhosp = 0;
					$id_bill = 0;
					$numbill = 0;
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
								<a href="formModifierBillHosp.php?num=<?php echo $ligne->numero;?>&numbill=<?php echo $ligne->id_factureHosp;?>&idhosp=<?php echo $ligne->id_hosp;?>&datehosp=<?php echo $ligne->dateEntree;?>&numroom=<?php echo $ligne->numroomPa;?>&numlit=<?php echo $ligne->numlitPa;?>&idassu=<?php echo $ligne->id_assuHosp;?>&nomassurance=<?php echo $ligne->nomassuranceHosp;?>&billpercent=<?php echo $ligne->insupercent_hosp;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligne->dateSortie;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Modifier';?></a>
							</td>
							
							<td>
								<!-- <a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligne->id_factureHosp;?>&deletebillhosp=ok&idhosp=<?php echo $ligne->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligne->id_factureHosp;?>"><i class="fa fa-trash fa-1x fa-fw"></i></a> -->
								<?php
									$numbill = $ligne->id_factureHosp;
									$idhosp = $ligne->idhosp;
								?>
								<span class="btn" id="myBtn2" onclick="displayModal('show')" title="Supprimer la facture n° <?php echo $ligne->id_factureHosp;?>" style="margin-left:10px;"><i class="fa fa-trash fa-1x fa-fw"></i></span>
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
			
			
			$resultatshosp=$connexion->query("SELECT *FROM patients_hosp ph WHERE ph.id_factureHosp IS NOT NULL AND ph.dateSortie!='0000-00-00' ORDER BY ph.id_factureHosp DESC") or die( print_r($connexion->errorInfo()));

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
							<a href="formModifierBillHosp.php?num=<?php echo $ligne->numero;?>&numbill=<?php echo $ligne->id_factureHosp;?>&idhosp=<?php echo $ligne->id_hosp;?>&datehosp=<?php echo $ligne->dateEntree;?>&numroom=<?php echo $ligne->numroomPa;?>&numlit=<?php echo $ligne->numlitPa;?>&idassu=<?php echo $ligne->id_assuHosp;?>&nomassurance=<?php echo $ligne->nomassuranceHosp;?>&billpercent=<?php echo $ligne->insupercent_hosp;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligne->dateSortie;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo 'Modifier';?></a>
						</td>
						
						<td>
							<!-- <a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $ligne->id_factureHosp;?>&deletebillhosp=ok&idhosp=<?php echo $ligne->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn" title="Supprimer la facture n° <?php echo $ligne->id_factureHosp;?>"><i class="fa fa-trash fa-1x fa-fw"></i></a> -->
							<?php
								$numbill = $ligne->id_factureHosp;
								$idhosp = $ligne->id_hosp;
							?>
							<span class="btn" id="myBtn2" onclick="displayModal('show')" title="Supprimer la facture n° <?php echo $ligne->id_factureHosp;?>" style="margin-left:10px;"><i class="fa fa-trash fa-1x fa-fw"></i></span>
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
	<!-- The Modal1 -->
	<div id="myModal2" class="modal flashing">

	  <!-- Modal content -->
	  <div class="modal-content">
	    <span class="close2" onclick="displayModal('close')" ><i class="fa fa-close"></i></span>
	    <p id="Content" style="text-align: center;color: #A00000;border-bottom: 1px solid #ddd;">Are You Sure You Want To Delete This Bill?</p>
	   
	    <div id="Info" style="margin-left: 35%;margin-top: 40px;">
	    	<?php
	    		if (isset($idhosp)) {
	    	?>
	    		<a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?>&deletebillhosp=ok&idhosp=<?php echo $idhosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" title="Supprimer la facture n° <?php echo $numbill;?>"><i class="fa fa-trash fa-1x fa-fw"></i>Yes</a>
	    	<?php
	    		}else{
	    	?>
	    		<a href="facturesedit.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?>&deletebill=ok&idconsu=<?php echo $id_consu;?>&idbill=<?php echo $id_bill;?>" class="btn-large" title="Supprimer la facture n° <?php echo $numbill;?>"><i class="fa fa-trash fa-1x fa-fw"></i>Yes</a>
	    	<?php
	    		}
	    	?>
			    				

			<!-- <span class="btn-large" onclick="displayModal('motif')"><i class="fa fa-trash fa-1x fa-fw"></i>Yes</span>
-->
			<span class="btn-large-inversed no" onclick="displayModal('hide')"  title="No" style="margin-left:10px;"><i class="fa fa-ban fa-1x fa-fw"></i>No</span>
	    		
	    </div>

	    
		<!-- <div id="formDiv" style="display: none;">
		    	<form method="POST" action="deletebill.php?manager=<?php echo $_SESSION['codeC'];?>&numbill=<?php echo $numbill;?>&deletebill=ok&idconsu=<?php echo $_GET['idconsu'];?>&idbill=<?php echo $idBilling;?>">
		    		<textarea class="form-control" name="motif_content" style="height: 100px;"></textarea>
		    		<button class="btn-large" type="submit" name="motif" style="position: relative;left: 100px;width: 300px;">Save</button>
		    	</form>
		    </div> -->
	  </div>
	
	
<script>

// Get the modal
var modal = document.getElementById("myModal");
var modal2 = document.getElementById("myModal2");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
var span2 = document.getElementsByClassName("close2")[0];
var no = document.getElementsByClassName("no")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

function displayModal(fld){
	if (fld=='show') {
		modal2.style.display = "block";
	}	
	if (fld=='hide') {
		modal2.style.display = "none";
	}	
	if (fld=='close') {
		modal2.style.display = "none";
		document.getElementById('formDiv').style.display="none";
		document.getElementById('Info').style.display="block";
		document.getElementById('Content').innerHTML="Are You Sure You Want To Delete This Bill?";

	}	

	if (fld=='motif') {
		//modal2.style.display = "none";
		document.getElementById('formDiv').style.display="block";
		document.getElementById('Info').style.display="none";
		document.getElementById('Content').innerHTML="Why Do You Want To Delete This Bill?";
	}
}
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
  modal2.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
  
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
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


	function ShowSearch(search)
	{
		if(search =='bydate')
		{
			document.getElementById('resultsDate').style.display='inline';
			document.getElementById('resultsName').style.display='none';
			document.getElementById('resultsSN').style.display='none';
			document.getElementById('resultsBN').style.display='none';
		}
		
		if(search =='byname')
		{
			document.getElementById('resultsDate').style.display='none';
			document.getElementById('resultsName').style.display='inline';
			document.getElementById('resultsSN').style.display='none';
			document.getElementById('resultsBN').style.display='none';
		}
		
		if(search =='bysn')
		{
			document.getElementById('resultsDate').style.display='none';
			document.getElementById('resultsName').style.display='none';
			document.getElementById('resultsSN').style.display='inline';
			document.getElementById('resultsBN').style.display='none';
		}
		
		if(search =='bybn')
		{
			document.getElementById('resultsDate').style.display='none';
			document.getElementById('resultsName').style.display='none';
			document.getElementById('resultsSN').style.display='none';
			document.getElementById('resultsBN').style.display='inline';
		}
		
		if(search =='bydateh')
		{
			document.getElementById('resultsDateH').style.display='inline';
			document.getElementById('resultsNameH').style.display='none';
			document.getElementById('resultsSNH').style.display='none';
			document.getElementById('resultsBNH').style.display='none';
		}
		
		if(search =='bynameh')
		{
			document.getElementById('resultsDateH').style.display='none';
			document.getElementById('resultsNameH').style.display='inline';
			document.getElementById('resultsSNH').style.display='none';
			document.getElementById('resultsBNH').style.display='none';
		}
		
		if(search =='bysnh')
		{
			document.getElementById('resultsDateH').style.display='none';
			document.getElementById('resultsNameH').style.display='none';
			document.getElementById('resultsSNH').style.display='inline';
			document.getElementById('resultsBNH').style.display='none';
		}
		
		if(search =='bybnh')
		{
			document.getElementById('resultsDateH').style.display='none';
			document.getElementById('resultsNameH').style.display='none';
			document.getElementById('resultsSNH').style.display='none';
			document.getElementById('resultsBNH').style.display='inline';
		}
	}

	function ShowList(list)
	{
		if( list =='Users')
		{
			document.getElementById('divMenuUser').style.display='inline-block';
			
			document.getElementById('listOn').style.display='inline';
			document.getElementById('newUser').style.display='inline';
			
			document.getElementById('listOff').style.display='none';
			
			document.getElementById('divMenuMsg').style.display='none';
			document.getElementById('divListe').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divListe').style.display='none';
			document.getElementById('divMenuMsg').style.display='inline-block';
			document.getElementById('divMenuUser').style.display='none';
			
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


</script>
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
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
  font-family: century Gothic;
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

</body>

</html>