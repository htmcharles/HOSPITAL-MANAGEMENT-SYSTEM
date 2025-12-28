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
		$resultats=$connexion->prepare('SELECT *FROM utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp and ph.numero=:operation');
		$resultats->execute(array(
		'operation'=>$_GET['num']	
		));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);
		if($ligne=$resultats->fetch())
		{
			$num=$ligne->numero;
			$idassu=$ligne->id_assuHosp;
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
			
			<h2>".$nomcatego."</h2>";
		
		
			$prestation.="
			
			<div id='divViewService' style='margin:10px auto 0; width:50%;'>
			
			";
		

			$prestation.="
				
				<table class='tablesorter tablesorter2' style='margin-top:10px;'>
					<tbody>
						<tr style='text-align:center'> 
							<td>Nouvelle prestation</td>
							<td>Prix unitaire</td>
							<td>Quantité</td>
						</tr>
			";
			
					for($i=0;$i<=4;$i++)
					{
			
			$prestation.="
						<tr style='text-align:center'> 
							<td>
								<input type='text' name='autreprestaServ[]' id='autreprestaServ' class='autreprestaServ' value=''/>
							</td>
							
							<td>
								<input type='text' name='autreprixprestaServ[]' id='autreprixprestaServ' class='autreprixprestaServ' style='width:70px'/>
							</td>
								
							<td>
								<input type='text' name='qteprestaServ[]' id='qteprestaServ' class='qteprestaServ' value='1' style='width:70px; text-align:center'/>
							</td>
						</tr>
			";
					}		

			$prestation.="
					</tbody>
				</table>
				
			</div>
				
			";
		}
	}else{
		echo "";
	}
			
	echo $prestation;
	$req->closeCursor();

}
?>

</body>

</html>