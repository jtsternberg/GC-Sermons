<?php
/**
 * GC Sermons Shortcode Base
 *
 * @version 0.1.3
 * @package GC Sermons
 */

abstract class GCS_Shortcodes_Run_Base extends WDS_Shortcodes {

	/**
	 * GCS_Sermons object
	 *
	 * @var   GCS_Sermons
	 * @since 0.1.0
	 */
	public $sermons;

	/**
	 * Constructor
	 *
	 * @since NEXT
	 *
	 * @param GCS_Sermons $sermons
	 */
	public function __construct( GCS_Sermons $sermons ) {
		$this->sermons = $sermons;
		parent::__construct();
	}

	protected function get_sermon() {
		$sermon_id = $this->att( 'sermon_id' );

		if ( ! $sermon_id || 'recent' === $sermon_id || '0' === $sermon_id || 0 === $sermon_id ) {

			$this->shortcode_object->set_att( 'sermon', $this->most_recent_sermon() );

		} elseif ( 'this' === $sermon_id ) {

			$this->shortcode_object->set_att( 'sermon', gc_get_sermon_post( get_queried_object_id() ) );

		} elseif ( is_numeric( $sermon_id ) ) {

			$this->shortcode_object->set_att( 'sermon', gc_get_sermon_post( $sermon_id ) );

		}

		return $this->att( 'sermon' );
	}

	protected function most_recent_sermon() {
		switch ( $this->att( 'recent', 'recent' ) ) {
			case 'audio':
				return $this->sermons->most_recent_with_audio();

			case 'video':
				return $this->sermons->most_recent_with_video();
		}

		return $this->sermons->most_recent();
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

}
