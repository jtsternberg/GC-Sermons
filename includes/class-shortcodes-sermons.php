<?php
/**
 * GC Sermons Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Sermons {

	/**
	 * Instance of GCSS_Sermons_Run
	 *
	 * @var GCSS_Sermons_Run
	 */
	public $run;

	/**
	 * Instance of GCSS_Sermons_Admin
	 *
	 * @var GCSS_Sermons_Admin
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
		$this->run   = new GCSS_Sermons_Run( $plugin->sermons );
		$this->admin = new GCSS_Sermons_Admin( $this->run );

		$this->run->hooks();
		$this->admin->hooks();
	}

}
