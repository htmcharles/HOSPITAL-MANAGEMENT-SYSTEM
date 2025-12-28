<?php
session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


	$annee = date('Y').'-'.date('m').'-'.date('d');
	

if(!isset($_GET['doneBill']) AND isset($_GET['deletebill']))
{
	
	$idBill=$_GET['deletebill'];

	$deleteBilling=$connexion->prepare('DELETE FROM bills WHERE id_bill=:idBill');
		
	$deleteBilling->execute(array(
	'idBill'=>$idBill
	
	))or die($deleteBilling->errorInfo());

}


if(isset($_GET['finishbtn']))
{
	createBN();
	
	$idBilling=$_GET['idbill'];
	$idconsu=$_GET['idconsu'];
	
	
	/*----------Update Consult----------------*/
	
	$updateIdFactureConsult=$connexion->prepare('UPDATE consultations c SET c.id_factureConsult=:idbill, c.codecashier=:codecashier WHERE c.id_consu=:idconsu AND c.id_factureConsult IS NULL AND c.numero=:num');

	$updateIdFactureConsult->execute(array(
	'idbill'=>$idBilling,
	'num'=>$_GET['num'],
	'idconsu'=>$idconsu,
	'codecashier'=>$_SESSION['codeCash']
	
	))or die( print_r($connexion->errorInfo()));
	
	
}

?>

<!doctype html>
<html>
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
	<title><?php echo getString(2); ?></title>
	
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
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"/><!--Header-->

		<!-------------------calendrier------------------->
	
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="calender/design.css" />
	<script type="text/javascript" src="calender/calendrier.js"></script>	
	<link href="css/form-signin.css" rel="stylesheet" type="text/css">

			<!---------------Pagination--------------------->
			
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">		
	
	<script type="text/javascript">

function controlFormBilling(theForm){
	var rapport="";
	var nomconsu="";
	var prixconsu="";
	
	nomconsu +=theForm.newtypeconsult.value;
	prixconsu +=theForm.newprixtypeconsult.value;
	
	rapport +=controlNewPrixConsult(theForm.newprixtypeconsult);
	// rapport +=compareDateDebuFin(theForm.datedebut,theForm.datefin);
	// rapport +=compareHeures(theForm.heurerdvDebut,theForm.heurerdvFin,theForm.minrdvDebut,theForm.minrdvFin);
	
		if (rapport != "") {
			alert("Check error please :\n" + rapport);
			return false;
		}else{
			return(confirm(
			'Le nouveau prix de ' + nomconsu + ' est ' + prixconsu + 'frw \n Etes-vous sur de vouloir enregistrer?'));
		}
}

function controlNewPrixConsult(fld){
	var erreur="";
	//var illegalChar=/[\(\)\<\>\,\;\:\\\"\[\]\/\à\{\}]/;
	
	if(fld.value.trim()==""){
		erreur="Le nouveau prix";
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


<script type="text/javascript">
 
function controlFormFacture(theForm){
	var rapport="";
	 
	rapport +=controlMotif(theForm.motif);
	rapport +=controlEtatPa(theForm.etatpa);
	rapport +=controlAntec(theForm.antec);
	// rapport +=controlSoinsfait(theForm.soinsfait);

		if (rapport != "") {
		alert("Veuillez corrigez les erreurs suivantes:\n" + rapport);
					return false;
		 }
}

function controlMotif(fld){
	var erreur="";

	if(fld.value.trim()==""){
	erreur="Le Motif\n";
	fld.style.background="cyan";
	}	
	return erreur;
}

 function controlEtatPa(fld){
	var erreur="";

	if(fld.value==""){
	erreur="L'etat du patient\n";
	fld.style.background="cyan";
	}	
	return erreur;
}


</script>

	
</head>

<body>
<?php

$id=$_SESSION['id'];

if(isset($_SESSION['codeCash']))
{
	$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");

	$comptidC=$sqlC->rowCount();
}else{

	if(isset($_SESSION['codeR']))
	{
		$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$id'");

		$comptidC=$sqlR->rowCount();
	}
}

$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidC!=0 AND isset($_GET['num']))
{
	if($status==1)
	{

		if(isset($_GET['num']))
		{
			$resultats=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
			$resultats->execute(array(
			'operation'=>$_GET['num']	
			));
			
			$resultats->setFetchMode(PDO::FETCH_OBJ);
			while($ligne=$resultats->fetch())
			{
				$num=$ligne->numero;
				$nom_uti=$ligne->nom_u;
				$prenom_uti=$ligne->prenom_u;
				$sexe=$ligne->sexe;
				$dateN=$ligne->date_naissance;
				$province=$ligne->province;
				$district=$ligne->district;
				$secteur=$ligne->secteur;
				$phone=$ligne->telephone;
				$mail=$ligne->e_mail;
				$profession=$ligne->profession;
				$password=$ligne->password;
				$idassu=$ligne->id_assurance;
				$bill=$ligne->bill;
				$idP=$ligne->id_u;
			}
			$resultats->closeCursor();

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
			
			
			$resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:assuId');
			
			$resultAssurance->execute(array(
			'assuId'=>$idassu
			));
			
			$resultAssurance->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			if($ligneAssu=$resultAssurance->fetch())//on recupere la liste des éléments
			{
				$insurance=$ligneAssu->nomassurance;
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
					<form method="post" action="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&facture=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="billing.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="billing.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['cashier'])){ echo '&cashier='.$_GET['cashier'];}if(isset($_GET['idconsu'])){ echo '&idconsu='.$_GET['idconsu'];}if(isset($_GET['search'])){ echo '&search='.$_GET['search'];}if(isset($_GET['facture'])){ echo '&facture='.$_GET['facture'];}if(isset($_GET['datefacture'])){ echo '&datefacture='.$_GET['datefacture'];}?>" class="btn"><?php echo getString(29);?></a>
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
	if(isset($_GET['cashier']))
	{
?>
		<div style="text-align:center;margin-top:20px;">
			
			<a href="patients1.php?receptioniste=ok" class="btn-large" name="savebtn" style="font-size:20px;height:40px;padding:10px 40px">
				<?php echo 'Reception';?>
			</a>

		</div>
<?php
	}
}
?>

<div class="account-container" style="width:90%">

<?php

if($comptidC!=0)
{
?>
<div id='cssmenu' style="text-align:center">
<ul>
	<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Patients</a></li>
	
	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49); ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49); ?></a></li>
	
</ul>
	
	<div style="display:none;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57); ?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>
	
	
</div>
<?php
}
?>

	
	<?php
	if(isset($_GET['datefacture']) OR isset($_GET['facture']))
	{
	?>
		<form action="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php if(isset($_SESSION['codeCash'])) echo $_SESSION['codeCash'];else echo $_SESSION['codeR'];?>&datefacture=ok&search=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" method="post" enctype="multipart/form-data" style="margin:auto;padding:35px;width:90%;">
	  
	<?php
	}
	?>
		
		<?php
		if(isset($_GET['num']))
		{
		?>
			<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:80%;">
				<tr>
					<td style="font-size:18px; text-align:center; width:33.333%;">
						<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $nom_uti.' '.$prenom_uti;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:23.333%;">
						<span style="font-weight:bold;"><?php echo 'Insurance';?> : </span><?php echo $insurance;?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:23.333%;">
						<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
						<?php
						if($sexe=="M")
						{
							$sexe = "Male";
						}elseif($sexe=="F"){						
							$sexe = "Female";						
						}else{				
							$sexe="";
						}
						
						echo $sexe;
						?>
					</td>
					
					<td style="font-size:18px; text-align:center; width:23.333%;">
						<span style="font-weight:bold;">Age : </span><?php echo $an;?>
					</td>
				</tr>
			</table>

		<?php
		}
		?>

	
	</form>
	
	<?php
	if(isset($_GET['datefacture']))
	{
	?>
		
		<div style="background:#fff;border:1px solid #eee;border-radius:3px;padding:20px;" class="step2"> 
		
		<table style="padding:20px;width:100%">	
			<tr>
				<td>
					<p class="patientId" style="text-align:center;color:#a00000;"><?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?></p>
								
				</td>
			</tr>

			<tr>	
				<?php
				
					$codecashier=$_GET['cashier'];
					$numPa=$_GET['num'];
					$consuId=$_GET['idconsu'];
					$datefacture=$_GET['datefacture'];

					
					$resultConsult=$connexion->prepare('SELECT *FROM consultations c WHERE c.id_consu=:consuId AND c.dateconsu=:datefacture AND c.numero=:num AND c.id_factureConsult IS NULL ORDER BY c.id_consu');		
					$resultConsult->execute(array(
					'consuId'=>$consuId,
					'num'=>$numPa,
					'datefacture'=>$datefacture	
					));
					
					$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptConsult=$resultConsult->rowCount();

				?>
				<td style="background:#f8f8f8; border:1px solid #eee; border-radius:4px; padding:5px;">
				<?php	
				if($comptConsult != 0)
				{
				?>
					<form method="post" action="printConsuBill.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&idconsu=<?php echo $_GET['idconsu'];?>&datefacture=<?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormBilling(this)" enctype="multipart/form-data">
					
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%;"> 
						<thead> 
							<tr>
								<th><?php echo getString(113);?></th>
								<th><?php echo getString(145);?></th>
								<th><?php echo getString(38);;?></th>
							</tr> 
						</thead> 


						<tbody>
					<?php
							while($ligneConsult=$resultConsult->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
								<?php											
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

						
								
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
									'prestaId'=>$ligneConsult->id_typeconsult
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($comptPresta!=0)
								{
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta;
								?>							
										</td>
										
										<td>
										
											<input type="text" id="prixpresta" name="prixpresta" style="width:80px;display:inline" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta;}else{ echo "";};?>" required />
											
											<input type="text" id="newprixpresta" name="newprixpresta" style="width:80px;display:none" value="0" required/>
										<br/>
											<!--
											
											<span class="btn" id="newprixprestabtn" name="newprixprestabtn" onclick="ShowNewprix('newprix')" style="display:inline"><?php echo getString(203) ?></span>
											-->

											<a class="btn" href="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&datefacture=<?php echo $_GET['datefacture'];?>&idconsu=<?php echo $_GET['idconsu'];?>"id="prixprestabtn" name="prixprestabtn" style="display:none"><?php echo getString(204) ?></a>

										</td>
								<?php
										}else{								
											echo $lignePresta->nompresta;
								?>							
										</td>
										
										<td>
										
											<input type="text" id="prixpresta" name="prixpresta" style="width:80px;display:inline" value="<?php if($lignePresta->prixpresta!=-1){ echo $lignePresta->prixpresta;}else{ echo "";}?>" required/>
											
											<input type="text" id="newprixpresta" name="newprixpresta" style="width:80px;display:none" value="0" required/>
										<br/>
										<!--
											<span class="btn" id="newprixprestabtn" name="newprixprestabtn" onclick="ShowNewprix('newprix')" style="display:inline">New price</span>
										
										-->

											<a class="btn" href="billing.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&datefacture=<?php echo $_GET['datefacture'];?>&idconsu=<?php echo $_GET['idconsu'];?>"id="prixprestabtn" name="prixprestabtn" style="display:none">Ignorer</a>

										</td>
								<?php
										
										}
									}
								}else{
							
									$nomconsu=$ligneConsult->autretypeconsult;
									if($ligneConsult->prixautretypeconsult!=0)
									{
										$prixautreconsu=$ligneConsult->prixautretypeconsult;
									}else{
										$prixautreconsu="";
									}
									echo '<input type="text" id="newtypeconsult" name="newtypeconsult" placeholder="Entrez le prix" style="width:100px;display:none;" value="'.$nomconsu.'"/>';
									
									echo $ligneConsult->autretypeconsult.'</td>
									<td>
										Nouveau prix<br/><input type="text" id="newprixtypeconsult" name="newprixtypeconsult" placeholder="Entrez le prix" style="width:100px" value="'.$prixautreconsu.'" required/>frw
									
									</td>
									';
								}
								?>
								<td>
									<input type="text" id="pourcentage" name="pourcentage" style="width:30px;" value="<?php echo $bill;?>" required /> %

								</td>
							</tr>
					<?php
							}
					?>		
						</tbody>
						</table>
					
						<p style="text-align: center;">
							<button style="width:300px; margin:10px auto auto;" type="submit" name="printbill" id="printbill" class="btn-large">
								<i class="fa fa-desktop fa-lg fa-fw"></i> <?php echo getString(148);?>
							</button>
						</p>
					</form>
					
				<?php
				}else{
				?>
					
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%;"> 
						<thead> 
							<tr style="background:#aaa">
								<th><?php echo "Ce patient n'a pas de nouvelle consultation à facturer pour aujourd'hui";?></th>
							</tr> 
						</thead>
						
						</table>
				<?php
				}
				?>
				</td>
					
			</tr>
						
		</table>
		
		</div>
		
		<div style="background:#fff;border:1px solid #eee;border-radius:3px;padding:20px;" class="step2"> 
		
		<table style="padding:20px;width:100%">	
			<tr>
				<td>
					<p class="patientId" style="text-align:center;color:#a00000;">Consultations facturées</p>
								
				</td>
			</tr>
			
			<tr>	
				<?php
				
					$codecashier=$_GET['cashier'];
					$numPa=$_GET['num'];
					$datefacture=$_GET['datefacture'];


					$resultConsult=$connexion->prepare('SELECT *FROM consultations c, bills b WHERE b.id_bill=c.id_factureConsult AND b.numero=c.numero AND b.dateconsu=c.dateconsu AND c.numero=:num AND c.id_factureConsult IS NOT NULL ORDER BY c.id_consu DESC');		
					$resultConsult->execute(array(
					'num'=>$numPa
					));
					
					$resultConsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptConsult=$resultConsult->rowCount();

				?>
				<td style="background:#f8f8f8; border:1px solid #eee; border-radius:4px; padding:5px;">
				<?php	
				if($comptConsult != 0)
				{
				?>
					<form method="post" action="printConsuBill.php?num=<?php echo $_GET['num'];?>&cashier=<?php echo $_GET['cashier'];?>&datefacture=<?php if(isset($_POST['datefacture'])){ echo $_POST['datefacture']; }else { if(isset($_GET['datefacture'])) echo $_GET['datefacture'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormBilling(this)" enctype="multipart/form-data">
					
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%;"> 
						<thead> 
							<tr>
								<th><?php echo getString(113);?></th>
								<th><?php echo getString(145);?></th>
								<th><?php echo 'Actions';?></th>
							</tr> 
						</thead> 


						<tbody>
					<?php
							while($ligneConsult=$resultConsult->fetch())
							{
					?>
							<tr style="text-align:center;">
								<td>
								<?php
								
								$idassu=$ligneConsult->id_assuConsu;								
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
		
								
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');		
								$resultPresta->execute(array(
								'prestaId'=>$ligneConsult->id_typeconsult
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($comptPresta!=0)
								{
									if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
									{
										if($lignePresta->namepresta!='')
										{
											echo $lignePresta->namepresta.'</td>
											<td>'.$ligneConsult->prixtypeconsult.'</td>';
										}else{								
											echo $lignePresta->nompresta.'</td>
											<td>'.$ligneConsult->prixtypeconsult.'</td>';
										}
									}
								}else{
							
									$nomconsu=$ligneConsult->autretypeconsult;	
									echo '<td>'.$ligneConsult->autretypeconsult.'</td>';
								}
								?>
								
								<td>
									<a class="btn" href="categoriesbill.php?cashier=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneConsult->numero;?>&idconsu=<?php echo $ligneConsult->id_consu;?>&idmed=<?php echo $ligneConsult->id_uM;?>&dateconsu=<?php echo $ligneConsult->dateconsu;?>&idtypeconsu=<?php echo $ligneConsult->id_typeconsult;?>&idassu=<?php echo $ligneConsult->id_assuConsu;?>&idbill=<?php echo $ligneConsult->id_factureConsult;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
										<i class="fa fa-plus fa-0.5x fa-fw"></i>
									</a>

								</td>
								
							</tr>
					<?php
							}
					?>		
						</tbody>
						</table>
					
					</form>
				<?php
				}else{
				?>
					
						<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff; width:70%;"> 
						<thead> 
							<tr>
								<th><?php echo "Ce patient n'a jamais été facturé";?></th>
							</tr> 
						</thead>
						
						</table>
				<?php
				}
				?>
				</td>
										
			</tr>
						
		</table>
		
		</div>
	<?php
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
		alert("Your Browser does not support   XMLHTTPRequest object...");
		return null;
	}

	return xhr;
	}

	
	function ShowNewprix(newprix)
	{
		if( newprix =='newprix')
		{
			document.getElementById('newprixpresta').style.display='inline';
			document.getElementById('prixprestabtn').style.display='inline';
			document.getElementById('prixpresta').style.display='none';
			document.getElementById('newprixprestabtn').style.display='none';
		
		}
		
	}
	
	function ShowList(list)
	{
		if( list =='Users')
		{
			document.getElementById('divMenuUser').style.display='inline';
			document.getElementById('divMenuMsg').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divMenuMsg').style.display='inline';
			document.getElementById('divMenuUser').style.display='none';
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
			document.getElementById('reception').style.display='inline';
			document.getElementById('EnvoiMsg').style.display='inline';
			document.getElementById('MsgEnvoye').style.display='inline';
			document.getElementById('MsgRecu').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
		if( list =='MsgEnvoye')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('MsgEnvoye').style.display='none';
			document.getElementById('EnvoiMsg').style.display='inline';
			document.getElementById('MsgRecu').style.display='inline';
			document.getElementById('envoye').style.display='inline';
		}
		
		if( list =='EnvoiMsg')
		{
			document.getElementById('formMsg').style.display='inline';
			document.getElementById('MsgEnvoye').style.display='inline';
			document.getElementById('MsgRecu').style.display='inline';
			document.getElementById('EnvoiMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
	}

	function controlFormPassword(theForm){
		var rapport="";
		
		rapport +=controlPass(theForm.Pass);

			if (rapport != "") {
			alert("Veuillez corrigez les erreurs suivantes:\n" + rapport);
						return false;
			 }
	}


	function controlPass(fld){
		var erreur="";
		
		if(fld.value=="")
		{
			erreur="Saisir nouveau mot de pass\n";
			fld.style.background="cyan";
		}
		
		return erreur;
	}


	</script>

</div>

<?php
	
	}else{
		echo '<script language="javascript"> alert("Vous avez été désactivé!!\n Demander à l\'administrateur de vous activer");</script>';
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

</body>
</html>