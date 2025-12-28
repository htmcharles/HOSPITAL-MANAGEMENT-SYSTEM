<?php
session_start();

include("connectLangues.php");
include("connect.php");
include("serialNumber.php");



	$annee = date('Y').'-'.date('m').'-'.date('d');


if(isset($_GET['num']))
{

	$result=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
	$result->execute(array(
	'operation'=>$_GET['num']	
	));
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	
	while($ligne=$result->fetch())
	{
	$num=$ligne->numero;
	$nom_uti=$ligne->nom_u;
	$prenom_uti=$ligne->prenom_u;
	$sexe=$ligne->sexe;
	$dateN=$ligne->date_naissance;
	$province=$ligne->province;
	$district=$ligne->district;
	$secteur=$ligne->secteur;
	$profession=$ligne->profession;
	$assuId=$ligne->id_assurance;
	$site=$_GET['num'];
	}
	$result->closeCursor();
	
	

	$old=$dateN[0].''.$dateN[1].''.$dateN[2].''.$dateN[3].'	';//reçoit l'année de naissance
	$month=$dateN[5].''.$dateN[6].'	';//reçoit le mois de naissance

	$an= date('Y')-$old.'	';//recupere l'âge en année
	$mois= date('m')-$month.'	';//recupere l'âge en mois

	if($mois<0)
	{
		$an= ($an-1).' ans	'.(12+$mois).' mois';
		// echo $an= $an-1;

	}else{

		$an= $an.' ans';
		//$an= $an.' ans	'.(date('m')-$month).' mois';// X ans Y mois
		// echo $mois= date('m')-$month;
	}
	
}

	if (isset($_GET['id_consu']) && isset($_GET['deleteHospRecom'])) {

	    /*update consultation*/
	    $updateconsu = $connexion->prepare('UPDATE consultations SET hospitalized=NULL,motifhospitalized=NULL WHERE id_consu=:id_consu');
	    $updateconsu->execute(array(
	      'id_consu'=>$_GET['id_consu']
	    ));

	    echo "<script>alert('Well done!!!');</script>";
	}


	if(isset($_GET['sortie']))
	{
		if($_GET['createBill']==1)
		{
			createON('H');
		}
		
		if(isset($_GET['id_consuHosp']))
		{
			$id_consuHosp=$_GET['id_consuHosp'];

            /*----------Update Consultations Clinic----------------*/

            $updateroom=$connexion->prepare('UPDATE consultations SET hospitalized=0 WHERE id_consu=:id_consu ');
            $updateroom->execute(array(
                'id_consu'=>$id_consuHosp
            ));

        }
		
		$idBilling=$_GET['idbill'];
		$idhosp=$_GET['idhosp'];
		$heureSortie = date('H').':'.date('i').':'.date('s');
		$statusPaHosp = 0;
		$numroom=$_GET['numroom'];

				
		/*----------Update Hosp----------------*/
		
		$updateIdPatientHosp=$connexion->prepare('UPDATE patients_hosp ph SET ph.id_factureHosp=:idbill, ph.statusPaHosp=:statusPaHosp, ph.codecashierHosp=:codecash WHERE ph.id_hosp=:idhosp');

		$updateIdPatientHosp->execute(array(
		'idhosp'=>$_GET['idhosp'],
		'idbill'=>$idBilling,
		'statusPaHosp'=>$statusPaHosp,
		'codecash'=>$_SESSION['codeCash']
		
		));
			
				/*----------Update Rooms----------------*/
				
				if($_GET['numlit']=='1')
				{
					$statusA=0;
										
					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusA=:statusA WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusA'=>$statusA,
					'numroom'=>$numroom
					
					));
					
					
				}elseif($_GET['numlit']=='2'){
				
					$statusB=0;
										
					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusB=:statusB WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusB'=>$statusB,
					'numroom'=>$numroom
					
					));
					
				}elseif($_GET['numlit']=='3'){

					$statusC=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusC=:statusC WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusC'=>$statusC,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='4'){

					$statusD=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusD=:statusD WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusD'=>$statusD,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='5'){

					$statusE=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusE=:statusE WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusE'=>$statusE,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='6'){

					$statusF=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusF=:statusF WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusF'=>$statusF,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='7'){

					$statusG=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusG=:statusG WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusG'=>$statusG,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='8'){

					$statusH=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusH=:statusH WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusH'=>$statusH,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='9'){

					$statusI=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusI=:statusI WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusI'=>$statusI,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='10'){

					$statusJ=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusJ=:statusJ WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusJ'=>$statusJ,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='11'){

					$statusK=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusK=:statusK WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusK'=>$statusK,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='12'){

					$statusL=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusL=:statusL WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusL'=>$statusL,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='13'){

					$statusM=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusM=:statusM WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusM'=>$statusM,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='14'){

					$statusN=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusN=:statusN WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusN'=>$statusN,
					'numroom'=>$numroom

					));

				}
		


        /*----------Update Med_Surge----------------*/
		
		$updateIdFactureMedSurge=$connexion->prepare('UPDATE med_surge_hosp ms SET ms.id_factureMedSurge=:idbill, ms.codecashier=:codecashier WHERE ms.id_hospSurge=:idhosp AND ms.numero=:num AND ms.id_factureMedSurge=""');

		$updateIdFactureMedSurge->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'] 
		
		));
		
		
		
		/*----------Update Med_Inf----------------*/

		$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.id_factureMedInf=:idbill, mi.codecashier=:codecashier WHERE mi.id_hospInf=:idhosp AND mi.numero=:num AND mi.id_factureMedInf=""');

		$updateIdFactureMedInf->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']

		));



		/*----------Update Med_Labo----------------*/
		
		$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.id_factureMedLabo=:idbill, ml.codecashier=:codecashier WHERE ml.id_hospLabo=:idhosp AND ml.numero=:num AND ml.id_factureMedLabo=""');

		$updateIdFactureMedLabo->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		));
		
		
		
		/*----------Update Med_Radio----------------*/
		
		$updateIdFactureMedRadio=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.id_factureMedRadio=:idbill, mr.codecashier=:codecashier WHERE mr.id_hospRadio=:idhosp AND mr.numero=:num AND mr.id_factureMedRadio=""');

		$updateIdFactureMedRadio->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		));
		
		
		
		/*----------Update Med_Consom----------------*/
		
		$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.id_factureMedConsom=:idbill, mco.codecashier=:codecashier WHERE mco.id_hospConsom=:idhosp AND mco.numero=:num AND mco.id_factureMedConsom=""');

		$updateIdFactureMedConsom->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		));
		
		
		
		/*----------Update Med_Medoc----------------*/
		
		$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.id_factureMedMedoc=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_hospMedoc=:idhosp AND mdo.numero=:num AND mdo.id_factureMedMedoc=""');

		$updateIdFactureMedMedoc->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']
		
		));



		/*----------Update Med_Kine----------------*/

		$updateIdFactureMedKine=$connexion->prepare('UPDATE med_kine_hosp mk SET mk.id_factureMedKine=:idbill, mk.codecashier=:codecashier WHERE mk.id_hospKine=:idhosp AND mk.numero=:num AND mk.id_factureMedKine=""');

		$updateIdFactureMedKine->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']

		));



		/*----------Update Med_Ortho----------------*/

		$updateIdFactureMedOrtho=$connexion->prepare('UPDATE med_ortho_hosp mo SET mo.id_factureMedOrtho=:idbill, mo.codecashier=:codecashier WHERE mo.id_hospOrtho=:idhosp AND mo.numero=:num AND mo.id_factureMedOrtho=""');

		$updateIdFactureMedOrtho->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash']

		));



        /*----------Update Med_Consult----------------*/

        $updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.id_factureMedConsu=:idbill, mc.codecashier=:codecashier WHERE mc.id_hospMed=:idhosp AND mc.numero=:num AND mc.id_factureMedConsu=""');

        $updateIdFactureMedConsult->execute(array(
            'idbill'=>$idBilling,
            'num'=>$_GET['numPa'],
            'idhosp'=>$idhosp,
            'codecashier'=>$_SESSION['codeCash']

        ));




    }

    if (isset($_GET['datefacturedebut'])) {
    	$datedebut = $_GET['datefacturedebut'];
    }

	if (isset($_GET['datefacturefin'])) {
    	$datefin = $_GET['datefacturefin'];
    }

    if(isset($_GET['facturer']))
	{
		if($_GET['createBill']==1)
		{
			createON('H');
		}
		
		if(isset($_GET['id_consuHosp']))
		{
			$id_consuHosp=$_GET['id_consuHosp'];

            /*----------Update Consultations Clinic----------------*/

            $updateroom=$connexion->prepare('UPDATE consultations SET hospitalized=0 WHERE id_consu=:id_consu ');
            $updateroom->execute(array(
                'id_consu'=>$id_consuHosp
            ));

        }
		
		$idBilling=$_GET['idbill'];
		$idhosp=$_GET['idhosp'];
		//$heureSortie = date('H').':'.date('i').':'.date('s');
		$statusPaHosp = 0;
		$numroom=$_GET['numroom'];

$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		/*----------Insert into jourhosp_fact----------------*/
		
		$insertjourHosp_fact=$connexion->prepare('INSERT INTO jourHosp_fact(numbill,id_hosp,numero,datedebut,datefin,codecashier) VALUES(:numbill,:idhosp,:numero,:datedebut,:datefin,:codecashier)');

		$insertjourHosp_fact->execute(array(
		'numbill'=>$_GET['idbill'],
		'idhosp'=>$_GET['idhosp'],
		'numero'=>$_GET['numPa'],
		'datedebut'=>$_GET['datefacturedebut'],
		'datefin'=>$_GET['datefacturefin'],
		'codecashier'=>$_SESSION['codeCash']
		
		));
		

				
		/*----------Update Hosp----------------*/
		
		/*$updateIdPatientHosp=$connexion->prepare('UPDATE patients_hosp ph SET ph.id_factureHosp=:idbill, ph.statusPaHosp=:statusPaHosp, ph.codecashierHosp=:codecash WHERE ph.id_hosp=:idhosp');

		$updateIdPatientHosp->execute(array(
		'idhosp'=>$_GET['idhosp'],
		'idbill'=>$idBilling,
		'statusPaHosp'=>$statusPaHosp,
		'codecash'=>$_SESSION['codeCash']
		
		));
		*/
		/*----------Update Restauration Hosp----------------*/
		
		/*$updateIdPatientHosp=$connexion->prepare('UPDATE restauration r SET r.id_factureHosp=:idbill, r.statusPaResto=:statusPaResto, r.codecashierHosp=:codecash WHERE r.id_resto=:idresto');

		$updateIdPatientHosp->execute(array(
		'idresto'=>$_GET['idresto'],
		'idbill'=>$idBilling,
		'statusPaResto'=>$statusPaHosp,
		'codecash'=>$_SESSION['codeCash']
		
		));*/
		
		/*----------Update Tour de salle Hosp----------------*/
		
		/*$updateIdPatientHosp=$connexion->prepare('UPDATE tour_de_salle ts SET ts.id_factureHosp=:idbill, ts.statusPa=:statusPa, ts.codecashierHosp=:codecash WHERE ts.id_tour_de_salle=:idtourdesalle');

		$updateIdPatientHosp->execute(array(
		'idtourdesalle'=>$_GET['idtourdesalle'],
		'idbill'=>$idBilling,
		'statusPa'=>$statusPaHosp,
		'codecash'=>$_SESSION['codeCash']
		
		));*/
			
				/*----------Update Rooms----------------*/
				
				/*if($_GET['numlit']=='1')
				{
					$statusA=0;
										
					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusA=:statusA WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusA'=>$statusA,
					'numroom'=>$numroom
					
					));
					
					
				}elseif($_GET['numlit']=='2'){
				
					$statusB=0;
										
					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusB=:statusB WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusB'=>$statusB,
					'numroom'=>$numroom
					
					));
					
				}elseif($_GET['numlit']=='3'){

					$statusC=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusC=:statusC WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusC'=>$statusC,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='4'){

					$statusD=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusD=:statusD WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusD'=>$statusD,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='5'){

					$statusE=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusE=:statusE WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusE'=>$statusE,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='6'){

					$statusF=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusF=:statusF WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusF'=>$statusF,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='7'){

					$statusG=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusG=:statusG WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusG'=>$statusG,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='8'){

					$statusH=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusH=:statusH WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusH'=>$statusH,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='9'){

					$statusI=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusI=:statusI WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusI'=>$statusI,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='10'){

					$statusJ=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusJ=:statusJ WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusJ'=>$statusJ,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='11'){

					$statusK=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusK=:statusK WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusK'=>$statusK,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='12'){

					$statusL=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusL=:statusL WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusL'=>$statusL,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='13'){

					$statusM=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusM=:statusM WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusM'=>$statusM,
					'numroom'=>$numroom

					));

				}elseif($_GET['numlit']=='14'){

					$statusN=0;

					$updateIdRoomHosp=$connexion->prepare('UPDATE rooms r SET r.statusN=:statusN WHERE r.numroom=:numroom');

					$updateIdRoomHosp->execute(array(
					'statusN'=>$statusN,
					'numroom'=>$numroom

					));

				}
		
*/

        /*----------Update Med_Surge----------------*/
		
		$updateIdFactureMedSurge=$connexion->prepare('UPDATE med_surge_hosp ms SET ms.id_factureMedSurge=:idbill, ms.codecashier=:codecashier WHERE ms.id_hospSurge=:idhosp AND ms.numero=:num AND ms.id_factureMedSurge="" AND ms.datehosp>=:datedebut AND ms.datehosp<=:datefin');

		$updateIdFactureMedSurge->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		));
		
		
		
		/*----------Update Med_Inf----------------*/

		$updateIdFactureMedInf=$connexion->prepare('UPDATE med_inf_hosp mi SET mi.id_factureMedInf=:idbill, mi.codecashier=:codecashier WHERE mi.id_hospInf=:idhosp AND mi.numero=:num AND mi.id_factureMedInf="" AND mi.datehosp>=:datedebut AND mi.datehosp<=:datefin');

		$updateIdFactureMedInf->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 

		));



		/*----------Update Med_Labo----------------*/
		
		$updateIdFactureMedLabo=$connexion->prepare('UPDATE med_labo_hosp ml SET ml.id_factureMedLabo=:idbill, ml.codecashier=:codecashier WHERE ml.id_hospLabo=:idhosp AND ml.numero=:num AND ml.id_factureMedLabo="" AND ml.datehosp>=:datedebut AND ml.datehosp<=:datefin');

		$updateIdFactureMedLabo->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		));
		
		
		
		/*----------Update Med_Radio----------------*/
		
		$updateIdFactureMedRadio=$connexion->prepare('UPDATE med_radio_hosp mr SET mr.id_factureMedRadio=:idbill, mr.codecashier=:codecashier WHERE mr.id_hospRadio=:idhosp AND mr.numero=:num AND mr.id_factureMedRadio="" AND mr.datehosp>=:datedebut AND mr.datehosp<=:datefin');

		$updateIdFactureMedRadio->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		));
		
		
		
		/*----------Update Med_Consom----------------*/
		
		$updateIdFactureMedConsom=$connexion->prepare('UPDATE med_consom_hosp mco SET mco.id_factureMedConsom=:idbill, mco.codecashier=:codecashier WHERE mco.id_hospConsom=:idhosp AND mco.numero=:num AND mco.id_factureMedConsom="" AND mco.datehosp>=:datedebut AND mco.datehosp<=:datefin');

		$updateIdFactureMedConsom->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		));
		
		
		
		/*----------Update Med_Medoc----------------*/
		
		$updateIdFactureMedMedoc=$connexion->prepare('UPDATE med_medoc_hosp mdo SET mdo.id_factureMedMedoc=:idbill, mdo.codecashier=:codecashier WHERE mdo.id_hospMedoc=:idhosp AND mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.datehosp>=:datedebut AND mdo.datehosp<=:datefin');

		$updateIdFactureMedMedoc->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 
		
		));



		/*----------Update Med_Kine----------------*/

		$updateIdFactureMedKine=$connexion->prepare('UPDATE med_kine_hosp mk SET mk.id_factureMedKine=:idbill, mk.codecashier=:codecashier WHERE mk.id_hospKine=:idhosp AND mk.numero=:num AND mk.id_factureMedKine="" AND mk.datehosp>=:datedebut AND mk.datehosp<=:datefin');

		$updateIdFactureMedKine->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 

		));



		/*----------Update Med_Ortho----------------*/

		$updateIdFactureMedOrtho=$connexion->prepare('UPDATE med_ortho_hosp mo SET mo.id_factureMedOrtho=:idbill, mo.codecashier=:codecashier WHERE mo.id_hospOrtho=:idhosp AND mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.datehosp>=:datedebut AND mo.datehosp<=:datefin');

		$updateIdFactureMedOrtho->execute(array(
		'idbill'=>$idBilling,
		'num'=>$_GET['numPa'],
		'idhosp'=>$idhosp,
		'codecashier'=>$_SESSION['codeCash'],
		'datedebut'=>$datedebut,
		'datefin'=>$datefin 

		));



        /*----------Update Med_Consult----------------*/

        $updateIdFactureMedConsult=$connexion->prepare('UPDATE med_consult_hosp mc SET mc.id_factureMedConsu=:idbill, mc.codecashier=:codecashier WHERE mc.id_hospMed=:idhosp AND mc.numero=:num AND mc.id_factureMedConsu="" AND mc.datehosp>=:datedebut AND mc.datehosp<=:datefin');

        $updateIdFactureMedConsult->execute(array(
            'idbill'=>$idBilling,
            'num'=>$_GET['numPa'],
            'idhosp'=>$idhosp,
            'codecashier'=>$_SESSION['codeCash'],
            'datedebut'=>$datedebut,
			'datefin'=>$datefin 

        ));




    }



?>

<!doctype html>
<html lang="en">
<noscript>
This page requires Javascript.
Please enable it in your browser.
</noscript>
<head>
	<meta charset="utf-8"/>
	<title><?php echo getString(92);?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico" />
	
			<!-------------------barre de menu------------------->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="source/cssmenu/styles.css">
	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	<script src="script.js"></script>
			
			<!------------------------------------>
			
	<link href="css/form-signin.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->
	
	<link href="css/patients1.css" rel="stylesheet" type="text/css" /><!--Header-->
	
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	

	
	<link rel="stylesheet" type="text/css" href="cssPagination/pagination.css" />
	<link rel="stylesheet" href="cssPagination/layout.css" type="text/css" media="screen" />
	<script src="myQuery.js"></script>
	
	<style type="text/css">
			body{
		font-family: Century Gothic;
	}

    .shaking {
	  position: relative;
	  animation-name: shake;
	  animation-duration: 1.5s;
	  animation-iteration-count: infinite;
	  animation-timing-function: ease-in;
	  cursor: pointer;
	}

	.shaking :hover {
	  animation-name: shakeAnim;
	}	

	.shaking :hover {
	  animation-name: shakeAnim;
	}	

	@keyframes shakeAnim {
	  0% {left: 0}
	  1% {left: -3px}
	  2% {left: 5px}
	  3% {left: -8px}
	  4% {left: 8px}
	  5% {left: -5px}
	  6% {left: -3px}
	  7% {left:	-5px}
	  8% {left: -3px}
	  9% {left: -5px}
	  10% {left: 0}
	}

	@keyframes shake {
	  0% {left: 0}
	  1% {left: -3px}
	  2% {left: 5px}
	  3% {left: -8px}
	  4% {left: 8px}
	  5% {left: -5px}
	  6% {left: 3px}
	  7% {left: -3}
	  8% {left: -5px}
	  9% {left: -3px}
	  10% {left: 0}
	}
	</style>
</head>

<body>
<?php
$connect=$_SESSION['connect'];
$status=$_SESSION['status'];

if($connect==true)
{
	if($status==1)
	{
?>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li class="">			
					<form method="post" action="patients1_hosp.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="patients1_hosp.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="patients1_hosp.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}?>" class="btn"><?php echo getString(29);?></a>
					<?php
					}					
					?>
						<br/>						
					
						<input type="text" name="Pass" id="Pass" placeholder="<?php echo getString(26);?>" style="margin-top:10px;margin-bottom:0;height:20px;"/>
						
						<input type="submit" name="confirmPass" id="confirmPass" class="btn"  value="<?php echo getString(27);?>"/>
						
					
					</form>
				</li>	
				</ul>
			</div><!--/.nav-collapse -->
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div><br><br><br><br><br>

<?php
if(isset($_SESSION['infhosp']))
{
	$infhosp=$_SESSION['infhosp'];
	
	if($infhosp!=0)
	{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
	}
}
?>

<?php
if(isset($_SESSION['codeO']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeM']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}
?>

<?php
if(isset($_SESSION['codeX']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}

if(isset($_SESSION['codeL']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
		<a href="patients1.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>

	</div>
<?php
}

if(isset($_SESSION['codeC']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		<a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Clinique';?>
		</a>
	</div>
<?php
}
?>



<div class="account-container" style="width:90%; text-align:center;">

<?php

$id=$_SESSION['id'];

$sqlI=$connexion->query("SELECT *FROM infirmiers i WHERE i.id_u='$id'");
$sqlO=$connexion->query("SELECT *FROM orthopedistes o WHERE o.id_u='$id'");
$sqlD=$connexion->query("SELECT *FROM medecins m WHERE m.id_u='$id'");
$sqlX=$connexion->query("SELECT *FROM radiologues x WHERE x.id_u='$id'");
$sqlL=$connexion->query("SELECT *FROM laborantins l WHERE l.id_u='$id'");
$sqlC=$connexion->query("SELECT *FROM cashiers c WHERE c.id_u='$id'");
$sqlmn=$connexion->query("SELECT *FROM coordinateurs mn WHERE mn.id_u='$id'");

$comptidI=$sqlI->rowCount();
$comptidO=$sqlO->rowCount();
$comptidD=$sqlD->rowCount();
$comptidX=$sqlX->rowCount();
$comptidL=$sqlL->rowCount();
$comptidC=$sqlC->rowCount();
$comptidmn=$sqlmn->rowCount();


if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidX!=0 OR $comptidL!=0 OR $comptidC!=0  OR $comptidmn!=0 )
{
?>
<div id='cssmenu' style="text-align:center">

<ul style="margin-top:20px;background:none;border:none;">
	<?php
	if($comptidI!=0)
	{
	?>
		<li style="width:50%;"><a href="patients1_inf.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Voir autres patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> Voir autres patients</a></li>
	<?php
	}elseif($comptidC!=0){
	?>
		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $id;?>&caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Patients de la clinique"><i class="fa fa-wheelchair fa-lg fa-fw"></i>Patients de la clinique</a></li>
	<?php
	}
	
	if($comptidO!=0)
	{
	?>
		<li style="width:50%;">----</li>
							
	<?php
	}
	
	if($comptidD!=0)
	{
	?>
		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&all=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Liste des patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(92);?></a></li>
							
	<?php
	}

	if($comptidX!=0)
	{
	?>
		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&all=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Liste des patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(92);?></a></li>
							
	<?php
	}

	if($comptidL!=0)
	{
	?>
		<li style="width:50%;"><a href="patients1.php?iduser=<?php echo $_SESSION['id'];?>&all=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="Liste des patients"><i class="fa fa-wheelchair fa-lg fa-fw"></i> <?php echo getString(92);?></a></li>
							
	<?php
	}	

	if($comptidmn!=0)
	{
	?>
		<li style="width:50%;"><a onclick="ShowList('Users')" style="margin-right:5px;" data-title="<?php echo getString(48);?>"><i class="fa fa-users fa-lg fa-fw"></i> <?php echo getString(48);?></a></li>
							
	<?php
	}
	?>
	<a onclick="ShowList('Liste')" id="listOn" class="btn-large" style="display:none;"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(55);?></a>
			
	<span onclick="ShowList('ListeNon')" id="listOff" class="btn-large" style="display:none;"><i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo getString(56);?></span>
	
	
	<?php
		$lu=0;
        $selectmsg=$connexion->prepare("SELECT * FROM messages WHERE lu=:lu AND receiverId=:receiverId");
        $selectmsg->execute(array("lu"=>$lu,"receiverId"=>$_SESSION['id']));
        $selectmsg->setFetchMode(PDO::FETCH_OBJ);
        $lignecount=$selectmsg->rowCount();
        /*echo $_SESSION['id'];*/
        /*echo $lignecount;*/
		?>
		<style type="text/css">.badge{background: black;}.badge2{background: rgb(160, 0, 0);}</style>
		<?php if($lignecount!=0){?>
		<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?> || Unread messages: <?php echo $lignecount; ?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?> <i class="badge flashing"><?php echo $lignecount; ?></i> </a></li>
        <?php }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
        <?php }?>

</ul>

<ul style="margin-top:20px; background:none;border:none;">
	<?php
	if($comptidI!=0)
	{
	?>
		<a href="utilisateurs_hosp.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn-large-inversed"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(88);?></a>
	<?php
	}
	?>
		
	<div style="display:none; margin-bottom:20px;" id="divMenuMsg">

		<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
		
		<a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
		
		<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i> <?php echo getString(59);?></a>

	</div>
		
</ul>
		
</div>


<?php 
}

	if(isset($_GET['divPa']))
	{
		
		/*-----------------Requête pour Nurse--------------*/
		
		if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidX!=0 OR $comptidL!=0 OR $comptidC!=0 OR $comptidmn!=0)
		{
			$resultatsI=$connexion->query('SELECT *FROM utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND ph.id_factureHosp IS NULL AND u.full_name LIKE \'%'.$_GET['fullname'].'%\' ');
			/* $resultatsI->execute(array(
			'idPa'=>$_GET['iduti']	
			));
		 */
			
			$resultatsI->setFetchMode(PDO::FETCH_OBJ);
			
			$comptPaI=$resultatsI->rowCount();
			
		}
	?>
	
	<div style="margin-top:15px;">
	
	
	<?php
	
	if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidX!=0 OR $comptidL!=0 OR $comptidC!=0 OR $comptidmn!=0)
	{
		if($comptPaI!=0)
		{
	?>
		<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			<thead> 
				<tr>
					<th>S/N</th>
					<th><?php echo getString(222);?></th>
					<th><?php echo getString(9);?></th>
					<th><?php echo getString(11);?></th>
					<th><?php echo "Numero chambre";?></th>
					<th><?php echo "Lit";?></th>
					<th><?php echo "Date d'entrée";?></th>
					<th><?php echo "Nbre de jours";?></th>
					<th colspan=2>Actions</th>
				</tr> 
			</thead>
			
			<tbody> 
			
			<span style="position:relative; font-size:250%;"><?php echo getString(60);?></span>
			
<?php

			while($ligneI=$resultatsI->fetch())//on recupere la liste des éléments
			{
?>
				<tr style="text-align:center;">
					<td><?php echo $ligneI->numero;?></td>
					<td><?php echo $ligneI->reference_idHosp;?></td>
					<td><?php echo $ligneI->full_name ;?></td>
					<td>
					<?php 
					if($ligneI->sexe=="M")
					{
						echo getString(12);
					}elseif($ligneI->sexe=="F")
					{
						echo getString(13);
					}
					?>
					</td>
					
					<td><?php echo $ligneI->numroomPa;?></td>
					<td><?php echo $ligneI->numlitPa;?></td>
					<td><?php echo $ligneI->dateEntree.' à '.$ligneI->heureEntree;?></td>
					
					<td>
					<?php
					
					$dateIn=strtotime($ligneI->dateEntree);
					$todaydate=strtotime($annee);
					
					$datediff= abs($todaydate - $dateIn);
					
					$nbrejrs= floor($datediff /(60*60*24));
					
					if($nbrejrs==0)
					{
						$nbrejrs=1;
					}
						echo $nbrejrs;
					?>
					</td>
					
					<?php
					if($comptidI!=0)
					{
					?>
					<td>	
						<a class="btn" href="hospForm.php?updateidPahosp=ok&inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
					</td>
					<td>	
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>

					</td>
					<?php
					}

					
					
					if($comptidO!=0)
					{
					?>
					<td>	
						<a class="btn" href="hospForm.php?updateidPahosp=ok&inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
					</td>
					<td>	
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>

					</td>
					<?php
					}
					
					if($comptidD!=0)
					{
					?>
					<td></td>
					<td>	
						<a class="btn" href="categoriesbill_hosp_medecin.php?idmed=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>

					</td>
					<?php
					}
					
					if($comptidX!=0)
					{
					?>
					<td></td>
					<td>	
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>

					</td>
					<?php
					}
					
					if($comptidL!=0)
					{
					?>
					<td></td>
					<td>
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Traiter
						</a>
					</td>
					<?php
					}
					
					if($comptidC!=0)
					{
					?>
					<td>
					<?php
					
					$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.surgefait=1 AND ms.numero=:num AND ms.id_factureMedSurge="" AND ms.id_hospSurge=:idhosp ORDER BY ms.id_medsurge');
					$resultMedSurge->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

					$comptMedSurge=$resultMedSurge->rowCount();



					$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');
					$resultMedInf->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));

					$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

					$comptMedInf=$resultMedInf->rowCount();



					$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.examenfait=0 AND ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');
					$resultMedLabo->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

					$comptMedLabo=$resultMedLabo->rowCount();



					$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.radiofait=0 AND mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
					$resultMedRadio->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

					$comptMedRadio=$resultMedRadio->rowCount();



					$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom="" AND mco.id_hospConsom=:idhosp ORDER BY mco.id_medconsom');		
					$resultMedConsom->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

					$comptMedConsom=$resultMedConsom->rowCount();



					$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.id_hospMedoc=:idhosp ORDER BY mdo.id_medmedoc');		
					$resultMedMedoc->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));
					
					$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

					$comptMedMedoc=$resultMedMedoc->rowCount();



					$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mk WHERE mk.numero=:num AND mk.id_factureMedKine="" AND mk.id_hospKine=:idhosp ORDER BY mk.id_medkine');
					$resultMedKine->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));

					$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

					$comptMedKine=$resultMedKine->rowCount();



					$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.id_hospOrtho=:idhosp ORDER BY mo.id_medortho');
					$resultMedOrtho->execute(array(
					'num'=>$ligneI->numero,
					'idhosp'=>$ligneI->id_hosp
					));

					$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

					$comptMedOrtho=$resultMedOrtho->rowCount();



                    $resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu="" AND mc.id_hospMed=:idhosp ORDER BY mc.id_medconsu');
                    $resultMedConsult->execute(array(
                        'num'=>$ligneI->numero,
                        'idhosp'=>$ligneI->id_hosp
                    ));

                    $resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

                    $comptMedConsult=$resultMedConsult->rowCount();


/* 
                    if($comptMedSurge!=0 OR $comptMedInf!=0 OR $comptMedLabo!=0 OR $comptMedRadio!=0 OR $comptMedConsom!=0 OR $comptMedMedoc!=0 OR $comptMedKine!=0 OR $comptMedOrtho!=0 OR $comptMedConsult!=0)
					{
					?>	
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&id_uM=<?php echo '0';?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&previewprint=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							<i class="fa fa-money fa-1x fa-fw"></i>
						</a>
					<?php
					}
*/
					?>
					</td>
					
					<td>
						<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&id_uM=<?php echo '0';?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&id_consuHosp=<?php echo $ligneI->id_consuHosp;?>&previewprint=ok&sortie=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
							Sortir Patient
						</a>
					</td>
					
					<?php
					}
					?>
				</tr>
			<?php
			}
			$resultatsI->closeCursor();
					
			?>
			</tbody> 
	
		</table>
		<?php
		}else{
		?>
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
				<thead> 
					<tr>
						<th><?php echo getString(152).' ';?> 
						<?php 
							if($ligneI=$resultatsI->fetch())//on récupère la liste des éléments
							{
								echo $ligneI->full_name;
							}
						?>
						<?php echo ' '. getString(153);?></th>
					</tr>
				</thead> 
			<table>
	<?php
		}
	}
	
	?>
	
	
	<a href="patients1_hosp.php?iduser=<?php echo $id;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>#listPatientHosp" style="margin-right:5px;" class="btn-large"><?php echo 'Afficher tous les patients hospitalisés';?></a>
	
	<br/><br/>
	
	</div>
	
	<?php
	}else{ echo '';}

	
		$getPaSentByDr=$connexion->query('SELECT *FROM consultations c WHERE c.hospitalized=1');
		/* $getPaSentByDr->execute(array(
			'idPa'=>$_GET['iduti']	
			));
		*/

		$getPaSentByDr->setFetchMode(PDO::FETCH_OBJ);
		
		$comptPaSentByDr=$getPaSentByDr->rowCount();
		
		if($comptPaSentByDr!=0 AND ($comptidD==0 OR $comptidO==0))
		{
		?>
			<h2 style="margin-top:10px;"><?php echo getString(276);?></h2>
			
			<table class="tablesorter" cellspacing="0" style="background-color:#FFF;margin-bottom:20px;margin-top:10px;">
				<thead> 
					<tr>
						<th style="width:10%">S/N</th> 
						<th style="width:25%"><?php echo getString(9);?></th>
						<th style="width:10%"><?php echo getString(11);?></th>
						<th style="width:15%"><?php echo 'Assurance';?></th> 
						<th style="width:20%"><?php echo 'Medecin expediteur';?></th> 
						<th style="padding:0; width:20%" colspan=3>Actions</th>
					</tr> 
				</thead>
				
				<tbody> 
				
				<?php

				while($lignePaSentByDr=$getPaSentByDr->fetch())
				{
					$resultStatuPaHosp=$connexion->prepare('SELECT *FROM patients_hosp ph WHERE ph.numero=:num AND ph.statusPaHosp=1');
					$resultStatuPaHosp->execute(array(
					'num'=>$lignePaSentByDr->numero
					));
						
					$compteStatuPaHosp=$resultStatuPaHosp->rowCount();

				?>
					<tr style="text-align:center;">
						<td><?php echo $lignePaSentByDr->numero;?></td>
						<td>
						<?php 
						
						$getPa=$connexion->prepare('SELECT *FROM utilisateurs u, patients p WHERE p.numero=:operation AND u.id_u=p.id_u');
						$getPa->execute(array(
						'operation'=>$lignePaSentByDr->numero	
						));
						$getPa->setFetchMode(PDO::FETCH_OBJ);
			
						if($lignePa=$getPa->fetch())
						{
							$idPatient = $lignePa->id_u;
							$nomPatient = $lignePa->full_name;
							$sexePatient = $lignePa->sexe;
							$idAssuPatient = $lignePa->id_assurance;
							
								$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassu ORDER BY a.id_assurance');		
								$getAssuConsu->execute(array(
								'idassu'=>$idAssuPatient
								));
								
								$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

								if($ligneNomAssu=$getAssuConsu->fetch())
								{
									$assuPatient=$ligneNomAssu->nomassurance;
								}
								
							$statuPatient = $lignePa->status;
							echo $nomPatient;
						}
						?>
						</td>
						
						<td>
						<?php 
						if($sexePatient=="M")
						{
							echo getString(12);
						}else{
							if($sexePatient=="F")
							echo getString(13);
						}
						?>
						</td>
						
						<td><?php echo $assuPatient;?></td>
						
						<td>
						<?php 
						
						$getDr=$connexion->prepare('SELECT *FROM utilisateurs u WHERE u.id_u=:operation');
						$getDr->execute(array(
						'operation'=>$lignePaSentByDr->id_uM	
						));
						$getDr->setFetchMode(PDO::FETCH_OBJ);
			
						if($ligneDr=$getDr->fetch())
						{
							$nomMedecin = $ligneDr->full_name;
							echo $nomMedecin;
						}
						?>
						</td>
						
						<td>	
						<?php
						if($compteStatuPaHosp==0)
						{
						?>
							<a class="btn" href="hospForm.php?idInf=<?php echo $_SESSION['id'];?>&num=<?php echo $lignePaSentByDr->numero;?>&id_consu=<?php echo $lignePaSentByDr->id_consu;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
								Hospitaliser
							</a>
						<?php
						}else{
						?>
							Déjà hospitalisé
						<?php
						}
						?>
						</td>
						
						<td>
							<a class="btn" href="utilisateurs_hosp.php?iduti=<?php echo $idPatient;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="<?php if($statuPatient==0){ echo "display:none";}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
						</td>
						<td style="text-align: center;">
                            <a href="patients1_hosp.php?id_consu=<?= $lignePaSentByDr->id_consu;?>&deleteHospRecom=ok" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                        </td>
					</tr>
				<?php
				}
				$getPaSentByDr->closeCursor();
						
				?>
				</tbody> 
		
			</table>
			
<?php
		}


	if(!isset($_GET['num']) AND !isset($_GET['divPa']))
	{
	?>
				
	<form class="ajax" action="search.php" method="get" id="listPatientHosp">
		<p style="margin-top:50px">
			
			<table align="center">
				<tr>
					<td></td>
					
					<td>
						<h2><?php echo getString(20);?>s hospitalisés</h2>
					</td>
					
					<td></td>
				</tr>
				
				<tr>
					<td>
						<label for="q"><?php echo getString(80);?></label>
						<input type="text" name="q" id="q" onclick="ShowSearch('byname')"/>
					</td>
					
					<td>
						<label for="r"><?php echo getString(223);?></label>
						<input type="text" name="r" id="r" onclick="ShowSearch('byri')"/>
					</td>
					
					<td>
						<label for="s"><?php echo getString(91);?></label>
						<input type="text" name="s" id="s" onclick="ShowSearch('bysn')"/>
					</td>
				</tr>
			</table>
			
		</p>
	</form>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="results"></div>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsSN"></div>

	<div style="text-align:center; margin-bottom:20px; margin-top:-15px" id="resultsRI"></div>

	<script type="text/javascript">
	$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#q').keyup( function(){
		$field = $(this);
		$('#results').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_patients1_hosp.php?name=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" style="margin:5px" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#results').html(data); // affichage des résultats dans le bloc
		}
		  });
		}		
	  });
	});

	</script>

	<script type="text/javascript">
	$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#s').keyup( function(){
		$field = $(this);
		$('#resultsSN').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_patients1_hosp.php?sn=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 's='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#resultsSN').html(data); // affichage des résultats dans le bloc
		}
		  });
		}		
	  });
	});

	</script>

	<script type="text/javascript">
	$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#r').keyup( function(){
		$field = $(this);
		$('#resultsRI').html(''); // on vide les resultats
		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > 0 )
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'traitement_patients1_hosp.php?ri=ok<?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>' , // url du fichier de traitement
		data : 'r='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after('<img src="images/loader_30x30.gif" alt="loader" id="ajax-loader" />'); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#resultsRI').html(data); // affichage des résultats dans le bloc
		}
		  });
		}		
	  });
	});

	</script>

		<?php
		
	}


		
if(!isset($_GET['divPa']))
{

		try
		{
			function pagination($current_page, $nb_pages, $link='?page=%d', $around=2, $firstlast=1)
			{
				
				$caissrecep='';
			
				$pagination = '';
				$link = preg_replace('`%([^d])`', '%%$1', $link);
				if ( !preg_match('`(?<!%)%d`', $link) ) $link .= '%d';
				if ( $nb_pages > 1 ) {
			 
					// Lien Précédent
					if ( $current_page > 1 )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];									
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}else{
								
								$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).''.$caissrecep.'#listPatientHosp" title="Page précédente" style="border-radius: 24px 4px 4px 14px;"> Précédent</a>';

							}
						}
						
					}else{
						$pagination .= '';
					}
			 
					// Lien(s) début
					for ( $i=1 ; $i<=$firstlast ; $i++ ) {
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						
							}
						}
						
					}
			 
					// ... après pages début ?
					if ( ($current_page-$around) > $firstlast+1 )
						$pagination .= '<span class="current">&hellip;</span>';
			 
					// On boucle autour de la page courante
					$start = ($current_page-$around)>$firstlast ? $current_page-$around : $firstlast+1;
					$end = ($current_page+$around)<=($nb_pages-$firstlast) ? $current_page+$around : $nb_pages-$firstlast;
					for ( $i=$start ; $i<=$end ; $i++ ) {
						$pagination .= ' ';
						if ( $i==$current_page )
						{
							$pagination .= '<span class="current">'.$i.'</span>';
						}else{
						
							if(isset($_GET['english']))
							{
								// echo '&english='.$_GET['english'];
								$pagination .= '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
							
							}else{
								if(isset($_GET['francais']))
								{
									// echo '&francais='.$_GET['francais'];
									$pagination .= '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
								}else{
									
									$pagination .= '<a href="'.sprintf($link, $i).''.$caissrecep.'#listPatientHosp">'.$i.'</a>';				
								}
							}
						}
					}
			 
					// ... avant page nb_pages ?
					if ( ($current_page+$around) < $nb_pages-$firstlast )
						$pagination .= '<span class="current">&hellip;</span>';
			 
				// Lien(s) fin
					$start = $nb_pages-$firstlast+1;
					if( $start <= $firstlast ) $start = $firstlast+1;
					
					for ( $i=$start ; $i<=$nb_pages ; $i++ )
					{
						$pagination .= ' ';
						
						if(isset($_GET['english']))
						{
							$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp">'.$i.'</a>';	
								
							}else{
								
								$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).''.$caissrecep.'#listPatientHosp">'.$i.'</a>';				
							}
						}
						
					}
			 
					// Lien suivant
					if ( $current_page < $nb_pages )
					{
						if(isset($_GET['english']))
						{
							// echo '&english='.$_GET['english'];
							
							$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&english='.$_GET['english'].''.$caissrecep.'#listPatientHosp" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
						
						}else{
							if(isset($_GET['francais']))
							{
								// echo '&francais='.$_GET['francais'];
								
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'&francais='.$_GET['francais'].''.$caissrecep.'#listPatientHosp" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
						
							}else{
								$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).''.$caissrecep.'#listPatientHosp" title="Page suivante" style="border-radius: 4px 24px 14px 4px;">Suivant</a>';
										
							}
						}
						
					}else{
						$pagination .= '';
					}
				}
				return $pagination;
			}
	
				
			// Numero de page (1 par défaut)
				if( isset($_GET['page']) && is_numeric($_GET['page']) )
						$page = $_GET['page'];
					else
						$page = 1;
					 
					// Nombre d'info par page
					$pagination =5;
					 
					// Numero du 1er enregistrement à lire
					$limit_start = ($page - 1) * $pagination;


		?>

			<?php
			
			if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidX!=0 OR $comptidL!=0 OR $comptidC!=0 OR $comptidmn!=0)
			{
			
				$resultatsI=$connexion->query('SELECT *FROM utilisateurs u, patients_hosp ph WHERE ph.id_uHosp=u.id_u AND ph.statusPaHosp=1 ORDER BY u.nom_u LIMIT '.$limit_start.', '.$pagination.'');
						
				$resultatsI->setFetchMode(PDO::FETCH_OBJ);
			
				$comptResultI=$resultatsI->rowCount();

			?>
				<table class="tablesorter tablesorter2" id="hospiTab" style="width:100%; margin-bottom:30px;">
				<?php
				
				if($comptResultI!=0)
				{
				?>	
					<thead> 
						<tr>
							<th style="width:10%">S/N</th> 
							<th style="width:5%"><?php echo getString(222);?></th> 
							<th style="width:20%"><?php echo getString(9);?></th>
							<th style="width:10%"><?php echo getString(11);?></th>
							<th style="width:10%"><?php echo "N° chambre";?></th>
							<th style="width:5%"><?php echo "Lit";?></th>
							<th style="width:10%"><?php echo "Date d'entrée";?></th>
							<th style="width:5%"><?php echo "Nbre de jours";?></th>
							<th style="width:15%" colspan="4">Actions</th>
							<th style="width:15%;<?php if(!isset($_SESSION['codeCash'])){echo "display: none;";} ?>">Clinic Info</th>
						</tr> 
					</thead>
			
					<tbody>
					<?php
					while($ligneI=$resultatsI->fetch())//on recupere la liste des éléments
					{
					?>
						<tr style="text-align:center;">
							<td><?php echo $ligneI->numero;?></td>
							<td><?php echo $ligneI->reference_idHosp;?></td>
							<td><?php echo $ligneI->full_name;?></td>
							<td>
							<?php 
							if($ligneI->sexe=="M")
							{
								echo getString(12);
							}elseif($ligneI->sexe=="F")
							{
								echo getString(13);
							}
							?>
							</td>
							
							<td><?php echo $ligneI->numroomPa;?></td>
							<td><?php echo $ligneI->numlitPa;?></td>
							<td>
							<?php
							
								echo $ligneI->dateEntree.' à '.$ligneI->heureEntree;
							?>
							</td>
							
							<td>
							<?php
							
							$dateIn=strtotime($ligneI->dateEntree);
							$todaydate=strtotime($annee);
							
							$datediff= abs($todaydate - $dateIn);
							
							$nbrejrs= floor($datediff /(60*60*24));
							
							if($nbrejrs==0)
							{
								$nbrejrs=1;
							}
								echo $nbrejrs;
							?>
							</td>
							
							<?php
							if($comptidI!=0)
							{
							?>
							<td>	
								<a class="btn" href="hospForm.php?updateidPahosp=ok&inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>"><i class="fa fa-pencil-square-o fa-lg fa-fw"></i> <?php echo getString(32)?></a>
							</td>

							<td>	
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
									Traiter
								</a>

							</td>
							<?php
							}

							if($comptidmn!=0)
							{
							?>
							<td>	
								<a class="btn" href="formPatient_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&showmore=ok&<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
									<i class="fa fa-eye"></i> View Profile
								</a>

							</td>
							<?php
							}
							
							if($comptidO!=0)
							{
							?>
							<td></td>

							<td>	
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>">
									Traiter
								</a>

							</td>
							<?php
							}
							
							if($comptidD!=0)
							{
							?>
							<td></td>
							<td>	
								<a class="btn" href="categoriesbill_hosp_medecin.php?idmed=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									Traiter
								</a>

							</td>
							<?php
							}
							
							if($comptidX!=0)
							{
							?>
							<td></td>
							<td>	
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									Traiter
								</a>

							</td>
							<?php
							}
							
							if($comptidL!=0)
							{
							?>
							<td></td>
							<td>
								<td>
									<!-- <a class="btn" href="patients1_hosp.php?num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&examenPaHosp=ok&<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
										Resultat
									</a> -->

									<a href="patients1_hosp_labresult.php?num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(109);?></a>

								</td>	
								<td>
									<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									Traiter
									</a>
								</td>

							</td>
							<?php
							}
							
							if($comptidC!=0)
							{
							?>
							<td>
							<?php
							
							$resultMedSurge=$connexion->prepare('SELECT *FROM med_surge_hosp ms WHERE ms.surgefait=1 AND ms.numero=:num AND ms.id_factureMedSurge="" AND ms.id_hospSurge=:idhosp ORDER BY ms.id_medsurge');
							$resultMedSurge->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedSurge->setFetchMode(PDO::FETCH_OBJ);

							$comptMedSurge=$resultMedSurge->rowCount();



							$resultMedInf=$connexion->prepare('SELECT *FROM med_inf_hosp mi WHERE mi.soinsfait=1 AND mi.numero=:num AND mi.id_factureMedInf="" AND mi.id_hospInf=:idhosp ORDER BY mi.id_medinf');
							$resultMedInf->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));

							$resultMedInf->setFetchMode(PDO::FETCH_OBJ);

							$comptMedInf=$resultMedInf->rowCount();



							$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo_hosp ml WHERE ml.examenfait=0 AND ml.numero=:num AND ml.id_factureMedLabo="" AND ml.id_hospLabo=:idhosp ORDER BY ml.id_medlabo');
							$resultMedLabo->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);

							$comptMedLabo=$resultMedLabo->rowCount();



							$resultMedRadio=$connexion->prepare('SELECT *FROM med_radio_hosp mr WHERE mr.radiofait=0 AND mr.numero=:num AND mr.id_factureMedRadio="" AND mr.id_hospRadio=:idhosp ORDER BY mr.id_medradio');		
							$resultMedRadio->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedRadio->setFetchMode(PDO::FETCH_OBJ);

							$comptMedRadio=$resultMedRadio->rowCount();



							$resultMedConsom=$connexion->prepare('SELECT *FROM med_consom_hosp mco WHERE mco.numero=:num AND mco.id_factureMedConsom="" AND mco.id_hospConsom=:idhosp ORDER BY mco.id_medconsom');		
							$resultMedConsom->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedConsom->setFetchMode(PDO::FETCH_OBJ);

							$comptMedConsom=$resultMedConsom->rowCount();



							$resultMedMedoc=$connexion->prepare('SELECT *FROM med_medoc_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedMedoc="" AND mdo.id_hospMedoc=:idhosp ORDER BY mdo.id_medmedoc');		
							$resultMedMedoc->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));
							
							$resultMedMedoc->setFetchMode(PDO::FETCH_OBJ);

							$comptMedMedoc=$resultMedMedoc->rowCount();



							$resultMedKine=$connexion->prepare('SELECT *FROM med_kine_hosp mdo WHERE mdo.numero=:num AND mdo.id_factureMedKine="" AND mdo.id_hospKine=:idhosp ORDER BY mdo.id_medkine');
							$resultMedKine->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));

							$resultMedKine->setFetchMode(PDO::FETCH_OBJ);

							$comptMedKine=$resultMedKine->rowCount();



							$resultMedOrtho=$connexion->prepare('SELECT *FROM med_ortho_hosp mo WHERE mo.numero=:num AND mo.id_factureMedOrtho="" AND mo.id_hospOrtho=:idhosp ORDER BY mo.id_medortho');
							$resultMedOrtho->execute(array(
							'num'=>$ligneI->numero,
							'idhosp'=>$ligneI->id_hosp
							));

							$resultMedOrtho->setFetchMode(PDO::FETCH_OBJ);

							$comptMedOrtho=$resultMedOrtho->rowCount();


                            $resultMedConsult=$connexion->prepare('SELECT *FROM med_consult_hosp mc WHERE mc.numero=:num AND mc.id_factureMedConsu="" AND mc.id_hospMed=:idhosp ORDER BY mc.id_medconsu');
                            $resultMedConsult->execute(array(
                                'num'=>$ligneI->numero,
                                'idhosp'=>$ligneI->id_hosp
                            ));

                            $resultMedConsult->setFetchMode(PDO::FETCH_OBJ);

                            $comptMedConsult=$resultMedConsult->rowCount();


							?>
							</td>
							
							<td>
								<a class="btn" href="categoriesbill_hosp.php?inf=<?php echo $_SESSION['id'];?>&num=<?php echo $ligneI->numero;?>&idhosp=<?php echo $ligneI->id_hosp;?>&datehosp=<?php echo $ligneI->dateEntree;?>&id_uM=<?php echo '0';?>&idassu=<?php echo $ligneI->id_assuHosp;?>&idbill=<?php echo $ligneI->id_factureHosp;?>&numroom=<?php echo $ligneI->numroomPa;?>&numlit=<?php echo $ligneI->numlitPa;?>&id_consuHosp=<?php echo $ligneI->id_consuHosp;?>&previewprint=ok&sortie=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="padding:5px 15px;">
									Sortir Patient
								</a>
							</td>
							<td style="text-align: center;" <?php if(!isset($_SESSION['codeCash'])){echo "display: none;";} ?>>
								<?php
									//Check If There is clinic Bills Before
								//echo $ligneI->id_consuHosp.''.$ligneI->numero.'<br>';
								$selectConsom = $connexion->prepare("SELECT * FROM med_consom WHERE id_consuConsom=:id_consuConsom AND numero=:numero AND id_factureMedConsom = 0");
								$selectConsom->execute(array('id_consuConsom'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countConsom = $selectConsom->rowCount();
								//echo $countConsom;

								$selectconsult  = $connexion->prepare("SELECT * FROM med_consult WHERE id_consuMed=:id_consuMed AND numero=:numero AND id_factureMedConsu = 0");
								$selectconsult ->execute(array('id_consuMed'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countconsult  = $selectconsult->rowCount();
								//echo $countconsult;

								$selectInf = $connexion->prepare("SELECT * FROM med_inf WHERE id_consuInf=:id_consuInf AND numero=:numero AND id_factureMedInf = 0");
								$selectInf->execute(array('id_consuInf'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countInf = $selectInf->rowCount();
								//echo $countInf;

								$selectkine = $connexion->prepare("SELECT * FROM med_kine WHERE id_consuKine=:id_consuKine AND numero=:numero AND id_factureMedKine = 0");
								$selectkine->execute(array('id_consuKine'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countkine = $selectkine->rowCount();
								//echo $countkine;

								$selectlabo = $connexion->prepare("SELECT * FROM med_labo WHERE id_consuLabo=:id_consuLabo AND numero=:numero AND id_factureMedLabo =0");
								$selectlabo->execute(array('id_consuLabo'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countlabo = $selectlabo->rowCount();
								//echo $countlabo;
								
								$selectmedoc = $connexion->prepare("SELECT * FROM med_medoc WHERE id_consuMedoc=:id_consuMedoc AND numero=:numero AND id_factureMedMedoc =0");
								$selectmedoc->execute(array('id_consuMedoc'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countmedoc = $selectmedoc->rowCount();
								//echo $countmedoc;
								
								$selectortho = $connexion->prepare("SELECT * FROM med_ortho WHERE id_consuOrtho=:id_consuOrtho AND numero=:numero AND id_factureMedOrtho =0");
								$selectortho->execute(array('id_consuOrtho'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countortho = $selectortho->rowCount();
								//echo $countortho;

								$selectpsy = $connexion->prepare("SELECT * FROM med_psy WHERE id_consuPSy=:id_consuPSy AND numero=:numero AND id_factureMedPsy =0");
								$selectpsy->execute(array('id_consuPSy'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countpsy = $selectpsy->rowCount();
								//echo $countpsy;
								
								$selectradio = $connexion->prepare("SELECT * FROM med_radio WHERE id_consuRadio=:id_consuRadio AND numero=:numero AND id_factureMedRadio =0");
								$selectradio->execute(array('id_consuRadio'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countradio = $selectradio->rowCount();
								//echo $countradio;
								
								$selectsurge = $connexion->prepare("SELECT * FROM med_surge WHERE id_consuSurge=:id_consuSurge AND numero=:numero AND id_factureMedSurge =0");
								$selectsurge->execute(array('id_consuSurge'=>$ligneI->id_consuHosp,'numero'=>$ligneI->numero));
								$countsurge = $selectsurge->rowCount();
								//echo $countsurge;

								if ($countConsom !=0 OR $countconsult!=0 OR $countInf!=0 OR $countkine!=0 OR $countlabo!=0 OR $countmedoc!=0 OR $countortho!=0 OR $countpsy!=0 OR $countradio!=0 OR $countsurge!=0) {
									?>
									
									<a href="patients1_hosp.php?copyPresta=ok&id_consu=<?php echo $ligneI->id_consuHosp;?>&numero=<?php echo $ligneI->numero;?>#hospiTab" alt='Copy To Hospital Report' title='Copy To Hospital Report' name="copyPrestations"><i class="fa fa-info-circle shaking" style="font-size: 55px;position: relative;right: 0;color: #e3e358;"></i></a>
								<?php
								}
								?>
							</td>
							<?php
							}
							?>						
						</tr>
					<?php
					}
					$resultatsI->closeCursor();
					?>

				<!-- 	<?php
						if (isset($_GET['copyPresta'])) {

							$id_consu = $_GET['id_consu'];
							$numero = $_GET['numero'];

							$selectConsom = $connexion->prepare("SELECT * FROM med_consom WHERE id_consuConsom=:id_consuConsom AND numero=:numero AND id_factureMedConsom = 0");
							$selectConsom->execute(array('id_consuConsom'=>$id_consu,'numero'=>$numero));
							$countConsom = $selectConsom->rowCount();
							if ($countConsom!=0) {
								$fetchConsom = $selectConsom->fetch();
								echo "<script>alert('Hello');</script>";
							}


							$selectconsult  = $connexion->prepare("SELECT * FROM med_consult WHERE id_consuMed=:id_consuMed AND numero=:numero AND id_factureMedConsu = 0");
							$selectconsult ->execute(array('id_consuMed'=>$id_consu,'numero'=>$numero));
							$countconsult  = $selectconsult->rowCount();
							if ($countconsult!=0) {
								$fetchConsult= $selectconsult->fetch();
							}


							$selectInf = $connexion->prepare("SELECT * FROM med_inf WHERE id_consuInf=:id_consuInf AND numero=:numero AND id_factureMedInf = 0");
							$selectInf->execute(array('id_consuInf'=>$id_consu,'numero'=>$numero));
							$countInf = $selectInf->rowCount();
							if ($countInf!=0) {
								$fetchInf= $selectInf->fetch();

								$Copy = $connexion->prepare("INSERT INTO med_inf_hosp VALUES(datehosp=:datehosp,id_prestation=:id_prestation,prixprestation=:prixprestation,soinsfait=:soinsfait,)");
							}

							$selectkine = $connexion->prepare("SELECT * FROM med_kine WHERE id_consuKine=:id_consuKine AND numero=:numero AND id_factureMedKine = 0");
							$selectkine->execute(array('id_consuKine'=>$id_consu,'numero'=>$numero));
							$countkine = $selectkine->rowCount();
							if ($countkine!=0) {
								$fetchkine= $selectkine->fetch();
							}

							$selectlabo = $connexion->prepare("SELECT * FROM med_labo WHERE id_consuLabo=:id_consuLabo AND numero=:numero AND id_factureMedLabo =0");
							$selectlabo->execute(array('id_consuLabo'=>$id_consu,'numero'=>$numero));
							$countlabo = $selectlabo->rowCount();
							if ($countlabo!=0) {
								$fetchlabo= $selectlabo->fetch();
							}

							$selectmedoc = $connexion->prepare("SELECT * FROM med_medoc WHERE id_consuMedoc=:id_consuMedoc AND numero=:numero AND id_factureMedMedoc =0");
							$selectmedoc->execute(array('id_consuMedoc'=>$id_consu,'numero'=>$numero));
							$countmedoc = $selectmedoc->rowCount();
							if ($countmedoc!=0) {
								$fetchmedoc= $selectmedoc->fetch();
							}

							$selectortho = $connexion->prepare("SELECT * FROM med_ortho WHERE id_consuOrtho=:id_consuOrtho AND numero=:numero AND id_factureMedOrtho =0");
							$selectortho->execute(array('id_consuOrtho'=>$id_consu,'numero'=>$numero));
							$countortho = $selectortho->rowCount();
							if ($countortho!=0) {
								$fetchortho= $selectortho->fetch();
							}

							$selectpsy = $connexion->prepare("SELECT * FROM med_psy WHERE id_consuPSy=:id_consuPSy AND numero=:numero AND id_factureMedPsy =0");
							$selectpsy->execute(array('id_consuPSy'=>$id_consu,'numero'=>$numero));
							$countpsy = $selectpsy->rowCount();
							if ($countpsy!=0) {
								$fetchpsy= $selectpsy->fetch();
							}

							$selectradio = $connexion->prepare("SELECT * FROM med_radio WHERE id_consuRadio=:id_consuRadio AND numero=:numero AND id_factureMedRadio =0");
							$selectradio->execute(array('id_consuRadio'=>$id_consu,'numero'=>$numero));
							$countradio = $selectradio->rowCount();
							if ($countradio!=0) {
								$fetchradio= $selectradio->fetch();
							}

							$selectsurge = $connexion->prepare("SELECT * FROM med_surge WHERE id_consuSurge=:id_consuSurge AND numero=:numero AND id_factureMedSurge =0");
							$selectsurge->execute(array('id_consuSurge'=>$id_consu,'numero'=>$numero));
							$countsurge = $selectsurge->rowCount();
							if ($countsurge!=0) {
								$fetchsurge= $selectsurge->fetch();
							}
						}
					?> -->

					</tbody>
					
				<?php
				}else{
				?>
					<thead> 
						<tr>
							<th><?php echo 'Aucun patient hospitalisé';?></th>
						</tr> 
					</thead>
				<?php
				}
				?>
		
				</table>
			<?php
			}
			
		}

		catch(Excepton $e)
		{
		echo 'Erreur:'.$e->getMessage().'<br/>';
		echo'Numero:'.$e->getCode();
		}


?>

	<?php
	
	if($comptidI!=0 OR $comptidO!=0 OR $comptidD!=0 OR $comptidC!=0 OR $comptidmn!=0)
	{
		echo '
		<tr>
			<td>';
			
			$nb_total=$connexion->query('SELECT COUNT(*) AS nb_total FROM utilisateurs u, patients_hosp ph WHERE ph.id_uHosp=u.id_u AND ph.statusPaHosp=1 ORDER BY u.nom_u');
			
			$nb_total=$nb_total->fetch();
			
			$nb_total = $nb_total['nb_total'];
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);
			// Affichage
			echo '<p class="pagination" style="text-align:center">' . pagination($page, $nb_pages) . '</p>';
		
		echo '
			</td>
		</tr>
		';
	}
	?>
	

</div>

<?php
}

if(isset($_GET['num']))
			{
			?>
				<table cellpadding=3 style="background:#fff; border:1px solid #eee; border-radius:4px; margin:auto auto 30px; padding: 10px; width:80%;">
					<tr>
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;"><?php echo getString(89) ?> : </span></span><?php echo $nom_uti.' '.$prenom_uti;?>
						</td>
						
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;"><?php echo getString(11) ?> : </span>
							<?php
							if($sexe=="M")
							{
								echo getString(12);
							}else{
								if($sexe=="F")
								echo getString(13);
							}
							?>
						</td>
						
						<td style="font-size:18px; text-align:center; width:33.333%;">
							<span style="font-weight:bold;">Age: </span><?php echo $an;?>
						</td>
					</tr>
				</table>
			<?php
			}



		if($comptidL!=0)
		{
			if(isset($_GET['examenPaHosp']))
			{
				
				if(isset($_GET['idmedLab']))
				{
				
					$annee = date('Y').'-'.date('m').'-'.date('d');
			?>
				<!-- <form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&idmedLab=<?php echo $_GET['idmedLab']; ?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
					
					
					<table class="tablesorter" style="margin: 20px auto auto; background: rgb(255, 255, 255) none repeat scroll 0% 0%; width: 80%; border: 1px solid rgb(238, 238, 238); border-radius: 5px; padding: 5px;">
						<tbody>
							<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
								<td style="font-size: 20px;">
									<span style="font-weight:bold;"><?php echo getString(139) ?>: </span>
						<?php echo $_GET['presta'];?>
							
								</td>
								
								<td style="color:#a00000; padding-right: 20px; padding-top: 10px; padding-bottom: 10px;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo 'Date d\'envoi : '.$annee;?></td>
							</tr>
							
							<tr>
								<td>
									<label style="padding-left: 40px; padding-top: 10px;" for="examen"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo getString(137) ?></label>
									
									<input type="text" id="examen" name="examen" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value=""/>
								</td>
								
								<td align="left" style="vertical-align: top; padding-left: 10px; padding-top:35px;display:none;">
								
									<label for="result"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo getString(138) ?></label>
									
									<input type="file" name="result" id="result" style="border:1px solid #eee; height:40px; padding 2px; width:95%;"/>
								
									<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
								</td>
							</tr>
						</tbody>
						
						<tfoot>
							<tr>
								<td colspan=2 style="text-align:center">
				
									<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="resultbtn" class="btn-large">
									<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
									</button>
									
									<a href="patients1_hosp.php?num=<?php echo $_GET['num'];?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></a>
									
									<input type="hidden" name="submitExa" value="<?php echo $_GET['idmedLab'];?>"/>
							
									
								</td>
							</tr>
						</tfoot>
						
					</table>
				</form> -->
			<?php
				}
				
				if(isset($_GET['updateidmedLabo']))
				{
					
					$updateResult=$connexion->prepare("SELECT *FROM med_labo WHERE id_medlabo =:updateidmedLabo");
					$updateResult->execute(array(
					'updateidmedLabo'=>$_GET['updateidmedLabo']
				
					));
					
					$updateResult->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
				
					$comptUpdateResult=$updateResult->rowCount();

					if($ligneUpdateResult=$updateResult->fetch())
					{
						$autreresultats=$ligneUpdateResult->autreresultats;
						$idFactureMedLabo=$ligneUpdateResult->id_factureMedLabo;
						$idPrestationExa=$ligneUpdateResult->id_prestationExa;
						$valeurLab=$ligneUpdateResult->valeurLab;
						$minresultats=$ligneUpdateResult->minresultats;
						$maxresultats=$ligneUpdateResult->maxresultats;
						$resultats=$ligneUpdateResult->resultats;
						
					}
				
			?>
				<form method="post" action="traitement_resultats.php?num=<?php echo $num;?>&updateidmedLabo=<?php echo $_GET['updateidmedLabo']; ?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResult(this)" enctype="multipart/form-data">
					
					
					<table class="tablesorter" style="padding: 5px;">
						<tbody>
							<tr style="background: rgb(248, 248, 248) none repeat scroll 0% 0%; font-size: 15px;">
								<td colspan=3 style="font-size: 20px;">
									<span style="font-weight:bold;"><?php echo getString(139) ?>: </span><?php echo $_GET['presta'];?>
							
								</td>
								<td style="color:#a00000; padding-right: 20px; padding-top: 10px; padding-bottom: 10px;"><i class="fa fa-calendar-o fa-lg fa-fw"></i> <?php echo 'Date d\'envoi : '.$annee;?></td>
							</tr>
							
							<tr>
								<td>
									<label style="text-align:right;padding-bottom:0;" for="examen"><i class="fa fa-keyboard-o fa-lg fa-fw"></i> <?php echo 'Modification des résultats' ?></label>
								</td>
								
								<td>
									<label style="text-align:center;padding-bottom:0;" for="valeur"><?php echo 'Valeur' ?></label>
								</td>
								
								<td>
									<label style="text-align:center;padding-bottom:0;" for="min"><?php echo 'Min' ?></label>
								</td>
								
								<td>
									<label style="text-align:left;padding-bottom:0;padding-left:30px;" for="max"><?php echo 'Max' ?></label>
									
								</td>
								
								<td align="center" style="vertical-align: top; padding-bottom:0;display:none;">
								
									<label for="result"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo 'Joindre nouveau résultat' ?></label>
								</td>
							</tr>
							
							<tr>
								<td style="text-align:right">
									<input type="text" id="examen" name="examen" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $autreresultats; ?>"/>
								</td>
								
								<td style="text-align:center">
									
									<input type="hidden" id="idprestationExa" name="idprestationExa[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $idPrestationExa;?>"/>
							
								<?php
								$k=0;
								
								if($idFactureMedLabo!=0)
								{
									$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen ORDER BY valeur');
									$resultValeur->execute(array(
									'idexamen'=>$idPrestationExa
									));
									
									$resultValeur->setFetchMode(PDO::FETCH_OBJ);

									$comptValeur=$resultValeur->rowCount();
									
									if($comptValeur!=0)
									{
									?>
										<select name="valeur" id="valeur" style="width:100px;">
							
											<option value='----' id="nominmax">
											<?php echo 'Select here...';?>
											</option>
											<?php

											while($ligneValeur=$resultValeur->fetch())
											{
											?>
											<option value='<?php echo $ligneValeur->valeur;?>' id='minmax' <?php if(isset($_GET['updateidmedLabo']) AND $ligneValeur->valeur==$valeurLab){ echo "selected='selected'";}else{ echo '';}?>>
											<?php
											if($ligneValeur->valeur!="")
											{
												echo $ligneValeur->valeur;
											}else{
												echo 'Default value';
											}
											?>
											</option>
											<?php
											}
											?>
										</select>
									
									<?php
									}elseif($comptValeur==0){
									
										if(isset($_GET['updateidmedLabo']))
										{
											echo "<input type='text' name='valeur' id='valeur' style='width:100px;' value='".$valeurLab."'/>";
										}else{
											echo "<input type='text' name='valeur' id='valeur' style='width:100px;' value=''/>";
										}
									
									}
								}
								?>
								</td>
								
								<td style="text-align:center">
									<input type="text" id="min" name="min" style="width:80px" value="<?php echo $minresultats;?>"/>
								</td>
								
								<td style="text-align:left">
									<input type="text" id="max" name="max" style="width:80px" value="<?php echo $maxresultats;?>"/>
								
									<?php
									/* if($resultats!="")
									{
									?>
										<a href="<?php echo $resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-folder-open fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
										
									<?php
									} */
									?>
								</td>
								
								
								<td style="text-align:center;display:none;">
								
									<input type="file" name="result" id="result" style="border:1px solid #eee; height:40px;width:250px;"/>
								
									<input type="hidden" name="MAX_FILE_SIZE" value="100000"/>
								</td>
								
							</tr>
						</tbody>
						
						<tfoot>
							<tr>
								<td colspan=4 style="text-align:center">
				
									<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="updateresultbtn" class="btn-large">
									<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(32) ?>
									</button>
									
									
									<a href="patients1_hosp.php?num=<?php echo $_GET['num'];?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="font-weight: 400; margin: 10px auto auto 10px; padding: 7px 40px 10px;" class="btn-large-inversed"><i class="fa fa-ban fa-lg fa-fw"></i> <?php echo getString(140);?></a>
									
									<input type="hidden" name="submitExa" value="<?php echo $_GET['updateidmedLabo'];?>"/>
							
									
								</td>
							</tr>
						</tfoot>
						
					</table>
				</form>
			<?php
				}
			}
			?>
			
			<?php
			if(isset($_GET['examenPaHosp']))
			{
			?>
				<span style="font-size:250%;"><?php echo getString(99);?></span>
			
			<?php
			}
			?>
			<table class="<?php  if(isset($_GET['examenPaHosp'])){ echo 'previewprint tablesorter1';}else{ echo 'tablesorter';}?>" cellpadding="5" style="background-color:#FFF;margin-top:10px;">
			<?php
		
			/*-----------Examen à faire---------------*/
			
			if(isset($_GET['examenPaHosp']))
			{
				$resultatsExamPa=$connexion->prepare("SELECT *FROM med_labo_hosp mlh,utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND mlh.numero=ph.numero AND (mlh.id_prestationExa IS NOT NULL OR mlh.autreExamen !='') AND mlh.examenfait=0 AND mlh.numero=:numPa ORDER BY mlh.id_medlabo DESC");
				
				$resultatsExamPa->execute(array(
				'numPa'=>$_GET['num']
				));
				
				$resultatsExamPa->setFetchMode(PDO::FETCH_OBJ);
			
				$comptResultExamPa=$resultatsExamPa->rowCount();

				
				$resultatsExamPaEdit=$connexion->prepare("SELECT *FROM med_labo_hosp mlh,utilisateurs u, patients_hosp ph WHERE u.id_u=ph.id_uHosp AND mlh.numero=ph.numero AND (mlh.id_prestationExa IS NOT NULL OR mlh.autreExamen !='') AND mlh.examenfait=1 AND mlh.numero=:numPa ORDER BY mlh.dateresultats DESC");
				
				$resultatsExamPaEdit->execute(array(
				'numPa'=>$_GET['num']
				));
				
				$resultatsExamPaEdit->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
			
				$comptResultExamPaEdit=$resultatsExamPaEdit->rowCount();
				
			}else{
				$start_week=strtotime("last week");
				$start_week=date("Y-m-d",$start_week);
				
				$resultatsL=$connexion->prepare("SELECT *FROM utilisateurs u, patients_hosp ph, med_labo_hosp mlh WHERE u.id_u=ph.id_uHosp AND mlh.numero=ph.numero AND mlh.dateconsu =:annee AND (mlh.dateresultats =:annee OR mlh.dateresultats ='0000-00-00' OR mlh.dateresultats IS NULL) AND (mlh.id_prestationExa IS NOT NULL OR mlh.autreExamen !='') GROUP BY ph.numero ORDER BY mlh.dateconsu DESC");
				$resultatsL->execute(array(
				'annee'=>$annee
				
				));
			
				
				$resultatsL->setFetchMode(PDO::FETCH_OBJ);
		
				$comptResultL=$resultatsL->rowCount();
			
			}
			
			
			if(!isset($_GET['examenPaHosp']))
			{
				if($comptResultL!=0)
				{
			?>
				<thead>
					<tr>
						<th>S/N</th>
						<th><?php echo getString(9);?></th>
						<th><?php echo getString(10);?></th>
						<th><?php echo getString(11);?></th>
						<th style="text-align:right;"><?php echo getString(99);?></th>
						<th>Rapport</th>
					</tr>
				</thead>
		<?php
				}
				
			}else{
				
				if(isset($_GET['examenPaHosp']))
				{
		?>
					<thead>
						<tr style="background-color:#A00000;color:white;margin-top:10px;">
							<th style="width:8%"><?php echo getString(97);?></th>
							<th><?php echo getString(19) ?></th>
							<th><?php echo getString(99);?></th>
							<th><?php echo 'Résultats';?></th>
							<th><?php echo 'Valeur';?></th>
							<th><?php echo 'Min';?></th>
							<th><?php echo 'Max'; ?></th>
							<th style="width:8%"><?php echo 'Date Résultats';?></th>
							<th><?php echo 'Done by';?></th>
							<th style="width:15%" colspan=2>Actions</th>
						</tr>
					</thead>
			<?php
				}
			}
			
			
			if(isset($_GET['examenPaHosp']))
			{
			?>
				<tbody>
				<?php
				if($comptResultExamPa != 0)
				{
					$comptFacture=0;
				?>
				<form method="post" action="traitement_resultats.php?num=<?php echo $_GET['num'];?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormResultat(this)" enctype="multipart/form-data">
					
					<?php
					$i=0;
					
					while($ligneExamPa=$resultatsExamPa->fetch())
					{
					
						$idassu=$ligneExamPa->id_assuLab;
					
						$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
						
						$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
						
						$assuCount = $comptAssuConsu->rowCount();
						
						for($l=1;$l<=$assuCount;$l++)
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

					?>
						<tr style="text-align:center;">
						
							<td>
								<?php echo $ligneExamPa->datehosp;?>
								<input type="hidden" id="idmedLaboResult<?php echo $i;?>" name="idmedLaboResult[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneExamPa->id_medlabo;?>"/>
								
								<input type="hidden" id="idprestationExa<?php echo $i;?>" name="idprestationExa[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;" value="<?php echo $ligneExamPa->id_prestationExa;?>"/>
							</td>
							
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') ;
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPa->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);
							
							if($ligneMed=$resultatsMed->fetch())
							{
								echo $ligneMed->full_name;
							}
							$resultatsMed->closeCursor();
							?>
							</td>
						
							<td>
							<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPa->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
										echo $lignePresta->namepresta;
									
									}else{
									
										$presta=$lignePresta->nompresta;
										echo $lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPa->autreExamen;
									$mesure='';
									
									echo $ligneExamPa->autreExamen;
								}
							
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<input type="text" id="resultats" name="resultats[]" style="background: rgb(250, 250, 250) none repeat scroll 0% 0%;width:130px" value="" placeholder="Taper les résultats ici..."/> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
							<?php
							}else{
								echo '---';
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
								$resultValeur=$connexion->prepare('SELECT *FROM valeurs_lab WHERE id_examen=:idexamen ORDER BY valeur');
								$resultValeur->execute(array(
								'idexamen'=>$ligneExamPa->id_prestationExa
								));
								
								$resultValeur->setFetchMode(PDO::FETCH_OBJ);

								$comptValeur=$resultValeur->rowCount();
								
								if($comptValeur!=0)
								{
								?>
									<select name="<?php echo $i;?>" id="valeur<?php echo $i;?>" style="width:100px;" onchange="ShowMinMax()">
						
										<option value='----' id="nominmax">
										<?php echo 'Select here...';?>
										</option>
										<?php
										$j = 0;
										while($ligneValeur=$resultValeur->fetch())
										{
										?>
										<option value='<?php echo $ligneValeur->valeur;?>' id='minmax<?php echo $j;?>'>
										<?php
										if($ligneValeur->valeur!="")
										{
											echo $ligneValeur->valeur;
										}else{
											echo 'Default value';
										}
										?>
										</option>
										<?php
										$j++;
										}
										?>
									</select>
								
								<?php
								}elseif($comptValeur==0){
								
									echo "<input type='text' name='".$i."' id='valeur'".$i."' style='width:100px;' value=''/>";
								
								}
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<input type="text" id="min<?php echo $i;?>" name="min[]" style="width:60px;" value=""/>
							<?php
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<input type="text" id="max<?php echo $i;?>" name="max[]" style="width:60px;" value=""/>
							<?php
							}
							?>
							</td>
							
							
							<td style="display:none">
								<input type="file" name="autreresult[]" id="autreresult" style="border:1px solid #eee; height:40px; padding 2px; width:230px;"/>
								
								<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
							</td>
							
							
							<td>
							<?php
							if($ligneExamPa->dateresultats != '0000-00-00')
							{
								echo $ligneExamPa->dateresultats;
							}
							?>
							</td>

							<td></td>
							
							<td colspan=2>
							<?php
							
							if($ligneExamPa->id_factureMedLabo>=0)
							{
							?>
								<a href="moreresultats.php?num=<?php echo $ligneExamPa->numero;?>&examenPaHosp=ok&idmedLab=<?php echo $ligneExamPa->id_medlabo;?>&idmed=<?php echo $ligneExamPa->id_uM;?>&idassu=<?php echo $ligneExamPa->id_assuLab;?>&dateconsu=<?php echo $ligneExamPa->datehosp;?>&dateresultats=<?php echo $ligneExamPa->dateresultats;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large"><i class="fa fa-plus fa-0.5x fa-fw"> </i> <?php echo 'Add'; ?></a>
								
								
							<?php
							}else{
							?>
								<span style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; border:1px solid #aaa; border-radius: 4px; padding: 8px 5px;"><?php echo getString(218) ?></span>
							<?php
							}
							?>
							</td>
							
						</tr>
					<?php
						$comptFacture=$comptFacture+$ligneExamPa->id_factureMedLabo;
						
						$i++;	
					}
					$resultatsExamPa->closeCursor();
					
					if($comptFacture>=0)
					{
					?>
						
						
						<input type='hidden' id='iVal' value='<?php echo $i-1;?>'/>
						<tr>
							<td colspan=11 style="text-align:center">
								<button style="height: 40px; width: 300px; margin: 10px auto auto; font-weight:100" type="submit" name="resultatbtn" class="btn-large">
								<i class="fa fa-paper-plane fa-lg fa-fw"></i> <?php echo getString(68) ?>
								</button>
								
							</td>
							
						</tr>
				<?php
					}
				}
				?>
				</form>
				<?php
				
				if($comptResultExamPaEdit != 0)
				{
					while($ligneExamPaEdit=$resultatsExamPaEdit->fetch())//on recupere la liste des éléments
					{
					
						$idassu=$ligneExamPaEdit->id_assuLab;
						
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


					
						if($ligneExamPaEdit->moreresultats!=0)
						{
				?>
						
						<tr style="text-align:center;<?php if($ligneExamPaEdit->id_uL == $_SESSION['id']){ echo "background-color:rgba(0,50,255,0.15);";}else{ echo "background-color:rgba(250,250,0,0.15);";}?>">
						
							<td><?php echo $ligneExamPaEdit->dateconsu;?></td>
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') ;
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPaEdit->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
							?>
								<?php echo $ligneMed->full_name;?>
								
							<?php
							}
							$resultatsMed->closeCursor();
							?>
							</td>
							
							<td>
							
								<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
									}else{
									
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPaEdit->autreExamen;
									$mesure='';
								}
								
									echo $presta;
								?>
							</td>
							
							<td colspan=4>
							</td>
						
							<td>
							<?php
							if($ligneExamPaEdit->dateresultats != '0000-00-00')
							{
								echo $ligneExamPaEdit->dateresultats;
							}
							?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
							</td>
							
							<td>
							<?php
							$idLabo=$ligneExamPaEdit->id_uL;
							
							$resultLaboId=$connexion->query( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=$idLabo");
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameLabo==$ligneLaboId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneLaboId->full_name;
								}
							}
							
							?>
							</td>
							
							
							<td colspan=2>
							<?php
							if($ligneExamPaEdit->examenfait==1)
							{
							?>
								<a href="<?php if($ligneExamPaEdit->moreresultats==1){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==2){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidspermomedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==0){ echo 'patients1_hosp.php?updateidmedLabo='.$ligneExamPaEdit->id_medlabo.'&idassuLab='.$ligneExamPaEdit->id_assuLab.'&examenPaHosp=ok';}}}?>&num=<?php echo $ligneExamPaEdit->numero;?>&dateconsu=<?php echo $ligneExamPaEdit->dateconsu;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="envoiresult" name="envoiresult" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"> </i> <?php echo getString(32) ?></a>
							<?php
							}
							?>
							</td>
							
							
						</tr>
						
							<?php
							if($ligneExamPaEdit->moreresultats==1)
							{
								$resultMoreMedLabo=$connexion->prepare('SELECT *FROM more_med_labo mml WHERE mml.numero=:num AND mml.id_medlabo=:idmedLab ORDER BY mml.id_moremedlabo');
								$resultMoreMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneExamPaEdit->id_medlabo
								));
								
								$resultMoreMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptMoreMedLabo=$resultMoreMedLabo->rowCount();
								
								while($ligneMoreMedLabo=$resultMoreMedLabo->fetch())
								{
									
									$idassuLab=$ligneExamPaEdit->id_assuLab;
									
									$comptAssuConsu=$connexion->query('SELECT *FROM assurances a ORDER BY a.id_assurance');
									
									$comptAssuConsu->setFetchMode(PDO::FETCH_OBJ);
									
									$assuCount = $comptAssuConsu->rowCount();
									
									for($a=1;$a<=$assuCount;$a++)
									{
										
										$getAssuConsu=$connexion->prepare('SELECT *FROM assurances a WHERE a.id_assurance=:idassuLab ORDER BY a.id_assurance');
										$getAssuConsu->execute(array(
										'idassuLab'=>$idassuLab
										));
										
										$getAssuConsu->setFetchMode(PDO::FETCH_OBJ);

										if($ligneNomAssu=$getAssuConsu->fetch())
										{
											$presta_assuLab='prestations_'.$ligneNomAssu->nomassurance;
										}
									}

								?>
								<tr>
									<td colspan=2></td>
									<td>
									<?php
										$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assuLab.' p WHERE p.id_prestation=:prestaId');
										$resultPresta->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
										
										$resultPresta->setFetchMode(PDO::FETCH_OBJ);

										$comptPresta=$resultPresta->rowCount();
										
										if($lignePresta=$resultPresta->fetch())
										{
											if($lignePresta->namepresta!='')
											{
												$presta=$lignePresta->namepresta;
											
											}else{
											
												$presta=$lignePresta->nompresta;
											}
											$mesure=$lignePresta->mesure;
										}else{
											$presta=$ligneMoreMedLabo->autreExamen;
											$mesure='';
										}
										
										echo $presta;
									?>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span>
									</td>
									
									<td>
									<?php echo $ligneMoreMedLabo->valeurLab;?>
									</td>
									
									<td>
									<?php
										$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');
										$resultExa->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
								
										$resultExa->setFetchMode(PDO::FETCH_OBJ);
										$comptExa=$resultExa->rowCount();

										if($ligneMoreMedLabo->minresultats =="")
										{
											if($comptExa ==1)
											{
												$ligneMin=$resultExa->fetch();
												echo $ligneMin->min_valeur;
											}
										}else{
											echo $ligneMoreMedLabo->minresultats;
										}
									?>
									</td>
									
									<td>
									<?php
										$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');
										$resultExa->execute(array(
										'prestaId'=>$ligneMoreMedLabo->id_prestationExa
										));
										
										$resultExa->setFetchMode(PDO::FETCH_OBJ);
										$comptExa=$resultExa->rowCount();

										if($ligneMoreMedLabo->maxresultats =="")
										{
											if($comptExa ==1)
											{
												$ligneMin=$resultExa->fetch();
												echo $ligneMin->max_valeur;
											}
										}else{
											echo $ligneMoreMedLabo->maxresultats;
										}
									?>
									</td>
									
									<td style='display:none'>
									<?php
									if($ligneMoreMedLabo->resultats!="")
									{
									?>
										<a href="<?php echo $ligneMoreMedLabo->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
									<?php
									}
									?>
									</td>
							
									<td colspan=4></td>
									
								</tr>
							<?php
								}
							}
							
							if($ligneExamPaEdit->moreresultats==2)
							{
								
								$resultSpermoMedLabo=$connexion->prepare('SELECT *FROM spermo_med_labo sml WHERE sml.numero=:num AND sml.id_medlabo=:idmedLab ORDER BY sml.id_spermomedlabo');
								$resultSpermoMedLabo->execute(array(
								'num'=>$_GET['num'],
								'idmedLab'=>$ligneExamPaEdit->id_medlabo
								));
								
								$resultSpermoMedLabo->setFetchMode(PDO::FETCH_OBJ);

								$comptSpermoMedLabo=$resultSpermoMedLabo->rowCount();
								
								while($ligneSpermoMedLabo=$resultSpermoMedLabo->fetch())
								{
								?>
								<tr style="background-color:#eee">
									<td colspan=5>EXAMEN MACROSCOPIQUES</td>
									<td style='border-left:1px solid #aaa;' colspan=6>EXAMEN MICROSCOPIQUES</td>
								</tr>
								
								<tr>
									<td>Volume</td>
									<td>Densité</td>
									<td>Viscosité</td>
									<td>PH</td>
									<td>Aspect</td>
									
									<td style='border-left:1px solid #aaa;'>Examen direct</td>
									<td>Mobilité après</td>
									<td>Numération</td>
									<td>V.N</td>
									<td>Spermocytogramme</td>
									<td>Autres</td>
								
								</tr>
								
								<tr>
									<td>
									<?php echo $ligneSpermoMedLabo->volume;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->densite;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->viscosite;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->ph;?>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->aspect;?>
									</td>
									
									<td style='border-left:1px solid #aaa;'>
									<?php echo $ligneSpermoMedLabo->examdirect;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td style='border-left:1px solid #aaa;'>0h après emission</td>
												<td>1h après emission</td>
												<td>2h après emission</td>
												<td>3h après emission</td>
												<td style='border-right:1px solid #aaa;'>4h après emission</td>
											</tr>
											
											<tr>
												<td style='border-left:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->zeroheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->uneheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->deuxheureafter;?>
												</td>
												<td>
												<?php echo $ligneSpermoMedLabo->troisheureafter;?>
												</td>
												<td style='border-right:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->quatreheureafter;?>
												</td>
											</tr>
										</table>
									</td>
									
									<td>
									<?php echo $ligneSpermoMedLabo->numeration;?>
									</td>
									
									<td>
									<?php echo $ligneSpermoMedLabo->vn;?>
									</td>
									
									<td>
										<table>
											<tr>
												<td style='border-left:1px solid #aaa;'>Forme typique</td>
												<td style='border-right:1px solid #aaa;'>Forme atypique</td>
											</tr>
											
											<tr>
												<td style='border-left:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->formtypik;?>
												</td>
												<td style='border-right:1px solid #aaa;'>
												<?php echo $ligneSpermoMedLabo->formatypik;?>
												</td>
											</tr>
										</table>
									</td>
									<td>
									<?php echo $ligneSpermoMedLabo->autre;?>
									</td>
									
								</tr>
								
								<tr style="background-color:#eee">
									<td colspan=11>CONCLUSION</td>
									
								</tr>
								<tr>
									<td colspan=11>
									<?php echo $ligneSpermoMedLabo->conclusion;?>
									</td>
									
								</tr>
								<?php
								}
							}
						}else{
						?>
						
						<tr style="text-align:center;<?php if($ligneExamPaEdit->id_uL == $_SESSION['id']){ echo "background-color:rgba(0,50,255,0.15);";}else{ echo "background-color:rgba(250,250,0,0.15);";}?>">
						
							<td><?php echo $ligneExamPaEdit->dateconsu;?></td>
							<td>
							<?php
							$resultatsMed=$connexion->prepare('SELECT *FROM utilisateurs u, medecins m WHERE u.id_u=m.id_u AND m.id_u=:idMed') ;
							$resultatsMed->execute(array(
							'idMed'=>$ligneExamPaEdit->id_uM
							));

							$resultatsMed->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet
							
							if($ligneMed=$resultatsMed->fetch())//on recupere la liste des éléments
							{
							?>
								<?php echo $ligneMed->full_name;?>
								
							<?php
							}
							$resultatsMed->closeCursor();
							?>
							</td>
							
							<td>
							
								<?php
								$resultPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:prestaId');
								$resultPresta->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

								$comptPresta=$resultPresta->rowCount();
								
								if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
								{
									if($lignePresta->namepresta!='')
									{
										$presta=$lignePresta->namepresta;
									}else{
									
										$presta=$lignePresta->nompresta;
									}
									$mesure=$lignePresta->mesure;
								}else{
									$presta=$ligneExamPaEdit->autreExamen;
									$mesure='';
								}
									
									echo $presta;
								?>
								
							</td>
							
							<td><?php echo $ligneExamPaEdit->autreresultats;?> <span style="font-size:80%; font-weight:normal;padding:5px;"><?php if($mesure!=''){ echo $mesure;}?></span></td>
							
							<td><?php echo $ligneExamPaEdit->valeurLab;?></td>
							
							<td>
							<?php
								$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');
								$resultExa->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneExamPaEdit->minresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->min_valeur;
									}
								}else{
									echo $ligneExamPaEdit->minresultats;
								}
							?>
							</td>
							
							<td>
							<?php
								$resultExa=$connexion->prepare('SELECT *FROM valeurs_lab v WHERE v.id_examen=:prestaId');
								$resultExa->execute(array(
								'prestaId'=>$ligneExamPaEdit->id_prestationExa
								));
								
								$resultExa->setFetchMode(PDO::FETCH_OBJ);
								$comptExa=$resultExa->rowCount();

								if($ligneExamPaEdit->maxresultats =="")
								{
									if($comptExa ==1)
									{
										$ligneMin=$resultExa->fetch();
										echo $ligneMin->max_valeur;
									}
								}else{
									echo $ligneExamPaEdit->maxresultats;
								}
							?>
							</td>
							
							<td style='display:none'>
							<?php
							if($ligneExamPaEdit->resultats!="")
							{
							?>
								<a href="<?php echo $ligneExamPaEdit->resultats;?>" id="viewresult" name="viewresult" class="btn" target="_blank"><i class="fa fa-paperclip fa-lg fa-fw"></i> <?php echo 'Fichier joint';?></a>
							<?php
							}
							?>
							</td>
							
							<td>
							<?php
							if($ligneExamPaEdit->dateresultats != '0000-00-00')
							{
								echo $ligneExamPaEdit->dateresultats;
							}
							?>
							</td>
							
							<td>
							<?php
							$idLabo=$ligneExamPaEdit->id_uL;
							
							$resultLaboId=$connexion->prepare( "SELECT *FROM utilisateurs u, laborantins l WHERE u.id_u=l.id_u AND l.id_u=:idLabo");
							$resultLaboId->execute(array(
							'idLabo'=>$idLabo
							));
							
							$resultLaboId->setFetchMode(PDO::FETCH_OBJ);
							
							$comptLaboId=$resultLaboId->rowCount();
							
							if( $ligneLaboId = $resultLaboId->fetch())
							{
								$fullnameLabo=$_SESSION['nom'].' '.$_SESSION['prenom'];
								if($fullnameLabo==$ligneLaboId->full_name)
								{
									echo "Vous même";
								}else{
									echo $ligneLaboId->full_name;
								}
							}
							?>
							</td>
							
							
							<td>
							<?php
							if($ligneExamPaEdit->examenfait==1 AND $ligneExamPaEdit->id_uL==$_SESSION['id'])
							{
								/* if($ligneExamPaEdit->postdiagnostic=='')
								{ */
							?>
								<a href="<?php if($ligneExamPaEdit->moreresultats==1){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==2){ echo 'moreresultats.php?labo='.$ligneExamPaEdit->id_uL.'&idmedLab='.$ligneExamPaEdit->id_medlabo.'&idmed='.$ligneExamPaEdit->id_uM.'&idassu='.$ligneExamPaEdit->id_assuLab.'&previewprint=ok&updateidmoremedLabo=ok';}else{ if($ligneExamPaEdit->moreresultats==0){ echo 'patients1_hosp.php?updateidmedLabo='.$ligneExamPaEdit->id_medlabo.'&idassuLab='.$ligneExamPaEdit->id_assuLab.'&examenPaHosp=ok';}}}?>&num=<?php echo $ligneExamPaEdit->numero;?>&dateconsu=<?php echo $ligneExamPaEdit->dateconsu;?>&presta=<?php echo $presta;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="envoiresult" name="envoiresult" class="btn"><i class="fa fa-pencil-square-o fa-lg fa-fw"> </i> <?php echo getString(32) ?></a>
							<?php
								/* }else{
									echo 'Dignosis Done';
								} */
							}
							?>
							</td>
							
						</tr>
						<?php
						}
				
					}
					$resultatsExamPaEdit->closeCursor();
				}

			
			}else{
				
				if($comptResultL!=0)
				{
			?>
					<span style="position:relative; font-size:250%;"><?php echo getString(108);?></span>
					
					<?php

					while($ligneL=$resultatsL->fetch())//on recupere la liste des éléments
					{
						
						$doneResultsLabo=$connexion->prepare('SELECT *FROM utilisateurs u, patients p, consultations c, med_labo ml WHERE u.id_u=p.id_u AND c.numero=p.numero AND ml.numero=p.numero AND ml.numero=:num AND c.id_consu=ml.id_consuLabo AND (ml.dateresultats="0000-00-00" OR ml.dateresultats IS NULL) ORDER BY c.numero');

						$doneResultsLabo->execute(array(
						'num'=>$ligneL->numero
						));

						$doneResultsLabo->setFetchMode(PDO::FETCH_OBJ);
					
						$comptDoneResultsLabo=$doneResultsLabo->rowCount();
				
					?>
						<tr style="text-align:center;<?php if($comptDoneResultsLabo==0){ echo 'background:rgba(0,100,255,0.5);';}?>">

							<td><?php echo $ligneL->numero;?></td>
							<td><?php echo $ligneL->nom_u;?></td>
							<td><?php echo $ligneL->prenom_u;?></td>
							<td>
							<?php
							if($ligneL->sexe=="M")
							{
								echo getString(12);
							}else{
								if($ligneL->sexe=="F")
								echo getString(13);
							}
							?>
							</td>
							
							<td style="text-align:right;">
							
								<a href="patients1_hosp.php?num=<?php echo $ligneL->numero;?>&examenPaHosp=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(109);?></a>
								
							</td>
							
							<td>
							
								<a href="patients_laboreport.php?num=<?php echo $ligneL->numero;?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo 'Rapport labo';?></a>
								
							</td>

						</tr>
				<?php
					}
					$resultatsL->closeCursor();
	
				}
				?>
				</tbody>
			
			<?php
			}
			?>
			
			</table>
	
		<?php
		}

?>


<script>

function getXMLHttpRequest() {
var xhr = null;

if (window.XMLHttpRequest || window.ActiveXObject) {
	if (window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	} else {
		xhr = new XMLHttpRequest(); 
	}
}else {
	alert("Your Browser does not support XMLHTTPRequest object...");
	return null;
}

return xhr;
}


function ShowSearch(search)
{
	if(search =='byname')
	{
		document.getElementById('results').style.display='inline';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsRI').style.display='none';
	}
	
	if(search =='bysn')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='inline';
		document.getElementById('resultsRI').style.display='none';
	}
	
	if(search =='byri')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='none';
		document.getElementById('resultsRI').style.display='inline';
	}
}

	function ShowList(list)
	{
		if( list =='Users')
		{
			document.getElementById('divMenuUser').style.display='inline-block';
			
			document.getElementById('listOn').style.display='inline';
			<?php
			if(isset($_GET['iduti']))
			{
			?>
			document.getElementById('newUser').style.display='inline';
			<?php
			}
			?>
			document.getElementById('listOff').style.display='none';
			
			document.getElementById('divMenuMsg').style.display='none';
			document.getElementById('divItem').style.display='none';
			document.getElementById('divListe').style.display='none';
		
		}
		
		if( list =='Msg')
		{
			document.getElementById('divMenuMsg').style.display='inline-block';
			document.getElementById('divMenuUser').style.display='none';
			document.getElementById('divItem').style.display='none';
			document.getElementById('divListe').style.display='none';
			
		}
		
		if( list =='Items')
		{
			document.getElementById('divItem').style.display='inline-block';
			document.getElementById('divMenuUser').style.display='none';
			document.getElementById('divMenuMsg').style.display='none';
			document.getElementById('divListe').style.display='none';
		}
		
		if( list =='Liste')
		{
			document.getElementById('divListe').style.display='inline';
			document.getElementById('listOff').style.display='inline';
			document.getElementById('listOn').style.display='none';
		}
		
		if( list =='ListeNon')
		{
			document.getElementById('divListe').style.display='none';
			document.getElementById('listOn').style.display='inline';
			document.getElementById('listOff').style.display='none';
		}
		
		if( list =='MsgRecu')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='inline-block';
			document.getElementById('EnvoiMsg').style.display='inline-block';
			document.getElementById('MsgEnvoye').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
		if( list =='MsgEnvoye')
		{
			document.getElementById('formMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('MsgEnvoye').style.display='none';
			document.getElementById('EnvoiMsg').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='inline-block';
			document.getElementById('envoye').style.display='inline-block';
		}
		
		if( list =='EnvoiMsg')
		{
			document.getElementById('formMsg').style.display='inline-block';
			document.getElementById('MsgEnvoye').style.display='inline-block';
			document.getElementById('MsgRecu').style.display='inline-block';
			document.getElementById('EnvoiMsg').style.display='none';
			document.getElementById('reception').style.display='none';
			document.getElementById('envoye').style.display='none';
		}
		
	}
function controlFormPassword(theForm){
	var rapport="";
	
	rapport +=controlPass(theForm.Pass);

		if (rapport != "") {
		alert("Please review the following fields:\n" + rapport);
					return false;
		 }
}


function controlPass(fld){
	var erreur="";
	
	if(fld.value=="")
	{
		erreur="Your new password\n";
		fld.style.background="rgba(0,255,0,0.3)";
	}
	
	return erreur;
}

</script>

<?php
	
	}else{
		echo '<script language="javascript"> alert("You\'ve been disabled!!\n Ask the administrator to enable you");</script>';
		echo '<script language="javascript">document.location.href="index.php"</script>';
	}
}else{
	echo '<script language="javascript">document.location.href="index.php"</script>';
}



	if(isset($_POST['confirmPass']))
	{
		
		if(isset($_GET['receptioniste']))
		{
			$caissrecep= '&receptioniste='.$_GET['receptioniste'];
		}else{
			if(isset($_GET['caissier']))
			{
				$caissrecep= '&caissier='.$_GET['caissier'];
			}else{
				$caissrecep='';
			}
		}

	
		$pass = $_POST['Pass'];
		$iduti = $_SESSION['id'];
				
		$resultats=$connexion->prepare('UPDATE utilisateurs SET password=:pass WHERE id_u=:modifierIduti');
						
		$resultats->execute(array(
		'pass'=>$pass,
		'modifierIduti'=>$iduti
		));
			
		echo '<script type="text/javascript"> alert("Your password have been changed\nYour new password is : '.$pass.'");</script>';
		
	}

?>
</div>

<div> <!-- footer -->
	<?php
		include('footer.php');
	?>
</div> <!-- /footer -->
	
</body>

</html>