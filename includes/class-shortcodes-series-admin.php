<?php
/**
 * GC Sermons Series Shortcode - Admin
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Series_Admin extends GCS_Shortcodes_Admin_Base {

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'GC Series', 'gc-sermons' ),
			'button_tooltip' => __( 'GC Series', 'gc-sermons' ),
			'icon'           => 'dashicons-images-alt',
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
			'name'    => __( 'Number of Series to Show Per-Page', 'gc-sermons' ),
			'desc'    => __( 'Select an odd number if choosing to "Highlight Most Recent"', 'gc-sermons' ),
			'type'    => 'text_small',
			'id'      => 'series_per_page',
			'default' => $this->atts_defaults['series_per_page'],
		);

		$fields[] = array(
			'name'    => __( 'Highlight Most Recent Series?', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => 'highlight_first',
			'default' => $this->atts_defaults['highlight_first'],
		);

		$fields[] = array(
			'name'    => __( 'Add Year Date Separators', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => 'date_separators',
			'default' => $this->atts_defaults['date_separators'],
		);

		$fields[] = array(
			'name'    => __( 'Show Thumbnail?', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => 'do_series_thumbnail',
			'default' => $this->atts_defaults['do_series_thumbnail'],
		);

		$fields[] = array(
			'name'    => __( 'Thumbnail Size (if checking "Show Thumbnail")', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => 'series_thumbnail_size',
			'default' => $this->atts_defaults['series_thumbnail_size'],
		);

		$fields[] = array(
			'name'    => __( 'Extra Wrap CSS Classes', 'gc-sermons' ),
			'desc'    => __( 'Enter classes separated by spaces (e.g. "class1 class2")', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => 'series_wrap_classes',
			'default' => $this->atts_defaults['series_wrap_classes'],
		);

		return $fields;
	}
}
