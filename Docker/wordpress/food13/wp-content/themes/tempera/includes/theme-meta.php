<?php /*
 * meta related functions
 *
 * @package tempera
 * @subpackage Functions
 */

/**
 * Filter for page meta title.
 */

function tempera_mobile_meta() {
	global $temperas;
	if ($temperas['tempera_iecompat']) echo PHP_EOL . '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />' . PHP_EOL;
	if ($temperas['tempera_zoom']==1) 
		echo '<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0, maximum-scale=3.0">';
	else 
		echo '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">';
	echo PHP_EOL;
}

add_action( 'cryout_meta_hook', 'tempera_mobile_meta' );


// favicon functionality removed @tempera 1.8.0

// FIN