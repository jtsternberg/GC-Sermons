<?php
/**
 * GC Sermons Search Shortcode - Admin
 * @version 0.1.5
 * @package GC Sermons
 */
class GCS_Shortcodes_Sermon_Search_Admin extends GCSS_Recent_Admin_Base {

	/**
	 * Shortcode prefix for field ids.
	 *
	 * @var   string
	 * @since 0.1.3
	 */
	protected $prefix = 'gc_search_';

	/**
	 * GCS_Taxonomies
	 *
	 * @var GCS_Taxonomies
	 */
	protected $taxonomies;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $run        Main plugin object.
	 * @param  object $taxonomies GCS_Taxonomies object.
	 * @return void
	 */
	public function __construct( GCS_Shortcodes_Run_Base $run, GCS_Taxonomies $taxonomies ) {
		$this->taxonomies = $taxonomies;
		parent::__construct( $run );
	}

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'GC Sermons Search', 'gc-sermons' ),
			'button_tooltip' => __( 'GC Sermons Search', 'gc-sermons' ),
			'icon'           => 'dashicons-search',
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
			'name'    => __( 'Search:', 'gc-sermons' ),
			'desc'    => sprintf( __( 'Select whether form allows searching %s, %s, or both.', 'gc-sermons' ), $this->run->sermons->post_type( 'plural' ), $this->taxonomies->series->taxonomy( 'plural' ) ),
			'id'      => $this->prefix . 'search',
			'type'    => 'select',
			'default' => $this->atts_defaults['search'],
			'options' => array(
				'sermons' => $this->run->sermons->post_type( 'plural' ),
				'series' => $this->taxonomies->series->taxonomy( 'plural' ),
				'' => __( 'Both', 'gc-sermons' ),
			),
		);

		$fields[] = array(
			'name'    => __( 'Number of results to show per-page', 'gc-sermons' ),
			'type'    => 'text_small',
			'id'      => $this->prefix . 'per_page',
			'default' => get_option( 'posts_per_page', $this->atts_defaults['per_page'] ),
		);

		$fields[] = array(
			'name'    => __( 'Content', 'gc-sermons' ),
			'type'    => 'radio',
			'id'      => $this->prefix . 'content',
			'default' => $this->atts_defaults['content'],
			'options' => array(
				''        => __( 'None', 'gc-sermons' ),
				'content' => __( 'Sermon Post Content', 'gc-sermons' ),
				'excerpt' => __( 'Sermon Post Excerpt', 'gc-sermons' ),
			),
		);

		$fields[] = array(
			'name'    => __( 'Remove Thumbnails', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => $this->prefix . 'remove_thumbnail',
			'default' => false,
		);

		$fields[] = array(
			'name'    => __( 'Thumbnail Size (if included)', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => $this->prefix . 'thumbnail_size',
			'default' => $this->atts_defaults['thumbnail_size'],
		);

		$fields[] = array(
			'name'    => __( 'Max number of columns', 'gc-sermons' ),
			'desc'    => __( 'Will vary on device screen width', 'gc-sermons' ),
			'type'    => 'radio_inline',
			'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4 ),
			'id'      => $this->prefix . 'number_columns',
			'default' => $this->atts_defaults['number_columns'],
		);

		return $fields;
	}
}
