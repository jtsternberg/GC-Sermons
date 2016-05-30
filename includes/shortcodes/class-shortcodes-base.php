<?php
/**
 * GC Sermons Shortcode Base
 *
 * @version 0.1.3
 * @package GC Sermons
 */

abstract class GCS_Shortcodes_Base {

	/**
	 * Instance of GCS_Shortcodes_Run_Base
	 *
	 * @var GCS_Shortcodes_Run_Base
	 */
	public $run;

	/**
	 * Instance of GCS_Shortcodes_Admin_Base
	 *
	 * @var GCS_Shortcodes_Admin_Base
	 */
	public $admin;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	abstract function __construct( $plugin );

	public function hooks() {
		$this->run->hooks();
		$this->admin->hooks();
	}

}
