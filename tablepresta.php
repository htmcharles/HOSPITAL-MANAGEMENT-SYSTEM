
<!doctype html>
<html>
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>

<head>
	<meta charset="utf-8"/>
	
</head>

<body>
<?php
include("connect.php");
include("connectLangues.php");

if(isset($_POST['categorie']))
{
	$prestation="";

	$id_serv=$_POST['categorie'];

	//$id_prov=1;
	if(isset($_GET['num']))
	{
		$resultats=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultats->execute(array(
		'operation'=>$_GET['num']	
		));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);
		if($ligne=$resultats->fetch())
		{
			$num=$ligne->numero;
			$idassu=$ligne->id_assurance;
		}
		$resultats->closeCursor();

	}
	
											
	$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
	
	$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
			
	$assuCount = $comptAssuConsu->rowCount();
	
	for($i=1;$i<=$assuCount;$i++)
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


		$req=$connexion->prepare('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=:catego ORDER BY p.id_prestation DESC');
		$req->execute(array(
			'catego'=>$id_serv
		));
		

	$comptPresta=$req->rowCount();
	
	if($comptPresta!=0)
	{
		if($lignecatego=$req->fetch(PDO::FETCH_ASSOC))
		{
			$nomcatego=$lignecatego['nomcategopresta'];
			
			$prestation.="
			
			<h2 style='margin:10px;'>".getCatego($lignecatego['id_categopresta'])."</h2>";
		
		
			$prestation.="
			
			<div id='divViewService' style='overflow:auto; height:600px; margin:10px auto; width:50%'>
			
			";
		

		$prestation.="
			
			<table class='tablesorter' id='typeconsu'>
				<thead> 
					<tr>
						<th>Actions</th>
						<th>Prestations</th>
						<th>Prix Unitaire</th>
					</tr> 
				</thead>
				
				<tbody>
		";
		
		if($lignecatego['nompresta']!="")
		{
			$presta=$lignecatego['nompresta'];
		}else{
			$presta=$lignecatego['namepresta'];
		}
		
			$prestation.="
				<tr style='text-align:center'> 
					<td><input type='checkbox' name='checkprestaServ[]' id='checkprestaServ' value='".$lignecatego['id_prestation']."_".$presta."'/></td>
					<td>";
					
			$prestation.=$presta;
											
			$prestation.="
					</td>
					<td>";
					
					if($lignecatego['prixpresta']==-1)
					{
						$prestation.="";
					
					}else{					
						$prestation.=$lignecatego['prixpresta'];
					
					}
					
			$prestation.="
					</td>
				</tr>";
		}
				
			while($reponse=$req->fetch(PDO::FETCH_ASSOC))
			{
				// $id_departement=$reponse['id_departement'];
				$prestation.="
					<tr style='text-align:center'> 
						<td><input type='checkbox' name='checkprestaServ[]' id='checkprestaServ' value='".$reponse['id_prestation']."_".$reponse['nompresta']."'/></td>
						<td>";
						
							if($reponse['nompresta']!="")
							{
								$prestation.=$reponse['nompresta'];
							}else{
								$prestation.=$reponse['namepresta'];
							}
						
				$prestation.="
						</td>
						<td>";
						
						if($reponse['prixpresta']==-1)
						{
							$prestation.="";
						
						}else{					
							$prestation.=$reponse['prixpresta'];
						
						}
					
						
			$prestation.="
						</td>
					</tr>";
			}
			
		$prestation.="
				</tbody>
			</table>
			
			</div>
			
		";
	}else{
		echo "";
	}	
	
		$prestation.="
				
			<table class='tablesorter tablesorter2' style='width:70px'>
				<tbody>
					<tr style='text-align:center'> 
						<td>Nouvelle prestation</td>
						<td>Nouveau prix</td>
					</tr>
					
					<tr style='text-align:center'> 
						<td>
							<input type='text' name='autreprestaServ' id='autreprestaServ' value=''/>
						</td>
						
						<td>
							<input type='text' name='autreprixprestaServ' id='autreprixprestaServ' style='width:70px'/>
						</td>
					</tr>
				</tbody>
			</table>
		";
			
	echo $prestation;
	$req->closeCursor();

}
?>

</body>

</html>