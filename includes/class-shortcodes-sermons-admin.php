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
			'id'      => 'sermons_posts_per_page',
			'default' => get_option( 'posts_per_page', $this->atts_defaults['sermons_posts_per_page'] ),
		);

		$fields[] = array(
			'name'    => __( 'Show Excerpts?', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => 'do_sermon_excerpt',
			'default' => $this->atts_defaults['do_sermon_excerpt'],
		);

		$fields[] = array(
			'name'    => __( 'Show Thumbnail?', 'gc-sermons' ),
			'type'    => 'checkbox',
			'id'      => 'do_sermon_thumbnail',
			'default' => $this->atts_defaults['do_sermon_thumbnail'],
		);

		$fields[] = array(
			'name'    => __( 'Thumbnail Size (if checking "Show Thumbnail")', 'gc-sermons' ),
			'type'    => 'text',
			'id'      => 'sermon_thumbnail_size',
			'default' => $this->atts_defaults['sermon_thumbnail_size'],
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
