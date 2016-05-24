<?php
/**
 * GC Sermons Series Shortcode - Run
 *
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Series_Run extends GCS_Shortcodes_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_series';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'series_per_page'       => 7, // Will use WP's per-page option.
		'highlight_first'       => true,
		'date_separators'       => true,
		'do_series_thumbnail'   => true,
		'series_thumbnail_size' => 'medium',
		'series_wrap_classes'   => '',
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
		$classes = ' ' . $this->att( 'series_wrap_classes' );
		return $classes;
	}

	public function style_block() {
		// Only output once, not once per shortcode.
		if ( ! self::$css_done ) {
			self::$css_done = true;
			return GCS_Template_Loader::get_template( 'series-shortcode-css' );
		}

	}

	public function do_scripts() {
	}
}
