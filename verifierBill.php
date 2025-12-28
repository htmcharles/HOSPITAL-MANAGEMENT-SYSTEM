<?php

	include("connect.php");
	include("connectLangues.php");
	include("serialNumber.php");

	$selectBills = $connexion->query('SELECT * FROM bills');
	$selectBills->setFetchMode(PDO::FETCH_OBJ);

	while ($lignebills = $selectBills->fetch()) {
		$idBill = $lignebills->id_bill;
		$numero = $lignebills->numero;

		$checkconsu = $connexion->prepare('SELECT * FROM consultations c, bills b WHERE c.id_factureConsult=b.id_bill AND c.id_factureConsult=:id_factureConsult AND c.numero!=:numero');
		$checkconsu->execute(array(
			'id_factureConsult'=>$idBill,
			'numero'=>$numero
		));
		$checkconsu->setFetchMode(PDO::FETCH_OBJ);
		$nbreconsu = $checkconsu->rowCount();


		$checkconsom = $connexion->prepare('SELECT * FROM med_consom mc, bills b WHERE mc.id_factureMedConsom=b.id_bill AND mc.id_factureMedConsom=:id_factureMedConsom AND mc.numero!=:numero');
		$checkconsom->execute(array(
			'id_factureMedConsom'=>$idBill,
			'numero'=>$numero
		));
		$checkconsom->setFetchMode(PDO::FETCH_OBJ);
		$nbreconsom = $checkconsom->rowCount();

		
		$checkconsult = $connexion->prepare('SELECT * FROM med_consult mc, bills b WHERE mc.id_factureMedConsu=b.id_bill AND mc.id_factureMedConsu=:id_factureMedConsu AND mc.numero!=:numero');
		$checkconsult->execute(array(
			'id_factureMedConsu'=>$idBill,
			'numero'=>$numero
		));
		$checkconsult->setFetchMode(PDO::FETCH_OBJ);
		$nbreconsult = $checkconsult->rowCount();

		
		$checkmdeinf = $connexion->prepare('SELECT * FROM med_inf mi, bills b WHERE mi.id_factureMedInf=b.id_bill AND mi.id_factureMedInf=:id_factureMedInf AND mi.numero!=:numero');
		$checkmdeinf->execute(array(
			'id_factureMedInf'=>$idBill,
			'numero'=>$numero
		));
		$checkmdeinf->setFetchMode(PDO::FETCH_OBJ);
		$nbremedinf = $checkmdeinf->rowCount();

		
		$checkmedkine = $connexion->prepare('SELECT * FROM med_kine mk, bills b WHERE mk.id_factureMedKine=b.id_bill AND mk.id_factureMedKine=:id_factureMedKine AND mk.numero!=:numero');
		$checkmedkine->execute(array(
			'id_factureMedKine'=>$idBill,
			'numero'=>$numero
		));
		$checkmedkine->setFetchMode(PDO::FETCH_OBJ);
		$nbremedkine = $checkmedkine->rowCount();


		$checkmedlabo = $connexion->prepare('SELECT * FROM med_labo ml, bills b WHERE ml.id_factureMedLabo=b.id_bill AND ml.id_factureMedLabo=:id_factureMedLabo AND ml.numero!=:numero');
		$checkmedlabo->execute(array(
			'id_factureMedLabo'=>$idBill,
			'numero'=>$numero
		));
		$checkmedlabo->setFetchMode(PDO::FETCH_OBJ);
		$nbremedlabo = $checkmedlabo->rowCount();


		$checkmedoc = $connexion->prepare('SELECT * FROM med_medoc mc, bills b WHERE mc.id_factureMedMedoc=b.id_bill AND mc.id_factureMedMedoc=:id_factureMedMedoc AND mc.numero!=:numero');
		$checkmedoc->execute(array(
			'id_factureMedMedoc'=>$idBill,
			'numero'=>$numero
		));
		$checkmedoc->setFetchMode(PDO::FETCH_OBJ);
		$nbremedoc = $checkmedoc->rowCount();

		
		$checkmedortho = $connexion->prepare('SELECT * FROM med_ortho mc, bills b WHERE mc.id_factureMedOrtho=b.id_bill AND mc.id_factureMedOrtho=:id_factureMedOrtho AND mc.numero!=:numero');
		$checkmedortho->execute(array(
			'id_factureMedOrtho'=>$idBill,
			'numero'=>$numero
		));
		$checkmedortho->setFetchMode(PDO::FETCH_OBJ);
		$nbremedortho = $checkmedortho->rowCount();

		
		$checkmdepsy = $connexion->prepare('SELECT * FROM med_psy mi, bills b WHERE mi.id_factureMedPsy=b.id_bill AND mi.id_factureMedPsy=:id_factureMedPsy AND mi.numero!=:numero');
		$checkmdepsy->execute(array(
			'id_factureMedPsy'=>$idBill,
			'numero'=>$numero
		));
		$checkmdepsy->setFetchMode(PDO::FETCH_OBJ);
		$nbremedpsy = $checkmdepsy->rowCount();

		
		$checkmedradio = $connexion->prepare('SELECT * FROM med_radio mk, bills b WHERE mk.id_factureMedRadio=b.id_bill AND mk.id_factureMedRadio=:id_factureMedRadio AND mk.numero!=:numero');
		$checkmedradio->execute(array(
			'id_factureMedRadio'=>$idBill,
			'numero'=>$numero
		));
		$checkmedradio->setFetchMode(PDO::FETCH_OBJ);
		$nbremedradio = $checkmedradio->rowCount();


		$checkmedsurge = $connexion->prepare('SELECT * FROM med_surge mk, bills b WHERE mk.id_factureMedSurge=b.id_bill AND mk.id_factureMedSurge=:id_factureMedSurge AND mk.numero!=:numero');
		$checkmedsurge->execute(array(
			'id_factureMedSurge'=>$idBill,
			'numero'=>$numero
		));
		$checkmedsurge->setFetchMode(PDO::FETCH_OBJ);
		$nbremedsurge = $checkmedsurge->rowCount();

		
		if ($nbreconsu!=0 OR $nbreconsom!=0 OR $nbreconsult!=0 OR $nbremedinf!=0 OR $nbremedkine!=0 OR $nbremedlabo!=0 OR $nbremedoc!=0 OR $nbremedortho!=0 OR $nbremedpsy!=0 OR $nbremedradio!=0 OR $nbremedsurge!=0) {
			echo "id_bill = ".$idBill;
		}
	}
