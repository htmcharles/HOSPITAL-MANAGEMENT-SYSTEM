<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

//recherche des résultats dans la base de données

if (!isset($_GET['trash'])) {

	if(isset($_GET['date']))
	{
		$result=$connexion->query( 'SELECT *FROM bills b WHERE b.status=0 AND b.datebill LIKE \'%' . safe( $_GET['d'] ) . '%\'' );
	}
	 
	if(isset($_GET['name']))
	{
		$result=$connexion->query( 'SELECT *FROM utilisateurs u,patients p,bills b WHERE u.id_u=p.id_u AND b.numero=p.numero AND b.status=0 AND (u.full_name LIKE \'%' . safe( $_GET['n'] ) . '%\')' );
	}
	 
	if(isset($_GET['sn']))
	{
		$result=$connexion->query( 'SELECT *FROM utilisateurs u,patients p,bills b WHERE u.id_u=p.id_u AND b.numero=p.numero AND b.status=0 AND b.numero LIKE \'%' . safe( $_GET['s'] ) . '%\'' );
	}
	 
	if(isset($_GET['bn']))
	{
		$result=$connexion->query( 'SELECT *FROM utilisateurs u,patients p,bills b WHERE u.id_u=p.id_u AND b.numero=p.numero AND b.status=0 AND b.numbill LIKE \'%' . safe( $_GET['b'] ) . '%\'' );
	}
}else{

	if(isset($_GET['date']))
	{
		$result=$connexion->query( 'SELECT *FROM bills b WHERE b.status=1 AND b.datebill LIKE \'%' . safe( $_GET['d'] ) . '%\'' );
	}
	 
	if(isset($_GET['name']))
	{
		$result=$connexion->query( 'SELECT *FROM utilisateurs u,patients p,bills b WHERE u.id_u=p.id_u AND b.numero=p.numero AND b.status=1 AND (u.full_name LIKE \'%' . safe( $_GET['n'] ) . '%\')' );
	}
	 
	if(isset($_GET['sn']))
	{
		$result=$connexion->query( 'SELECT *FROM utilisateurs u,patients p,bills b WHERE u.id_u=p.id_u AND b.numero=p.numero AND b.status=1 AND b.numero LIKE \'%' . safe( $_GET['s'] ) . '%\'' );
	}
	 
	if(isset($_GET['bn']))
	{
		$result=$connexion->query( 'SELECT *FROM utilisateurs u,patients p,bills b WHERE u.id_u=p.id_u AND b.numero=p.numero AND b.status=1 AND b.numbill LIKE \'%' . safe( $_GET['b'] ) . '%\'' );
	}
}


 
/*--------affichage d'un message "pas de résultats"------------*/

$billRows=$result->rowCount();
if( $billRows == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['name'])){ echo $_GET['n'];}if(isset($_GET['sn'])){ echo $_GET['s'];}if(isset($_GET['date'])){ echo $_GET['d'];}if(isset($_GET['bn'])){ echo $_GET['b'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
<?php
}else{
?>
	<table style="margin:auto;" cellspacing="0">
	<tr>	
<?php
    // parcours et affichage des résultats
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	if(isset($_GET['date']))
	{
		while( $post = $result->fetch())
		{
			if (!isset($_GET['trash'])) {
				$link = "facturesedit.php";
			}else{
				$link = "RecycleBin";
			}
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_bill?>">
			
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?idbill=<?php echo $post->id_bill?>&numero=<?php echo $post->numero;?>&codeCoord=<?php echo $_GET['codeCoord'];?>&divBill=ok">
								<h4><?php echo $post->numbill.'<br/>('.$post->datebill.')'; ?></h4>
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
			if (!isset($_GET['trash'])) {
				$link = "facturesedit.php";
			}else{
				$link = "RecycleBin";
			}
	?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_bill?>">
			
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?idbill=<?php echo $post->id_bill?>&numero=<?php echo $post->numero;?>&codeCoord=<?php echo $_GET['codeCoord'];?>&divBill=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->numbill.'('.$post->datebill.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
	if(isset($_GET['sn']))
	{
		while( $post = $result->fetch())
		{
			if (!isset($_GET['trash'])) {
				$link = "facturesedit.php";
			}else{
				$link = "RecycleBin";
			}
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_bill?>">
			
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?idbill=<?php echo $post->id_bill?>&numero=<?php echo $post->numero;?>&codeCoord=<?php echo $_GET['codeCoord'];?>&divBill=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->numbill.'('.$post->datebill.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
	if(isset($_GET['bn']))
	{
		while( $post = $result->fetch())
		{
			if (!isset($_GET['trash'])) {
				$link = "facturesedit.php";
			}else{
				$link = "RecycleBin";
			}
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_bill?>">
			
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?idbill=<?php echo $post->id_bill?>&numero=<?php echo $post->numero;?>&codeCoord=<?php echo $_GET['codeCoord'];?>&divBill=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->numbill.'('.$post->datebill.')'; ?></h4>
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