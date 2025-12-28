<?php
session_start();
include("connect.php");

try
{
	$heure=date("H");
	$min=date("i");


	$heureToday=$heure.':'.$min;

	//-------Creation fiche par le medecin
	
	if(!isset($_GET['deleteMedconsu']))
	{
		if(!isset($_GET['deleteMedinf']))
		{
			if(!isset($_GET['deleteMedlabo']))
			{
				$idMedecin=$_POST['medecins'];
				
				if($_POST['typeconsult']!="autretypeconsult")
				{
					
					$idassu=$_GET['idassu'];
																
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
		

			
					$typeconsult=$_POST['typeconsult'];
			
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
							$prixConsult=$lignePrixtypeconsult->prixpresta;
						}
					}
				
				}else{
					$typeconsult=$_POST['areaAutretypeconsult'];
					$prixConsult=0;
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

	if(isset ($_POST['datesoins']))
	{
		$datesoins=$_POST['datesoins'];

	}else{
		$datesoins='';
	}
	
	if(isset ($_POST['soins']))
	{
		$soins=nl2br($_POST['soins']);
		$soinsfait=0;
		$soinsexecuter='';
	}
	
	if(isset ($_POST['examen']))
	{
		$examen=nl2br($_POST['examen']);
		$examenfait=0;
		$result='';
	}
	
	$idmed=$_SESSION['id'];
	
	$num=$_GET['num'];
	


	
 	if(isset($_POST['savebtn']))
	{
		// echo '*Le motif: '.$motif.'<br/>*L\'etat du patient: '.$etatpa.'<br/>*Les recommandations: '.$recomm.'<br/>*Les soins: '.$soins.'<br/>*Les examens à faire:'.$examen.'<br/>*Code Medecin: '.$codemed.'<br/>*Numero Patient: '.$num.'<br/>*Code de l\'infirmier: '.$codeI;

		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,heureconsu=:heureconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
				
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'heureconsu'=>$heureToday,
		'motif'=>$motif,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'diagno'=>$prediagno,
		'idMed'=>$idmed,
		'num'=>$num,
		'idtypeconsu'=>$typeconsult,
		'prixtypeconsu'=>$prixConsult,
		'modifierConsu'=>$idconsuAdd,
		))or die( print_r($connexion->errorInfo()));
		
		
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&showfiche=ok&english='.$_GET['english'].'#fichepatient";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&showfiche=ok&francais='.$_GET['francais'].'#fichepatient";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&showfiche=ok#fichepatient";</script>';
			}
		}
		
							

	}
	
 	if(isset($_POST['saveconsubtn']))
	{
		$idassurance=$_POST['assuranceId'];
		$nameassurance=$_POST['assuranceName'];

		if (isset($_GET['idconsu'])) {
			if (isset($_SESSION['codeI'])) {
			
				$poids=$_POST['poids'];
				$taille=$_POST['taille'];
				$tempera=$_POST['tempera'];
				$tensionart=$_POST['tensionart'];
				$pouls=$_POST['pouls'];
				$oxgen=$_POST['oxgen'];

				$updatePa=$connexion->prepare('UPDATE patients SET poidsPa=:poidsPa,taillePa=:taillePa,temperaturePa=:temperaturePa,tensionarteriellePa=:tensionarteriellePa,poulsPa=:poulsPa,oxgen=:oxgen WHERE numero=:num');
						
				$updatePa->execute(array(
				'poidsPa'=>$poids,
				'taillePa'=>$taille,
				'temperaturePa'=>$tempera,
				'tensionarteriellePa'=>$tensionart,
				'poulsPa'=>$pouls,
				'oxgen'=>$oxgen,
				'num'=>$num
				));

				$updatePa=$connexion->prepare('UPDATE consultations SET poids=:poidsPa,taille=:taillePa,temperature=:temperaturePa,tensionart=:tensionarteriellePa,pouls=:poulsPa,oxgen=:oxgen WHERE numero=:num AND id_consu=:id_consu');
						
				$updatePa->execute(array(
				'poidsPa'=>$poids,
				'taillePa'=>$taille,
				'temperaturePa'=>$tempera,
				'tensionarteriellePa'=>$tensionart,
				'poulsPa'=>$pouls,
				'oxgen'=>$oxgen,
				'num'=>$num,
				'id_consu'=>$_GET['idconsu']
				));

			}else{
				$updateConsu = $connexion->prepare('UPDATE consultations SET id_uM=:idMed, id_typeconsult=:idtypeconsu WHERE id_consu=:id_consu');
				$updateConsu->execute(array(
					'idMed'=>$idMedecin,
					'idtypeconsu'=>$typeconsult,
					'id_consu'=>$_GET['idconsu']
				));
			}
		}else{
			if (isset($_SESSION['codeI'])) {
			
				$poids=$_POST['poids'];
				$taille=$_POST['taille'];
				$tempera=$_POST['tempera'];
				$tensionart=$_POST['tensionart'];
				$pouls=$_POST['pouls'];
				$oxgen=$_POST['oxgen'];
			
		
			// echo $idassurance;
			
			//echo 'INSERT INTO consultations (id_uR,dateconsu,tensionart,poids,taille,temperature,pouls,heureconsu,id_uM,numero,id_typeconsult,prixtypeconsult,id_assuConsu,assuranceConsuName) VALUES('.$_SESSION['id'].','.$_POST['dateconsu'].','.$tensionart.','.$poids.','.$taille.','.$tempera.','.$pouls.','.$heureToday.','.$idMedecin.','.$num.','.$typeconsult.','.$prixConsult.','.$idassurance.','.$nameassurance.')<br/>';

				if($_POST['typeconsult']!="autretypeconsult")
				{
					$resultats=$connexion->prepare('INSERT INTO consultations (id_uR,dateconsu,tensionart,poids,taille,temperature,pouls,oxgen,heureconsu,id_uM,numero,id_typeconsult,prixtypeconsult,id_assuConsu,assuranceConsuName) VALUES(:idRec,:dateconsu,:tensionart,:poids,:taille,:temperature,:pouls,:oxgen,:heureconsu,:idMed,:num,:idtypeconsu,:prixtypeconsu,:idassu,:nameassu)');
						
					$resultats->execute(array(
					'idRec'=>$_SESSION['id'],
					'dateconsu'=>$_POST['dateconsu'],
					'tensionart'=>$tensionart,
					'poids'=>$poids,
					'taille'=>$taille,
					'temperature'=>$tempera,
					'pouls'=>$pouls,
					'oxgen'=>$oxgen,
					'heureconsu'=>$heureToday,			
					'idMed'=>$idMedecin,
					'num'=>$num,
					'idtypeconsu'=>$typeconsult,
					'prixtypeconsu'=>$prixConsult,
					'idassu'=>$idassurance,
					'nameassu'=>$nameassurance
					));
				
				}else{

					$resultats=$connexion->prepare('INSERT INTO consultations (id_uR,dateconsu,tensionart,poids,taille,temperature,pouls,oxgen,heureconsu,id_uM,numero,autretypeconsult,prixautretypeconsult,id_assuConsu,assuranceConsuName) VALUES(:idRec,:dateconsu,:heureconsu,:tensionart,:poids,:taille,:temperature,:pouls,:oxgen,:idMed,:num,:autretypeconsu,:prixautretypeconsu,:idassu,:nameassu)');
						
					$resultats->execute(array(
					'idRec'=>$_SESSION['id'],
					'dateconsu'=>$_POST['dateconsu'],
					'tensionart'=>$tensionart,
					'poids'=>$poids,
					'taille'=>$taille,
					'temperature'=>$tempera,
					'pouls'=>$pouls,
					'oxgen'=>$oxgen,
					'heureconsu'=>$heureToday,
					'idMed'=>$idMedecin,
					'num'=>$num,
					'autretypeconsu'=>$typeconsult,
					'prixautretypeconsu'=>$prixConsult,
					'idassu'=>$idassurance,
					'nameassu'=>$nameassurance
					));

				}

				$updatePa=$connexion->prepare('UPDATE patients SET poidsPa=:poidsPa,taillePa=:taillePa,temperaturePa=:temperaturePa,tensionarteriellePa=:tensionarteriellePa,poulsPa=:poulsPa,oxgen=:oxgen WHERE numero=:num');
					
				$updatePa->execute(array(
				'poidsPa'=>$poids,
				'taillePa'=>$taille,
				'temperaturePa'=>$tempera,
				'tensionarteriellePa'=>$tensionart,
				'poulsPa'=>$pouls,
				'oxgen'=>$oxgen,
				'num'=>$num
				));
			}else{
				if($_POST['typeconsult']!="autretypeconsult")
				{
					$resultats=$connexion->prepare('INSERT INTO consultations (id_uR,dateconsu,heureconsu,id_uM,numero,id_typeconsult,prixtypeconsult,id_assuConsu,assuranceConsuName) VALUES(:idRec,:dateconsu,:heureconsu,:idMed,:num,:idtypeconsu,:prixtypeconsu,:idassu,:nameassu)');
						
					$resultats->execute(array(
					'idRec'=>$_SESSION['id'],
					'dateconsu'=>$_POST['dateconsu'],
					'heureconsu'=>$heureToday,			
					'idMed'=>$idMedecin,
					'num'=>$num,
					'idtypeconsu'=>$typeconsult,
					'prixtypeconsu'=>$prixConsult,
					'idassu'=>$idassurance,
					'nameassu'=>$nameassurance
					));
				
				}else{

					$resultats=$connexion->prepare('INSERT INTO consultations (id_uR,dateconsu,heureconsu,id_uM,numero,autretypeconsult,prixautretypeconsult,id_assuConsu,assuranceConsuName) VALUES(:idRec,:dateconsu,:heureconsu,:idMed,:num,:autretypeconsu,:prixautretypeconsu,:idassu,:nameassu)');
						
					$resultats->execute(array(
					'idRec'=>$_SESSION['id'],
					'dateconsu'=>$_POST['dateconsu'],
					'heureconsu'=>$heureToday,
					'idMed'=>$idMedecin,
					'num'=>$num,
					'autretypeconsu'=>$typeconsult,
					'prixautretypeconsu'=>$prixConsult,
					'idassu'=>$idassurance,
					'nameassu'=>$nameassurance
					));

				}
			}	
		}

			
			
			
			
			
		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			// echo '<script type="text/javascript"> alert("Le malade peut aller payé!");</script>';
			
			echo '<script type="text/javascript">document.location.href="consultations.php?english='.$_GET['english'].'&num='.$num.'&typeconsult='.$typeconsult.'&consu=ok&receptioniste=ok";</script>';
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				// echo '<script type="text/javascript"> alert("Le malade peut aller payé!");</script>';
			
				echo '<script type="text/javascript">document.location.href="consultations.php?francais='.$_GET['francais'].'&num='.$num.'&typeconsult='.$typeconsult.'&consu=ok&receptioniste=ok";</script>';
			
			}else{
				// echo '<script type="text/javascript"> alert("Le malade peut aller payé!");</script>';
			
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&typeconsult='.$typeconsult.'&consu=ok&receptioniste=ok";</script>';
			}
		}
		
							

	}

	
 	if(isset($_POST['soinsbtn']))
	{
		$id_consu=$_POST['idConsult'];
		$datesoins=$_POST['datesoins'];
		$poids=$_POST['poids'];
		$taille=$_POST['taille'];
		$tempera=$_POST['tempera'];
		$tensionart=$_POST['tensionart'];
		$pouls=$_POST['pouls'];
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
			
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$_POST['dateconsu'].'&consu=ok"</script>';
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
				echo '<script language="javascript">document.location.href="consultations.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$dateresult.'&consu=ok"</script>';
				
			}else{
			
				if($_FILES['result']['error']== UPLOAD_ERR_FORM_SIZE)
				{
					echo '<script language="javascript"> alert ("La taille de ce document depasse la taille autorisée!");</script>';
					echo '<script language="javascript">document.location.href="consultations.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$dateresult.'&consu=ok"</script>';
				
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
					))or die( print_r($connexion->errorInfo()));
						
					echo '<script type="text/javascript"> alert("Vous venez d\' envoyer les résultats de '.$exam.'");</script>';
					
					echo '<script type="text/javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok";</script>';
							
				}
			}
			
		}else{
			echo '<script language="javascript"> alert("Veuillez charger des résultats!!");</script>';
			echo '<script language="javascript">document.location.href="consultations.php?num='.$num.'&idconsu='.$id_consu.'&dateconsu='.$dateresult.'&consu=ok"</script>';
		}

	}
	
	
	if(isset($_POST['updatebtn']))
	{
		$newmotif=nl2br($_POST['motif']);
		$newetatpa=nl2br($_POST['etatpa']);
		$newantec=nl2br($_POST['antec']);
		$newrecomm=nl2br($_POST['recomm']);
		$newsisympt=nl2br($_POST['sisympt']);
		$newpoids=nl2br($_POST['poids']);
		$newtaille=nl2br($_POST['taille']);
		$newtempera=nl2br($_POST['tempera']);
		$newtensionart=nl2br($_POST['tensionart']);
		$newpouls=nl2br($_POST['pouls']);
		$newoxgen=nl2br($_POST['oxgen']);
		$newprediagno=nl2br($_POST['prediagno']);
		
		$newid_uM=$_SESSION['id'];
		$newnum=$_GET['num'];

		$modifierConsu=$_POST['idopereMedPa'];
		

	// echo $newmotif.'_'.$newetatpa.'_'.$newobserv.'_'.$newrecomm.'_'.$idPa.'_'.$newmotif.'_'.$modifierRdv.'_'.$newheureDbu;		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'taille'=>$taille,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'diagno'=>$prediagno,
		'idMed'=>$newid_uM,
		'num'=>$newnum,
		'idtypeconsu'=>$typeconsult,
		'prixtypeconsu'=>$prixConsult,
		'modifierConsu'=>$modifierConsu,
		))or die( print_r($connexion->errorInfo()));

		
		echo '<script type="text/javascript"> alert("Vous venez de modifier une consultation");</script>';

		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$newnum.'&showfiche=ok&english='.$_GET['english'].'#fichepatient";</script>';
		
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$newnum.'&showfiche=ok&francais='.$_GET['francais'].'#fichepatient";</script>';
			
			}else{
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$newnum.'&showfiche=ok#fichepatient";</script>';
			}
		}
				
	
	}
	
	
	if(isset($_POST['updateconsubtn']))
	{
		$newpoids=nl2br($_POST['poids']);
		$newtaille=nl2br($_POST['taille']);
		$newtempera=nl2br($_POST['tempera']);
		$newtensionart=nl2br($_POST['tensionart']);
		$newpouls=nl2br($_POST['pouls']);
		$newoxgen=nl2br($_POST['oxgen']);
		
		$newid_uM=$_POST['medecins'];
		$newnum=$_GET['num'];

		$modifierConsu=$_GET['idconsu'];
		

	// echo $newmotif.'_'.$newetatpa.'_'.$newobserv.'_'.$newrecomm.'_'.$idPa.'_'.$newmotif.'_'.$modifierRdv.'_'.$newheureDbu;		
		
		$resultats=$connexion->prepare('UPDATE consultations SET poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
						
		$resultats->execute(array(
		'poids'=>$newpoids,
		'taille'=>$newtaille,
		'tempera'=>$newtempera,
		'tensionart'=>$newtensionart,
		'pouls'=>$newpouls,
		'oxgen'=>$newoxgen,
		'idMed'=>$newid_uM,
		'num'=>$newnum,
		'idtypeconsu'=>$typeconsult,
		'prixtypeconsu'=>$prixConsult,
		'modifierConsu'=>$modifierConsu,
		))or die( print_r($connexion->errorInfo()));

			$updatePa=$connexion->prepare('UPDATE patients SET poidsPa=:poidsPa,taillePa=:taillePa,temperaturePa=:temperaturePa,tensionarteriellePa=:tensionarteriellePa,poulsPa=:poulsPa WHERE numero=:num');
				
			$updatePa->execute(array(
			'poidsPa'=>$newpoids,
			'taillePa'=>$newtaille,
			'temperaturePa'=>$newtempera,
			'tensionarteriellePa'=>$newtensionart,
			'poulsPa'=>$newpouls,
			'num'=>$num
			))or die( print_r($connexion->errorInfo()));
			
		
		echo '<script type="text/javascript"> alert("Vous venez de modifier une consultation");</script>';

		if(isset($_GET['english']))
		{
			// echo '&english='.$_GET['english'];
			// echo '<script type="text/javascript"> alert("Le malade peut aller payé!");</script>';
			
			echo '<script type="text/javascript">document.location.href="consultations.php?english='.$_GET['english'].'&num='.$num.'&typeconsult='.$typeconsult.'&consu=ok&receptioniste=ok";</script>';
		}else{
			if(isset($_GET['francais']))
			{
				// echo '&francais='.$_GET['francais'];
				// echo '<script type="text/javascript"> alert("Le malade peut aller payé!");</script>';
			
				echo '<script type="text/javascript">document.location.href="consultations.php?francais='.$_GET['francais'].'&num='.$num.'&typeconsult='.$typeconsult.'&consu=ok&receptioniste=ok";</script>';
			
			}else{
				// echo '<script type="text/javascript"> alert("Le malade peut aller payé!");</script>';
			
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$num.'&typeconsult='.$typeconsult.'&consu=ok&receptioniste=ok";</script>';
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
		
		$dateconsu = $_GET['dateconsu'];
		$numero = $_GET['num'];
		$id_uM = $_GET['idMed'];
		$soinsfait=0;
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
					
		$resultats->execute(array(
		'dateconsu'=>$_POST['dateconsu'],
		'motif'=>$motif,
		'etatpa'=>$etatpa,
		'antec'=>$antec,
		'recomm'=>$recomm,
		'sisympt'=>$sisympt,
		'poids'=>$poids,
		'tempera'=>$tempera,
		'tensionart'=>$tensionart,
		'pouls'=>$pouls,
		'oxgen'=>$oxgen,
		'diagno'=>$prediagno,
		'idMed'=>$id_uM,
		'num'=>$numero,
		'idtypeconsu'=>$typeconsult,
		'prixtypeconsu'=>$prixConsult,
		'modifierConsu'=>$idconsuAdd,
		))or die( print_r($connexion->errorInfo()));
	
		
		if(isset($_POST['addNursery']))
		{
			if($_POST['soins'] != 'autresoins')
			{
				$idPresta=$_POST['soins'];
				
				if($idPresta != 0)
				{
					// echo $idPresta.','.$soinsfait.','.$dateconsu.','.$id_uM.','.$numero;

					$prixprestainf=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
					$prixprestainf->execute(array(
					'idPresta'=>$_POST['soins']
					));
					
					$prixprestainf->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPrixtypeconsult=$prixprestainf->rowCount();
					
					if($lignePrixprestainf=$prixprestainf->fetch())
					{
						$prixInf=$lignePrixprestainf->prixpresta;
					}
				
				
					
					$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,soinsfait,numero,id_uM,id_consuInf) VALUES(:dateconsu,:idPresta,:prixPresta,:soinsfait,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>$dateconsu,
					'idPresta'=>$idPresta,
					'prixPresta'=>$prixInf,
					'soinsfait'=>$soinsfait,					
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
					
				
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
					// echo $idAutrePresta.','.$soinsfait.','.$dateconsu.','.$id_uM.','.$numero;	
					
					$resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,soinsfait,autrePrestaM,numero,id_uM,id_consuInf) VALUES(:dateconsu,:soinsfait,:autrePrestaM,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>$dateconsu,
					'soinsfait'=>$soinsfait,
					'autrePrestaM'=>$idAutrePresta,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idconsuAdd'=>$idconsuAdd
					)) or die( print_r($connexion->errorInfo()));
					
				}
			}
		}
			
			
		if(isset($_GET['idconsuNext']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsuNext='.$idconsuAdd.'#forBilling";</script>';
		
		}else{
			if(isset($_GET['idconsu']))
			{
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
			
			}else{
			
				if(isset($_GET['idconsult']))
				{
					echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
				
				}
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
		
		$dateconsu = $_GET['dateconsu'];
		$numero = $_GET['num'];
		$id_uM = $_GET['idMed'];
		$examenfait=0;
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=oxgen,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'motif'=>$motif,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'diagno'=>$prediagno,
			'idMed'=>$id_uM,
			'num'=>$numero,
			'idtypeconsu'=>$typeconsult,
			'prixtypeconsu'=>$prixConsult,
			'modifierConsu'=>$idconsuAdd,
			))or die( print_r($connexion->errorInfo()));
			
		if(isset($_POST['addExam']))
		{	
			if($_POST['listexamen'] != 'autreexamen')
			{
				$idPresta=$_POST['listexamen'];
				
				if($idPresta != 0)
				{
					// echo $idPresta.','.$examenfait.','.$dateconsu.','.$id_uM.','.$numero;
					
					$prixprestalabo=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
					$prixprestalabo->execute(array(
					'idPresta'=>$_POST['listexamen']
					));
					
					$prixprestalabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

					$comptPrixprestalabo=$prixprestalabo->rowCount();
					
					if($lignePrixprestalabo=$prixprestalabo->fetch())
					{
						$prixLabo=$lignePrixprestalabo->prixpresta;
					}
				
				
					$resultat=$connexion->prepare('INSERT INTO med_labo (dateconsu,id_prestationExa,prixprestationExa,examenfait,numero,id_uM,id_consuLabo) VALUES(:dateconsu,:idPresta,:prixPresta,:examenfait,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>nl2br($dateconsu),
					'idPresta'=>nl2br($idPresta),
					'prixPresta'=>$prixLabo,
					'examenfait'=>nl2br($examenfait),					
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
		
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
					// echo $idAutreExam.','.$examenfait.','.$dateconsu.','.$id_uM.','.$numero;	
					
					$resultat=$connexion->prepare('INSERT INTO med_labo (dateconsu,examenfait,autreExamen,numero,id_uM,id_consuLabo) VALUES(:dateconsu,:examenfait,:autreExamen,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>nl2br($dateconsu),
					'examenfait'=>nl2br($examenfait),
					'autreExamen'=>nl2br($idAutreExam),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
					
				}
			}
		}
			
		if(isset($_GET['idconsuNext']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsuNext='.$idconsuAdd.'#forBilling";</script>';
		
		}else{
			if(isset($_GET['idconsu']))
			{
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
			
			}else{
			
				if(isset($_GET['idconsult']))
				{
					echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
				
				}
			}		
		}
		
	}
	
	
	if(isset($_POST['addConsult']) OR isset($_POST['addAutreConsult']))
	{
		// echo $_POST['addConsult'];
		
		
		if(isset($_GET['idconsuNext']))
		{
			$idconsuAdd = $_GET['idconsuNext'];
		}
		
		if(isset($_GET['idconsu']))
		{
			$idconsuAdd = $_GET['idconsu'];
		}
		
		$dateconsu = $_GET['dateconsu'];
		$numero = $_GET['num'];
		$id_uM = $_GET['idMed'];
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'motif'=>$motif,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'diagno'=>$prediagno,
			'idMed'=>$id_uM,
			'num'=>$numero,
			'idtypeconsu'=>$typeconsult,
			'prixtypeconsu'=>$prixConsult,
			'modifierConsu'=>$idconsuAdd,
			))or die( print_r($connexion->errorInfo()));
			
		if(isset($_POST['addConsult']))
		{
			if($_POST['consult'] != 'autreconsult')
			{
				$idPrestaConsu=$_POST['consult'];
				
				if($idPrestaConsu != 0)
				{
					// echo $idPrestaConsu.','.$autreConsu.','.$dateconsu.','.$id_uM.','.$numero;
					
					$prixprestaconsu=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');		
					$prixprestaconsu->execute(array(
					'idPresta'=>$_POST['consult']
					));
					
					$prixprestaconsu->setFetchMode(PDO::FETCH_OBJ);

					$comptPrixprestaconsu=$prixprestaconsu->rowCount();
					
					if($lignePrixprestaconsu=$prixprestaconsu->fetch())
					{
						$prixConsu=$lignePrixprestaconsu->prixpresta;
					}
				
				
					$resultat=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,numero,id_uM,id_consuMed) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>nl2br($dateconsu),
					'idPrestaConsu'=>nl2br($idPrestaConsu),
					'prixPrestaConsu'=>$prixConsu,
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
					
					
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
					// echo $idAutreConsult.','.$dateconsu.','.$id_uM.','.$numero;	
					
					$resultat=$connexion->prepare('INSERT INTO med_consult (dateconsu,autreConsu,numero,id_uM,id_consuMed) VALUES(:dateconsu,:autreConsu,:numero,:id_uM,:idconsuAdd)');
					$resultat->execute(array(
					'dateconsu'=>nl2br($dateconsu),
					'autreConsu'=>nl2br($idAutreConsult),
					'numero'=>nl2br($numero),
					'id_uM'=>nl2br($id_uM),
					'idconsuAdd'=>nl2br($idconsuAdd)
					)) or die( print_r($connexion->errorInfo()));
					
					
				}
			}
		}
		
			
		if(isset($_GET['idconsuNext']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsuNext='.$idconsuAdd.'#forBilling";</script>';
		
		}else{
			if(isset($_GET['idconsu']))
			{
				echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
			
			}else{
			
				if(isset($_GET['idconsult']))
				{
					echo '<script type="text/javascript">document.location.href="consultations.php?num='.$numero.'&consu=ok&forBilling=ok&idconsu='.$idconsuAdd.'#forBilling";</script>';
				
				}
			}		
		}
		
	}
	
		// echo $_POST['deleteMedconsu'.$_POST['deleteIdMedconsu'].''];
		
	if(isset($_POST['deleteMedconsu']))
	{
		
		$numero = $_GET['num'];
		$id_uM = $_GET['idMed'];
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'motif'=>$motif,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'diagno'=>$prediagno,
			'idMed'=>$id_uM,
			'num'=>$numero,
			'idtypeconsu'=>$typeconsult,
			'prixtypeconsu'=>$prixConsult,
			'modifierConsu'=>$idconsuAdd,
			))or die( print_r($connexion->errorInfo()));


	
		$resultats=$connexion->prepare('DELETE FROM med_consult WHERE id_medconsu=:id_medC');
		
		$resultats->execute(array(
		'id_medC'=>$_POST['deleteIdMedconsu']
		
		))or die($resultats->errorInfo());

		// echo '<script type="text/javascript"> alert("L\'article '.$_POST['deleteIdMedconsu'].' a bien été supprimé");</script>';
		
		if(isset($_GET['idconsuNext']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$_GET['num'].'&idconsuNext='.$_GET['idconsuNext'].'&delete=ok&consu=ok&forBilling=ok#forBilling"</script>';
		}
		
		if(isset($_GET['idconsu']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&delete=ok&consu=ok&forBilling=ok#forBilling"</script>';
		}
		
	}
	
		
	if(isset($_POST['deleteMedinf']))
	{
	
		$numero = $_GET['num'];
		$id_uM = $_GET['idMed'];
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen=:oxgen,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'motif'=>$motif,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'diagno'=>$prediagno,
			'idMed'=>$id_uM,
			'num'=>$numero,
			'idtypeconsu'=>$typeconsult,
			'prixtypeconsu'=>$prixConsult,
			'modifierConsu'=>$idconsuAdd,
			))or die( print_r($connexion->errorInfo()));


	
		$resultats=$connexion->prepare('DELETE FROM med_inf WHERE id_medinf=:id_medI');
		
		$resultats->execute(array(
		'id_medI'=>$_POST['deleteIdMedinf']
		
		))or die($resultats->errorInfo());

		// echo '<script type="text/javascript"> alert("L\'article '.$_POST['deleteIdMedinf'].' a bien été supprimé");</script>';

		
		if(isset($_GET['idconsuNext']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$_GET['num'].'&idconsuNext='.$_GET['idconsuNext'].'&delete=ok&consu=ok&forBilling=ok#forBilling"</script>';
		}
		
		if(isset($_GET['idconsu']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&delete=ok&consu=ok&forBilling=ok#forBilling"</script>';
		}
		
	}
	
		
	if(isset($_POST['deleteMedlabo']))
	{
	
		$numero = $_GET['num'];
		$id_uM = $_GET['idMed'];
		
		
		$resultats=$connexion->prepare('UPDATE consultations SET dateconsu=:dateconsu,motif=:motif,etatpatient=:etatpa,antecedent=:antec,recommandations=:recomm,signsymptomes=:sisympt,poids=:poids,taille=:taille,temperature=:tempera,tensionart=:tensionart,pouls=:pouls,oxgen,=>oxgen,diagnostic=:diagno,id_uM=:idMed,numero=:num,id_typeconsult=:idtypeconsu,prixtypeconsult=:prixtypeconsu WHERE id_consu=:modifierConsu');
					
			$resultats->execute(array(
			'dateconsu'=>$_POST['dateconsu'],
			'motif'=>$motif,
			'etatpa'=>$etatpa,
			'antec'=>$antec,
			'recomm'=>$recomm,
			'sisympt'=>$sisympt,
			'poids'=>$poids,
			'taille'=>$taille,
			'tempera'=>$tempera,
			'tensionart'=>$tensionart,
			'pouls'=>$pouls,
			'oxgen'=>$oxgen,
			'diagno'=>$prediagno,
			'idMed'=>$id_uM,
			'num'=>$numero,
			'idtypeconsu'=>$typeconsult,
			'prixtypeconsu'=>$prixConsult,
			'modifierConsu'=>$idconsuAdd,
			))or die( print_r($connexion->errorInfo()));


		$resultats=$connexion->prepare('DELETE FROM med_labo WHERE id_medlabo=:id_medL');
		
		$resultats->execute(array(
		'id_medL'=>$_POST['deleteIdMedlabo']
		
		))or die($resultats->errorInfo());

		// echo '<script type="text/javascript"> alert("L\'article '.$_GET['deleteIdMedlabo'].' a bien été supprimé");</script>';

		if(isset($_GET['idconsuNext']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$_GET['num'].'&idconsuNext='.$_GET['idconsuNext'].'&delete=ok&consu=ok&forBilling=ok#forBilling"</script>';
		}
		
		if(isset($_GET['idconsu']))
		{
			echo '<script type="text/javascript">document.location.href="consultations.php?num='.$_GET['num'].'&idconsu='.$_GET['idconsu'].'&delete=ok&consu=ok&forBilling=ok#forBilling"</script>';
		}
	}

	
}

catch(Excepton $e)
{
	echo 'Erreur:'.$e->getMessage().'<br/>';
	echo'Numero:'.$e->getCode();
}



?>