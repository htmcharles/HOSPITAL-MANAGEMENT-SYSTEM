<?php
session_start();

include("connectLangues.php");
include("connect.php");
	$annee = date('Y').'-'.date('m').'-'.date('d');

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title><?php echo getString(163);?></title>
	
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
	
</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlAcc=$connexion->query("SELECT *FROM accountants acc WHERE acc.id_u='$id'");


$comptidAcc=$sqlAcc->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidAcc!=0)
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
					<form method="post" action="billsaccount_hosp.php?<?php if(isset($_GET['codeAcc'])){ echo 'codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
						<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
						
						<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
						
						<?php
						if($langue == 'francais')
						{
						?>
							<a href="billsaccount_hosp.php?english=english<?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?>" class="btn"><?php echo getString(30);?></a>
						<?php
						}else{
						?>
							<a href="billsaccount_hosp.php?francais=francais<?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?>" class="btn"><?php echo getString(29);?></a>
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

<div style="text-align:center;margin-top:20px;">
	<a href="billsaccount.php?codeAcc=<?php echo $_SESSION['codeAcc'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
		<?php echo 'Factures';?>
	</a>
	<a href="report.php?codeAcc=<?php echo $_SESSION['codeAcc'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
		<?php echo 'Report';?>
	</a>
</div>

<div class="account-container" style="width:95%;margin:auto; text-align:center;">

<div id='cssmenu' style="text-align:center">

<ul>
	<li style="width:50%;"><a href="prestations.php<?php if(isset($_GET['english'])){ echo '?english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '?francais='.$_GET['francais'];}}?>" style="margin-left:5px;" data-title="Show/Add Prestations"><i class="fa fa-plus-circle fa-1x fa-fw"></i> Show/Add Prestations</a></li>

	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-1x fa-fw"></i> <?php echo getString(49);?></a></li>
	
</ul>


<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">
	
		<div style="display:none;" id="divMenuMsg">

			<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
			
			<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
			
			<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

		</div>

</ul>	
</div>

<table>
	<tr>
		<td style="vertical-align: top;">

			<div>
			
			<b><h3><?php echo getString(164)." Hosp";?></h3></b>
			
			<br/>
			
			<form class="ajax" action="search.php" method="get">
				<p>
					<label for="q"><?php echo getString(165);?></label>
					<input type="text" name="q" id="q"/>
				</p>
			</form>

			<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

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
				url : 'search_billsaccount_hosp1.php' , // url du fichier de traitement
				data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
				beforeSend : function() { // traitements JS à faire AVANT l'envoi
					$field.after('<img src="images/loader4.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
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
				<?php 
				if(isset($_GET['divAcc']))
				{
					
					$resultatsBills=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE p.id_factureHosp=:idBill');
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
								<th style="width:12%"><?php echo getString(71)." Sortie";?></th>
								<th style="width:8%"><?php echo getString(166);?></th>
								<th style="width:20%"><?php echo getString(89);?></th>
								<th style="width:10%"><?php echo getString(76);?></th>
								<th>Actions</th>
							</tr> 
						</thead> 
						
						<tbody>
						<?php
						
						while($ligneBill=$resultatsBills->fetch())//on recupere la liste des éléments
						{
						?>
							<tr style="text-align:center;<?php if($ligneBill->status==0){?>background:rgba(255,255,0, 0.3)<?php ;}?>">
								<td><?php echo $ligneBill->datebill;?></td>
								<td>
									<a href="printBillAccountHosp.php?num=<?php echo $ligneBill->numero;?>&id_factureHosp=<?php echo $ligneBill->id_factureHosp;?>&cashier=<?php echo $ligneBill->codecashierHosp;?>&dateSortie=<?php echo $ligneBill->dateSortie;?>&id_hosp=<?php echo $ligneBill->id_hosp;?>"><?php echo $ligneBill->numbill;?></a>
								</td>
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
										
										echo '<td>'.$fullname.'</td>';
									}else{
										echo '<td></td>';
									}
								?>
								
								<td><?php echo $ligneBill->nomassuranceHosp.' '.$ligneBill->insupercent_hosp.'%';?></td>
								
								<?php
								if($ligneBill->status==1)
								{
								?>
								<td>
									<a href="traitement_billsaccount_hosp.php?idbillDesactif=<?php echo $ligneBill->id_hosp?>&code=<?php echo $ligneBill->codeaccount;?>&divAcc=ok<?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>"><?php echo getString(167);?><input type="image" style="border-radius:100px;" src="images/onBlue.png" name="desactifbtn"/></a>
								
								</td>
								<?php
								}else{
									if($ligneBill->status==0)
									{
								?>
									<td>
										<a href="traitement_billsaccount_hosp.php?idbillActif=<?php echo $ligneBill->id_hosp?>&code=<?php echo $ligneBill->codeaccount;?>&divAcc=ok<?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>"><?php echo getString(168);?><input type="image" style="border-radius:100px;" src="images/offBlue.png" name="actifbtn"/></a>
									
									</td>
								<?php
									}
								}
								?>
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
					
					<a href="billsaccount_hosp.php?page=1&iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Bills" class="btn-large"><?php echo getString(170);?></a>
				<?php
				}
				?>

				<?php 
				if(!isset($_GET['divAcc']))
				{
					/*-----------Requête pour comptable-----------*/
						
					$resultats=$connexion->query("SELECT *FROM patients_hosp ph WHERE ph.id_factureHosp IS NOT NULL AND ph.dateSortie!='0000-00-00' ORDER BY ph.id_factureHosp DESC") or die( print_r($connexion->errorInfo()));

					$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
					
					$comptBill=$resultats->rowCount();
					
				?>
				<div style="overflow:auto;height:1000px;background-color:none;">
				<?php
				if($comptBill!=0)
				{
				?>
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr>
							<th style="width:12%"><?php echo getString(71).' Sortie';?></th>
							<th style="width:8%"><?php echo getString(166);?></th>
							<th style="width:20%"><?php echo getString(89);?></th>
							<th style="width:10%"><?php echo getString(147);?></th>
							<!-- <th>Actions</th> -->
							
						</tr> 
					</thead>
					
					<tbody>
					<?php
					try
					{
						while($ligne=$resultats->fetch())//on recupere la liste des éléments
						{
							/*$TotalGnlPatientPrice=($ligne->totalgnlprice * $ligne->billpercent)/100;
							
							$TotalGnlInsurancePrice= $ligne->totalgnlprice - $TotalGnlPatientPrice;*/
					?>
							<tr style="text-align:center;">
								<td><?php echo $ligne->dateSortie;?></td>
								
								<td>
									<a href="printBillAccountHosp.php?num=<?php echo $ligne->numero;?>&numbill=<?php echo $ligne->id_factureHosp;?>&cashier=<?php echo $ligne->codecashierHosp;?>&datefacture=<?php echo $ligne->dateSortie;?>&id_hosp=<?php echo $ligne->id_hosp;?>"><?php echo $ligne->id_factureHosp;?></a>
								</td>
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
										
										echo '<td>'.$fullname.' ('.$snPa.')</td>';
									}else{
										echo '<td></td>';
									}
									
								?>
								
								<td>
								<?php
									if($ligne->nomassuranceHosp !="")
										echo $ligne->nomassuranceHosp.' '.$ligne->insupercent_hosp.'%';
								?>
								</td>								
								<!-- <?php
								if($ligne->statusBill==1)
								{
								?>
								<td>
									<a href="traitement_billsaccount_hosp.php?idbillDesactif=<?php echo $ligne->id_hosp?>&code=<?php echo $ligne->codeaccount;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>"><?php echo getString(167);?><input type="image" style="border-radius:100px;" src="images/onBlue.png" name="desactifbtn"/></a>
								
								</td>
								<?php
								}else{
									if($ligne->statusBill==0)
									{
								?>
									<td>
										<a href="traitement_billsaccount_hosp.php?idbillActif=<?php echo $ligne->id_hosp?>&code=<?php echo $ligne->codeaccount;?><?php if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}else{ echo '&page=1';}?>"><?php echo getString(168);?><input type="image" style="border-radius:100px;" src="images/offBlue.png" name="actifbtn"/></a>
									</td>
								<?php
									}
								}
								?> -->
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
				
		</td>
	
	</tr>
</table>
	
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


</script>

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