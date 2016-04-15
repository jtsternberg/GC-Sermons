<?php
/**
 * GC Sermons Play Button Shortcode - Run
 *
 * @todo Add overlay/video popup JS, etc
 * @todo Use dashicons as fallback.
 *
 * @version 0.1.2
 * @package GC Sermons
 */

class GCS_PBS_Run extends WDS_Shortcodes {

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
	 * GCS_Sermon_Post object
	 *
	 * @var   GCS_Sermon_Post
	 * @since 0.1.0
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
		$output .= $this->output_css();
		$output .= '<a data-sermonid="'. $sermon->ID .'" class="gc-sermons-play-button fa ' . $extra_class . '"' . $style . ' href="'. $video_url .'">';
		$output .= '</a>';

		if ( $this->att( 'do_scripts' ) ) {
			wp_enqueue_style( 'qode_font_awesome-css' );
			wp_enqueue_style( 'font_awesome' );
			wp_enqueue_style( 'fontawesome' );

			add_action( 'wp_footer', array( $this, 'video_modal' ) );
			wp_enqueue_script( 'fitvids', GC_Sermons_Plugin::$url . 'assets/js/vendor/jquery.fitvids.js', array( 'jquery' ), '1.1', true );
			wp_enqueue_script( 'gc-sermon-videos', GC_Sermons_Plugin::$url . 'assets/js/gc-sermon-videos.js', array( 'fitvids' ), GC_Sermons_Plugin::VERSION, true );
		}

		return $output;
	}

	public function get_sermon() {
		$sermon_id = $this->att( 'sermon_id' );

		if ( ! $sermon_id || 'recent' === $sermon_id || '0' === $sermon_id || 0 === $sermon_id ) {

			$this->shortcode_object->set_att( 'sermon', $this->sermons->most_recent_with_video() );

		} elseif ( is_numeric( $sermon_id ) ) {
			$this->shortcode_object->set_att( 'sermon', new GCS_Sermon_Post( get_post( absint( $sermon_id ) ) ) );
		}

		return $this->att( 'sermon' );
	}

	public function get_extra_classes( $has_icon_font_size = false ) {
		$classes = ' ' . implode( ' ', array_map( 'esc_attr', explode( ' ', $this->att( 'icon_class' ) ) ) );

		if ( ! $has_icon_font_size ) {
			$classes .= ' icon-size-' . esc_attr( $this->att( 'icon_size', 'large' ) );
		}

		return $classes;
	}

	public function video_modal() {
		static $done;

		// Get shortcode instances
		$shortcodes = WDS_Shortcode_Instances::get( $this->shortcode );

		if ( $done || empty( $shortcodes ) ) {
			return;
		}

		$videos = '';
		foreach ( $shortcodes as $shortcode ) {
			// Check for found sermons
			if ( ! ( $sermon = $shortcode->att( 'sermon' ) ) ) {
				continue;
			}

			// Check for video player
			if ( ! ( $player = $sermon->get_video_player() ) ) {
				return;
			}

			// Ok, add the video markup
			$videos .= $this->video_wrap( $sermon->ID, $player );
		}

		if ( $videos ) {
			// Output the video modal wrapper/overlay
			echo '<div id="gc-video-overlay" style="display:none;">'. $videos .'</div>';
		}

		$done = true;
	}

	public function video_wrap( $sermon_id, $player ) {
		return '
		<div id="gc-sermons-video-' . $sermon_id . '" class="gc-sermons-modal gcinvisible">
			<div class="gc-sermons-video-container"></div>
			<script type="text/template" class="tmpl-videoModal">
				' . $player . '
			</script>
		</div>';
	}

	public function output_css() {
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
			#gc-video-overlay {
				display: block;
				width: 100%;
				position: fixed;
				left: 0;
				top: 0;
				height: 100%;
				background: rgba(0,0,0,.62);
				z-index: 9999998;
			}
			.gc-sermons-modal {
				position: absolute;
				display: block;
				top: 50%;
				left: 0;
				width: 90%;
				margin-left: 5%;
			}
			.gcinvisible {
				visibility: hidden;
			}
		</style>
		<?php

		self::$css_done = true;

		return ob_get_clean();
	}

}
