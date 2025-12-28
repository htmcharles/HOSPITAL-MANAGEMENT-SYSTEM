<?php
session_start();
include("connect.php");
include("connectLangues.php");


	if(isset($_GET['english']))
	{
		$langue="&english=english";				
	
	}else{
		if(isset($_GET['francais']))
		{
			$langue="&francais=francais";
			
		}else{
			$langue="";
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


	$annee = date('Y').'-'.date('m').'-'.date('d');


	$idmedlabo=$_GET['idmedLab'];
	$idassu=$_GET['idassu'];
	$numero=$_GET['num'];
	$id_uM=$_GET['idmed'];
	$idlabo=$_SESSION['id'];
	$dateresultats=$annee;


			
	if(isset($_POST['addbtn']))
	{	
		if(isset($_POST['checkprestaExam']))
		{
			$addLab = array();
			foreach($_POST['checkprestaExam'] as $valeurLab)
			{
				$addLab[] = $valeurLab; 
			}
			
			
			for($i=0;$i<sizeof($addLab);$i++)
			{

				$resultatLabo=$connexion->prepare('INSERT INTO more_med_labo (id_medlabo,id_prestationExa,id_assuLab,numero,id_uM,id_uL) VALUES(:idmedlabo,:idPrestaLab,:idassu,:numero,:id_uM,:idlabo)');
				$resultatLabo->execute(array(
				'idmedlabo'=>$idmedlabo,
				'idPrestaLab'=>$addLab[$i],
				'idassu'=>$idassu,
				'numero'=>$numero,
				'id_uM'=>$id_uM,
				'idlabo'=>$idlabo,
				)) or die( print_r($connexion->errorInfo()));

			}
		}


		if($_POST['autreprestaExam']!="")
		{
			$addautreLab = array();
			foreach($_POST['autreprestaExam'] as $valeurautreLab)
			{
				$addautreLab[] = $valeurautreLab; 
			}
			
			
			for($i=0;$i<sizeof($addautreLab);$i++)
			{
				
				if($addautreLab[$i]!="")
				{
					$resultatLab=$connexion->prepare('INSERT INTO more_med_labo (id_medlabo,autreExamen,id_assuLab,numero,id_uM,id_uL) VALUES(:idmedlabo,:autreprestaExam,:idassu,:numero,:id_uM,:idlabo)');
					
					$resultatLab->execute(array(
					'idmedlabo'=>$idmedlabo,
					'autreprestaExam'=>$addautreLab[$i],
					'idassu'=>$idassu,
					'numero'=>$numero,
					'id_uM'=>$id_uM,
					'idlabo'=>$idlabo,
					)) or die( print_r($connexion->errorInfo()));
				}
			}
			
		}


				if(isset($_GET['english']))
				{
					$langue='&english='.$_GET['english'];

				}else{
					if(isset($_GET['francais']))
					{
						$langue='&francais='.$_GET['francais'];
						
					}else{
						$langue='';
					}
				}

				if(isset($_GET['updateidmoremedLabo']))
				{
					$updateidmoremedLabo="&updateidmoremedLabo=ok";				
				}else{
					$updateidmoremedLabo="";				
				}
			
			
			$updateMedLabo=$connexion->query('UPDATE med_labo ml SET ml.moreresultats=1,ml.examenfait=1,ml.dateresultats=\''.$dateresultats.'\',ml.id_uL='.$idlabo.' WHERE ml.id_medlabo='.$idmedlabo.'');
			
			echo '<script text="text/javascript">document.location.href="moreresultats.php?num='.$_GET['num'].'&idmedLab='.$_GET['idmedLab'].'&idmed='.$_GET['idmed'].'&dateconsu='.$_GET['dateconsu'].'&presta='.$_GET['presta'].'&idassu='.$_GET['idassu'].'&examenPa=ok&back=ok'.$langue.''.$updateidmoremedLabo.'"</script>';
	}
	
	
	if(isset($_POST['addspermobtn']))
	{
		
		$resultLab=$connexion->prepare('INSERT INTO spermo_med_labo (id_medlabo,volume,densite,viscosite,ph,aspect,examdirect,zeroheureafter,uneheureafter,deuxheureafter,troisheureafter,quatreheureafter,numeration,vn,formtypik,formatypik,autre,conclusion,id_assuLab,numero,id_uM,id_uL) VALUES(:idmedlabo,:volume,:densite,:viscosite,:ph,:aspect,:examdirect,:zeroheureafter,:uneheureafter,:deuxheureafter,:troisheureafter,:quatreheureafter,:numeration,:vn,:formtypik,:formatypik,:autre,:conclusion,:idassu,:numero,:id_uM,:idlabo)');
		
		$resultLab->execute(array(
		'idmedlabo'=>$idmedlabo,
		'volume'=>$_POST['volume'],
		'densite'=>$_POST['densite'],
		'viscosite'=>$_POST['viscosite'],
		'ph'=>$_POST['ph'],
		'aspect'=>$_POST['aspect'],
		'examdirect'=>$_POST['examdirect'],
		'zeroheureafter'=>$_POST['zeroheureafter'],
		'uneheureafter'=>$_POST['uneheureafter'],
		'deuxheureafter'=>$_POST['deuxheureafter'],
		'troisheureafter'=>$_POST['troisheureafter'],
		'quatreheureafter'=>$_POST['quatreheureafter'],
		'numeration'=>$_POST['numeration'],
		'vn'=>$_POST['vn'],
		'formtypik'=>$_POST['formtypik'],
		'formatypik'=>$_POST['formatypik'],
		'autre'=>$_POST['autre'],
		'conclusion'=>$_POST['conclusion'],
		'idassu'=>$idassu,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idlabo'=>$idlabo
		)) or die( print_r($connexion->errorInfo()));
		
		$updateMedLabo=$connexion->query('UPDATE med_labo ml SET ml.moreresultats=2,ml.examenfait=1,ml.dateresultats=\''.$dateresultats.'\',ml.id_uL='.$idlabo.' WHERE ml.id_medlabo='.$idmedlabo.'');
			
		echo '<script type="text/javascript"> alert("Results sent!");</script>';
		echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
						
	}
	
	if(isset($_POST['updatespermobtn']))
	{
		
		$resultLab=$connexion->prepare('UPDATE spermo_med_labo sml SET sml.id_medlabo=:idmedlabo,sml.volume=:volume,sml.densite=:densite,sml.viscosite=:viscosite,sml.ph=:ph,sml.aspect=:aspect,sml.examdirect=:examdirect,sml.zeroheureafter=:zeroheureafter,sml.uneheureafter=:uneheureafter,sml.deuxheureafter=:deuxheureafter,sml.troisheureafter=:troisheureafter,sml.quatreheureafter=:quatreheureafter,sml.numeration=:numeration,sml.vn=:vn,sml.formtypik=:formtypik,sml.formatypik=:formatypik,sml.autre=:autre,sml.conclusion=:conclusion,sml.id_assuLab=:idassu,sml.numero=:numero,sml.id_uM=:id_uM,sml.id_uL=:idlabo WHERE sml.id_medlabo='.$idmedlabo.'');
		
		$resultLab->execute(array(
		'idmedlabo'=>$idmedlabo,
		'volume'=>$_POST['volume'],
		'densite'=>$_POST['densite'],
		'viscosite'=>$_POST['viscosite'],
		'ph'=>$_POST['ph'],
		'aspect'=>$_POST['aspect'],
		'examdirect'=>$_POST['examdirect'],
		'zeroheureafter'=>$_POST['zeroheureafter'],
		'uneheureafter'=>$_POST['uneheureafter'],
		'deuxheureafter'=>$_POST['deuxheureafter'],
		'troisheureafter'=>$_POST['troisheureafter'],
		'quatreheureafter'=>$_POST['quatreheureafter'],
		'numeration'=>$_POST['numeration'],
		'vn'=>$_POST['vn'],
		'formtypik'=>$_POST['formtypik'],
		'formatypik'=>$_POST['formatypik'],
		'autre'=>$_POST['autre'],
		'conclusion'=>$_POST['conclusion'],
		'idassu'=>$idassu,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idlabo'=>$idlabo
		)) or die( print_r($connexion->errorInfo()));
		
		$updateMedLabo=$connexion->query('UPDATE med_labo ml SET ml.moreresultats=2,ml.examenfait=1,ml.dateresultats=\''.$dateresultats.'\',ml.id_uL='.$idlabo.' WHERE ml.id_medlabo='.$idmedlabo.'');
			
		echo '<script type="text/javascript"> alert("Results sent!");</script>';
		echo '<script language="javascript">document.location.href="patients1.php?num='.$num.'&examenPa=ok'.$langue.'"</script>';
						
	}
?>