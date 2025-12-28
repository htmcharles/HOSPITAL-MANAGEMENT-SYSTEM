<?php
	/* 
		S/N format: M15A01
		Remember to create new table in your database:
			name: sn_table
			columns: sn_type(M), sn_id_y(15), sn_id_l(A) and sn_id_n(0);
	*/
	
	/*
		BiHMC number format: #015-HMCB/8AK047	
	*/
	
	/*
		Report number format: #PVM047	
	*/
	
	function formatBN($num)
	{
		if ($num < 10)
		{
			return "00".$num;
		}else if($num < 100)
		{
			return "0".$num;
		}else
		{
			return $num;
		}
	}
	
	function formatSN($num)
	{
		if($num < 10)
		{
			return "0".$num;
		}else
		{
			return $num;
		}
		return nuHMC;
	}
	
	function createBN()
	{
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$title = 'B';
		
		$sy = "SELECT bn_y
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sy) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$year = $bn_y;
		
		$sni = "SELECT bn_n_i
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sni) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$ini_num = $bn_n_i;
		
		$sl = "SELECT bn_l
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $bn_l;
		
		$sn = "SELECT bn_n
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $bn_n;
		$num++;
		if($num > 999)
		{
			if($letter == 'ZZ')
			{
				$ini_num++;
				$sql_n_i = "UPDATE bn_table SET bn_n_i='$ini_num' WHERE bn_type='$title'";
				$result = mysql_query($sql_n_i);
				
				$letter = 'A';
				$sql_l = "UPDATE bn_table SET bn_l='$letter' WHERE sn_type='$title'";
				$result = mysql_query($sql_l);
				$num = 1;
			}else
			{
				$letter++;
				$sql_l = "UPDATE bn_table SET bn_l='$letter' WHERE bn_type='$title'";
				$result = mysql_query($sql_l);
				$num = 1;
			}
		}
		$sql_n = "UPDATE bn_table SET bn_n=$num WHERE bn_type='$title'";
		$result = mysql_query($sql_n);
		
		$n_year = (int) date('Y') - 2000;
		if($n_year > $year)
		{
			$year = $n_year;
			$sql_y = "UPDATE bn_table SET bn_y='$year' WHERE bn_type='$title'";
			$result = mysql_query($sql_y);
			
			$ini_num = 1;
			$sql_n_i = "UPDATE bn_table SET bn_n_i='$ini_num' WHERE bn_type='$title'";
			$result = mysql_query($sql_n_i);
			
			$letter = 'A';
			$sql_l = "UPDATE bn_table SET bn_l='$letter' WHERE bn_type='$title'";
			$result = mysql_query($sql_l);
			
			$num = 1;
			$sql_n = "UPDATE bn_table SET bn_n=$num WHERE bn_type='$title'";
			$result = mysql_query($sql_n);
		}
		return ''. formatBN($year). '-' . $hosp . $title . '/' . $ini_num . $letter. formatBN($num);
	}
	
	function createON($title)
	{
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$title = strtoupper($title);
		
		$sy = "SELECT bn_y
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sy) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$year = $bn_y;
		
		$sni = "SELECT bn_n_i
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sni) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$ini_num = $bn_n_i;
		
		$sl = "SELECT bn_l
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $bn_l;
		
		$sn = "SELECT bn_n
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $bn_n;
		$num++;
		if($num > 999)
		{
			if($letter == 'ZZ')
			{
				$ini_num++;
				$sql_n_i = "UPDATE bn_table SET bn_n_i='$ini_num' WHERE bn_type='$title'";
				$result = mysql_query($sql_n_i);
				
				$letter = 'A';
				$sql_l = "UPDATE bn_table SET bn_l='$letter' WHERE sn_type='$title'";
				$result = mysql_query($sql_l);
				$num = 1;
			}else
			{
				$letter++;
				$sql_l = "UPDATE bn_table SET bn_l='$letter' WHERE bn_type='$title'";
				$result = mysql_query($sql_l);
				$num = 1;
			}
		}
		$sql_n = "UPDATE bn_table SET bn_n=$num WHERE bn_type='$title'";
		$result = mysql_query($sql_n);
		
		$n_year = (int) date('Y') - 2000;
		if($n_year > $year)
		{
			$year = $n_year;
			$sql_y = "UPDATE bn_table SET bn_y='$year' WHERE bn_type='$title'";
			$result = mysql_query($sql_y);
			
			$ini_num = 1;
			$sql_n_i = "UPDATE bn_table SET bn_n_i='$ini_num' WHERE bn_type='$title'";
			$result = mysql_query($sql_n_i);
			
			$letter = 'A';
			$sql_l = "UPDATE bn_table SET bn_l='$letter' WHERE bn_type='$title'";
			$result = mysql_query($sql_l);
			
			$num = 1;
			$sql_n = "UPDATE bn_table SET bn_n=$num WHERE bn_type='$title'";
			$result = mysql_query($sql_n);
		}
		return ''. formatBN($year). '-' . $hosp . $title . '/' . $ini_num . $letter. formatBN($num);
	}
	
	function createSN($user)
	{
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$user = strtoupper($user);
		
		$sy = "SELECT sn_id_y
					FROM sn_table
						WHERE sn_type = '$user'";
		$result = mysql_query($sy) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$year = $sn_id_y;
		
		$sl = "SELECT sn_id_l
					FROM sn_table
						WHERE sn_type = '$user'";
		$result = mysql_query($sl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $sn_id_l;
		
		$sn = "SELECT sn_id_n
					FROM sn_table
						WHERE sn_type = '$user'";
		$result = mysql_query($sn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $sn_id_n;
		$num++;
		if($num > 99)
		{
			$letter++;
			$sql_l = "UPDATE sn_table SET sn_id_l='$letter' WHERE sn_type='$user'";
			$result = mysql_query($sql_l);
			$num = 1;
			$sql_n = "UPDATE sn_table SET sn_id_n=$num WHERE sn_type='$user'";
			$result = mysql_query($sql_n);
		}
		$sql_n = "UPDATE sn_table SET sn_id_n=$num WHERE sn_type='$user'";
		$result = mysql_query($sql_n);
		
		$n_year = (int) date('Y') - 2000;
		if($n_year > $year)
		{
			$year = $n_year;
			$sql_y = "UPDATE sn_table SET sn_id_y='$year' WHERE sn_type='$user'";
			$result = mysql_query($sql_y);
			
			$letter = 'A';
			$sql_l = "UPDATE sn_table SET sn_id_l='$letter' WHERE sn_type='$user'";
			$result = mysql_query($sql_l);
			
			$num = 1;
			$sql_n = "UPDATE sn_table SET sn_id_n=$num WHERE sn_type='$user'";
			$result = mysql_query($sql_n);
		}
		return $hosp . $user . formatSN($year) . $letter. formatSN($num);
	}
	
	function createRN($type)
	{
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$type = strtoupper($type);
		
		$rl = "SELECT rn_id_l
					FROM rn_table
						WHERE rn_type = '$type'";
		$result = mysql_query($rl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $rn_id_l;
		
		$rn = "SELECT rn_id_n
					FROM rn_table
						WHERE rn_type = '$type'";
		$result = mysql_query($rn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $rn_id_n;
		$num++;
		if($num > 999)
		{
			$letter++;
			$sql_l = "UPDATE rn_table SET rn_id_l='$letter' WHERE rn_type='$type'";
			$result = mysql_query($sql_l);
			$num = 1;
		}
		$sql_n = "UPDATE rn_table SET rn_id_n=$num WHERE rn_type='$type'";
		$result = mysql_query($sql_n);

		return $hosp . '-' . $type . $letter . formatBN($num);
	}
	
	function showBN()
	{
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$title = 'B';
		
		$sy = "SELECT bn_y
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sy) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$year = $bn_y;
		
		$sni = "SELECT bn_n_i
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sni) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$ini_num = $bn_n_i;
		
		$sl = "SELECT bn_l
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $bn_l;
		
		$sn = "SELECT bn_n
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $bn_n;
		$num++;
		if($num > 999)
		{
			if($letter == 'ZZ')
			{
				$ini_num++;				
				$letter = 'A';
				$num = 1;
			}else
			{
				$letter++;
				$num = 1;
			}
		}
		$n_year = (int) date('Y') - 2000;
		if($n_year > $year)
		{
			$year = $n_year;
			
			$ini_num = 1;
			$letter = 'A';
			$num = 1;
		}
		return '' . formatBN($year). '-' . $hosp . $title . '/' . $ini_num . $letter. formatBN($num);
	}
	
	function showON($title)
	{
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$title = strtoupper($title);
		
		$sy = "SELECT bn_y
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sy) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$year = $bn_y;
		
		$sni = "SELECT bn_n_i
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sni) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$ini_num = $bn_n_i;
		
		$sl = "SELECT bn_l
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $bn_l;
		
		$sn = "SELECT bn_n
					FROM bn_table
						WHERE bn_type = '$title'";
		$result = mysql_query($sn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $bn_n;
		$num++;
		if($num > 999)
		{
			if($letter == 'ZZ')
			{
				$ini_num++;				
				$letter = 'A';
				$num = 1;
			}else
			{
				$letter++;
				$num = 1;
			}
		}
		$n_year = (int) date('Y') - 2000;
		if($n_year > $year)
		{
			$year = $n_year;
			
			$ini_num = 1;
			$letter = 'A';
			$num = 1;
		}
		return '' . formatBN($year). '-' . $hosp . $title . '/' . $ini_num . $letter. formatBN($num);
	}
	
	function showSN($user)
	{
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$user = strtoupper($user);
		
		$sy = "SELECT sn_id_y
					FROM sn_table
						WHERE sn_type = '$user'";
		$result = mysql_query($sy) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$year = $sn_id_y;
		
		$sl = "SELECT sn_id_l
					FROM sn_table
						WHERE sn_type = '$user'";
		$result = mysql_query($sl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $sn_id_l;
		
		$sn = "SELECT sn_id_n
					FROM sn_table
						WHERE sn_type = '$user'";
		$result = mysql_query($sn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $sn_id_n;
		$num++;
		if($num > 99)
		{
			$letter++;
			$num = 1;
		}
		
		$n_year = (int) date('Y') - 2000;
		if($n_year > $year)
		{
			$year = $n_year;
			$letter = 'A';
			$num = 1;
		}
		return $hosp . $user . formatSN($year) . $letter. formatSN($num);
	}
	
	function showRN($type)
	{
		$connect = mysql_connect("localhost", "zag_dbsuperuser", "{{Carbon\Carbon::now()}}") or die(mysql_error());
		mysql_select_db("HMC_dbase");
		
		$hosp = "HMC";
		$type = strtoupper($type);
		
		$rl = "SELECT rn_id_l
					FROM rn_table
						WHERE rn_type = '$type'";
		$result = mysql_query($rl) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$letter = $rn_id_l;
		
		$rn = "SELECT rn_id_n
					FROM rn_table
						WHERE rn_type = '$type'";
		$result = mysql_query($rn) or die(mysql_error());
		$row = mysql_fetch_array($result);
		extract($row);
		$num = $rn_id_n;
		$num++;
		if($num > 999)
		{
			$letter++;
			$num = 1;
		}

		return $hosp . '-' . $type . $letter . formatBN($num);
	}
?>