<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");

	$heure=date("H");
	$min=date("i");


	$heureToday=$heure.':'.$min;

	$annee = date('Y').'-'.date('m').'-'.date('d');

	/*--------Supression des RDV passé-------*/
	 
	$deleteRdv=$connexion->query('DELETE FROM rendez_vous WHERE daterdv < \''.$annee.'\'');
	
	// echo 'DELETE FROM rendez_vous WHERE daterdv < '.$annee.'';
	
if(isset($_GET['editrdv']))
{
	$resultRdv=$connexion->prepare('SELECT *FROM rendez_vous r WHERE r.id_rdv=:id_rdv');
	$resultRdv->execute(array(
	'id_rdv'=>$_GET['editrdv']
	));
	$resultRdv->setFetchMode(PDO::FETCH_OBJ);
	
	// echo 'SELECT *FROM rendez_vous r, patients p,medecins m WHERE p.numero=r.numero AND r.id_uM=m.id_u AND r.id_uM='.$_SESSION['id'].' AND r.numero='.$_GET['num'].'';
	
	$comptRdv=$resultRdv->rowCount();
		
	if($comptRdv!=0)
	{
		while($ligneRdv=$resultRdv->fetch())
		{
			$idRdv=$ligneRdv->id_rdv;
			$numpaRdv=$ligneRdv->numero;
			$autrePaRdv=$ligneRdv->autrePa;
			$autreTelRdv=$ligneRdv->autreTel;
			$idmedRdv=$ligneRdv->id_uM;
			$dateRdv=$ligneRdv->daterdv;
				$anneeRdv=date('Y', strtotime($ligneRdv->daterdv));
				$moisRdv=date('m', strtotime($ligneRdv->daterdv));
				$joursRdv=date('d', strtotime($ligneRdv->daterdv));
			$heureMinRdv=$ligneRdv->heurerdv;
				$heureRdv=date('H', strtotime($ligneRdv->heurerdv));
				$minRdv=date('i', strtotime($ligneRdv->heurerdv));
			$motifRdv=$ligneRdv->motifrdv;
			$statusRdv=$ligneRdv->statusRdv;
		}
		$resultRdv->closeCursor();
	}

}else{
	$comptRdv=0;
}


if(isset($_GET['deleterdv']))
{
	
	$idRdv=$_GET['deleterdv'];
	 
	$deleteRdv=$connexion->prepare('DELETE FROM rendez_vous WHERE id_rdv=:idRdv');
		
	$deleteRdv->execute(array(
	'idRdv'=>$idRdv
	
	))or die($deleteRdv->errorInfo());
	
		
	echo '<script type="text/javascript"> alert("Rendez-vous deleted");</script>';
	
	echo '<script text="text/javascript">document.location.href="rendezvous1.php"</script>';
	
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
	<title><?php echo 'Show RDV';?></title>
	
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
		
	
	<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"/><!--Header-->

	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	
		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>	
	<link href="css/form-signin.css" rel="stylesheet" type="text/css">

	<script src="myQuery.js"></script>	

</head>

<body>

<?php

$id=$_SESSION['id'];

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
					<form method="post" action="rendezvous1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ', '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="rendezvous1.php?english=english<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="rendezvous1.php?francais=francais<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?>" class="btn"><?php echo getString(29);?></a>
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
?>
	<div style="text-align:center;margin:20px;">		
		<?php
		if(isset($_GET['receptioniste']))
		{
		?>
			<a href="patients1.php?caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
				<?php echo 'Caisse';?>
			</a>

		<?php
		}else{
		?>
			<a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px;padding:10px 40px;">
				<?php echo 'Reception';?>
			</a>

		<?php
		}
		?>
	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeC']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reports';?>
		</a>

		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Factures';?>
		</a>
	
	</div>
	
<?php
}
?>


<?php
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;margin-bottom:15px;">
		
		<a href="report.php?med=<?php echo $_SESSION['id'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="font-size:20px;height:40px; padding:10px 40px;"><?php echo getString(94);?></a>
		
	</div>
<?php
}
?>

<div  class="account-container" style="width:80%;margin:auto; text-align:center;">

	<div id='cssmenu' style="text-align:center;">
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
				if(isset($_SESSION['codeM']))
				{
			?>
					<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><b><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</b></a></li>
			
		<?php
				}else{
			?>
					<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-lg fa-fw"></i> <?php echo getString(48);?></a></li>
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
			
		<div style="margin:20px">

		<a href="rendezvous.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" id="newRdv" style="margin:10px;"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(269);?></a>
			
		</div>
		
	</div>	
	
	<?php
	if(isset($_GET['editrdv']))
	{
	?>
	<div style="background:#F8F8F8; margin-top:10px; padding:10px 50px; text-align:center;">
	
		<h2><?php echo 'Formulaire RENDEZ-VOUS';?></h2>
   
		<form action="traitement_rendezvous.php" onsubmit="return controlFormRDV(this)" method="post" enctype="multipart/form-data">
		   		   
			<table class="cons-info" cellpadding=10 style="margin:20px auto auto auto;background:#e8e8e8;width:90%;">
				
				<tr>
					<td style="text-align:right;">Patient</td>
					<td style="text-align:left;">
						<select name="numPa" id="numPa" class="chosen-select" onchange="ShowAutrePa('NewPa')">
						<?php
						$bd=$connexion->query("SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u ORDER BY u.full_name");

						$comptPaDb=$bd->rowCount();
						
						while ($ligne=$bd->fetch(PDO::FETCH_OBJ))
						{
						?>
							<option value="<?php echo $ligne->numero;?>" <?php if($comptRdv!=0 AND $numpaRdv==$ligne->numero) echo 'selected="selected"';?>><?php echo $ligne->full_name;?></option>
						<?php
						}
							$bd->closeCursor();
						?>
							<option value="0" <?php if($comptRdv!=0 AND $autrePaRdv!=NULL) echo 'selected="selected"';?>><?php echo 'Autre Patient';?></option>
						</select>
						<br/>
						<br/>
						
						<input name="newPa" id="newPa" type="text" style="width:200px;<?php if($comptRdv!=0 AND $autrePaRdv!=NULL) { echo 'display:inline;';}else{ echo 'display:none;';}?>" placeholder="Nouveau Patient" value="<?php if($comptRdv!=0 AND $autrePaRdv!="") echo $autrePaRdv;?>">
						<input type="text" name="newTelPa" id="newTelPa" style="width:100px;<?php if($comptRdv!=0 AND $autrePaRdv!=NULL) { echo 'display:inline;';}else{ echo 'display:none;';}?>" placeholder="Telephone" value="<?php if($comptRdv!=0 AND $autreTelRdv!="") echo $autreTelRdv;?>">
						
					</td>
				</tr>
								   
				<?php
				if(!isset($_SESSION['codeM']))
				{
				?>
				<tr>
					<td style="text-align:right;">Medecin</td>
					<td style="text-align:left;">	
						<select name="idMed" id="idMed" class="chosen-select">
						<?php
						$bd=$connexion->query("SELECT *FROM utilisateurs u,medecins m WHERE u.id_u=m.id_u ORDER BY nom_u");

						while ($ligne=$bd->fetch(PDO::FETCH_OBJ))
						{
						?>
						<option value="<?php echo $ligne->id_u;?>"><?php echo $ligne->full_name;?></option>

						<?php
						
						}
							$bd->closeCursor();
						?>
						</select>
						

					</td>
				</tr>
								
				<?php
				}else{
				?>
				<tr style="display:none;">
					<td style="text-align:right;">Medecin</td>
					<td style="text-align:left;">	
						<input type="text"  name="idMed" value="<?php echo $_SESSION['id'];?>" style="width:200px;"/>
					</td>
				</tr>
						
				<?php
				}
				?>
				<tr>
					<td style="text-align:right">Date rendez-vous</td>
					<td style="text-align:left;" colspan=2>
						<select name="anneerdv" id="anneerdv" style="width:100px;background:#f8f8f8;">
							<?php
							$juska=date('Y')+5;
							
							for($a=date('Y');$a<=$juska;$a++)
							{
							?>
								<option value="<?php echo $a;?>" <?php if(date('Y')==$a OR ($comptRdv!=0 AND $anneeRdv==$a)) echo 'selected="selected"';?>>
								
									<?php echo $a;?>
								</option>
							<?php
							}
							?>
						</select>						
				
						<select name="moisrdv" id="moisrdv" style="width:120px;background:#f8f8f8;">
							<?php
							for($m=1;$m<=12;$m++)
							{
								$moisString=date("F",mktime(0,0,0,$m,10));
								if($m<10)
								{
									$m='0'.$m;
								}
							?>
								<option value="<?php echo $m;?>" <?php if(date('F')==$moisString) echo 'selected="selected"'; if(isset($_GET['idconsu']) AND $comptRdv!=0){ if(date("F",mktime(0,0,0,$moisRdv,10))==$moisString) { echo 'selected="selected"';} }?>>
								
								<?php 
									echo $moisString;
								?>
								</option>
							<?php
							}
							?>
						</select>
						
						<select name="joursrdv" id="joursrdv" style="width:80px;background:#f8f8f8;" required="required">
							<?php
							for($d=1;$d<=31;$d++)
							{
								if($d<10)
								{
									$d='0'.$d;
								}
							?>
								<option value="<?php echo $d;?>" <?php if($comptRdv!=0 AND $joursRdv==$d) echo 'selected="selected"';?>>
								<?php echo $d;?>
								</option>
							<?php
							}
							?>
						</select>
				
					</td>
												
					<td style="text-align:left;">à
						<select name="heurerdv" id="heurerdv" style="width:100px;height:40px;background:#f8f8f8;">
						<?php 
						for($h=0;$h<=23;$h++)
						{
							if($h<10)
							{
								$h='0'.$h;
							}
						?>
							<option value='<?php echo $h;?>' <?php if($comptRdv!=0 AND $heureRdv==$h) echo 'selected="selected"';?>><?php echo $h;?></option>
						<?php 
						}
						?>
						</select>H :
						<select name="minrdv" id="minrdv" style="width:100px;height:40px;background:#f8f8f8;">
						<?php 
						for($m=0;$m<=59;$m++)
						{
							if($m<10)
							{
								$m='0'.$m;
							}
						?>
							<option value='<?php echo $m;?>' <?php if($comptRdv!=0 AND $minRdv==$m) echo 'selected="selected"';?>><?php echo $m;?></option>
						<?php 
						}
						?>
						</select>Min
					</td>
				</tr>
						
				<tr>
					<td style="text-align:right">Motif</td>
					<td style="text-align:left">
						<textarea name="motifrdv" id="motifrdv" value="" style="margin:auto; max-width:300px; max-height:300px; min-height:50px; min-width:50px;background:#f8f8f8;" placeholder="Tapez ici............"><?php
						if($comptRdv!=0 AND $motifRdv!="")
						{
							echo $motifRdv;
						}else{
							echo "";
						}
						?></textarea>

					</td>
				</tr>
				
			</table>
			
			<table class="cons-info" style="background:#f8f8f8;border: none;margin:10px auto;">
			
				<tr>
					<td>
						<input type="hidden" name="idrdv" value="<?php echo $idRdv;?>" style="width:200px;"/>
				
						<button type="submit" name="updatebtn" class="btn-large" style="width:200px;"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(32);?></button>
						
						<a href="rendezvous1.php" class="btn-large-inversed" style="width:150px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></button>
					</td>
				</tr>
				
			</table>
			
		</form>

	</div>
	<?php
	}
	?>
	
	<form class="ajax" action="search.php" method="get">
		<p>			
			<table align="center">
				<tr>
					<td>
						<label for="d"><?php echo getString(268);?></label>
						<input type="text" name="d" id="d" onclick="ShowSearch('bydate')"/>
					</td>
							
					<td>
						<label for="q"><?php echo getString(80);?></label>
						<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
					</td>
					
					<td>
						<label for="r"><?php echo getString(223);?></label>
						<input type="text" name="r" id="r" onclick="ShowSearch('byri')"/>
					</td>
					
					<td>
						<label for="s"><?php echo getString(91);?></label>
						<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
					</td>
				</tr>
			</table>
			
		</p>
	</form>

		
	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsDate"></div>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsRI"></div>

	<script type="text/javascript">
	$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#d').keyup( function(){
		$field = $(this);
		$('#resultsDate').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_rdv1.php?date=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
		url : 'traitement_rdv1.php?name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" style="margin:5px" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
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
	  // détection de la saisie dans le champ de recherche
	  $('#s').keyup( function(){
		$field = $(this);
		$('#resultsSN').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_rdv1.php?sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
	  $('#r').keyup( function(){
		$field = $(this);
		$('#resultsRI').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_rdv1.php?ri=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 'r='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#resultsRI').html(data); // affichage des résultats dans le bloc
		}
		  });
		}		
	  });
	});

	</script>
		
	<?php
	if(isset($_GET['divRdv']))
	{
	?>
	
		<?php
		
		if(isset($_SESSION['codeM']))
		{
			$ownrdv='AND r.id_uM='.$_SESSION['id'].'';
		}else{
			$ownrdv='';
		}
		
		$resultats=$connexion->query('SELECT *FROM rendez_vous r, utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=r.numero '.$ownrdv.' AND u.full_name LIKE \'%'.$_GET['fullname'].'%\'') or die( print_r($connexion->errorInfo()));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);
		$comptResultats=$resultats->rowCount();
		
		$resultatsRdvTable=$connexion->query('SELECT *FROM rendez_vous r WHERE r.autrePa LIKE \'%'.$_GET['fullname'].'%\'') or die( print_r($connexion->errorInfo()));
		
		$resultatsRdvTable->setFetchMode(PDO::FETCH_OBJ);
		$comptResultatsRdvTable=$resultatsRdvTable->rowCount();
		
		if($comptResultats!=0 OR $comptResultatsRdvTable!=0)
		{
		?>
			<h2><?php echo getString(60)?></h2>
   
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th>Date du rendez-vous</th>
						<th>Heures</th>
						<th>Nom du patient</th>
						<?php
						if(!isset($_SESSION['codeM']))
						{
						?>
						<th>Nom du medecin</th>
						<?php
						}
						?>
						<th>Motif</th>
						<th>Add By</th>
						<th>Date d'attribution</th> 
						<th>Action</th>
						<th></th>
					</tr> 
				</thead>
				
				<tbody>
				<?php
				try
				{

					while($ligne=$resultats->fetch())
					{
					?>
						<tr> 
							<td style="text-align:center;"><?php echo $ligne->daterdv;?></td>
							<td style="text-align:center;"><?php echo $ligne->heurerdv;?></td>
							<td style="text-align:center;">
								<?php 
								$codeP=$ligne->numero;
								
								$resultat=$connexion->query("SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero='".$codeP."'") or die( print_r($connexion->errorInfo()));
								
								while($ligneP=$resultat->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo '<span style="text-align:center;font-weight:bold;">'.$ligneP->full_name.'</span><br/> ('.$ligneP->telephone.')';
								}
								$resultat->closeCursor();
								
								if($ligne->autrePa!=NULL)
								{
									echo '<span style="text-align:center;font-weight:bold;">'.$ligne->autrePa.'</span><br/> ('.$ligne->autreTel.')';
								}
								?>
							</td>
							<?php
							if(!isset($_SESSION['codeM']))
							{
							?>
							<td style="text-align:center;">
								<?php 
								$codeM=$ligne->id_uM;
								
								$resultat=$connexion->query('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u='.$codeM.'') or die( print_r($connexion->errorInfo()));
								
								while($ligneM=$resultat->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo $ligneM->full_name;
								}
								$resultat->closeCursor();
								?>
							</td>
							<?php
							}
							?>
							<td style="text-align:center;"><?php echo $ligne->motifrdv;?></td>
							
							<td style="text-align:center;">
							<?php 
								$doneBy=$ligne->doneby;
								
								$result=$connexion->query("SELECT *FROM utilisateurs u WHERE u.id_u='".$doneBy."'") or die( print_r($connexion->errorInfo()));
								
								while($ligneDoneBy=$result->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo $ligneDoneBy->full_name;
								}
								$result->closeCursor();
								?>
							</td>

							<td style="text-align:center;"><?php echo $ligne->dateattribution;?></td>
							<?php
														
							$comptRdvDone=$connexion->prepare('SELECT *FROM rendez_vous r, consultations c WHERE c.dateconsu=r.daterdv AND c.dateconsu=:daterdv AND c.numero=:num AND c.id_uM=:idMed AND c.done=1 ORDER BY r.id_rdv');
							$comptRdvDone->execute(array(
							'daterdv'=>$ligne->daterdv,
							'num'=>$ligne->numero,
							'idMed'=>$ligne->id_uM
							));
								
							$comptRdvDone->setFetchMode(PDO::FETCH_OBJ);
									
							$countRdvDone = $comptRdvDone->rowCount();
							
							// echo 'SELECT *FROM rendez_vous r, consultations c WHERE c.dateconsu=r.daterdv AND c.dateconsu='.$ligne->daterdv.' AND c.numero='.$ligne->numero.' AND c.id_uM='.$ligne->id_uM.' AND c.done=1 ORDER BY r.id_rdv : '.$countRdvDone.'<br/>';
								
							if($_SESSION['id']==$ligne->doneby AND $countRdvDone==0)
							{
							?>
								<td style="text-align:center;">
									<?php
									if($ligne->daterdv >= $annee)
									{
									?>
									<a href="rendezvous1.php?editrdv=<?php echo $ligne->id_rdv;?>" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
									<?php
									}
									?>
									<a href="rendezvous1.php?deleterdv=<?php echo $ligne->id_rdv;?>" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i> </a>
								</td>
							<?php
							}else{
							?>
							<td style="text-align:center;">---</td>
							<?php
							}
								
							if($countRdvDone != 0)
							{
							?>
							
							<td style="text-align:center;">
								<i class="fa fa-check fa-lg fa-fw"></i>
							</td>
							<?php
							}
							?>
							</td>

						</tr>
				<?php
					}
					$resultats->closeCursor();
					
					while($ligne=$resultatsRdvTable->fetch())
					{
					?>
						<tr> 
							<td style="text-align:center;"><?php echo $ligne->daterdv;?></td>
							<td style="text-align:center;"><?php echo $ligne->heurerdv;?></td>
							<?php
							if(!isset($_SESSION['codeM']))
							{
							?>
							<td style="text-align:center;">
								<?php 
								$codeM=$ligne->id_uM;
								
								$resultat=$connexion->query('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u='.$codeM.'') or die( print_r($connexion->errorInfo()));
								
								while($ligneM=$resultat->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo $ligneM->full_name;
								}
								$resultat->closeCursor();
								?>
							</td>
							<?php
							}
							?>
							<td style="text-align:center;">
								<?php 
								echo '<span style="text-align:center;font-weight:bold;">'.$ligne->autrePa.'</span><br/> ('.$ligne->autreTel.')';
								?>
							</td>
							<td style="text-align:center;"><?php echo $ligne->motifrdv;?></td>
							
							<td style="text-align:center;">
							<?php 
								$doneBy=$ligne->doneby;
								
								$result=$connexion->query("SELECT *FROM utilisateurs u WHERE u.id_u='".$doneBy."'") or die( print_r($connexion->errorInfo()));
								
								while($ligneDoneBy=$result->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo $ligneDoneBy->full_name;
								}
								$result->closeCursor();
								?>
							</td>

							<td style="text-align:center;"><?php echo $ligne->dateattribution;?></td>
							<?php
														
							$comptRdvDone=$connexion->prepare('SELECT *FROM rendez_vous r, consultations c WHERE c.dateconsu=r.daterdv AND c.dateconsu=:daterdv AND c.numero=:num AND c.id_uM=:idMed AND c.done=1 ORDER BY r.id_rdv');
							$comptRdvDone->execute(array(
							'daterdv'=>$ligne->daterdv,
							'num'=>$ligne->numero,
							'idMed'=>$ligne->id_uM
							));
								
							$comptRdvDone->setFetchMode(PDO::FETCH_OBJ);
									
							$countRdvDone = $comptRdvDone->rowCount();
							
							// echo 'SELECT *FROM rendez_vous r, consultations c WHERE c.dateconsu=r.daterdv AND c.dateconsu='.$ligne->daterdv.' AND c.numero='.$ligne->numero.' AND c.id_uM='.$ligne->id_uM.' AND c.done=1 ORDER BY r.id_rdv : '.$countRdvDone.'<br/>';
								
							if($_SESSION['id']==$ligne->doneby AND $countRdvDone==0)
							{
							?>
								<td style="text-align:center;">
									<?php
									if($ligne->daterdv >= $annee)
									{
									?>
									<a href="rendezvous1.php?editrdv=<?php echo $ligne->id_rdv;?>" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
									<?php
									}
									?>
									<a href="rendezvous1.php?deleterdv=<?php echo $ligne->id_rdv;?>" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i> </a>
								</td>
							<?php
							}else{
							?>
							<td style="text-align:center;">---</td>
							<?php
							}
								
							if($countRdvDone != 0)
							{
							?>
							
							<td style="text-align:center;">
								<i class="fa fa-check fa-lg fa-fw"></i>
							</td>
							<?php
							}
							?>
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
			<h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['fullname'])){ echo $_GET['fullname'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
	<?php
		}
	}
	
			
		if(isset($_SESSION['codeM']))
		{
			$ownrdv='WHERE r.id_uM='.$_SESSION['id'].'';
		}else{
			$ownrdv='';
		}
		
		$resultats=$connexion->query('SELECT *FROM rendez_vous r '.$ownrdv.' ORDER BY r.daterdv ASC') or die( print_r($connexion->errorInfo()));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);
			
		$comptAllRdv=$resultats->rowCount();
			
		if($comptAllRdv!=0)
		{
	?>	
			<h2><?php echo 'Liste des Rendez-vous';?></h2>
	   
		<div style="overflow:auto;max-height:500px;">
			
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th>Date du rendez-vous</th>
						<th>Heures</th>
						<th>Nom du patient</th>
						<?php
						if(!isset($_SESSION['codeM']))
						{
						?>
						<th>Nom du medecin</th>
						<?php
						}
						?>
						<th>Motif</th>
						<th>Add By</th>
						<th>Date d'attribution</th> 
						<th>Action</th>
						<th></th>
					</tr> 
				</thead>
				
				<tbody>
				<?php
				try
				{

					while($ligne=$resultats->fetch())
					{
					?>
						<tr> 
							<td style="text-align:center;"><?php echo $ligne->daterdv;?></td>
							<td style="text-align:center;"><?php echo $ligne->heurerdv;?></td>
							<td style="text-align:center;">
								<?php 
								$codeP=$ligne->numero;
								
								$resultat=$connexion->query("SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero='".$codeP."'") or die( print_r($connexion->errorInfo()));
								
								while($ligneP=$resultat->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo '<span style="text-align:center;font-weight:bold;">'.$ligneP->full_name.'</span><br/> ('.$ligneP->telephone.')';
								}
								$resultat->closeCursor();
								
								if($ligne->autrePa!=NULL)
								{
									echo '<span style="text-align:center;font-weight:bold;">'.$ligne->autrePa.'</span><br/> ('.$ligne->autreTel.')';
								}
								?>
							</td>
							<?php
							if(!isset($_SESSION['codeM']))
							{
							?>
							<td style="text-align:center;">
								<?php 
								$codeM=$ligne->id_uM;
								
								$resultat=$connexion->query('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u='.$codeM.'') or die( print_r($connexion->errorInfo()));
								
								while($ligneM=$resultat->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo $ligneM->full_name;
								}
								$resultat->closeCursor();
								?>
							</td>
							<?php
							}
							?>
							<td style="text-align:center;"><?php echo $ligne->motifrdv;?></td>
							
							<td style="text-align:center;">
							<?php 
								$doneBy=$ligne->doneby;
								
								$result=$connexion->query("SELECT *FROM utilisateurs u WHERE u.id_u='".$doneBy."'") or die( print_r($connexion->errorInfo()));
								
								while($ligneDoneBy=$result->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
								{
									echo $ligneDoneBy->full_name;
								}
								$result->closeCursor();
								?>
							</td>

							<td style="text-align:center;"><?php echo $ligne->dateattribution;?></td>
							<?php
														
							$comptRdvDone=$connexion->prepare('SELECT *FROM rendez_vous r, consultations c WHERE c.dateconsu=r.daterdv AND c.dateconsu=:daterdv AND c.numero=:num AND c.id_uM=:idMed AND c.done=1 ORDER BY r.id_rdv');
							$comptRdvDone->execute(array(
							'daterdv'=>$ligne->daterdv,
							'num'=>$ligne->numero,
							'idMed'=>$ligne->id_uM
							));
								
							$comptRdvDone->setFetchMode(PDO::FETCH_OBJ);
									
							$countRdvDone = $comptRdvDone->rowCount();
							
							// echo 'SELECT *FROM rendez_vous r, consultations c WHERE c.dateconsu=r.daterdv AND c.dateconsu='.$ligne->daterdv.' AND c.numero='.$ligne->numero.' AND c.id_uM='.$ligne->id_uM.' AND c.done=1 ORDER BY r.id_rdv : '.$countRdvDone.'<br/>';
								
							if($_SESSION['id']==$ligne->doneby AND $countRdvDone==0)
							{
							?>
								<td style="text-align:center;">
									<?php
									if($ligne->daterdv >= $annee)
									{
									?>
									<a href="rendezvous1.php?editrdv=<?php echo $ligne->id_rdv;?>" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
									<?php
									}
									?>
									<a href="rendezvous1.php?deleterdv=<?php echo $ligne->id_rdv;?>" class="btn"><i class="fa fa-trash fa-1x fa-fw"></i> </a>
								</td>
							<?php
							}else{
							?>
							<td style="text-align:center;">---</td>
							<?php
							}
								
							if($countRdvDone != 0)
							{
							?>
							
							<td style="text-align:center;">
								<i class="fa fa-check fa-lg fa-fw"></i>
							</td>
							<?php
							}
							?>
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
			
		</div>
		
		<?php
		}else{
			?>
				
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th>No Rendez-vous Yet</th>
					</tr>
				</thead>
			</table>
	<?php
		}


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
		<p style="margin:0"><span style="color:#a00000">Medical FIle®</span> is a product of <span style="font-style:bold;">Innovate Solutions Ltd</span>. ©2022-2016 All rights reserved.</p>
	</footer>
</div>

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#numPa').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		$('#idMed').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
		
		
	
	
	function ShowAutrePa(){
		
		var patient=document.getElementById('numPa').value;
		
		if(patient=="0")
		{
			document.getElementById('newPa').style.display='inline';
			document.getElementById('newTelPa').style.display='inline';
		}else{
			document.getElementById('newPa').style.display='none';
			document.getElementById('newTelPa').style.display='none';
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


	function ShowSearch(search)
	{
		if(search =='bydate')
		{
			document.getElementById('resultsDate').style.display='inline';
			document.getElementById('results').style.display='none';
			document.getElementById('resultsSN').style.display='none';
			document.getElementById('resultsRI').style.display='none';
		}
	
		if(search =='byname')
		{
			document.getElementById('resultsDate').style.display='none';
			document.getElementById('results').style.display='inline';
			document.getElementById('resultsSN').style.display='none';
			document.getElementById('resultsRI').style.display='none';
		}
		
		if(search =='bysn')
		{
			document.getElementById('resultsDate').style.display='none';
			document.getElementById('results').style.display='none';
			document.getElementById('resultsSN').style.display='inline';
			document.getElementById('resultsRI').style.display='none';
		}
		
		if(search =='byri')
		{
			document.getElementById('resultsDate').style.display='none';
			document.getElementById('results').style.display='none';
			document.getElementById('resultsSN').style.display='none';
			document.getElementById('resultsRI').style.display='inline';
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
