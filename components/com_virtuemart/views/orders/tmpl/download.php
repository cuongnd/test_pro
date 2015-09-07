<?php

$filename=JPATH_ROOT.'/media/vmfiles/ueb3c_extensions.sql.gz';
// For a certain unmentionable browser -- Thank you, Nooku, for the tip
if (function_exists ( 'ini_get' ) && function_exists ( 'ini_set' )) {
	if (ini_get ( 'zlib.output_compression' )) {
		ini_set ( 'zlib.output_compression', 'Off' );
	}
}

// Remove php's time limit -- Thank you, Nooku, for the tip
if (function_exists ( 'ini_get' ) && function_exists ( 'set_time_limit' )) {
	if (! ini_get ( 'safe_mode' )) {
		@set_time_limit ( 0 );
	}
}

$basename = @basename ( $filename );
$filesize = @filesize ( $filename );
$extension = strtolower ( str_replace ( ".", "", strrchr ( $filename, "." ) ) );

while ( @ob_end_clean () )
	;
@clearstatcache ();
// Send MIME headers
header ( 'MIME-Version: 1.0' );
header ( 'Content-Disposition: attachment; filename="' . $basename . '"' );
header ( 'Content-Transfer-Encoding: binary' );
header ( 'Accept-Ranges: bytes' );

switch ($extension) {
	case 'zip' :
		// ZIP MIME type
		header ( 'Content-Type: application/zip' );
		break;

	default :
		// Generic binary data MIME type
		header ( 'Content-Type: application/octet-stream' );
		break;
}
// Notify of filesize, if this info is available
if ($filesize > 0)
	header ( 'Content-Length: ' . @filesize ( $filename ) );
	// Disable caching
header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header ( "Expires: 0" );
header ( 'Pragma: no-cache' );
flush ();
if ($filesize > 0) {
	// If the filesize is reported, use 1M chunks for echoing the data to the browser
	$blocksize = 1048756; // 1M chunks
	$handle = @fopen ( $filename, "r" );
	// Now we need to loop through the file and echo out chunks of file data
	if ($handle !== false)
		while ( ! @feof ( $handle ) ) {
			echo @fread ( $handle, $blocksize );
			@ob_flush ();
			flush ();
		}
	if ($handle !== false)
		@fclose ( $handle );
} else {
	// If the filesize is not reported, hope that readfile works
	@readfile ( $filename );
}
exit ( 0 );