<?php
/**
 * GC Sermons Shortcode - Admin
 * @version 0.1.3
 * @package GC Sermons
 */

class GCSS_Sermons_Admin extends GCS_Shortcodes_Admin_Base {

	/**
	 * Sets up the button
	 *
	 * @return array
	 */
	function js_button_data() {
		return array(
			'qt_button_text' => __( 'GC Sermons', 'gc-sermons' ),
			'button_tooltip' => __( 'GC Sermons', 'gc-sermons' ),
			'icon'           => $this->run->sermons->arg_overrides['menu_icon'],
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
			'name'    => __( 'Number of sermons to show per-page', 'gc-sermons' ),
			'type'    => 'text_small',
			'id'      => 'sermon_per_page',
			'default' => get_option( 'posts_per_page', $this->atts_defaults['sermon_per_page'] ),
		);

		$fields[] = array(
			'name'    => __( 'Remove Pagination', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => 'sermon_remove_pagination',
			'default' => false,
		);

		$fields[] = array(
			'name'    => __( 'Content', 'gc-sermons' ),
			'type'    => 'radio',
			'id'      => 'sermon_content',
			'default' => $this->atts_defaults['sermon_content'],
			'options' => array(
				''        => __( 'None', 'gc-sermons' ),
				'content' => __( 'Sermon Post Content', 'gc-sermons' ),
				'excerpt' => __( 'Sermon Post Excerpt', 'gc-sermons' ),
			),
		);

		$fields[] = array(
			'name'    => __( 'Remove Thumbnails', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => 'sermon_remove_thumbnail',
			'default' => false,
		);

		$fields[] = array(
			'name'    => __( 'Thumbnail Size (if included)', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => 'sermon_thumbnail_size',
			'default' => $this->atts_defaults['sermon_thumbnail_size'],
		);

		$fields[] = array(
			'name'    => __( 'Max number of columns', 'gc-sermons' ),
			'desc'    => __( 'Will vary on device screen width', 'gc-sermons' ),
			'type'    => 'radio_inline',
			'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4 ),
			'id'      => 'sermon_number_columns',
			'default' => $this->atts_defaults['sermon_number_columns'],
		);

		$fields[] = array(
			'name'            => __( 'Offset', 'gc-sermons' ),
			'desc'            => __( 'Changes which sermon starts the list', 'gc-sermons' ),
			'type'            => 'text_small',
			'id'              => 'sermon_list_offset',
			'sanitization_cb' => 'absint',
			'default'         => $this->atts_defaults['sermon_list_offset'],
		);

		$fields[] = array(
			'name'    => __( 'Extra Wrap CSS Classes', 'gc-sermons' ),
			'desc'    => __( 'Enter classes separated by spaces (e.g. "class1 class2")', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => 'sermon_wrap_classes',
			'default' => $this->atts_defaults['sermon_wrap_classes'],
		);

		return $fields;
	}
}
