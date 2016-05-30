<?php
/**
 * GC Sermons Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Sermons extends GCS_Shortcodes_Base {
	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run   = new GCSS_Sermons_Run( $plugin->sermons, $plugin->taxonomies );
		$this->admin = new GCSS_Sermons_Admin( $this->run, $plugin->taxonomies );

		parent::hooks();
	}
}
