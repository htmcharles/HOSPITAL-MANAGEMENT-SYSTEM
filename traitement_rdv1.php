<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

//recherche des résultats dans la base de données

if(isset($_GET['date']))
{
	if(isset($_SESSION['codeM']))
	{
		$ownrdv='r.id_uM='.$_SESSION['id'].' AND';
	}else{
		$ownrdv='';
	}
				
	$result=$connexion->query( 'SELECT *FROM rendez_vous r WHERE '.$ownrdv.' r.daterdv LIKE \'%' . safe( $_GET['d'] ) . '%\'' );
	
	$resultRdvTable=$connexion->query( 'SELECT *FROM rendez_vous r WHERE '.$ownrdv.' r.daterdv LIKE \'%' . safe( $_GET['d'] ) . '%\'' );
}
 
if(isset($_GET['name']))
{
	$result=$connexion->query( 'SELECT u.id_u,u.full_name,p.id_u,u.status,p.numero FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND (u.full_name LIKE \'%' . safe( $_GET['q'] ) . '%\') GROUP BY u.full_name LIMIT 0,10');
	
	$resultRdvTable=$connexion->query( 'SELECT *FROM rendez_vous r WHERE r.autrePa LIKE \'%' . safe( $_GET['q'] ) . '%\' GROUP BY r.autrePa LIMIT 0,10');
	
}

if(isset($_GET['sn']))
{
	$result=$connexion->query( 'SELECT u.id_u,u.full_name,p.id_u,u.status,p.numero FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero LIKE \'%' . safe( $_GET['s'] ) . '%\' LIMIT 0,5');
	
	$resultRdvTable=$connexion->query( 'SELECT *FROM rendez_vous r, patients p WHERE r.numero=p.numero');
	
}


if(isset($_GET['ri']))
{
	$result=$connexion->query( 'SELECT u.id_u,u.full_name,p.id_u,u.status,p.numero,p.reference_id FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.reference_id LIKE \'%' . safe( $_GET['r'] ) . '%\' LIMIT 0,5');
	
	$resultRdvTable=$connexion->query( 'SELECT *FROM rendez_vous r, patients p WHERE r.numero=p.numero');
}


/*--------affichage d'un message "pas de résultats"------------*/

$numRows=$result->rowCount();
$numRowsRdvTable=$resultRdvTable->rowCount();

if( $numRows == 0 AND $numRowsRdvTable == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['date'])){ echo $_GET['d'];}if(isset($_GET['name'])){ echo $_GET['q'];}if(isset($_GET['sn'])){ echo $_GET['s'];}if(isset($_GET['ri'])){ echo $_GET['r'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
<?php
}else{
?>
	<table style="margin: auto;" cellspacing="0">
	<tr>	
<?php
    // parcours et affichage des résultats
	$result->setFetchMode(PDO::FETCH_OBJ);
	$resultRdvTable->setFetchMode(PDO::FETCH_OBJ);
	
	if(isset($_GET['date']))
	{
		while( $post = $result->fetch())
		{
							
			$numeroPa=$post->numero;
				
			$resultPa=$connexion->prepare("SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u AND p.numero LIKE :numeroPa");
			$resultPa->execute(array(
			'numeroPa'=>$numeroPa
			))or die( print_r($connexion->errorInfo()));
							
			$resultPa->setFetchMode(PDO::FETCH_OBJ);
			$compNumPa=$resultPa->rowCount();
			
			$lignePa = $resultPa->fetch();
			
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_rdv?>">
			
				<table cellspacing="10" style="<?php if($post->statusRdv!=0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->statusRdv!=0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="rendezvous1.php?numPa=<?php echo $post->numero?>&fullname=<?php if($compNumPa!=0) { echo $namePa=$lignePa->full_name;}else{ echo $namePa=$post->autrePa;}?>&divRdv=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
								<h4><?php echo $post->daterdv.'<br/>(Patient :'.$namePa.')';?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
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
						<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="rendezvous1.php?numPa=<?php echo $post->numero?>&fullname=<?php echo $post->full_name;?>&divRdv=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
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
		
		while( $post = $resultRdvTable->fetch())
		{
		?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_rdv;?>">
				<table cellspacing="10" style="<?php if($post->statusRdv==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
				<tr>
					<td>
						<a class="<?php if($post->statusRdv==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="rendezvous1.php?numPa=<?php echo $post->numero?>&fullname=<?php echo $post->autrePa;?>&divRdv=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
							<h4>
							<?php 
								echo $post->autrePa;
							
								echo '<br/>('.$post->autreTel.')';
						
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
						<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="rendezvous1.php?numPa=<?php echo $post->numero?>&fullname=<?php echo $post->full_name;?>&divRdv=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
							<h4><?php echo $post->numero.' </br>('.$post->full_name.')'; ?></h4>
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
						<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="rendezvous1.php?numPa=<?php echo $post->reference_id?>&fullname=<?php echo $post->full_name;?>&divRdv=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
							<h4><?php echo $post->reference_id.' </br>('.$post->full_name.')'; ?></h4>
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
	// //$var = mysql_real_escape_string($var);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}
?>