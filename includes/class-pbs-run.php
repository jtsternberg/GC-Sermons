<?php
/**
 * GC Sermons Play Button Shortcode - Run
 *
 * @todo Add overlay/video popup JS, etc
 * @todo Use dashicons as fallback.
 *
 * @version 0.1.0
 * @package GC Sermons
 */

class GCS_PBS_Run extends WDS_Shortcodes {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since NEXT
	 */
	public $shortcode = 'sermon_play_button';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since NEXT
	 */
	public $atts_defaults = array(
		'sermon_id'  => 0,
		'icon_class' => 'fa-youtube-play',
		'icon_color' => '#000000',
		'icon_size'  => 'large',
	);

	/**
	 * GCS_Sermon_Post object
	 *
	 * @var   GCS_Sermon_Post
	 * @since NEXT
	 */
	public $sermons;

	/**
	 * Whether css has been output yet.
	 * @var bool
	 */
	protected static $css_done = false;

	/**
	 * Shortcode Output
	 */
	public function shortcode() {

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

		// Get our extra_class attribute
		$extra_class = $this->get_extra_classes( $has_icon_font_size );

		$sermon = $this->get_sermon();

		if ( ! $sermon || ! isset( $sermon->ID ) ) {
			return '<!-- no sermons found -->';
		}

		$video_url = get_post_meta( $sermon->ID, 'gc_sermon_video_url', 1 );

		$output = '';
		$output .= $this->css();
		$output .= '<a class="gc-sermons-play-button fa ' . $extra_class . '"' . $style . ' href="'. $video_url .'">';
		$output .= '</a>';

		wp_enqueue_style( 'qode_font_awesome-css' );
		wp_enqueue_style( 'font_awesome' );
		wp_enqueue_style( 'fontawesome' );
		return $output;
	}

	public function get_extra_classes( $has_icon_font_size = false ) {
		$classes = ' ' . implode( ' ', array_map( 'esc_attr', explode( ' ', $this->att( 'icon_class' ) ) ) );

		if ( ! $has_icon_font_size ) {
			$classes .= ' icon-size-' . esc_attr( $this->att( 'icon_size', 'large' ) );
		}

		return $classes;
	}

	public function get_sermon() {
		$sermon = false;
		$sermon_id = $this->att( 'sermon_id' );

		if ( ! $sermon_id || 'recent' === $sermon_id || '0' === $sermon_id || 0 === $sermon_id ) {

			$sermon = $this->sermons->most_recent_with_video();

		} elseif ( is_numeric( $sermon_id ) ) {
			$sermon = get_post( absint( $sermon_id ) );
		}

		return $sermon;
	}

	public function css() {
		// Only output once, not once per shortcode.
		if ( self::$css_done ) {
			return '';
		}

		ob_start();
		?>
		<style type="text/css" media="screen">
		.gc-sermons-play-button {
			padding: 1em;
			/*background: red;*/
		}
		.gc-sermons-play-button.icon-size-large {
			font-size: 2em;
		}
		.gc-sermons-play-button.icon-size-medium {
			font-size: 1em;
		}
		.gc-sermons-play-button.icon-size-small {
			font-size: .5em;
		}
		/*.becool-shortcode:before {
			margin-right: .78em;
			font-size: 2em;
			margin-top: -.05em;
			margin-left: -.05em;
		}*/
		</style>
		<?php

		self::$css_done = true;

		return ob_get_clean();
	}

}
