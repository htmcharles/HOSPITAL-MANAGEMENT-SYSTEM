<?php
session_start();

include("connectLangues.php");
include("connect.php");


	$now = date('Y').'-'.date('m').'-'.date('d');
	$capitauxnamemsg="";
	$Amountmsg="";
	$msg="";
	$msg_exp = "";
	if(isset($_POST['save_capitaux_btn'])){
		$namedonor=$_POST['namedonor'];
		$Amount=$_POST['Amount'];
		if(isset($_SESSION['codeC'])){
			$user=$_SESSION['codeC'];
		}else{
			if(isset($_SESSION['codeCash'])){
			$user=$_SESSION['codeCash'];
			}
		}
		
		if (empty($namedonor)) {
			$capitauxnamemsg="Please Enter Expense Name";
		}
		if (empty($Amount)) {
			$Amountmsg="Please Enter Amount";
		}
		if(!empty($namedonor) && !empty($Amount)){
			$savecapitaux=$connexion->prepare("INSERT INTO capitaux(amount,donor,datedone,doneby) VALUES (:Amount,:namedonor,:datedone,:doneby)");
			$savecapitaux->execute([
				"Amount"=>$Amount,
				"namedonor"=>$namedonor,
				"datedone"=>$now,
				"doneby"=>$user
			]);
			$savecapitaux->setFetchMode(PDO::FETCH_OBJ);
			$rowcapitaux = $savecapitaux->rowCount();

			if($rowcapitaux != 0){
				$msg="Capitaux Saved";
				$namedonor="";
				$Amount="";
			}else{
				$msg="Capitaux Not Saved";
			}
		}
	}

	/*-----------------------Capitaux Expenses-----------------*/
	if(isset($_POST['save_expenses_btn'])){
		$expensesname=$_POST['expensesname'];
		$Motif=$_POST['Motif'];
		$Amount=$_POST['Amount'];
		if(isset($_SESSION['codeC'])){
			$user=$_SESSION['codeC'];
		}else{
			if(isset($_SESSION['codeCash'])){
			$user=$_SESSION['codeCash'];
			}
		}
		
		if (empty($expensesname)) {
			$expensesnamemsg="Please Enter Expense Name";
		}
		if (empty($Amount)) {
			$Amountmsg="Please Enter Amount";
		}
		if(!empty($expensesname) && !empty($Amount)){

			$saveexpenses=$connexion->prepare("INSERT INTO capitaux_expense(expensename, Motif,amount,datebill,doneby) VALUES (:expensesname,:Motif,:Amount,:datebill,:doneby)");
			$saveexpenses->execute(["expensesname"=>$expensesname,"Motif"=>$Motif,"Amount"=>$Amount,"datebill"=>$now,"doneby"=>$user]);

			if($saveexpenses=True){
				
				/*--------------Select capitaux done-----------*/
				$selectcapitauxdone = $connexion->query('SELECT * FROM capitaux WHERE status=1 ORDER BY datedone ASC');
				$selectcapitauxdone->setFetchMode(PDO::FETCH_OBJ);
				$row = $selectcapitauxdone->rowCount();
				$capidiff =0;
				if ($row != 0) {
					while ($lignecapi = $selectcapitauxdone->fetch()) {
						echo $realcapi = $lignecapi->reste_amount.'<br>';
						echo $id_capit = $lignecapi->id_capitaux.'<br>';
						echo "<br>amount = $Amount<br>";

						if ($capidiff == 0) {
							//echo "string";
							// echo $realcapi.'<br>';
							// echo "<br>amount = $Amount<br>";
							if ($realcapi == $Amount) {
								echo "string";
								$newReste = 0;
								$newStatus = 0;
								$updatecapi = $connexion->prepare('UPDATE capitaux SET reste_amount=:reste, status=:status WHERE id_capitaux=:id_capit');
								$updatecapi->execute(array(
									'reste'=>$newReste,
									'status'=>$newStatus,
									'id_capit'=>$id_capit
								));

								//Select id expense 
								$selectidexpe = $connexion->query('SELECT expeid FROM capitaux_expense ORDER BY expeid DESC LIMIT 1');
								$selectidexpe->setFetchMode(PDO::FETCH_OBJ);
								if ($ligneselectidexp = $selectidexpe->fetch()) {
									$idexpemse = $ligneselectidexp->expeid;
								}
								echo "last id = ".$idexpemse;
								//insert in capitaux histori
								if (isset($idexpemse) && isset($id_capit)) {
									echo "string";
									$insert_histo = $connexion->prepare('INSERT INTO capitaux_histori_expense (id_capitaux, id_expense, amount) VALUES (:id_capitaux, :id_expense, :amount)');
									$insert_histo->execute(array(
										'id_capitaux'=>$id_capit,
										'id_expense'=>$idexpemse,
										'amount'=>$Amount
									));
								}
								continue;
							}
						}else{
							echo "string";
						}

					}
				}
				$msg_exp="Expenses Saved";
				$expensesname="";
				$Motif="";
				$Amount="";

				/*while ($lignecapi = $selectcapitauxdone->fetch()) {
					$realcapi = $lignecapi->reste_amount;
					$id_capit = $lignecapi->id_capitaux;


					switch (true) {
						case ($realcapi <= $Amount):
							echo 'hello';
							break;
						
						default:
							echo "string";
							break;
					}

					//update current capitaux;
					//$updateCurrentCapi = $connexion
				}*/
				


			}else{
				$msg="Expenses Not Saved";
			}
		}
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
	<script src="myQuery.js"></script></head>
	<body>

<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<img src="images/logo_large.png" class="brand" />	
			
			<div class="nav-collapse">
			
				<ul class="nav pull-right">
				<li class="">			
					<form method="post" action="patients1.php?<?php if(isset($_GET['num'])){ echo 'num='.$_GET['num'];}?><?php if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}?><?php if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" onsubmit="return controlFormPassword(this)">
					
					<span style="color:#333;padding-top:4px;vertical-align:middle;"><?php echo getString(24);?><?php echo ' '.$_SESSION['nom'].' '.$_SESSION['prenom'];?></span>
					
					<a href="deconnect.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?>" class="btn"><?php echo getString(25);?></a>
					
					<?php
					if($langue == 'francais')
					{
					?>
						<a href="capitaux.php?english=english<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(30);?></a>
					<?php
					}else{
					?>
						<a href="capitaux.php?francais=francais<?php if(isset($_GET['num'])){ echo '&num='.$_GET['num'];}if(isset($_GET['receptioniste'])){ echo '&receptioniste=ok';}if(isset($_GET['soinsPa'])){ echo '&soinsPa='.$_GET['soinsPa'];}if(isset($_GET['examenPa'])){ echo '&examenPa='.$_GET['examenPa'];}if(isset($_GET['divPa'])){ echo '&divPa='.$_GET['divPa'];}if(isset($_GET['idmedLabo'])){ echo '&idmedLabo='.$_GET['idmedLabo'];}if(isset($_GET['id_consu'])){ echo '&id_consu='.$_GET['id_consu'];}if(isset($_GET['dateconsu'])){ echo '&dateconsu='.$_GET['dateconsu'];}if(isset($_GET['showresult'])){ echo '&showresult='.$_GET['showresult'];}?>" class="btn"><?php echo getString(29);?></a>
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
if(isset($_SESSION['codeR']) AND isset($_SESSION['codeCash']))
{
?>
	<div style="text-align:center;margin-top:20px;">
		
	<?php
	if(isset($_GET['receptioniste']))
	{
	?>
		<a href="patients1.php?caissier=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Caisse';?>
		</a>

	<?php
	}else{
	?>	

		<a href="patients1.php?receptioniste=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reception';?>
		</a>
	
		<a href="patients1_hosp.php?cashHosp=ok<?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="savebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Hospitalisation';?>
		</a>
	
		<a href="listfacture.php?codeCash=<?php echo $_SESSION['codeCash'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;">
			<?php echo 'Factures';?>
		</a>
	
	<?php
	}
	?>		
		<a href="report.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="font-size:20px;height:40px; padding:10px 40px;margin-left:10px;"><?php echo getString(94);?></a>
		<a href="capitaux.php?cash=<?php echo $_SESSION['codeCash'];?>&coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&capitaux=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<?php echo 'capitaux';?>
		</a>
	</div>
<?php
}
?>
<?php
	if(isset($_SESSION['codeC'])){?>


		<div style="text-align:center;margin-top:20px;">
		
		<a href="report.php?coordi=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="reportsbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Reports';?>
		</a>
		
		<a href="facturesedit.php?codeCoord=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Factures';?>
		</a>

		<a href="assurances.php?<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="assurancebtn" style="font-size:20px;height:40px; padding:10px 40px;margin-right:10px;">
			<?php echo 'Assurances';?>
		</a>

		<a href="expenses.php?&codecashier=<?php if(isset($_SESSION['codeCash'])){echo $_SESSION['codeCash'];} ?>&codeCoord=<?php if(isset($_SESSION['codeC'])){echo  $_SESSION['codeC'];}?>&capitaux=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" name="billListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<?php echo 'Expenses';?>
		</a>

		<a href="dettesList.php?codeCash=<?php echo $_SESSION['codeC'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?><?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="dettesListbtn" style="font-size:20px;height:40px; padding:10px 40px;margin-left: 10px;">
			<?php echo 'Dettes';?>
		</a>
	</div>
		<?php
	}
?>
<div id='cssmenu' style="text-align:center;margin-top:50px;">

<ul>
				<li style="width:50%;">
				<a href="capitaux.php?iduser=<?php echo $_SESSION['id'];?>&codeCoord=<?php if(isset($_SESSION['codeC'])){echo $_SESSION['codeC'];} ?>&codecashier=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];} ?>&capitauxList=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" style="margin-right:5px;" data-title="List Of capitaux"><b><i class="fa fa-list fa-lg"></i> List Of capitaux</b>
				</a>
				</li>
	
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
               <?php 
              }else{?>
        	<li style="width:50%;"><a onclick="ShowList('Msg')" style="margin-left:5px;" data-title="<?php echo getString(49);?>"><i class="fa fa-envelope fa-lg fa-fw"></i> <?php echo getString(49);?></a></li>
              <?php }?>

</ul>


<ul style="margin-top:20px; margin-bottom:20px; background:none;border:none;">

		<div id="divMenuUser" style="display:none;">
		
			<a href="utilisateurs.php?<?php if(isset($_GET['english'])){ echo 'english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo 'francais='.$_GET['francais'];}}?><?php if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}else{ if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}}?>" class="btn-large"><i class="fa fa-user-plus fa-lg fa-fw"></i> <?php echo getString(87);?></a>
			
			<a onclick="ShowList('Liste')" id="listOn" class="btn-large" style="display:inline;"><i class="fa fa-eye fa-lg fa-fw"></i> <?php echo getString(55);?></a>
			
			<span onclick="ShowList('ListeNon')" id="listOff" class="btn-large" style="display:none;"><i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo getString(56);?></span>
		
		</div>

	
		<div style="display:none;" id="divMenuMsg">

			<a href="messages.php?ecrire=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" name="newMsg" id="EnvoiMsg"><i class="fa fa-pencil fa-lg fa-fw"></i> <?php echo getString(57);?></a>
			
			<?php if($lignecount!=0){?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?> <i class="badge flashing"><?php echo $lignecount; ?></i></a>
        <?php }else{?>
                <a href="messages.php?recu=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgRecu" class="btn-large" onclick="ShowList('MsgRecu')" ><i class="fa fa-arrow-down fa-lg fa-fw"></i> <?php echo getString(58);?></a>
        <?php }?>
			
			<a href="messages.php?envoye=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" id="MsgEnvoye" class="btn-large" onclick="ShowList('MsgEnvoye')" ><i class="fa fa-arrow-up fa-lg fa-fw"></i><?php echo getString(59);?></a>

		</div>

</ul>	
</div>

<?php if(isset($_GET['capitaux'])):?>
	<div class="capitaux">
		<div class="container">
			<div style="width: 50%;position: relative;left: 45%;font-size: 20px;font-weight: bold;font-family: verdana,arial,sans-serif;">
				Capitaux Registration
			</div>
		    <?php if($msg!=""): ?>
				<div class="alert alert-info" style="width: 50%;position: relative;left: 45%;"><?php echo $msg; ?></div>
		    <?php endif ?>
			<form method="POST">
				<table  style="margin-left:20%; margin-top: 40px;">
					<tr>
						<td>
							<span style="padding-right: 15px;">Name Donor</span>
						</td>
			
						<td>
							<input type="text" name="namedonor" style="width: 500px;" value="<?php if(isset($_POST['namedonor'])){ echo $namedonor;} ?>">
							<p style="color: red;font-size: 12px;text-transform: capitalize; font-family: arial,sans-serif;"><?php echo $capitauxnamemsg; ?></p>
						</td>
					</tr>

					<tr>
						<td>
							<span>Amount</span>
						</td>
			
						<td>
							<input type="text" name="Amount" style="width: 500px;" value="<?php if(isset($_POST['Amount'])){echo $Amount;} ?>">
							<p style="color: red;font-size: 12px;text-transform: capitalize; font-family: arial,sans-serif;"><?php echo $Amountmsg; ?></p>
						</td>
					</tr>
				<!-- 
					<tr>
						<td>
							<span>Motif</span>
						</td>
			
						<td>
							<textarea style="width: 500px;" name="Motif"><?php if(isset($_POST['Motif'])){echo $_POST['Motif'];}else{echo $_POST['Motif']="";}?></textarea>
						</td>
					</tr> -->

					<tr>
						<td></td>
						<td>
							<button class="btn-large" name="save_capitaux_btn" style="position: relative;left: 20%;width: 50%;">Save</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
<?php endif ?>

<?php if(isset($_GET['capitaux_expe'])):?>
	<div class="expenses">
		<div class="container">
			<div style="width: 50%;position: relative;left: 45%;font-size: 20px;font-weight: bold;font-family: verdana,arial,sans-serif;">
				Capitaux Expenses Registration
				<?php
					//$selectcapitaux = $connexion->query("SELECT ")
				?>
			</div>
		    <?php if($msg_exp!=""): ?>
			<div class="alert alert-info" style="width: 50%;position: relative;left: 45%;"><?php echo $msg_exp; ?></div>
		    <?php endif ?>
			<form method="POST">
				<table  style="margin-left:20%; margin-top: 40px;">
					<tr>
						<td>
							<span style="padding-right: 15px;">Capitaux Expense Name </span>
							<br>

						</td>
						
						<td>
								<input type="text" name="expensesname" style="width: 500px;" value="<?php if(isset($_POST['expensesname'])){echo $_POST['expensesname'];} ?>">
								<p style="color: red;font-size: 12px;text-transform: capitalize; font-family: arial,sans-serif;"><?php if(isset($expensesnamemsg)){ echo $expensesnamemsg;} ?></p>
						</td>
					</tr>

					<tr>
						<td>
							<span>Motif</span>
						</td>
							
							<td>
									<textarea style="width: 500px;" name="Motif"><?php if(isset($_POST['Motif'])){echo $_POST['Motif'];}?></textarea>
							</td>
					</tr>
					<tr>
						<td>
							<span>Amount</span>
						</td>
						
						<td>
								<input type="text" name="Amount" style="width: 500px;" value="<?php if(isset($_POST['Amount'])){echo $_POST['Amount'];} ?>">
								<p style="color: red;font-size: 12px;text-transform: capitalize; font-family: arial,sans-serif;"><?php echo $Amountmsg; ?></p>
						</td>
					</tr>

						<tr>
							<td></td>
						<td>
								<button class="btn-large" name="save_expenses_btn" style="position: relative;left: 20%;width: 50%;">Save</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
<?php endif ?>
<?php
		if(isset($_SESSION['codeC'])){
			$doneby=$_SESSION['codeC'];
		}else{
			if(isset($_SESSION['codeCash'])){
			$doneby=$_SESSION['codeCash'];
			}
		}

		if (isset($_SESSION['codeC'])) {
			$selectExpe=$connexion->prepare("SELECT * FROM capitaux WHERE (doneby=:doneby OR doneby!=:doneby) AND status=1");
		}else{
			$selectExpe=$connexion->prepare("SELECT * FROM capitaux WHERE doneby=:doneby AND status=1");
		}
		$selectExpe->execute(['doneby'=>$doneby]);
		$selectExpe->setFetchMode(PDO::FETCH_OBJ);
		$expecount=$selectExpe->rowCount();
		// echo $now;
		$capitauxtotal=0;
?>
<?php if(isset($_GET['capitauxList'])){?>
<div class="capitauxList">
	<div class="container-fluid">
		<div style="width: 50%;position: relative;left: 47%;font-size: 20px;padding-bottom: 20px;font-weight: bold;font-family: verdana,arial,sans-serif;">capitaux List</div>
		<div style="width: 50%;position: relative;left: 40%;font-size: 20px;padding-bottom: 20px;font-weight: bold;font-family: verdana,arial,sans-serif;">
			
			<a href="capitaux.php?cash=<?php if(isset($_SESSION['codeCash'])){echo $_SESSION['codeCash'];}?>&coordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&capitaux=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large">
			<?php echo 'New capitaux';?>
	    	</a>
&nbsp
			<a href="capitaux.php?cash=<?php if(isset($_SESSION['codeCash'])){echo $_SESSION['codeCash'];}?>&coordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&capitaux_expe=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed">
			<?php echo 'Capitaux expense';?>
	    	</a>

	    	<a href="capitaux_report.php?cash=<?php if(isset($_SESSION['codeCash'])){echo $_SESSION['codeCash'];} ?>&coordi=<?php if(isset($_SESSION['codeC'])){echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&capitaux=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large" style="margin-left: 20px;">
				<?php echo 'capitaux Report';?>
			</a>

      <!-- 		<a href="capitaux_report.php?cash=<?php if(isset($_GET['codeCash'])){echo $_SESSION['codeCash'];}else{echo $_SESSION['codeCash'];}?>&coordi=<?php echo $_SESSION['id'];?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&report=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="margin-left: 10px;">
			<?php echo 'capitaux Report';?>
		</a> -->

	</div>
		<?php if($expecount!=0 AND isset($_SESSION['codeCash'])):?>
	    	<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			<thead> 
				<tr>
					<th style="width:2%">#</th> 
					<th style="width:18%">Name Donor</th> 
					<th style="width:20%">Amount</th>
					<th style="width:15%">Added By55</th>
					<th style="width:20%; padding:0" colspan=3><?php echo 'Actions';?></th>
				</tr>
			</thead> 
			<tbody>

				<?php 
					$i = 1;
					while($ligneexpe=$selectExpe->fetch()){ 
				?>
			<tr>
                <td><?php echo $i;?></td>
				<td><?php echo $ligneexpe->donor;?></td>
			    <td><?php echo $ligneexpe->amount.' ';?>Rwf</td>
			    <td><?php 
			    	$selectUsername=$connexion->prepare("SELECT * FROM utilisateurs u,cashiers c WHERE u.id_u=c.id_u  AND c.codecashier=:numero");
					$selectUsername->execute(['numero'=>$ligneexpe->doneby]);
					$selectUsername->setFetchMode(PDO::FETCH_OBJ);
					$ligneselect=$selectUsername->fetch();
					$expecount=$selectUsername->rowCount();
					echo $ligneselect->full_name;
			     ?></td>
			    <td><a href="capitaux.php?cash=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}?>&codecoordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?>&id_capitaux=<?php echo $ligneexpe->id_capitaux;?>&edit=ok" class="btn-large">Edit</a>

			    	<a href="capitaux.php?cash=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}?>&codecoordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&id_capitaux=<?php echo $ligneexpe->id_capitaux;?>&delete=ok" class="btn-large-inversed">  <i class="fa fa-trash fa-fw"></i> Delete</a>
			    </td>
			    <?php 
					$capitauxtotal+=$ligneexpe->amount;
				 ?>
			</tr>
			<?php
					$i++;
				} 
			?>
				<tr colspan=10>
				<td style="font-weight: bold;text-decoration: underline;">Total</td>
				<td></td>
				<td></td>
				<td style="font-weight: bold;"><?php echo $capitauxtotal.' ';?>Rwf</td>
			</tr>
			</tbody>
		</table>
		<?php endif ?>

		<?php if($expecount!=0 AND isset($_SESSION['codeC'])):?>
	    	<table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
			<thead> 
				<tr>
					<th style="width:2%">#</th> 
					<th style="width:18%">Name Donor</th> 
					<th style="width:20%">Amount</th>
					<th style="width:15%">Added By</th>
					<th style="width:20%; padding:0" colspan=3><?php echo 'Actions';?></th>
				</tr>
			</thead> 
			<tbody>
				<?php 
					$i = 1 ;
					while($ligneexpe=$selectExpe->fetch()){ 
				?>
			<tr>
                <td><?php echo $i;?></td>
				<td><?php echo $ligneexpe->donor;?></td>
			    <td><?php echo $ligneexpe->amount.' ';?>Rwf</td>
			    <td><?php 
			    	$selectUsername=$connexion->prepare("SELECT * FROM utilisateurs u,coordinateurs c, cashiers ca WHERE (u.id_u=c.id_u  AND c.codecoordi=:numero) OR (u.id_u=ca.id_u  AND ca.codecashier=:numero)");
					$selectUsername->execute(['numero'=>$ligneexpe->doneby]);
					$selectUsername->setFetchMode(PDO::FETCH_OBJ);
					$ligneselect=$selectUsername->fetch();
					$expecount=$selectUsername->rowCount();
					echo $ligneselect->full_name;
			     ?></td>
			    <td>
			    	<?php
			    		if (isset($_SESSION['codeC']) AND $_SESSION['codeC']!=$ligneexpe->doneby) {
			    			echo "-------No Action------";
			    		}else{
			    	?>
			    	<a href="capitaux.php?cash=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}?>&codecoordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?>&id_capitaux=<?php echo $ligneexpe->id_capitaux;?>&edit=ok" class="btn-large">Edit</a>

			    	<a href="capitaux.php?cash=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}?>&codecoordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&id_capitaux=<?php echo $ligneexpe->id_capitaux;?>&delete=ok" class="btn-large-inversed">  <i class="fa fa-trash fa-fw"></i> Delete</a>
			    	<?php
			    		}
			    	?>
			    </td>
			    <?php 
					$capitauxtotal+=$ligneexpe->amount;
				 ?>
			</tr>
			<?php 
					$i++;
				} 
			?>
				<tr colspan=10>
				<td style="font-weight: bold;text-decoration: underline;">Total</td>
				<td></td>
				<td></td>
				<td style="font-weight: bold;"><?php echo $capitauxtotal.' ';?>Rwf</td>
			</tr>
			</tbody>
		</table>
		<?php endif ?>

		<?php if($expecount==0): ?>
		 <table class="tablesorter" cellspacing="0" style="background-color:#FFF;width:100%;margin-bottom:20px;">
          <thead>
          	<th style="width:12%;text-align:center">No capitaux Today</th>
          </thead>
		 </table>

		<?php endif ?>
</div>
</div>
<?php } ?>

<?php
	$updatemsg="";
	if(isset($_POST['edit_btn'])){
		$update=$connexion->prepare("UPDATE capitaux SET ,amount=:amount,donor=:capitauxname WHERE id_capitaux=:id_capitaux");
		$update->execute(["capitauxname"=>$_POST['capitauxname'],"Motif"=>$_POST['Motif'],"amount"=>$_POST['Amount'],"id_capitaux"=>$_GET['id_capitaux']]);
		if ($update=True) {
			$updatemsg="Capitaux Updated";

		}else{
			$updatemsg="Something Wrong";
		}
	}
	if(isset($_GET['edit'])){
	$editcapitaux=$connexion->prepare("SELECT * FROM capitaux WHERE id_capitaux=:id_capitaux");
	$editcapitaux->execute(["id_capitaux"=>$_GET['id_capitaux']]);
	$editcapitaux->setFetchMode(PDO::FETCH_OBJ);
	$ligneditexpe=$editcapitaux->fetch();
?>
	
	<div class="edit">
		<div class="container-fluid">
		<div style="width: 50%;position: relative;left: 45%;font-size: 20px;padding-bottom: 20px;font-weight: bold;font-family: verdana,arial,sans-serif;">Edit Capitaux</div>
		<div style="width: 50%;position: relative;left: 45%;font-size: 20px;padding-bottom: 20px;font-weight: bold;font-family: verdana,arial,sans-serif;">
			<a href="capitaux.php?cash=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}?>&codecoordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&capitaux=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large">
				<?php echo 'New Capitaux';?>
			</a>

			<a href="capitaux_report.php?cash=<?php if(isset($_SESSION['codeCash'])){ echo $_SESSION['codeCash'];}?>&codecoordi=<?php if(isset($_SESSION['codeC'])){ echo $_SESSION['codeC'];}?><?php if(isset($_GET['receptioniste'])){ echo '&receptioniste='.$_GET['receptioniste'];}else{ if(isset($_GET['caissier'])){ echo '&caissier='.$_GET['caissier'];}}?>&capitaux=ok<?php if(isset($_GET['english'])){ echo '&english='.$_GET['english'];}else{ if(isset($_GET['francais'])){ echo '&francais='.$_GET['francais'];}}?>" class="btn-large-inversed" style="margin-left: 20px;">
				<?php echo 'Capitaux Report';?>
			</a>

	</div>
				<?php if($updatemsg!=""): ?>
		<div class="alert alert-info" style="width: 50%;position: relative;left: 20%;"><?php echo $updatemsg; ?></div>
	    <?php endif ?>
				<form method="POST">
		<table  style="margin-left:20%; margin-top: 40px;">
		<tr>
			<td>
				<span style="padding-right: 15px;">Expense Name</span>
			</td>
			
			<td>
					<input type="text" name="capitauxname" style="width: 500px;" value="<?php echo $ligneditexpe->expensename; ?>">
			</td>
		</tr>

		<tr>
			<td>
				<span>Motif</span>
			</td>
			
			<td>
					<textarea style="width: 500px;" name="Motif"><?php echo $ligneditexpe->Motif; ?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<span>Amount</span>
			</td>
			
			<td>
					<input type="text" name="Amount" style="width: 500px;" value="<?php echo $ligneditexpe->amount; ?>">
			</td>
		</tr>

			<tr>
				<td></td>
			<td>
					<button class="btn-large" name="edit_btn" style="position: relative;left: 20%;width: 50%;">Save Change</button>
			</td>
		</tr>
	</table>
</form>
	</div>

<?php	
	}
?>


<?php
if (isset($_GET['delete'])) {

	$deletecapitaux=$connexion->prepare("DELETE FROM capitaux WHERE id_capitaux=:id_capitaux");
	$deletecapitaux->execute(["id_capitaux"=>$_GET['id_capitaux']]);
	// echo "<script>alert('Expense Deleted Successfully');</script>";
	 // echo header.location('capitaux.php?capitauxList=ok');
	if($deletecapitaux){
	echo '<script text="text/javascript">document.location.href="capitaux.php?capitauxList=ok&cash=<?php echo $_SESSION["codeCash"]; ?>"</script>';	
}
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
	}
	
	if(search =='bysn')
	{
		document.getElementById('results').style.display='none';
		document.getElementById('resultsSN').style.display='inline';
	}
}

function ShowList(list)
{
	if( list =='Users')
	{
		document.getElementById('divMenuUser').style.display='inline';
		document.getElementById('divMenuMsg').style.display='none';
		document.getElementById('divListe').style.display='none';
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
	}
	
	if( list =='Msg')
	{
		document.getElementById('divMenuMsg').style.display='inline';
		document.getElementById('divMenuUser').style.display='none';
		document.getElementById('divListe').style.display='none';
		document.getElementById('listOn').style.display='inline';
		document.getElementById('listOff').style.display='none';
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

	</body>
	<html>