<?php
/**
 * GC Sermons Recent Speaker Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Recent_Speaker extends GCS_Shortcodes_Base {
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

		parent::hooks();
	}
}
