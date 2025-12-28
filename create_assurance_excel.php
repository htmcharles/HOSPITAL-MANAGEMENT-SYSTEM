<?php
	session_start();
	include("connect.php");
	include("connectLangues.php");
	include("serialNumber.php");

	/** Include PHPExcel */
	require_once 'PHPExcel.php';

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	if(isset($_GET['assurance_selection_btn']))
	{
		$assurances_name=$_GET['chosen_assurances'];
		$assurances_name = strtolower($assurances_name);
		$presta_assu = 'prestations_'.$assurances_name;
		$doneby = $_SESSION['nom'].' '.$_SESSION['prenom'];

		if(isset($assurances_name))
		{
			//header('location:prices_edit_'.$assurances_name.'.php?assurances_name='.$assurances_name.'');

            $objPHPExcel->getProperties()->setCreator(''.$nameHospital.'')
                ->setLastModifiedBy(''.$doneby.'')
                ->setTitle('Report Excel for #'.$assurances_name.'')
                ->setSubject("Report information")
                ->setDescription('Report information for insurance : '.$assurances_name.'')
                ->setKeywords("Report Excel")
                ->setCategory("Report");

            for($col = ord('a'); $col <= ord('z'); $col++)
            {
                $objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Insurance')
                ->setCellValue('B1', ''.$assurances_name.'');

            $i = 1;

            for ($id=0; $id < 24; $id++) { 
            	if ($id!=5 AND $id!=6 AND $id!=8 AND $id!=9 AND $id!=10 AND $id!=11 AND $id!=15 AND $id!=16 AND $id!=17 AND $id!=18) {
            		$selectAssuranceActe = $connexion->query('SELECT * FROM '.$presta_assu.' WHERE id_categopresta='.$id.'');
	            
		            $selectAssuranceActe->setFetchMode(PDO::FETCH_OBJ);

		            $compselectAssuranceActe=$selectAssuranceActe->rowCount();

		            if($compselectAssuranceActe!=0)
					{

						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A7', 'N°')
									->setCellValue('B7', 'Categories')
									->setCellValue('C7', 'Denominations')
									->setCellValue('D7', 'Mesure')
									->setCellValue('E7', 'Prix prestation');
						
						while($ligneselectAssuranceActe=$selectAssuranceActe->fetch())
						{

							$arrayConsult[$i][0]=$i;
							if ($ligneselectAssuranceActe->id_categopresta == 1) {
								$arrayConsult[$i][1]="Consultation";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 2) {
								$arrayConsult[$i][1]="Hospitalisation";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 3) {
								$arrayConsult[$i][1]="Nursing care";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 4) {
								$arrayConsult[$i][1]="Chirurgie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 5) {
								$arrayConsult[$i][1]="Medecine Interne";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 6) {
								$arrayConsult[$i][1]="Pediatrie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 7) {
								$arrayConsult[$i][1]="Petite Chirurgie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 8) {
								$arrayConsult[$i][1]="Reduction Fracture";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 9) {
								$arrayConsult[$i][1]="Platre ordinaire";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 10) {
								$arrayConsult[$i][1]="Dermatologie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 11) {
								$arrayConsult[$i][1]="Gynecologie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 12) {
								$arrayConsult[$i][1]="Laboratoire";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 13) {
								$arrayConsult[$i][1]="Radiologie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 14) {
								$arrayConsult[$i][1]="Kinesitherapie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 15) {
								$arrayConsult[$i][1]="Ophtamologie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 16) {
								$arrayConsult[$i][1]="Stomatologie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 17) {
								$arrayConsult[$i][1]="Dentisterie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 18) {
								$arrayConsult[$i][1]="ORL";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 19) {
								$arrayConsult[$i][1]="Anestesie";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 20) {
								$arrayConsult[$i][1]="Autres Actes";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 21) {
								$arrayConsult[$i][1]="Consommables";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 22) {
								$arrayConsult[$i][1]="Medicaments";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 23) {
								$arrayConsult[$i][1]="P & O";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 24) {
								$arrayConsult[$i][1]="Menagere";
							}elseif ($ligneselectAssuranceActe->id_categopresta == 25) {
								$arrayConsult[$i][1]="Psychologue";
							}
							
							if ($ligneselectAssuranceActe->nompresta != "") {
								$arrayConsult[$i][2]=$ligneselectAssuranceActe->nompresta;
							}else{
								$arrayConsult[$i][2]=$ligneselectAssuranceActe->namepresta;
							}
							$arrayConsult[$i][3]=$ligneselectAssuranceActe->mesure;
							$arrayConsult[$i][4]=$ligneselectAssuranceActe->prixpresta;

							$i++;
						}
					}
            	}	            	
			}
			$objPHPExcel->setActiveSheetIndex(0)
						->fromArray($arrayConsult,'','A8');

			$callStartTime = microtime(true);

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			
			$reportsn= 'Tarrif_'.$assurances_name;

			$objWriter->save('C:/Users/ADMIN/Documents/Reports/Insurances/Excel/'.$reportsn.'.xlsx');
				
			$callEndTime = microtime(true);
			$callTime = $callEndTime - $callStartTime;
			
			echo '<script type="text/javascript"> alert("File name : '.$reportsn.'.xlsx \n Saved in \n C:/Users/ADMIN/Documents/Reports/Insurances/Excel/");</script>';

			echo '<script text="text/javascript">document.location.href="prices_edit.php?assurances_name='.$assurances_name.'"</script>';
		}
	}
?> 