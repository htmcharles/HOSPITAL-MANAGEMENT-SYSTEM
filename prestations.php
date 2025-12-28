<?php
session_start();

include("connectLangues.php");
include("connect.php");

			/*-------------Add CategoPresta---------------*/
					
if(isset($_POST['categoprestabtn']))
{

	$nomcatego = $_POST['nomcatego'];
	$namecatego = $_POST['namecatego'];
	
	$createCategoPresta=$connexion->prepare('INSERT INTO categopresta (nomcategopresta,namecategopresta) VALUES(:nomcategopresta, :namecategopresta)');
	
	$createCategoPresta->execute(array(
	'nomcategopresta'=>$nomcatego,
	'namecategopresta'=>$namecatego
	))or die( print_r($connexion->errorInfo()));
	

	echo '<script type="text/javascript"> alert("Success!!!");</script>';
?>	
	<script type="text/javascript">
	document.location.href="prestations.php?categopresta=ok'<?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
	</script>

<?php
}


		/*----------------Edit Categopresta--------------*/

if(isset($_GET['editcatego']))
{

	$resultEditCatego=$connexion->prepare('SELECT *FROM categopresta c WHERE c.id_categopresta=:operation');
	$resultEditCatego->execute(array(
	'operation'=>$_GET['editcatego']	
	));
	$resultEditCatego->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($lignePresta=$resultEditCatego->fetch())
	{
		$nomcatego=$lignePresta->nomcategopresta;
		$namecatego=$lignePresta->namecategopresta;
		$modifierIdCatego=$_GET['editcatego'];
	}
	$resultEditCatego->closeCursor();
	
	
}

if(isset($_POST['editcategobtn']))
{
	
	$idcatego = $_GET['editcatego'];
	$nomcatego = $_POST['nomcatego'];
	$namecatego = $_POST['namecatego'];
	$iduti = $_SESSION['id'];

	$editCatego=$connexion->prepare('UPDATE categopresta SET nomcategopresta=:nom,namecategopresta=:name WHERE id_categopresta=:idcatego');
					
	$editCatego->execute(array(
	'nom'=>$nomcatego,
	'name'=>$namecatego,
	'idcatego'=>$idcatego
	))or die( print_r($connexion->errorInfo()));
			
	echo '<script type="text/javascript"> alert("Edit Categorie Successfully!!!");</script>';
	
?>	
	<script type="text/javascript">
	document.location.href="prestations.php?categopresta=ok'<?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
	</script>

<?php
}

		/*----------------Add Presta--------------*/

if(isset($_POST['prestabtn']))
{
	$categopresta = $_GET['viewcategopresta'];
	$nompresta = $_POST['nompresta'];
	$namepresta = $_POST['namepresta'];
	$prix = $_POST['prix'];
	$iduti = $_SESSION['id'];

	$createPresta=$connexion->prepare('INSERT INTO prestations (nompresta,namepresta,prixpresta,id_categopresta) VALUES(:nompresta, :namepresta, :prix, :categopresta)');
					
	$createPresta->execute(array(
	'nompresta'=>$nompresta,
	'namepresta'=>$namepresta,
	'prix'=>$prix,
	'categopresta'=>$categopresta
	))or die( print_r($connexion->errorInfo()));

		
?>	
	<script type="text/javascript"> 
		alert("Add Prestation Successfully!!!");
	</script>
	
	<script type="text/javascript">
	document.location.href="prestations.php?<?php if(isset($_GET['nomcat'])){ echo 'nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
	</script>

<?php
}

		/*----------------Edit Prestations--------------*/

if(isset($_GET['presta']))
{

	$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:operation');
	$resultPresta->execute(array(
	'operation'=>$_GET['presta']	
	));
	$resultPresta->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($lignePresta=$resultPresta->fetch())
	{
		$nompresta=$lignePresta->nompresta;
		$namepresta=$lignePresta->namepresta;
		$prixpresta=$lignePresta->prixpresta;
		$modifierIdPresta=$_GET['presta'];
	}
	$resultPresta->closeCursor();
	
	
}


if(isset($_POST['editprestabtn']))
{
	
		$idpresta = $_GET['editpresta'];
		$nompresta = $_POST['nompresta'];
		$namepresta = $_POST['namepresta'];
		$prix = $_POST['prix'];
		$iduti = $_SESSION['id'];

		$editPresta=$connexion->prepare('UPDATE prestations SET nompresta=:nom,namepresta=:name,prixpresta=:prix WHERE id_prestation=:idpresta');
						
		$editPresta->execute(array(
		'nom'=>$nompresta,
		'name'=>$namepresta,
		'prix'=>$prix,
		'idpresta'=>$idpresta
		))or die( print_r($connexion->errorInfo()));
			
	echo '<script type="text/javascript"> alert("Edit Successfully!!!");</script>';
?>	
	<script type="text/javascript">
	document.location.href="prestations.php?<?php if(isset($_GET['viewcategopresta'])){ echo 'viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
	</script>

<?php		
}

	
			/*----------Delete Prestations-------------------*/
	
	if(isset($_GET['deletepresta']))
	{	
		$idpresta=$_GET['deletepresta'];

		$deletePresta=$connexion->prepare('UPDATE prestations SET statupresta=1 WHERE id_prestation=:idpresta');
			
		$deletePresta->execute(array(
		'idpresta'=>$idpresta
		
		))or die($deletePresta->errorInfo());
?>	
		<script type="text/javascript"> 
			alert("Delete Prestation Successfully!!!");
		</script>
		
		<script type="text/javascript">
		document.location.href="prestations.php?<?php if(isset($_GET['viewcategopresta'])){ echo 'viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
		</script>

<?php
	
	}


		/*----------------Add Presta Consu--------------*/

if(isset($_POST['prestaconsubtn']))
{
	$categopresta = $_GET['consu'];
	$nompresta = $_POST['nompresta'];
	$namepresta = $_POST['namepresta'];
	$prix = $_POST['prix'];
	$iduti = $_SESSION['id'];

	$createPresta=$connexion->prepare('INSERT INTO prestations (nompresta,namepresta,prixpresta,id_categopresta) VALUES(:nompresta, :namepresta, :prix, :categopresta)');
					
	$createPresta->execute(array(
	'nompresta'=>$nompresta,
	'namepresta'=>$namepresta,
	'prix'=>$prix,
	'categopresta'=>$categopresta
	))or die( print_r($connexion->errorInfo()));

		
?>	
	<script type="text/javascript"> 
		alert("Add Prestation Successfully!!!");
	</script>
	
	<script type="text/javascript">
	document.location.href="prestations.php?<?php if(isset($_GET['typeconsu'])){ echo 'typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['consu'])){ echo '&consu='.$_GET['consu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
	</script>

<?php
}

		/*----------------Edit Prestations Consu--------------*/

if(isset($_GET['prestaconsu']))
{

	$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:operation');
	$resultPresta->execute(array(
	'operation'=>$_GET['prestaconsu']	
	));
	$resultPresta->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($lignePresta=$resultPresta->fetch())
	{
		$nompresta=$lignePresta->nompresta;
		$namepresta=$lignePresta->namepresta;
		$prixpresta=$lignePresta->prixpresta;
		$modifierIdPresta=$_GET['prestaconsu'];
	}
	$resultPresta->closeCursor();
	
	
}


if(isset($_POST['editprestaconsubtn']))
{
	
		$idpresta = $_GET['editprestaconsu'];
		$nompresta = $_POST['nompresta'];
		$namepresta = $_POST['namepresta'];
		$prix = $_POST['prix'];
		$iduti = $_SESSION['id'];

		$editPrestaConsu=$connexion->prepare('UPDATE prestations SET nompresta=:nom,namepresta=:name,prixpresta=:prix WHERE id_prestation=:idpresta');
						
		$editPrestaConsu->execute(array(
		'nom'=>$nompresta,
		'name'=>$namepresta,
		'prix'=>$prix,
		'idpresta'=>$idpresta
		))or die( print_r($connexion->errorInfo()));
			
	echo '<script type="text/javascript"> alert("Edit Successfully!!!");</script>';
?>	
	<script type="text/javascript">
	document.location.href="prestations.php?<?php if(isset($_GET['consu'])){ echo 'consu='.$_GET['consu'];}?><?php if(isset($_GET['typeconsu'])){ echo '&typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
	</script>

<?php		
}

?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<title>Prestations</title>
	
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

	function controlFormCategoPresta(theForm){
		var rapport="";
			

		rapport +=controlCatego(theForm.nomcatego,theForm.namecatego)

			if (rapport != "") {
			alert("Please correct the following errors:\n" + rapport);
						return false;
			 }
	}
	
	function controlFormPresta(theForm){
		var rapport="";
			
		rapport +=controlPresta(theForm.nompresta,theForm.namepresta);
		rapport +=controlPrix(theForm.prix);

			if (rapport != "") {
			alert("Please correct the following errors:\n" + rapport);
						return false;
			 }
	}


		function controlCatego(fld1,fld2){
		
			var erreur="";
			var nomcatego=fld1.value;
			var namecatego=fld2.value;
			
			if(nomcatego == "" && namecatego == "")
			{
				if(nomcatego == "")
				{
					fld1.style.background="rgba(0,255,0,0.3)";
				}
				
				if(namecatego == "")
				{
					fld2.style.background="rgba(0,255,0,0.3)";
				}

				erreur="The name of the categorie.\n";
			}
			
			return erreur;
		}
		
		
		function controlPresta(fld1,fld2){
			var erreur="";
			var nompresta=fld1.value;
			var namepresta=fld2.value;
			
			if(nompresta == "" && namepresta == "")
			{
				if(nompresta == "")
				{
					fld1.style.background="rgba(0,255,0,0.3)";
				}
				
				if(namepresta == "")
				{
					fld2.style.background="rgba(0,255,0,0.3)";			
				}

			erreur="The name of the prestation.\n";
			}
			
			return erreur;
		}

		function controlPrix(fld){
			var erreur="";
				
			if(fld.value.trim()==""){
			erreur="The price\n";
			fld.style.background="rgba(0,255,0,0.3)";
			}
			
			return erreur;
		}	

	</script>

</head>

<body>
<?php

$id=$_SESSION['id'];

$sqlA=$connexion->query("SELECT *FROM accountants a WHERE a.id_u='$id'");


$comptidA=$sqlA->rowCount();


$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true AND $comptidA!=0)
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
					<form method="post" action="prestations.php?<?php if(isset($_GET['codeAcc'])){ echo 'codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}if(isset($_GET['divPresta'])){ echo '&divPresta='.$_GET['divPresta'];}if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}if(isset($_GET['categopresta'])){ echo '&categopresta='.$_GET['categopresta'];}?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="prestations.php?english=english<?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}if(isset($_GET['divPresta'])){ echo '&divPresta='.$_GET['divPresta'];}if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}if(isset($_GET['categopresta'])){ echo '&categopresta='.$_GET['categopresta'];}?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="prestations.php?francais=francais<?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}if(isset($_GET['divPresta'])){ echo '&divPresta='.$_GET['divPresta'];}if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}if(isset($_GET['categopresta'])){ echo '&categopresta='.$_GET['categopresta'];}?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?>" class="btn"><?php echo getString(29);?></a>
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



<div class="account-container" style="width:90%; text-align:center;">

<?php

if($comptidA!=0)
{
?>
<div id='cssmenu' style="text-align:center" class="menu">

<ul>
	<li style="width:50%;"><a href="billsaccount.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Bills"><i class="fa fa-money fa-1x fa-fw"></i> Bills</a></li>

	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-right:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-1x fa-fw"></i> <?php echo getString(49);?></a></li>

</ul>

<ul style="margin-top:20px; background:none;border:none;">

</ul>
	
	<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>
	
</div>


<?php 
}
?>

		<table cellpadding=3 style="margin:auto auto 10px auto; padding: 10px; width:95%;">
			<tr>
				<td style="font-size:18px; text-align:center; width:33.333%;">
				
					<a href="prestations.php?acc=<?php echo $_SESSION['id'];?>&typeconsu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
					
						<button id="typeconsu" class="btn-large">Type de consultation</button>
						
					</a>
				
					<a href="prestations.php?acc=<?php echo $_SESSION['id'];?>&categopresta=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
					
					<button id="categopresta" class="btn-large">Categories prestations</button>						
					</a>
					
				</td>
			</tr>
		</table>
		
		<div id="divTypeConsu" style="<?php if(!isset($_GET['typeconsu'])){ echo "display:none";}else{ echo "display:inline";}?>;">
		
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:50%;margin-bottom:20px;" align="center">
			
				<tr>
					<td>
						<a href="prestations.php?acc=<?php echo $_SESSION['id'];?>&consu=20&typeconsu=Specialiste<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
				
							<button id="showspecialist" class="btn">Specialiste</button>
					</td>
					
					<td>
						<a href="prestations.php?acc=<?php echo $_SESSION['id'];?>&consu=1&typeconsu=Généraliste<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
				
							<button id="showgeneralist" class="btn">Généraliste</button>
							
						</a>
					</td>
				</tr>
					
			</table>
			
		</div>
		
		<?php 
		if(isset($_GET['consu']))
		{
		?>
		
			<div id="divPresta">
			
			<h2>Consultations <?php echo $_GET['typeconsu'];?></h2>
			
			<form class="ajax" action="search.php" method="get">
				<p>
					<table align="center">
						<tr>
							<td>
								<label for="q"><?php echo getString(80);?></label>
								<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
							</td>
							
						</tr>
					</table>
				</p>
			</form>

			<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

			<!--<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>-->

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
				url : 'traitement_prestations.php?nameconsu=ok&consu=<?php echo $_GET['consu'];?>&typeconsu=<?php echo $_GET['typeconsu'];?>' , // url du fichier de traitement
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

			if(!isset($_GET['prestaconsu']))
			{
			?>
				<form method="post" action="prestations.php?<?php if(isset($_GET['consu'])){ echo 'consu='.$_GET['consu'];}?><?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['typeconsu'])){ echo '&typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPresta(this)">
					
					
					<table align="center" id="newPrestaconsu">

					<tr>
						<td><label for="nompresta">Nom prestation</label>
						
							<input type="text" name="nompresta" id="nompresta" value="" />
					
						</td>

						<td><label for="namepresta">Name prestation</label>
						
							<input type="text" name="namepresta" id="namepresta" value="" />
					
						</td>

						<td><label for="prix">Prix</label>
						
							<input type="text" name="prix" id="prix" value="" />
					
						</td>
						
						<td>
							<button type="submit" class="btn-large" name="prestaconsubtn" style="font-size:20px;height:auto;margin-left:50px;margin-top:10px;width:100%;">
								<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(28);?>
							</button>
						</td>
					</tr>

					</table>
						
				</form>
			<?php
			}

			if(isset($_GET['prestaconsu']))
			{
			?>
				<form method="post" action="prestations.php?editprestaconsu=<?php echo $_GET['prestaconsu'];?><?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['consu'])){ echo '&consu='.$_GET['consu'];}?><?php if(isset($_GET['typeconsu'])){ echo '&typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormEditPresta(this)">
				
								<h2>Modification</h2>
				
					<table align="center" id="updatePrestaconsu">

					<tr>
						<td><label for="nompresta">Nom prestation</label>
						
							<input type="text" name="nompresta" id="nompresta" value="<?php if(isset($_GET['prestaconsu']) and $comptidA!=0){ echo $nompresta;}else{ echo '';}?>" />
					
						</td>

						<td><label for="namepresta">Name prestation</label>
						
							<input type="text" name="namepresta" id="namepresta" value="<?php if(isset ($_GET['prestaconsu']) and $comptidA!=0){ echo $namepresta;}else{ echo '';}?>" />
					
						</td>

						<td><label for="prix">Prix</label>
						
							<input type="text" name="prix" id="prix" value="<?php if(isset ($_GET['prestaconsu']) and $comptidA!=0){ echo $prixpresta;}else{ echo '';}?>" />
					
						</td>
						
						<td>
							<button type="submit" class="btn-large" name="editprestaconsubtn" style="font-size:20px;height:auto;margin-left:50px;margin-top:10px;width:100%;">
								<i class="fa fa-check fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(141);?>
							</button>
						</td>
					</tr>

					</table>
						
				</form>
			<?php
			}
			?>
			
			<?php 
			if(isset($_GET['divPrestaConsu']))
			{
				
				/*-----------Requête pour accountant-----------*/
				
				if($comptidA!=0)
				{
					$resultatsA=$connexion->prepare('SELECT *FROM categopresta c, prestations p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=:idcatego AND p.id_prestation=:idPresta ORDER BY p.id_prestation');
					$resultatsA->execute(array(
					'idcatego'=>$_GET['consu'],	
					'idPresta'=>$_GET['idpresta']	
					))or die( print_r($connexion->errorInfo()));
					
					$resultatsA->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				

			?>
				<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
					
					<thead> 
						<tr>
							<th>Nom prestation</th>
							<th>Name prestation</th>
							<th>Prix</th>
							<th colspan=2>Actions</th>
					</thead>
					<tbody>
						<?php
						while($ligneA=$resultatsA->fetch())//on récupère la liste des éléments
						{
						?>
						<tr style="text-align:center;"> 
							<td><?php echo $ligneA->nompresta;?></td>
							<td><?php echo $ligneA->namepresta;?></td>
							<td><?php echo $ligneA->prixpresta;?></td>
							
							<td>
								<a href="prestations.php?prestaconsu=<?php echo $ligneA->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['consu'])){ echo '&consu='.$_GET['consu'];}?><?php if(isset($_GET['typeconsu'])){ echo '&typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
							</td>
							
							<td>
								<a href="prestations.php?deleteprestaconsu=<?php echo $ligne->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['consu'])){ echo '&consu='.$_GET['consu'];}?><?php if(isset($_GET['typeconsu'])){ echo '&typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Delete</a>
							</td>
							
						</tr>
						<?php
						}
							$resultatsA->closeCursor();
						?>
				
					</tbody> 
			
				</table>
				<?php
				}
				?>
			
				<a href="prestations.php?iduser=<?php echo $_SESSION['id'];?>&consu=<?php echo $_GET['consu'];?>&typeconsu=<?php echo $_GET['typeconsu'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Prestations" class="btn-large">Show Prestations</a>
			<?php
			}
			?>
			
			<?php 
			if(!isset($_GET['divPrestaConsu']))
			{
			?>
			
			<div style="overflow:auto;height:300px;background-color:none;"> 
			
			<?php
			
				$resultats=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_categopresta=:idcatego AND statupresta=0 ORDER BY p.id_prestation DESC') or die( print_r($connexion->errorInfo()));
				$resultats->execute(array(
				'idcatego'=>$_GET['consu']
				))or die( print_r($connexion->errorInfo()));
				

				$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$prestaRows=$resultats->rowCount();
				
			if($prestaRows!=0)
			{
			?>
			
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th>Nom prestation</th>
						<th>Name prestation</th>
						<th>Prix</th>
						<th colspan=2>Actions</th>
					</tr> 
				</thead> 
				<tbody>
			<?php	
					
						while($ligne=$resultats->fetch())//on recupere la liste des éléments
						{
					?>
						<tr style="text-align:center;"> 
							<td><?php echo $ligne->nompresta;?></td>
							<td><?php echo $ligne->namepresta;?></td>
							<td><?php echo $ligne->prixpresta;?></td>
							
							<td>
								<a href="prestations.php?prestaconsu=<?php echo $ligne->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['consu'])){ echo '&consu='.$_GET['consu'];}?><?php if(isset($_GET['typeconsu'])){ echo '&typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
							</td>
							
							<td>
								<a href="prestations.php?deleteprestaconsu=<?php echo $ligne->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['consu'])){ echo '&consu='.$_GET['consu'];}?><?php if(isset($_GET['typeconsu'])){ echo '&typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Delete</a>
							</td>
							
						</tr>
					<?php
						}
						$resultats->closeCursor();
					?>
				</tbody> 
			</table>
			<?php
			}else{
			?>
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th>No prestation</th>
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
		<?php
		}
		?>
		
		<div id="divCategoPresta" style="<?php if(!isset($_GET['categopresta'])){ echo "display:none";}else{ echo "display:inline";}?>">
		
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:auto;margin-bottom:20px;" align="center">
			
				<tr>
					<td colspan=3 style="<?php if(isset($_GET['newcategopresta'])){ echo "display:none";}else{ echo "display:inline";}?>">
						<a href="prestations.php?acc=<?php echo $_SESSION['id'];?>&categopresta=ok&newcategopresta=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="text-align:center">
				
							<button id="newcategopresta" class="btn-large-inversed">Add new</button>
							
						</a>
					</td>
				</tr>
				
				<tr style="<?php if(!isset($_GET['newcategopresta'])){ echo "display:none";}else{ echo "display:inline";}?>">
					
					<td>
					<?php
					if(!isset ($_GET['editcatego']))
					{
					?>
						<form method="post" action="prestations.php?<?php if(isset($_GET['codeAcc'])){ echo 'codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormCategoPresta(this)">
						
							<table class="tablesorter" id="newCatego">

							<tr align="center">
								<td><label for="nomcatego">Nom categorie</label>
									<input type="text" name="nomcatego" id="nomcatego" value=""/>
							
								</td>

								<td><label for="namecatego">Name categorie</label>
									<input type="text" name="namecatego" id="namecatego" value=""/>
							
								</td>
								
								<td>
									<button type="submit" class="btn-large" name="categoprestabtn" style="font-size:20px;height:auto;margin-top:10px;width:100%;">
										<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(28);?>
									
									</button>
								</td>
								
								<td>
									<a href="prestations.php?categopresta=ok" class="btn-large-inversed" style="font-size:20px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?> 
									</a>
								</td>
								
							</tr>
							</table>
						
						</form>		
					<?php
					}
					
					if(isset ($_GET['editcatego']))
					{
					?>
						<form method="post" action="prestations.php?<?php if(isset($_GET['codeAcc'])){ echo 'codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['categopresta'])){ echo 'categopresta=ok';}?><?php if(isset($_GET['editcatego'])){ echo '&editcatego='.$_GET['editcatego'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPresta(this)">
						
								<h2>Modification</h2>
								
							<table class="tablesorter" id="newCatego">

							<tr align="center">
								<td><label for="nomcatego">Nom categorie</label>
								
									<input type="text" name="nomcatego" id="nomcatego" value="<?php if(isset ($_GET['editcatego']) and $comptidA!=0){ echo $nomcatego;}else{ echo '';}?>"/>
							
								</td>

								<td><label for="namecatego">Name categorie</label>
								
									<input type="text" name="namecatego" id="namecatego" value="<?php if(isset ($_GET['editcatego']) and $comptidA!=0){ echo $namecatego;}else{ echo '';}?>"/>
							
								</td>
								
								<td>
									<button type="submit" class="btn-large" name="editcategobtn" style="font-size:20px;height:auto;margin-top:10px;width:100%;">
										<i class="fa fa-check fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(141);?>
									
									</button>
								</td>
								
								<td>
									<a href="prestations.php?categopresta=ok" class="btn-large-inversed" style="font-size:20px;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?> 
									</a>
								</td>
					
							</tr>
							</table>
						
						</form>		
					<?php
					}
					?>
					</td>
				</tr>
			</table>
			<?php
					
			$resultatsCatego=$connexion->query('SELECT *FROM categopresta c WHERE c.id_categopresta!=1 AND c.id_categopresta!=20 AND statucategopresta=0 ORDER BY c.id_categopresta DESC');
			
			$resultatsCatego->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$categoprestaRows=$resultatsCatego->rowCount();
			
			if($categoprestaRows!=0)
			{
			?>
			<div style="overflow:auto;height:300px;width:auto;"> 
	
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;margin-bottom:20px;" align="center">
				<thead> 
					<tr>
						<th>Nom Categorie</th>
						<th>Name Categorie</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while($ligneCatego=$resultatsCatego->fetch())//on récupère la liste des éléments
					{
					?>
					<tr style="text-align:center;"> 
						<td><?php echo $ligneCatego->nomcategopresta;?></td>
						<td><?php echo $ligneCatego->namecategopresta;?></td>
										
						<td>
							<a href="prestations.php?editcatego=<?php echo $ligneCatego->id_categopresta;?>&categopresta=ok&newcategopresta=okidacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
							
							<a href="prestations.php?viewcategopresta=<?php echo $ligneCatego->id_categopresta;?>&nomcat=<?php echo $ligneCatego->nomcategopresta;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">View</a>
						
							<a href="prestations.php?deletecategopresta=<?php echo $ligneCatego->id_categopresta;?>&categopresta=ok&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Delete</a>
						</td>
						
					</tr>
					<?php
					}
						$resultatsCatego->closeCursor();
					?>
					
				</tbody> 
		
			</table>
			
			</div>
			<?php
			}else{
			?>
			<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr>
						<th>No Categories</th>
					</tr> 
				</thead>
			</table>
			<?php
			}
			?>
		</div>
		
<?php

if(isset($_GET['viewcategopresta']))
{
?>
		
	<div id="divPresta">
		
		<h2>Prestations <?php echo $_GET['nomcat'];?></h2>
		
		<form class="ajax" action="search.php" method="get">
			<p>
				<table align="center">
					<tr>
						<td>
							<label for="q"><?php echo getString(80);?></label>
							<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
						</td>
						
					</tr>
				</table>
			</p>
		</form>

		<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

		<!--<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>-->

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
			url : 'traitement_prestations.php?name=ok&viewcategopresta=<?php echo $_GET['viewcategopresta'];?>&nomcat=<?php echo $_GET['nomcat'];?>' , // url du fichier de traitement
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

if(!isset($_GET['presta']))
{
?>
	<form method="post" action="prestations.php?<?php if(isset($_GET['viewcategopresta'])){ echo 'viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPresta(this)">
		
		
		<table align="center" id="newPresta">

		<tr>
			<td><label for="nompresta">Nom prestation</label>
			
				<input type="text" name="nompresta" id="nompresta" value="" />
		
			</td>

			<td><label for="namepresta">Name prestation</label>
			
				<input type="text" name="namepresta" id="namepresta" value="" />
		
			</td>

			<td><label for="prix">Prix</label>
			
				<input type="text" name="prix" id="prix" value="" />
		
			</td>
			
			<td>
				<button type="submit" class="btn-large" name="prestabtn" style="font-size:20px;height:auto;margin-left:50px;margin-top:10px;width:100%;">
					<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(28);?>
				</button>
			</td>
		</tr>

		</table>
			
	</form>
<?php
}

if(isset($_GET['presta']))
{
?>
	<form method="post" action="prestations.php?editpresta=<?php echo $_GET['presta'];?><?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormEditPresta(this)">
	
					<h2>Modification</h2>
	
		<table align="center" id="newPresta">

		<tr>
			<td><label for="nompresta">Nom prestation</label>
			
				<input type="text" name="nompresta" id="nompresta" value="<?php if(isset($_GET['presta']) and $comptidA!=0){ echo $nompresta;}else{ echo '';}?>" />
		
			</td>

			<td><label for="namepresta">Name prestation</label>
			
				<input type="text" name="namepresta" id="namepresta" value="<?php if(isset ($_GET['presta']) and $comptidA!=0){ echo $namepresta;}else{ echo '';}?>" />
		
			</td>

			<td><label for="prix">Prix</label>
			
				<input type="text" name="prix" id="prix" value="<?php if(isset ($_GET['presta']) and $comptidA!=0){ echo $prixpresta;}else{ echo '';}?>" />
		
			</td>
			
			<td>
				<button type="submit" class="btn-large" name="editprestabtn" style="font-size:20px;height:auto;margin-left:50px;margin-top:10px;width:100%;">
					<i class="fa fa-check fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(141);?>
				</button>
			</td>
		</tr>

		</table>
			
	</form>
<?php
}
?>
	<?php 
	if(isset($_GET['divPresta']))
	{
		
		/*-----------Requête pour accountant-----------*/
		
		if($comptidA!=0)
		{
			$resultatsA=$connexion->prepare('SELECT *FROM categopresta c, prestations p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=:idcatego AND p.id_prestation=:idPresta ORDER BY p.id_prestation');
			$resultatsA->execute(array(
			'idcatego'=>$_GET['viewcategopresta'],	
			'idPresta'=>$_GET['idpresta']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsA->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		

	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			
			<thead> 
				<tr>
					<th>Nom prestation</th>
					<th>Name prestation</th>
					<th>Prix</th>
					<th colspan=2>Actions</th>
			</thead>
			<tbody>
				<?php
				while($ligneA=$resultatsA->fetch())//on récupère la liste des éléments
				{
				?>
				<tr style="text-align:center;"> 
					<td><?php echo $ligneA->nompresta;?></td>
					<td><?php echo $ligneA->namepresta;?></td>
					<td><?php echo $ligneA->prixpresta;?></td>
					
					<td>
						<a href="prestations.php?presta=<?php echo $ligneA->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
					</td>
					
					<td>
						<a href="prestations.php?deletepresta=<?php echo $ligneA->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Delete</a>
					</td>
					
				</tr>
				<?php
				}
					$resultatsA->closeCursor();
				?>
		
			</tbody> 
	
		</table>
		<?php
		}
		?>
	
		<a href="prestations.php?iduser=<?php echo $_SESSION['id'];?>&viewcategopresta=<?php echo $_GET['viewcategopresta'];?>&nomcat=<?php echo $_GET['nomcat'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Prestations" class="btn-large">Show Prestations</a>
	<?php
	}
	?>
	
	<?php 
	if(!isset($_GET['divPresta']))
	{
	?>
	<div style="overflow:auto;height:300px;background-color:none;"> 
	
	<?php
	
		$resultats=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_categopresta=:idcatego AND p.statupresta=0 ORDER BY p.id_prestation DESC') or die( print_r($connexion->errorInfo()));
		$resultats->execute(array(
		'idcatego'=>$_GET['viewcategopresta']
		))or die( print_r($connexion->errorInfo()));


		$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

		$prestaRows=$resultats->rowCount();
		
	if($prestaRows!=0)
	{
	?>
	
	<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th>Nom prestation</th>
				<th>Name prestation</th>
				<th>Prix</th>
				<th colspan=2>Actions</th>
			</tr> 
		</thead> 
		<tbody>
	<?php	
			
				while($ligne=$resultats->fetch())//on recupere la liste des éléments
				{
			?>
				<tr style="text-align:center;"> 
					<td><?php echo $ligne->nompresta;?></td>
					<td><?php echo $ligne->namepresta;?></td>
					<td><?php echo $ligne->prixpresta;?></td>
					
					<td>
						<a href="prestations.php?presta=<?php echo $ligne->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
					</td>
					
					<td>
						<a href="prestations.php?deletepresta=<?php echo $ligne->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['viewcategopresta'])){ echo '&viewcategopresta='.$_GET['viewcategopresta'];}?><?php if(isset($_GET['nomcat'])){ echo '&nomcat='.$_GET['nomcat'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Delete</a>
					</td>
					
				</tr>
			<?php
				}
				$resultats->closeCursor();
			?>
		</tbody> 
	</table>
	<?php
	}else{
	?>
	<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th>No prestation</th>
			</tr> 
		</thead>
	</table>
	<?php
	}
	?>
	</div>
	
	<!--
	
	<tr>
		<td>
		<?php

		$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM categopresta c, prestations p WHERE c.id_categopresta=p.id_categopresta ORDER BY p.id_prestation');
			$nb_total=$nb_total->fetch();
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
				   // Affichage
			  echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		?>
		</td>
	</tr>
	
	-->
	
	<?php 
	}
	?>

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
	alert("Your Browser does not support XMLHTTPRequest object...");
	return null;
}

return xhr;
}


function ShowDivSoumenu(soumenu)
{
	// var soumenu=document.getElementById('categopresta').value;

	if(soumenu =='divTypeConsu')
	{
		document.getElementById('divTypeConsu').style.display='inline';
		
		document.getElementById('divCategoPresta').style.display='none';
	
	}
	
	if(soumenu =='divCategoPresta')
	{
		document.getElementById('divTypeConsu').style.display='none';
		
		document.getElementById('divCategoPresta').style.display='inline';
	}

}



function ShowPresta(categopresta)
{
	var categopresta=document.getElementById('categopresta').value;

	if(categopresta !='NULL')
	{
		if(categopresta =='autrecatego' )
		{
			document.getElementById('newCatego').style.display='inline';
		}else{
			document.getElementById('newCatego').style.display='none';
		}
		
			document.getElementById('newPresta').style.display='inline';
		
	}else{
		
		document.getElementById('newPresta').style.display='none';
	}

}


function ShowSearch(search)
{
	if(search =='byname')
	{
		document.getElementById('results').style.display='inline';
		document.getElementById('resultsSN').style.display='none';
	}
	
	if(search =='bysn')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='inline';
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
	
	
				/*----------Delete Prestation Consu-------------------*/
	
	if(isset($_GET['deleteprestaconsu']))
	{	
		$idprestaconsu=$_GET['deleteprestaconsu'];

		$deletePrestaConsu=$connexion->prepare('UPDATE prestations SET statupresta=1 WHERE id_prestation=:idprestaconsu');
			
		$deletePrestaConsu->execute(array(
		'idprestaconsu'=>$idprestaconsu
		
		))or die($deletePrestaConsu->errorInfo());
?>	
		<script type="text/javascript"> 
			alert("Delete Prestation Successfully!!!");
		</script>
		
		<script type="text/javascript">
		document.location.href="prestations.php?<?php if(isset($_GET['typeconsu'])){ echo 'typeconsu='.$_GET['typeconsu'];}?><?php if(isset($_GET['consu'])){ echo '&consu='.$_GET['consu'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
		</script>

<?php
	
	}
	
	
			/*----------Delete Categorie Prestation-----------*/
	
	if(isset($_GET['deletecategopresta']))
	{	
		$idcategoprestaconsu=$_GET['deletecategopresta'];

		$deleteCategopresta=$connexion->prepare('UPDATE categopresta SET statucategopresta=1 WHERE id_categopresta=:idcategopresta');
			
		$deleteCategopresta->execute(array(
		'idcategopresta'=>$idcategoprestaconsu
		
		))or die($deleteCategopresta->errorInfo());
?>	
		<script type="text/javascript"> 
			alert("Delete Categorie Prestation Successfully!!!");
		</script>
		
		<script type="text/javascript">
		document.location.href="prestations.php?categopresta=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>";
		</script>

<?php
	
	}


?>

</body>

</html>