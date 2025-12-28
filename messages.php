<?php
session_start();

include("connect.php");
include("connectLangues.php");


$annee = date('Y').'-'.date('m').'-'.date('d');

if(isset($_GET['deleteIDconsu']))
{
	$idconsu=$_GET['deleteIDconsu'];
	
	
	/*-----------Delete From Mailry------------*/
	
	$getIdInf=$connexion->prepare('SELECT *FROM med_inf WHERE id_consuInf=:id_medI');
	$getIdInf->execute(array(
	'id_medI'=>$idconsu
	));
	
	$getIdInf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		
	$compteurInf=$getIdInf->rowCount();
		
	if($compteurInf!=0)
	{	

		$deleteInf=$connexion->prepare('DELETE FROM med_inf WHERE id_consuInf=:id_medI');
			
		$deleteInf->execute(array(
		'id_medI'=>$idconsu
		
		))or die($deleteInf->errorInfo());
	}
	
	
	/*-----------Delete From Labs------------*/
	
	$getIdLabo=$connexion->prepare('SELECT *FROM med_labo WHERE id_consuLabo=:id_medL');
	$getIdLabo->execute(array(
	'id_medL'=>$idconsu
	));
	
	$getIdLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurLabo=$getIdLabo->rowCount();

	if($compteurLabo!=0)
	{
		$deleteLabo=$connexion->prepare('DELETE FROM med_labo WHERE id_consuLabo=:id_medL');
			
		$deleteLabo->execute(array(
		'id_medL'=>$idconsu
		
		))or die($deleteLabo->errorInfo());
	}
	
	
	/*-----------Delete From Med_Consu------------*/
	
	$getIdMedConsu=$connexion->prepare('SELECT *FROM med_consult WHERE id_consuMed=:id_medC');
	$getIdMedConsu->execute(array(
	'id_medC'=>$idconsu
	));
	
	$getIdMedConsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

	$compteurMedConsu=$getIdMedConsu->rowCount();
		
	if($compteurMedConsu!=0)
	{
		$deleteMedConsu=$connexion->prepare('DELETE FROM med_consult WHERE id_consuMed=:id_medC');
		
		$deleteMedConsu->execute(array(
		'id_medC'=>$idconsu
		
		))or die($deleteMedConsu->errorInfo());
	
	}

	 
	$deleteConsu=$connexion->prepare('DELETE FROM consultations WHERE id_consu=:id_medConsu');
		
	$deleteConsu->execute(array(
	'id_medConsu'=>$idconsu
	
	))or die($deleteConsu->errorInfo());
	
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
	<title><?php echo getString(173);?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
	<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<script src="script.js"></script>
			
			<!------------------------------------>
			
			
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
		
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	
	<link href="AdministrationSOMADO/css/form-consultation.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->

	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>

	<!---------------Pagination--------------------->
			
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />		

	<?php if(isset($_GET['recuAll']) OR  isset($_GET['envoye'])  OR  isset($_GET['SpecifiedMail']) ){?>
	   <!-- BOOTSTRAP CORE STYLE  -->
	  <link href="assets/css/bootstrap.css" rel="stylesheet" />
	<?php }?>
	
	<script src="myQuery.js"></script>
	
	<script type="text/javascript">
	
	function controlFormMessage(theForm){
		var rapport="";
		
		rapport +=controlDest(theForm.desti);
		rapport +=controlContenu(theForm.contenu);
		// rapport +=controlContenuRetour(theForm.contenuRetour);

		if (rapport != "") {
		alert("Veuillez corrigez les erreurs suivantes:\n\n" + rapport);
					return false;
		 }
	}

	function controlDest(fld){
		var erreur="";
		// var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
		if(fld.value.trim()==""){
		erreur="Le Destinataire\n";
		fld.style.background="rgba(0,255,0,0.3)";
		}
		
		return erreur;
	}

	function controlContenu(fld){
		var erreur="";
		// var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
		if(fld.value==""){
		erreur="Le Contenu\n";
		fld.style.background="rgba(0,255,0,0.3)";
		}
		
		return erreur;
	}

	function controlContenuRetour(fld){
		var erreur="";
		// var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
		if(fld.value==""){
		erreur="Le Contenu de votre réponse\n";
		fld.style.background="rgba(0,255,0,0.3)";
		}
		
		return erreur;
	}



	</script>
	<style type="text/css">
		body{
			font-family: Century Gothic;
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
					<form method="post" action="messages.php?<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn" style="border:1px solid #ddd;"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="messages.php?english=english<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"style="border:1px solid #ddd;"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="messages.php?francais=francais<?php if(isset($_GET['recu'])){ echo '&recu='.$_GET['recu'];} if(isset($_GET['envoye'])){ echo '&envoye='.$_GET['envoye'];} if(isset($_GET['ecrire'])){ echo '&ecrire='.$_GET['ecrire'];} if(isset($_GET['idMsg'])){ echo '&idMsg='.$_GET['idMsg'];} if(isset($_GET['idMsgRecu'])){ echo '&idMsgRecu='.$_GET['idMsgRecu'];}?>" class="btn"style="border:1px solid #ddd;"><?php echo getString(29);?></a>
					<?php
					}					
					?>
						<br/>						
					
						<input type="text" name="Pass" id="Pass" placeholder="<?php echo getString(26);?>" style="margin-top:10px;margin-bottom:0;height:30px;width: 55%;"/>
						
						<input type="submit" name="confirmPass" id="confirmPass" class="btn" style="border:1px solid #ddd;"  value="<?php echo getString(27);?>"/>
						
					
					</form>
				</li>	
				</ul>
			</div><!--/.nav-collapse -->
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div><br><br><br><br><br>

	<div style="text-align:center;margin-top:20px;">
		<a href="utilisateurs.php" class="btn-large-inversed" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<i class="fa fa-home"></i> <?php echo 'Home';?>
		</a>
	
	</div>
	<br>

<?php

if(isset($_GET['idMsg']))
{
	
	$result=$connexion->prepare('SELECT *FROM messages m WHERE m.id_message=:operation');
	$result->execute(array(
	'operation'=>$_GET['idMsg']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
		$dateMsg=$ligne->datemessage;
					
			$resultDesti=$connexion->query('SELECT *FROM utilisateurs u WHERE u.id_u='.$ligne->receiverId.'') or die( print_r($connexion->errorInfo()));
			
			if($ligneDesti=$resultDesti->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$to = $ligneDesti->nom_u.' '.$ligneDesti->prenom_u;
			}
			$resultDesti->closeCursor();

		$content=strip_tags($ligne->contenu);
		$annexe=$ligne->annexe;
		$objet=$ligne->objet;
		$site=$_GET['idMsg'];
	}
	$result->closeCursor();
}


if(isset($_GET['idMsgRecu']))
{
	$lu=1;

	$resultats=$connexion->prepare('UPDATE messages SET lu=:lu WHERE id_message=:idMsgRecu');
				
	$resultats->execute(array(
	'idMsgRecu'=>$_GET['idMsgRecu'],
	'lu'=>$lu

	));

	$result=$connexion->prepare('SELECT *FROM messages m WHERE m.id_message=:operation');
	$result->execute(array(
	'operation'=>$_GET['idMsgRecu']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
		$dateMsg=$ligne->datemessage;
					
			$resultDesti=$connexion->query('SELECT *FROM utilisateurs u WHERE u.id_u='.$ligne->senderId.'') or die( print_r($connexion->errorInfo()));
			
			if($ligneDesti=$resultDesti->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$to = $ligneDesti->nom_u.' '.$ligneDesti->prenom_u;
			}
			$resultDesti->closeCursor();

		$idDesti=strip_tags($ligne->senderId);
		$content=strip_tags($ligne->contenu);
		$annexe=$ligne->annexe;
		$objet=$ligne->objet;
		$site=$_GET['idMsgRecu'];
	}
	$result->closeCursor();
}

?>

<div class="account-container" style="width:90%; text-align:center;">

<div id='cssmenu' style="text-align:center">

<ul style="margin-top:20px;background:none;border:none;">

<?php

$id=$_SESSION['id'];
$annee = date('Y').'-'.date('m').'-'.date('d');

$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$id'");
$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");
$sqlA=$connexion->query("SELECT *FROM auditors a WHERE a.id_u='$id'");
$sqlAcc=$connexion->query("SELECT *FROM accountants acc WHERE acc.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");

$comptidD=$sqlD->rowCount();
$comptidI=$sqlI->rowCount();
$comptidL=$sqlL->rowCount();
$comptidR=$sqlR->rowCount();
$comptidC=$sqlC->rowCount();
$comptidA=$sqlA->rowCount();
$comptidAcc=$sqlAcc->rowCount();
$comptidM=$sqlM->rowCount();

		if($comptidD!=0 or $comptidI!=0 or $comptidL!=0 or $comptidR!=0 or $comptidC!=0 or $comptidA!=0 or $comptidAcc!=0)
		{
		?>
			<?php
			if(isset($_SESSION['codeR']))
			{
			?>
				<li style="width:50%;"><a href="utilisateurs.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="<?php echo getString(88);?>"><i class="fa fa-plus-circle fa-lg fa-fw"></i> <?php echo getString(88);?></a></li>
			<?php
			}else{
			
				if(isset($_SESSION['codeAcc']))
				{
			?>
				<li style="width:50%;"><a href="billsaccount.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Bill Check"><i class="fa fa-plus-circle fa-lg fa-fw"></i> <?php echo getString(174);?></a></li>
			<?php
				}else{
			
					if(isset($_SESSION['codeA']))
					{
			?>
					<li style="width:50%;"><a href="report.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Report"><i class="fa fa-file fa-lg fa-fw"></i> Reports</a></li>
			<?php
					}else{
			?>
					<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
			<?php
					}
				}
			}
			?>
			<?php
		$lu=0;
        $selectmsg=$connexion->prepare("SELECT * FROM messages WHERE lu=:lu AND receiverId=:receiverId");
        $selectmsg->execute(array("lu"=>$lu,"receiverId"=>$_SESSION['id']));
        $selectmsg->setFetchMode(PDO::FETCH_OBJ);
        $lignecount=$selectmsg->rowCount();
        // echo 'id:'.$_SESSION['id'];
        // echo 'Numero:'.$lignecount;
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?>  <i class="badge flashing"><?php echo $lignecount; ?></i> </a></li>
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>

		</ul>

		<ul style="margin-top:20px; background:none;border:none;">

		</ul>
			
			<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
				
				 <?php if($lignecount!=0){?>
                	<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
		        <?php }else{?>
		            <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		        <?php }?>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

			</div>
			
		</div>


		<?php 
		}else{
			if($comptidM!=0)
			{
	?>	
	<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-lg fa-fw"></i> <?php echo getString(48);?></a></li>

					<?php
		$lu=0;
        $selectmsg=$connexion->prepare("SELECT * FROM messages WHERE lu=:lu AND receiverId=:receiverId");
        $selectmsg->execute(array("lu"=>$lu,"receiverId"=>$_SESSION['id']));
        $selectmsg->setFetchMode(PDO::FETCH_OBJ);
        $lignecount=$selectmsg->rowCount();
        // echo $_SESSION['id'];
        // echo $lignecount;	
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge flashing"><?php echo $lignecount; ?></i> </a></li>
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>


				</ul>

				<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">

					<div id="divMenuUser" style="display:none;">
					
						<a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(87);?></a>
						
						<a onclick="ShowList('Liste')" id="listOn" class="btn-large" style="display:inline;"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(55);?></a>
						
						<span onclick="ShowList('ListeNon')" id="listOff" class="btn-large" style="display:none;"><i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo getString(56);?></span>
					
					</div>

				
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

					<div style="display:none;" id="divListe" align="center">

						<a href="patients1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(20);?></a>
						
						<a href="medecins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(19);?></a>
						
						<a href="infirmiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(21);?></a>
						
						<a href="laborantins1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(22);?></a>
						
						<a href="receptionistes1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(40);?></a>
		
						<a href="caissiers1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(23);?></a>
						
						<a href="auditeurs1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(149);?></a>

						<a href="comptables1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(150);?></a>
						
						
					</div>

	<?php
				
				}
			}
?>	
<br/>

<?php

if(isset($_GET['ecrire'])=='ok' or isset($_GET['idMsgRecu'])=='ok')
{
?>

<div id="formMsg">

<form method="post" action="traitement_messages.php?back=ok<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" onsubmit="return controlFormMessage(this)" enctype="multipart/form-data">
	
	
	<?php 

	if( isset($_GET['ecrire']) AND !isset($_GET['idMsg']) AND !isset($_GET['idMsgRecu']))
	{
	?>
		<h3><?php echo getString(57);?></h3>
	<?php 
	}
	
	if(isset($_GET['idMsg']) AND isset($_GET['ecrire']))
	{
	?>
		<h3><?php echo getString(175);?></h3>
	<?php 
	}
	if(isset($_GET['idMsgRecu']) AND isset($_GET['ecrire']))
	{
	?>
		<h3><?php echo getString(176);?> | <button class="btn-large border-radius" style="width: auto;" onclick="window.location.href='messages.php?recuAll=ok';"><i class="fa fa-arrow-left"></i></button></h3>
	<?php 
	}
	?>
	

<table class="tablesorter tablesortermail" cellspacing="0">

<tr style="text-align:center;">
	<td></td>
	<td>
		<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php if(isset($_GET['idMsg']) or isset($_GET['idMsgRecu'])){ echo $dateMsg;}else{ echo $annee;}?>

</tr>
	
<tr>	
	</td>
	
	<td>
	
	<?php 

	if( isset($_GET['ecrire']) AND !isset($_GET['idMsg']) AND !isset($_GET['idMsgRecu']))
	{
	?>
		<label for="desti"><?php echo getString(64);?></label>
	<?php 
	}
	
	if(isset($_GET['idMsg']) AND isset($_GET['ecrire']))
	{
	?>
		<label for="desti"><?php echo getString(73);?></label>
	<?php 
	}
	if(isset($_GET['idMsgRecu']) AND isset($_GET['ecrire']))
	{
	?>
		<label for="desti"><?php echo getString(72);?></label>
	<?php 
	}
	?>
	</td>
	<td>
		<?php
		if (isset($_GET['idMsgRecu']))
		{
		?>
			<input type="hidden" name="idresultsRep" id="idresultsRep" value="<?php echo $idDesti;?>"/>
			<input type="hidden" name="idMsg" id="idMsg" value="<?php echo $site;?>"/>
			<input type="text" name="resultsRep" id="resultsRep" value="<?php echo $to;?>" readonly="readonly"/>
			
		<?php
		}else{
			if (isset($_GET['idMsg']))
			{
		?>
				<input type="text" name="resultsSent" id="resultsSent" value="<?php echo $to;?>" readonly="readonly"/>
		
		<?php
			}else{
				if(isset($_GET['ecrire'])=='ok')
				{
		?>
				<select name="desti" id="desti" onchange="ShowDesti('desti')">
						<option value=""><?php echo getString(177);?>...</option>
						<option value='Med'><?php echo getString(19);?></option>
						<option value='Inf'><?php echo getString(21);?></option>
						<option value='Lab'><?php echo getString(22);?></option>
						<option value='Rec'><?php echo getString(40);?></option>
						<option value='Cash'><?php echo getString(23);?></option>
						<option value='Aud'><?php echo getString(149);?></option>
						<option value='Acc'><?php echo getString(150);?></option>
						<option value='Man'><?php echo getString(179);?></option>

				</select>
				<script src="jQuery.js"></script>
				<script>
				$(function(){
					$("#desti").change(function(){
				var destiChoisi='desti='+$(this).val();
					//alert(destiChoisi);
					$.ajax({
						url:"msgDesti.php",
						type:"POST",
						data:destiChoisi,
						
						success:function(resultat)
						{
							
							// alert(resultat);
							$('#to').html(resultat);
						}
						});
					});
				});
				</script>
				
				<select style="width:340px;display:none;" name="to" id="to">
					<option value=""></option>
				</select>
		<?php
				}
			}
		}
		?>
	</td>
</tr>

<tr>
	<td><label for="objet"><?php echo getString(65);?></label></td>
	<td>
		<input type="text" name="objet" id="objet" value="<?php if(isset($_GET['idMsg']) or isset($_GET['idMsgRecu'])){ echo $objet;}else{ echo '';}?>" <?php if(isset($_GET['idMsg']) or isset($_GET['idMsgRecu'])){ echo 'readonly="readonly"';}else{ echo '';}?> placeholder="Entrez l'objet"/>
	</td>
</tr>

<tr>
	<td><label for="contenu"><?php echo getString(181);?></label></td>
	<td>
		<textarea style="height:150px" id="contenu" name="contenu" <?php if(isset($_GET['idMsg']) or isset($_GET['idMsgRecu'])){ echo 'readonly="readonly"';}else{ echo '';}?>><?php if(isset($_GET['idMsg']) or isset($_GET['idMsgRecu'])){ echo $content;}else{ echo '';}?></textarea>
	</td>
</tr>

<?php 

if(isset($_GET['idMsg']) or isset($_GET['idMsgRecu']))
{

	if($annexe!="")
	{
?>
	<tr>
		<td><?php echo getString(182);?></td>
		<td style="text-align:left;">
			<a href="<?php echo $annexe;?>" class="btn" target="_blank"><?php echo getString(183);?></a>
		</td>
	</tr>
<?php
	}
}
?>

<?php 
if(isset($_GET['idMsgRecu']))
{
?>

<tr style="background:#f8f8f8; border-left:none; border-right:none;"> 
	<td></td>
	<td>
		<h2 style="font-size:28px; font-weight:600;">Reply</h2>
	</td>
</tr>
<tr>
	<td></td>
	<td><br/>
		<input type="hidden" name="dateRetour" id="dateRetour" value="<?php echo $annee;?>" readonly="readonly"/>
		<textarea style="height:150px;" id="contenuRetour" name="contenuRetour" placeholder="<?php echo getString(184);?>"></textarea>
	</td>
</tr>
<?php 
}
if(isset($_GET['idMsgRecu']) or !isset($_GET['idMsg']))
{
?>
<tr>
	<td></td>
	<td>
		<input type="file" name="annexe" id="annexe"/>
	</td>
	<td><input type="hidden" name="MAX_FILE_SIZE" value="100000"/></td>
</tr>
<?php 
}
?>

<tr>
	<td></td>
	<td>
		<?php 
		if(isset($_GET['idMsg']))
		{
		?><br/>
			<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg"><?php echo getString(63);?></a>

		<?php
		}else{
			if(isset($_GET['idMsgRecu']))
			{
		?><br/>
				<button type="submit" class="btn-large" name="answerbtn">
				<?php echo getString(180);?>
				</button>
				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" name="newMsg">
				<button class="btn-large-inversed"><?php echo getString(63);?></button></a>
		<?php
			}else{
				if(isset($_GET['ecrire'])=='ok')
				{
		?><br/>
					<input type="submit" class="btn-large" name="sendbtn" value="<?php echo getString(68);?>"/>
		<?php
				}
			}
		}
		?>
	</td>
</tr>


</table>

</form>
</div>

<?php
}
?>

<?php
	if(isset($_GET['recu'])=='ok')
	{
?>
	<h1><?php echo getString(58);?> | <button class="btn-large" style="width: 20%;" onclick="window.location.href='messages.php?recuAll=ok';"><i class="fa fa-envelope"></i> All Mails</button></h1>
	<hr>
	<h4>Find Mail According To Specified Period</h4>
	<hr>
	<div id="selectdatePersoSMSReport">
		<form action="messages.php?recu=ok&smsFinder=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" style="margin:auto;padding:5px;width:90%;">
		
			<table id="dmacbillperso" style="margin:auto auto 20px">
				<tr style="display:inline-block; margin-bottom:25px;">
					<td>
						<span style="text-align:center;width:100px;" id="dailybtn" onclick="ShowSelectreport('dailybillPerso')" class="btn">Daily</span>
					</td>
					
					<td>
						<span style="text-align:center;width:100px;" id="monthlybtn" onclick="ShowSelectreport('monthlybillPerso')" class="btn">Monthly</span>
					</td>
					
					<td>
						<span style="text-align:center;width:100px;" id="annualybtn" onclick="ShowSelectreport('annualybillPerso')" class="btn">Annualy</span>
					</td>
					
					<td>
						<span style="text-align:center;width:100px;" id="custombtn" onclick="ShowSelectreport('custombillPerso')" class="btn">Custom</span>
					</td>
				</tr>
				
				<tr style="visibility:visible">
				
					<td id="dailybillPerso" style="display:none">Select date
						<input type="date" id="dailydatebillPerso" name="dailydatebillPerso" value="" style="width: 258px;text-align: center;" />
					
						<button type="submit" name="searchdailybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:38px;">Search</button>
					</td>
					
					<td id="monthlybillPerso" style="display:none">Select Month
					
						<select name="monthlydatebillPerso" id="monthlydatebillPerso" style="width:120px;height:40px;">
						
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
						
						<select name="monthlydatebillPersoYear" id="monthlydatebillPersoYear" style="width:100px;height:40px;">
						<?php 
						for($i=2016;$i<=2030;$i++)
						{
						?>
							<option value='<?php echo $i;?>' <?php if(date('Y')==$i) {echo 'selected="selected"';}?>><?php echo $i;?></option>
						<?php 
						}
						?>
						</select>
						
						<button type="submit"  name="searchmonthlybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
						
					</td>
					
					<td id="annualybillPerso" style="display:none">Select Year
					
						<select name="annualydatebillPerso" id="annualydatebillPerso" style="width:100px;height:40px;">
						<?php 
						for($i=2016;$i<=2030;$i++)
						{
						?>
							<option value='<?php echo $i;?>' <?php if(date('Y')==$i) {echo 'selected="selected"';}?>><?php echo $i;?></option>
						<?php 
						}
						?>
						</select>
					
						<button type="submit"  name="searchannualybillPerso" class="btn-large-inversed" style="width:auto;vertical-align:top;height:39px;">Search</button>
					</td>
					
					<td id="custombillPerso" style="display:none">
					
						<table>
							<tr>
								<td>From</td>
								<td>
									<input type="date" id="customdatedebutbillPerso" name="customdatedebutbillPerso" value="" style="width:150px;text-align: center;"/>
								</td>
							
								<td>&nbsp;&nbsp;&nbsp;&nbsp;To</td>
								<td>
									<input type="date" id="customdatefinbillPerso" name="customdatefinbillPerso" value="" style="width:150px;text-align: center;"/>
								</td>
							
								<td style="vertical-align:top;">
									<button type="submit"  name="searchcustombillPerso" class="btn-large-inversed" style="width:auto;height:38px;margin-left:5px;">Search</button>
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
		if(isset($_GET['smsFinder']))
		{
			$stringResult = "";
			$dailydateperso = "";
			$caVisit="gnlPersoBill";
			
			if(isset($_POST['searchdailybillPerso']))
			{
				if(isset($_POST['dailydatebillPerso']))
				{
					$dailydateperso = 'm.datemessage LIKE \''.$_POST['dailydatebillPerso'].'%\'';
					
					$caVisit="dailyPersoBill";
				
					$stringResult="Daily Mails : ".$_POST['dailydatebillPerso'];
					//echo "string";
		
				}
			}
			
			if(isset($_POST['searchmonthlybillPerso']))
			{

				if(isset($_POST['monthlydatebillPerso']) AND isset($_POST['monthlydatebillPersoYear']))
				{
					if($_POST['monthlydatebillPerso']<10)
					{
						$ukwezi = '0'.$_POST['monthlydatebillPerso'];
					}else{						
						$ukwezi = $_POST['monthlydatebillPerso'];
					}
					
					$umwaka = $_POST['monthlydatebillPersoYear'];
				
					$daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					if($daysmonth<10)
					{
						$daysmonth='0'.$daysmonth;
					}
					
					$dailydateperso = 'm.datemessage>=\''.$umwaka.'-'.$ukwezi.'-01\' AND (m.datemessage<\''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'\' OR m.datemessage LIKE \''.$umwaka.'-'.$ukwezi.'-'.$daysmonth.'%\')';

					$caVisit="monthlyPersoBill";
					
					$stringResult='Monthly Mails : '. date("F",mktime(0,0,0,$_POST['monthlydatebillPerso'],10))." - ".$_POST['monthlydatebillPersoYear'];
			
				}
			}

			if(isset($_POST['searchannualybillPerso']))
			{
				if(isset($_POST['annualydatebillPerso']))
				{
					$year = $_POST['annualydatebillPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$year);
					
					$dailydateperso = 'm.datemessage>=\''.$year.'-01-01\' AND m.datemessage<=\''.$year.'-12-31\'';
					
					$docVisit="annualyPersoMedic";
			
					$stringResult="Annualy Mails : ".$_POST['annualydatebillPerso'];
					
					// $stringResult=$_POST['annualydatePerso'];
			
				}
			
			}

			if(isset($_POST['searchcustombillPerso']))
			{
				if(isset($_POST['customdatedebutbillPerso']) AND isset($_POST['customdatefinbillPerso']))
				{
					$debut = $_POST['customdatedebutbillPerso'];
					$fin = $_POST['customdatefinbillPerso'];
				
					// $daysmonth= cal_days_in_month(CAL_GREGORIAN,$ukwezi,$umwaka);
					
					$dailydateperso = 'm.datemessage>=\''.$debut.'\' AND (m.datemessage<\''.$fin.'\' OR m.datemessage LIKE \''.$fin.'%\')';
					
					$caVisit="customPersoBill";
					
					$stringResult="Custom Mails : [ ".$_POST['customdatedebutbillPerso']."/".$_POST['customdatefinbillPerso']." ]";

				}
			}

		?>


		<div id="dmacBillReport" style="display:inline">
			
			<table style="width:100%;">
				<tr>
					<td style="text-align:center; width:33.333%;">
										
					</td>
					
					<td style="text-align:center; width:40%;">
						<span style="position:relative; font-size:150%;"></i><?php echo $stringResult;?></span>
				
					</td>
					
					<td style="font-size:18px; padding-right:20px; text-align:center; width:33.333%;">
						
					</td>
				</tr>			
			</table>
			
				<?php
				
				$resultMailReport=$connexion->query('SELECT *FROM messages m, utilisateurs u WHERE u.id_u=m.receiverId AND m.receiverId='.$_SESSION['id'].' AND '.$dailydateperso.'');		
				//echo "SELECT * FROM med_medoc WHERE $dailydateperso";
				$resultMailReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$compMailReport=$resultMailReport->rowCount();

				if($compMailReport!=0)
				{
				?>
				
			<table style="width:100%;">
				<tr>
					<td style="text-align:left; width:33.333%;">
						<a href="messages.php?SpecifiedMail=ok&dailydateperso=<?= $dailydateperso;?>&stringResult=<?php echo $stringResult;?>" style="text-align:center" id="dmacbillpersopreview"> 
							<button style="width:250px; margin:auto;" type="submit" name="printbill" id="printbill" class="btn-large flashing">
								<i class="fa fa-desktop fa-lg fa-fw"></i> Preview print <span class="badge"><?php echo $compMailReport; ?></span>
							</button>
						
						</a>
												
						<input type="hidden" name="dateprint" value="<?php  echo $annee;?>"/>
				
					</td>
					
					<td style="text-align:center; width:33.333%;">
						
					</td>
					
					<td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
						<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y',strtotime($annee));?>
					</td>
				</tr>			
			</table>
			<?php }else{?>

				<table class="tablesorter tablesorter4 flashing" cellspacing="0" style="background:#fff; width:100%; margin-top:10px;"> 
								
					<thead>
						<tr>
							<th style="width:12%;text-align:center">No Mail Found For Specified Period!</th>
						</tr> 
					</thead> 
				</table> 

		<?php
			}
		}
	}
?>



<?php

if (isset($_GET['SpecifiedMail'])) {

		$dailydateperso = $_GET['dailydateperso'];

		$resultMailReport=$connexion->query('SELECT *FROM messages m, utilisateurs u WHERE u.id_u=m.receiverId AND m.receiverId='.$_SESSION['id'].' AND '.$dailydateperso.'');	
		//echo "SELECT * FROM med_medoc WHERE $dailydateperso";
		$resultMailReport->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$compMailReport=$resultMailReport->rowCount();

?>
<h1><?php echo getString(58);?></h1> | <span style="font-weight: bold;"><?php echo $_GET['stringResult'];?></span></h3>
<hr>
<table class="tablesorter" cellspacing="0" id="dataTables-example"> 
	<thead> 
		<tr>
			<th style="text-align: center;">#</th> 
			<th style="text-align: center;"><?php echo getString(71);?></th> 
			<th style="text-align: center;"><?php echo getString(65);?></th>
			<th style="text-align: center;"><?php echo getString(181);?></th>
			<th style="text-align: center;"><?php echo getString(72);?></th>
			<th style="text-align: center;"></th>
		</tr> 
	</thead>
	
	<tbody>
	
	<?php

		
			$counter = 1;
			while($ligne=$resultMailReport->fetch())//on recupere la liste des éléments
			{
		?>
			<tr style="text-align:center;<?php if($ligne->lu==0){?>background:rgb(253,168,170)<?php ;}?>" class="readmessage" onclick="window.location.href='traitement_messages.php?idMsgRecu=<?php echo $ligne->id_message;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>';"> 
				<td><?php echo $counter;?></td>
				<td><?php echo $ligne->datemessage;?></td>
				<td><?php echo $ligne->objet;?></td>
				<td>
					<a href="traitement_messages.php?idMsgRecu=<?php echo $ligne->id_message;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><span title="<?php echo getString(186);?>" name="contenubtn"><i class="fa fa-eye"></i></span> Read More</a>
				</td>
				
				<td>
				<?php 
						$code=$ligne->senderId;
						
						$result=$connexion->query('SELECT *FROM utilisateurs u WHERE u.id_u='.$code.'') or die( print_r($connexion->errorInfo()));
						
						if($ligneMsg=$result->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
						{
							echo $ligneMsg->nom_u.' '.$ligneMsg->prenom_u;
						}
						$result->closeCursor();
				?>
				</td>
				
				<?php 
				if($ligne->lu==1)
				{
				?>
				<td class="doubleBtn">
					<i class="fa fa-check fa-lg fa-fw"></i>
					<a class="fa fa-trash" style="font-size: 30px;color: #a00000;" href='traitement_messages.php?idMsgDeleteRecu=<?php echo $ligne->id_message;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>'></a>
				</td>
				<?php 
				}else{
				?>
				<td>
					<i class="fa fa-spinner fa-spin fa-lg fa-fw"></i>
				</td>
				<?php 
				}
				?>
			</tr>
		<?php
		$counter++;
			}
			$resultMailReport->closeCursor();
	?>
	
	</tbody>

</table>



<?php
	}
?>












<?php
if(isset($_GET['recuAll'])=='ok')
{

	function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
	{
		$pagination = '';
		$link = preg_replace('`%([^d])`', '%%$1', $link);
		if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
		if ( $nb_pages > 1 ) {
	 
			// Lien Précédent
			if ( $current_page > 1 )
				$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&recu=ok" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
			else
				$pagination .= '';
	 
			// Lien(s) début
			for ( $i=1 ; $i<=$firstlast ; $i++ ) {
				$pagination .= ' ';
				$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&recu=ok">'.$i.'</a>';
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
					$pagination .= '<span class="current">'.$i.'</span>';
				else
					$pagination .= '<a href="'.sprintf($link, $i).'&recu=ok">'.$i.'</a>';
			}
	 
			// ... avant page nb_pages ?
			if ( ($current_page+$around) < $nb_pages-$firstlast )
				$pagination .= '<span class="current">&hellip;</span>';
	 
		// Lien(s) fin
			$start = $nb_pages-$firstlast+1;
			if( $start <= $firstlast ) $start = $firstlast+1;
			for ( $i=$start ; $i<=$nb_pages ; $i++ ) {
				$pagination .= ' ';
			$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&recu=ok">'.$i.'</a>';
			}
	 
			// Lien suivant
			if ( $current_page < $nb_pages )
				$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&recu=ok" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
			else
				$pagination .= '';
		}
		return $pagination;
	}


	// Numero de page (1 par défaut)
		if( isset($_GET['page']) && is_numeric($_GET['page']) )
				$page = $_GET['page'];
			else
				$page = 1;
			 
			// Nombre d'info par page
			$pagination =30;
			 
			// Numero du 1er enregistrement à lire
			$limit_start = ($page - 1) * $pagination;



	$resultats=$connexion->query('SELECT *FROM messages m, utilisateurs u WHERE u.id_u=m.receiverId AND m.receiverId='.$_SESSION['id'].' ORDER BY m.id_message DESC') or die( print_r($connexion->errorInfo()));

	$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
	
	$comptMsgReceive=$resultats->rowCount();
	
	if($comptMsgReceive!=0)
	{
?>

<div id="reception">

	<h1>All <?php echo getString(58);?> | <button class="btn-large-inversed" style="width: 20%;" onclick="window.location.href='messages.php?recu=ok';"><i class="fa fa-envelope"></i> Specific Mails</button></h1>
	<hr>
<table class="tablesorter" cellspacing="0" id="dataTables-example"> 
	<thead> 
		<tr>
			<th style="text-align: center;">#</th> 
			<th style="text-align: center;width: 10%;"><?php echo getString(71);?></th> 
			<th style="text-align: center;"><?php echo getString(65);?></th>
			<th style="text-align: center;"><?php echo getString(181);?></th>
			<th style="text-align: center;"><?php echo getString(72);?></th>
			<th style="text-align: center; width: 10%;"></th>
		</tr> 
	</thead>
	
	<tbody>
	
	<?php
	try
	{
		
			$counter = 1;
			while($ligne=$resultats->fetch())//on recupere la liste des éléments
			{
		?>
			<tr style="text-align:center;<?php if($ligne->lu==0){?>background:rgb(253,168,170)<?php ;}?>" class="readmessage" onclick="window.location.href='traitement_messages.php?idMsgRecu=<?php echo $ligne->id_message;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>';"> 
				<td><?php echo $counter;?></td>
				<td><?php echo $ligne->datemessage;?></td>
				<td><?php echo $ligne->objet;?></td>
				<td>
					<a href="traitement_messages.php?idMsgRecu=<?php echo $ligne->id_message;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><span title="<?php echo getString(186);?>" name="contenubtn"><i class="fa fa-eye"></i></span> Read More</a>
				</td>
				
				<td>
				<?php 
						$code=$ligne->senderId;
						
						$result=$connexion->query('SELECT *FROM utilisateurs u WHERE u.id_u='.$code.'') or die( print_r($connexion->errorInfo()));
						
						if($ligneMsg=$result->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
						{
							echo $ligneMsg->nom_u.' '.$ligneMsg->prenom_u;
						}
						$result->closeCursor();
				?>
				</td>
				
				<?php 
				if($ligne->lu==1)
				{
				?>
				<td class="doubleBtn">
					<i class="fa fa-check fa-lg fa-fw"></i>
					<a class="fa fa-trash" style="font-size: 30px;color: #a00000;" href='traitement_messages.php?idMsgDeleteRecu=<?php echo $ligne->id_message;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>'></a>
				</td>
				<?php 
				}else{
				?>
				<td>
					<i class="fa fa-spinner fa-spin fa-lg fa-fw"></i>
				</td>
				<?php 
				}
				?>
			</tr>
		<?php
		$counter ++;
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

<!-- <tr>
	<td>
	<?php

	$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM messages m, utilisateurs u WHERE u.id_u=m.receiverId AND m.receiverId='.$_SESSION['id'].' ORDER BY m.id_message DESC');
	$nb_total=$nb_total->fetch();
	$nb_total = $nb_total['nb_total'];
	// Pagination
	$nb_pages = ceil($nb_total / $pagination);
		   // Affichage
	  echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
	?>
	</td>
</tr> -->

</div>

<?php
	}else{
?>
	<h3><?php echo getString(58);?></h3>
	
	<table class="tablesorter" cellspacing="0"> 
	<thead> 
		<tr>
			<th style="color:red;background-color:black;"><?php echo getString(187);?></th> 
		</tr> 
	</thead>
	</table>
<?php
	}
}
?>

<?php
if(isset($_GET['envoye'])=='ok')
{

function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
	{
		$pagination = '';
		$link = preg_replace('`%([^d])`', '%%$1', $link);
		if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
		if ( $nb_pages > 1 ) {
	 
			// Lien Précédent
			if ( $current_page > 1 )
				$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&envoye=ok" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
			else
				$pagination .= '';
	 
			// Lien(s) début
			for ( $i=1 ; $i<=$firstlast ; $i++ ) {
				$pagination .= ' ';
				$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&envoye=ok">'.$i.'</a>';
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
					$pagination .= '<span class="current">'.$i.'</span>';
				else
					$pagination .= '<a href="'.sprintf($link, $i).'&envoye=ok">'.$i.'</a>';
			}
	 
			// ... avant page nb_pages ?
			if ( ($current_page+$around) < $nb_pages-$firstlast )
				$pagination .= '<span class="current">&hellip;</span>';
	 
		// Lien(s) fin
			$start = $nb_pages-$firstlast+1;
			if( $start <= $firstlast ) $start = $firstlast+1;
			for ( $i=$start ; $i<=$nb_pages ; $i++ ) {
				$pagination .= ' ';
			$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&envoye=ok">'.$i.'</a>';
			}
	 
			// Lien suivant
			if ( $current_page < $nb_pages )
				$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&envoye=ok" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
			else
				$pagination .= '';
		}
		return $pagination;
	}


	// Numero de page (1 par défaut)
		if( isset($_GET['page']) && is_numeric($_GET['page']) )
				$page = $_GET['page'];
			else
				$page = 1;
			 
			// Nombre d'info par page
			$pagination =5;
			 
			// Numero du 1er enregistrement à lire
			$limit_start = ($page - 1) * $pagination;

	
	$resultats=$connexion->query('SELECT *FROM messages m, utilisateurs u WHERE u.id_u=m.senderId AND m.senderId='.$_SESSION['id'].' ORDER BY m.id_message DESC LIMIT '.$limit_start.', '.$pagination.'') or die( print_r($connexion->errorInfo()));

	$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
	
	$comptMsgSend=$resultats->rowCount();
	
	if($comptMsgSend!=0)
	{
?>

<div id="envoye">

	<h3><?php echo getString(59);?></h3>
	
<table class="tablesorter" cellspacing="0" id="dataTables-example"> 
	<thead> 
		<tr>
			<th><?php echo getString(71);?></th> 
			<th><?php echo getString(65);?></th>
			<th><?php echo getString(181);?></th>
			<th><?php echo getString(73);?></th>
			<th><?php echo getString(70);?></th>
		</tr> 
	</thead> 

	<tbody>
	<?php
	try
	{
		while($ligne=$resultats->fetch())//on recupere la liste des éléments
		{
	?>
			<tr style="text-align:center;<?php if($ligne->lu==0){?>background:rgb(253,168,170)<?php ;}?>"> 
				<td><?php echo $ligne->datemessage;?></td>
				<td><?php echo $ligne->objet;?></td>
				<td>
					<a href="messages.php?idMsg=<?php echo $ligne->id_message?>&ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><span title="<?php echo getString(186);?>" name="contenubtn"><?php echo getString(185);?></span></a>
				</td>
				
				<td>
				<?php 
						$code=$ligne->receiverId;
						
						$result=$connexion->query('SELECT *FROM utilisateurs u WHERE u.id_u='.$code.'') or die( print_r($connexion->errorInfo()));
						
						if($ligneMsg=$result->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
						{
							echo $ligneMsg->nom_u.' '.$ligneMsg->prenom_u;
						}
						$result->closeCursor();
				?>
				</td>
				<td>
					<a href="traitement_messages.php?idMsgDeleteRecu=<?php echo $ligne->id_message;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" name="deleteMsg" title="<?php echo getString(70);?>" class="btn"> <?php echo getString(70);?></a>
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

<table align="center">

<tr>
	<td>
	<?php

	$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM messages m, utilisateurs u WHERE u.id_u=m.senderId AND m.senderId='.$_SESSION['id'].' ORDER BY m.id_message DESC ');
	
	$nb_total=$nb_total->fetch();
	$nb_total = $nb_total['nb_total'];
	// Pagination
	$nb_pages = ceil($nb_total / $pagination);
		   // Affichage
	  echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
	?>
	</td>
</tr>
</table>

</div>

<?php

	}else{
?>
		<h3><?php echo getString(59);?></h3>
		
		<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th style="color:red;background-color:black;"><?php echo getString(188);?></th> 
			</tr> 
		</thead>
		</table>
<?php
	}
}
?>


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


function GetOption(Uti)
{
	if( Uti =='Uti')
	{
		document.getElementById('q').style.display='none';
	}

}

function ShowDesti(Desti)
{
	var to = document.getElementById('desti').value;
	
	if( to =='')
	{
		document.getElementById('to').style.display='none';
	}else{
		document.getElementById('to').style.display='inline';
	}
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
		document.getElementById('divMenuUser').style.display='none';
		document.getElementById('divListe').style.display='none';
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
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

	document.getElementById('checkassu').style.display='inline-block';
	document.getElementById('checkassu2').style.display='inline-block';
	
}


</script>

</div>

<?php
	
	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
}else{
	echo '<script language="javascript"> alert("Vous n\'êtes pas connecté");</script>';
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
			
		echo '<script type="text/javascript"> alert("Vous venez de modifier votre mot de passe");</script>';
		
	}
?>

      <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
      <!-- CORE JQUERY  -->
     
      <!-- BOOTSTRAP SCRIPTS  -->
      <script src="assets/js/bootstrap.js"></script>
      <!-- DATATABLE SCRIPTS  -->
      <script src="assets/js/dataTables/jquery.dataTables.js"></script>
      <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
      <script src="assets/js/custom.js"></script>  
</body>

</html>