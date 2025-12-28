<?php
session_start();

include("connectLangues.php");
include("connect.php");

	$annee = date('Y').'-'.date('m').'-'.date('d');


if(isset($_GET['num']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
	$result->execute(array(
	'operation'=>$_GET['num']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
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
		
		
		$datetime1 = new DateTime(date('Y-m-d H:i:s', strtotime($ligne->date_naissance)));
		$datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($annee)));
		$interval = $datetime1->diff($datetime2);
		
		if($interval->format('%y')!=0 OR $interval->format('%m')!=0 OR $interval->format('%d')!=0)
		{
			$an = $interval->format('%y '.getString(224).', %m '.getString(228).', %d '.getString(229).'');
		}
	
	}
	$result->closeCursor();
	
		
	$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
	
	$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
			
	$assuCount = $comptAssuConsu->rowCount();
	
	for($a=1;$a<=$assuCount;$a++)
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


}

if(isset($_POST['dettebtn']))
{
	$detteIdAfter=array();
	
	$prixDette=array();
	$idDette=array();
	
	foreach($_POST['prixDette'] as $valDette)
	{
		$prixDette[] = $valDette;
	}
	foreach($_POST['idDette'] as $valIdDette)
	{
		$idDette[] = $valIdDette;
	}

	$dettePaid=$_POST['dettepaid'];
		
	for($d=0;$d<sizeof($idDette);$d++)
	{
		$getUnderPaid=$connexion->prepare('SELECT *FROM bills b WHERE (b.dette<:dettePaid OR b.dette=:dettePaid) AND b.id_bill=:idbill ORDER BY b.id_bill');		
		$getUnderPaid->execute(array(
		'dettePaid'=>$dettePaid,
		'idbill'=>$idDette[$d]
		));
		
		$getUnderPaid->setFetchMode(PDO::FETCH_OBJ);
				
		$underPaidCount = $getUnderPaid->rowCount();
		
		if($underPaidCount!=0)
		{
			if($ligneUnderPaid=$getUnderPaid->fetch())
			{
				/* 
				echo 'SELECT *FROM bills b WHERE (b.dette<'.$dettePaid.' OR b.dette='.$dettePaid.') AND b.id_bill='.$idDette[$d].' ORDER BY b.id_bill<br/>';
				echo 'Paid ='.$dettePaid.'<br/>';
				
				echo 'if('.$ligneUnderPaid->dette.'<'.$dettePaid.')<br/>';
				echo $dettePaid.' - '.$ligneUnderPaid->dette.'<br/>';
				 
				*/
				 
				$amountPaid=$ligneUnderPaid->dette + $ligneUnderPaid->amountpaid;
				
				$dettePaid=$dettePaid - $ligneUnderPaid->dette;
				
			/* 
				echo 'Dette_dbase =0<br/><br/>';
				echo 'Reste ='.$dettePaid.'<br/><br/>';
				
				echo 'UPDATE bills b SET b.dette=NULL, b.amountpaid='.$amountPaid.', b.detteDone=1, b.detteIdOut='.$_SESSION['id'].', b.dateDetteOut='.$annee.' WHERE b.id_bill='.$idDette[$d].' <br/><br/>';
			 
			 */
				
				$updateIdBill=$connexion->prepare('UPDATE bills b SET b.dette=NULL, b.amountpaid=:dettePaid, b.detteDone=1, b.detteIdOut=:idOut, b.dateDetteOut=:dateDetteOut WHERE b.id_bill=:idbill');

				$updateIdBill->execute(array(
				'dettePaid'=>$amountPaid,
				'idbill'=>$idDette[$d],
				'idOut'=>$_SESSION['id'],
				'dateDetteOut'=>$annee
				
				))or die( print_r($connexion->errorInfo()));
				
			}

		}else{
			$detteIdAfter[]=$idDette[$d];
		}
	}
				
	if($dettePaid!=0)
	{	
		if(isset($detteIdAfter[0])!="")
		{
			// echo $detteIdAfter[0].'<br/>';
			
			$getUpperPaid=$connexion->prepare('SELECT *FROM bills b WHERE b.numero=:num AND (b.dette>:dettePaid OR b.dette=:dettePaid) AND b.detteDone IS NULL AND b.id_bill='.$detteIdAfter[0].' ORDER BY b.id_bill');		
			$getUpperPaid->execute(array(
			'num'=>$_GET['num'],
			'dettePaid'=>$dettePaid
			));
			
			$getUpperPaid->setFetchMode(PDO::FETCH_OBJ);
					
			$upperPaidCount = $getUpperPaid->rowCount();
			
			if($upperPaidCount!=0)
			{			
				if($ligneUpperPaid=$getUpperPaid->fetch())
				{
					/* 
					echo 'SELECT *FROM bills b WHERE b.numero='.$_GET['num'].' AND (b.dette>'.$dettePaid.' OR b.dette='.$dettePaid.') AND b.detteDone IS NULL AND b.id_bill='.$detteIdAfter[0].' ORDER BY b.id_bill<br/>';
					echo 'Paid ='.$dettePaid.'<br/>';
					
					echo 'if('.$ligneUpperPaid->dette.'>'.$dettePaid.')<br/>';
					echo $ligneUpperPaid->dette.' - '.$dettePaid.'<br/>';
					
					*/
					 
					$amountpaid=$dettePaid + $ligneUpperPaid->amountpaid;
					 
					$dettePaid=$ligneUpperPaid->dette - $dettePaid;
					
					if($dettePaid==0)
					{
						$done=1;
						$amountpaid=$ligneUpperPaid->dette + $ligneUpperPaid->amountpaid;
					}else{
						$done=NULL;
					}
					
				/* 
					echo 'Amount paid ='.$amountpaid.'<br/>';
					echo 'Reste Dette_dbase ='.$dettePaid.'<br/><br/>';
				
					echo 'UPDATE bills b SET b.dette='.$dettePaid.',b.amountpaid='.$amountpaid.', b.detteDone='.$done.', b.detteIdOut='.$_SESSION['id'].', b.dateDetteOut='.$annee.' WHERE b.idbill='.$detteIdAfter[0].' <br/><br/>';
				*/	
					
					$updateIdBill=$connexion->prepare('UPDATE bills b SET b.dette=:dette, b.amountpaid=:amountpaid, b.detteDone=:done, b.detteIdOut=:idOut, b.dateDetteOut=:dateDetteOut WHERE b.id_bill=:idbill');

					$updateIdBill->execute(array(
					'dette'=>$dettePaid,
					'amountpaid'=>$amountpaid,
					'done'=>$done,
					'idOut'=>$_SESSION['id'],
					'dateDetteOut'=>$annee,
					'idbill'=>$detteIdAfter[0]
					
					))or die( print_r($connexion->errorInfo()));
					
				}
			}
		}
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
	<meta charset="utf-8">
	<title><?php echo 'DETTES';?></title>
	
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
	
	
	<script type="text/javascript">

function controlFormDette(theForm){
	var rapport="";
	
	rapport +=controlDettepaid(theForm.dettepaid,theForm.totaldette);
	
		if (rapport != "") {
			alert("Please correct the following errors:\n" + rapport);
					return false;
		}
 }


function controlDettepaid(fld1,fld2){
	var erreur="";
	
	if(parseFloat(fld1.value)>parseFloat(fld2.value) || fld1.value <0){
		
		erreur="Amount Paid "+fld1.value+" must be a positive number, equal or under Total Debts "+fld2.value+"\n";
		
		fld1.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;
}
	</script>
	
</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlCash=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");
$sqlManager=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");


$comptidCash=$sqlCash->rowCount();
$comptidManager=$sqlManager->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND ($comptidCash!=0 OR $comptidManager!=0))
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
					<form method="post" action="dettesList.php?<?php if(isset($_GET['codeCash'])){ echo '&codeCash='.$_GET['codeCash'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
						<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
						
						<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
						
						<?php
						if($langue == 'francais')
						{
						?>
							<a href="dettesList.php?english=english<?php if(isset($_GET['codeCash'])){ echo '&codeAcc='.$_GET['codeCash'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?>" class="btn"><?php echo getString(30);?></a>
						<?php
						}else{
						?>
							<a href="dettesList.php?francais=francais<?php if(isset($_GET['codeCash'])){ echo '&codeAcc='.$_GET['codeCash'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['divAcc'])){ echo '&divAcc='.$_GET['divAcc'];}?><?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?>" class="btn"><?php echo getString(29);?></a>
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

		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Factures';?>
		</a>
	
	</div>
	
<?php
}
?>

<?php
if(isset($_SESSION['codeR']) AND isset($_SESSION['codeCash']))
{
?>
	<div style="text-align:center;margin:20px;">
		
	<?php
	if(isset($_GET['receptioniste']))
	{
	?>
		<a href="patients1.php?caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Caisse';?>
		</a>

	<?php
	}else{
	?>	
		<a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="patientsClinbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reception';?>
		</a>
	
		<a href="patients1_hosp.php?cashHosp=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="patientsHospbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Hospitalisation';?>
		</a>
	
	<?php
	}
	?>
		
		<a href="listfacture.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Factures';?>
		</a>
		
		<a href="report.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;margin-left:10px;"><?php echo getString(94);?></a>
	</div>
<?php
}
?>

<div class="account-container" style="width:95%;margin:auto; text-align:center;">

<div id='cssmenu' style="text-align:center">

	<ul>
	<?php
	if($comptidManager!=0)
	{
	?>
		<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-lg fa-fw"></i> <?php echo getString(48);?></a></li>
	<?php
	}else{
	?>	
		<li style="width:50%;"><a href="patients1.php?caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="<?php echo 'Patients';?>"><i class="fa fa-wheelchair fa-1x fa-fw"></i><?php echo 'Patients';?></a></li>
	<?php
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
		
	</ul>


<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">
	
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
</div>

	<div>
			
		<b><h3><?php echo 'Dettes';?></h3></b>
		
		<form class="ajax" action="search.php" method="get">
			<p>
				<table align="center">
					<tr>
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
						
						<td>											
						<?php
						if(isset($_GET['divDette']))
						{
						?>
						<a href="dettesList.php?<?php if(isset($_SESSION['codeCash'])){ echo 'cashier='.$_SESSION['codeCash'];}?><?php if(isset($_SESSION['codeC'])){ echo 'coordi='.$_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn">
							<?php echo 'Liste de toutes les dettes';?>
						</a>
						<?php
						}
						?>
						</td>
					</tr>
				</table>				
			</p>
		</form>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsRI"></div>

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
	url : 'traitement_patients1.php?name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
	url : 'traitement_patients1.php?sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
	url : 'traitement_patients1.php?ri=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
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
	if(isset($_GET['divDette']))
	{
	?>
		<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto; padding: 10px; width:80%;">
			<tr>
				<td style="font-size:18px; text-align:center; width:33.333%;">
					<span style="font-weight:bold;"><?php echo getString(89) ?> : </span><?php echo $nom_uti.' '.$prenom_uti;?>
					
				</td>
				
				<td style="font-size:18px; text-align:center; width:33.333%;">
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
				
				<td style="font-size:18px; text-align:center; width:33.333%;">
				
					<span style="font-weight:bold;">Age : </span><?php echo $an;?>
				</td>
			</tr>
		</table>

		<?php
		
		$getIdDebt=$connexion->prepare('SELECT * FROM bills b WHERE b.numero=:numero AND b.detteDone IS NULL AND b.dette IS NOT NULL');
		$getIdDebt->execute(array(
		'numero'=>$_GET['num']
		)); 
		
		$getIdDebt->setFetchMode(PDO::FETCH_OBJ);
				
		$idDebtCount = $getIdDebt->rowCount();

		// echo $idDebtCount;
		
		$getIdDebtDone=$connexion->prepare('SELECT * FROM bills b WHERE b.numero=:numero AND b.amountpaid IS NOT NULL');
		$getIdDebtDone->execute(array(
		'numero'=>$_GET['num']
		)); 
		
		$getIdDebtDone->setFetchMode(PDO::FETCH_OBJ);
				
		$idDebtDoneCount = $getIdDebtDone->rowCount();

		// echo $idDebtDoneCount;
		if($idDebtCount!=0)
		{
		?>
		
		<table class="tablesorter" cellspacing="0">
			<tr>
				<td style="vertical-align:top;">
				
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr>
							<th><?php echo getString(71);?></th>
							<th><?php echo getString(166);?></th>
							<th><?php echo 'Debts';?></th>
							<th><?php echo 'Put by';?></th>
						</tr> 
					</thead>
					
					<tbody>
					
						<form method="post" action="dettesList.php?<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}?>&divDette=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormDette(this)">	
						<?php
						try
						{
							$totalDettes=NULL;
							
							while($ligneAllDebt=$getIdDebt->fetch())
							{
								$detteRestante=$ligneAllDebt->dette;
						?>
								<tr style="text-align:center;">
									<td><?php echo date('d-M-Y', strtotime($ligneAllDebt->datebill));?></td>
									<td>
									<?php
									$getBillConsu=$connexion->prepare("SELECT *FROM consultations c WHERE c.id_factureConsult=:id_bill") or die( print_r($connexion->errorInfo()));
									$getBillConsu->execute(array(
									'id_bill'=>$ligneAllDebt->id_bill
									))or die( print_r($connexion->errorInfo()));
									
									$getBillConsu->setFetchMode(PDO::FETCH_OBJ);
								
									if($ligneBillConsu=$getBillConsu->fetch())
									{
										if(isset($_SESSION['codeCash']))
										{
										?>
											<a href="bills.php?num=<?php echo $ligneAllDebt->numero;?>&idbill=<?php echo $ligneAllDebt->id_bill;?>&idconsu=<?php echo $ligneBillConsu->id_consu;?>&idmed=<?php echo $ligneBillConsu->id_uM;?>&dateconsu=<?php echo $ligneBillConsu->dateconsu;?>&idtypeconsu=<?php echo $ligneBillConsu->id_typeconsult;?>&idassu=<?php echo $ligneBillConsu->id_assuConsu;?><?php if(isset($_SESSION['codeCash'])){ echo '&cashier='.$_SESSION['codeCash'];}?><?php if(isset($_SESSION['codeC'])){ echo '&cashier='.$_SESSION['codeC'];}?>&datefacture=<?php echo $ligneAllDebt->datebill;?>&idbill=<?php echo $ligneAllDebt->id_bill;?>&dettelist=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneAllDebt->numbill;?></a>
										<?php
										}elseif(isset($_SESSION['codeC'])){
										?>
											<a href="reprintbill.php?num=<?php echo $ligneAllDebt->numero;?>&idbill=<?php echo $ligneAllDebt->id_bill;?>&idconsu=<?php echo $ligneBillConsu->id_consu;?>&idmed=<?php echo $ligneBillConsu->id_uM;?>&dateconsu=<?php echo $ligneBillConsu->dateconsu;?>&idtypeconsu=<?php echo $ligneBillConsu->id_typeconsult;?>&idassu=<?php echo $ligneBillConsu->id_assuConsu;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligneAllDebt->datebill;?>&idbill=<?php echo $ligneAllDebt->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneAllDebt->numbill;?></a>
									<?php
										}
									}
									?>
										
										<input type="hidden" name="idDette[]" id="idDette" value="<?php echo $ligneAllDebt->id_bill; ?>" style="margin-top:5px;margin-bottom:0;"/>
								
									</td>
									<td>
										<?php echo $detteRestante;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										<?php 
										if($ligneAllDebt->detteDone == 1)
										{
										?>
										<i class="fa fa-check fa-lg fa-fw"></i>
										<?php
										}
										?>
										<input type="hidden" name="prixDette[]" id="prixDette" value="<?php echo $detteRestante; ?>" style="margin-top:5px;margin-bottom:0;"/>
								
									</td>
									<td>
									<?php

									$resultDetteIdIn=$connexion->prepare('SELECT *FROM utilisateurs u WHERE u.id_u=:idUti') or die( print_r($connexion->errorInfo()));
									$resultDetteIdIn->execute(array(
									'idUti'=>$ligneAllDebt->detteIdIn
									));

									$resultDetteIdIn->setFetchMode(PDO::FETCH_OBJ);
										
									if($ligneDetteIdIn=$resultDetteIdIn->fetch())
									{
										echo $ligneDetteIdIn->full_name;
									}
									?>
									</td>
								</tr> 
							<?php
								if($ligneAllDebt->detteDone != 1)
								{
									$totalDettes=$totalDettes + $detteRestante;
								}
							}
							?>
						
						<tr style="background:#f8f8f8;">
							<td colspan=2 style="text-align:right;font-weight:700;">Total debts</td>
							
							<td style="text-align:center;font-size:30px;font-weight:700;color:red;">							<?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
							</td>
							
							<td colspan=3 style="text-align:left;">
								<input type="hidden" name="totaldette" id="totaldette" value="<?php echo $totalDettes; ?>" style="margin-top:5px;margin-bottom:0;"/>
							</td>
						</tr>
						<?php
						}

						catch(Excepton $e)
						{
						echo 'Erreur:'.$e->getMessage().'<br/>';
						echo'Numero:'.$e->getCode();
						}
						?>	
						<tr style="background:#eee;">
							<td colspan=2 style="text-align:right;">
								Amount paid						
							</td>
							
							<td colspan=2 style="text-align:left;">						
								<input type="text" name="dettepaid" id="dettepaid" placeholder="<?php echo '...........';?>" style="margin:5px;width:100px;"/>
								
								<button type="submit" name="dettebtn" id="dettebtn" class="btn-large"><?php echo getString(27);?></button>		
													
							</td>
						</tr>
						
						</form>		
					</tbody> 
					
				</table>
				
				</td>
				
				<td style="vertical-align:top;">
				<?php
				if($idDebtDoneCount!=0)
				{
				?>

				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr>
							<!--<th><?php echo getString(71);?></th>-->
							<th><?php echo getString(166);?></th>
							<th><?php echo 'Total/Debt';?></th>
							<th><?php echo 'Amount paid';?></th>
							<th><?php echo 'Confirmed by';?></th>
							<th><?php echo 'Deletion date';?></th>
						</tr> 
					</thead>
					
					<tbody>
						<?php
						try
						{
							$totalDettesDone=NULL;
							
							while($ligneAllDebtDone=$getIdDebtDone->fetch())
							{
								$detteTotal=$ligneAllDebtDone->dette + $ligneAllDebtDone->amountpaid;
						?>
								<tr style="text-align:center;">
									<!--<td><?php echo $ligneAllDebtDone->datebill;?></td>-->
									<td>
									<?php
									$getBillConsu=$connexion->prepare("SELECT *FROM consultations c WHERE c.id_factureConsult=:id_bill") or die( print_r($connexion->errorInfo()));
									$getBillConsu->execute(array(
									'id_bill'=>$ligneAllDebtDone->id_bill
									))or die( print_r($connexion->errorInfo()));
									
									$getBillConsu->setFetchMode(PDO::FETCH_OBJ);
								
									if($ligneBillConsu=$getBillConsu->fetch())
									{
										if(isset($_SESSION['codeCash']))
										{
										?>
											<a href="bills.php?num=<?php echo $ligneAllDebtDone->numero;?>&idbill=<?php echo $ligneAllDebtDone->id_bill;?>&idconsu=<?php echo $ligneBillConsu->id_consu;?>&idmed=<?php echo $ligneBillConsu->id_uM;?>&dateconsu=<?php echo $ligneBillConsu->dateconsu;?>&idtypeconsu=<?php echo $ligneBillConsu->id_typeconsult;?>&idassu=<?php echo $ligneBillConsu->id_assuConsu;?><?php if(isset($_SESSION['codeCash'])){ echo '&cashier='.$_SESSION['codeCash'];}?><?php if(isset($_SESSION['codeC'])){ echo '&cashier='.$_SESSION['codeC'];}?>&datefacture=<?php echo $ligneAllDebtDone->datebill;?>&idbill=<?php echo $ligneAllDebtDone->id_bill;?>&dettelist=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneAllDebtDone->numbill;?></a>
										<?php
										}elseif(isset($_SESSION['codeC'])){
										?>
											<a href="reprintbill.php?num=<?php echo $ligneAllDebtDone->numero;?>&idbill=<?php echo $ligneAllDebtDone->id_bill;?>&idconsu=<?php echo $ligneBillConsu->id_consu;?>&idmed=<?php echo $ligneBillConsu->id_uM;?>&dateconsu=<?php echo $ligneBillConsu->dateconsu;?>&idtypeconsu=<?php echo $ligneBillConsu->id_typeconsult;?>&idassu=<?php echo $ligneBillConsu->id_assuConsu;?>&manager=<?php echo $_SESSION['codeC'];?>&datefacture=<?php echo $ligneAllDebtDone->datebill;?>&idbill=<?php echo $ligneAllDebtDone->id_bill;?>&dettelist=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneAllDebtDone->numbill;?></a>
									<?php
										}
									}
									?>
										
										<input type="hidden" name="idDette[]" id="idDette" value="<?php echo $ligneAllDebtDone->id_bill; ?>" style="margin-top:5px;margin-bottom:0;"/>
								
									</td>
									<td><?php echo $detteTotal;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
										<?php 
										if($ligneAllDebtDone->detteDone == 1)
										{
										?>
										<i class="fa fa-check fa-lg fa-fw"></i>
										<?php
										}
										?>
										
									</td>
									<td>
										<?php echo $ligneAllDebtDone->amountpaid;?><span style="font-size:70%; font-weight:normal;">Rwf</span>
									</td>
									<td>
									<?php

									$resultDetteIdIn=$connexion->prepare('SELECT *FROM utilisateurs u WHERE u.id_u=:idUti') or die( print_r($connexion->errorInfo()));
									$resultDetteIdIn->execute(array(
									'idUti'=>$ligneAllDebtDone->detteIdOut
									));

									$resultDetteIdIn->setFetchMode(PDO::FETCH_OBJ);
										
									if($ligneDetteIdIn=$resultDetteIdIn->fetch())
									{
										echo $ligneDetteIdIn->full_name;
									}else{
									
										if($ligneAllDebtDone->codecashier!="")
										{
											$idDoneby=$ligneAllDebtDone->codecashier;
														
											$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, cashiers c WHERE u.id_u=c.id_u AND c.codecashier=:operation');
											$resultatsDoneby->execute(array(
											'operation'=>$idDoneby	
											));

											$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
											if($ligneDoneby=$resultatsDoneby->fetch())
											{
												$doneby = $ligneDoneby->full_name;
											}	
									
										}elseif($ligneAllDebtDone->codecoordi!=""){
											
											$idDoneby=$ligneAllDebtDone->codecoordi;
												
											$resultatsDoneby=$connexion->prepare('SELECT *FROM utilisateurs u, coordinateurs c WHERE u.id_u=c.id_u AND c.codecoordi=:operation');
											$resultatsDoneby->execute(array(
											'operation'=>$idDoneby	
											));

											$resultatsDoneby->setFetchMode(PDO::FETCH_OBJ);
											if($ligneDoneby=$resultatsDoneby->fetch())
											{
												$doneby = $ligneDoneby->full_name;
											}	
									
										}
		
										echo $doneby;
									}
									?>
									</td>
									<td>
									<?php
									if($ligneAllDebtDone->dateDetteOut!=NULL)
									{
										echo $ligneAllDebtDone->dateDetteOut;
									}else{
										echo $datebill = date('d-M-Y', strtotime($ligneAllDebtDone->datebill));
									}
									?>
									</td>
								</tr> 
							<?php
								$totalDettesDone=$totalDettesDone + $ligneAllDebtDone->amountpaid;
							}
							?>
						
						<tr style="background:#f8f8f8;">
							
							<td colspan=2 style="text-align:right;font-weight:700;">Total Paid</td>
							
							<td colspan=3 style="text-align:left;font-size:30px;font-weight:700;color:green;">
								<?php echo $totalDettesDone;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
							
								<input type="hidden" name="totaldette" id="totaldette" value="<?php echo $totalDettesDone; ?>" style="margin-top:5px;margin-bottom:0;"/>
							</td>
						</tr>
						<?php
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
				}
				?>	
				
				</td>		
			</tr> 
			
		</table>
		<?php
		}else{
		?>		
		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo 'No Debts';?></th>
				</tr> 
			</thead>
		</table>	
		<?php
		}
		
	}
	
	if(!isset($_GET['divDette']))
	{
	
		$getAllDebt=$connexion->query('SELECT * FROM bills b WHERE b.dette IS NOT NULL GROUP BY b.numero');
		
		$getAllDebt->setFetchMode(PDO::FETCH_OBJ);
				
		$allDebtCount = $getAllDebt->rowCount();

		// echo $allDebtCount;
		
		
		if($allDebtCount!=0)
		{
			$totalGnlDettes=NULL;
		?>

		<table class="tablesorter" cellspacing="0" style="width:50%"> 
			<thead>
				<tr>
					<th><?php echo 'Full name';?></th>
					<th><?php echo 'Debts';?></th>
					<!--<th><?php echo getString(166);?></th>-->
					<th><?php echo getString(71);?></th>
				</tr> 
			</thead>
			
			<tbody>
				<?php
				while($ligneAllDebt=$getAllDebt->fetch())
				{
				?>
					<tr style="text-align:center;">
						<td>
						<?php 
											
						$getPatient=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero=:num ORDER BY u.nom_u');
						$getPatient->execute(array(
						'num'=>$ligneAllDebt->numero
						));
						$getPatient->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet 
										
						$comptPatient=$getPatient->rowCount();
						
						
						if($lignePatient=$getPatient->fetch())
						{
							$fullname = $lignePatient->full_name;
							$numero = $lignePatient->numero;
						}
						$getPatient->closeCursor();
						
						echo $fullname;
						?>
						</td>
						<td>
							<?php 
							$getIdDebt=$connexion->prepare('SELECT * FROM bills b WHERE b.numero=:numero AND b.dette IS NOT NULL');
							$getIdDebt->execute(array(
							'numero'=>$ligneAllDebt->numero
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
								
							$totalGnlDettes=$totalGnlDettes+$totalDettes;
							?>
							<a class="btn" href="dettesList.php?num=<?php echo $ligneAllDebt->numero;?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>&divDette=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="color:red;"><?php echo $totalDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
							</a>
							
						</td>
						<!--
						<td>
						<?php
						
						$getBillConsu=$connexion->prepare("SELECT *FROM consultations c WHERE c.id_factureConsult=:id_bill") or die( print_r($connexion->errorInfo()));
						$getBillConsu->execute(array(
						'id_bill'=>$ligneAllDebt->id_bill
						))or die( print_r($connexion->errorInfo()));
						
						$getBillConsu->setFetchMode(PDO::FETCH_OBJ);
					
						if($ligneBillConsu=$getBillConsu->fetch())
						{				
						?>
							<a href="bills.php?num=<?php echo $ligneAllDebt->numero;?>&idbill=<?php echo $ligneAllDebt->id_bill;?>&idconsu=<?php echo $ligneBillConsu->id_consu;?>&idmed=<?php echo $ligneBillConsu->id_uM;?>&dateconsu=<?php echo $ligneBillConsu->dateconsu;?>&idtypeconsu=<?php echo $ligneBillConsu->id_typeconsult;?>&idassu=<?php echo $ligneBillConsu->id_assuConsu;?><?php if(isset($_SESSION['codeCash'])){ echo '&cashier='.$_SESSION['codeCash'];}?><?php if(isset($_SESSION['codeC'])){ echo '&cashier='.$_SESSION['codeC'];}?>&datefacture=<?php echo $ligneAllDebt->datebill;?>&idbill=<?php echo $ligneAllDebt->id_bill;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><?php echo $ligneAllDebt->numbill;?></a>
						<?php
						}
						?>
						</td>
						-->
						<td><?php echo $ligneAllDebt->datebill;?></td>
					</tr> 
				<?php
				}
				?>
				
					<tr style="background:#f8f8f8;">
						<td></td>
						<td colspan=3 style="text-align:left;font-size:30px;font-weight:700;color:red;"><span style="font-size:20px;color:black;">Total Dettes</span>
						
						
							<?php echo $totalGnlDettes;?><span style="font-size:70%; font-weight:normal;color:black;">Rwf</span>
						
						</td>
					</tr>
			</tbody> 
			
		</table>
		<?php
		}	
	}
	?>
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