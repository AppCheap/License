<?php

class Verify {

	public function __construct() {
		$this->run();
	}

	public function run() {
		$this->verify();
	}

	public function verify() {
		$this->verify_file( 'src/Updater.php' );
		$this->verify_file( 'src/Client.php' );
		$this->verify_file( 'src/Theme.php' );
		$this->verify_file( 'src/Plugin.php' );
	}

	public function verify_file( $file ) {
		$content = file_get_contents( $file );
		$content = str_replace( 'Appsero', 'Appcheap', $content );
		file_put_contents( $file, $content );
	}
}
