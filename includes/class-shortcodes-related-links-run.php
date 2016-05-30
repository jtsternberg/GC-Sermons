<?php
/**
 * GC Sermons Related Links Shortcode
 *
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Related_Links_Run extends GCS_Shortcodes_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_related_links';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermon_id' => 0, // 'Blank, "recent", or "0" will play the most recent video.
		'recent'    => 'recent', // Options: 'recent', 'audio', 'video'
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		return gc_get_sermon_related_links( $this->get_sermon() );
	}

}
