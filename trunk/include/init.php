<?php



	///LOAD TEXT DOMAIN
	add_action( 'plugins_loaded', function(){
		$mo_file_path = __DIR__ . '/languages/hw-core-2-' . get_locale() . '.mo';
		load_textdomain( 'hw-core-3', $mo_file_path );
	} );

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