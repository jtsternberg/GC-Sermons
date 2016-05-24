<?php
/**
 * GC Sermons Play Button Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Play_Button {

	/**
	 * Instance of GCSS_Play_Button_Run
	 *
	 * @var GCSS_Play_Button_Run
	 */
	public $run;

	/**
	 * Instance of GCSS_Play_Button_Admin
	 *
	 * @var GCSS_Play_Button_Admin
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
		$this->run = new GCSS_Play_Button_Run( $plugin->sermons );
		$this->admin = new GCSS_Play_Button_Admin( $this->run );

		$this->run->hooks();
		$this->admin->hooks();
	}

}
