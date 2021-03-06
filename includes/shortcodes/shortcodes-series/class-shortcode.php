<?php
/**
 * GC Sermons Series Shortcode
 *
 * @package GC Sermons
 */

class GCS_Shortcodes_Series extends GCS_Shortcodes_Base {
	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run   = new GCSS_Series_Run( $plugin->sermons, $plugin->series );
		$this->admin = new GCSS_Series_Admin( $this->run );

		parent::hooks();
	}
}
