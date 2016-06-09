<?php
/**
 * GC Sermons Play Button Shortcode
 * @version 0.1.6
 * @package GC Sermons
 */

class GCS_Shortcodes_Play_Button extends GCS_Shortcodes_Base {

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

		parent::hooks();
	}

}
