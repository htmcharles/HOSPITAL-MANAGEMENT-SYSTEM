<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

//recherche des résultats dans la base de données
if (!isset($_GET['trash'])) {
	if(isset($_GET['datehosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM patients_hosp ph WHERE ph.statusPaHosp=0 AND ph.statusBill=0 AND ph.dateSortie LIKE \'%' . safe( $_GET['dh'] ) . '%\'' );
	}
	 
	if(isset($_GET['namehosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM utilisateurs u,patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.statusPaHosp=0 AND ph.statusBill=0 AND (u.full_name LIKE \'%' . safe( $_GET['nh'] ) . '%\')' );
	}
	 
	if(isset($_GET['snhosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM utilisateurs u,patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.statusPaHosp=0 AND ph.statusBill=0 AND ph.numero LIKE \'%' . safe( $_GET['sh'] ) . '%\'' );
	}
	 
	if(isset($_GET['bnhosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM utilisateurs u,patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.statusPaHosp=0 AND ph.statusBill=0 AND ph.id_factureHosp LIKE \'%' . safe( $_GET['bh'] ) . '%\'' );
	}
}else{

	if(isset($_GET['datehosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM patients_hosp ph WHERE ph.statusPaHosp=0 AND ph. AND ph.statusBill=1 AND ph.dateSortie LIKE \'%' . safe( $_GET['dh'] ) . '%\'' );
	}
	 
	if(isset($_GET['namehosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM utilisateurs u,patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.statusPaHosp=0 AND ph. AND ph.statusBill=1 AND (u.full_name LIKE \'%' . safe( $_GET['nh'] ) . '%\')' );
	}
	 
	if(isset($_GET['snhosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM utilisateurs u,patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.statusPaHosp=0 AND ph. AND ph.statusBill=1 AND ph.numero LIKE \'%' . safe( $_GET['sh'] ) . '%\'' );
	}
	 
	if(isset($_GET['bnhosp']))
	{
		$resulthosp=$connexion->query( 'SELECT *FROM utilisateurs u,patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.statusPaHosp=0 AND ph.statusBill=1 AND ph.id_factureHosp LIKE \'%' . safe( $_GET['bh'] ) . '%\'' );
	}
}

 
 
/*--------affichage d'un message "pas de résultats"------------*/

$billhospRows=$resulthosp->rowCount();

if( $billhospRows == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['namehosp'])){ echo $_GET['nh'];}if(isset($_GET['snhosp'])){ echo $_GET['sh'];}if(isset($_GET['datehosp'])){ echo $_GET['dh'];}if(isset($_GET['bnhosp'])){ echo $_GET['bh'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
<?php
}else{
?>
	<table style="margin:auto;" cellspacing="0">
	<tr>	
<?php
    // parcours et affichage des résultats
	$resulthosp->setFetchMode(PDO::FETCH_OBJ);
	
	if(isset($_GET['datehosp']))
	{
		while( $post = $resulthosp->fetch())
		{
			if (isset($_GET['trash'])) {
				$link = "RecycleBin";
			}else{
				if(isset($_GET['cai'])){
					$link = "listfacture.php";
				}else{
					$link = "facturesedit.php";
				}
			}
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_hosp?>">
			
				<table cellspacing="10" style="<?php if($post->statusPaHosp==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->statusPaHosp==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?id_hosp=<?php echo $post->id_hosp?><?php if(isset($_GET['codeCoord'])){echo '&codeCoord='.$_GET['codeCoord'];}?>&divBillHosp=ok">
								<h4><?php echo $post->id_factureHosp.'<br/>('.$post->dateSortie.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
	if(isset($_GET['namehosp']))
	{
		while( $post = $resulthosp->fetch())
		{
			if (isset($_GET['trash'])) {
				$link = "RecycleBin";
			}else{
				if(isset($_GET['cai'])){
					$link = "listfacture.php";
				}else{
					$link = "facturesedit.php";
				}
			}
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_hosp?>">
			
				<table cellspacing="10" style="<?php if($post->statusPaHosp==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->statusPaHosp==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?id_hosp=<?php echo $post->id_hosp?><?php if(isset($_GET['codeCoord'])){echo '&codeCoord='.$_GET['codeCoord'];}?>&divBillHosp=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->id_factureHosp.'('.$post->dateSortie.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
	if(isset($_GET['snhosp']))
	{
		while( $post = $resulthosp->fetch())
		{
			if (isset($_GET['trash'])) {
				$link = "RecycleBin";
			}else{
				if(isset($_GET['cai'])){
					$link = "listfacture.php";
				}else{
					$link = "facturesedit.php";
				}
			}
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_hosp;?>">
			
				<table cellspacing="10" style="<?php if($post->statusPaHosp==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->statusPaHosp==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?id_hosp=<?php echo $post->id_hosp?><?php if(isset($_GET['codeCoord'])){echo '&codeCoord='.$_GET['codeCoord'];}?>&divBillHosp=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->id_factureHosp.'('.$post->dateSortie.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
	if(isset($_GET['bnhosp']))
	{
		while( $post = $resulthosp->fetch())
		{
			if (isset($_GET['trash'])) {
				$link = "RecycleBin";
			}else{
				if(isset($_GET['cai'])){
					$link = "listfacture.php";
				}else{
					$link = "facturesedit.php";
				}
			}
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_hosp?>">
			
				<table cellspacing="10" style="<?php if($post->statusPaHosp==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->statusPaHosp==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="<?= $link;?>?id_hosp=<?php echo $post->id_hosp?><?php if(isset($_GET['codeCoord'])){echo '&codeCoord='.$_GET['codeCoord'];}?>&divBillHosp=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->id_factureHosp.'('.$post->dateSortie.')'; ?></h4>
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
	//$var = mysql_real_escape_string($var);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}
?>