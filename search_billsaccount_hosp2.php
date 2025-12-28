<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

//recherche des résultats dans la base de données
$result=$connexion->query( 'SELECT *FROM patients_hosp ph WHERE ph.statusBill=1 AND ph.id_factureHosp LIKE \'%' . safe( $_GET['b'] ) . '%\'' );
 
/*--------affichage d'un message "pas de résultats"------------*/

$billRows=$result->rowCount();
if( $billRows == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php echo $_GET['b'];?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
<?php
}
else
{
?>
	<table style="margin: auto;" cellspacing="0">
	<tr>	
<?php
    // parcours et affichage des résultats
	$result->setFetchMode(PDO::FETCH_OBJ);
    while( $post = $result->fetch())
    {
    ?>
		<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
		<td id="<?php echo $post->id_factureHosp?>">
			<table cellspacing="10" style="<?php if($post->statusBill==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
				
				<tr>
					<td>
						<a class="<?php if($post->statusBill==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="billsaccount.php?id_factureHosp=<?php echo $post->id_factureHosp?>&codeAcc=<?php echo $post->codeaccount?>&divAcc2=ok">
							<h4><?php echo $post->id_factureHosp; ?></h4>
						</a>
					</td>
				
				</tr>
				
			</table>
		</td>	
	<?php
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