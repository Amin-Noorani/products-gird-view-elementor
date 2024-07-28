<?php
namespace MN\RTL;

class Includes {
	public static function main() {
		include( MN_RTL_DIR . "Utils.php" );
	}
}

Includes::main();
$is_admin = is_admin();
if( $is_admin === true ) { // Only backends
} else {
}