<?php
include("connect.php");
include("connectLangues.php");
    $annee = date('Y').'-'.date('m').'-'.date('d');

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
			$idP=$ligne->id_u;
		}
		$resultats->closeCursor();

	}


    $numero=$_GET['num'];
    $idbill=$_GET['idbill'];
    
    
    $checkIdBill=$connexion->prepare('SELECT *FROM bills b WHERE b.id_bill=:idbill ORDER BY b.id_bill LIMIT 1');

    $checkIdBill->execute(array(
    'idbill'=>$idbill
    ));

    $comptidBill=$checkIdBill->rowCount();

    // echo $comptidBill;
    
    if($comptidBill != 0)
    {
        $checkIdBill->setFetchMode(PDO::FETCH_OBJ);
        
        $ligne=$checkIdBill->fetch();
        
        $idBilling = $ligne->id_bill;
        $datebill = $ligne->datebill;

            $resultAssurance=$connexion->prepare('SELECT *FROM assurances a WHERE a.nomassurance=:assuName');

            $resultAssurance->execute(array(
                'assuName'=>$ligne->nomassurance
            ));

            $resultAssurance->setFetchMode(PDO::FETCH_OBJ);

            if($ligneAssu=$resultAssurance->fetch())
            {
                $idassu = $ligneAssu->id_assurance;
                $nomassurancebill = $ligneAssu->nomassurance;
                $presta_assu='prestations_'.strtolower($ligneAssu->nomassurance);
            }

        $idorgBill = $ligne->idorgBill;
        $idcardbill = $ligne->idcardbill;
        $numpolicebill = $ligne->numpolicebill;
        $adherentbill = $ligne->adherentbill;
        $bill = $ligne->billpercent;
        $dateconsu = $ligne->dateconsu;
        $vouchernum = $ligne->vouchernum;
        
        $numbill = $ligne->numbill;
        $createBill = 0;

        $cashierBy = $ligne->codecashier;
        
    }
    
    //Get Consultation Id
    $resultatConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.id_factureConsult=:id_Bill AND p.numero=:num AND p.numero=c.numero AND c.numero=:num ORDER BY c.id_consu');
    $resultatConsult->execute(array(
    'id_Bill'=>$idbill,
    'num'=>$numero
    ));

    $resultatConsult->setFetchMode(PDO::FETCH_OBJ);

    $comptConsult=$resultatConsult->rowCount();
    if($comptConsult!=0){
     $ligneconsu = $resultatConsult->fetch();
     $id_uM=$ligneconsu->id_uM;
     $idconsuAdd=$ligneconsu->id_consu;
     $dateconsu=$ligneconsu->dateconsu;
    }else{

        //Get Consultation Id
        $resultatConsult=$connexion->prepare('SELECT *FROM consultations c, patients p WHERE c.dateconsu=:dateconsu AND p.numero=c.numero AND c.numero=:num ORDER BY c.id_consu');
        $resultatConsult->execute(array(
        'dateconsu'=>$annee,
        'num'=>$numero
        ));

        $resultatConsult->setFetchMode(PDO::FETCH_OBJ);

        $comptConsult=$resultatConsult->rowCount();
        if($comptConsult!=0){
         $ligneconsu = $resultatConsult->fetch();
         $id_uM=$ligneconsu->id_uM;
         $idconsuAdd=$ligneconsu->id_consu;
         $dateconsu=$ligneconsu->dateconsu;
        }

    }

	// $idassu=$_GET['idassu'];
	// $bill=$_GET['billpercent'];
	
	
	// $numero=$_GET['num'];





if(isset($_POST['checkprestaSurge']))
{
	$addSurge = array();
	$surgefait=0;

	foreach($_POST['checkprestaSurge'] as $valeurSurge)
	{
		$addSurge[] = $valeurSurge;
	}
		
	for($i=0;$i<sizeof($addSurge);$i++)
	{
		
		$resSurge=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=4 AND p.id_prestation="'.$addSurge[$i].'" ORDER BY p.nompresta');
					
		$comptSurge=$resSurge->rowCount();
			
		if($ligneprestaSurge=$resSurge->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaSurge=$ligneprestaSurge->prixpresta;
			$prixprestaSurgeCCO=$ligneprestaSurge->prixprestaCCO;

			// echo $addSurge[$i].' ('.$ligneprestaSurge->prixpresta.')<br/>';
		}
		
		
		$resultatSurge=$connexion->prepare('INSERT INTO med_surge (dateconsu,id_prestationSurge,prixprestationSurge,prixprestationSurgeCCO,surgefait,id_assuSurge,insupercentSurge,numero,id_uM,id_consuSurge,id_factureMedSurge,codecashier) VALUES(:dateconsu,:idPrestaSurge,:prixPresta,:prixPrestaCCO,:surgefait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedSurge,:codecashier)');
		
		$resultatSurge->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaSurge'=>$addSurge[$i],
		'prixPresta'=>$prixprestaSurge,
		'prixPrestaCCO'=>$prixprestaSurgeCCO,
		'surgefait'=>$surgefait,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd,
        'id_factureMedSurge'=>$idbill,
        'codecashier'=>$cashierBy
		
		)) or die( print_r($connexion->errorInfo()));
		
				
		
	}
	
}


if(isset($_POST['autreprestaSurge']))
{
    $surgefait=0;
    $idAutrePresta=$_POST['autreprestaSurge'];

    if($idAutrePresta != "")
    {
        $id_categopresta=4;
        $id_souscategopresta=0;

        $mesure=NULL;
        $statupresta=0;

        $addSurgeautre = array();
        $addSurgeautreprix = array();

        foreach($_POST['autreprestaSurge'] as $valeurSurge)
        {
            $addSurgeautre[] = $valeurSurge;

        }
        foreach($_POST['autreprixprestaSurge'] as $valeurSurgeprix)
        {
            $addSurgeautreprix[] = $valeurSurgeprix;

        }
        foreach($_POST['autreprixprestaSurgeCCO'] as $valeurSurgeprixCCO)
        {
            $addSurgeautreprixCCO[] = $valeurSurgeprixCCO;

        }

        for($i=0;$i<sizeof($addSurgeautre);$i++)
        {
            if($addSurgeautre[$i]!="")
            {
                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=4 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addSurgeautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addSurgeautre[$i]),
                        'namepresta'=>nl2br($addSurgeautre[$i]),
                        'prixpresta'=>$addSurgeautreprix[$i],
                        'prixprestaCCO'=>$addSurgeautreprixCCO[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

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

                    $comptPrixprestaconsu=$prixprestasurge->rowCount();

                    if($lignePrixprestasurge=$prixprestasurge->fetch())
                    {
                        $prixSurge=$lignePrixprestasurge->prixpresta;
                        $prixSurgeCCO=$lignePrixprestasurge->prixprestaCCO;
                    }

                    $resultat=$connexion->prepare('INSERT INTO med_surge (dateconsu,id_prestationSurge,prixprestationSurge,prixprestationSurgeCCO,surgefait,id_assuSurge,insupercentSurge,numero,id_uM,id_consuSurge,:id_factureMedSurge,:codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:surgefait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedSurge,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$prixSurge,
                        'prixPrestaCCO'=>$prixSurgeCCO,
                        'surgefait'=>$surgefait,
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'numero'=>$numero,
                        'id_uM'=>$id_uM,
                        'idconsuAdd'=>$idconsuAdd,
                        'id_factureMedSurge'=>$idbill,
                        'codecashier'=>$cashierBy

                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}


if(isset($_POST['checkprestaInf']))
{
	$addInf = array();
	$soinsfait=0;

	foreach($_POST['checkprestaInf'] as $valeurInf)
	{
		$addInf[] = $valeurInf;
	}

	for($i=0;$i<sizeof($addInf);$i++)
	{

		$resInf=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=3 AND p.id_prestation="'.$addInf[$i].'" ORDER BY p.nompresta');

		$comptInf=$resInf->rowCount();

		if($ligneprestaInf=$resInf->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaInf=$ligneprestaInf->prixpresta;

			// echo $addInf[$i].' ('.$ligneprestaInf->prixpresta.')<br/>';
		}


		$resultatInf=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_consuInf,id_factureMedInf,codecashier) VALUES(:dateconsu,:idPrestaInf,:prixPresta,:soinsfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedInf,:codecashier)');

		$resultatInf->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaInf'=>$addInf[$i],
		'prixPresta'=>$prixprestaInf,
		'soinsfait'=>$soinsfait,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd,
        'id_factureMedInf'=>$idbill,
        'codecashier'=>$cashierBy

		)) or die( print_r($connexion->errorInfo()));



	}

}


if(isset($_POST['autreprestaInf']))
{
    $soinsfait=0;
    $idAutrePresta=$_POST['autreprestaInf'];

    if($idAutrePresta != "")
    {
        $id_categopresta=3;
        $id_souscategopresta=0;

        $mesure=NULL;
        $statupresta=0;

        $addInfautre = array();
        $addInfautreprix = array();

        foreach($_POST['autreprestaInf'] as $valeurInf)
        {
            $addInfautre[] = $valeurInf;

        }
        foreach($_POST['autreprixprestaInf'] as $valeurInfprix)
        {
            $addInfautreprix[] = $valeurInfprix;

        }
        foreach($_POST['autreprixprestaInfCCO'] as $valeurInfprixCCO)
        {
            $addInfautreprixCCO[] = $valeurInfprixCCO;

        }

        for($i=0;$i<sizeof($addInfautre);$i++)
        {
            if($addInfautre[$i]!="")
            {
                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=3 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addInfautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addInfautre[$i]),
                        'namepresta'=>nl2br($addInfautre[$i]),
                        'prixpresta'=>$addInfautreprix[$i],
                        'prixprestaCCO'=>$addInfautreprixCCO[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

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

                    $comptPrixprestaconsu=$prixprestainf->rowCount();

                    if($lignePrixprestainf=$prixprestainf->fetch())
                    {
                        $prixInf=$lignePrixprestainf->prixpresta;
                        $prixInfCCO=$lignePrixprestainf->prixprestaCCO;
                    }

                    $resultat=$connexion->prepare('INSERT INTO med_inf (dateconsu,id_prestation,prixprestation,prixprestationCCO,soinsfait,id_assuInf,insupercentInf,numero,id_uM,id_consuInf,id_factureMedInf,codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:soinsfait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedInf,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$prixInf,
                        'prixPrestaCCO'=>$prixInfCCO,
                        'soinsfait'=>$soinsfait,
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'numero'=>$numero,
                        'id_uM'=>$id_uM,
                        'idconsuAdd'=>$idconsuAdd,
                        'id_factureMedInf'=>$idbill,
                        'codecashier'=>$cashierBy

                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}


if(isset($_POST['checkprestaLab']))
{
	$addLab = array();
	
	$prixautreExa=0;
	$examenfait=0;
	$dateresultat="0000-00-00";
	$idfacture=0;

	foreach($_POST['checkprestaLab'] as $valeurLab)
	{
		$addLab[] = $valeurLab; 
	}
	
	
	for($i=0;$i<sizeof($addLab);$i++)
	{

		$resLab=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=12 AND p.id_prestation="'.$addLab[$i].'" ORDER BY p.nompresta');
					
		$comptLab=$resLab->rowCount();
			
		if($ligneprestaLab=$resLab->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaLab=$ligneprestaLab->prixpresta;

			// echo $addLab[$i].' ('.$prixprestaLab.')<br/><br/>';
		}


		$resultatLabo=$connexion->prepare('INSERT INTO med_labo (dateconsu,id_prestationExa,prixprestationExa,prixautreExamen,id_assuLab,insupercentLab,examenfait,dateresultats,numero,id_uM,id_consuLabo,id_factureMedLabo,codecashier) VALUES(:dateconsu,:idPrestaLab,:prixPresta,:prixautreExa,:idassu,:bill,:examenfait,:dateresultat,:numero,:id_uM,:idconsuAdd,:idfacture,:codecashier)');
		$resultatLabo->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaLab'=>$addLab[$i],
		'prixPresta'=>$prixprestaLab,
		'prixautreExa'=>$prixautreExa,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'examenfait'=>$examenfait,
		'dateresultat'=>$dateresultat,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd,
		'idfacture'=>$idbill,
        'codecashier'=>$cashierBy
		)) or die( print_r($connexion->errorInfo()));

			/* echo 'Date : '.$dateconsu.'<br/>';
			echo 'Id presta : '.$addLab[$i].'<br/>';
			echo 'Prix presta : '.$prixprestaLab.'<br/>';
			echo '% : '.$bill.'<br/>';
			echo 'Examenfait : '.$examenfait.'<br/>';
			echo 'Numero Patient : '.$numero.'<br/>';
			echo 'Id Medecin : '.$id_uM.'<br/>';
			echo 'Id Consu : '.$idconsuAdd.'<br/><br/>'; */
	}
}


if($_POST['autreprestaLab']!="")
{
    $examenfait=0;
    $idfacture=0;
    $idAutreExam=$_POST['autreprestaLab'];

    if($idAutreExam != "")
    {
        $prixpresta=$_POST['autreprixprestaLab'];
        $id_categopresta=12;
        $id_souscategopresta=7;

        $mesure=NULL;
        $statupresta=0;

        $addLabautre = array();
        $addLabautreprix = array();

        foreach($_POST['autreprestaLab'] as $valeurLab)
        {
            $addLabautre[] = $valeurLab;

        }
        foreach($_POST['autreprixprestaLab'] as $valeurLabprix)
        {
            $addLabautreprix[] = $valeurLabprix;

        }

        for($i=0;$i<sizeof($addLabautre);$i++)
        {

            if($addLabautre[$i]!="")
            {
                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=12 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addLabautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addLabautre[$i]),
                        'namepresta'=>nl2br($addLabautre[$i]),
                        'prixpresta'=>$addLabautreprix[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

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

                    $resultat=$connexion->prepare('INSERT INTO med_labo (dateconsu,id_prestationExa,prixprestationExa,id_assuLab,insupercentLab,examenfait,numero,id_uM,id_consuLabo,id_factureMedLabo,codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:examenfait,:numero,:id_uM,:idconsuAdd,:id_factureMedLabo,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$prixLabo,
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'examenfait'=>nl2br($examenfait),
                        'numero'=>nl2br($numero),
                        'id_uM'=>nl2br($id_uM),
                        'idconsuAdd'=>nl2br($idconsuAdd),
                        'id_factureMedLabo'=>$idbill,
                        'codecashier'=>$cashierBy
                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}


if(isset($_POST['checkprestaRad']))
{
	$addRad = array();
	
	$prixautreRadio=0;
	$radiofait=0;
	$dateradio="0000-00-00";
	$idfacture=0;

	foreach($_POST['checkprestaRad'] as $valeurRad)
	{
		$addRad[] = $valeurRad; 
	}
	
	
	for($i=0;$i<sizeof($addRad);$i++)
	{

		$resRad=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=13 AND p.id_prestation="'.$addRad[$i].'" ORDER BY p.nompresta');
					
		$comptRad=$resRad->rowCount();
			
		if($ligneprestaRad=$resRad->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
		{
			$prixprestaRad=$ligneprestaRad->prixpresta;

			// echo $addRad[$i].' ('.$prixprestaRad.')<br/><br/>';
		}


		$resultatRadio=$connexion->prepare('INSERT INTO med_radio (dateconsu,id_prestationRadio,prixprestationRadio,prixautreRadio,id_assuRad,insupercentRad,radiofait,dateradio,numero,id_uM,id_consuRadio,id_factureMedRadio,codecashier) VALUES(:dateconsu,:idPrestaRad,:prixPresta,:prixautreRadio,:idassu,:bill,:radiofait,:dateradio,:numero,:id_uM,:idconsuAdd,:idfacture,:codecashier)');
		$resultatRadio->execute(array(
		'dateconsu'=>$dateconsu,
		'idPrestaRad'=>$addRad[$i],
		'prixPresta'=>$prixprestaRad,
		'prixautreRadio'=>$prixautreRadio,
		'idassu'=>$idassu,
		'bill'=>$bill,
		'radiofait'=>$radiofait,
		'dateradio'=>$dateradio,
		'numero'=>$numero,
		'id_uM'=>$id_uM,
		'idconsuAdd'=>$idconsuAdd,
		'idfacture'=>$idbill,
        'codecashier'=>$cashierBy
		)) or die( print_r($connexion->errorInfo()));

			/* echo 'Date : '.$dateconsu.'<br/>';
			echo 'Id presta : '.$addRad[$i].'<br/>';
			echo 'Prix presta : '.$prixprestaRad.'<br/>';
			echo '% : '.$bill.'<br/>';
			echo 'Radiofait : '.$radiofait.'<br/>';
			echo 'Numero Patient : '.$numero.'<br/>';
			echo 'Id Medecin : '.$id_uM.'<br/>';
			echo 'Id Consu : '.$idconsuAdd.'<br/><br/>'; */
	}
}


if($_POST['autreprestaRad']!="")
{

    $radiofait=0;
    $idfacture=0;
    $idAutreRadio=$_POST['autreprestaRad'];

    if($idAutreRadio != "")
    {
        $prixpresta=$_POST['autreprixprestaRad'];
        $id_categopresta=13;
        $id_souscategopresta=10;

        $mesure=NULL;
        $statupresta=0;

        $addRadautre = array();
        $addRadautreprix = array();

        foreach($_POST['autreprestaRad'] as $valeurRad)
        {
            $addRadautre[] = $valeurRad;

        }
        foreach($_POST['autreprixprestaRad'] as $valeurRadprix)
        {
            $addRadautreprix[] = $valeurRadprix;

        }

        for($i=0;$i<sizeof($addRadautre);$i++)
        {

            if($addRadautre[$i]!="")
            {
                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=13 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addRadautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addRadautre[$i]),
                        'namepresta'=>nl2br($addRadautre[$i]),
                        'prixpresta'=>$addRadautreprix[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

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


                    $resultat=$connexion->prepare('INSERT INTO med_radio (dateconsu,id_prestationRadio,prixprestationRadio,id_assuRad,insupercentRad,radiofait,numero,id_uM,id_consuRadio,id_factureMedRadio,codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:idassu,:bill,:radiofait,:numero,:id_uM,:idconsuAdd,:id_factureMedRadio,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$prixRadio,
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'radiofait'=>nl2br($radiofait),
                        'numero'=>nl2br($numero),
                        'id_uM'=>nl2br($id_uM),
                        'idconsuAdd'=>nl2br($idconsuAdd),
                        'id_factureMedRadio'=>$idbill,
                        'codecashier'=>$cashierBy
                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}


if(isset($_POST['checkprestaKine']))
{
    $addKine = array();
    $kinefait=0;

    foreach($_POST['checkprestaKine'] as $valeurKine)
    {
        $addKine[] = $valeurKine;
    }

    for($i=0;$i<sizeof($addKine);$i++)
    {

        $resKine=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=14 AND p.id_prestation="'.$addKine[$i].'" ORDER BY p.nompresta');

        $comptKine=$resKine->rowCount();

        if($ligneprestaKine=$resKine->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
        {
            $prixprestaKine=$ligneprestaKine->prixpresta;
            $prixprestaKineCCO=$ligneprestaKine->prixprestaCCO;

            // echo $addKine[$i].' ('.$ligneprestaKine->prixpresta.')<br/>';
        }

//        echo 'INSERT INTO med_kine (dateconsu,id_prestationKine,prixprestationKine,prixprestationKineCCO,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_consuKine) VALUES('.$dateconsu.','.$addKine[$i].','.$prixprestaKine.','.$prixprestaKineCCO.','.$kinefait.','.$idassu.','.$bill.','.$numero.','.$id_uM.','.$idconsuAdd.') <br/>';


        $resultatKine=$connexion->prepare('INSERT INTO med_kine (dateconsu,id_prestationKine,prixprestationKine,prixprestationKineCCO,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_consuKine,id_factureMedKine,codecashier) VALUES(:dateconsu,:idPrestaKine,:prixPresta,:prixPrestaCCO,:kinefait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedKine,:codecashier)');

        $resultatKine->execute(array(
            'dateconsu'=>$dateconsu,
            'idPrestaKine'=>$addKine[$i],
            'prixPresta'=>$prixprestaKine,
            'prixPrestaCCO'=>$prixprestaKineCCO,
            'kinefait'=>$kinefait,
            'idassu'=>$idassu,
            'bill'=>$bill,
            'numero'=>$numero,
            'id_uM'=>$id_uM,
            'idconsuAdd'=>$idconsuAdd,
            'id_factureMedKine'=>$idbill,
            'codecashier'=>$cashierBy

        )) or die( print_r($connexion->errorInfo()));


    }

}


if(isset($_POST['autreprestaKine']))
{
    $kinefait=0;
    $idAutrePresta=$_POST['autreprestaKine'];

    if($idAutrePresta != "")
    {
        $id_categopresta=14;
        $id_souscategopresta=0;

        $mesure=NULL;
        $statupresta=0;

        $addKineautre = array();
        $addKineautreprix = array();

        foreach($_POST['autreprestaKine'] as $valeurKine)
        {
            $addKineautre[] = $valeurKine;

        }
        foreach($_POST['autreprixprestaKine'] as $valeurKineprix)
        {
            $addKineautreprix[] = $valeurKineprix;

        }
        foreach($_POST['autreprixprestaKineCCO'] as $valeurKineprixCCO)
        {
            $addKineautreprixCCO[] = $valeurKineprixCCO;

        }

        for($i=0;$i<sizeof($addKineautre);$i++)
        {
            if($addKineautre[$i]!="")
            {
                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=14 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addKineautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addKineautre[$i]),
                        'namepresta'=>nl2br($addKineautre[$i]),
                        'prixpresta'=>$addKineautreprix[$i],
                        'prixprestaCCO'=>$addKineautreprixCCO[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

                    $searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');

                }else{
                    $ligneNomPresta=$searchNomPresta->fetch();

                    $searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');

                }

                /*-------Put in med_kine---------*/

                $searchLastId->setFetchMode(PDO::FETCH_OBJ);

                if($ligneLastId=$searchLastId->fetch())
                {

                    $lastIdPresta=$ligneLastId->id_prestation;

                    $prixprestakine=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
                    $prixprestakine->execute(array(
                        'idPresta'=>$lastIdPresta
                    ));

                    $prixprestakine->setFetchMode(PDO::FETCH_OBJ);

                    $comptPrixprestaconsu=$prixprestakine->rowCount();

                    if($lignePrixprestakine=$prixprestakine->fetch())
                    {
                        $prixKine=$lignePrixprestakine->prixpresta;
                        $prixKineCCO=$lignePrixprestakine->prixprestaCCO;
                    }

                    $resultat=$connexion->prepare('INSERT INTO med_kine (dateconsu,id_prestationKine,prixprestationKine,prixprestationKineCCO,kinefait,id_assuKine,insupercentKine,numero,id_uM,id_consuKine,id_factureMedKine,codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:kinefait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedKine,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$prixKine,
                        'prixPrestaCCO'=>$prixKineCCO,
                        'kinefait'=>$kinefait,
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'numero'=>$numero,
                        'id_uM'=>$id_uM,
                        'idconsuAdd'=>$idconsuAdd,
                        'id_factureMedKine'=>$idbill,
                        'codecashier'=>$cashierBy

                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}


if(isset($_POST['checkprestaOrtho']))
{
    $addOrtho = array();
    $orthofait=0;

    foreach($_POST['checkprestaOrtho'] as $valeurOrtho)
    {
        $addOrtho[] = $valeurOrtho;
    }

    for($i=0;$i<sizeof($addOrtho);$i++)
    {

        $resOrtho=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=23 AND p.id_prestation="'.$addOrtho[$i].'" ORDER BY p.nompresta');

        $comptOrtho=$resOrtho->rowCount();

        if($ligneprestaOrtho=$resOrtho->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
        {
            $prixprestaOrtho=$ligneprestaOrtho->prixpresta;
            $prixprestaOrthoCCO=$ligneprestaOrtho->prixprestaCCO;

            // echo $addOrtho[$i].' ('.$ligneprestaOrtho->prixpresta.')<br/>';
        }


        $resultatOrtho=$connexion->prepare('INSERT INTO med_ortho (dateconsu,id_prestationOrtho,prixprestationOrtho,prixprestationOrthoCCO,orthofait,id_assuOrtho,insupercentOrtho,numero,id_uM,id_consuOrtho,id_factureMedOrtho,codecashier) VALUES(:dateconsu,:idPrestaOrtho,:prixPresta,:prixPrestaCCO,:orthofait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedOrtho,:codecashier)');

        $resultatOrtho->execute(array(
            'dateconsu'=>$dateconsu,
            'idPrestaOrtho'=>$addOrtho[$i],
            'prixPresta'=>$prixprestaOrtho,
            'prixPrestaCCO'=>$prixprestaOrthoCCO,
            'orthofait'=>$orthofait,
            'idassu'=>$idassu,
            'bill'=>$bill,
            'numero'=>$numero,
            'id_uM'=>$id_uM,
            'idconsuAdd'=>$idconsuAdd,
            'id_factureMedOrtho'=>$idbill,
            'codecashier'=>$cashierBy

        )) or die( print_r($connexion->errorInfo()));



    }

}


if(isset($_POST['autreprestaOrtho']))
{
    $orthofait=0;
    $idAutrePresta=$_POST['autreprestaOrtho'];

    if($idAutrePresta != "")
    {
        $id_categopresta=14;
        $id_souscategopresta=0;

        $mesure=NULL;
        $statupresta=0;

        $addOrthoautre = array();
        $addOrthoautreprix = array();

        foreach($_POST['autreprestaOrtho'] as $valeurOrtho)
        {
            $addOrthoautre[] = $valeurOrtho;

        }
        foreach($_POST['autreprixprestaOrtho'] as $valeurOrthoprix)
        {
            $addOrthoautreprix[] = $valeurOrthoprix;

        }
        foreach($_POST['autreprixprestaOrthoCCO'] as $valeurOrthoprixCCO)
        {
            $addOrthoautreprixCCO[] = $valeurOrthoprixCCO;

        }

        for($i=0;$i<sizeof($addOrthoautre);$i++)
        {
            if($addOrthoautre[$i]!="")
            {
                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=14 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addOrthoautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addOrthoautre[$i]),
                        'namepresta'=>nl2br($addOrthoautre[$i]),
                        'prixpresta'=>$addOrthoautreprix[$i],
                        'prixprestaCCO'=>$addOrthoautreprixCCO[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

                    $searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');

                }else{
                    $ligneNomPresta=$searchNomPresta->fetch();

                    $searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');

                }

                /*-------Put in med_ortho---------*/

                $searchLastId->setFetchMode(PDO::FETCH_OBJ);

                if($ligneLastId=$searchLastId->fetch())
                {

                    $lastIdPresta=$ligneLastId->id_prestation;

                    $prixprestaortho=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
                    $prixprestaortho->execute(array(
                        'idPresta'=>$lastIdPresta
                    ));

                    $prixprestaortho->setFetchMode(PDO::FETCH_OBJ);

                    $comptPrixprestaconsu=$prixprestaortho->rowCount();

                    if($lignePrixprestaortho=$prixprestaortho->fetch())
                    {
                        $prixOrtho=$lignePrixprestaortho->prixpresta;
                        $prixOrthoCCO=$lignePrixprestaortho->prixprestaCCO;
                    }

                    $resultat=$connexion->prepare('INSERT INTO med_ortho (dateconsu,id_prestationOrtho,prixprestationOrtho,prixprestationOrthoCCO,orthofait,id_assuOrtho,insupercentOrtho,numero,id_uM,id_consuOrtho,id_factureMedOrtho,codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:orthofait,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedOrtho,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$prixOrtho,
                        'prixPrestaCCO'=>$prixOrthoCCO,
                        'orthofait'=>$orthofait,
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'numero'=>$numero,
                        'id_uM'=>$id_uM,
                        'idconsuAdd'=>$idconsuAdd,
                        'id_factureMedOrtho'=>$idbill,
                        'codecashier'=>$cashierBy

                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}


if(isset($_POST['checkprestaConsom']))
{
	$addConsom = array();

	$prixautreConsom=0;
	$prixautreConsomCCO=0;
    $qteConsom=1;
	$idfacture=0;

	foreach($_POST['checkprestaConsom'] as $valeurConsom)
	{
		$addConsom[] = $valeurConsom;
	}
		
	for($i=0;$i<sizeof($addConsom);$i++)
	{
		
		$resConsom=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation="'.$addConsom[$i].'" ORDER BY p.nompresta');
					
		$comptConsom=$resConsom->rowCount();
		
		if($comptConsom==0)
		{
			$resConsom=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=21 AND p.id_prestation="'.$addConsom[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaConsom=$resConsom->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaConsom=$ligneprestaConsom->prixpresta;
				$prixprestaConsomCCO=$ligneprestaConsom->prixprestaCCO;

				// echo $comptConsom.'_'.$addConsom[$i].' ('.$prixprestaConsom.')<br/>';
			}
			
				
			$resultatConsom=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,prixprestationConsomCCO,prixautreConsom,prixautreConsomCCO,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom,id_factureMedConsom,codecashier) VALUES(:dateconsu,:idPrestaConsom,:prixPresta,:prixPrestaCCO,:prixautreConsom,:prixautreConsomCCO,:qteConsom,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture,:codecashier)');
			$resultatConsom->execute(array(
			'dateconsu'=>$dateconsu,
			'idPrestaConsom'=>$addConsom[$i],
			'prixPresta'=>$prixprestaConsom,
			'prixPrestaCCO'=>$prixprestaConsomCCO,
			'prixautreConsom'=>$prixautreConsom,
			'prixautreConsomCCO'=>$prixautreConsomCCO,
			'qteConsom'=>$qteConsom,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idconsuAdd'=>$idconsuAdd,
			'idfacture'=>$idbill,
            'codecashier'=>$cashierBy
			)) or die( print_r($connexion->errorInfo()));

	}
	
}


if($_POST['autreprestaConsom']!="")
{
    if($_POST['autreprestaConsom']!="")
    {
        $id_categopresta=21;
        $id_souscategopresta=0;

        $mesure=NULL;
        $statupresta=0;

        $addConsomautre = array();
        $addConsomautreprix = array();
        $addConsomautreprixCCO = array();
        $qteConsom = array();

        foreach($_POST['autreprestaConsom'] as $valeurConsom)
        {
            $addConsomautre[] = $valeurConsom;

        }
        foreach($_POST['autreprixprestaConsom'] as $valeurConsomprix)
        {
            $addConsomautreprix[] = $valeurConsomprix;

        }
        foreach($_POST['autreprixprestaConsomCCO'] as $valeurConsomprixCCO)
        {
            $addConsomautreprixCCO[] = $valeurConsomprixCCO;

        }
        foreach($_POST['qteprestaConsom'] as $valeurConsomqte)
        {
            $qteConsom[] = $valeurConsomqte;

        }

        for($i=0;$i<sizeof($addConsomautre);$i++)
        {

            if($addConsomautre[$i]!="")
            {
                if($qteConsom[$i]==0)
                {
                    $qteConsom[$i]=1;
                }

                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=21 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addConsomautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addConsomautre[$i]),
                        'namepresta'=>nl2br($addConsomautre[$i]),
                        'prixpresta'=>$addConsomautreprix[$i],
                        'prixprestaCCO'=>$addConsomautreprixCCO[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

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

                    $comptPrixprestaradio=$prixprestaconsom->rowCount();

                    if($addConsomautreprix[$i]=="")
                    {
                        if($lignePrixprestaconsom=$prixprestaconsom->fetch())
                        {
                            $addConsomautreprix[$i]=$lignePrixprestaconsom->prixpresta;
                            $addConsomautreprixCCO[$i]=$lignePrixprestaconsom->prixprestaCCO;
                        }
                    }

// echo 'INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom) VALUES('.date('Y-m-d', strtotime($dateconsu)).','.$lastIdPresta.','.$addConsomautreprix[$i].','.$qteConsom[$i].','.$idassu.','.$bill.','.nl2br($numero).','.nl2br($id_uM).','.nl2br($idconsuAdd).')<br/>';

                    $resultat=$connexion->prepare('INSERT INTO med_consom (dateconsu,id_prestationConsom,prixprestationConsom,prixprestationConsomCCO,qteConsom,id_assuConsom,insupercentConsom,numero,id_uM,id_consuConsom,id_factureMedConsom,codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:qteConsom,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedConsom,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$addConsomautreprix[$i],
                        'prixPrestaCCO'=>$addConsomautreprixCCO[$i],
                        'qteConsom'=>$qteConsom[$i],
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'numero'=>nl2br($numero),
                        'id_uM'=>nl2br($id_uM),
                        'idconsuAdd'=>nl2br($idconsuAdd),
                        'id_factureMedConsom'=>$idbill,
                        'codecashier'=>$cashierBy
                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}
		

if(isset($_POST['checkprestaMedoc']))
{
	$addMedoc = array();

	$prixautreMedoc=0;
	$prixautreMedocCCO=0;
    $qteMedoc=1;
	$idfacture=0;

	foreach($_POST['checkprestaMedoc'] as $valeurMedoc)
	{
		$addMedoc[] = $valeurMedoc;
	}
		
	for($i=0;$i<sizeof($addMedoc);$i++)
	{
		
		$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
					
		$comptMedoc=$resMedoc->rowCount();
		
		if($comptMedoc==0)
		{
			$resMedoc=$connexion->query('SELECT *FROM categopresta_ins c, prestations_private p WHERE c.id_categopresta=p.id_categopresta AND p.id_categopresta=22 AND p.id_prestation="'.$addMedoc[$i].'" ORDER BY p.nompresta');
			
		}
			if($ligneprestaMedoc=$resMedoc->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
			{
				$prixprestaMedoc=$ligneprestaMedoc->prixpresta;
				$prixprestaMedocCCO=$ligneprestaMedoc->prixprestaCCO;

				// echo $comptMedoc.'_'.$addMedoc[$i].' ('.$prixprestaMedoc.')<br/>';
			}
			
				
			$resultatMedoc=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,prixprestationMedocCCO,prixautreMedoc,prixautreMedocCCO,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc,id_factureMedMedoc,codecashier) VALUES(:dateconsu,:idPrestaMedoc,:prixPresta,:prixPrestaCCO,:prixautreMedoc,:prixautreMedocCCO,:qteMedoc,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:idfacture,:codecashier)');
			$resultatMedoc->execute(array(
			'dateconsu'=>$dateconsu,
			'idPrestaMedoc'=>$addMedoc[$i],
			'prixPresta'=>$prixprestaMedoc,
			'prixPrestaCCO'=>$prixprestaMedocCCO,
			'prixautreMedoc'=>$prixautreMedoc,
			'prixautreMedocCCO'=>$prixautreMedocCCO,
			'qteMedoc'=>$qteMedoc,
			'idassu'=>$idassu,
			'bill'=>$bill,
			'numero'=>$numero,
			'id_uM'=>$id_uM,
			'idconsuAdd'=>$idconsuAdd,
			'idfacture'=>$idbill,
            'codecashier'=>$cashierBy
			)) or die( print_r($connexion->errorInfo()));
		
		
			/* 
			echo 'Date : '.$dateconsu.'<br/>';
			echo 'Id presta : '.$addMedoc[$i].'<br/>';
			echo 'Prix presta : '.$prixprestaMedoc.'<br/>';
			echo '% : '.$bill.'<br/>';
			echo 'Numero Patient : '.$numero.'<br/>';
			echo 'Id Medecin : '.$id_uM.'<br/>';
			echo 'Id Consu : '.$idconsuAdd.'<br/><br/>';
		 */
	}
	
}


if($_POST['autreprestaMedoc']!='')
{
    if($_POST['autreprestaMedoc']!="")
    {
        $id_categopresta=22;
        $id_souscategopresta=0;

        $mesure=NULL;
        $statupresta=0;

        $addMedocautre = array();
        $addMedocautreprix = array();
        $addMedocautreprixCCO = array();
        $qteMedoc = array();

        foreach($_POST['autreprestaMedoc'] as $valeurMedoc)
        {
            $addMedocautre[] = $valeurMedoc;

        }
        foreach($_POST['autreprixprestaMedoc'] as $valeurMedocprix)
        {
            $addMedocautreprix[] = $valeurMedocprix;

        }
        foreach($_POST['autreprixprestaMedocCCO'] as $valeurMedocprixCCO)
        {
            $addMedocautreprixCCO[] = $valeurMedocprixCCO;

        }
        foreach($_POST['qteprestaMedoc'] as $valeurMedocqte)
        {
            $qteMedoc[] = $valeurMedocqte;

        }

        for($i=0;$i<sizeof($addMedocautre);$i++)
        {

            if($addMedocautre[$i]!="")
            {
                if($qteMedoc[$i]==0)
                {
                    $qteMedoc[$i]=1;
                }

                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=22 AND (nompresta=:idAutrePresta OR namepresta=:idAutrePresta) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutrePresta'=>$addMedocautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addMedocautre[$i]),
                        'namepresta'=>nl2br($addMedocautre[$i]),
                        'prixpresta'=>$addMedocautreprix[$i],
                        'prixprestaCCO'=>$addMedocautreprixCCO[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

                    $searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' ORDER BY id_prestation DESC LIMIT 1');

                }else{
                    $ligneNomPresta=$searchNomPresta->fetch();

                    $searchLastId=$connexion->query('SELECT *FROM '.$presta_assu.' WHERE id_prestation='.$ligneNomPresta->id_prestation.'');

                }

                /*-------Put in med_medoc---------*/

                $searchLastId->setFetchMode(PDO::FETCH_OBJ);

                if($ligneLastId=$searchLastId->fetch())
                {

                    $lastIdPresta=$ligneLastId->id_prestation;

                    $prixprestamedoc=$connexion->prepare('SELECT *FROM '.$presta_assu.' p WHERE p.id_prestation=:idPresta ORDER BY p.id_prestation');
                    $prixprestamedoc->execute(array(
                    'idPresta'=>$lastIdPresta
                    ));

                    $prixprestamedoc->setFetchMode(PDO::FETCH_OBJ);

                    $comptPrixprestaradio=$prixprestamedoc->rowCount();

                    if($addMedocautreprix[$i]=="")
                    {
                        if($lignePrixprestamedoc=$prixprestamedoc->fetch())
                        {
                            $addMedocautreprix[$i]=$lignePrixprestamedoc->prixpresta;
                            $addMedocautreprixCCO[$i]=$lignePrixprestamedoc->prixprestaCCO;
                        }
                    }

                    $resultat=$connexion->prepare('INSERT INTO med_medoc (dateconsu,id_prestationMedoc,prixprestationMedoc,prixprestationMedocCCO,qteMedoc,id_assuMedoc,insupercentMedoc,numero,id_uM,id_consuMedoc,id_factureMedMedoc,codecashier) VALUES(:dateconsu,:idPresta,:prixPresta,:prixPrestaCCO,:qteMedoc,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedMedoc,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPresta'=>$lastIdPresta,
                        'prixPresta'=>$addMedocautreprix[$i],
                        'prixPrestaCCO'=>$addMedocautreprixCCO[$i],
                        'qteMedoc'=>$qteMedoc[$i],
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'numero'=>nl2br($numero),
                        'id_uM'=>nl2br($id_uM),
                        'idconsuAdd'=>nl2br($idconsuAdd),
                        'id_factureMedMedoc'=>$idbill,
                        'idconsuAdd'=>$cashierBy
                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}


if(isset($_POST['checkprestaServ']))
{
    $add = array();

    foreach($_POST['checkprestaServ'] as $valeur)
    {
        $add[] = $valeur;

    }

    for($i=0;$i<sizeof($add);$i++)
    {

        $resServ=$connexion->query('SELECT *FROM categopresta_ins c, '.$presta_assu.' p WHERE c.id_categopresta=p.id_categopresta AND p.id_prestation="'.$add[$i].'" ORDER BY p.nompresta');

        $comptServ=$resServ->rowCount();

        if($ligneprestaServ=$resServ->fetch(PDO::FETCH_OBJ))//on recupere la liste des éléments
        {
            $prixprestaServ=$ligneprestaServ->prixpresta;
            $prixprestaServCCO=$ligneprestaServ->prixprestaCCO;

            // echo $add[$i].' ('.$ligneprestaServ->prixpresta.')<br/>';
        }

        $resultatServ=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,prixprestationConsuCCO,id_assuServ,insupercentServ,numero,id_uM,id_consuMed,id_factureMedConsu,codecashier) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:prixPrestaConsuCCO,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedConsu,:codecashier)');
        $resultatServ->execute(array(
            'dateconsu'=>$dateconsu,
            'idPrestaConsu'=>$add[$i],
            'prixPrestaConsu'=>$prixprestaServ,
            'prixPrestaConsuCCO'=>$prixprestaServCCO,
            'idassu'=>$idassu,
            'bill'=>$bill,
            'numero'=>$numero,
            'id_uM'=>$id_uM,
            'idconsuAdd'=>$idconsuAdd,
            'id_factureMedConsu'=>$idbill,
            'codecashier'=>$cashierBy
        ));

    }
}


if(isset($_POST['autreprestaServ']))
{
    $idAutreConsult=$_POST['autreprestaServ'];

    if($idAutreConsult != "")
    {
        $prixpresta=$_POST['autreprixprestaServ'];
        $id_categopresta=20;
        $id_souscategopresta=0;

        $mesure=NULL;
        $statupresta=0;

        $addautre = array();
        $addautreprix = array();
        $addautreprixCCO = array();

        foreach($_POST['autreprestaServ'] as $valeur)
        {
            $addautre[] = $valeur;

        }
        foreach($_POST['autreprixprestaServ'] as $valeurprix)
        {
            $addautreprix[] = $valeurprix;

        }
        foreach($_POST['autreprixprestaServCCO'] as $valeurprixCCO)
        {
            $addautreprixCCO[] = $valeurprixCCO;

        }

        for($i=0;$i<sizeof($addautre);$i++)
        {
            if($addautre[$i]!="")
            {
                $searchNomPresta=$connexion->prepare('SELECT *FROM '.$presta_assu.' WHERE id_categopresta=20 AND (nompresta=:idAutreConsult OR namepresta=:idAutreConsult) ORDER BY id_prestation');
                $searchNomPresta->execute(array(
                    'idAutreConsult'=>$addautre[$i]
                ));

                $searchNomPresta->setFetchMode(PDO::FETCH_OBJ);

                $comptNomPresta=$searchNomPresta->rowCount();

                if($comptNomPresta==0)
                {
                    $insertNewPresta=$connexion->prepare('INSERT INTO '.$presta_assu.' (nompresta,namepresta,prixpresta,prixprestaCCO,id_categopresta,id_souscategopresta,mesure,statupresta) VALUES(:nompresta,:namepresta,:prixpresta,:prixprestaCCO,:id_categopresta,:id_souscategopresta,:mesure,:statupresta)');
                    $insertNewPresta->execute(array(
                        'nompresta'=>nl2br($addautre[$i]),
                        'namepresta'=>nl2br($addautre[$i]),
                        'prixpresta'=>$addautreprix[$i],
                        'prixprestaCCO'=>$addautreprixCCO[$i],
                        'id_categopresta'=>$id_categopresta,
                        'id_souscategopresta'=>$id_souscategopresta,
                        'mesure'=>$mesure,
                        'statupresta'=>$statupresta
                    )) or die( print_r($connexion->errorInfo()));

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
                        $prixConsuCCO=$lignePrixprestaconsu->prixprestaCCO;
                    }

                    $resultat=$connexion->prepare('INSERT INTO med_consult (dateconsu,id_prestationConsu,prixprestationConsu,prixprestationConsuCCO,id_assuServ,insupercentServ,numero,id_uM,id_consuMed,id_factureMedConsu,codecashier) VALUES(:dateconsu,:idPrestaConsu,:prixPrestaConsu,:prixPrestaConsuCCO,:idassu,:bill,:numero,:id_uM,:idconsuAdd,:id_factureMedConsu,:codecashier)');
                    $resultat->execute(array(
                        'dateconsu'=>date('Y-m-d', strtotime($dateconsu)),
                        'idPrestaConsu'=>$lastIdPresta,
                        'prixPrestaConsu'=>$prixConsu,
                        'prixPrestaConsuCCO'=>$prixConsuCCO,
                        'idassu'=>$idassu,
                        'bill'=>$bill,
                        'numero'=>nl2br($numero),
                        'id_uM'=>nl2br($id_uM),
                        'idconsuAdd'=>nl2br($idconsuAdd),
                        'id_factureMedConsu'=>$idbill,
                        'codecashier'=>$cashierBy
                    )) or die( print_r($connexion->errorInfo()));

                }
            }
        }
    }
}





echo '<script text="text/javascript">document.location.href="categoriesbill_modifier.php?manager='.$_GET['manager'].'&numbill='.$_GET['numbill'].'&num='.$_GET['num'].'&billpercent='.$_GET['billpercent'].'&idbill='.$_GET['idbill'].'&finishbtn=ok"</script>';

?>