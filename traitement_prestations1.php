<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

//recherche des résultats dans la base de données

if(isset($_GET['name']))
{
	$result=$connexion->query( 'SELECT *FROM prestations p WHERE p.nompresta LIKE \'%' . safe( $_GET['q'] ) . '%\' OR p.namepresta LIKE \'%' . safe( $_GET['q'] ) . '%\' LIMIT 0,5' );
}

if(isset($_GET['catego']))
{	
	$result=$connexion->query( 'SELECT *FROM categopresta cp WHERE cp.nomcategopresta LIKE \'%' . safe( $_GET['s'] ) . '%\' OR cp.namecategopresta LIKE \'%' . safe( $_GET['s'] ) . '%\' LIMIT 0,5' );
}

/*--------affichage d'un message "pas de résultats"------------*/

$numRows=$result->rowCount();

if( $numRows == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['name'])){ echo $_GET['q'];}if(isset($_GET['catego'])){ echo $_GET['s'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
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
		<td id="<?php echo $post->id_prestation?>">
			<table cellspacing="10" style="background-color:#fff; color:#a00000; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
			<tr>
				<td>
					<a class="searchB" href="prestations1.php?iduti=<?php echo $_SESSION['id'];?>&idpresta=<?php echo $post->id_prestation?>&divPresta=ok">
						<h4>
							<?php
							if($post->namepresta!="" AND $post->nompresta!="")
							{
								echo $post->namepresta.' ('.$post->nompresta.')';
							}else{
								if($post->namepresta!="" AND $post->nompresta=="")
								{
									echo $post->namepresta;
								}else{
									if($post->namepresta=="" AND $post->nompresta!="")
									{	
										echo $post->nompresta;
									}
								}
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
	}
	
	if(isset($_GET['catego']))
	{
		while( $post = $result->fetch())
		{
    ?>
		<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
		<td id="<?php echo $post->id_categopresta?>">
			<table cellspacing="10" style="background-color:#fff; color:#a00000; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
			<tr>
				<td>
					<a class="searchB" href="prestations1.php?iduti=<?php echo $_SESSION['id'];?>&idcategopresta=<?php echo $post->id_categopresta;?>&divCategoPresta=ok#divsearchcategopresta">
						<h4>
							<?php
							if($post->namecategopresta!="" AND $post->nomcategopresta!="")
							{
								echo $post->namecategopresta.' ('.$post->nomcategopresta.')';
							}else{
								if($post->namecategopresta!="" AND $post->nomcategopresta=="")
								{
									echo $post->namecategopresta;
								}else{
									if($post->namecategopresta=="" AND $post->nomcategopresta!="")
									{	
										echo $post->nomcategopresta;
									}
								}
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