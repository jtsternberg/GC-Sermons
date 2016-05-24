<?php
/**
 * GC Sermons Recent Series Shortcode
 *
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Recent_Series_Run extends GCS_Shortcodes_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_recent_series';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermon_id'           => 0, // 'Blank, "recent", or "0" will play the most recent video.
		'sermon_recent'       => 'recent', // Options: 'recent', 'audio', 'video'
		'series_do_thumbnail' => false,
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		$content = gc_get_sermon_series_info( $this->get_sermon(), $this->att( 'series_do_thumbnail' ) );

		return $content;
	}

}
