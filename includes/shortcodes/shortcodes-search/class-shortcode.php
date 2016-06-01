<?php
/**
 * GC Sermons Search Shortcode
 * @version 0.1.4
 * @package GC Sermons
 */
class GCS_Shortcodes_Sermon_Search extends GCS_Shortcodes_Base {

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run   = new GCS_Shortcodes_Sermon_Search_Run( $plugin->sermons, $plugin->taxonomies );
		$this->admin = new GCS_Shortcodes_Sermon_Search_Admin( $this->run, $plugin->taxonomies );

		parent::hooks();
	}

}
