<?php
/**
 * GC Sermons Recent Speaker Shortcode
 *
 * @version 0.1.4
 * @package GC Sermons
 */

class GCSS_Recent_Speaker_Run extends GCS_Shortcodes_Run_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_recent_speaker';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermon_id'        => 0, // 'Blank, "recent", or "0" will play the most recent video.
		'recent'           => 'recent', // Options: 'recent', 'audio', 'video'
		'remove_thumbnail' => false,
		'thumbnail_size'   => 'medium',
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		$content = gc_get_sermon_speaker_info( $this->get_sermon(), ! $this->bool_att( 'remove_thumbnail' ) );

		return $content;
	}

}
