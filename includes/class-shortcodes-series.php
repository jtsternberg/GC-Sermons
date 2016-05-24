<?php
/**
 * GC Sermons Series Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Series {

	/**
	 * Instance of GCSS_Series_Run
	 *
	 * @var GCSS_Series_Run
	 */
	public $run;

	/**
	 * Instance of GCSS_Series_Admin
	 *
	 * @var GCSS_Series_Admin
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
		$this->run   = new GCSS_Series_Run( $plugin->sermons );
		$this->admin = new GCSS_Series_Admin( $this->run );

		$this->run->hooks();
		$this->admin->hooks();
	}

}
