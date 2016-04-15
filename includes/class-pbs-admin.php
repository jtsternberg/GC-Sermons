<?php
/**
 * GC Sermons Play Button Shortcode - Admin
 * @version 0.1.2
 * @package GC Sermons
 */

class GCS_PBS_Admin extends WDS_Shortcode_Admin {
	/**
	 * Parent plugin class
	 *
	 * @var   GCS_PBS_Run
	 * @since 0.1.0
	 */
	protected $run;

	/**
	 * Constructor
	 *
	 * @since  0.1.0
	 * @param  object $run Main plugin object.
	 * @return void
	 */
	public function __construct( GCS_PBS_Run $run ) {
		$this->run = $run;

		parent::__construct(
			$this->run->shortcode,
			GC_Sermons_Plugin::VERSION,
			$this->run->atts_defaults
		);
	}

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'GC Sermon Play', 'gc-sermons' ),
			'button_tooltip' => __( 'GC Sermon Play Button', 'gc-sermons' ),
			'icon'           => 'dashicons-controls-play',
			'mceView'        => true, // The future
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
			'name'            => __( 'Sermon to play', 'gc-sermons' ),
			'desc'            => __( 'Blank, "recent", or "0" will play the most recent video. Otherwise enter a post ID. click the magnifying glass to search for a Sermon post.', 'gc-sermons' ),
			'id'              => 'sermon_id',
			'type'            => 'post_search_text',
			'post_type'       => $this->run->sermons->post_type(),
			'select_type'     => 'radio',
			'select_behavior' => 'replace',
		);

		$fields[] = array(
			'name'    => __( 'Icon Color', 'gc-sermons' ),
			'type'    => 'colorpicker',
			'id'      => 'icon_color',
			'default' => $this->atts_defaults['icon_color'],
		);

		$fields[] = array(
			'name'    => __( 'Icon Size', 'gc-sermons' ),
			'desc'    => __( 'Select a font-size (in <code>em</code>s, <strong>or</strong> enter either "medium", "large", or "small".', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => 'icon_size',
			'default' => $this->atts_defaults['icon_size'],
		);

		$fields[] = array(
			'name'    => __( 'Extra CSS Classes', 'cool-shortcode' ),
			'desc'    => __( 'Enter classes separated by spaces (e.g. "class1 class2")', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => 'icon_class',
			'default' => $this->atts_defaults['icon_class'],
		);

		return $fields;
	}
}
