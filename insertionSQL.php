<?php

session_start();
include("connect.php");
include("connectLangues.php");
include("serialNumber.php");

$pageTable='';

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

    if(isset($_POST['confirmExam']))
    {

        $checkExam = array();
        $checkSousCatego = array();
        $valeur = array();
        $min = array();
        $max = array();
        $mesure = array();


        $autreExam = array();
        $autreSousCatego = array();
        $autrevaleur = array();
        $autremin = array();
        $autremax = array();
        $autremesure = array();


        foreach($_POST['checkExam'] as $valeurexam)
        {
            $checkExam[]=$valeurexam;
        }

        foreach($_POST['checkSousCatego'] as $valeursouscatego)
        {
            if($valeursouscatego=='')
            {
                $checkSousCatego[]=NULL;
            }else{
                $checkSousCatego[]=$valeursouscatego;
            }
        }

        foreach($_POST['valeur'] as $valeurV)
        {
            if($valeurV=='')
            {
                $valeur[]=NULL;
            }else{
                $valeur[]=$valeurV;
            }
        }

        foreach($_POST['min'] as $valeurmin)
        {
            $min[]=$valeurmin;
        }

        foreach($_POST['max'] as $valeurmax)
        {
            $max[]=$valeurmax;
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

        /*---------------Autre Exam-----------*/

        foreach($_POST['autreExam'] as $valeurautreexam)
        {
            $autreExam[]=$valeurautreexam;
        }

        foreach($_POST['autreSousCatego'] as $valeurautresouscatego)
        {
            if($valeurautresouscatego=='')
            {
                $autreSousCatego[]=NULL;
            }else{
                $autreSousCatego[]=$valeurautresouscatego;
            }
        }

        foreach($_POST['autrevaleur'] as $valeurautreV)
        {
            if($valeurautreV=='')
            {
                $autrevaleur[]=NULL;
            }else{
                $autrevaleur[]=$valeurautreV;
            }
        }

        foreach($_POST['autremin'] as $valeurautremin)
        {
            $autremin[]=$valeurautremin;
        }

        foreach($_POST['autremax'] as $valeurautremax)
        {
            $autremax[]=$valeurautremax;
        }

        foreach($_POST['autremesure'] as $valeurautremesure)
        {
            if($valeurautremesure=='')
            {
                $autremesure[]=NULL;
            }else{
                $autremesure[]=$valeurautremesure;
            }
        }




        $nomassuArray=array();
        $idassuArray=array();

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


        // $highNumber = max(sizeof($checkExam),sizeof($autreExam));

        for($i=0;$i<$assuCount;$i++)
        {
            $presta_assu='prestations_'.$nomassuArray[$i];

            for($j=0;$j<sizeof($checkExam);$j++)
            {
                if($checkExam[$j] !="")
                {
                    $getPresta=$connexion->query("SELECT * FROM ".$presta_assu." WHERE nompresta LIKE '".$checkExam[$j]."' OR namepresta LIKE '".$checkExam[$j]."'");

                    $getPresta->setFetchMode(PDO::FETCH_OBJ);

                    $prestaCount = $getPresta->rowCount();

                    if($prestaCount ==0)
                    {
                        $insertPresta=$connexion->query("INSERT INTO ".$presta_assu." (id_prestation, nompresta, namepresta, prixpresta, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$checkExam[$j]."', '".$checkExam[$j]."', '-1', '12', '".$checkSousCatego[$j]."', '".$mesure[$j]."', '0')");

                    }else{
                        $updatePresta=$connexion->query("UPDATE ".$presta_assu." p SET p.id_souscategopresta='".$checkSousCatego[$j]."',p.mesure='".$mesure[$j]."' WHERE nompresta LIKE '".$checkExam[$j]."' OR namepresta LIKE '".$checkExam[$j]."'");

                        $getValeur=$connexion->query("SELECT * FROM valeurs_lab WHERE valeur ='".$valeur[$j]."' AND nomexam LIKE '".$checkExam[$j]."'");

                        $valeurCount = $getValeur->rowCount();

                        if($valeurCount!=0)
                        {
                            $updateValeur=$connexion->query("UPDATE valeurs_lab v SET v.min_valeur='".$min[$j]."',v.max_valeur='".$max[$j]."',v.nomexam='".$checkExam[$j]."' WHERE v.valeur ='".$valeur[$j]."' AND v.nomexam LIKE '".$checkExam[$j]."'");

                        }

                        // echo '<script text="text/javascript">alert("Already Exist \n '.$presta_assu.' \n Nom exam :'.$checkExam[$j].' \n Valeur :'.$valeur[$j].' \n Min :'.$min[$j].' \n Max :'.$max[$j].'");</script>';
                    }
                }
            }

            for($j=0;$j<sizeof($autreExam);$j++)
            {
                if($autreExam[$j] !="")
                {
                    $getPresta=$connexion->query("SELECT * FROM ".$presta_assu." WHERE nompresta LIKE '".$autreExam[$j]."' OR namepresta LIKE '".$autreExam[$j]."'");

                    $prestaCount = $getPresta->rowCount();

                    $getPresta->setFetchMode(PDO::FETCH_OBJ);

                    if($prestaCount ==0)
                    {
                        $insertPresta=$connexion->query("INSERT INTO ".$presta_assu." (id_prestation, nompresta, namepresta, prixpresta, id_categopresta, id_souscategopresta, mesure, statupresta) VALUES (NULL, '".$autreExam[$j]."', '".$autreExam[$j]."', '-1', '12', '".$autreSousCatego[$j]."', '".$autremesure[$j]."', '0')");

                    }else{
                        $updatePresta=$connexion->query("UPDATE ".$presta_assu." p SET p.id_souscategopresta='".$autreSousCatego[$j]."',p.mesure='".$autremesure[$j]."' WHERE nompresta LIKE '".$autreExam[$j]."' OR namepresta LIKE '".$autreExam[$j]."'");

                        $getValeur=$connexion->query("SELECT * FROM valeurs_lab WHERE valeur ='".$autrevaleur[$j]."' AND nomexam LIKE '".$autreExam[$j]."'");

                        $valeurCount = $getValeur->rowCount();

                        if($valeurCount!=0)
                        {
                            $updateValeur=$connexion->query("UPDATE valeurs_lab v SET v.min_valeur='".$autremin[$j]."',v.max_valeur='".$autremax[$j]."',v.nomexam='".$autreExam[$j]."' WHERE v.valeur ='".$autrevaleur[$j]."' AND v.nomexam LIKE '".$autreExam[$j]."'");

                        }
                        // echo '<script text="text/javascript">alert("Already Exist \n '.$presta_assu.' \n Nom exam :'.$autreExam[$j].' \n Valeur :'.$autrevaleur[$j].' \n Min :'.$min[$j].' \n Max :'.$max[$j].'");</script>';
                    }
                }
            }

        }

        // var_dump($valeur);
        // var_dump($min);
        // var_dump($max);
        // var_dump($checkExam);

        // echo '------------------';

        // var_dump($autrevaleur);
        //var_dump($autremin);
        // var_dump($autremax);
        // var_dump($autreExam);


        for($v=0;$v<sizeof($checkExam);$v++)
        {
            $getValeur=$connexion->query("SELECT * FROM valeurs_lab WHERE valeur ='".$valeur[$v]."' AND nomexam LIKE '".$checkExam[$v]."'");

            $valeurCount = $getValeur->rowCount();

            if($valeurCount==0)
            {
                if($checkExam[$v] !="")
                {
                    $insertValeur=$connexion->query("INSERT INTO valeurs_lab (id_valeur, valeur, min_valeur, max_valeur, nomexam) VALUES (NULL, '".$valeur[$v]."', '".$min[$v]."', '".$max[$v]."', '".$checkExam[$v]."')");

                    echo '<script text="text/javascript">alert("Saved \n Nom exam :'.$checkExam[$v].' \n Valeur :'.$valeur[$v].' \n Min :'.$min[$v].' \n Max :'.$max[$v].'");</script>';
                }
            }
        }


        for($v=0;$v<sizeof($autreExam);$v++)
        {
            $getValeur=$connexion->query("SELECT * FROM valeurs_lab WHERE valeur ='".$autrevaleur[$v]."' AND nomexam LIKE '".$autreExam[$v]."'");

            $valeurCount = $getValeur->rowCount();

            if($valeurCount==0)
            {
                if($autreExam[$v] !="")
                {
                    $insertValeur=$connexion->query("INSERT INTO valeurs_lab (id_valeur, valeur, min_valeur, max_valeur, nomexam) VALUES (NULL, '".$autrevaleur[$v]."', '".$autremin[$v]."', '".$autremax[$v]."', '".$autreExam[$v]."')");

                    echo '<script text="text/javascript">alert("Saved \n Nom exam :'.$autreExam[$v].' \n Valeur :'.$autrevaleur[$v].' \n Min :'.$autremin[$v].' \n Max :'.$autremax[$v].'");</script>';
                }
            }
        }

       // echo '<script text="text/javascript">alert("Well Saved"'.$checkExam[0].'"");</script>';
        echo '<script text="text/javascript">document.location.href="examedit.php?english=english"</script>';

    }

    if(isset($_POST['confirmAssu']))
    {
        $nomAssu = array();
        $prixref = array();

        foreach($_POST['nomAssu'] as $valeurassu)
        {
            $nomAssu[]=$valeurassu;
        }

        foreach($_POST['prixref'] as $valeurprix)
        {
            $prixref[]=$valeurprix;
        }

        for($i=0;$i<sizeof($nomAssu);$i++) {


            if ($nomAssu[$i] != "") {

                $nomAssu[$i] = trim($nomAssu[$i]);

                $nomAssu[$i] = str_replace(" ","_", $nomAssu[$i]);


                $getAssurance = $connexion->query("SELECT *FROM assurances a WHERE a.nomassurance LIKE '" . $nomAssu[$i] . "' ORDER BY a.id_assurance");

                $getAssurance->setFetchMode(PDO::FETCH_OBJ);
                $assuCount = $getAssurance->rowCount();

                if($assuCount==0) {
                    echo $nomAssu[$i].' > New <br/>';

                    $insertNewAssu = $connexion->query("INSERT INTO assurances (nomassurance) VALUES('" . strtoupper($nomAssu[$i]) . "')");

                    $createNewAssu = $connexion->query("CREATE TABLE prestations_" . $nomAssu[$i] . " LIKE prestations_" . $prixref[$i] . "");

                    $insertAllNewPrestaAssu = $connexion->query("INSERT INTO prestations_" . $nomAssu[$i] . " SELECT * FROM prestations_" . $prixref[$i] . "");

                    $insertReportName = $connexion->query("INSERT INTO rn_table (rn_type,rn_id_l,rn_id_n) VALUES('" . strtoupper($nomAssu[$i]) . "','A','0')");

                }else{
                    echo $nomAssu[$i].' > Old <br/>';
                }
                 echo '<script text="text/javascript">document.location.href="assurances.php?ecrire=ok&francais=francais"</script>';
            }
        }
    }

    if(isset($_POST['editAssu']) AND $_POST['newNomAssu']!="") {

        $oldIdAssu = $_POST['oldIdAssu'];
        $oldNomAssu = $_POST['oldNomAssu'];
        $newNomAssu = $_POST['newNomAssu'];

        $getAssurance = $connexion->query("SELECT *FROM assurances a WHERE a.id_assurance = '" . $oldIdAssu . "' ORDER BY a.id_assurance");

        $getAssurance->setFetchMode(PDO::FETCH_OBJ);
        $assuCount = $getAssurance->rowCount();

        if ($assuCount != 0) {

            $newNomAssu = trim($newNomAssu);

            $newNomAssu = str_replace(" ","_", $newNomAssu);

            $updateNomAssu = $connexion->query("UPDATE assurances a SET a.nomassurance='" . $newNomAssu . "' WHERE a.id_assurance = '" . $oldIdAssu . "'");


            $getRn = $connexion->query("SELECT *FROM rn_table r WHERE r.rn_type LIKE '" . $oldNomAssu . "'");

            $getRn->setFetchMode(PDO::FETCH_OBJ);
            $rnCount = $getRn->rowCount();

            if ($rnCount != 0) {
                $updateRn = $connexion->query("UPDATE rn_table r SET r.rn_type='" . $newNomAssu . "' WHERE r.rn_type LIKE '" . $oldNomAssu . "'");

                $updateRn = $connexion->query("RENAME TABLE prestations_" . $oldNomAssu . " TO prestations_" . $newNomAssu . "");

//                echo "RENAME TABLE prestations_" . $oldNomAssu . " TO prestations_" . $newNomAssu . "";
            } else {

                $insertNewRn = $connexion->query("INSERT INTO rn_table (rn_type, rn_id_l, rn_id_n) VALUE ('" . $newNomAssu . "', 'A','0')");

            }

            $updateNomAssuBill = $connexion->query("UPDATE bills b SET b.nomassurance='" . $newNomAssu . "' WHERE b.nomassurance = '" . $oldNomAssu . "'");

            $updateNomAssuConsult = $connexion->query("UPDATE consultations c SET c.assuranceConsuName='" . $newNomAssu . "' WHERE c.assuranceConsuName = '" . $oldNomAssu . "'");
        }

        if(isset($_GET['page'])){
            $pageTable='&page='.$_GET['page'].'#tableAssu';
        }else{
            $pageTable='#tableAssu';
        }
         echo '<script text="text/javascript">document.location.href="assurances.php?ecrire=ok&francais=francais'.$pageTable.'"</script>';
    }


       
?>