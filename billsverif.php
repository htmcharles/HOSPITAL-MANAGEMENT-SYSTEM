<?php

include("connect.php");
	include("connectLangues.php");
	include("serialNumber.php");

	$selectBills = $connexion->query('SELECT * FROM bills WHERE id_bill=6636');
	$selectBills->setFetchMode(PDO::FETCH_OBJ);

	while ($lignebills = $selectBills->fetch()) {
		$idBill = $lignebills->id_bill;
		$numero = $lignebills->numero;

		$checkconsu = $connexion->prepare('SELECT * FROM consultions c, bills b WHERE c.id_factureConsult=b.id_bill AND c.id_factureConsult=:id_factureConsult');
		$checkconsu->execute(array(
			'id_factureConsult'=>$idBill
		));
		$checkconsu->setFetchMode(PDO::FETCH_OBJ);
		$nbreconsu = $checkconsu->rowCount();


		$checkconsom = $connexion->prepare('SELECT * FROM med_consom mc, bills b WHERE mc.id_factureMedConsom=b.id_bill AND mc.id_factureMedConsom=:id_factureMedConsom');
		$checkconsom->execute(array(
			'id_factureMedConsom'=>$idBill
		));
		$checkconsom->setFetchMode(PDO::FETCH_OBJ);
		$nbreconsom = $checkconsom->rowCount();

		
		$checkconsult = $connexion->prepare('SELECT * FROM med_consult mc, bills b WHERE mc.id_factureMedConsu=b.id_bill AND mc.id_factureMedConsu=:id_factureMedConsu');
		$checkconsult->execute(array(
			'id_factureMedConsu'=>$idBill
		));
		$checkconsult->setFetchMode(PDO::FETCH_OBJ);
		$nbreconsult = $checkconsult->rowCount();

		
		$checkmdeinf = $connexion->prepare('SELECT * FROM med_inf mi, bills b WHERE mi.id_factureMedInf=b.id_bill AND mi.id_factureMedInf=:id_factureMedInf');
		$checkmdeinf->execute(array(
			'id_factureMedInf'=>$idBill
		));
		$checkmdeinf->setFetchMode(PDO::FETCH_OBJ);
		$nbremedinf = $checkmdeinf->rowCount();

		
		$checkmedkine = $connexion->prepare('SELECT * FROM med_kine mk, bills b WHERE mk.id_factureMedKine=b.id_bill AND mk.id_factureMedKine=:id_factureMedKine');
		$checkmedkine->execute(array(
			'id_factureMedKine'=>$idBill
		));
		$checkmedkine->setFetchMode(PDO::FETCH_OBJ);
		$nbremedkine = $checkmedkine->rowCount();


		$checkmedlabo = $connexion->prepare('SELECT * FROM med_labo ml, bills b WHERE ml.id_factureMedLabo=b.id_bill AND ml.id_factureMedLabo=:id_factureMedLabo');
		$checkmedlabo->execute(array(
			'id_factureMedLabo'=>$idBill
		));
		$checkmedlabo->setFetchMode(PDO::FETCH_OBJ);
		$nbremedlabo = $checkmedlabo->rowCount();


		$checkmedoc = $connexion->prepare('SELECT * FROM med_medoc mc, bills b WHERE mc.id_factureMedMedoc=b.id_bill AND mc.id_factureMedMedoc=:id_factureMedMedoc');
		$checkmedoc->execute(array(
			'id_factureMedMedoc'=>$idBill
		));
		$checkmedoc->setFetchMode(PDO::FETCH_OBJ);
		$nbremedoc = $checkmedoc->rowCount();

		
		$checkmedortho = $connexion->prepare('SELECT * FROM med_ortho mc, bills b WHERE mc.id_factureMedOrtho=b.id_bill AND mc.id_factureMedOrtho=:id_factureMedOrtho');
		$checkmedortho->execute(array(
			'id_factureMedOrtho'=>$idBill
		));
		$checkmedortho->setFetchMode(PDO::FETCH_OBJ);
		$nbremedortho = $checkmedortho->rowCount();

		
		$checkmdepsy = $connexion->prepare('SELECT * FROM med_psy mi, bills b WHERE mi.id_factureMedPsy=b.id_bill AND mi.id_factureMedPsy=:id_factureMedPsy');
		$checkmdepsy->execute(array(
			'id_factureMedPsy'=>$idBill
		));
		$checkmdepsy->setFetchMode(PDO::FETCH_OBJ);
		$nbremedpsy = $checkmdepsy->rowCount();

		
		$checkmedradio = $connexion->prepare('SELECT * FROM med_radio mk, bills b WHERE mk.id_factureMedRadio=b.id_bill AND mk.id_factureMedRadio=:id_factureMedRadio');
		$checkmedradio->execute(array(
			'id_factureMedRadio'=>$idBill
		));
		$checkmedradio->setFetchMode(PDO::FETCH_OBJ);
		$nbremedradio = $checkmedradio->rowCount();


		$checkmedsurge = $connexion->prepare('SELECT * FROM med_surge mk, bills b WHERE mk.id_factureMedSurge=b.id_bill AND mk.id_factureMedSurge=:id_factureMedSurge');
		$checkmedsurge->execute(array(
			'id_factureMedSurge'=>$idBill
		));
		$checkmedsurge->setFetchMode(PDO::FETCH_OBJ);
		$nbremedsurge = $checkmedsurge->rowCount();

		if ($nbreconsu!=0 OR $nbreconsom!=0 OR $nbreconsult!=0 OR $nbremedinf!=0 OR $nbremedkine!=0 OR $nbremedlabo!=0 OR $nbremedoc!=0 OR $nbremedortho!=0 OR $nbremedpsy!=0 OR $nbremedradio!=0 OR $nbremedsurge!=0) {
			if ($nbreconsu != 0) {
				echo "consu ";
			}
			if ($nbreconsom != 0) {
				echo "consom ";
			}
			if ($nbreconsult != 0) {
				echo "consult ";
			}
			if ($nbremedkine != 0) {
				echo "kine ";
			}
			if ($nbremedinf != 0) {
				echo "inf ";
			}
			if ($nbremedlabo != 0) {
				echo "labo ";
			}
			if ($nbremedoc != 0) {
				echo "medoc ";
			}
			if ($nbremedortho != 0) {
				echo "ortho ";
			}
			if ($nbremedpsy != 0) {
				echo "psy ";
			}
			if ($nbremedradio != 0) {
				echo "radio ";
			}
			if ($nbremedsurge != 0) {
				echo "surge ";
			}
		}
	}