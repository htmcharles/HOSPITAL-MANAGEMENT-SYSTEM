<?php
session_start();

include("connect.php");
include("connectLangues.php");


$annee = date('Y').'-'.date('m').'-'.date('d');

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<meta charset="utf-8"/>
	<title><?php echo 'HOSPITALISATION';?></title>
	
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
	
		
	<script src="myQuery.js"></script>

	<script type="text/javascript">
	 
	function controlHospForm(theForm){
		var rapport="";
		
		
		rapport +=controlLitNum(theForm.litnumber);
		
		if (rapport != "") {
		alert("Veuillez corrigez les erreurs suivantes:\n" + rapport);
			return false;
		}
	}

	function controlLitNum(fld){
		var erreur="";

		if(fld.value==""){
		erreur="<?php echo 'Le numero du lit';?>\n";
		fld.style.background="rgba(255,255,0,0.3)";
		}	
		return erreur;
	}

	</script>

</head>

<body>
<?php

if(isset($_GET['num']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
	$result->execute(array(
	'operation'=>$_GET['num']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
		$idPa=$ligne->id_u;
		$num=$ligne->numero;
		$nom_uti=$ligne->nom_u;
		$prenom_uti=$ligne->prenom_u;
		$sexe=$ligne->sexe;
		$dateN=$ligne->date_naissance;
		$poidsPa=$ligne->poidsPa;
		$taillePa=$ligne->taillePa;
		$temperaPa=$ligne->temperaturePa;
		$tensionartPa=$ligne->tensionarteriellePa;
		$poulsPa=$ligne->poulsPa;
		$bill=$ligne->bill;
		$idassu=$ligne->id_assurance;
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
			
			
	$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
	
	$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
			
	$assuCount = $comptAssuConsu->rowCount();
	
	for($i=1;$i<=$assuCount;$i++)
	{
		
		$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
		$getAssuConsu->execute(array(
		'idassu'=>$idassu
		));
		
		$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

		if($ligneNomAssu=$getAssuConsu->fetch())
		{
			$presta_assu='prestations_'.strtolower($ligneNomAssu->nomassurance);
		}
	}
								
						
	if(isset($_GET['updateidPahosp']))
	{
		$resultats=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.id_hosp=:operation;');
		$resultats->execute(array(
		'operation'=>$_GET['idhosp']	
		));
		
		$comptidH=$resultats->rowCount();	
		
		if($comptidH!=0)
		{
			$resultats->setFetchMode(PDO::FETCH_OBJ);
			
			while($ligneH=$resultats->fetch())
			{
				$numroomEdit=$ligneH->numroomPa;
				$numlitEdit=$ligneH->numlitPa;
				$dateInEdit=$ligneH->dateEntree;
				
				$dateheure=$ligneH->dateEntree.' '.$ligneH->heureEntree.':00';
				$heureEdit= date('H', strtotime($dateheure));
				$minEdit=date('i', strtotime($dateheure));
			}
			$resultats->closeCursor();
		}
	
	}
}

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
					<form method="post" action="hospForm.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="hospForm.php?english=english" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="hospForm.php?francais=francais" class="btn"><?php echo getString(29);?></a>
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

<div class="account-container" style="width:80%; text-align:center;margin-top:20px;">

<div id='cssmenu' style="text-align:center">

<ul style="margin-top:20px;background:none;border:none;">

<?php

$id=$_SESSION['id'];
$annee = date('Y').'-'.date('m').'-'.date('d');

$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");


$comptidI=$sqlI->rowCount();


?>
			<li style="width:50%;"><a href="patients1_hosp.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients hospitalisés"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients hospitalisés</a></li>
			
			<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>

		</ul>

		
		<ul style="margin-top:20px; background:none;border:none;">
			
			<a href="utilisateurs_hosp.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(88);?></a>

				
			<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

				<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
				
				<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
				
				<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

			</div>
				
		</ul>
	
			
		</div>


	<div id="hosp" style="margin-top:50px">

		<form method="post" action="traitement_hospForm.php?num=<?php echo $_GET['num'];?><?php if(isset($_GET['idhosp'])){ echo '&idhosp='.$_GET['idhosp'];}?><?php if(isset($_GET['updateidPahosp'])){ echo '&numroomEdit='.$numroomEdit.'&numlitEdit='.$numlitEdit;}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlHospForm(this)" enctype="multipart/form-data">
			
			
			<table style="margin-top:40px; margin-bottom:-10px; width:100%;">
				<tr>
					<td></td>
					<td>
						<span style="position:relative;">
			<h1><?php echo 'Formulaire d\'hospitalisation';?></h1>
</span>
					</td>
					<td style="font-size:18px; padding-right:20px; text-align:right; width:33.333%;">
						<span style="font-weight:bold; color:#a00000;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo getString(71) ?>: </span><?php echo date('d-M-Y', strtotime($annee));?>
					</td>
				</tr>			
			</table>			
				
		
			<?php
			if(isset($_GET['num']))
			{
			?>
				<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:100%;">
					<tr>
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $nom_uti.' '.$prenom_uti;?>
						</td>
						
						<td style="font-size:18px; text-align:center; width:23.333%;">
							<span style="font-weight:bold;"><?php echo "Numero" ?> : </span><?php echo $_GET['num'];?>
						</td>
						
						<td style="font-size:18px; text-align:center; width:23.333%;">
							<span style="font-weight:bold;"><?php echo getString(11) ?> : </span><?php echo $sexe;?>
						</td>
						
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;">Age : </span><?php echo $an;?>
						</td>
					</tr>
				</table>

			<?php
			}
			?>	

			
		<?php
		if(!isset($_GET['updateidPahosp']) AND isset($_GET['num']))
		{
		?>
			<div id="formHosp" style="margin-top:50px">	
			
				<table cellspacing="0" align="center">

					<tr style="display:none;">
						<td><label for="num"><?php echo 'Patient n°';?></label></td>
						<td>
							<input type="text" name="num" id="num" size="10px" value="<?php if(isset($_GET['num'])){ echo $_GET['num'];}?>" readonly="readonly" />
							<input type="text" name="idPa" id="idPa" value="<?php if(isset($_GET['num'])){ echo $idPa;}?>" readonly="readonly"/>
						</td>
					</tr>
					
					<tr>
						<td><label for="roomnumber"><?php echo 'Numero de chambre';?></label></td>
						<td>
							<select name="roomnumber" id="roomnumber" onchange="ShowLit('room')">
								<option value=""><?php echo 'Selectionner la chambre...';?></option>
								<?php

								$resultats=$connexion->query('SELECT *FROM rooms WHERE statusA!=1 OR statusB!=1 ORDER BY id_room');
								
								$freerooms=$resultats->rowCount();
								
								while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
								{
								?>
								<option value='<?php echo $ligne->id_room;?>' id="roomNum" name="roomNum"  <?php if(isset($_GET['iduti']) and $idroom == $ligne->id_room){echo "selected='selected'";}?>>
								<?php
									echo $ligne->numroom;
									
									if($ligne->id_prestationHosp==29)
									{
										echo ' (Chambre à 1 lit avec toilette à l\'extérieur)';
									}else{								
										if($ligne->id_prestationHosp==30)
										{
											echo ' (Chambre à 1 lit avec toilette à l\'interieur)';
										}else{
											if($ligne->id_prestationHosp==31)
											{
												echo ' (Chambre à 2 lit avec toilette à l\'extérieur)';
											}else{
												if($ligne->id_prestationHosp==32)
												{
													echo ' (Chambre à 2 lit avec toilette à l\'interieur)';
												}else{
													echo '';
												}
											}
										}
									}
								
								?>
								</option>
								<?php
								}
								?>
							</select>
							<script src="jQuery.js"></script>
							<script>
							$(function(){
								$("#roomnumber").change(function(){
							var roomChoisi='roomnumber='+$(this).val();
								// alert(roomChoisi);
								$.ajax({
									url:"roomLitFree.php",
									type:"POST",
									data:roomChoisi,
									
									success:function(resultat)
									{
										
										// alert(resultat);
										$('#litnumber').html(resultat);
									}
									});
								});
							});
							</script>
							
						</td>
						
						<td style="padding:20px;">
						<?php
						
						$results=$connexion->query('SELECT *FROM rooms WHERE statusA=1 AND (statusB=1 OR statusB IS NULL) ORDER BY numroom');
						
						$fullrooms=$results->rowCount();
						
						$numroom = array();
						
						if($fullrooms!=0)
						{
							while($line=$results->fetch(PDO::FETCH_OBJ))
							{
								$numroom=$line->numroom;
								
								echo "<input type='hidden' name='numroom[]' class='numroom' value=".$numroom.">";
							}
						?>
							<?php echo '( '.$fullrooms.' rooms are full )';?>
							<span onclick="ShowRoom('showroom')" class="btn" title="Watch full rooms"><?php echo 'Click to watch';?></span>
						<?php
						}
						?>
						</td>
					</tr>

				</table>
			
				<table class="tablesorter tablesorter2" id="lit" style="display:none;width:auto;">

				<tr>
					<td><?php echo 'N° Lit';?></td>
					<td>		
						<select style="width:340px;" name="litnumber" id="litnumber">
							<option value=""><?php echo 'Selectionner ici...';?></option>
						
						</select>
					</td>	
						
				</tr>
				
				<tr style="background-color:rgb(248, 248, 248)">
					<td><?php echo "Date d'entrée";?></td>
					<td>
						<input type="text" id="dateEntree" name="dateEntree" onclick="ds_sh(this);" value="<?php if(isset($_GET['iduti'])){ echo "";}else{ $annee = date('Y').'-'.date('m').'-'.date('d'); echo $annee;}?>"/>
						
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<table align="center">
							<tr style="background-color:rgb(248, 248, 248);">
								<td><?php echo "Heures d'entrée";?>
									<select name="heureEntree" id="heureEntree" onchange="ShowLit('room')" style="width:70px">
									<?php
									$heure=date('H')+2;
									
									if($heure >23)
									{
										$heure=$heure-24;
									}
									
									for($i=0;$i<=23;$i++)
									{
										if($i<10)
											$h='0'.$i;
										else
											$h=$i;
									?>
										<option value='<?php echo $i;?>' id="heure" name="heure" <?php if($h==$heure){ echo 'selected="selected"';}?>>
										
										<?php if($i<10)echo '0'.$i; else echo $i;?>
										</option>
									<?php
									}
									?>
									</select>
									
									<span style="font-weight:bold;"> : </span>
									<select name="minuteEntree" id="minuteEntree" onchange="ShowLit('room')" style="width:70px">
									<?php
									$min=date('i');

									for($i=0;$i<=59;$i++)
									{
										if($i<10)
											$m='0'.$i;
										else
											$m=$i;
									?>
										<option value='<?php echo $i;?>' id="min" name="min" <?php if($m==$min){ echo 'selected="selected"';}?>>
										<?php if($i<10)echo '0'.$i; else echo $i;?>
									</option>
									<?php
									}
									?>
									</select>
									
								</td>
							</tr>
						</table>
					</td>
				</tr>

				</table>
				
				
				<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; width:430px;">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
				</table>
				
				<table id="savehospbtn" style="display:none;" align="center">
					<tr>
						<td>
							<button type="submit" class="btn-large" name="savehospbtn">
								<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(28);?>
							</button>
					
						</td>
					</tr>
				</table>
				
			</div>
			
		<?php
		}
		?>
			
		<?php
		if(isset($_GET['updateidPahosp']) AND isset($_GET['num']))
		{
		?>
			<div id="formHosp" style="margin-top:50px">	
			
				<table cellspacing="0" align="center">

					<tr style="display:none;">
						<td><label for="num"><?php echo 'Patient n°';?></label></td>
						<td>
							<input type="text" name="num" id="num" size="10px" value="<?php if(isset($_GET['num'])){ echo $_GET['num'];}?>" readonly="readonly" />
							<input type="text" name="idPa" id="idPa" value="<?php if(isset($_GET['num'])){ echo $idPa;}?>" readonly="readonly"/>
						</td>
					</tr>
					
					<tr>
						<td><label for="roomnumber"><?php echo 'Numero de chambre';?></label></td>
						<td>
							<select name="roomnumber" id="roomnumber" onchange="ShowLit('room')" style="width:150px">
								<?php

								$resultats=$connexion->query('SELECT *FROM rooms ORDER BY id_room');
								
								$freerooms=$resultats->rowCount();
								
								while($ligne=$resultats->fetch(PDO::FETCH_OBJ))
								{
								?>
								<option value='<?php echo $ligne->id_room;?>' id="roomNum" name="roomNum"  <?php if(isset($_GET['updateidPahosp']) and $numroomEdit == $ligne->numroom){echo "selected='selected'";}?>>
								<?php
									echo $ligne->numroom;
									/* 
									if($ligne->id_prestationHosp==29)
									{
										echo ' (Chambre à 1 lit avec toilette à l\'extérieur)';
									}else{								
										if($ligne->id_prestationHosp==30)
										{
											echo ' (Chambre à 1 lit avec toilette à l\'interieur)';
										}else{
											if($ligne->id_prestationHosp==31)
											{
												echo ' (Chambre à 2 lit avec toilette à l\'extérieur)';
											}else{
												if($ligne->id_prestationHosp==32)
												{
													echo ' (Chambre à 2 lit avec toilette à l\'interieur)';
												}else{
													echo '';
												}
											}
										}
									} */
								
								?>
								</option>
								<?php
								}
								?>
							</select>
							<script src="jQuery.js"></script>
							<script>
							$(function(){
								$("#roomnumber").change(function(){
							var roomChoisi='roomnumber='+$(this).val();
								// alert(roomChoisi);
								$.ajax({
									url:"roomLitFree.php",
									type:"POST",
									data:roomChoisi,
									
									success:function(resultat)
									{
										
										// alert(resultat);
										$('#litnumber').html(resultat);
									}
									});
								});
							});
							</script>
							
						</td>
						
						<td style="padding:20px;">
						<?php
						
						$results=$connexion->query('SELECT *FROM rooms WHERE statusA=1 AND statusB=1 ORDER BY numroom');
						
						$fullrooms=$results->rowCount();
						
						$numroom = array();
						
						if($fullrooms!=0)
						{
							while($line=$results->fetch(PDO::FETCH_OBJ))
							{
								$numroom=$line->numroom;
								
								echo "<input type='hidden' name='numroom[]' class='numroom' value=".$numroom.">";
							}
						?>
							<?php echo '( '.$fullrooms.' rooms are full )';?>
							<span onclick="ShowRoom('showroom')" class="btn" title="Watch full rooms"><?php echo 'Click to watch';?></span>
						<?php
						}
						?>
						</td>
					</tr>

				</table>
			
				<table class="tablesorter tablesorter2" id="lit" style="width:auto;">

				<tr>
					<td><?php echo 'N° Lit';?></td>
					<td>	
						<?php
						
						$req=$connexion->prepare('SELECT * FROM rooms WHERE numroom=:room');
						$req->execute(array(
						'room'=>$numroomEdit
						));

						?>	
						<select name='litnumber' id='litnumber'>
							
						<?php
						while($reponse=$req->fetch(PDO::FETCH_ASSOC))
						{
							if($reponse['statusA']==0 OR $numlitEdit=="A")
							{
						?>	
							<option value='A' <?php if($numlitEdit=="A"){ echo 'selected="selected"';}?>>A</option>
						<?php
							}
							
							if($reponse['statusB']!=NULL)
							{
								if($reponse['statusB']==0 OR $numlitEdit=="B")
								{
						?>
								<option value='B' <?php if($numlitEdit=="B"){ echo 'selected="selected"';}?>>B</option>
						<?php
								}
							}
						}
						?>
						</select>
					</td>	
						
				</tr>
				
				<tr style="background-color:rgb(248, 248, 248)">
					<td><?php echo "Date d'entrée";?></td>
					<td>
						<input type="text" id="dateEntree" name="dateEntree" onclick="ds_sh(this);" value="<?php if(isset($_GET['updateidPahosp'])){ echo $dateInEdit;}else{ $annee = date('Y').'-'.date('m').'-'.date('d'); echo $annee;}?>"/>
						
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<table align="center">
							<tr style="background-color:rgb(248, 248, 248);">
								<td><?php echo "Heures d'entrée";?>
									<select name="heureEntree" id="heureEntree" onchange="ShowLit('room')" style="width:70px">
									<?php
									$heure=date('H')+2;
									
									if($heure >23)
									{
										$heure=$heure-24;
									}
									
									for($i=0;$i<=23;$i++)
									{
										if($i<10)
											$h='0'.$i;
										else
											$h=$i;
									?>
										<option value='<?php echo $i;?>' id="heure" name="heure" <?php if($h==$heureEdit){ echo 'selected="selected"';}?>>
										
										<?php if($i<10)echo '0'.$i; else echo $i;?>
										</option>
									<?php
									}
									?>
									</select>
									
									<span style="font-weight:bold;"> : </span>
									<select name="minuteEntree" id="minuteEntree" onchange="ShowLit('room')" style="width:70px">
									<?php
									$min=date('i');

									for($i=0;$i<=59;$i++)
									{
										if($i<10)
											$m='0'.$i;
										else
											$m=$i;
									?>
										<option value='<?php echo $i;?>' id="min" name="min" <?php if($m==$minEdit){ echo 'selected="selected"';}?>>
										<?php if($i<10)echo '0'.$i; else echo $i;?>
									</option>
									<?php
									}
									?>
									</select>
									
								</td>
							</tr>
						</table>
					</td>
				</tr>

				</table>
				
				
				<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none; width:430px;">
				<tr>
					<td style="padding:5px;" id="ds_calclass"></td>
				</tr>
				</table>
				
				<table id="updatehospbtn" align="center">
					<tr>
						<td>
							<button type="submit" class="btn-large" name="updatehospbtn" style="width:250px; margin-top:20px;">
								<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(28);?>
							</button>
						</td>
						
						<td style="padding-top:13px;text-align:center;">
						
							<a class="btn-large-inversed" href="patients1_hosp.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>">
								<i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?>
							</a>
						</td>
					</tr>
				</table>
				
			</div>
		<?php
		}
		?>
			

		</form>
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
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
	}
	
	if( list =='Msg')
	{
		document.getElementById('divMenuMsg').style.display='inline';
		document.getElementById('divMenuUser').style.display='none';
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
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
	
}


function controlFormPassword(theForm){
	var rapport="";
	
	rapport +=controlPass(theForm.Pass);

		if (rapport != "") {
		alert("Please review the following fields:\n" + rapport);
					return false;
		 }
}


function ShowLit(room)
{
	if( room =='room')
	{
		var roomnumber=document.getElementById('roomnumber').value;
		
		if( roomnumber =='')
		{			
			document.getElementById('lit').style.display='none';
			document.getElementById('savehospbtn').style.display='none';
		}else{
			document.getElementById('lit').style.display='inline-block';
			document.getElementById('savehospbtn').style.display='table';
		}
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


function ShowRoom(showroom){
	
	var elem = document.getElementsByClassName("numroom");
	var names = [];
	for (var i = 0; i < elem.length; ++i) {
	
		if (typeof elem[i].value != "") {
			names.push(elem[i].value);
		}
	}
	
	var webcamval = names;
	
	alert("Liste des chambres pleines :\n" + webcamval);
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



	/* for($i=0;$i<sizeof($add);$i++)
	{ */


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

<div> <!-- footer -->
	<?php
		include('footer.php');
	?>
</div> <!-- /footer -->

</body>

</html>