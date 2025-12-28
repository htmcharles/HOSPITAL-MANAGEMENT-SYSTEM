<?php

	/**
		Please include barcode/createBC.php to your php file.
		This function takes as parameter 
		the number to parse as barcode & the user serial number
	*/

	function createBC($numToParse, $userSerialNumber)
	{
		require_once('class/BCGFontFile.php');
		require_once('class/BCGColor.php');
		require_once('class/BCGDrawing.php');
		require_once('class/BCGcode93.barcode.php');
		
		$font = new BCGFontFile('font/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		// Barcode Part
		$code = new BCGcode93();
		$code->setScale(2);
		$code->setThickness(30);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setLabel('# '.$num.' #');
		$code->parse(''.$num.'');
		
		// Drawing Part
		$drawing = new BCGDrawing('png/barcode'. $user .'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		 
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}
?>