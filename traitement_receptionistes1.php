<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

//recherche des résultats dans la base de données

if(isset($_GET['name']))
{
	$result=$connexion->query( 'SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u AND (u.nom_u LIKE \'%' . safe( $_GET['q'] ) . '%\' OR u.prenom_u LIKE \'%' . safe( $_GET['q'] ) . '%\' OR u.full_name LIKE \'%' . safe( $_GET['q'] ) . '%\') AND r.codereceptio LIKE \'PER%\' LIMIT 0,5' );
}

if(isset($_GET['sn']))
{
	$result=$connexion->query( 'SELECT *FROM utilisateurs u, receptionistes r WHERE u.id_u=r.id_u AND r.codereceptio LIKE \'%' . safe( $_GET['s'] ) . '%\' AND r.codereceptio LIKE \'PER%\' LIMIT 0,5' );
}

/*--------affichage d'un message "pas de résultats"------------*/

$numRows=$result->rowCount();

if( $numRows == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['name'])){ echo $_GET['q'];}if(isset($_GET['sn'])){ echo $_GET['s'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
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
					<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="receptionistes1.php?iduti=<?php echo $post->id_u?>&codeRec=<?php echo $post->codereceptio;?>&divRec=ok">
						<h4><?php echo $post->nom_u.' '.$post->prenom_u.' ('.$post->codereceptio.')'; ?></h4>
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
    ?>
		<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
		<td id="<?php echo $post->id_u?>">
			<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
			<tr>
				<td>
					<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="receptionistes1.php?iduti=<?php echo $post->id_u?>&codeRec=<?php echo $post->codereceptio;?>&divRec=ok">
						<h4><?php echo $post->codereceptio.' ('.$post->nom_u.' '.$post->prenom_u.')'; ?></h4>
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