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

if(isset($_POST['souscategorie']))
{
	$prestation="";

	$souscatego_id=$_POST['souscategorie'];

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


		$req=$connexion->prepare('SELECT *FROM souscategopresta scp, '.$presta_assu.' p WHERE scp.catego_id=12 AND scp.catego_id=p.id_categopresta AND scp.catego_id=p.id_categopresta AND p.id_souscategopresta=scp.souscatego_id AND p.id_souscategopresta=:souscatego AND p.nompresta!=:nompresta ORDER BY p.nompresta ASC');
		$req->execute(array(
		'souscatego'=>$souscatego_id,
		'nompresta'=>$_GET['presta']
		));
		

	$comptPresta=$req->rowCount();
	$nomcatego="";
	
	if($comptPresta!=0)
	{
		if($lignecatego=$req->fetch(PDO::FETCH_ASSOC))
		{
			$nomcatego=$lignecatego['nomsouscatego'];
			
			$prestation.="
			
			<h2 style='margin:10px;'>".$lignecatego['nomsouscatego']."</h2>";
		
		
			$prestation.="
			
			<div id='divViewExam' style='margin:20px;' align='center'>
			
			";
		

			$prestation.="
			
			<select style='margin:auto' multiple='multiple' name='checkprestaExam[]' class='chosen-select' id='checkprestaExam'>
				
				<option value='".$lignecatego['id_prestation']."'>".$lignecatego['nompresta']."</option>
			
			";
			
			while($lignePrestaExamen=$req->fetch(PDO::FETCH_ASSOC))//on recupere la liste des éléments
			{		
			
				$prestation.="
					<option value='".$lignePrestaExamen['id_prestation']."'>".$lignePrestaExamen['nompresta']."</option>
				";
			}	
			
			$prestation.="
			</select>
			";
		
		}
		/* 
		$prestation.="
			
			<table class='tablesorter' id='typeconsu'>
				<thead> 
					<tr>
						<th>Actions</th>
						<th>Prestations</th>
					</tr> 
				</thead>
				
				<tbody>
		";
		
			$prestation.="
				<tr style='text-align:center'> 
					<td><input type='checkbox' name='checkprestaExam[]' id='checkprestaExam' value='".$lignecatego['id_prestation']."_".$lignecatego['nompresta']."'/></td>
					<td>";
					
						if($lignecatego['nompresta']!="")
						{
							$prestation.=$lignecatego['nompresta'];
						}else{
							$prestation.=$lignecatego['namepresta'];
						}
					
			$prestation.="
					</td>
				</tr>";
					
			while($reponse=$req->fetch(PDO::FETCH_ASSOC))
			{
				// $id_departement=$reponse['id_departement'];
				$prestation.="
					<tr style='text-align:center'> 
						<td><input type='checkbox' name='checkprestaExam[]' id='checkprestaExam' value='".$reponse['id_prestation']."_".$reponse['nompresta']."'/></td>
						<td>";
						
							if($reponse['nompresta']!="")
							{
								$prestation.=$reponse['nompresta'];
							}else{
								$prestation.=$reponse['namepresta'];
							}
						
				$prestation.="
						</td>
					</tr>";
			}
			
			$prestation.="
				</tbody>
			</table>
			
			";
		 */
		
		$prestation.="	
			</div>
			
		";
	}else{
		if($souscatego_id=='spermo')
		{
			$prestation.="
				
				
			<div id='divSpermo' style='margin:40px auto 0; text-align:center;background:#eee;width:60%;'>

				<table align='center' style='margin:20px auto; display:inline;'>
					<tr>
						<td style='padding:5px;text-align:center;'>
							EXAMEN MACROSCOPIQUES
						</td>
					</tr>
					
					<tr>
						<td style='padding:5px;text-align:center;'>
							<table align='center' style='margin:20px auto; display:inline;'>
											
								<tr>
									<td style='padding:15px;text-align:right;'>
										Volume
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='volume' value='' placeholder='Entrez volume ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Densité
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='densite' value='' placeholder='Entrez densité ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Viscosité
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='viscosite' value='' placeholder='Entrez viscosité ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										PH
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='ph' value='' placeholder='Entrez PH ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Aspect
									</td>
									<td style='text-align:center;'>
										<input style='margin:0' type='text' name='aspect' value='' placeholder='Entrez aspect ici...'/>
									</td>
								</tr>
							</table>
						</td>
					</tr>						
						
					<tr>
						<td style='padding:5px;text-align:center;'>
							EXAMEN MICROSCOPIQUES
						</td>
					</tr>
					
					<tr>
						<td style='padding:5px;text-align:center;'>
							<table align='center' style='margin:20px auto; display:inline;'>
											
								<tr>
									<td style='padding:15px;text-align:right;'>
										Examen direct
									</td>
									<td style='text-align:left;'>
										<input style='margin:0' type='text' name='examdirect' value='' placeholder='Entrez examen direct ici...'/>
									</td>
								</tr>
								
								<tr>
									<td style='text-align:center;width:50px;' colspan=2>
									
										<table class='tablesorter'style='background:#fff;'>
													
											<tr>
												<td style='padding:15px;text-align:right;background:#eee;'>
													Mobilité
												</td>
												
												<td style='text-align:right;'>
													0h après emission
												</td>
												
												<td style='text-align:left	;'>
													<input style='margin:0;width:150px;' type='text' name='zeroheureafter' value=''/>
												</td>
											</tr>		
											<tr>
												<td style='padding:15px;text-align:right;background:#eee;'></td>
												
												<td style='text-align:right;'>
													1h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='uneheureafter' value=''/>
												</td>
											</tr>		
											<tr>
												<td style='padding:15px;text-align:right;background:#eee;'></td>
												
												<td style='text-align:right;'>
													2h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='deuxheureafter' value=''/>
												</td>
											</tr>		
											<tr>
												<td style='padding:15px;text-align:right;background:#eee;'></td>
												
												<td style='text-align:right;'>
													3h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='troisheureafter' value=''/>
												</td>
											</tr>		
											<tr>
												<td style='padding:15px;text-align:right;background:#eee;'></td>
												
												<td style='text-align:right;'>
													4h après emission
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='quatreheureafter' value=''/>
												</td>
											</tr>
										</table>
										
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Numeration
									</td>
									
									<td>
									
										<table class='tablesorter tablesorter3' style='background:#eee;'>
													
											<tr>
												<td style='text-align:left;'>
													<input style='margin:0;width:200px;' type='text' name='numeration' value='' placeholder='Entrez numeration ici...'/>
												</td>
												
												<td style='border-right:none'>
													V.N
												</td>
												
												<td style='padding:5px;'>
													<input style='margin:0;width:150px;' type='text' name='vn' value='' placeholder='.......................................'/>
												</td>
											</tr>		
											
										</table>
									</td>
									
								</tr>
								
								<tr>
									
									<td style='text-align:center;' colspan=2>
										
										<table class='tablesorter' style='background:#fff;'>
													
											<tr>
												<td style='padding:15px;text-align:right;background:#eee;'>
													Spermocytogramme
												</td>
												
												<td style='text-align:right;'>
													Forme typique
												</td>
												
												<td style='text-align:left	;'>
													<input style='margin:0;width:150px;' type='text' name='formtypik' value=''/> %
												</td>
											</tr>		
											<tr>
												<td style='padding:15px;text-align:right;background:#eee;'></td>
												
												<td style='text-align:right;'>
													Forme atypique
												</td>
												
												<td style='text-align:left;'>
													<input style='margin:0;width:150px;' type='text' name='formatypik' value=''/> %
												</td>
											</tr>
										</table>
									</td>
								</tr>
								
								<tr>
									<td style='padding:15px;text-align:right;'>
										Autre
									</td>
									<td style='text-align:left;'>
										<input style='margin:0' type='text' name='autre' value=''/>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					<tr>
						<td style='padding-top:45px;text-align:center;'>
							<table align='center' style='margin:20px auto; display:inline;'>
											
								<tr>
									<td style='padding:15px;text-align:right;'>
										CONCLUSION
									</td>
									<td style='text-align:left;'>
										<input style='margin:0' type='text' name='conclusion' value='' placeholder='Entrez la conclusion ici...'/>
									</td>
								</tr>
								
							</table>
						</td>
					</tr>
					
				</table>
			
			</div>
			
			";
		}else{
			echo "";
		}
	}	
	
		if($souscatego_id!='spermo' AND $souscatego_id!='0')
		{
			$prestation.="
					
				<table class='tablesorter tablesorter2' style='width:70px'>
					<tbody>
						<tr style='text-align:center'> 
							<td colspan=5>Autres examens (".$nomcatego.")</td>
						</tr>
						
						<tr style='text-align:center'> 
			";
						for($x=1;$x<=5;$x++)
						{
							$prestation.="
						
							<td>
								<input type='text' name='autreprestaExam[]' id='autreprestaExam' value='' style='width:200px'/>
							</td>
							";
						}
						
			$prestation.="
			
						</tr>
					</tbody>
				</table>
			";
		}
		
	echo $prestation;
	$req->closeCursor();

}
?>

	<script type="text/javascript" src="js/jquery-2.2.0.min.js"></script>
	<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript">
	
		$('#checkprestaExam').chosen({width:"350px", search_contains: true, inherit_select_classes: true});
	</script>
	
</body>

</html>