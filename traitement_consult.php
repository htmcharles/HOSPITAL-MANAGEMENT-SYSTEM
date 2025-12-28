<?php
session_start();
include("connect.php");

try
{
	$heure=date("H");
	$min=date("i");


	$heureToday=$heure.':'.$min;

	$annee = date('Y').'-'.date('m').'-'.date('d');

	//-------Creation fiche par le medecin
	
	if(isset($_GET['idassuconsu']))
	{
		$idassu=$_GET['idassuconsu'];
										
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

		
	}
	
	if(!isset($_GET['deleteMedmotif']))
	{
		if(!isset($_GET['deleteMedconsu']))
		{
			if(!isset($_GET['deleteMedinf']))
			{
				if(!isset($_GET['deleteMedlabo']))
				{
					if(!isset($_GET['deleteMedradio']))
					{
						if(!isset($_GET['deleteMedconsom']))
						{
							if(!isset($_GET['deleteMedmedoc']))
							{
								if(!isset($_GET['deleteMedkine']))
								{
									if(!isset($_GET['deleteMedPsy']))
									{

									if(!isset($_GET['deleteMedortho']))
									{
										if(!isset($_GET['deleteMedsurge']))
										{
											if(!isset($_GET['deleteMedpredia']))
											{
												if(!isset($_GET['deleteMedpostdia']))
												{
													$idMedecin=$_SESSION['id'];
													
													if($_POST['typeconsult']!="autretypeconsult")
													{
														
														$typeconsult=$_POST['typeconsult'];
														$autretypeconsult='';
												
														$prixtypeconsult=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
														$prixtypeconsult->execute(array(
														'idPresta'=>$typeconsult
														));

														$prixtypeconsult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

														$comptPrixtypeconsult=$prixtypeconsult->rowCount();

														if($comptPrixtypeconsult!=0)
														{
															if($lignePrixtypeconsult=$prixtypeconsult->fetch())
															{
																$prixConsultCCO=$lignePrixtypeconsult->prixprestaCCO;
																$prixConsult=$lignePrixtypeconsult->prixpresta;
																$prixautreConsult=0;
															}
														}
													
													}else{
														$typeconsult=NULL;
														$autretypeconsult=$_POST['areaAutretypeconsult'];
														$prixConsult=0;
														$prixautreConsult=0;
													}

													// echo $typeconsult;
													
													if(isset($_GET['idconsuNext']))
													{
														$idconsuAdd = $_GET['idconsuNext'];
													}else{
													
														if(isset($_GET['idconsu']))
														{
															$idconsuAdd = $_GET['idconsu'];
														}else{
													
															if(isset($_GET['idconsult']))
															{
																$idconsuAdd = $_GET['idconsult'];
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

	
	if(isset($_GET['num']))
	{
		$resultats=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE u.id_u=p.id_u and p.numero=:operation');
		$resultats->execute(array(
		'operation'=>$_GET['num']	
		));
		
		$resultats->setFetchMode(PDO::FETCH_OBJ);
		while($ligne=$resultats->fetch())
		{
			$num=$ligne->numero;
			$bill=$ligne->bill;
			$idP=$ligne->id_u;
		}
		$resultats->closeCursor();

	}				
					
	$idmed=$_SESSION['id'];
	$num=$_GET['num'];
	
	$idtypeconsult=$_GET['idtypeconsult'];	
	$motif="";
	$anamnese=$_POST['anamnese'];
	$clihist=$_POST['clihist'];
	$etatpa=$_POST['etatpa'];
	$antec=$_POST['antec'];
	$allergie=$_POST['allergie'];
	$examcli=$_POST['examcli'];
	$sisympt=$_POST['sisympt'];
	$recomm=$_POST['recomm'];
	$poids=$_POST['poids'];
	$taille=$_POST['taille'];
	$tempera=$_POST['tempera'];
	$pouls=$_POST['pouls'];
	$oxgen=$_POST['oxgen'];
	$tensionart=$_POST['tensionart'];	
	
	if(isset($_POST['hospitalized']))
	{
		$hospitalized=true;
	}else{
		$hospitalized=NULL;
	}
		$motifhospitalized=$_POST['motifhospitalized'];
	
	// echo '<script type="text/javascript"> alert("Motif Hospi : '.$motifhospitalized.'");</script>';
	
	if(isset($_POST['physio']))
	{
		$physio=true;
	}else{
		$physio=NULL;
	}
	
	if(isset($_POST['motifphysio']))
	{
		$motifphysio=$_POST['motifphysio'];
	}else{
		$motifphysio="";
	}
	
	if(isset($_POST['transfer']))
	{
		$transfer=true;
	}else{
		$transfer=NULL;
	}
		$motiftransfer=$_POST['motiftransfer'];
	
	
	$idconsuAdd=$_GET['idconsu'];
	
	if($_POST['joursrdv']!="00")
	{
		$dateRdv=$_POST['anneerdv'].'-'.$_POST['moisrdv'].'-'.$_POST['joursrdv'];
	
		$heureRdv=$_POST['heurerdv'].':'.$_POST['minrdv'];
		
	}else{
		$dateRdv="0000-00-00";
		$heureRdv="";	
	}
	
	
	$idRad = array();
	$resultatsRad = array();
	
	if(isset($_POST['idRad']))
	{
		foreach($_POST['idRad'] as $idR)
		{
			$idRad[] = $idR;
		}
			
		foreach($_POST['resultatsRad'] as $echo)
		{
			$resultatsRad[] = $echo;
		}
		
		for($i=0;$i<sizeof($idRad);$i++)
		{			
			$resultats=$connexion->prepare('UPDATE med_radio mr SET mr.dateradio=:dateradio, mr.resultatsRad=:resultatsRad, mr.numero=:num, mr.id_uX=:idmed, mr.radiofait=1 WHERE id_medradio=:modifierRadio');
					
			$resultats->execute(array(
			'dateradio'=>$_POST['dateconsu'],
			'resultatsRad'=>$resultatsRad[$i],
			'num'=>$_GET['num'],
			'idmed'=>$idmed,
			'modifierRadio'=>$idRad[$i]
			
			));
		}
		
	}
		
/* 
	if(isset ($_POST['soins']))
	{
		$soins=nl2br($_POST['soins']);
		$soinsfait=0;
		$soinsexecuter='';
	}
	 */
	if(isset ($_POST['examen']))
	{
		$examen=nl2br($_POST['examen']);
		$examenfait=0;
		$result='';
	}
	
	if(isset ($_POST['radio']))
	{
		$radio=nl2br($_POST['radio']);
		$radiofait=0;
		$result='';
	}
	


	
 	if(isset($_POST['savebtn']))
	{ 

		// echo '*Le motif: '.$motif.'<br/>*L\'etat du patient: '.$etatpa.'<br/>*Les recommandations: '.$recomm.'<br/>*Les soins: '.$soins.'<br/>*Les examens à faire:'.$examen.'<br/>*Code Medecin: '.$codemed.'<br/>*Numero Patient: '.$num.'<br/>*Code de l\'infirmier: '.$codeI;
		
		if($_POST['typeconsult']!="autretypeconsult")
		{
			$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,heureconsu=:heureconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,:oxgen=:oxgen,id_uM=:idMed,numero=:num,id_typeconsult=:typeconsu,prixautretypeconsult=:prixautretypeconsu,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'heureconsu'=>$heureToday,
			'motif'=>$motif,
			'anamnese'=>$anamnese,
			'clihist'=>$clihist,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'allergie'=>$allergie,
			'examcli'=>$examcli,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'idMed'=>$idmed,
			'num'=>$num,
			'typeconsu'=>$typeconsult,
			'prixautretypeconsu'=>$prixautreConsult,
			'hospitalized'=>$hospitalized,
			'motifhospitalized'=>$motifhospitalized,
			'physio'=>$physio,
			'motifphysio'=>$motifphysio,
			'transfer'=>$transfer,
			'motiftransfer'=>$motiftransfer,
			'modifierConsu'=>$idconsuAdd
			
			));
		
		}else{
			
			$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,heureconsu=:heureconsu,,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,id_uM=:idMed,numero=:num,idtypeconsult=:typeconsu,autretypeconsult=:autretypeconsu,prixautretypeconsult=:prixautretypeconsu,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'heureconsu'=>$heureToday,
			'motif'=>$motif,
			'anamnese'=>$anamnese,
			'clihist'=>$clihist,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'allergie'=>$allergie,
			'examcli'=>$examcli,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'idMed'=>$idmed,
			'num'=>$num,
			'typeconsu'=>$typeconsult,
			'autretypeconsu'=>$autretypeconsult,
			'prixautretypeconsu'=>$prixautreConsult,
			'hospitalized'=>$hospitalized,
			'motifhospitalized'=>$motifhospitalized,
			'physio'=>$physio,
			'motifphysio'=>$motifphysio,
			'transfer'=>$transfer,
			'motiftransfer'=>$motiftransfer,
			'modifierConsu'=>$idconsuAdd
			
			));
		
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$idconsuAdd.'&idassuconsu='.$idassu.'&showfiche=ok&english='.$_GET['english'].'#fichepatient";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$idconsuAdd.'&idassuconsu='.$idassu.'&showfiche=ok&francais='.$_GET['francais'].'#fichepatient";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$idconsuAdd.'&idassuconsu='.$idassu.'&showfiche=ok#fichepatient";</script>';
			}
		}
		
							

	}
	
 	
 	if(isset($_POST['soinsbtn']))
	{
		$id_consu=$_POST['idConsult'];
			
		if(isset ($_POST['datesoins']))
		{
			$datesoins=$_POST['datesoins'];

		}else{
			$datesoins='';
		}
	
		$poids=$_POST['poids'];
		$taille=$_POST['taille'];
		$tempera=$_POST['tempera'];
		$tensionart=$_POST['tensionart'];
		$pouls=$_POST['pouls'];
		$oxgen=$_POST['oxgen'];
		$soins=nl2br($_POST['soins']);
		$soinsfait=1;
		$soinsexecuter=nl2br($_POST['soinsfait']);

		$codeinf=$_SESSION['codeI'];
		$num=$_POST['idopereInfPa'];
		
		// echo '*ID de la consult: '.$id_consu.'<br/>*Date des soins: '.$datesoins.'<br/>*Les soins: '.$soins.'<br/>*Le poids:'.$poids.'<br/>*La taille:'.$taille.'<br/>*Temperature: '.$tempera.'<br/>*Tension arterielle: '.$tensionart.'<br/>*Pouls: '.$pouls.'<br/>*Code Infirmier: '.$codeinf.'<br/>*Numero Patient: '.$num.'<br/>';

		if($_POST['soinsfait']!="")
		{
			$resultats=$connexion->prepare('UPDATE consultations SET id_consu=:idconsu,dateconsu=:dateconsu,heuresoins=:heuresoins,datesoins=:datesoins,soins=:soins,soinsfait=:soinsfait,soinsexecuter=:soinsexecuter,codeinfirmier=:codeinf WHERE id_consu=:idconsu');
					
			$resultats->execute(array(
			'idconsu'=>$id_consu,
			'dateconsu'=>$_POST['dateconsu'],
			'heuresoins'=>$heureToday,
			'datesoins'=>$datesoins,
			'soins'=>$soins,
			'soinsfait'=>$soinsfait,
			'soinsexecuter'=>$soinsexecuter,
			'codeinf'=>$codeinf
			));
					
			echo '<script type="text/javascript"> alert("Vous venez de soigner un malade");</script>';
			
			echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&soinsPa=ok";</script>';

		}else{
			echo '<script type="text/javascript"> alert("Vous n\'avez pas traiter le patient");</script>';
			
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$_POST['dateconsu'].'&consu=ok"</script>';
		}
	}


	// echo $_POST['idConsult'];
	
 	if(isset($_POST['resultbtn']))
	{
		
		// echo '<script language="javascript"> alert("KEEP CALM!!");</script>';
		
		$codelabo=$_SESSION['codeL'];
		$num=$_POST['idopereResultatPa'];
		$id_consu=$_POST['idConsult'];
		$dateresult=$_POST['dateresultat'];
		$exam=nl2br($_POST['examen']);

		$examenfait=1;
		
		/* echo '*ID de la consult: '.$id_consu.'<br/>*Date des résultats: '.$dateresult.'<br/>*Le nom de l\'examen: '.$exam.'<br/>*Code Laborantin: '.$codelabo.'<br/>*Numero Patient: '.$num.'<br/>*Résultats: '; 
 */
		

		if(!($_FILES['result']['error']== UPLOAD_ERR_NO_FILE))
		{
			$extAuthorisedDoc=array('.png','.gif','.jpg','.jpeg','.pdf','.doc','.docx','.PNG','.GIF','.JPG','.JPEG','.PDF','.DOC','.DOCX');

			$extensionDoc=strrchr($_FILES['result']['name'],'.');
			//echo $extensionPic.'<br/>';
			
			if(!in_array($extensionDoc,$extAuthorisedDoc))
			{
				echo '<script language="javascript"> alert("Ce n\'est pas le type de document qui peut être téléchargé!");</script>';
				echo '<script language="javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$dateresult.'&consu=ok"</script>';
				
			}else{
			
				if($_FILES['result']['error']== UPLOAD_ERR_FORM_SIZE)
				{
					echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
					echo '<script language="javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$dateresult.'&consu=ok"</script>';
				
				}else{
					$nomDoc=time().strtolower($extensionDoc);
					
					echo $nomDoc.'<br/>';
					
					$resultat=move_uploaded_file($_FILES['result']['tmp_name'], $nomDoc);
					
					
					
					echo '*ID de la consult: '.$id_consu.'<br/>*Date des résultats: '.$dateresult.'<br/>*Le nom de l\'examen: '.$exam.'<br/>*Code Laborantin: '.$codelabo.'<br/>*Numero Patient: '.$num.'<br/>*Résultats: '.$nomDoc; 

					
					$resultats=$connexion->prepare('UPDATE consultations SET id_consu=:idconsu,examen=:exam,examenfait=:examfait,resultats=:resultats,dateresultat=:dateresult,codelabo=:codelabo WHERE id_consu=:idconsu');
							
					$resultats->execute(array(
					'idconsu'=>$id_consu,
					'exam'=>$exam,
					'examfait'=>$examenfait,
					'resultats'=>$nomDoc,
					'dateresult'=>$dateresult,
					'codelabo'=>$codelabo
					));
						
					echo '<script type="text/javascript"> alert("Vous venez d\' envoyer les résultats de '.$exam.'");</script>';
					
					echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok";</script>';
							
				}
			}
			
		}else{
			echo '<script language="javascript"> alert("Veuillez charger des résultats!!");</script>';
			echo '<script language="javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$dateresult.'&consu=ok"</script>';
		}

	}
	
	
	if(isset($_POST['updatebtn']))
	{
		
		$modifierConsu=$_POST['idopereMedPa'];
		
		
		if($_POST['typeconsult']!="autretypeconsult")
		{
			$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,heureconsu=:heureconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,id_uM=:idMed,numero=:num,id_typeconsult=:typeconsu,prixautretypeconsult=:prixautretypeconsu,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'heureconsu'=>$heureToday,
			'motif'=>$motif,
			'anamnese'=>$anamnese,
			'clihist'=>$clihist,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'allergie'=>$allergie,
			'examcli'=>$examcli,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'idMed'=>$idmed,
			'num'=>$num,
			'typeconsu'=>$typeconsult,
			'prixautretypeconsu'=>$prixautreConsult,
			'hospitalized'=>$hospitalized,
			'motifhospitalized'=>$motifhospitalized,
			'physio'=>$physio,
			'motifphysio'=>$motifphysio,
			'transfer'=>$transfer,
			'motiftransfer'=>$motiftransfer,
			'modifierConsu'=>$modifierConsu
			
			));
		
		}else{
			
			$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,heureconsu=:heureconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,id_uM=:idMed,numero=:num,idtypeconsult=:typeconsu,autretypeconsult=:autretypeconsu,prixautretypeconsult=:prixautretypeconsu,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'heureconsu'=>$heureToday,
			'motif'=>$motif,
			'anamnese'=>$anamnese,
			'clihist'=>$clihist,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'allergie'=>$allergie,
			'examcli'=>$examcli,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'idMed'=>$idmed,
			'num'=>$num,
			'typeconsult'=>$typeconsult,
			'autretypeconsu'=>$autretypeconsult,
			'prixautretypeconsu'=>$prixautreConsult,
			'hospitalized'=>$hospitalized,
			'motifhospitalized'=>$motifhospitalized,
			'physio'=>$physio,
			'motifphysio'=>$motifphysio,
			'transfer'=>$transfer,
			'motiftransfer'=>$motiftransfer,
			'modifierConsu'=>$modifierConsu
			
			));
		
		}
		
		
		if($dateRdv != "0000-00-00")
		{
			
			$getRdv=$connexion->prepare('SELECT *FROM rendez_vous r WHERE r.daterdv=:daterdv AND r.heurerdv=:heurerdv AND r.numero=:num  AND r.id_uM=:idmed ORDER BY r.id_rdv');		
			$getRdv->execute(array(
			'daterdv'=>$dateRdv,
			'heurerdv'=>$heureRdv,
			'num'=>$num,
			'idmed'=>$idmed	
			));
			
			$getRdv->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

			$comptgetRdv=$getRdv->rowCount();
			
			if($comptgetRdv ==0)
			{
				$doneby=$_SESSION['id'];
				$statusRdv=0;
				
				if($annee < $dateRdv)
				{
					$resultat=$connexion->prepare('INSERT INTO rendez_vous (dateattribution,daterdv,heurerdv,id_uM,numero,motifrdv,id_consurdv,doneby,statusRdv) VALUES(:dateattri,:daterdv,:heurerdv,:idmed,:num,:motifrdv,:id_consurdv,:doneby,:statusRdv)');
					$resultat->execute(array(
					'dateattri'=>date('Y-m-d', strtotime($annee)),
					'daterdv'=>date('Y-m-d', strtotime($dateRdv)),
					'heurerdv'=>$heureRdv,
					'idmed'=>nl2br($idmed),
					'num'=>nl2br($num),
					'motifrdv'=>nl2br($_POST['motifrdv']),
					'id_consurdv'=>$modifierConsu,
					'doneby'=>$doneby,
					'statusRdv'=>$statusRdv
					));
				}
			}
		}
			
		
		$idRad = array();
		$resultatsRad = array();
		
		if(isset($_POST['idRad']))
		{
			foreach($_POST['idRad'] as $idR)
			{
				$idRad[] = $idR;
			}
				
			foreach($_POST['resultatsRad'] as $echo)
			{
				$resultatsRad[] = $echo;
			}
			
			for($i=0;$i<sizeof($idRad);$i++)
			{
				
				$resultats=$connexion->prepare('UPDATE med_radio mr SET mr.dateradio=:dateradio, mr.resultatsRad=:resultatsRad, mr.numero=:num, mr.id_uX=:idmed, mr.radiofait=1 WHERE id_medradio=:modifierRadio');
						
				$resultats->execute(array(
				'dateradio'=>$_POST['dateconsu'],
				'resultatsRad'=>$resultatsRad[$i],
				'num'=>$_GET['num'],
				'idmed'=>$idmed,
				'modifierRadio'=>$idRad[$i]
				
				));
			}
			
		}
		
		
		echo '<script type="text/javascript"> alert("Vous venez de modifier une consultation");</script>';

		if($dateRdv != "0000-00-00")
		{
			if($annee < $dateRdv)
			{
				echo '<script type="text/javascript"> alert("Prochain RDV :\n'.$dateRdv.' à '.$heureRdv.'");</script>';		
			}
		}

		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$modifierConsu.'&idassuconsu='.$idassu.'&showfiche=ok&english='.$_GET['english'].'#fichepatient";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$modifierConsu.'&idassuconsu='.$idassu.'&showfiche=ok&francais='.$_GET['francais'].'#fichepatient";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$num.'&idconsu='.$modifierConsu.'&idassuconsu='.$idassu.'&showfiche=ok#fichepatient";</script>';
			}
		}
				
	
	}

	
	if(isset($_POST['addMotif']) OR isset($_POST['addAutreMotif']))
	{
		
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		
		}elseif(isset($_GET['idconsuNext']))
		{	
			$idconsuAdd = $_GET['idconsuNext'];
		}
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
			
		if(isset($_POST['addMotif']))
		{
			$idMotif = array();

			foreach($_POST['motif'] as $valuemotif)
			{
				$idMotif[] = $valuemotif;			   
			}
			
			for($m=0;$m<sizeof($idMotif);$m++)
			{
				// echo $idMotif[$m].'<br/>'.$dateconsu.'<br/>'.$id_uM.'<br/>'.$numero.'<br/><br/>';
				
				if($idMotif[$m] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_motif (dateconsu,id_motif,numero,id_uM,id_consumotif) VALUES(:dateconsu,:id_motif,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'id_motif'=>$idMotif[$m],
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					));
					
				}
			}
		}
		
		if(isset($_POST['addAutreMotif']))
		{
			if(isset($_POST['areaMotif']))
			{
				$autreMotif=ucwords($_POST['areaMotif']);

				if($autreMotif != "")
				{
					// echo $autreMotif.','.$dateconsu.','.$id_uM.','.$numero;Bilateral Hip pain	
					
					$resultat=$connexion->prepare('INSERT INTO med_motif (dateconsu,autremotif,numero,id_uM,id_consumotif) VALUES(:dateconsu,:autremotif,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'autremotif'=>nl2br($autreMotif),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					));
					
					// echo 'INSERT INTO med_motif (dateconsu,autremotif,numero,id_uM,id_consumotif) VALUES('.date('Y-m-d', strtotime($dateconsu)).','.nl2br($autreMotif).','.nl2br($numero).','.nl2br($id_uM).','.nl2br($idconsuAdd).')';
					//Save new motif into motif table
					$Mot = $connexion->prepare("INSERT INTO motifs(nommotif) VALUES(:nommotif)");
					$Mot->execute(array("nommotif"=>$autreMotif));
				}
			}
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#motifTable";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#motifTable";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'#motifTable";</script>';
		
			}
		}
		
	}

	
	if(isset($_POST['addPrediagno']) OR isset($_POST['addAutrePrediagno']))
	{
		
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		
		}elseif(isset($_GET['idconsuNext']))
		{	
			$idconsuAdd = $_GET['idconsuNext'];
		}
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
			
		if(isset($_POST['addPrediagno']))
		{
			$idPredia = array();

			foreach($_POST['prediagno'] as $valueprediagno)
			{
				$idPredia[] = $valueprediagno;			   
			}
			
			for($p=0;$p<sizeof($idPredia);$p++)
			{
				// echo $idPredia[$p].'<br/>'.$dateconsu.'<br/>'.$id_uM.'<br/>'.$numero.'<br/><br/>';
				
				if($idPredia[$p] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO prepostdia (dateconsu,id_predia,numero,id_uM,id_consudia) VALUES(:dateconsu,:id_predia,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'id_predia'=>$idPredia[$p],
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) ;
					
				}
			}
		}
		
		if(isset($_POST['addAutrePrediagno']))
		{
			if(isset($_POST['areaPrediagno']))
			{
				$idAutrePrediagno=ucwords($_POST['areaPrediagno']);

				if($idAutrePrediagno != "")
				{
					// echo $idAutrePrediagno.','.$dateconsu.','.$id_uM.','.$numero;	
					
					$resultat=$connexion->prepare('INSERT INTO prepostdia (dateconsu,autrepredia,numero,id_uM,id_consudia) VALUES(:dateconsu,:autrepredia,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'autrepredia'=>nl2br($idAutrePrediagno),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) ;


					// Insert into pre-built diagnostic

					$finalDia = $connexion->prepare("INSERT INTO diagnostic(nomdiagno) VALUES(:nomdiagno)");
					$finalDia->execute(array("nomdiagno"=>$idAutrePrediagno));
					
				}
			}
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagno=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#diagno";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagno=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#diagno";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagno=ok&idconsu='.$idconsuAdd.'#diagno";</script>';
		
			}
		}
		
	}
	
	if(isset($_POST['addPostdiagno']) OR isset($_POST['addAutrePostdiagno']))
	{
		
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
			
		if(isset($_POST['addPostdiagno']))
		{
			$idPostdia = array();

			foreach($_POST['postdiagno'] as $valueprediagno)
			{
				$idPostdia[] = $valueprediagno;			   
			}
			
			for($p=0;$p<sizeof($idPostdia);$p++)
			{
				// echo $idPostdia[$p].'<br/>'.$dateconsu.'<br/>'.$id_uM.'<br/>'.$numero.'<br/><br/>';
				
				if($idPostdia[$p] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO prepostdia (dateconsu,id_postdia,numero,id_uM,id_consudia) VALUES(:dateconsu,:id_postdia,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'id_postdia'=>$idPostdia[$p],
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) ;
					
				}
			}
		}
		
		if(isset($_POST['addAutrePostdiagno']))
		{
			if(isset($_POST['areaPostdiagno']))
			{
				$idAutrePrediagno=ucwords($_POST['areaPostdiagno']);

				if($idAutrePrediagno != "")
				{
					// echo $idAutrePrediagno.','.$dateconsu.','.$id_uM.','.$numero;	
					
					$resultat=$connexion->prepare('INSERT INTO prepostdia (dateconsu,autrepostdia,numero,id_uM,id_consudia) VALUES(:dateconsu,:autrepostdia,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'autrepostdia'=>nl2br($idAutrePrediagno),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) ;
					
					//Save new diagnostic
					$finalDia = $connexion->prepare("INSERT INTO diagnostic(nomdiagno) VALUES(:nomdiagno)");
					$finalDia->execute(array("nomdiagno"=>$idAutrePrediagno));
				}
			}
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagnopost=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#diagnopost";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagnopost=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#diagnopost";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagnopost=ok&idconsu='.$idconsuAdd.'#diagnopost";</script>';
		
			}
		}
		
	}
	
	
	if(isset($_POST['addConsult']) OR isset($_POST['addAutreConsult']))
	{
		
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
			
		if(isset($_POST['addConsult']))
		{
			$idPrestaConsu = array();

			foreach($_POST['consult'] as $valueConsu)
			{
				$idPrestaConsu[] = $valueConsu;			   
			}
			
			for($x=0;$x<sizeof($idPrestaConsu);$x++)
			{
				// echo $idPrestaConsu.','.$autreConsu.','.$dateconsu.','.$id_uM.','.$numero;
				
				$prixprestaconsu=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestaconsu->execute(array(
				'idPresta'=>$idPrestaConsu[$x]
				));
				
				$prixprestaconsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestaconsu=$prixprestaconsu->rowCount();
				
				if($lignePrixprestaconsu=$prixprestaconsu->fetch())
				{
					$prixConsuCCO=$lignePrixprestaconsu->prixprestaCCO;
					$prixConsu=$lignePrixprestaconsu->prixpresta;
				}
				
				if($idPrestaConsu[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsuCCO,prixprestationConsu,id_assuServ,insupercentServ,numero,id_uM,id_consuMed) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsuCCO,:prixPrestaConsu,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPrestaConsu'=>$idPrestaConsu[$x],
					'prixPrestaConsuCCO'=>$prixConsuCCO,
					'prixPrestaConsu'=>$prixConsu,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) ;
					
				}
			}
		}
		
		if(isset($_POST['addAutreConsult']))
		{
			if(isset($_POST['areaAutreconsult']))
			{
				$idAutreConsult=$_POST['areaAutreconsult'];

				if($idAutreConsult != "")
				{
					$prixpresta=-1;
					$id_categopresta=20;
					$id_souscategopresta=0;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=20 AND (nompresta=:idAutreConsult OR namepresta=:idAutreConsult) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutreConsult'=>$idAutreConsult
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutreConsult),
						'namepresta'=>nl2br($idAutreConsult),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_consult---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestaconsu=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestaconsu->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestaconsu->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPrixprestaconsu=$prixprestaconsu->rowCount();
						
						if($lignePrixprestaconsu=$prixprestaconsu->fetch())
						{
							$prixConsu=$lignePrixprestaconsu->prixpresta;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,id_assuServ,insupercentServ,numero,id_uM,id_consuMed) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPrestaConsu'=>$lastIdPresta,
						'prixPrestaConsu'=>$prixConsu,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>nl2br($numero),
						'id_uM'=>nl2br($id_uM),
						'idconsuAdd'=>nl2br($idconsuAdd)
						)) ;
						
					}
					
				}
			}
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}
	

	if(isset($_POST['addNursery']) or isset($_POST['addAutreNursery']))
	{
		// echo $_POST['soins'];
				
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		$soinsfait=0;
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		if(isset($_POST['addNursery']))
		{
			$idPresta = array();

			foreach($_POST['soins'] as $valueSoins)
			{
				$idPresta[] = $valueSoins;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$soinsfait.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestainf=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestainf->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestainf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestainf=$prixprestainf->rowCount();
				
				if($lignePrixprestainf=$prixprestainf->fetch())
				{
					$prixInfCCO=$lignePrixprestainf->prixprestaCCO;
					$prixInf=$lignePrixprestainf->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestationCCO,prixprestation,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:soinsfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixInfCCO,
					'prixPresta'=>$prixInf,
					'soinsfait'=>$soinsfait,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreNursery']))
		{
			if(isset($_POST['areaAutresoins']))
			{
				$idAutrePresta=$_POST['areaAutresoins'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=3;
					$id_souscategopresta=0;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=3 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_inf---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestainf=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestainf->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestainf->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestainf=$prixprestainf->rowCount();
						
						if($lignePrixprestainf=$prixprestainf->fetch())
						{
							$prixInf=$lignePrixprestainf->prixpresta;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPresta,:prixPresta,:soinsfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixInf,
						'soinsfait'=>$soinsfait,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}
	
	
	if(isset($_POST['addExam']) OR isset($_POST['addAutreExam']))
	{
		// echo $_POST['listexamen'];
		
		if(isset($_GET['idconsuNext']))
		{
			$idconsuAdd = $_GET['idconsuNext'];
		}
		
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		$examenfait=0;
		$prixautreExa=0;
		$dateresultat="0000-00-00";
		$idfacture=0;

		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		if(isset($_POST['addExam']))
		{	
			$idPresta = array();

			foreach($_POST['listexamen'] as $valueExa)
			{
				$idPresta[] = $valueExa;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				
				$prixprestalabo=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestalabo->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestalabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestalabo=$prixprestalabo->rowCount();
				
				if($lignePrixprestalabo=$prixprestalabo->fetch())
				{
					$prixLaboCCO=$lignePrixprestalabo->prixprestaCCO;
					$prixLabo=$lignePrixprestalabo->prixpresta;
				}
			
				
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_labo (dateconsu,id_prestationExa,prixprestationExaCCO,prixprestationExa,id_assuLab,insupercentLab,examenfait,numero,id_uM,id_consuLabo) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:idassu,:bill,:examenfait,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixLaboCCO,
					'prixPresta'=>$prixLabo,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'examenfait'=>nl2br($examenfait),				
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) ;
		
				}
			}
		}
		
		if(isset($_POST['addAutreExam']))
		{
		
			if(isset($_POST['areaAutreexamen']))
			{
				$idAutreExam=$_POST['areaAutreexamen'];

				if($idAutreExam != "")
				{
					$prixpresta=-1;
					$id_categopresta=12;
					$id_souscategopresta=7;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=12 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutreExam
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutreExam),
						'namepresta'=>nl2br($idAutreExam),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_labo---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestalabo=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestalabo->execute(array(
						'idPresta'=>$lastIdPresta
						));
						
						$prixprestalabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

						$comptPrixprestalabo=$prixprestalabo->rowCount();
						
						if($lignePrixprestalabo=$prixprestalabo->fetch())
						{
							$prixLabo=$lignePrixprestalabo->prixpresta;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_labo (dateconsu,id_prestationExa,prixprestationExa,id_assuLab,insupercentLab,examenfait,numero,id_uM,id_consuLabo) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:examenfait,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixLabo,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'examenfait'=>nl2br($examenfait),
						'numero'=>nl2br($numero),
						'id_uM'=>nl2br($id_uM),
						'idconsuAdd'=>nl2br($idconsuAdd)
						)) ;
					
					}
				}
			}
		}
			
				
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}
	
	
	if(isset($_POST['addRadio']) OR isset($_POST['addAutreRadio']))
	{
		// echo $_POST['listradio'];
		
		if(isset($_GET['idconsuNext']))
		{
			$idconsuAdd = $_GET['idconsuNext'];
		}
		
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		$radiofait=0;
		$prixautreRadio=0;
		$dateradio="0000-00-00";
		$idfacture=0;

		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		if(isset($_POST['addRadio']))
		{
			$idPresta = array();

			foreach($_POST['listradio'] as $valueRad)
			{
				$idPresta[] = $valueRad;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{	
					$prixprestaradio=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
					$prixprestaradio->execute(array(
					'idPresta'=>$idPresta[$x]
					));
					
					$prixprestaradio->setFetchMode(PDO::FETCH_OBJ);

					$comptPrixprestaradio=$prixprestaradio->rowCount();
					
					if($lignePrixprestaradio=$prixprestaradio->fetch())
					{
						$prixRadioCCO=$lignePrixprestaradio->prixprestaCCO;
						$prixRadio=$lignePrixprestaradio->prixpresta;
					}
				
				
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_radio (dateconsu,id_prestationRadio,prixprestationRadioCCO,prixprestationRadio,id_assuRad,insupercentRad,radiofait,numero,id_uM,id_consuRadio) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:idassu,:bill,:radiofait,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixRadioCCO,
					'prixPresta'=>$prixRadio,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'radiofait'=>nl2br($radiofait),					
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) ;
		
				}
			}
		}
		
		if(isset($_POST['addAutreRadio']))
		{
		
			if(isset($_POST['areaAutreradio']))
			{
				$idAutreRadio=$_POST['areaAutreradio'];

				if($idAutreRadio != "")
				{
					$prixpresta=-1;
					$id_categopresta=13;
					$id_souscategopresta=9;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=13 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutreRadio
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutreRadio),
						'namepresta'=>nl2br($idAutreRadio),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_labo---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestaradio=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestaradio->execute(array(
						'idPresta'=>$lastIdPresta
						));
						
						$prixprestaradio->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestaradio=$prixprestaradio->rowCount();
						
						if($lignePrixprestaradio=$prixprestaradio->fetch())
						{
							$prixRadio=$lignePrixprestaradio->prixpresta;
						}

						
						$resultat=$connexion->prepare('INSERT INTO med_radio (dateconsu,id_prestationRadio,prixprestationRadio,id_assuRad,insupercentRad,radiofait,numero,id_uM,id_consuRadio) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:radiofait,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixRadio,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'radiofait'=>nl2br($radiofait),
						'numero'=>nl2br($numero),
						'id_uM'=>nl2br($id_uM),
						'idconsuAdd'=>nl2br($idconsuAdd)
						)) ;
							
					}
				}
			}
		}
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}
	
	if(isset($_POST['addConsom']) or isset($_POST['addAutreConsom']))
	{
		// echo $_POST['consom'];
				
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		if(isset($_POST['addConsom']))
		{
			$idPresta = array();

			foreach($_POST['consom'] as $valueConsom)
			{
				$idPresta[] = $valueConsom;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestaconsom=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestaconsom->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestaconsom->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestaconsom=$prixprestaconsom->rowCount();
				
				if($lignePrixprestaconsom=$prixprestaconsom->fetch())
				{
					$prixConsomCCO=$lignePrixprestaconsom->prixprestaCCO;
					$prixConsom=$lignePrixprestaconsom->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
			
					$resultat=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsomCCO,prixprestationConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixConsomCCO,
					'prixPresta'=>$prixConsom,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreConsom']))
		{
			if(isset($_POST['areaAutreconsom']))
			{
				$idAutrePresta=$_POST['areaAutreconsom'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=21;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=21 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_consom---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestaconsom=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestaconsom->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestaconsom->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestaconsom=$prixprestaconsom->rowCount();
						
						if($lignePrixprestaconsom=$prixprestaconsom->fetch())
						{
							$prixConsom=$lignePrixprestaconsom->prixpresta;
						}
						
						$resultat=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixConsom,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}
	

	if(isset($_POST['addMedoc']) or isset($_POST['addAutreMedoc']))
	{
		// echo $_POST['soins'];
				
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		if(isset($_POST['addMedoc']))
		{
			$idPresta = array();

			foreach($_POST['medoc'] as $valueMedoc)
			{
				$idPresta[] = $valueMedoc;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestamedoc=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestamedoc->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestamedoc->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestamedoc=$prixprestamedoc->rowCount();
				
				if($lignePrixprestamedoc=$prixprestamedoc->fetch())
				{
					$prixMedocCCO=$lignePrixprestamedoc->prixprestaCCO;
					$prixMedoc=$lignePrixprestamedoc->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedocCCO,prixprestationMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixMedocCCO,
					'prixPresta'=>$prixMedoc,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreMedoc']))
		{
			if(isset($_POST['areaAutremedoc']))
			{
				$idAutrePresta=$_POST['areaAutremedoc'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=22;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=22 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_inf---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestamedoc=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestamedoc->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestamedoc->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestamedoc=$prixprestamedoc->rowCount();
						
						if($lignePrixprestamedoc=$prixprestamedoc->fetch())
						{
							$prixMedoc=$lignePrixprestamedoc->prixpresta;
						}
					
						$resultat=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixMedoc,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}


	if (isset($_POST['addConsomRecomm'])) {
		
		$id_categopresta = 21;
		$consomRecomm = $_POST['consomRecomm'];
		$idconsu = $_GET['idconsu'];
		$idMed = $_SESSION['id'];
		$numero = $_GET['num'];

		$insertConsomReco = $connexion->prepare("INSERT INTO doctorrecommandations(`recommandations`, `idcategopresta`, `idconsu`, `numero`, `id_M`,`duration`) VALUES(:recomm,:id_categopresta,:idconsu,:numero,:id_M,:duration)");
		$insertConsomReco->execute(array('recomm'=>$consomRecomm,'id_categopresta'=>$id_categopresta,'idconsu'=>$idconsu,'numero'=>$numero,'id_M'=>$idMed,'duration'=>$annee));


		// if(isset($_GET['english']))
		// 	{
		// 		// echo '&english='.$_GET['english'];
		// 		echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		// 	}else{
		// 		if(isset($_GET['francais']))
		// 		{
		// 			// echo '&francais='.$_GET['francais'];
		// 			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
			
		// 		}else{
		// 			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
			
		// 		}
		// 	}
			
		}


	if (isset($_POST['addMedocRecomm'])) {
		
		$id_categopresta = 22;
		$consomRecomm = $_POST['medocRecomm'];
		$idconsu = $_GET['idconsu'];
		$idMed = $_SESSION['id'];
		$numero = $_GET['num'];

		//echo $numero;

		$insertConsomReco = $connexion->prepare("INSERT INTO doctorrecommandations(`recommandations`, `idcategopresta`, `idconsu`, `numero`, `id_M`,`duration`) VALUES(:recomm,:id_categopresta,:idconsu,:numero,:id_M,:duration)");
		$insertConsomReco->execute(array('recomm'=>$consomRecomm,'id_categopresta'=>$id_categopresta,'idconsu'=>$idconsu,'numero'=>$numero,'id_M'=>$idMed,'duration'=>$annee));


		if(isset($_GET['english']))
			{
				// echo '&english='.$_GET['english'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

			}else{
				if(isset($_GET['francais']))
				{
					// echo '&francais='.$_GET['francais'];
					echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
			
				}else{
					echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
			
				}
			}
			
		}

		

	if(isset($_POST['addKine']) or isset($_POST['addAutreKine']))
	{
		$kinefait=1;		
		$id_uK=$_SESSION['id'];
		
		// echo $_POST['kine'];
				
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		if(isset($_POST['addKine']))
		{
			$idPresta = array();

			foreach($_POST['kine'] as $valueKine)
			{
				$idPresta[] = $valueKine;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestakine=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestakine->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestakine->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestakine=$prixprestakine->rowCount();
				
				if($lignePrixprestakine=$prixprestakine->fetch())
				{
					$prixKineCCO=$lignePrixprestakine->prixprestaCCO;
					$prixKine=$lignePrixprestakine->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_kine (dateconsu,id_prestationKine,prixprestationKineCCO,prixprestationKine,id_uK,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_consuKine) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:id_uK,:kinefait,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixKineCCO,
					'prixPresta'=>$prixKine,
					'id_uK'=>$id_uK,
					'kinefait'=>$kinefait,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreKine']))
		{
			if(isset($_POST['areaAutrekine']))
			{
				$idAutrePresta=$_POST['areaAutrekine'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=14;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=14 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_inf---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestakine=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestakine->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestakine->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestakine=$prixprestakine->rowCount();
						
						if($lignePrixprestakine=$prixprestakine->fetch())
						{
							$prixKine=$lignePrixprestakine->prixpresta;
						}
					
						$resultat=$connexion->prepare('INSERT INTO med_kine (dateconsu,id_prestationKine,prixprestationKine,id_uK,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_consuKine) VALUES(:dateconsu,:idPresta,:prixPresta,:id_uK,:kinefait,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixKine,
						'id_uK'=>$id_uK,
						'kinefait'=>$kinefait,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#tableKine";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#tableKine";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'#tableKine";</script>';
		
			}
		}
		
	}
	
	
	if(isset($_POST['addOrtho']) or isset($_POST['addAutreOrtho']))
	{
		// echo $_POST['ortho'];
				
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		if(isset($_POST['addOrtho']))
		{
			$idPresta = array();

			foreach($_POST['ortho'] as $valueOrtho)
			{
				$idPresta[] = $valueOrtho;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestaortho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestaortho->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestaortho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestaortho=$prixprestaortho->rowCount();
				
				if($lignePrixprestaortho=$prixprestaortho->fetch())
				{
					$prixOrthoCCO=$lignePrixprestaortho->prixprestaCCO;
					$prixOrtho=$lignePrixprestaortho->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_ortho (dateconsu,id_prestationOrtho,prixprestationOrthoCCO,prixprestationOrtho,id_assuOrtho,insupercentOrtho,numero,id_uM,id_consuOrtho) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixOrthoCCO,
					'prixPresta'=>$prixOrtho,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreOrtho']))
		{
			if(isset($_POST['areaAutreortho']))
			{
				$idAutrePresta=$_POST['areaAutreortho'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=23;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=23 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_inf---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestaortho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestaortho->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestaortho->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestaortho=$prixprestaortho->rowCount();
						
						if($lignePrixprestaortho=$prixprestaortho->fetch())
						{
							$prixOrtho=$lignePrixprestaortho->prixpresta;
						}
					
						$resultat=$connexion->prepare('INSERT INTO med_ortho (dateconsu,id_prestationOrtho,prixprestationOrtho,id_assuOrtho,insupercentOrtho,numero,id_uM,id_consuOrtho) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixOrtho,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#tableOrtho";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#tableOrtho";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'#tableOrtho";</script>';
		
			}
		}
		
	}
		

	if(isset($_POST['addSurge']) or isset($_POST['addAutreSurge']))
	{
		// echo $_POST['surge'];
				
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		if(isset($_POST['addSurge']))
		{
			$idPresta = array();

			foreach($_POST['surge'] as $valueSurge)
			{
				$idPresta[] = $valueSurge;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestasurge=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestasurge->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestasurge->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				$comptPrixprestasurge=$prixprestasurge->rowCount();
				
				if($lignePrixprestasurge=$prixprestasurge->fetch())
				{
					echo "string";
					$prixSurgeCCO=$lignePrixprestasurge->prixprestaCCO;
					$prixSurge=$lignePrixprestasurge->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_surge (dateconsu,id_prestationSurge,prixprestationSurgeCCO,prixprestationSurge,id_assuSurge,insupercentSurge,numero,id_uM,id_consuSurge) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixSurgeCCO,
					'prixPresta'=>$prixSurge,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutreSurge']))
		{
			if(isset($_POST['areaAutresurge']))
			{
				$idAutrePresta=$_POST['areaAutresurge'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=4;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=4 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_surge---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestasurge=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestasurge->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestasurge->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestasurge=$prixprestasurge->rowCount();
						
						if($lignePrixprestasurge=$prixprestasurge->fetch())
						{
							$prixSurge=$lignePrixprestasurge->prixpresta;
						}
					
						$resultat=$connexion->prepare('INSERT INTO med_surge (dateconsu,id_prestationSurge,prixprestationSurge,id_assuSurge,insupercentSurge,numero,id_uM,id_consuSurge) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixSurge,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		/*if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#tableSurge";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#tableSurge";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'#tableSurge";</script>';
		
			}
		}*/
		
	}




	if(isset($_POST['addpsy']) or isset($_POST['areaAutrepsy']))
	{
		//echo $_POST['addpsy'];
				
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}else{
			
			if(isset($_GET['idconsuNext']))
			{
				$idconsuAdd = $_GET['idconsuNext'];
			}
		}
		
		
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		if(isset($_POST['addpsy']))
		{
			$idPresta = array();

			foreach($_POST['psycho'] as $valuePsycho)
			{
				$idPresta[] = $valuePsycho;			   
			}
			
			for($x=0;$x<sizeof($idPresta);$x++)
			{
				// echo $idPresta.','.$dateconsu.','.$id_uM.','.$numero;

				$prixprestapsycho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
				$prixprestapsycho->execute(array(
				'idPresta'=>$idPresta[$x]
				));
				
				$prixprestapsycho->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

				echo $comptPrixprestapsycho=$prixprestapsycho->rowCount();
				
				if($lignePrixprestapsycho=$prixprestapsycho->fetch())
				{
					$prixPsychoCCO=$lignePrixprestapsycho->prixprestaCCO;
					$prixPsycho=$lignePrixprestapsycho->prixpresta;
				}
			
				if($idPresta[$x] != "")
				{
					$resultat=$connexion->prepare('INSERT INTO med_psy (dateconsu,id_prestation,prixprestationCCO,prixprestation,id_assuPsy,insupercentPsy,numero,id_uM,id_consuPSy) VALUES(:dateconsu,:idPresta,:prixPrestaCCO,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
					'idPresta'=>$idPresta[$x],
					'prixPrestaCCO'=>$prixPsychoCCO,
					'prixPresta'=>$prixPsycho,
					'idassu'=>$idassu,
					'bill'=>$bill,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					
					)) ;
					
				}
			}			
		}
	
		if(isset($_POST['addAutrePsycho']))
		{
			if(isset($_POST['areaAutrepsy']))
			{
				$idAutrePresta=$_POST['areaAutrepsy'];

				if($idAutrePresta != "")
				{
					$prixpresta=-1;
					$id_categopresta=25;
					$id_souscategopresta=NULL;
					
					$mesure=NULL;
					$statupresta=0;
					
					
					$searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=4 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
					$searchNomPresta->execute(array(
					'idAutrePresta'=>$idAutrePresta
					));
									
					$searchNomPresta->setFetchMode(PDO::FETCH_OBJ);
					
					$comptNomPresta=$searchNomPresta->rowCount();
								
					if($comptNomPresta==0)
					{
						$insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
						$insertNewPresta->execute(array(
						'nompresta'=>nl2br($idAutrePresta),
						'namepresta'=>nl2br($idAutrePresta),
						'prixpresta'=>$prixpresta,
						'id_categopresta'=>$id_categopresta,
						'id_souscategopresta'=>$id_souscategopresta,
						'mesure'=>$mesure,
						'statupresta'=>$statupresta
						)) ;
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');
						
					}else{
						$ligneNomPresta=$searchNomPresta->fetch();
						
						$searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');
						
					}
					
					/*-------Put in med_Psy---------*/
				 
					$searchLastId->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligneLastId=$searchLastId->fetch())
					{
						
						$lastIdPresta=$ligneLastId->id_prestation;
					
						$prixprestapycho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
						$prixprestapycho->execute(array(
						'idPresta'=>$lastIdPresta
						));
				
						$prixprestapycho->setFetchMode(PDO::FETCH_OBJ);

						$comptPrixprestapsy=$prixprestapycho->rowCount();
						
						if($lignePrixprestapsy=$prixprestapycho->fetch())
						{
							$prixPsy=$lignePrixprestapsy->prixpresta;
						}
					
						$resultat=$connexion->prepare('INSERT INTO med_psy (dateconsu,id_prestation,prixprestation,id_assuPsy,insupercentPsy,numero,id_uM,id_consuPSy) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:numero,:id_uM,:idconsuAdd)');
						$resultat->execute(array(
						'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
						'idPresta'=>$lastIdPresta,
						'prixPresta'=>$prixPsy,
						'idassu'=>$idassu,
						'bill'=>$bill,
						'numero'=>$numero,
						'id_uM'=>$id_uM,
						'idconsuAdd'=>$idconsuAdd
						
						)) ;
					
					}
				}
			}
		}
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}
	
			
	// echo $_POST['deleteMedmotif'.$_POST['deleteIdMedmotif'].''];
		
	if(isset($_POST['deleteMedmotif']))
	{
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedmotif = array();

		foreach($_POST['deleteMedmotif'] as $valeur)
		{
			$deleteIdMedmotif[] = $valeur;		
		}
		
		
		for($i=0;$i<sizeof($deleteIdMedmotif);$i++)
		{
			// echo $deleteIdMedpredia[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_motif WHERE id_medmotif=:id_medM');
			
			$resultats->execute(array(
			'id_medM'=>$deleteIdMedmotif[$i]
			
			))or die($resultats->errorInfo());
		
			// echo '<script type="text/javascript"> alert("Le diagnoPro'.$deleteIdMedpredia[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#motifTable";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#motifTable";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&idconsu='.$idconsuAdd.'#motifTable";</script>';
		
			}
		}
		
	}
	
			
	// echo $_POST['deleteMedpredia'.$_POST['deleteIdMedpredia'].''];
		
	if(isset($_POST['deleteMedpredia']))
	{
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedpredia = array();

		foreach($_POST['deleteMedpredia'] as $valeur)
		{
			$deleteIdMedpredia[] = $valeur;		
		}
		
		
		for($i=0;$i<sizeof($deleteIdMedpredia);$i++)
		{
			// echo $deleteIdMedpredia[$i];
	
			$resultats=$connexion->prepare('DELETE FROM prepostdia WHERE id_dia=:id_medD');
			
			$resultats->execute(array(
			'id_medD'=>$deleteIdMedpredia[$i]
			
			))or die($resultats->errorInfo());
		
			// echo '<script type="text/javascript"> alert("Le diagnoPro'.$deleteIdMedpredia[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagno=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#diagno";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagno=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#diagno";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagno=ok&idconsu='.$idconsuAdd.'#diagno";</script>';
		
			}
		}
		
	}
		
	// echo $_POST['deleteMedpostdia'.$_POST['deleteIdMedpostdia'].''];
		
	if(isset($_POST['deleteMedpostdia']))
	{
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedpostdia = array();

		foreach($_POST['deleteMedpostdia'] as $valeur)
		{
			$deleteIdMedpostdia[] = $valeur;		
		}
		
		
		for($i=0;$i<sizeof($deleteIdMedpostdia);$i++)
		{
			// echo $deleteIdMedpostdia[$i];
	
			$resultats=$connexion->prepare('DELETE FROM prepostdia WHERE id_dia=:id_medD');
			
			$resultats->execute(array(
			'id_medD'=>$deleteIdMedpostdia[$i]
			
			))or die($resultats->errorInfo());
		
			// echo '<script type="text/javascript"> alert("Le diagnoPro'.$deleteIdMedpostdia[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagnopost=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#diagnopost";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagnopost=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#diagnopost";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&diagnopost=ok&idconsu='.$idconsuAdd.'#diagnopost";</script>';
		
			}
		}
		
	}
			
	// echo $_POST['deleteMedconsu'.$_POST['deleteIdMedconsu'].''];
		
	if(isset($_POST['deleteMedconsu']))
	{
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedConsu = array();

		foreach($_POST['deleteMedconsu'] as $valeur)
		{
			$deleteIdMedConsu[] = $valeur;		
		}
		
		
		for($i=0;$i<sizeof($deleteIdMedConsu);$i++)
		{
			// echo $deleteIdMedConsu[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_consult WHERE id_medconsu=:id_medC');
			
			$resultats->execute(array(
			'id_medC'=>$deleteIdMedConsu[$i]
			
			))or die($resultats->errorInfo());
		
			// echo '<script type="text/javascript"> alert("Le service'.$deleteIdMedConsu[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}
	
		
	if(isset($_POST['deleteMedinf']))
	{		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		
		$deleteIdMedinf = array();

		foreach($_POST['deleteMedinf'] as $valeur)
		{
			$deleteIdMedinf[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedinf);$i++)
		{
			// echo $deleteIdMedinf[$i];
	
			$resultats=$connexion->prepare('DELETE FROM med_inf WHERE id_medinf=:id_medI');
		
			$resultats->execute(array(
			'id_medI'=>$deleteIdMedinf[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le soin '.$deleteIdMedinf[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}
	
		
	if(isset($_POST['deleteMedlabo']))
	{		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedlabo = array();

		foreach($_POST['deleteMedlabo'] as $valeur)
		{
			$deleteIdMedlabo[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedlabo);$i++)
		{
			// echo $deleteIdMedlabo[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_labo WHERE id_medlabo=:id_medL');
		
			$resultats->execute(array(
			'id_medL'=>$deleteIdMedlabo[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("L\'examen '.$deleteIdMedlabo[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}

	
		
	if(isset($_POST['deleteMedradio']))
	{		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedradio = array();

		foreach($_POST['deleteMedradio'] as $valeur)
		{
			$deleteIdMedradio[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedradio);$i++)
		{
			// echo $deleteIdMedradio[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_radio WHERE id_medradio=:id_medR');
		
			$resultats->execute(array(
			'id_medR'=>$deleteIdMedradio[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("La radio '.$deleteIdMedradio[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}	

	if(isset($_POST['deleteMedPsy']))
	{		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedpsy = array();

		foreach($_POST['deleteMedPsy'] as $valeur)
		{
			$deleteIdMedpsy[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedpsy);$i++)
		{
			// echo $deleteIdMedradio[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_psy WHERE id_medpsy=:id_medpsy');
		
			$resultats->execute(array(
			'id_medpsy'=>$deleteIdMedpsy[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("La radio '.$deleteIdMedpsy[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#forBilling";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#forBilling";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
		
			}
		}
		
	}

	
		
	if(isset($_POST['deleteMedconsom']))
	{		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedconsom = array();

		foreach($_POST['deleteMedconsom'] as $valeur)
		{
			$deleteIdMedconsom[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedconsom);$i++)
		{
			// echo $deleteIdMedconsom[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_consom WHERE id_medconsom=:id_medCo');
		
			$resultats->execute(array(
			'id_medCo'=>$deleteIdMedconsom[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le matériel '.$deleteIdMedconsom[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}

	
		
	if(isset($_POST['deleteMedmedoc']))
	{
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedmedoc = array();

		foreach($_POST['deleteMedmedoc'] as $valeur)
		{
			$deleteIdMedmedoc[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedmedoc);$i++)
		{
			// echo $deleteIdMedmedoc[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_medoc WHERE id_medmedoc=:id_medMo');
		
			$resultats->execute(array(
			'id_medMo'=>$deleteIdMedmedoc[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le médicament '.$deleteIdMedmedoc[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}	

	if(isset($_POST['deleteMedocRecom']))
	{	
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedmedocReco = array();

		foreach($_POST['deleteMedocRecom'] as $valeur)
		{
			$deleteIdMedmedocReco[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedmedocReco);$i++)
		{
			// echo $deleteIdMedmedoc[$i];
			
			$resultats=$connexion->prepare('DELETE FROM doctorrecommandations WHERE idreco=:idreco');
		
			$resultats->execute(array(
			'idreco'=>$deleteIdMedmedocReco[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le médicament '.$deleteIdMedmedoc[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}

	if(isset($_POST['deleteMedconsomRecom']))
	{	
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedconsomReco = array();

		foreach($_POST['deleteMedconsomRecom'] as $valeur)
		{
			$deleteIdMedconsomReco[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedconsomReco);$i++)
		{
			// echo $deleteIdMedmedoc[$i];
			
			$resultats=$connexion->prepare('DELETE FROM doctorrecommandations WHERE idreco=:idreco');
		
			$resultats->execute(array(
			'idreco'=>$deleteIdMedconsomReco[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("Le médicament '.$deleteIdMedmedoc[$i].' a bien été supprimé");</script>';
		
		}
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServConsomMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServConsomMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServConsomMedoc";</script>';
		
			}
		}
		
	}
	
	
	if(isset($_POST['deleteMedkine']))
	{		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedkine = array();

		foreach($_POST['deleteMedkine'] as $valeur)
		{
			$deleteIdMedkine[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedkine);$i++)
		{
			// echo $deleteIdMedkine[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_kine WHERE id_medkine=:id_medMo');
		
			$resultats->execute(array(
			'id_medMo'=>$deleteIdMedkine[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("L'acte '.$deleteIdMedkine[$i].' a bien été supprimé");</script>';
		
		}
		 
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#tableKine";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#tableKine";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#tableKine";</script>';
		
			}
		}

	}
	
	
	if(isset($_POST['deleteMedortho']))
	{		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedortho = array();

		foreach($_POST['deleteMedortho'] as $valeur)
		{
			$deleteIdMedortho[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedortho);$i++)
		{
			// echo $deleteIdMedortho[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_ortho WHERE id_medortho=:id_medMo');
		
			$resultats->execute(array(
			'id_medMo'=>$deleteIdMedortho[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("L'appareil '.$deleteIdMedortho[$i].' a bien été supprimé");</script>';
		
		}
		 
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#tableOrtho";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#tableOrtho";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#tableOrtho";</script>';
		
			}
		}

	}
	
	
	if(isset($_POST['deleteMedsurge']))
	{		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,anamnese=:anamnese,clihist=:clihist,etatpatient=:etatpa,antecedent=:antec,allergie=:allergie,examcli=:examcli,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,hospitalized=:hospitalized,motifhospitalized=:motifhospitalized,physio=:physio,motifphysio=:motifphysio,transfer=:transfer,motiftransfer=:motiftransfer,done=1 WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'anamnese'=>$anamnese,
		'clihist'=>$clihist,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'allergie'=>$allergie,
		'examcli'=>$examcli,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'hospitalized'=>$hospitalized,
		'motifhospitalized'=>$motifhospitalized,
		'physio'=>$physio,
		'motifphysio'=>$motifphysio,
		'transfer'=>$transfer,
		'motiftransfer'=>$motiftransfer,
		'modifierConsu'=>$idconsuAdd,
		
		));

		
		
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];
		
		$deleteIdMedsurge = array();

		foreach($_POST['deleteMedsurge'] as $valeur)
		{
			$deleteIdMedsurge[] = $valeur;
		}
		
		for($i=0;$i<sizeof($deleteIdMedsurge);$i++)
		{
			// echo $deleteIdMedsurge[$i];
			
			$resultats=$connexion->prepare('DELETE FROM med_surge WHERE id_medsurge=:id_medMs');
		
			$resultats->execute(array(
			'id_medMs'=>$deleteIdMedsurge[$i]
			
			))or die($resultats->errorInfo());

			// echo '<script type="text/javascript"> alert("L'acte '.$deleteIdMedsurge[$i].' a bien été supprimé");</script>';
		
		}
		 
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#tableSurge";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#tableSurge";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#tableSurge";</script>';
		
			}
		}

	}
	
	if(isset($_POST['idmedMedocUpdate']))
	{	
		//echo "ok";
		$idMedoc= array();
		$qteMedoc= array();
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];


		foreach($_POST['quantityMedoc'] as $valeurQteMedoc)
		{
			$qteMedoc[] = $valeurQteMedoc;
		}

		foreach($_POST['idmedMedocUpdate'] as $valeurIdMedoc)
		{
			$idMedoc[] = $valeurIdMedoc;
		}
					
		for($i=0;$i<sizeof($idMedoc);$i++)
		{
			if(isset($_POST['addQteMedocBtn'.$idMedoc[$i]]))
			{
				if($qteMedoc[$i]>0)
				{
					$searchMedoc=$connexion->prepare('SELECT *FROM med_medoc WHERE id_medmedoc=:idmedmedoc');
					$searchMedoc->execute(array(
					'idmedmedoc'=>$idMedoc[$i]
					)) or die( print_r($connexion->errorInfo()));
					
					$searchMedoc->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchMedoc=$searchMedoc->rowCount();
					
					if($comptSearchMedoc!=0)				
					{
						if($ligneMedoc=$searchMedoc->fetch())
						{
							$iassu= $ligneMedoc->id_assuMedoc;
							$updateQteMedMedoc=$connexion->prepare('UPDATE med_medoc mdo SET mdo.qteMedoc=:qteMedoc WHERE mdo.id_medmedoc=:idmedmedoc');
							
							$updateQteMedMedoc->execute(array(
							'qteMedoc'=>$qteMedoc[$i],
							'idmedmedoc'=>$idMedoc[$i],
							))or die($connexion->errorInfo());
		
						// echo $idMedoc[$i].' : '.$qteConsom[$i].'_'.$percentConsom[$i].'_'.$prixConsom[$i].'<br/>';
						}
						
					}
				}
		
			}
		}
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServMedocMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServMedocMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServMedocMedoc";</script>';
		
			}
		}
		
	}

		if(isset($_POST['idmedConsomUpdate']))
	{	
		//echo "ok";
		$idConso= array();
		$qteConsom= array();
		$dateconsu = $_GET['datetoday'];
		$numero = $_GET['num'];
		$id_uM = $_SESSION['id'];


		foreach($_POST['quantityConsom'] as $valeurQteConsom)
		{
			$qteConsom[] = $valeurQteConsom;
		}

		foreach($_POST['idmedConsomUpdate'] as $valeurIdConsom)
		{
			$idConsom[] = $valeurIdConsom;
		}
					
		for($i=0;$i<sizeof($idConsom);$i++)
		{
			if(isset($_POST['addQteConsomBtn'.$idConsom[$i]]))
			{
				if($qteConsom[$i]>0)
				{
					$searchConsom=$connexion->prepare('SELECT *FROM med_consom WHERE id_medconsom=:id_medconsom');
					$searchConsom->execute(array(
					'id_medconsom'=>$idConsom[$i]
					)) or die( print_r($connexion->errorInfo()));
					
					$searchConsom->setFetchMode(PDO::FETCH_OBJ);
					
					$comptSearchConsom=$searchConsom->rowCount();
					
					if($comptSearchConsom!=0)				
					{
						if($ligneConsom=$searchConsom->fetch())
						{
							$iassu= $ligneConsom->id_assuConsom;
							$updateQteMedConsom=$connexion->prepare('UPDATE med_consom mdo SET mdo.qteConsom=:qteConsom WHERE mdo.id_medconsom=:id_medconsom');
							
							$updateQteMedConsom->execute(array(
							'qteConsom'=>$qteConsom[$i],
							'id_medconsom'=>$idConsom[$i],
							))or die($connexion->errorInfo());
		
						// echo $idMedoc[$i].' : '.$qteConsom[$i].'_'.$percentConsom[$i].'_'.$prixConsom[$i].'<br/>';
						}
						
					}
				}
		
			}
		}
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&english='.$_GET['english'].'#ServMedocMedoc";</script>';

		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'&francais='.$_GET['francais'].'#ServMedocMedoc";</script>';
		
			}else{
				echo '<script type="text/javascript">document.location.href="consult.php?num='.$numero.'&idtypeconsult='.$idtypeconsult.'&idassuconsu='.$idassu.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#ServMedocMedoc";</script>';
		
			}
		}
		
	}

	
}

catch(Excepton $e)
{
	echo 'Erreur:'.$e->getMessage().'<br/>';
	echo'Numero:'.$e->getCode();
}



?>