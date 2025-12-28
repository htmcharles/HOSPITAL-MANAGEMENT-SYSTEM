<?php
session_start();

include("connectLangues.php");
include("connect.php");


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



		/*----------------Edit Categorie Prestations--------------*/

if(isset($_GET['categopresta']))
{

	$resultCategoPresta=$connexion->prepare('SELECT *FROM categopresta cp WHERE cp.id_categopresta=:operation');
	$resultCategoPresta->execute(array(
	'operation'=>$_GET['categopresta']	
	));
	$resultCategoPresta->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligneCategoPresta=$resultCategoPresta->fetch())
	{
		$nomcategopresta=$ligneCategoPresta->nomcategopresta;
		$namecategopresta=$ligneCategoPresta->namecategopresta;
		$idgrade=$ligneCategoPresta->id_grade;
		$modifierIdCategoPresta=$_GET['categopresta'];
	}
	$resultCategoPresta->closeCursor();
	
	
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

function controlFormPresta(theForm){
	var rapport="";
		
	if(theForm.categopresta.value == "autrecatego")
	{
		rapport +=controlCatego(theForm.nomcatego,theForm.namecatego)
		rapport +=controlGrade(theForm.grade);
	}
	
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

	erreur="The name of the type of consultation.\n";
	}
	
	return erreur;
}

function controlGrade(fld){
	var erreur="";
		
	if(fld.value.trim()=="NULL"){
	erreur="The grade\n";
	fld.style.background="rgba(0,255,0,0.3)";
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

	
	<script type="text/javascript">

function controlFormEditCategoPresta(theForm){
	
	var rapport="";
	
	rapport +=controlCatego(theForm.nomcategopresta,theForm.namecategopresta)
	rapport +=controlGrade(theForm.gradecatego);

	
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

	erreur="The name of the type of consultation.\n";
	}
	
	return erreur;
}

function controlGrade(fld){
	var erreur="";
		
	if(fld.value.trim()=="NULL"){
	erreur="The grade\n";
	fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;
}	

</script>

	
	<script type="text/javascript">

function controlFormEditPresta(theForm){
	
	var rapport="";
		
	rapport +=controlPresta(theForm.nompresta,theForm.namepresta);
	rapport +=controlPrix(theForm.prix);

	
		if (rapport != "") {
		alert("Please correct the following errors:\n" + rapport);
					return false;
		 }
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
					<form method="post" action="prestations1.php?<?php if(isset($_GET['codeAcc'])){ echo 'codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}if(isset($_GET['divPresta'])){ echo '&divPresta='.$_GET['divPresta'];}if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}if(isset($_GET['categopresta'])){ echo '&categopresta='.$_GET['categopresta'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="prestations1.php?english=english<?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}if(isset($_GET['divPresta'])){ echo '&divPresta='.$_GET['divPresta'];}if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}if(isset($_GET['categopresta'])){ echo '&categopresta='.$_GET['categopresta'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="prestations1.php?francais=francais<?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}if(isset($_GET['iduti'])){ echo '&iduti='.$_GET['iduti'];}if(isset($_GET['page'])){ echo '&page='.$_GET['page'];}if(isset($_GET['divPresta'])){ echo '&divPresta='.$_GET['divPresta'];}if(isset($_GET['presta'])){ echo '&presta='.$_GET['presta'];}if(isset($_GET['categopresta'])){ echo '&categopresta='.$_GET['categopresta'];}?>" class="btn"><?php echo getString(29);?></a>
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
			<h2>Prestations</h2>
		
<form class="ajax" action="search.php" method="get">
	<p>
		<table align="center">
			<tr>
				<td>
					<label for="q"><?php echo getString(80);?></label>
					<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
				</td>
				
				<!--
				
				<td>
					<label for="s">Search by s/n</label>
					<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
				</td>
				
				-->
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
	url : 'traitement_prestations1.php?name=ok' , // url du fichier de traitement
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

<!--

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
	url : 'traitement_prestations1.php?sn=ok' , // url du fichier de traitement
	data : 's='+$(this).val() , // donn? ?nvoyer en  GET ou POST
	beforeSend : function() { // traitements JS ?aire AVANT l'envoi
		$field.after('<img src="images/loader4.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
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

-->

<?php

if(!isset($_GET['presta']))
{
?>
	<form method="post" action="prestations1.php?<?php if(isset($_GET['codeAcc'])){ echo 'codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPresta(this)">
		
		<table align="center">

		<tr>
			<td><label for="categopresta">Add new prestation</label>
			
				<select name="categopresta" id="categopresta" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:300px;" onchange="ShowPresta('categopresta')">

					<option value='NULL' id="nocatego"><?php echo getString(114); ?></option>
				<?php
				
				$resultatsCatPresta=$connexion->query('SELECT *FROM  categopresta cp ORDER BY cp.nomcategopresta ASC');
				
				$resultatsCatPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
				while($ligneCatPresta=$resultatsCatPresta->fetch())
				{	
				?>
						<option value='<?php echo $ligneCatPresta->id_categopresta;?>'><?php echo getCatego($ligneCatPresta->id_categopresta);?></option>
						
				<?php
				}
				?>
				
					<option value="autrecatego" id="autrecatego">Nouvelle Type de Consultation</option>
					
				</select>			
			</td>
			
			</tr>
		</table>

		<table id="newCatego" style="display:none">

		<tr align="center">
			<td><label for="nomcatego">Nom type de consultation</label>
			
				<input type="text" name="nomcatego" id="nomcatego" value="<?php if(isset ($_GET['idpresta']) and $comptidA!=0){ echo 'nomcatego';}else{ echo '';}?>"/>
		
			</td>

			<td><label for="namecatego">Name type of consultation</label>
			
				<input type="text" name="namecatego" id="namecatego" value="<?php if(isset ($_GET['idpresta']) and $comptidA!=0){ echo 'namecatego';}else{ echo '';}?>"/>
		
			</td>
			
			<td><label for="grade"><?php echo getString(34);?></label>
			
				<select name="grade" id="grade" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:230px;" onchange="ShowPresta('presta')" onchange="ShowService('Service')">
				
					<option value="NULL"><?php echo getString(194) ?></option>
				<?php

					$resultats=$connexion->query('SELECT *FROM grades ORDER BY id_grade');
					while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
					{
				?>
					<option value='<?php echo $ligne->id_grade;?>' id="grade"  <?php if(isset($_GET['iduti']) and $grade == $ligne->nomgrade){echo "selected='selected'";}?>>
					<?php echo $ligne->nomgrade;?>
					</option>
				<?php
					}
				?>
				</select>
				
			</td>
			
		</tr>
		</table>

		<table align="center" id="newPresta" style="display:none">

		<tr>
			<td><label for="nompresta">Nom prestation</label>
			
				<input type="text" name="nompresta" id="nompresta" value="<?php if(isset ($_GET['idpresta']) and $comptidA!=0){ echo 'nompresta';}else{ echo '';}?>" />
		
			</td>

			<td><label for="namepresta">Name prestation</label>
			
				<input type="text" name="namepresta" id="namepresta" value="<?php if(isset ($_GET['idpresta']) and $comptidA!=0){ echo 'namepresta';}else{ echo '';}?>" />
		
			</td>

			<td><label for="prix">Prix</label>
			
				<input type="text" name="prix" id="prix" value="<?php if(isset ($_GET['idpresta']) and $comptidA!=0){ echo 'prix';}else{ echo '';}?>" />
		
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
	<form method="post" action="prestations1.php?editpresta=<?php echo $_GET['presta'];?><?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormEditPresta(this)">
	
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
					<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(141);?>
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
			$resultatsA=$connexion->prepare('SELECT *FROM categopresta c, prestations p WHERE c.id_categopresta=p.id_categopresta AND p.id_prestation=:idPresta ORDER BY p.id_prestation');
			$resultatsA->execute(array(
			'idPresta'=>$_GET['idpresta']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsA->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		}

		
		if($comptidA!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			
			<thead> 
				<tr>
					<th>Nom prestation</th>
					<th>Name prestation</th>
					<th>Prix</th>
					<th>Categorie</th>
					<th>Actions</th>
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
					<?php
							
						$resultCategopresta=$connexion->prepare('SELECT *FROM categopresta WHERE id_categopresta=:idCatego');
						$resultCategopresta->execute(array(
						'idCatego'=>$ligneA->id_categopresta
						));
								
						$resultCategopresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptCategopresta=$resultCategopresta->rowCount();
						
						if($ligneCategopresta=$resultCategopresta->fetch())
						{
							if($ligneCategopresta->nomcategopresta == $ligneA->nomcategopresta)
							{
								$categopresta = getCatego($ligneCategopresta->id_categopresta);
								
								echo $categopresta;
							}
						}

					?>
					</td>
					
					<td>
						<a href="prestations1.php?presta=<?php echo $ligneA->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
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
	
		<a href="prestations1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Show Prestations" class="btn-large">Show Prestations</a>
	<?php
	}
	?>
	
	<?php 
	if(!isset($_GET['divPresta']))
	{
	?>
	<div style="overflow:auto;height:300px;background-color:none;"> 
	
	<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th>Nom prestation</th>
				<th>Name prestation</th>
				<th>Prix</th>
				<th>Categorie</th>
				<th>Actions</th>
			</tr> 
		</thead> 
		<tbody>
		<?php
		try
		{
			function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
			{
				$pagination = '';
				$link = preg_replace('`%([^d])`', '%%$1', $link);
				if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
				if ( $nb_pages > 1 ) {
			 
					// Lien Précédent
					if ( $current_page > 1 )
						$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
					else
						$pagination .= '';
			 
					// Lien(s) début
					for ( $i=1 ; $i<=$firstlast ; $i++ ) {
						$pagination .= ' ';
						$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'">'.$i.'</a>';
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
							$pagination .= '<a href="'.sprintf($link, $i).'">'.$i.'</a>';
					}
			 
					// ... avant page nb_pages ?
					if ( ($current_page+$around) < $nb_pages-$firstlast )
						$pagination .= '<span class="current">&hellip;</span>';
			 
				// Lien(s) fin
					$start = $nb_pages-$firstlast+1;
					if( $start <= $firstlast ) $start = $firstlast+1;
					for ( $i=$start ; $i<=$nb_pages ; $i++ ) {
						$pagination .= ' ';
					$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'">'.$i.'</a>';
					}
			 
					// Lien suivant
					if ( $current_page < $nb_pages )
						$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
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

				
				/*-----------Requête pour coordinateur-----------*/
				
			$resultats=$connexion->query('SELECT *FROM categopresta c, prestations p WHERE c.id_categopresta=p.id_categopresta ORDER BY p.id_prestation DESC') or die( print_r($connexion->errorInfo()));

			$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			
				while($ligne=$resultats->fetch())//on recupere la liste des éléments
				{
			?>
				<tr style="text-align:center;"> 
					<td><?php echo $ligne->nompresta;?></td>
					<td><?php echo $ligne->namepresta;?></td>
					<td><?php echo $ligne->prixpresta;?></td>
					<td>
					<?php
							
						$resultCategopresta=$connexion->prepare('SELECT *FROM categopresta WHERE id_categopresta=:idCatego');
						$resultCategopresta->execute(array(
						'idCatego'=>$ligne->id_categopresta
						));
								
						$resultCategopresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptCategopresta=$resultCategopresta->rowCount();
						
						if($ligneCategopresta=$resultCategopresta->fetch())
						{
							if($ligneCategopresta->nomcategopresta == $ligne->nomcategopresta)
							{
								$categopresta = getCatego($ligneCategopresta->id_categopresta);
								
								echo $categopresta;
							}
						}

					?>
					</td>
					
					<td>
						<a href="prestations1.php?presta=<?php echo $ligne->id_prestation;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
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

		<h2 style="margin:20px">Categorie Prestations</h2>
		
<form class="ajax" action="search.php" method="get">
	<p>
		<table align="center">
			<tr>
				<td>
					<label for="s"><?php echo getString(80);?></label>
					<input type="text" name="s" id="s" onclick="ShowSearch('byname')"/>
				</td>
								
			</tr>
		</table>
	</p>
</form>

<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsCatego"></div>

<script type="text/javascript">
$(document).ready( function() {
  // détection de la saisie dans le champ de recherche
  $('#s').keyup( function(){
    $field = $(this);
    $('#resultsCatego').html(''); // on vide les resultats
    $('#ajax-loader').remove(); // on retire le loader
 
    // on commence à traiter à partir du 2ème caractère saisie
    if( $field.val().length > 0 )
    {
      // on envoie la valeur recherché en GET au fichier de traitement
      $.ajax({
  	type : 'GET', // envoi des données en GET ou POST
	url : 'traitement_prestations1.php?catego=ok' , // url du fichier de traitement
	data : 's='+$(this).val() , // données à envoyer en  GET ou POST
	beforeSend : function() { // traitements JS à faire AVANT l'envoi
		$field.after('<img src="images/loader4.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
	},
	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
		$('#ajax-loader').remove(); // on enleve le loader
		$('#resultsCatego').html(data); // affichage des résultats dans le bloc
	}
      });
    }		
  });
});
</script>

<?php
if(isset($_GET['categopresta']))
{
?>
	<form method="post" action="prestations1.php?editcategopresta=<?php echo $_GET['categopresta'];?><?php if(isset($_GET['codeAcc'])){ echo '&codeAcc='.$_GET['codeAcc'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#divcategopresta" onsubmit="return controlFormEditCategoPresta(this)">
	
					<h2>Modification</h2>
	
		<table align="center" id="newCategoPresta">

		<tr>
			<td><label for="nomcategopresta">Nom type de consultation</label>
			
				<input type="text" name="nomcategopresta" id="nomcategopresta" value="<?php if(isset($_GET['categopresta']) and $comptidA!=0){ echo $nomcategopresta;}else{ echo '';}?>" />
		
			</td>

			<td><label for="namecategopresta">Name type of consultation</label>
			
				<input type="text" name="namecategopresta" id="namecategopresta" value="<?php if(isset ($_GET['categopresta']) and $comptidA!=0){ echo $namecategopresta;}else{ echo '';}?>" />
		
			</td>

			<td><label for="grade"><?php echo getString(34);?></label>
			
				<select name="gradecatego" id="gradecatego" style="background:#fbfbfb; border:1px solid #ddd; height:40px; width:230px;">
				
					<option value="NULL"><?php echo getString(194) ?></option>
				<?php

					$resultats=$connexion->query('SELECT *FROM grades ORDER BY id_grade');
					while($ligne=$resultats->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
					{
				?>
					<option value='<?php echo $ligne->id_grade;?>' <?php if(isset($_GET['categopresta']) and $idgrade == $ligne->id_grade){echo "selected='selected'";}?>>
					<?php echo $ligne->nomgrade;?>
					</option>
				<?php
					}
				?>
				</select>
				
			</td>
			
			<td>
				<button type="submit" class="btn-large" name="editcategoprestabtn" style="font-size:20px;height:auto;margin-left:50px;margin-top:10px;width:100%;">
					<i class="fa fa-save fa-lg fa-fw" style=" vertical-align:middle;"></i> &nbsp;<?php echo getString(141);?>
				</button>
			</td>	
			<td>
				<a href="prestations1.php#divcategopresta" class="btn-large-inversed" style="font-size:20px;height:auto;margin-left:60px;margin-top:10px;width:100%;"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?> </a>
			</td>
		</tr>

		</table>
			
	</form>
<?php
}
?>
	<?php 
	if(isset($_GET['divCategoPresta']))
	{
		
		/*-----------Requête pour accountant-----------*/
		
		if($comptidA!=0)
		{
			$resultatsA=$connexion->prepare('SELECT *FROM categopresta cp WHERE cp.id_categopresta=:idCategoPresta ORDER BY cp.id_categopresta');
			$resultatsA->execute(array(
			'idCategoPresta'=>$_GET['idcategopresta']	
			))or die( print_r($connexion->errorInfo()));
			
			$resultatsA->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
		}

		
		if($comptidA!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;" id="divsearchcategopresta">
			
			<thead> 
				<tr>
					<th>Nom Categorie</th>
					<th>Name Categorie</th>
					<th>Grade</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				while($ligneA=$resultatsA->fetch())//on récupère la liste des éléments
				{
				?>
				<tr style="text-align:center;"> 
					<td><?php echo $ligneA->nomcategopresta;?></td>
					<td><?php echo $ligneA->namecategopresta;?></td>
					
					<td>
					<?php
							
						$resultGrade=$connexion->prepare('SELECT *FROM grades WHERE id_grade=:idgrade');
						$resultGrade->execute(array(
						'idgrade'=>$ligneA->id_grade
						));
								
						$resultGrade->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptGrade=$resultGrade->rowCount();
						
						if($ligneGrade=$resultGrade->fetch())
						{
							if($ligneGrade->id_grade == $ligneA->id_grade)
							{
								$nomgrade = $ligneGrade->nomgrade;
								
								echo $nomgrade;
							}
						}

					?>
					</td>
									
					<td>
						<a href="prestations1.php?categopresta=<?php echo $ligneA->id_categopresta;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn">Modifier</a>
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
	
		<a href="prestations1.php?iduser=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#divcategopresta" style="margin-right:5px;" class="btn-large">Show Categorie Prestations</a>
	<?php
	}
	?>
	
	
	<?php 
	if(!isset($_GET['divCategoPresta']))
	{
	?>
	<div style="overflow:auto;height:300px;background-color:none;" id="divcategopresta"> 
	
	<table class="tablesorter" cellspacing="0"> 
		<thead> 
			<tr>
				<th>Nom Categorie</th>
				<th>Name Categorie</th>
				<th>Grade</th>
				<th>Actions</th>
			</tr> 
		</thead> 
		<tbody>
		<?php
		try
		{
			
				/*-----------Requête pour comptables-----------*/
				
			$resultats=$connexion->query('SELECT *FROM categopresta cp ORDER BY cp.id_categopresta') or die( print_r($connexion->errorInfo()));

			$resultats->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			
				while($ligne=$resultats->fetch())//on recupere la liste des éléments
				{
			?>
				<tr style="text-align:center;"> 
					<td><?php echo $ligne->nomcategopresta;?></td>
					<td><?php echo $ligne->namecategopresta;?></td>
					
					<td>
					<?php
							
						$resultGrade=$connexion->prepare('SELECT *FROM grades WHERE id_grade=:idgrade');
						$resultGrade->execute(array(
						'idgrade'=>$ligne->id_grade
						));
								
						$resultGrade->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptGrade=$resultGrade->rowCount();
						
						if($ligneGrade=$resultGrade->fetch())
						{
							if($ligneGrade->id_grade == $ligne->id_grade)
							{
								$nomgrade = $ligneGrade->nomgrade;
								
								echo $nomgrade;
							}
						}

					?>
					</td>
					
					<td>
						<a href="prestations1.php?categopresta=<?php echo $ligne->id_categopresta;?>&idacc=<?php echo $_SESSION['id'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#divcategopresta" class="btn">Modifier</a>
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


	if(isset($_POST['prestabtn']))
	{
		if($_POST['categopresta'] == 'autrecatego')
		{
			$nomcatego = $_POST['nomcatego'];
			$namecatego = $_POST['namecatego'];
			$grade = $_POST['grade'];
			
			$createCategoPresta=$connexion->prepare('INSERT INTO categopresta (nomcategopresta,namecategopresta,id_grade) VALUES(:nomcatego, :namecatego, :grade)');
			
			$createCategoPresta->execute(array(
			'nomcatego'=>$nomcatego,
			'namecatego'=>$namecatego,
			'grade'=>$grade
			))or die( print_r($connexion->errorInfo()));
			
			$getIdCategoPresta=$connexion->query('SELECT *FROM categopresta c ORDER BY c.id_categopresta DESC LIMIT 1');
			
			$getIdCategoPresta->setFetchMode(PDO::FETCH_OBJ);
		
			$ligneIdCategoPresta=$getIdCategoPresta->fetch();
			
			$idCategoPresta = $ligneIdCategoPresta->id_categopresta;
			$nompresta = $_POST['nompresta'];
			$namepresta = $_POST['namepresta'];
			$prix = $_POST['prix'];
			$iduti = $_SESSION['id'];

			$createPresta=$connexion->prepare('INSERT INTO prestations (nompresta,namepresta,prixpresta,id_categopresta) VALUES(:nompresta, :namepresta, :prix, :idCategoPresta)');
							
			$createPresta->execute(array(
			'nompresta'=>$nompresta,
			'namepresta'=>$namepresta,
			'prix'=>$prix,
			'idCategoPresta'=>$idCategoPresta
			))or die( print_r($connexion->errorInfo()));
			
		
		}else{

			$categopresta = $_POST['categopresta'];
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
		}
				
		echo '<script type="text/javascript"> alert("Success!!!");</script>';
		
		echo '<script type="text/javascript">document.location.href="prestations1.php";</script>';
		
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
		
		echo '<script type="text/javascript">document.location.href="prestations1.php";</script>';
		
	}

	
	if(isset($_POST['editcategoprestabtn']))
	{
		
			$idcategopresta = $_GET['editcategopresta'];
			$nomcategopresta = $_POST['nomcategopresta'];
			$namecategopresta = $_POST['namecategopresta'];
			$idgrade = $_POST['gradecatego'];
			$iduti = $_SESSION['id'];

			$editPresta=$connexion->prepare('UPDATE categopresta SET nomcategopresta=:nomcatego,namecategopresta=:namecatego,id_grade=:grade WHERE id_categopresta=:idcategopresta');
							
			$editPresta->execute(array(
			'nomcatego'=>$nomcategopresta,
			'namecatego'=>$namecategopresta,
			'grade'=>$idgrade,
			'idcategopresta'=>$idcategopresta
			))or die( print_r($connexion->errorInfo()));
				
		echo '<script type="text/javascript"> alert("Edit Catego Successfully!!!");</script>';
		
		echo '<script type="text/javascript">document.location.href="prestations1.php?#divcategopresta";</script>';
		
	}


?>

</body>

</html>