<?php
/**
 * GC Sermons Recent Speaker Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Recent_Speaker {

	/**
	 * Instance of GCSS_Recent_Speaker_Run
	 *
	 * @var GCSS_Recent_Speaker_Run
	 */
	public $run;

	/**
	 * Instance of GCSS_Recent_Speaker_Admin
	 *
	 * @var GCSS_Recent_Speaker_Admin
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
		$this->run   = new GCSS_Recent_Speaker_Run( $plugin->sermons );
		$this->admin = new GCSS_Recent_Speaker_Admin( $this->run );

		$this->run->hooks();
		$this->admin->hooks();
	}

}
