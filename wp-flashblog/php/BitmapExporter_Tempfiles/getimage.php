<?

	$file = basename($_GET["name"]);
	if (file_exists($file)) 
	{
		$_format = explode(  ".", $file);
		$format  = strtolower( $_format[1] );
			
		switch ( $format )
		{
			case "png":
				header ("Content-type: image/png");
				break;
				
			case "jpg":
			case "jpeg":
				header ("Content-type: image/jpeg");
				break;
				
			case "bmp":
				header ("Content-type: image/bmp");
				break;
				
			default:
				//error_log("Unknown filetype: ".$format, 3, "error_log" );
				exit();
				break;
		}
		header("Content-Length: ".(string)(filesize($file)));
     
		readfile( $file );
		while(!unlink( $file ));
	} else {
		//error_log($file." does not exist", 3, "error_log" );
	
		exit();
	}

	// The following routine will delete all orphan files from the
	// temp folder that are older than 1 day
	// Orphans can happen when the save routine got interrupted
	// and the dropImageHandle method hadn't been called:

	$days = 1;
	
	$dir = opendir(".");
	while( false !== ( $file = readdir( $dir ) ) )
	{
		$tsuffix = explode(".",$file);
		$suffix = $suffix[count($tsuffix)-1];
		if ( $suffix=="tmp" || $suffix=="png" || $suffix=="jpg" || $suffix=="jpeg" || $suffix=="bmp" )
		{
			$find_date = ( time() - filemtime($file) )/60/60/24;
			if( $find_date > $days )
			{
				while( !unlink($file) );
			}
		}
	}
	closedir($dir);
	
	 
?>