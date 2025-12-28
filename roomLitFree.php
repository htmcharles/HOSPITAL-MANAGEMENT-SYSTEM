<?php
include("connect.php");

if(isset($_POST['roomnumber']))
{

	$id_room=$_POST['roomnumber'];

	$req=$connexion->prepare('SELECT * FROM rooms WHERE id_room=:room');
	$req->execute(array(
	'room'=>$id_room
	));

	$litnumber="";
	
	$litnumber.="<select name='litnumber' id='litnumber'>";
		
		while($reponse=$req->fetch(PDO::FETCH_ASSOC))
		{
			if($reponse['statusA']==0)
			{
				$litnumber.="<option value='1'>1</option>";
			}
			
			if($reponse['statusB']!=NULL)
			{
				if($reponse['statusB']==0)
				{
					$litnumber.="<option value='2'>2</option>";
				}
			}
			
			if($reponse['statusC']!=NULL)
			{
				if($reponse['statusC']==0)
				{
					$litnumber.="<option value='3'>3</option>";
				}
			}
			
			if($reponse['statusD']!=NULL)
			{
				if($reponse['statusD']==0)
				{
					$litnumber.="<option value='4'>4</option>";
				}
			}
			
			if($reponse['statusE']!=NULL)
			{
				if($reponse['statusE']==0)
				{
					$litnumber.="<option value='5'>5</option>";
				}
			}
			
			if($reponse['statusF']!=NULL)
			{
				if($reponse['statusF']==0)
				{
					$litnumber.="<option value='6'>6</option>";
				}
			}
			
			if($reponse['statusG']!=NULL)
			{
				if($reponse['statusG']==0)
				{
					$litnumber.="<option value='7'>7</option>";
				}
			}
			
			if($reponse['statusH']!=NULL)
			{
				if($reponse['statusH']==0)
				{
					$litnumber.="<option value='8'>8</option>";
				}
			}
			
			if($reponse['statusI']!=NULL)
			{
				if($reponse['statusI']==0)
				{
					$litnumber.="<option value='9'>9</option>";
				}
			}
			
			if($reponse['statusJ']!=NULL)
			{
				if($reponse['statusJ']==0)
				{
					$litnumber.="<option value='10'>10</option>";
				}
			}
			
			if($reponse['statusK']!=NULL)
			{
				if($reponse['statusK']==0)
				{
					$litnumber.="<option value='11'>11</option>";
				}
			}
			
			if($reponse['statusL']!=NULL)
			{
				if($reponse['statusL']==0)
				{
					$litnumber.="<option value='12'>12</option>";
				}
			}
			
			if($reponse['statusM']!=NULL)
			{
				if($reponse['statusM']==0)
				{
					$litnumber.="<option value='13'>13</option>";
				}
			}
			
			if($reponse['statusN']!=NULL)
			{
				if($reponse['statusN']==0)
				{
					$litnumber.="<option value='14'>14</option>";
				}
			}
		}
		
	$litnumber.="</select>";

	echo $litnumber;
	$req->closeCursor();

}
?>