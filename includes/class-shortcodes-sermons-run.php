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
		'sermons_posts_per_page' => 10, // Will use WP's per-page option.
		'do_sermon_excerpt'      => true,
		'do_sermon_thumbnail'    => true,
		'sermon_thumbnail_size'  => 'medium',
		'sermon_wrap_classes'    => '',
	);

	/**
	 * Whether css has been output yet.
	 * @var bool
	 */
	protected static $css_done = false;

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

	public function style_block() {
		// Only output once, not once per shortcode.
		if ( ! self::$css_done ) {
			self::$css_done = true;
			return GCS_Template_Loader::get_template( 'sermon-shortcode-css' );
		}

	}

	public function do_scripts() {
	}
}
