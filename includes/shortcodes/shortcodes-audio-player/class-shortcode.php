<?php
/**
 * GC Sermons Audio Player Shortcode
 *
 * @package GC Sermons
 */
class GCS_Shortcodes_Audio_Player extends GCS_Shortcodes_Base {

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run   = new GCS_Shortcodes_Audio_Player_Run( $plugin->sermons );
		$this->admin = new GCS_Shortcodes_Audio_Player_Admin( $this->run );

		parent::hooks();
	}

}

/**
 * GC Sermons Audio Player Shortcode
 *
 * @version 0.1.3
 * @package GC Sermons
 */
class GCS_Shortcodes_Audio_Player_Run extends GCS_Shortcodes_Run_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_audio_player';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermon_id' => 0, // 'Blank, "recent", or "0" will play the most recent audio.
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		return gc_get_sermon_audio_player( $this->get_sermon() );
	}

}


/**
 * GC Sermons Audio Player Shortcode - Admin
 * @version 0.1.3
 * @package GC Sermons
 */
class GCS_Shortcodes_Audio_Player_Admin extends GCSS_Recent_Admin_Base {

	/**
	 * Shortcode prefix for field ids.
	 *
	 * @var   string
	 * @since 0.1.3
	 */
	protected $prefix = 'gc_audplayer_';

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'GC Sermon Audio Player', 'gc-sermons' ),
			'button_tooltip' => __( 'GC Sermon Audio Player', 'gc-sermons' ),
			'icon'           => 'dashicons-format-audio',
			// 'mceView'        => true, // The future
		);
	}

	/**
	 * Adds fields to the button modal using CMB2
	 *
	 * @param $fields
	 * @param $button_data
	 *
	 * @return array
	 */
	function fields( $fields, $button_data ) {

		$fields[] = array(
			'name'            => __( 'Sermon ID', 'gc-sermons' ),
			'desc'            => __( 'Blank, "recent", or "0" will get the most recent sermon\'s audio player. Otherwise enter a post ID. Click the magnifying glass to search for a Sermon post.', 'gc-sermons' ),
			'id'              => $this->prefix . 'sermon_id',
			'type'            => 'post_search_text',
			'post_type'       => $this->run->sermons->post_type(),
			'select_type'     => 'radio',
			'select_behavior' => 'replace',
			'row_classes'     => 'check-if-recent',
		);

		return $fields;
	}
}
