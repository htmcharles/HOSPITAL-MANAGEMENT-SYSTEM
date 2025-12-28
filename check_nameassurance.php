<?php
	session_start();
	include("connect.php");
	include("connectLangues.php");
	include("serialNumber.php");
	
	if(isset($_POST['assurance_selection_btn']))
	{
		$assurances_name=$_POST['chosen_assurances'];
		$assurances_name = strtolower($assurances_name);
		if(isset($assurances_name))
		{
			header('location:prices_edit.php?assurances_name='.$assurances_name.'');
		}
	}
?>