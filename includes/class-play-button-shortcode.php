<?php
/**
 * GC Sermons Play Button Shortcode
 * @version 0.1.0
 * @package GC Sermons
 */

class GCS_Shortcodes_Play_Button {

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run = new GCS_PBS_Run();
		$this->run->sermons = $plugin->sermons;

		$this->admin = new GCS_PBS_Admin( $this->run, $plugin->sermons->post_type() );

		$this->run->hooks();
		$this->admin->hooks();
	}

}