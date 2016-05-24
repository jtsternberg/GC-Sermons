<?php
/**
 * GC Sermons Play Button Shortcode - Run
 *
 * @todo Add overlay/video popup JS, etc
 * @todo Use dashicons as fallback.
 *
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Play_Button_Run extends GCS_Shortcodes_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'sermon_play_button';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermon_id'  => 0,
		'icon_class' => 'fa-youtube-play',
		'icon_color' => '#000000',
		'icon_size'  => 'large',
		'do_scripts' => true,
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

		$sermon = $this->get_sermon();

		if ( ! $sermon || ! isset( $sermon->ID ) ) {
			return apply_filters( 'gcs_sermon_play_button_shortcode_output', GCS_Template_Loader::get_template( 'play-button-shortcode-not-found' ), $this );
		}

		if ( $this->att( 'do_scripts' ) ) {
			$this->do_scripts();
		}

		$output = '';

		// Only output once, not once per shortcode.
		if ( ! self::$css_done ) {
			self::$css_done = true;
			$output .= GCS_Template_Loader::get_template( 'play-button-shortcode-css' );
		}

		list( $style, $has_icon_font_size ) = $this->get_inline_styles();

		$output .= apply_filters( 'gcs_sermon_play_button_shortcode_output', GCS_Template_Loader::get_template(
			'play-button-shortcode',
			array(
				// Get our extra_class attribute
				'extra_classes' => $this->get_extra_classes( $has_icon_font_size ),
				'sermond_id'    => $sermon->ID,
				'style'         => $style,
				'video_url'     => get_post_meta( $sermon->ID, 'gc_sermon_video_url', 1 ),
			)
		), $this );

		return $output;
	}

	protected function most_recent_sermon() {
		return $this->sermons->most_recent_with_video();
	}

	public function get_inline_styles() {
		$style = '';
		$has_icon_font_size = false;

		if ( $this->att( 'icon_color' ) || $this->att( 'icon_size' ) ) {
			$style = ' style="';
			// Get/check our text_color attribute
			if ( $this->att( 'icon_color' ) ) {
				$text_color = sanitize_text_field( $this->att( 'icon_color' ) );
				$style .= 'color: ' . $text_color .';';
			}
			if ( is_numeric( $this->att( 'icon_size' ) ) ) {
				$has_icon_font_size = absint( $this->att( 'icon_size' ) );
				$style .= 'font-size: ' . $has_icon_font_size .'em;';
			}
			$style .= '"';
		}

		return array( $style, $has_icon_font_size );
	}

	public function get_extra_classes( $has_icon_font_size = false ) {
		$classes = ' ' . implode( ' ', array_map( 'esc_attr', explode( ' ', $this->att( 'icon_class' ) ) ) );

		if ( ! $has_icon_font_size ) {
			$classes .= ' icon-size-' . esc_attr( $this->att( 'icon_size', 'large' ) );
		}

		return $classes;
	}

	public function style_block() {
		// Only output once, not once per shortcode.
		if ( ! self::$css_done ) {
			self::$css_done = true;
			return GCS_Template_Loader::get_template( 'play-button-shortcode-css' );
		}

	}

	public function do_scripts() {

		// Enqueue whatever version of fontawesome that's registereed (if it is registered)
		wp_enqueue_style( 'qode_font_awesome-css' );
		wp_enqueue_style( 'font_awesome' );
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'fontawesome' );

		add_action( 'wp_footer', array( $this, 'video_modal' ) );

		wp_enqueue_script(
			'fitvids',
			GC_Sermons_Plugin::$url . 'assets/js/vendor/jquery.fitvids.js',
			array( 'jquery' ),
			'1.1',
			true
		);

		wp_enqueue_script(
			'gc-sermon-videos',
			GC_Sermons_Plugin::$url . 'assets/js/gc-sermon-videos.js',
			array( 'fitvids' ),
			GC_Sermons_Plugin::VERSION,
			true
		);
	}

	public function video_modal() {
		static $done;

		// Get shortcode instances
		$shortcodes = WDS_Shortcode_Instances::get( $this->shortcode );

		if ( $done || empty( $shortcodes ) ) {
			return;
		}

		$videos = array();
		foreach ( $shortcodes as $shortcode ) {
			// Check for found sermons
			if ( ! ( $sermon = $shortcode->att( 'sermon' ) ) ) {
				continue;
			}

			// Check for video player
			if ( ! ( $player = $sermon->get_video_player() ) ) {
				return;
			}

			// Ok, add the video player
			$videos[ $sermon->ID ] = $player;
		}

		if ( ! empty( $videos ) ) {
			echo new GCS_Template_Loader( 'play-button-shortcode-modal-videos', array(
				'videos' => $videos,
			) );
		}

		$done = true;
	}

}
