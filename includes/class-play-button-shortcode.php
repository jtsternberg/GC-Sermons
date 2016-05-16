<?php
/**
 * GC Sermons Play Button Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Play_Button {

	/**
	 * Instance of GCS_PBS_Run
	 *
	 * @var GCS_PBS_Run
	 */
	protected $run;

	/**
	 * Instance of GCS_PBS_Admin
	 *
	 * @var GCS_PBS_Admin
	 */
	protected $admin;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run = new GCS_PBS_Run();
		$this->run->sermons = $plugin->sermons;

		$this->admin = new GCS_PBS_Admin( $this->run );

		$this->run->hooks();
		$this->admin->hooks();
	}

}
