<?php

	use hiweb\errors\display;


	function errors_display( $showBacktrace = false ){
		return display::enable( $showBacktrace );
	}
