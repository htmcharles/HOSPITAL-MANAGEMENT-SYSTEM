<?php

session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");


	if(isset($_POST['confirmDiagno']))
	{

		$groupe = array();
		$localisation = array();
		$produit = array();
		
		foreach($_POST['groupe'] as $valeurgroupe)
		{
			$groupe[]=$valeurgroupe;
		}
		
		foreach($_POST['localisation'] as $valeurlocalisation)
		{
			$localisation[]=$valeurlocalisation;
		}
		
		foreach($_POST['produit'] as $valeurproduit)
		{
			$produit[]=$valeurproduit;
		}
		
		
		for($j=0;$j<sizeof($produit);$j++)
		{
			if($produit[$j] !="")
			{
				$getDiagno=$connexion->query("SELECT * FROM diagnostic WHERE nomdiagno LIKE '".$produit[$j]."'");
				
				$diagnoCount = $getDiagno->rowCount();
				
				// echo "SELECT * FROM diagnostic WHERE nomdiagno LIKE '".$produit[$j]."'<br/><br/>";	
				
				// echo $diagnoCount.' <br/>';
				
				if($diagnoCount ==0)
				{
					$resultAssurance=$connexion->query("INSERT INTO diagnostic (nomdiagno, groupe, localisation) VALUES ('".$produit[$j]."', '".$groupe[$j]."', '".$localisation[$j]."')");

					// echo "INSERT INTO diagnostic (id_diagno, nomdiagno, groupe, localisation) VALUES (NULL, '".$produit[$j]."', '".$groupe[$j]."', '".$localisation[$j]."')<br/>";
				}else{
					// echo "Existe Déjà <br/><br/>";
				}					
			}
		}					
			
		// echo '<script text="text/javascript">alert("Well Saved"'.$produit[0].'"");</script>';

	}

	if(isset($_POST['confirmProduct']))
	{

		$checkSousCatego = array();
		$produit = array();
		$product = array();
		$mesure = array();
		$prixassuMUSA = array();
		$prixassuMMI = array();
		$prixassuRAMA = array();
		$prixassuAutre = array();
		$prixCCO = array();
		
		 
		foreach($_POST['checkSousCatego'] as $valeursouscatego)
		{
			if($valeursouscatego=='')
			{
				$checkSousCatego[]=NULL;
			}else{
				$checkSousCatego[]=$valeursouscatego;	
			}
		}
				 
		foreach($_POST['produit'] as $valeurproduit)
		{
			$produit[]=$valeurproduit;
		}
				 
		foreach($_POST['product'] as $valeurproduct)
		{
			$product[]=$valeurproduct;
		}
		
		foreach($_POST['mesure'] as $valeurmesure)
		{
			if($valeurmesure=='')
			{
				$mesure[]=NULL;
			}else{
				$mesure[]=$valeurmesure;	
			}
		}
		
		foreach($_POST['prixassuMUSA'] as $valeurprixMUSA)
		{
			if($valeurprixMUSA=='')
			{
				$prixassuMUSA[]=-1;
			}else{
				$prixassuMUSA[]=$valeurprixMUSA;	
			}
		}
		
		foreach($_POST['prixassuMMI'] as $valeurprixMMI)
		{
			if($valeurprixMMI=='')
			{
				$prixassuMMI[]=-1;
			}else{
				$prixassuMMI[]=$valeurprixMMI;	
			}
		}
		
		foreach($_POST['prixassuRAMA'] as $valeurprixRAMA)
		{
			if($valeurprixRAMA=='')
			{
				$prixassuRAMA[]=-1;
			}else{
				$prixassuRAMA[]=$valeurprixRAMA;	
			}
		}
		
		foreach($_POST['prixassuAutre'] as $valeurprixAutre)
		{
			if($valeurprixAutre=='')
			{
				$prixassuAutre[]=-1;
			}else{
				$prixassuAutre[]=$valeurprixAutre;	
			}
		}
		
		foreach($_POST['prixCCO'] as $valeurprixCCO)
		{
			if($valeurprixCCO=="")
			{
				$prixCCO[]=-1;
			}else{
				$prixCCO[]=$valeurprixCCO;
			}
		}
		
		$nomassuArray=array();
			
		$getAssurance=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
		
		$getAssurance->setFetchMode(PDO::FETCH_OBJ);	
		$assuCount = $getAssurance->rowCount();
		
		while($ligneNomAssu=$getAssurance->fetch())
		{
			$nomassuArray[]=$ligneNomAssu->nomassurance;
			$idassuArray[]=$ligneNomAssu->id_assurance;
		}
			// var_dump($nomassuArray);
			// var_dump($idassuArray);
		
		// echo $assuCount.'<br/>';
			
			for($i=0;$i<$assuCount;$i++)
			{
				// echo $idassuArray[$i].'__'.$nomassuArray[$i].'<br/>';
					
				$presta_assu='prestations_'.$nomassuArray[$i];
				
				// echo $presta_assu.'<br/>';
								
				for($j=0;$j<sizeof($produit);$j++)
				{
					if($produit[$j] !="" AND $product[$j] =="")
					{
						$product[$j]=$produit[$j];
					}
					
					if($presta_assu == "prestations_MUSA")
					{
						if($produit[$j] !="")
						{
							$getPrestaMUSA=$connexion->query("SELECT * FROM prestations_MUSA WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
							
							$prestaCountMUSA = $getPrestaMUSA->rowCount();
							
							// echo "SELECT * FROM prestations_MUSA WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."' <br/><br/>";	
							
							// echo $prestaCountMUSA.' <br/>';
							
							if($prestaCountMUSA ==0)
							{
								// echo sizeof($produit).'--'.$product[$j].'_'.$prixassuMUSA[$j].'_'.$prixCCO[$j].'<br/><br/>';
								
								$resultAssurance=$connexion->query("INSERT INTO prestations_MUSA (id_prestation, nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$produit[$j]."', '".$product[$j]."', '".$prixassuMUSA[$j]."', '".$prixCCO[$j]."', '23', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0')");

								// echo "INSERT INTO ".$presta_assu." (id_prestation, nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$produit[$j]."', '".$product[$j]."', '".$prixassuMUSA[$j]."', '".$prixCCO[$j]."', '23', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0'); <br/>";
								
							}else{
								$updatePrixPresta=$connexion->query("UPDATE prestations_MUSA p SET p.prixpresta='".$prixassuMUSA[$j]."',p.prixprestaCCO='".$prixCCO[$j]."' WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
							}					
						}
					}else{
						if($presta_assu == "prestations_MMI" OR $presta_assu == "prestations_UR")
						{
							if($produit[$j] !="")
							{
								$getPrestaMMI=$connexion->query("SELECT * FROM prestations_MMI WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
								
								$prestaCountMMI = $getPrestaMMI->rowCount();
								
								if($prestaCountMMI ==0)
								{
									$resultAssuranceMMI=$connexion->query("INSERT INTO prestations_MMI (id_prestation, nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$produit[$j]."', '".$product[$j]."', '".$prixassuMMI[$j]."', '".$prixCCO[$j]."', '23', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0')");

								}else{
									$updatePrixPrestaMMI=$connexion->query("UPDATE prestations_MMI p SET p.prixpresta='".$prixassuMMI[$j]."',p.prixprestaCCO='".$prixCCO[$j]."' WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
								}
								
								
								$getPrestaUR=$connexion->query("SELECT * FROM prestations_UR WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
								
								$prestaCountUR = $getPrestaUR->rowCount();
								
								if($prestaCountUR ==0)
								{
									$resultAssuranceUR=$connexion->query("INSERT INTO prestations_UR (id_prestation, nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$produit[$j]."', '".$product[$j]."', '".$prixassuMMI[$j]."', '".$prixCCO[$j]."', '23', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0')");

								}else{
									$updatePrixPrestaUR=$connexion->query("UPDATE prestations_UR p SET p.prixpresta='".$prixassuMMI[$j]."',p.prixprestaCCO='".$prixCCO[$j]."' WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
								}					
							}
						}else{
							if($presta_assu == "prestations_RSSB")
							{
								if($produit[$j] !="")
								{
									$getPrestaRSSB=$connexion->query("SELECT * FROM prestations_RSSB WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
									
									$prestaCountRSSB = $getPrestaRSSB->rowCount();
									if($prestaCountRSSB ==0)
									{
										$resultAssuranceRSSB=$connexion->query("INSERT INTO prestations_RSSB (id_prestation, nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$produit[$j]."', '".$product[$j]."', '".$prixassuRAMA[$j]."', '".$prixCCO[$j]."', '23', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0')");

									}else{
										$updatePrixPrestaRSSB=$connexion->query("UPDATE prestations_RSSB p SET p.prixpresta='".$prixassuRAMA[$j]."',p.prixprestaCCO='".$prixCCO[$j]."' WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
									}					
								}
							}else{
								if($presta_assu == "prestations_PRIVATE")
								{
									if($produit[$j] !="")
									{
										$getPrestaPRIVATE=$connexion->query("SELECT * FROM prestations_PRIVATE WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
										
										$prestaCountPRIVATE = $getPrestaPRIVATE->rowCount();
										if($prestaCountPRIVATE ==0)
										{
											$resultAssurancePRIVATE=$connexion->query("INSERT INTO prestations_PRIVATE (id_prestation, nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$produit[$j]."', '".$product[$j]."', '".$prixCCO[$j]."', '".$prixCCO[$j]."', '23', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0')");

										}else{
											$updatePrixPrestaPRIVATE=$connexion->query("UPDATE prestations_PRIVATE p SET p.prixpresta='".$prixCCO[$j]."',p.prixprestaCCO='".$prixCCO[$j]."' WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
										}					
									}
								}else{
									if($presta_assu != "prestations_MUSA" AND $presta_assu != "prestations_MMI" AND $presta_assu != "prestations_UR" AND $presta_assu != "prestations_RSSB" AND $presta_assu != "prestations_PRIVATE")
									{
										if($produit[$j] !="")
										{
											$getPresta=$connexion->query("SELECT * FROM ".$presta_assu." WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
											
											$prestaCount = $getPresta->rowCount();
											if($prestaCount ==0)
											{
												$resultAssurance=$connexion->query("INSERT INTO ".$presta_assu." (id_prestation, nompresta, namepresta, prixpresta, prixprestaCCO, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$produit[$j]."', '".$product[$j]."', '".$prixassuAutre[$j]."', '".$prixCCO[$j]."', '23', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0')");

											}else{
												$updatePrixPresta=$connexion->query("UPDATE ".$presta_assu." p SET p.prixpresta='".$prixassuAutre[$j]."',p.prixprestaCCO='".$prixCCO[$j]."' WHERE nompresta LIKE '".$produit[$j]."' OR namepresta LIKE '".$product[$j]."'");
											}					
										}
									}
								}
							}
						}					
					}	
				}					
			}
		
		// echo '<script text="text/javascript">alert("Well Saved"'.$produit[0].'"");</script>';

	}

	echo '<script text="text/javascript">document.location.href="messages.php?ecrire=ok&francais=francais"</script>';


?>