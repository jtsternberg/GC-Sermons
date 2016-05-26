<?php
/**
 * GC Sermons Shortcode - Run
 *
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Sermons_Run extends GCS_Shortcodes_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_sermons';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermons_posts_per_page'  => 10, // Will use WP's per-page option.
		'sermon_remove_excerpt'   => false,
		'sermon_remove_thumbnail' => false,
		'sermon_thumbnail_size'   => 'medium',
		'sermon_wrap_classes'     => '',
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		// @todo
	}

	public function get_extra_classes( $has_icon_font_size = false ) {
		$classes = ' ' . $this->att( 'sermon_wrap_classes' );
		return $classes;
	}

	public function do_scripts() {
	}
}
