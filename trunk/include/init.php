<?php


	///INCLUDE INIT FILE
	foreach( scandir( __DIR__ ) as $dir ){
		if( preg_match( '/(\.|\.\.)/', $dir ) > 0 ) continue;
		$path = __DIR__ . '/' . $dir;
		if( !is_dir( $path ) ) continue;
		$include_array = [ 'init.php' ];
		foreach( $include_array as $fileName ){
			$filePath = $path . '/' . $fileName;
			if( is_file( $filePath ) && is_readable( $filePath ) ){
				include_once $filePath;
			}
		}
	}