<?php
/**
 * GC Sermons Related Links Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Related_Links {

	/**
	 * Instance of GCSS_Related_Links_Run
	 *
	 * @var GCSS_Related_Links_Run
	 */
	public $run;

	/**
	 * Instance of GCSS_Related_Links_Admin
	 *
	 * @var GCSS_Related_Links_Admin
	 */
	public $admin;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run   = new GCSS_Related_Links_Run( $plugin->sermons );
		$this->admin = new GCSS_Related_Links_Admin( $this->run );

		$this->run->hooks();
		$this->admin->hooks();
	}

}
