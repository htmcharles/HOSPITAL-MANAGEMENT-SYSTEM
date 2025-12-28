<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

$sqlP=$connexion->query("SELECT *FROM patients_hosp ph WHERE ph.id_uHosp='$id'");

$resultM=$connexion->query('SELECT *FROM consultations c,utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.id_factureHosp IS NULL AND c.numero=ph.numero');

$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlM=$connexion->query("SELECT *FROM coordinateurs c WHERE c.id_u='$id'");
$sqlR=$connexion->query("SELECT *FROM receptionistes r WHERE r.id_u='$id'");
$sqlA=$connexion->query("SELECT *FROM auditors a WHERE a.id_u='$id'");
$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");


$comptidP=$sqlP->rowCount();
$comptidD=$sqlD->rowCount();
$comptidI=$sqlI->rowCount();
$comptidL=$sqlL->rowCount();
$comptidM=$sqlM->rowCount();
$comptidR=$sqlR->rowCount();
$comptidA=$sqlA->rowCount();
$comptidC=$sqlC->rowCount();

$comptcodeM=$resultM->rowCount();

//recherche des résultats dans la base de données

if(isset($_GET['name']))
{
	$result=$connexion->query( 'SELECT u.id_u,u.full_name,ph.id_uHosp,u.status,ph.numero FROM utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND (u.full_name LIKE \'%' . safe( $_GET['q'] ) . '%\') GROUP BY u.full_name LIMIT 0,10');
}

if(isset($_GET['sn']))
{
	$result=$connexion->query( 'SELECT * FROM utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.numero LIKE \'%' . safe( $_GET['s'] ) . '%\' LIMIT 0,5');
}


if(isset($_GET['ri']))
{
	$result=$connexion->query( 'SELECT * FROM utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.reference_idHosp LIKE \'%' . safe( $_GET['r'] ) . '%\' LIMIT 0,5');
}


/*--------affichage d'un message "pas de résultats"------------*/

$numRows=$result->rowCount();

if( $numRows == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['name'])){ echo $_GET['q'];}if(isset($_GET['sn'])){ echo $_GET['s'];}if(isset($_GET['ri'])){ echo $_GET['r'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
<?php
}else{
?>
	<table style="margin: auto;" cellspacing="0">
	<tr>	
<?php
    // parcours et affichage des résultats
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	if(isset($_GET['name']))
	{
		while( $post = $result->fetch())
		{
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_u?>">
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
				<tr>
					<td>
						<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="patients1_hosp.php?iduti=<?php echo $post->id_u?>&numPa=<?php echo $post->numero?>&fullname=<?php echo $post->full_name;?>&divPa=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
							<h4>
							<?php 
								echo $post->full_name;
							
							if(isset($_GET['caissier']))
							{
								echo '<br/>('.$post->numero.')';
							}
							?>
							</h4>
						</a>
					</td>
				
				</tr>
				</table>
			</td>
		<?php
		}
		?>
			<td>
			
			</td>
<?php
	}

	if(isset($_GET['sn']))
	{
		while( $post = $result->fetch())
		{
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_u?>">
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
				<tr>
					<td>
						<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="patients1_hosp.php?iduti=<?php echo $post->id_u?>&numPa=<?php echo $post->numero?>&fullname=<?php echo $post->full_name;?>&divPa=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
							<h4><?php echo $post->numero.' </br>('.$post->nom_u.' '.$post->prenom_u.')'; ?></h4>
						</a>
					</td>
				
				</tr>
				</table>
			</td>
<?php
		}
	}
	
	if(isset($_GET['ri']))
	{
		while( $post = $result->fetch())
		{
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_u?>">
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
				<tr>
					<td>
						<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="patients1_hosp.php?iduti=<?php echo $post->id_u?>&numPa=<?php echo $post->reference_id?>&fullname=<?php echo $post->full_name;?>&divPa=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
							<h4><?php echo $post->reference_id.' </br>('.$post->nom_u.' '.$post->prenom_u.')'; ?></h4>
						</a>
					</td>
				
				</tr>
				</table>
			</td>
<?php
		}
	}
?>
	</tr>
	</table>

 <?php
}
 
/*****
fonctions
*****/
function safe($var)
{
	////$var = mysql_real_escape_string($var);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}
?>