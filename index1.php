<?php
session_start();
		
$_SESSION['id']=0;
$_SESSION['connect']=false;
$_SESSION['status']=0;
		
include("connect.php");
include("connectLangues.php");
?>

<!doctype html>
<html lang="en">
<noscript>
Cette page requiert du Javascript.
Veuillez l'activer pour votre navigateur
</noscript>
<head>
	<meta charset="utf-8">
	<title><?php echo getString(44);?></title>
	
	<link rel="icon" href="images/favicon.ico">
	<link rel="shortcut icon" href="images/favicon.ico"/>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	
    <meta name="apple-mobile-web-app-capable" content="yes"> 
	
	<link href="AdministrationSOMADO/css/bootstrap.min.css" rel="stylesheet" type="text/css" /><!--Header-->
	<link href="AdministrationSOMADO/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" /><!--Header-->

	<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
		
	<link href="AdministrationSOMADO/css/style.css" rel="stylesheet" type="text/css"><!--Header-->
	
	<link href="css/login.css" rel="stylesheet" type="text/css"><!--Le "div"==>account-container càd tt le formulaire-->

	
	<script type="text/javascript">

function controlFormLogin(theForm){
	var rapport="";
	
	rapport +=controlS_number(theForm.s_number);
	rapport +=controlPassword(theForm.password); 
		if (rapport != "") {
		alert("Please correct the following errors:\n" + rapport);
					return false;
		 }
}


function controlS_number(fld){
	var erreur="";
	// var format=/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
	if(fld.value==""){
	erreur="Enter serial number\n";
	fld.style.background="rgba(0,255,0,0.3)";
	}
	
	/* if(!format.test(fld.value){
	erreur="SAISIE MAIL NON VALIDE";
	} */
	
	return erreur;
}
		
function controlPassword(fld){
	var erreur="";
	if(fld.value==""){
	erreur="Enter password\n";
	fld.style.background="rgba(0,255,0,0.3)";
	}
		return erreur;
} 
	</script>
	
</head>

<body>

	<div>
		<div class="account-container">
			
				
				<form method="post" action="index.php" onsubmit="return controlFormLogin(this)" style="margin-bottom:20px;">

					<!--
					<a href="../somado13" class="button btn btn-success btn-large">
						<i class="icon-chevron-left"></i>
						Aller à la page d'acceuil
					</a>
					-->	
					
					<img src="images/logo_large.png"/>	
					<h1 style="color:#333"><?php echo getString(44);?></h1>
					
					<div class="login-fields">
						
						<p><?php echo getString(45);?></p>
						
						<div class="field">
							<label for="e_mail"><?php echo getString(7);?></label>
							<input type="text" name="s_number" placeholder="<?php echo getString(81);?>" id="s_number" class="username-field" />
						</div> <!-- /field -->
						
						<div class="field">
							<label for="password"><?php echo getString(17);?></label> 
							<input type="password" name="password" placeholder="<?php echo getString(82);?>" id="password" class="password-field"/>
						</div> <!-- /password -->
						
					</div> <!-- /login-fields -->
						<p style="text-align:left"><?php echo getString(46);?><input type="checkbox" name="checkpass" id="checkpass" onclick="ShowHelp()"/></p>

					<div class="login-actions">
						<button type="submit" name="loginbtn" class="btn-large" style="margin:15px 0 0;">
							<?php echo getString(5);?>&nbsp;&nbsp;&nbsp;<i class="fa fa-sign-in fa-2x fa-fw" style=" vertical-align:middle;"></i>
						</button>
					</div> <!-- .actions -->
					
				</form>
				
		</div> <!-- /account-container -->

		<div style="position:fixed; bottom:0; width:100%;"> <!-- footer -->
		<?php
			include('footer.php');
		?>
		</div> <!-- /footer -->
	</div>

<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>

<script src="js/signin.js"></script>

<script type="text/javascript">

function ShowHelp(){
	
	if(document.getElementById('checkpass').checked==true)
	{
		document.getElementById('password').type='text';
	
	}else{
	
		if(document.getElementById('checkpass').checked==false)
		{
			document.getElementById('password').type='password';
		}
	
	}
	
	
}


</script>


<?php
try
{
	include("connect.php");

 	if(isset($_POST['loginbtn']))
	{
		// $e_mail=htmlspecialchars($_POST['mail']);
		$s_number=htmlspecialchars($_POST['s_number']);
		$password=htmlspecialchars($_POST['password']);
	
		if(!empty($s_number) AND !empty($password))
		{
			
			$sqlP=$connexion->query("SELECT *FROM patients p, utilisateurs u WHERE u.password='$password' AND p.numero='$s_number' AND p.id_u=u.id_u");
			$sqlD=$connexion->query("SELECT *FROM medecins m , utilisateurs u WHERE u.password='$password' AND m.codemedecin='$s_number' AND m.id_u=u.id_u");
			$sqlI=$connexion->query("SELECT *FROM infirmiers i, utilisateurs u WHERE u.password='$password' AND i.codeinfirmier='$s_number' AND i.id_u=u.id_u");
			$sqlL=$connexion->query("SELECT *FROM laborantins l, utilisateurs u WHERE u.password='$password' AND l.codelabo='$s_number' AND l.id_u=u.id_u");
			$sqlX=$connexion->query("SELECT *FROM radiologues x, utilisateurs u WHERE u.password='$password' AND x.coderadio='$s_number' AND x.id_u=u.id_u");
			$sqlR=$connexion->query("SELECT *FROM receptionistes r, utilisateurs u WHERE u.password='$password' AND r.codereceptio='$s_number' AND r.id_u=u.id_u");
			$sqlC=$connexion->query("SELECT *FROM cashiers c, utilisateurs u WHERE u.password='$password' AND c.codecashier='$s_number' AND c.id_u=u.id_u");
			$sqlA=$connexion->query("SELECT *FROM auditors a, utilisateurs u WHERE u.password='$password' AND a.codeaudit='$s_number' AND a.id_u=u.id_u");
			$sqlM=$connexion->query("SELECT *FROM coordinateurs c, utilisateurs u WHERE u.password='$password' AND c.codecoordi='$s_number' AND c.id_u=u.id_u");
			$sqlAcc=$connexion->query("SELECT *FROM accountants acc, utilisateurs u WHERE u.password='$password' AND acc.codeaccount='$s_number' AND acc.id_u=u.id_u");

			$comptidP=$sqlP->rowCount();
			$comptidD=$sqlD->rowCount();
			$comptidI=$sqlI->rowCount();
			$comptidL=$sqlL->rowCount();
			$comptidX=$sqlX->rowCount();
			$comptidR=$sqlR->rowCount();
			$comptidC=$sqlC->rowCount();
			$comptidA=$sqlA->rowCount();
			$comptidM=$sqlM->rowCount();
			$comptidAcc=$sqlAcc->rowCount();

			
					
			if($comptidP!=0)
			{
				$sqlP->setFetchMode(PDO::FETCH_OBJ);
				
				if($ligne=$sqlP->fetch())
				{
					$_SESSION['connect']=true;
					$_SESSION['id']=$ligne->id_u;
					$_SESSION['nom']=$ligne->nom_u;
					$_SESSION['prenom']=$ligne->prenom_u;
					$_SESSION['sexe']=$ligne->sexe;
					$_SESSION['province']=$ligne->province;
					$_SESSION['district']=$ligne->district;
					$_SESSION['secteur']=$ligne->secteur;
					$_SESSION['telephone']=$ligne->telephone;
					$_SESSION['e_mail']=$ligne->e_mail;
					$_SESSION['password']=$ligne->password;
					$_SESSION['status']=$ligne->status;
				
					$_SESSION['numero']=$ligne->numero;
					// echo $_SESSION['numero'];
					
					echo '<script text="text/javascript">document.location.href="acceuil.php?francais=francais"</script>';
				}
			
			}else{
			
				if($comptidD!=0)
				{
					$sqlD->setFetchMode(PDO::FETCH_OBJ);
					
					if($ligne=$sqlD->fetch())
					{
						$_SESSION['connect']=true;
						$_SESSION['id']=$ligne->id_u;
						$_SESSION['nom']=$ligne->nom_u;
						$_SESSION['prenom']=$ligne->prenom_u;
						$_SESSION['sexe']=$ligne->sexe;
						$_SESSION['province']=$ligne->province;
						$_SESSION['district']=$ligne->district;
						$_SESSION['secteur']=$ligne->secteur;
						$_SESSION['telephone']=$ligne->telephone;
						$_SESSION['e_mail']=$ligne->e_mail;
						$_SESSION['password']=$ligne->password;
						$_SESSION['idgrade']=$ligne->id_grade;
						$_SESSION['status']=$ligne->status;
					
						$_SESSION['codeM']=$ligne->codemedecin;
						// echo $_SESSION['codeM'];
						
						echo '<script text="text/javascript">document.location.href="patients1.php?francais=francais"</script>';
					}
				
				}else{
				
					if($comptidI!=0)
					{
						$sqlI->setFetchMode(PDO::FETCH_OBJ);
						
						if($ligne=$sqlI->fetch())
						{
							$_SESSION['connect']=true;
							$_SESSION['id']=$ligne->id_u;
							$_SESSION['nom']=$ligne->nom_u;
							$_SESSION['prenom']=$ligne->prenom_u;
							$_SESSION['sexe']=$ligne->sexe;
							$_SESSION['province']=$ligne->province;
							$_SESSION['district']=$ligne->district;
							$_SESSION['secteur']=$ligne->secteur;
							$_SESSION['telephone']=$ligne->telephone;
							$_SESSION['e_mail']=$ligne->e_mail;
							$_SESSION['password']=$ligne->password;
							$_SESSION['status']=$ligne->status;
												
							$_SESSION['codeI']=$ligne->codeinfirmier;
							$_SESSION['infhosp']=$ligne->inf_hosp;
							// echo $_SESSION['codeI'];
							
							echo '<script text="text/javascript">document.location.href="patients1.php?francais=francais"</script>';
						}
						
					}else{
					
						if($comptidL!=0)
						{
							$sqlL->setFetchMode(PDO::FETCH_OBJ);
							
							if($ligne=$sqlL->fetch())
							{
						
								$_SESSION['connect']=true;
								$_SESSION['id']=$ligne->id_u;
								$_SESSION['nom']=$ligne->nom_u;
								$_SESSION['prenom']=$ligne->prenom_u;
								$_SESSION['sexe']=$ligne->sexe;
								$_SESSION['province']=$ligne->province;
								$_SESSION['district']=$ligne->district;
								$_SESSION['secteur']=$ligne->secteur;
								$_SESSION['telephone']=$ligne->telephone;
								$_SESSION['e_mail']=$ligne->e_mail;
								$_SESSION['password']=$ligne->password;
								$_SESSION['status']=$ligne->status;
							
								$_SESSION['codeL']=$ligne->codelabo;
								
								echo '<script text="text/javascript">document.location.href="patients1.php?francais=francais"</script>';
							}
							
						}else{
					
							if($comptidX!=0)
							{
								$sqlX->setFetchMode(PDO::FETCH_OBJ);
								
								if($ligne=$sqlX->fetch())
								{
							
									$_SESSION['connect']=true;
									$_SESSION['id']=$ligne->id_u;
									$_SESSION['nom']=$ligne->nom_u;
									$_SESSION['prenom']=$ligne->prenom_u;
									$_SESSION['sexe']=$ligne->sexe;
									$_SESSION['province']=$ligne->province;
									$_SESSION['district']=$ligne->district;
									$_SESSION['secteur']=$ligne->secteur;
									$_SESSION['telephone']=$ligne->telephone;
									$_SESSION['e_mail']=$ligne->e_mail;
									$_SESSION['password']=$ligne->password;
									$_SESSION['status']=$ligne->status;
								
									$_SESSION['codeX']=$ligne->coderadio;
									
									echo '<script text="text/javascript">document.location.href="patients1.php?francais=francais"</script>';
								}
								
							}else{
							
								if($comptidR!=0)
								{
									$sqlR->setFetchMode(PDO::FETCH_OBJ);
									
									if($ligne=$sqlR->fetch())
									{
										$_SESSION['connect']=true;
										$_SESSION['id']=$ligne->id_u;
										$_SESSION['nom']=$ligne->nom_u;
										$_SESSION['prenom']=$ligne->prenom_u;
										$_SESSION['sexe']=$ligne->sexe;
										$_SESSION['province']=$ligne->province;
										$_SESSION['district']=$ligne->district;
										$_SESSION['secteur']=$ligne->secteur;
										$_SESSION['telephone']=$ligne->telephone;
										$_SESSION['e_mail']=$ligne->e_mail;
										$_SESSION['password']=$ligne->password;
										$_SESSION['status']=$ligne->status;
					
									$resultRecCash=$connexion->prepare("SELECT *FROM receptionistes r, cashiers c WHERE c.id_u=r.id_u AND (c.id_u=:iduser OR r.id_u=:iduser)");
									$resultRecCash->execute(array(
									'iduser'=>$ligne->id_u
									));

									$comptidRecCash=$resultRecCash->rowCount();

									if($comptidRecCash!=0)
									{
										$_SESSION['codeR']=$ligne->codereceptio;
										$_SESSION['codeCash']=$ligne->codereceptio;
									}else{
										$_SESSION['codeR']=$ligne->codereceptio;
									}
																
										echo '<script text="text/javascript">document.location.href="utilisateurs.php?receptioniste=ok&francais=francais"</script>';
									}
								
								}else{
								
									if($comptidC!=0)
									{
										$sqlC->setFetchMode(PDO::FETCH_OBJ);
										
										if($ligne=$sqlC->fetch())
										{
											$_SESSION['connect']=true;
											$_SESSION['id']=$ligne->id_u;
											$_SESSION['nom']=$ligne->nom_u;
											$_SESSION['prenom']=$ligne->prenom_u;
											$_SESSION['sexe']=$ligne->sexe;
											$_SESSION['province']=$ligne->province;
											$_SESSION['district']=$ligne->district;
											$_SESSION['secteur']=$ligne->secteur;
											$_SESSION['telephone']=$ligne->telephone;
											$_SESSION['e_mail']=$ligne->e_mail;
											$_SESSION['password']=$ligne->password;
											$_SESSION['status']=$ligne->status;
											$resultRecCash=$connexion->prepare("SELECT *FROM receptionistes r, cashiers c WHERE c.id_u=r.id_u AND (c.id_u=:iduser OR r.id_u=:iduser)");
											$resultRecCash->execute(array(
											'iduser'=>$ligne->id_u
											));

											$comptidRecCash=$resultRecCash->rowCount();

											if($comptidRecCash!=0)
											{
												$_SESSION['codeR']=$ligne->codecashier;
												$_SESSION['codeCash']=$ligne->codecashier;
											}else{
												$_SESSION['codeCash']=$ligne->codecashier;
											}
															
											echo '<script text="text/javascript">document.location.href="patients1.php?caissier=ok&francais=francais"</script>';
										}
								
									}else{
								
										if($comptidA!=0)
										{
											$sqlA->setFetchMode(PDO::FETCH_OBJ);
											
											if($ligne=$sqlA->fetch())
											{
												$_SESSION['connect']=true;
												$_SESSION['id']=$ligne->id_u;
												$_SESSION['nom']=$ligne->nom_u;
												$_SESSION['prenom']=$ligne->prenom_u;
												$_SESSION['sexe']=$ligne->sexe;
												$_SESSION['province']=$ligne->province;
												$_SESSION['district']=$ligne->district;
												$_SESSION['secteur']=$ligne->secteur;
												$_SESSION['telephone']=$ligne->telephone;
												$_SESSION['e_mail']=$ligne->e_mail;
												$_SESSION['password']=$ligne->password;
												$_SESSION['status']=$ligne->status;
											
												$_SESSION['codeA']=$ligne->codeaudit;
																		
												echo '<script text="text/javascript">document.location.href="report.php?francais=francais"</script>';
											}
									
										}else{
										
											if($comptidM!=0)
											{
												$sqlM->setFetchMode(PDO::FETCH_OBJ);
												
												if($ligne=$sqlM->fetch())
												{
													$_SESSION['connect']=true;
													$_SESSION['id']=$ligne->id_u;
													$_SESSION['nom']=$ligne->nom_u;
													$_SESSION['prenom']=$ligne->prenom_u;
													$_SESSION['sexe']=$ligne->sexe;
													$_SESSION['province']=$ligne->province;
													$_SESSION['district']=$ligne->district;
													$_SESSION['secteur']=$ligne->secteur;
													$_SESSION['telephone']=$ligne->telephone;
													$_SESSION['e_mail']=$ligne->e_mail;
													$_SESSION['password']=$ligne->password;
													$_SESSION['status']=$ligne->status;
												
													$_SESSION['codeC']=$ligne->codecoordi;
													// echo $_SESSION['codeC'];
													
													echo '<script text="text/javascript">document.location.href="utilisateurs.php?francais=francais"</script>';
												}
												
											}else{
										
												if($comptidAcc!=0)
												{
													$sqlAcc->setFetchMode(PDO::FETCH_OBJ);
													
													if($ligne=$sqlAcc->fetch())
													{
														$_SESSION['connect']=true;
														$_SESSION['id']=$ligne->id_u;
														$_SESSION['nom']=$ligne->nom_u;
														$_SESSION['prenom']=$ligne->prenom_u;
														$_SESSION['sexe']=$ligne->sexe;
														$_SESSION['province']=$ligne->province;
														$_SESSION['district']=$ligne->district;
														$_SESSION['secteur']=$ligne->secteur;
														$_SESSION['telephone']=$ligne->telephone;
														$_SESSION['e_mail']=$ligne->e_mail;
														$_SESSION['password']=$ligne->password;
														$_SESSION['status']=$ligne->status;
													
														$_SESSION['codeAcc']=$ligne->codeaccount;
														// echo $_SESSION['codeC'];
														
														echo '<script text="text/javascript">document.location.href="billsaccount.php?francais=francais"</script>';
													}
													
												}else{
												
													$_SESSION['connect']=false;
													echo '<script text="text/javascript">alert("Invalid input");</script>';
													echo '<script text="text/javascript">document.location.href="index.php"</script>';
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
				
		}else{
			echo '<script text="text/javascript">alert("You did not enter anything");</script>';
			echo '<script text="text/javascript">document.location.href="index.php"</script>';
		}
	} 

}

catch(Excepton $e)
{
echo 'Erreur:'.$e->getMessage().'<br/>';
echo'Numero:'.$e->getCode();
}

?>
</body>

</html>
