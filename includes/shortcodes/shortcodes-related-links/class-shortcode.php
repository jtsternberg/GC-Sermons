<?php
/**
 * GC Sermons Related Links Shortcode
 * @version 0.1.3
 * @package GC Sermons
 */

class GCS_Shortcodes_Related_Links extends GCS_Shortcodes_Base {
	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->run   = new GCSS_Related_Links_Run( $plugin->sermons );
		$this->admin = new GCSS_Related_Links_Admin( $this->run );

		parent::hooks();
	}

}

/**
 * GC Sermons Related Links Shortcode
 *
 * @version 0.1.3
 * @package GC Sermons
 */
class GCSS_Related_Links_Run extends GCS_Shortcodes_Run_Base {

	/**
	 * The Shortcode Tag
	 * @var string
	 * @since 0.1.0
	 */
	public $shortcode = 'gc_related_links';

	/**
	 * Default attributes applied to the shortcode.
	 * @var array
	 * @since 0.1.0
	 */
	public $atts_defaults = array(
		'sermon_id' => 0, // 'Blank, "recent", or "0" will play the most recent video.
		'recent'    => 'recent', // Options: 'recent', 'audio', 'video'
	);

	/**
	 * Shortcode Output
	 */
	public function shortcode() {
		return gc_get_sermon_related_links( $this->get_sermon() );
	}

}

/**
 * GC Sermons Related Links Shortcode - Admin
 * @version 0.1.3
 * @package GC Sermons
 */
class GCSS_Related_Links_Admin extends GCSS_Recent_Admin_Base {

	/**
	 * Shortcode prefix for field ids.
	 *
	 * @var   string
	 * @since NEXT
	 */
	protected $prefix = 're_links_';

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'GC Sermon Links', 'gc-sermons' ),
			'button_tooltip' => __( 'GC Sermon Related Links', 'gc-sermons' ),
			'icon'           => 'dashicons-admin-links',
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
			'desc'            => __( 'Blank, "recent", or "0" will get the most recent sermon\'s speaker info. Otherwise enter a post ID. Click the magnifying glass to search for a Sermon post.', 'gc-sermons' ),
			'id'              => $this->prefix . 'sermon_id',
			'type'            => 'post_search_text',
			'post_type'       => $this->run->sermons->post_type(),
			'select_type'     => 'radio',
			'select_behavior' => 'replace',
			'row_classes'     => 'check-if-recent',
		);

		$fields[] = array(
			'name'        => __( 'Filter Most Recent Sermon By:', 'gc-sermons' ),
			'desc'        => __( 'If setting "Sermon ID" above to blank, "recent", or "0", this setting determines which type of most recent sermon to get the related links info for.', 'gc-sermons' ),
			'type'        => 'select',
			'id'          => $this->prefix . 'recent',
			'default'     => $this->atts_defaults['recent'],
			'row_classes' => 'hide-if-not-recent',
			'options'     => array(
				'recent' => __( 'Most Recent', 'gc-sermons' ),
				'audio' => __( 'Most Recent with Audio', 'gc-sermons' ),
				'video' => __( 'Most Recent with Video', 'gc-sermons' ),
			),
		);

		return $fields;
	}
}
