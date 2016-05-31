<?php
/**
 * GC Sermons Play Button Shortcode - Admin
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Play_Button_Admin extends GCS_Shortcodes_Admin_Base {

	/**
	 * Shortcode prefix for field ids.
	 *
	 * @var   string
	 * @since 0.1.3
	 */
	protected $prefix = 'pl_btn_';

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
			'desc'            => __( 'Blank, "recent", or "0" will play the most recent video. Otherwise enter a post ID. Click the magnifying glass to search for a Sermon post.', 'gc-sermons' ),
			'id'              => $this->prefix . 'sermon_id',
			'type'            => 'post_search_text',
			'post_type'       => $this->run->sermons->post_type(),
			'select_type'     => 'radio',
			'select_behavior' => 'replace',
		);

		$fields[] = array(
			'name'    => __( 'Icon Color', 'gc-sermons' ),
			'type'    => 'colorpicker',
			'id'      => $this->prefix . 'icon_color',
			'default' => $this->atts_defaults['icon_color'],
		);

		$fields[] = array(
			'name'    => __( 'Icon Size', 'gc-sermons' ),
			'desc'    => __( 'Select a font-size (in <code>em</code>s, <strong>or</strong> enter either "medium", "large", or "small".', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => $this->prefix . 'icon_size',
			'default' => $this->atts_defaults['icon_size'],
		);

		$fields[] = array(
			'name'    => __( 'Extra CSS Classes', 'gc-sermons' ),
			'desc'    => __( 'Enter classes separated by spaces (e.g. "class1 class2")', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => $this->prefix . 'icon_class',
			'default' => $this->atts_defaults['icon_class'],
		);

		return $fields;
	}
}
