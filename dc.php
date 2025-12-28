$resultMedLabo=$connexion->prepare('SELECT *FROM med_labo ml WHERE ml.examenfait=1 AND ml.id_uM=:med AND ml.id_consuLabo=:idMedLabo ORDER BY ml.id_medlabo');		
							$resultMedLabo->execute(array(
							'med'=>$idDoc,					
							'idMedLabo'=>$ligneConsult->id_consu
							));
							
							$resultMedLabo->setFetchMode(PDO::FETCH_OBJ);//on veut que le  résultat soit récupérable sous forme d'objet

							$comptMedLabo=$resultMedLabo->rowCount();


							if($comptMedLabo != 0)
							{
							?>	
								<table class="tablesorter tablesorter2" cellspacing="0" style="background:#fff;"> 
								
								<tbody>
							<?php
									while($ligneMedLabo=$resultMedLabo->fetch())
									{
							?>
									<tr style="text-align:center;">
										<td>
											<?php
											$resultPresta=$connexion->prepare('SELECT *FROM prestations p WHERE p.id_prestation=:prestaId');		
											$resultPresta->execute(array(
												'prestaId'=>$ligneMedLabo->id_prestationExa
											));
											
											$resultPresta->setFetchMode(PDO::FETCH_OBJ);//on veut que le résultat soit récupérable sous forme d'objet

											$comptPresta=$resultPresta->rowCount();
											
											if($lignePresta=$resultPresta->fetch())//on recupere la liste des éléments
											{
												if($lignePresta->namepresta!='')
												{
													echo $lignePresta->namepresta.'</td>';
												}else{
													echo $lignePresta->nompresta.'</td>';
												}
											}
											
												echo $ligneMedLabo->autreExamen;
											?>
										</td>
									</tr>
							<?php
									}
							?>		
								</tbody>
								</table>
							<?php
							}
							?>
							</td>