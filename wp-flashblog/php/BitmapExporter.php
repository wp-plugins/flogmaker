<?php

	// $RELATIVE_SAVEPATH contains the path to the folder where the temporary files are stored
	// the location is relative to this file:
	
	$RELATIVE_SAVEPATH = "BitmapExporter_Tempfiles/";
	
	
	// $DOWNLOADURL contains the url to the getimage.php script as seen from the Flash file
	// getimage.php will return the file and clean up afterwards.
	
	$DOWNLOADURL = "/wp-flashblog/PHP/BitmapExporter_Tempfiles/getimage.php?name=";
	
	// if you are experiences errors, try to set $LOGGING to true. 
	// All errors/messages will be stored in "be_log" in the same directory
	// as this file
	$LOGGING = false;
	
	if ($LOGGING) error_log( "mode: ".$mode."\n",3,"be_log");
	
	$postsize=0;
	foreach ($_POST as $key => $value) 
	{
		$postsize += strlen($value);
	}
	
	$mode = $_POST["mode"];
	
	switch ($mode)
	{
		
		case "getImageHandle":
	
			$uniqueID = md5( microtime() . rand() );
			echo "success=1&uniqueID=$uniqueID";
			exit();
		break;
		
		
		case "turboscan":
		
			$uniqueID     = $_POST["uniqueID"];
		 	$bitmapString = $_POST["bitmapString"];
			appendToTempFile( $uniqueID, $bitmapString );
			
			echo "success=1&sentBytes=".$postsize;
			exit();
		
		break;
	
		case "fastscan":
		
			$uniqueID     = $_POST["uniqueID"];
		 	$bitmapString = $_POST["bitmapString"];
			
			$data         = explode( ",", $bitmapString );
			$l            = count($data);
			$bitmapData   = array();	
			
			for ($i=0; $i<$l; $i++ )
			{
				$bitmapData[$i] = base_convert( $data[$i], 36, 10 ); 
			}
			
			appendToTempFile( $uniqueID, implode( ",", $bitmapData) );
			
			echo "success=1&sentBytes=".$postsize;
			exit();
		
		break;
	
		case "default":
			
	  		$uniqueID     = $_POST["uniqueID"];
		 	$bitmapString = $_POST["bitmapString"];
		 	
		 	$bitmapXor = ord( $bitmapString{0} );
			
			$bitmapString = decode( $bitmapString );
			
			$l   = strlen($bitmapString);
			$idx = 0;
			
			$bitmapData = array();	
			for ( $i=1; $i<$l; $i+=3 )
			{
				$bitmapData[$idx++] = ( ord( $bitmapString{$i} )^$bitmapXor)<<16 | ( ord($bitmapString{$i+1})^$bitmapXor)<<8 | (ord($bitmapString{$i+2})^$bitmapXor);
			}
			
			appendToTempFile( $uniqueID, implode( ",", $bitmapData) );
			
			echo "success=1&sentBytes=".$postsize;
			exit();
		 	
		break;
		
		case "lzw":
			
	  		$uniqueID     = $_POST["uniqueID"];
		 	$bitmapString = $_POST["bitmapString"];
		 	
		 	$bitmapXor = ord( $bitmapString{0} );
			
			$bitmapString= decode( $bitmapString );
			
			$l = strlen($bitmapString);
			$idx=0;
			
			$lzwData = array();
			for ($i=1; $i<$l; $i+=2)
			{
				$lzwData[$idx++] = (ord($bitmapString{$i})^$bitmapXor)<<8 | (ord($bitmapString{$i+1})^$bitmapXor);
			}
			$bitmapData = decompress( $lzwData );
			
			appendToTempFile( $uniqueID, implode( ",", $bitmapData ) );
				
			echo "success=1&sentBytes=".$postsize;
			exit();
		
		break;
		
		case "palette":
		
			
		 	$uniqueID = $_POST["uniqueID"];
		 	$xStart = $_POST["xStart"];
		 	$xEnd = $_POST["xEnd"];
		 	$top = $_POST["top"];
		 	$width = $_POST["width"];
		 	$lines = $_POST["lines"];
		 	$paletteString = $_POST["paletteString"];
		 	$bitmapString  = $_POST["bitmapString"];
			
			$bitmapXor = ord($bitmapString{0});
			$bitmapString= decode( $bitmapString );
			
			$l = strlen($bitmapString);
			
			$bitmapData = array();
			
			for ($i=1; $i<$l; $i++){
				$bitmapData[$i-1] = ord($bitmapString{$i})^$bitmapXor;
			}
			
			$paletteXor = ord($paletteString{0});
			$paletteString = decode( $paletteString );
			
			$paletteData = array();
			$l = strlen($paletteString);
	
			$idx = 0;
			
			for ($i=1; $i<$l; $i+=3)
			{
				$paletteData[$idx++] = ( ord($paletteString{$i})^$paletteXor )<<16 | ( ord($paletteString{$i+1})^$paletteXor )<<8 | ( ord($paletteString{$i+2})^$paletteXor );
			}
			
			$idx = 0;
			
			$paletteIdx = 0;
			
			$y = $top;
			$x = $xStart;
			$lineEnd = ( $y < $top + $lines - 1 ? $width : $xEnd ); 
			
			$decodedBitmap = array();
				
			
			while ( $y < $top + $lines ) 
			{
				$repeat = 2;
				if ( $bitmapData[$idx]==0x80 && $bitmapData[$idx+1]==0)
				{
					$repeat = 2 * ( ($bitmapData[$idx+2]<<8) + $bitmapData[$idx+3]);
					$idx+=4;
				} 
				
				$pointer = 0;
				
				while ($repeat>0)
				{
					$offset = ( ( $bitmapData[$idx + $pointer] << 8 ) + $bitmapData[ $idx+1+$pointer]);
					if ( $offset > 0x7fff ) $offset -= 0x10000;
					$paletteIdx += $offset;
					
					$decodedBitmap[] = $paletteData[$paletteIdx];
					$repeat--;
					$x++;
					if ($x>=$lineEnd){
						$x=0;
						$y++;
						$lineEnd = ( $y < $top + $lines - 1 ? $width : $xEnd ); 
					}
					$pointer = 2 - $pointer;
				}
				$idx+=4;
			}
			
			
			appendToTempFile( $uniqueID, implode( ",", $decodedBitmap ) );
			
			echo "success=1&sentBytes=".$postsize;
			exit();
			
		break;
		
		case "palettelzw":
		
			
			$uniqueID      = $_POST["uniqueID"];
		 	$xStart        = $_POST["xStart"];
		 	$xEnd          = $_POST["xEnd"];
		 	$top           = $_POST["top"];
		 	$width         = $_POST["width"];
		 	$lines         = $_POST["lines"];
		 	$paletteString = $_POST["paletteString"];
		 	$bitmapString  = $_POST["bitmapString"];
			
			$bitmapXor    = ord( $bitmapString{ 0 } );
			$bitmapString = decode( $bitmapString );
			$l            = strlen( $bitmapString );
			$lzwData      = array();
			$idx          = 0;
			
			for ( $i=1; $i < $l; $i += 2 )
			{
				$lzwData[ $idx++ ] = ( ord( $bitmapString{ $i } ) ^ $bitmapXor ) << 8 | ( ord($bitmapString{$i+1})^$bitmapXor );
			}
			
			$lzwData = decompress( $lzwData );
			$l       = count( $lzwData );
			$idx     = 0;
			
			for ( $i = 0; $i < $l; $i++ )
			{
				$bitmapData[ $idx++ ] = ($lzwData[ $i ] >> 24) & 0xff;
				$bitmapData[ $idx++ ] = ($lzwData[ $i ] >> 16) & 0xff;
				$bitmapData[ $idx++ ] = ($lzwData[ $i ] >> 8) & 0xff;
				$bitmapData[ $idx++ ] =  $lzwData[ $i ] & 0xff;
			}
			
			$paletteXor    = ord( $paletteString{ 0 } );
			$paletteString = decode( $paletteString );
			$l             = strlen( $paletteString );
			$paletteData   = array();
			$lzwData       = array();
			$idx           = 0;
			
			for ( $i=1; $i<$l; $i+=2 )
			{
				$lzwData[ $idx++ ] = ( ord( $paletteString{ $i } ) ^ $paletteXor ) << 8 | ( ord( $paletteString{ $i + 1 } ) ^ $paletteXor );
			}
			
			$paletteData   = decompress( $lzwData );
			$idx           = 0;
			$paletteIdx    = 0;
			$y             = $top;
			$x             = $xStart;
			$lineEnd       = ( $y < $top + $lines - 1 ? $width : $xEnd ); 
			$decodedBitmap = array();
			
			while ( $y < $top + $lines ) 
			{
				$repeat = 2;
				if ( $bitmapData[ $idx ] == 0x80 && $bitmapData[ $idx + 1 ] == 0 )
				{
					$repeat = 2 * ( ( $bitmapData[ $idx + 2 ] << 8 ) + $bitmapData[ $idx + 3 ] );
					$idx += 4;
				} 
				$pointer = 0;
				while ( $repeat > 0 )
				{
					$offset = ( ( $bitmapData[ $idx + $pointer ] << 8 ) + $bitmapData[ $idx + 1 + $pointer ] );
					if ( $offset > 0x7fff ) $offset -= 0x10000;
					$paletteIdx += $offset;
					
					$decodedBitmap[] = $paletteData[$paletteIdx];
					$repeat--;
					$x++;
					if ( $x >= $lineEnd )
					{
						$x = 0;
						$y++;
						$lineEnd = ( $y < $top + $lines - 1 ? $width : $xEnd ); 
					}
					$pointer = 2 - $pointer;
				}
				$idx += 4;
			} 
			
			appendToTempFile( $uniqueID, implode( ",", $decodedBitmap ) );
			
			echo "success=1&sentBytes=".$postsize;
			exit();
			
		break;
		
		case "save":
		
			$width    = $_POST["width"];
			$height   = $_POST["height"];
			$uniqueID = $_POST["uniqueID"];
			$filename = $_POST["filename"];
			$quality  = $_POST["quality"];
			
			if ( $width <= 0 || $height <= 0 )
			{
				if ($LOGGING) error_log("Wrong width or height data submitted\n",3,"be_log");
				echo "success=0&error=".urlencode("Wrong width or height data submitted");
				exit();	
			}
			
			$tempImagePath = $RELATIVE_SAVEPATH.$uniqueID.".tmp";
			checkTempImage();
			
			$bitmapData = explode(",", implode( "", file( $tempImagePath ) ));
			
			unlink( $tempImagePath );
			
			if ( count($bitmapData) != $width * $height + 1  )
			{
				$error = "Data corruption: Size of temporay file (".(count($bitmapData)-1)." bytes ) does not fit image dimensions (".($width * $height)." bytes needed)\n";
				if ($LOGGING) error_log($error,3,"be_log");
				echo "success=0&error=".urlencode( $error );
				exit();
			}
			
			
			$_format = explode(  ".", $filename);
			$format  = strtolower( $_format[1] );
			
			$tempFileName = $uniqueID.".".$format;
			
			switch ( $format )
			{
				case "bmp":
					$fp = fopen( $RELATIVE_SAVEPATH.$tempFileName, "w" );
					error_log("BMP Open\n",3,"be_log");
					fputs( $fp, stringToBmp( $bitmapData, $width, $height ) );
					error_log("BMP Written\n",3,"be_log");
					fclose ( $fp );
				break;
				
				case "png":
				case "jpg":
				case "jpeg":
			
					if ( !function_exists("imagecreatetruecolor") )
					{
						if ($LOGGING) error_log("No GD installed on Server\n",3,"be_log");
						echo "success=0&error=".urlencode("No GD installed on Server");
						exit();
					}
					
					$im = @imagecreatetruecolor ( $width, $height );
					
					if (!$im)
					{
						if ($LOGGING) error_log("Could not create GD-image: ".$php_errormsg."\n",3,"be_log");
						echo "success=0&error=".urlencode("Could not create GD-image (".$php_errormsg.")");
						exit();
					}
			
					$idx = 0;
					
					for ( $y = 0; $y < $height; $y++ ){
						for ( $x = 0; $x < $width; $x++ ){
							@imagesetpixel ( $im, $x, $y, $bitmapData[$idx++] );
						}	
					}
					
					
					if ( $format=="jpg" || $format=="jpeg" )
					{
						if (!@imagejpeg ( $im , $RELATIVE_SAVEPATH.$tempFileName, $quality )){
							echo "success=0&error=".urlencode("Could not generate JPEG file");
							exit();
						}
					} else {
						if (!@imagepng ( $im , $RELATIVE_SAVEPATH.$tempFileName )){
							echo "success=0&error=".urlencode("Could not generate PNG file");
							exit();
						}
					}
				break;	
				
				default:
				
					if ( $format != "jpg" && $format != "jpeg" && $format != "png" ){
						echo "success=0&error=".urlencode("Desired file-format is not available (".$format.")");	
						exit();
					}	
				break;
			}
			
			
			echo "success=1&url=".urlencode( $DOWNLOADURL.$tempFileName )."&size=".$size;
			$nomimage = "background.png";
			copy($RELATIVE_SAVEPATH.$tempFileName,'./../img/'.$nomimage);
			
			//echo "success=1";
			
		break;
		
	
		case "dropImageHandle":
		
			$uniqueID = $_POST["uniqueID"];
			
			$imageFilePath = $RELATIVE_SAVEPATH.$uniqueID;
			
			if ( file_exists( $imageFilePath.".png" ) )
			{
				unlink( $imageFilePath.".png");
			} else if ( file_exists( $imageFilePath.".jpg" ) )
			{
				unlink( $imageFilePath.".jpg");
			} else if ( file_exists( $imageFilePath.".jpeg" ) )
			{
				unlink( $imageFilePath.".jpeg");
			}  else if ( file_exists( $imageFilePath.".bmp" ) )
			{
				unlink( $imageFilePath.".bmp");
			}  
			
			echo "success=1";
			exit();
		break;	
		
	}


	function decompress($data)
	{
		
		$dict = array();
		
		for ($i = 0; $i < 256; $i++)
		{
			$dict[$i] = $i;
		}
		
		$length = count($data);
		$nbChar = 256;
		$buffer = "";
		$chn = "";
		$currentValue = 0;
		$result = array();
		$r=0;
		for ($i = 0; $i < $length; $i++)
		{
			$code = $data[$i];
			$current = $dict[$code];
			if ($buffer == "")
			{
				$buffer = chr($current);
				if ( $current != 44 ){
					$currentValue <<= 7;
					$currentValue += $current-128;
				} else {
					$result[$r++] = $currentValue ;
					$currentValue=0;
				}
			} else {
				if ( $code < 256 )
				{
					if ( $current != 44){
						$currentValue <<= 7;
						$currentValue += $current-128;
					} else {
						$result[$r++] = $currentValue ;
						$currentValue = 0;
					}
					$chn = $buffer . chr($current);
					$dict[$nbChar++] = $chn;
					$buffer = chr( $current );
				}
				else
				{
					
					$chn = $dict[$code];
					if ($chn == "") $chn = $buffer . $buffer{0};
					
					for ( $j =0; $j < strlen($chn); $j++ ){
						$current = ord($chn{$j});
						if ($current != 44){
							$currentValue <<= 7;
							$currentValue += $current - 128;
						} else {
							$result[$r++] = $currentValue;
							$currentValue = 0;
						}
					}
					
					$dict[$nbChar++] = $buffer . $chn{0};
					$buffer = $chn;
				}
			}
		}
			
		return $result;
	}

	function decode( $str )
	{
		return 	str_replace("\x01\x02","\x01",
			str_replace("\x01\x20","\x5c",
			str_replace("\x01\x1f","\x27",
			str_replace("\x01\x1e","\x22",
			str_replace("\x01\x1d","\x9f",
			str_replace("\x01\x1c","\x9e",
			str_replace("\x01\x1b","\x9c",
			str_replace("\x01\x1a","\x9b",
			str_replace("\x01\x19","\x9a",
			str_replace("\x01\x18","\x99",
			str_replace("\x01\x17","\x98",
			str_replace("\x01\x16","\x97",
			str_replace("\x01\x15","\x96",
			str_replace("\x01\x14","\x95",
			str_replace("\x01\x13","\x94",
			str_replace("\x01\x12","\x93",
			str_replace("\x01\x11","\x92",
			str_replace("\x01\x10","\x91",
			str_replace("\x01\x0f","\x8e",
			str_replace("\x01\x0e","\x8c",
			str_replace("\x01\x0d","\x8b",
			str_replace("\x01\x0c","\x8a",
			str_replace("\x01\x0b","\x89",
			str_replace("\x01\x0a","\x88",
			str_replace("\x01\x09","\x87",
			str_replace("\x01\x08","\x86",
			str_replace("\x01\x07","\x85",
			str_replace("\x01\x06","\x84",
			str_replace("\x01\x05","\x83",
			str_replace("\x01\x04","\x82",
			str_replace("\x01\x03","\x80",
			str_replace("\x01\x01","\x00",$str))))))))))))))))))))))))))))))));	
		
	}
	
	function checkTempImage()
	{
		global $tempImagePath;
		if ( !file_exists( $tempImagePath ) ){
			echo "success=0&error=".urlencode("No temporary image with provided uniqueID found");
			exit();
		}
	}
	
	function appendToTempFile( $uniqueID, $bitmapString )
	{
		global $RELATIVE_SAVEPATH;
		
		$tempImagePath = $RELATIVE_SAVEPATH.$uniqueID.".tmp";
		
		$fp = @fopen( $tempImagePath, "a+" );
			
		if ( !$fp )
		{
			echo "success=0&error=".urlencode("Could not write to temporary file");
			exit();	
		}
		
		fputs( $fp, $bitmapString."," );
		fclose ( $fp );
				
	}
	
	
	// stringToBmp function with kind permission 
	// by Patrick Mineault, http://www.5etdemi.com/
	// adapted to BitmapExporter data stream format
	
	function stringToBmp($stream, $width, $height)
	{
	//Bitmap header
	$bmpHeader = "BM";
	
	//Length is number of bytes + header size (54 bytes)
	$len = $width*$height*3;
	//write as little endian
	$bmpHeader .= pack("V", $len + 54);
	
	//Four empty bytes
	$bmpHeader .= "\0\0\0\0";
	//Offset to beginning of data
	$bmpHeader .= chr(54) . "\0\0\0";
	
	//Size of the bmpinfo header
	$bmpHeader .= chr(40) . "\0\0\0";
	//width
	$bmpHeader .= pack("V", $width);
	//height
	$bmpHeader .= pack("V", $height);
	//Number of biplanes
	$bmpHeader .= chr(1) . "\0";
	//Bits per pixel
	$bmpHeader .= chr(24) . "\0\0\0";
	//Compression type (0 = none)
	$bmpHeader .= "\0\0";
	//Image data size
	$bmpHeader .= pack("V", $len);
	//BiXPelsPerMeter & BiYPelsPerMeter (this is the value Fireworks writes)
	$bmpHeader .= pack("V", 2834) . pack("V", 2834);
	//Extra useless bytes
	$bmpHeader .= "\0\0\0\0\0\0\0\0";
	
	//Normalize the string so it's the right length, fill with white
	//pixels if necesary
	//$slicedStream = strrev($stream);
	// $slicedStream = substr($slicedStream, 0, $len);
	
	$idx = $width * $height;
	while ( --$idx > -1 ){
		$slicedStream .=  chr( $stream[$idx] & 0xff ) . chr( ($stream[$idx] >> 8) & 0xff ) . chr( ($stream[$idx] >> 16) & 0xff );
	}
	
	$slicedStream = str_pad($slicedStream, $len, str_repeat(chr(255), 1024));
	
	//And we're done
	return $bmpHeader . $slicedStream;
	}


?>