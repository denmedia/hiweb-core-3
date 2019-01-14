<?php

	namespace hiweb;


	add_action( 'shutdown', 'hiweb\console\messages::the', 9999999999999 );